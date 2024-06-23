<?php
session_start(); // Start the session.
$page_title = 'Delete Vehicle Rental Information';
include ('./includes_manager/header.html');
?>
<form action="delete_vehicle.php" method="post">
<h1>Delete Vehicle Rental Information</h1>
<h3>Search and delete vehicle record</h3></br>
<label for="vehicleType"><p>Vehicle type:</label>
	<?php
		$mysqli = New mysqli ('localhost', 'root', '', 'projectrental');
		$resultSet = $mysqli->query("SELECT type FROM vehicleTypes");
	?>
<select id ="vehicleType" name="vehicleType">
<option value="">Choose a vehicle type</option>
		<?php
		while ($row = $resultSet -> fetch_assoc()){
			$vehicleType = $row ["type"];
			echo "<option value = '$vehicleType' >$vehicleType</option>";
		}
	?>
</select> <input type="submit" name="search" value="Search" /></p>
<?php
if (isset($_POST['search'])) {
	$vType = $_POST['vehicleType'];
	echo "<label for='vehicleRecord'><p>Vehicle records:</label></p>";
	
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
<p><input type="submit" name="delete" value="Delete" /></p>
</form>
</br>
<fieldset>
<?php
if (isset($_POST['delete'])) {
	require_once ('mysqli.php'); // Connect to the db.
	global $dbc;

	$errorMessage = array(); // Initialize error array.

	//Validate the vehicle ID record
	if(isset($_POST['vehicleRecord']) && $_POST['vehicleRecord'] == ""){
		$vehicleID = NULL;
		$errorMessage[] = 'You forgot to choose the vehicle record!';
	}else {
		$vehicleID = ($_POST['vehicleRecord']);
	}

	if (empty($errorMessage)){
		// Check that they've entered the right vehicle ID record.
		$query = "SELECT vehicleID FROM vehicle WHERE vehicleID = '$vehicleID'";
		$result = @mysqli_query ($dbc,$query); // Run the query.
		$num = mysqli_num_rows($result);
		if (mysqli_num_rows($result) == 1) { // Match was made.
		
			// Get the vehicle id.
			$row = mysqli_fetch_array($result, MYSQLI_NUM);

			// Make the DELETE query.
			$query = "DELETE FROM vehicle WHERE vehicleID = $row[0]";		
			$result = @mysqli_query ($dbc,$query); // Run the query.
			if ($result) { // If it ran OK.
							
				// Print a message.
				echo '<h1 id="mainhead">Thank you!</h1>
				<p>The vehicle record has been deleted. </p><p><br /></p>';	
			
				// Include the footer and quit the script (to not show the form).
				include ('./includes_manager/footer.html'); 
				exit();
				
			} else { // If it did not run OK.
				echo '<h1 id="mainhead">System Error</h1>
				<p class="error">The vehicle record could not be changed due to a system error. We apologize for any inconvenience.</p>'; // Public message.
				echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
				include ('./includes_manager/footer.html'); 
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