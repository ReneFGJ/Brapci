<?
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('revistas','brapci_brapci_journal.php'));
////////////////////////////

require("cab.php");

echo '<h1>Refer�ncias com problemas</h1>';

require("../_class/_class_cited.php");
$cited = new cited;

echo $cited->resumo();

$proc = $cited->cited_problens();
echo '<BR><BR>Identificado '.$proc.' refer�ncias';

require("../foot.php");
?>
