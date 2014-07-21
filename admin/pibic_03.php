<?php
require("cab.php");

/*
 * $breadcrumbs
 */
$breadcrumbs = array();
array_push($breadcrumbs,array(http.'admin/index.php',msg('principal')));
array_push($breadcrumbs,array(http.'admin/index.php',msg('menu')));
echo '<div id="breadcrumbs">'.breadcrumbs().'</div>';

require("../_class/_class_pibic_pagamento.php");
$clx = new pagamentos;

$clx->row();
	$tabela = $clx->tabela;
	
	/* Não alterar - dados comuns */
	$http_edit = 'pibic_03e.php';
	//$http_ver = 'pibic_bolsa_tipo_detalhe.php'; 
	$editar = True;
	$http_redirect = 'pibic_03.php';
	$clx->row();
	$busca = true;
	$offset = 20;
	//$pre_where = " e_mailing = '".$cl->mail_codigo."' ";
	if ($order == 0) { $order  = $cdf[1]; }
	
	echo '<TABLE width="'.$tab_max.'" align="center"><TR><TD>';
	require($include.'sisdoc_row.php');	
	echo '</table>';	

require("../foot.php");	
?>