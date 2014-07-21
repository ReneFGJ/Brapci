<?php
/*** Modelo ****/
require("cab.php");
	
	/* Dados da Classe */
	require('../_class/_class_qualis.php');
	$clx = new qualis;

	$name = $_FILES['arquivo']['name'];
	if (strlen($name)==0)
		{
			echo $clx->cited_inport_qualis();
		} else {
			echo $clx->cited_inport_qualis_post();
			echo '--importar--';
		}

require("../foot.php");		
?> 