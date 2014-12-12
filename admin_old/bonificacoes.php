<?
require("cab.php");
require('../_class/_class_bonificacao.php');

global $acao,$dd,$cp,$tabela;
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_form2.php');
require($include.'sisdoc_debug.php');

	$clx = new bonificacao;
	$tabela = 'bonificacao';
	
	$label = msg("bonificao");
	$http_edit = 'bonificacoes_ed.php'; 
	$http_ver = ''; 
	
 	if ($perfil->valid('#RES')) { $editar = True; }
	$http_redirect = 'bonificacoes.php';
	$editar = True;
	
	$clx->row();
	$busca = true;
	$offset = 20;
	//$pre_where = " e_mailing = '".$cl->mail_codigo."' ";
	
	//exit;
	echo '<TABLE width="100%" align="center"><TR><TD>';
	require($include.'sisdoc_row.php');	
	echo '</table>';	

require("../foot.php");		
?> 