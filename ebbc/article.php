<?php
require("cab.php");
require("../_class/_class_referencia.php");

/* Classe do journal */
require("../_class/_class_journals.php");
$journals = new journals;

/* Classe do autor */
require("../_class/_class_author.php");
$autor = new author;

/* Classe do artigo */
require("../_class/_class_article.php");
$art = new article;

/* Classe da Edição */
require("../_class/_class_issue.php");
$edi = new issue;


/* Le dados do artigo */
$art->le($dd[0]);
$journals->le($art->journal_id);
$issue = $art->issue;

echo ($edi->issue_legend($issue));
echo '<BR>';
echo ($art->mostra());

echo ($art->article_arquivos());

echo ($art->article_referencias());

echo $art->seek_google();

require("foot.php");
?>