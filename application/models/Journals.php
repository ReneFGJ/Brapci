<?php 
class journals extends CI_model {
	
	function le($id=0)
		{
			$sql = "select * from brapci_journal
					left join brapci_journal_tipo on jnl_tipo = jtp_codigo 
					left join ajax_cidade on cidade_codigo = jnl_cidade
					left join brapci_periodicidade on jnl_periodicidade = peri_codigo
					where id_jnl = ".$id."
					order by jtp_descricao, jnl_nome
			 ";
			$rlt = $this->db->query($sql);
			$rlt = $rlt->result();
			$line = db_read($rlt);
			
			if (strlen(trim($line['jnl_issn_eletronico']))==0) { $line['jnl_issn_eletronico'] = '<font color="grey">não cadastrado</font>'; }
			
			$line['na'] = 0;
			
			$line['desde'] = $line['jnl_ano_inicio'];
			$line['anos'] = date("Y")-$line['jnl_ano_inicio'];
			
			/* Calcula */
			$sql = "select count(*) as artigos from brapci_article where ar_journal_id = '".strzero($id,7)."' and ar_status <> 'X'  ";
			$query = $this->db->query($sql);
			$rlt = $query->result();
			$xline = db_read($rlt);
			$line['na'] = $xline['artigos'];

			$sql = "select count(*) as edicoes from brapci_edition where ed_journal_id = '".strzero($id,7)."' and ed_status <> 'X' ";
			$query = $this->db->query($sql);
			$rlt = $query->result();
			$xline = db_read($rlt);
			$line['nr'] = $xline['edicoes'];
			
			$line['logo'] = '';
			$logo = trim($line['jnl_codigo']).'.jpg';
			$file_logo = 'img/journals/'.$logo;
			if (file_exists($file_logo))
				{
					$line['logo'] = '<img src="'.base_url('img/journals/'.$logo).'" width="150">';
				} else {
					
				}							
						
			return($line);
			
		}
	
	function show_publish($tipo='')
		{
			$sql = "select * from brapci_journal
					left join brapci_journal_tipo on jnl_tipo = jtp_codigo 
					left join ajax_cidade on cidade_codigo = jnl_cidade
					left join brapci_periodicidade on jnl_periodicidade = peri_codigo
					order by jtp_descricao, jnl_nome
			 ";
			$rlt = $this->db->query($sql);
			$rlt = $rlt->result();
			$xtp = '';
			
			$sx = '<table width="100%" class="tabela00">';
			while ($line = db_read($rlt))
				{
					$tp = $line['jtp_descricao'];
					
					if ($tp <> $xtp)
						{
							$sx .= '<TR><TD colspan=3 class="lt4">'.$tp;
							$xtp = $tp;
						}
					$link = base_url('journal/view/'.$line['id_jnl']);
					$link = '<A HREF="'.$link.'" class="link">';
					$sx .= '<TR>';
					$sx .= '<TD width="20">&nbsp;</td>';
					$sx .= '<TD class="tabela01">';
					$sx .= $link.trim($line['jnl_nome']).'</a>';
					
					$sx .= '<TD class="tabela01">';
					$sx .= $link.trim($line['cidade_nome']).'</a>';
					
					$sx .= '<TD class="tabela01">';
					$sx .= $link.trim($line['peri_nome']).'</a>';

					$sx .= '<TD class="tabela01" align="center" width="5%">';
					$sx .= trim($line['jnl_issn_impresso']);
				}
			$sx .= '</table>';
			return($sx);
		}
}
	