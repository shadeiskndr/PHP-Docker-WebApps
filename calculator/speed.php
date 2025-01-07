<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/output.css" rel="stylesheet">
    <title>Speed Converter</title>
</head>
<body class="bg-gradient-to-br from-orange-200 to-amber-200 dark:from-gray-800 dark:to-gray-900 text-gray-900 dark:text-gray-100 min-h-screen transition-colors duration-200">
    <div class="flex min-h-screen">
        <?php include BASE_PATH . '/includes/navigation.php'; ?>

        <div class="flex-1 p-6 sm:ml-64">
            <header class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-8 mb-8 border border-gray-200 dark:border-gray-700">
                <h1 class="text-5xl font-extrabold bg-gradient-to-r from-orange-600 to-amber-600 bg-clip-text text-transparent">Speed Converter</h1>
                <p class="mt-4 text-gray-600 dark:text-gray-300 text-xl">Convert speeds between MPH and KPH</p>
            </header>

            <section class="bg-white dark:bg-gray-800 rounded-xl p-8 shadow-lg hover:shadow-xl transition-shadow duration-300 mb-8 border border-gray-200 dark:border-gray-700">
                <form action="speed" method="post">
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label for="speed" class="text-lg font-medium text-gray-700 dark:text-gray-300">Speed (MPH)</label>
                            <input type="text" name="speed" id="speed" value="<?php if(isset($_POST['speed'])) echo $_POST['speed']; ?>"
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:outline-none
                                    focus:ring-2 focus:ring-orange-500 hover:border-orange-500 focus:border-orange-500
                                    dark:bg-gray-700 dark:text-white transition duration-200" />
                        </div>
                        
                        <div class="space-y-2">
                            <p class="text-lg font-medium text-gray-700 dark:text-gray-300">From: Miles per Hour (MPH)</p>
                            <p class="text-lg font-medium text-gray-700 dark:text-gray-300">To: Kilometer per Hour (KPH)</p>
                        </div>

                        <button type="submit" name="submit" 
                            class="px-6 py-3 bg-gradient-to-r from-orange-600 to-amber-600
                                text-white font-semibold rounded-lg shadow-md hover:from-orange-700
                                hover:to-amber-700 focus:outline-none focus:ring-2 focus:ring-orange-500
                                  focus:ring-opacity-75 hover:scale-[1.02] transition-transform duration-300">
                            <i class="fas fa-exchange-alt mr-2"></i> Convert Speed
                        </button>
                    </div>
                </form>
            </section>

            <?php if(isset($_REQUEST['submit'])): ?>
                <section class="bg-white dark:bg-gray-800 rounded-xl p-8 shadow-lg hover:shadow-xl transition-shadow duration-300 mb-8 border border-gray-200 dark:border-gray-700">
                    <?php
                    if (empty($_POST['speed'])) {
                        echo '<div class="p-4 bg-red-100 dark:bg-red-900 rounded-lg">
                                <p class="text-red-700 dark:text-red-100 font-bold">Please enter a valid speed value!</p>
                            </div>';
                    } else {
                        $speed = $_POST['speed'];
                        if (is_numeric($speed)) {
                            $converted_speed = number_format($speed * 1.609, 3);
                            echo '<div class="p-4 bg-green-100 dark:bg-green-900 rounded-lg">
                                    <p class="text-green-700 dark:text-green-100">
                                        <span class="text-xl font-bold block mb-2">Conversion Results</span>
                                        <span class="block">Original Speed: ' . htmlspecialchars($speed) . ' MPH</span>
                                        <span class="block font-bold mt-2">Converted Speed: ' . htmlspecialchars($converted_speed) . ' KPH</span>
                                    </p>
                                </div>';
                        } else {
                            echo '<div class="p-4 bg-red-100 dark:bg-red-900 rounded-lg">
                                    <p class="text-red-700 dark:text-red-100 font-bold">Please enter a numeric value only!</p>
                                </div>';
                        }
                    }
                    ?>
                </section>
            <?php endif; ?>
        </div>
    </div>
    <script src="<?= $baseUrl ?>public/js/darkMode.js" defer></script>
    <script src="<?= $baseUrl ?>public/js/mobileNav.js" defer></script>
    <script src="<?= $baseUrl ?>public/js/all.min.js"></script>
    <script src="<?= $baseUrl ?>public/js/accordion.js" defer></script>
</body>
</html>
