<html>
<head>
 	<title>Integers Addition</title>
</head> 
<body>
<?php

// Set the variables.
$integer1 = 12; // First integer is 12
$integer2 = 8.5; // Second integer is 8.5
$integer3 = 10; // Third integer is 10

// Calculate the total.
$total = $integer1 + $integer2 + $integer3;
$total = number_format($total, 2);

// Print the results.
echo 'The first integer <b>' . $integer1 . '</b> is added to the second integer <b>' . $integer2 . '</b> and added to the third integer <b>' . $integer3 . '</b>.';
echo '<br /> The total of those three integers is <b>' . $total . '</b>.';

?>
</body>
</html>