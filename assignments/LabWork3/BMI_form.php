<html>
<head>
<title>BMI Calculator</title>
</head>

<?php
include('./includes/header.html');
?>

<body>
<form action="BMI_result.php" method="post">
<h1>BMI Calculator</h1>
<fieldset>
<legend>Enter your information in the form below:</legend>
<p><b>Name:</b> <input type="text" name="name" size="20" maxlength="40" /></p>
<p><b>Height (m):</b> <input type="text" name="height" size="20" maxlength="40" /></p>
<p><b>Weight (kg):</b> <input type="text" name="weight" size="20" maxlength="40" /></p>
</fieldset>
<div align="left"><input type="submit" name="submit" value="Calculate" /></div>
</form>
</body>
</html>