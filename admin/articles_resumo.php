<?php
require("cab.php");

/* header */
require("../_class/_class_journals.php");
$jnl = new journals;

$sta = $dd[1];
echo $jnl->journals_articles_status($sta);

require("foot.php");
?>
