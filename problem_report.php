<?php
require("db.php");
require($include.'sisdoc_debug.php');
require("_class/_class_header_bp.php");
$hd = new header;

require($include.'_class_form.php');
$form = new form;
$form->class_radio = 'radio_form_big';


echo $hd->head();
echo $hd->cab_popup();

$cp = array();

$op .= '&1:Modificação no título, resumo, palavras-chave (Original)<BR>'.chr(13).chr(10);
$op .= '&2:Modificação no título, resumo, palavras-chave (Alternativo)<BR>'.chr(13).chr(10);
$op .= '&3:Referência incorreta<BR>'.chr(13).chr(10);
$op .= '&4:PDF incorreto<BR>'.chr(13).chr(10);
$op .= '&5:Nome dos autores incorreto<BR>'.chr(13).chr(10);


array_push($cp,array('$H','','',False,True));
array_push($cp,array('$A','','Informe o erro',False,True));
array_push($cp,array('$R '.$op,'','Tipo do erro',True,True));
array_push($cp,array('$B8','','Continuar >>>',False,True));
$tela = $form->editar($cp,'');

if ($form->saved > 0)
	{
		$erro = round($dd[2]);
		if ($erro > 0 and $erro < 2)
			{ redirecina('problem_report_'.$erro.'.php?dd0='.$dd[0]); }
	} else {
		echo $tela;		
	}

?>
