<?php
require("cab.php");

require('../_class/_class_cited.php');
$ct = new cited;

$tabela = $ct->tabela_journal;

echo $ct->lista_completa_remissivas();

?>
