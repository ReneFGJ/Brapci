<?php
require("cab.php");
require("_class/_class_scimago.php");
$sci = new scimago;
$file = '2012/Accounting.xls';
echo $file;

require("../_class/_class_cited.php");
$jl = new cited;
$jl->updatex_journal();

$sci->process($file);
?>
