<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="404 - Page Not Found">
    <link href="/output.css" rel="stylesheet">
    <title>404 - Page Not Found | My PHP Docker Web App</title>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900 text-gray-900 dark:text-gray-100 min-h-screen transition-colors duration-200">
    <div class="flex min-h-screen">
        <?php include BASE_PATH . '/includes/navigation.php'; ?>

        <div class="flex-1 p-6 sm:ml-64">
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-8 mb-8 border border-gray-200 dark:border-gray-700 text-center">
                <div class="inline-flex items-center justify-center">
                    <span class="text-5xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-purple-600 ml-4">
                        404
                    </span>
                </div>
                <p class="mt-4 text-2xl md:text-3xl font-light text-gray-600 dark:text-gray-300">Oops! The page you're looking for isn't here.</p>
                <p class="text-md md:text-lg mt-2 text-gray-500 dark:text-gray-400">You might have the wrong address, or the page may have moved.</p>
                <div class="mt-8 space-x-4">
                    <a href="/" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white rounded-lg hover:opacity-90 transition duration-200">Return Home</a>
                </div>
            </div>

            <footer class="bg-white dark:bg-gray-800 rounded-xl p-6 text-center shadow-lg border border-gray-200 dark:border-gray-700">
                <p class="text-gray-600 dark:text-gray-300">&copy; <?= date('Y') ?> My PHP Full-Stack Web App | Created by 
                    <a href="https://shahathir.me" class="text-blue-600 dark:text-blue-400 hover:underline font-medium">Shahathir Iskandar</a>
                </p>
            </footer>
        </div>
    </div>
    <script src="<?= $baseUrl ?>public/js/darkMode.js" defer></script>
    <script src="<?= $baseUrl ?>public/js/mobileNav.js" defer></script>
    <script src="<?= $baseUrl ?>public/js/all.min.js"></script>
    <script src="<?= $baseUrl ?>public/js/accordion.js" defer></script>
</body>
</html>
