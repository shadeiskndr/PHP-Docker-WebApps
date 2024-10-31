<?php
session_start(); // Start the session.
// Check if the user is logged in
if (!isset($_SESSION['customerID'])) {
    // If not, redirect to login page
    header("Location: login_customer.php");
    exit();
}
$page_title = 'Delete Pending Rental Request';

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
    <title>Delete Pending Rental Request</title>
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
                <h1 class="text-4xl font-bold">Delete Pending Rental Request</h1>
            </header>

            <!-- Navigation Links -->
            <nav class="bg-white shadow-md rounded-lg p-4 mb-4">
                <ul class="flex space-x-4">
                    <li><a href="cust_addReq.php" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75">Request Rental</a></li>
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
                    <h2 class="text-3xl font-semibold mb-4">Delete vehicle rental request (pending only)</h2>
                    <form action="cust_delReq.php" method="post" class="space-y-4">
                        <div>
                            <label for="requests" class="block text-lg font-medium text-gray-700">Pending rental requests:</label>
                            <select id="requests" name="requests" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                <option value="">Choose a request to delete</option>
                                <?php
                                    $customerID = $_SESSION['customerID'];
                                    $query = "SELECT v.vehicleType AS vehicleType, v.vehicleModel AS vehicleModel, r.durationNo AS durationNo, r.hourOrDay AS hourOrDay, r.totalPrice AS totalPrice, r.rentalID AS rentalID FROM vehicle AS v INNER JOIN rental AS r ON v.vehicleID = r.vehicleID WHERE r.customerID = '$customerID' AND r.status = 'Pending'";        
                                    $resultSet = @mysqli_query($connect,$query); // Run the query.
                                    while ($row = $resultSet->fetch_assoc()){
                                        $rentalID1 = $row["rentalID"];
                                        $vehicleType1 = $row["vehicleType"];
                                        $vehicleModel1 = $row["vehicleModel"];
                                        $durationNo1 = $row["durationNo"];
                                        $hourOrDay1 = $row["hourOrDay"];
                                        $totalPrice1 = $row["totalPrice"];
                                        echo "<option value='$rentalID1'>$rentalID1 - $vehicleType1/$vehicleModel1 | $durationNo1 $hourOrDay1 | RM$totalPrice1</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div>
                            <input type="submit" name="delete" value="Delete" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75" />
                        </div>
                    </form>
                </section>

                <section class="my-8">
                    <fieldset class="bg-white shadow-md rounded-lg p-4">
                        <?php
                        if (isset($_POST['delete'])) {

                            $errorMessage = array(); // Initialize error array.

                            //Validate the rental ID record
                            if(isset($_POST['requests']) && $_POST['requests'] == ""){
                                $rentalID = NULL;
                                $errorMessage[] = 'You forgot to choose the vehicle rental request!';
                            } else {
                                $rentalID = ($_POST['requests']);
                            }

                            if (empty($errorMessage)){
                                // Check that they've entered the right rental request ID record.
                                $query = "SELECT rentalID FROM rental WHERE rentalID = '$rentalID'";
                                $result = @mysqli_query ($connect,$query); // Run the query.
                                $num = mysqli_num_rows($result);
                                if (mysqli_num_rows($result) == 1) { // Match was made.
                                
                                    // Get the rental id.
                                    $row = mysqli_fetch_array($result, MYSQLI_NUM);

                                    // Make the DELETE query.
                                    $query = "DELETE FROM rental WHERE rentalID = $row[0]";        
                                    $result = @mysqli_query ($connect,$query); // Run the query.
                                    if ($result) { // If it ran OK.
                                                    
                                        // Print a message.
                                        echo '<h1 id="mainhead">Thank you!</h1>
                                        <p>The vehicle rental request has been deleted. </p><p><br /></p>';    
                                        exit();
                                        
                                    } else { // If it did not run OK.
                                        echo '<h1 id="mainhead">System Error</h1>
                                        <p class="error">The vehicle rental request could not be changed due to a system error. We apologize for any inconvenience.</p>'; // Public message.
                                        echo '<p>' . mysqli_error($connect) . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
                                        exit();
                                    }
                                        
                                } else { // Invalid rental request ID record.
                                    echo '<h1 id="mainhead">Error!</h1>
                                    <p class="error">The rental request ID record do not match those on file.</p>';
                                }
                                
                            } else { // Report the errors.
                            
                                echo '<h1 id="mainhead">Error!</h1>
                                <p class="error">The following error(s) occurred:<br />';
                                foreach ($errorMessage as $msg) { // Print each error.
                                    echo " - $msg<br />\n";
                                }
                                echo '</p><p>Please try again.</p><p><br /></p>';
                            }
                            mysqli_close($connect); // Close the database connection.
                        }
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
