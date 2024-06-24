<?php

$connect = mysqli_connect(
    'localhost', # hostname
    'admindb', # username
    'password', # password
    'mydatabase' # db
);

$table_name = "inventorybaju";
$queryCategory = "SELECT DISTINCT category FROM inventorybaju";
$queryProduct = "SELECT DISTINCT product FROM inventorybaju";
$querySize = "SELECT DISTINCT size FROM inventorybaju";

?>

<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../output.css" rel="stylesheet">
    <title>Mawar Boutique Inventory Update</title>
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
                <li><a href="updateInventory.php" class="block py-2 px-4 rounded hover:bg-blue-700">Mawar Boutique Inventory</a></li>
                <li><a href="../CinemaTicketing/admin_listMovie.php" class="block py-2 px-4 rounded hover:bg-blue-700">Movies I Watched</a></li>
                <li><a href="../CarForSale/view_carList.php" class="block py-2 px-4 rounded hover:bg-blue-700">Cars Database</a></li>
                <li><a href="../VehicleRentalProject/homepage.php" class="block py-2 px-4 rounded hover:bg-blue-700">Vehicle Rental Project</a></li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="flex-1 p-4">
            <header class="bg-blue-600 text-white p-4 rounded mb-4">
                <h1 class="text-4xl font-bold">Mawar Boutique Inventory Update</h1>
            </header>
            <form action="updateInventory.php" method="post" class="bg-white shadow-md rounded-lg p-6 mb-8">
                <fieldset>
                    <legend class="text-2xl font-semibold mb-4">Enter the forms below to update record:</legend>
                    <div class="mb-4">
                        <label for="Category" class="block text-lg font-medium text-gray-700">Category:</label>
                        <select id="Category" name="Category" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">Choose a category</option>
                            <?php
                                $resultCategories = mysqli_query($connect, $queryCategory);
                                while ($row = mysqli_fetch_assoc($resultCategories)){
                                    $category1 = $row["category"];
                                    echo "<option value='$category1'>$category1</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="Product" class="block text-lg font-medium text-gray-700">Product:</label>
                        <select id="Product" name="Product" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">Choose a product</option>
                            <?php
                                $resultProducts = mysqli_query($connect, $queryProduct);
                                while ($row = mysqli_fetch_assoc($resultProducts)){
                                    $product1 = $row["product"];
                                    echo "<option value='$product1'>$product1</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="Size" class="block text-lg font-medium text-gray-700">Size:</label>
                        <select id="Size" name="Size" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">Choose a size</option>
                            <?php
                                $resultSize = mysqli_query($connect, $querySize);
                                while ($row = mysqli_fetch_assoc($resultSize)){
                                    $size1 = $row["size"];
                                    echo "<option value='$size1'>$size1</option>";
                                }
                            ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="Quantity" class="block text-lg font-medium text-gray-700">NEW Quantity:</label>
                        <input type="text" name="Quantity" id="Quantity" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" size="10" maxlength="10" value="<?php if(isset($_POST['Quantity'])) echo $_POST['Quantity']; ?>" />
                    </div>
                </fieldset>
                <div class="mt-4">
                    <input type="submit" name="update" value="INVENTORY UPDATE ITEM" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75" />
                </div>
            </form>

            <?php
            if (isset($_POST['update'])) {

                $errorMessage = array(); // Initialize error array.

                // Validate category.
                if (isset($_POST['Category']) && $_POST['Category'] == "") {
                    $errorMessage[] = 'You forgot to enter the category.';
                } else {
                    $category = ($_POST['Category']);
                }

                // Validate product.
                if (isset($_POST['Product']) && $_POST['Product'] == "") {
                    $errorMessage[] = 'You forgot to enter the product.';
                } else {
                    $product = ($_POST['Product']);
                }

                // Validate size.
                if (isset($_POST['Size']) && $_POST['Size'] == "") {
                    $errorMessage[] = 'You forgot to enter the size.';
                } else {
                    $size = ($_POST['Size']);
                }

                //Validate quantity
                if (!empty($_POST['Quantity'])) {
                    if (is_numeric($_POST['Quantity'])) {
                        if ($_POST['Quantity'] < 1){
                            $quantity = NULL;
                            $errorMessage[] = '<p>The NEW quantity is invalid!</p>';
                        } else {
                            $quantity = $_POST['Quantity'];
                        }
                    } else {
                        $quantity = NULL;
                        $errorMessage[] = '<p>You must enter NEW quantity in numeric only!</p>';
                    }
                } else {
                    $quantity = NULL;
                    $errorMessage[] = '<p>You forgot to enter the NEW quantity!</p>';
                }

                if (empty($errorMessage)){
                    // Check that they've entered the inventory record.
                    $queryInventoryRecord = "SELECT * FROM inventorybaju WHERE category = '$category' AND product = '$product' AND size = '$size'";
                    $responseInventoryRecord = mysqli_query($connect, $queryInventoryRecord); // Run the query.
                    $num = mysqli_num_rows($responseInventoryRecord);
                    
                    if (mysqli_num_rows($responseInventoryRecord) == 1) { // Match was made.
                    
                        // Get the record.
                        $row = mysqli_fetch_array($responseInventoryRecord, MYSQLI_NUM);

                        // Make the UPDATE query.
                        $queryUpdateRecord = "UPDATE inventorybaju SET quantity = '$quantity'  WHERE category = '$category' AND product = '$product' AND size = '$size'";        
                        $responseUpdateRecord = @mysqli_query($connect,$queryUpdateRecord); // Run the query.
                        if ($responseUpdateRecord) { // If it ran OK.
                                        
                            // Print a message.
                            echo "<div class='bg-white shadow-md rounded-lg p-6 mb-8'>";
                            echo "<h1 class='text-2xl font-semibold mb-4'>Thank you!</h1>
                            <p>The quantity of '$category': '$product': '$size' has been updated in the database. </p><p><br /></p>";	 
                            
                            // Fetch and display all records from the inventorybaju table
                            $queryAllRecords = "SELECT * FROM inventorybaju";
                            $responseAllRecords = mysqli_query($connect, $queryAllRecords);

                            if ($responseAllRecords) {
                                echo "<h2 class='text-2xl font-semibold mb-4'>Current Inventory</h2>";
                                echo "<table class='min-w-full bg-white'>
                                        <thead>
                                            <tr>
                                                <th class='py-2 px-4 border-b-2 border-gray-200 bg-gray-100 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider'>Category</th>
                                                <th class='py-2 px-4 border-b-2 border-gray-200 bg-gray-100 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider'>Product</th>
                                                <th class='py-2 px-4 border-b-2 border-gray-200 bg-gray-100 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider'>Size</th>
                                                <th class='py-2 px-4 border-b-2 border-gray-200 bg-gray-100 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider'>Quantity</th>
                                            </tr>
                                        </thead>
                                        <tbody>";
                                while ($row = mysqli_fetch_assoc($responseAllRecords)) {
                                    echo "<tr>
                                            <td class='py-2 px-4 border-b border-gray-200'>{$row['category']}</td>
                                            <td class='py-2 px-4 border-b border-gray-200'>{$row['product']}</td>
                                            <td class='py-2 px-4 border-b border-gray-200'>{$row['size']}</td>
                                            <td class='py-2 px-4 border-b border-gray-200'>{$row['quantity']}</td>
                                          </tr>";
                                }
                                echo "</tbody></table>";
                            } else {
                                echo "<p class='text-red-500'>Unable to fetch inventory records.</p>";
                            }

                            echo "</div>";
                            exit();
                            
                        } else { // If it did not run OK.
                            echo '<div class="bg-white shadow-md rounded-lg p-6 mb-8">';
                            echo '<h1 class="text-2xl font-semibold mb-4">System Error</h1>
                            <p class="text-red-500">The record could not be changed because it is not found.</p>'; // Public message.
                            echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
                            echo '</div>';
                            exit();
                        }
                            
                    } else { // Invalid record.
                        echo '<div class="bg-white shadow-md rounded-lg p-6 mb-8">';
                        echo '<h1 class="text-2xl font-semibold mb-4">Error!</h1>
                        <p class="text-red-500">The record is NOT found.</p>';
                        echo '</div>';
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
            ?>
        </div>
    </div>
</body>
</html>
