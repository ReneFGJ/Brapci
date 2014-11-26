<?php
require("cab.php");

require('../_class/_class_publications.php');

require("../_class/_class_bris.php");
$bris = new bris;

$ano = date("Y");
echo '<H1>Indice de autocitacação do autor</h1>';
echo '<h3>Ano Base: '.$ano.'</h3>';

echo $bris->indicador_autocitacao($ano);
?>
