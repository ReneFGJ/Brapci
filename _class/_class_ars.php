<?php
class ars
	{
		var $authors;
		
		function mostrar($txt)
			{
				$this->separar($txt.chr(13).chr(10));
			}
		
		function separar($txt)
			{
				$txt = troca($txt,chr(13),'¢');
				$txt = troca($txt,chr(10),'');
				$txt = troca($txt,'{','');
				$txt = troca($txt,'}','');
				$ln = splitx('¢',$txt);
				
				/* Identificar o número de autores */
				$author = array();
				
				for ($r=0;$r < count($ln); $r++)
					{
						$lnx = $ln[$r].';';
						$aut = splitx(';',$lnx);
						for ($t=0;$t < count($aut);$t++)
							{
								$name = trim($aut[$t]);
								$x = in_array($name, $author);
								if ($x == 0)
									{ array_push($author,$name); }
							}
					}
				/* Ordena o array dos autores */
				sort($author);
				
				/* Monta matrix Vazia */
				$mxt = array();
				$mxt1 = array();
				for ($x=0;$x < count($author);$x++)
					{ array_push($mxt1,0); }
				for ($x=0;$x < count($author);$x++)
					{ array_push($mxt, $mxt1); }
				
				/* Monta matrix com dados */
				$max = 1;
				for ($r=0;$r < count($ln); $r++)
					{
						$lnx = $ln[$r].';';
						$aut = splitx(';',$lnx);
						$att = array();
						for ($t=0;$t < count($aut);$t++)
							{
								$name = trim($aut[$t]);
								$x = array_search($name, $author);
								array_push($att,$x);
							}
						for ($x=0;$x < count($att);$x++)
							{
								for ($y=$x; $y < count($att);$y++)
									{
										$a1 = $att[$x];
										$a2 = $att[$y];
										$vlr = $mxt[$a1][$a2]+1;
										if ($vlr > $max) { $max = $vlr; }
										$mxt[$a1][$a2] = ($vlr);
										$mxt[$a2][$a1] = ($vlr);
									}
							}
					}
				echo 'Max:'.$max;
				$sx = '<table class="lt0">';
				for ($x=0;$x < count($mxt);$x++)
					{
						$sx .= '<TR>';
						for ($y=0;$y < count($mxt[$x]);$y++)
							{
								$sx .= '<TD align="center">'.$mxt[$x][$y].'</TD>';
							}
					}
				$sx .= '</table>';
				echo $sx;
			}	
	}
?>
