<?php

$connect = mysqli_connect(
    'db', # service name
    'php_docker', # username
    'password', # password
    'php_docker' # db table
);

$table_name = "inventory";
$queryCategory = "SELECT DISTINCT category FROM inventory";
$queryProduct = "SELECT DISTINCT product FROM inventory";
$querySize = "SELECT DISTINCT size FROM inventory";

?>

<?php
$page_title = 'Mawar Boutique Inventory Update';
?>
<form action="updateInventory.php" method="post">
<h1>Mawar Boutique Inventory Update</h1>
<h3>Enter the forms below to update record</h3>
<label for="Category">Category: </label>
	<?php
		$resultCategories = mysqli_query($connect, $queryCategory);
	?>
<select id ="Category" name="Category">
<option value="">Choose a category</option><p></p>
		<?php
		while ($row = mysqli_fetch_assoc($resultCategories)){
			$category1 = $row ["category"];
			echo "<option value = '$category1' >$category1</option>";
		}
	?>
</select><p></p>

<label for="Product">Product: </label>
	<?php
		$resultProducts = mysqli_query($connect, $queryProduct);
	?>
<select id ="Product" name="Product">
<option value="">Choose a product</option><p></p>
		<?php
		while ($row = mysqli_fetch_assoc($resultProducts)){
			$product1 = $row ["product"];
			echo "<option value = '$product1' >$product1</option>";
		}
	?>
</select><p></p>

<label for="Size">Size: </label>
	<?php
		$resultSize = mysqli_query($connect, $querySize);
	?>
<select id ="Size" name="Size">
<option value="">Choose a size</option><p></p>
		<?php
		while ($row = mysqli_fetch_assoc($resultSize)){
			$size1 = $row ["size"];
			echo "<option value = '$size1' >$size1</option>";
		}
	?>
</select><p></p>

<label for="Quantity">NEW Quantity: </label>
	<input type="text" name="Quantity" size="10" maxlength="10" value="<?php if(isset($_POST['Quantity'])) echo $_POST['Quantity']; ?>"/><p></p>

<input type="submit" name="update" value="INVENTORY UPDATE ITEM" /><br></br>

<?php
if (isset($_POST['update'])) {

	$errorMessage = array(); // Initialize error array.

	// Validate category.
	if (isset($_POST['Category']) && $_POST['Category'] == "") {
		$errorMessage[] = 'You forgot to enter the category.';
	} else {
		$category = ($_POST['Category']);
	}

	// Validate product.
	if (isset($_POST['Product']) && $_POST['Product'] == "") {
		$errorMessage[] = 'You forgot to enter the product.';
	} else {
		$product = ($_POST['Product']);
	}

	// Validate size.
	if (isset($_POST['Size']) && $_POST['Size'] == "") {
		$errorMessage[] = 'You forgot to enter the size.';
	} else {
		$size = ($_POST['Size']);
	}

	//Validate quantity
	if (!empty($_POST['Quantity'])) {
		if (is_numeric($_POST['Quantity'])) {
			if ($_POST['Quantity'] < 1){
				$quantity = NULL;
				$errorMessage[] = '<p>The NEW quantity is invalid!</p>';
			} else {
				$quantity = $_POST['Quantity'];
			}
		} else {
			$quantity = NULL;
			$errorMessage[] = '<p>You must enter NEW quantity in numeric only!</p>';
		}
	} else {
		$quantity = NULL;
		$errorMessage[] = '<p>You forgot to enter the NEW quantity!</p>';
	}

	if (empty($errorMessage)){
		// Check that they've entered the inventory record.
		$queryInventoryRecord = "SELECT * FROM inventory WHERE category = '$category' AND product = '$product' AND size = '$size'";
		$responseInventoryRecord = mysqli_query($connect, $queryInventoryRecord); // Run the query.
		$num = mysqli_num_rows($responseInventoryRecord);
		
		if (mysqli_num_rows($responseInventoryRecord) == 1) { // Match was made.
		
			// Get the record.
			$row = mysqli_fetch_array($responseInventoryRecord, MYSQLI_NUM);

			// Make the UPDATE query.
			$queryUpdateRecord = "UPDATE inventory SET quantity = '$quantity'  WHERE category = '$category' AND product = '$product' AND size = '$size'";		
			$responseUpdateRecord = @mysqli_query($connect,$queryUpdateRecord); // Run the query.
			if ($responseUpdateRecord) { // If it ran OK.
							
				// Print a message.
				echo "<h1 id=\"mainhead\">Thank you!</h1>
				<p>The quantity of '$category': '$product': '$size' has been updated in the database. </p><p><br /></p>";	 
				exit();
				
			} else { // If it did not run OK.
				echo '<h1 id="mainhead">System Error</h1>
				<p class="error">The record could not be changed because it is not found.</p>'; // Public message.
				echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $query . '</p>'; // Debugging message.
				exit();
			}
				
		} else { // Invalid record.
			echo '<h1 id="mainhead">Error!</h1>
			<p class="error">The record is NOT found.</p>';
		}
		
	} else { // Report the errors.
	
		echo '<h1 id="mainhead">Error!</h1>
		<p class="error">The following error(s) occurred:<br />';
		foreach ($errorMessage as $msg) { // Print each error.
			echo " - $msg<br />\n";
		}
		echo '</p><p>Please try again.</p><p><br /></p>';
	}
}
?>