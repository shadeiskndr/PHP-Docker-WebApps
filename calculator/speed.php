<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../output.css" rel="stylesheet">
    <title>Speed Converter</title>
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
                <h1 class="text-4xl font-bold">Speed Converter</h1>
            </header>
            <form action="speed_converter.php" method="post" class="bg-white shadow-md rounded-lg p-6 mb-8">
                <fieldset>
                    <legend class="text-2xl font-semibold mb-4">Enter the speed to convert:</legend>
                    <div class="mb-4">
                        <label for="speed" class="block text-lg font-medium text-gray-700">Speed:</label>
                        <input type="text" name="speed" id="speed" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" size="20" maxlength="40"/>
                    </div>
                    <div class="mb-4">
                        <p class="block text-lg font-medium text-gray-700">From: Miles per Hour (MPH)</p>
                    </div>
                    <div class="mb-4">
                        <p class="block text-lg font-medium text-gray-700">To: Kilometer per Hour (KPH)</p>
                    </div>
                </fieldset>
                <div class="mt-4">
                    <input type="submit" name="submit" value="Convert" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75" />
                </div>
            </form>

            <?php
            if(isset($_REQUEST['submit'])){
                // Validate speed
                if (empty($_POST['speed'])) {
                    $speed = NULL;
                } else {
                    $speed = $_POST['speed'];
                }

                // If everything is okay, print the message.
                if ($speed) {
                    // Convert the speed.
                    $converted_speed = $speed * 1.609;
                    $converted_speed = number_format($converted_speed, 3);
                    // Print the results.
                    echo '
                    <div class="bg-white shadow-md rounded-lg p-6">
                        <h1 class="text-2xl font-semibold mb-4"><b>Conversion Result</b></h1>
                        <p><b>' . $speed . '</b> Miles per hour is equals to <b>' . $converted_speed . '</b> Kilometer per hour.</p>
                    </div>';
                } else {
                    // Form element was not filled out properly.
                    echo '
                    <div class="bg-white shadow-md rounded-lg p-6">
                        <h1 class="text-2xl font-semibold mb-4"><b>Conversion Result</b></h1>
                        <p><b><font color="red">Error! Please enter a valid value.</font></b></p>
                    </div>';
                }
            }
            ?>
        </div>
    </div>
</body>
</html>
