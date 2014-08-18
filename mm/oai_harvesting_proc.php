<?
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('revistas','brapci_brapci_journal.php'));
////////////////////////////

require("cab.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_debug.php");
require($include.'sisdoc_windows.php');
require($include.'sisdoc_message.php');
require($include.'sisdoc_autor.php');

?><div id="main">HARVESTING OAI-PHM<BR><?
require("oai_cab.php");
$sql = "select * from oai_cache where cache_status = 'A' ";
$sql .= " and cache_status = 'A' ";
$sql .= " order by id_cache desc ";
$zrlt = db_query($sql);

if ($line = db_read($zrlt))
	{
	$jid = $line['cache_journal'];
	require("oai/oai_process_record.php");
	?>
	<BR><BR><BR>
	<? if (strlen($err) == 0) { echo '<META HTTP-EQUIV="Refresh" CONTENT="3;URL=oai_harvesting_proc.php">'; } ?>
	<?
	} else {
	?>
	<center>
	<BR><BR><BR><H1>Fim do processamento</H1>
	</center>
	<?
	}
	?>
	</table>
</div>
