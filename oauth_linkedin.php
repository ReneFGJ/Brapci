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
		$link = $user->linkedin_authorized($code);
		echo '<A HREF="'.$link.'">LINK</A>';
		echo '<HR>CODE: '.$link.'<HR>';
	}
