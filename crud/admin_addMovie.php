<?php
$connect = mysqli_connect(
    'db', # hostname
    'admindb', # username
    'password', # password
    'mydatabase' # db
);
$page_title = 'Add a Movie';
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

            <form action="admin_addMovie.php" method="post" class="bg-white shadow-md rounded-lg p-6 mb-8">
                <fieldset>
                    <legend class="text-2xl font-semibold mb-4">Please enter the movie information:</legend>
                    <div class="mb-4">
                        <label for="name" class="block text-lg font-medium text-gray-700">Movie Name:</label>
                        <input type="text" name="name" id="name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" size="15" maxlength="30" value="<?php if (isset($_POST['name'])) echo $_POST['name']; ?>" />
                    </div>
                    <div class="mb-4">
                        <label for="year" class="block text-lg font-medium text-gray-700">Movie Year Release:</label>
                        <input type="text" name="year" id="year" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" size="15" maxlength="30" value="<?php if (isset($_POST['year'])) echo $_POST['year']; ?>" />
                    </div>
                    <div class="mb-4">
                        <label for="genre" class="block text-lg font-medium text-gray-700">Movie Genre:</label>
                        <select id="genre" name="genre" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">Choose a movie genre</option>
                            <option value="1" <?php if(isset($_POST['genre']) && $_POST['genre'] == "1") echo 'selected="selected"'; ?> >Action/Adventure</option>
                            <option value="2" <?php if(isset($_POST['genre']) && $_POST['genre'] == "2") echo 'selected="selected"'; ?> >Comedy</option>
                            <option value="3" <?php if(isset($_POST['genre']) && $_POST['genre'] == "3") echo 'selected="selected"'; ?> >Drama</option>
                            <option value="4" <?php if(isset($_POST['genre']) && $_POST['genre'] == "4") echo 'selected="selected"'; ?> >Fantasy/Sci-Fi</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="rating" class="block text-lg font-medium text-gray-700">Movie Rating:</label>
                        <select id="rating" name="rating" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">Choose a movie rating</option>
                            <option value="1" <?php if(isset($_POST['rating']) && $_POST['rating'] == "1") echo 'selected="selected"'; ?> >1</option>
                            <option value="2" <?php if(isset($_POST['rating']) && $_POST['rating'] == "2") echo 'selected="selected"'; ?> >2</option>
                            <option value="3" <?php if(isset($_POST['rating']) && $_POST['rating'] == "3") echo 'selected="selected"'; ?> >3</option>
                            <option value="4" <?php if(isset($_POST['rating']) && $_POST['rating'] == "4") echo 'selected="selected"'; ?> >4</option>
                            <option value="5" <?php if(isset($_POST['rating']) && $_POST['rating'] == "5") echo 'selected="selected"'; ?> >5</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="ticket_price" class="block text-lg font-medium text-gray-700">Movie Ticket Price:</label>
                        <input type="text" name="ticket_price" id="ticket_price" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" size="10" maxlength="10" value="<?php if(isset($_POST['ticket_price'])) echo $_POST['ticket_price']; ?>" />
                    </div>
                    <div class="mb-4">
                        <label for="mdate" class="block text-lg font-medium text-gray-700">Movie Date:</label>
                        <input type="date" name="mdate" id="mdate" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required />
                    </div>
                    <div class="mb-4">
                        <label for="mtime" class="block text-lg font-medium text-gray-700">Movie Time:</label>
                        <input type="time" name="mtime" id="mtime" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required />
                    </div>
                </fieldset>
                <div class="mt-4">
                    <input type="submit" name="submit" value="Add" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75" />
                </div>
            </form>

            <fieldset class="bg-white shadow-md rounded-lg p-6 mb-8">
                <?php

                // Check if the form has been submitted.
                if (isset($_POST['submit'])) {
                    $errorMessage = array(); // Initialize error array.
                    
                    // Check for movie name.
                    if (empty($_POST['name'])) {
                        $errorMessage[] = 'You forgot to enter movie name.';
                    } else {
                        $name = ($_POST['name']);
                    }
                    
                    //Validate movie year
                    if (!empty($_POST['year'])) {
                        if (is_numeric($_POST['year'])) {
                            if ($_POST['year'] < 1){
                                $year = NULL;
                                $errorMessage[] = '<p>The movie year is invalid!</p>';
                            } else {
                                $year = $_POST['year'];
                            }
                        } else {
                            $year = NULL;
                            $errorMessage[] = '<p>You must enter the movie year in numeric only!</p>';
                        }
                    } else {
                        $year = NULL;
                        $errorMessage[] = '<p>You forgot to enter the movie year!</p>';
                    }
                    
                    //Validate movie genre
                    if(isset($_POST['genre']) && $_POST['genre'] == ""){
                        $genre = NULL;
                        $errorMessage[] = '<p>You forgot to choose movie genre!</p>';
                    }else{
                        $genre = $_POST['genre'];
                    }

                    //Validate movie rating
                    if(isset($_POST['rating']) && $_POST['rating'] == ""){
                        $rating = NULL;
                        $errorMessage[] = '<p>You forgot to choose movie rating!</p>';
                    }else{
                        $rating = $_POST['rating'];
                    }

                    //Validate movie ticket price
                    if (!empty($_POST['ticket_price'])) {
                        if (is_numeric($_POST['ticket_price'])) {
                            if ($_POST['ticket_price'] < 1){
                                $ticket_price = NULL;
                                $errorMessage[] = '<p>The ticket price value is invalid!</p>';
                            } else {
                                $ticket_price = $_POST['ticket_price'];
                            }
                        } else {
                            $ticket_price = NULL;
                            $errorMessage[] = '<p>You must enter the ticket price in numeric only!</p>';
                        }
                    } else {
                        $ticket_price = NULL;
                        $errorMessage[] = '<p>You forgot to enter the ticket price!</p>';
                    }
                        
                    $mdate = ($_POST['mdate']);

                    $mtime = ($_POST['mtime']);
                        
                    
                    if (empty($errorMessage)) { // If everything's okay.
                        
                        // Check for previous registration
                        $query = "SELECT id FROM movies WHERE name='$name'";
                        $result = @mysqli_query ($connect,$query); // Run the query.
                        if (mysqli_num_rows($result) == 0) {
                            
                            // Make the query.
                            $query = "INSERT INTO movies (name, year, genre, rating, ticket_price, mdate, mtime) VALUES ('$name', '$year', '$genre','$rating', '$ticket_price', '$mdate', '$mtime')";
                            $result = @mysqli_query ($connect,$query); // Run the query. // Run the query.
                            if ($result) { // If it ran OK. == IF TRUE
                                
                                // Print a message.
                                echo '<h1 class="text-2xl font-semibold mb-4">Thank you!</h1>
                                <p>Movie is now registered. </p><p><br /></p>';	
                            
                                // Include the footer and quit the script (to not show the form).
                                
                                exit();
                                
                            } else { // If it did not run OK.
                                echo '<h1 class="text-2xl font-semibold mb-4">System Error</h1>
                                <p class="text-red-500">Movie could not be registered due to a system error. We apologize for any inconvenience.</p>'; // Public message.
                                echo '<p>' . mysqli_error($connect)  . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
                                
                                exit();
                            }

                        } else { // Already registered.
                            echo '<h1 class="text-2xl font-semibold mb-4">Error!</h1>
                            <p class="text-red-500">The movie record has already been added.</p>';
                        }
                        
                    } else { // Report the errors.
                    
                        echo '<h1 class="text-2xl font-semibold mb-4">Error!</h1>
                        <p class="text-red-500">The following error(s) occurred:<br />';
                        foreach ($errorMessage as $msg) { // Print each error.
                            echo " - $msg<br />\n";
                        }
                        echo '</p><p>Please try again.</p><p><br /></p>';
                        
                    } // End of if (empty($errors)) IF.

                    mysqli_close($connect); // Close the database connection.
                        
                } // End of the main Submit conditional.
                ?>
            </fieldset>
        </div>
    </div>
</body>
</html>
