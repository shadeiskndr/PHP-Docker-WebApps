<?php

$connect = mysqli_connect(
    'localhost', # hostname
    'admindb', # username
    'password', # password
    'database' # db
);

// Set the page title and include the HTML header.
$page_title = 'List of Movies';
?>

<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../output.css" rel="stylesheet">
    <title><?php echo $page_title; ?></title>
</head>
<body class="bg-gray-100 text-gray-900">
    <div class="flex">
        <!-- Vertical Navigation Bar -->
        <nav class="w-64 bg-gray-800 text-white min-h-screen p-4">
            <h2 class="text-2xl font-bold mb-6">Navigation</h2>
            <ul class="space-y-4">
				<li><a href="../index.php" class="block py-2 px-4 rounded hover:bg-blue-700">Home</a></li>
                <li><a href="../quiz1/puteriClothes.php" class="block py-2 px-4 rounded hover:bg-blue-700">Puteri Clothes Calculator</a></li>
                <li><a href="../Assignment1/airform2.php" class="block py-2 px-4 rounded hover:bg-blue-700">Air Conditioner Calculator</a></li>
                <li><a href="../LabWorkX8/Discount.php" class="block py-2 px-4 rounded hover:bg-blue-700">Discount Calculator</a></li>
                <li><a href="../LabWork3/speed_converter.php" class="block py-2 px-4 rounded hover:bg-blue-700">Speed Converter</a></li>
                <li><a href="../LabWork3/tax_form.php" class="block py-2 px-4 rounded hover:bg-blue-700">Tax Calculator</a></li>
                <li><a href="../LabWork3/BMI_form_sticky.php" class="block py-2 px-4 rounded hover:bg-blue-700">BMI Calculator</a></li>
                <li><a href="../LabWork4/biggest_num.php" class="block py-2 px-4 rounded hover:bg-blue-700">Biggest Number</a></li>
                <li><a href="../LabWork1/integers.php" class="block py-2 px-4 rounded hover:bg-blue-700">Add 3 Integers</a></li>
                <li><a href="../updateInventory/updateInventory.php" class="block py-2 px-4 rounded hover:bg-blue-700">Mawar Boutique Inventory</a></li>
                <li><a href="admin_listMovie.php" class="block py-2 px-4 rounded hover:bg-blue-700">Movies I Watched</a></li>
                <li><a href="../CarForSale/view_carList.php" class="block py-2 px-4 rounded hover:bg-blue-700">Cars Database</a></li>
                <li><a href="../VehicleRentalProject/homepage.php" class="block py-2 px-4 rounded hover:bg-blue-700">Vehicle Rental Project</a></li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="flex-1 p-4">
            <header class="bg-blue-600 text-white p-4 rounded mb-4">
                <h1 class="text-4xl font-bold"><?php echo $page_title; ?></h1>
            </header>
            
            <!-- Navigation Links -->
            <nav class="bg-white shadow-md rounded-lg p-4 mb-4">
                <ul class="flex space-x-4">
                    <li><a href="admin_addMovie.php" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75">Add Movie</a></li>
					<li><a class="px-3 py-2 bg-transparent text-white font-semibold"></a></li>
                    <li><a href="admin_listMovie.php" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75">View Movie List</a></li>
                </ul>
            </nav>

            <form action="admin_listMovie.php" method="post" class="bg-white shadow-md rounded-lg p-6 mb-8">
                <fieldset>
                    <legend class="text-2xl font-semibold mb-4">List all or search movies by genre:</legend>
                    <div class="mb-4">
                        <label for="genre" class="block text-lg font-medium text-gray-700">Movie genre:</label>
                        <select id="genre" name="genre" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">Choose a movie genre</option>
                            <option value="1" <?php if(isset($_POST['genre']) && $_POST['genre'] == "1") echo 'selected="selected"'; ?> >Action/Adventure</option>
                            <option value="2" <?php if(isset($_POST['genre']) && $_POST['genre'] == "2") echo 'selected="selected"'; ?> >Comedy</option>
                            <option value="3" <?php if(isset($_POST['genre']) && $_POST['genre'] == "3") echo 'selected="selected"'; ?> >Drama</option>
                            <option value="4" <?php if(isset($_POST['genre']) && $_POST['genre'] == "4") echo 'selected="selected"'; ?> >Fantasy/Sci-Fi</option>
                        </select>
                    </div>
                </fieldset>
                <div class="mt-4">
                    <input type="submit" name="search" value="Search" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75" />
                    <input type="submit" name="list" value="List All" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75 ml-2" />
                </div>
            </form>

            <?php
            // Search for a genre and display the movies related to that genre
            if (isset($_POST['search'])) {

                $errorMessage = array(); // Initialize error array.

                //Validate movie genre
                if(isset($_POST['genre']) && $_POST['genre'] == ""){
                    $genre = NULL;
                    $errorMessage[] = '<p>You forgot to choose movie genre!</p>';
                }else{
                    $genre = $_POST['genre'];
                }

                if (empty($errorMessage)) { // If everything's OK.
                    //Search for movies with said movie genre.
                    $query = "SELECT m.name AS moviename, m.year AS year, g.name AS genrename, m.mdate AS moviedate, m.mtime AS movietime, m.rating AS movierating FROM movies AS m INNER JOIN genres AS g ON m.genre = g.id WHERE m.genre = $genre";
                    $result = mysqli_query($connect,$query); // Run the query.
                    $num = mysqli_num_rows($result);
                    if (mysqli_num_rows($result) > 0) { // Match was made.
                        
                        // Table header.
                        echo "<div class='bg-white shadow-md rounded-lg p-6 mb-8'>";
                        echo "<h2 class='text-2xl font-semibold mb-4'>Movies List</h2>";
                        echo "<table class='min-w-full bg-white'>
                                <thead>
                                    <tr>
                                        <th class='py-2 px-4 border-b-2 border-gray-200 bg-gray-100 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider'>Name</th>
                                        <th class='py-2 px-4 border-b-2 border-gray-200 bg-gray-100 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider'>Year</th>
                                        <th class='py-2 px-4 border-b-2 border-gray-200 bg-gray-100 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider'>Genre</th>
                                        <th class='py-2 px-4 border-b-2 border-gray-200 bg-gray-100 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider'>Date</th>
                                        <th class='py-2 px-4 border-b-2 border-gray-200 bg-gray-100 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider'>Time</th>
                                        <th class='py-2 px-4 border-b-2 border-gray-200 bg-gray-100 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider'>Rating</th>
                                    </tr>
                                </thead>
                                <tbody>";
                        
                        // Fetch and print all the records.
                        while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                            echo "<tr>
                                    <td class='py-2 px-4 border-b border-gray-200'>{$row['moviename']}</td>
                                    <td class='py-2 px-4 border-b border-gray-200'>{$row['year']}</td>
                                    <td class='py-2 px-4 border-b border-gray-200'>{$row['genrename']}</td>
                                    <td class='py-2 px-4 border-b border-gray-200'>{$row['moviedate']}</td>
                                    <td class='py-2 px-4 border-b border-gray-200'>{$row['movietime']}</td>
                                    <td class='py-2 px-4 border-b border-gray-200'>{$row['movierating']}</td>
                                  </tr>";
                        }
                        echo "</tbody></table>";
                        echo "</div>";
                        mysqli_free_result($result); // Free up the resources.    
                    } else { // If it did not run OK.
                        echo '<p class="text-red-500">There are currently no movies with the following genre.</p>';
                    }
                } else { // Report the errors.
                    echo '<div class="bg-white shadow-md rounded-lg p-6 mb-8">';
                    echo '<h1 class="text-2xl font-semibold mb-4">Error!</h1>
                    <p class="text-red-500">The following error(s) occurred:<br />';
                    foreach ($errorMessage as $msg) { // Print each error.
                        echo " - $msg<br />\n";
                    }
                    echo '</p><p>Please try again.</p><p><br /></p>';
                    echo '</div>';
                }    
            }

            // Check if the form has been submitted.
            if (isset($_POST['list'])) {

                // Make the query.
                $query = "SELECT m.name AS moviename, m.year AS year, g.name AS genrename, m.mdate AS moviedate, m.mtime AS movietime, m.rating AS movierating FROM movies AS m INNER JOIN genres AS g ON m.genre = g.id";        
                $result = mysqli_query($connect,$query); // Run the query.
                $num = mysqli_num_rows($result);

                if ($num > 0) { // If it ran OK, display the records.

                    echo "<div class='bg-white shadow-md rounded-lg p-6 mb-8'>";
                    echo "<h2 class='text-2xl font-semibold mb-4'>Movies List</h2>";
                    echo "<p>There are currently $num movies.</p>\n";

                    // Table header.
                    echo "<table class='min-w-full bg-white'>
                            <thead>
                                <tr>
                                    <th class='py-2 px-4 border-b-2 border-gray-200 bg-gray-100 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider'>Name</th>
                                    <th class='py-2 px-4 border-b-2 border-gray-200 bg-gray-100 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider'>Year</th>
                                    <th class='py-2 px-4 border-b-2 border-gray-200 bg-gray-100 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider'>Genre</th>
                                    <th class='py-2 px-4 border-b-2 border-gray-200 bg-gray-100 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider'>Date</th>
                                    <th class='py-2 px-4 border-b-2 border-gray-200 bg-gray-100 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider'>Time</th>
                                    <th class='py-2 px-4 border-b-2 border-gray-200 bg-gray-100 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider'>Rating</th>
                                </tr>
                            </thead>
                            <tbody>";
                    
                    // Fetch and print all the records.
                    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                        echo "<tr>
                                <td class='py-2 px-4 border-b border-gray-200'>{$row['moviename']}</td>
                                <td class='py-2 px-4 border-b border-gray-200'>{$row['year']}</td>
                                <td class='py-2 px-4 border-b border-gray-200'>{$row['genrename']}</td>
                                <td class='py-2 px-4 border-b border-gray-200'>{$row['moviedate']}</td>
                                <td class='py-2 px-4 border-b border-gray-200'>{$row['movietime']}</td>
                                <td class='py-2 px-4 border-b border-gray-200'>{$row['movierating']}</td>
                              </tr>";
                    }

                    echo "</tbody></table>";
                    echo "</div>";
                    mysqli_free_result($result); // Free up the resources.    

                } else { // If it did not run OK.
                    echo '<p class="text-red-500">There are currently no registered users.</p>';
                }

                mysqli_close($connect); // Close the database connection.
                    
            } // End of the main Submit conditional.
            ?>
        </div>
    </div>
</body>
</html>
