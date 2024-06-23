<?php # Script 9.9 - logout.php (2nd version after Script 9.4)
// This page lets the user logout.

session_start(); // Access the existing session.

// Set the page title and include the HTML header.
$page_title = 'Logged Out!';
include ('./includes/headerCregister.html');

// Print a customized message.
echo "<h1>Logged Out!</h1>
<p>You are now logged out! ".$_SESSION['name']."</p>
<p><br /><br /></p>";

$_SESSION = array(); // Destroy the variables.
session_destroy(); // Destroy the session itself.
setcookie ('PHPSESSID', '', time()-300, '/', '', 0); // Destroy the cookie.

include ('./includes/footer.html');
?>