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

require('../_class/_class_publications.php');
require($include.'_class_form.php');
$form = new form;
$pb = new publications;

$jnl = trim($dd[2]);

require("../_class/_class_issue.php");
$clx = new issue;
$tabela = $clx->tabela;
$cp = $clx->cp();

$tela = $form->editar($cp,$tabela);

if ($form->saved > 0)
	{
		$clx->updatex();
		echo 'saved';
	} else {
		echo $tela;
	}
?>
