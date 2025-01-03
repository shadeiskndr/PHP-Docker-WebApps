<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../output.css" rel="stylesheet">
    <title>Tax Form</title>
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
                <li><a href="speed_converter.php" class="block py-2 px-4 rounded hover:bg-blue-700">Speed Converter</a></li>
                <li><a href="tax_form.php" class="block py-2 px-4 rounded hover:bg-blue-700">Tax Calculator</a></li>
                <li><a href="BMI_form_sticky.php" class="block py-2 px-4 rounded hover:bg-blue-700">BMI Calculator</a></li>
                <li><a href="../LabWork4/biggest_num.php" class="block py-2 px-4 rounded hover:bg-blue-700">Biggest Number</a></li>
                <li><a href="../LabWork1/integers.php" class="block py-2 px-4 rounded hover:bg-blue-700">Add 3 Integers</a></li>
                <li><a href="../updateInventory/updateInventory.php" class="block py-2 px-4 rounded hover:bg-blue-700">Mawar Boutique Inventory</a></li>
                <li><a href="../CinemaTicketing/admin_listMovie.php" class="block py-2 px-4 rounded hover:bg-blue-700">Movies I Watched</a></li>
                <li><a href="../CarForSale/view_carList.php" class="block py-2 px-4 rounded hover:bg-blue-700">Cars Database</a></li>
                <li><a href="../VehicleRentalProject/homepage.php" class="block py-2 px-4 rounded hover:bg-blue-700">Vehicle Rental Project</a></li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="flex-1 p-4">
            <header class="bg-blue-600 text-white p-4 rounded mb-4">
                <h1 class="text-4xl font-bold">Tax Form</h1>
            </header>
            <form action="tax_form.php" method="post" class="bg-white shadow-md rounded-lg p-6 mb-8">
                <fieldset>
                    <legend class="text-2xl font-semibold mb-4">Calculate tax:</legend>
                    <div class="mb-4">
                        <label for="quantity" class="block text-lg font-medium text-gray-700">Quantity:</label>
                        <input type="text" name="quantity" id="quantity" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" size="20" maxlength="40"/>
                    </div>
                    <div class="mb-4">
                        <label for="price" class="block text-lg font-medium text-gray-700">Price (RM):</label>
                        <input type="text" name="price" id="price" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" size="20" maxlength="40"/>
                    </div>
                    <div class="mb-4">
                        <label for="tax" class="block text-lg font-medium text-gray-700">Tax rate (%):</label>
                        <input type="text" name="tax" id="tax" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" size="20" maxlength="40"/>
                    </div>
                </fieldset>
                <div class="mt-4">
                    <input type="submit" name="submit" value="Calculate" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75" />
                </div>
            </form>

            <?php
            if(isset($_POST['submit'])){
                // Validate quantity
                if (!empty($_POST['quantity'])) {
                    $quantity = $_POST['quantity'];
                } else {
                    $quantity = NULL;
                    echo '<p><b>You forgot to enter your quantity!</b></p>';
                }

                // Validate price
                if (!empty($_POST['price'])) {
                    $price = $_POST['price'];
                } else {
                    $price = NULL;
                    echo '<p><b>You forgot to enter your price!</b></p>';
                }

                // Validate tax
                if (!empty($_POST['tax'])) {
                    $tax = $_POST['tax'];
                } else {
                    $tax = NULL;
                    echo '<p><b>You forgot to enter your tax!</b></p>';
                }

                // If everything is okay, print the message.
                if ($quantity && $price && $tax) {
                    // Calculate the total.
                    $total = $quantity * $price;
                    $total = $total + ($total * ($tax/100)); // Calculate and add the tax.
                    $total = number_format($total, 2);
                    // Print the results.
                    echo '
                    <div class="bg-white shadow-md rounded-lg p-6">
                        <h1 class="text-2xl font-semibold mb-4"><b>Tax Calculation Result</b></h1>
                        <p>You are purchasing <b>' . $quantity . '</b> widget(s) at a cost of <b>RM' . $price . '</b> each. With tax, the total comes to <b>RM' . $total . '</b>.</p>
                    </div>';
                } else {
                    // One form element was not filled out properly.
                    echo '
                    <div class="bg-white shadow-md rounded-lg p-6">
                        <h1 class="text-2xl font-semibold mb-4"><b>Tax Calculation Result</b></h1>
                        <p><b><font color="red">Please go back and fill out the form again.</font></b></p>
                    </div>';
                }
            }
            ?>
        </div>
    </div>
</body>
</html>
