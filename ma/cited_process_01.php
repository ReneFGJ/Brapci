<?
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('revistas','brapci_brapci_journal.php'));
////////////////////////////

require("cab_process.php");

echo '<h1>Identificar ano de publicação (Fase I)</h1>';

require("cited_menu.php");

require("../_class/_class_cited.php");
$cited = new cited;

echo $cited->resumo();

$proc = $cited->cited_I_recover_year();
echo '<BR><BR>Processadas '.$proc.' referências';
if ($proc > 0)
	{
			echo '<meta http-equiv="refresh" content="2;'.page().'" />';
	}
echo '</table>';

require("../foot.php");
?>
