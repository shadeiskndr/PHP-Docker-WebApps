<?php
session_start(); // Start the session.
$page_title = 'Rental Request List';
include ('./includes_manager/header.html');
?>
<form action="mngr_listReq.php" method="post">
<h1>Vehicle Rental Request List</h1>
<h3>Search vehicle rental request by status</h3>
<label for="status"><p>Rental request status: </label>
	<?php
		//$customerID = $_SESSION['managerID'];
		$mysqli = New mysqli ('localhost', 'root', '', 'projectrental');
		$resultSet = $mysqli->query("SELECT DISTINCT status FROM rental");
	?>
<select id ="status" name="status">
<option value="">Choose a status</option>
		<?php
		while ($row = $resultSet -> fetch_assoc()){
			$status = $row ["status"];
			echo "<option value = '$status' >$status</option>";
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
	if(isset($_POST['status']) && $_POST['status'] == ""){
		$statusSearch = NULL;
		$errorMessage[] = 'You forgot to choose a status!';
	}else{
		$statusSearch = $_POST['status'];
	}

	if (empty($errorMessage)) { // If everything's OK.
		//Search for vehicle record with vehice type.
		$query = "SELECT v.vehicleType AS vehicleType, v.vehicleModel AS vehicleModel, r.durationNo AS durationNo, r.hourOrDay AS hourOrDay, r.startDate AS startDate, r.returnDate AS returnDate, r.totalPrice AS totalPrice, r.status AS status, c.name AS name, c.contactNo AS contact, c.address AS address FROM vehicle AS v INNER JOIN rental AS r ON v.vehicleID = r.vehicleID INNER JOIN customer AS c ON c.customerID = r.customerID WHERE r.status = '$statusSearch'";
		$result = @mysqli_query ($dbc,$query); // Run the query.
		$num = mysqli_num_rows($result);
		if (mysqli_num_rows($result) > 0) { // Match was made.

			echo "<p>There are currently $num vehicle rental requests.</p>\n";
			
			// Table header.
			echo '
			<table align="center" cellspacing="0" cellpadding="5">
			<tr>
			<td align="left"><b>Name</b></td>
			<td align="left"><b>Contact No.</b></td>
			<td align="left"><b>Address</b></td>
			<td align="left"><b>Type/Model</b></td>
			<td align="left"><b>Duration</b></td>
			<td align="left"><b>Start Date</b></td>
			<td align="left"><b>Return Date</b></td>
			<td align="left"><b>Total Rate (RM)</b></td>
			<td align="left"><b>Status</b></td>
			</tr>
			';
			
			// Fetch and print all the records.
			while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
				echo '
				<tr>
				<td align="left">' . $row['name'] . '</td>
				<td align="left">' . $row['contact'] . '</td>
				<td align="left">' . $row['address'] . '</td>
				<td align="left">' . $row['vehicleType'] . '/' . $row['vehicleModel'] . '</td>
				<td align="left">' . $row['durationNo'] . ' ' . $row['hourOrDay'] . '</td>
				<td align="left">' . $row['startDate'] . '</td>
				<td align="left">' . $row['returnDate'] . '</td>
				<td align="left">' . $row['totalPrice'] . '</td>
				<td align="left">' . $row['status'] . '</td>
				</tr>
				';
			}
			echo '</table>';
			mysqli_free_result($result); // Free up the resources.	
			

		}else { // If it did not run OK.
			echo '<p class="error">There are currently no vehicle rental requests with the following status.</p>';
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
	$query = "SELECT v.vehicleType AS vehicleType, v.vehicleModel AS vehicleModel, r.durationNo AS durationNo, r.hourOrDay AS hourOrDay, r.startDate AS startDate, r.returnDate AS returnDate, r.totalPrice AS totalPrice, r.status AS status, c.name AS name, c.contactNo AS contact, c.address AS address FROM vehicle AS v INNER JOIN rental AS r ON v.vehicleID = r.vehicleID INNER JOIN customer AS c ON c.customerID = r.customerID";		
	$result = @mysqli_query ($dbc,$query); // Run the query.
	$num = mysqli_num_rows($result);

	if ($num > 0) { // If it ran OK, display the records.

		echo "<p>There are currently $num vehicle rental requests.</p>\n";

		// Table header.
		echo '
			<table align="center" cellspacing="0" cellpadding="5">
			<tr>
			<td align="left"><b>Name</b></td>
			<td align="left"><b>Contact No.</b></td>
			<td align="left"><b>Address</b></td>
			<td align="left"><b>Type/Model</b></td>
			<td align="left"><b>Duration</b></td>
			<td align="left"><b>Start Date</b></td>
			<td align="left"><b>Return Date</b></td>
			<td align="left"><b>Total Rate (RM)</b></td>
			<td align="left"><b>Status</b></td>
			</tr>
			';
		
		// Fetch and print all the records.
		while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			echo '
				<tr>
				<td align="left">' . $row['name'] . '</td>
				<td align="left">' . $row['contact'] . '</td>
				<td align="left">' . $row['address'] . '</td>
				<td align="left">' . $row['vehicleType'] . '/' . $row['vehicleModel'] . '</td>
				<td align="left">' . $row['durationNo'] . ' ' . $row['hourOrDay'] . '</td>
				<td align="left">' . $row['startDate'] . '</td>
				<td align="left">' . $row['returnDate'] . '</td>
				<td align="left">' . $row['totalPrice'] . '</td>
				<td align="left">' . $row['status'] . '</td>
				</tr>
				';
		}

		echo '</table>';
	
		mysqli_free_result($result); // Free up the resources.	

	} else { // If it did not run OK.
		echo '<p class="error">There are currently no vehicle rental requests available in the database.</p>';
	}

	mysqli_close($dbc); // Close the database connection.
		
} // End of the main Submit conditional.

?>
</fieldset>
<?php
include ('./includes_manager/footer.html');
?>