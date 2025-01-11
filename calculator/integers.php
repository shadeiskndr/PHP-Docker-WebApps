<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/output.css" rel="stylesheet">
    <title>Integer Addition Calculator</title>
</head>
<body class="bg-gradient-to-br from-sky-200 to-blue-200 dark:from-gray-800 dark:to-gray-900 text-gray-900 dark:text-gray-100 min-h-screen transition-colors duration-200">
    <div class="flex min-h-screen">
        <?php include BASE_PATH . '/includes/navigation.php'; ?>

        <div class="flex-1 p-6 sm:ml-64">
            <header class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-8 mb-8 border border-gray-200 dark:border-gray-700">
                <div class="text-5xl font-extrabold ...">
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-sky-500 to-blue-600">
                        Integer Addition
                    </span>
                </div>
                <p class="mt-4 text-gray-600 dark:text-gray-300 text-xl">Add three integers together</p>
            </header>

            <section class="bg-white dark:bg-gray-800 rounded-xl p-8 shadow-lg hover:shadow-xl transition-shadow duration-300 mb-8 border border-gray-200 dark:border-gray-700">
                <form action="integers" method="post">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                        <div class="space-y-2 w-full">
                            <label for="integer1" class="text-lg font-medium text-gray-700 dark:text-gray-300">First Integer</label>
                            <input type="text" name="integer1" id="integer1" value="<?php if(isset($_POST['integer1'])) echo $_POST['integer1']; ?>"
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:outline-none
                                    focus:ring-2 focus:ring-sky-500 hover:border-sky-500 focus:border-sky-500
                                    dark:bg-gray-700 dark:text-white transition duration-200" />
                        </div>
                        <div class="space-y-2 w-full">
                            <label for="integer2" class="text-lg font-medium text-gray-700 dark:text-gray-300">Second Integer</label>
                            <input type="text" name="integer2" id="integer2" value="<?php if(isset($_POST['integer2'])) echo $_POST['integer2']; ?>"
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:outline-none
                                    focus:ring-2 focus:ring-sky-500 hover:border-sky-500 focus:border-sky-500
                                    dark:bg-gray-700 dark:text-white transition duration-200" />
                        </div>
                        <div class="space-y-2 w-full">
                            <label for="integer3" class="text-lg font-medium text-gray-700 dark:text-gray-300">Third Integer</label>
                            <input type="text" name="integer3" id="integer3" value="<?php if(isset($_POST['integer3'])) echo $_POST['integer3']; ?>"
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:outline-none
                                    focus:ring-2 focus:ring-sky-500 hover:border-sky-500 focus:border-sky-500
                                    dark:bg-gray-700 dark:text-white transition duration-200" />
                        </div>
                    </div>
                    <button type="submit" name="submit" 
                        class="px-6 py-3 bg-gradient-to-r from-sky-500 to-blue-600 
                            text-white font-semibold rounded-lg shadow-md hover:from-sky-600 
                            hover:to-blue-700 focus:outline-none focus:ring-2 focus:ring-sky-500 
                            focus:ring-opacity-75 hover:scale-[1.02] transition-transform duration-300">
                        <i class="fas fa-plus-circle mr-2"></i> Add Numbers
                    </button>
                </form>
            </section>

            <?php if(isset($_POST['submit'])): ?>
                <section class="bg-white dark:bg-gray-800 rounded-xl p-8 shadow-lg hover:shadow-xl transition-shadow duration-300 mb-8 border border-gray-200 dark:border-gray-700">
                <?php
                if(isset($_POST['submit'])){
                    $errors = array();

                    // Validation for all three integers with consistent error styling
                    foreach(['integer1', 'integer2', 'integer3'] as $index => $field) {
                        if (!empty($_POST[$field])) {
                            if (is_numeric($_POST[$field])) {
                                ${'integer'.($index+1)} = $_POST[$field];
                            } else {
                                ${'integer'.($index+1)} = NULL;
                                $errors[] = "<div class='p-4 bg-red-100 dark:bg-red-900 rounded-lg mb-4'>
                                                <p class='text-red-700 dark:text-red-100 font-bold'>
                                                    <i class='fas fa-exclamation-circle mr-2'></i>Integer ".($index+1)." must be numeric
                                                </p>
                                            </div>";
                            }
                        } else {
                            ${'integer'.($index+1)} = NULL;
                            $errors[] = "<div class='p-4 bg-red-100 dark:bg-red-900 rounded-lg mb-4'>
                                            <p class='text-red-700 dark:text-red-100 font-bold'>
                                                <i class='fas fa-exclamation-circle mr-2'></i>Please enter integer ".($index+1)."
                                            </p>
                                        </div>";
                        }
                    }

                    if (empty($errors)) {
                        $total = $integer1 + $integer2 + $integer3;
                        echo "<div class='p-6 bg-sky-50 dark:bg-sky-900/30 rounded-xl shadow-md'>
                                <div class='grid grid-cols-1 md:grid-cols-3 gap-4 mb-4'>
                                    <div class='p-4 bg-white dark:bg-gray-800 rounded-lg shadow'>
                                        <p class='text-3xl font-bold text-sky-600 dark:text-sky-400'>$integer1</p>
                                        <p class='text-gray-500 dark:text-gray-400'>First Integer</p>
                                    </div>
                                    <div class='p-4 bg-white dark:bg-gray-800 rounded-lg shadow'>
                                        <p class='text-3xl font-bold text-sky-600 dark:text-sky-400'>$integer2</p>
                                        <p class='text-gray-500 dark:text-gray-400'>Second Integer</p>
                                    </div>
                                    <div class='p-4 bg-white dark:bg-gray-800 rounded-lg shadow'>
                                        <p class='text-3xl font-bold text-sky-600 dark:text-sky-400'>$integer3</p>
                                        <p class='text-gray-500 dark:text-gray-400'>Third Integer</p>
                                    </div>
                                </div>
                                <div class='p-4 bg-white dark:bg-gray-800 rounded-lg shadow text-center'>
                                    <p class='text-2xl font-bold text-sky-600 dark:text-sky-400'>
                                        <i class='fas fa-equals mr-2'></i>Total: $total
                                    </p>
                                </div>
                            </div>";
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
