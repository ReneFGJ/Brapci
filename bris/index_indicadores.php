<?php
require("cab.php");
require("_class/_class_bris.php");
$br = new bris;

if (strlen($ano) == 0) { $ano = (date("Y")-1); }

echo '<H1>Indicadores de Produção</h1>';
echo '<UL>';
echo '<LI><a href="indicador_tipologia.php?dd0='.$ano.'">Tipologia das fontes</A></LI>';
echo '<LI><a href="indicador_icpa.php?dd0='.$ano.'">Indice de Concentração de Produção por Autor (iCPA)</A></LI>';
echo '<LI><a href="indicador_periodicidade.php">Periodicidade das publicações</A></LI>';
echo '</UL>';




?>
