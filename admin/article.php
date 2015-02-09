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

require($include.'_class_form.php');
$form = new form;

$issue = $ar->issue;
require("../_class/_class_referencia.php");

echo $is->issue_legend($issue);



echo '<table border=1 id="article_info" >';
echo '<TR valign="top">';
echo '<TD width="60%" id="article_data">';

echo '<table border=0 class="tabela00"><TR><TD width="60%">';
/* Title */
echo '<div id="ab0" class="article_title">'.$ar->title.'</div>';
echo '<div><iframe id="abs0" width="100%" height="300px" 
			src="article_abstract.php?dd0='.$dd[0].'&dd1=ABS0"
			class="border0 padding0"
			style="display: none;">
			</iframe></div>';
/* View PDF File */
echo '<TD rowspan=10 width="40%" id="article_pdf">';
//echo '<div id="article_pdf">PDF';
echo $ar -> show_pdf();

echo '<TR><TD id="ab0a" class="article_title_alt">'.$ar->title_alt.'</td></tr>';

/* Authors */
echo '<TR><TD id="article_author_main">
			'.$ar->autores().'
			</td></TR>';

/* Abstract */
echo '<TR valign="top"><TD>';
echo '<div class="article_abs" id="ab1">'.$ar->show_abstract(1).'</div>';
echo '<div><iframe id="abs1" width="100%" height="300px" 
			src="article_abstract.php?dd0='.$dd[0].'&dd1=ABS1"
			style="display: none;">
			</iframe></div>';
/* Abstract II */
echo '<TR valign="top"><TD>';
echo '<div class="article_abs" id="ab2">'.$ar->show_abstract(2).'</div>';
echo '<div><iframe id="abs2" width="100%" height="300px" 
			src="article_abstract.php?dd0='.$dd[0].'&dd1=ABS2"
			style="display: none;"
			>
			</iframe></div>';
			
/* Links */
echo '<TR valign="top"><TD>';
require ("../_class/_class_publications.php");
$res = new publications;
$art = strzero($dd[0],10);
//$res -> article_id = $art;
//$res -> le($art);
echo $res -> show_files($art);
echo $res -> upload_files($art);
echo $res -> novo_link();

//http://revista.ibict.br/liinc/index.php/liinc/article/viewFile/727/504
echo '</TD></TR>';
echo '</table>';

echo '
<script>
	$("#ab0").click(function() { $("#abs0").fadeToggle(); });
	$("#ab1").click(function() { $("#abs1").fadeToggle(); });
	$("#ab2").click(function() { $("#abs2").fadeToggle(); });
</script>
';


//echo '</div>';
echo '</td></tr>';
echo '</table>';

echo $form->ajax('article_links',$id);

echo $ar->actions();

echo '</div>';
require("foot.php");

function msg($sx) { return($sx); }
?>
