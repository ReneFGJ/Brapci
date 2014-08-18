<?
require("oai_function.php");

$sql = "select * from brapci_journal where (id_jnl = 0".$dd[0].") or (id_jnl = '".round('0'.$jid)."') ";
$rlt = db_query($sql);

if ($line = db_read($rlt))
	{
		$link_oai = trim($line['jnl_url_oai']);
		$jid = $line['jnl_codigo'];
		$jnome = $line['jnl_nome'];
		$harvesting_last = trim($line['jnl_last_harvesting']);
		if ($harvesting_last < 19900101)
			{ $harvesting_last = ''; }
	} else {
		echo '<font color="red">Journal não localizado</font>';
		exit;
	}
	
if (strlen($link_oai) == 0)
	{ echo 'Link do OAI inválido'; exit; }
?>