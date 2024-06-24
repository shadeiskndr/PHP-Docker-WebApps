<?php
session_start(); // Start the session.
// Check if the user is logged in
if (!isset($_SESSION['managerID'])) {
    // If not, redirect to login page
    header("Location: login_manager.php");
    exit();
}
$page_title = 'Update Vehicle Rental Information';

$connect = mysqli_connect(
    'localhost', # hostname
    'admindb', # username
    'password', # password
    'database' # db
);
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
                <li><a href="../CinemaTicketing/admin_listMovie.php" class="block py-2 px-4 rounded hover:bg-blue-700">Movies I Watched</a></li>
                <li><a href="../CarForSale/view_carList.php" class="block py-2 px-4 rounded hover:bg-blue-700">Cars Database</a></li>
                <li><a href="homepage.php" class="block py-2 px-4 rounded hover:bg-blue-700">Vehicle Rental Project</a></li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="flex-1 p-4">
            <header class="bg-blue-600 text-white p-4 rounded mb-4">
                <h1 class="text-4xl font-bold">Update Vehicle Rental Information</h1>
            </header>

            <!-- Navigation Links -->
            <nav class="bg-white shadow-md rounded-lg p-4 mb-4">
                <ul class="flex space-x-4">
                    <li><a href="add_vehicle.php" class="px-3 py-2 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75">Add Vehicle</a></li>
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
                    <form action="update_vehicleInfo.php" method="post" class="bg-white shadow-md rounded-lg p-6 mb-8">
                        <h3 class="text-2xl font-semibold mb-4">Search a vehicle record to update</h3>
                        <label for="vType" class="block mb-2">Filter records by:</label>
                        <?php
                            $query = "SELECT type FROM vehicletypes";        
                            $resultSet = @mysqli_query($connect,$query); // Run the query.
                        ?>
                        <select id="vType" name="vType" class="block w-full p-2 border border-gray-300 rounded mb-4">
                            <option value="">Vehicle type</option>
                            <?php
                            while ($row = $resultSet -> fetch_assoc()){
                                $vType1 = $row ["type"];
                                echo "<option value='$vType1'>$vType1</option>";
                            }
                            ?>
                        </select>
                        <input type="submit" name="search" value="Search" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75 mb-4" />
                    </form>

                    <?php
                    if (isset($_POST['search'])) {
                        $vType = $_POST['vType'];
                        echo "<form action='update_vehicleInfo.php' method='post' class='bg-white shadow-md rounded-lg p-6 mb-8'>";
                        echo "<label for='vehicleRecord' class='block mb-2'>Vehicle records:</label>";
                        
                        $query = "SELECT vehicleID, vehicleType, vehicleModel, ratePerHour, ratePerDay FROM vehicle WHERE vehicleType = '$vType'";        
                        $resultSet = @mysqli_query($connect,$query); // Run the query.

                        echo "<select id='vehicleRecord' name='vehicleRecord' class='block w-full p-2 border border-gray-300 rounded mb-4'>";
                        echo "<option value=''>Choose a vehicle record</option>";
                        while ($row = $resultSet -> fetch_assoc()){
                            $vehicleID1 = $row ['vehicleID'];
                            $vehicleType1 = $row ['vehicleType'];
                            $vehicleModel1 = $row ['vehicleModel'];
                            $ratePerHour1 = $row ['ratePerHour'];
                            $ratePerDay1 = $row ['ratePerDay'];
                            echo "<option value='$vehicleID1'>$vehicleID1 - Type: $vehicleType1, Model: $vehicleModel1, Rate: RM$ratePerHour1/hour & RM$ratePerDay1/day</option>"; 
                        }
                        echo "</select>";
                        echo "</form>";
                    }
                    ?>

                    <form action="update_vehicleInfo.php" method="post" class="bg-white shadow-md rounded-lg p-6 mb-8">
                        <h3 class="text-2xl font-semibold mb-4">Enter only ONE form to update for the chosen record</h3>
                        <label for="vehicleType" class="block mb-2">Vehicle Type:</label>
                        <?php
                        $query = "SELECT type FROM vehicletypes";        
                        $resultSet = @mysqli_query($connect,$query); // Run the query.
                        ?>
                        <select name="vehicleType" class="block w-full p-2 border border-gray-300 rounded mb-4">
                            <option value="">Please choose a vehicle type</option>
                            <?php
                            while ($row = $resultSet -> fetch_assoc()){
                                $vehicleType2 = $row ["type"];
                                echo "<option value='$vehicleType2'>$vehicleType2</option>";
                            }
                            ?>
                        </select>
                        <input type="submit" name="update1" value="Update" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75 mb-4" />
                        
                        <label for="vehicleModel" class="block mb-2">Vehicle Model:</label>
                        <input type="text" name="vehicleModel" size="15" maxlength="30" value="" class="block w-full p-2 border border-gray-300 rounded mb-4" />
                        <input type="submit" name="update2" value="Update" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75 mb-4" />
                        
                        <label for="HourRate" class="block mb-2">Rental Rate Per Hour:</label>
                        <input type="text" name="HourRate" size="10" maxlength="10" value="" class="block w-full p-2 border border-gray-300 rounded mb-4" />
                        <input type="submit" name="update3" value="Update" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75 mb-4" />
                        
                        <label for="DayRate" class="block mb-2">Rental Rate Per Day:</label>
                        <input type="text" name="DayRate" size="10" maxlength="10" value="" class="block w-full p-2 border border-gray-300 rounded mb-4" />
                        <input type="submit" name="update4" value="Update" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75 mb-4" />
                    </form>

                    <?php
