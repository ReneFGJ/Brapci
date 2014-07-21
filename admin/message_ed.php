<?php
require('cab.php');
$ln = new message;

global $acao,$dd,$cp,$tabela;
require($include.'cp2_gravar.php');
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_form2.php');
require($include.'sisdoc_debug.php');

	/* Mensagens */
	$link_msg = '../messages/msg_message_ed.php';
	echo $link_msg;
	if (file_exists($link_msg)) { require($link_msg); }	

	$cl = new message;
	$cp = $cl->cp();
	$tabela = $cl->tabela;
	
	
	$http_edit = 'message_ed.php';
	$http_redirect = '';
	$tit = msg("titulo");

	/** Comandos de Edição */
	echo '<CENTER><font class=lt5>'.msg('titulo').'</font></CENTER>';
	?><TABLE width="<?=$tab_max;?>" align="center" bgcolor="<?=$tab_color;?>"><TR><TD><?
	editar();
	?></TD></TR></TABLE><?	
	
	/** Caso o registro seja validado */
	if ($saved > 0)
		{
			echo 'Salvo';
			$cl->updatex();
			redirecina('message.php');
		}
require("../foot.php");
?>

