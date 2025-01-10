<?php
require_once __DIR__ . '/../includes/db.php';

$db = Database::getInstance();
$pdo = $db->getConnection();

$table_name = "inventorybaju";
$queryCategory = $pdo->query("SELECT DISTINCT category FROM inventorybaju");
$queryProduct = $pdo->query("SELECT DISTINCT product FROM inventorybaju");
$querySize = $pdo->query("SELECT DISTINCT size FROM inventorybaju");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/output.css" rel="stylesheet">
    <title>Mawar Boutique Inventory Update</title>
</head>
<body class="bg-gradient-to-br from-pink-200 to-purple-200 dark:from-gray-800 dark:to-gray-900 text-gray-900 dark:text-gray-100 min-h-screen transition-colors duration-200">
    <div class="flex min-h-screen">
        <?php include BASE_PATH . '/includes/navigation.php'; ?>

        <div class="flex-1 p-6 sm:ml-64">
            <header class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-8 mb-8 border border-gray-200 dark:border-gray-700">
                <h1 class="text-5xl font-extrabold bg-gradient-to-r from-pink-600 to-purple-600 bg-clip-text text-transparent">Boutique Inventory Update</h1>
                <p class="mt-4 text-gray-600 dark:text-gray-300 text-xl">Update inventory records</p>
            </header>

            <!-- Form Section -->
            <section class="bg-white dark:bg-gray-800 rounded-xl p-8 shadow-lg hover:shadow-xl transition-shadow duration-300 mb-8 border border-gray-200 dark:border-gray-700">
                <form action="boutique" method="post" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Category Select -->
                        <div class="space-y-2">
                            <label class="text-lg font-medium text-gray-700 dark:text-gray-300">Category</label>
                            <select name="Category" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:bg-gray-700">
                                <option value="">Choose a category</option>
                                <?php while ($row = $queryCategory->fetch()): ?>
                                    <option value="<?= htmlspecialchars($row['category']) ?>"><?= htmlspecialchars($row['category']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <!-- Product Select -->
                        <div class="space-y-2">
                            <label class="text-lg font-medium text-gray-700 dark:text-gray-300">Product</label>
                            <select name="Product" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:bg-gray-700">
                                <option value="">Choose a product</option>
                                <?php while ($row = $queryProduct->fetch()): ?>
                                    <option value="<?= htmlspecialchars($row['product']) ?>"><?= htmlspecialchars($row['product']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <!-- Size Select -->
                        <div class="space-y-2">
                            <label class="text-lg font-medium text-gray-700 dark:text-gray-300">Size</label>
                            <select name="Size" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:bg-gray-700">
                                <option value="">Choose a size</option>
                                <?php while ($row = $querySize->fetch()): ?>
                                    <option value="<?= htmlspecialchars($row['size']) ?>"><?= htmlspecialchars($row['size']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <!-- Quantity Input -->
                        <div class="space-y-2">
                            <label class="text-lg font-medium text-gray-700 dark:text-gray-300">Quantity</label>
                            <input type="text" name="Quantity" class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-pink-500 dark:bg-gray-700" value="<?= isset($_POST['Quantity']) ? htmlspecialchars($_POST['Quantity']) : '' ?>">
                        </div>
                    </div>

                    <button type="submit" name="update" class="w-full md:w-auto px-6 py-3 bg-gradient-to-r from-pink-600 to-purple-600 text-white font-semibold rounded-lg shadow-md hover:from-pink-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-opacity-75 hover:scale-[1.02] transition-transform duration-300">
                        Update Inventory
                    </button>
                </form>
            </section>

            <!-- Results Section -->
            <?php if(isset($_POST['update'])): ?>
                <section class="bg-white dark:bg-gray-800 rounded-xl p-8 shadow-lg hover:shadow-xl transition-shadow duration-300 mb-8 border border-gray-200 dark:border-gray-700">
                    <?php
                    $errors = [];

                    // Validate inputs
                    if (empty($_POST['Category'])) {
                        $errors[] = "Category is required";
                    }
                    if (empty($_POST['Product'])) {
                        $errors[] = "Product is required";
                    }
                    if (empty($_POST['Size'])) {
                        $errors[] = "Size is required";
                    }
                    if (!is_numeric($_POST['Quantity']) || $_POST['Quantity'] < 0) {
                        $errors[] = "Quantity must be a positive number";
                    }

                    if (empty($errors)) {
                        try {
                            $stmt = $pdo->prepare("UPDATE inventorybaju SET quantity = ? WHERE category = ? AND product = ? AND size = ?");
                            $result = $stmt->execute([
                                $_POST['Quantity'],
                                $_POST['Category'],
                                $_POST['Product'],
                                $_POST['Size']
                            ]);

                            if ($result && $stmt->rowCount() > 0) {
                                echo '<div class="p-4 bg-green-100 dark:bg-green-900 rounded-lg">
                                        <p class="text-green-700 dark:text-green-100">Inventory updated successfully!</p>
                                      </div>';
                                
                                // Display updated inventory
                                $stmt = $pdo->query("SELECT * FROM inventorybaju ORDER BY category, product, size");
                                echo '<div class="mt-6">
                                        <h2 class="text-2xl font-bold mb-4">Current Inventory</h2>
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                                <thead>
                                                    <tr class="bg-gray-50 dark:bg-gray-700">
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Category</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Product</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Size</th>
                                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Quantity</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">';
                                while ($row = $stmt->fetch()) {
                                    echo "<tr>
                                            <td class='px-6 py-4 whitespace-nowrap'>{$row['category']}</td>
                                            <td class='px-6 py-4 whitespace-nowrap'>{$row['product']}</td>
                                            <td class='px-6 py-4 whitespace-nowrap'>{$row['size']}</td>
                                            <td class='px-6 py-4 whitespace-nowrap'>{$row['quantity']}</td>
                                          </tr>";
                                }
                                echo '</tbody></table></div></div>';
                            } else {
                                echo '<div class="p-4 bg-yellow-100 dark:bg-yellow-900 rounded-lg">
                                        <p class="text-yellow-700 dark:text-yellow-100">No matching record found to update.</p>
                                      </div>';
                            }
                        } catch (PDOException $e) {
                            echo '<div class="p-4 bg-red-100 dark:bg-red-900 rounded-lg">
                                    <p class="text-red-700 dark:text-red-100">Database error: ' . htmlspecialchars($e->getMessage()) . '</p>
                                  </div>';
                        }
                    } else {
                        echo '<div class="p-4 bg-red-100 dark:bg-red-900 rounded-lg">
                                <p class="text-red-700 dark:text-red-100">Please correct the following errors:</p>
                                <ul class="list-disc list-inside mt-2">';
                        foreach ($errors as $error) {
                            echo '<li>' . htmlspecialchars($error) . '</li>';
                        }
                        echo '</ul></div>';
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
