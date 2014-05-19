<?php
require("cab.php");
$include = '../include/';
require($include."_class_menus.php");
require("../_class/_class_resume.php");
$res = new resume;

require("main_menu.php");
//echo $res->resume();

echo '<h1>DIR!S</h1>';
/* MENU */
$menu = array();
array_push($menu,array('Autores','Diretório de autores','diris_authors.php'));
array_push($menu,array('Autores','Diretório de pesquisadores (pós-graduação)','diris_authors_pos.php'));

array_push($menu,array('Programas Pós-Graduação','Programas Pós-Graduação',''));

menus($menu,'3');

require("../foot.php");
?>
