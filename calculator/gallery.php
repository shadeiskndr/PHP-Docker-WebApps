<?php
// Include necessary files
require_once BASE_PATH . '/includes/minioService.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Browse through media gallery in this PHP Docker Web Application">
    <link href="/output.css" rel="stylesheet">
    <title>Media Gallery - My PHP Docker Web App</title>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900 text-gray-900 dark:text-gray-100 min-h-screen transition-colors duration-200">
    <div class="flex min-h-screen">
        <?php include BASE_PATH . '/includes/navigation.php'; ?>

        <div class="flex-1 p-6 sm:ml-64">
            <header class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-8 mb-8 border border-gray-200 dark:border-gray-700">
                <div class="text-4xl font-extrabold">
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-purple-600">
                        Media Gallery
                    </span>
                </div>
                <p class="mt-4 text-gray-600 dark:text-gray-300 text-xl">Browse through all uploaded media files</p>
            </header>

            <section class="bg-white dark:bg-gray-800 rounded-xl p-8 mb-8 shadow-lg hover:shadow-xl transition-shadow duration-300 border border-gray-200 dark:border-gray-700">
            <div class="flex justify-between items-center mb-6 border-b pb-4 border-gray-200 dark:border-gray-700">
                <h2 class="text-3xl font-bold">All Media Files</h2>
                <a href="/media/upload" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md transition-colors duration-200 flex items-center">
                    <i class="fas fa-plus mr-2"></i> 
                    Upload
                </a>
            </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <?php
                    try {
                        $db = Database::getInstance()->getConnection();
                        $stmt = $db->prepare("SELECT * FROM media_files ORDER BY upload_date DESC");
                        $stmt->execute();
                        $files = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        foreach($files as $file) {
                            echo '<div class="bg-white dark:bg-gray-800 rounded-xl overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-300 border border-gray-200 dark:border-gray-700 transform hover:scale-[1.03] transition-all duration-200">';
                            
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
                            echo '<h3 class="font-medium text-gray-800 dark:text-gray-200 truncate">' . htmlspecialchars($file['original_filename']) . '</h3>';
                            echo '<div class="mt-2 flex justify-between items-center">';
                            echo '<span class="text-sm text-gray-500 dark:text-gray-400">' . date('F j, Y', strtotime($file['upload_date'])) . '</span>';
                            echo '<span class="text-sm text-gray-500 dark:text-gray-400">' . round($file['file_size'] / 1024, 2) . ' KB</span>';
                            echo '</div>';
                            echo '<div class="mt-3 flex space-x-2">';
                            echo '<a href="' . htmlspecialchars($file['url']) . '" target="_blank" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-1 px-3 rounded-md text-sm transition-colors duration-200">View Full Size</a>';
                            echo '<button class="inline-block bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-bold py-1 px-3 rounded-md text-sm transition-colors duration-200" onclick="copyToClipboard(\'' . htmlspecialchars($file['url']) . '\')">Copy URL</button>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        }
                        
                        if (count($files) === 0) {
                            echo '<div class="col-span-full text-center p-12">';
                            echo '<p class="text-gray-500 dark:text-gray-400 text-xl">No media files have been uploaded yet.</p>';
                            echo '<a href="/media/upload" class="mt-4 inline-block bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md transition-colors duration-200">Upload Media</a>';
                            echo '</div>';
                        }
                    } catch(PDOException $e) {
                        echo '<div class="col-span-full p-4 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-100 rounded-lg">';
                        echo "Error loading media files: " . htmlspecialchars($e->getMessage());
                        echo '</div>';
                    }
                    ?>
                </div>
            </section>
        </div>
    </div>

    <!-- JavaScript for copying URLs to clipboard -->
    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                alert('URL copied to clipboard!');
            }).catch(err => {
                console.error('Failed to copy URL: ', err);
            });
        }
    </script>
    
    <script src="<?= $baseUrl ?>public/js/darkMode.js" defer></script>
    <script src="<?= $baseUrl ?>public/js/mobileNav.js" defer></script>
    <script src="<?= $baseUrl ?>public/js/all.min.js"></script>
    <script src="<?= $baseUrl ?>public/js/accordion.js" defer></script>
</body>
</html>
