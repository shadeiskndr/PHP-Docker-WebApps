<html>
<head>
</head>
<?php
$page_title = 'Air Conditioner Calculator';
include('includes/header.html');
?>
<body>
<form action="airform.php" method="post">
<h1>Air Conditioner Calculator</h1>
<h3>Please fill in the forms below:</h3>
<label for="length"><p>Room length (ft):</label>
	<input type="text" name="length" size="10" maxlength="10" value="<?php if(isset($_POST['length'])) echo $_POST['length']; ?>"/></p>
<label for="width"><p>Room width (ft):</label>
	<input type="text" name="width" size="10" maxlength="10" value="<?php if(isset($_POST['width'])) echo $_POST['width']; ?>" /></p>
<label for="height"><p>Room height (ft):</label>
	<input type="text" name="height" size="10" maxlength="10" value="<?php if(isset($_POST['height'])) echo $_POST['height']; ?>" /></p>
<label for="people"><p>Number of people in the room:</label>
	<input type="text" name="people" size="10" maxlength="10" value="<?php if(isset($_POST['people'])) echo $_POST['people']; ?>" /></p>
<label for="room"><p>Room type:</label>
<select id ="room" name="room">
	<option value="">Choose a room type</option>
	<option value="bedroom" <?php if(isset($_POST['room']) && $_POST['room'] == "bedroom") echo 'selected="selected"'; ?> >Bedroom</option>
	<option value="kitchen" <?php if(isset($_POST['room']) && $_POST['room'] == "kitchen") echo 'selected="selected"'; ?> >Kitchen</option>
	<option value="living room" <?php if(isset($_POST['room']) && $_POST['room']=="living room") echo 'selected="selected"'; ?> >Living room</option>
</select></p>
<label for="location"><p>Location of the room:</label>
<select id ="location" name="location">
	<option value="">Choose a location</option>
	<option value="sunny" <?php if(isset($_POST['location']) && $_POST['location']=="sunny") echo 'selected="selected"'; ?> >Facing the sun</option>
	<option value="shaded" <?php if(isset($_POST['location']) && $_POST['location']=="shaded") echo 'selected="selected"'; ?> >Is shaded</option>
</select></p>
<p><div align="left"><input type="submit" name="submit" value="Submit" /></div></p>
<fieldset>
<?php
//Initialize error message array
$errorMessage = array();

