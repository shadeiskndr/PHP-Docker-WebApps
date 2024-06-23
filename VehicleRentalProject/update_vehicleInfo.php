<?php
session_start(); // Start the session.
$page_title = 'Update Vehicle Rental Information';
include ('./includes_manager/header.html');
?>
<form action="update_vehicleInfo.php" method="post">
<h1>Update Vehicle Rental Information</h1>
<h3>Search a vehicle record to update</h3></br>
<label for="vType"><p>Filter records by:</label>
	<?php
		$mysqli = New mysqli ('localhost', 'root', '', 'projectrental');
		$resultSet = $mysqli->query("SELECT type FROM vehicleTypes");
	?>
<select id ="vType" name="vType">
<option value="">vehicle type</option>
		<?php
		while ($row = $resultSet -> fetch_assoc()){
			$vType1 = $row ["type"];
			echo "<option value = '$vType1' >$vType1</option>";
		}
	?>
</select> <input type="submit" name="search" value="Search" /></p>
<?php
if (isset($_POST['search'])) {
	$vType = $_POST['vType'];
	echo "<label for='vehicleRecord'><p>Vehicle records: </label></p>";
	
		$mysqli = New mysqli ('localhost', 'root', '', 'projectrental');
		$resultSet = $mysqli->query("SELECT vehicleID, vehicleType, vehicleModel, ratePerHour, ratePerDay FROM vehicle WHERE vehicleType = '$vType'");
	
echo "<p><select id ='vehicleRecord' name='vehicleRecord'>";
echo "<option value=''>Choose a vehicle record</option>";
		while ($row = $resultSet -> fetch_assoc()){
			$vehicleID1 = $row ['vehicleID'];
			$vehicleType1 = $row ['vehicleType'];
			$vehicleModel1 = $row ['vehicleModel'];
			$ratePerHour1 = $row ['ratePerHour'];
			$ratePerDay1 = $row ['ratePerDay'];
			echo "<option value = '$vehicleID1' >$vehicleID1 - Type: $vehicleType1, Model:  $vehicleModel1, Rate: RM$ratePerHour1/hour & RM$ratePerDay1/day</option>"; 
				
		}
echo "</select></p>";
}
?>
<h3>Enter only ONE form to update <br>for the chosen record</h3>
<label for='vehicleType'>Vehicle Type:</label>
	<?php
	$mysqli = New mysqli ('localhost', 'root', '', 'projectrental');
	$resultSet = $mysqli->query("SELECT type FROM vehicleTypes");
	?>
<select name='vehicleType'>
<option value = '' >Please choose a vehicle type</option>
	<?php
	while ($row = $resultSet -> fetch_assoc()){
			$vehicleType2 = $row ["type"];
			echo "<option value = '$vehicleType2' >$vehicleType2</option>";
	}
	?>
</select> <input type='submit' name='update1' value='Update' /><p></p>
<label for="vehicleModel">Vehicle Model:</label>
	<input type="text" name="vehicleModel" size="15" maxlength="30" value="" /> <input type="submit" name="update2" value="Update " /><p></p>
<label for="HourRate">Rental Rate Per Hour:</label>
	<input type="text" name="HourRate" size="10" maxlength="10" value=""/> <input type="submit" name="update3" value="Update" /><p></p>
<label for="DayRate">Rental Rate Per Day:</label>
	<input type="text" name="DayRate" size="10" maxlength="10" value=""/> <input type="submit" name="update4" value="Update" /><br></br>
	<input type="hidden" name="" value="" />
</form>
<fieldset>
<?php
if (isset($_POST['update1'])) {
	require_once ('mysqli.php'); // Connect to the db.
	global $dbc;

	$errorMessage = array(); // Initialize error array.

	// Validate vehicle type.
	if (isset($_POST['vehicleType']) && $_POST['vehicleType'] == "") {
		$errorMessage[] = 'You forgot to enter the vehicle type.';
	} else {
		$vehicleType = ($_POST['vehicleType']);
	}

	//Validate the vehicle ID record
	if(isset($_POST['vehicleRecord']) && $_POST['vehicleRecord'] == ""){
		$vehicleID = NULL;
		$errorMessage[] = 'You forgot to choose the vehicle record!';
	}else {
		$vehicleID = ($_POST['vehicleRecord']);
	}

	if (empty($errorMessage)){
		// Check that they've entered the right vehicle ID record.
		$query = "SELECT vehicleID FROM vehicle WHERE vehicleID = $vehicleID";
		$result = @mysqli_query ($dbc,$query); // Run the query.
		$num = mysqli_num_rows($result);
		if (mysqli_num_rows($result) == 1) { // Match was made.
		
			// Get the vehicle id.
			$row = mysqli_fetch_array($result, MYSQLI_NUM);

			// Make the UPDATE query.
			$query = "UPDATE vehicle SET vehicleType = '$vehicleType' WHERE vehicleID = $row[0]";		
			$result = @mysqli_query ($dbc,$query); // Run the query.
			if ($result) { // If it ran OK.
							
				// Print a message.
				echo '<h1 id="mainhead">Thank you!</h1>
				<p>The vehicle type has been updated. </p><p><br /></p>';	
			
				// Include the footer and quit the script (to not show the form).
				include ('./includes/footer.html'); 
				exit();
				
			} else { // If it did not run OK.
				echo '<h1 id="mainhead">System Error</h1>
				<p class="error">The vehicle type could not be changed due to a system error. We apologize for any inconvenience.</p>'; // Public message.
				echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
				include ('./includes/footer.html'); 
				exit();
			}
				
		} else { // Invalid vehicle ID record.
			echo '<h1 id="mainhead">Error!</h1>
			<p class="error">The vehicle ID record do not match those on file.</p>';
		}
		
	} else { // Report the errors.
	
		echo '<h1 id="mainhead">Error!</h1>
		<p class="error">The following error(s) occurred:<br />';
		foreach ($errorMessage as $msg) { // Print each error.
			echo " - $msg<br />\n";
		}
		echo '</p><p>Please try again.</p><p><br /></p>';
	}
	mysqli_close($dbc); // Close the database connection.
}

