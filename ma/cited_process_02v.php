<?
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('revistas','brapci_brapci_journal.php'));
////////////////////////////

require("cab_process.php");
require('../include/sisdoc_windows.php');

echo '<h1>Identificar tipo de publicação (Fase II)</h1>';

require("../_class/_class_cited.php");
$cited = new cited;
if ($dd[1]=='ERRO')
	{
		$cited->marcar_como_erro($dd[0]);
		redirecina(page());
		exit;
	}
echo $cited->resumo();

echo $cited->journal_form_insert($dd[10]);
echo $cited->book_form_insert($dd[12]);
echo $cited->anais_form_insert($dd[14]);
echo '<HR>';

$proc = $cited->cited_IIv();

if ($proc > 0)
	{
		redirecina(page());
	}

require("../foot.php");
?>