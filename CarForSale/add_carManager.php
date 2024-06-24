<?php

$page_title = 'Car Manager';

$connect = mysqli_connect(
    'localhost', # hostname
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
                <h1 class="text-4xl font-bold"><?php echo $page_title; ?></h1>
            </header>
            
            <!-- Navigation Links -->
            <nav class="bg-white shadow-md rounded-lg p-4 mb-4">
                <ul class="flex space-x-4">
                    <li><a href="add_carManager.php" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75">Add Car</a></li>
                    <li><a class="px-3 py-2 bg-transparent text-white font-semibold"></a></li>
                    <li><a href="view_carList.php" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75">View Car List</a></li>
                </ul>
            </nav>

            <form action="add_carManager.php" method="post" class="bg-white shadow-md rounded-lg p-6 mb-8">
                <fieldset>
                    <legend class="text-2xl font-semibold mb-4">Please enter the car information:</legend>
                    <div class="mb-4">
                        <label for="manufacturer" class="block text-lg font-medium text-gray-700">Manufacturer Name:</label>
                        <?php
                            $query = "SELECT name from manufacturername";		
                            $resultSet = @mysqli_query ($connect,$query); // Run the query.
                        ?>
                        <select name="manufacturer" id="manufacturer" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">Please Choose a Manufacturer</option>
                            <?php
                                while ($row = $resultSet -> fetch_assoc()){
                                    $manufacturer = $row["name"];
                                    echo "<option value='$manufacturer'>$manufacturer</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="model" class="block text-lg font-medium text-gray-700">Model Name:</label>
                        <input type="text" name="model" id="model" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" size="15" maxlength="30" value="<?php if (isset($_POST['model'])) echo $_POST['model']; ?>" />
                    </div>
                    <div class="mb-4">
                        <label for="acquisition_price" class="block text-lg font-medium text-gray-700">Acquisition Price:</label>
                        <input type="text" name="acquisition_price" id="acquisition_price" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" size="10" maxlength="10" value="<?php if(isset($_POST['acquisition_price'])) echo $_POST['acquisition_price']; ?>" />
                    </div>
                    <div class="mb-4">
                        <label for="mdate" class="block text-lg font-medium text-gray-700">Date Acquired:</label>
                        <input type="date" name="mdate" id="mdate" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required />
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
                    
                    // Validate manufacturer.
                    if (empty($_POST['manufacturer'])) {
                        $errorMessage[] = 'You forgot to enter manufacturer name.';
                    } else {
                        $manuname = ($_POST['manufacturer']);
                    }

                    // Validate model name.
                    if (empty($_POST['model'])) {
                        $errorMessage[] = 'You forgot to enter model.';
                    } else {
                        $modelname = ($_POST['model']);
                    }
                    
                    //Validate acquisition price
                    if (!empty($_POST['acquisition_price'])) {
                        if (is_numeric($_POST['acquisition_price'])) {
                            if ($_POST['acquisition_price'] < 1){
                                $price = NULL;
                                $errorMessage[] = '<p>The acquisition price is invalid!</p>';
                            } else {
                                $price = $_POST['acquisition_price'];
                            }
                        } else {
                            $price = NULL;
                            $errorMessage[] = '<p>You must enter the acquisition price in numeric only!</p>';
                        }
                    } else {
                        $price = NULL;
                        $errorMessage[] = '<p>You forgot to enter the acquisition price!</p>';
                    }
                        
                    $mdate = ($_POST['mdate']);
                        
                    if (empty($errorMessage)) { // If everything's okay.

                        // Check for previous registration
                        $query = "SELECT carForSaleID FROM carforsale WHERE modelName='$modelname'";
                        $result = @mysqli_query ($connect,$query); // Run the query.
                        if (mysqli_num_rows($result) == 0) {
                    
                            // Make the query.
                            $query = "INSERT INTO carforsale (manufacturerName, modelName, acquisitionPrice, dateAcquired) VALUES ('$manuname', '$modelname','$price', '$mdate')";
                            $result = @mysqli_query ($connect,$query); // Run the query. // Run the query.
                            if ($result) { // If it ran OK. == IF TRUE
                                
                                // Print a message.
                                echo '<h1 class="text-2xl font-semibold mb-4">Thank you!</h1>
                                <p>Car is now added. </p><p><br /></p>';	
                                exit();
                                
                            } else { // If it did not run OK.
                                echo '<h1 class="text-2xl font-semibold mb-4">System Error</h1>
                                <p class="text-red-500">You could not be registered due to a system error. We apologize for any inconvenience.</p>'; // Public message.
                                echo '<p>' . mysqli_error($connect)  . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
                                exit();
                            }
                        } else { // Already registered.
                            echo '<h1 class="text-2xl font-semibold mb-4">Error!</h1>
                            <p class="text-red-500">The record has already been added.</p>';
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
