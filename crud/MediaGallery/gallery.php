<?php
// Include necessary files
require_once BASE_PATH . '/includes/minioService.php';
require_once BASE_PATH . '/includes/mediaCard.php';

// Pagination parameters
$itemsPerPage = 6;
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($currentPage < 1) $currentPage = 1;
$offset = ($currentPage - 1) * $itemsPerPage;
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

                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-3 gap-6">
                    <?php
                    try {
                        $db = Database::getInstance()->getConnection();
                        
                        // Get total count for pagination
                        $countStmt = $db->query("SELECT COUNT(*) FROM media_files");
                        $totalItems = $countStmt->fetchColumn();
                        $totalPages = ceil($totalItems / $itemsPerPage);
                        
                        // If current page is beyond total pages and there are pages, redirect to last page
                        if ($currentPage > $totalPages && $totalPages > 0) {
                            header("Location: ?page=" . $totalPages);
                            exit();
                        }
                        
                        // Paginated query
                        $stmt = $db->prepare("SELECT * FROM media_files ORDER BY upload_date DESC LIMIT :limit OFFSET :offset");
                        $stmt->bindParam(':limit', $itemsPerPage, PDO::PARAM_INT);
                        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
                        $stmt->execute();
                        $files = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        foreach($files as $file) {
                            renderMediaCard($file);
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
                
                <?php if ($totalItems > 0): ?>
                <!-- Pagination Controls -->
                <div class="mt-8 flex justify-between items-center border-t pt-4 border-gray-200 dark:border-gray-700">
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        Showing <?php echo min(($currentPage - 1) * $itemsPerPage + 1, $totalItems); ?> to 
                        <?php echo min($currentPage * $itemsPerPage, $totalItems); ?> of <?php echo $totalItems; ?> files
                    </div>
                    <div class="flex space-x-2">
                        <?php if ($currentPage > 1): ?>
                            <a href="?page=<?php echo $currentPage - 1; ?>" class="bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 font-bold py-2 px-4 rounded-md transition-colors duration-200">
                                <i class="fas fa-chevron-left mr-1"></i> Previous
                            </a>
                        <?php else: ?>
                            <button disabled class="bg-gray-100 dark:bg-gray-800 text-gray-400 dark:text-gray-600 font-bold py-2 px-4 rounded-md cursor-not-allowed">
                                <i class="fas fa-chevron-left mr-1"></i> Previous
                            </button>
                        <?php endif; ?>
                        
                        <?php if ($currentPage < $totalPages): ?>
                            <a href="?page=<?php echo $currentPage + 1; ?>" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md transition-colors duration-200">
                                Next <i class="fas fa-chevron-right ml-1"></i>
                            </a>
                        <?php else: ?>
                            <button disabled class="bg-blue-400 text-white font-bold py-2 px-4 rounded-md cursor-not-allowed">
                                Next <i class="fas fa-chevron-right ml-1"></i>
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
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
