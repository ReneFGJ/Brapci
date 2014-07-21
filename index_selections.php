<?php
require("cab.php");
require($include.'sisdoc_data.php');
require("_class/_class_search.php");
$sh = new search;

echo $sh->selections();

?>
