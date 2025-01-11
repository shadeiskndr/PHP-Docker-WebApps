<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/output.css" rel="stylesheet">
    <title>Tax Calculator</title>
</head>
<body class="bg-gradient-to-br from-yellow-200 to-amber-200 dark:from-gray-800 dark:to-gray-900 text-gray-900 dark:text-gray-100 min-h-screen transition-colors duration-200">
    <div class="flex min-h-screen">
        <?php include BASE_PATH . '/includes/navigation.php'; ?>

        <div class="flex-1 p-6 sm:ml-64">
            <header class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-8 mb-8 border border-gray-200 dark:border-gray-700">
                <div class="text-5xl font-extrabold ...">
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-yellow-600 to-amber-600">
                        Tax Calculator
                    </span>
                </div>
                <p class="mt-4 text-gray-600 dark:text-gray-300 text-xl">Calculate the total price including tax</p>
            </header>

            <section class="bg-white dark:bg-gray-800 rounded-xl p-8 shadow-lg hover:shadow-xl transition-shadow duration-300 mb-8 border border-gray-200 dark:border-gray-700">
                <form action="tax" method="post">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                        <div class="space-y-2 w-full">
                            <label for="quantity" class="text-lg font-medium text-gray-700 dark:text-gray-300">Quantity</label>
                            <input type="text" name="quantity" id="quantity" value="<?php if(isset($_POST['quantity'])) echo $_POST['quantity']; ?>"
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:outline-none
                                    focus:ring-2 focus:ring-yellow-500 hover:border-yellow-500 focus:border-yellow-500
                                    dark:bg-gray-700 dark:text-white transition duration-200" />
                        </div>
                        <div class="space-y-2 w-full">
                            <label for="price" class="text-lg font-medium text-gray-700 dark:text-gray-300">Price (RM)</label>
                            <input type="text" name="price" id="price" value="<?php if(isset($_POST['price'])) echo $_POST['price']; ?>"
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:outline-none
                                    focus:ring-2 focus:ring-yellow-500 hover:border-yellow-500 focus:border-yellow-500
                                    dark:bg-gray-700 dark:text-white transition duration-200" />
                        </div>
                        <div class="space-y-2 w-full">
                            <label for="tax" class="text-lg font-medium text-gray-700 dark:text-gray-300">Tax Rate (%)</label>
                            <input type="text" name="tax" id="tax" value="<?php if(isset($_POST['tax'])) echo $_POST['tax']; ?>"
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:outline-none
                                    focus:ring-2 focus:ring-yellow-500 hover:border-yellow-500 focus:border-yellow-500
                                    dark:bg-gray-700 dark:text-white transition duration-200" />
                        </div>
                    </div>
                    <button type="submit" name="submit" 
                        class="px-6 py-3 bg-gradient-to-r from-yellow-600 to-amber-600 
                            text-white font-semibold rounded-lg shadow-md hover:from-yellow-700 
                            hover:to-amber-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 
                            focus:ring-opacity-75 hover:scale-[1.02] transition-transform duration-300">
                        <i class="fas fa-calculator mr-2"></i> Calculate Tax
                    </button>
                </form>
            </section>

            <?php if(isset($_POST['submit'])): ?>
                <section class="bg-white dark:bg-gray-800 rounded-xl p-8 shadow-lg hover:shadow-xl transition-shadow duration-300 mb-8 border border-gray-200 dark:border-gray-700">
                    <?php
                        if (!empty($_POST['quantity']) && !empty($_POST['price']) && !empty($_POST['tax'])) {
                            // Validate that all inputs are numeric
                            if (is_numeric($_POST['quantity']) && is_numeric($_POST['price']) && is_numeric($_POST['tax'])) {
                                $quantity = floatval($_POST['quantity']);
                                $price = floatval($_POST['price']);
                                $tax = floatval($_POST['tax']);
                                
                                // Additional validation for positive numbers
                                if ($quantity > 0 && $price > 0 && $tax >= 0) {
                                    $total = $quantity * $price;
                                    $total = $total + ($total * ($tax/100));
                                    $total = number_format($total, 2);
                                    
                                    echo '<div class="p-4 bg-green-100 dark:bg-green-900 rounded-lg">
                                        <p class="text-green-700 dark:text-green-100">
                                            <span class="text-xl font-bold block mb-2">Calculation Results</span>
                                            <span class="block">Quantity: ' . htmlspecialchars($quantity) . '</span>
                                            <span class="block">Price per Unit: RM ' . htmlspecialchars($price) . '</span>
                                            <span class="block">Tax Rate: ' . htmlspecialchars($tax) . '%</span>
                                            <span class="block font-bold mt-2">Final Price: RM ' . htmlspecialchars($total) . '</span>
                                        </p>
                                    </div>';
                                } else {
                                    echo '<div class="p-4 bg-red-100 dark:bg-red-900 rounded-lg">
                                        <p class="text-red-700 dark:text-red-100 font-bold"><i class="fas fa-exclamation-circle mr-2"></i>Please enter valid positive numbers.</p>
                                    </div>';
                                }
                            } else {
                                echo '<div class="p-4 bg-red-100 dark:bg-red-900 rounded-lg">
                                    <p class="text-red-700 dark:text-red-100 font-bold"><i class="fas fa-exclamation-circle mr-2"></i>Please enter numeric values only.</p>
                                </div>';
                            }
                        } else {
                            echo '<div class="p-4 bg-red-100 dark:bg-red-900 rounded-lg">
                                <p class="text-red-700 dark:text-red-100 font-bold"><i class="fas fa-exclamation-circle mr-2"></i>Please fill out all fields in the form.</p>
                            </div>';
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
