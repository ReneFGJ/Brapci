<?php
// This file is part of the Brapci Software. 
// 
// Copyright 2015, UFPR. All rights reserved. You can redistribute it and/or modify
// Brapci under the terms of the Brapci License as published by UFPR, which
// restricts commercial use of the Software. 
// 
// Brapci is distributed in the hope that it will be useful, but WITHOUT ANY
// WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
// PARTICULAR PURPOSE. See the ProEthos License for more details. 
// 
// You should have received a copy of the Brapci License along with the Brapci
// Software. If not, see
// https://github.com/ReneFGJ/Brapci/tree/master//LICENSE.txt 
/* @author: Rene Faustino Gabriel Junior <renefgj@gmail.com>
 * @date: 2015-12-01
 */
class articles extends CI_model {
	
	function resumo()
		{
			$sql = "SELECT count(*) as total, ar_status FROM `brapci_article` group by ar_status";
			$rlt = $this->db->query($sql);
			$rlt = $rlt->result_array();
			$sx = '';
			$rs = array(0,0,0,0,0,0,0,0,0);
			for ($r=0;$r < count($rlt);$r++)
				{
					$line = $rlt[$r];
					$tp = $line['ar_status'];
					$total = $line['total'];
					switch ($tp)
						{
						case 'A':
							$rs[0] = $rs[0] + $total;
							break;
						case 'B':
							$rs[1] = $rs[1] + $total;
							break;
						case 'C':
							$rs[2] = $rs[2] + $total;
							break;
						case 'D':
							$rs[3] = $rs[3] + $total;
							break;
						case 'F':
							$rs[3] = $rs[3] + $total;
							break;
						case 'X':
							$rs[5] = $rs[5] + $total;
							break;
						default:
							$rs[0] = $rs[0] + $total;
							break;							
						}
				}
			$sx .= '<table width="400">
					<tr><th>situação</th><th>quant.</th></tr>
					<tr><td>Em indexação</td><td align="right">'.number_format($rs[0],0,',','.').'</td></tr>
					<tr><td>Em 1º Revisão</td><td align="right">'.number_format($rs[1],0,',','.').'</td></tr>
					<tr><td>Em 2º Revisão</td><td align="right">'.number_format($rs[2],0,',','.').'</td></tr>
					<tr><td>Indexados</td><td align="right">'.number_format($rs[3],0,',','.').'</td></tr>
					<tr><th>Total</th><td align="right"><b>'.number_format(($rs[0]+$rs[1]+$rs[2]+$rs[3]+$rs[4]),0,',','.').'</b></td></tr>
					</table>';
			return($sx);
		}
	
