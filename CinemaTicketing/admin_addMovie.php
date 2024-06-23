<?php

$page_title = 'Add a Movie';
include ('./includes/header.html');
?>
<form action="admin_addMovie.php" method="post">
<h1>Add Movie</h1>
<h3>Please enter the movie information</h3>
<label for="name"><p>Movie Name:</label>
	<input type="text" name="name" size="15" maxlength="30" value="<?php if (isset($_POST['name'])) echo $_POST['name']; ?>" /></p>
<label for="year"><p>Movie Year Release:</label>
	<input type="text" name="year" size="15" maxlength="30" value="<?php if (isset($_POST['year'])) echo $_POST['year']; ?>" /></p>
<label for="genre"><p>Movie Genre:</label>
	<select id ="genre" name="genre">
		<option value="">Choose a movie genre</option>
		<option value="1" <?php if(isset($_POST['genre']) && $_POST['genre'] == "1") echo 'selected="selected"'; ?> >Action/Adventure</option>
		<option value="2" <?php if(isset($_POST['genre']) && $_POST['genre'] == "2") echo 'selected="selected"'; ?> >Comedy</option>
		<option value="3" <?php if(isset($_POST['genre']) && $_POST['genre'] == "3") echo 'selected="selected"'; ?> >Drama</option>
		<option value="4" <?php if(isset($_POST['genre']) && $_POST['genre'] == "4") echo 'selected="selected"'; ?> >Fantasy/Sci-Fi</option>
	</select></p>
<label for="rating"><p>Movie Rating:</label>
	<select id ="rating" name="rating">
		<option value="">Choose a movie rating</option>
		<option value="1" <?php if(isset($_POST['rating']) && $_POST['rating'] == "1") echo 'selected="selected"'; ?> >1</option>
		<option value="2" <?php if(isset($_POST['rating']) && $_POST['rating'] == "2") echo 'selected="selected"'; ?> >2</option>
		<option value="3" <?php if(isset($_POST['rating']) && $_POST['rating'] == "3") echo 'selected="selected"'; ?> >3</option>
		<option value="4" <?php if(isset($_POST['rating']) && $_POST['rating'] == "4") echo 'selected="selected"'; ?> >4</option>
		<option value="5" <?php if(isset($_POST['rating']) && $_POST['rating'] == "5") echo 'selected="selected"'; ?> >5</option>
	</select></p>
<label for="ticket_price"><p>Movie Ticket Price:</label>
	<input type="text" name="ticket_price" size="10" maxlength="10" value="<?php if(isset($_POST['ticket_price'])) echo $_POST['ticket_price']; ?>"/></p>
<label for="mdate"><p>Movie Date:</label>
	<input type="date" name="mdate" required/></p>
<label for="mtime"><p>Movie Time:</label>
	<input type="time" name="mtime" required/></p>
	<p><input type="submit" name="submit" value="Add" /></p>
	<input type="hidden" name="" value="" />
</form>
<fieldset>
<?php

// Check if the form has been submitted.
if (isset($_POST['submit'])) {
	require_once ('mysqli.php'); // Connect to the db.
	global $dbc;

	$errorMessage = array(); // Initialize error array.
	
	// Check for movie name.
	if (empty($_POST['name'])) {
		$errorMessage[] = 'You forgot to enter movie name.';
	} else {
		$name = ($_POST['name']);
	}
	
	//Validate movie year
	if (!empty($_POST['year'])) {
		if (is_numeric($_POST['year'])) {
			if ($_POST['year'] < 1){
				$year = NULL;
				$errorMessage[] = '<p>The movie year is invalid!</p>';
			} else {
				$year = $_POST['year'];
			}
		} else {
			$year = NULL;
			$errorMessage[] = '<p>You must enter the movie year in numeric only!</p>';
		}
	} else {
		$year = NULL;
		$errorMessage[] = '<p>You forgot to enter the movie year!</p>';
	}
	
	//Validate movie genre
	if(isset($_POST['genre']) && $_POST['genre'] == ""){
		$genre = NULL;
		$errorMessage[] = '<p>You forgot to choose movie genre!</p>';
	}else{
		$genre = $_POST['genre'];
	}

	//Validate movie rating
	if(isset($_POST['rating']) && $_POST['rating'] == ""){
		$rating = NULL;
		$errorMessage[] = '<p>You forgot to choose movie rating!</p>';
	}else{
		$rating = $_POST['rating'];
	}

	//Validate movie ticket price
	if (!empty($_POST['ticket_price'])) {
		if (is_numeric($_POST['ticket_price'])) {
			if ($_POST['ticket_price'] < 1){
				$ticket_price = NULL;
				$errorMessage[] = '<p>The ticket price value is invalid!</p>';
			} else {
				$ticket_price = $_POST['ticket_price'];
			}
		} else {
			$ticket_price = NULL;
			$errorMessage[] = '<p>You must enter the ticket price in numeric only!</p>';
		}
	} else {
		$ticket_price = NULL;
		$errorMessage[] = '<p>You forgot to enter the ticket price!</p>';
	}
		
	$mdate = ($_POST['mdate']);

	$mtime = ($_POST['mtime']);
		
	
	if (empty($errorMessage)) { // If everything's okay.
		
		// Check for previous registration
		$query = "SELECT id FROM movies WHERE name='$name'";
		$result = @mysqli_query ($dbc,$query); // Run the query.
		if (mysqli_num_rows($result) == 0) {
			
			// Make the query.
			$query = "INSERT INTO movies (name, year, genre, rating, ticket_price, mdate, mtime) VALUES ('$name', '$year', '$genre','$rating', '$ticket_price', '$mdate', '$mtime')";
			$result = @mysqli_query ($dbc,$query); // Run the query. // Run the query.
			if ($result) { // If it ran OK. == IF TRUE
				
				// Print a message.
				echo '<h1 id="mainhead">Thank you!</h1>
				<p>Movie is now registered. </p><p><br /></p>';	
			
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
			<p class="error">The movie record has already been added.</p>';
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
</fieldset>
<?php
include ('./includes/footer.html');
?>