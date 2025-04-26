<?php
require_once __DIR__ . '/../includes/db.php';

$db = Database::getInstance();
$pdo = $db->getConnection();

// Set the page title and include the HTML header.
$page_title = 'List of Movies';

// Get genres for search dropdown
$genreQuery = $pdo->query("SELECT * FROM genres ORDER BY name");
$genres = $genreQuery->fetchAll(PDO::FETCH_ASSOC);

$errors = [];
$movies = [];
$showResults = false;
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
                <p class="mt-4 text-gray-600 dark:text-gray-300 text-xl">View or search movies by genre</p>
            </header>

            <!-- Navigation Links -->
            <nav class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-4 mb-4 border border-gray-200 dark:border-gray-700">
                <ul class="flex space-x-4">
                    <li><a href="/movies/add" class="px-4 py-2 bg-gradient-to-r from-pink-600 to-purple-600 text-white font-semibold rounded-md shadow-md hover:scale-[1.02] transition-transform duration-300">Add Movie</a></li>
                    <li><a class="px-3 py-2 bg-transparent text-white font-semibold"></a></li>
                    <li><a href="/movies/list" class="px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-md shadow-md hover:scale-[1.02] transition-transform duration-300">View Movie List</a></li>
                </ul>
            </nav>

            <section class="bg-white dark:bg-gray-800 rounded-xl p-8 shadow-lg hover:shadow-xl transition-shadow duration-300 mb-8 border border-gray-200 dark:border-gray-700">
                <form action="/movies/list" method="post" class="space-y-6">
                    <fieldset>
                        <legend class="text-2xl font-semibold mb-4">List all or search movies by genre:</legend>
                        <div class="space-y-2">
                            <label for="genre" class="text-lg font-medium text-gray-700 dark:text-gray-300">Movie genre:</label>
                            <select id="genre" name="genre" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-pink-500 focus:border-pink-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                <option value="">Choose a movie genre</option>
                                <?php foreach ($genres as $genre): ?>
                                    <option value="<?= $genre['id'] ?>" <?php if(isset($_POST['genre']) && $_POST['genre'] == $genre['id']) echo 'selected="selected"'; ?>>
                                        <?= htmlspecialchars($genre['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </fieldset>
                    <div class="grid grid-cols-1 md:flex md:flex-row md:justify-start md:items-center gap-y-6 md:gap-x-4 mt-6">
                        <button type="submit" name="search" class="w-full md:w-auto flex items-center justify-center px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-semibold rounded-lg shadow-md hover:scale-[1.02] transition-transform duration-300">Search</button>
                        <button type="submit" name="list" class="w-full md:w-auto flex items-center justify-center px-6 py-3 bg-gradient-to-r from-pink-600 to-purple-600 text-white font-semibold rounded-lg shadow-md hover:scale-[1.02] transition-transform duration-300 ml-2">List All</button>
                    </div>
                </form>
            </section>

            <?php
            // Process form submissions
            if (isset($_POST['search'])) {
                $showResults = true;
                
                // Validate movie genre
                if (empty($_POST['genre'])) {
                    $errors[] = 'You forgot to choose movie genre!';
                } else {
                    $genre = (int)$_POST['genre'];
                    
                    try {
                        // Search for movies with selected genre
                        $stmt = $pdo->prepare("
                            SELECT m.id, m.name AS moviename, m.year, g.name AS genrename, 
                                   m.mdate AS moviedate, m.mtime AS movietime, m.rating AS movierating, 
                                   m.ticket_price
                            FROM movies AS m 
                            INNER JOIN genres AS g ON m.genre = g.id 
                            WHERE m.genre = ? 
                            ORDER BY m.name
                        ");
                        $stmt->execute([$genre]);
                        $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        
                    } catch (PDOException $e) {
                        $errors[] = 'Database error: ' . $e->getMessage();
                    }
                }
            }

            // List all movies
            if (isset($_POST['list'])) {
                $showResults = true;
                
                try {
                    // Get all movies
                    $stmt = $pdo->prepare("
                        SELECT m.id, m.name AS moviename, m.year, g.name AS genrename, 
                               m.mdate AS moviedate, m.mtime AS movietime, m.rating AS movierating, 
                               m.ticket_price
                        FROM movies AS m 
                        INNER JOIN genres AS g ON m.genre = g.id 
                        ORDER BY m.name
                    ");
                    $stmt->execute();
                    $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                } catch (PDOException $e) {
                    $errors[] = 'Database error: ' . $e->getMessage();
                }
            }
            ?>

            <!-- Error Messages -->
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
            <?php endif; ?>

            <!-- Results Section -->
            <?php if ($showResults && empty($errors)): ?>
                <section class="bg-white dark:bg-gray-800 rounded-xl p-8 shadow-lg hover:shadow-xl transition-shadow duration-300 mb-8 border border-gray-200 dark:border-gray-700">
                    <h2 class="text-2xl font-semibold mb-4">Movies List</h2>
                    
                    <?php if (!empty($movies)): ?>
                        <p class="mb-4 text-gray-600 dark:text-gray-300">Found <?= count($movies) ?> movie(s).</p>
                        
                        <!-- Desktop Table -->
                        <div class="hidden md:block overflow-x-auto">
                            <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Year</th>
                                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Genre</th>
                                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Date</th>
                                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Time</th>
                                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Rating</th>
                                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Price</th>
                                        <th class="py-3 px-4 text-left text-sm font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    <?php foreach ($movies as $movie): ?>
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-600">
                                            <td class="py-3 px-4 text-sm text-gray-900 dark:text-gray-100"><?= htmlspecialchars($movie['moviename']) ?></td>
                                            <td class="py-3 px-4 text-sm text-gray-900 dark:text-gray-100"><?= htmlspecialchars($movie['year']) ?></td>
                                            <td class="py-3 px-4 text-sm text-gray-900 dark:text-gray-100"><?= htmlspecialchars($movie['genrename']) ?></td>
                                            <td class="py-3 px-4 text-sm text-gray-900 dark:text-gray-100"><?= date('M j, Y', strtotime($movie['moviedate'])) ?></td>
                                            <td class="py-3 px-4 text-sm text-gray-900 dark:text-gray-100"><?= date('g:i A', strtotime($movie['movietime'])) ?></td>
                                            <td class="py-3 px-4 text-sm text-gray-900 dark:text-gray-100">
                                                <div class="flex items-center">
                                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                                        <svg class="w-4 h-4 <?= $i <= $movie['movierating'] ? 'text-yellow-400' : 'text-gray-300' ?>" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                        </svg>
                                                    <?php endfor; ?>
                                                    <span class="ml-2 text-xs text-gray-500">(<?= $movie['movierating'] ?>)</span>
                                                </div>
                                            </td>
                                            <td class="py-3 px-4 text-sm text-gray-900 dark:text-gray-100">$<?= number_format($movie['ticket_price'], 2) ?></td>
                                            <td class="py-3 px-4 text-sm">
                                                <div class="flex space-x-2">
                                                    <a href="/movies/edit?id=<?= $movie['id'] ?>" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs transition-colors duration-200">
                                                        <i class="fas fa-edit mr-1"></i>Edit
                                                    </a>
                                                    <button class="delete-movie-btn bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs transition-colors duration-200"
                                                            data-movie-id="<?= $movie['id'] ?>"
                                                            data-movie-name="<?= htmlspecialchars($movie['moviename']) ?>">
                                                        <i class="fas fa-trash mr-1"></i>Delete
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Mobile Cards -->
                        <div class="md:hidden space-y-4">
                            <?php foreach ($movies as $movie): ?>
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                                    <div class="flex justify-between items-start mb-2">
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100"><?= htmlspecialchars($movie['moviename']) ?></h3>
                                        <div class="flex space-x-2">
                                            <a href="/movies/edit?id=<?= $movie['id'] ?>" class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs transition-colors duration-200">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button class="delete-movie-btn bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs transition-colors duration-200"
                                                    data-movie-id="<?= $movie['id'] ?>"
                                                    data-movie-name="<?= htmlspecialchars($movie['moviename']) ?>">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-2 text-sm">
                                        <div><span class="font-medium">Year:</span> <?= htmlspecialchars($movie['year']) ?></div>
                                        <div><span class="font-medium">Genre:</span> <?= htmlspecialchars($movie['genrename']) ?></div>
                                        <div><span class="font-medium">Date:</span> <?= date('M j, Y', strtotime($movie['moviedate'])) ?></div>
                                        <div><span class="font-medium">Time:</span> <?= date('g:i A', strtotime($movie['movietime'])) ?></div>
                                        <div><span class="font-medium">Price:</span> $<?= number_format($movie['ticket_price'], 2) ?></div>
                                        <div class="flex items-center">
                                            <span class="font-medium mr-2">Rating:</span>
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <svg class="w-4 h-4 <?= $i <= $movie['movierating'] ? 'text-yellow-400' : 'text-gray-300' ?>" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2h4a1 1 0 110 2h-1v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6H3a1 1 0 110-2h4zM6 6v12h12V6H6zm8-2V2H10v2h8z"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No movies found</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                <?php if (isset($_POST['search'])): ?>
                                    No movies found with the selected genre.
                                <?php else: ?>
                                    No movies have been added to the database yet.
                                <?php endif; ?>
                            </p>
                        </div>
                    <?php endif; ?>
                </section>
            <?php endif; ?>

            <script src="<?= $baseUrl ?>public/js/darkMode.js" defer></script>
            <script src="<?= $baseUrl ?>public/js/mobileNav.js" defer></script>
            <script src="<?= $baseUrl ?>public/js/all.min.js"></script>
            <script src="<?= $baseUrl ?>public/js/accordion.js" defer></script>
            <script src="<?= $baseUrl ?>public/js/movies/deleteMovie.js" defer></script>
        </div>
    </div>
</body>
</html>
