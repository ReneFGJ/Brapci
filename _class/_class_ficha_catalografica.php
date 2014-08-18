<?php
class ficha_catalografica
	{
	var $cutter;
	var $line_1th;
	var $line_2th;
	var $line_3th;
	
	var $cdd;
	var $ponto_acesso;
	var $pistas;
	var $autores;
	
	function mostra()
		{
			$sx = '
					<style>
						.ficha_catalografica
							{
									font-family: "Courier New","Courier","Times New Roman";
									font-size: 12px;
							}
					</style>
					';
			$sx .= '<center><table width=610 border=1 cellpadding=4 cellspacing=0>';
			$sx .= '<tr><td>';
				$sx .= '<table width=610 border=0 height=380 cellpadding=6 cellspacing=6 class="ficha_catalografica">';
				$sx .= '<tr valign="top"><td rowspan=10>';
				$sx .= '<nobr>'.$this->cutter.'&nbsp;&nbsp;';
				$sx .= '<TD colspan=2 height="2%">'.$this->line_1th;
				
				$sx .= '<TR><TD colspan=2 height="2%">&nbsp;&nbsp;&nbsp;'.$this->line_2th;
				
				$sx .= '<TR><TD width="40" height="90%">&nbsp;&nbsp;&nbsp;<TD>'.$this->line_3th;
				
				$sx .= '<TR><TD colspan=2 height="2%" valign="botton">&nbsp;&nbsp;&nbsp;'.$this->ponto_acesso;
				$sx .= ' '.$this->pistas;
				
				$sx .= '<TR valign="botton"><TD align="right" colspan=2>CDD '.$this->cdd;
				
				$sx .= '</table>';
			$sx .= '</table></center>';
			return($sx);
		}
		
	}
?>
