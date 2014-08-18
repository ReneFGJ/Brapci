<?php
require("cab.php");

require("../_class/_class_bris.php");
$bris = new bris;
$ano = $dd[0];
$ano = 2013;
$ano2 = 2013;
echo '<H1>Journal JHanking</h1>';
echo '<h3>Ano Base: '.$ano.'-'.$ano2.'</h3>';

echo $bris->fi_journal($ano,2);
echo $bris->fi_journal($ano,3);
echo $bris->fi_journal($ano,5);

/* Indice H */
$sql = "select * from brapci_journal ";
$rlt = db_query($sql);
$id = 0;
while ($line = db_read($rlt))
	{
		$id++;
		$autor = trim($line['jnl_codigo']);
		echo '<HR>';
		echo $line['jnl_nome'].' '.$autor;
		echo $bris->calcular_indice_h_revista($autor);
	}
	
/* Fator de Impacto */
echo $bris->calcula_fator_impacto_revista();	
	




?>
