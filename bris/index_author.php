<?php
require("cab.php");
require("../_class/_class_bris.php");
$br = new bris;

$br->processa_citacoes_do_autor();

/* Atualiza��o de dados */
if ($dd[1]=='1')
	{
		for ($r=1972;$r <= 2011;$r++)
			{
				echo '<BR>Processando dados '.$r;
				$br->ranking_author_create($r);
				echo 'Gerando dados';
			}
	} else {
		echo '<H1>Autor Ranking</h1>';
		$ano = '2013';
		echo $br->ranking_author(1972,$ano);
	}


?>
