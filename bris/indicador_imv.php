<?php
require("cab.php");

require("../_class/_class_bris.php");
$bris = new bris;

if (strlen($dd[0])==0)
	{
		echo 'Ano não informado, dd0=';
		exit;
	}

$ano = $dd[0];
echo '<H1>Meia Vida da Literatura (iMV)</h1>';
echo '<h3>Ano Base: '.$ano.'</h3>';

echo $bris->indicador_imv($ano);

?>
