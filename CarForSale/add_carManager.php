<?php

$page_title = 'Car Manager';
include ('./includes/header.html');
?>
<form action="add_carManager.php" method="post">
<h1>Car Manager</h1>
<h3>Please enter car information</h3>
<label for="manufacturer"><p>Manufacturer Name:</label><br/>
	<?php
		$mysqli = New mysqli ('localhost', 'root', '', 'carforsale');
		$resultSet = $mysqli->query("SELECT name from manufacturername");
	?>
<select name="manufacturer">
	<option value = "" >Please Choose a Manufacturer</option>
	<?php
		while ($row = $resultSet -> fetch_assoc()){
			$manufacturer = $row ["name"];
			echo "<option value = '$manufacturer' >$manufacturer</option>";
		}
	?>
</select>
<label for="model"><p>Model Name:</label>
	<input type="text" name="model" size="15" maxlength="30" value="<?php if (isset($_POST['model'])) echo $_POST['model']; ?>" /></p>
<label for="acquisition_price"><p>Acquisition Price:</label>
	<input type="text" name="acquisition_price" size="10" maxlength="10" value="<?php if(isset($_POST['acquisition_price'])) echo $_POST['acquisition_price']; ?>"/></p>
<label for="mdate"><p>Date Acquired:</label>
	<input type="date" name="mdate" required/></p>
<p><input type="submit" name="submit" value="Add" /></p>
</form>
<fieldset>
<?php

// Check if the form has been submitted.
if (isset($_POST['submit'])) {
	require_once ('mysqli.php'); // Connect to the db.
	global $dbc;

	$errorMessage = array(); // Initialize error array.
	
	// Validate manufacturer.
	if (empty($_POST['manufacturer'])) {
		$errorMessage[] = 'You forgot to enter manufacturer name.';
	} else {
		$manuname = ($_POST['manufacturer']);
	}

	// Validate model name.
	if (empty($_POST['model'])) {
		$errorMessage[] = 'You forgot to enter model.';
	} else {
		$modelname = ($_POST['model']);
	}
	
	//Validate acquisition price
	if (!empty($_POST['acquisition_price'])) {
		if (is_numeric($_POST['acquisition_price'])) {
			if ($_POST['acquisition_price'] < 1){
				$price = NULL;
				$errorMessage[] = '<p>The acquisition price is invalid!</p>';
			} else {
				$price = $_POST['acquisition_price'];
			}
		} else {
			$price = NULL;
			$errorMessage[] = '<p>You must enter the acquisition price in numeric only!</p>';
		}
	} else {
		$price = NULL;
		$errorMessage[] = '<p>You forgot to enter the acquisition price!</p>';
	}
		
	$mdate = ($_POST['mdate']);
		
	if (empty($errorMessage)) { // If everything's okay.

		// Check for previous registration
		$query = "SELECT carForSaleID FROM carForSale WHERE modelName='$modelname'";
		$result = @mysqli_query ($dbc,$query); // Run the query.
		if (mysqli_num_rows($result) == 0) {
	
			// Make the query.
			$query = "INSERT INTO carforsale (manufacturerName, modelName, acquisitionPrice, dateAcquired) VALUES ('$manuname', '$modelname','$price', '$mdate')";
			$result = @mysqli_query ($dbc,$query); // Run the query. // Run the query.
			if ($result) { // If it ran OK. == IF TRUE
				
				// Print a message.
				echo '<h1 id="mainhead">Thank you!</h1>
				<p>Car is now added. </p><p><br /></p>';	
			
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
			<p class="error">The record has already been added.</p>';
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