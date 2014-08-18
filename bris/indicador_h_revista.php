<?php
require("cab.php");

require("../_class/_class_bris.php");
$bris = new bris;
$ano = $dd[0];
if (strlen($ano) == 0) { $ano = date("Y"); }
echo '<H1>Ranking revista com maior indice h</h1>';
echo '<h3>Ano Base: '.$ano.'</h3>';

echo $bris->indice_h_revista($ano);

?>
