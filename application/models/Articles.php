<?php
class articles extends CI_model {

	function le($id) {
		$sql = "select * from brapci_article
						inner join brapci_journal on ar_journal_id = jnl_codigo
						inner join brapci_edition on ar_edition = ed_codigo 
						where ar_codigo = '$id' ";
		$query = $this -> db -> query($sql);
		$query = $query -> result();
		$line = db_read($query);
		$line['ar_keyw_1'] = 'key1';
		$line['ar_keyw_2'] = 'key2';
		$line['author'] = $this -> author_article($id);
		$line['cited'] = $this -> cited($id);
		$line['link_pdf'] = 'http://www.brapci.inf.br/_repositorio/2015/04/pdf_fc4b213028_0010946.pdf';

		if (strlen(trim($line['ar_doi'])) == 0) { $line['ar_doi'] = '<font color="red">empty</font>';
		}

		return ($line);
	}

	function cited($id) {

		$sql = "select * from mar_works
						where m_work = '$id' 
					order by m_ref";
		echo $sql;
		$query = $this -> db -> query($sql);
		$query = $query -> result();
		$sx = '<ul id="refs">';
		while ($line = db_read($query)) {
			$bdoi = trim($line['m_bdoi']);
			if (strlen($bdoi) > 0)
				{
					$bdoi = ' <font color="blue">('.$bdoi.')</font>';
				}
			$sx .= '<li>' . htmlspecialchars($line['m_ref']) . $bdoi . '</li>';
		}
		$sx .= '</ul>';
		return ($sx);
	}

	function author_article($id) {			$sql = "
				SELECT * FROM `brapci_article_author` 
				inner join brapci_autor on autor_codigo = ae_author
				where ae_article = '$id'
				order by ae_pos			
				 ";
		$query = $this -> db -> query($sql);
		$query = $query -> result();
		$sx = '';
		$id = 0;
		while ($line = db_read($query)) {
			$id++;
			if (strlen($sx) > 0)
				{ $sx .= '; '; }
			$sx .= htmlspecialchars($line['autor_nome']);
			$info = trim($line['ae_bio']);
			if (strlen($info) > 0)
				{
					$sx .= ' <sup><a href="#" title="'.$info.'">'.$id.'</a></sup>';
				}
		}
		$sx .= '.';
		return ($sx);

	}

	function articles_author($codigo) {
		$sql = "
				SELECT * FROM `brapci_article_author` 
				inner join brapci_article on ae_article = ar_codigo
				inner join brapci_edition on ar_edition = ed_codigo
				inner join brapci_journal on jnl_codigo = ar_journal_id 
				where ae_author = '$codigo'			
				order by ed_ano desc ";

		$query = $this -> db -> query($sql);
		$query = $query -> result();
		$sx = '<table width="100%" class="tabela00">';
		$r = 0;
		foreach ($query as $row) {
			$r++;
			//$title = trim($row['ar_titulo_1']);
			$title = $row -> ar_titulo_1;
			$journal = $row -> jnl_nome;
			$ano = $row -> ed_ano;
			$vol = $row -> ed_vol;
			$nr = $row -> ed_nr;
			$tipo = $row -> ar_tipo;

			$sx .= '<tr valign="top">';
			$sx .= '<td width="30" align="right">' . $r . '.&nbsp;</td>';
			$sx .= '<td>';
			$sx .= trim($title) . '. ';
			$sx .= trim($journal);
			if (strlen($vol)) { $sx .= 'v. ' . $vol;
			}
			if (strlen($nr)) { $sx .= ', nr. ' . $nr;
			}
			$sx .= ', ' . $ano;
			/* tipo */
			if (strlen($tipo)) { $sx .= ' (' . $tipo . ')';
			}

			$sx .= '</td>';
			$sx .= '</tr>';

			//$cited = $row['at_citacoes'];
			//print_r($row);
			//echo '<HR>';
		}
		$sx .= '</table>';
		return ($sx);
	}

}
?>
