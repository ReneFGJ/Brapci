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
if (strlen($dd[0]) == 0) { $dd[0] = '1'; }
require("oai/oai_listIdentifiers.php");
require("oai/oai_ListSets.php");
?>
<BR><BR><BR>
</div>
