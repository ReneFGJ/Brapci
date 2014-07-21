<?php
require("cab.php");
require($include.'sisdoc_colunas.php');
require("_class/_class_journals.php");
$jnl = new journals;
echo '<h2>'.msg('journal_list').' '.msg("current").'</h2>';
echo $jnl->list_journals('A');

echo '<h2>'.msg('journal_list').' '.msg("closed").'</h2>';
echo $jnl->list_journals('B');

require("foot.php");
?>
