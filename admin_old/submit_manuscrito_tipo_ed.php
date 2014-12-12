<?php
require('cab.php');
require('../_class/_class_submit_manuscrito_tipo.php');

$ln = new message;

global $acao,$dd,$cp,$tabela;
require($include.'cp2_gravar.php');
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_form2.php');
require($include.'sisdoc_data.php');
require($include.'sisdoc_debug.php');

	$cl = new submit_manuscrito_tipo;
	$cp = $cl->cp();
	$tabela = $cl->tabela;
	
	/* Mensagens */
	$link_msg = '../messages/msg_'.$tabela.'_ed.php';
	if (file_exists($link_msg)) { require($link_msg); }	
	
	$http_edit = $tabela.'_ed.php';
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
			redirecina($tabela.'.php');
		}
require("../foot.php");	
?>

