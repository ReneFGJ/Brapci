<?php
require("cab.php");

require("../_class/_class_bris.php");
$bris = new bris;
$ano = $dd[0];
$ano = 2012;
$ano2 = 2012;
echo '<H1>Indice de Autores mais produtivos(iAP)</h1>';
echo '<h3>Ano Base: '.$ano.'-'.$ano2.'</h3>';

echo $bris->grupos_iap($ano,$ano2);

?>
