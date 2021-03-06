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
class editions extends CI_model {
	var $tabela = 'brapci_edition';
	var $row = '';
    
    function last_editions($total=5)
        {
            $sql = "select * from brapci_edition
                        INNER JOIN brapci_journal ON ed_journal_id = jnl_codigo 
                        order by ed_created desc
                        limit $total ";
            $rlt = $this->db->query($sql);
            $rlt = $rlt->result_array();
            $sx = '<span class="big"><b>'.msg('last_update').'</b></span>'.cr();
            $sx .= '<br>'.cr();
            $sx .= '<br>'.cr();
            $sx .= '<ul>'.cr();
            for ($r=0;$r < count($rlt);$r++)
                {
                    $line = $rlt[$r];
                    
                    $link = $this->rdf->link_issue($line);
                    
                    $sx .= '<li>';
                    $sx .= '<a href="'.$link.'">';
                    $sx .= $line['jnl_nome'];
                    
                    $sx .= ', v.' . $line['ed_vol'];
                    $sx .= ', n.' . $line['ed_nr'];
                    $sx .= ', ' . $line['ed_ano'];
                    $sx .= '.';
                    
                    $sx .= '</a>';
                    
                    $sx .= '</li>';
                }
            $sx .= '</ul>'.cr();
            return($sx);
        }

	function update_status($id) {
		$ide = strzero($id, 7);
		$sql = "select ar_status from brapci_article where ar_edition = '$ide' and ar_status <> 'X'";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array($rlt);

		$sta = 'D';
		$to = 0;
		for ($r = 0; $r < count($rlt); $r++) {
			$line = $rlt[$r];
			if ($line['ar_status'] == 'A') { $sta = 'A';
			}
			if (($line['ar_status'] == 'B') and ($sta != 'A')) { $sta = 'B';
			}
			if (($line['ar_status'] == 'C') and (($sta != 'A') and ($sta != 'B'))) { $sta = 'C';
			}
			$to++;
		}

		if ($to > 0) {
			$sql = "update brapci_edition set ed_status = '$sta' where ed_codigo = '$ide' ";
			$rlt = $this -> db -> query($sql);
		}
	}

	function cp() {
		global $jid;
		$cp = array();
		$wh = '';
		if (strlen($jid) > 0) {
			$wh = 'jnl_codigo = ' . chr(39) . strzero($jid, 7) . chr(39);
		}
		array_push($cp, array('$H8', 'id_ed', 'id_ed', False, True, ''));
		array_push($cp, array('$H8', 'ed_codigo', '', False, True, ''));
		array_push($cp, array('$Q jnl_codigo:jnl_nome:SELECT * FROM brapci_journal where jnl_status <> \'X\'' . $wh . ' order by jnl_nome', 'ed_journal_id', msg('publication'), True, True, ''));
		array_push($cp, array('$S10', 'ed_ano', msg('year'), False, True, ''));
		array_push($cp, array('$S10', 'ed_vol', msg('volume'), False, True, ''));
		array_push($cp, array('$S10', 'ed_nr', msg('numero'), False, True, ''));
		array_push($cp, array('$S20', 'ed_periodo', 'Edição (jan./abr. 2009)', False, True, ''));
		array_push($cp, array('$[0-12]', 'ed_mes_inicial', 'Mês incial', False, True, ''));
		array_push($cp, array('$[0-12]', 'ed_mes_final', 'Mês final', False, True, ''));
		array_push($cp, array('$S100', 'ed_tematica_titulo', 'Título temático', False, True, ''));
		//array_push($cp, array('$O 9:Não definido &1:SIM&0:NÃO', 'ed_biblioteca', 'Acervo da biblioteca', False, True, ''));
		array_push($cp, array('$H8', 'ed_obs', '', False, True, ''));
		array_push($cp, array('$H8', '', '', False, True, ''));
		array_push($cp, array('$O -1:Em preparo&1:Disponível&0:Inativo&', 'ed_ativo', '<I>Status</I> atual', True, True, ''));
		array_push($cp, array('$H8', 'ed_editor', '', False, True, ''));
		array_push($cp, array('$H8', 'ed_coeditor', '', False, True, ''));
		array_push($cp, array('$H8', 'ed_qualis', '', False, True, ''));
		array_push($cp, array('$H8', 'ed_notas', 'Notas sobre edição', False, True, ''));
		array_push($cp, array('$H8', 'ed_data_publicacao', '', False, True, ''));
		array_push($cp, array('$U8', 'ed_data_cadastro', '', False, True, ''));
		array_push($cp, array('$H8', 'ed_oai_issue', 'OAI Source', False, True, ''));
		array_push($cp, array('$O A:Preparo&B:Revisão&D:Ready of print&E:Disponível', 'ed_status', 'OAI Source', False, True, ''));
		array_push($cp, array('$S30', 'ed_path', 'Atalho de acesso', False, True, ''));
		array_push($cp, array('$B8', '', 'Gravar >>', False, True, ''));
		return ($cp);
	}

