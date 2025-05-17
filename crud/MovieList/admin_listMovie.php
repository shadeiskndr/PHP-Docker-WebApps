<?php
require_once __DIR__ . '/../../includes/db.php';

$db = Database::getInstance();
$pdo = $db->getConnection();

// Set the page title and include the HTML header.
$page_title = 'List of Movies';

// Pagination settings
$moviesPerPage = 12; // 12 movies per page for nice grid layout
$currentPage = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset = ($currentPage - 1) * $moviesPerPage;

// Get genres for search dropdown
$genreQuery = $pdo->query("SELECT * FROM genres ORDER BY name");
$genres = $genreQuery->fetchAll(PDO::FETCH_ASSOC);

$errors = [];
$movies = [];
$showResults = false;
$totalMovies = 0;
$totalPages = 0;
?>

<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/output.css" rel="stylesheet">
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

            <!-- Enhanced Movie Navigation -->
            <nav class="nav-card bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6 mb-6 border border-gray-200 dark:border-gray-700 backdrop-blur-sm">
                <!-- Breadcrumb -->
                <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-4">
                    <a href="/" class="breadcrumb-item hover:text-gray-700 dark:hover:text-gray-200 transition-colors duration-200">
                        <i class="fas fa-home mr-1"></i>Home
                    </a>
                    <i class="fas fa-chevron-right mx-2 text-xs"></i>
                    <a href="/movies/list" class="breadcrumb-item hover:text-gray-700 dark:hover:text-gray-200 transition-colors duration-200">
                        <i class="fas fa-film mr-1"></i>Movies
                    </a>
                    <i class="fas fa-chevron-right mx-2 text-xs"></i>
                    <span class="text-gray-700 dark:text-gray-300 font-medium">List Movies</span>
                </div>

                <!-- Navigation Tabs -->
                <div class="flex flex-wrap gap-3">
                    <!-- Add Movie Button -->
                    <a href="/movies/add" 
                       class="nav-button group flex items-center px-6 py-3 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 
                              text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 
                              transition-all duration-300 ease-out border-0 relative overflow-hidden"
                       data-tooltip="Create a new movie entry">
                        <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-20 transition-opacity duration-300"></div>
                        <i class="fas fa-plus mr-2 text-lg group-hover:rotate-90 transition-transform duration-300"></i>
                        <span class="relative z-10">Add New Movie</span>
                        <div class="ml-2 px-2 py-1 bg-white bg-opacity-20 rounded-full text-xs kbd-badge">
                            <i class="fas fa-keyboard"></i> N
                        </div>
                    </a>

                    <!-- Current Page - List Movies (Active State) -->
                    <div class="flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 
                                text-white font-semibold rounded-lg shadow-lg border-2 border-blue-400 relative">
                        <div class="absolute inset-0 bg-white opacity-10"></div>
                        <i class="fas fa-list mr-2 text-lg animate-pulse"></i>
                        <span class="relative z-10">View Movies</span>
                        <div class="ml-2 px-2 py-1 bg-white bg-opacity-30 rounded-full text-xs font-bold">
                            ACTIVE
                        </div>
                    </div>

                    <!-- Edit Movie Link (if needed) -->
                    <a href="#" 
                       onclick="showEditTip()" 
                       class="nav-button tooltip group flex items-center px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 
                              text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 
                              transition-all duration-300 ease-out relative overflow-hidden"
                       data-tooltip="Select a movie to edit from the list below">
                        <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-20 transition-opacity duration-300"></div>
                        <i class="fas fa-edit mr-2 text-lg group-hover:scale-110 transition-transform duration-300"></i>
                        <span class="relative z-10">Edit Movies</span>
                        <div class="ml-2 px-2 py-1 bg-white bg-opacity-20 rounded-full text-xs">
                            <i class="fas fa-info-circle"></i>
                        </div>
                    </a>

                    <!-- Movie Statistics -->
                    <div class="flex items-center px-4 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 
                                rounded-lg border border-gray-300 dark:border-gray-600 ml-auto">
                        <i class="fas fa-chart-bar mr-2 text-purple-500"></i>
                        <span class="text-sm font-medium">
                            Total Movies: <span id="movieCount" class="font-bold text-purple-600 dark:text-purple-400">-</span>
                        </span>
                    </div>
                </div>

                <!-- Quick Actions Bar -->
                <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-200 dark:border-gray-600">
                    <div class="flex items-center space-x-3">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Quick Actions:</span>
                        <button onclick="clearAllFilters()" 
                                class="px-3 py-1 text-xs bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 
                                       text-gray-700 dark:text-gray-300 rounded-md transition-colors duration-200">
                            <i class="fas fa-eraser mr-1"></i>Clear Filters
                        </button>
                        <button onclick="exportMovieList()" 
                                class="px-3 py-1 text-xs bg-blue-100 dark:bg-blue-900 hover:bg-blue-200 dark:hover:bg-blue-800 
                                       text-blue-700 dark:text-blue-300 rounded-md transition-colors duration-200">
                            <i class="fas fa-download mr-1"></i>Export
                        </button>
                    </div>
                    
                    <div class="flex items-center space-x-2">
                        <span class="text-xs text-gray-500 dark:text-gray-400">Keyboard shortcuts enabled</span>
                        <div class="w-2 h-2 bg-green-400 rounded-full status-dot"></div>
                    </div>
                </div>
            </nav>

            <section class="bg-white dark:bg-gray-800 rounded-xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 mb-8 border border-gray-200 dark:border-gray-700">
                <div class="mb-6">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-800 dark:text-gray-200">
                        <i class="fas fa-search mr-2 text-blue-600"></i>
                        Search & Filter Movies
                    </h2>
                </div>
                
                <form action="/movies/list" method="post" class="space-y-6" id="searchForm">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <!-- Genre Filter -->
                        <div class="space-y-2">
                            <label for="genre" class="flex items-center text-sm font-semibold text-gray-700 dark:text-gray-300">
                                <i class="fas fa-tags mr-2 text-purple-500"></i>
                                Filter by Genre
                            </label>
                            <div class="relative">
                                <select id="genre" name="genre" class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg 
                                               focus:border-purple-500 focus:ring-2 focus:ring-purple-200 dark:focus:ring-purple-800 
                                               transition-all duration-300 bg-white dark:bg-gray-700 text-gray-900 dark:text-white 
                                               appearance-none">
                                    <option value="">All Genres</option>
                                    <?php foreach ($genres as $genre): ?>
                                        <option value="<?= $genre['id'] ?>" <?php if(isset($_POST['genre']) && $_POST['genre'] == $genre['id']) echo 'selected="selected"'; ?>>
                                            <?= htmlspecialchars($genre['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Quick Search -->
                        <div class="space-y-2">
                            <label for="quickSearch" class="flex items-center text-sm font-semibold text-gray-700 dark:text-gray-300">
                                <i class="fas fa-search mr-2 text-blue-500"></i>
                                Quick Search
                            </label>
                            <div class="relative">
                                <input type="text" 
                                       id="quickSearch" 
                                       placeholder="Search movies..." 
                                       class="w-full px-4 py-3 pl-10 border-2 border-gray-300 dark:border-gray-600 rounded-lg 
                                              focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 
                                              transition-all duration-300 bg-white dark:bg-gray-700 text-gray-900 dark:text-white 
                                              placeholder-gray-500 dark:placeholder-gray-400">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Sort Options -->
                        <div class="space-y-2">
                            <label for="sortBy" class="flex items-center text-sm font-semibold text-gray-700 dark:text-gray-300">
                                <i class="fas fa-sort mr-2 text-green-500"></i>
                                Sort By
                            </label>
                            <div class="relative">
                                <select id="sortBy" class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg 
                                               focus:border-green-500 focus:ring-2 focus:ring-green-200 dark:focus:ring-green-800 
                                               transition-all duration-300 bg-white dark:bg-gray-700 text-gray-900 dark:text-white 
                                               appearance-none">
                                    <option value="name">Name (A-Z)</option>
                                    <option value="name_desc">Name (Z-A)</option>
                                    <option value="year">Year (Oldest)</option>
                                    <option value="year_desc">Year (Newest)</option>
                                    <option value="rating">Rating (Low to High)</option>
                                    <option value="rating_desc">Rating (High to Low)</option>
                                    <option value="price">Price (Low to High)</option>
                                    <option value="price_desc">Price (High to Low)</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit" name="search" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 
                                       hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-lg 
                                       shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                            <i class="fas fa-search mr-2"></i>Search Movies
                        </button>
                        <button type="submit" name="list" class="px-6 py-3 bg-gradient-to-r from-pink-600 to-purple-600 
                                       hover:from-pink-700 hover:to-purple-700 text-white font-semibold rounded-lg 
                                       shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                            <i class="fas fa-list mr-2"></i>Show All Movies
                        </button>
                        <button type="button" id="clearFilters" class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white 
                                       font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 
                                       transition-all duration-300">
                            <i class="fas fa-eraser mr-2"></i>Clear Filters
                        </button>
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
                        // Count total movies for pagination
                        $countStmt = $pdo->prepare("SELECT COUNT(*) FROM movies WHERE genre = ?");
                        $countStmt->execute([$genre]);
                        $totalMovies = $countStmt->fetchColumn();
                        $totalPages = ceil($totalMovies / $moviesPerPage);
                        
                        // Search for movies with selected genre with pagination
                        $stmt = $pdo->prepare("
                            SELECT m.id, m.name AS moviename, m.year, g.name AS genrename, 
                                   m.mdate AS moviedate, m.mtime AS movietime, m.rating AS movierating, 
                                   m.ticket_price, m.poster_url
                            FROM movies AS m 
                            INNER JOIN genres AS g ON m.genre = g.id 
                            WHERE m.genre = ? 
                            ORDER BY m.name
                            LIMIT ? OFFSET ?
                        ");
                        $stmt->execute([$genre, $moviesPerPage, $offset]);
                        $movies = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        
                    } catch (PDOException $e) {
                        $errors[] = 'Database error: ' . $e->getMessage();
                    }
                }
            }

            // List all movies (default behavior or when explicitly requested)
            if (isset($_POST['list']) || (!isset($_POST['search']) && !isset($_POST['list']))) {
                $showResults = true;
                
                try {
                    // Count total movies for pagination
                    $countStmt = $pdo->prepare("SELECT COUNT(*) FROM movies");
                    $countStmt->execute();
                    $totalMovies = $countStmt->fetchColumn();
                    $totalPages = ceil($totalMovies / $moviesPerPage);
                    
                    // Get all movies with pagination
                    $stmt = $pdo->prepare("
                        SELECT m.id, m.name AS moviename, m.year, g.name AS genrename, 
                               m.mdate AS moviedate, m.mtime AS movietime, m.rating AS movierating, 
                               m.ticket_price, m.poster_url
                        FROM movies AS m 
                        INNER JOIN genres AS g ON m.genre = g.id 
                        ORDER BY m.name
                        LIMIT ? OFFSET ?
                    ");
                    $stmt->execute([$moviesPerPage, $offset]);
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

            <?php if ($showResults && empty($errors)): ?>
                <section class="bg-white dark:bg-gray-800 rounded-xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 mb-8 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">
                            <i class="fas fa-film mr-2 text-pink-600"></i>
                            Movies Collection
                        </h2>
                        <div class="flex items-center space-x-4">
                            <span class="text-sm text-gray-600 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 px-3 py-1 rounded-full">
                                <i class="fas fa-hashtag mr-1"></i>
                                <?= count($movies) ?> movie(s) found
                            </span>
                            <div class="flex space-x-2">
                                <button id="gridViewBtn" class="p-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700 transition-colors duration-200" title="Grid View">
                                    <i class="fas fa-th-large"></i>
                                </button>
                                <button id="listViewBtn" class="p-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors duration-200" title="List View">
                                    <i class="fas fa-list"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <?php if (!empty($movies)): ?>
                        <!-- Grid View (Default) -->
                        <div id="gridView" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                            <?php foreach ($movies as $movie): ?>
                                <div class="movie-card bg-gradient-to-br from-white to-gray-50 dark:from-gray-700 dark:to-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-600 shadow-md hover:shadow-xl transform hover:scale-105 transition-all duration-300 movie-item" 
                                     data-movie-name="<?= strtolower(htmlspecialchars($movie['moviename'])) ?>"
                                     data-genre="<?= $movie['genrename'] ?>"
                                     data-year="<?= $movie['year'] ?>"
                                     data-rating="<?= $movie['movierating'] ?>"
                                     data-price="<?= $movie['ticket_price'] ?>">
                                    
                                    <!-- Movie Poster -->
                                    <div class="relative w-full h-32 rounded-lg mb-4 overflow-hidden">
                                        <?php if (!empty($movie['poster_url'])): ?>
                                            <img src="<?= htmlspecialchars($movie['poster_url']) ?>" 
                                                 alt="<?= htmlspecialchars($movie['moviename']) ?> Poster" 
                                                 class="w-full h-full object-cover">
                                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                                        <?php else: ?>
                                            <div class="flex items-center justify-center w-full h-full bg-gradient-to-br from-pink-500 to-purple-600 text-white">
                                                <i class="fas fa-film text-4xl"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <!-- Movie Info -->
                                    <div class="space-y-3">
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 line-clamp-2" title="<?= htmlspecialchars($movie['moviename']) ?>">
                                            <?= htmlspecialchars($movie['moviename']) ?>
                                        </h3>
                                        
                                        <!-- Genre Badge -->
                                        <div class="flex items-center space-x-2">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                                <i class="fas fa-tag mr-1"></i>
                                                <?= htmlspecialchars($movie['genrename']) ?>
                                            </span>
                                            <span class="text-sm text-gray-600 dark:text-gray-400">
                                                <?= htmlspecialchars($movie['year']) ?>
                                            </span>
                                        </div>
                                        
                                        <!-- Rating Stars -->
                                        <div class="flex items-center space-x-2">
                                            <div class="flex items-center">
                                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                                    <svg class="w-4 h-4 <?= $i <= $movie['movierating'] ? 'text-yellow-400' : 'text-gray-300' ?>" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                <?php endfor; ?>
                                            </div>
                                            <span class="text-sm text-gray-600 dark:text-gray-400"><?= $movie['movierating'] ?>/5</span>
                                        </div>
                                        
                                        <!-- Date & Time -->
                                        <div class="grid grid-cols-2 gap-2 text-sm text-gray-600 dark:text-gray-400">
                                            <div class="flex items-center space-x-1">
                                                <i class="fas fa-calendar text-blue-500"></i>
                                                <span><?= date('M j', strtotime($movie['moviedate'])) ?></span>
                                            </div>
                                            <div class="flex items-center space-x-1">
                                                <i class="fas fa-clock text-orange-500"></i>
                                                <span><?= date('g:i A', strtotime($movie['movietime'])) ?></span>
                                            </div>
                                        </div>
                                        
                                        <!-- Price -->
                                        <div class="flex items-center justify-between pt-2 border-t border-gray-200 dark:border-gray-600">
                                            <span class="text-lg font-bold text-green-600 dark:text-green-400">
                                                $<?= number_format($movie['ticket_price'], 2) ?>
                                            </span>
                                            <div class="flex space-x-2">
                                                <a href="/movies/edit?id=<?= $movie['id'] ?>" 
                                                   class="p-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors duration-200 transform hover:scale-110" 
                                                   title="Edit Movie">
                                                    <i class="fas fa-edit text-sm"></i>
                                                </a>
                                                <button class="delete-movie-btn p-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors duration-200 transform hover:scale-110"
                                                        data-movie-id="<?= $movie['id'] ?>"
                                                        data-movie-name="<?= htmlspecialchars($movie['moviename']) ?>"
                                                        title="Delete Movie">
                                                    <i class="fas fa-trash text-sm"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- List View (Hidden by default) -->
                        <div id="listView" class="hidden">
                            <!-- Desktop Table -->
                            <div class="hidden lg:block overflow-x-auto">
                                <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg">
                                    <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600">
                                        <tr>
                                            <th class="py-4 px-6 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Movie</th>
                                            <th class="py-4 px-6 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Genre & Year</th>
                                            <th class="py-4 px-6 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Schedule</th>
                                            <th class="py-4 px-6 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Rating</th>
                                            <th class="py-4 px-6 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Price</th>
                                            <th class="py-4 px-6 text-center text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                        <?php foreach ($movies as $movie): ?>
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200 movie-item"
                                                data-movie-name="<?= strtolower(htmlspecialchars($movie['moviename'])) ?>"
                                                data-genre="<?= $movie['genrename'] ?>"
                                                data-year="<?= $movie['year'] ?>"
                                                data-rating="<?= $movie['movierating'] ?>"
                                                data-price="<?= $movie['ticket_price'] ?>">
                                                <td class="py-4 px-6">
                                                    <div class="flex items-center space-x-3">
                                                        <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-pink-500 to-purple-600 rounded-lg flex items-center justify-center">
                                                            <i class="fas fa-film text-white text-sm"></i>
                                                        </div>
                                                        <div>
                                                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100"><?= htmlspecialchars($movie['moviename']) ?></h4>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="py-4 px-6">
                                                    <div class="space-y-1">
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                                            <?= htmlspecialchars($movie['genrename']) ?>
                                                        </span>
                                                        <div class="text-sm text-gray-600 dark:text-gray-400"><?= htmlspecialchars($movie['year']) ?></div>
                                                    </div>
                                                </td>
                                                <td class="py-4 px-6">
                                                    <div class="space-y-1 text-sm">
                                                        <div class="flex items-center text-gray-900 dark:text-gray-100">
                                                            <i class="fas fa-calendar text-blue-500 mr-2"></i>
                                                            <?= date('M j, Y', strtotime($movie['moviedate'])) ?>
                                                        </div>
                                                        <div class="flex items-center text-gray-600 dark:text-gray-400">
                                                            <i class="fas fa-clock text-orange-500 mr-2"></i>
                                                            <?= date('g:i A', strtotime($movie['movietime'])) ?>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="py-4 px-6">
                                                    <div class="flex items-center space-x-2">
                                                        <div class="flex items-center">
                                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                                <svg class="w-4 h-4 <?= $i <= $movie['movierating'] ? 'text-yellow-400' : 'text-gray-300' ?>" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                                </svg>
                                                            <?php endfor; ?>
                                                        </div>
                                                        <span class="text-xs text-gray-500"><?= $movie['movierating'] ?>/5</span>
                                                    </div>
                                                </td>
                                                <td class="py-4 px-6">
                                                    <span class="text-lg font-bold text-green-600 dark:text-green-400">
                                                        $<?= number_format($movie['ticket_price'], 2) ?>
                                                    </span>
                                                </td>
                                                <td class="py-4 px-6">
                                                    <div class="flex justify-center space-x-2">
                                                        <a href="/movies/edit?id=<?= $movie['id'] ?>" 
                                                           class="p-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors duration-200 transform hover:scale-110" 
                                                           title="Edit Movie">
                                                            <i class="fas fa-edit text-sm"></i>
                                                        </a>
                                                        <button class="delete-movie-btn p-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors duration-200 transform hover:scale-110"
                                                                data-movie-id="<?= $movie['id'] ?>"
                                                                data-movie-name="<?= htmlspecialchars($movie['moviename']) ?>"
                                                                title="Delete Movie">
                                                            <i class="fas fa-trash text-sm"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Mobile Cards for List View -->
                            <div class="lg:hidden space-y-4">
                                <?php foreach ($movies as $movie): ?>
                                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600 movie-item"
                                         data-movie-name="<?= strtolower(htmlspecialchars($movie['moviename'])) ?>"
                                         data-genre="<?= $movie['genrename'] ?>"
                                         data-year="<?= $movie['year'] ?>"
                                         data-rating="<?= $movie['movierating'] ?>"
                                         data-price="<?= $movie['ticket_price'] ?>">
                                        <div class="flex justify-between items-start mb-3">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-12 h-12 bg-gradient-to-br from-pink-500 to-purple-600 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-film text-white"></i>
                                                </div>
                                                <div>
                                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100"><?= htmlspecialchars($movie['moviename']) ?></h3>
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                                        <?= htmlspecialchars($movie['genrename']) ?>
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="flex space-x-2">
                                                <a href="/movies/edit?id=<?= $movie['id'] ?>" class="p-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors duration-200">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button class="delete-movie-btn p-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors duration-200"
                                                        data-movie-id="<?= $movie['id'] ?>"
                                                        data-movie-name="<?= htmlspecialchars($movie['moviename']) ?>">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-3 text-sm">
                                            <div><span class="font-medium">Year:</span> <?= htmlspecialchars($movie['year']) ?></div>
                                            <div><span class="font-medium">Price:</span> $<?= number_format($movie['ticket_price'], 2) ?></div>
                                            <div><span class="font-medium">Date:</span> <?= date('M j, Y', strtotime($movie['moviedate'])) ?></div>
                                            <div><span class="font-medium">Time:</span> <?= date('g:i A', strtotime($movie['movietime'])) ?></div>
                                        </div>
                                        <div class="flex items-center mt-3 pt-3 border-t border-gray-200 dark:border-gray-600">
                                            <span class="font-medium mr-2">Rating:</span>
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <svg class="w-4 h-4 <?= $i <= $movie['movierating'] ? 'text-yellow-400' : 'text-gray-300' ?>" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                </svg>
                                            <?php endfor; ?>
                                            <span class="ml-2 text-xs text-gray-500"><?= $movie['movierating'] ?>/5</span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <!-- Pagination -->
                            <?php if ($totalPages > 1): ?>
                                <div class="mt-12 flex flex-col sm:flex-row items-center justify-between border-t border-gray-200 dark:border-gray-700 pt-8">
                                    <!-- Results Info -->
                                    <div class="mb-4 sm:mb-0">
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            Showing <span class="font-medium"><?= $offset + 1 ?></span> to 
                                            <span class="font-medium"><?= min($offset + $moviesPerPage, $totalMovies) ?></span> of 
                                            <span class="font-medium"><?= $totalMovies ?></span> movies
                                        </p>
                                    </div>
                                    
                                    <!-- Pagination Controls -->
                                    <div class="flex items-center space-x-2">
                                        <!-- Previous Button -->
                                        <?php if ($currentPage > 1): ?>
                                            <a href="?page=<?= $currentPage - 1 ?>" 
                                               class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                                <i class="fas fa-chevron-left mr-1"></i>
                                                Previous
                                            </a>
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-300 dark:text-gray-600 bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg cursor-not-allowed">
                                                <i class="fas fa-chevron-left mr-1"></i>
                                                Previous
                                            </span>
                                        <?php endif; ?>
                                        
                                        <!-- Page Numbers -->
                                        <div class="hidden sm:flex items-center space-x-1">
                                            <?php
                                            $startPage = max(1, $currentPage - 2);
                                            $endPage = min($totalPages, $currentPage + 2);
                                            
                                            // Show first page if not in range
                                            if ($startPage > 1): ?>
                                                <a href="?page=1" class="px-3 py-2 text-sm font-medium text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">1</a>
                                                <?php if ($startPage > 2): ?>
                                                    <span class="px-2 py-2 text-sm text-gray-400">...</span>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                            
                                            <!-- Current page range -->
                                            <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                                                <?php if ($i == $currentPage): ?>
                                                    <span class="px-3 py-2 text-sm font-medium text-white bg-gradient-to-r from-pink-600 to-purple-600 border border-transparent rounded-lg">
                                                        <?= $i ?>
                                                    </span>
                                                <?php else: ?>
                                                    <a href="?page=<?= $i ?>" class="px-3 py-2 text-sm font-medium text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                                        <?= $i ?>
                                                    </a>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                            
                                            <!-- Show last page if not in range -->
                                            <?php if ($endPage < $totalPages): ?>
                                                <?php if ($endPage < $totalPages - 1): ?>
                                                    <span class="px-2 py-2 text-sm text-gray-400">...</span>
                                                <?php endif; ?>
                                                <a href="?page=<?= $totalPages ?>" class="px-3 py-2 text-sm font-medium text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200"><?= $totalPages ?></a>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <!-- Next Button -->
                                        <?php if ($currentPage < $totalPages): ?>
                                            <a href="?page=<?= $currentPage + 1 ?>" 
                                               class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                                Next
                                                <i class="fas fa-chevron-right ml-1"></i>
                                            </a>
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-300 dark:text-gray-600 bg-gray-100 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg cursor-not-allowed">
                                                Next
                                                <i class="fas fa-chevron-right ml-1"></i>
                                            </span>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <!-- Mobile Page Info -->
                                    <div class="sm:hidden mt-4">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 text-center">
                                            Page <?= $currentPage ?> of <?= $totalPages ?>
                                        </p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-16">
                            <div class="mx-auto h-24 w-24 text-gray-400 mb-6">
                                <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-full h-full">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2h4a1 1 0 110 2h-1v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6H3a1 1 0 110-2h4zM6 6v12h12V6H6zm8-2V2H10v2h8z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-medium text-gray-900 dark:text-gray-100 mb-3">No movies found</h3>
                            <p class="text-gray-500 dark:text-gray-400 mb-8 max-w-md mx-auto">
                                <?php if (isset($_POST['search'])): ?>
                                    No movies match your search criteria. Try adjusting your filters or search terms.
                                <?php else: ?>
                                    No movies have been added to the database yet. Start building your collection!
                                <?php endif; ?>
                            </p>
                            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                                <a href="/movies/add" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-pink-600 to-purple-600 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                                    <i class="fas fa-plus mr-2"></i>
                                    Add New Movie
                                </a>
                                <?php if (isset($_POST['search'])): ?>
                                    <button onclick="clearAllFilters()" class="inline-flex items-center px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                                        <i class="fas fa-eraser mr-2"></i>
                                        Clear Filters
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </section>
            <?php endif; ?>

            <script src="<?= $baseUrl ?>public/js/darkMode.js" defer></script>
            <script src="<?= $baseUrl ?>public/js/mobileNav.js" defer></script>
            <script src="<?= $baseUrl ?>public/js/all.min.js"></script>
            <script src="<?= $baseUrl ?>public/js/accordion.js" defer></script>
            <script src="<?= $baseUrl ?>crud/MovieList/js/deleteMovie.js" defer></script>
            <script src="<?= $baseUrl ?>crud/MovieList/js/movieList.js" defer></script>
            
            <!-- Enhanced Navigation Functions -->
            <script>
                // Enhanced navigation functions
                document.addEventListener('DOMContentLoaded', function() {
                    // Update movie count
                    updateMovieCount();
                    
                    // Add keyboard shortcuts
                    document.addEventListener('keydown', function(e) {
                        if (e.altKey) {
                            switch(e.key) {
                                case 'n': // Alt+N for Add Movie
                                    e.preventDefault();
                                    window.location.href = '/movies/add';
                                    break;
                                case 'v': // Alt+V for View Movies
                                    e.preventDefault();
                                    window.location.href = '/movies/list';
                                    break;
                            }
                        }
                    });
                });

                function showEditTip() {
                    showToast('Click on any movie\'s edit button to modify it!', 'info');
                }

                function exportMovieList() {
                    // Simple CSV export functionality
                    const movies = document.querySelectorAll('.movie-item:not([style*="display: none"])');
                    let csvContent = "Movie Name,Year,Genre,Rating,Date,Time,Price\n";
                    
                    movies.forEach(movie => {
                        const name = movie.querySelector('h3')?.textContent || '';
                        const details = movie.querySelectorAll('.text-gray-600');
                        // Add to CSV (simplified for demo)
                        csvContent += `"${name}",,,,,\n`;
                    });
                    
                    const blob = new Blob([csvContent], { type: 'text/csv' });
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = 'movie-list.csv';
                    a.click();
                    
                    showToast('Movie list exported successfully!', 'success');
                }

                function updateMovieCount() {
                    const visibleMovies = document.querySelectorAll('.movie-item:not([style*="display: none"])');
                    const countElement = document.getElementById('movieCount');
                    if (countElement) {
                        countElement.textContent = visibleMovies.length;
                    }
                }

                // Enhanced toast notification (if not already defined)
                if (typeof showToast === 'undefined') {
                    function showToast(message, type = 'success') {
                        const existingToasts = document.querySelectorAll('.toast-notification');
                        existingToasts.forEach(toast => toast.remove());

                        const toast = document.createElement('div');
                        toast.className = `toast-notification fixed top-4 right-4 z-50 p-4 rounded-lg shadow-xl transform transition-all duration-300 translate-x-full max-w-sm`;

                        const colors = {
                            success: 'bg-green-500 text-white',
                            error: 'bg-red-500 text-white',
                            warning: 'bg-yellow-500 text-black',
                            info: 'bg-blue-500 text-white'
                        };

                        const icons = {
                            success: 'fas fa-check-circle',
                            error: 'fas fa-exclamation-circle',
                            warning: 'fas fa-exclamation-triangle',
                            info: 'fas fa-info-circle'
                        };

                        toast.className += ` ${colors[type]}`;
                        toast.innerHTML = `
                            <div class="flex items-center space-x-3">
                                <i class="${icons[type]} text-lg"></i>
                                <span class="font-medium">${message}</span>
                                <button onclick="this.parentElement.parentElement.remove()" class="ml-2 text-lg hover:opacity-75">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        `;

                        document.body.appendChild(toast);

                        setTimeout(() => toast.style.transform = 'translateX(0)', 100);
                        setTimeout(() => {
                            toast.style.transform = 'translateX(100%)';
                            setTimeout(() => toast.remove(), 300);
                        }, 5000);
                    }
                }
            </script>
        </div>
    </div>
</body>
</html>
