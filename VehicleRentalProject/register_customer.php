<?php 
$page_title = 'Customer Register';
include ('./includes/headerCregister.html');
?>
<h1>Customer Register</h1>
<form action="register_customer.php" method="post">
<label for="name">Customer Name:</label>
	<input type="text" name="name" size="15" maxlength="30" value="<?php if (isset($_POST['name'])) echo $_POST['name']; ?>" /><p></p>
<label for="contact">Contact Number:</label>
	<input type="text" name="contact" size="15" maxlength="30" value="<?php if (isset($_POST['contact'])) echo $_POST['contact']; ?>" /><p></p>
<label for="address">Address:</label>
	<input type="text" name="address" size="15" maxlength="30" value="<?php if (isset($_POST['address'])) echo $_POST['address']; ?>" /><p></p>
<label for="email">Email:</label>
	<input type="text" name="email" size="15" maxlength="30" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>" /><p></p>
<label for="password">Password:</label>
	<input type="password" name="password" size="15" maxlength="30"/><p></p>
<label for="confpassword">Confirm Password:</label>
	<input type="password" name="confpassword" size="15" maxlength="30"/><p></p>
<p><input type="submit" name="submit" value="Register" /></p>
</form>
<?php
// Check if the form has been submitted.
if (isset($_POST['submit'])) {

	require_once ('mysqli.php'); // Connect to the db.
	global $dbc;

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
		$result = @mysqli_query ($dbc,$query); // Run the query.
		if (mysqli_num_rows($result) == 0) {

			// Make the query.
			$query = "INSERT INTO customer (name, contactNo, address, email, password, registration_date) VALUES ('$name', '$contact', '$address', '$email', SHA('$password'), NOW())";		
			$result = @mysqli_query ($dbc,$query); // Run the query. // Run the query.
			if ($result) { // If it ran OK. == IF TRUE
			
				// Send an email, if desired.
				
				// Print a message.
				echo '<h1 id="mainhead">Thank you!</h1>
				<p>Customer is now registered. </p><p><br /></p>';	
			
				// Include the footer and quit the script (to not show the form).
				include ('./includes/footer.html'); 
				exit();
				
			} else { // If it did not run OK.
				echo '<h1 id="mainhead">System Error</h1>
				<p class="error">You could not be registered due to a system error. We apologize for any inconvenience.</p>'; // Public message.
				echo '<p>' . mysqli_error($dbc)  . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
				include ('./includes/footer.html'); 
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

	mysqli_close($dbc); // Close the database connection.
		
} // End of the main Submit conditional.
?>
<?php
include ('./includes/footer.html');
?>