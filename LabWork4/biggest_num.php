<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../output.css" rel="stylesheet">
    <title>What number's bigger?</title>
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
                <li><a href="biggest_num.php" class="block py-2 px-4 rounded hover:bg-blue-700">Biggest Number</a></li>
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
                <h1 class="text-4xl font-bold">Biggest Number</h1>
            </header>
            <form action="biggest_num.php" method="post" class="bg-white shadow-md rounded-lg p-6 mb-8">
                <fieldset>
                    <legend class="text-2xl font-semibold mb-4">Enter your numbers:</legend>
                    <div class="mb-4">
                        <label for="first" class="block text-lg font-medium text-gray-700">First Number:</label>
                        <input type="text" name="first" id="first" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" size="20" maxlength="40" value="<?php if(isset($_POST['first'])) echo $_POST['first']; ?>"/>
                    </div>
                    <div class="mb-4">
                        <label for="second" class="block text-lg font-medium text-gray-700">Second Number:</label>
                        <input type="text" name="second" id="second" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" size="20" maxlength="40" value="<?php if(isset($_POST['second'])) echo $_POST['second']; ?>"/>
                    </div>
                </fieldset>
                <div class="mt-4">
                    <input type="submit" name="submit" value="Check!" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75" />
                </div>
            </form>

            <?php
            if(isset($_REQUEST['submit'])){
                $errors = array();

                // Validate first number
                if (!empty($_POST['first'])) {
                    if (is_numeric($_POST['first'])) {
                        $first = $_POST['first'];
                    } else {
                        $first = NULL;
                        $errors[] = '<p><b>You must enter your first number in numeric only!</b></p>';
                    }
                } else {
                    $first = NULL;
                    $errors[] = '<p><b>You forgot to enter your first number!</b></p>';
                }

                // Validate second number
                if (!empty($_POST['second'])) {
                    if (is_numeric($_POST['second'])) {
                        $second = $_POST['second'];
                    } else {
                        $second = NULL;
                        $errors[] = '<p><b>You must enter your second number in numeric only!</b></p>';
                    }
                } else {
                    $second = NULL;
                    $errors[] = '<p><b>You forgot to enter your second number!</b></p>';
                }

                // If both numbers are entered, do a comparison
                if (empty($errors)){
                    echo "<div class='bg-white shadow-md rounded-lg p-6'>";
                    switch(true){
                        case $first > $second:
                            echo "<b>$first is bigger than $second.</b>";
                            break;
                        case $first < $second:
                            echo "<b>$second is bigger than $first.</b>";
                            break;
                        case $first == $second:
                            echo "<b>Both numbers are equal!</b>";
                            break;
                    }
                    echo "</div>";
                } else { // One form element was not filled out properly.
                    echo '<h3 class="text-red-500 text-xl font-bold">ERROR!</h3>';
                    foreach ($errors as $msg){
                        echo '<p class="text-red-500">-' . $msg . '</p>';
                    }
                    echo '<p class="text-red-500"><p>-</p>Please go back and fill out the form again.</p>';
                }
            }
            ?>
        </div>
    </div>
</body>
</html>
