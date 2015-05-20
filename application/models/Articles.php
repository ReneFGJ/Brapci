<?php
class articles extends CI_model {
	
	function save_ISSUE($id,$p1,$p2,$issue,$doi)
		{
			/* title 1 */
			$p1 = trim($p1);
			$p2 = trim($p2);
						
			$sql = "update brapci_article set
					ar_pg_inicial = '$p1',
					ar_pg_final = '$p2',
					ar_edition = '$issue',
					ar_doi = '$doi' 
					where id_ar = ".$id;
			$this->db->query($sql);	
		}
	function save_TITLE($id, $title_1,$title_2,$idioma_1,$idioma_2)
		{
			/* title 1 */
			$title_1 = trim($title_1);
			$title_1 = troca($title_1,chr(13),' ');
			$title_1 = troca($title_1,chr(10),'');
			$title_1 = troca($title_1,'  ',' ');
			/* title 2 */
			$title_2 = trim($title_2);
			$title_2 = troca($title_2,chr(13),' ');
			$title_2 = troca($title_2,chr(10),'');
			$title_2 = troca($title_2,'  ',' ');
			
			$sql = "update brapci_article set
					ar_titulo_1 = '$title_1',
					ar_titulo_2 = '$title_2',
					ar_idioma_1 = '$idioma_1',
					ar_idioma_2 = '$idioma_2' 
					where id_ar = ".$id;
			$this->db->query($sql);
		}
	function save_ABSTRACT($id,$abstract,$lc=1)
		{
			if ($lc == 2) { $fld = 'ar_resumo_2'; }
			else { $fld = 'ar_resumo_1'; }
			/* Abstract */
			$abstract = trim($abstract);
			$abstract = troca($abstract,chr(13),' ');
			$abstract = troca($abstract,chr(10),'');
			$abstract = troca($abstract,'  ',' ');
			$sql = "update brapci_article set
					$fld = '$abstract'
					where id_ar = ".$id;
			$this->db->query($sql);
			return(1);		
		}
	function le($id) {
		$id = strzero($id,10);
		$sql = "select * from brapci_article
						inner join brapci_journal on ar_journal_id = jnl_codigo
						inner join brapci_edition on ar_edition = ed_codigo 
						where ar_codigo = '$id' ";
					
		$query = $this -> db -> query($sql);
		$query = $query -> result();
		$line = db_read($query);
		
		/* Kwywords */
		$this->load->model('keywords');
		
		$line['ar_keyw_1'] = $this->keywords->retrieve_keywords($id,$line['ar_idioma_1']);
		$line['ar_keyw_2'] = $this->keywords->retrieve_keywords($id,$line['ar_idioma_2']);
		$line['author'] = $this -> author_article($id);
		$line['authores_row'] = $this -> author_article_row($id);
		$line['cited'] = $this -> cited($id);
		$line['link_pdf'] = $this->arquivos($id);
		
		/* Pages */
		$p1 = $line['ar_pg_inicial'];
		$p2 = $line['ar_pg_final'];
		$line['pages'] = '';
		if ($p1 > 0)
			{
				$line['pages'] = ', p.'.$p1;
				if (strlen($p2) > 0)
					{
						$line['pages'] .= '-'.$p2;		
					}
			}
		
		 

		if (strlen(trim($line['ar_doi'])) == 0) { $line['ar_doi'] = '<font color="red">empty</font>';
		}

		return ($line);
	}

	function arquivos($id) {
		$sql = "select * from brapci_article_suporte where bs_article = '$id' and bs_adress like '_repo%' order by bs_type ";
		$rlt = db_query($sql);
		$line = db_read($rlt);
		$link = trim($line['bs_adress']);
		if (strlen($link) > 0) { $fl = base_url($link); }
		else {$fl = ''; }
		return($fl);
	}

	function cited($id) {

		$sql = "select * from mar_works
						where m_work = '$id' 
					order by m_ref";
		$query = $this -> db -> query($sql);
		$query = $query -> result();
		$sx = '<ul id="refs">';
		while ($line = db_read($query)) {
			$bdoi = trim($line['m_bdoi']);
			if (strlen($bdoi) > 0) {
				$bdoi = ' <font color="blue">(' . $bdoi . ')</font>';
			}
			$sx .= '<li>' . htmlspecialchars($line['m_ref']) . $bdoi . '</li>';
		}
		$sx .= '</ul>';
		return ($sx);
	}

	function author_article($id) {
			$sql = "
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
			if (strlen($sx) > 0) { $sx .= '; ';
			}
			$sx .= htmlspecialchars($line['autor_nome']);
			$info = trim($line['ae_bio']);
			if (strlen($info) > 0) {
				$sx .= ' <sup><a href="#" title="' . $info . '">' . $id . '</a></sup>';
			}
		}
		$sx .= '.';
		return ($sx);

	}
	function author_article_row($id) {
			$sql = "
				SELECT * FROM `brapci_article_author` 
				inner join brapci_autor on autor_codigo = ae_author
				where ae_article = '$id'
				order by ae_pos			
				 ";
		$query = $this -> db -> query($sql);
		$query = $query -> result_array();
		$sx = '';
		$id = 0;
		for ($r=0;$r < count($query);$r++) {
			$sx .= htmlspecialchars($query[$r]['autor_nome']).chr(13).chr(10);
		}
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
