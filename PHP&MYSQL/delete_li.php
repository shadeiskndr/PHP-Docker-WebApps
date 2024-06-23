<?php
// Set the page title and include the HTML header.
$page_title = 'Search and Delete User';
include ('./includes/header.html');
?>
<h2>Search User Record</h2>
<form action="delete_li.php" method="post">
	<p>Search user's name: <input type="text" name="name" size="20" maxlength="40" value="<?php if (isset($_POST['name'])) echo $_POST['name']; ?>"  /> </p>
	<p><input type="submit" name="search" value="Search" /></p>
<h2>Delete User Record</h2>
	<p>Email Address: <input type="text" name="email" size="20" maxlength="40" value="<?php if (isset($_POST['email'])) echo $_POST['email']; ?>"  /> </p>
	<p>Password: <input type="password" name="password" size="10" maxlength="20" /></p>
	<p>Confirm Password: <input type="password" name="password2" size="10" maxlength="20" /></p>
	<p><input type="submit" name="submit" value="Delete" /></p>
	<input type="hidden" name="" value="" />
</form>
<fieldset>
<?php
// Search for a user name and display their registered email
if (isset($_POST['search'])) {
	require_once ('mysqli.php'); // Connect to the db.
	global $dbc;

	$errors = array(); // Initialize error array.

	//Check for a user's name
	if (empty($_POST['name'])) {
		$errors[] = 'You forgot to enter the user name.';
	} else {
		$n = ($_POST['name']);
	}

	if (empty($errors)) { // If everything's OK.
		// Check that they've entered a registered name.
		$query = "SELECT CONCAT(last_name, ', ', first_name) AS name, email FROM users WHERE (first_name LIKE '%$n%' OR last_name LIKE '%$n%' )";
		$result = @mysqli_query ($dbc,$query); // Run the query.
		$num = mysqli_num_rows($result);
		if (mysqli_num_rows($result) > 0) { // Match was made.
			// Table header.
			echo '<table align="center" cellspacing="0" cellpadding="5">
			<tr><td align="left"><b>Name</b></td><td align="left"><b>Registered Email</b></td></tr>';
			// Fetch and print all the records.
			while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			echo '<tr><td align="left">' . $row['name'] . '</td><td align="left">' . $row['email'] . '</td></tr>';
			}
			echo '</table>';
			mysqli_free_result($result); // Free up the resources.	
			

		}else { // If it did not run OK.
			echo '<p class="error">There are currently no registered users.</p>';
		}
	}	

}
?>

<?php
// Check if the form has been submitted.
if (isset($_POST['submit'])) {


	require_once ('mysqli.php'); // Connect to the db.
		
	global $dbc;


	$errors = array(); // Initialize error array.
	
	// Check for an email address.
	if (empty($_POST['email'])) {
		$errors[] = 'You forgot to enter your email address.';
	} else {
		$e = ($_POST['email']);
	}
	
	// Check for an existing password.
	if (empty($_POST['password'])) {
		$errors[] = 'You forgot to enter your password.';
	} else {
		$p = ($_POST['password']);
	}

	// Check for a password and match against the confirmed password.
	if (!empty($_POST['password'])) {
		if ($_POST['password'] != $_POST['password2']) {
			$errors[] = 'Your password did not match the confirmed password.';
		} else {
			$cp = ($_POST['password']);
		}
	} else {
		$errors[] = 'You forgot to enter your confirmed password.';
	}
	
	if (empty($errors)) { // If everything's OK.
	
		// Check that they've entered the right email address/password combination.
		$query = "SELECT user_id FROM users WHERE (email='$e' AND password=SHA('$p') )";
		$result = @mysqli_query ($dbc,$query); // Run the query.
		$num = mysqli_num_rows($result);
		if (mysqli_num_rows($result) == 1) { // Match was made.
		
			// Get the user_id.
			$row = mysqli_fetch_array($result, MYSQLI_NUM);

			// Make the DELETE query.
			$query = "DELETE FROM users WHERE user_id=$row[0]";		
			$result = @mysqli_query ($dbc,$query); // Run the query.
			if ($result) { // If it ran OK.
			
				// Send an email, if desired.
				
				// Print a message.
				echo '<h1 id="mainhead">Thank you!</h1>
				<p>The user record has been deleted. </p><p><br /></p>';	
			
				// Include the footer and quit the script (to not show the form).
				include ('./includes/footer.html'); 
				exit();
				
			} else { // If it did not run OK.
				echo '<h1 id="mainhead">System Error</h1>
				<p class="error">The user record could not be deleted due to a system error. We apologize for any inconvenience.</p>'; // Public message.
				echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
				include ('./includes/footer.html'); 
				exit();
			}
				
		} else { // Invalid email address/password combination.
			echo '<h1 id="mainhead">Error!</h1>
			<p class="error">The email address and password do not match those on file.</p>';
		}
		
	} else { // Report the errors.
	
		echo '<h1 id="mainhead">Error!</h1>
		<p class="error">The following error(s) occurred:<br />';
		foreach ($errors as $msg) { // Print each error.
			echo " - $msg<br />\n";
		}
		echo '</p><p>Please try again.</p><p><br /></p>';
		
	} // End of if (empty($errors)) IF.

	mysqli_close($dbc); // Close the database connection.
		
} // End of the main Submit conditional.
?>
</fieldset>
<?php
include ('./includes/footer.html');
?>

