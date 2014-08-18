<?php
class graphic
	{
		var $scale = 5;
		function gr_bolinhas($dados,$title='',$texto='')
			{
				$bmax = 10;
				$site = 'http://www2.pucpr.br/reol/';
				$img = array('infografico_gr1_blue.png','infografico_gr1_green.png',
							'infografico_gr1_orange.png','infografico_gr1_red.png',
							'infografico_gr1_yellow.png','infografico_gr1_green2.png'
							);
				$cor = array("blue",'green','orange','red','#C0C000');
				$sx .= '<table BORDER=0 class="lt1" width="230">';
				if (strlen($title.$texto) > 0)
					{
					$sx .= '<TR><TD colspan=3>';
					if (strlen($title) > 0) { $sx .= '<font class="lt2"><B>'.$title.'</B></font><BR>'; }
					if (strlen($texto) > 0) { $sx .= '<font class="lt0">'.$texto.'</font><BR>&nbsp;'; }
					}
				
				for ($r=0;$r < count($dados);$r++)
					{
						$max = 0;
						$font = '<font color="'.$cor[$r].'">';
						$sx .= '<TR valign="middle">';
						$sx .= '<TD valign="top" align="right"><font style="font-size: 8px;">';
						$sx .= $font.'<font style="font-size: 8px;"><B>'.$dados[$r][0].'</B></font><BR>';						
						$sx .= '<TD>';
						$vlr = ($dados[$r][1] / $this->scale);
						if ($vlr != round($vlr)) {$vlr = round($vlr+1); }
						for ($y=1;$y < $vlr;$y++)
							{
								if (($max > 0) and (round($max/$bmax) == ($max/$bmax))) { $sx .= '<BR>'; $max = 0; }
								$sx .= '<img src="'.$site.'/img/'.$img[($r)].'" title="'.$dados[$r][0].'" style="border: 1px solid #FFFFFF;">';
								$max++;
							}
						
						$sx .= '<TD><nobr>';
						$sx .= $font.'} '.$dados[$r][1];
					}
				$sx .= '</table>';
				return($sx);
			}
	}
?>
