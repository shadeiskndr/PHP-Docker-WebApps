<?php
// Include necessary files
require_once BASE_PATH . '/includes/minioService.php';

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    // Handle file upload
    $file = $_FILES['file'];
    
    if ($file['error'] === 0) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'video/mp4', 'video/quicktime'];
        
        if (in_array($file['type'], $allowedTypes)) {
            // Generate a unique file name
            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $fileName = uniqid() . '.' . $extension;
            
            // Get file data
            $fileData = file_get_contents($file['tmp_name']);
            
            // Upload to MinIO
            $minioService = MinioService::getInstance();
            $fileUrl = $minioService->uploadFile($fileData, $fileName, $file['type']);
            
            if ($fileUrl) {
                // Store file metadata in database
                try {
                    $db = Database::getInstance()->getConnection();
                    $stmt = $db->prepare("INSERT INTO media_files (filename, original_filename, file_type, file_size, url) VALUES (?, ?, ?, ?, ?)");
                    $stmt->execute([
                        $fileName,
                        $file['name'],
                        $file['type'],
                        $file['size'],
                        $fileUrl
                    ]);
                    
                    $message = "File uploaded successfully!";
                } catch(PDOException $e) {
                    $error = "Database error: " . $e->getMessage();
                    // Delete from MinIO if DB insert fails
                    $minioService->deleteFile($fileName);
                }
            } else {
                $error = "Failed to upload to storage service";
            }
        } else {
            $error = "Invalid file type. Allowed types: JPEG, PNG, GIF, MP4, MOV";
        }
    } else {
        $error = "Error uploading file: " . $file['error'];
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
                        <h2 class="text-3xl font-bold text-gray-800 dark:text-gray-200 mb-4">Upload New Media</h2>
                        <label for="file" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Select File</label>
                        <input type="file" name="file" id="file" class="mt-1 block w-full p-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Supported formats: JPG, PNG, GIF, MP4, MOV</p>
                    </div>
                    
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md transition-colors duration-200 transform hover:scale-105">
                        Upload File
                    </button>
                </form>
            </section>

            <section class="bg-white dark:bg-gray-800 rounded-xl p-8 mb-8 shadow-lg hover:shadow-xl transition-shadow duration-300 border border-gray-200 dark:border-gray-700">
                <h2 class="text-3xl font-bold mb-6 border-b pb-4 border-gray-200 dark:border-gray-700">Recently Uploaded Files</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php
                    try {
                        $db = Database::getInstance()->getConnection();
                        $stmt = $db->prepare("SELECT * FROM media_files ORDER BY upload_date DESC LIMIT 6");
                        $stmt->execute();
                        $files = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        foreach($files as $file) {
                            echo '<div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow transform hover:scale-[1.01] transition-all duration-200">';
                            
                            if (strpos($file['file_type'], 'image/') === 0) {
                                echo '<div class="relative aspect-video bg-gray-200 dark:bg-gray-700">';
                                echo '<img src="' . htmlspecialchars($file['url']) . '" alt="' . htmlspecialchars($file['original_filename']) . '" class="absolute inset-0 w-full h-full object-cover">';
                                echo '</div>';
                            } else if (strpos($file['file_type'], 'video/') === 0) {
                                echo '<div class="relative aspect-video bg-gray-200 dark:bg-gray-700">';
                                echo '<video controls class="absolute inset-0 w-full h-full object-cover">
                                        <source src="' . htmlspecialchars($file['url']) . '" type="' . htmlspecialchars($file['file_type']) . '">
                                        Your browser does not support the video tag.
                                      </video>';
                                echo '</div>';
                            }
                            
                            echo '<div class="p-4">';
                            echo '<p class="font-medium text-gray-800 dark:text-gray-200 truncate">' . htmlspecialchars($file['original_filename']) . '</p>';
                            echo '<div class="mt-2 flex justify-between items-center">';
                            echo '<span class="text-sm text-gray-500 dark:text-gray-400">' . date('F j, Y', strtotime($file['upload_date'])) . '</span>';
                            echo '<span class="text-sm text-gray-500 dark:text-gray-400">' . round($file['file_size'] / 1024, 2) . ' KB</span>';
                            echo '</div>';
                            echo '<a href="' . htmlspecialchars($file['url']) . '" target="_blank" class="mt-3 inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded-md text-sm transition-colors duration-200">View</a>';
                            echo '</div>';
                            echo '</div>';
                        }
                        
                        if (count($files) === 0) {
                            echo '<div class="col-span-full text-center p-12">';
                            echo '<p class="text-gray-500 dark:text-gray-400 text-xl">No files uploaded yet.</p>';
                            echo '</div>';
                        } else {
                            echo '<div class="col-span-full text-center mt-4">';
                            echo '<a href="/media/gallery" class="inline-block bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold py-2 px-6 rounded-md transition-colors duration-200">View All Files</a>';
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
</body>
</html>
