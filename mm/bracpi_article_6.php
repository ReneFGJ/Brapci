<?
$sx .= '<B>Observação ';

if (strlen($obs) == 0)
	{ $sx .= '<font color="orange">Sem observação</font>';} else
	{ $sx .= msg_alert($obs.' <BR><font class="lt0">em :'.stodbr($obsdt).'</font>'); }
$sx .= '</B>';
//if ($ed_editar == 1) 
	{ 
	$linke = 'onclick="newxy2('.chr(39).'brapci_obs_ed.php?dd0='.$ida.chr(39).',650,350);"'; 
	$sa1 = '<img src="img/icone_editar.gif" width="20" height="19" alt="Editar página" border="0" '.$linke.' >';
	$sx .= tips($sa1,'Alterar numeração de página');
	}
?>