<?php 

session_start(); // Start the session.

// Set the page title and include the HTML header.
$page_title = 'Logged In!';
include ('./includes_manager/header.html');

// Print a customized message.
echo "<h1>Logged In!</h1>
<p>You are now logged in, Welcome {$_SESSION['name']}! ID: {$_SESSION['managerID']}.</p>
<p>Please navigate the system by using the navigation menu on the right.</p>
<p><br /><br /></p>";

include ('./includes/footer.html');
?>