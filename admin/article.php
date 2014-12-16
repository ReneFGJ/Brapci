<?php
require("cab.php");

/* header */
require("../_class/_class_journals.php");
$jnl = new journals;

require("../_class/_class_issue.php");
$is = new issue;

require("../_class/_class_article.php");
$ar = new article;
$ar->le($dd[0]);
$id = $dd[0];

require('../'.$include.'_class_form.php');
$form = new form;

$issue = $ar->issue;
require("../_class/_class_referencia.php");

echo $is->issue_legend($issue);



echo '<table border=1 id="article_info">';
echo '<TR valign="top">';
echo '<TD width="60%">';
echo '<div id="article_data">';

echo '<div id="article_title_main" class="lt3">'.$ar->title.'</div>';
echo '<div id="article_issue_main">'.$ar->title.'</div>';
echo '<BR>';
echo '<div><B>Autores</B><div id="article_author_main">Loading....</div></div>';
echo '<BR>';
echo '<div><B>RESUMO</B><div id="article_abstract_1_main" class="justify">Loading....</div></div>';
echo '<div><B>Palavras-chave</B><div id="article_keyword_1_main" class="justify">Loading....</div></div>';
echo '<BR>';
echo '<div><B>ABSTRACT</B><div id="article_abstract_2_main" class="justify">Loading....</div></div>';
echo '<div><B>KEYWORD</B><div id="article_keyword_2_main" class="justify">Loading....</div></div>';
echo '<BR><BR><BR><BR>';
echo '<TD width="40%">';
echo '<div id="article_pdf">PDF';
echo '</div>';
echo '</td></tr>';
echo '</table>';
echo $form->ajax('article_issue',$id);
echo $form->ajax('article_author',$id);
echo $form->ajax('article_title',$id);
echo $form->ajax('article_abstract_1',$id);
echo $form->ajax('article_keyword_1',$id);
echo $form->ajax('article_abstract_2',$id);
echo $form->ajax('article_keyword_2',$id);

echo $form->ajax('article_links',$id);

echo $ar->actions();

echo '</div>';
require("foot.php");
?>
