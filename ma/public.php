<?php
require("cab.php");
echo '<h3>Cited</h3>';

//require("cited_menu.php");

require("../_class/_class_cited.php");
$cited = new cited;

echo $cited->resumo();

$menu = array();
array_push($menu,array('Módulo público','Exportar para módulo público','export_public.php'));
require($include.'sisdoc_menus.php');

echo menus($menu,3);

require("../foot.php");
?>
