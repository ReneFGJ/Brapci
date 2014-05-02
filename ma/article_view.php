<?php
require("cab.php");

require("../_class/_class_issue.php");

require("../_class/_class_author.php");

require("../_class/_class_publications.php");
$res = new publications;

require('../include/sisdoc_windows.php');

$art = $dd[0];
$editar = 1;
$res->article_id = $art;
echo $res->show_article($art);

echo $res->show_files($art);
echo $res->show_references($art);


/* Acoes */
echo $res->acoes();

require("../foot.php");
?>
