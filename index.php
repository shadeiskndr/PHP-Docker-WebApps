<?php
declare(strict_types=1);
session_start();

// Security headers remain the same
header("Content-Security-Policy: default-src 'self'; style-src 'self' 'unsafe-inline';");
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");
header("X-Content-Type-Options: nosniff");

require_once __DIR__ . '/includes/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="PHP Docker Web Application with various calculators and tools">
    <link href="output.css" rel="stylesheet">
    <title>My PHP Docker Web App</title>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900 text-gray-900 dark:text-gray-100 min-h-screen transition-colors duration-200">
    <div class="flex min-h-screen">
        <?php include 'includes/navigation.php'; ?>

        <div class="flex-1 p-6 sm:ml-64">
            <header class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-8 mb-8 transform hover:scale-[1.02] transition-transform duration-300">
                <h1 class="text-5xl font-extrabold bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Welcome to My PHP Docker Web App</h1>
                <p class="mt-4 text-gray-600 dark:text-gray-300 text-xl">Your modern solution for web applications</p>
            </header>

            <main class="space-y-12">
                <section class="bg-white dark:bg-gray-800 rounded-xl p-8 shadow-lg hover:shadow-xl transition-shadow duration-300">
                    <h2 class="text-3xl font-bold mb-6 border-b pb-4 border-gray-200 dark:border-gray-700">About This Project</h2>
                    <p class="text-xl leading-relaxed text-gray-700 dark:text-gray-300">This is a sophisticated PHP web application running inside a Docker container, showcasing modern development practices and clean architecture.</p>
                </section>

                <section class="bg-white dark:bg-gray-800 rounded-xl p-8 shadow-lg hover:shadow-xl transition-shadow duration-300">
                    <h2 class="text-3xl font-bold mb-6 border-b pb-4 border-gray-200 dark:border-gray-700">Latest Posts</h2>
                    <div class="space-y-6">
                        <?php
                        try {
                            $db = Database::getInstance()->getConnection();
                            $stmt = $db->prepare("SELECT * FROM php_docker_table ORDER BY date_created DESC");
                            $stmt->execute();
                            $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            foreach($posts as $post) {
                                echo "<article class='p-6 bg-gray-50 dark:bg-gray-700 rounded-lg transform hover:scale-[1.01] transition-all duration-200'>";
                                echo "<h3 class='text-2xl font-bold mb-2 text-blue-600 dark:text-blue-400'>" . htmlspecialchars($post['title']) . "</h3>";
                                echo "<p class='text-gray-700 dark:text-gray-300 mb-4 leading-relaxed'>" . htmlspecialchars($post['body']) . "</p>";
                                echo "<time class='text-sm text-gray-500 dark:text-gray-400 italic'>" . date('F j, Y', strtotime($post['date_created'])) . "</time>";
                                echo "</article>";
                            }
                        } catch(PDOException $e) {
                            echo "<div class='p-4 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-100 rounded-lg'>";
                            echo "Error loading posts: " . htmlspecialchars($e->getMessage());
                            echo "</div>";
                        }
                        ?>
                    </div>
                </section>
            </main>

            <footer class="mt-12 bg-white dark:bg-gray-800 rounded-xl p-6 text-center shadow-lg">
                <p class="text-gray-600 dark:text-gray-300">&copy; <?= date('Y') ?> My PHP Full-Stack Web App | Created by 
                    <a href="https://shahathir.me" class="text-blue-600 dark:text-blue-400 hover:underline font-medium">Shahathir Iskandar</a>
                </p>
            </footer>
        </div>
    </div>
    <script src="public/js/darkMode.js" defer></script>
    <script src="public/js/mobileNav.js" defer></script>
    <script src="public/js/all.min.js"></script>
</body>
</html>
