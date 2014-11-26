<?php
require("cab.php");

require("../_class/_class_bris.php");
$bris = new bris;
$ano = $dd[0];
$ano = 2013;
$ano2 = 2013;
echo '<H1>Índice h - Autore(iHa)</h1>';
echo '<h3>Ano Base: '.$ano.'-'.$ano2.'</h3>';
$sql = "select * from bris_autor where au_update < ".date("Ymd")." and au_ano = ".date("Y")." limit 100 ";
$rlt = db_query($sql);
$id = 0;
while ($line = db_read($rlt))
	{
		$id++;
		$autor = trim($line['au_codigo']);
		echo $bris->calcular_indice_h_autor($autor);
	}
echo '==>'.$id;
?>
