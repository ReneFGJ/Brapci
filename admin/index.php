<?php
require("cab.php");

/* header */
require("../_class/_class_journals.php");
$jnl = new journals;

echo $jnl->jnl_resumo();

require("foot.php");
?>
