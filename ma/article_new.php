<?php
require("cab.php");
require($include.'_class_form.php');
$form = new form;

require("../_class/_class_article.php");
$clx = new article;

require("../_class/_class_issue.php");
$iss = new issue;

$issue = trim($dd[2]);
echo $iss->issue_legend($issue);

$cp = $clx->cp_new();
$tabela = $clx->tabela;

$tela = $form->editar($cp,$tabela);

if ($form->saved > 0)
	{
		$clx->updatex();
		redirecina('publication_issue.php?dd0='.$dd[2]);		
	} else {
		echo $tela;
	}

require("../foot.php");
?>
