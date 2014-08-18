<?php
require("cab.php");
require("../_class/_class_bris.php");
$br = new bris;

$jid = round($dd[0]);
if ($jid < 1) { redirecina('bris_journal.php'); }

require("../_class/_class_journals.php");
$jnl = new journals;

$jnl->le($jid);
if (strlen($dd[1]) > 0) 
	{
		echo '<HR>Processando dados...<HR>';
		$br->journal_fasciculos_insert($jid);
		echo '<HR>Processando dados II...<HR>';
		$br->citacoes_por_ano_base(); 
	}

echo $jnl->mostra();

echo $br->journal_anos($jid);

echo '<h1>'.$journal.'</h1>';

?>
