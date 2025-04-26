<?php
require_once __DIR__ . '/../includes/db.php';

$db = Database::getInstance();
$pdo = $db->getConnection();

$page_title = 'Edit Movie';

// Get genres for dropdown
$genreQuery = $pdo->query("SELECT * FROM genres ORDER BY name");
$genres = $genreQuery->fetchAll(PDO::FETCH_ASSOC);

$errors = [];
$successMessage = "";
$movie = null;

// Get movie ID from URL
if (isset($_GET['id'])) {
    $movieId = (int)$_GET['id'];
    
    try {
        $stmt = $pdo->prepare("SELECT * FROM movies WHERE id = ?");
        $stmt->execute([$movieId]);
        $movie = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$movie) {
            header('Location: /movies/list');
            exit;
        }
    } catch (PDOException $e) {
        $errors[] = 'Database error: ' . $e->getMessage();
    }
} else {
    header('Location: /movies/list');
    exit;
}

// Process form submission
if (isset($_POST['update'])) {
    // Check for movie name.
    if (empty($_POST['name'])) {
        $errors[] = 'You forgot to enter movie name.';
    } else {
        $name = trim($_POST['name']);
    }
    
    // Validate movie year
    if (!empty($_POST['year'])) {
        if (is_numeric($_POST['year'])) {
            $year = (int)$_POST['year'];
            if ($year < 1900 || $year > (date('Y') + 10)) {
                $errors[] = 'The movie year must be between 1900 and ' . (date('Y') + 10) . '.';
            }
        } else {
            $errors[] = 'You must enter the movie year in numeric format only!';
        }
    } else {
        $errors[] = 'You forgot to enter the movie year!';
    }
    
    // Validate movie genre
    if (empty($_POST['genre'])) {
        $errors[] = 'You forgot to choose movie genre!';
    } else {
        $genre = (int)$_POST['genre'];
        // Verify genre exists in database
        $genreCheck = $pdo->prepare("SELECT id FROM genres WHERE id = ?");
        $genreCheck->execute([$genre]);
        if (!$genreCheck->fetch()) {
            $errors[] = 'Invalid genre selected!';
        }
    }

    // Validate movie rating
    if (empty($_POST['rating'])) {
        $errors[] = 'You forgot to choose movie rating!';
    } else {
        $rating = (int)$_POST['rating'];
        if ($rating < 1 || $rating > 5) {
            $errors[] = 'Movie rating must be between 1 and 5 stars!';
        }
    }

    // Validate movie ticket price
    if (!empty($_POST['ticket_price'])) {
        if (is_numeric($_POST['ticket_price'])) {
            $ticket_price = (float)$_POST['ticket_price'];
            if ($ticket_price <= 0) {
                $errors[] = 'The ticket price must be greater than 0!';
            }
        } else {
            $errors[] = 'You must enter the ticket price in numeric format only!';
        }
    } else {
        $errors[] = 'You forgot to enter the ticket price!';
    }
    
    // Validate date and time
    if (empty($_POST['mdate'])) {
        $errors[] = 'You forgot to enter the movie date!';
    } else {
        $mdate = $_POST['mdate'];
    }

    if (empty($_POST['mtime'])) {
        $errors[] = 'You forgot to enter the movie time!';
    } else {
        $mtime = $_POST['mtime'];
    }
    
    if (empty($errors)) {
        try {
            // Check if another movie with same name exists (excluding current movie)
            $checkStmt = $pdo->prepare("SELECT id FROM movies WHERE name = ? AND id != ?");
            $checkStmt->execute([$name, $movieId]);
            
            if ($checkStmt->rowCount() == 0) {
                // Update the movie
                $updateStmt = $pdo->prepare("UPDATE movies SET name = ?, year = ?, genre = ?, rating = ?, ticket_price = ?, mdate = ?, mtime = ? WHERE id = ?");
                $result = $updateStmt->execute([$name, $year, $genre, $rating, $ticket_price, $mdate, $mtime, $movieId]);
                
                if ($result) {
                    $successMessage = "Movie '$name' has been successfully updated!";
                    // Refresh movie data
                    $stmt = $pdo->prepare("SELECT * FROM movies WHERE id = ?");
                    $stmt->execute([$movieId]);
                    $movie = $stmt->fetch(PDO::FETCH_ASSOC);
                } else {
                    $errors[] = 'Movie could not be updated due to a system error.';
                }
            } else {
                $errors[] = 'A movie with this name already exists in the database.';
            }
        } catch (PDOException $e) {
            $errors[] = 'Database error: ' . $e->getMessage();
        }
    }
}
?>

<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../output.css" rel="stylesheet">
    <title><?php echo $page_title; ?></title>
