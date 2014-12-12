<?
/*** Modelo ****/
require("cab.php");
ini_set('max_execution_time','2360');
global $acao,$dd,$cp,$tabela;
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_form2.php');
require($include.'sisdoc_debug.php');

	/* Dados da Classe */
	require('../_class/_class_pibic_mirror.php');

	$clx = new mirror;
	$cp = $clx->cp();

	echo '<BR>==STATUS A==<BR>'.chr(13);
	$clx->espelho_status_a();
	echo '<BR>==STATUS B==<BR>'.chr(13);
	$clx->espelho_status_b();


	?><META HTTP-EQUIV=Refresh CONTENT="2; URL=pibic_espelho_processar.php"><?

	
require("../foot.php");		
?> 
