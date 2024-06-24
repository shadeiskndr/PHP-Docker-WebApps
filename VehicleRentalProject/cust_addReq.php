<?php
session_start(); // Start the session.
// Check if the user is logged in
if (!isset($_SESSION['customerID'])) {
    // If not, redirect to login page
    header("Location: login_customer.php");
    exit();
}
$page_title = 'Add Vehicle Rental Request';

$connect = mysqli_connect(
    'db', # hostname
    'php_docker', # username
    'password', # password
    'php_docker' # db
);

?>

<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../output.css" rel="stylesheet">
    <title>Add Vehicle Rental Request</title>
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
                <h1 class="text-4xl font-bold">Add Vehicle Rental Request</h1>
            </header>

            <!-- Navigation Links -->
            <nav class="bg-white shadow-md rounded-lg p-4 mb-4">
                <ul class="flex space-x-4">
                    <li><a href="cust_delReq.php" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75">Delete Request</a></li>
                    <li><a class="px-3 py-2 bg-transparent text-white font-semibold"></a></li>
                    <li><a href="cust_listReq.php" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75">My Requests</a></li>
                    <li><a class="px-3 py-2 bg-transparent text-white font-semibold"></a></li>
                    <li>
                        <?php
                        // Create a login/logout link.
                        if ((isset($_SESSION['customerID'])) && (!strpos($_SERVER['PHP_SELF'], 'logout_customer.php'))) {
                            echo '<a href="logout_customer.php" class="px-4 py-2 bg-gray-800 text-white font-semibold rounded-md shadow-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-75" title="Logout">Logout</a>';
                        } else {
                            echo '<a href="login_customer.php" class="px-4 py-2 bg-gray-800 text-white font-semibold rounded-md shadow-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-75" title="Login">Login</a>';
                        }
                        ?>
                    </li>
                </ul>
            </nav>

            <main class="mt-8">
                <section class="my-8">
                    <h2 class="text-3xl font-semibold mb-4">Search a vehicle to add for rental request</h2>
                    <form action="cust_addReq.php" method="post" class="space-y-4">
                        <div>
                            <label for="vType" class="block text-lg font-medium text-gray-700">Filter records by:</label>
                            <select id="vType" name="vType" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                <option value="">vehicle type</option>
                                <?php
                                    $query = "SELECT type FROM vehicletypes";        
                                    $resultSet = @mysqli_query($connect,$query); // Run the query.
                                    while ($row = $resultSet->fetch_assoc()){
                                        $vType1 = $row["type"];
                                        echo "<option value='$vType1'>$vType1</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div>
                            <input type="submit" name="search" value="Search" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75" />
                        </div>
                    </form>
                    <?php
                    if (isset($_POST['search'])) {
                        $vType = $_POST['vType'];
                        echo "<label for='vehicleRecord' class='block text-lg font-medium text-gray-700'>Vehicle records:</label>";
                        $query = "SELECT vehicleID, vehicleType, vehicleModel, ratePerHour, ratePerDay FROM vehicle WHERE vehicleType = '$vType'";        
                        $resultSet = @mysqli_query($connect,$query); // Run the query.
                        echo "<select id='vehicleRecord' name='vehicleRecord' class='mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md'>";
                        echo "<option value=''>Choose a vehicle record</option>";
                        while ($row = $resultSet->fetch_assoc()){
                            $vehicleID1 = $row['vehicleID'];
                            $vehicleType1 = $row['vehicleType'];
                            $vehicleModel1 = $row['vehicleModel'];
                            $ratePerHour1 = $row['ratePerHour'];
                            $ratePerDay1 = $row['ratePerDay'];
                            echo "<option value=\"$vehicleID1|$ratePerHour1|$ratePerDay1\">$vehicleID1 - Type: $vehicleType1, Model: $vehicleModel1, Rate: RM$ratePerHour1/hour & RM$ratePerDay1/day</option>"; 
                        }
                        echo "</select>";
                    }
                    ?>
                </section>

                <section class="my-8">
                    <h3 class="text-3xl font-semibold mb-4">Enter the forms below to request the chosen vehicle for rental</h3>
                    <form action="cust_addReq.php" method="post" class="space-y-4">
                        <div>
                            <label for="startDate" class="block text-lg font-medium text-gray-700">Rental Start (date and time):</label>
                            <input type="datetime-local" name="startDate" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md" />
                        </div>
                        <div>
                            <label for="durationNo" class="block text-lg font-medium text-gray-700">Rental Duration (number):</label>
                            <input type="text" name="durationNo" size="10" maxlength="10" value="<?php if(isset($_POST['durationNo'])) echo $_POST['durationNo']; ?>" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md" />
                        </div>
                        <div class="flex items-center">
                            <input type="radio" id="Hour" name="hourOrDay" value="Hour" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                            <label for="Hour" class="ml-1 block text-lg font-medium text-gray-700"> Hour</label>
                        </div>
                        <div class="flex items-center">
                            <input type="radio" id="Day" name="hourOrDay" value="Day" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300 mr-2">
                            <label for="Day" class="ml-1 block text-lg font-medium text-gray-700"> Day</label>
                        </div>
                        <input type="hidden" id="status" name="status" value="Pending">
                        <div>
                            <input type="submit" name="submit" value="Request" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75" />
                        </div>
                    </form>
                    <?php
if (isset($_POST['submit'])) {
    $errorMessage = array(); // Initialize error array.

    // Validate the vehicle ID record
    if(isset($_POST['vehicleRecord']) && $_POST['vehicleRecord'] == ""){
        $vehicleID = NULL;
        $errorMessage[] = 'You forgot to choose the vehicle record!';
    } else {
        $vehicleResult = $_POST['vehicleRecord'];
        $result_explode = explode('|', $vehicleResult);
        $vehicleID = $result_explode[0];
        $ratePerHour = $result_explode[1];
        $ratePerDay = $result_explode[2];
    }

    // Validate the start date and time
    if(isset($_POST['startDate']) && strtotime($_POST['startDate'])){
        $startDate = $_POST['startDate'];
    } else {
        $errorMessage[] = "You forgot to enter the start rental date and time";
    }

    // Validate duration number
    if (!empty($_POST['durationNo'])) {
        if (is_numeric($_POST['durationNo'])) {
            if ($_POST['durationNo'] < 1){
                $durationNo = NULL;
                $errorMessage[] = 'The rental duration number is invalid!';
            } else {
                $durationNo = $_POST['durationNo'];
            }
        } else {
            $durationNo = NULL;
            $errorMessage[] = 'You must enter the rental duration number in numeric only!';
        }
    } else {
        $durationNo = NULL;
        $errorMessage[] = 'You forgot to enter the rental duration number!';
    }

    // Validate duration radio choice hour or day
    if (!isset($_POST['hourOrDay'])){
        $hourOrDay = NULL;
        $errorMessage[] = 'You forgot to select hour or day duration!';
    } else {
        $hourOrDay = $_POST['hourOrDay'];
    }

    // Assign hidden status input type form to a variable
    $status = $_POST['status'];

    // Assign session customer ID to a variable
    $customerID = $_SESSION['customerID'];

    if (empty($errorMessage)) { // If everything's okay.

        function rateCalculation($rate, $durationNo){
            $total = $rate * $durationNo;
            return $total;
        }

        switch ($hourOrDay){
            case "Hour":
                $rate = $ratePerHour;
                $totalPrice = rateCalculation($rate, $durationNo);
                $returnDate = date("Y-m-d H:i:s", strtotime("+{$durationNo} hours", strtotime($startDate)));
                break;

            case "Day":
                $rate = $ratePerDay;
                $totalPrice = rateCalculation($rate, $durationNo);
                $returnDate = date("Y-m-d H:i:s", strtotime("+{$durationNo} days", strtotime($startDate)));
                break;
        }

        // Check for previous vehicle records
        $query = "SELECT rentalID FROM rental WHERE (vehicleID = '$vehicleID' AND customerID = '$customerID')";
        $result = @mysqli_query($connect,$query); // Run the query.
        if (mysqli_num_rows($result) == 0) {

            // Make the query.
            $query = "INSERT INTO rental (startDate, returnDate, durationNo, hourOrDay, status, totalPrice, vehicleID, customerID) VALUES ('$startDate', '$returnDate', '$durationNo', '$hourOrDay', '$status', '$totalPrice', '$vehicleID', '$customerID')";
            $result = @mysqli_query($connect,$query); // Run the query.
            if ($result) { // If it ran OK. == IF TRUE

                // Print a message.
                echo '<h1 id="mainhead">Thank you!</h1>
                <p>Vehicle rental request is now added. </p><p><br /></p>'; 
                exit();

            } else { // If it did not run OK.
                echo '<h1 id="mainhead">System Error</h1>
                <p class="error">Vehicle rental request could not be added due to a system error. We apologize for any inconvenience.</p>'; // Public message.
                echo '<p>' . mysqli_error($connect)  . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
                exit();
            }
        } else { // Already registered.
            echo '<h1 id="mainhead">Error!</h1>
            <p class="error">The vehicle rental request has already been added.</p>';
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