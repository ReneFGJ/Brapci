<?php
require('cab.php');

global $acao,$dd,$cp,$tabela;
require($include.'cp2_gravar.php');
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_form2.php');
require($include.'sisdoc_data.php');
require($include.'sisdoc_debug.php');

	require("../_class/_class_pibic_pagamento.php");
	echo '1';	
	$cl = new pagamentos;
	echo '2';
	$cp = $cl->cp();
	echo '3';
	$tabela = $cl->tabela;
	
	print_r($cp);
	
	$http_edit = 'pibic_03e.php';
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
			redirecina('pibic_03.php');
		}
require("../foot.php");	
?>

