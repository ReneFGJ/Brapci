<?php
require("cab.php");
require("_class/_class_cited.php");
require("_class/_class_email.php");

require($include.'_class_form.php');

$email = new email;
$ct = new cited;
echo $ct->busca_form();

if (strlen($dd[1]) > 0)
	{
		$term = UpperCaseSql($dd[1]);
		echo $ct->result_search($term);
	}
require("foot.php");
?>

