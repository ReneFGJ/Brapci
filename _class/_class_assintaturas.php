<?php
class assintaturas
	{
		function mostra_dir_pesquisa($texto='')
			{
				$texto = 'Curitiba, '.date("d").' de '.nomemes(round(date("m"))).' de '.date("Y").'.<BR><BR>'.$texto;
				$sx = '<TABLE class="lt1">';
				$sx .= '<TR><TD height=40>';
				$sx .= '<TD rowspan=3 width="30">';
				$sx .= '<TD rowspan=3>'.$texto;
				$sx .= '<TR><TD align="center">____________________________________';
				$sx .= '<TR><TD align="center"><B>Paula C. Trevilatto</B>';
				$sx .= '<TR><TD align="center">Diretora de Pesquisa';				
				$sx .= '</TABLE>';
				return($sx);
			}	
	}
?>