</head>
<body class="bg-gradient-to-br from-pink-200 to-purple-200 dark:from-gray-800 dark:to-gray-900 text-gray-900 dark:text-gray-100 min-h-screen transition-colors duration-200">
    <div class="flex min-h-screen">
        <?php include BASE_PATH . '/includes/navigation.php'; ?>

        <!-- Main Content -->
        <div class="flex-1 p-6 sm:ml-64">
            <header class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-8 mb-8 border border-gray-200 dark:border-gray-700">
                <div class="text-5xl font-extrabold">
                    <span class="bg-clip-text text-transparent bg-gradient-to-r from-pink-600 to-purple-600">
                        <?php echo $page_title; ?>
                    </span>
                </div>
                <p class="mt-4 text-gray-600 dark:text-gray-300 text-xl">Edit movie information</p>
            </header>

            <!-- Navigation Links -->
            <nav class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-4 mb-4 border border-gray-200 dark:border-gray-700">
                <ul class="flex space-x-4">
                    <li><a href="/movies/add" class="px-4 py-2 bg-gradient-to-r from-pink-600 to-purple-600 text-white font-semibold rounded-md shadow-md hover:scale-[1.02] transition-transform duration-300">Add Movie</a></li>
                    <li><a class="px-3 py-2 bg-transparent text-white font-semibold"></a></li>
                    <li><a href="/movies/list" class="px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-md shadow-md hover:scale-[1.02] transition-transform duration-300">View Movie List</a></li>
                </ul>
            </nav>

            <!-- Feedback Section -->
            <?php if (!empty($errors)): ?>
                <section class="bg-white dark:bg-gray-800 rounded-xl p-8 mb-8 border border-gray-200 dark:border-gray-700">
                    <h1 class="text-2xl font-semibold mb-4 text-red-600">Error!</h1>
                    <div class="text-red-500">
                        <p>The following error(s) occurred:</p>
                        <ul class="list-disc list-inside mt-2">
                            <?php foreach ($errors as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                        <p class="mt-4">Please try again.</p>
                    </div>
                </section>
            <?php elseif (!empty($successMessage)): ?>
                <section class="bg-white dark:bg-gray-800 rounded-xl p-8 mb-8 border border-gray-200 dark:border-gray-700">
                    <h1 class="text-2xl font-semibold mb-4 text-green-600">Success!</h1>
                    <p class="text-green-500"><?= htmlspecialchars($successMessage) ?></p>
                </section>
            <?php endif; ?>

            <section class="bg-white dark:bg-gray-800 rounded-xl p-8 shadow-lg hover:shadow-xl transition-shadow duration-300 mb-8 border border-gray-200 dark:border-gray-700">
                <form method="post" class="space-y-6">
                    <fieldset>
                        <legend class="text-2xl font-semibold mb-4">Edit movie information:</legend>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label for="name" class="text-lg font-medium text-gray-700 dark:text-gray-300">Movie Name:</label>
                                <input type="text" name="name" id="name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" size="15" maxlength="30" value="<?php echo htmlspecialchars($movie['name']); ?>" required />
                            </div>
                            <div class="space-y-2">
                                <label for="year" class="text-lg font-medium text-gray-700 dark:text-gray-300">Movie Year Release:</label>
                                <input type="number" name="year" id="year" min="1900" max="<?= date('Y') + 5 ?>" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" value="<?php echo htmlspecialchars($movie['year']); ?>" required />
                            </div>
                            <div class="space-y-2">
                                <label for="genre" class="text-lg font-medium text-gray-700 dark:text-gray-300">Movie Genre:</label>
                                <select id="genre" name="genre" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                                    <option value="">Choose a movie genre</option>
                                    <?php foreach ($genres as $genre): ?>
                                        <option value="<?= $genre['id'] ?>" <?php if($movie['genre'] == $genre['id']) echo 'selected="selected"'; ?>>
                                            <?= htmlspecialchars($genre['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label for="rating" class="text-lg font-medium text-gray-700 dark:text-gray-300">Movie Rating:</label>
                                <select id="rating" name="rating" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
                                    <option value="">Choose a movie rating</option>
                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <option value="<?= $i ?>" <?php if($movie['rating'] == $i) echo 'selected="selected"'; ?>>
                                            <?= $i ?> Star<?= $i > 1 ? 's' : '' ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="space-y-2">
                                <label for="ticket_price" class="text-lg font-medium text-gray-700 dark:text-gray-300">Movie Ticket Price:</label>
                                <input type="number" name="ticket_price" id="ticket_price" step="0.01" min="0" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" value="<?php echo htmlspecialchars($movie['ticket_price']); ?>" required />
                            </div>
                            <div class="space-y-2">
                                <label for="mdate" class="text-lg font-medium text-gray-700 dark:text-gray-300">Movie Date:</label>
                                <input type="date" name="mdate" id="mdate" value="<?php echo htmlspecialchars($movie['mdate']); ?>" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required />
                            </div>
                            <div class="space-y-2">
                                <label for="mtime" class="text-lg font-medium text-gray-700 dark:text-gray-300">Movie Time:</label>
                                <input type="time" name="mtime" id="mtime" value="<?php echo htmlspecialchars($movie['mtime']); ?>" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required />
                            </div>
                        </div>
                    </fieldset>
                    <div class="grid grid-cols-1 md:flex md:flex-row md:justify-start md:items-center gap-y-6 md:gap-x-4 mt-6">
                        <button type="submit" name="update" class="w-full md:w-auto flex items-center justify-center px-6 py-3 bg-gradient-to-r from-pink-600 to-purple-600 text-white font-semibold rounded-lg shadow-md hover:scale-[1.02] transition-transform duration-300">
                            <i class="fas fa-save mr-2"></i> Update Movie
                        </button>
                        <a href="/movies/list" class="w-full md:w-auto flex items-center justify-center px-6 py-3 bg-gradient-to-r from-gray-500 to-gray-600 text-white font-semibold rounded-lg shadow-md hover:scale-[1.02] transition-transform duration-300">
                            <i class="fas fa-times mr-2"></i> Cancel
                        </a>
                    </div>
                </form>
            </section>

            <script src="<?= $baseUrl ?>public/js/darkMode.js" defer></script>
            <script src="<?= $baseUrl ?>public/js/mobileNav.js" defer></script>
            <script src="<?= $baseUrl ?>public/js/all.min.js"></script>
            <script src="<?= $baseUrl ?>public/js/accordion.js" defer></script>
        </div>
    </div>
</body>
</html>
