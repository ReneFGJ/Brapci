<?php
require("cab.php");
require("_class/_class_bris.php");
$br = new bris;

if (strlen($ano) == 0) { $ano = (date("Y")-1); }

echo '<H1>Indicadores de Produ��o</h1>';
echo '<UL>';
echo '<LI><a href="indicador_tipologia.php?dd0='.$ano.'">Tipologia das fontes</A></LI>';
echo '<LI><a href="indicador_icpa.php?dd0='.$ano.'">Indice de Concentra��o de Produ��o por Autor (iCPA)</A></LI>';
echo '<LI><a href="indicador_periodicidade.php">Periodicidade das publica��es</A></LI>';
echo '</UL>';




?>
