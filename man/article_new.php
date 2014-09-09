<?php
require("cab.php");
require($include.'sisdoc_form2.php');
require($include.'cp2_gravar.php');
require($include.'sisdoc_colunas.php');
require("../_class/_class_article.php");
$clx = new article;

require("../_class/_class_issue.php");
$iss = new issue;

$issue = trim($dd[2]);
echo $iss->issue_legend($issue);

$cp = $clx->cp_new();
$tabela = $clx->tabela;

editar();

if ($saved > 0)
	{
		$clx->updatex();
		redirecina('publication_issue.php?dd0='.$dd[2]);		
	}

require("../foot.php");
?>
