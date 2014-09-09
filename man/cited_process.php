<?

/**
 * Cited - Indexação automática de citações
 * @author Rene Faustino Gabriel Junior
 * @package Cited
 */
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('revistas','brapci_brapci_journal.php'));
////////////////////////////

require("cab.php");

echo '<h1>Identificar ano de publicação (Fase I)</h1>';

require("../_class/_class_cited.php");
$cited = new cited;

echo $cited->resumo();

require("../foot.php");
?>
