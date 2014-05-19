<?php
class ticket
	{
		var $tabela = "ticket";
		
		var $line;
		
		function altera_status($id,$sta)
			{
				$sql = "update ".$this->tabela." set tk_status = '".$sta."',
							tk_revisao_data = ".date("Ymd").",
							tk_revisao_hora = '".date("H:i:s")."',
							tk_revisor = '".$user_id."'
						where id_tk = ".round($id);
				echo $sql; 
			}
		
		function le($id)
			{
				$sql = "select * from ".$this->tabela." where id_tk = ".round($id);
				$rlt = db_query($sql);
				if ($line = db_read($rlt))
					{
						$this->line = $line;
					}
				return(1);
			}
		
		function list_bugs($sta)
			{
				$sql = "select * from ".$this->tabela." 
						where
						tk_status = '$sta'
						order by tk_data desc, tk_hora desc
						";
				$rlt = db_query($sql);
				$sx .= '<table>';
				while ($line = db_read($rlt))
					{
						$link = '<A HREF="ticket_task.php?dd0='.$line['id_tk'].'&dd90='.checkpost($line['id_tk']).'" target="new">';
						
						$sx .= '<TR valign="top" style="border-top: 1px solid #000000;">';
						$sx .= '<TD class="lt0" align="center"><NOBR>';
						$sx .= $link.'<B>TICKET'.$line['id_tk'].'</B></A><BR>';
						$sx .= stodbr($line['tk_data']);
						$sx .= '<BR>';
						$sx .= $line['tk_hora'];
						$sx .= '<TD class="lt0" align="center">';
						$sx .= $line['tk_type'];
						$sx .= '<TD class="lt1" align="left">';
						if (strlen(trim($line['tk_field_1']))> 0) { $sx .= $line['tk_field_1'].'<BR>'; }
					}
				$sx .= '</table>';
				return($sx);
			}
		
		function resumo()
			{
				$sql = "select count(*) as total, tk_status 
							from ".$this->tabela."
						group by tk_status	
						order by tk_status
						";
				$rlt = db_query($sql);
				$id = 0;
				$sta = array('A'=>'Aberto');
				while ($line = db_read($rlt))
					{
						$id++;
						$sx .= '<TD width="%%" align="center">';
						$sx .= $sta[$line['tk_status']];
						$sx .= '<BR>';
						$sx .= '<A href="ticket_list.php?dd1='.$line['tk_status'].'">';
						$sx .= '<font style="font-size:40px;" class="link">';						
						$sx .= $line['total'];
						$sx .= '</A>';
						$sx .= '</font>';
					}
				if ($id == 0) { return(''); }
				$size = round(100/$id);
				$sx = '<table width="80%">'.$sx.'</table>';
				$sx = troca($sx,'%%',$size.'%');
				return($sx);
				
			}
		
		function abre_ticket($article,$tipo,$titulo,$resumo,$keyword,$user)
			{
				$data = date("Ymd");
				$hora = date("H:i:s");
				$article = strzero($article,10);
				$wk = array();
				array_push($wk,array($titulo,$idioma));
				array_push($wk,array($resumo,$idioma));
				array_push($wk,array($keyword,$idioma));
				
				for ($r=0;$r < count($wk);$r++)
					{
						$st = trim($wk[$r][0]);
						if (strlen($st) > 0)
							{
							$tt = 'A'.($r+1);
							$sql = "insert into ".$this->tabela." 
								(
									tk_data, tk_hora, tk_user,
									tk_status, tk_type, tk_field_1,
									tk_revisor,
									tk_revisao_data, tk_revisao_hora, tk_aceito,
									tk_article
								) values (
									$data,'$hora','$user',
									'A','$tt','$st',
									'',
									0,'','0',
									'$article'
								)";
								$rlt = db_query($sql);
								echo $sql;
							}
					}
				
			}
	}
