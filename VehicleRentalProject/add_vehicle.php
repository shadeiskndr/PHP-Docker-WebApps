<?php
session_start(); // Start the session.
$page_title = 'Add Vehicle';
include ('./includes_manager/header.html');
?>
<form action="add_vehicle.php" method="post">
<h1>Add Vehicle</h1>
<h3>Please enter vehicle information</h3>
<label for="vehicleType"><p>Vehicle Type:</label><br/>
	<?php
		$mysqli = New mysqli ('localhost', 'root', '', 'projectrental');
		$resultSet = $mysqli->query("SELECT type FROM vehicleTypes");
	?>
<select name="vehicleType">
	<option value = "" >Please choose a vehicle type</option>
	<?php
		while ($row = $resultSet -> fetch_assoc()){
			$vehicleType = $row ["type"];
			echo "<option value = '$vehicleType' >$vehicleType</option>";
		}
	?>
</select>
<label for="vehicleModel"><p>Vehicle Model:</label>
	<input type="text" name="vehicleModel" size="15" maxlength="30" value="<?php if (isset($_POST['vehicleModel'])) echo $_POST['vehicleModel']; ?>" /></p>
<label for="HourRate"><p>Rental Rate Per Hour:</label>
	<input type="text" name="HourRate" size="10" maxlength="10" value="<?php if(isset($_POST['HourRate'])) echo $_POST['HourRate']; ?>"/></p>
<label for="DayRate"><p>Rental Rate Per Day:</label>
	<input type="text" name="DayRate" size="10" maxlength="10" value="<?php if(isset($_POST['DayRate'])) echo $_POST['DayRate']; ?>"/></p>
<p><input type="submit" name="submit" value="Add" /></p>
</form>
<fieldset>
<?php

// Check if the form has been submitted.
if (isset($_POST['submit'])) {
	require_once ('mysqli.php'); // Connect to the db.
	global $dbc;

	$errorMessage = array(); // Initialize error array.
	
	//Validate vehicle type
	if(isset($_POST['vehicleType'])&&$_POST['vehicleType'] == ""){
		$vehicleTypeAdd = NULL;
		$errorMessage[] = 'You forgot to choose the vehicle type!';
	}else {
		$vehicleTypeAdd = $_POST['vehicleType'];
	}

	// Validate model name.
	if (empty($_POST['vehicleModel'])) {
		$errorMessage[] = 'You forgot to enter vehicle model.';
	} else {
		$vehicleModel = ($_POST['vehicleModel']);
	}
	
	//Validate rate per hour
	if (!empty($_POST['HourRate'])) {
		if (is_numeric($_POST['HourRate'])) {
			if ($_POST['HourRate'] < 1){
				$HourRate = NULL;
				$errorMessage[] = 'The rental rate per hour is invalid!';
			} else {
				$HourRate = $_POST['HourRate'];
			}
		} else {
			$HourRate = NULL;
			$errorMessage[] = 'You must enter the rental rate per hour in numeric only!';
		}
	} else {
		$HourRate = NULL;
		$errorMessage[] = 'You forgot to enter the rental rate per hour!';
	}

	//Validate rate per day
	if (!empty($_POST['DayRate'])) {
		if (is_numeric($_POST['DayRate'])) {
			if ($_POST['DayRate'] < 1){
				$DayRate = NULL;
				$errorMessage[] = 'The rental rate per day is invalid!';
			} else {
				$DayRate = $_POST['DayRate'];
			}
		} else {
			$DayRate = NULL;
			$errorMessage[] = 'You must enter the rental rate per day in numeric only!';
		}
	} else {
		$DayRate = NULL;
		$errorMessage[] = 'You forgot to enter the rental rate per day!';
	}
		
		
	if (empty($errorMessage)) { // If everything's okay.

		// Check for previous vehicle records
		$query = "SELECT vehicleID FROM vehicle WHERE vehicleModel='$vehicleModel'";
		$result = @mysqli_query ($dbc,$query); // Run the query.
		if (mysqli_num_rows($result) == 0) {
	
			// Make the query.
			$query = "INSERT INTO vehicle (vehicleType, vehicleModel, ratePerHour, ratePerDay) VALUES ('$vehicleTypeAdd', '$vehicleModel','$HourRate', '$DayRate')";
			$result = @mysqli_query ($dbc,$query); // Run the query. // Run the query.
			if ($result) { // If it ran OK. == IF TRUE
				
				// Print a message.
				echo '<h1 id="mainhead">Thank you!</h1>
				<p>Vehicle is now added. </p><p><br /></p>';	
			
				// Include the footer and quit the script (to not show the form).
				include ('./includes/footer.html'); 
				exit();
				
			} else { // If it did not run OK.
				echo '<h1 id="mainhead">System Error</h1>
				<p class="error">Vehicle could not be added due to a system error. We apologize for any inconvenience.</p>'; // Public message.
				echo '<p>' . mysqli_error($dbc)  . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
				include ('./includes/footer.html'); 
				exit();
			}
		} else { // Already registered.
			echo '<h1 id="mainhead">Error!</h1>
			<p class="error">The vehicle record has already been added.</p>';
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
include ('./includes_manager/footer.html');
?>