<?php
class parecer
	{
	var $protocolo;
	var $data_prazo;
	var $estudante;
	var $orientador;
	var $avaliador;
	var $parecer_data;
	var $nrparecer;
	var $tipo = '2012P';
	var $protocolo;
	var $protocolo_mae;
	var $avaliador;
	var $revisor;
	var $status;
	var $data_leitura;
	var $data;
	var $id_pp;


	var $tabela = "pibic_parecer_2011";
	
	function tabela_vigente()
		{
			$tabela = "pibic_parecer_".date("Y");
			$sql = "select relname from pg_class 
						where relname = '".$tabela."' and relkind='r'; 
					";
			$rlt = db_query($sql);
			if (!($line = db_read($rlt)))
				{
					echo 'TABELA NÃO EXISTE';
					exit;
				}
			$this->tabela = $tabela;
			return($tabela);
		}
			
	function parecer_abertos($dd1=20100101,$dd2=20500101)
		{
			global $jid;
			$sql = "select * from ".$this->tabela;
			$sql .= " inner join pareceristas on pp_avaliador = us_codigo ";
			$sql .= " inner join submit_documento on pp_protocolo = doc_protocolo ";
			$sql .= " where us_journal_id = ".round($jid);
			$sql .= " and pp_status = 'I' ";
			$sql .= " and (pp_data >= $dd1 and pp_data <= $dd2) ";
			$sql .= " order by us_nome, pp_data desc ";
						
			$rlt = db_query($sql);
			$sx .= '<table width="100%" border=0 class="lt1">';
			$sx .= '<TR><TD colspan=10><H9>Indicações não avaliadas</h9>';
			$sx .= '<BR>&nbsp;&nbsp;<font class="lt0">Indicados entre '.stodbr($dd1).' e '.stodbr($dd2);
			$xnome = 'x';
			$id=0;
			while ($line = db_read($rlt))
				{
					$id++;
					$link = '<A href="#" onclick="newxy2(\'parecer_declinar.php?dd0='.$line['id_pp'].'\',600,400);" class="link">Declinar</A>';
					$nome = trim($line['us_nome']);
					if ($xnome != $nome)
						{
						$sx .= '<TR>';
						$sx .= '<TD colspan=10><h7>'.$line['us_nome'].'</h7>';
						$xnome = $nome;
						}
					$sx .= '<TR valign="top">';			
					
					$sx .= '<TD width="20">&nbsp;';
							
					$sx .= '<TD align="center" class="tabela01">';
					$sx .= stodbr($line['pp_data']);

					$sx .= '<TD align="center">';
					$sx .= $link;

					$sx .= '<TD align="center" class="tabela01">';
					$sx .= $line['pp_protocolo'];
					
					$sx .= '<TD class="tabela01">';
					$sx .= $line['doc_1_titulo'];
					
					$ln = $line;
				}
			$sx .= '<TR><TD colspan=10><B>'.msg('total').' '.$id.'</B>';
			$sx .= '</table>';
			//print_r($ln);
			return($sx);
		}	
	
	function parecer_alterar_status()
		{
			$sql = "update ".$this->tabela." set pp_status = '".$this->status."' where id_pp = ".$this->id_pp;
			$rlt = db_query($sql);
			return(1);			
		}
	
	function le($id)
		{
			$sql = "select * from ".$this->tabela;
			$sql .= " where id_pp = ".sonumero($id);
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					$this->parecer_data = $line['pp_parecer_data'];
					$this->nrparecer = $line['pp_nrparecer'];
					$this->tipo = $line['pp_tipo'];
					$this->protocolo = $line['pp_protocolo'];
					$this->protocolo_mae = $line['pp_protocolo_mae'];
					$this->avaliador = $line['pp_avaliador'];
					$this->revisor = $line['pp_revisor'];
					$this->status = $line['pp_status'];
					$this->data_leitura = $line['pp_data_leitura'];
					$this->data = $line['pp_data'];
					$this->id_pp = $line['id_pp'];
					return(1);
				}
			return(0);
		}
		
	
	function array_status()
		{
			$sta = array('@'=>'não avaliado','A'=>'em análise','B'=>'concluído','C'=>'parecer emitido','D'=>'comunicado orientado','X'=>'Cancelado');
			return($sta);
		}
		
	function avaliacao_parecer_ver()
		{
			global $cp;
			$id = $this->id_pp;
			$sql = "select * from ".$this->tabela;
			$sql .= " where id_pp = ".sonumero($id);
			$rlt = db_query($sql);	
			if ($line = db_read($rlt))
				{
					for ($r=0;$r < count($cp);$r++)
						{
							$tip = trim($cp[$r][0]);
							$fld = trim($cp[$r][1]);
							$cnt = trim($line[$fld]);
							if ($fld == 'pp_p01')
								{
									$tp = trim($line[$fld]);
									$cpx = array('1'=>'Aprovado','2'=>'Não aprovado');
									$cpa = '5) Situação do relatório:';
									$cnt = $cpx[$tp];					
								} else {
									if (substr($tip,0,2) == '$H') { $cnt = ''; }
									if ((strlen($fld) > 0) and (strlen($cnt) > 0))
										{ $cpa = trim($cp[$r][2]); }
								}
							if (strlen($cnt) > 0)
								{
								$sa .= '<BR><BR><B>';
								$sa .= $cpa.'</B>';
								$sa .= '<BR><I>';
								//echo '('.$fld.')'/
								$sa .= $cnt;
								$sa .= '</I>';
								}
						}
					return($sa);
				}
			else
				{ return(0); }
		}

	function resumo_avaliacao()
		{
			// Avaliados
			$status = $this->array_status();
			$sql = "select count(*) as total, pp_status, pp_tipo from ".$this->tabela;
			$sql .= " where (pp_tipo = '".$this->tipo."') ";
			$sql .= " and not(pp_protocolo like 'X%') ";
			$sql .= " group by pp_status, pp_tipo ";
			$rlt = db_query($sql);
			while ($line = db_read($rlt))
				{
					$sx .= '<TR>';
					$sx .= '<TD>';
					$sx .= $status[$line['pp_status']];
					$sx .= '<TD align="center">';
					$sx .= $line['pp_tipo'];
					$sx .= '<TD align="center">';
					$sx .= $line['total'];
				}
			$sa = '<table width=450 align=center class=lt1 >';
			$sa .= '<TR><th>status<TH>tipo<TH>total';
			$sa .= $sx;
			$sa .= '</table>';
			return($sa);
		}
		
	function cancelar_avaliacoes_idicadas($tipo)
		{
			$sql = "update ".$this->tabela." set pp_status = 'X' 
				where pp_status = '@' and pp_tipo = '$tipo' ";
			$rlt = db_query($sql);
		}
		
	function resumo_avaliacao_leitura()
		{
			// Avaliados
			$status = $this->array_status();
			$sql = "select count(*) as total, pp_status, pp_tipo, pp_data_leitura from ".$this->tabela;
			$sql .= " where pp_tipo = '2012P' and pp_status = '@' ";
			$sql .= " group by pp_status, pp_tipo, pp_data_leitura ";
			$sql .= " order by pp_data_leitura ";
			$rlt = db_query($sql);
			while ($line = db_read($rlt))
				{
					$data = stodbr($line['pp_data_leitura']);
					if ($line['pp_data_leitura'] < 20100101)
						{ $data = 'não lido'; }
					$sx .= '<TR>';
					$sx .= '<TD>';
					$sx .= $status[$line['pp_status']];
					$sx .= '<TD align="center">';
					$sx .= $data;
					$sx .= '<TD align="center">';
					$sx .= $line['pp_tipo'];
					$sx .= '<TD align="center">';
					$sx .= $line['total'];
				}
			$sa = '<table width=450 align=center class=lt1 >';
			$sa .= '<TR><th>status<TH>data leitura<TH>tipo<TH>total';
			$sa .= $sx;
			$sa .= '</table>';
			return($sa);		
		}
	
	function mostra_mini($line)
		{
			global $date;
			$lido = $line['pp_data_leitura'];
			if ($lido > 20100101)
				{ $lido = '<BR>	<BR><i>(lido)</I>'; } else
				{ $lido = '<BR><BR><B>não lido</B>'; }
			$edital = lowercase($line['doc_edital']);
			$data_parecer = $line['pp_parecer_data'];			
			$data_indicacao = stodbr($line['pp_data']);
			$data_prazo = "15/03/2011";
			$dias = round(DiffDataDias(date("Ymd"),'20120312'));
			$edital = lowercase($line['doc_edital']);
			$protocolo = $line['pp_protocolo'];
			$img = 'capa_'.$edital.'.png';
			$titulo_plano = $line['pb_titulo_projeto'];
			$orientador = $line['pp_nome'];
			$estudante = $line['pa_nome'];
			$ano = $line['pb_ano'];
			$status = $line['pp_status'];
			$id = $line['id'];
			$chk = checkpost($id);
			$link = '<A HREF="avaliacao_pibic.php?dd0='.$id.'&dd90='.$chk.'" >';
			$cor="red";
			$sx .= '<TR><TD>';
			$sx .= "<TR valign=top ><TD rowspan=6 width=90>
					<img src=../editora/img_edicao/$img   height=80>
					<TD>Protocolo: <B>".$protocolo."</B></TD>
					<TD>Indicado avaliação:<I> $data_indicacao </I></TD>
					<TD>Prazo para avalição:<I> $data_prazo [$status]</I></TD>
					<TD width=10% aling=center class=lt5 rowspan=6>
					<center><font color=$cor >
					<font class=lt0>faltam<BR></font>$dias<BR>
					<font class=lt0>dias$lido</font></font>
					<TR>
					<TD colspan=3 ><B>".$link.$titulo_plano." </A></TD>
					<TR><td colspan=3 class=lt0 >orientador</td></TR>
					<TR><td colspan=3 ><B>".$orientador."</td></TR>
					<TR><td colspan=3 class=lt0 >estudante</td></TR>
					<TR><td colspan=3 ><B>".$estudante."</td></TR>
					</TR>
					<TR><TD colspan=4><HR width=80% size=1></TR>";
			return($sx);	
		}
	function structure()
		{
			$sql = "CREATE TABLE ".$this->tabela."
				( 
				id_pp serial NOT NULL, 
				pp_nrparecer char(7), 
				pp_tipo char(5), 
				pp_protocolo char(7), 
				pp_protocolo_mae char(7), 
				pp_avaliador char(7), 
				pp_revisor char(7), 				
				pp_status char(1), 
				pp_pontos int8 DEFAULT 0, 
				pp_pontos_pp int8 DEFAULT 0, 
				pp_data int8, 
				pp_data_leitura int8,
				pp_hora char(5), 
				pp_parecer_data int8, 
				pp_parecer_hora char(5), 
				pp_p01 char(5), 
				pp_p02 char(5), 
				pp_p03 char(5), 
				pp_p04 char(5), 
				pp_p05 char(5), 
				pp_p06 char(5), 
				pp_p07 char(5), 
				pp_p08 char(5), 
				pp_p09 char(5), 
				pp_p10 char(5), 
				pp_p11 char(5), 
				pp_p12 char(5), 
				pp_p13 char(5), 
				pp_p14 char(5), 
				pp_p15 char(5), 
				pp_p16 char(5), 
				pp_p17 char(5), 
				pp_p18 char(5), 
				pp_p19 char(5), 
				pp_abe_01 text, 
				pp_abe_02 text, 
				pp_abe_03 text, 
				pp_abe_04 text, 
				pp_abe_05 text, 
				pp_abe_06 text,
				pp_abe_07 text, 
				pp_abe_08 text, 
				pp_abe_09 text, 
				pp_abe_10 text 
				); ";
			$rlt = db_query($sql);
		}	
	}
?>
