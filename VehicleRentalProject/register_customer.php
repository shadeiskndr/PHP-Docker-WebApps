<?php 
// Begin the page now.

$connect = mysqli_connect(
    'db', # hostname
    'admindb', # username
    'password', # password
    'mydatabase' # db
);

$page_title = 'Customer Register';
?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../output.css" rel="stylesheet">
    <title>Customer Register</title>
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
                <li><a href="../CarForSale/view_carList.php" class="block py-2 px-4 rounded hover:bg-blue-700">Cars Database</a></li>
                <li><a href="homepage.php" class="block py-2 px-4 rounded hover:bg-blue-700">Vehicle Rental Project</a></li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="flex-1 p-4">
            <header class="bg-blue-600 text-white p-4 rounded mb-4">
                <h1 class="text-4xl font-bold">Customer Register</h1>
            </header>
            <main class="mt-8">
                
                <section class="my-8">
                    <h2 class="text-3xl font-semibold mb-4">Register a new account</h2>
                    <form action="register_customer.php" method="post" class="space-y-4">
                        <div>
                            <label for="name" class="block text-lg">Customer Name:</label>
                            <input type="text" name="name" size="15" maxlength="30" value="<?php if (isset($_POST['name'])) echo $_POST['name']; ?>" class="w-full p-2 border rounded" />
                        </div>
                        <div>
                            <label for="contact" class="block text-lg">Contact Number:</label>
                            <input type="text" name="contact" size="15" maxlength="30" value="<?php if (isset($_POST['contact'])) echo $_POST['contact']; ?>" class="w-full p-2 border rounded" />
                        </div>
                        <div>
                            <label for="address" class="block text-lg">Address:</label>
                            <input type="text" name="address" size="15" maxlength="30" value="<?php if (isset($_POST['address'])) echo $_POST['address']; ?>" class="w-full p-2 border rounded" />
                        </div>
                        <div>
                            <label for="email" class="block text-lg">Email:</label>
                            <input type="text" name="email" size="15" maxlength="30" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>" class="w-full p-2 border rounded" />
                        </div>
                        <div>
                            <label for="password" class="block text-lg">Password:</label>
                            <input type="password" name="password" size="15" maxlength="30" class="w-full p-2 border rounded" />
                        </div>
                        <div>
                            <label for="confpassword" class="block text-lg">Confirm Password:</label>
                            <input type="password" name="confpassword" size="15" maxlength="30" class="w-full p-2 border rounded" />
                        </div>
                        <div>
                            <input type="submit" name="submit" value="Register" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700" />
                        </div>
                    </form>
                </section>
                <?php
                // Check if the form has been submitted.
                if (isset($_POST['submit'])) {

                    $errorMessage = array(); // Initialize error array.
                    
                    // Validate name.
                    if (empty($_POST['name'])) {
                        $errorMessage[] = 'You forgot to enter your name.';
                    } else {
                        $name = ($_POST['name']);
                    }

                    //Validate contact number
                    if (!empty($_POST['contact'])) {
                        if (is_numeric($_POST['contact'])) {
                            if ($_POST['contact'] < 1){
                                $contact = NULL;
                                $errorMessage[] = '<p>Your contact number is invalid!</p>';
                            } else {
                                $contact = $_POST['contact'];
                            }
                        } else {
                            $contact = NULL;
                            $errorMessage[] = '<p>You must enter your contact number in numeric only!</p>';
                        }
                    } else {
                        $contact = NULL;
                        $errorMessage[] = '<p>You forgot to enter your contact number!</p>';
                    }

                    // Validate address
                    if (empty($_POST['address'])) {
                        $errorMessage[] = 'You forgot to enter your address.';
                    } else {
                        $address = ($_POST['address']);
                    }
                    
                    // Validate email
                    if (empty($_POST['email'])) {
                        $errorMessage[] = 'You forgot to enter your email address.';
                    } else {
                        $email = ($_POST['email']);
                    }
                    
                    // Validate password and match against the confirmed password.
                    if (!empty($_POST['password'])) {
                        if ($_POST['password'] != $_POST['confpassword']) {
                            $errorMessage[] = 'Your password did not match the confirmed password.';
                        } else {
                            $password = ($_POST['password']);
                        }
                    } else {
                        $errorMessage[] = 'You forgot to enter your password.';
                    }
                    
                    if (empty($errorMessage)) { // If everything's okay.
                    
                        // Register the user in the database.
                        
                        // Check for previous registration.
                        $query = "SELECT customerID FROM customer WHERE email = '$email'";
                        $result = @mysqli_query ($connect,$query); // Run the query.
                        if (mysqli_num_rows($result) == 0) {

                            // Make the query.
                            $query = "INSERT INTO customer (name, contactNo, address, email, password, registration_date) VALUES ('$name', '$contact', '$address', '$email', SHA('$password'), NOW())";        
                            $result = @mysqli_query ($connect,$query); // Run the query. // Run the query.
                            if ($result) { // If it ran OK. == IF TRUE
                            
                                // Send an email, if desired.
                                
                                // Print a message.
                                echo '<h1 id="mainhead">Thank you!</h1>
                                <p>Customer is now registered. </p><p><br /></p>';    
                            
                                // Include the footer and quit the script (to not show the form).
                                
                                exit();
                                
                            } else { // If it did not run OK.
                                echo '<h1 id="mainhead">System Error</h1>
                                <p class="error">You could not be registered due to a system error. We apologize for any inconvenience.</p>'; // Public message.
                                echo '<p>' . mysqli_error($connect)  . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
                               
                                exit();
                            }
                                
                        } else { // Already registered.
                            echo '<h1 id="mainhead">Error!</h1>
                            <p class="error">The email address has already been registered.</p>';
                        }
                        
                    } else { // Report the errors.
                    
                        echo '<h1 id="mainhead">Error!</h1>
                        <p class="error">The following error(s) occurred:<br />';
                        foreach ($errorMessage as $msg) { // Print each error.
                            echo " - $msg<br />\n";
                        }
                        echo '</p><p>Please try again.</p><p><br /></p>';
                        
                    } // End of if (empty($errors)) IF.

                    mysqli_close($connect); // Close the database connection.
                        
                } // End of the main Submit conditional.
                ?>
            </main>
            <!-- Navigation Links -->
            <nav class="bg-white shadow-md rounded-lg p-4 mb-4">
                <ul class="flex space-x-4">
                    <li><a href="homepage.php" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75">Return to Main</a></li>
                    <li><a class="px-3 py-2 bg-transparent text-white font-semibold"></a></li>
                    <li><a href="login_customer.php" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75">Customer Log in</a></li>
                </ul>
            </nav>

            <footer class="bg-blue-600 text-white p-4 text-center rounded">
                <p>&copy; 2024 Vehicle Rental Project</p>
            </footer>
        </div>
    </div>
</body>
</html>
