<?php
error_reporting(E_ALL & ~E_WARNING);

// Check for oversized POST before doing anything else
if ($_SERVER['REQUEST_METHOD'] === 'POST' && 
    empty($_POST) && 
    empty($_FILES) && 
    $_SERVER['CONTENT_LENGTH'] > 0) {
    
    $postMaxSize = ini_get('post_max_size');
    $contentLength = $_SERVER['CONTENT_LENGTH'];
    
    // Convert post_max_size to bytes for comparison
    function parseSize($size) {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size);
        $size = preg_replace('/[^0-9\.]/', '', $size);
        if ($unit) {
            return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
        }
        return round($size);
    }
    
    function formatFileSize($bytes) {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, 2) . ' ' . $units[$pow];
    }
    
    $error = 'File size (' . formatFileSize($contentLength) . ') exceeds the maximum allowed size (' . $postMaxSize . '). Please upload a smaller file.';
}

// Include necessary files
require_once BASE_PATH . '/includes/minioService.php';
require_once BASE_PATH . '/includes/mediaCard.php';

class MediaUploadHandler {
    private $minioService;
    private $config;
    private $db;

    public function __construct() {
        $this->minioService = MinioService::getInstance();
        
        // Fix config loading
        $configPath = BASE_PATH . '/config/minio.php';
        if (!file_exists($configPath)) {
            throw new Exception('MinIO configuration file not found');
        }
        
        $this->config = require $configPath;
        if (!is_array($this->config)) {
            throw new Exception('Invalid MinIO configuration');
        }
        
        $this->db = Database::getInstance()->getConnection();
    }

    public function handleUpload($file) {
        $transactionStarted = false;
        $fileName = null;
        
        try {
            // Check for PHP upload errors first
            $this->checkPhpUploadLimits();
            
            $this->validateFile($file);
            
            // Generate secure filename
            $fileName = $this->generateSecureFilename($file['name']);
            
            // Upload to MinIO first
            $fileUrl = $this->minioService->uploadFile($file['tmp_name'], $fileName, $file['type']);
            
            if (!$fileUrl) {
                throw new Exception('Failed to upload file to storage service');
            }
            
            // Start transaction only after successful MinIO upload
            $this->db->beginTransaction();
            $transactionStarted = true;
            
            $stmt = $this->db->prepare("
                INSERT INTO media_files (filename, original_filename, file_type, file_size, url, upload_date) 
                VALUES (?, ?, ?, ?, ?, NOW())
            ");
            
            $result = $stmt->execute([
                $fileName,
                $file['name'],
                $file['type'],
                $file['size'],
                $fileUrl
            ]);
            
            if (!$result) {
                throw new Exception('Failed to save file information to database');
            }
            
            $this->db->commit();
            
            return [
                'success' => true,
                'message' => 'File uploaded successfully!',
                'file_id' => $this->db->lastInsertId(),
                'url' => $fileUrl
            ];
            
        } catch (Exception $e) {
            // Only rollback if transaction was started
            if ($transactionStarted) {
                try {
                    $this->db->rollBack();
                } catch (PDOException $rollbackError) {
                    error_log("Rollback failed: " . $rollbackError->getMessage());
                }
            }
            
            // Clean up MinIO if file was uploaded but DB failed
            if ($fileName && $this->minioService) {
                try {
                    $this->minioService->deleteFile($fileName);
                } catch (Exception $deleteError) {
                    error_log("Failed to delete file from MinIO during cleanup: " . $deleteError->getMessage());
                }
            }
            
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    private function checkPhpUploadLimits() {
        // Check if POST data was truncated due to size limits
        if (empty($_POST) && empty($_FILES) && $_SERVER['CONTENT_LENGTH'] > 0) {
            $postMaxSize = $this->parseSize(ini_get('post_max_size'));
            $contentLength = (int)$_SERVER['CONTENT_LENGTH'];
            
            throw new Exception(
                'File size (' . $this->formatFileSize($contentLength) . ') exceeds PHP post_max_size limit (' . 
                $this->formatFileSize($postMaxSize) . '). Please upload a smaller file.'
            );
        }
        
        // Check if file upload failed due to size
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_INI_SIZE) {
            $uploadMaxSize = $this->parseSize(ini_get('upload_max_filesize'));
            throw new Exception(
                'File size exceeds PHP upload_max_filesize limit (' . 
                $this->formatFileSize($uploadMaxSize) . '). Please upload a smaller file.'
            );
        }
        
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_FORM_SIZE) {
            throw new Exception(
                'File size exceeds the maximum allowed size. Please upload a smaller file.'
            );
        }
    }

    private function parseSize($size) {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size);
        $size = preg_replace('/[^0-9\.]/', '', $size);
        if ($unit) {
            return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
        }
        return round($size);
    }

