<?php
class indicadores extends CI_Model
	{
		function indicador_producao_ano()
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
				
				for ($r=0;$r < count($rlt);$r++)
					{
						$line = $rlt[$r];
						print_r($line);
						echo '<hr>';
					}
			}
	}
