<?
require("cab.php");
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
		redirecina('publications_details.php?dd0='.$dd[2]);
	} else {
		echo $tela;
	}
require("../foot.php");
?>
