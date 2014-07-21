<?php
require("cab.php");

require("../_class/_class_bris.php");
$bris = new bris;

$ano = $dd[0];
if (strlen($ano) == 0) { $ano = (date("Y")-1); }
echo '<H1>Tipologia das fontes de informação</h1>';
echo '<h3>Ano Base: '.$ano.'</h3>';

echo $bris->tipologia_fontes($ano,$ano);

?>
