<?php
/*** Modelo ****/
require("cab_cip.php");

global $acao,$dd,$cp,$tabela;
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_form2.php');
require($include.'sisdoc_debug.php');

	/* Dados da Classe */
	require('../_class/_class_programa_pos.php');

	$clx = new programa_pos;
	$cp = $clx->cp();
	$tabela = $clx->tabela;
	
	/* Não alterar - dados comuns */
	$label = msg($tabela);
	$http_edit = $tabela.'_ed.php'; 
	$http_ver = $tabela.'_detalhe.php'; 
	$editar = True;
	$http_redirect = $tabela.'.php';
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