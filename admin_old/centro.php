<?
/*** Modelo ****/
require("cab.php");
global $acao,$dd,$cp,$tabela;
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_form2.php');
require($include.'sisdoc_debug.php');

	/* Dados da Classe */
	require('../_class/_class_centro.php');
	
	$clx = new centro;
	$tabela = $clx->tabela;

	$sql = "delete from ".$tabela." where centro_nome = '-Doutorandos-'";
	$rlt = db_query($sql);	
	/* Mensagens */
	$link_msg = '../messages/msg_'.$tabela.'.php';
	if (file_exists($link_msg)) { require($link_msg); }
	
	/* Não alterar - dados comuns */
	$label = msg($tabela);
	$http_edit = $tabela.'_ed.php'; 
	$http_ver = $tabela.'_ver.php'; 
	$editar = True;
	$http_redirect = $tabela.'.php';
	$clx->row();
	$busca = true;
	$offset = 20;
	//$pre_where = " e_mailing = '".$cl->mail_codigo."' ";
	if ($order == 0) { $order  = $cdf[1]; }
	
	echo '<TABLE width="'.$tab_max.'" align="center"><TR><TD>';
	require($include.'sisdoc_row.php');	
	echo '</table>';	

require("../foot.php");		
?> 