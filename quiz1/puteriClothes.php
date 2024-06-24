<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../output.css" rel="stylesheet">
    <title>Puteri Clothes Calculator</title>
</head>
<body class="bg-gray-100 text-gray-900">
    <div class="flex">
        <!-- Vertical Navigation Bar -->
        <nav class="w-64 bg-gray-800 text-white min-h-screen p-4">
            <h2 class="text-2xl font-bold mb-6">Navigation</h2>
            <ul class="space-y-4">
                <li><a href="../index.php" class="block py-2 px-4 rounded hover:bg-blue-700">Home</a></li>
                <li><a href="puteriClothes.php" class="block py-2 px-4 rounded hover:bg-blue-700">Puteri Clothes Calculator</a></li>
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
                <li><a href="../VehicleRentalProject/homepage.php" class="block py-2 px-4 rounded hover:bg-blue-700">Vehicle Rental Project</a></li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="flex-1 p-4">
            <header class="bg-blue-600 text-white p-4 rounded mb-4">
                <h1 class="text-4xl font-bold">Puteri Clothes Calculator</h1>
            </header>
            <form action="puteriClothes.php" method="post" class="bg-white shadow-md rounded-lg p-6 mb-8">
                <fieldset>
                    <legend class="text-2xl font-semibold mb-4">Please enter the clothes' code and the quantity in the form below:</legend>
                    <div class="mb-4">
                        <label for="code" class="block text-lg font-medium text-gray-700">Clothes' code:</label>
                        <input type="text" name="code" id="code" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" size="20" maxlength="40" />
                    </div>
                    <div class="mb-4">
                        <label for="quantity" class="block text-lg font-medium text-gray-700">Quantity:</label>
                        <input type="text" name="quantity" id="quantity" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" size="20" maxlength="40" />
                    </div>
                </fieldset>
                <div class="mt-4">
                    <input type="submit" name="submit" value="Calculate" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75" />
                </div>
            </form>

            <?php
            if(isset($_REQUEST['submit'])){
                // Validate clothes' code
                if (!empty($_POST['code'])) {
                    if (is_numeric($_POST['code'])) {
                        if ($_POST['code'] > 4 || $_POST['code'] < 1) {
                            $code = NULL;
                            echo "<p class='text-red-500'><b>The code is invalid!</b></p>";
                        } else {
                            $code = $_POST['code'];
                        }
                    } else {
                        $code = NULL;
                        echo "<p class='text-red-500'><b>You must enter your clothes' code in numeric only!</b></p>";
                    }
                } else {
                    $code = NULL;
                    echo "<p class='text-red-500'><b>You forgot to enter your clothes' code!</b></p>";
                }

                // Validate quantity
                if (!empty($_POST['quantity'])) {
                    if (is_numeric($_POST['quantity'])) {
                        if ($_POST['quantity'] < 1) {
                            $quantity = NULL;
                            echo "<p class='text-red-500'><b>The quantity is invalid!</b></p>";
                        } else {
                            $quantity = $_POST['quantity'];
                        }
                    } else {
                        $quantity = NULL;
                        echo "<p class='text-red-500'><b>You must enter your quantity in numeric only!</b></p>";
                    }
                } else {
                    $quantity = NULL;
                    echo '<p class="text-red-500"><b>You forgot to enter your quantity!</b></p>';
                }

                $codeArr = array(1 => 210.9, 2 => 249.0, 3 => 310.9, 4 => 250.9);

                // If everything is okay, print the message.
                if ($code && $quantity) {
                    // Calculate the price.
                    $price = $codeArr[$code] * $quantity;
                    // Print the results.
                    echo "<div class='bg-white shadow-md rounded-lg p-6 mb-8'>";
                    echo "<p class='text-lg'>Hi! The price for the clothes is RM$price</p>";

                    switch (true) {
                        case $price > 500.0:
                            $price = $price - (10 / 100 * $price);
                            $price = number_format($price, 2);
                            echo "<p class='text-green-500'>Since the purchase is more than RM500.00, a 10% discount is given.<br/>
                            The price for the clothes is RM$price</p>";
                            break;
                        case $price < 500.0:
                            $price = number_format($price, 2);
                            echo "<p class='text-blue-500'>Since the purchase is not more than RM500.00, a 10% discount is not given.<br/>
                            The final price for the clothes is RM$price</p>";
                    }
                    echo "</div>";
                } else {
                    // One form element was not filled out properly.
                    echo '<p class="text-red-500"><b>Please go back and fill out the form again.</b></p>';
                }
            }
            ?>

            <!-- Table with 3 columns -->
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-2xl font-semibold mb-4">Clothes Information</h2>
                <table class="min-w-full bg-white">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b-2 border-gray-200 bg-gray-100 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Code</th>
                            <th class="py-2 px-4 border-b-2 border-gray-200 bg-gray-100 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Description</th>
                            <th class="py-2 px-4 border-b-2 border-gray-200 bg-gray-100 text-left text-sm font-semibold text-gray-600 uppercase tracking-wider">Price (RM)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="py-2 px-4 border-b border-gray-200">1</td>
                            <td class="py-2 px-4 border-b border-gray-200">Baju Kurung Tradisional</td>
                            <td class="py-2 px-4 border-b border-gray-200">210.90</td>
                        </tr>
                        <tr>
                            <td class="py-2 px-4 border-b border-gray-200">2</td>
                            <td class="py-2 px-4 border-b border-gray-200">Baju Kurung Moden</td>
                            <td class="py-2 px-4 border-b border-gray-200">249.00</td>
                        </tr>
                        <tr>
                            <td class="py-2 px-4 border-b border-gray-200">3</td>
                            <td class="py-2 px-4 border-b border-gray-200">Kebaya</td>
                            <td class="py-2 px-4 border-b border-gray-200">310.90</td>
                        </tr>
                        <tr>
                            <td class="py-2 px-4 border-b border-gray-200">4</td>
                            <td class="py-2 px-4 border-b border-gray-200">Jubah</td>
                            <td class="py-2 px-4 border-b border-gray-200">250.90</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
