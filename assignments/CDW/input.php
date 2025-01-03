<html>
<body>
<?php
$page_title = 'Multiplefiles';
include('includes/header.html');
?>
<h2>Register</h2>
<form action="output.php" method="post">
	<p>Name: <input type="text" name="name" size="20" maxlength="40" /></p>
	<p>Email Address: <input type="text" name="email" size="20" maxlength="40" /> </p>
	<p><input type="submit" name="submit" value="Submit" /></p>
	<input type="hidden" name="submitted" value="TRUE" />
</form>

<?php
include('includes/footer.html');
?>
</body>

</html>