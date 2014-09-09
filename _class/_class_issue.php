<?php
class issue
	{
	var $id;
	var $codigo;
	var $journal;
	
	var $tabela = 'brapci_edition';
	
	function updatex()
			{
				$c = 'ed';
				$c1 = 'id_'.$c;
				$c2 = $c.'_codigo';
				$c3 = 7;
				$sql = "update ".$this->tabela." set $c2 = lpad($c1,$c3,0) where $c2='' ";
				$rlt = db_query($sql);
			}
	
	function articles_for_editions()
		{ 
			$sql = "SELECT count(*) as total, ar_journal_id FROM `brapci_article` ";
			$sql .= " where ar_status <> 'X' ";
			$sql .= "group by ar_journal_id";
			$rlt = db_query($sql);
			$sqlu = "";
			while ($line = db_read($rlt))
				{
				$sqlu = "update brapci_journal set jnl_artigos=".$line['total']." where jnl_codigo='".$line['ar_journal_id']."';".chr(13).chr(10);
				$xrlt = db_query($sqlu);
				}

			$sql = "SELECT count(*) as total, ar_journal_id FROM `brapci_article` WHERE ar_status = 'A' ";
			$sql .= "group by ar_journal_id";
			$rlt = db_query($sql);
			while ($line = db_read($rlt))
				{
				$sqlu = "update brapci_journal set jnl_artigos_indexados=".$line['total']." ";
				$sqlu .= "where jnl_codigo='".$line['ar_journal_id']."';".chr(13).chr(10);
				$xrlt = db_query($sqlu);
				}
			return(1);
		}
	
	function cp()
		{
			global $jid;
			$cp = array();
			if (strlen($jid) > 0)
				{
				$wh = 'jnl_codigo = '.chr(39).strzero($jid,7).chr(39);
				}
			array_push($cp,array('$H8','id_ed','id_ed',False,True,''));
			array_push($cp,array('$H8','ed_codigo','',False,True,''));
			array_push($cp,array('$Q jnl_nome:jnl_codigo:SELECT * FROM brapci_journal where jnl_status <> \'X\''.$wh,'ed_journal_id',msg('publication'),True,True,''));
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
			return($cp);
		}
	function journal_go($jid)
		{
			$sx .= '<A HREF="publications_details.php?dd0='.$jid.'" class="link">';
			$sx .= msg('return_to_journal');
			$sx .= '</A>';
			return($sx);			
		}
	function issue_go($issue)
		{
			$sx .= '<A HREF="publication_issue.php?dd0='.$issue.'" class="link">';
			$sx .= msg('return_to_issue');
			$sx .= '</A>';
			return($sx);
		}
	function issue_legend($issue)
		{
			global $db_apoio,$db_base;
			$this->id = $issue;
			$sql = "select * from brapci_edition
					inner join brapci_journal on jnl_codigo = ed_journal_id 
					left join ".$db_base."ajax_cidade on jnl_cidade = cidade_codigo
					where ed_codigo = '$issue'
			";
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					$this->journal = $line['ed_journal_id'];
					$sx .= '<h2>';
					$sx .= trim($line['jnl_nome']);
					$cid = trim($line['cidade_nome']);
					if (strlen($cid) > 0) { $sx .= ', '.$cid; }
					$vol = $line['ed_vol'];
					$ano = $line['ed_ano'];
					$nr = $line['ed_nr'];
					if (strlen($vol) > 0) { $sx .= ', v. '.$vol; }
					if (strlen($nr) > 0) { $sx .= ', n. '.$nr; }
					if (strlen($ano) > 0) { $sx .= ', '.$ano; }
					$sx .= '</h2>';
				}
			return($sx);
		}
	
	function issue_list()
		{
			$sql = "select * from ".$this->tabela." where ed_journal_id = '".$this->journal."' 
				order by ed_ano desc, ed_vol desc, ed_nr desc
			";
			$rlt = db_query($sql);
			
			$sx .= '<table width="99%" class="lt1" cellpadding=0 cellspacing=2 >';
			$sx .= '<TR><TH>'.msg('year');
			$sx .= '<Th width="12%">'.msg("issue");
			$sx .= '<Th width="12%">'.msg("issue");
			$sx .= '<Th width="12%">'.msg("issue");
			$sx .= '<Th width="12%">'.msg("issue");
			$sx .= '<Th width="12%">'.msg("issue");
			$sx .= '<Th width="12%">'.msg("issue");
			$sx .= '<Th width="12%">'.msg("issue");
			$sx .= '<Th width="12%">'.msg("issue");
			
			$xano = 9999;
			while ($line = db_read($rlt))
				{
					$ano = $line['ed_ano'];
					$link = '<A HREF="journal_issue.php?dd0='.$line['id_ed'].'"><font class="lt0">';
					if ($ano != $xano)
						{
							$sx .= '<TR class="lt1">';
							$sx .= '<TD>'.$line['ed_ano'];
							$xano = $ano;
						}
					
					$vol = $line['ed_vol'];
					if (strlen($vol) > 0) { $vol = 'v. '.$vol; }
					
					$nr = trim($line['ed_nr']);
					if (strlen($nr) > 0) { $nr = ', n. '.$nr; }
					
					$sx .= '<TD class="lt1" align="center">';
					$sx .= $link;
					$sx .= $vol.' '.$nr;
					$sx .= '</A>';
				}
			$sx .= '</table>';				
			return($sx);
			
		}
	}
?>