if (isset($_POST['update1'])) {
    $errorMessage = array(); // Initialize error array.

    // Validate vehicle type.
    if (isset($_POST['vehicleType']) && $_POST['vehicleType'] == "") {
        $errorMessage[] = 'You forgot to enter the vehicle type.';
    } else {
        $vehicleType = ($_POST['vehicleType']);
    }

    // Validate the vehicle ID record
    if (!isset($_POST['vehicleRecord']) || $_POST['vehicleRecord'] == "") {
        $vehicleID = NULL;
        $errorMessage[] = 'You forgot to choose the vehicle record!';
    } else {
        $vehicleID = ($_POST['vehicleRecord']);
    }

    if (empty($errorMessage)) {
        // Check that they've entered the right vehicle ID record.
        $query = "SELECT vehicleID FROM vehicle WHERE vehicleID = $vehicleID";
        $result = @mysqli_query($connect, $query); // Run the query.
        $num = mysqli_num_rows($result);
        if (mysqli_num_rows($result) == 1) { // Match was made.
            // Get the vehicle id.
            $row = mysqli_fetch_array($result, MYSQLI_NUM);

            // Make the UPDATE query.
            $query = "UPDATE vehicle SET vehicleType = '$vehicleType' WHERE vehicleID = $row[0]";
            $result = @mysqli_query($connect, $query); // Run the query.
            if ($result) { // If it ran OK.
                // Print a message.
                echo '<div class="bg-white shadow-md rounded-lg p-6 mb-8">';
                echo '<h1 class="text-2xl font-semibold mb-4">Thank you!</h1>';
                echo '<p>The vehicle type has been updated.</p>';
                echo '</div>';
                exit();
            } else { // If it did not run OK.
                echo '<div class="bg-white shadow-md rounded-lg p-6 mb-8">';
                echo '<h1 class="text-2xl font-semibold mb-4">System Error</h1>';
                echo '<p class="text-red-500">The vehicle type could not be changed due to a system error. We apologize for any inconvenience.</p>';
                echo '<p>' . mysqli_error($connect) . '<br /><br />Query: ' . $query . '</p>';
                echo '</div>';
                exit();
            }
        } else { // Invalid vehicle ID record.
            echo '<div class="bg-white shadow-md rounded-lg p-6 mb-8">';
            echo '<h1 class="text-2xl font-semibold mb-4">Error!</h1>';
            echo '<p class="text-red-500">The vehicle ID record do not match those on file.</p>';
            echo '</div>';
        }
    } else { // Report the errors.
        echo '<div class="bg-white shadow-md rounded-lg p-6 mb-8">';
        echo '<h1 class="text-2xl font-semibold mb-4">Error!</h1>';
        echo '<p class="text-red-500">The following error(s) occurred:<br />';
        foreach ($errorMessage as $msg) { // Print each error.
            echo " - $msg<br />\n";
        }
        echo '</p><p>Please try again.</p>';
        echo '</div>';
    }
    mysqli_close($connect); // Close the database connection.
}

