<?php
require('cab.php');

global $acao,$dd,$cp,$tabela;
require($include.'cp2_gravar.php');
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_form2.php');
require($include.'sisdoc_data.php');
require($include.'sisdoc_debug.php');

	require("../_class/_class_parecer_pibic.php");
	$cl = new parecer_pibic;
	$cp = $cl->cp();
	$cl->tabela = "pibic_parecer_".date("Y");
	$http_edit = 'pibic_05e.php';
	$http_redirect = '';
	$tit = msg("titulo");
	$tabela = $cl->tabela;
	/** Comandos de Edição */
	echo '<CENTER><font class=lt5>'.msg('titulo').'</font></CENTER>';
	?><TABLE width="<?=$tab_max;?>" align="center" bgcolor="<?=$tab_color;?>"><TR><TD><?
	editar();
	?></TD></TR></TABLE><?	
	
	/** Caso o registro seja validado */
	if ($saved > 0)
		{
			echo 'Salvo';
			redirecina('pibic_05.php');
		}
require("../foot.php");	
?>

