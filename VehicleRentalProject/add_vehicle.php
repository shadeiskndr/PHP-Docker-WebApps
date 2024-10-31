<?php
session_start(); // Start the session.

// Check if the user is logged in
if (!isset($_SESSION['managerID'])) {
    // If not, redirect to login page
    header("Location: login_manager.php");
    exit();
}

$page_title = 'Add Vehicle';

$connect = mysqli_connect(
    'db', # hostname
    'admindb', # username
    'password', # password
    'mydatabase' # db
);

?>

<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../output.css" rel="stylesheet">
    <title>Add Vehicle</title>
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
                <li><a href="../CinemaTicketing/admin_listMovie.php" class="block py-2 px-4 rounded hover:bg-blue-700">Movies I Watched</a></li>
                <li><a href="../CarForSale/view_carList.php" class="block py-2 px-4 rounded hover:bg-blue-700">Cars Database</a></li>
                <li><a href="homepage.php" class="block py-2 px-4 rounded hover:bg-blue-700">Vehicle Rental Project</a></li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="flex-1 p-4">
            <header class="bg-blue-600 text-white p-4 rounded mb-4">
                <h1 class="text-4xl font-bold">Add Vehicle</h1>
            </header>

            <!-- Navigation Links -->
            <nav class="bg-white shadow-md rounded-lg p-4 mb-4">
                <ul class="flex space-x-4">
                    <li><a href="update_vehicleInfo.php" class="px-3 py-2 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75">Update Vehicle Info</a></li>
                    <li><a class="px-3 bg-transparent text-white font-semibold"></a></li>
                    <li><a href="delete_vehicle.php" class="px-3 py-2 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75">Delete Vehicle</a></li>
                    <li><a class="px-3 bg-transparent text-white font-semibold"></a></li>
                    <li><a href="list_vehicle.php" class="px-3 py-2 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75">List Vehicles</a></li>
                    <li><a class="px-3 bg-transparent text-white font-semibold"></a></li>
                    <li><a href="mngr_listReq.php" class="px-3 py-2 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75">Requests List</a></li>
                    <li><a class="px-3 bg-transparent text-white font-semibold"></a></li>
                    <li><a href="appv_rentalReq.php" class="px-3 py-2 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75">Approve Request</a></li>
                    <li><a class="px-3 bg-transparent text-white font-semibold"></a></li>
                    <li>
                        <?php
                        // Create a login/logout link.
                        if ((isset($_SESSION['managerID'])) && (!strpos($_SERVER['PHP_SELF'], 'logout_manager.php'))) {
                            echo '<a href="logout_manager.php" class="px-3 py-2 bg-gray-800 text-white font-semibold rounded-md shadow-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-75" title="Logout">Logout</a>';
                        } else {
                            echo '<a href="login_manager.php" class="px-3 py-2 bg-gray-800 text-white font-semibold rounded-md shadow-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-75" title="Login">Login</a>';
                        }
                        ?>
                    </li>
                </ul>
            </nav>

            <main class="mt-8">
                <section class="my-8">
                    <h2 class="text-3xl font-semibold mb-4">Please enter vehicle information</h2>
                    <form action="add_vehicle.php" method="post" class="space-y-4">
                        <div>
                            <label for="vehicleType" class="block text-lg font-medium text-gray-700">Vehicle Type:</label>
                            <?php
                                $query = "SELECT type FROM vehicletypes";
                                $resultSet = @mysqli_query($connect, $query);
                            ?>
                            <select name="vehicleType" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                <option value="">Please choose a vehicle type</option>
                                <?php
                                    while ($row = $resultSet->fetch_assoc()){
                                        $vehicleType = $row["type"];
                                        echo "<option value='$vehicleType'>$vehicleType</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div>
                            <label for="vehicleModel" class="block text-lg font-medium text-gray-700">Vehicle Model:</label>
                            <input type="text" name="vehicleModel" size="15" maxlength="30" value="<?php if (isset($_POST['vehicleModel'])) echo $_POST['vehicleModel']; ?>" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md" />
                        </div>
                        <div>
                            <label for="HourRate" class="block text-lg font-medium text-gray-700">Rental Rate Per Hour:</label>
                            <input type="text" name="HourRate" size="10" maxlength="10" value="<?php if(isset($_POST['HourRate'])) echo $_POST['HourRate']; ?>" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md" />
                        </div>
                        <div>
                            <label for="DayRate" class="block text-lg font-medium text-gray-700">Rental Rate Per Day:</label>
                            <input type="text" name="DayRate" size="10" maxlength="10" value="<?php if(isset($_POST['DayRate'])) echo $_POST['DayRate']; ?>" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md" />
                        </div>
                        <div>
                            <input type="submit" name="submit" value="Add" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75" />
                        </div>
                    </form>
					<?php
if (isset($_POST['submit'])) {
    $errorMessage = array(); // Initialize error array.

    // Validate vehicle type
    if(isset($_POST['vehicleType']) && $_POST['vehicleType'] == ""){
        $vehicleTypeAdd = NULL;
        $errorMessage[] = 'You forgot to choose the vehicle type!';
    } else {
        $vehicleTypeAdd = $_POST['vehicleType'];
    }

    // Validate model name.
    if (empty($_POST['vehicleModel'])) {
        $errorMessage[] = 'You forgot to enter vehicle model.';
    } else {
        $vehicleModel = ($_POST['vehicleModel']);
    }
    
    // Validate rate per hour
    if (!empty($_POST['HourRate'])) {
        if (is_numeric($_POST['HourRate'])) {
            if ($_POST['HourRate'] < 1){
                $HourRate = NULL;
                $errorMessage[] = 'The rental rate per hour is invalid!';
            } else {
                $HourRate = $_POST['HourRate'];
            }
        } else {
            $HourRate = NULL;
            $errorMessage[] = 'You must enter the rental rate per hour in numeric only!';
        }
    } else {
        $HourRate = NULL;
        $errorMessage[] = 'You forgot to enter the rental rate per hour!';
    }

    // Validate rate per day
    if (!empty($_POST['DayRate'])) {
        if (is_numeric($_POST['DayRate'])) {
            if ($_POST['DayRate'] < 1){
                $DayRate = NULL;
                $errorMessage[] = 'The rental rate per day is invalid!';
            } else {
                $DayRate = $_POST['DayRate'];
            }
        } else {
            $DayRate = NULL;
            $errorMessage[] = 'You must enter the rental rate per day in numeric only!';
        }
    } else {
        $DayRate = NULL;
        $errorMessage[] = 'You forgot to enter the rental rate per day!';
    }
        
    if (empty($errorMessage)) { // If everything's okay.

        // Check for previous vehicle records
        $query = "SELECT vehicleID FROM vehicle WHERE vehicleModel='$vehicleModel'";
        $result = @mysqli_query($connect, $query); // Run the query.
        if (mysqli_num_rows($result) == 0) {
    
            // Make the query.
            $query = "INSERT INTO vehicle (vehicleType, vehicleModel, ratePerHour, ratePerDay) VALUES ('$vehicleTypeAdd', '$vehicleModel','$HourRate', '$DayRate')";
            $result = @mysqli_query($connect, $query); // Run the query.
            if ($result) { // If it ran OK. == IF TRUE
                
                // Print a message.
                echo '<h1 id="mainhead">Thank you!</h1>
                <p>Vehicle is now added. </p><p><br /></p>';    
        
                exit();
                
            } else { // If it did not run OK.
                echo '<h1 id="mainhead">System Error</h1>
                <p class="error">Vehicle could not be added due to a system error. We apologize for any inconvenience.</p>'; // Public message.
                echo '<p>' . mysqli_error($connect)  . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
                exit();
            }
        } else { // Already registered.
            echo '<h1 id="mainhead">Error!</h1>
            <p class="error">The vehicle record has already been added.</p>';
        }
        
    } else { // Report the errors.
    
        echo '<h1 id="mainhead">Error!</h1>
        <p class="error">The following error(s) occurred:<br />';
        foreach ($errorMessage as $msg) { // Print each error.
            echo " - $msg<br />\n";
        }
        echo '</p><p>Please try again.</p><p><br /></p>';
        
    } // End of if (empty($errors)) IF.

    mysqli_close($connect); // Close the database connection.
        
} // End of the main Submit conditional.
?>

                </section>
				
            </main>
            <footer class="bg-blue-600 text-white p-4 text-center rounded">
                <p>&copy; 2024 Vehicle Rental Project</p>
            </footer>
        </div>
    </div>
</body>
</html>