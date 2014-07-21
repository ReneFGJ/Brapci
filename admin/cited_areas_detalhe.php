<?php
/*** Modelo ****/
require("cab.php");
global $acao,$dd,$cp,$tabela;
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_debug.php');

	/* Dados da Classe */
	require('../_class/_class_qualis.php');
	$clx = new qualis;
	
	echo $clx->mostra_area_resumo($id);

require("../foot.php");		
?> 