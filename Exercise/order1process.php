<html>
<head>
<title>Beautiful Flower Supplies</title>
</head>
<body>
<fieldset>
<?php
$item = $_POST['item'];

if (!empty($_POST['quantity'])){
	$quantity = $_POST['quantity'];
} else {
	$quantity = NULL;
	echo '<p><b>You forgot to enter your quantity!</b></p>';
	}

if ($item && $quantity){
	echo "You ordered $quantity $item.</br>
	Thank you for ordering from Beautiful Flower Supplies!";
} else {
	echo '<p><font color="red">Please go back and fill out the form again.</font></p>';
	}
?>
</fieldset>
</body>
</html>