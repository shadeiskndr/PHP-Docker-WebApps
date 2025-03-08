<?php
require_once __DIR__ . '/../../includes/db.php';

$db = Database::getInstance();
$pdo = $db->getConnection();

$table_name = "inventorybaju";

$queryCategory = $pdo->query("SELECT DISTINCT category FROM $table_name");
$queryProduct = $pdo->query("SELECT DISTINCT product FROM $table_name");
$querySize = $pdo->query("SELECT DISTINCT size FROM $table_name");

$errors = [];
$successMessage = "";

// Determine if search is active
$isSearchActive = isset($_POST['search']) && (
    !empty($_POST['Category']) || 
    !empty($_POST['Product']) || 
    !empty($_POST['Size'])
);

// Pagination settings - only applied when search is not active
$recordsPerPage = 5;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($page - 1) * $recordsPerPage;

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

// Build a base SQL for counting total records
$countSql = "SELECT COUNT(*) FROM $table_name WHERE 1=1";
$countParams = [];

// Build a base SELECT for displaying inventory
$sql = "SELECT * FROM $table_name WHERE 1=1";
$params = [];

if (isset($_POST['search'])) {
    if (!empty($_POST['Category'])) {
        $sql .= " AND category = ?";
        $countSql .= " AND category = ?";
        $params[] = $_POST['Category'];
        $countParams[] = $_POST['Category'];
    }
    if (!empty($_POST['Product'])) {
        $sql .= " AND product = ?";
        $countSql .= " AND product = ?";
        $params[] = $_POST['Product'];
        $countParams[] = $_POST['Product'];
    }
    if (!empty($_POST['Size'])) {
        $sql .= " AND size = ?";
        $countSql .= " AND size = ?";
        $params[] = $_POST['Size'];
        $countParams[] = $_POST['Size'];
    }
}

// Count total records for pagination or info display
$countStmt = $pdo->prepare($countSql);
$countStmt->execute($countParams);
$totalRecords = $countStmt->fetchColumn();

// Apply sorting and pagination only if search is not active
$sql .= " ORDER BY category, product, size";

