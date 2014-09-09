<?php
require("cab.php");
$id = round($dd[0]);
if ($id == 0) { $id = 0; }
$jid = round($dd[1]);
require($include.'sisdoc_debug.php');

require("../_class/_class_oai.php");
$oai = new oai;

require("../_class/_class_journals.php");
$jnl = new journals;

echo $oai->cab();

echo $oai->resumo_process();

echo '<h3>Harveting</h3>';
$id = $oai->cache_next_harvesting_journal($id,$jid);
$jnl->le($id);
echo $jnl->mostra();

if (strlen($id) > 0)
	{
	echo 'downloading...:'.$id;
	$erro = $oai->oai_listidentifiers($id);
	echo '<BR>Status: '.$erro;
	
	$oai->atualiza_harvesting($id,$erro);
	}

if (($id > 0) and ($dd[5] != 1))
	{
		echo '
		<meta http-equiv="refresh" content="5; URL = oai_harvesting.php?dd0='.($id++).'">
		';		
	} else {
		echo '<BR>Finalizado com status: '.$id;
	}
require("../foot.php");
?>
