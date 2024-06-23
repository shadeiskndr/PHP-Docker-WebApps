<html>
<head>
<title>Tax Result</title>
</head>
<body>
<?php
// Validate quantity
if (!empty($_POST['quantity'])) {
$quantity = $_POST['quantity'];
} else {
$quantity = NULL;
echo '<p><b>You forgot to enter your quantity!</b></p>';
}

// Validate price
if (!empty($_POST['price'])) {
$price = $_POST['price'];
} else {
$price = NULL;
echo '<p><b>You forgot to enter your price!</b></p>';
}

// Validate tax
if (!empty($_POST['tax'])) {
$tax = $_POST['tax'];
} else {
$tax = NULL;
echo '<p><b>You forgot to enter your tax!</b></p>';
}

// If everything is okay, print the message.
if ($quantity && $price && $tax) {
// Calculate the total.
$total = $quantity * $price;
$total = $total + ($total * ($tax/100)); // Calculate and add the tax.
$total = number_format($total, 2);
// Print the results.
echo 'You are purchasing <b>' . $quantity . '</b> widget(s) at a cost of <b>RM' . $price
. '</b> each. With tax, the total comes to <b>RM' . $total . '</b>.';
} else { // One form element was not filled out properly.
echo '<p><font color="red">Please go back and fill out the form again.</font></p>';
}

?>
</body>
</html>