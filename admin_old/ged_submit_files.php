<?php
require("cab.php");
require("../_class/_class_ged.php");

global $acao,$dd,$cp,$tabela;
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_form2.php');
require($include.'sisdoc_debug.php');

	$clx = new ged;
	$clx->tabela = 'submit_files_tipo';
	$tabela = $clx->tabela;
	
	$label = msg("Documentos - Tipo");
	$http_edit = troca(page(),'.php','_ed.php'); 
	$editar = True;
	
	$http_redirect = page();
	
	
	$clx->row_type();
	$busca = true;
	$offset = 20;
	//$pre_where = " e_mailing = '".$cl->mail_codigo."' ";
	
	$order  = "";
	//exit;
	echo '<TABLE width="100%" align="center"><TR><TD>';
	require($include.'sisdoc_row.php');	
	echo '</table>';

require("../foot.php");
?>
