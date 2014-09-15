<?php
require("cab.php");
require("../_class/_class_referencia.php");
require("../_class/_class_journals.php");
require("../_class/_class_article.php");
$art = new article;
$journals = new journals;

$sr = $art->article_issue($dd[0]);

$journals->le($art->journal_id);
$sx = $journals->journals_mostra();

echo $sx;
echo '<h3>'.msg('issue_articles').'</h2>';
echo $sr;

?>