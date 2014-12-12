<?php
/*** Modelo ****/
require("cab.php");
global $acao,$dd,$cp,$tabela;
require($include.'sisdoc_colunas.php');
require($include.'_class_form.php');
$form = new form;

require($include.'sisdoc_debug.php');
	echo 'oi';
	/* Dados da Classe */
	require('../_class/_class_qualis.php');
	echo 'ola';
	$clx = new qualis;
	$cp = $clx->cp_cited();
	$tabela = $clx->tabela_estrato;
	$tela = $form->editar($cp,$tabela);
	
	if ($form->saved > 0)
		{
			echo 'saved';
		} else {
			echo $tela;
		}

require("../foot.php");		
?> 