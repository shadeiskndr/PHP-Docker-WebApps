<?php
require_once __DIR__ . '/../includes/db.php';

$db = Database::getInstance();
$pdo = $db->getConnection();

$table_name = "inventorybaju";

$queryCategory = $pdo->query("SELECT DISTINCT category FROM $table_name");
$queryProduct = $pdo->query("SELECT DISTINCT product FROM $table_name");
$querySize = $pdo->query("SELECT DISTINCT size FROM $table_name");

$errors = [];
$successMessage = "";

// Handle form submission
if (isset($_POST['update'])) {
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

    // If no errors, attempt update
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("UPDATE $table_name SET quantity = ? WHERE category = ? AND product = ? AND size = ?");
            $result = $stmt->execute([
                $_POST['Quantity'],
                $_POST['Category'],
                $_POST['Product'],
                $_POST['Size']
            ]);

            if ($result && $stmt->rowCount() > 0) {
                $successMessage = "Inventory updated successfully!";
            } else {
                $errors[] = "No matching record found to update.";
            }
        } catch (PDOException $e) {
            $errors[] = "Database error: " . htmlspecialchars($e->getMessage());
        }
    }
}

// After form submission logic, fetch all inventory records (updated or initial)
$stmt = $pdo->query("SELECT * FROM $table_name ORDER BY category, product, size");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            <!-- Header Section -->
            <header class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-8 mb-8 border border-gray-200 dark:border-gray-700">
                <div class="text-5xl font-extrabold">
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-pink-600 to-purple-600">
                        Boutique Inventory Update
                    </span>
                </div>
                <p class="mt-4 text-gray-600 dark:text-gray-300 text-xl">Update inventory records</p>
            </header>

            <!-- Form Section -->
            <section class="bg-white dark:bg-gray-800 rounded-xl p-8 shadow-lg hover:shadow-xl transition-shadow duration-300 mb-8 border border-gray-200 dark:border-gray-700">
                <form action="" method="post" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Category Select -->
                        <div class="space-y-2">
                            <label class="text-lg font-medium text-gray-700 dark:text-gray-300">Category</label>
                            <select 
                                name="Category" 
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none 
                                       focus:ring-2 focus:ring-pink-500 hover:border-pink-500 focus:border-pink-500 transition duration-200"
                            >
                                <option value="">Choose a category</option>
                                <?php while ($row = $queryCategory->fetch()): ?>
                                    <option value="<?= htmlspecialchars($row['category']) ?>" <?= (isset($_POST['Category']) && $_POST['Category'] == $row['category']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($row['category']) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <!-- Product Select -->
                        <div class="space-y-2">
                            <label class="text-lg font-medium text-gray-700 dark:text-gray-300">Product</label>
                            <select 
                                name="Product" 
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none 
                                       focus:ring-2 focus:ring-pink-500 hover:border-pink-500 focus:border-pink-500 transition duration-200"
                            >
                                <option value="">Choose a product</option>
                                <?php while ($row = $queryProduct->fetch()): ?>
                                    <option value="<?= htmlspecialchars($row['product']) ?>" <?= (isset($_POST['Product']) && $_POST['Product'] == $row['product']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($row['product']) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <!-- Size Select -->
                        <div class="space-y-2">
                            <label class="text-lg font-medium text-gray-700 dark:text-gray-300">Size</label>
                            <select 
                                name="Size" 
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none 
                                       focus:ring-2 focus:ring-pink-500 hover:border-pink-500 focus:border-pink-500 transition duration-200"
                            >
                                <option value="">Choose a size</option>
                                <?php while ($row = $querySize->fetch()): ?>
                                    <option value="<?= htmlspecialchars($row['size']) ?>" <?= (isset($_POST['Size']) && $_POST['Size'] == $row['size']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($row['size']) ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <!-- Quantity Input -->
                        <div class="space-y-2">
                            <label class="text-lg font-medium text-gray-700 dark:text-gray-300">Quantity</label>
                            <input 
                                type="text" 
                                name="Quantity" 
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:outline-none 
                                       focus:ring-2 focus:ring-pink-500 hover:border-pink-500 focus:border-pink-500 transition duration-200"
                                value="<?= isset($_POST['Quantity']) ? htmlspecialchars($_POST['Quantity']) : '' ?>"
                            >
                        </div>
                    </div>

                    <button 
                        type="submit" 
                        name="update" 
                        class="w-full md:w-auto px-6 py-3 bg-gradient-to-r from-pink-600 to-purple-600 text-white font-semibold rounded-lg shadow-md hover:from-pink-700 hover:to-purple-700 focus:outline-none 
                               focus:ring-2 focus:ring-purple-500 focus:ring-opacity-75 hover:scale-[1.02] transition-transform duration-300"
                    >
                        Update Inventory
                    </button>
                </form>
            </section>

            <!-- Feedback Section -->
            <?php if (!empty($errors)): ?>
                <section class="bg-white dark:bg-gray-800 rounded-xl p-8 mb-8 border border-gray-200 dark:border-gray-700">
                    <div class="space-y-4">
                        <?php foreach ($errors as $error): ?>
                            <div class="p-4 bg-red-100 dark:bg-red-900 rounded-lg">
                                <p class="text-red-700 dark:text-red-100 font-bold">
                                    <i class="fas fa-exclamation-circle mr-2"></i><?= htmlspecialchars($error) ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php elseif (!empty($successMessage)): ?>
                <section class="bg-white dark:bg-gray-800 rounded-xl p-8 mb-8 border border-gray-200 dark:border-gray-700">
                    <div class="p-4 bg-green-100 dark:bg-green-900 rounded-lg">
                        <p class="text-green-700 dark:text-green-100">
                            <span class="text-xl font-bold block mb-2"><i class="fas fa-check-circle mr-2"></i>Success!</span>
                            <span class="block"><?= htmlspecialchars($successMessage) ?></span>
                        </p>
                    </div>
                </section>
            <?php endif; ?>

            <!-- Unified Inventory Display -->
            <section class="bg-white dark:bg-gray-800 rounded-xl p-8 shadow-lg hover:shadow-xl transition-shadow duration-300 mb-8 border border-gray-200 dark:border-gray-700">
                <h2 class="text-2xl font-bold mb-4">Current Inventory</h2>
                <div class="hidden sm:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead>
                            <tr class="bg-gray-50 dark:bg-gray-700">
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"><i class="fas fa-tags mr-1"></i>Category</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider"><i class="fas fa-tshirt mr-1"></i>Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <i class="fas fa-ruler-combined mr-1"></i>Size
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <i class="fas fa-cubes mr-1"></i>Quantity
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <?php foreach ($rows as $row): ?>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($row['category']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($row['product']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($row['size']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($row['quantity']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="block sm:hidden space-y-4">
                <?php foreach ($rows as $row): ?>
                    <div class="flex flex-col bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 p-6 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-300">
                        <!-- Category Section -->
                        <div class="flex items-center justify-between mb-4 border-b dark:border-gray-600 pb-3">
                            <div class="flex items-center text-pink-600 dark:text-pink-400 font-semibold">
                                <i class="fas fa-tags text-lg mr-3"></i>
                                <span class="text-sm uppercase tracking-wider">Category</span>
                            </div>
                            <div class="text-gray-800 dark:text-gray-200 font-medium"><?= htmlspecialchars($row['category']) ?></div>
                        </div>

                        <!-- Product Section -->
                        <div class="flex items-center justify-between mb-4 border-b dark:border-gray-600 pb-3">
                            <div class="flex items-center text-pink-600 dark:text-pink-400 font-semibold">
                                <i class="fas fa-tshirt text-lg mr-3"></i>
                                <span class="text-sm uppercase tracking-wider">Product</span>
                            </div>
                            <div class="text-gray-800 dark:text-gray-200 font-medium"><?= htmlspecialchars($row['product']) ?></div>
                        </div>

                        <!-- Size Section -->
                        <div class="flex items-center justify-between mb-4 border-b dark:border-gray-600 pb-3">
                            <div class="flex items-center text-pink-600 dark:text-pink-400 font-semibold">
                                <i class="fas fa-ruler-combined text-lg mr-3"></i>
                                <span class="text-sm uppercase tracking-wider">Size</span>
                            </div>
                            <div class="text-gray-800 dark:text-gray-200 font-medium"><?= htmlspecialchars($row['size']) ?></div>
                        </div>

                        <!-- Quantity Section -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center text-pink-600 dark:text-pink-400 font-semibold">
                                <i class="fas fa-cubes text-lg mr-3"></i>
                                <span class="text-sm uppercase tracking-wider">Quantity</span>
                            </div>
                            <div class="text-gray-800 dark:text-gray-200 font-medium"><?= htmlspecialchars($row['quantity']) ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <script src="<?= $baseUrl ?>public/js/darkMode.js" defer></script>
        <script src="<?= $baseUrl ?>public/js/mobileNav.js" defer></script>
        <script src="<?= $baseUrl ?>public/js/all.min.js"></script>
        <script src="<?= $baseUrl ?>public/js/accordion.js" defer></script>
    </div>
</div>
</body>
</html>