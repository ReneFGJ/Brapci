<?php
class referencia
	{
		function mostra_artigo_lista($line)
			{
				$titulo = trim($line['ar_titulo_1']);

				$pagi = trim($line['ar_pg_inicial']);
				$pagf = trim($line['ar_pg_final']);
				if (strlen($pagi) > 0)
					{
						$pag = $pagi;
						if (strlen($pagf) > 0) { $pag.= '-'.$pagf; } 
					} else {
						if (strlen($pagf) > 0) { $pag.= $pagf; }						
					} 
				if (strlen($pag) > 0) { $pag = 'p. '.$pag.''; }
				
				/* */
				$link = '<A HREF="article.php?dd0='.$line['id_ar'].'&dd90='.checkpost($line['id_ar']).'" class="lt1">';
				$pag = '';
				$sx = $link.$titulo.' '.$pag.trim($year).'</A>';
				$sx .= '<BR><i>'.$line['Author_Analytic'].'</A>';
				return($sx);
				
			}
		function mostra_referencia($line)
			{
				$titulo = trim($line['ar_titulo_1']);
				$journal = '<B>'.trim($line['jnl_nome_abrev']).'</B>';
				$vol = trim($line['ed_vol']);
				if (strlen($vol) > 0) { $vol = 'v. '.$vol; }
				$nr = trim($line['ed_nr']);
				if (strlen($nr) > 0) { $nr = 'n. '.$nr; }
				$year = trim($line['ed_periodo']);
				$pagi = trim($line['ar_pg_inicial']);
				$pagf = trim($line['ar_pg_final']);
				if (strlen($pagi) > 0)
					{
						$pag = $pagi;
						if (strlen($pagf) > 0) { $pag.= '-'.$pagf; } 
					} else {
						if (strlen($pagf) > 0) { $pag.= $pagf; }						
					} 
				if (strlen($pag) > 0) { $pag = 'p. '.$pag.', '; }
				
				$sx = $titulo.'. '.$journal.', '.$vol.' '.$nr.', '.$pag.trim($year);
				return($sx);
			}
	}
