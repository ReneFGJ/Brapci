<?php
require("cab.php");

/*
 * $breadcrumbs
 */
$breadcrumbs = array();
array_push($breadcrumbs,array(http.'admin/index.php',msg('principal')));
array_push($breadcrumbs,array(http.'admin/index.php',msg('menu')));
echo '<div id="breadcrumbs">'.breadcrumbs().'</div>';

require("../_class/_class_parecer_pibic.php");
$clx = new parecer_pibic;


$clx->tabela = "pibic_parecer_".date("Y");
if ($dd[0]=='S')
{
	$clx->cancelar_avaliacoes('RPAR');
}


require("../foot.php");	
?>