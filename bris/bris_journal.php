<?php
require("cab.php");
require("_class/_class_bris.php");
$br = new bris;

echo '<h1>Journal Ranking</h1>';

echo $br->journal_ranking();

?>