	function updatex() {
		$c = 'ed';
		$c1 = 'id_' . $c;
		$c2 = $c . '_codigo';
		$c3 = 7;
		$sql = "update " . $this -> tabela . " set $c2 = lpad($c1,$c3,0) where $c2='' ";
		$rlt = $this -> db -> query($sql);
	}

	function le($id = 0) {
		$sql = "select * from brapci_edition 
					inner join brapci_journal on ed_journal_id = jnl_codigo
				where id_ed = " . $id;
		$rlt = db_query($sql);

		if ($line = db_read($rlt)) {
			return ($line);
		}
		return ( array());
	}

	function issue_view($issue = 0, $ed = 1, $admin=0) {
		$issue = strzero($issue, 7);
		$sql = "select * from brapci_article
						left join brapci_section on se_codigo = ar_section
						where ar_edition = '$issue'
						order by se_ordem, se_descricao, CHARACTER_LENGTH(ar_pg_inicial), ar_titulo_1
						";
		$rlt = db_query($sql);

		$sx = '<table width="100%" class="tabela00">';
		$art = 0;
		$xsec = '';
		$sh = '<tr> <th class="tabela01">pos</th>
						<th class="tabela01">article</th>
						<th class="tabela01">pag.</th>
						<th class="tabela01">status</th>						
						</tr>						
						';
		while ($line = db_read($rlt)) {
			$cor = '';
			$xcor = '';
			$sta = trim($line['ar_status']);
			if ($sta == 'X') { $cor = '<font color="red"><S>';
				$xcor = '</S></font>';
			}
			if ((($sta == 'X') and ($ed == 1)) or ($sta != 'X')) {
				if ($admin == 1)
					{
						$link = '<A HREF="' . base_url('index.php/admin/article_view/' . $line['id_ar']) . '/' . checkpost_link($line['id_ar']) . '" >';
					} else {
						$link = '<A HREF="' . base_url('index.php/article/view/' . $line['ar_codigo']) . '/' . checkpost_link($line['ar_codigo']) . '" >';
					}
				
				$sec = trim($line['se_descricao']);
				if ($sec != $xsec) {
					$xsec = $sec;
					$sx .= '<tr bgcolor="#F0F0F0"><td class="lt3" colspan=5>' . $sec . '</td></tr>';
					$sx .= $sh;
					$art = 1;
				} else {
					$art++;
				}
				$sx .= '<tr valign="top">';
				$sx .= '<td align="center">';
				$sx .= $cor . $art . '.' . $xcor;
				$sx .= '</td>';

				$sx .= '<td class="lt2 borderb1">';
				$sx .= $link;
				$sx .= $cor . trim($line['ar_titulo_1']) . $xcor;
				$sx .= '</A>';
				$sx .= '</td>';

				$sx .= '<td width="60" align="center" class="borderb1"><nobr>';
				$pag = $cor . $line['ar_pg_inicial'];
				if ($line['ar_pg_final'] > 0) {
					$pag .= ' - ' . $line['ar_pg_final'];
				}
				$pag .= $xcor;

				$sx .= $pag;
				$sx .= '</nobr></td>';

				$sx .= '<td width="120" align="center" class="borderb1">';
				$sta = $cor . msg('status_article_' . $line['ar_status']) . $xcor;
				$sx .= $sta;
				$sx .= '</td>';

				$sx .= '</tr>';
			}
		}
		$sx .= '</table>';
		return ($sx);
	}