if(isset($_REQUEST['submit'])){

	//Validate room length
	if (!empty($_POST['length'])) {
		if (is_numeric($_POST['length'])) {
			if ($_POST['length'] < 1){
				$length = NULL;
				$errorMessage[] = '<p>The room length value is invalid!</p>';
			} else {
				$length = $_POST['length'];
			}
		} else {
			$length = NULL;
			$errorMessage[] = '<p>You must enter the room length in numeric only!</p>';
		}
	} else {
		$length = NULL;
		$errorMessage[] = '<p>You forgot to enter the room length!</p>';
	}

	//Validate room width
	if (!empty($_POST['width'])) {
		if (is_numeric($_POST['width'])) {
			if ($_POST['width'] < 1){
				$width = NULL;
				$errorMessage[] = '<p>The room width value is invalid!</p>';
			} else {
				$width = $_POST['width'];
			}
		} else {
			$width = NULL;
			$errorMessage[] = '<p>You must enter the room width in numeric only!</p>';
		}
	} else {
		$width = NULL;
		$errorMessage[] = '<p>You forgot to enter the room width!</p>';
	}

	//Validate room height
	if (!empty($_POST['height'])) {
		if (is_numeric($_POST['height'])) {
			if ($_POST['height'] < 1){
				$height = NULL;
				$errorMessage[] = '<p>The room height value is invalid!</p>';
			} else {
				$height = $_POST['height'];
			}
		} else {
			$height = NULL;
			$errorMessage[] = '<p>You must enter the room height in numeric only!</p>';
		}
	} else {
		$height = NULL;
		$errorMessage[] = '<p>You forgot to enter the room height!</p>';
	}

	//Validate number of people
	if (!empty($_POST['people'])) {
		if (is_numeric($_POST['people'])) {
			if ($_POST['people'] > 200){
				$people = NULL;
				$errorMessage[] = '<p>The number of people in the room is too many! (1-200 people)</p>';
			} else if ($_POST['people'] < 1){
				$people = NULL;
				$errorMessage[] = '<p>The number of people is invalid!</p>';
			} else {
				$people = $_POST['people'];
			}
		} else {
			$people = NULL;
			$errorMessage[] = '<p>You must enter the number of people in numeric only!</p>';
			}
	} else {
		$people = NULL;
		$errorMessage[] = '<p>You forgot to enter the number of people in the room!</p>';
	}

	//Validate room type
	if(isset($_POST['room'])&&$_POST['room'] == ""){
		$room = NULL;
		$errorMessage[] = '<p>You forgot to choose the room type!</p>';
	}else{
		$room = $_POST['room'];
	}
	
	//Validate location of the room
	if(isset($_POST['location'])&&$_POST['location'] == ""){
		$location = NULL;
		$errorMessage[] = '<p>You forgot to choose the location of the room!</p>';
	}else {
		$location = $_POST['location'];
	}

	
	$area = $length * $width; //Calculate the room area

	//Validate the room area
	if ($area < 100){
		$area = NULL;
		$errorMessage[] = '<p>The room area is too small! Recommendation is for room with area of 100 to 2500 ft squared only.</p>';
	}else if ($area > 2500){
		$area = NULL;
		$errorMessage[] = '<p>The room area is too big! Recommendation is for room with area of 100 to 2500 ft squared only.</p>';
	}

	//If there are no errors stored, start calculation
	if (empty($errorMessage)){
		
		//Room height aircond capacity value calculation
		$heightcapacity = 1000 * $height;

		//Number of people in a room aircond capacity value calculation
		$peoplecapacity = 600 * $people;

		//Type of room aircond capacity value calculation
		$roomArr = array("bedroom" => 0, "kitchen" => 4000, "living room" => 0);
		$roomcapacity = $roomArr[$room];

		//getArea function to return aircond capacity based on room area
		function getArea($area){
			if ($area <= 150) return 5000;
			if ($area <= 250) return 6000;
			if ($area <= 300) return 7000;
			if ($area <= 350) return 8000;
			if ($area <= 400) return 9000;
			if ($area <= 450) return 10000;
			if ($area <= 550) return 12000;
			if ($area <= 700) return 14000;
			if ($area <= 1000) return 18000;
			if ($area <= 1200) return 21000;
			if ($area <= 1400) return 23000;
			if ($area <= 1500) return 24000;
			if ($area <= 2000) return 30000;
			if ($area <= 2500) return 34000;
		}

		//function to increase total aircond capacity by 10%
		function locationIncreaseCapacity($cap, $heightcapacity, $peoplecapacity, $roomcapacity){
			$areaCap = $cap + $heightcapacity + $peoplecapacity + $roomcapacity;
			return $areaCap = $areaCap + ($areaCap * 10/100);
		}

		//function to decrease total aircond capacity by 10%
		function locationDecreaseCapacity($cap, $heightcapacity, $peoplecapacity, $roomcapacity){
			$areaCap = $cap + $heightcapacity + $peoplecapacity + $roomcapacity;
			return $areaCap = $areaCap - ($areaCap * 10/100);
		}

		//function to convert BTU to horsepower and display final message
		function conversionHorsepower($areacapacity){
			$hp = $areacapacity / 2509.6259059886;
			$hp = number_format($hp, 3);
			echo'
			<p><b>The recommended air conditioner capacity for your room is:</br></b></p>
			<b><font color="green">' . $areacapacity . '</font></b> BTU/hr, or</br>
			<b><font color="green">' . $hp . '</font></b> horsepower (metric).
			';
		}

		//Decide which case to run based on location of the room
		switch ($location){
		case "sunny":
		$cap = getArea($area);
		$areacapacity = locationIncreaseCapacity($cap, $heightcapacity, $peoplecapacity, $roomcapacity);
		echo conversionHorsepower($areacapacity);
		break;

		case "shaded":
		$cap = getArea($area);
		$areacapacity = locationDecreaseCapacity($cap, $heightcapacity, $peoplecapacity, $roomcapacity);
		echo conversionHorsepower($areacapacity);
		break;
		}
	
		
	}else { // One form element was not filled out properly.
		echo '<h3><font color="red">ERROR!</font></h3>';
		foreach ($errorMessage as $msg){
			echo '<p><font color="red">' . $msg . '</font></p>';
		}
		echo '<h3><font color="red">Please go back and fill out the form again.</font><h3>';
	}
}
?>
</fieldset>
</form>
<?php
include('includes/footer.html');
?>
</body>
</html>