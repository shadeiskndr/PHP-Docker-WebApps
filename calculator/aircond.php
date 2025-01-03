<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../output.css" rel="stylesheet">
    <title>Air Conditioner Calculator</title>
</head>
<body class="bg-gray-100 text-gray-900">
    <div class="flex">
        <!-- Vertical Navigation Bar -->
        <nav class="w-64 bg-gray-800 text-white min-h-screen p-4">
            <h2 class="text-2xl font-bold mb-6">Navigation</h2>
            <ul class="space-y-4">
				<li><a href="../index.php" class="block py-2 px-4 rounded hover:bg-blue-700">Home</a></li>
                <li><a href="../quiz1/puteriClothes.php" class="block py-2 px-4 rounded hover:bg-blue-700">Puteri Clothes Calculator</a></li>
                <li><a href="airform2.php" class="block py-2 px-4 rounded hover:bg-blue-700">Air Conditioner Calculator</a></li>
                <li><a href="../LabWorkX8/Discount.php" class="block py-2 px-4 rounded hover:bg-blue-700">Discount Calculator</a></li>
                <li><a href="../LabWork3/speed_converter.php" class="block py-2 px-4 rounded hover:bg-blue-700">Speed Converter</a></li>
                <li><a href="../LabWork3/tax_form.php" class="block py-2 px-4 rounded hover:bg-blue-700">Tax Calculator</a></li>
                <li><a href="../LabWork3/BMI_form_sticky.php" class="block py-2 px-4 rounded hover:bg-blue-700">BMI Calculator</a></li>
                <li><a href="../LabWork4/biggest_num.php" class="block py-2 px-4 rounded hover:bg-blue-700">Biggest Number</a></li>
                <li><a href="../LabWork1/integers.php" class="block py-2 px-4 rounded hover:bg-blue-700">Add 3 Integers</a></li>
                <li><a href="../updateInventory/updateInventory.php" class="block py-2 px-4 rounded hover:bg-blue-700">Mawar Boutique Inventory</a></li>
                <li><a href="../CinemaTicketing/admin_listMovie.php" class="block py-2 px-4 rounded hover:bg-blue-700">Movies I Watched</a></li>
                <li><a href="../CarForSale/view_carList.php" class="block py-2 px-4 rounded hover:bg-blue-700">Cars Database</a></li>
                <li><a href="../VehicleRentalProject/homepage.php" class="block py-2 px-4 rounded hover:bg-blue-700">Vehicle Rental Project</a></li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="flex-1 p-4">
            <header class="bg-blue-600 text-white p-4 rounded mb-4">
                <h1 class="text-4xl font-bold">Air Conditioner Calculator</h1>
            </header>

            <form action="airform2.php" method="post" class="bg-white p-6 rounded shadow-md">
                <fieldset>
                    <legend class="text-xl font-semibold mb-4">Please fill in the forms below:</legend>
                    <div class="mb-4">
                        <label for="length" class="block text-sm font-medium text-gray-700">Room length (ft):</label>
                        <input type="text" name="length" size="20" maxlength="40" value="<?php if(isset($_POST['length'])) echo $_POST['length']; ?>" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"/>
                    </div>
                    <div class="mb-4">
                        <label for="width" class="block text-sm font-medium text-gray-700">Room width (ft):</label>
                        <input type="text" name="width" size="20" maxlength="40" value="<?php if(isset($_POST['width'])) echo $_POST['width']; ?>" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"/>
                    </div>
                    <div class="mb-4">
                        <label for="height" class="block text-sm font-medium text-gray-700">Room height (ft):</label>
                        <input type="text" name="height" size="20" maxlength="40" value="<?php if(isset($_POST['height'])) echo $_POST['height']; ?>" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"/>
                    </div>
                    <div class="mb-4">
                        <label for="people" class="block text-sm font-medium text-gray-700">Number of people in the room:</label>
                        <input type="text" name="people" size="20" maxlength="40" value="<?php if(isset($_POST['people'])) echo $_POST['people']; ?>" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"/>
                    </div>
                    <div class="mb-4">
                        <label for="room" class="block text-sm font-medium text-gray-700">Room type:</label>
                        <select id="room" name="room" class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">Choose a room type</option>
                            <option value="bedroom" <?php if(isset($_POST['room']) && $_POST['room'] == "bedroom") echo 'selected="selected"'; ?>>Bedroom</option>
                            <option value="kitchen" <?php if(isset($_POST['room']) && $_POST['room'] == "kitchen") echo 'selected="selected"'; ?>>Kitchen</option>
                            <option value="living room" <?php if(isset($_POST['room']) && $_POST['room']=="living room") echo 'selected="selected"'; ?>>Living room</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label for="location" class="block text-sm font-medium text-gray-700">Location of the room:</label>
                        <select id="location" name="location" class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            <option value="">Choose a location</option>
                            <option value="sunny" <?php if(isset($_POST['location']) && $_POST['location']=="sunny") echo 'selected="selected"'; ?>>Facing the sun</option>
                            <option value="shaded" <?php if(isset($_POST['location']) && $_POST['location']=="shaded") echo 'selected="selected"'; ?>>Is shaded</option>
                        </select>
                    </div>
                </fieldset>
                <div class="mt-6">
                    <button type="submit" name="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">Submit</button>
                </div>
            </form>

            <fieldset class="mt-6">
			<?php