	function issue_view_bootstrap($issue = 0, $ed = 1, $admin=0) {
		$issue = strzero($issue, 7);
		$sql = "select * from brapci_article
						left join brapci_section on se_codigo = ar_section
						where ar_edition = '$issue'
						order by se_ordem, se_descricao, CHARACTER_LENGTH(ar_pg_inicial), ar_titulo_1
						";
		$rlt = db_query($sql);
		$sx = '<div class="container"><div class="row"><div class="col-md-12">';
		$sx .= '<table id="fresh-table" class="table" border=1 width="100%">';
		$art = 0;
		$xsec = '';
		$sh = '<thead><tr> 
					<th data-field="pos" data-sortable="true"><abbr title="Categoria">#</abbr></th>
					<th data-field="work" data-sortable="true"><abbr title="Work">'.msg('work').'</abbr></th>
					<th data-field="pag" data-sortable="true"><abbr title="Pag">'.msg('pag.').'</abbr></th>
					<th data-field="status" data-sortable="true"><abbr title="Status">'.msg('status').'</abbr></th>
					</tr>
					</thead>
					<tbody>';
		$sx .= $sh;
		while ($line = db_read($rlt)) {
			$cor = '';
			$xcor = '';
			$sta = trim($line['ar_status']);
			if ($sta == 'X') { $cor = '<font color="red"><S>';
				$xcor = '</S></font>';
			}
			if ((($sta == 'X') and ($ed == 1)) or ($sta != 'X')) {
				if ($admin == 1)
					{
						$link = '<A HREF="' . base_url('index.php/admin/article_view/' . $line['id_ar']) . '/' . checkpost_link($line['id_ar']) . '" >';
					} else {
						$link = '<A HREF="' . base_url('index.php/article/view/' . $line['ar_codigo']) . '/' . checkpost_link($line['ar_codigo']) . '" >';
					}
				
				$sec = trim($line['se_descricao']);
				if ($sec != $xsec) {
					$xsec = $sec;
//					$sx .= '<tr bgcolor="#F0F0F0"><td class="lt3" colspan=5>' . $sec . '</td></tr>';
					//$sx .= $sh;
					$art = 1;
				} else {
					$art++;
				}
				$sx .= '<tr valign="top">';
				$sx .= '<td align="center">';
				$sx .= $cor . $art . '.' . $xcor;
				$sx .= '</td>';

				$sx .= '<td class="lt2 borderb1">';
				$sx .= $link;
				$sx .= $cor . trim($line['ar_titulo_1']) . $xcor;
				$sx .= '</A>';
				$sx .= '</td>';

				$sx .= '<td width="60" align="center" class="borderb1"><nobr>';
				$pag = $cor . $line['ar_pg_inicial'];
				if ($line['ar_pg_final'] > 0) {
					$pag .= ' - ' . $line['ar_pg_final'];
				}
				$pag .= $xcor;

				$sx .= $pag;
				$sx .= '</nobr></td>';

				$sx .= '<td width="120" align="center" class="borderb1">';
				$sta = $cor . msg('status_article_' . $line['ar_status']) . $xcor;
				$sx .= $sta;
				$sx .= '</td>';
				$sx .= '</tr>';
			}
		}
		$sx .= '</tbody></table>';
		$sx .= '</div></div>';
		return ($sx);
	}

