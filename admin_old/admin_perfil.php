<?
require("cab.php");

global $acao,$dd,$cp,$tabela;
require($include.'cp2_gravar.php');
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_form2.php');
require($include.'sisdoc_debug.php');
	
	$clx = new user_perfil;
	$tabela = $clx->tabela;
	
	$label = msg("mensagens");
	$http_edit = 'admin_perfil_ed.php'; 
	$editar = True;
	
	$http_ver = 'admin_perfil_ver.php';
	
	$http_redirect = 'admin_perfil.php';
	
	$clx->row_perfil();
	
	$busca = true;
	$offset = 20;
	//$pre_where = " e_mailing = '".$cl->mail_codigo."' ";
	
	$order  = "usp_descricao";
	//exit;
	echo '<TABLE width="'.$tab_max.'" align="center"><TR><TD>';
	require($include.'sisdoc_row.php');	
	echo '</table>';	

require("../foot.php");		
?> 