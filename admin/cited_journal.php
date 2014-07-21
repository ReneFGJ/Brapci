<?php
/*** Modelo ****/
require("cab.php");
global $acao,$dd,$cp,$tabela;
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_form2.php');
require($include.'sisdoc_debug.php');
	
	/* Dados da Classe */
	require('../_class/_class_qualis.php');
	$clx = new qualis;
	$tabela = $clx->tabela_journal;
	
	/* Não alterar - dados comuns */
	$label = msg($tabela);
	$http_edit = 'cited_journal_ed.php'; 
	$http_ver = 'cited_journal_detalhe.php'; 
	$editar = True;
	$http_redirect = 'cited_journal.php';
	$clx->row_journal();
	$busca = true;
	$offset = 20;
	
	if ($order == 0) { $order  = $cdf[1]; }
	
	echo '<TABLE width="'.$tab_max.'" align="center"><TR><TD>';
	require($include.'sisdoc_row.php');	
	echo '</table>';	

require("../foot.php");		
?> 