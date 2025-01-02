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
        <?php include '../includes/navigation.php'; ?>

        <div class="flex-1 p-6 sm:ml-64">
            <header class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-8 mb-8 transform hover:scale-[1.02] transition-transform duration-300">
                <h1 class="text-5xl font-extrabold bg-gradient-to-r from-pink-600 to-purple-600 bg-clip-text text-transparent">Puteri Clothes Calculator</h1>
                <p class="mt-4 text-gray-600 dark:text-gray-300 text-xl">Calculate prices for traditional Malaysian clothing</p>
            </header>

            <!-- Calculator Form Section -->
            <section class="bg-white dark:bg-gray-800 rounded-xl p-8 shadow-lg hover:shadow-xl transition-shadow duration-300 mb-8">
                <form action="puteriClothes.php" method="post" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label for="code" class="text-lg font-medium text-gray-700 dark:text-gray-300">Clothes Code</label>
                            <input type="text" name="code" id="code" 
                                   class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 
                                          focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white
                                          transition duration-200" />
                        </div>
                        <div class="space-y-2">
                            <label for="quantity" class="text-lg font-medium text-gray-700 dark:text-gray-300">Quantity</label>
                            <input type="text" name="quantity" id="quantity" 
                                   class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 
                                          focus:ring-2 focus:ring-purple-500 dark:bg-gray-700 dark:text-white
                                          transition duration-200" />
                        </div>
                    </div>
                    <button type="submit" name="submit" 
                            class="w-full md:w-auto px-6 py-3 bg-gradient-to-r from-pink-600 to-purple-600 
                                   text-white font-semibold rounded-lg shadow-md hover:from-pink-700 
                                   hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 
                                   focus:ring-opacity-75 transition duration-200">
                        Calculate Price
                    </button>
                </form>
            </section>

            <!-- Results Section -->
            <?php if(isset($_REQUEST['submit'])): ?>
                <section class="bg-white dark:bg-gray-800 rounded-xl p-8 shadow-lg hover:shadow-xl transition-shadow duration-300 mb-8">
                    <?php
                    if (!empty($_POST['code'])) {
                        if (is_numeric($_POST['code'])) {
                            if ($_POST['code'] > 4 || $_POST['code'] < 1) {
                                $code = NULL;
                                echo "<div class='p-4 bg-red-100 dark:bg-red-900 rounded-lg mb-4'>
                                        <p class='text-red-700 dark:text-red-100 font-semibold'>The code is invalid!</p>
                                    </div>";
                            } else {
                                $code = $_POST['code'];
                            }
                        } else {
                            $code = NULL;
                            echo "<div class='p-4 bg-red-100 dark:bg-red-900 rounded-lg mb-4'>
                                    <p class='text-red-700 dark:text-red-100 font-semibold'>You must enter your clothes' code in numeric only!</p>
                                </div>";
                        }
                    } else {
                        $code = NULL;
                        echo "<div class='p-4 bg-red-100 dark:bg-red-900 rounded-lg mb-4'>
                                <p class='text-red-700 dark:text-red-100 font-semibold'>You forgot to enter your clothes' code!</p>
                            </div>";
                    }

                    // Quantity validation with styled messages
                    if (!empty($_POST['quantity'])) {
                        if (is_numeric($_POST['quantity'])) {
                            if ($_POST['quantity'] < 1) {
                                $quantity = NULL;
                                echo "<div class='p-4 bg-red-100 dark:bg-red-900 rounded-lg mb-4'>
                                        <p class='text-red-700 dark:text-red-100 font-semibold'>The quantity is invalid!</p>
                                    </div>";
                            } else {
                                $quantity = $_POST['quantity'];
                            }
                        } else {
                            $quantity = NULL;
                            echo "<div class='p-4 bg-red-100 dark:bg-red-900 rounded-lg mb-4'>
                                    <p class='text-red-700 dark:text-red-100 font-semibold'>You must enter your quantity in numeric only!</p>
                                </div>";
                        }
                    } else {
                        $quantity = NULL;
                        echo "<div class='p-4 bg-red-100 dark:bg-red-900 rounded-lg mb-4'>
                                <p class='text-red-700 dark:text-red-100 font-semibold'>You forgot to enter your quantity!</p>
                            </div>";
                    }

                    $codeArr = array(1 => 210.9, 2 => 249.0, 3 => 310.9, 4 => 250.9);

                    if ($code && $quantity) {
                        $price = $codeArr[$code] * $quantity;
                        echo "<div class='space-y-4'>";
                        echo "<p class='text-xl text-gray-700 dark:text-gray-300'>Initial price calculation: <span class='font-semibold'>RM$price</span></p>";

                        switch (true) {
                            case $price > 500.0:
                                $price = $price - (10 / 100 * $price);
                                $price = number_format($price, 2);
                                echo "<div class='p-4 bg-green-100 dark:bg-green-900 rounded-lg'>
                                        <p class='text-green-700 dark:text-green-100'>
                                            <span class='font-bold'>10% Discount Applied!</span><br>
                                            Purchase exceeds RM500.00<br>
                                            Final price: <span class='font-bold'>RM$price</span>
                                        </p>
                                    </div>";
                                break;
                            case $price < 500.0:
                                $price = number_format($price, 2);
                                echo "<div class='p-4 bg-blue-100 dark:bg-blue-900 rounded-lg'>
                                        <p class='text-blue-700 dark:text-blue-100'>
                                            <span class='font-bold'>No Discount Applied</span><br>
                                            Purchase below RM500.00<br>
                                            Final price: <span class='font-bold'>RM$price</span>
                                        </p>
                                    </div>";
                        }
                        echo "</div>";
                    } else {
                        echo "<div class='p-4 bg-yellow-100 dark:bg-yellow-900 rounded-lg'>
                                <p class='text-yellow-700 dark:text-yellow-100 font-semibold'>Please fill out all fields correctly and try again.</p>
                            </div>";
                    }
                    ?>
                </section>
            <?php endif; ?>


            <!-- Price Table Section -->
            <section class="bg-white dark:bg-gray-800 rounded-xl p-8 shadow-lg hover:shadow-xl transition-shadow duration-300">
                <h2 class="text-3xl font-bold mb-6 bg-gradient-to-r from-pink-600 to-purple-600 bg-clip-text text-transparent">Clothes Pricing Guide</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead>
                            <tr class="bg-gradient-to-r from-pink-50 to-purple-50 dark:from-gray-700 dark:to-gray-700">
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">Code</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">Description</th>
                                <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700 dark:text-gray-200 uppercase tracking-wider">Price (RM)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-300">1</td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-300">Baju Kurung Tradisional</td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-300">210.90</td>
                            </tr>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-300">2</td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-300">Baju Kurung Moden</td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-300">249.00</td>
                            </tr>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-300">3</td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-300">Kebaya</td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-300">310.90</td>
                            </tr>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-300">4</td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-300">Jubah</td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-700 dark:text-gray-300">250.90</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    </div>
    <script src="<?= $baseUrl ?>public/js/darkMode.js" defer></script>
    <script src="<?= $baseUrl ?>public/js/mobileNav.js" defer></script>
    <script src="<?= $baseUrl ?>public/js/all.min.js"></script>
</body>
</html>
