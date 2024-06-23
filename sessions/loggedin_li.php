<?php 

session_start(); // Start the session.

// Set the page title and include the HTML header.
$page_title = 'Logged In!';
include ('./includes/header.html');

// Print a customized message.
echo "<h1>Logged In!</h1>
<p>You are now logged in, {$_SESSION['first_name']} {$_SESSION['user_id']}!</p>
<p><br /><br /></p>";

include ('./includes/footer.html');
?>