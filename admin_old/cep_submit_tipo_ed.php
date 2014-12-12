<?php
require('cab.php');
require('../_class/_class_cep_submit_tipo.php');
$file = '../messages/msg_cep_submit_tipo_ed.php ';
if (file_exists($file)) { require($file); }
$ln = new message;

global $acao,$dd,$cp,$tabela;
require($include.'cp2_gravar.php');
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_form2.php');
require($include.'sisdoc_debug.php');

	$cl = new cep_submit_tipo;
	$cp = $cl->cp();
	$tabela = $cl->tabela;
	
	$http_edit = 'cep_submit_tipo_ed.php';
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
			redirecina('cep_submit_tipo.php');
		}
require("../foot.php");
?>