	function editions_row($journal = '', $idi = 0) {
		$journal = strzero($journal, 7);
		$sql = "select * from " . $this -> tabela . " 
						where ed_journal_id = '$journal'
						order by ed_ano desc, ed_vol desc, ed_nr desc 
			";
		$sx = '<div id="issue_row">';
		$sx .= '<Table class="tabela00" width="100%">';
		$sx .= '<TR><th width="65%">editions</th>
						<th width="35%">status</th></tr>';
		$rlt = db_query($sql);

		$issue = 0;
		while ($line = db_read($rlt)) {
			if (strlen($this -> row) > 0) {
				$link = '<A HREF="' . $this -> row . '/' . $line['id_ed'] . '/' . checkpost_link($line['id_ed']) . '" class="link">';
			} else {
				$link = '<A HREF="' . base_url('index.php/journal/issue/' . $line['id_ed']) . '" class="link">';
			}

			$issue++;
			$sx .= '<TR>';
			$sx .= '<TD class="tabela01" >';

			$sx .= $link;

			/* bold */
			$sxf = '';
			if ($idi == $line['id_ed']) { $sx .= '<b>';
				$sxf = '</b>';
			}

			$sx .= $line['ed_ano'] . ', ';
			$sx .= 'v.' . $line['ed_vol'];
			$sx .= ', n.' . $line['ed_nr'];
			$sx .= $sxf;
			$sx .= '</td>';

			$sx .= '<td align="center" class="tabela01">';
			$sta = trim($line['ed_status']);
			$sx .= $link;
			switch($sta) {
				case 'A' :
					$sx .= '<font color="green">'.msg('to index').'<font>';
					break;
				case 'F' :
					$sx .= '<font color="orange">'.msg('revised').'<font>';
					break;
				case 'D' :
					$sx .= '<font color="blue">'.msg('finished').'<font>';
					break;
				case 'C' :
					$sx .= '<font color="orange">'.msg('revised').'<font>';
					break;
				case 'B' :
					$sx .= '<font color="orange">'.msg('to review').'<font>';
					break;
				case '' :
					$sx .= '<font color="green">'.msg('to review').'<font>';
					break;
				default :
					$sx .= '(' . $sta . ')';
					break;
			}
			$sx .= '</A>';
			$sx .= '</td>';
			$sx .= '</tr>';
		}
		$sx .= '<TR><TD colspan=10>Total de <B>' . $issue . '</B> edições';
		$sx .= '</table>';
		$sx .= '</div>';
		return ($sx);
	}

	function editions($journal = '', $tipo = '2') {
		$journal = strzero($journal, 7);
		$sql = "select * from " . $this -> tabela . " 
						where ed_journal_id = '$journal'
						order by ed_ano desc, ed_vol desc, ed_nr desc
			";
		$query = $this -> db -> query($sql);
		$rlt = $query -> result();

		$xano = '';
		$issue = 0;
		
		switch($tipo)
			{
			case '2':
				$sx = '<ul class="ul_editions">';
				while ($line = db_read($rlt)) {
					$link = '<A HREF="' . base_url('index.php/journal/issue/' . $line['id_ed']) . '" class="link lt0">';
					$issue++;
					$ano = $line['ed_ano'];
					if ($xano != $ano) {
						$sx .= '<li class="li_editions_year">';
						$sx .= $ano;
						$sx .= '</li>';
						$xano = $ano;
					}
					$sx .= '<li class="li_editions">';
					$sx .= $link;
					$sx .= 'v.' . $line['ed_vol'];
					$sx .= ', n.' . $line['ed_nr'];
					$sx .= '</a>';
					$sx .= '</li>';
				}
				$sx .= '</ul>';
				$sx .= 'Total de <B>' . $issue . '</B> edições';
				break;
			default:
				$sx = '<Table class="tabela00 lt1" width="400">';
				while ($line = db_read($rlt)) {
					$link = '<A HREF="' . base_url('index.php/journal/issue/' . $line['id_ed']) . '" class="link lt0">';
					$issue++;
					$ano = $line['ed_ano'];
					if ($xano != $ano) {
						$sx .= '<TR>';
						$sx .= '<TD width="50" align="center">' . $ano;
						$xano = $ano;
					}
					$sx .= '<TD class="tabela01" width="100" align="center">';
					$sx .= $link;
					$sx .= 'v.' . $line['ed_vol'];
					$sx .= ', n.' . $line['ed_nr'];
				}
				$sx .= '<TR><TD colspan=10>Total de <B>' . $issue . '</B> edições';
				$sx .= '</table>';
				break;
			}
		return ($sx);
	}

}
?>
