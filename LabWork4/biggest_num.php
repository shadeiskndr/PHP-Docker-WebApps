<html>
<head>
<title>What number's bigger?</title>
</head>
<body>
<form action="biggest_num.php" method="post">
<h1>What number is bigger?</h1>
<fieldset><legend><b>Enter your numbers:</b></legend>
<p><b>First Number:</b> <input type="text" name="first" size="20" maxlength="40" value="<?php if(isset($_POST['first'])) echo $_POST['first']; ?>"/></p>
<p><b>Second Number:</b> <input type="text" name="second" size="20" maxlength="40" value="<?php if(isset($_POST['second'])) echo $_POST['second']; ?>" /></p>
</fieldset>
<div align="left"><input type="submit" name="submit" value="Check!" /></div>
</form>

<?php
if(isset($_REQUEST['submit'])){
			$errors = array();
			// Validate first number
			if (!empty($_POST['first'])) {
				if (is_numeric($_POST['first'])) {
					$first = $_POST['first'];
					}
					else {
						$first = NULL;
						$errors[] = '<p><b>You must enter your first number in numeric only!</b></p>';
						}
			} else {
			$first = NULL;
			$errors[] = '<p><b>You forgot to enter your first number!</b></p>';
			}
	
				// Validate second number
				if (!empty($_POST['second'])) {
					if (is_numeric($_POST['second'])) {
						$second = $_POST['second'];
						}
						else {
							$second = NULL;
							$errors[] = '<p><b>You must enter your second number in numeric only!</b></p>';
							}
				} else {
				$second = NULL;
				$errors[] = '<p><b>You forgot to enter your second number!</b></p>';
				}

			//If both numbers are entered, do a comparison
			if (empty($errors)){
				switch(true){
					case $first > $second:
					echo "<b>$first is bigger than $second.</b>";
					break;
					case $first < $second:
					echo "<b>$second is bigger than $first.</b>";
					break;
					case $first = $second:
					echo "<b>Both numbers are equal!</b>";
					break;
				}

			}else { // One form element was not filled out properly.
				echo '<h3><font color="red">ERROR!</font></h3>';
				foreach ($errors as $msg){
					echo '<p><font color="red">-' . $msg . '</font></p>';
				}
				echo '<p><font color="red"><p>-</p>Please go back and fill out the form again.</font></p>';
				}
}
?>
</body>
</html>