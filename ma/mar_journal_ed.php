<?php
require("cab.php");
require('../_class/_class_cited.php');
$ct = new cited;

$cp = $ct->cp_mar_journal();
$tabela = $ct->tabela_journal;
echo '===>'.$tabela;

require($include.'_class_form.php');
$form = new form;

$tela = $form->editar($cp,$tabela);

if ($form->saved > 0)
	{
		redirecina('mar_journal.php');
	} else {
		echo $tela;
	}



?>
