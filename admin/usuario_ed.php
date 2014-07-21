<?php
require('cab.php');
require('../_class/_class_usuario.php');

global $acao,$dd,$cp,$tabela;
require($include.'cp2_gravar.php');
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_form2.php');
require($include.'sisdoc_debug.php');

	$cl = new users;
	$cp = $cl->cp();
	$tabela = $cl->tabela;
	
	$http_edit = 'usuario_ed.php';
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
			redirecina('usuario.php');
		}
require("../foot.php");
?>

