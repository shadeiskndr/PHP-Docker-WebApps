<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/output.css" rel="stylesheet">
    <title>Discount Calculator</title>
</head>
<body class="bg-gradient-to-br from-red-200 to-rose-200 dark:from-gray-800 dark:to-gray-900 text-gray-900 dark:text-gray-100 min-h-screen transition-colors duration-200">
    <div class="flex min-h-screen">
        <?php include BASE_PATH . '/includes/navigation.php'; ?>

        <div class="flex-1 p-6 sm:ml-64">
            <header class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-8 mb-8 border border-gray-200 dark:border-gray-700">
                <div class="text-5xl font-extrabold ...">
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-red-600 to-rose-900">
                        Discount Calculator
                    </span>
                </div>
                <p class="mt-4 text-gray-600 dark:text-gray-300 text-xl">Calculate the final price after discount</p>
            </header>

            <section class="bg-white dark:bg-gray-800 rounded-xl p-8 shadow-lg hover:shadow-xl transition-shadow duration-300 mb-8 border border-gray-200 dark:border-gray-700">
                <form action="discount" method="post">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                        <div class="space-y-2 w-full">
                            <label for="articlePrice" class="text-lg font-medium text-gray-700 dark:text-gray-300">Article Price (RM)</label>
                            <input type="text" name="articlePrice" id="articlePrice" value="<?php if(isset($_POST['articlePrice'])) echo $_POST['articlePrice']; ?>"
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:outline-none
                                    focus:ring-2 focus:ring-red-500 hover:border-red-500 focus:border-red-500
                                    dark:bg-gray-700 dark:text-white transition duration-200" />
                        </div>
                        <div class="space-y-2 w-full">
                            <label for="priceCode" class="text-lg font-medium text-gray-700 dark:text-gray-300">Price Code</label>
                            <select name="priceCode" id="priceCode"
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:outline-none
                                    focus:ring-2 focus:ring-red-500 hover:border-red-500 focus:border-red-500
                                    dark:bg-gray-700 dark:text-white transition duration-200">
                                <option value="">Select discount code</option>
                                <option value="h">H - 50% off</option>
                                <option value="f">F - 40% off</option>
                                <option value="q">Q - 33% off</option>
                                <option value="t">T - 25% off</option>
                                <option value="z">Z - No discount</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" name="submit" 
                        class="px-6 py-3 bg-gradient-to-r from-red-600 to-rose-900 
                            text-white font-semibold rounded-lg shadow-md hover:from-red-700 
                            hover:to-rose-900 focus:outline-none focus:ring-2 focus:ring-red-500 
                            focus:ring-opacity-75 hover:scale-[1.02] transition-transform duration-300">
                        <i class="fas fa-calculator mr-2"></i> Calculate Discount
                    </button>
                </form>
            </section>

            <?php if(isset($_REQUEST['submit'])): ?>
                <section class="bg-white dark:bg-gray-800 rounded-xl p-8 shadow-lg hover:shadow-xl transition-shadow duration-300 mb-8 border border-gray-200 dark:border-gray-700">
                <?php
                    if(isset($_REQUEST['submit'])){
                        $errors = array();

                        // Validate article price
                        if (!empty($_POST['articlePrice'])) {
                            if (is_numeric($_POST['articlePrice'])) {
                                $articlePrice = $_POST['articlePrice'];
                            } else {
                                $articlePrice = NULL;
                                $errors[] = '<div class="p-4 bg-red-100 dark:bg-red-900 rounded-lg"><p class="text-red-700 dark:text-red-100 font-bold"><i class="fas fa-exclamation-circle mr-2"></i>You must enter your article price in numeric only!</p></div>';
                            }
                        } else {
                            $articlePrice = NULL;
                            $errors[] = '<div class="p-4 bg-red-100 dark:bg-red-900 rounded-lg"><p class="text-red-700 dark:text-red-100 font-bold"><i class="fas fa-exclamation-circle mr-2"></i>You forgot to enter your article price!</p></div>';
                        }

                        // Validate pricing code
                        if (!empty($_POST['priceCode'])) {
                            if (!is_numeric($_POST['priceCode'])) {
                                if (in_array(strtolower($_POST['priceCode']), array("h", "f", "q", "t", "z"))){
                                    $priceCode = strtolower($_POST['priceCode']);
                                } else {
                                    $priceCode = NULL;
                                    $errors[] = '<div class="p-4 bg-red-100 dark:bg-red-900 rounded-lg"><p class="text-red-700 dark:text-red-100 font-bold"><i class="fas fa-exclamation-circle mr-2"></i>Invalid discount code</p></div>';
                                }
                            } else {
                                $priceCode = NULL;
                                $errors[] = '<div class="p-4 bg-red-100 dark:bg-red-900 rounded-lg"><p class="text-red-700 dark:text-red-100 font-bold"><i class="fas fa-exclamation-circle mr-2"></i>You must enter your pricing code in letters only!</p></div>';
                            }
                        } else {
                            $priceCode = NULL;
                            $errors[] = '<div class="p-4 bg-red-100 dark:bg-red-900 rounded-lg"><p class="text-red-700 dark:text-red-100 font-bold"><i class="fas fa-exclamation-circle mr-2"></i>You forgot to enter your pricing code!</p></div>';
                        }

                        // If there are no errors perform calculation
                        if (empty($errors)){ 
                            $priceCodeArr = array('h' => 50, 'f' => 40, 'q' => 33, 't' => 25, 'z' => 0);
                            $discountPercentage = $priceCodeArr[$priceCode];
                            
                            function Calculate($articlePrice, $discountPercentage){
                                $total = $articlePrice - ($discountPercentage / 100 * $articlePrice);
                                $total = number_format($total, 2);
                                return '
                                <div class="p-4 bg-green-100 dark:bg-green-900 rounded-lg">
                                    <p class="text-green-700 dark:text-green-100">
                                        <span class="text-xl font-bold block mb-2">Calculation Results</span>
                                        <span class="block">Article Price: RM ' . htmlspecialchars($articlePrice) . '</span>
                                        <span class="block">Discount Applied: ' . htmlspecialchars($discountPercentage) . '%</span>
                                        <span class="block font-bold mt-2">Final Price: RM ' . htmlspecialchars($total) . '</span>
                                    </p>
                                </div>';
                            }
                            
                            echo Calculate($articlePrice, $discountPercentage);
                            
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

            <!-- Pricing Code Reference Table -->
            <section class="bg-white dark:bg-gray-800 rounded-xl p-8 shadow-lg hover:shadow-xl transition-shadow duration-300 mb-8 border border-gray-200 dark:border-gray-700">
                <h2 class="text-3xl font-bold mb-4 text-red-600 dark:text-red-400">Discount Code Reference</h2>
                <!-- Desktop Table View -->
                <div class="hidden sm:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead>
                            <tr class="bg-gradient-to-r from-red-50 to-red-100 dark:from-gray-700 dark:to-gray-700">
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">
                                    <i class="fas fa-barcode mr-2"></i>Code
                                </th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">
                                    <i class="fas fa-percent mr-2"></i>Discount
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            <?php
                            $discounts = [
                                ['code' => 'H', 'discount' => '50%'],
                                ['code' => 'F', 'discount' => '40%'],
                                ['code' => 'Q', 'discount' => '33%'],
                                ['code' => 'T', 'discount' => '25%'],
                                ['code' => 'Z', 'discount' => '0%']
                            ];
                            ?>
                            <?php foreach ($discounts as $discount): ?>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-300"><?= $discount['code'] ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-300"><?= $discount['discount'] ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Mobile List View -->
                <div class="block sm:hidden space-y-4">
                    <?php foreach ($discounts as $discount): ?>
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow transform hover:scale-[1.01] transition-all duration-200">
                            <div class="flex items-center gap-4 mb-2 sm:mb-0">
                                <span class="w-8 h-8 flex items-center justify-center bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-400 rounded-full font-bold"><?= $discount['code'] ?></span>
                                <h3 class="font-semibold text-gray-800 dark:text-gray-200">Discount</h3>
                            </div>
                            <div class="text-lg font-bold text-red-600 dark:text-red-400"><?= $discount['discount'] ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </section>
        </div>
    </div>
    <script src="<?= $baseUrl ?>public/js/darkMode.js" defer></script>
    <script src="<?= $baseUrl ?>public/js/mobileNav.js" defer></script>
    <script src="<?= $baseUrl ?>public/js/all.min.js"></script>
    <script src="<?= $baseUrl ?>public/js/accordion.js" defer></script>
</body>
</html>
