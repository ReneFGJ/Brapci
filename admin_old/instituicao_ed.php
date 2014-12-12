<?php
require('cab.php');
require('../_class/_class_instituicao.php');

	/* Mensagens */
	$link_msg = '../messages/msg_'.page();
	if (file_exists($link_msg)) { require($link_msg); } else { echo 'erro:msg';}
	
$ln = new message;

global $acao,$dd,$cp,$tabela;
require($include.'cp2_gravar.php');
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_form2.php');
require($include.'sisdoc_debug.php');

	$cl = new instituicao;
	$cp = $cl->cp();
	$tabela = $cl->tabela;
	
	$http_edit = 'instituicao_ed.php';
	$http_redirect = '';
	$tit = msg("titulo");
	$dd[2] = uppercasesql($dd[1]);

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
			redirecina('instituicao.php');
		}
require("../foot.php");
?>

