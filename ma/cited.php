<?php
require("cab.php");
echo '<h3>Cited</h3>';

require("cited_menu.php");

require("../_class/_class_cited.php");
$cited = new cited;

echo $cited->resumo();

echo '</table>';

require("../foot.php");
?>