if (isset($_POST['update2'])) {
	require_once ('mysqli.php'); // Connect to the db.
	global $dbc;

	$errorMessage = array(); // Initialize error array.

	// Validate vehicle type.
	if (empty($_POST['vehicleModel'])) {
		$errorMessage[] = 'You forgot to enter the vehicle model.';
	} else {
		$vehicleModel = ($_POST['vehicleModel']);
	}

	//Validate the vehicle ID record
	if(isset($_POST['vehicleRecord']) && $_POST['vehicleRecord'] == ""){
		$vehicleID = NULL;
		$errorMessage[] = 'You forgot to choose the vehicle record!';
	}else {
		$vehicleID = ($_POST['vehicleRecord']);
	}

	if (empty($errorMessage)){
		// Check that they've entered the right vehicle ID record.
		$query = "SELECT vehicleID FROM vehicle WHERE vehicleID = $vehicleID";
		$result = @mysqli_query ($dbc,$query); // Run the query.
		$num = mysqli_num_rows($result);
		if (mysqli_num_rows($result) == 1) { // Match was made.
		
			// Get the vehicle id.
			$row = mysqli_fetch_array($result, MYSQLI_NUM);

			// Make the UPDATE query.
			$query = "UPDATE vehicle SET vehicleModel = '$vehicleModel' WHERE vehicleID = $row[0]";		
			$result = @mysqli_query ($dbc,$query); // Run the query.
			if ($result) { // If it ran OK.
							
				// Print a message.
				echo '<h1 id="mainhead">Thank you!</h1>
				<p>The vehicle model has been updated. </p><p><br /></p>';	
			
				// Include the footer and quit the script (to not show the form).
				include ('./includes/footer.html'); 
				exit();
				
			} else { // If it did not run OK.
				echo '<h1 id="mainhead">System Error</h1>
				<p class="error">The vehicle model could not be changed due to a system error. We apologize for any inconvenience.</p>'; // Public message.
				echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
				include ('./includes/footer.html'); 
				exit();
			}
				
		} else { // Invalid vehicle ID record.
			echo '<h1 id="mainhead">Error!</h1>
			<p class="error">The vehicle ID record do not match those on file.</p>';
		}
		
	} else { // Report the errors.
	
		echo '<h1 id="mainhead">Error!</h1>
		<p class="error">The following error(s) occurred:<br />';
		foreach ($errorMessage as $msg) { // Print each error.
			echo " - $msg<br />\n";
		}
		echo '</p><p>Please try again.</p><p><br /></p>';
	}
	mysqli_close($dbc); // Close the database connection.
}

