<?php
require("cab.php");
require("../_class/_class_bris.php");
$br = new bris;

$jid = round($dd[1]);
if ($jid < 1) { redirecina('bris_journal.php'); }

require("../_class/_class_journals.php");
$jnl = new journals;

$jnl->le($jid);
if (strlen($dd[1]) > 0) { $br->journal_fasciculos_insert($jid); }

echo $jnl->mostra();
$ano = sonumero($dd[0]);

echo $br->journal_fasciculos($jid,$ano);

echo '<h1>'.$journal.'</h1>';

?>
