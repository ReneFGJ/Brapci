<?
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('revistas','brapci_brapci_journal.php'));
////////////////////////////

require("cab.php");

echo '<h1>ReferÍncias com problemas</h1>';

require('../include/sisdoc_windows.php');
require("../_class/_class_cited.php");
$cited = new cited;

echo $cited->resumo();

echo $cited->mostra_botao_erro($dd[0]);

$proc = $cited->cited_problens();
echo '<BR><BR>Identificado '.$proc.' referÍncias';

require("../foot.php");
?>
