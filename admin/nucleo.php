<?
require("cab.php");
$file = '../messages/msg_nucleo.php';
if (file_exists($file)) { require($file); }

global $acao,$dd,$cp,$tabela;
require($include.'cp2_gravar.php');
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_form2.php');
require($include.'sisdoc_debug.php');
require("../_class/_class_nucleo.php");

	$clx = new nucleo;
	$tabela = $clx->tabela;
	
	$label = msg("nucleo");
	$http_edit = 'nucleo_ed.php'; 
	$editar = True;
	
	$http_ver = '';
	
	$http_redirect = 'nucleo.php';
	
	$cdf = array('id_n','n_descricao','n_codigo','n_ativo');
	$cdm = array('cod',msg('nome'),msg('codigo'),msg('ativo'));
	$masc = array('','','','','','','');
	$busca = true;
	$offset = 20;
	//$pre_where = " e_mailing = '".$cl->mail_codigo."' ";
	
	$order  = "n_descricao";
	//exit;
	echo '<TABLE width="'.$tab_max.'" align="center"><TR><TD>';
	require($include.'sisdoc_row.php');	
	echo '</table>';	

require("../foot.php");		
?> 