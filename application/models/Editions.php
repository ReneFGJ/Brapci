<?php
class editions extends CI_model
	{
	var $tabela = 'brapci_edition';
	var $row = '';
	
	function cp()
		{
			global $jid;
			$cp = array();
			$wh = '';
			if (strlen($jid) > 0)
				{
				$wh = 'jnl_codigo = '.chr(39).strzero($jid,7).chr(39);
				}
			array_push($cp,array('$H8','id_ed','id_ed',False,True,''));
			array_push($cp,array('$H8','ed_codigo','',False,True,''));
			array_push($cp,array('$Q jnl_codigo:jnl_nome:SELECT * FROM brapci_journal where jnl_status <> \'X\''.$wh.' order by jnl_nome','ed_journal_id',msg('publication'),True,True,''));
			array_push($cp,array('$S10','ed_ano',msg('year'),False,True,''));
			array_push($cp,array('$S10','ed_vol',msg('volume'),False,True,''));
			array_push($cp,array('$S10','ed_nr',msg('numero'),False,True,''));
			array_push($cp,array('$S20','ed_periodo','Edição (jan./abr. 2009)',False,True,''));
			array_push($cp,array('$[0-12]','ed_mes_inicial','Mês incial',False,True,''));
			array_push($cp,array('$[0-12]','ed_mes_final','Mês final',False,True,''));
			array_push($cp,array('$S100','ed_tematica_titulo','Título temático',False,True,''));
			array_push($cp,array('$O 9:Não definido &1:SIM&0:NÃO','ed_biblioteca','Acervo da biblioteca',False,True,''));
			array_push($cp,array('$H8','ed_obs','',False,True,''));
			array_push($cp,array('$O -1:Em preparo&1:Disponível&0:Inativo&','ed_ativo','<I>Status</I> atual',True,True,''));
			array_push($cp,array('$H8','ed_editor','',False,True,''));
			array_push($cp,array('$H8','ed_coeditor','',False,True,''));
			array_push($cp,array('$H8','ed_qualis','',False,True,''));
			array_push($cp,array('$T60:6','ed_notas','Notas sobre edição',False,True,''));
			array_push($cp,array('$H8','ed_data_publicacao','',False,True,''));
			array_push($cp,array('$U8','ed_data_cadastro','',False,True,''));
			array_push($cp,array('$T60:3','ed_oai_issue','OAI Source',False,True,''));
			array_push($cp,array('$O A:Preparo&B:Revisão&D:Ready of print&E:Disponível','ed_status','OAI Source',False,True,''));
			array_push($cp,array('$H8','ed_path','Atalho de acesso',False,True,''));
			array_push($cp,array('$B8','','Save >>',False,True,''));
			return($cp);
		}
		
	function updatex()
			{
				$c = 'ed';
				$c1 = 'id_'.$c;
				$c2 = $c.'_codigo';
				$c3 = 7;
				$sql = "update ".$this->tabela." set $c2 = lpad($c1,$c3,0) where $c2='' ";
				$rlt = $this->db->query($sql);
			}		
	
	function le($id=0)
		{
			$sql = "select * from brapci_edition where id_ed = ".$id;
			$rlt = db_query($sql);
			
			if ($line = db_read($rlt))
				{
					return($line);
				}
			return(array());
		}
		
	function issue_view($issue=0)
		{
			$issue = strzero($issue,7);
			$sql = "select * from brapci_article
						left join brapci_section on se_codigo = ar_section
						where ar_edition = '$issue'
						order by se_ordem, se_descricao, ar_pg_inicial
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
			while ($line = db_read($rlt))
				{
					$link = '<A HREF="'.base_url('admin/article_view/'.$line['id_ar']).'/'.checkpost_link($line['id_ar']).'" target="_new_'.$line['id_ar'].'">';
					$sec = trim($line['se_descricao']);
					if ($sec != $xsec)
						{
							$xsec = $sec;
							$sx .= '<tr bgcolor="#F0F0F0"><td class="lt3" colspan=5>'.$sec.'</td></tr>';
							$sx .= $sh;
							$art = 1;
						} else {
							$art++;
						}
					$sx .= '<tr>';
					$sx .= '<td align="center">';
					$sx .= $art.'.';
					$sx .= '</td>';
					
					$sx .= '<td class="lt2">';
					$sx .= $link;
					$sx .= trim($line['ar_titulo_1']);
					$sx .= '</A>';
					$sx .= '</td>';
					
					$sx .= '<td width="60" align="center">';
					$pag = $line['ar_pg_inicial'];
					$sx .= $pag;
					$sx .= '</td>';
					
					$sx .= '<td width="60" align="center">';
					$sta = $line['ar_status'];
					$sx .= $sta;
					$sx .= '</td>';

					$sx .= '</tr>';
				}
			$sx .= '</table>';
			return($sx);
		}
	
	function editions_row($journal='',$idi=0)
		{
			$journal = strzero($journal,7);
			$sql = "select * from ".$this->tabela." 
						where ed_journal_id = '$journal'
						order by ed_ano desc, ed_vol 
			";
			$sx = '<div id="issue_row">';
			$sx .= '<Table class="tabela00" width="100%">';
			$sx .= '<TR><th width="65%">editions</th>
						<th width="35%">status</th></tr>';
			$query = $this->db->query($sql);
			$rlt = $query->result();
			
			$issue = 0;
			while ($line = db_read($rlt))
				{
					if (strlen($this->row) > 0)
						{
							$link = '<A HREF="'.$this->row.'/'.$line['id_ed'].'/'.checkpost_link($line['id_ed']).'" class="link">';
						} else {
							$link = '<A HREF="'.base_url('journal/issue/'.$line['id_ed']).'" class="link">';		
						}
					
					$issue++;
					$sx .= '<TR>';
					$sx .= '<TD class="tabela01">';
					
										
					$sx .= $link;
					
					/* bold */
					$sxf = '';
					if ($idi == $line['id_ed']) { $sx .= '<b>'; $sxf = '</b>';}
					
					$sx .= $line['ed_ano'].', ';					
					$sx .= 'v.'.$line['ed_vol'];
					$sx .= ', n.'.$line['ed_nr'];
					$sx .= $sxf;
					$sx .= '</td>';
					
					$sx .= '<td align="center" class="tabela01">';
					$sta = trim($line['ed_status']);
					$sx .= $link;
					switch($sta)
						{
						case 'A':
							$sx .= '<font color="green">to review<font>';
							break;
						case 'F':
							$sx .= '<font color="orange">revised<font>';
							break;	
						case 'C':
							$sx .= '<font color="orange">revised<font>';
							break;
						case '':
							$sx .= '<font color="green">to review<font>';
							break;																					
						default:
							$sx .= '('.$sta.')';
							break;
						}
					$sx .= '</A>';
					$sx .= '</td>';
					$sx .= '</tr>';
				}
			$sx .= '<TR><TD colspan=10>Total de <B>'.$issue.'</B> edições';
			$sx .= '</table>';
			$sx .= '</div>';
			return($sx);
		}		
	
	function editions($journal='')
		{
			$journal = strzero($journal,7);
			$sql = "select * from ".$this->tabela." 
						where ed_journal_id = '$journal'
						order by ed_ano desc, ed_vol 
			";
			$sx = '<Table class="tabela00 lt2">';
			$query = $this->db->query($sql);
			$rlt = $query->result();
			
			$xano = '';
			$issue = 0;
			while ($line = db_read($rlt))
				{
					$link = '<A HREF="'.base_url('journal/issue/'.$line['id_ed']).'" class="link">';
					$issue++;
					$ano = $line['ed_ano'];
					if ($xano != $ano)
						{
							$sx .= '<TR>';
							$sx .= '<TD>'.$ano;
							$xano = $ano;
						}
					$sx .= '<TD class="tabela01" width="110" align="left">';
					$sx .= $link;
					$sx .= 'v.'.$line['ed_vol'];
					$sx .= ', n.'.$line['ed_nr'];
				}
			$sx .= '<TR><TD colspan=10>Total de <B>'.$issue.'</B> edições';
			$sx .= '</table>';
			return($sx);
		}	
	}
?>
