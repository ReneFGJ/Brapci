<?php
require("cab.php");
require("../_class/_class_bris.php");
$br = new bris;

/* Atualização de dados */
if ($dd[1]=='1')
	{
		for ($r=1972;$r <= date("Y");$r++)
			{
				echo '<BR>Processando dados '.$r;
				$br->ranking_author_create($r);
				echo ' Gerando dados';
			}
	} else {
		echo '<H1>Autor Ranking</h1>';
		$ano = '2014';
		echo $br->ranking_author(1900,$ano);
	}


?>
