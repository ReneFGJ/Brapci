<?php
require("cab.php");

/* header */
require("../_class/_class_journals.php");
$jnl = new journals;

require("../_class/_class_issue.php");
$is = new issue;

require("../_class/_class_article.php");
$ar = new article;

require("../_class/_class_referencia.php");

echo $is->issue_legend($dd[0]);

echo $ar->article_issue($dd[0]);
require("foot.php");
?>
