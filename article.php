<?php
require("cab.php");
require('include/sisdoc_windows.php');

require("_class/_class_referencia.php");
$ref = new referencia;

/* Classe do journal */
require("_class/_class_journals.php");
$journals = new journals;

/* Classe do autor */
require("_class/_class_author.php");
$autor = new author;

/* Classe do artigo */
require("_class/_class_article.php");
$art = new article;

/* Classe da Edição */
require("_class/_class_issue.php");
$edi = new issue;


/* Le dados do artigo */
$art->le($dd[0]);
$journals->le($art->journal_id);
$issue = $art->issue;

//print_r($art);
$cr = chr(13).chr(10);
/* Dados */
$autor = $art->autores_row;
$edicao_data = $art->ed_data_cadastro;
$edicao_data = substr(edicao_data,0,4).'-'.substr(edicao_data,4,2).'-'.substr(edicao_data,5,2);
$titulo 	= $art->line['ar_titulo_1'];
$titulo_alt = $art->line['ar_titulo_2'];
$journal_title = $art->line['jnl_nome'];
$issue = $art->line['ed_nr'];
$vol = $art->line['ed_vol'];
$page = trim($art->pagf);
if (strlen($page) > 0) { $page = '-'.$page;}
$page = trim($art->pagi).$page;

/* Medatados */
$sx .= '<meta>'.$cr;
/* Autores */
for ($r=0;$r < count($autor);$r++)
	{
		$sx .= '	<meta name="DC.Creator.PersonalName" content="'.$autor[$r].'"/>'.$cr;
		$sx .= '	<meta name="citation_author" content="'.$autor[$r].'"/>'.$cr;
	}
$sx .= '	<meta name="DC.Date.created" scheme="ISO8601" content="'.$edicao_data.'"/>'.$cr;
$sx .= '	<meta name="DC.Date.dateSubmitted" scheme="ISO8601" content="'.$edicao_data.'"/>'.$cr;
$sx .= '	<meta name="DC.Date.issued" scheme="ISO8601" content="'.$edicao_data.'"/>'.$cr;
$sx .= '	<meta name="DC.Date.modified" scheme="ISO8601" content="'.$edicao_data.'"/>'.$cr;
$sx .= '	<meta name="DC.Title" content="'.$titulo.'"/>'.$cr;
$sx .= '	<meta name="DC.Title.Alternative" xml:lang="en" content="'.$titulo_alt.'"/>'.$cr;

/* Palavras-chave */
$key = $art->keyword_array;
for ($r=0;$r < count($key);$r++)
	{
		$sx .= '	<meta name="DC.Subject" content="'.$key[$r].'"/>'.$cr;
		$sx .= '	<meta name="citation_keywords" xml:lang="pt" content="'.$key[$r].'"/>'.$cr;
	}

$sx .= '
	<meta name="DC.Description" xml:lang="en" content="There is much interest in building ontologies, however, there is little work and use of ontologies in large-scale. Some reasons for this are time, cost and resources associated with the development. Aiming to reduce these efforts, ontology learning methods have been developed. Therefore, it is necessary to automate the process of acquiring knowledge and different approaches have been suggested. The use of texts as a source of knowledge acquisition appears to be a correct path, since language is the first transfer of knowledge between human beings, besides having a diversity of digital documents available. To support semi-automatic construction of ontologies from texts, several tools were developed, each one using different techniques and methods. Due to the importance of using these tools, this paper aims to present the tools available, and their main features and usability."/>
	<meta name="DC.Description" xml:lang="pt" content="Há muito interesse na construção de ontologias, porém, há pouco trabalho desenvolvido e pouca utilização de ontologias em larga escala. Algumas das razões para isso são o tempo, custo e recursos associados neste desenvolvimento. Com o objetivo de reduzir estes esforços, métodos de aprendizagem de ontologias têm sido desenvolvidos. Para isso, é necessária a automatização do processo de aquisição de conhecimento e diversas abordagens têm sido sugeridas. A utilização de textos como fonte de aquisição de conhecimento parece ser um caminho correto, visto que a linguagem é a primeira forma de transferência de conhecimento entre os seres humanos, além de haver uma diversidade de documentos digitais disponíveis. Para dar suporte a construção semi automática de ontologias a partir de textos, várias ferramentas foram desenvolvidas, cada uma utilizando técnicas e métodos diferentes. Devido à importância do uso destas ferramentas, este artigo tem como objetivo apresentar as ferramentas disponíveis, assim como suas características principais e usabilidade."/>
	<meta name="DC.Format" scheme="IMT" content="application/pdf"/>
	<meta name="DC.Identifier" content="1150"/>
	<meta name="DC.Identifier.pageNumber" content="3-21"/>
	<meta name="DC.Identifier.URI" content="http://www.brapci.inf.br/article.php?dd0='.$dd[0].'"/>
	<meta name="DC.Language" scheme="ISO639-1" content="pt"/>
	<meta name="DC.Rights" content="Direitos Reservados ao autor"/>
	<meta name="DC.Source" content="'.$journal_title.'"/>
	<meta name="DC.Source.ISSN" content="'.$issn.'"/>
	<meta name="DC.Source.Issue" content="'.$issue.'"/>	
	<meta name="DC.Source.URI" content="http://www.brapci.inf.br/article.php?dd0='.$dd[0].'"/>
	<meta name="DC.Source.Volume" content="'.$vol.'"/>						
	<meta name="DC.Type" content="Text.Serial.Journal"/>
	<meta name="DC.Type.articleType" content="Artigos"/>
	<meta name="gs_meta_revision" content="1.1" />
	<meta name="citation_journal_title" content="'.$journal_title.'"/>
	<meta name="citation_issn" content="'.$issn.'"/>
	<meta name="citation_title" content="Ferramentas para aprendizagem de ontologias a partir de textos"/>

	<meta name="citation_date" content="2014/03/31"/>

	<meta name="citation_volume" content="'.$vol.'"/>
	<meta name="citation_issue" content="'.$issue.'"/>

	<meta name="citation_firstpage" content="'.$page.'"/>
	<meta name="citation_abstract_html_url" content="http://www.brapci.inf.br/article.php?dd0='.$dd[0].'"/>
	<meta name="citation_language" content="pt"/>
	<meta name="citation_pdf_url" content="http://www.brapci.inf.br/article.php?dd0='.$dd[0].'"/>';
$sx .= '<meta>';									
echo $sx;			

echo ($edi->issue_legend($issue));
echo '<BR>';
echo ($art->mostra());

echo ($ref->exportar_ref($art->line));
echo $art->report_a_bug($dd[0]);

echo ($art->article_arquivos());

echo ($art->article_referencias());

//$journals->le($art->journal_id);
//$sx = $journals->journals_mostra();


$sa = ($autor->publicoes_dos_autores($dd[0]));
if (strlen($sa) > 0)
	{
		echo '<BR><h3>'.msg('otherts_bibliographics').'</h3><BR>';
		echo '<div class="resumo">';
		echo $sa;
		echo '</div>';
	}

echo $art->seek_google();

require("foot.php");
?>