<?php
class parecer_journal
	{
	var $protocolo;
	var $data_prazo;
	var $estudante;
	var $orientador;
	var $avaliador;
	var $parecer_data;
	var $nrparecer;
	var $tipo = '2012A';
	var $protocolo;
	var $protocolo_mae;
	var $avaliador;
	var $revisor;
	var $status;
	var $data_leitura;
	var $data;
	var $id_pp;


	var $tabela = "reol_parecer_enviado";
	
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
			$sql .= " where pp_tipo = '".$this->tipo."' ";
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
	function parecer_cp_modelo($mod='')
		{
				$cp = array();
				array_push($cp,array('$H8','id_pp','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
	
				/** Data, hora e estatus da avaliavcao **/
				array_push($cp,array('$HV','pp_parecer_data',date('Yms'),False,True));
				array_push($cp,array('$HV','pp_parecer_hora',date('H:m'),False,True));
				array_push($cp,array('$HV','pp_status','B',False,True));
				$ccor = '<font color=blue >';
		
				/** Field Assunto **/
				$cap = '1) Proposta do artigo para a revista';
				array_push($cp,array('${','',$cap,True,True));

						/** Primeira questão **/
						$opc = '';
						$opc .= '1:<font color="green">Sim, está dentro da proposta da revista<BR>';
						$opc .= '&0:<font color="red">Não, está fora da proposta da revista';
						$cap = '1.1)O trabalho está dentro da proposta da revista';
						array_push($cp,array('$R '.$opc,'pp_p02',$cap,True,True));
					
						/** Segunda questão **/
						$cap = '<B>1.2)Quais são os objetivos da pesquisa</B>';
						array_push($cp,array('$T80:4','pp_abe_01',$cap,True,True));
		
				/** fecha Field **/
				array_push($cp,array('$}','',$cap,True,True));
				
				/** Field Assunto **/
				$cap = '2) Sobre o assunto abordado';
				array_push($cp,array('${','',$cap,True,True));


						/** Primeira questão **/
						$opc = '';
						$opc .= '4:Excelente, tema relavante é inédito<BR>';
						$opc .= '&3:Bom, tema inédito<BR>';
						$opc .= '&3:Ruim, tema já explorado com poucas novidades<BR>';
						$opc .= '&3:Péssimo, tema já explorado pela literatura, sem novidades';
						$cap = "2.1) Originalidade do tema";
						array_push($cp,array('$R '.$opc,'pp_p03',$cap,True,True));


						/** Segunda Questão **/
						$cap = "2.2) Clareza, legibilidade e objetividade (português, organização geral do texto, figuras, gráficos, tabelas, referências, etc.):";
						$opc  = '1:Excelente:Excelente<BR>&';
						$opc .= '2:Bom:Bom<BR>&';
						$opc .= '3:Regular. Muitas correções são necessárias.</font>.:Regular. Muitas correções são necessárias '.$ccor.'(especificá-las nos comentários)</font>.<BR>&';
						$opc .= '4:Ruim. O relatório precisa ser refeito.:Ruim. O relatório precisa ser refeito '.$ccor.'(comentar)</font>.';
						array_push($cp,array('$H8','id_pp','',True,True));
						array_push($cp,array('$R '.$opc,'pp_abe_01',$cap,True,True));
						array_push($cp,array('$T80:4','pp_abe_02',$comentarios,False,True));

						/** Terceira Questão **/
						$cap = $sp.'3.2) Sobre o embasamento teórico:';
						$opc  = '1:Excelente. Com autores alinhados com o assunto.<BR>&';
						$opc .= '2:Bom. Requer a incorporação de autores mais contemporaneos.<BR>&';
						$opc .= '3:Alguns autores não estão adequados ao tema.<BR>&';
						$opc .= '4:Ruim, o assunto abordado não condiz com a literatura atual';
						array_push($cp,array('$R '.$opc,'pp_abe_03',$cap,True,True));
						array_push($cp,array('$T80:4','pp_abe_04',$comentarios,False,True));
			
				/** fecha Field **/
				array_push($cp,array('$}','',$cap,True,True));

				/** Quarta questão **/
				$cap = $sp.'3) Resultados parciais obtidos:';
				$opc  = 'Altamente relevantes para prosseguimento das atividades.:Altamente relevantes para prosseguimento das atividades.<BR>&';
				$opc .= 'As conclusões extraídas dos dados obtidos são satisfatórias.:As conclusões extraídas dos dados obtidos são satisfatórias.<BR>&';
				$opc .= 'Mais conclusões sobre os dados apresentados são necessárias.:Mais conclusões sobre os dados apresentados são necessárias. '.$ccor.'(Comentar)</font><BR>&';
				$opc .= 'Regulares. Dados obtidos não são analisados dificultando a avaliação da sua relevância no contexto do projeto.:Regulares. Dados obtidos não são analisados dificultando a avaliação da sua relevância no contexto do projeto. '.$ccor.'(Comentar)</font><BR>&';
				$opc .= 'Ruins. Poucos (ou nenhum) resultados relevantes no contexto do projeto foram apresentados.:Ruins. Poucos (ou nenhum) resultados relevantes no contexto do projeto foram apresentados. '.$ccor.'(Comentar)</font><BR>&';
				array_push($cp,array('$R '.$opc,'pp_abe_05',$cap,True,True));
				array_push($cp,array('$T80:4','pp_abe_06',$comentarios,False,True));
	
				/** Questão quatro **/
				$cap = $sp.'<B>4) Comentários sobre a avaliação</B><BR>(Justifique aqui as opções marcadas acima, fazendo as sugestões que julgar adequadas para melhorar a qualidade do relatório apresentado e fazendo sua apreciação geral do trabalho).';
				array_push($cp,array('$T80:8','pp_abe_07',$cap,True,True));
		
				/** Quita Questão **/
				$cap = $sp.'5) Resultado da avaliação:';
				$opc  = '1:<font color=Green>Aprovado</font><BR>&';
				$opc .= '2:<font color=rorange>Aprovado, porém necessita de correções.</font><BR>&';
				$opc .= '3:<font color=red>Não aprovado.</font>';
				array_push($cp,array('$R '.$opc,'pp_p01',$cap,True,True));

				array_push($cp,array('$B8','','Finaliza avaliação >>',False,True));
				return($cp);			
			}
	function resumo_avaliador_pendencia($parecerista)
		{
			$sql = "select * from ".$this->tabela." ";
			$sql .= "inner join submit_documento on doc_protocolo = pp_protocolo  ";
			$sql .= "left join journals on doc_journal_id = jnl_codigo ";
			$sql .= " where pp_avaliador = '".$parecerista."' ";
			$sql .= " and pp_status = 'C' and doc_status <> 'X' ";
			$sql .= " order by id_pp desc, pp_protocolo ";	
			
			$rlt = db_query($sql);
			$sx .= '<div><table width="97%" align="center" class="lt1" border=0 >'.chr(13);
			$tot = 0;
			while ($line = db_read($rlt))
				{
					//echo $line['pp_avaliador'].'=='.$line['pp_status'].'<BR>';
					//$sx .= $line['pp_avaliador'].'<BR>';
					$tot++; 
					$sx .= $this->mostra_mini($line);
				}
			$sx .= '</table></div>'.chr(13);
			return(array($tot,$sx));			
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
			$data_prazo = date("Ymd");
			$dias = round(DiffDataDias(date("Ymd"),$data_prazo));
			$data_prazo = stodbr($data_prazo);
	
			$edital = trim(lowercase($line['path']));
			$protocolo = $line['pp_protocolo'];
			$img = 'capa_'.$edital.'.png';
			$titulo_plano = $line['doc_1_titulo'];
			$orientador = $line['pp_nome'];
			$estudante = $line['pa_nome'];
			$ano = $line['pb_ano'];
			$status = $line['pp_status'];
			$id = $line['id_pp'];
			$chk = checkpost($id);
			$link = '<A HREF="avaliacao_journal.php?dd0='.$id.'&dd90='.$chk.'" >';
			$cor="red";
			$sx .= '<TR><TD>';
			$sx .= "<TR valign=top ><TD rowspan=2 width=90>
					<img src=../editora/img_edicao/$img   height=80>
					<TD>Protocolo: <B>".$protocolo."</B></TD>
					<TD>Indicado avaliação:<I> $data_indicacao </I></TD>
					<TD>Prazo para avalição:<I> $data_prazo [$status]</I></TD>
					<TD width=50 aling=center class=lt5 rowspan=2>
					<center><font color=$cor >
					<font class=lt0>faltam<BR></font>$dias<BR>
					<font class=lt0>dias$lido</font></font>
					<TR>
					<TD colspan=3 ><B>".$link.$titulo_plano." </A></TD>
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
