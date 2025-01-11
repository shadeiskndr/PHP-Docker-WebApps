<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/output.css" rel="stylesheet">
    <title>BMI Calculator</title>
</head>
<body class="bg-gradient-to-br from-yellow-200 to-lime-200 dark:from-gray-800 dark:to-gray-900 text-gray-900 dark:text-gray-100 min-h-screen transition-colors duration-200">
    <div class="flex min-h-screen">
        <?php include BASE_PATH . '/includes/navigation.php'; ?>

        <div class="flex-1 p-6 sm:ml-64">
            <header class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-8 mb-8 border border-gray-200 dark:border-gray-700">
                <div class="text-5xl font-extrabold ...">
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-yellow-600 to-lime-600">
                        BMI Calculator
                    </span>
                </div>
                <p class="mt-4 text-gray-600 dark:text-gray-300 text-xl">Calculate your Body Mass Index (BMI)</p>
            </header>

            <section class="bg-white dark:bg-gray-800 rounded-xl p-8 shadow-lg hover:shadow-xl transition-shadow duration-300 mb-8 border border-gray-200 dark:border-gray-700">
                <form action="bmi" method="post">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                        <div class="space-y-2 w-full">
                            <label for="name" class="text-lg font-medium text-gray-700 dark:text-gray-300">Name</label>
                            <input type="text" name="name" id="name" value="<?php if(isset($_POST['name'])) echo $_POST['name']; ?>"
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:outline-none
                                    focus:ring-2 focus:ring-lime-500 hover:border-lime-500 focus:border-lime-500
                                    dark:bg-gray-700 dark:text-white transition duration-200" />
                        </div>
                        <div class="space-y-2 w-full">
                            <label for="height" class="text-lg font-medium text-gray-700 dark:text-gray-300">Height (m)</label>
                            <input type="text" name="height" id="height" value="<?php if(isset($_POST['height'])) echo $_POST['height']; ?>"
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:outline-none
                                    focus:ring-2 focus:ring-lime-500 hover:border-lime-500 focus:border-lime-500
                                    dark:bg-gray-700 dark:text-white transition duration-200" />
                        </div>
                        <div class="space-y-2 w-full">
                            <label for="weight" class="text-lg font-medium text-gray-700 dark:text-gray-300">Weight (kg)</label>
                            <input type="text" name="weight" id="weight" value="<?php if(isset($_POST['weight'])) echo $_POST['weight']; ?>"
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:outline-none
                                    focus:ring-2 focus:ring-lime-500 hover:border-lime-500 focus:border-lime-500
                                    dark:bg-gray-700 dark:text-white transition duration-200" />
                        </div>
                    </div>
                    <button type="submit" name="submit" 
                        class="px-6 py-3 bg-gradient-to-r from-yellow-600 to-lime-600 
                            text-white font-semibold rounded-lg shadow-md hover:from-yellow-700 
                            hover:to-lime-700 focus:outline-none focus:ring-2 focus:ring-lime-500 
                            focus:ring-opacity-75 hover:scale-[1.02] transition-transform duration-300">
                        <i class="fas fa-calculator mr-2"></i> Calculate BMI
                    </button>
                </form>
            </section>

            <?php if(isset($_REQUEST['submit'])): ?>
                <section class="bg-white dark:bg-gray-800 rounded-xl p-8 shadow-lg hover:shadow-xl transition-shadow duration-300 mb-8 border border-gray-200 dark:border-gray-700">
                    <?php
                        if(isset($_REQUEST['submit'])){
                            $errors = array();

                            // Validate name
                            if (!empty($_POST['name'])) {
                                if (!is_numeric($_POST['name'])) {
                                    $name = $_POST['name'];
                                } else {
                                    $name = NULL;
                                    $errors[] = '<div class="p-4 bg-red-100 dark:bg-red-900 rounded-lg mb-4">
                                                    <p class="text-red-700 dark:text-red-100 font-bold">
                                                        <i class="fas fa-exclamation-circle mr-2"></i>Name must be text only
                                                    </p>
                                                </div>';
                                }
                            } else {
                                $name = NULL;
                                $errors[] = '<div class="p-4 bg-red-100 dark:bg-red-900 rounded-lg mb-4">
                                                <p class="text-red-700 dark:text-red-100 font-bold">
                                                    <i class="fas fa-exclamation-circle mr-2"></i>Please enter your name
                                                </p>
                                            </div>';
                            }

                            // Validate height
                            if (!empty($_POST['height'])) {
                                if (is_numeric($_POST['height'])) {
                                    $height = $_POST['height'];
                                    if ($height > 3) {
                                        $height = NULL;
                                        $errors[] = '<div class="p-4 bg-red-100 dark:bg-red-900 rounded-lg mb-4">
                                                        <p class="text-red-700 dark:text-red-100 font-bold">
                                                            <i class="fas fa-exclamation-circle mr-2"></i>Height must be in meters (e.g., 1.75)
                                                        </p>
                                                    </div>';
                                    }
                                } else {
                                    $height = NULL;
                                    $errors[] = '<div class="p-4 bg-red-100 dark:bg-red-900 rounded-lg mb-4">
                                                    <p class="text-red-700 dark:text-red-100 font-bold">
                                                        <i class="fas fa-exclamation-circle mr-2"></i>Height must be numeric
                                                    </p>
                                                </div>';
                                }
                            } else {
                                $height = NULL;
                                $errors[] = '<div class="p-4 bg-red-100 dark:bg-red-900 rounded-lg mb-4">
                                                <p class="text-red-700 dark:text-red-100 font-bold">
                                                    <i class="fas fa-exclamation-circle mr-2"></i>Please enter your height
                                                </p>
                                            </div>';
                            }

                            // Validate weight
                            if (!empty($_POST['weight'])) {
                                if (is_numeric($_POST['weight'])) {
                                    $weight = $_POST['weight'];
                                } else {
                                    $weight = NULL;
                                    $errors[] = '<div class="p-4 bg-red-100 dark:bg-red-900 rounded-lg mb-4">
                                                    <p class="text-red-700 dark:text-red-100 font-bold">
                                                        <i class="fas fa-exclamation-circle mr-2"></i>Weight must be numeric
                                                    </p>
                                                </div>';
                                }
                            } else {
                                $weight = NULL;
                                $errors[] = '<div class="p-4 bg-red-100 dark:bg-red-900 rounded-lg mb-4">
                                                <p class="text-red-700 dark:text-red-100 font-bold">
                                                    <i class="fas fa-exclamation-circle mr-2"></i>Please enter your weight
                                                </p>
                                            </div>';
                            }

                            // If everything is valid, calculate and display BMI
                            if ($name && $height && $weight) {
                                $bmi = $weight / ($height * $height);
                                $bmi = number_format($bmi, 2);
                                
                                // Determine BMI category and corresponding color
                                $categoryClass = '';
                                $categoryIcon = '';
                                switch(true) {
                                    case $bmi < 18.5:
                                        $category = "UNDERWEIGHT";
                                        $categoryClass = "text-blue-600 dark:text-blue-400";
                                        $categoryIcon = "fa-arrow-down";
                                        break;
                                    case $bmi <= 24.9:
                                        $category = "NORMAL";
                                        $categoryClass = "text-green-600 dark:text-green-400";
                                        $categoryIcon = "fa-check";
                                        break;
                                    case $bmi <= 29.9:
                                        $category = "OVERWEIGHT";
                                        $categoryClass = "text-yellow-600 dark:text-yellow-400";
                                        $categoryIcon = "fa-arrow-up";
                                        break;
                                    default:
                                        $category = "OBESE";
                                        $categoryClass = "text-red-600 dark:text-red-400";
                                        $categoryIcon = "fa-exclamation-triangle";
                                }

                                echo "<div class='p-6 bg-lime-50 dark:bg-lime-900/30 rounded-xl shadow-md'>
                                        <div class='mb-4'>
                                            <h3 class='text-2xl font-bold text-lime-700 dark:text-lime-300 mb-2'>Hello, $name!</h3>
                                            <p class='text-gray-600 dark:text-gray-300 text-lg'>Your BMI Results:</p>
                                        </div>
                                        <div class='grid grid-cols-1 md:grid-cols-2 gap-4 mb-4'>
                                            <div class='p-4 bg-white dark:bg-gray-800 rounded-lg shadow'>
                                                <p class='text-3xl font-bold text-lime-600 dark:text-lime-400'>$bmi</p>
                                                <p class='text-gray-500 dark:text-gray-400'>BMI Value</p>
                                            </div>
                                            <div class='p-4 bg-white dark:bg-gray-800 rounded-lg shadow'>
                                                <p class='text-3xl font-bold $categoryClass'>
                                                    <i class='fas $categoryIcon mr-2'></i>$category
                                                </p>
                                                <p class='text-gray-500 dark:text-gray-400'>Category</p>
                                            </div>
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