	function insert_suporte($codigo,$link,$jid)
		{
			$sql = "select * from brapci_article_suporte where bs_adress = '$link' ";
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					return(0);
				} else {
					$data = date("Ymd");
					
					$type = 'URL';
					if (substr($link,0,3) == '10.') { $type = 'DOI'; }
					
					$sql = "insert into brapci_article_suporte 
							(
							bs_article, bs_type, bs_adress,
							bs_status, bs_journal_id, bs_update
							) values (
							'$codigo','$type','$link',
							'A','$jid',$data							
							)					
					";
					$this->db->query($sql);
					return(1);
				}
		}

	function insert_new_article($article) {
		$id = $article['idf'];
		$xsql = "select * from brapci_article where ar_oai_id = '$id' ";
		$rlt = db_query($xsql);

		if ($line = db_read($rlt)) {
			$cod = $line['ar_codigo'];
			$section = $article['section'];
			$edition = $article['issue_id'];
			$sql = "update brapci_article set 
					ar_edition= '$edition',
					ar_section = '$section'
					where id_ar = ".$line['id_ar'];
			$rlt = $this->db->query($sql);
			return($cod);
		} else {
			$section = $article['section'];
			$edition = $article['issue_id'];
			
			$journal_id = $article['journal_id'];
			$idioma = $article['titles'][0]['idioma'];
			$titulo = troca($article['titles'][0]['title'],"'",'´');
			$ar_resumo_1 = troca($article['abstract'][0]['content'],"'",'´');
			
			if (isset($article['titles'][1]['idioma']))
				{
					$idioma2 = $article['titles'][1]['idioma'];
					$titulo2 = troca($article['titles'][1]['title'],"'",'´');
					$ar_resumo_2 = troca($article['abstract'][1]['content'],"'",'´');
				} else {
					$idioma2 = '';
					$titulo2 = '';
					$ar_resumo_2 = '';
				}

			if ($idioma2 == 'pt-BR') {
				$idioma3 = $idioma;
				$titulo3 = $titulo;
				$ar_resumo_3 = $ar_resumo_1;

				$idioma = $idioma2;
				$titulo = $titulo2;
				$ar_resumo_1 = $ar_resumo_2;

				$idioma2 = $idioma3;
				$titulo2 = $titulo3;
				$ar_resumo_2 = $ar_resumo_3;
			}

			$ano = $article['ano'];

			$titulo_asc = UpperCaseSql($titulo);

			$sql = "insert into brapci_article
			
			(ar_journal_id, ar_codigo, ar_titulo_1,
			ar_titulo_1_asc, ar_titulo_2,
			ar_idioma_1, ar_idioma_2,
			ar_status, ar_resumo_1, ar_resumo_2,
			
			ar_edition, ar_section,
			
			ar_doi, ar_oai_id, ar_brapci_id,
			ar_ano, ar_bdoi, at_citacoes
			) values (
			'$journal_id','','$titulo',
			'$titulo_asc','$titulo2',
			'$idioma','$idioma2',
			'A', '$ar_resumo_1','$ar_resumo_2',
			
			'$edition','$section',
			
			'','$id','',
			'$ano','',0)			
			";
			$this->db->query($sql);
			$this->updatex();
			
			$rlt = db_query($xsql);
			$line = db_read($rlt);
			$cod = $line['ar_codigo'];
			return($cod);							
		}
	}

	function save_ISSUE($id, $p1, $p2, $issue, $doi, $sec) {
		/* title 1 */
		$p1 = trim($p1);
		$p2 = trim($p2);
		
		/* Regras */
		if ((strpos($p1,'-') > 0) and (strlen($p2) == 0))
			{
				$p2 = trim(substr($p1,strpos($p1,'-')+1,10));
				$p1 = trim(substr($p1,0,strpos($p1,'-')));
			}

		$sql = "update brapci_article set
					ar_pg_inicial = '$p1',
					ar_pg_final = '$p2',
					ar_edition = '$issue',
					ar_section = '$sec',
					ar_doi = '$doi' 
					where id_ar = " . $id;
		$this -> db -> query($sql);
	}

	function save_TITLE($id, $title_1, $title_2, $idioma_1, $idioma_2) {
		/* title 1 */
		$title_1 = trim($title_1);
		$title_1 = troca($title_1, chr(13), ' ');
		$title_1 = troca($title_1, chr(10), '');
		$title_1 = troca($title_1, '  ', ' ');
		/* title 2 */
		$title_2 = trim($title_2);
		$title_2 = troca($title_2, chr(13), ' ');
		$title_2 = troca($title_2, chr(10), '');
		$title_2 = troca($title_2, '  ', ' ');

		$sql = "update brapci_article set
					ar_titulo_1 = '$title_1',
					ar_titulo_2 = '$title_2',
					ar_idioma_1 = '$idioma_1',
					ar_idioma_2 = '$idioma_2' 
					where id_ar = " . $id;
		$this -> db -> query($sql);
	}

	function save_ABSTRACT($id, $abstract, $lc = 1) {
		if ($lc == 2) { $fld = 'ar_resumo_2';
		} else { $fld = 'ar_resumo_1';
		}
		/* Abstract */
		$abstract = trim($abstract);
		$abstract = troca($abstract, chr(13), ' ');
		$abstract = troca($abstract, chr(10), '');
		$abstract = troca($abstract, '  ', ' ');
		$sql = "update brapci_article set
					$fld = '$abstract'
					where id_ar = " . $id;
		$this -> db -> query($sql);
		return (1);
	}

	function updatex() {
		$c = 'ar';
		$c1 = 'id_' . $c;
		$c2 = $c . '_codigo';
		$c3 = 10;
		$sql = "update brapci_article set $c2 = lpad($c1,$c3,0) where $c2='' ";
		$rlt = $this -> db -> query($sql);
	}

	function le($id) {
		$id = strzero($id, 10);
		$sql = "select * from brapci_article
						left join brapci_journal on ar_journal_id = jnl_codigo
						left join brapci_edition on ar_edition = ed_codigo 
						left join brapci_section on ar_section = se_codigo
						where ar_codigo = '$id' ";
		$query = $this -> db -> query($sql);
		$query = $query -> result();
		$line = db_read($query);

		/* Kwywords */
		$this -> load -> model('keywords');

		$line['ar_keyw_1'] = $this -> keywords -> retrieve_keywords($id, $line['ar_idioma_1']);
		$line['ar_keyw_2'] = $this -> keywords -> retrieve_keywords($id, $line['ar_idioma_2']);
		$line['author'] = $this -> author_article($id);
		$line['authores_row'] = $this -> author_article_row($id);
		$line['cited'] = $this -> cited($id);
		$line['link_pdf'] = $this -> arquivos($id);

		/* Pages */
		$p1 = $line['ar_pg_inicial'];
		$p2 = $line['ar_pg_final'];
		$line['pages'] = '';
		if ($p1 > 0) {
			$line['pages'] = ', p.' . $p1;
			if (strlen($p2) > 0) {
				$line['pages'] .= '-' . $p2;
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
		if (strlen($link) > 0) { $fl = base_url($link);
		} else {$fl = '';
		}
		return ($fl);
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
		for ($r = 0; $r < count($query); $r++) {
			$sx .= htmlspecialchars($query[$r]['autor_nome']) . chr(13) . chr(10);
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
			$link = '<A HREF="' . base_url('index.php/admin/article_view/' . $row -> id_ar . '/' . checkpost_link($row -> id_ar)) . '" target="_new' . $row -> id_ar . '">';
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
			$sx .= $link;
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
			$sx .= '</A>';
			$sx .= '</td>';
			$sx .= '</tr>';
		}
		$sx .= '</table>';
		return ($sx);
	}

}
?>
