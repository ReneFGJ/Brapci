<?php
require("cab.php");
require("../_class/_class_bris.php");
$br = new bris;

echo '<h1>Journal Ranking</h1>';
require($include.'_class_form.php');
$form = new form;
$cp = array();
array_push($cp,array('$H8','','',False,False));
array_push($cp,array('$[2012-2013]D','','Ranking',True,True));
$tela = $form->editar($cp,'');

if ($form->saved > 0)
	{
		$ano = $dd[1];
		echo $br->journal_ranking($ano);		
	} else {
		echo $tela;
	}
?>
