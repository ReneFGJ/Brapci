<?php
/*** Modelo ****/
require("cab_cip.php");

global $acao,$dd,$cp,$tabela;
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_form2.php');
require($include.'sisdoc_debug.php');

	/* Dados da Classe */
	require('../_class/_class_diris.php');

	$clx = new diris;
	$tabela = $clx->tabela;
	
	/* Não alterar - dados comuns */
	$http_edit = 'diris_autores_ed.php'; 
	$http_ver = 'diris_autores_detalhe.php'; 
	$editar = True;
	$http_redirect = 'diris_autores.php';
	$clx->row();
	$busca = true;
	$offset = 20;
	
	if ($order == 0) { $order  = $cdf[1]; }

	require("../db_diris.php");
	
	echo '<TABLE width="'.$tab_max.'" align="center"><TR><TD>';
	require($include.'sisdoc_row.php');	
	echo '</table>';	

require("../foot.php");		
?> 