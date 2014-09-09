<?
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('revistas','brapci_brapci_journal.php'));
////////////////////////////

require("cab.php");
require($include.'sisdoc_windows.php');

echo '<h1>Identificar tipo de publicação (Fase II)</h1>';

require("cited_menu.php");

require("../_class/_class_cited.php");
$cited = new cited;

echo $cited->resumo();

$proc = $cited->cited_III_recover();
echo '<BR><BR>Processadas '.$proc.' referências';
if ($proc > 0)
	{
			echo '<meta http-equiv="refresh" content="2;'.page().'" />';
	}

require("../foot.php");
exit;
?>
