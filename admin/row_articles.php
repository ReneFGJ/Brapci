<?
require ("db.php");
require ("_class/_class_header.php");
$hd = new header;
echo $hd -> head();

require ('../_class/_class_oauth_v1.php');
$user = new oauth;
$user -> token();

require ("../_class/_class_message.php");

/* header */
require("../_class/_class_journals.php");
$jnl = new journals;

require("../_class/_class_issue.php");
$is = new issue;

require("../_class/_class_article.php");
$ar = new article;

require("../_class/_class_referencia.php");

echo $is->issue_legend($dd[0]);
/* Link new issue */
$journal = $is->journal;
$sx = '<A HREF="article_ed.php?dd1='.$journal.'&dd2='.$dd[0].'" target="frame_articles">'.msg('new_issue').'</A>';
echo $sx;

echo $ar->article_issue($dd[0]);
?>