    private function validateFile($file) {
        if (!is_array($file)) {
            throw new Exception('Invalid file data');
        }
        
        if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
            $errorMessage = isset($file['error']) ? $this->getUploadErrorMessage($file['error']) : 'Unknown upload error';
            throw new Exception('File upload error: ' . $errorMessage);
        }

        if (!isset($file['size']) || $file['size'] <= 0) {
            throw new Exception('Invalid file size');
        }

        if (!isset($file['type']) || empty($file['type'])) {
            throw new Exception('Invalid file type');
        }

        if (!isset($file['tmp_name']) || !file_exists($file['tmp_name'])) {
            throw new Exception('Uploaded file not found');
        }

        // Check file size limit
        $maxSize = isset($this->config['max_file_size']) ? $this->config['max_file_size'] : (100 * 1024 * 1024); // 100MB default
        if ($file['size'] > $maxSize) {
            throw new Exception('File size exceeds maximum allowed size of ' . $this->formatFileSize($maxSize));
        }

        // Check allowed file types
        $allowedTypes = isset($this->config['allowed_types']) ? $this->config['allowed_types'] : [
            'image/jpeg', 'image/png', 'image/gif', 'image/webp',
            'video/mp4', 'video/quicktime', 'video/webm'
        ];
        
        if (!in_array($file['type'], $allowedTypes)) {
            throw new Exception('File type not allowed. Allowed types: ' . implode(', ', $allowedTypes));
        }

        // Additional security: check actual file content
        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $actualMimeType = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);

            if ($actualMimeType && $actualMimeType !== $file['type']) {
                throw new Exception('File type mismatch detected. Expected: ' . $file['type'] . ', Got: ' . $actualMimeType);
            }
        }
    }

    private function generateSecureFilename($originalName) {
        if (empty($originalName)) {
            throw new Exception('Original filename is required');
        }
        
        $extension = pathinfo($originalName, PATHINFO_EXTENSION);
        if (empty($extension)) {
            throw new Exception('File must have an extension');
        }
        
        return bin2hex(random_bytes(16)) . '.' . strtolower($extension);
    }

    private function getUploadErrorMessage($errorCode) {
        $errors = [
            UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize',
            UPLOAD_ERR_FORM_SIZE => 'File exceeds MAX_FILE_SIZE',
            UPLOAD_ERR_PARTIAL => 'File was only partially uploaded',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
            UPLOAD_ERR_EXTENSION => 'File upload stopped by extension'
        ];
        
        return $errors[$errorCode] ?? 'Unknown upload error (code: ' . $errorCode . ')';
    }
    
    private function formatFileSize($bytes) {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, 2) . ' ' . $units[$pow];
    }
}

