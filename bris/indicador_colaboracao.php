<?php
require("cab.php");

require("../_class/_class_bris.php");
$bris = new bris;

$ano = $dd[0];
echo '<H1>Indice de Colaboração Científica</h1>';
echo '<h3>Ano Base: '.$ano.'</h3>';

echo $bris->indicador_cc($ano);

?>
