<?php
$newURL = 'http://basessibi.c3sl.ufpr.br/brapci/index.php/enancib17/';
header('Location: '.$newURL);

exit;
require("cab.php");

require("../_class/_class_article.php");
$ar = new article;

require("../_class/_class_referencia.php");


/* Classe da Edi��o */
require("../_class/_class_issue.php");
$edi = new issue;


/* Le dados do artigo */
$issue = 1017;
echo '<H1>Anais do Evento</h1>';
echo ($edi->issue_legend($issue));
echo $ar->article_issue($issue);

require("foot.php");
?>