if (isset($_POST['update2'])) {
    $errorMessage = array(); // Initialize error array.

    // Validate vehicle model.
    if (empty($_POST['vehicleModel'])) {
        $errorMessage[] = 'You forgot to enter the vehicle model.';
    } else {
        $vehicleModel = ($_POST['vehicleModel']);
    }

    // Validate the vehicle ID record
    if (!isset($_POST['vehicleRecord']) || $_POST['vehicleRecord'] == "") {
        $vehicleID = NULL;
        $errorMessage[] = 'You forgot to choose the vehicle record!';
    } else {
        $vehicleID = ($_POST['vehicleRecord']);
    }

    if (empty($errorMessage)) {
        // Check that they've entered the right vehicle ID record.
        $query = "SELECT vehicleID FROM vehicle WHERE vehicleID = $vehicleID";
        $result = @mysqli_query($connect, $query); // Run the query.
        $num = mysqli_num_rows($result);
        if (mysqli_num_rows($result) == 1) { // Match was made.
            // Get the vehicle id.
            $row = mysqli_fetch_array($result, MYSQLI_NUM);

            // Make the UPDATE query.
            $query = "UPDATE vehicle SET vehicleModel = '$vehicleModel' WHERE vehicleID = $row[0]";
            $result = @mysqli_query($connect, $query); // Run the query.
            if ($result) { // If it ran OK.
                // Print a message.
                echo '<div class="bg-white shadow-md rounded-lg p-6 mb-8">';
                echo '<h1 class="text-2xl font-semibold mb-4">Thank you!</h1>';
                echo '<p>The vehicle model has been updated.</p>';
                echo '</div>';
                exit();
            } else { // If it did not run OK.
                echo '<div class="bg-white shadow-md rounded-lg p-6 mb-8">';
                echo '<h1 class="text-2xl font-semibold mb-4">System Error</h1>';
                echo '<p class="text-red-500">The vehicle model could not be changed due to a system error. We apologize for any inconvenience.</p>';
                echo '<p>' . mysqli_error($connect) . '<br /><br />Query: ' . $query . '</p>';
                echo '</div>';
                exit();
            }
        } else { // Invalid vehicle ID record.
            echo '<div class="bg-white shadow-md rounded-lg p-6 mb-8">';
            echo '<h1 class="text-2xl font-semibold mb-4">Error!</h1>';
            echo '<p class="text-red-500">The vehicle ID record do not match those on file.</p>';
            echo '</div>';
        }
    } else { // Report the errors.
        echo '<div class="bg-white shadow-md rounded-lg p-6 mb-8">';
        echo '<h1 class="text-2xl font-semibold mb-4">Error!</h1>';
        echo '<p class="text-red-500">The following error(s) occurred:<br />';
        foreach ($errorMessage as $msg) { // Print each error.
            echo " - $msg<br />\n";
        }
        echo '</p><p>Please try again.</p>';
        echo '</div>';
    }
    mysqli_close($connect); // Close the database connection.
}

if (isset($_POST['update3'])) {
    $errorMessage = array(); // Initialize error array.

    // Validate rate per hour
    if (!empty($_POST['HourRate'])) {
        if (is_numeric($_POST['HourRate'])) {
            if ($_POST['HourRate'] < 1) {
                $ratePerHour = NULL;
                $errorMessage[] = 'The rental rate per hour is invalid!';
            } else {
                $ratePerHour = $_POST['HourRate'];
            }
        } else {
            $ratePerHour = NULL;
            $errorMessage[] = 'You must enter the rental rate per hour in numeric only!';
        }
    } else {
        $ratePerHour = NULL;
        $errorMessage[] = 'You forgot to enter the rental rate per hour!';
    }

    // Validate the vehicle ID record
    if (!isset($_POST['vehicleRecord']) || $_POST['vehicleRecord'] == "") {
        $vehicleID = NULL;
        $errorMessage[] = 'You forgot to choose the vehicle record!';
    } else {
        $vehicleID = ($_POST['vehicleRecord']);
    }

    if (empty($errorMessage)) {
        // Check that they've entered the right vehicle ID record.
        $query = "SELECT vehicleID FROM vehicle WHERE vehicleID = $vehicleID";
        $result = @mysqli_query($connect, $query); // Run the query.
        $num = mysqli_num_rows($result);
        if (mysqli_num_rows($result) == 1) { // Match was made.
            // Get the vehicle id.
            $row = mysqli_fetch_array($result, MYSQLI_NUM);

            // Make the UPDATE query.
            $query = "UPDATE vehicle SET ratePerHour = '$ratePerHour' WHERE vehicleID = $row[0]";
            $result = @mysqli_query($connect, $query); // Run the query.
            if ($result) { // If it ran OK.
                // Print a message.
                echo '<div class="bg-white shadow-md rounded-lg p-6 mb-8">';
                echo '<h1 class="text-2xl font-semibold mb-4">Thank you!</h1>';
                echo '<p>The rental rate per hour has been updated.</p>';
                echo '</div>';
                exit();
            } else { // If it did not run OK.
                echo '<div class="bg-white shadow-md rounded-lg p-6 mb-8">';
                echo '<h1 class="text-2xl font-semibold mb-4">System Error</h1>';
                echo '<p class="text-red-500">The rental rate per hour could not be changed due to a system error. We apologize for any inconvenience.</p>';
                echo '<p>' . mysqli_error($connect) . '<br /><br />Query: ' . $query . '</p>';
                echo '</div>';
                exit();
            }
        } else { // Invalid vehicle ID record.
            echo '<div class="bg-white shadow-md rounded-lg p-6 mb-8">';
            echo '<h1 class="text-2xl font-semibold mb-4">Error!</h1>';
            echo '<p class="text-red-500">The vehicle ID record do not match those on file.</p>';
            echo '</div>';
        }
    } else { // Report the errors.
        echo '<div class="bg-white shadow-md rounded-lg p-6 mb-8">';
        echo '<h1 class="text-2xl font-semibold mb-4">Error!</h1>';
        echo '<p class="text-red-500">The following error(s) occurred:<br />';
        foreach ($errorMessage as $msg) { // Print each error.
            echo " - $msg<br />\n";
        }
        echo '</p><p>Please try again.</p>';
        echo '</div>';
    }
    mysqli_close($connect); // Close the database connection.
}

