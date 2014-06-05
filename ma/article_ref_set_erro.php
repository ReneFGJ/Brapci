<?php
$xcab = '1';
$popup = 1;
require("cab.php");

require($include.'_class_form.php');
$form = new form;

require('../_class/_class_cited.php');
$cited = new cited;

$acao = "gravar";

$cp = array();
array_push($cp,array('$H8','id_m','',False,True));
array_push($cp,array('$HV','m_status','Z',True,True));
array_push($cp,array('$B8','','Enviar para erro',False,True));

$tela = $form->editar($cp,$cited->tabela);

if ($form->saved > 0)
	{
		require("../close.php");	
	} else {
		echo $tela;
	}

?>
