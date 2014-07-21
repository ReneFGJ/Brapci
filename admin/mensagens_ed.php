<?php
require('cab.php');
$ln = new message;

global $acao,$dd,$cp,$tabela;
require($include.'cp2_gravar.php');
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_form2.php');
require($include."sisdoc_editor.php");
require($include.'sisdoc_debug.php');
require($include.'sisdoc_data.php');

	/* Mensagens */
	require("../_class/_class_ic.php");
	$cl = new ic;
	$cp = $cl->cp();
	$tabela = $cl->tabela;
		
	$http_edit = 'mensagens_ed.php';
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
			//$cl->updatex();
			redirecina('mensagens_ver.php');
		}
require("../foot.php");
?>

