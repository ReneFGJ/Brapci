<?php
require("cab.php");

/* header */
require("../_class/_class_journals.php");
$jnl = new journals;

$sta = $dd[1];
$journal = $dd[2];

echo $jnl->journals_articles_lista($sta,$journal);

require("foot.php");
?>
