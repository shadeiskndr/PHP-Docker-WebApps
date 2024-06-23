<?php
// Set the page title and include the HTML header.
$page_title = 'List of Movies';
include ('./includes/header.html');
?>
<form action="admin_listMovie.php" method="post">
<h1>Movie List</h1>
<h3>List all or search movies by genre</h3>
<label for="name"><p>Movie genre:</label>
<select id ="genre" name="genre">
<option value="">Choose a movie genre</option>
		<option value="1" <?php if(isset($_POST['genre']) && $_POST['genre'] == "1") echo 'selected="selected"'; ?> >Action/Adventure</option>
		<option value="2" <?php if(isset($_POST['genre']) && $_POST['genre'] == "2") echo 'selected="selected"'; ?> >Comedy</option>
		<option value="3" <?php if(isset($_POST['genre']) && $_POST['genre'] == "3") echo 'selected="selected"'; ?> >Drama</option>
		<option value="4" <?php if(isset($_POST['genre']) && $_POST['genre'] == "4") echo 'selected="selected"'; ?> >Fantasy/Sci-Fi</option>
	</select> <input type="submit" name="search" value="Search" /><input type="submit" name="list" value="List All" /></p>
	<input type="hidden" name="" value="" />
</form>
<fieldset>

<?php
// Search for a genre and display the movies related to that genre
if (isset($_POST['search'])) {
	require_once ('mysqli.php'); // Connect to the db.
	global $dbc;

	$errorMessage = array(); // Initialize error array.

	//Validate movie genre
	if(isset($_POST['genre']) && $_POST['genre'] == ""){
		$genre = NULL;
		$errorMessage[] = '<p>You forgot to choose movie genre!</p>';
	}else{
		$genre = $_POST['genre'];
	}

	if (empty($errorMessage)) { // If everything's OK.
		//Search for movies with said movie genre.
		$query = "SELECT m.name AS moviename, m.year AS year, g.name AS genrename, m.mdate AS moviedate, m.mtime AS movietime, m.rating AS movierating FROM movies AS m INNER JOIN genres AS g ON m.genre = g.id WHERE m.genre = $genre";
		$result = @mysqli_query ($dbc,$query); // Run the query.
		$num = mysqli_num_rows($result);
		if (mysqli_num_rows($result) > 0) { // Match was made.
			
			// Table header.
			echo '
			<table align="center" cellspacing="0" cellpadding="5">
			<tr>
			<td align="left"><b>Name</b></td>
			<td align="left"><b>Year</b></td>
			<td align="left"><b>Genre</b></td>
			<td align="left"><b>Date</b></td>
			<td align="left"><b>Time</b></td>
			<td align="left"><b>Rating</b></td>
			</tr>
			';
			
			// Fetch and print all the records.
			while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
				echo '
				<tr>
				<td align="left">' . $row['moviename'] . '</td>
				<td align="left">' . $row['year'] . '</td>
				<td align="left">' . $row['genrename'] . '</td>
				<td align="left">' . $row['moviedate'] . '</td>
				<td align="left">' . $row['movietime'] . '</td>
				<td align="left">' . $row['movierating'] . '</td>
				</tr>
				';
			}
			echo '</table>';
			mysqli_free_result($result); // Free up the resources.	
			

		}else { // If it did not run OK.
			echo '<p class="error">There are currently no movies with the following genre.</p>';
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
	$query = "SELECT m.name AS moviename, m.year AS year, g.name AS genrename, m.mdate AS moviedate, m.mtime AS movietime, m.rating AS movierating FROM movies AS m INNER JOIN genres AS g ON m.genre = g.id";		
	$result = @mysqli_query ($dbc,$query); // Run the query.
	$num = mysqli_num_rows($result);

	if ($num > 0) { // If it ran OK, display the records.

		echo "<p>There are currently $num movies.</p>\n";

		// Table header.
		echo '
			<table align="center" cellspacing="0" cellpadding="5">
			<tr>
			<td align="left"><b>Name</b></td>
			<td align="left"><b>Year</b></td>
			<td align="left"><b>Genre</b></td>
			<td align="left"><b>Date</b></td>
			<td align="left"><b>Time</b></td>
			<td align="left"><b>Rating</b></td>
			</tr>
			';
		
		// Fetch and print all the records.
		while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
			echo '
				<tr>
				<td align="left">' . $row['moviename'] . '</td>
				<td align="left">' . $row['year'] . '</td>
				<td align="left">' . $row['genrename'] . '</td>
				<td align="left">' . $row['moviedate'] . '</td>
				<td align="left">' . $row['movietime'] . '</td>
				<td align="left">' . $row['movierating'] . '</td>
				</tr>
				';
		}

		echo '</table>';
	
		mysqli_free_result($result); // Free up the resources.	

	} else { // If it did not run OK.
		echo '<p class="error">There are currently no registered users.</p>';
	}

	mysqli_close($dbc); // Close the database connection.
		
} // End of the main Submit conditional.

?>
</fieldset>
<?php
include ('./includes/footer.html');
?>