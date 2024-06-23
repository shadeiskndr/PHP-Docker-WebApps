<?php
session_start(); // Start the session.
$page_title = 'Delete Pending Rental Request';
include ('./includes_customer/header.html');
?>
<form action="cust_delReq.php" method="post">
<h1>Delete Pending Rental Request</h1>
<h3>Delete vehicle rental request (pending only)</h3>
<label for="requests">Pending rental requests:</label>
	<?php
		$customerID = $_SESSION['customerID'];
		$mysqli = New mysqli ('localhost', 'root', '', 'projectrental');
		$resultSet = $mysqli->query("SELECT v.vehicleType AS vehicleType, v.vehicleModel AS vehicleModel, r.durationNo AS durationNo, r.hourOrDay AS hourOrDay, r.totalPrice AS totalPrice, r.rentalID AS rentalID FROM vehicle AS v INNER JOIN rental AS r ON v.vehicleID = r.vehicleID WHERE r.customerID = '$customerID' AND r.status = 'Pending'");
	?>
<select id ="requests" name="requests">
<option value=""><p>Choose a request to delete</option><p></p>
		<?php
		while ($row = $resultSet -> fetch_assoc()){
			$rentalID1 = $row ["rentalID"];
			$vehicleID1 = $row ["vehicleID"];
			$vehicleType1 = $row ["vehicleType"];
			$vehicleModel1 = $row ["vehicleModel"];
			$durationNo1 = $row ["durationNo"];
			$hourOrDay1 = $row ["hourOrDay"];
			$totalPrice1 = $row ["totalPrice"];
			echo "<option value = '$rentalID1'>$rentalID1 - $vehicleType1/$vehicleModel1 | $durationNo1 $hourOrDay1 | RM$totalPrice1</option>";
		}
	?>
</select></p> <input type="submit" name="delete" value="Delete" />
</form>
</br>
<fieldset>
<?php
if (isset($_POST['delete'])) {
	require_once ('mysqli.php'); // Connect to the db.
	global $dbc;

	$errorMessage = array(); // Initialize error array.

	//Validate the rental ID record
	if(isset($_POST['requests']) && $_POST['requests'] == ""){
		$rentalID = NULL;
		$errorMessage[] = 'You forgot to choose the vehicle rental request!';
	}else {
		$rentalID = ($_POST['requests']);
	}

	if (empty($errorMessage)){
		// Check that they've entered the right rental request ID record.
		$query = "SELECT rentalID FROM rental WHERE rentalID = '$rentalID'";
		$result = @mysqli_query ($dbc,$query); // Run the query.
		$num = mysqli_num_rows($result);
		if (mysqli_num_rows($result) == 1) { // Match was made.
		
			// Get the rental id.
			$row = mysqli_fetch_array($result, MYSQLI_NUM);

			// Make the DELETE query.
			$query = "DELETE FROM rental WHERE rentalID = $row[0]";		
			$result = @mysqli_query ($dbc,$query); // Run the query.
			if ($result) { // If it ran OK.
							
				// Print a message.
				echo '<h1 id="mainhead">Thank you!</h1>
				<p>The vehicle rental request has been deleted. </p><p><br /></p>';	
			
				// Include the footer and quit the script (to not show the form).
				include ('./includes_customer/footer.html'); 
				exit();
				
			} else { // If it did not run OK.
				echo '<h1 id="mainhead">System Error</h1>
				<p class="error">The vehicle rental request could not be changed due to a system error. We apologize for any inconvenience.</p>'; // Public message.
				echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
				include ('./includes/footer.html'); 
				exit();
			}
				
		} else { // Invalid rental request ID record.
			echo '<h1 id="mainhead">Error!</h1>
			<p class="error">The rental request ID record do not match those on file.</p>';
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
include ('./includes_customer/footer.html');
?>