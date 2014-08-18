<?php
require("cab.php");
require("../_class/_class_bris.php");
$br = new bris;

$jid = round($dd[1]);
if ($jid < 1) { redirecina('bris_journal.php'); }

require("../_class/_class_journals.php");
$jnl = new journals;

$jnl->le($jid);
echo $jnl->mostra();

echo $br->journal_fasciculos_articles($dd[0]);


echo '<h1>'.$journal.'</h1>';

?>
