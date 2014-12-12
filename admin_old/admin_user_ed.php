<?php
require("cab.php");

global $acao,$dd,$cp,$tabela;
require($include.'cp2_gravar.php');
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_form2.php');
require("../_class/_class_usuario.php");

	$cl = new users;
	$cp = $cl->cp_admin();
	$tabela = 'usuario';
	$http_edit = 'admin_user_ed.php';
	$http_redirect = '';
	echo '-->'.$tabela;
	/** Comandos de Edicao */
	echo '<div id="content">';
	echo '<CENTER><h2>'.msg('users').'</h2></CENTER>';
	?><TABLE width="<?=$tab_max;?>" align="center" bgcolor="<?=$tab_color;?>"><TR><TD><?
	editar();
	?></TD></TR></TABLE><?	
	
	/** Caso o registro seja validado */
	if ($saved > 0)
		{
			echo 'Salvo';
			$cl->updatex();
			redirecina('admin_user.php');
		}
	echo '</div>';
require("foot.php");
?>
