<?php
require ("cab.php");
require ("../_class/_class_bris.php");
require ("../_class/_class_journals.php");
require ("../_class/_class_issue.php");
$br = new bris;
$jnl = new journals;
$issue = new issue;

$jnl->le(round($dd[0]));
$issue->journal = $jnl->codigo;

echo $jnl->mostra();
echo $jnl->mostra_detalhe();

echo '<table width="98%" align="center">';
echo '<TR valign="top">';
echo '<TD>';
echo $issue->issue_list();
echo '<TD>';
echo $br->journal_fi_issn($jnl->codigo);
echo '</table>';

?>
