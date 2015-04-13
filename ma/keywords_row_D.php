<?php
require("cab.php");
require($include.'sisdoc_colunas.php');

require('../_class/_class_keyword.php');
$clx = new keyword;

echo '<H1>Não catalogados</h1>';

	$tabela = $clx->tabela;
	
	/* Não alterar - dados comuns */
	$http_edit = 'keyword_ed.php'; 
	$http_ver = 'keyword_detalhe.php'; 
	$editar = True;
	$http_redirect = page();
	$clx->row();
	$busca = true;
	$offset = 20;
	$pre_where = " kw_tipo = 'D' ";
	
	if ($order == 0) { $order  = $cdf[1]; }
	$tab_max = '100%';
	echo '<TABLE width="98%" align="center"><TR><TD>';
	require($include.'sisdoc_row.php');	
	echo '</table>';

?>
