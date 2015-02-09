<?
require ("db.php");
require ("_class/_class_header.php");
$hd = new header;
echo $hd -> head();

require ('../_class/_class_oauth_v1.php');
$user = new oauth;
$user -> token();

require ("../_class/_class_message.php");

require("../include/sisdoc_debug.php");

require('../_class/_class_article.php');
require($include.'_class_form.php');
$form = new form;

$clx = new article;
$tabela = $clx->tabela;
$cp = $clx->cp_new();

$tela = $form->editar($cp,$tabela);

if ($form->saved > 0)
	{
		$clx->updatex();
		echo 'saved';
		redireciona('row_articles.php?dd0='.$dd[2].'&dd90='.checkpost($dd[2]));
	} else {
		echo $tela;
	}
?>
