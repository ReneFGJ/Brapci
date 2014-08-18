<?
$ac = 0;
if ($dd[2] == 'prefTerm')
	{
	require('class_propriety_rules_01.php');
	$ac = 1;
	}
if ($ac == 0) { print_r($dd);}
?>