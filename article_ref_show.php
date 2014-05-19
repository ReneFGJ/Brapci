<?php
require("db.php");
require("_class/_class_article.php");
$art = new article;
$art->le($dd[0]);

require("_class/_class_referencia.php");
$ref = new referencia;

$ref->show_ref($art,$dd[1]);

?>
