<?php
session_start(); // Start the session.
$page_title = 'Vehicle List';
include ('./includes_manager/header.html');
?>
<form action="list_vehicle.php" method="post">
<h1>Vehicle List</h1>
<h3>List all or search vehicle by type</h3>
<label for="vehicleType"><p>Vehicle type:</label>
	<?php
		$mysqli = New mysqli ('localhost', 'root', '', 'projectrental');
		$resultSet = $mysqli->query("SELECT DISTINCT vehicleType FROM vehicle");
	?>
<select id ="vehicleType" name="vehicleType">
<option value="">Choose a vehicle type </option>
		<?php
		while ($row = $resultSet -> fetch_assoc()){
			$vehicleType = $row ["vehicleType"];
			echo "<option value = '$vehicleType' >$vehicleType</option>";
		}
	?>
</select> <input type="submit" name="search" value="Search" /><p></p>
	<input type="submit" name="list" value="List All" /></p>
	<input type="hidden" name="" value="" />
</form>
</br>
<fieldset>

<?php
// Search for a vehicle type and display the vehicles related to that type
if (isset($_POST['search'])) {
	require_once ('mysqli.php'); // Connect to the db.
	global $dbc;

	$errorMessage = array(); // Initialize error array.

	//Validate vehicle type
	if(isset($_POST['vehicleType']) && $_POST['vehicleType'] == ""){
		$vehicleTypeSearch = NULL;
		$errorMessage[] = 'You forgot to choose vehicle type!';
	}else{
		$vehicleTypeSearch = $_POST['vehicleType'];
	}

	if (empty($errorMessage)) { // If everything's OK.
		//Search for vehicle record with vehice type.
		$query = "SELECT vehicleID, vehicleType, vehicleModel, ratePerHour, ratePerDay FROM vehicle WHERE vehicleType = '$vehicleTypeSearch'";
		$result = @mysqli_query ($dbc,$query); // Run the query.
		$num = mysqli_num_rows($result);
		if (mysqli_num_rows($result) > 0) { // Match was made.

			echo "<p>There are currently $num vehicle record.</p>\n";
			
			// Table header.
			echo '
			<table align="center" cellspacing="0" cellpadding="5">
			<tr>
			<td align="left"><b>ID</b></td>
			<td align="left"><b>Type</b></td>
			<td align="left"><b>Model</b></td>
			<td align="left"><b>Hour Rate (RM)</b></td>
			<td align="left"><b>Day Rate (RM)</b></td>
			</tr>
			';
			
			// Fetch and print all the records.
			while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
				echo '
				<tr>
				<td align="left">' . $row['vehicleID'] . '</td>
				<td align="left">' . $row['vehicleType'] . '</td>
				<td align="left">' . $row['vehicleModel'] . '</td>
				<td align="left">' . $row['ratePerHour'] . '</td>
				<td align="left">' . $row['ratePerDay'] . '</td>
				</tr>
				';
			}
			echo '</table>';
			mysqli_free_result($result); // Free up the resources.	
			

		}else { // If it did not run OK.
			echo '<p class="error">There are currently no vehicles with the following type.</p>';
		}
	}else { // Report the errors.
	
	echo '<h1 id="mainhead">Error!</h1>
	<p class="error">The following error(s) occurred:<br />';
	foreach ($errorMessage as $msg) { // Print each error.
		echo " - $msg<br />\n";
	}
	echo '</p><p>Please try again.</p><p><br /></p>';
	}	

}
?>

<?php
// Check if the form has been submitted.
if (isset($_POST['list'])) {
	require_once ('mysqli.php'); // Connect to the db.
	global $dbc;

	// Make the query.
	$query = "SELECT vehicleID, vehicleType, vehicleModel, ratePerHour, ratePerDay FROM vehicle";		
	$result = @mysqli_query ($dbc,$query); // Run the query.
	$num = mysqli_num_rows($result);

	if ($num > 0) { // If it ran OK, display the records.

		echo "<p>There are currently $num vehicle record.</p>\n";

		// Table header.
		echo '
			<table align="center" cellspacing="0" cellpadding="5">
			<tr>
			<td align="left"><b>ID</b></td>
			<td align="left"><b>Type</b></td>
			<td align="left"><b>Model</b></td>
			<td align="left"><b>Hour Rate (RM)</b></td>
			<td align="left"><b>Day Rate (RM)</b></td>
			</tr>
			';
		
		// Fetch and print all the records.
		while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			echo '
				<tr>
				<td align="left">' . $row['vehicleID'] . '</td>
				<td align="left">' . $row['vehicleType'] . '</td>
				<td align="left">' . $row['vehicleModel'] . '</td>
				<td align="left">' . $row['ratePerHour'] . '</td>
				<td align="left">' . $row['ratePerDay'] . '</td>
				</tr>
				';
		}

		echo '</table>';
	
		mysqli_free_result($result); // Free up the resources.	

	} else { // If it did not run OK.
		echo '<p class="error">There are currently no registered vehicles in the database.</p>';
	}

	mysqli_close($dbc); // Close the database connection.
		
} // End of the main Submit conditional.

?>
</fieldset>
<?php
include ('./includes_manager/footer.html');
?>