<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="/output.css" rel="stylesheet">
    <title>Air Conditioner Calculator</title>
</head>
<body class="bg-gradient-to-br from-purple-200 to-indigo-200 dark:from-gray-800 dark:to-gray-900 text-gray-900 dark:text-gray-100 min-h-screen transition-colors duration-200">
    <div class="flex min-h-screen">
		<?php include BASE_PATH . '/includes/navigation.php'; ?>

        <div class="flex-1 p-6 sm:ml-64">
            <header class="bg-white dark:bg-gray-800 shadow-lg rounded-xl p-8 mb-8 border border-gray-200 dark:border-gray-700">
                <h1 class="text-5xl font-extrabold bg-gradient-to-r from-purple-600 to-indigo-600 bg-clip-text text-transparent">Air Conditioner Calculator</h1>
                <p class="mt-4 text-gray-600 dark:text-gray-300 text-xl">Calculate the recommended AC capacity for your room</p>
            </header>

			<section class="bg-white dark:bg-gray-800 rounded-xl p-8 shadow-lg hover:shadow-xl transition-shadow duration-300 mb-8 border border-gray-200 dark:border-gray-700">
				<form action="aircond" method="post">
    				<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
						<div class="space-y-2 w-full">
							<label for="length" class="text-lg font-medium text-gray-700 dark:text-gray-300">Room Length (ft)</label>
							<input type="text" name="length" id="length" value="<?php if(isset($_POST['length'])) echo $_POST['length']; ?>"
								class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:outline-none
										focus:ring-2 focus:ring-purple-500 hover:border-purple-500 focus:border-purple-500
										dark:bg-gray-700 dark:text-white transition duration-200" />
						</div>
						<div class="space-y-2 w-full">
							<label for="width" class="text-lg font-medium text-gray-700 dark:text-gray-300">Room Width (ft)</label>
							<input type="text" name="width" id="width" value="<?php if(isset($_POST['width'])) echo $_POST['width']; ?>"
								class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:outline-none
										focus:ring-2 focus:ring-purple-500 hover:border-purple-500 focus:border-purple-500
										dark:bg-gray-700 dark:text-white transition duration-200" />
						</div>
						<div class="space-y-2 w-full">
							<label for="height" class="text-lg font-medium text-gray-700 dark:text-gray-300">Room Height (ft)</label>
							<input type="text" name="height" id="height" value="<?php if(isset($_POST['height'])) echo $_POST['height']; ?>"
								class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:outline-none
										focus:ring-2 focus:ring-purple-500 hover:border-purple-500 focus:border-purple-500
										dark:bg-gray-700 dark:text-white transition duration-200" />
						</div>
						<div class="space-y-2 w-full">
							<label for="people" class="text-lg font-medium text-gray-700 dark:text-gray-300">Number of People</label>
							<input type="text" name="people" id="people" value="<?php if(isset($_POST['people'])) echo $_POST['people']; ?>"
								class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:outline-none
										focus:ring-2 focus:ring-purple-500 hover:border-purple-500 focus:border-purple-500
										dark:bg-gray-700 dark:text-white transition duration-200" />
						</div>
						<div class="space-y-2 w-full">
							<label for="room" class="text-lg font-medium text-gray-700 dark:text-gray-300">Room Type</label>
							<select name="room" id="room" 
									class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:outline-none
										focus:ring-2 focus:ring-purple-500 hover:border-purple-500 focus:border-purple-500
										dark:bg-gray-700 dark:text-white transition duration-200">
								<option value="">Choose a room type</option>
								<option value="bedroom" <?php if(isset($_POST['room']) && $_POST['room'] == "bedroom") echo 'selected="selected"'; ?>>Bedroom</option>
								<option value="kitchen" <?php if(isset($_POST['room']) && $_POST['room'] == "kitchen") echo 'selected="selected"'; ?>>Kitchen</option>
								<option value="living room" <?php if(isset($_POST['room']) && $_POST['room'] == "living room") echo 'selected="selected"'; ?>>Living Room</option>
							</select>
						</div>
						<div class="space-y-2 w-full">
							<label for="location" class="text-lg font-medium text-gray-700 dark:text-gray-300">Room Location</label>
							<select name="location" id="location"
									class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 focus:outline-none
										focus:ring-2 focus:ring-purple-500 hover:border-purple-500 focus:border-purple-500
										dark:bg-gray-700 dark:text-white transition duration-200">
								<option value="">Choose a location</option>
								<option value="sunny" <?php if(isset($_POST['location']) && $_POST['location'] == "sunny") echo 'selected="selected"'; ?>>Facing the sun</option>
								<option value="shaded" <?php if(isset($_POST['location']) && $_POST['location'] == "shaded") echo 'selected="selected"'; ?>>Is shaded</option>
							</select>
						</div>
					</div>
					<button type="submit" name="submit" 
						class="px-6 py-3 bg-gradient-to-r from-purple-600 to-indigo-600 
							text-white font-semibold rounded-lg shadow-md hover:from-purple-700 
							hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-blue-500 
							focus:ring-opacity-75 hover:scale-[1.02] transition-transform duration-300">
						<i class="fas fa-calculator mr-2"></i> Calculate Capacity
					</button>
				</form>
			</section>

			<?php
				// Error messages array
				$errorMessages = array(
					'length' => array(
						'empty' => 'You forgot to enter the room length!',
						'not_numeric' => 'You must enter the room length in numeric only!',
						'too_big' => 'The room length is too big! (%d-%d ft)',
						'too_small' => 'The room length is too small! (%d-%d ft)',
					),
					'width' => array(
						'empty' => 'You forgot to enter the room width!',
						'not_numeric' => 'You must enter the room width in numeric only!',
						'too_big' => 'The room width is too big! (%d-%d ft)',
						'too_small' => 'The room width is too small! (%d-%d ft)',
					),
					'height' => array(
						'empty' => 'You forgot to enter the room height!',
						'not_numeric' => 'You must enter the room height in numeric only!',
						'too_big' => 'The room height is too big! (%d-%d ft)',
						'too_small' => 'The room height is too small! (%d-%d ft)',
					),
					'people' => array(
						'empty' => 'You forgot to enter the number of people in the room!',
						'not_numeric' => 'You must enter the number of people in numeric only!',
						'too_big' => 'The number of people in the room is too many! (%d-%d people)',
						'too_small' => 'This number of people is invalid!',
					),
					'room' => array(
						'empty' => 'You forgot to choose the room type!',
					),
					'location' => array(
						'empty' => 'You forgot to choose the location of the room!',
					),
				);

				// Constants for validation ranges
				define('LENGTH_MIN', 10);
				define('LENGTH_MAX', 50);
				define('WIDTH_MIN', 10);
				define('WIDTH_MAX', 50);
				define('HEIGHT_MIN', 8);
				define('HEIGHT_MAX', 50);
				define('PEOPLE_MIN', 1);
				define('PEOPLE_MAX', 200);

				// Helper functions
				function wrapErrorMessage($message) {
					return '<div class="p-4 bg-red-100 dark:bg-red-900 rounded-lg"><p class="text-red-700 dark:text-red-100 font-bold">'. htmlspecialchars($message) . '</p></div>';
				}

				function getErrorMessage($fieldName, $errorType, $min = null, $max = null) {
					global $errorMessages;
					$message = $errorMessages[$fieldName][$errorType];
					if ($min !== null && $max !== null) {
						$message = sprintf($message, $min, $max);
					}
					return wrapErrorMessage($message);
				}

				function validateNumericInput($value, $fieldName, $min, $max, &$errors) {
					if (!empty($value)) {
						if (is_numeric($value)) {
							$value = floatval($value);
							if ($value > $max) {
								$errors[] = getErrorMessage($fieldName, 'too_big', $min, $max);
								return null;
							} elseif ($value < $min) {
								$errors[] = getErrorMessage($fieldName, 'too_small', $min, $max);
								return null;
							} else {
								return $value;
							}
						} else {
							$errors[] = getErrorMessage($fieldName, 'not_numeric');
							return null;
						}
					} else {
						$errors[] = getErrorMessage($fieldName, 'empty');
						return null;
					}
				}

				function validateSelectInput($value, $fieldName, &$errors) {
					if (isset($value) && $value !== "") {
						return $value;
					} else {
						$errors[] = getErrorMessage($fieldName, 'empty');
						return null;
					}
				}

				function conversionHorsepower($areacapacity) {
					$hp = $areacapacity / 2509.6259059886;
					return number_format($hp, 3);
				}

				function locationAdjustCapacity($cap, $heightcapacity, $peoplecapacity, $roomcapacity, $location) {
					$areaCap = $cap + $heightcapacity + $peoplecapacity + $roomcapacity;
					if ($location == "sunny") {
						return $areaCap + ($areaCap * 0.10);
					} elseif ($location == "shaded") {
						return $areaCap - ($areaCap * 0.10);
					} else {
						return $areaCap; // No adjustment if location is not specified
					}
				}

				function resultMessage($areacapacity, $horsepower) {
					echo '
					<div class="p-4 bg-green-100 dark:bg-green-900 rounded-lg">
						<p class="text-green-700 dark:text-green-100">
							<span class="font-bold">Recommended AC Capacity:</span><br>
							<span class="font-bold">' . htmlspecialchars(number_format($areacapacity, 2)) . '</span> BTU/hr<br>
							<span class="font-bold">' . htmlspecialchars($horsepower) . '</span> horsepower (metric)
						</p>
					</div>';
				}

				// Main code
				if(isset($_REQUEST['submit'])) {
					$errors = array();
					$length = validateNumericInput($_POST['length'], 'length', LENGTH_MIN, LENGTH_MAX, $errors);
					$width = validateNumericInput($_POST['width'], 'width', WIDTH_MIN, WIDTH_MAX, $errors);
					$height = validateNumericInput($_POST['height'], 'height', HEIGHT_MIN, HEIGHT_MAX, $errors);
					$people = validateNumericInput($_POST['people'], 'people', PEOPLE_MIN, PEOPLE_MAX, $errors);
					$room = validateSelectInput($_POST['room'], 'room', $errors);
					$location = validateSelectInput($_POST['location'], 'location', $errors);

					// All inputs are valid
					$area = $length * $width;
					$heightcapacity = 1000 * $height;
					$peoplecapacity = 600 * $people;
					$roomArr = array("bedroom" => 0, "kitchen" => 4000, "living room" => 0);
					$roomcapacity = isset($roomArr[$room]) ? $roomArr[$room] : 0;

					// Define area capacity mapping
					$areaCapacities = array(
						array('max_area' => 150, 'capacity' => 5000),
						array('max_area' => 250, 'capacity' => 6000),
						array('max_area' => 300, 'capacity' => 7000),
						array('max_area' => 350, 'capacity' => 8000),
						array('max_area' => 400, 'capacity' => 9000),
						array('max_area' => 450, 'capacity' => 10000),
						array('max_area' => 550, 'capacity' => 12000),
						array('max_area' => 700, 'capacity' => 14000),
						array('max_area' => 1000, 'capacity' => 18000),
						array('max_area' => 1200, 'capacity' => 21000),
						array('max_area' => 1400, 'capacity' => 23000),
						array('max_area' => 1500, 'capacity' => 24000),
						array('max_area' => 2000, 'capacity' => 30000),
						array('max_area' => 2500, 'capacity' => 34000),
					);

					// Find capacity based on area
					$baseCapacity = null;
					foreach ($areaCapacities as $areaCapacity) {
						if ($area <= $areaCapacity['max_area']) {
							$baseCapacity = $areaCapacity['capacity'];
							break;
						}
					}

					if ($baseCapacity === null) {
						// Area is too large, could output an error or set to maximum capacity
						$baseCapacity = end($areaCapacities)['capacity'];
					}

					$areacapacity = locationAdjustCapacity($baseCapacity, $heightcapacity, $peoplecapacity, $roomcapacity, $location);
					$horsepower = conversionHorsepower($areacapacity);					
				}
			?>
			<!-- Results section -->
			<?php if(isset($_REQUEST['submit'])): ?>
				<section class="bg-white dark:bg-gray-800 rounded-xl p-8 shadow-lg hover:shadow-xl transition-shadow duration-300 mb-8 border border-gray-200 dark:border-gray-700">
				<div class="space-y-4">
					<?php
					if (!empty($errors)) {
						// Output errors
						foreach ($errors as $error) {
							echo $error;
						}
					} else {
						// Output the recommended AC capacity
						resultMessage($areacapacity, $horsepower);
					}
					?>
				</div>
				</section>
			<?php endif; ?>
        </div>
    </div>
    <script src="<?= $baseUrl ?>public/js/darkMode.js" defer></script>
    <script src="<?= $baseUrl ?>public/js/mobileNav.js" defer></script>
	<script src="<?= $baseUrl ?>public/js/all.min.js"></script>
	<script src="<?= $baseUrl ?>public/js/accordion.js" defer></script>
</body>
</html>
