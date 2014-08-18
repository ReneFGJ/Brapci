<?
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('revistas','brapci_brapci_journal.php'));
////////////////////////////

require("cab.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_debug.php");
?><div id="main">HARVESTING OAI-PHM<BR><?
require("oai_cab.php");

$err = 0;
if (strlen($dd[0]) == 0) 
	{
		$sql = "select * from brapci_journal where jnl_url_oai <> '' order by id_jnl ";
		$zrlt = db_query($sql);
		if ($line = db_read($zrlt))
			{ $dd[0] = $line['id_jnl']; }
			else 
			{ $err = 1; }
	} else {
		$sql = "select * from brapci_journal where jnl_url_oai <> '' and id_jnl > ".$dd[0]." order by id_jnl ";
		$zrlt = db_query($sql);
		if ($line = db_read($zrlt))
			{ $dd[0] = $line['id_jnl']; }
			else 
			{ $err = 1; }
	}
if ($err == 0)
	{
	require("oai/oai_listIdentifiers.php");
	require("oai/oai_ListSets.php");
	if (strlen($dd[1]) > 0)
		{
		echo '<META HTTP-EQUIV="Refresh" CONTENT="10;URL=oai_harvesting.php?dd0='.($dd[0]+1).'&dd1=1">';
		}
	}
?>
<BR><BR><BR>
</div>
