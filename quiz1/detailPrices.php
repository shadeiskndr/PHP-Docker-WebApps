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
        <nav class="w-64 bg-blue-600 text-white min-h-screen p-4">
            <h2 class="text-2xl font-bold mb-6">Navigation</h2>
            <ul class="space-y-4">
                <li><a href="../index.php" class="block py-2 px-4 rounded hover:bg-blue-700">Home</a></li>
                <li><a href="puteriClothes.html" class="block py-2 px-4 rounded hover:bg-blue-700">Puteri Clothes</a></li>
				<li><a href="../Assignment1/airform2.php" class="block py-2 px-4 rounded hover:bg-blue-700">Air Conditioner Calculator</a></li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="flex-1 p-4">
            <h1 class="text-4xl font-bold mb-4">Puteri Clothes Calculator</h1>
            <div class="bg-white shadow-md rounded-lg p-6">
                <?php
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
                } else {
                    // One form element was not filled out properly.
                    echo '<p class="text-red-500"><b>Please go back and fill out the form again.</b></p>';
                }
                ?>
            </div>
        </div>
    </div>
</body>
</html>
