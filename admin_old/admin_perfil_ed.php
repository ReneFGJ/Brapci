<?php
require('cab.php');
global $acao,$dd,$cp,$tabela;
require($include.'cp2_gravar.php');
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_form2.php');
require($include.'sisdoc_data.php');
require($include.'sisdoc_debug.php');

	$cl = new user_perfil;
	$cp = $cl->cp_perfil();
	$tabela = $cl->tabela;
	
	$http_edit = 'admin_perfil_ed.php';
	$http_redirect = '';

	/** Comandos de Edicao */
	echo '<CENTER><font class=lt5>'.msg('titulo').'</font></CENTER>';
	?><TABLE width="<?=$tab_max;?>" align="center" bgcolor="<?=$tab_color;?>"><TR><TD><?
	editar();
	?></TD></TR></TABLE><?	
	
	/** Caso o registro seja validado */
	if ($saved > 0)
		{
			echo 'Salvo';
			redirecina('admin_perfil.php');
		}
		
?>

