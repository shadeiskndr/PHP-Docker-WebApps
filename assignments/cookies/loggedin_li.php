<?php 

$page_title = 'Logged In!';
include ('./includes/header.html');

// Print a customized message.
echo "<h1>Logged In!</h1>
<p>You are now logged in, {$_COOKIE['first_name']} {$_COOKIE['user_id']}!</p>
<p><br /><br /></p>";

include ('./includes/footer.html');
?>