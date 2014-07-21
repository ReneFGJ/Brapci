<?php
require("cab.php");

require("../_class/_class_bris.php");
$bris = new bris;

$ano = $dd[0];
$tipo = $dd[1];

if (strlen($ano) == 0) { $ano = (date("Y")-1); }
echo '<H1>Tipologia das fontes de informação - ano da fonte</h1>';
echo '<h3>Ano Base: '.$ano.'</h3>';

echo $bris->artigos_ano_citacao($ano,$tipo);

?>