$errorMessage = array(
					array(
					'<p><font color="red">You forgot to enter the room length!</font></p>',
					'<p><font color="red">You must enter the room length in numeric only!</font></p>',
					'<p><font color="red">The room length is too big! (10-50 ft)</font></p>',
					'<p><font color="red">The room length is too small! (10-50 ft)</font></p>'
					),
					array(
					'<p><font color="red">You forgot to enter the room width!</font></p>',
					'<p><font color="red">You must enter the room width in numeric only!</font></p>',
					'<p><font color="red">The room width is too big! (10-50 ft)</font></p>',
					'<p><font color="red">The room width is too small! (10-50 ft)</font></p>'
					),
					array(
					'<p><font color="red">You forgot to enter the room height!</font></p>',
					'<p><font color="red">You must enter the room height in numeric only!</font></p>',
					'<p><font color="red">The room height is too big! (8-50 ft)</font></p>',
					'<p><font color="red">The room height is too small! (8-50 ft)</font></p>'
					),
					array(
					'<p><font color="red">You forgot to enter the number of people in the room!</font></p>',
					'<p><font color="red">You must enter the room height in numeric only!</font></p>',
					'<p><font color="red">The number of people in the room is too many! (1-200 people)</font></p>',
					'<p><font color="red">This number of people is invalid!</font></p>'
					),
					array(
					'<p><font color="red">You forgot to choose the room type!</font></p>',
					'<p><font color="red">You forgot to choose the location of the room!</font></p>',
					)
				);

