<?
ob_start();
$dd[0] = $_GET['dd0'];
echo '<META HTTP-EQUIV="Refresh" CONTENT="10;URL=brapci_harvesting.php?dd0='.($dd[0]+1).'">';
require("brapci_brapci_journal_harvesting.php");
?>

