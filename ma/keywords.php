<?php
require("cab.php");

$menu = array();
array_push($menu,array('Palavras-Chave','Lista de descritores','keywords_row.php'));
require($include.'sisdoc_menus.php');

echo menus($menu,3);
?>
