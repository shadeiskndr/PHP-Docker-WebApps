<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/minioService.php';

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
    
    // Handle poster upload/removal
    $posterUrl = $movie['poster_url'] ?? '';
    
    // Check if poster should be removed
    if (isset($_POST['remove_poster']) && $_POST['remove_poster'] == '1') {
        if (!empty($posterUrl)) {
            // Delete from MinIO if exists
            try {
                $minioService = new MinioService();
                $filename = basename($posterUrl);
                $minioService->deleteFile('posters', $filename);
            } catch (Exception $e) {
                error_log("Error deleting poster from MinIO: " . $e->getMessage());
            }
        }
        $posterUrl = '';
    }
    
    // Handle new poster upload
    if (isset($_FILES['poster']) && $_FILES['poster']['error'] === UPLOAD_ERR_OK) {
        try {
            $minioService = new MinioService();
            $bucketName = 'posters';
            
            // Create bucket if it doesn't exist
            $minioService->createBucket($bucketName);
            
            // Delete old poster if exists
            if (!empty($posterUrl)) {
                $oldFilename = basename($posterUrl);
                $minioService->deleteFile($bucketName, $oldFilename);
            }
            
            // Upload new poster
            $file = $_FILES['poster'];
            $filename = 'movie_' . $movieId . '_' . time() . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
            
            $uploadResult = $minioService->uploadFile($bucketName, $filename, $file['tmp_name'], $file['type']);
            
            if ($uploadResult) {
                $posterUrl = $minioService->getFileUrl($bucketName, $filename);
            }
            
        } catch (Exception $e) {
            $errors[] = "Error uploading poster: " . $e->getMessage();
        }
    }
    
    if (empty($errors)) {
        try {
            // Check if another movie with same name exists (excluding current movie)
            $checkStmt = $pdo->prepare("SELECT id FROM movies WHERE name = ? AND id != ?");
            $checkStmt->execute([$name, $movieId]);
            
            if ($checkStmt->rowCount() == 0) {
                // Update the movie with poster URL
                $updateStmt = $pdo->prepare("UPDATE movies SET name = ?, year = ?, genre = ?, rating = ?, ticket_price = ?, mdate = ?, mtime = ?, poster_url = ? WHERE id = ?");
                $result = $updateStmt->execute([$name, $year, $genre, $rating, $ticket_price, $mdate, $mtime, $posterUrl, $movieId]);
                
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
                <p class="mt-4 text-gray-600 dark:text-gray-300 text-xl">Edit movie information</p>
            </header>

            <!-- Enhanced Movie Navigation -->
            <nav class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-6 mb-6 border border-gray-200 dark:border-gray-700 backdrop-blur-sm">
                <!-- Breadcrumb -->
                <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-4">
                    <a href="/" class="hover:text-gray-700 dark:hover:text-gray-200 transition-colors duration-200">
                        <i class="fas fa-home mr-1"></i>Home
                    </a>
                    <i class="fas fa-chevron-right mx-2 text-xs"></i>
                    <a href="/movies/list" class="hover:text-gray-700 dark:hover:text-gray-200 transition-colors duration-200">
                        <i class="fas fa-film mr-1"></i>Movies
                    </a>
                    <i class="fas fa-chevron-right mx-2 text-xs"></i>
                    <span class="text-gray-700 dark:text-gray-300 font-medium">
                        Edit: <?= htmlspecialchars($movie['name'] ?? 'Movie') ?>
                    </span>
                </div>

                <!-- Navigation Tabs -->
                <div class="flex flex-wrap gap-3">
                    <!-- Add Movie Button -->
                    <a href="/movies/add" 
                       class="group flex items-center px-6 py-3 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 
                              text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 
                              transition-all duration-300 ease-out border-0 relative overflow-hidden">
                        <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-20 transition-opacity duration-300"></div>
                        <i class="fas fa-plus mr-2 text-lg group-hover:rotate-90 transition-transform duration-300"></i>
                        <span class="relative z-10">Add Movie</span>
                    </a>

                    <!-- View Movies Button -->
                    <a href="/movies/list" 
                       class="group flex items-center px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 hover:from-blue-600 hover:to-indigo-700 
                              text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 
                              transition-all duration-300 ease-out border-0 relative overflow-hidden">
                        <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-20 transition-opacity duration-300"></div>
                        <i class="fas fa-list mr-2 text-lg group-hover:scale-110 transition-transform duration-300"></i>
                        <span class="relative z-10">View Movies</span>
                    </a>

                    <!-- Current Page - Edit Movie (Active State) -->
                    <div class="flex items-center px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-500 
                                text-white font-semibold rounded-lg shadow-lg border-2 border-amber-400 relative">
                        <div class="absolute inset-0 bg-white opacity-10"></div>
                        <i class="fas fa-edit mr-2 text-lg animate-pulse"></i>
                        <span class="relative z-10">Edit Movie</span>
                        <div class="ml-2 px-2 py-1 bg-white bg-opacity-30 rounded-full text-xs font-bold">
                            ACTIVE
                        </div>
                    </div>

                    <!-- Delete Movie Button -->
                    <button onclick="confirmDelete(<?= $movie['id'] ?? 0 ?>)" 
                            class="group flex items-center px-6 py-3 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 
                                   text-white font-semibold rounded-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 
                                   transition-all duration-300 ease-out relative overflow-hidden">
                        <div class="absolute inset-0 bg-white opacity-0 group-hover:opacity-20 transition-opacity duration-300"></div>
                        <i class="fas fa-trash mr-2 text-lg group-hover:scale-110 transition-transform duration-300"></i>
                        <span class="relative z-10">Delete</span>
                        <div class="ml-2 px-2 py-1 bg-white bg-opacity-20 rounded-full text-xs">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                    </button>

                    <!-- Movie Info Display -->
                    <div class="flex items-center px-4 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 
                                rounded-lg border border-gray-300 dark:border-gray-600 ml-auto">
                        <i class="fas fa-info-circle mr-2 text-blue-500"></i>
                        <span class="text-sm font-medium">
                            ID: <span class="font-bold text-blue-600 dark:text-blue-400"><?= $movie['id'] ?? 'N/A' ?></span>
                        </span>
                    </div>
                </div>

                <!-- Quick Actions Bar -->
                <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-200 dark:border-gray-600">
                    <div class="flex items-center space-x-3">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Quick Actions:</span>
                        <button onclick="resetForm()" 
                                class="px-3 py-1 text-xs bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 
                                       text-gray-700 dark:text-gray-300 rounded-md transition-colors duration-200">
                            <i class="fas fa-undo mr-1"></i>Reset Changes
                        </button>
                        <button onclick="saveDraft()" 
                                class="px-3 py-1 text-xs bg-yellow-100 dark:bg-yellow-900 hover:bg-yellow-200 dark:hover:bg-yellow-800 
                                       text-yellow-700 dark:text-yellow-300 rounded-md transition-colors duration-200">
                            <i class="fas fa-save mr-1"></i>Save Draft
                        </button>
                        <button onclick="previewChanges()" 
                                class="px-3 py-1 text-xs bg-purple-100 dark:bg-purple-900 hover:bg-purple-200 dark:hover:bg-purple-800 
                                       text-purple-700 dark:text-purple-300 rounded-md transition-colors duration-200">
                            <i class="fas fa-eye mr-1"></i>Preview
                        </button>
                    </div>
                    
                    <div class="flex items-center space-x-2">
                        <span class="text-xs text-gray-500 dark:text-gray-400">Last modified: Just now</span>
                        <div class="w-2 h-2 bg-orange-400 rounded-full animate-pulse"></div>
                    </div>
                </div>
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

            <section class="bg-white dark:bg-gray-800 rounded-xl p-8 shadow-lg hover:shadow-xl transition-all duration-300 mb-8 border border-gray-200 dark:border-gray-700 backdrop-blur-sm">
                <form method="post" enctype="multipart/form-data" class="space-y-8" id="editMovieForm">
                    <fieldset class="space-y-6">
                        <legend class="text-2xl font-semibold mb-6 text-gray-800 dark:text-gray-200">
                            <i class="fas fa-edit mr-2 text-blue-600"></i>
                            Edit Movie Information
                        </legend>

                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                            <!-- Movie Poster Upload -->
                            <div class="lg:col-span-1 space-y-4">
                                <label class="flex items-center text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    <i class="fas fa-image mr-2 text-purple-500"></i>
                                    Movie Poster
                                </label>
                                
                                <!-- Current Poster Display -->
                                <div class="poster-preview-container">
                                    <div id="posterPreview" class="w-full h-80 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-600 flex items-center justify-center relative overflow-hidden group">
                                        <?php if (!empty($movie['poster_url'])): ?>
                                            <img src="<?= htmlspecialchars($movie['poster_url']) ?>" 
                                                 alt="Movie Poster" 
                                                 class="w-full h-full object-cover rounded-lg"
                                                 id="currentPoster">
                                            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all duration-300 flex items-center justify-center">
                                                <button type="button" 
                                                        onclick="document.getElementById('poster').click()"
                                                        class="px-4 py-2 bg-blue-600 text-white rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                                    <i class="fas fa-camera mr-2"></i>Change
                                                </button>
                                            </div>
                                        <?php else: ?>
                                            <div class="text-center cursor-pointer" onclick="document.getElementById('poster').click()">
                                                <i class="fas fa-image text-4xl text-gray-400 mb-4"></i>
                                                <p class="text-gray-500 dark:text-gray-400 mb-2">Click to upload poster</p>
                                                <p class="text-xs text-gray-400 dark:text-gray-500">JPG, PNG, GIF up to 10MB</p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <!-- File Input -->
                                <input type="file" 
                                       id="poster" 
                                       name="poster" 
                                       accept="image/*" 
                                       class="hidden"
                                       onchange="previewPoster(this)">
                                
                                <!-- Upload Progress -->
                                <div id="uploadProgress" class="hidden w-full bg-gray-200 rounded-full h-2">
                                    <div id="uploadProgressBar" class="bg-blue-600 h-2 rounded-full transition-all duration-300" style="width: 0%"></div>
                                </div>
                                
                                <!-- Poster Actions -->
                                <div class="flex gap-2">
                                    <button type="button" 
                                            onclick="document.getElementById('poster').click()"
                                            class="flex-1 px-4 py-2 bg-gradient-to-r from-purple-500 to-pink-500 text-white rounded-lg hover:from-purple-600 hover:to-pink-600 transition-all duration-300">
                                        <i class="fas fa-upload mr-2"></i>Upload
                                    </button>
                                    <?php if (!empty($movie['poster_url'])): ?>
                                        <button type="button" 
                                                id="removePosterBtn"
                                                onclick="removePoster()"
                                                class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors duration-300">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                                
                                <input type="hidden" id="posterUrl" name="poster_url" value="<?= htmlspecialchars($movie['poster_url'] ?? '') ?>">
                                <input type="hidden" id="removePoster" name="remove_poster" value="0">
                            </div>

                            <!-- Movie Details -->
                            <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Movie Name -->
                                <div class="space-y-2 group">
                                    <label for="name" class="flex items-center text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        <i class="fas fa-video mr-2 text-pink-500"></i>
                                        Movie Title <span class="text-red-500 ml-1">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="text" 
                                               name="name" 
                                               id="name" 
                                               class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg 
                                                      focus:border-pink-500 focus:ring-2 focus:ring-pink-200 dark:focus:ring-pink-800 
                                                      transition-all duration-300 bg-white dark:bg-gray-700 text-gray-900 dark:text-white 
                                                      placeholder-gray-500 dark:placeholder-gray-400 group-hover:border-pink-300" 
                                               placeholder="Enter movie title..." 
                                               maxlength="100" 
                                               value="<?php echo htmlspecialchars($movie['name']); ?>"
                                               required />
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                            <i class="fas fa-check text-green-500 hidden" id="name-check"></i>
                                        </div>
                                    </div>
                                </div>

                                <!-- Movie Year -->
                                <div class="space-y-2 group">
                                    <label for="year" class="flex items-center text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        <i class="fas fa-calendar-alt mr-2 text-blue-500"></i>
                                        Release Year <span class="text-red-500 ml-1">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="number" 
                                               name="year" 
                                               id="year" 
                                               min="1900" 
                                               max="<?= date('Y') + 5 ?>" 
                                               class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg 
                                                      focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-800 
                                                      transition-all duration-300 bg-white dark:bg-gray-700 text-gray-900 dark:text-white 
                                                      placeholder-gray-500 dark:placeholder-gray-400 group-hover:border-blue-300" 
                                               value="<?php echo htmlspecialchars($movie['year']); ?>"
                                               required />
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                            <i class="fas fa-check text-green-500 hidden" id="year-check"></i>
                                        </div>
                                    </div>
                                </div>

                                <!-- Movie Genre -->
                                <div class="space-y-2 group">
                                    <label for="genre" class="flex items-center text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        <i class="fas fa-tags mr-2 text-purple-500"></i>
                                        Genre <span class="text-red-500 ml-1">*</span>
                                    </label>
                                    <div class="relative">
                                        <select id="genre" 
                                                name="genre" 
                                                class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg 
                                                       focus:border-purple-500 focus:ring-2 focus:ring-purple-200 dark:focus:ring-purple-800 
                                                       transition-all duration-300 bg-white dark:bg-gray-700 text-gray-900 dark:text-white 
                                                       appearance-none group-hover:border-purple-300"
                                                required>
                                            <option value="">Select a genre...</option>
                                            <?php foreach ($genres as $genre): ?>
                                                <option value="<?= $genre['id'] ?>" <?php if($movie['genre'] == $genre['id']) echo 'selected="selected"'; ?>>
                                                    <?= htmlspecialchars($genre['name']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                            <i class="fas fa-chevron-down text-gray-400"></i>
                                        </div>
                                    </div>
                                </div>

                                <!-- Movie Rating with Stars -->
                                <div class="space-y-2 group">
                                    <label class="flex items-center text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        <i class="fas fa-star mr-2 text-yellow-500"></i>
                                        Rating <span class="text-red-500 ml-1">*</span>
                                    </label>
                                    <div class="star-rating-container">
                                        <div class="flex items-center space-x-1 mb-2">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <button type="button" 
                                                        class="star-btn text-2xl <?= $i <= $movie['rating'] ? 'text-yellow-400' : 'text-gray-300' ?> hover:text-yellow-400 transition-colors duration-200 focus:outline-none" 
                                                        data-rating="<?= $i ?>">
                                                    <i class="fas fa-star"></i>
                                                </button>
                                            <?php endfor; ?>
                                            <span class="ml-2 text-sm text-yellow-600 dark:text-yellow-400 font-medium" id="rating-text">
                                                <?= $movie['rating'] ?> Star<?= $movie['rating'] > 1 ? 's' : '' ?>
                                            </span>
                                        </div>
                                        <input type="hidden" name="rating" id="rating" value="<?= $movie['rating'] ?>" required>
                                    </div>
                                </div>

                                <!-- Ticket Price -->
                                <div class="space-y-2 group">
                                    <label for="ticket_price" class="flex items-center text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        <i class="fas fa-dollar-sign mr-2 text-green-500"></i>
                                        Ticket Price <span class="text-red-500 ml-1">*</span>
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <span class="text-gray-500 dark:text-gray-400">$</span>
                                        </div>
                                        <input type="number" 
                                               name="ticket_price" 
                                               id="ticket_price" 
                                               step="0.01" 
                                               min="0.01" 
                                               class="w-full pl-8 pr-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg 
                                                      focus:border-green-500 focus:ring-2 focus:ring-green-200 dark:focus:ring-green-800 
                                                      transition-all duration-300 bg-white dark:bg-gray-700 text-gray-900 dark:text-white 
                                                      placeholder-gray-500 dark:placeholder-gray-400 group-hover:border-green-300" 
                                               value="<?= $movie['ticket_price'] ?>"
                                               required />
                                    </div>
                                </div>

                                <!-- Movie Date -->
                                <div class="space-y-2 group">
                                    <label for="mdate" class="flex items-center text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        <i class="fas fa-calendar mr-2 text-indigo-500"></i>
                                        Show Date <span class="text-red-500 ml-1">*</span>
                                    </label>
                                    <input type="date" 
                                           name="mdate" 
                                           id="mdate" 
                                           value="<?= $movie['mdate'] ?>" 
                                           class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg 
                                                  focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 dark:focus:ring-indigo-800 
                                                  transition-all duration-300 bg-white dark:bg-gray-700 text-gray-900 dark:text-white 
                                                  group-hover:border-indigo-300" 
                                           required />
                                </div>

                                <!-- Movie Time -->
                                <div class="space-y-2 group">
                                    <label for="mtime" class="flex items-center text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        <i class="fas fa-clock mr-2 text-orange-500"></i>
                                        Show Time <span class="text-red-500 ml-1">*</span>
                                    </label>
                                    <input type="time" 
                                           name="mtime" 
                                           id="mtime" 
                                           value="<?= $movie['mtime'] ?>" 
                                           class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg 
                                                  focus:border-orange-500 focus:ring-2 focus:ring-orange-200 dark:focus:ring-orange-800 
                                                  transition-all duration-300 bg-white dark:bg-gray-700 text-gray-900 dark:text-white 
                                                  group-hover:border-orange-300" 
                                           required />
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    
                    <div class="flex flex-col sm:flex-row gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit" 
                                name="update" 
                                id="updateBtn"
                                class="flex-1 sm:flex-none px-8 py-4 bg-gradient-to-r from-blue-600 to-indigo-600 
                                       hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-lg 
                                       shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-300 
                                       focus:outline-none focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800 
                                       disabled:opacity-50 disabled:cursor-not-allowed">
                            <i class="fas fa-save mr-2"></i>
                            <span id="updateBtnText">Update Movie</span>
                            <i class="fas fa-spinner fa-spin ml-2 hidden" id="updateSpinner"></i>
                        </button>
                        <a href="/movies/list" 
                           class="flex-1 sm:flex-none px-8 py-4 bg-gray-500 hover:bg-gray-600 text-white font-semibold 
                                  rounded-lg shadow-lg hover:shadow-xl transform hover:scale-[1.02] transition-all duration-300 
                                  text-center">
                            <i class="fas fa-times mr-2"></i>
                            Cancel
                    </div>
                </form>
            </section>

            <script src="/public/js/darkMode.js" defer></script>
            <script src="/public/js/mobileNav.js" defer></script>
            <script src="/public/js/all.min.js"></script>
            <script src="/public/js/accordion.js" defer></script>
            <script src="/crud/MovieList/js/movieForm.js"></script>
            
            <script>
                // Enhanced form functionality
                document.addEventListener('DOMContentLoaded', function() {
                    initializeStarRating();
                    initializeFormProgress();
                });

                function initializeStarRating() {
                    const starBtns = document.querySelectorAll(".star-btn");
                    const ratingInput = document.getElementById("rating");
                    const ratingText = document.getElementById("rating-text");
                    
                    let currentRating = parseInt(ratingInput.value) || 0;
                    
                    // Set initial state
                    updateStars(currentRating);
                    
                    starBtns.forEach((btn, index) => {
                        const rating = index + 1;
                        
                        btn.addEventListener("click", (e) => {
                            e.preventDefault();
                            currentRating = rating;
                            ratingInput.value = rating;
                            updateStars(rating);
                            updateRatingText(rating);
                            
                            // Add success animation
                            btn.style.transform = "scale(1.2)";
                            setTimeout(() => {
                                btn.style.transform = "scale(1)";
                            }, 150);
                        });
                        
                        btn.addEventListener("mouseenter", () => {
                            updateStars(rating, true);
                        });
                        
                        btn.addEventListener("mouseleave", () => {
                            updateStars(currentRating);
                        });
                    });
                    
                    function updateStars(rating, isHover = false) {
                        starBtns.forEach((btn, index) => {
                            const star = btn.querySelector("i");
                            if (index < rating) {
                                star.className = "fas fa-star";
                                btn.className = `star-btn text-2xl text-yellow-400 ${isHover ? 'scale-110' : ''} transition-all duration-200 focus:outline-none`;
                            } else {
                                star.className = "fas fa-star";
                                btn.className = `star-btn text-2xl text-gray-300 hover:text-yellow-400 transition-all duration-200 focus:outline-none`;
                            }
                        });
                    }
                    
                    function updateRatingText(rating) {
                        ratingText.textContent = `${rating} Star${rating > 1 ? 's' : ''}`;
                    }
                }

                function initializeFormProgress() {
                    const form = document.getElementById('editMovieForm');
                    if (!form) return;
                    
                    const inputs = form.querySelectorAll('input[required], select[required]');
                    
                    function updateProgress() {
                        let filledCount = 0;
                        inputs.forEach(input => {
                            if (input.value.trim() !== '') {
                                filledCount++;
                            }
                        });
                        
                        const progress = Math.min((filledCount / inputs.length) * 100, 100);
                        console.log(`Form progress: ${progress}%`);
                    }
                    
                    inputs.forEach(input => {
                        input.addEventListener('input', updateProgress);
                        input.addEventListener('change', updateProgress);
                    });
                    
                    updateProgress();
                }

                // Poster upload functionality
                function previewPoster(input) {
                    const file = input.files[0];
                    const preview = document.getElementById('posterPreview');
                    const progressContainer = document.getElementById('uploadProgress');
                    const progressBar = document.getElementById('uploadProgressBar');
                    
                    if (file) {
                        // Validate file type
                        if (!file.type.startsWith('image/')) {
                            showToast('Please select a valid image file.', 'error');
                            input.value = '';
                            return;
                        }
                        
                        // Validate file size (10MB limit)
                        if (file.size > 10 * 1024 * 1024) {
                            showToast('File size must be less than 10MB.', 'error');
                            input.value = '';
                            return;
                        }
                        
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            preview.innerHTML = `
                                <img src="${e.target.result}" 
                                     alt="Movie Poster Preview" 
                                     class="w-full h-full object-cover rounded-lg"
                                     id="currentPoster">
                                <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-50 transition-all duration-300 flex items-center justify-center">
                                    <button type="button" 
                                            onclick="document.getElementById('poster').click()"
                                            class="px-4 py-2 bg-blue-600 text-white rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <i class="fas fa-camera mr-2"></i>Change
                                    </button>
                                </div>
                            `;
                            
                            // Reset remove poster flag
                            const removePosterFlag = document.getElementById('removePoster');
                            if (removePosterFlag) {
                                removePosterFlag.value = '0';
                            }
                            
                            showToast('Poster preview loaded successfully!', 'success');
                        };
                        reader.readAsDataURL(file);
                    }
                }
                
                function removePoster() {
                    const preview = document.getElementById('posterPreview');
                    const posterInput = document.getElementById('poster');
                    const removePosterFlag = document.getElementById('removePoster');
                    
                    preview.innerHTML = `
                        <div class="text-center cursor-pointer" onclick="document.getElementById('poster').click()">
                            <i class="fas fa-image text-4xl text-gray-400 mb-4"></i>
                            <p class="text-gray-500 dark:text-gray-400 mb-2">Click to upload poster</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">JPG, PNG, GIF up to 10MB</p>
                        </div>
                    `;
                    
                    posterInput.value = '';
                    if (removePosterFlag) {
                        removePosterFlag.value = '1';
                    }
                    
                    showToast('Poster will be removed when you save changes.', 'info');
                }
                
                function showToast(message, type = 'info') {
                    // Create toast element
                    const toast = document.createElement('div');
                    toast.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300 ${
                        type === 'success' ? 'bg-green-500 text-white' :
                        type === 'error' ? 'bg-red-500 text-white' :
                        type === 'warning' ? 'bg-yellow-500 text-black' :
                        'bg-blue-500 text-white'
                    }`;
                    toast.innerHTML = `
                        <div class="flex items-center">
                            <i class="fas fa-${type === 'success' ? 'check' : type === 'error' ? 'exclamation-triangle' : 'info'} mr-2"></i>
                            ${message}
                        </div>
                    `;
                    
                    document.body.appendChild(toast);
                    
                    // Animate in
                    setTimeout(() => {
                        toast.style.transform = 'translateX(0)';
                    }, 100);
                    
                    // Animate out and remove
                    setTimeout(() => {
                        toast.style.transform = 'translateX(100%)';
                        setTimeout(() => {
                            document.body.removeChild(toast);
                        }, 300);
                    }, 3000);
                }

                // Enhanced Navigation Functions for Edit Movie
                function confirmDelete(movieId) {
                    if (confirm('Are you sure you want to delete this movie? This action cannot be undone.')) {
                        window.location.href = `/movies/delete?id=${movieId}`;
                    }
                }

                function resetForm() {
                    if (confirm('Are you sure you want to reset all changes?')) {
                        location.reload();
                    }
                }

                function saveDraft() {
                    const form = document.getElementById('movieForm');
                    if (form) {
                        const formData = new FormData(form);
                        const draftData = {};
                        
                        for (let [key, value] of formData.entries()) {
                            draftData[key] = value;
                        }
                        
                        localStorage.setItem('movieEditDraft', JSON.stringify(draftData));
                        showToast('Changes saved as draft!', 'success');
                    }
                }

                function previewChanges() {
                    showToast('Preview functionality coming soon!', 'info');
                }

                // Keyboard shortcuts for edit page
                document.addEventListener('keydown', function(e) {
                    if (e.altKey) {
                        switch(e.key) {
                            case 'v': // Alt+V for View Movies
                                e.preventDefault();
                                window.location.href = '/movies/list';
                                break;
                            case 'a': // Alt+A for Add Movie
                                e.preventDefault();
                                window.location.href = '/movies/add';
                                break;
                        }
                    }
                    
                    if (e.ctrlKey) {
                        switch(e.key) {
                            case 's': // Ctrl+S for Save Draft
                                e.preventDefault();
                                saveDraft();
                                break;
                            case 'z': // Ctrl+Z for Reset
                                e.preventDefault();
                                resetForm();
                                break;
                        }
                    }
                });

                // Auto-save for edit form
                document.addEventListener('DOMContentLoaded', function() {
                    const form = document.getElementById('movieForm');
                    if (form) {
                        const inputs = form.querySelectorAll('input, select');
                        let autoSaveTimeout;
                        
                        inputs.forEach(input => {
                            input.addEventListener('input', () => {
                                clearTimeout(autoSaveTimeout);
                                autoSaveTimeout = setTimeout(() => {
                                    saveDraft();
                                }, 5000); // Auto-save after 5 seconds of inactivity
                            });
                        });
                    }
                });
            </script>
            </script>
        </div>
    </div>
</body>
</html>
