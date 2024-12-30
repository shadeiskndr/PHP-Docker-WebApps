<?php
declare(strict_types=1);
session_start();

// Security headers
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
<body class="bg-gray-100 text-gray-900">
    <div class="flex">
        <?php include 'includes/navigation.php'; ?>

        <div class="flex-1 p-4">
            <header class="bg-gray-800 text-white p-4 rounded">
                <h1 class="text-4xl font-bold">Welcome to My PHP Docker Web App</h1>
            </header>

            <main class="mt-8">
                <section class="my-8">
                    <h2 class="text-3xl font-semibold mb-4">Hello world!</h2>
                    <p class="text-lg">This is a simple PHP web application running inside of a Docker container.</p>
                </section>

                <section class="my-8">
                    <h2 class="text-2xl font-semibold mb-4">Posts</h2>
                    <div class="bg-white shadow-md rounded-lg p-4">
                        <?php
                        try {
                            $db = Database::getInstance()->getConnection();
                            $stmt = $db->prepare("SELECT * FROM php_docker_table");
                            $stmt->execute();
                            $posts = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            echo "<div class='space-y-4'>";
                            foreach($posts as $post) {
                                echo "<div class='p-4 border-b border-gray-200'>";
                                echo "<h3 class='text-xl font-bold'>" . htmlspecialchars($post['title']) . "</h3>";
                                echo "<p class='text-gray-700'>" . htmlspecialchars($post['body']) . "</p>";
                                echo "<p class='text-sm text-gray-500'>" . htmlspecialchars($post['date_created']) . "</p>";
                                echo "</div>";
                            }
                            echo "</div>";
                        } catch(PDOException $e) {
                            echo "<div class='text-red-500'>Error loading posts: " . htmlspecialchars($e->getMessage()) . "</div>";
                        }
                        ?>
                    </div>
                </section>
            </main>

            <footer class="bg-gray-800 text-white p-4 text-center rounded">
                <p>&copy; <?= date('Y') ?> My PHP Full-Stack Web App | Made by <a href="https://shahathir.me" class="underline">Shahathir Iskandar</a></p>
            </footer>
        </div>
    </div>
</body>
</html>
