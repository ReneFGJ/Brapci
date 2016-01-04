<?php
class indicadores extends CI_Model
	{
		function indicador_producao_ano($jnl=0)
			{
				$sql = "SELECT * FROM ( 
							SELECT count(*) as total, ar_ano 
							FROM brapci_article 
							INNER JOIN brapci_journal on ar_journal_id = jnl_codigo 
							WHERE ar_status <> 'X' and jnl_tipo = 'J' and ar_ano > 1950 
							GROUP BY ar_ano 
							) as tabela 
						order by ar_ano";
				$rlt = $this->db->query($sql);
				$rlt = $rlt->result_array();
				$dados = array();
				$dados_ac = array();
				$to_ac = 0;
				for ($r=0;$r < count($rlt);$r++)
					{
						$line = $rlt[$r];
						
						$ano = $line['ar_ano'];
						$total = $line['total'];
						$dados[$ano] = $total;
						
						/* Acumulado */
						$to_ac = $to_ac + $total;
						$dados_ac[$ano] = $to_ac;
						//print_r($line);
					}
				$data['dados'] = $dados;
				$data['div_name'] = 'grapho_01';
				$sx = $this->load->view('grapho/grapho_producao',$data,True);
				
				$data['div_name'] = 'grapho_01A';
				$data['dados'] = $dados_ac;
				$sx .= $this->load->view('grapho/grapho_producao_acumulado',$data,True);
				return($sx);
			}
		function indicador_producao_journals_ano($jnl=0)
			{
				$sql = "SELECT count(*) as total, ar_ano FROM ( 
							SELECT 1, ar_ano, ar_journal_id 
							FROM brapci_article 
							INNER JOIN brapci_journal on ar_journal_id = jnl_codigo 
							WHERE ar_status <> 'X' and jnl_tipo = 'J' and ar_ano > 1950 
							GROUP BY ar_ano, ar_journal_id
							) as tabela 
						GROUP BY ar_ano							
						order by ar_ano";
				$rlt = $this->db->query($sql);
				$rlt = $rlt->result_array();
				$dados = array();
				for ($r=0;$r < count($rlt);$r++)
					{
						$line = $rlt[$r];
						$ano = $line['ar_ano'];
						$total = $line['total'];
						$dados[$ano] = $total;
						//print_r($line);
					}
				$data['dados'] = $dados;
				$data['div_name'] = 'grapho_02';
				$sx = $this->load->view('grapho/grapho_producao_journals',$data,True);
				return($sx);
			}			
	}
