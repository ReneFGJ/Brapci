<?php
require("cab.php");
$include = '../include/';
require($include."_class_menus.php");
require("../_class/_class_resume.php");
$res = new resume;

require("../db_diris.php");
require("../_class/_class_diris.php");
$diris = new diris;

echo '<h1>DIR!S</h1>';
echo '<h3>Lista de Pesquisadores</h3>';

echo $diris->lista_professores_pos('bra');

require("../foot.php");
?>
