<?php
session_start(); // Start the session.
$page_title = 'Public Vehicle List';
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
    <title>Vehicle List</title>
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
                <h1 class="text-4xl font-bold">Vehicle List</h1>
            </header>

            <main class="mt-8">
                <section class="my-8">
                    <h2 class="text-3xl font-semibold mb-4">List all or search vehicle by type</h2>
                    <form action="public_vehicle_list.php" method="post" class="space-y-4">
                        <div>
                            <label for="vehicleType" class="block text-lg font-medium text-gray-700">Vehicle type:</label>
                            <select id="vehicleType" name="vehicleType" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md">
                                <option value="">Choose a vehicle type</option>
                                <?php
                                    $resultSet = mysqli_query($connect, "SELECT DISTINCT vehicleType FROM vehicle");
                                    while ($row = $resultSet->fetch_assoc()){
                                        $vehicleType = $row["vehicleType"];
                                        echo "<option value='$vehicleType'>$vehicleType</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div>
                            <input type="submit" name="search" value="Search" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75" />
                            <input type="submit" name="publiclist" value="List All" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75" />
                        </div>
                    </form>
                </section>

                <section class="my-8">
                    <fieldset class="bg-white shadow-md rounded-lg p-4">
                        <?php
                        // Search for a vehicle type and display the vehicles related to that type
                        if (isset($_POST['search'])) {

                            $errorMessage = array(); // Initialize error array.

                            // Validate vehicle type
                            if(isset($_POST['vehicleType']) && $_POST['vehicleType'] == ""){
                                $vehicleTypeSearch = NULL;
                                $errorMessage[] = 'You forgot to choose vehicle type!';
                            }else{
                                $vehicleTypeSearch = $_POST['vehicleType'];
                            }

                            if (empty($errorMessage)) { // If everything's OK.
                                // Search for vehicle record with vehicle type.
                                $query = "SELECT vehicleID, vehicleType, vehicleModel, ratePerHour, ratePerDay FROM vehicle WHERE vehicleType = '$vehicleTypeSearch'";
                                $result = @mysqli_query ($connect,$query); // Run the query.
                                $num = mysqli_num_rows($result);
                                if ($num > 0) { // Match was made.

                                    echo "<p>There are currently $num vehicle records.</p>\n";
                                    
                                    // Table header.
                                    echo '
                                    <table class="min-w-full bg-white">
                                    <thead>
                                    <tr>
                                    <th class="py-2">ID</th>
                                    <th class="py-2">Type</th>
                                    <th class="py-2">Model</th>
                                    <th class="py-2">Hour Rate (RM)</th>
                                    <th class="py-2">Day Rate (RM)</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    ';
                                    
                                    // Fetch and print all the records.
                                    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                        echo '
                                        <tr>
                                        <td class="border px-4 py-2">' . $row['vehicleID'] . '</td>
                                        <td class="border px-4 py-2">' . $row['vehicleType'] . '</td>
                                        <td class="border px-4 py-2">' . $row['vehicleModel'] . '</td>
                                        <td class="border px-4 py-2">' . $row['ratePerHour'] . '</td>
                                        <td class="border px-4 py-2">' . $row['ratePerDay'] . '</td>
                                        </tr>
                                        ';
                                    }
                                    echo '</tbody></table>';
                                    mysqli_free_result($result); // Free up the resources.    
                                    

                                }else { // If it did not run OK.
                                    echo '<p class="error">There are currently no vehicles with the following type.</p>';
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
                        if (isset($_POST['publiclist'])) {

                            // Make the query.
                            $query = "SELECT vehicleID, vehicleType, vehicleModel, ratePerHour, ratePerDay FROM vehicle";        
                            $result = @mysqli_query ($connect,$query); // Run the query.
                            $num = mysqli_num_rows($result);

                            if ($num > 0) { // If it ran OK, display the records.

                                echo "<p>There are currently $num vehicle records.</p>\n";

                                // Table header.
                                echo '
                                    <table class="min-w-full bg-white">
                                    <thead>
                                    <tr>
                                    <th class="py-2">ID</th>
                                    <th class="py-2">Type</th>
                                    <th class="py-2">Model</th>
                                    <th class="py-2">Hour Rate (RM)</th>
                                    <th class="py-2">Day Rate (RM)</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    ';
                                
                                // Fetch and print all the records.
                                while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
                                    echo '
                                        <tr>
                                        <td class="border px-4 py-2">' . $row['vehicleID'] . '</td>
                                        <td class="border px-4 py-2">' . $row['vehicleType'] . '</td>
                                        <td class="border px-4 py-2">' . $row['vehicleModel'] . '</td>
                                        <td class="border px-4 py-2">' . $row['ratePerHour'] . '</td>
                                        <td class="border px-4 py-2">' . $row['ratePerDay'] . '</td>
                                        </tr>
                                        ';
                                }

                                echo '</tbody></table>';
                            
                                mysqli_free_result($result); // Free up the resources.    

                            } else { // If it did not run OK.
                                echo '<p class="error">There are currently no registered vehicles in the database.</p>';
                            }

                            mysqli_close($connect); // Close the database connection.
                                
                        } // End of the main Submit conditional.

                        ?>
                    </fieldset>
                </section>
            </main>

            <!-- Navigation Links -->
            <nav class="bg-white shadow-md rounded-lg p-4 mb-4">
                <ul class="flex space-x-4">
                    <li><a href="homepage.php" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75">Return to Main</a></li>
                </ul>
            </nav>

            <footer class="bg-blue-600 text-white p-4 text-center rounded">
                <p>&copy; 2024 Vehicle Rental Project</p>
            </footer>
        </div>
    </div>
</body>
</html>
