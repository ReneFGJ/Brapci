<?php
/*** Modelo ****/
require("cab.php");
global $acao,$dd,$cp,$tabela;
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_form2.php');
require($include.'sisdoc_debug.php');
	echo 'oi';
	/* Dados da Classe */
	require('../_class/_class_qualis.php');
	echo 'ola';
	$clx = new qualis;
	
	echo $clx->cited_inport_qualis();
	
	echo $clx->cited_inport_qualis_post();	

require("../foot.php");		
?> 