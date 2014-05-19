<?php
require("db.php");
require($include.'sisdoc_debug.php');
require("_class/_class_header_bp.php");
$hd = new header;

require('_class/_class_ticket.php');
$tk = new ticket;

require($include.'_class_form.php');
$form = new form;
$form->class_radio = 'radio_form_big';


echo $hd->head();
echo $hd->cab_popup();

$cp = array();

array_push($cp,array('$H8','','',False,True));
array_push($cp,array('$A','','Informe os Campos incorretos',False,True));
array_push($cp,array('$T60:3','','Título Correto (Opcional)',False,True));
array_push($cp,array('$T60:8','','Resumo Correto (Opcional)',False,True));
array_push($cp,array('$T60:2','','Palavras-Chave (Opcional)',False,True));
array_push($cp,array('$B8','','Finalizar >>>',False,True));
$tela = $form->editar($cp,'');
$article = strzero($dd[0],10);
if (($form->saved > 0) and (round($article) > 0))
	{
		if (strlen($dd[2].$dd[3].$dd[4]) > 0)
			{
				$tk->abre_ticket($article,'1',$dd[2],$dd[3],$dd[4],$user);
				redirecina('problem_report_thank.php?dd0='.$dd[0]);
				exit;
			} else {
				echo $tela;
			}
	} else {
		echo $tela;		
	}

?>
