<?php
require("cab.php");
require("../_class/_class_bris.php");
$br = new bris;

require($include.'_class_form.php');
$form = new form;

$cp = array();
array_push($cp,array('$H8','','',False,False));
array_push($cp,array('$O 2013:2013&2012:2012','','Ano',True,True));
array_push($cp,array('$O S:Revistas Indexados na Brapci&O:Outras publicações não indexadas&T:Todas as revistas&A:Todas as citações','','Tipo',True,True));
array_push($cp,array('$O 2:Dois anos&3:Três ano&5:Cinco anos&100:Todos os anos','','Anos de análise',True,True));
$tela = $form->editar($cp,'');

if ($form->saved > 0)
	{
		$ano = $dd[1];
		$tipo = $dd[2];
		$anos = $dd[3];
		if (strlen($dd[1]) > 0) { $ano = $dd[1]; }
		echo $br->journal_cited_by_year($ano,$tipo,$anos);
		
		echo $br->journal_cited_by_years($ano,$tipo,$anos);
	} else {
		echo $tela;
	}



?>