if (isset($_POST['update3'])) {
	require_once ('mysqli.php'); // Connect to the db.
	global $dbc;

	$errorMessage = array(); // Initialize error array.

	//Validate rate per hour
	if (!empty($_POST['HourRate'])) {
		if (is_numeric($_POST['HourRate'])) {
			if ($_POST['HourRate'] < 1){
				$ratePerHour = NULL;
				$errorMessage[] = 'The rental rate per hour is invalid!';
			} else {
				$ratePerHour = $_POST['HourRate'];
			}
		} else {
			$ratePerHour = NULL;
			$errorMessage[] = 'You must enter the rental rate per hour in numeric only!';
		}
	} else {
		$ratePerHour = NULL;
		$errorMessage[] = 'You forgot to enter the rental rate per hour!';
	}

	//Validate the vehicle ID record
	if(isset($_POST['vehicleRecord']) && $_POST['vehicleRecord'] == ""){
		$vehicleID = NULL;
		$errorMessage[] = 'You forgot to choose the vehicle record!';
	}else {
		$vehicleID = ($_POST['vehicleRecord']);
	}

	if (empty($errorMessage)){
		// Check that they've entered the right vehicle ID record.
		$query = "SELECT vehicleID FROM vehicle WHERE vehicleID = $vehicleID";
		$result = @mysqli_query ($dbc,$query); // Run the query.
		$num = mysqli_num_rows($result);
		if (mysqli_num_rows($result) == 1) { // Match was made.
		
			// Get the vehicle id.
			$row = mysqli_fetch_array($result, MYSQLI_NUM);

			// Make the UPDATE query.
			$query = "UPDATE vehicle SET ratePerHour = '$ratePerHour' WHERE vehicleID = $row[0]";		
			$result = @mysqli_query ($dbc,$query); // Run the query.
			if ($result) { // If it ran OK.
							
				// Print a message.
				echo '<h1 id="mainhead">Thank you!</h1>
				<p>The rental rate per hour has been updated. </p><p><br /></p>';	
			
				// Include the footer and quit the script (to not show the form).
				include ('./includes/footer.html'); 
				exit();
				
			} else { // If it did not run OK.
				echo '<h1 id="mainhead">System Error</h1>
				<p class="error">The rental rate per hour could not be changed due to a system error. We apologize for any inconvenience.</p>'; // Public message.
				echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
				include ('./includes/footer.html'); 
				exit();
			}
				
		} else { // Invalid vehicle ID record.
			echo '<h1 id="mainhead">Error!</h1>
			<p class="error">The vehicle ID record do not match those on file.</p>';
		}
		
	} else { // Report the errors.
	
		echo '<h1 id="mainhead">Error!</h1>
		<p class="error">The following error(s) occurred:<br />';
		foreach ($errorMessage as $msg) { // Print each error.
			echo " - $msg<br />\n";
		}
		echo '</p><p>Please try again.</p><p><br /></p>';
	}
	mysqli_close($dbc); // Close the database connection.
}

if (isset($_POST['update4'])) {
	require_once ('mysqli.php'); // Connect to the db.
	global $dbc;

	$errorMessage = array(); // Initialize error array.

	//Validate rate per day
	if (!empty($_POST['DayRate'])) {
		if (is_numeric($_POST['DayRate'])) {
			if ($_POST['DayRate'] < 1){
				$ratePerDay = NULL;
				$errorMessage[] = 'The rental rate per day is invalid!';
			} else {
				$ratePerDay = $_POST['DayRate'];
			}
		} else {
			$ratePerDay = NULL;
			$errorMessage[] = 'You must enter the rental rate per day in numeric only!';
		}
	} else {
		$ratePerDay = NULL;
		$errorMessage[] = 'You forgot to enter the rental rate per day!';
	}

	//Validate the vehicle ID record
	if(isset($_POST['vehicleRecord']) && $_POST['vehicleRecord'] == ""){
		$vehicleID = NULL;
		$errorMessage[] = 'You forgot to choose the vehicle record!';
	}else {
		$vehicleID = ($_POST['vehicleRecord']);
	}

	if (empty($errorMessage)){
		// Check that they've entered the right vehicle ID record.
		$query = "SELECT vehicleID FROM vehicle WHERE vehicleID = $vehicleID";
		$result = @mysqli_query ($dbc,$query); // Run the query.
		$num = mysqli_num_rows($result);
		if (mysqli_num_rows($result) == 1) { // Match was made.
		
			// Get the vehicle id.
			$row = mysqli_fetch_array($result, MYSQLI_NUM);

			// Make the UPDATE query.
			$query = "UPDATE vehicle SET ratePerDay = '$ratePerDay' WHERE vehicleID = $row[0]";		
			$result = @mysqli_query ($dbc,$query); // Run the query.
			if ($result) { // If it ran OK.
							
				// Print a message.
				echo '<h1 id="mainhead">Thank you!</h1>
				<p>The rental rate per day has been updated. </p><p><br /></p>';	
			
				// Include the footer and quit the script (to not show the form).
				include ('./includes/footer.html'); 
				exit();
				
			} else { // If it did not run OK.
				echo '<h1 id="mainhead">System Error</h1>
				<p class="error">The rental rate per day could not be changed due to a system error. We apologize for any inconvenience.</p>'; // Public message.
				echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
				include ('./includes/footer.html'); 
				exit();
			}
				
		} else { // Invalid vehicle ID record.
			echo '<h1 id="mainhead">Error!</h1>
			<p class="error">The vehicle ID record do not match those on file.</p>';
		}
		
	} else { // Report the errors.
	
		echo '<h1 id="mainhead">Error!</h1>
		<p class="error">The following error(s) occurred:<br />';
		foreach ($errorMessage as $msg) { // Print each error.
			echo " - $msg<br />\n";
		}
		echo '</p><p>Please try again.</p><p><br /></p>';
	}
	mysqli_close($dbc); // Close the database connection.
}
?>
</fieldset>
<?php
include ('./includes_manager/footer.html');
?>