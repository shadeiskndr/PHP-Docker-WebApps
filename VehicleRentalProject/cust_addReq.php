<?php
session_start(); // Start the session.
$page_title = 'Add Vehicle Rental Request';
include ('./includes_customer/header.html');
?>
<form action="cust_addReq.php" method="post">
<h1>Add Vehicle Rental Request</h1>
<h3>Search a vehicle to add for rental request</h3>
<label for="vType">Filter records by:</label>
	<?php
		$mysqli = New mysqli ('localhost', 'root', '', 'projectrental');
		$resultSet = $mysqli->query("SELECT type FROM vehicleTypes");
	?>
<select id ="vType" name="vType">
<option value="">vehicle type</option><p></p>
		<?php
		while ($row = $resultSet -> fetch_assoc()){
			$vType1 = $row ["type"];
			echo "<option value = '$vType1' >$vType1</option>";
		}
	?>
</select> <input type="submit" name="search" value="Search" /><p></p>
<?php
if (isset($_POST['search'])) {
	$vType = $_POST['vType'];
	echo "<label for='vehicleRecord'>Vehicle records: </label>";
	
		$mysqli = New mysqli ('localhost', 'root', '', 'projectrental');
		$resultSet = $mysqli->query("SELECT vehicleID, vehicleType, vehicleModel, ratePerHour, ratePerDay FROM vehicle WHERE vehicleType = '$vType'");
	
echo "<select id ='vehicleRecord' name='vehicleRecord'>";
echo "<option value=''>Choose a vehicle record</option>";
		while ($row = $resultSet -> fetch_assoc()){
			$vehicleID1 = $row ['vehicleID'];
			$vehicleType1 = $row ['vehicleType'];
			$vehicleModel1 = $row ['vehicleModel'];
			$ratePerHour1 = $row ['ratePerHour'];
			$ratePerDay1 = $row ['ratePerDay'];
			echo "<option value = \"$vehicleID1|$ratePerHour1|$ratePerDay1\" >$vehicleID1 - Type: $vehicleType1, Model:  $vehicleModel1, Rate: RM$ratePerHour1/hour & RM$ratePerDay1/day</option>"; 
				
		}
echo "</select><p></p>";
}
?>
<h3>Enter the forms below to request</br>the chosen vehicle for rental</h3>
<label for="startDate"><p>Rental Start (date and time): </label>
	<input type="datetime-local" name="startDate"/></p>
<label for="durationNo"><p>Rental Duration (number):</label>
	<input type="text" name="durationNo" size="10" maxlength="10" value="<?php if(isset($_POST['durationNo'])) echo $_POST['durationNo']; ?>"/>
  <input type="radio" id="Hour" name="hourOrDay" value="Hour">
<label for="Hour">Hour</label>
  <input type="radio" id="hourOrDay" name="hourOrDay" value="Day">
 <label for="Day">Day</label></p>
<input type="hidden" id="status" name="status" value="Pending">
<p><input type="submit" name="submit" value="Request" /></p>
</form>
<fieldset>
<?php

// Check if the form has been submitted.
if (isset($_POST['submit'])) {
	require_once ('mysqli.php'); // Connect to the db.
	global $dbc;

	$errorMessage = array(); // Initialize error array.
	
	//Validate the vehicle ID record
	if(isset($_POST['vehicleRecord']) && $_POST['vehicleRecord'] == ""){
		$vehicleID = NULL;
		$errorMessage[] = 'You forgot to choose the vehicle record!';
	}else {
		$vehicleResult = $_POST['vehicleRecord'];
    	$result_explode = explode('|', $vehicleResult);
		$vehicleID = $result_explode[0];
		$ratePerHour = $result_explode[1];
		$ratePerDay = $result_explode[2];
	}

	// Validate the start date and time
	if(isset($_POST['startDate']) && strtotime($_POST['startDate'])){
       $startDate = $_POST['startDate'];
   } else {
      $errorMessage[] = "You forgot to enter the start rental date and time";
   }        

	//Validate duration number
	if (!empty($_POST['durationNo'])) {
		if (is_numeric($_POST['durationNo'])) {
			if ($_POST['durationNo'] < 1){
				$durationNo = NULL;
				$errorMessage[] = 'The rental duration number is invalid!';
			} else {
				$durationNo = $_POST['durationNo'];
			}
		} else {
			$durationNo = NULL;
			$errorMessage[] = 'You must enter the rental duration number in numeric only!';
		}
	} else {
		$durationNo = NULL;
		$errorMessage[] = 'You forgot to enter the rental duration number!';
	}

	//Validate duration radio choice hour or day
	if (!isset($_POST['hourOrDay'])){
		$hourOrDay = NULL;
		$errorMessage[] = 'You forgot to select hour or day duration!';
	}else {
		$hourOrDay = $_POST['hourOrDay'];
	}

	//Assign hidden status input type form to a variable
	$status = $_POST['status'];
	
	//Assign session customer ID to a variable
	$customerID = $_SESSION['customerID'];

	if (empty($errorMessage)) { // If everything's okay.

		function rateCalculation($rate, $durationNo){
			$total = $rate * $durationNo;
			return $total;
		}

		switch ($hourOrDay){
			case "Hour":
			$rate = $ratePerHour;
			$totalPrice = rateCalculation($rate, $durationNo);
			$returnDate = date("Y-m-d H:i:s", strtotime("+{$durationNo} hours", strtotime($startDate)));
			break;

			case "Day":
			$rate = $ratePerDay;
			$totalPrice = rateCalculation($rate, $durationNo);
			$returnDate = date("Y-m-d H:i:s", strtotime("+{$durationNo} days", strtotime($startDate)));
			break;
		}

		// Check for previous vehicle records
		$query = "SELECT rentalID FROM rental WHERE (vehicleID = '$vehicleID' AND customerID = '$customerID')";
		$result = @mysqli_query ($dbc,$query); // Run the query.
		if (mysqli_num_rows($result) == 0) {
	
			// Make the query.
			$query = "INSERT INTO rental (startDate, returnDate, durationNo, hourOrDay, status, totalPrice, vehicleID, customerID) VALUES ('$startDate', '$returnDate', '$durationNo', '$hourOrDay', '$status', '$totalPrice', '$vehicleID', '$customerID')";
			$result = @mysqli_query ($dbc,$query); // Run the query. // Run the query.
			if ($result) { // If it ran OK. == IF TRUE
				
				// Print a message.
				echo '<h1 id="mainhead">Thank you!</h1>
				<p>Vehicle rental request is now added. </p><p><br /></p>';	
			
				// Include the footer and quit the script (to not show the form).
				include ('./includes/footer.html'); 
				exit();
				
			} else { // If it did not run OK.
				echo '<h1 id="mainhead">System Error</h1>
				<p class="error">Vehicle rental request could not be added due to a system error. We apologize for any inconvenience.</p>'; // Public message.
				echo '<p>' . mysqli_error($dbc)  . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
				include ('./includes/footer.html'); 
				exit();
			}
		} else { // Already registered.
			echo '<h1 id="mainhead">Error!</h1>
			<p class="error">The vehicle rental request has already been added.</p>';
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
include ('./includes_customer/footer.html');
?>