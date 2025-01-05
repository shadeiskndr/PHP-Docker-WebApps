<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../output.css" rel="stylesheet">
    <title>Puteri Clothes Calculator</title>
</head>
<body class="bg-gradient-to-br from-pink-50 to-purple-50 dark:from-gray-800 dark:to-gray-900 text-gray-900 dark:text-gray-100 min-h-screen transition-colors duration-200">
    <div class="flex min-h-screen">
        <?php include BASE_PATH . '/includes/navigation.php'; ?>

        <div class="flex-1 p-6 sm:ml-64">
            <header class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-8 mb-8 border border-gray-200 dark:border-gray-700">
                <h1 class="text-5xl font-extrabold bg-gradient-to-r from-pink-600 to-purple-600 bg-clip-text text-transparent">Puteri Clothes Calculator</h1>
                <p class="mt-4 text-gray-600 dark:text-gray-300 text-xl">Calculate prices for traditional Malaysian clothing</p>
            </header>

            <!-- Calculator Form Section -->
            <section class="bg-white dark:bg-gray-800 rounded-xl p-8 shadow-lg hover:shadow-xl transition-shadow duration-300 mb-8 border border-gray-200 dark:border-gray-700">
                <form action="puteri" method="post" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="code" class="text-lg font-medium text-gray-700 dark:text-gray-300">Clothes Code</label>
                            <input type="text" name="code" id="code" 
                                    class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:outline-none
                                          focus:ring-2 focus:ring-purple-500 hover:border-purple-500 focus:border-purple-500
                                        dark:bg-gray-700 dark:text-white transition duration-200" />
                        </div>
                        <div class="space-y-2">
                            <label for="quantity" class="text-lg font-medium text-gray-700 dark:text-gray-300">Quantity</label>
                            <input type="text" name="quantity" id="quantity" 
                                   class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:outline-none
                                          focus:ring-2 focus:ring-purple-500 hover:border-purple-500 focus:border-purple-500
                                        dark:bg-gray-700 dark:text-white transition duration-200" />
                        </div>
                    </div>
                    <button type="submit" name="submit" 
                            class="w-full md:w-auto px-6 py-3 bg-gradient-to-r from-pink-600 to-purple-600 
                                   text-white font-semibold rounded-lg shadow-md hover:from-pink-700 
                                   hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 
                                   focus:ring-opacity-75 hover:scale-[1.02] transition-transform duration-300">
                        <i class="fas fa-calculator mr-2"></i> Calculate Price
                    </button>
                </form>
            </section>

            <!-- Results Section -->
            <?php if(isset($_REQUEST['submit'])): ?>
                <section class="bg-white dark:bg-gray-800 rounded-xl p-8 shadow-lg hover:shadow-xl transition-shadow duration-300 mb-8 border border-gray-200 dark:border-gray-700">
                    <?php
                    // Initialize variables and error array
                    $code = null;
                    $quantity = null;
                    $errors = [];

                    // Validate code input
                    if (empty($_POST['code'])) {
                        $errors[] = "You forgot to enter your clothes' code!";
                    } elseif (!is_numeric($_POST['code'])) {
                        $errors[] = "You must enter your clothes' code in numeric only!";
                    } elseif ($_POST['code'] < 1 || $_POST['code'] > 4) {
                        $errors[] = "The code is invalid!";
                    } else {
                        $code = $_POST['code'];
                    }

                    // Validate quantity input
                    if (empty($_POST['quantity'])) {
                        $errors[] = "You forgot to enter your quantity!";
                    } elseif (!is_numeric($_POST['quantity'])) {
                        $errors[] = "You must enter your quantity in numeric only!";
                    } elseif ($_POST['quantity'] < 1) {
                        $errors[] = "The quantity is invalid!";
                    } else {
                        $quantity = $_POST['quantity'];
                    }

                    // Display errors if any
                    if (!empty($errors)) {
                        foreach ($errors as $error) {
                            echo "<div class='p-4 bg-red-100 dark:bg-red-900 rounded-lg mb-4'>
                                    <p class='text-red-700 dark:text-red-100 font-semibold'>{$error}</p>
                                </div>";
                        }
                        echo "<div class='p-4 bg-yellow-100 dark:bg-yellow-900 rounded-lg'>
                                <p class='text-yellow-700 dark:text-yellow-100 font-semibold'>Please fill out all fields correctly and try again.</p>
                            </div>";
                    } else {
                        // Array of prices corresponding to codes
                        $codeArr = [1 => 210.9, 2 => 249.0, 3 => 310.9, 4 => 250.9];
                        $initialPrice = $codeArr[$code] * $quantity;
                        $formattedInitialPrice = number_format($initialPrice, 2);

                        echo "<div class='space-y-4'>";
                        echo "<p class='text-xl text-gray-700 dark:text-gray-300'>Initial price calculation: <span class='font-semibold'>RM{$formattedInitialPrice}</span></p>";

                        // Discount calculation
                        if ($initialPrice > 500.0) {
                            $discountedPrice = $initialPrice * 0.9;
                            $formattedDiscountedPrice = number_format($discountedPrice, 2);
                            echo "<div class='p-4 bg-green-100 dark:bg-green-900 rounded-lg'>
                                    <p class='text-green-700 dark:text-green-100'>
                                        <span class='font-bold'>10% Discount Applied!</span><br>
                                        Purchase exceeds RM500.00<br>
                                        Final price: <span class='font-bold'>RM{$formattedDiscountedPrice}</span>
                                    </p>
                                </div>";
                        } else {
                            echo "<div class='p-4 bg-blue-100 dark:bg-blue-900 rounded-lg'>
                                    <p class='text-blue-700 dark:text-blue-100'>
                                        <span class='font-bold'>No Discount Applied</span><br>
                                        Purchase below RM500.00<br>
                                        Final price: <span class='font-bold'>RM{$formattedInitialPrice}</span>
                                    </p>
                                </div>";
                        }
                        echo "</div>";
                    }
                    ?>
                </section>
            <?php endif; ?>

            <!-- Price Table Section -->
            <section class="bg-white dark:bg-gray-800 rounded-xl p-8 shadow-lg hover:shadow-xl transition-shadow duration-300 border border-gray-200 dark:border-gray-700">
                <h2 class="text-3xl font-bold mb-6 bg-gradient-to-r from-pink-600 to-purple-600 bg-clip-text text-transparent">Clothes Pricing Guide</h2>
                
                <?php
                $items = [
                    ['code' => 1, 'name' => 'Baju Kurung Tradisional', 'price' => 210.90],
                    ['code' => 2, 'name' => 'Baju Kurung Moden', 'price' => 249.00],
                    ['code' => 3, 'name' => 'Kebaya', 'price' => 310.90],
                    ['code' => 4, 'name' => 'Jubah', 'price' => 250.90]
                ];
                ?>

                <!-- Desktop Table View -->
                <div class="hidden sm:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead>
                            <tr class="bg-gradient-to-r from-pink-50 to-purple-50 dark:from-gray-700 dark:to-gray-700">
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">
                                    <i class="fas fa-barcode mr-2"></i>Code
                                </th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">
                                    <i class="fas fa-tag mr-2"></i>Description
                                </th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">
                                    <i class="fas fa-money-bill-wave mr-2"></i>Price (RM)
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            <?php foreach ($items as $item): ?>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-300"><?= $item['code'] ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-300"><?= $item['name'] ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-300"><?= number_format($item['price'], 2) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Mobile List View -->
                <div class="block sm:hidden space-y-4">
                    <?php foreach ($items as $item): ?>
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow transform hover:scale-[1.01] transition-all duration-200">
                            <div class="flex items-center gap-4 mb-2 sm:mb-0">
                                <span class="w-8 h-8 flex items-center justify-center bg-pink-100 dark:bg-pink-900 text-pink-600 dark:text-pink-400 rounded-full font-bold"><?= $item['code'] ?></span>
                                <h3 class="font-semibold text-gray-800 dark:text-gray-200"><?= $item['name'] ?></h3>
                            </div>
                            <div class="text-lg font-bold text-purple-600 dark:text-purple-400">RM <?= number_format($item['price'], 2) ?></div>
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
