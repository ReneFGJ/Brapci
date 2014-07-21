<?php
require("cab.php");
require($include.'sisdoc_colunas.php');
require("_class/_class_journals.php");
$jnl = new journals;

require("_class/_class_oai.php");
$oai = new oai;

echo $oai->cache_para_coletar();
require("foot.php");
?>
