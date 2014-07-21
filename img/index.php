<?php
require("cab.php");
require("_class/_class_ajax.php");
require("_class/_class_search.php");
$sr = new search;

echo $sr->busca_form();

require("foot.php");
?>
