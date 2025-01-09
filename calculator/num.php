<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/output.css" rel="stylesheet">
    <title>Number Comparison</title>
</head>
<body class="bg-gradient-to-br from-emerald-200 to-green-200 dark:from-gray-800 dark:to-gray-900 text-gray-900 dark:text-gray-100 min-h-screen transition-colors duration-200">
    <div class="flex min-h-screen">
        <?php include BASE_PATH . '/includes/navigation.php'; ?>

        <div class="flex-1 p-6 sm:ml-64">
            <header class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-8 mb-8 border border-gray-200 dark:border-gray-700">
                <h1 class="text-5xl font-extrabold bg-gradient-to-r from-emerald-600 to-green-700 bg-clip-text text-transparent">Number Comparison</h1>
                <p class="mt-4 text-gray-600 dark:text-gray-300 text-xl">Find out which number is bigger</p>
            </header>

            <section class="bg-white dark:bg-gray-800 rounded-xl p-8 shadow-lg hover:shadow-xl transition-shadow duration-300 mb-8 border border-gray-200 dark:border-gray-700">
                <form action="num" method="post">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                        <div class="space-y-2 w-full">
                            <label for="first" class="text-lg font-medium text-gray-700 dark:text-gray-300">First Number</label>
                            <input type="text" name="first" id="first" value="<?php if(isset($_POST['first'])) echo $_POST['first']; ?>"
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:outline-none
                                    focus:ring-2 focus:ring-emerald-500 hover:border-emerald-500 focus:border-emerald-500
                                    dark:bg-gray-700 dark:text-white transition duration-200" />
                        </div>
                        <div class="space-y-2 w-full">
                            <label for="second" class="text-lg font-medium text-gray-700 dark:text-gray-300">Second Number</label>
                            <input type="text" name="second" id="second" value="<?php if(isset($_POST['second'])) echo $_POST['second']; ?>"
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:outline-none
                                    focus:ring-2 focus:ring-emerald-500 hover:border-emerald-500 focus:border-emerald-500
                                    dark:bg-gray-700 dark:text-white transition duration-200" />
                        </div>
                    </div>
                    <button type="submit" name="submit" 
                        class="px-6 py-3 bg-gradient-to-r from-emerald-600 to-green-700 
                            text-white font-semibold rounded-lg shadow-md hover:from-emerald-700 
                            hover:to-green-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 
                            focus:ring-opacity-75 hover:scale-[1.02] transition-transform duration-300">
                        <i class="fas fa-greater-than mr-2"></i> Compare Numbers
                    </button>
                </form>
            </section>

            <?php if(isset($_REQUEST['submit'])): ?>
                <section class="bg-white dark:bg-gray-800 rounded-xl p-8 shadow-lg hover:shadow-xl transition-shadow duration-300 mb-8 border border-gray-200 dark:border-gray-700">
                <?php
                if(isset($_REQUEST['submit'])){
                    $errors = array();

                    // Validate first number
                    if (!empty($_POST['first'])) {
                        if (is_numeric($_POST['first'])) {
                            $first = $_POST['first'];
                        } else {
                            $first = NULL;
                            $errors[] = '<div class="p-4 bg-red-100 dark:bg-red-900 rounded-lg mb-4">
                                            <p class="text-red-700 dark:text-red-100 font-bold">
                                                <i class="fas fa-exclamation-circle mr-2"></i>First number must be numeric
                                            </p>
                                        </div>';
                        }
                    } else {
                        $first = NULL;
                        $errors[] = '<div class="p-4 bg-red-100 dark:bg-red-900 rounded-lg mb-4">
                                        <p class="text-red-700 dark:text-red-100 font-bold">
                                            <i class="fas fa-exclamation-circle mr-2"></i>Please enter the first number
                                        </p>
                                    </div>';
                    }

                    // Validate second number
                    if (!empty($_POST['second'])) {
                        if (is_numeric($_POST['second'])) {
                            $second = $_POST['second'];
                        } else {
                            $second = NULL;
                            $errors[] = '<div class="p-4 bg-red-100 dark:bg-red-900 rounded-lg mb-4">
                                            <p class="text-red-700 dark:text-red-100 font-bold">
                                                <i class="fas fa-exclamation-circle mr-2"></i>Second number must be numeric
                                            </p>
                                        </div>';
                        }
                    } else {
                        $second = NULL;
                        $errors[] = '<div class="p-4 bg-red-100 dark:bg-red-900 rounded-lg mb-4">
                                        <p class="text-red-700 dark:text-red-100 font-bold">
                                            <i class="fas fa-exclamation-circle mr-2"></i>Please enter the second number
                                        </p>
                                    </div>';
                    }

                    if (empty($errors)) {
                        echo "<div class='p-6 bg-emerald-50 dark:bg-emerald-900/30 rounded-xl shadow-md'>
                                <div class='grid grid-cols-1 md:grid-cols-2 gap-4 mb-4'>
                                    <div class='p-4 bg-white dark:bg-gray-800 rounded-lg shadow'>
                                        <p class='text-3xl font-bold text-emerald-600 dark:text-emerald-400'>$first</p>
                                        <p class='text-gray-500 dark:text-gray-400'>First Number</p>
                                    </div>
                                    <div class='p-4 bg-white dark:bg-gray-800 rounded-lg shadow'>
                                        <p class='text-3xl font-bold text-emerald-600 dark:text-emerald-400'>$second</p>
                                        <p class='text-gray-500 dark:text-gray-400'>Second Number</p>
                                    </div>
                                </div>
                                <div class='p-4 bg-white dark:bg-gray-800 rounded-lg shadow text-center'>";
                        switch(true) {
                            case $first > $second:
                                echo "<p class='text-2xl font-bold text-emerald-600 dark:text-emerald-400'>
                                        <i class='fas fa-trophy mr-2'></i>$first is bigger than $second
                                    </p>";
                                break;
                            case $first < $second:
                                echo "<p class='text-2xl font-bold text-emerald-600 dark:text-emerald-400'>
                                        <i class='fas fa-trophy mr-2'></i>$second is bigger than $first
                                    </p>";
                                break;
                            default:
                                echo "<p class='text-2xl font-bold text-emerald-600 dark:text-emerald-400'>
                                        <i class='fas fa-equals mr-2'></i>Both numbers are equal
                                    </p>";
                        }
                        echo "</div></div>";
                    } else {
                        echo '<div class="space-y-4">';
                        foreach ($errors as $error) {
                            echo $error;
                        }
                        echo '</div>';
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
