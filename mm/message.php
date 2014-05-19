<?php
require("cab.php");

$file = '../messages/msg_message.php';
if (file_exists($file)) { require($file); }

$tp = new message;
//$tp->language_set('pt_BR');


global $acao,$dd,$cp,$tabela;
require($include.'cp2_gravar.php');
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_form2.php');
require($include.'sisdoc_debug.php');

//$sql = "delete from _messages where 1=1";
//$rlt = db_query($sql);

	$clx = new message;
	$cp = $clx->cp();
	$tabela = $clx->tabela;
	
	$label = msg("mensagens");
	$http_edit = 'message_ed.php'; 
	$editar = True;
	
	//$http_ver = 'cliente_ver.php';
	
	$http_redirect = 'message.php?dd98='.$dd[98].'&dd97='.$dd[97];
	
	$cdf = array('id_msg','msg_field','msg_language','msg_content');
	$cdm = array('cod',msg('field'),msg('language'),msg('content'));
	$masc = array('','','','','','','');
	$busca = true;
	$offset = 20;
	//$pre_where = " e_mailing = '".$cl->mail_codigo."' ";
	
	//$dd[97] = 'pt_BR';
	if (strlen($dd[98]) > 0)
		{ $pre_where = "msg_pag = '".$dd[98]."' "; }
	if (strlen($dd[97]) > 0)
		{ $pre_where = "msg_language = '".$dd[97]."' "; }
	
	$order  = "msg_pag";
	echo '<div id="content">';
	echo '<TABLE width="'.$tab_max.'" align="center"><TR><TD>';
	require($include.'sisdoc_row.php');	
	echo '</table>';	
	echo '</div>';
	
	

require("foot.php");		
?> 
<script>
	$("#content").corner();
</script>