<?php
require("cab.php");

/* header */
require("../_class/_class_journals.php");
$jnl = new journals;

echo $jnl->jnl_resumo();

echo '<a href="main.php">MAIN</A>';
echo '<a href="export_public.php">EXPORT</A>';

require("foot.php");
?>
