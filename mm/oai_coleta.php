<?php
require("cab.php");

require("../_class/_class_oai.php");
$oai = new oai;
echo $oai->cab();

/* Recupera Journal */
$jid = round('0'.$dd[1]);

echo $oai->resumo_process($jid);
$id = $oai->cache_next_harvesting($jid);

							$sql = "update oai_cache set
										cache_status = '@'
										where cache_status = 'A' 
							";
							//$rlt = db_query($sql);

if (strlen($id) > 0)
	{
	echo 'downloading...:'.$id;
	echo '<BR>Status: '.$oai->cache_coleta_registro($id);
	}

if ($oai->cache_para_coletar($jid) > 0)
	{
		echo '
		<meta http-equiv="refresh" content="3; URL = oai_coleta.php?dd1='.$dd[1].'">
		';
		
	}
require("../foot.php");
?>
