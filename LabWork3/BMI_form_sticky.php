<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../output.css" rel="stylesheet">
    <title>BMI Calculator</title>
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
                <h1 class="text-4xl font-bold">BMI Calculator</h1>
            </header>
            <form action="BMI_form_sticky.php" method="post" class="bg-white shadow-md rounded-lg p-6 mb-8">
                <fieldset>
                    <legend class="text-2xl font-semibold mb-4">Enter your information in the form below:</legend>
                    <div class="mb-4">
                        <label for="name" class="block text-lg font-medium text-gray-700">Name:</label>
                        <input type="text" name="name" id="name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" size="20" maxlength="40" value="<?php if(isset($_POST['name'])) echo $_POST['name']; ?>"/>
                    </div>
                    <div class="mb-4">
                        <label for="height" class="block text-lg font-medium text-gray-700">Height (m):</label>
                        <input type="text" name="height" id="height" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" size="20" maxlength="40" value="<?php if(isset($_POST['height'])) echo $_POST['height']; ?>" />
                    </div>
                    <div class="mb-4">
                        <label for="weight" class="block text-lg font-medium text-gray-700">Weight (kg):</label>
                        <input type="text" name="weight" id="weight" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" size="20" maxlength="40" value="<?php if(isset($_POST['weight'])) echo $_POST['weight']; ?>" />
                    </div>
                </fieldset>
                <div class="mt-4">
                    <input type="submit" name="submit" value="Calculate" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75" />
                </div>
            </form>
            <?php
            if(isset($_REQUEST['submit'])){
                // Validate name
                if (!empty($_POST['name'])) {
                    if (!is_numeric($_POST['name'])) {
                        $name = $_POST['name'];
                    } else {
                        $name = NULL;
                        echo '<p><b>You must enter your name in text only!</b></p>';
                    }
                } else {
                    $name = NULL;
                    echo '<p><b>You forgot to enter your name!</b></p>';
                }

                // Validate height
                if (!empty($_POST['height'])) {
                    if (is_numeric($_POST['height'])) {
                        $height = $_POST['height'];
                        // Convert height from cm to m if it seems to be in cm
                        if ($height > 3) {
                            $height = NULL;
                        	echo '<p><b>You must enter your height in meters only!</b></p>';
                        }
                    } else {
                        $height = NULL;
                        echo '<p><b>You must enter your height in numeric only!</b></p>';
                    }
                } else {
                    $height = NULL;
                    echo '<p><b>You forgot to enter your height!</b></p>';
                }

                // Validate weight
                if (!empty($_POST['weight'])) {
                    if (is_numeric($_POST['weight'])) {
                        $weight = $_POST['weight'];
                    } else {
                        $weight = NULL;
                        echo '<p><b>You must enter your weight in numeric only!</b></p>';
                    }
                } else {
                    $weight = NULL;
                    echo '<p><b>You forgot to enter your weight!</b></p>';
                }

                // If everything is okay, print the message.
                if ($name && $height && $weight) {
                    // Calculate the BMI.
                    $bmi = $weight / ($height * $height);
                    $bmi = number_format($bmi, 2);
                    // Print the results.
                    echo "
                        <div class='bg-white shadow-md rounded-lg p-6'>
                            <p>Hi $name!</p>
                            <p>Your Body Mass Index (BMI) is $bmi</p>
                            <p>";
                    switch(true){
                        case $bmi < 18.5:
                            echo "Your BMI is UNDERWEIGHT";
                            break;
                        case $bmi <= 24.9:
                            echo "Your BMI is NORMAL";
                            break;
                        case $bmi <= 29.9:
                            echo "Your BMI is OVERWEIGHT";
                            break;
                        case $bmi >= 30.0:
                            echo "Your BMI is OBESE";
                            break;
                    }
                    echo "</p></div>";
                } else { // One form element was not filled out properly.
                    echo '<p><font color="red">Please go back and fill out the form again.</font></p>';
                }
            }
            ?>
        </div>
    </div>
</body>
</html>
