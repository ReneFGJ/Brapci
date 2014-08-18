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
require("oai/oai_process_record.php");
?>
<BR><BR><BR>
</div>
