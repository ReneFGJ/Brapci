<?php
require("cab.php");
require($include.'sisdoc_windows.php');

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

require ("../_class/_class_publications.php");
require ("../_class/_class_progressive_bar.php");
$res = new publications;

$artx = $dd[0];
$editar = 1;
$res -> article_id = $artx;
$res -> le($artx);

/* Progress Bar */
$status = trim($res -> line['ar_status']);

/* Le dados do artigo */

$art->le($dd[0]);

$journals->le($art->journal_id);
$issue = $art->line['ar_edition'];

//print_r($art);
$cr = chr(13).chr(10);
/* Dados */
$autor = $art->autores_row;
$edicao_data = $art->ed_data_cadastro;
$edicao_data = substr(edicao_data,0,4).'-'.substr(edicao_data,4,2).'-'.substr(edicao_data,5,2);
$titulo 	= $art->line['ar_titulo_1'];
$titulo_alt = $art->line['ar_titulo_2'];
$journal_title = $art->line['jnl_nome'];
//$issue = $art->line['ed_nr'];
$issue_name = $edi->issue_legend($issue);
$vol = $art->line['ed_vol'];
$page = trim($art->pagf);
if (strlen($page) > 0) { $page = '-'.$page;}
$page = trim($art->pagi).$page;

			

echo '<table width="100%"><TR><TD>';
echo ($issue_name);
echo ($art->mostra());

echo ($ref->exportar_ref($art->line));
echo $art->report_a_bug($dd[0]);

echo ($art->article_arquivos());

echo ($art->article_referencias());

//$journals->le($art->journal_id);
//$sx = $journals->journals_mostra();


//$sa = ($autor->publicoes_dos_autores($dd[0]));
if (strlen($sa) > 0)
	{
		echo '<BR><h3>'.msg('otherts_bibliographics').'</h3><BR>';
		echo '<div class="resumo">';
		echo $sa;
		echo '</div>';
	}

echo $art->seek_google();
echo '</table>';

echo $res -> show_files($art);
echo $res -> upload_files($art);

echo $res -> show_references($art);
?>