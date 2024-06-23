<html>
<head>
<title>Conversion Result</title>
</head>
<body>

<fieldset>
<?php
// Validate speed
if (empty($_POST['speed'])) {
$speed = NULL;
} else {
$speed = $_POST['speed'];
}

// If everything is okay, print the message.
if ($speed) {
// Convert the speed.
$converted_speed = $speed * 1.609;
$converted_speed = number_format($converted_speed, 3);
// Print the results.
echo '<h1><b>Conversion Result<b></h1>
<p><b>' . $speed . '</b> Miles per hour is equals to <b>' . $converted_speed
. '</b> Kilometer per hour.</p>';
} else{
// Form element was not filled out properly.
echo '<h1><b>Conversion Result<b></h1>
<p><b><font color="red">Error! Please enter a valid value.</font></b></p>';
}
?>
</fieldset>

</body>
</html>