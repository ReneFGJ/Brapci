<?php
require("cab.php");
echo '<h3>Cited</h3>';

//require("cited_menu.php");

require("../_class/_class_cited.php");
$cited = new cited;

echo $cited->resumo();

$menu = array();
array_push($menu,array('M�dulo p�blico','Exportar para m�dulo p�blico','export_public.php'));
require($include.'sisdoc_menus.php');

echo menus($menu,3);

require("../foot.php");
?>
