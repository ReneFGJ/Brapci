<?php
require ("cab.php");

$user->token();

$sx = '
<div id="login">
	<h1 class="blue">Login</h1>
	'.$user->login().'
</div>
';

echo $sx;
?>
