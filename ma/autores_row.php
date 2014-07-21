<?php
require("cab.php");
require($include.'sisdoc_colunas.php');

require('../_class/_class_author.php');
$clx = new author;

	$tabela = $clx->tabela;
	
	/* Não alterar - dados comuns */
	$http_edit = 'autores_ed.php'; 
	$http_ver = 'autores_detalhe.php'; 
	$editar = True;
	$http_redirect = page();
	$clx->row();
	$busca = true;
	$offset = 20;
	
	if ($order == 0) { $order  = $cdf[1]; }
	$tab_max = '100%';
	echo '<TABLE width="98%" align="center"><TR><TD>';
	require($include.'sisdoc_row.php');	
	echo '</table>';

?>
