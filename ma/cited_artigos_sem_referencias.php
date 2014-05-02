<?php
require("cab.php");

require("../include/_class_menus.php");
require("../_class/_class_resume.php");
$res = new resume;

require("../_class/_class_cited.php");
$cited = new cited;

require("main_menu.php");
//echo $res->resume();

echo '<H1>'.msg('artigos_sem_referencias').'</h1>';

if (strlen($dd[1])==0) { $dd[1]= (date("Y")-2); }
echo $cited->article_without_ref(0,0,$dd[1]);
echo '<HR>';

require("../foot.php");
?>