if (isset($_POST['update4'])) {
    $errorMessage = array(); // Initialize error array.

    // Validate rate per day
    if (!empty($_POST['DayRate'])) {
        if (is_numeric($_POST['DayRate'])) {
            if ($_POST['DayRate'] < 1) {
                $ratePerDay = NULL;
                $errorMessage[] = 'The rental rate per day is invalid!';
            } else {
                $ratePerDay = $_POST['DayRate'];
            }
        } else {
            $ratePerDay = NULL;
            $errorMessage[] = 'You must enter the rental rate per day in numeric only!';
        }
    } else {
        $ratePerDay = NULL;
        $errorMessage[] = 'You forgot to enter the rental rate per day!';
    }

    // Validate the vehicle ID record
    if (!isset($_POST['vehicleRecord']) || $_POST['vehicleRecord'] == "") {
        $vehicleID = NULL;
        $errorMessage[] = 'You forgot to choose the vehicle record!';
    } else {
        $vehicleID = ($_POST['vehicleRecord']);
    }

    if (empty($errorMessage)) {
        // Check that they've entered the right vehicle ID record.
        $query = "SELECT vehicleID FROM vehicle WHERE vehicleID = $vehicleID";
        $result = @mysqli_query($connect, $query); // Run the query.
        $num = mysqli_num_rows($result);
        if (mysqli_num_rows($result) == 1) { // Match was made.
            // Get the vehicle id.
            $row = mysqli_fetch_array($result, MYSQLI_NUM);

            // Make the UPDATE query.
            $query = "UPDATE vehicle SET ratePerDay = '$ratePerDay' WHERE vehicleID = $row[0]";
            $result = @mysqli_query($connect, $query); // Run the query.
            if ($result) { // If it ran OK.
                // Print a message.
                echo '<div class="bg-white shadow-md rounded-lg p-6 mb-8">';
                echo '<h1 class="text-2xl font-semibold mb-4">Thank you!</h1>';
                echo '<p>The rental rate per day has been updated.</p>';
                echo '</div>';
                exit();
            } else { // If it did not run OK.
                echo '<div class="bg-white shadow-md rounded-lg p-6 mb-8">';
                echo '<h1 class="text-2xl font-semibold mb-4">System Error</h1>';
                echo '<p class="text-red-500">The rental rate per day could not be changed due to a system error. We apologize for any inconvenience.</p>';
                echo '<p>' . mysqli_error($connect) . '<br /><br />Query: ' . $query . '</p>';
                echo '</div>';
                exit();
            }
        } else { // Invalid vehicle ID record.
            echo '<div class="bg-white shadow-md rounded-lg p-6 mb-8">';
            echo '<h1 class="text-2xl font-semibold mb-4">Error!</h1>';
            echo '<p class="text-red-500">The vehicle ID record do not match those on file.</p>';
            echo '</div>';
        }
    } else { // Report the errors.
        echo '<div class="bg-white shadow-md rounded-lg p-6 mb-8">';
        echo '<h1 class="text-2xl font-semibold mb-4">Error!</h1>';
        echo '<p class="text-red-500">The following error(s) occurred:<br />';
        foreach ($errorMessage as $msg) { // Print each error.
            echo " - $msg<br />\n";
        }
        echo '</p><p>Please try again.</p>';
        echo '</div>';
    }
    mysqli_close($connect); // Close the database connection.
}
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