if (!$isSearchActive) {
    $totalPages = ceil($totalRecords / $recordsPerPage);
    $sql .= " LIMIT $recordsPerPage OFFSET $offset";
} else {
    // When search is active, no pagination, show all results
    $totalPages = 1;
    $page = 1;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/output.css" rel="stylesheet">
    <title>Boutique Inventory Update</title>
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

                    <!-- Keep buttons grouped at the left side with some space between them -->
                    <div class="grid grid-cols-1 md:flex md:flex-row md:justify-start md:items-center gap-y-6 md:gap-x-4 mt-6">
                        <!-- Update Inventory Button -->
                        <button 
                            type="submit" 
                            name="update" 
                            class="w-full md:w-auto flex items-center justify-center px-6 py-3 bg-gradient-to-r from-pink-600 to-purple-600 text-white font-semibold rounded-lg shadow-md 
                                hover:from-pink-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-opacity-75 
                                hover:scale-[1.02] transition-transform duration-300"
                        >
                            <i class="fas fa-save mr-2"></i>
                            Update
                        </button>
                        <!-- Search Button -->
                        <button 
                            type="submit" 
                            name="search"
                            class="w-full md:w-auto flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-lg shadow-md 
                                hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-75 
                                hover:scale-[1.02] transition-transform duration-300"
                        >
                            <i class="fas fa-search mr-2"></i> 
                            Search
                        </button>
                        <button
                            type="button"
                            name="add" 
                            class="w-full md:w-auto flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-lg shadow-md 
                                hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-75 
                                hover:scale-[1.02] transition-transform duration-300"
                        >
                            <i class="fas fa-plus mr-2"></i> 
                            Add Category
                        </button>
                    </div>
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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                <i class="fas fa-cog mr-1"></i>Actions
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
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <button 
                                        data-inventory-id="<?= $row['id'] ?>" 
                                        class="delete-inventory-btn text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-200 transition-colors duration-200">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <!-- Pagination Component -->
                <div class="flex items-center justify-between border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-3 sm:px-6">
                    <?php if (!$isSearchActive): ?>
                        <div class="flex flex-1 justify-between sm:hidden">
                            <a href="?page=<?= max(1, $page - 1) ?>" class="relative inline-flex items-center rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <i class="fas fa-chevron-left mr-2"></i> Previous
                            </a>
                            <a href="?page=<?= min($totalPages, $page + 1) ?>" class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                                Next <i class="fas fa-chevron-right ml-2"></i>
                            </a>
                        </div>
                        
                        <div class="hidden sm:flex sm:flex-1 sm:items-center">
                            <div>
                                <p class="text-sm text-gray-700 dark:text-gray-300">
                                    Showing
                                    <span class="font-medium"><?= ($offset + 1) ?></span>
                                    to
                                    <span class="font-medium"><?= min($offset + $recordsPerPage, $totalRecords) ?></span>
                                    of
                                    <span class="font-medium"><?= $totalRecords ?></span>
                                    results
                                </p>
                            </div>
                            
                            <?php if ($totalPages > 1): ?>
                            <div class="ml-auto">
                                <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                                    <!-- Previous Page Button -->
                                    <a href="?page=<?= max(1, $page - 1) ?>" class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 dark:text-gray-400 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 focus:z-20 focus:outline-offset-0">
                                        <span class="sr-only">Previous</span>
                                        <i class="fas fa-chevron-left size-5"></i>
                                    </a>
                                    
                                    <!-- Page Links -->
                                    <?php 
                                    // Determine which page numbers to show
                                    $startPage = max(1, min($page - 2, $totalPages - 4));
                                    $endPage = min($totalPages, max($page + 2, 5));
                                    
                                    // Show first page if not in range
                                    if ($startPage > 1): ?>
                                        <a href="?page=1" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 dark:text-gray-200 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 focus:z-20 focus:outline-offset-0">1</a>
                                        <?php if ($startPage > 2): ?>
                                            <span class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-400 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 focus:outline-offset-0">...</span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    
                                    <!-- Loop through the page range -->
                                    <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                                        <?php if ($i == $page): ?>
                                            <a href="?page=<?= $i ?>" aria-current="page" class="relative z-10 inline-flex items-center bg-gradient-to-r from-pink-600 to-purple-600 px-4 py-2 text-sm font-semibold text-white focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-purple-600"><?= $i ?></a>
                                        <?php else: ?>
                                            <a href="?page=<?= $i ?>" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 dark:text-gray-200 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 focus:z-20 focus:outline-offset-0"><?= $i ?></a>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                    
                                    <!-- Show last page if not in range -->
                                    <?php if ($endPage < $totalPages): ?>
                                        <?php if ($endPage < $totalPages - 1): ?>
                                            <span class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-700 dark:text-gray-400 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 focus:outline-offset-0">...</span>
                                        <?php endif; ?>
                                        <a href="?page=<?= $totalPages ?>" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 dark:text-gray-200 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 focus:z-20 focus:outline-offset-0"><?= $totalPages ?></a>
                                    <?php endif; ?>
                                    
                                    <!-- Next Page Button -->
                                    <a href="?page=<?= min($totalPages, $page + 1) ?>" class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 dark:text-gray-400 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 focus:z-20 focus:outline-offset-0">
                                        <span class="sr-only">Next</span>
                                        <i class="fas fa-chevron-right size-5"></i>
                                    </a>
                                </nav>
                            </div>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                    <!-- When search is active, show a message instead of pagination -->
                    <div class="w-full text-sm text-gray-700 dark:text-gray-300 py-3">
                        <p>Showing all <?= $totalRecords ?> results matching your search criteria.</p>
                        <a href="/boutique" class="text-blue-600 dark:text-blue-400 hover:underline mt-2 inline-block">
                            <i class="fas fa-undo mr-1"></i> Clear search and return to paginated view
                        </a>
                    </div>
                    <?php endif; ?>
                </div>
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
                        <div class="flex justify-end mt-4 pt-3 border-t dark:border-gray-600">
                            <button 
                                data-inventory-id="<?= $row['id'] ?>" 
                                class="delete-inventory-btn text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-200 transition-colors duration-200">
                                <i class="fas fa-trash fa-lg"></i>
                            </button>
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <!-- Mobile Pagination -->
                <?php if (!$isSearchActive): ?>
                <div class="flex items-center justify-between border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-3 rounded-lg mt-4">
                    <div class="flex flex-1 justify-between">
                        <a href="?page=<?= max(1, $page - 1) ?>" class="relative inline-flex items-center rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                            <i class="fas fa-chevron-left mr-2"></i> Previous
                        </a>
                        <span class="mx-4 flex items-center text-gray-700 dark:text-gray-300">
                            Page <?= $page ?> of <?= $totalPages ?>
                        </span>
                        <a href="?page=<?= min($totalPages, $page + 1) ?>" class="relative inline-flex items-center rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600">
                            Next <i class="fas fa-chevron-right ml-2"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php else: ?>
                <div class="block sm:hidden mt-4">
                    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 text-sm text-gray-700 dark:text-gray-300">
                        <p>Showing all <?= $totalRecords ?> results matching your search criteria.</p>
                        <a href="/boutique" class="text-blue-600 dark:text-blue-400 hover:underline mt-2 inline-block">
                            <i class="fas fa-undo mr-1"></i> Clear search
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </section>
        <script src="<?= $baseUrl ?>public/js/darkMode.js" defer></script>
        <script src="<?= $baseUrl ?>public/js/mobileNav.js" defer></script>
        <script src="<?= $baseUrl ?>public/js/all.min.js"></script>
        <script src="<?= $baseUrl ?>public/js/accordion.js" defer></script>
        <script src="<?= $baseUrl ?>crud/BoutiqueInventory/js/modal.js" defer></script>
        <script src="<?= $baseUrl ?>crud/BoutiqueInventory/js/deleteInventory.js" defer></script>
    </div>

    <!-- Modal -->
    <div id="addCategoryModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-gray-100">Add New Category</h3>
                <div class="mt-4">
                    <form id="addCategoryForm" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category Name</label>
                        <input type="text" name="categoryName" id="categoryName" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Product Name</label>
                        <input type="text" name="productName" id="productName" required
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-pink-500 focus:ring-pink-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    </div>
                        <div class="flex justify-end gap-3">
                            <button type="button" id="closeModal"
                                    class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-500">
                                Cancel
                            </button>
                            <button type="submit"
                                    class="px-4 py-2 bg-gradient-to-r from-pink-600 to-purple-600 text-white rounded-md hover:from-pink-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500">
                                    <i class="fas fa-plus mr-2"></i> Save Category
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

