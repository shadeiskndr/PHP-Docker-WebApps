<html>
<head>
<title>BMI Calculator</title>
</head>

<?php
include('./includes/header.html');
?>

<body>
<form action="BMI_form_sticky.php" method="post">
<h1>BMI Calculator</h1>
<fieldset>
<legend>Enter your information in the form below:</legend>
<p><b>Name:</b> <input type="text" name="name" size="20" maxlength="40" value="<?php if(isset($_POST['name'])) echo $_POST['name']; ?>"/></p>
<p><b>Height (m):</b> <input type="text" name="height" size="20" maxlength="40" value="<?php if(isset($_POST['height'])) echo $_POST['height']; ?>" /></p>
<p><b>Weight (kg):</b> <input type="text" name="weight" size="20" maxlength="40" value="<?php if(isset($_POST['weight'])) echo $_POST['weight']; ?>" /></p>
</fieldset>
<div align="left"><input type="submit" name="submit" value="Calculate" /></div>
</form>
<?php
if(isset($_REQUEST['submit'])){
	// Validate name
		if (!empty($_POST['name'])) {
			if (!is_numeric($_POST['name'])) {
				$name = $_POST['name'];
				}
				else {
					$name = NULL;
					echo '<p><b>You must enter your name in text only!</b></p>';
					}
		} else {
		$name = NULL;
		echo '<p><b>You forgot to enter your name!</b></p>';
		}
		
		// Validate height
		if (!empty($_POST['height'])) {
			if (is_numeric($_POST['height'])) {
				$height = $_POST['height'];
				}
				else {
					$height = NULL;
					echo '<p><b>You must enter your height in numeric only!</b></p>';
					}
		} else {
		$height = NULL;
		echo '<p><b>You forgot to enter your height!</b></p>';
		}
		
		// Validate weight
		if (!empty($_POST['weight'])) {
			if (is_numeric($_POST['weight'])) {
				$weight = $_POST['weight'];
				}
				else {
					$weight = NULL;
					echo '<p><b>You must enter your weight in numeric only!</b></p>';
					}
		} else {
		$weight = NULL;
		echo '<p><b>You forgot to enter your weight!</b></p>';
		}
		
		// If everything is okay, print the message.
		if ($name && $height && $weight) {
		// Calculate the BMI.
		$bmi = $weight / ($height * $height);
		$bmi = number_format($bmi, 2);
		// Print the results.
		echo "
			Hi $name!<br/>
			Your Body Mass Index (BMI) is $bmi<br/>
			";
			
			switch(true){
			case $bmi < 18.5:
				echo "UNDERWEIGHT";
				break;
			case $bmi <= 24.9:
				echo "NORMAL";
				break;
			case $bmi <= 29.9:
				echo "OVERWEIGHT";
				break;
			case $bmi <= 30.0:
				echo "OBESE";
				break;
				}
			
			
			/*if ($bmi < 18.5){
				echo "UNDERWEIGHT";
				}
			else if ($bmi <= 24.9){
				echo "NORMAL";
				}
			else if ($bmi <= 29.9){
				echo "OVERWEIGHT";
				}
			else if ($bmi >= 30.0) {
				echo "OBESE";
				}**/
			
			
		} else { // One form element was not filled out properly.
		echo '<p><font color="red">Please go back and fill out the form again.</font></p>';
		}	
}
?>
</body>
</html>