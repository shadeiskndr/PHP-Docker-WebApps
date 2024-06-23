<html>
<head>
<title>Discount Code</title>
</head>
<body>
<form action="Discount.php" method="post">
<h1>Discount Code</h1>
<fieldset><legend><b>Please fill in the forms below:</b></legend>
<p><b>Pricing of an Article:</b> <input type="text" name="articlePrice" size="20" maxlength="40" value="<?php if(isset($_POST['articlePrice'])) echo $_POST['articlePrice']; ?>"/></p>
<p><b>Pricing Code:</b> <input type="text" name="priceCode" size="20" maxlength="40" value="<?php if(isset($_POST['priceCode'])) echo $_POST['priceCode']; ?>" /></p>
</fieldset>
<div align="left"><input type="submit" name="submit" value="Check!" /></div>
</form>

<?php
if(isset($_REQUEST['submit'])){
			$errors = array();
			//$var = strtolower($_POST['priceCode']);
			// Validate article price
			if (!empty($_POST['articlePrice'])) {
				if (is_numeric($_POST['articlePrice'])) {
					$articlePrice = $_POST['articlePrice'];
					}
					else {
						$articlePrice = NULL;
						$errors[] = '<p><b>You must enter your article price in numeric only!</b></p>';
						}
			} else {
			$articlePrice = NULL;
			$errors[] = '<p><b>You forgot to enter your article price!</b></p>';
			}
	
				// Validate pricing code
				if (!empty($_POST['priceCode'])) {
					if (!is_numeric($_POST['priceCode'])) {
						if (in_array(strtolower($_POST['priceCode']), array("h", "f", "q", "t", "z"))){
							$priceCode = strtolower($_POST['priceCode']);
						}else{
							$priceCode = NULL;
							$errors[] = '<p><b>Invalid discount code</b></p>';
						}
					}else {
						$priceCode = NULL;
						$errors[] = '<p><b>You must enter your pricing code in letters only!</b></p>';
						}
				} else {
				$priceCode = NULL;
				$errors[] = '<p><b>You forgot to enter your pricing code!</b></p>';
				}

			//If there are no errors perform calculation
			if (empty($errors)){ 
				$priceCodeArr = array('h' => 50, 'f' => 40, 'q' => 33, 't' => 25, 'z' => 0);
				$discountPercentage = $priceCodeArr[$priceCode];
				function Calculate($articlePrice, $discountPercentage){
					$total = $articlePrice - ($discountPercentage /100 * $articlePrice);
					$total = number_format($total,2);
					$result = "
					<h3><font color='green'>TOTAL</font></h3>
					<p><b>The article price: RM $articlePrice</b></p>
					<p><b>The pricing discount: $discountPercentage %</b></p>
					<p><b>The total pricing: RM $total</b></p>
					";
					return $result;
				}
				
				$final = Calculate($articlePrice,$discountPercentage);
				echo $final;
				
			}else { // One form element was not filled out properly.
				echo '<h3><font color="red">ERROR!</font></h3>';
				foreach ($errors as $msg){
					echo '<p><font color="red">-' . $msg . '</font></p>';
				}
				echo '<p><font color="red"><p>-</p>Please go back and fill out the form again.</font></p>';
				}
}
//LINE 36 CAN ALSO USE MULTIPLE CONDITION
//strtolower($_POST['priceCode']) == 'h' or strtolower($_POST['priceCode']) == 'f' or strtolower($_POST['priceCode']) == 'q' or strtolower($_POST['priceCode']) == 't' or strtolower($_POST['priceCode']) == 'z'
?>
</body>
</html>