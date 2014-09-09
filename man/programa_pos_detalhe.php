<?php
/*** Modelo ****/
require("cab_cip.php");
global $acao,$dd,$cp,$tabela;
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_windows.php');
require($include.'sisdoc_debug.php');

	require("../db_diris.php");
	
	/* Dados da Classe */
	require('../_class/_class_programa_pos.php');

	$clx = new programa_pos;
	$cp = $clx->cp();
	$tabela = $clx->tabela;
	
	/* Mensagens */
	$link_msg = '../messages/msg_'.page();
	if (file_exists($link_msg)) { require($link_msg); }
	
	$clx->le($dd[0]);
	echo $clx->mostra();
	echo $clx->mostra_docentes();
	
require("../foot.php");		
?> 