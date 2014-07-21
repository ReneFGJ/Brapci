<?php
require("cab.php");
require('../_class/_class_cited.php');
$ct = new cited;

$ct->ajuste_automatico();

$ct->processa_remissivas();

?>