$message = '';
if (!isset($error)) {
    $error = '';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$error) {
    try {
        $uploadHandler = new MediaUploadHandler();
        
        // Check if file was uploaded
        if (!isset($_FILES['file']) || empty($_FILES['file']['name'])) {
            throw new Exception('No file was selected for upload');
        }
        
        $result = $uploadHandler->handleUpload($_FILES['file']);
        
        if ($result['success']) {
            $message = $result['message'];
        } else {
            $error = $result['message'];
        }
    } catch (Exception $e) {
        $error = 'Upload failed: ' . $e->getMessage();
        error_log("Upload error: " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Upload and manage media files in this PHP Docker Web Application">
    <link href="/output.css" rel="stylesheet">
    <title>Upload Media - My PHP Docker Web App</title>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900 text-gray-900 dark:text-gray-100 min-h-screen transition-colors duration-200">
    <div class="flex min-h-screen">
        <?php include BASE_PATH . '/includes/navigation.php'; ?>

        <div class="flex-1 p-6 sm:ml-64">
            <header class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-8 mb-8 border border-gray-200 dark:border-gray-700">
                <div class="text-4xl font-extrabold">
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-purple-600">
                        Upload Media Files
                    </span>
                </div>
                <p class="mt-4 text-gray-600 dark:text-gray-300 text-xl">Upload and manage your images and videos</p>
            </header>

            <section class="bg-white dark:bg-gray-800 rounded-xl p-8 mb-8 shadow-lg hover:shadow-xl transition-shadow duration-300 border border-gray-200 dark:border-gray-700">
                <?php if (!empty($message)): ?>
                    <div class="p-4 mb-4 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-100 rounded-lg">
                        <?php echo htmlspecialchars($message); ?>
                    </div>
                <?php endif; ?>
                
                <?php if (!empty($error)): ?>
                    <div class="p-4 mb-4 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-100 rounded-lg">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" enctype="multipart/form-data" class="space-y-6">
                    <div class="border-b pb-4 border-gray-200 dark:border-gray-700">
                        <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo 100 * 1024 * 1024; ?>" />
                        <h2 class="text-3xl font-bold text-gray-800 dark:text-gray-200 mb-4">Upload New Media</h2>
                        <label for="file" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Select File</label>
                        <input type="file" name="file" id="file" class="mt-1 block w-full p-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Supported formats: JPG, PNG, GIF, MP4, MOV, WEBM (Max: 100MB)</p>
                    </div>
                    
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md transition-colors duration-200 transform hover:scale-105">
                        <i class="fas fa-upload mr-2"></i> 
                        Upload File
                    </button>
                </form>
            </section>

            <section class="bg-white dark:bg-gray-800 rounded-xl p-8 mb-8 shadow-lg hover:shadow-xl transition-shadow duration-300 border border-gray-200 dark:border-gray-700">
                <div class="flex justify-between items-center mb-6 border-b pb-4 border-gray-200 dark:border-gray-700">
                    <h2 class="text-3xl font-bold">Recent Files</h2>
                    <a href="/media/gallery" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md transition-colors duration-200 flex items-center">
                        <i class="fas fa-images mr-2"></i> 
                        View All
                    </a>
                </div>
    
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php
                    try {
                        $db = Database::getInstance()->getConnection();
                        $stmt = $db->prepare("SELECT * FROM media_files ORDER BY upload_date DESC LIMIT 3");
                        $stmt->execute();
                        $files = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        foreach($files as $file) {
                            renderMediaCard($file, false, 'small');
                        }
                        
                        if (count($files) === 0) {
                            echo '<div class="col-span-full text-center p-12">';
                            echo '<p class="text-gray-500 dark:text-gray-400 text-xl">No files uploaded yet.</p>';
                            echo '</div>';
                        }
                    } catch(PDOException $e) {
                        echo '<p class="col-span-full p-4 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-100 rounded-lg">Error loading files: ' . htmlspecialchars($e->getMessage()) . '</p>';
                    }
                    ?>
                </div>
            </section>
        </div>
    </div>
    <script src="<?= $baseUrl ?>public/js/darkMode.js" defer></script>
    <script src="<?= $baseUrl ?>public/js/mobileNav.js" defer></script>
    <script src="<?= $baseUrl ?>public/js/all.min.js"></script>
    <script src="<?= $baseUrl ?>public/js/accordion.js" defer></script>
    <script src="<?= $baseUrl ?>public/js/mediaGallery.js" defer></script>
    </body>
</html>
