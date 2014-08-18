<?php
class avaliacao
	{
	var $tabela;
	function show_trabalhos()	
		{
			//$sql = "alter table pibic_bolsa_contempladas add column pb_semic_apresentado char(1) ";
			//$rlt = db_query($sql);
			
			$sql = "select * from pibic_bolsa_contempladas
					left join articles on article_ref = pb_protocolo
					left join pibic_semic_avaliador_notas on article_ref = av_ref
					limit 10
			";
			$rlt = db_query($sql);
			
			while ($line = db_read($rlt))
				{
					$publicado = trim($line['article_publicado']);
					print_r($line);
					echo '<HR>';
				}
		}
	function show_avaliacoes()
		{
			return($this->show_trabalhos());
			exit;
			$sql = "select count(*) as total, av_area, av_numtrab, av_tipo_trabalho
		 		from pibic_semic_avaliador_notas 
				group by av_area, av_numtrab, av_tipo_trabalho
				order by av_area, av_numtrab, av_tipo_trabalho
			";
			$rlt = db_query($sql);
			$sx =  '<table class="tabela00">';		
			$tot = 0;
			$xtb = 'x';
			while ($line = db_read($rlt))
				{
					$trab = $line['av_area']. strzero($line['av_numtrab'],2);
					if ($trab != $xtb)
						{
						if (($tt > 0) and ($tt < 3))
							{ $sx .=  '<TD><====='; }
						$tt = 0;
						$xtb = $trab;
						$tot++;
						$sx .=  '<TR>';
						$sx .=  '<TD>';
						$sx .=  $trab; 
						}
						$sx .=  '<TD>';
						$sx .=  $line['total'];
						$sx .=  '('.$line['av_tipo_trabalho'].')';
						$tt=$tt+$line['total'];
				}
			$sx .=  'Total >>>'.$tot;
			$sx .=  '</table>';
			return($sx);
		}	
	}
?>