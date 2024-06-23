<html>
<head>
<title>Tax Form</title>
</head>
<body>
<form action="tax_result.php" method="post">
<fieldset><legend>Calculate tax:</legend>
<p><b>Quantity:</b> <input type="text" name="quantity" size="20" maxlength="40" /></p>
<p><b>Price(RM):</b> <input type="text" name="price" size="20" maxlength="40" /></p>
<p><b>Tax rate(%):</b> <input type="text" name="tax" size="20" maxlength="40" /></p>
</fieldset>
<div align="left"><input type="submit" name="submit" value="Calculate" /></div>
</form>
</body>
</html>