<?php
session_start(); // Start the session.
// Check if the user is logged in
if (!isset($_SESSION['customerID'])) {
    // If not, redirect to login page
    header("Location: login_customer.php");
    exit();
}
$page_title = 'Rental Request List';
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
    <title>Rental Request List</title>
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
                <h1 class="text-4xl font-bold">Rental Request List</h1>
            </header>

            <!-- Navigation Links -->
            <nav class="bg-white shadow-md rounded-lg p-4 mb-4">
                <ul class="flex space-x-4">
                    <li><a href="cust_addReq.php" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75">Request Rental</a></li>
                    <li><a class="px-3 py-2 bg-transparent text-white font-semibold"></a></li>
                    <li><a href="cust_delReq.php" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75">Delete Request</a></li>
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
                    <h2 class="text-3xl font-semibold mb-4">Search vehicle rental request by status</h2>
                    <form action="cust_listReq.php" method="post" class="space-y-4">
                        <div>
                            <label for="status" class="block text-lg font-medium text-gray-700">Rental request status:</label>
                            <select id="status" name="status" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                <option value="">Choose a status</option>
                                <?php
                                    $customerID = $_SESSION['customerID'];
                                    $query = "SELECT DISTINCT status FROM rental WHERE customerID = '$customerID'";        
                                    $resultSet = @mysqli_query($connect,$query); // Run the query.
                                    while ($row = $resultSet->fetch_assoc()){
                                        $status = $row["status"];
                                        echo "<option value='$status'>$status</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div>
                            <input type="submit" name="search" value="Search" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75" />
                            <input type="submit" name="list" value="List All" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75" />
                        </div>
                    </form>
                </section>

                <section class="my-8">
                    <fieldset class="bg-white shadow-md rounded-lg p-4">
                        <?php
                        // Search for a vehicle type and display the vehicles related to that type
                        if (isset($_POST['search'])) {

                            $errorMessage = array(); // Initialize error array.

                            //Validate vehicle type
                            if(isset($_POST['status']) && $_POST['status'] == ""){
                                $statusSearch = NULL;
                                $errorMessage[] = 'You forgot to choose a status!';
                            }else{
                                $statusSearch = $_POST['status'];
                            }

                            $customerID = $_SESSION['customerID'];

                            if (empty($errorMessage)) { // If everything's OK.
                                //Search for vehicle record with vehice type.
                                $query = "SELECT v.vehicleType AS vehicleType, v.vehicleModel AS vehicleModel, r.durationNo AS durationNo, r.hourOrDay AS hourOrDay, r.startDate AS startDate, r.returnDate AS returnDate, r.totalPrice AS totalPrice, r.status AS status FROM vehicle AS v INNER JOIN rental AS r ON v.vehicleID = r.vehicleID WHERE r.customerID = '$customerID' AND r.status = '$statusSearch'";
                                $result = @mysqli_query ($connect,$query); // Run the query.
                                $num = mysqli_num_rows($result);
                                if (mysqli_num_rows($result) > 0) { // Match was made.

                                    echo "<p>There are currently $num vehicle rental requests.</p>\n";
                                    
                                    // Table header.
                                    echo '
                                    <table class="min-w-full bg-white">
                                    <thead>
                                    <tr>
                                    <th class="py-2">Type/Model</th>
                                    <th class="py-2">Duration</th>
                                    <th class="py-2">Start Date</th>
                                    <th class="py-2">Return Date</th>
                                    <th class="py-2">Total Rate (RM)</th>
                                    <th class="py-2">Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    ';
                                    
                                    // Fetch and print all the records.
                                    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                        echo '
                                        <tr>
                                        <td class="border px-4 py-2">' . $row['vehicleType'] . '/' . $row['vehicleModel'] . '</td>
                                        <td class="border px-4 py-2">' . $row['durationNo'] . ' ' . $row['hourOrDay'] . '</td>
                                        <td class="border px-4 py-2">' . $row['startDate'] . '</td>
                                        <td class="border px-4 py-2">' . $row['returnDate'] . '</td>
                                        <td class="border px-4 py-2">' . $row['totalPrice'] . '</td>
                                        <td class="border px-4 py-2">' . $row['status'] . '</td>
                                        </tr>
                                        ';
                                    }
                                    echo '</tbody></table>';
                                    mysqli_free_result($result); // Free up the resources.    
                                    

                                }else { // If it did not run OK.
                                    echo '<p class="error">There are currently no vehicle rental requests with the following status.</p>';
                                }
                            }else { // Report the errors.
                            
                            echo '<h1 id="mainhead">Error!</h1>
                            <p class="error">The following error(s) occurred:<br />';
                            foreach ($errorMessage as $msg) { // Print each error.
                                echo " - $msg<br />\n";
                            }
                            echo '</p><p>Please try again.</p><p><br /></p>';
                            }    

                        }
                        ?>

                        <?php
                        // Check if the form has been submitted.
                        if (isset($_POST['list'])) {

                            // Make the query.
                            $query = "SELECT v.vehicleType AS vehicleType, v.vehicleModel AS vehicleModel, r.durationNo AS durationNo, r.hourOrDay AS hourOrDay, r.startDate AS startDate, r.returnDate AS returnDate, r.totalPrice AS totalPrice, r.status AS status FROM vehicle AS v INNER JOIN rental AS r ON v.vehicleID = r.vehicleID WHERE r.customerID = '$customerID'";        
                            $result = @mysqli_query ($connect,$query); // Run the query.
                            $num = mysqli_num_rows($result);

                            if ($num > 0) { // If it ran OK, display the records.

                                echo "<p>There are currently $num vehicle rental requests.</p>\n";

                                // Table header.
                                echo '
                                    <table class="min-w-full bg-white">
                                    <thead>
                                    <tr>
                                    <th class="py-2">Type/Model</th>
                                    <th class="py-2">Duration</th>
                                    <th class="py-2">Start Date</th>
                                    <th class="py-2">Return Date</th>
                                    <th class="py-2">Total Rate (RM)</th>
                                    <th class="py-2">Status</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    ';
                                
                                // Fetch and print all the records.
                                while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                    echo '
                                        <tr>
                                        <td class="border px-4 py-2">' . $row['vehicleType'] . '/' . $row['vehicleModel'] . '</td>
                                        <td class="border px-4 py-2">' . $row['durationNo'] . ' ' . $row['hourOrDay'] . '</td>
                                        <td class="border px-4 py-2">' . $row['startDate'] . '</td>
                                        <td class="border px-4 py-2">' . $row['returnDate'] . '</td>
                                        <td class="border px-4 py-2">' . $row['totalPrice'] . '</td>
                                        <td class="border px-4 py-2">' . $row['status'] . '</td>
                                        </tr>
                                        ';
                                }

                                echo '</tbody></table>';
                            
                                mysqli_free_result($result); // Free up the resources.    

                            } else { // If it did not run OK.
                                echo '<p class="error">There are currently no vehicle rental requests from you.</p>';
                            }

                            mysqli_close($connect); // Close the database connection.
                                
                        } // End of the main Submit conditional.

                        ?>
                    </fieldset>
                </section>
            </main>

            <footer class="bg-blue-600 text-white p-4 text-center rounded">
                <p>&copy; 2024 Vehicle Rental Project</p>
            </footer>
        </div>
    </div>
</body>
</html>
