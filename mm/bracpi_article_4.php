<?
$sx .= '<B>P�g. ';
if (strlen($pgini) == 0)
	{ $sx .= '<font color="red">p�gina inv�lida</font>';} else
	{ $sx .= $pgini.' - '.$pgfim; }
$sx .= '</B>';
if ($ed_editar == 1) 
	{ 
	$linke = 'onclick="newxy2('.chr(39).'brapci_pagina_ed.php?dd0='.$ida.chr(39).',240,350);"'; 
	$sa1 = '<img src="img/icone_editar.gif" width="20" height="19" alt="Editar p�gina" border="0" '.$linke.' >';
	$sx .= tips($sa1,'Alterar numera��o de p�gina');
	}
?>