if(isset($_REQUEST['submit'])){
	//Validate room length
	if (!empty($_POST['length'])) {
		if (is_numeric($_POST['length'])) {
			if ($_POST['length'] > 50){
			$length = NULL;
			echo $errorMessage[0][2];
			} else if ($_POST['length'] < 10){
			$length = NULL;
			echo $errorMessage[0][3];
			} else {
			$length = $_POST['length'];
			}
		} else {
			$length = NULL;
			echo $errorMessage[0][1];
			}
	} else {
		$length = NULL;
		echo $errorMessage[0][0];
		}

	//Validate room width
	if (!empty($_POST['width'])) {
		if (is_numeric($_POST['width'])) {
			if ($_POST['width'] > 50){
			$width = NULL;
			echo errorMessage[1][2];
			} else if ($_POST['width'] < 10){
			$width = NULL;
			echo $errorMessage[1][3];
			} else {
			$width = $_POST['width'];
			}
		} else {
			$width = NULL;
			echo $errorMessage[1][1];
			}
	} else {
		$width = NULL;
		echo $errorMessage[1][0];
		}

	//Validate room height
	if (!empty($_POST['height'])) {
		if (is_numeric($_POST['height'])) {
			if ($_POST['height'] > 50){
			$height = NULL;
			echo $errorMessage[2][2];
			} else if ($_POST['height'] < 8){
			$height = NULL;
			echo $errorMessage[2][3];
			} else {
			$height = $_POST['height'];
			}
		} else {
			$height = NULL;
			echo $errorMessage[2][1];
			}
	} else {
		$height = NULL;
		echo $errorMessage[2][0];
		}

	//Validate number of people
	if (!empty($_POST['people'])) {
		if (is_numeric($_POST['people'])) {
			if ($_POST['people'] > 200){
			$people = NULL;
			echo $errorMessage[3][2];
			} else if ($_POST['people'] < 1){
			$people = NULL;
			echo $errorMessage[3][3];
			} else {
			$people = $_POST['people'];
			}
		} else {
			$people = NULL;
			echo $errorMessage[3][1];
			}
	} else {
		$people = NULL;
		echo $errorMessage[3][0];
		}

	if(isset($_POST['room'])&&$_POST['room'] == ""){
		$room = NULL;
		echo $errorMessage[4][0];
	}else{
		$room = $_POST['room'];
	}
	
	if(isset($_POST['location'])&&$_POST['location'] == ""){
		$location = NULL;
		echo $errorMessage[4][1];
	}else {
		$location = $_POST['location'];
	}

	if ($length && $width && $height && $people && $room && $location){
		
		//Calculate room area
		$area = $length * $width;
		$heightcapacity = 1000 * $height;
		$peoplecapacity = 600 * $people;
		$roomArr = array("bedroom" => 0, "kitchen" => 4000, "living room" => 0);
		$roomcapacity = $roomArr[$room];

		function conversionHorsepower($areacapacity){
		$hp = $areacapacity / 2509.6259059886;
		$hp = number_format($hp, 3);
		return $hp;
		}

		function resultMessage($areacapacity, $horsepower){
		echo'
		<p><b>The recommended air conditioner capacity for your room is:</br></b></p>
		<b><font color="green">' . $areacapacity . '</font></b> BTU/hr, or</br>
		<b><font color="green">' . $horsepower . '</font></b> horsepower (metric).
		';
		return;
		}

		function locationIncreaseCapacity($cap, $heightcapacity, $peoplecapacity, $roomcapacity){
			$areaCap = $cap + $heightcapacity + $peoplecapacity + $roomcapacity;
			$areaCap = $areaCap + ($areaCap * 10/100);
			return $areaCap;
		}

		function locationDecreaseCapacity($cap, $heightcapacity, $peoplecapacity, $roomcapacity){
			$areaCap = $cap + $heightcapacity + $peoplecapacity + $roomcapacity;
			$areaCap = $areaCap - ($areaCap * 10/100);
			return $areaCap;
		}

		switch (true){
			case $area <= 150:
			if ($location == "sunny"){
				$areacapacity = locationIncreaseCapacity(5000, $heightcapacity, $peoplecapacity, $roomcapacity);
			}else if ($location == "shaded"){
				$areacapacity = locationDecreaseCapacity(5000, $heightcapacity, $peoplecapacity, $roomcapacity);
			}
			$horsepower = conversionHorsepower($areacapacity);
			resultMessage($areacapacity, $horsepower);
			break;

			case $area <= 250:
			if ($location == "sunny"){
				$areacapacity = locationIncreaseCapacity(6000, $heightcapacity, $peoplecapacity, $roomcapacity);
			}else if ($location == "shaded"){
				$areacapacity = locationDecreaseCapacity(6000, $heightcapacity, $peoplecapacity, $roomcapacity);
			}
			$horsepower = conversionHorsepower($areacapacity);
			resultMessage($areacapacity, $horsepower);
			break;

			case $area <= 300:
			if ($location == "sunny"){
				$areacapacity = locationIncreaseCapacity(7000, $heightcapacity, $peoplecapacity, $roomcapacity);
			}else if ($location == "shaded"){
				$areacapacity = locationDecreaseCapacity(7000, $heightcapacity, $peoplecapacity, $roomcapacity);
			}
			$horsepower = conversionHorsepower($areacapacity);
			resultMessage($areacapacity, $horsepower);
			break;

			case $area <= 350:
			if ($location == "sunny"){
				$areacapacity = locationIncreaseCapacity(8000, $heightcapacity, $peoplecapacity, $roomcapacity);
			}else if ($location == "shaded"){
				$areacapacity = locationDecreaseCapacity(8000, $heightcapacity, $peoplecapacity, $roomcapacity);
			}
			$horsepower = conversionHorsepower($areacapacity);
			resultMessage($areacapacity, $horsepower);
			break;

			case $area <= 400:
			if ($location == "sunny"){
				$areacapacity = locationIncreaseCapacity(9000, $heightcapacity, $peoplecapacity, $roomcapacity);
			}else if ($location == "shaded"){
				$areacapacity = locationDecreaseCapacity(9000, $heightcapacity, $peoplecapacity, $roomcapacity);
			}
			$horsepower = conversionHorsepower($areacapacity);
			resultMessage($areacapacity, $horsepower);
			break;

			case $area <= 450:
			if ($location == "sunny"){
				$areacapacity = locationIncreaseCapacity(10000, $heightcapacity, $peoplecapacity, $roomcapacity);
			}else if ($location == "shaded"){
				$areacapacity = locationDecreaseCapacity(10000, $heightcapacity, $peoplecapacity, $roomcapacity);
			}
			$horsepower = conversionHorsepower($areacapacity);
			resultMessage($areacapacity, $horsepower);
			break;

			case $area <= 550:
			if ($location == "sunny"){
				$areacapacity = locationIncreaseCapacity(12000, $heightcapacity, $peoplecapacity, $roomcapacity);
			}else if ($location == "shaded"){
				$areacapacity = locationDecreaseCapacity(12000, $heightcapacity, $peoplecapacity, $roomcapacity);
			}
			$horsepower = conversionHorsepower($areacapacity);
			resultMessage($areacapacity, $horsepower);
			break;

			case $area <= 700:
			if ($location == "sunny"){
				$areacapacity = locationIncreaseCapacity(14000, $heightcapacity, $peoplecapacity, $roomcapacity);
			}else if ($location == "shaded"){
				$areacapacity = locationDecreaseCapacity(14000, $heightcapacity, $peoplecapacity, $roomcapacity);
			}
			$horsepower = conversionHorsepower($areacapacity);
			resultMessage($areacapacity, $horsepower);
			break;

			case $area <= 1000:
			if ($location == "sunny"){
				$areacapacity = locationIncreaseCapacity(18000, $heightcapacity, $peoplecapacity, $roomcapacity);
			}else if ($location == "shaded"){
				$areacapacity = locationDecreaseCapacity(18000, $heightcapacity, $peoplecapacity, $roomcapacity);
			}
			$horsepower = conversionHorsepower($areacapacity);
			resultMessage($areacapacity, $horsepower);
			break;

			case $area <= 1200:
			if ($location == "sunny"){
				$areacapacity = locationIncreaseCapacity(21000, $heightcapacity, $peoplecapacity, $roomcapacity);
			}else if ($location == "shaded"){
				$areacapacity = locationDecreaseCapacity(21000, $heightcapacity, $peoplecapacity, $roomcapacity);
			}
			$horsepower = conversionHorsepower($areacapacity);
			resultMessage($areacapacity, $horsepower);
			break;

			case $area <= 1400:
			if ($location == "sunny"){
				$areacapacity = locationIncreaseCapacity(23000, $heightcapacity, $peoplecapacity, $roomcapacity);
			}else if ($location == "shaded"){
				$areacapacity = locationDecreaseCapacity(23000, $heightcapacity, $peoplecapacity, $roomcapacity);
			}
			$horsepower = conversionHorsepower($areacapacity);
			resultMessage($areacapacity, $horsepower);
			break;

			case $area <= 1500:
			if ($location == "sunny"){
				$areacapacity = locationIncreaseCapacity(24000, $heightcapacity, $peoplecapacity, $roomcapacity);
			}else if ($location == "shaded"){
				$areacapacity = locationDecreaseCapacity(24000, $heightcapacity, $peoplecapacity, $roomcapacity);
			}
			$horsepower = conversionHorsepower($areacapacity);
			resultMessage($areacapacity, $horsepower);
			break;

			case $area <= 2000:
			if ($location == "sunny"){
				$areacapacity = locationIncreaseCapacity(30000, $heightcapacity, $peoplecapacity, $roomcapacity);
			}else if ($location == "shaded"){
				$areacapacity = locationDecreaseCapacity(30000, $heightcapacity, $peoplecapacity, $roomcapacity);
			}
			$horsepower = conversionHorsepower($areacapacity);
			resultMessage($areacapacity, $horsepower);
			break;

			case $area <= 2500:
			if ($location == "sunny"){
				$areacapacity = locationIncreaseCapacity(34000, $heightcapacity, $peoplecapacity, $roomcapacity);
			}else if ($location == "shaded"){
				$areacapacity = locationDecreaseCapacity(34000, $heightcapacity, $peoplecapacity, $roomcapacity);
			}
			$horsepower = conversionHorsepower($areacapacity);
			resultMessage($areacapacity, $horsepower);
			break;
		}
		
	}else { // One form element was not filled out properly.
		echo '<p><font color="red">Please go back and fill out the form again.</font></p>';
		}
}
?>
            </fieldset>
        </div>
    </div>
</body>
</html>
