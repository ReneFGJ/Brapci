<?php
class posicao
	{
		function show($pos,$total,$desc=array(),$pages=array())
			{
				global $dd;
				$sx .= '<link rel="stylesheet" href="'.http.'css/style_cabecalho_passos.css">'.chr(13);
				$sx .= '
				<table id="cabecalho-localizador" border=0 cellpadding=10 cellspacing=0 >
				<tr>
				';
				for ($r=1;$r <= $total;$r++ )
				{
					if (strlen($pages[$r])==0) { $page = page(); }
						else { $page = $pages[$r]; }
					$class3 = "passo-concluido";
					$class2 = "passo-atual";
					$class1 = "passo-fazer";
					if ($r < $pos) { $class = $class3; }
					if ($r == $pos) { $class = $class2; }
					if ($r > $pos) { $class = $class1; }
					$link = '<A HREF="'.$page.'?dd0='.$dd[0].'&dd90='.$dd[90].'&dd99='.$dd[99].'&pag='.$r.'" class="passo-numero">';
					$sx .= '<td width="65" align="center" height="65" class="'.$class.'">
					'.$link.'<span class="passo-numero">'.$r.'</span></A>
					</td>
					';
				}
				$sx .= '</table>'.chr(13);
			return($sx);
			}
	}
?>