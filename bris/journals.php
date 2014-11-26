<?php
require("cab.php");
require("../_class/_class_referencia.php");
require("../_class/_class_journals.php");
require("../_class/_class_article.php");
$art = new article;
$journals = new journals;

$sx = $journals->list_journals('J','');
echo $sx;

$sx = $journals->list_journals('A','');
echo $sx;

$sx = $journals->list_journals('T','');
echo $sx;



?>