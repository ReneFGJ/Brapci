<?php
require("cab.php");
require('../include/sisdoc_windows.php');

require("../_class/_class_referencia.php");
$ref = new referencia;

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

echo ($ref->exportar_ref($art->line));
echo $art->report_a_bug($dd[0]);

echo ($art->article_arquivos());

echo ($art->article_referencias());

echo $art->seek_google();

require("foot.php");
?>