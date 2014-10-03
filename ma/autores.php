<?php
require("cab.php");

$menu = array();
array_push($menu,array('Autores','Lista de autores','autores_row.php'));
array_push($menu,array('Autores','Atualiza remissivas','autores_autoridades_remissavas.php'));

require($include.'sisdoc_menus.php');

echo menus($menu,3);
?>
