<?
global $tempo_consulta;

function display_paginas($total,$mostra,$tipo)
	{
	global $tempo_consulta;
	global $dd;
	$st = '';
	for ($ka=0;$ka <= 99;$ka++)
		{
		if ((strlen($dd[$ka]) > 0) and ($ka != 50))
			{
			if (strlen($st) > 0) { $st .= '&'; }
			$st .= 'dd'.$ka.'='.$dd[$ka]; 
			}
		}
	if (strlen($tipo) == 0) {$tipo = '1'; }
	$pag = 0;
	//////////////////////// Se oFFset zerado inserir página 1
	if (strlen($dd[50]) == 0)
		{ $dd[50] = "1"; }
		
	if ($total > $mostra)
		{
		if ($tipo == '1')
			{
			$sr = '<table border="0"><TR>';
			$sr .= '<TR><TD>Páginas&nbsp;</TD>';
				for ($ka=1;$ka <= $total;$ka = $ka + $mostra)
					{	
						$pag++;
						
						if ($ka != $dd[50])
							{
								$sr .= '<TD><div id="page_div">';
								$sr .= '<A HREF="'.$_SERVER['SCRIPT_NAME'].'?'.$st.'&dd50='.$ka.'">';
							} else {
								$sr .= '<TD><div id="page_sel">';
								$sr .= '<B>';
							}
						$sr .= $pag;
						$sr .= '</A>';
						$sr .= '</div>';
					}
			$inim = $dd[50];
			$inif = $dd[50]+$mostra-1;
			if ($inif > $total) { $inif = $total; }
			$sr .= '<TR><TD class="lt0" colspan="20"><nobr>resultado '.$inim.' à '.$inif.' de '.$total;
			if (strlen($tempo_consulta) > 0) { $sr .= $tempo_consulta; }
			$sr .= '</TD>';
			$sr .= '</table>';
			$sr .= '<BR>';
			}
		}
	return($sr);
	}
?>
<style>
#page_div {
	width : 20px;
	border : 1px;
	color : Black;
	background-color : #FFFFE0;
	border-style : dotted;
	border-color : #FF4500;
	padding-bottom : 2px;
	padding-left : 2px;
	padding-right : 2px;
	padding-top : 2px;
	text-align : center;
	height : 20px;
	text-decoration : none;
}

#page_sel {
	width : 20px;
	border : 1px;
	color : Black;
	background-color : #c9c6ea;
	border-style : dotted;
	border-color : #FF4500;
	padding-bottom : 2px;
	padding-left : 2px;
	padding-right : 2px;
	padding-top : 2px;
	text-align : center;
	height : 20px;
	text-decoration : none;
}

#page_div a { text-decoration : none; }

#page_div :hover {
	background-color : #ccffcc;
}
</style>