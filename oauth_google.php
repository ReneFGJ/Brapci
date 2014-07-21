<?php
require("cab.php");

print_r($_SESSION);
echo '<HR>';

print_r($_POST);
echo '<HR>';


print_r($_GET);
echo '<HR>';

print_r($_COOKIE);
echo '<HR>';

/* */
$code = $_GET['code'];
if (strlen($code) > 0)
	{
		echo '<font color="red">';
		echo $user->get_oauth2_token($code);
		echo '</font>';
	}
