<?php
require("cab.php");
require("../_class/_class_bris.php");
$br = new bris;

$rs = $br->processa_citacoes_do_autor();
echo '==========>'.$rs;
if ($rs > 0)
	{
		echo '<meta http-equiv="refresh" content="5;'.page().'">';
	}
?>