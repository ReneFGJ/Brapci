<?php
require("cab.php");
require($include.'sisdoc_colunas.php');
require("../_class/_class_publications_type.php");
$clx = new publication_type;

echo '<h3>'.msg('type_publications').'</h3>';

	/* Não alterar - dados comuns */
	$tabela = $clx->tabela;
	$http_edit = 'publications_type_ed.php';
	//$http_ver = 'pibic_bolsa_tipo_detalhe.php'; 
	$editar = True;
	$http_redirect = page();
	$clx->row();
	$busca = true;
	$offset = 20;

	echo '<TABLE width="'.$tab_max.'" align="center"><TR><TD>';
	require($include.'sisdoc_row.php');	
	echo '</table>';
	
require("../foot.php");
?>
