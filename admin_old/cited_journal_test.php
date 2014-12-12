<?php
/*** Modelo ****/
require("cab.php");
	
	/* Dados da Classe */
	require('../_class/_class_qualis.php');
	$clx = new qualis;

	$clx->read_qualis_page();	

require("../foot.php");		
?> 