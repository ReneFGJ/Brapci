<?php
require("cab.php");
$include = '../include/';
require($include."_class_menus.php");
require("../_class/_class_resume.php");
$res = new resume;

require("main_menu.php");
//echo $res->resume();

echo '<H1>'.msg('citacoes').'</h1>';

require("../foot.php");
?>
