<?php
require("cab.php");
require("../_class/_class_bris.php");
$br = new bris;

echo '<H1>Autor Ranking</h1>';
$ano = '2013';
echo $br->ranking_author($ano);

if ($dd[1]=='1')
	{
		$br->ranking_author_create('2013');
		$br->ranking_author_create('2012');
		echo 'Gerando dados';
	}


?>
