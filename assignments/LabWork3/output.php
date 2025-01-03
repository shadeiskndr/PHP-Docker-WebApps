<?php 

// Check if the form has been submitted.
if (isset($_POST['submitted'])) {

	$errors = array(); // Initialize error array.
	
	// Check for a name.
	if (empty($_POST['name'])) {
		$errors[] = 'You forgot to enter your name.';
	}
	
	// Check for an email address.
	if (empty($_POST['email'])) {
		$errors[] = 'You forgot to enter your email address.';
	}
	
	if (empty($errors)) { // If everything's okay.
	
		echo '<h1 id="mainhead">Thank you!</h1>';
		echo "{$_POST['name']} for the information!\n";
		echo"<p>An email has been sent to {$_POST['email']} </p><p><br /></p>";	
		
	} else { // Report the errors.
	
		echo '<h1 id="mainhead">Error!</h1>
		<p class="error">The following error(s) occurred:<br />';
		foreach ($errors as $msg) { // Print each error.
			echo " - $msg<br />\n";
		}
		echo '</p><p>Please go back and try again.</p><p><br /></p>';
		
	} // End of if (empty($errors)) IF.

} 
?>