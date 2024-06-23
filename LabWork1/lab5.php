<html>
<head>
 	<title>Numbers</title>
</head> 
<body>
<?php

// Set the variables.
$quantity = 30; // Buying 30 widgets.
$price = 119.95;
$taxrate = .05; // 5% sales tax.
 
// Calculate the total.
$total = $quantity * $price;
$total = $total + ($total * $taxrate); // Calculate and add the tax. $total = number_format ($total, 2);

// Print the results.
echo 'You are purchasing <b>' . $quantity . '</b> widget(s) at a cost of <b>$' . $price . '</b> each. With tax, the total comes to <b>$' . $total . '</b>.';

?>
</body>
</html>