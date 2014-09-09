<?php
require("cab.php");

require("../_class/_class_bris.php");
$bris = new bris;
$ano = $dd[0];
$ano = 2013;
$ano2 = 2013;
echo '<H1>Journal JRanking</h1>';
echo '<h3>Ano Base: '.$ano.'-'.$ano2.'</h3>';

echo $bris->calcula_fator_impacto_revista();
?>
