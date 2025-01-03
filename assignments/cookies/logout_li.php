<?php 
// Set the page title and include the HTML header.
$page_title = 'Logged Out!';
include ('./includes/header.html');

// Print a customized message.
echo "<h1>Logged Out!</h1>
<p>You are now logged out, {$_COOKIE['first_name']}!</p>
<p><br /><br /></p>";

setcookie ('first_name', '');
setcookie ('user_id', '');

include ('./includes/footer.html');
?>