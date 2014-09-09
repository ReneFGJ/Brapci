<?php
$xcab = '1';
require("cab.php");

echo '1';
require($include.'_class_form.php');
$form = new form;

require('../_class/_class_cited.php');
$cited = new cited;

$cp = $cited->cp();
$tela = $form->editar($cp,$cited->tabela);

if (strlen($dd[0]) > 0)
	{
		$link = '<A HREF="'.page().'?dd0='.$dd[0].'&dd90=DEL" class="link">CANCELAR</A>';
		echo $link;
		
		if ($dd[90]=='DEL')
			{
				$id = $dd[0];
				$cited->ref_save_year($id,'','X');
				require("../close.php");
			}
	}

if ($form->saved > 0)
	{
		require("../close.php");	
	} else {
		echo $tela;
	}

?>
