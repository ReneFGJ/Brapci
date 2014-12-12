<?php
require("cab.php");

/*
 * $breadcrumbs
 */
$breadcrumbs = array();
array_push($breadcrumbs,array(http.'admin/index.php',msg('principal')));
array_push($breadcrumbs,array(http.'admin/index.php',msg('menu')));
echo '<div id="breadcrumbs">'.breadcrumbs().'</div>';

require($include.'_class_form.php');
$form = new form;

$cp = array();
array_push($cp,array('$S7','','Informe o protocolo',True,True));

$tela = $form->editar($cp,'');

if ($form->saved > 0)
	{
		echo 'Saved';
		redirecina('../pibic/gerar_termo_bolsa_pdf.php?dd99='.$dd[0]);
	} else {
		echo $tela;
	}

require("../foot.php");	
?>