<?
require("cab.php");
$file = '../messages/msg_cep_submit_tipo.php';
if (file_exists($file)) { require($file); }

global $acao,$dd,$cp,$tabela;
require($include.'cp2_gravar.php');
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_form2.php');
require($include.'sisdoc_debug.php');
require("../_class/_class_cep_submit_tipo.php");

	$clx = new cep_submit_tipo;
	$tabela = $clx->tabela;
	
	$label = msg("titulo");
	$http_edit = 'cep_submit_tipo_ed.php'; 
	$editar = True;
	
	$http_ver = 'cep_submit_tipo_ver.php';
	
	$http_redirect = 'cep_submit_tipo.php';
	
	$cdf = array('id_sp','sp_descricao','sp_codigo','sp_ativo','sp_nucleo');
	$cdm = array('cod',msg('nome'),msg('codigo'),msg('ativo'),msg('nucleo'));
	$masc = array('','','','','','','','');
	$busca = true;
	$offset = 20;
	//$pre_where = " e_mailing = '".$cl->mail_codigo."' ";
	
	$order  = "sp_descricao";
	//exit;
	echo '<TABLE width="'.$tab_max.'" align="center"><TR><TD>';
	require($include.'sisdoc_row.php');	
	echo '</table>';	

require("../foot.php");		
?> 