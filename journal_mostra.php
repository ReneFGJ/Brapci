<?php
require("cab.php");
require($include.'sisdoc_colunas.php');
require("_class/_class_journals.php");
$jnl = new journals;

require("_class/_class_issue.php");
$issue = new issue;

echo '<h2>'.msg('journal_list').'</h2>';

$jnl->le($dd[0]);
echo $jnl->journals_mostra();

/* issue */
$issue->journal = $jnl->codigo;

echo '<BR><BR>';
echo $issue->issue_list();

require("foot.php");
?>
