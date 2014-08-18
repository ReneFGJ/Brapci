<?php
class pibic_submit_documento
	{
		var $id;
		var $protocolo;
		var $protocolo_mae;
		var $titulo;
		var $edital;
		var $ano;
		var $pesquisador;
		
		var $tabela = 'pibic_submit_documento';
		
		function resumo()
			{
				
				$sql = "select count(*) as total, doc_ano, doc_edital, doc_status from ".$this->tabela." 
					where doc_autor_principal = '".$this->pesquisador."' 
						and doc_protocolo_mae <> ''
					group by doc_ano, doc_edital, doc_status
					order by doc_ano desc, doc_edital
					";
				$rlt = db_query($sql);
				$col = 0;
				$nrc = 0;
				$sx = '<style>'.chr(13);
				$sx .= '.div_res { background-color: #F0FFFF; border: 1px solid Black; }'.chr(13);				
				$sx .= '</style>'.chr(13);
				$sx .= '<table width="100%" cellpadding=4 cellspacing=0 ><TR>';
				$sj = '';
				while ($line = db_read($rlt))
					{
						if ($col > 5) { $col=0; }
						$sx .= '<TD align=center>';
						$sx .= '<div id="rs'.$nrc.'" class="div_res">';
						$sx .= trim($line['doc_edital']).'-'.trim($line['doc_ano']);
						$sx .= '<BR><font class="lt4">'.$line['total'];
						$sx .= '</div>';
						
						$sj .= '$("#rs'.$nrc.'").corner();'.chr(13);
						$col++;
						$nrc++;
					}
				$sx .= '</table>';
				echo $sx;
				echo '<script>'.chr(13);
				echo $sj;
				echo '</script>'.chr(13);
			}
	}
?>