<?php
require ("cab.php");

$user->token();

/* Login */
$user->auth_microsoft = 0;
$user->auth_linkedin = 0;
$user->auth_facebook = 1;
$user->auth_google = 0;

$sx = '
<div id="login">
	<h1 class="blue">Login</h1>
	'.$user->login().'
</div>
';

echo $sx;
?>
