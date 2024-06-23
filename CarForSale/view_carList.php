<?php # Script 7.6 - view_users.php (2nd version after Script 7.4)
// This script retrieves all the records from the users table.

$page_title = 'View the List of Cars';
include ('./includes/header.html');

// Page header.
echo '<h1 id="mainhead">Car List</h1>';

require_once ('mysqli.php'); // Connect to the db.
global $dbc;
		
// Make the query.
$query = "SELECT manufacturerName, modelName, acquisitionPrice, dateAcquired
		FROM carforsale ORDER BY manufacturerName ASC";		
$result = @mysqli_query ($dbc,$query); // Run the query.
$num = mysqli_num_rows($result);

if ($num > 0) { // If it ran OK, display the records.

	echo "<p>There are currently $num registered cars.</p>\n";

	// Table header.
	echo '
			<table align="center" cellspacing="0" cellpadding="5">
			<tr>
			<td align="left"><b>Manufacturer</b></td>
			<td align="left"><b>Name</b></td>
			<td align="left"><b>Price</b></td>
			<td align="left"><b>Date</b></td>
			</tr>
			';
	
	// Fetch and print all the records.
	while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
		echo '
		<tr>
				<td align="left">' . $row['manufacturerName'] . '</td>
				<td align="left">' . $row['modelName'] . '</td>
				<td align="left">' . $row['acquisitionPrice'] . '</td>
				<td align="left">' . $row['dateAcquired'] . '</td>
				</tr>
				';
	}

	echo '</table>';
	
	mysqli_free_result($result); // Free up the resources.	

} else { // If it did not run OK.
	echo '<p class="error">There are currently no registered cars.</p>';
}

mysqli_close($dbc); // Close the database connection.

include ('./includes/footer.html'); // Include the HTML footer.
?>
