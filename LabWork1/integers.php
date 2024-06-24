<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../output.css" rel="stylesheet">
    <title>Integers Addition</title>
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
                <li><a href="integers.php" class="block py-2 px-4 rounded hover:bg-blue-700">Add 3 Integers</a></li>
                <li><a href="../updateInventory/updateInventory.php" class="block py-2 px-4 rounded hover:bg-blue-700">Mawar Boutique Inventory</a></li>
                <li><a href="../CinemaTicketing/admin_listMovie.php" class="block py-2 px-4 rounded hover:bg-blue-700">Movies I Watched</a></li>
                <li><a href="../CarForSale/view_carList.php" class="block py-2 px-4 rounded hover:bg-blue-700">Cars Database</a></li>
                <li><a href="../VehicleRentalProject/homepage.php" class="block py-2 px-4 rounded hover:bg-blue-700">Vehicle Rental Project</a></li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="flex-1 p-4">
            <header class="bg-blue-600 text-white p-4 rounded mb-4">
                <h1 class="text-4xl font-bold">Integers Addition</h1>
            </header>
            <form action="integers.php" method="post" class="bg-white shadow-md rounded-lg p-6 mb-8">
                <fieldset>
                    <legend class="text-2xl font-semibold mb-4">Enter your integers:</legend>
                    <div class="mb-4">
                        <label for="integer1" class="block text-lg font-medium text-gray-700">First Integer:</label>
                        <input type="text" name="integer1" id="integer1" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" size="20" maxlength="40" value="<?php if(isset($_POST['integer1'])) echo $_POST['integer1']; ?>"/>
                    </div>
                    <div class="mb-4">
                        <label for="integer2" class="block text-lg font-medium text-gray-700">Second Integer:</label>
                        <input type="text" name="integer2" id="integer2" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" size="20" maxlength="40" value="<?php if(isset($_POST['integer2'])) echo $_POST['integer2']; ?>"/>
                    </div>
                    <div class="mb-4">
                        <label for="integer3" class="block text-lg font-medium text-gray-700">Third Integer:</label>
                        <input type="text" name="integer3" id="integer3" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" size="20" maxlength="40" value="<?php if(isset($_POST['integer3'])) echo $_POST['integer3']; ?>"/>
                    </div>
                </fieldset>
                <div class="mt-4">
                    <input type="submit" name="submit" value="Calculate" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75" />
                </div>
            </form>

            <?php
            if(isset($_POST['submit'])){
                $errors = array();

                // Validate first integer
                if (!empty($_POST['integer1'])) {
                    if (is_numeric($_POST['integer1'])) {
                        $integer1 = $_POST['integer1'];
                    } else {
                        $integer1 = NULL;
                        $errors[] = '<p><b>You must enter your first integer in numeric only!</b></p>';
                    }
                } else {
                    $integer1 = NULL;
                    $errors[] = '<p><b>You forgot to enter your first integer!</b></p>';
                }

                // Validate second integer
                if (!empty($_POST['integer2'])) {
                    if (is_numeric($_POST['integer2'])) {
                        $integer2 = $_POST['integer2'];
                    } else {
                        $integer2 = NULL;
                        $errors[] = '<p><b>You must enter your second integer in numeric only!</b></p>';
                    }
                } else {
                    $integer2 = NULL;
                    $errors[] = '<p><b>You forgot to enter your second integer!</b></p>';
                }

                // Validate third integer
                if (!empty($_POST['integer3'])) {
                    if (is_numeric($_POST['integer3'])) {
                        $integer3 = $_POST['integer3'];
                    } else {
                        $integer3 = NULL;
                        $errors[] = '<p><b>You must enter your third integer in numeric only!</b></p>';
                    }
                } else {
                    $integer3 = NULL;
                    $errors[] = '<p><b>You forgot to enter your third integer!</b></p>';
                }

                // If all integers are entered and valid, calculate the total
                if (empty($errors)){
                    $total = $integer1 + $integer2 + $integer3;
                    $total = number_format($total, 2);
                    echo "<div class='bg-white shadow-md rounded-lg p-6'>";
                    echo "<p>The first integer <b>$integer1</b> is added to the second integer <b>$integer2</b> and added to the third integer <b>$integer3</b>.</p>";
                    echo "<p>The total of those three integers is <b>$total</b>.</p>";
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
