<?php
// Begin the page now.
$page_title = 'Customer Log in';
include ('./includes/headerClogin.html');
?>

<h1>Customer Log in</h1>
<form action="login_customer.php" method="post">
	<label for="email">Email:</label>
	<input type="text" name="email" size="15" maxlength="30" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>" /><p></p>
<label for="password">Password:</label>
	<input type="password" name="password" size="15" maxlength="30"/><p></p>
	<p><input type="submit" name="submit" value="Login" /></p>
</form>

<?php 

// Check if the form has been submitted.
if (isset($_POST['submit'])) {

	require_once ('mysqli.php'); // Connect to the db.
	global $dbc;
	
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
		$result = @mysqli_query ($dbc,$query); // Run the query.
		$row = mysqli_fetch_array ($result, MYSQLI_NUM); // Return a record, if applicable.
		
		if ($row) { // A record was pulled from the database.
				
			// Set the session data & redirect.
			session_start();
			$_SESSION['customerID'] = $row[0];
			$_SESSION['name'] = $row[1];

			// Redirect the user to the loggedin.php page.
			// Start defining the URL.
			$url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
			// Check for a trailing slash.
			if ((substr($url, -1) == '/') OR (substr($url, -1) == '\\') ) {
				$url = substr ($url, 0, -1); // Chop off the slash.
			}
			// Add the page.
			$url .= '/loggedin_customer.php';
			
			header("Location: $url");
			exit(); // Quit the script.
				
		} else { // No record matched the query.
			$errorMessage[] = 'The email address and password entered do not match those on file.'; // Public message.
			$errorMessage[] = mysqli_error($dbc)  . '<br /><br />Query: ' . $query; // Debugging message.
		}
		
	} // End of if (empty($$errorMessage)) IF.
		
	mysqli_close($dbc); // Close the database connection.

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

// Create the form.
?>
<?php
include ('./includes/footer.html');
?>