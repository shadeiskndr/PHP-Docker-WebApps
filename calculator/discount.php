<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../output.css" rel="stylesheet">
    <title>Discount Code</title>
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
                <li><a href="Discount.php" class="block py-2 px-4 rounded hover:bg-blue-700">Discount Calculator</a></li>
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
                <h1 class="text-4xl font-bold">Discount Calculator</h1>
            </header>
            <form action="Discount.php" method="post" class="bg-white shadow-md rounded-lg p-6 mb-8">
                <fieldset>
                    <legend class="text-2xl font-semibold mb-4">Please fill in the forms below:</legend>
                    <div class="mb-4">
                        <label for="articlePrice" class="block text-lg font-medium text-gray-700">Pricing of an Article:</label>
                        <input type="text" name="articlePrice" id="articlePrice" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" size="20" maxlength="40" value="<?php if(isset($_POST['articlePrice'])) echo $_POST['articlePrice']; ?>"/>
                    </div>
                    <div class="mb-4">
                        <label for="priceCode" class="block text-lg font-medium text-gray-700">Pricing Code:</label>
                        <input type="text" name="priceCode" id="priceCode" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm" size="20" maxlength="40" value="<?php if(isset($_POST['priceCode'])) echo $_POST['priceCode']; ?>"/>
                    </div>
                </fieldset>
                <div class="mt-4">
                    <input type="submit" name="submit" value="Check!" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75" />
                </div>
            </form>

            <?php
            if(isset($_REQUEST['submit'])){
                $errors = array();

                // Validate article price
                if (!empty($_POST['articlePrice'])) {
                    if (is_numeric($_POST['articlePrice'])) {
                        $articlePrice = $_POST['articlePrice'];
                    } else {
                        $articlePrice = NULL;
                        $errors[] = '<p><b>You must enter your article price in numeric only!</b></p>';
                    }
                } else {
                    $articlePrice = NULL;
                    $errors[] = '<p><b>You forgot to enter your article price!</b></p>';
                }

                // Validate pricing code
                if (!empty($_POST['priceCode'])) {
                    if (!is_numeric($_POST['priceCode'])) {
                        if (in_array(strtolower($_POST['priceCode']), array("h", "f", "q", "t", "z"))){
                            $priceCode = strtolower($_POST['priceCode']);
                        } else {
                            $priceCode = NULL;
                            $errors[] = '<p><b>Invalid discount code</b></p>';
                        }
                    } else {
                        $priceCode = NULL;
                        $errors[] = '<p><b>You must enter your pricing code in letters only!</b></p>';
                    }
                } else {
                    $priceCode = NULL;
                    $errors[] = '<p><b>You forgot to enter your pricing code!</b></p>';
                }

                // If there are no errors perform calculation
                if (empty($errors)){ 
                    $priceCodeArr = array('h' => 50, 'f' => 40, 'q' => 33, 't' => 25, 'z' => 0);
                    $discountPercentage = $priceCodeArr[$priceCode];
                    function Calculate($articlePrice, $discountPercentage){
                        $total = $articlePrice - ($discountPercentage / 100 * $articlePrice);
                        $total = number_format($total, 2);
                        $result = "
                        <div class='bg-white shadow-md rounded-lg p-6'>
                            <h3 class='text-green-500 text-xl font-bold'>TOTAL</h3>
                            <p><b>The article price: RM $articlePrice</b></p>
                            <p><b>The pricing discount: $discountPercentage %</b></p>
                            <p><b>The total pricing: RM $total</b></p>
                        </div>
                        ";
                        return $result;
                    }
                    
                    $final = Calculate($articlePrice, $discountPercentage);
                    echo $final;
                    
                } else { // One form element was not filled out properly.
                    echo '<h3 class="text-red-500 text-xl font-bold">ERROR!</h3>';
                    foreach ($errors as $msg){
                        echo '<p class="text-red-500">-' . $msg . '</p>';
                    }
                    echo '<p class="text-red-500"><p>-</p>Please go back and fill out the form again.</p>';
                }
            }
            ?>

            <!-- Pricing Code Table -->
            <div class="mt-8">
                <h2 class="text-2xl font-bold mb-4">Pricing Code and Discount Percentage</h2>
                <table class="min-w-full bg-white border border-gray-200">
                    <thead>
                        <tr>
                            <th class="py-2 px-4 border-b">Pricing Code</th>
                            <th class="py-2 px-4 border-b">Discount Percentage</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="py-2 px-4 border-b">H</td>
                            <td class="py-2 px-4 border-b">50%</td>
                        </tr>
                        <tr>
                            <td class="py-2 px-4 border-b">F</td>
                            <td class="py-2 px-4 border-b">40%</td>
                        </tr>
                        <tr>
                            <td class="py-2 px-4 border-b">Q</td>
                            <td class="py-2 px-4 border-b">33%</td>
                        </tr>
                        <tr>
                            <td class="py-2 px-4 border-b">T</td>
                            <td class="py-2 px-4 border-b">25%</td>
                        </tr>
                        <tr>
                            <td class="py-2 px-4 border-b">Z</td>
                            <td class="py-2 px-4 border-b">0%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
