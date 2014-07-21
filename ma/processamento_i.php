<?php
require("cab.php");

$menu = array();
array_push($menu,array('Processamento I','Sem título','article_sem_titulo.php'));
array_push($menu,array('Processamento I','Títulos duplicados','article_double.php'));
require($include.'sisdoc_menus.php');

echo menus($menu,3);
?>
