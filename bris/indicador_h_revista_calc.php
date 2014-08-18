<?php
require("cab.php");

require("../_class/_class_bris.php");
$bris = new bris;
$ano = $dd[0];
$ano = 2012;
$ano2 = 2012;
echo '<H1>Índice h - Autore(iHa)</h1>';
echo '<h3>Ano Base: '.$ano.'-'.$ano2.'</h3>';
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
echo '==>'.$id;
?>
