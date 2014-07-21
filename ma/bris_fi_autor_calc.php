<?php
require("cab.php");
echo '<h3>iCPA</h3>';
echo '<font class="lt1">Fator de Impacto do autor</font>';

require("../_class/_class_article.php");
$ar = new article;

require("../_class/_class_bris.php");
$bris = new bris;

require($include.'_class_form.php');
$form = new form;

$cp = array();
array_push($cp,array('$H8','','',False,True));
array_push($cp,array('$[1972-'.date("Y").']D','','Ano de cálculo',True,True));

$tela = $form->editar($cp,'');

if ($form->saved > 0)
	{
		$bris->ranking_author_create($dd[1]);
		echo 'Gerando dados';
	} else {
		echo $tela;		
	}



require("../foot.php");
?>
