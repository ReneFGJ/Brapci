<?php
class indicadores extends CI_Model {
	function indicador_producao_ano($jnl = 0) {
		$sql = "SELECT * FROM ( 
							SELECT count(*) as total, ar_ano 
							FROM brapci_article 
							INNER JOIN brapci_journal on ar_journal_id = jnl_codigo 
							WHERE ar_status <> 'X' and jnl_tipo = 'J' and ar_ano > 1950 
							GROUP BY ar_ano 
							) as tabela 
						order by ar_ano";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		$dados = array();
		$dados_ac = array();
		$to_ac = 0;
		for ($r = 0; $r < count($rlt); $r++) {
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
		$sx = $this -> load -> view('grapho/grapho_producao', $data, True);

		$data['div_name'] = 'grapho_01A';
		$data['dados'] = $dados_ac;
		$sx .= $this -> load -> view('grapho/grapho_producao_acumulado', $data, True);
		return ($sx);
	}

	function indicador_producao_journals_ano($jnl = 0) {
		$sql = "SELECT count(*) as total, ar_ano FROM ( 
							SELECT 1, ar_ano, ar_journal_id 
							FROM brapci_article 
							INNER JOIN brapci_journal on ar_journal_id = jnl_codigo 
							WHERE ar_status <> 'X' and jnl_tipo = 'J' and ar_ano > 1950 
							GROUP BY ar_ano, ar_journal_id
							) as tabela 
						GROUP BY ar_ano							
						order by ar_ano";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		$dados = array();
		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			$ano = $line['ar_ano'];
			$total = $line['total'];
			$dados[$ano] = $total;
			//print_r($line);
		}
		$data['dados'] = $dados;
		$data['div_name'] = 'grapho_02';
		$sx = $this -> load -> view('grapho/grapho_producao_journals', $data, True);
		return ($sx);
	}

	function indicador_autores_producao() {
		$sql = "
					SELECT COUNT(*) AS soma, total from
					(
						select ae_author, count(*) as total from brapci_article_author
						LEFT JOIN brapci_article on ae_article = ar_codigo
						WHERE not ar_codigo is null and ar_status <> 'X'
						GRoup BY ae_author
					) as tabela 
					GROUP BY total
					ORDER BY total";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		$dados = array();
		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			$ano = $line['soma'];
			$total = $line['total'];
			$dados[$ano] = $total;
			//print_r($line);
		}
		$data['dados'] = $dados;
		$data['div_name'] = 'grapho_03';
		$data['title_div'] = 'Autores e quantidade de publicações';
		$data['axis_x'] = 'Quant. Trabalhos';
		$data['axis_y'] = 'Autores';
		$sx = $this -> load -> view('grapho/grapho_03', $data, True);
		return ($sx);
	}

	function colaboracao() {
		$sql = "
				select count(*) as soma, autores, ar_ano from (
    					select ae_article, count(*) as autores, ar_ano from brapci_article_author
    					LEFT JOIN brapci_article on ae_article = ar_codigo
    					WHERE not ar_codigo is null and ar_status <> 'X' and ar_ano > 1900
    					group by ae_article, ar_ano
					) as tabela 
					group by autores, ar_ano
					order by ar_ano, autores
				";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		$dados = array();
		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			$total = $line['soma'];
			$ano = $line['ar_ano'];
			$aut = $line['autores'];
			switch ($aut)
				{
				case '1':
					if (!isset($dados[$ano][0])) { $dados[$ano][0] =0; }
					$dados[$ano][0] = $dados[$ano][0]  + $total; 
					break;
				case '2':
					if (!isset($dados[$ano][1])) { $dados[$ano][1] =0; }
					$dados[$ano][1] = $dados[$ano][1]  + $total; 
					break;
				case '3':
					if (!isset($dados[$ano][2])) { $dados[$ano][2] =0; }
					$dados[$ano][2] = $dados[$ano][2]  + $total; 
					break;
				case '4':
					if (!isset($dados[$ano][3])) { $dados[$ano][3] =0; }
					$dados[$ano][3] = $dados[$ano][3]  + $total; 
					break;
				case '5':
					if (!isset($dados[$ano][4])) { $dados[$ano][4] =0; }
					$dados[$ano][4] = $dados[$ano][4]  + $total; 
					break;
				default:
					if (!isset($dados[$ano][5])) { $dados[$ano][5] =0; }
					$dados[$ano][5] = $dados[$ano][5]  + $total; 
					break;
				}
		}

		$data['dados'] = $dados;
		$data['div_name'] = 'grapho_04';
		$data['title_div'] = 'Autores e quantidade de publicações';
		$data['axis_x'] = 'Quant. Trabalhos';
		$data['axis_y'] = 'Autores';
		$sx = $this -> load -> view('grapho/grapho_04', $data, True);
		return ($sx);
	}

}
