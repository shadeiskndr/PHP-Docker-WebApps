<?php
ob_start(); // Start output buffering
session_start(); // Start the session
// Begin the page now.

$connect = mysqli_connect(
    'db', # hostname
    'admindb', # username
    'password', # password
    'mydatabase' # db
);

$page_title = 'Customer Log in';

?>

<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../output.css" rel="stylesheet">
    <title>Customer Log in</title>
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
                <h1 class="text-4xl font-bold">Customer Log in</h1>
            </header>
            <main class="mt-8">
                <section class="my-8">
                    <h2 class="text-3xl font-semibold mb-4">Log in to your account</h2>
                    <form action="login_customer.php" method="post" class="space-y-4">
                        <div>
                            <label for="email" class="block text-lg">Email:</label>
                            <input type="text" name="email" size="15" maxlength="30" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>" class="w-full p-2 border rounded" />
                        </div>
                        <div>
                            <label for="password" class="block text-lg">Password:</label>
                            <input type="password" name="password" size="15" maxlength="30" class="w-full p-2 border rounded" />
                        </div>
                        <div>
                            <input type="submit" name="submit" value="Login" class="bg-blue-600 text-white py-2 px-4 rounded hover:bg-blue-700" />
                        </div>
                    </form>
                </section>
                <?php 
                    // Check if the form has been submitted.
if (isset($_POST['submit'])) {
    
    $errorMessage = array(); // Initialize error array.
    
    // Check for an email address.
    if (empty($_POST['email'])) {
        $errorMessage[] = 'You forgot to enter your email address.';
    } else {
        $email = ($_POST['email']);
    }
    
    // Check for a password.
    if (empty($_POST['password'])) {
        $errorMessage[] = 'You forgot to enter your password.';
    } else {
        $password = ($_POST['password']);
    }
    
    if (empty($errorMessage)) { // If everything's OK.

        /* Retrieve the customerID and name for 
        that email/password combination. */
        $query = "SELECT customerID, name FROM customer WHERE email='$email' AND password=SHA('$password')";        
        $result = @mysqli_query ($connect,$query); // Run the query.
        $row = mysqli_fetch_array ($result, MYSQLI_NUM); // Return a record, if applicable.
        
        if ($row) { // A record was pulled from the database.
            // Set the session data & redirect.
            session_start();
            $_SESSION['customerID'] = $row[0];
            $_SESSION['name'] = $row[1];

            // Redirect the user to the loggedin_customer.php page.
            // Start defining the URL.
            $url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
            // Check for a trailing slash.
            if ((substr($url, -1) == '/') OR (substr($url, -1) == '\\') ) {
                $url = substr($url, 0, -1); // Chop off the slash.
            }
            // Add the page.
            $url .= '/loggedin_customer.php';
            
            header("Location: $url");
            exit(); // Quit the script.
                
        } else { // No record matched the query.
            $errorMessage[] = 'The email address and password entered do not match those on file.'; // Public message.
            $errorMessage[] = mysqli_error($connect)  . '<br /><br />Query: ' . $query; // Debugging message.
        }
        
    } // End of if (empty($errorMessage)) IF.
        
    mysqli_close($connect); // Close the database connection.

} else { // Form has not been submitted.

    $errorMessage = NULL;

} // End of the main Submit conditional.

if (!empty($errorMessage)) { // Print any error messages.
    echo '<h1 id="mainhead">Error!</h1>
    <p class="error">The following error(s) occurred:<br />';
    foreach ($errorMessage as $msg) { // Print each error.
        echo " - $msg<br />\n";
    }
    echo '</p><p>Please try again.</p>';
}
                ?>
            </main>
			<!-- Navigation Links -->
			<nav class="bg-white shadow-md rounded-lg p-4 mb-4">
                <ul class="flex space-x-4">
                    <li><a href="homepage.php" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75">Return to Main</a></li>
                    <li><a class="px-3 py-2 bg-transparent text-white font-semibold"></a></li>
					<li><a href="register_customer.php" class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-75">Customer Register</a></li>
                </ul>
            </nav>

            <footer class="bg-blue-600 text-white p-4 text-center rounded">
                <p>&copy; 2024 Vehicle Rental Project</p>
            </footer>
        </div>
    </div>
</body>
</html>
