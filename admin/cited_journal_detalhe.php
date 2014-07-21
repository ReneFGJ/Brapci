<?php
/*** Modelo ****/
require("cab.php");
global $acao,$dd,$cp,$tabela;
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_debug.php');

	/* Dados da Classe */
	require('../_class/_class_qualis.php');
	$clx = new qualis;
	$clx->le($dd[0]);
	
	echo $clx->mostra_dados();
	echo $clx->mostra_qualis();

require("../foot.php");		
?> 