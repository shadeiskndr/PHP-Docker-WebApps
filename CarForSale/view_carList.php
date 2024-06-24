<?php # Script 7.6 - view_users.php (2nd version after Script 7.4)
// This script retrieves all the records from the users table.

$page_title = 'View the List of Cars';

$connect = mysqli_connect(
    'localhost', # hostname
    'admindb', # username
    'password', # password
    'database' # db
);
		
// Make the query.
$query = "SELECT manufacturerName, modelName, acquisitionPrice, dateAcquired
		FROM carforsale ORDER BY manufacturerName ASC";		
$result = @mysqli_query ($connect,$query); // Run the query.
$num = mysqli_num_rows($result);

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
                <li><a href="view_carList.php" class="block py-2 px-4 rounded hover:bg-blue-700">Cars Database</a></li>
                <li><a href="../VehicleRentalProject/homepage.php" class="block py-2 px-4 rounded hover:bg-blue-700">Vehicle Rental Project</a></li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="flex-1 p-4">
            <header class="bg-blue-600 text-white p-4 rounded mb-4">
                <h1 class="text-4xl font-bold">Car List</h1>
            </header>

			<!-- Navigation Links -->
            <nav class="bg-white shadow-md rounded-lg p-4 mb-4">
                <ul class="flex space-x-4">
                    <li><a href="add_carManager.php" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75">Add Car</a></li>
                    <li><a class="px-3 py-2 bg-transparent text-white font-semibold"></a></li>
                    <li><a href="view_carList.php" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75">View Car List</a></li>
                </ul>
            </nav>

            <?php
            if ($num > 0) { // If it ran OK, display the records.

                echo "<p>There are currently $num registered cars.</p>\n";

                // Table header.
                echo '
                <div class="bg-white shadow-md rounded-lg p-6 mb-8">
                    <table class="min-w-full bg-white">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 border-b-2 border-gray-200 bg-gray-100 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Manufacturer</th>
                                <th class="py-2 px-4 border-b-2 border-gray-200 bg-gray-100 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Name</th>
                                <th class="py-2 px-4 border-b-2 border-gray-200 bg-gray-100 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Price</th>
                                <th class="py-2 px-4 border-b-2 border-gray-200 bg-gray-100 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                ';
                
                // Fetch and print all the records.
                while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                    echo '
                    <tr>
                        <td class="py-2 px-4 border-b border-gray-200">' . $row['manufacturerName'] . '</td>
                        <td class="py-2 px-4 border-b border-gray-200">' . $row['modelName'] . '</td>
                        <td class="py-2 px-4 border-b border-gray-200">' . $row['acquisitionPrice'] . '</td>
                        <td class="py-2 px-4 border-b border-gray-200">' . $row['dateAcquired'] . '</td>
                    </tr>
                    ';
                }

                echo '
                        </tbody>
                    </table>
                </div>
                ';
                
                mysqli_free_result($result); // Free up the resources.	

            } else { // If it did not run OK.
                echo '<div class="bg-white shadow-md rounded-lg p-6 mb-8">';
                echo '<h1 class="text-2xl font-semibold mb-4">Error!</h1>';
                echo '<p class="text-red-500">There are currently no registered cars.</p>';
                echo '</div>';
            }

            mysqli_close($connect); // Close the database connection.

            
            ?>
        </div>
    </div>
</body>
</html>
