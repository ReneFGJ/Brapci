<?php
require("cab.php");

$menu = array();
array_push($menu,array('Autores','Lista de autores','autores_row.php'));
require($include.'sisdoc_menus.php');

echo menus($menu,3);
?>
