<?
class cep
	{
	var $id_cep;
	var $protocolo;
	var $line;
	
	var $tabela = 'cep_protocolos';
	
	function le($id = '')
		{
			if (strlen($id) > 0) { $this->id_cep = $id; }
			$sql = "select * from ".$this->tabela." ";
			$sql.= " left join submit_autor on sa_codigo = cep_pesquisador ";
			$sql .= " where id_cep = ".sonumero("0".$this->id_cep);

			$rlt = db_query($sql);
			$this->line = db_read($rlt);
			$this->protocolo = $this->line['cep_protocol'];
			return(1);			
		}
		
	function parecer()
		{
			$sql = "select * from ".$this->tabela." ";
			//$sql .= " left join ".$parecer." on (pr_protocol = cep_protocol) and (pr_versao = cep_versao) ";
			$sql .= " left join usuario on cep_relator = us_codigo ";
			$sql .= " where cep_protocol = '".trim($this->protocolo)."' order by pr_versao desc ";
			$frlt = db_query($sql);
			$sp = '';
			$st = msg('hist_parecer');
			$sp .= '	<TR class="lt0"><TH>'.msg('parecer_nr').'</TH><TH>'.msg('protocol_version').'</TH><TH>'.msg('date').'</TH><TH>'.msg('hour').'</TH>';
			$sp .= '<TH>'.msg('relator').'</TH><TH>'.msg('cep_dt_ciencia_pesq').'</TH><TH>Obs</TH></TR>';
			$col = 1;
			while ($fline = db_read($frlt))
				{
				$enf = ''; if (trim($versao) == trim($fline['cep_versao'])) { $enf = '<B>'; }
				$link = $enf.'<A HREF="parecer_mostrar.php?dd0='.$fline['id_cep'].'&dd1='.$fline['pr_data'].'&dd2='.$fline['pr_hora'].'" target="_blank">';
				$link2 = '<A HREF="protocolo_detalhe.php?dd0='.$fline['id_cep'].'&dd90='.checkpost($fline['id_cep']).'" >';
				$sp .= '<TR valign="top" '.coluna().' class="lt1" >';
				
				$sp .= '<TD align="center">';
				$sp .= $link;
				$sp .= ($fline['pr_nr']);	
			
				$sp .= '<TD align="center">';
				$sp .= $link2;
				$sp .= trim($fline['cep_protocol']);
				$sp .= '</A> ';
				$sp .= $link2.'v.';
				$sp .= trim($fline['cep_versao']);
	
				$dtc = $fline['pr_data'];
				if ($dtc > 19000101) { $dtc = stodbr($dtc); } else { $dtc = '-'; }
				$sp .= '<TD align="center">'.$dtc.'</TD>';	

				$sp .= '<TD align="center">';
				$sp .= trim($fline['pr_hora']);
	
				$sp .= "<TD>".$fline['us_nome'].'</TD>';
				$dtc = $fline['cep_dt_ciencia'];
				if ($dtc > 19000101) { $dtc = stodbr($dtc); } else { $dtc = '-'; }
				$sp .= '<TD align="center">'.$dtc.'</TD>';

				$dtv = '<font color=red >Parecer da v.'.trim($fline['cep_versao']).'</font>';
	
				if (strlen(trim($fline['pr_nr'])) == 0) { $dtv = '<font color="#00cc99" ><B>sem parecer</B>'; }
				$sp .= '<TD align="center">'.$dtv.'</TD>';
				}
				$sx = $sp;
			return($sx);
		}
	function dados()
		{
			$line = $this->line;
			$gr = trim($line['cep_grupo']);
			$versao = $line['cep_versao'];
			if (strlen($gr) == 0)
				{
				$sql = "update ".$pp." set cep_grupo = 'III' where id_cep = ".$line['id_cep'];
				$rrr = db_query($sql);
				}
			
			$codigo = $line['cep_codigo'];
			$sp = '<TR valign="top" >';
			$sp .= '<TD align="center" colspan="4">';
			$sp .= '<font class="lt2"><B>';
			$sp .= trim($line['cep_titulo']);
		
			$sp .= '<TD align="center" width="10%" rowspan="5">';
			$sp .= '<fieldset><legend><font class="lt0">'.msg('protocol').'</legend><NOBR>';
			$sp .= '<font class="lt5">';	
			$sp .= $line['cep_protocol'];
			$sp .= '</font><BR><font class="lt0">';
			$sp .= '<B>'.msg('group').' '.($gr);
			$sp .= ' - ';
			$sp .= 'ver. '.$line['cep_versao'];
			$sp .= '</B>';
			////////////// Status
			$status = trim($line['cep_status']);
			$sta = $status;
			//require("_status.php");
			/////////////////////
			$sp .= '<BR>'.$line['cep_caae'];
			$sp .= '</TR>';
				
			///////////////////////////// Linha 2
			$d1 = $line['cep_dt_liberacao'];
			$d2 = $line['cep_dt_ciencia'];
			$d3 = $line['cep_reuniao'];
			
			if ($d1 < 2000) { $d1 = '&nbsp;&nbsp;-'; } else {$d1 = stodbr($d1); }
			if ($d2 < 2000) { $d2 = '&nbsp;&nbsp;-'; } else {$d2 = stodbr($d2); }
			if ($d3 < 2000) { $d3 = '&nbsp;&nbsp;-'; } else {$d3 = stodbr($d3); }
			
			$sp .= '<TR class="lt0">';
			$sp .= '<TD>'.msg('folha_rosto').'</TD>';
			$sp .= '<TD>'.msg('status').'</TD>';
			$sp .= '<TD>'.msg('data_hora_post').'</TD>';
			$sp .= '<TD>'.msg('data_ciencia').'</TD>';
			$sp .= '</TR>';
			$sp .= '<TR class="lt2" bgcolor="'.$cab_bg.'">';
			$sp .= '<TD width="20%">'.$line['cep_fr'].'</TD>';
			$sp .= '<TD width="20%">'.$rstatus.'</TD>';
			$sp .= '<TD width="20%">'.stodbr($line['cep_data']).' '.$line['cep_hora'].'</TD>';
			$sp .= '<TD width="20%">'.$d2.'</TD>';	
			$sp .= '</TR>';
			
			/////////////////////////////// Linha 3
			$sp .= '<TR>';
			$sp .= '<TD colspan="4" class="lt0">'.msg('pesq_resp').'</TD>';
			$sp .= '	<TR class="lt2" bgcolor="'.$cab_bg.'">';
			$sp .= '<TD colspan="4">'.$line['cep_pesquisador'].'</TD>';
		
			/////////////////////////////// Linha 4
			$sp .= '<TR class="lt0">';
			$sp .= '<TD colspan="4">'.msg('local_pesq').'</TD>';
			$sp .= '<TD width="150">'.msg('data_reuniao').'</TD>';
			$sp .= '<TR class="lt2" bgcolor="'.$cab_bg.'">';
			$sp .= '<TD colspan="4">'.$line['cep_local_realizacao'].'</TD>';
			$sp .= '<TD align="center">'.$d3.'</TD>';
		
			$sp .= '<TR class="lt0">';
			$sp .= '<TD colspan="4">'.msg('pesq_resp').'</TD>';
			$sp .= '<TR class="lt2" bgcolor="'.$cab_bg.'">';
			$sp .= '<TD colspan="4">'.nbr_autor($line['sa_nome'],8).'&nbsp;</TD>';
		
			/////////////////////////////// Linha 5 - Instituições conveniadas
			if (1==2)
			{
				$sp .= '<TR class="lt0">';
				$sp .= '<TD colspan="4">'.msg('inst_part').'</TD>';
				$sp .= '<TD>Tipo</TD>';
				$sp .= '<TR class="lt2" bgcolor="'.$cab_bg.'">';
				$sp .= '<TD colspan="4">&nbsp;</TD>';
				$sp .= '<TD colspan="1">&nbsp;</TD>';
			}	
			return($sp);		
			}
	function resumo_query()
		{
			global $nucleo;
			$sql = "select total, cep_status, ess_descricao_1 as ps_nome from (";
			$sql .= "select count(*) as total, cep_status ";
			$sql .= " from ".$this->tabela;
			$sql .= " group by cep_status ";
			$sql .= ") as tabela ";
			$sql .= " left join cep_status on ess_status = cep_status and ess_nucleo = '".$nucleo."' ";
			$sql .= " order by cep_status ";
			$rlt = db_query($sql);
			$rst = array();
			while ($line = db_read($rlt))
				{
					array_push($rst,$line);
				}
			return($rst);
		}
	function resumo()
		{
			global $colunas;
			$rst = $this->resumo_query();
			$sc = '<table class="lt1">';
			$sc .= '<TR>';
			$sc .= '<TH>'.msg('total');
			$sc .= '<TH>'.msg('tipo');
			for ($r=0;$r < count($rst);$r++)
				{
					$status = trim($line['cep_status']);
					$link = 'protocolo_status.php?dd52='.$status;
					$link = '<A HREF="'.$link.'">';
					$name = trim($line['ps_nome']);
					if (strlen($name)==0) { $name = $line['cep_status']; }
					$line = $rst[$r];
					$sc .= '<TR '.coluna().'>';
					$sc .= '<TD align="center"><B>';
					$sc .= $line['total'];
					$sc .= '<TD>';
					$sc .= $link;
					$sc .= $name;
					$sc .= '</A>';
					$sc .= ' ('.$status.')';
					$sc .= '<TD>';
				}
			$sc .= '</table>';
			return($sc);
		}	
	}
?>
