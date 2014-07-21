<?
require("cab.php");

global $acao,$dd,$cp,$tabela;
require($include.'cp2_gravar.php');
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_form2.php');
require($include.'sisdoc_debug.php');
require("../_class/_class_perfil.php");

$clx = new user_perfil;

	$cp = $clx->cp();
	$tabela = $clx->tabela;
	
	$label = msg("perfil");
	$http_edit = 'perfil_ed.php'; 
	$editar = True;
	
	$http_ver = 'perfil_ver.php';
	
	$http_redirect = 'perfil.php';
	
	$cdf = array('id_per','per_nome','per_codigo','per_ativo');
	$cdm = array('cod',msg('nome'),msg('codigo'),msg('ativo'));
	$masc = array('','','','','','','');
	$busca = true;
	$offset = 20;
	//$pre_where = " e_mailing = '".$cl->mail_codigo."' ";
	
	$order  = "per_nome";
	//exit;
	echo '<TABLE width="'.$tab_max.'" align="center"><TR><TD>';
	require($include.'sisdoc_row.php');	
	echo '</table>';	

require("../foot.php");		
?> 