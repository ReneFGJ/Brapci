<?php
if (strlen($class_parecer) == 0)
{
class parecer_pibic
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



	var $tabela = "pibic_parecer_2011";
	
	function parecer_indicacao()
		{
			$sta = array('@'=>'Não avaliado','A'=>'Finalizado','X'=>'Cancelado','D'=>'Declinou');
			$sql = "select * from ".$this->tabela."
				inner join pareceristas on pp_avaliador = us_codigo
				inner join instituicao on us_instituicao = inst_codigo
				where pp_protocolo = '".$this->protocolo."'  
			";
			
			$rlt = db_query($sql);
			$sx .= '<table width="100%">';
			while ($line = db_read($rlt))
			{
				$link = '<A HREF="javascript:newxy2(\'parecer_declinar.php?dd0='.$line['id_pp'].'&dd90='.checkpost($line['id_pp']).'&dd1='.$this->tabela.'&dd2=DECLINAR\',400,200);">';
				$sx .= '<TR>';
				$sx .= '<TD>';
				$sx .= $line['us_nome'];
				$sx .= '<TD>';
				$sx .= $line['inst_abreviatura'];
				$sx .= '<TD>';
				$sx .= stodbr($line['pp_data']);
				$sx .= '<TD>';
				if ($line['pp_status'] == '@')
					{ $sx .= $link; }
				$sx .= $sta[$line['pp_status']];
			}
			$sx .= '</table>';
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
	
	function parecer_cp_modelo_pl()
		{
				$cp = array();
				array_push($cp,array('$H8','id_pp','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));

				array_push($cp,array('${','','<B>Parecer do Plano do Aluno</B>',False,True));
				/** Primeira questão **/
				$opc = '';
				$opc .= '15:excelente ';
				$opc .= '&13:muito bom ';
				$opc .= '&10:bom ';
				$opc .= '&7:regular ';
				$opc .= '&5:ruim ';
				$opc .= '&1:muito ruim ';
				
				$cap = '<B>Critério 4</B>: Coerência entre o projeto do orientador e o plano de trabalho do aluno, considerando a contribuição para a formação do discente.';		
				array_push($cp,array('$R '.$opc,'pp_p06',$cap,True,True));

				/** Segunda questão **/
				$opc = '';
				$opc .= '15:excelente ';
				$opc .= '&13:muito bom ';
				$opc .= '&10:bom ';
				$opc .= '&7:regular ';
				$opc .= '&1:muito ruim ';
				
				$cap = '<B>Critério 5</B>: Roteiro de atividades do aluno considerando a sua adequação ao processo de iniciação científica.';		
				array_push($cp,array('$R '.$opc,'pp_p07',$cap,True,True));

				/** Terceira questão **/
				$opc = '';
				$opc .= '10:adequado ';
				$opc .= '&7:parcialmente adequado ';
				$opc .= '&1:indequado ';
				
				$cap = '<B>Critério 6</B>: Adequação do cronograma para a execução da proposta.';		
				array_push($cp,array('$R '.$opc,'pp_p08',$cap,True,True));
				return($cp);								
		}	
	
	function parecer_cp_modelo_pp($mod='')
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

						/** Primeira questão **/
						$opc = '';
						$opc .= '20:excelente ';
						$opc .= '&17:muito bom ';
						$opc .= '&15:bom ';
						$opc .= '&12:regular ';
						$opc .= '&7:ruim ';
						$opc .= '&1:muito ruim ';
						
						$opc_sn .= '1:Sim&2:Não';
						$cap = 'Critério 1</B>: Relevância do projeto do orientador e contribuição para a formação do aluno.';		
						array_push($cp,array('$R '.$opc,'pp_p02',$cap,True,True));
					
						/** Segunda questão **/
						$cap = 'Critério 2</B>: Coerência do projeto do orientador de acordo com os itens: Introdução, Objetivo, Método e Referências Bibliográficas.';
						array_push($cp,array('$R '.$opc,'pp_p03',$cap,True,True));
		
						/** Terceira questão **/
						$cap = 'Critério 3</B>: Coerência e adequação entre a capacitação e a experiência do professor orientador proponente e a realização do projeto, considerando as informações curriculares apresentadas.';
						array_push($cp,array('$R '.$opc,'pp_p04',$cap,True,True));
		
						/** Área Estratégica **/
						$cap = 'Área Estratégica</B>: Este projeto foi assinalado pelo professor proponente para concorrer as bolsas de IC de áreas estratégicas (Meio Ambiente e Sustentabilidade) da PUCPR. O projeto se enquadra na área assinalada?';
						array_push($cp,array('$R '.$opc_sn,'pp_p05',$cap,True,True));
						
						/** Planos de Aluno **/

				/** fecha Field **/
				return($cp);			
			}
	function resumo_avaliador_pendencia($parecerista)
		{
			$sql = "select * from ".$this->tabela." ";
			$sql .= "inner join pibic_projetos on pj_codigo = pp_protocolo  ";
			$sql .= " where pp_avaliador = '".$parecerista."' ";
			$sql .= " and pp_status = '@' ";
			$sql .= " order by id_pp desc, pp_protocolo ";	
			
			$rlt = db_query($sql);
			$sx .= '<div><table width="97%" align="center" class="lt1" border=0 >'.chr(13);
			$tot = 0;
			while ($line = db_read($rlt))
				{	
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
			$data_prazo = $line['pp_data'];
			$dias = round(DiffDataDias(date("Ymd"),$data_prazo));
			$data_prazo = stodbr($data_prazo);
	
			$edital = trim(lowercase($line['path']));
			if (strlen($edital) == 0) { $edital = 'pibic'; }
			$protocolo = $line['pp_protocolo'];
			$img = 'capa_'.$edital.'.png';
			$titulo_plano = $line['pj_titulo'];
			$orientador = $line['pp_nome'];
			$estudante = $line['pa_nome'];
			$ano = $line['pb_ano'];
			$status = $line['pp_status'];
			$id = $line['id_pp'];
			$chk = checkpost($id);
			$link = '<A HREF="avaliacao_pibic_submit.php?dd0='.$id.'&dd90='.$chk.'" >';
			$cor="red";
			$sx .= '<TR><TD>';
			$sx .= "<TR valign=top ><TD rowspan=2 width=90>
					<img src=../editora/img_edicao/$img   height=80>
					<TD>Protocolo: <B>".$protocolo."</B></TD>
					
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
			//$sql = "DROP TABLE pibic_parecer_2012";
			//$rlt = db_query($sql);
			
			$sql = "CREATE TABLE ".$this->tabela."
				( 
				id_pp serial NOT NULL, 
				pp_nrparecer char(7), 
				pp_tipo char(5), 
				pp_protocolo char(7), 
				pp_protocolo_mae char(7), 
				pp_avaliador char(8), 
				pp_revisor char(8), 				
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
}
?>
