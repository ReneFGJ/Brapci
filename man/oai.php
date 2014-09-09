<?php
require("cab.php");
require($include.'sisdoc_data.php');

require("../_class/_class_oai.php");
$oai = new oai;
echo $oai->cab();

echo $oai->resumo_process();

echo $oai->show_last_harvesting();

echo '<UL>';
echo '<LI>';
echo '<a href="oai_processar.php">Processar coletas</A>';
echo '</LI>';

echo '<LI>';
echo '<a href="oai_harvesting.php">OAI-PMH Harvesting</A>';
echo '</LI>';

echo '</UL>';
require("../foot.php");
?>
