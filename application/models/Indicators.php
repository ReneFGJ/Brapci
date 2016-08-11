<?php
class indicators extends CI_model
	{
		function resume_articles()
			{
				$sql = "SELECT count(*) as total, ed_ano FROM `brapci_article`
					INNER JOIN brapci_edition on ar_edition = ed_codigo
					INNER JOIN brapci_section on ar_section = se_codigo
					where ar_status <> 'X' and se_processar = 1
					group by ed_ano
					order by ed_ano desc";
				$rlt = $this->db->query($sql);
				$rlt = $rlt->result_array();
				return($rlt);
			}
	}
