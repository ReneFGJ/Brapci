<?php
class bonificacao
	{
		var $protocolo;
		var $tabela = 'bonificacao';
		var $origem_protocolo;
		var $origem_tipo;
		var $valor;
		var $professor;
		var $professor_nome;
		var $status;
		var $descricao;
		var $beneficiario;
		var $line;
		var $erro;
		
	function cancelar_form()
		{
			global $dd,$acao,$cp,$saved,$cap,$user;
			$tbela = '';;
			$cp = array();
			array_push($cp,array('$H8','','',True,True));
			array_push($cp,array('$T80:3','','Motivo do cancelamento',True,True));
			array_push($cp,array('$O : &S:SIM','','Confirmar',True,True));		
			editar();
			
			if ($saved > 0)
				{
					$protocolo = $cap->protocolo;
					$historico = 'Cancelado Bonificacao por '.$user->user_login; $ope = 'XBN';
					$this->historico_inserir($protocolo,$ope,$historico);

					$sql = "update ".$this->tabela." set 
							bn_status = 'X'
						where id_bn = ".$dd[0].';'.chr(13);
					$obs = $cap->comentario_adm.chr(13);
					$obs .= '<BR>'.date("d/m/Y H:i").mst($dd[1]).' por '.$user->user_login;
					$sql .= "update captacao set ca_obs_adm = '".$obs."' 
							where ca_protocolo = '".$protocolo."' ";
					$rlt = db_query($sql);			
					
					echo '<h3>Bonificação cancelada</h3>';
				}
			
		}

	function desmembrar_form()
		{
			global $dd,$acao,$cp,$saved;
			$tbela = '';;
			$cp = array();
			array_push($cp,array('$H8','','',True,True));
			array_push($cp,array('$S8','','Cracha pesquisador',True,True));
			array_push($cp,array('$N8','','Valor do repasse',True,True));
			if (strlen($dd[1]) > 0)
				{
					array_push($cp,array('$HV','','1',True,True));
					array_push($cp,array('$O : &S:SIM','','Confirmar',True,True));		
				} else {
					array_push($cp,array('$HV','','',True,True));
				}
			editar();
			
			if ($saved > 0)
				{
					require_once("../_class/_class_docentes.php");
					$doc = new docentes;
					$prof = trim($dd[1]);
					$doc->le($prof);
					$professor_nome = $doc->pp_nome;
					
					if (strlen($professor_nome)==0)
						{
							echo '<h3>Nome do professor não localidado '.$prof.'</h3>';
							exit;
						}
					$professor = $doc->pp_cracha;
					
					$sql = "select * from ".$this->tabela." where
						 bn_original_protocolo = '".trim($this->line['bn_original_protocolo'])."'
						 and bn_original_tipo = 'PRJ' 
						 order by id_bn limit 10";
					$rlt = db_query($sql);

					if ($line = db_read($rlt))
						{
							$proto_original = $line['bn_original_protocolo'];
							$protocolo = $proto_original;
							$historico = 'Desmembrado pagamento '.$user->user_login; $ope = 'DBN';
							$this->historico_inserir($protocolo,$ope,$historico);								
							
							$vlr = $line['bn_valor'];
							if ($vlr >= round($dd[2]))
								{
									$ano = $line['bn_ano'];								
									$data = date("Ymd");
									$hora = date("H:i");
									$desc = substr('Rateado para '.$professor_nome.' do '.$line['bn_nome'],0,100);
									$vlrp = round($dd[2]*100)/100;
									$proto_original = $line['bn_original_protocolo'];
									$tipo = $line['bn_original_tipo'];
									$descricao = substr($line['bn_descricao'].' rateado para '.$professor_nome,0,200);
									$ano = $line['bn_ano'];
									$ano = $line['bn_ano'];
								
							$sql = "insert into ".$this->tabela."							
								(
								bn_codigo, bn_ano, bn_professor,
								bn_professor_nome, bn_professor_cracha, bn_data,
								bn_hora, bn_status, bn_cr,
								
								bn_nome, bn_valor, bn_aprovador,
								bn_ordenador, bn_liberacao, bn_previsao,
								bn_original_protocolo, bn_original_tipo, bn_deneficiario,
								
								bn_renuncia, bn_data_implementacao, bn_beneficiario,
								bn_rf_parcela, bn_rf_valor, bn_modalide,
								bn_descricao
								) values (
								'','$ano','$professor',
								'$professor_nome','$professor',$data,
								'$hora','@','',
								
								'$desc','$vlrp','',
								'',19000101,19000101,
								'$proto_original','$tipo','',
								
								'0','0','',
								'0',0,'',
								'$descricao'); ".chr(13);
							$rlt = db_query($sql);								
							$sql = "update ".$this->tabela." 
									set bn_valor = ".($line['bn_valor']-$vlrp)."
									where id_bn = ".$line['id_bn'];
							$rlt = db_query($sql);
							$this->updatex();
							
							$sql = "select * from ".$this->tabela." where bn_codigo = '".$this->line['bn_codigo']."'; ";
							$rlt = db_query($sql);
							$sql = "";
							if ($line = db_read($rlt))
								{
									$vlr = $line['bn_valor'];
									if ($vlr <= 1)
										{
											$sql .= "update ".$this->tabela." 
												set bn_valor = 0, bn_status = 'X'
												where id_bn = ".$line['id_bn'].';';
											$rlt = db_query($sql);
										}
								}
								
								echo '<H3>Salvo com sucesso!</h3>';							
							} else {
								echo '<H3>Valor inferior ao total!</h3>';
							}
						}
				}
			
		}
		
	function resumo()
		{
			$sql = "update ".$this->tabela." set 
					bn_status = '!' 
					where (bn_status = '*' or bn_status = '#')
					and (bn_original_tipo='IPR')";
			$rlt = db_query($sql);
			
			$sql = "select count(*) as total, bn_original_tipo, bn_status from ".$this->tabela." 
				group by bn_original_tipo, bn_status
				order by bn_original_tipo, bn_status
			";
			$rlt = db_query($sql);
			$api = array(0,0,0,0);
			$apb = array(0,0,0,0);
			while ($line = db_read($rlt))
				{
					$sta = trim($line['bn_status']);
					$tip = trim($line['bn_original_tipo']);
					
					if ($tip == 'IPR')
						{
						if ($sta == '!') { $api[0] = $api[0] + $line['total']; }
						if ($sta == '*') { $api[0] = $api[0] + $line['total']; }
						if ($sta == '#') { $api[0] = $api[0] + $line['total']; }
						if ($sta == 'A') { $api[1] = $api[1] + $line['total']; }
						if ($sta == 'F') { $api[2] = $api[2] + $line['total']; }
						if ($sta == 'G') { $api[2] = $api[2] + $line['total']; }
						if ($sta == 'X') { $api[3] = $api[3] + $line['total']; }
						}
				}
			$sx = '<table class="lt3" width="700" align="center">';
			$sx .= '<TR><TD><TD colspan=4><center><h2>Resumo</h2>';
			$sx .= '<TR>';
			$sx .= '<TH>';
			$sx .= '<TH width="15%">Indicadas';
			$sx .= '<TH width="15%">Para isentar';
			$sx .= '<TH width="15%">Isentas';
			$sx .= '<TH width="15%">Não aplicável';
			
			$sx .= '<TR>';
			$sx .= '<TD align="right">Isenções';
			$sx .= '<TD class="tabela01" align="center">'.$api[0];
			$sx .= '<TD class="tabela01" align="center">'.$api[1];
			$sx .= '<TD class="tabela01" align="center">'.$api[2];
			$sx .= '<TD class="tabela01" align="center">'.$api[3];
			
			$sx .= '<TR class="lt0"><TD>';
			$sx .= '<TD>';
			$sx .= '<A HREF="isencao_detalhes.php?dd0=!">detalhes</A>';
			$sx .= '<TD>';
			$sx .= '<A HREF="isencao_detalhes.php?dd0=A">detalhes</A>';
			$sx .= '<TD>';
			$sx .= '<A HREF="isencao_detalhes.php?dd0=F">detalhes</A>';
			$sx .= '<TD>';
			$sx .= '<A HREF="isencao_detalhes.php?dd0=X">detalhes</A>';


			$sx .= '<TR>';
			$sx .= '<TD align="right">Bonificações';
			$sx .= '<TD class="tabela01" align="center">'.$apb[0];
			$sx .= '<TD class="tabela01" align="center">'.$apb[1];
			$sx .= '<TD class="tabela01" align="center">'.$apb[2];
			$sx .= '<TD class="tabela01" align="center">'.$apb[3];			
			$sx .= '</table>';
			
			echo $sx;
		}
		
	function grafico_isencoes()
		{
			global $gr;
			$cp = "bn_status, bn_original_tipo, bn_modalide";
			$sql = "select $cp, count(*) as total, 
				round(bn_data_implementacao/10000) as d1,
				round(bn_data / 10000) as d2
				 from ".$this->tabela." 
				where bn_original_tipo = 'IPR'
				group by $cp,d1,d2
			";
			$rlt = db_query($sql);
			
			$tot1 = 0;
			$tot2 = 0;
			$arr = array();
			while ($line = db_read($rlt))
				{
					$sta = trim($line['bn_status']);
					if ($sta == 'F')
						{
							$tot1 = $tot1 + $line['total'];
							array_push($arr,array($line['d1'],$line['d2'],$line['total'],$line['bn_status'],$line['bn_status'],$line['bn_modalide'])); 
						}
					if (($sta == '!') or ($sta == '#') or ($sta == '*'))
						{ $tot2 = $tot2 + $line['total']; }
				}
			
			$sx = '>>> Total de isenções '.$tot1.'/'.$tot2;;	
			
			
			$ai = array();
			array_push($ai,array(date("Y"),0,0,0,0,0,0,0)); 
			array_push($ai,array(date("Y")-1,0,0,0,0,0,0,0));
			array_push($ai,array(date("Y")-2,0,0,0,0,0,0,0));
			array_push($ai,array(date("Y")-3,0,0,0,0,0,0,0));
			
			for ($r=0;$r < count($arr);$r++)
				{
					$line = $arr[$r];
					$edital = trim($line[3]);
					$ano = round($line[1]);
					$total = $line[2];
					$ok = 0;
					//echo '<HR>'.$edital.'=='.$ano.'-->'.$total.'<HR>';
					for ($y=0;$y < count($ai);$y++)
						{
							if ($ai[$y][0]==$ano)
								{
									if ($edital == 'F') 
										{
											$mod = $line[5];
											$ii = 3;
											if ($mod == 'M') { $ii = 2; }
											if ($mod == 'D') { $ii = 1; }
											$ai[$y][$ii] = $ai[$y][$ii] + $total; 
										}
								}
						}
				}
			/* legenda */
			
			$hg = 12;
			$legend  = '<img src="'.http.'img/img_icone_boneco_01.png" height="'.$hg.'" title="PIBIC">'.chr(13);
			$legend .= 'Doutorado, ';
			$legend .= '<img src="'.http.'img/img_icone_boneco_02.png" height="'.$hg.'" title="PIBIC">'.chr(13);
			$legend .= 'Mestrado. ';
			
			$sx .= $gr->grafico_bonecos($ai,'isencoes_pos',$legend);
			$sx .= '<A HREF="'.http.'cip/bolsa_isencao_projetos.php?dd0=F" class="lt0">Detalhes</A>';		
			return($sx);
									
		}
	function grafico_bonificacoes()
		{
			
		}
	function cp_editar()
		{
			global $dd;
			$cp = array();
			array_push($cp,array('$H8','id_bn','',false,true));
			array_push($cp,array('$S8','bn_codigo','Código',false,false));
			array_push($cp,array('$S100','bn_professor_nome','Nome',false,false));
			array_push($cp,array('$S8','bn_original_protocolo','Protocolo',false,false));
			
			if ($this->status=='F')
				{
					$op = ' : ';
					$op .= '&F:Isenção finalizada';
					$op .= '&G:Concessão encerrada';					
					array_push($cp,array('$S100','bn_descricao','Descricao',false,false));
					array_push($cp,array('$I8','bn_rf_parcela','Parcela',true,true));
					array_push($cp,array('$N8','bn_rf_valor','Valor',true,true));
					array_push($cp,array('$O'.$op,'bn_status','Status',true,true));
					array_push($cp,array('$O : &M:Mestrado&D:Doutorado','bn_modalide','Modalidade',true,true));
				} else {
					$op = ' : ';
					$op .= '&!:Para ser indicado (!)';
					$op .= '&#:Para ser indicado (#)';
					$op .= '&A:Indicado (em processso de isenção)';
					$op .= '&X:Cancelado isenção';
					$op .= '&E:Aguardando documentação';
					$op .= '&F:Isenção finalizada';					
					$op .= '&G:Concessão encerrada';					

					array_push($cp,array('$O'.$op,'bn_status','Status',true,true));
					array_push($cp,array('$S8','bn_beneficiario','Estudante',false,true));
					array_push($cp,array('$I8','bn_rf_parcela','Parcela',true,true));
					array_push($cp,array('$N8','bn_rf_valor','Valor',true,true));
					array_push($cp,array('$O : &M:Mestrado&D:Doutorado','bn_modalide','Modalidade',false,true));
				}

			return($cp);			
		}

	function cp_frase()
		{
			global $dd;
			
			//$sql = "alter table bonificacao alter column bn_nome TYPE char(200)";
			//$rlt = db_query($sql);
			
			$cp = array();
			array_push($cp,array('$H8','id_bn','',false,true));
			
			array_push($cp,array('$S100','bn_descricao','Descricao',false,false));
			array_push($cp,array('$S6','bn_cr','CR',true,true));
			array_push($cp,array('$S100','bn_nome','Tipo',true,true));
			array_push($cp,array('$T60:5','bn_descricao','Descrição',true,true));
			
			array_push($cp,array('$S20','crp_data_1_str','De',True,True));
			array_push($cp,array('$S20','crp_data_2_str','à',True,True));			
			
			return($cp);			
		}

	function cp()	
		{
			$cp = array();
			array_push($cp,array('$H8','id_bn','',false,true));
			array_push($cp,array('$S8','bn_codigo','Código',false,false));
			array_push($cp,array('$S8','bn_original_protocolo','Protocolo',True,True));
			array_push($cp,array('$S8','bn_professor','Prof. Cracha',True,True));
			array_push($cp,array('$S100','bn_professor_nome','Nome',false,false));
			array_push($cp,array('$T80:4','bn_descricao','Descricao',false,true));
			array_push($cp,array('$I8','bn_rf_parcela','Parcela',true,true));
			array_push($cp,array('$N8','bn_valor','Valor da bonoficação',true,true));
			array_push($cp,array('$N8','bn_rf_valor','Valor',true,true));
			array_push($cp,array('$S1','bn_status','Status',true,true));
			array_push($cp,array('$O : &M:Mestrado&D:Doutorado','bn_modalide','Modalidade',False,true));
			array_push($cp,array('$H8','','Protocolo',false,false));
			array_push($cp,array('$S3','bn_original_tipo','TIPO',true,true));
			array_push($cp,array('$D8','bn_data','Data',true,true));
			array_push($cp,array('$D8','bn_liberacao','Data liberação',true,true));
			array_push($cp,array('$S6','bn_cr','CR',true,true));
			
			
			
			/*
					id_bn serial not null,
					bn_codigo char(7),
					bn_original_protocolo char(7),
					bn_original_tipo char(3),
					bn_ano char(4),
					bn_professor char(8),
					bn_professor_nome char(100),
					bn_professor_cracha char(13),
					bn_data integer,
					bn_hora char(8),
					bn_status char(1),
					bn_descricao text,
					bn_cr char(6),
					bn_nome char(100),
					bn_valor float,
					bn_aprovador char(8),
					bn_ordenador char(8),
					bn_liberacao integer,
					bn_previsao integer,
					bn_modalidade char(1),
					bn_rf_parcela integer,
					bn_rf_valor float
			 */			
			
			return($cp);
		}
		
	function isencao_inicio($data1,$data2)
		{ 
				$sql = "select * from ".$this->tabela."
				inner join pibic_aluno on bn_beneficiario = pa_cracha
				where bn_original_tipo = 'IPR'
				
				order by bn_professor_nome
			";
			$rlt = db_query($sql);
			$sx = '<table width="100%" class="lt1">';
			$sx .= '<TR><TH>Professor<TH>Estudante<TH>Parcelas<TH>Valor';
			$tot=0;
			$tot1=0;
			$tot2=0;
			while ($line = db_read($rlt))
				{
					$tot1 = $tot1 + $line['bn_rf_valor'];
					$tot2 = $tot2 + $line['bn_rf_parcela'];
					$tot = $tot + 1;
					$sx .= '<TR '.coluna().'>';
					$sx .= '<TD>'.$line['bn_professor_nome'];
					$sx .= '<TD>'.$line['pa_nome'];
					$sx .= '<TD align="right">'.$line['bn_rf_parcela'];
					$sx .= '<TD align="right">'.number_format($line['bn_rf_valor'],2,',','.');
					$sx .= '<TD>'.$line['bn_original_protocolo'];
					$sx .= '<TD>'.$line['bn_status'];
					$ln = $line;
				}
			$sx .= '<TR><TD colspan="4" class="lt0">';
			$sx .= 'Total de '.$tot.', no valor total de R$ '.number_format($tot1 * $tot,2,',','.');
			$sx .= ', meses isento '.$tot2;
			$sx .= '</table>';
			//print_r($ln);
			return($sx);
					
		}
		
	function mostra_bonificacoes_por_projeto($projeto)
		{
			global $ged;
			$sql = "select * from ".$this->tabela." 
					left join pibic_aluno on pa_cracha = bn_beneficiario
					where bn_original_protocolo = '$projeto' ";
			$sql .= " order by bn_liberacao desc ";
			$rlt = db_query($sql);
			$sr = '';
			while ($line = db_read($rlt))
			{
				$ged->protocol = $line['bn_codigo'];				
				$tipo = trim($line['bn_original_tipo']);
				$this->line = $line;
				switch ($tipo)
					{						
					case 'IXR':
						$sr .= $this->mostar_isencao();
						break;
					case 'IPR':
						$sr .= $this->mostar_isencao();
						break;
					case 'IPQ':	
						$sr .= $this->mostar_isencao_cnpq();
						break;
					case 'ICP':				
						$sr .= $this->mostar_isencao_capes();
						break;
					case 'PRJ':				
						$sr .= $this->mostar_bonificacao();
						break;
					case 'BNI':					
						$sr .= $this->mostar_bonificacao_artigo();
						break;
					case 'BN2':					
						$sr .= $this->mostar_bonificacao_artigo();
						break;						
					default:
						echo '===>'.$tipo;
						echo 'Não Informado';
					}
				//print_r($line);
				//echo '<HR>';
			}
			$sr .= '<BR><BR>';
			return($sr);
		}
	function mostar_bonificacao()
		{
			global $ss,$nw,$perfil,$ged;
			$sta = trim($this->line['bn_status']);
			$vlr = round($this->line['bn_rf_parcela']);
			$descricao = $this->line['bn_descricao'];
			$dl = stodbr($this->line['bn_liberacao']);
			if (strlen($dl)==0) 
				{
					$dl = '<font color="organge">Não pago</font>';
				}
			if ($sta == '!') { $sta = 'não efetuado pagamento'; }
			if ($sta == 'B') { $sta = 'Pago'; }
			if ($sta == 'A') { $sta = 'Em processo de liberação (Diretoria)'; }
			$sx .= '<fieldset><legend>Bonificação de projetos</legend>';
			$sx .= '<table width=100% class="lt1">';
			$sx .= '<TR><TD rowspan=6 width="30">';
			if ($vlr == 5)
				{
				$sx .= '<IMG SRC="../img/label_5percente_blue.png" height="60">';
				} else {			
				$sx .= '<IMG SRC="../img/label_3percente_green.png" height="60">';
				}
			//$sx .= '<TD>'.$this->line['bn_descricao'];
			$sx .= '<TD>Beneficiário: <B>'.$this->line['bn_professor_nome'].'</B>';
			$sx .= '<TR>';
			$sx .= '<TD>Data de processamento: <B>'.stodbr($this->line['bn_data']).'</B>';
			$sx .= ' protocolo: '.$this->line['bn_codigo'];
			$sx .= '<TR><TD>'.$descricao.'';
			if ($perfil->valid('#SCR#ADM'))
				{
					$link = 'onclick="newxy2(\'bonificacao_ed_frs.php?dd0='.$this->line['id_bn'].'\',600,500);" ';
					$sx .= ' (<A HREF="#" class="link" '.$link.'>editar</A>)';
				}			$sx .= '<TR><TD>Situação <B>'.$sta.'</B>';
			$sx .= '<TR><TD>Valor da Bonificação <B>'.number_format($this->line['bn_valor'],2,',','.');
			$sx .= '</B> pago em '.$dl;
			$sx .= '<TR><TD>CR: '.$cr.' '.$this->line['bn_cr'];
				
			$sx .= '</table>';
			
			$sr = $ged->filelist();
			if ($ged->total_files > 0) { $sx .= $sr; }
			
			if ($perfil->valid('#SCR#ADM'))
				{
				
				$sx .= $ged->upload_botton();
				}		
			$sx .= '</fieldset>';
			return($sx);
		}		

	function mostar_bonificacao_artigo()
		{
			global $ss,$nw,$perfil,$ged;
			$this->updatex();
			$sta = trim($this->line['bn_status']);
			$vlr = round($this->line['bn_rf_parcela']);
			$descricao = $this->line['bn_descricao'];
			$dl = stodbr($this->line['bn_liberacao']);
			if (strlen($dl)==0) 
				{
					$dl = '<font color="organge">Não pago</font>';
				}
			if ($sta == '!') { $sta = 'não efetuado pagamento'; }
			if ($sta == 'B') { $sta = 'Pago'; }
			if ($sta == 'A') { $sta = 'Em processo de liberação (Diretoria)'; }
			$sx .= '<fieldset><legend>Bonificação de projetos</legend>';
			$sx .= '<table width=100% class="lt1">';
			$sx .= '<TR><TD rowspan=6 width="30">';
			$sx .= '<IMG SRC="../img/label_artigo.png" height="60">';
			//$sx .= '<TD>'.$this->line['bn_descricao'];
			$sx .= '<TD>Beneficiário: <B>'.$this->line['bn_professor_nome'].'</B>';
			$sx .= '<TR>';
			$sx .= '<TD>Data de processamento: <B>'.stodbr($this->line['bn_data']).'</B>';
			$sx .= ' protocolo: '.$this->line['bn_codigo'];
			$sx .= '<TR><TD>'.$descricao.'';
			if ($perfil->valid('#SCR#ADM'))
				{
					$link = 'onclick="newxy2(\'bonificacao_ed_frs.php?dd0='.$this->line['id_bn'].'\',600,500);" ';
					$sx .= ' (<A HREF="#" class="link" '.$link.'>editar</A>)';
				}			$sx .= '<TR><TD>Situação <B>'.$sta.'</B>';
			$sx .= '<TR><TD>Valor da Bonificação <B>'.number_format($this->line['bn_valor'],2,',','.');
			$sx .= '</B> pago em '.$dl;
			$sx .= '<TR><TD>CR: '.$cr.' '.$this->line['bn_cr'];
				
			$sx .= '</table>';
			
			$sr = $ged->filelist();
			if ($ged->total_files > 0) { $sx .= $sr; }
			
			if ($perfil->valid('#SCR#ADM'))
				{
				if (strlen($ged->count) == 0)
					{
						$sx .= '<A HREF="formulario_pagamento.php?dd0='.$ged->protocol.'" target="_novo_formulario">Imprimir Formulário de pagamento</A>';
						$sx .= ' ';
					}
				$sx .= $ged->upload_botton();
				
				}		
			$sx .= '</fieldset>';
			return($sx);
		}		

	function mostar_isencao()
		{
			global $ss,$nw,$perfil,$ged;
			$line = $this->line;
			//print_r($line);
			$sta = trim($this->line['bn_status']);
			$proto = trim($this->line['bn_codigo']);
			if ($sta == '!') { $sta = 'Não Indicado'; }
			if ($sta == '#') { $sta = 'Não informado pesquisador'; }
			if ($sta == 'A') { $sta = 'Aguardando Liberação da Diretoria de Pesquisa'; }
			if ($sta == 'X') { $sta = '** Cancelado **'; }
			if ($sta == 'F') { $sta = 'Isentado'; }
			$sx .= '<fieldset><legend>Isenção de mensalidade</legend>';
			$sx .= '<table width=100% class="lt1" border=0>';
			$sx .= '<TR><TD rowspan=4 width="30">';
			$sx .= '<IMG SRC="../img/icone_academic.png" height="50">';
			$nome = trim($this->line['bn_beneficiario']);
			if (strlen($nome) == 0) 
				{ $nome = '<font color="orange">Não implementado</font>'; }
			
			$sx .= '<TD>Beneficiario: '.$nome;
			$sx .= '</B>';
			$sx .= '<TD>Data de processamento: <B>'.stodbr($this->line['bn_data']).'</B>';

			$sx .= '<TD width="50" align="center">';
			if ($perfil->valid('#SCR#ADM'))
				{
					$linkx = '<span class="link" onclick="newxy2(\'bonificacao_ed.php?dd0='.$line['id_bn'].'&dd90='.checkpost($line['id_bn'].'bon2012').'\',600,400);">';
					$linkx .= 'Editar';
					$linkx .= '</span>';
					$sx .= $linkx;
				}
			$sx .= '<TR>';
			$sx .= '<TD colspan=2>';
			
			if ($this->line['bn_status'])
				{ $sx .= '<B>'.$line['pa_nome'].'</B>'; }
			
			$dt = $line['bn_rf_parcela'];
			
			if ($dt > 0)
				{
				$sx .= '<TR><TD>Benefício: '.$this->line['bn_rf_parcela'];
	//			$sx .= ', na modalidade de ';
	//			$mod = $this->line[''];
				$sx .= ' x R$ '.number_format($this->line['bn_rf_valor'],2,',','.');
				$sx .= ' = R$ <B>'.number_format($this->line['bn_rf_parcela'] * $this->line['bn_rf_valor'],2,',','.').'</B>';
				}
			$sx .= '<TR><TD><B>'.$sta.' no protocolo '.$proto.'</B>';			
			$sx .= '</table>';
			
			$sr = $ged->filelist();
			if ($ged->total_files > 0) { $sx .= $sr; }
			
			if ($perfil->valid('#SCR#ADM'))
				{
				$sx .= $ged->upload_botton();
				}
			
			$sx .= '</fieldset>';		
			return($sx);
		}
		
	function mostar_isencao_capes()
		{
			global $ss,$nw,$perfil,$ged;
			$line = $this->line;
			//print_r($line);
			$sta = trim($this->line['bn_status']);
			$proto = trim($this->line['bn_codigo']);
			if ($sta == '!') { $sta = 'Não Indicado'; }
			if ($sta == '#') { $sta = 'Não informado pesquisador'; }
			if ($sta == 'A') { $sta = 'Aguardando Liberação da Diretoria de Pesquisa'; }
			if ($sta == 'X') { $sta = '** Cancelado **'; }
			if ($sta == 'F') { $sta = 'Isentado'; }
			$sx .= '<fieldset><legend>Isenção de mensalidade</legend>';
			$sx .= '<table width=100% class="lt1" border=0>';
			$sx .= '<TR><TD rowspan=4 width="30">';
			$sx .= '<IMG SRC="../img/icone_academic_capes.png" height="50">';
			$nome = trim($this->line['bn_beneficiario']);
			if (strlen($nome) == 0) 
				{ $nome = '<font color="orange">Não implementado</font>'; }
			
			$sx .= '<TD>Beneficiario: '.$nome;
			$sx .= '</B>';
			$sx .= '<TD>Data de processamento: <B>'.stodbr($this->line['bn_data']).'</B>';

			$sx .= '<TD width="50" align="center">';
			if ($perfil->valid('#SCR#ADM'))
				{
					$linkx = '<span class="link" onclick="newxy2(\'bonificacao_ed.php?dd0='.$line['id_bn'].'&dd90='.checkpost($line['id_bn'].'bon2012').'\',600,400);">';
					$linkx .= 'Editar';
					$linkx .= '</span>';
					$sx .= $linkx;
				}
			$sx .= '<TR>';
			$sx .= '<TD colspan=2>';
			
			if ($this->line['bn_status'])
				{ $sx .= '<B>'.$line['pa_nome'].'</B>'; }
			
			$dt = $line['bn_rf_parcela'];
			
			if ($dt > 0)
				{
				$sx .= '<TR><TD>Benefício: '.$this->line['bn_rf_parcela'];
	//			$sx .= ', na modalidade de ';
	//			$mod = $this->line[''];
				$sx .= ' x R$ '.number_format($this->line['bn_rf_valor'],2,',','.');
				$sx .= ' = R$ <B>'.number_format($this->line['bn_rf_parcela'] * $this->line['bn_rf_valor'],2,',','.').'</B>';
				}
			$sx .= '<TR><TD><B>'.$sta.' no protocolo '.$proto.'</B>';			
			$sx .= '</table>';
			
			$sr = $ged->filelist();
			if ($ged->total_files > 0) { $sx .= $sr; }
			if ($perfil->valid('#SCR#ADM'))
				{
				$sx .= $ged->upload_botton();
				}			
			$sx .= '</fieldset>';		
			return($sx);
		}

	function mostar_isencao_cnpq()
		{
			global $ss,$nw,$perfil,$ged;
			$line = $this->line;
			//print_r($line);
			$proto = trim($this->line['bn_codigo']);
			$sta = trim($this->line['bn_status']);
			if ($sta == '!') { $sta = 'Não Indicado'; }
			if ($sta == '#') { $sta = 'Não informado pesquisador'; }
			if ($sta == 'A') { $sta = 'Aguardando Liberação da Diretoria de Pesquisa'; }
			if ($sta == 'X') { $sta = '** Cancelado **'; }
			if ($sta == 'F') { $sta = 'Isentado'; }
			$sx .= '<fieldset><legend>Isenção de mensalidade</legend>';
			$sx .= '<table width=100% class="lt1" border=0>';
			$sx .= '<TR><TD rowspan=4 width="30">';
			$sx .= '<IMG SRC="../img/icone_academic_cnpq.png" height="50">';
			$nome = trim($this->line['bn_beneficiario']);
			if (strlen($nome) == 0) 
				{ $nome = '<font color="orange">Não implementado</font>'; }
			
			$sx .= '<TD>Beneficiario: '.$nome;
			$sx .= '</B>';
			$sx .= '<TD>Data de processamento: <B>'.stodbr($this->line['bn_data']).'</B>';

			$sx .= '<TD width="50" align="center">';
			if (($perfil->valid('#SCR#ADM')))
				{
					$linkx = '<span class="link" onclick="newxy2(\'bonificacao_ed.php?dd0='.$line['id_bn'].'&dd90='.checkpost($line['id_bn'].'bon2012').'\',600,400);">';
					$linkx .= 'Editar';
					$linkx .= '</span>';
					$sx .= $linkx;
				}
			$sx .= '<TR>';
			$sx .= '<TD colspan=2>';
			
			if ($this->line['bn_status'])
				{ $sx .= '<B>'.$line['pa_nome'].'</B>'; }
			
			$dt = $line['bn_rf_parcela'];
			
			if ($dt > 0)
				{
				$sx .= '<TR><TD>Benefício: '.$this->line['bn_rf_parcela'];
	//			$sx .= ', na modalidade de ';
	//			$mod = $this->line[''];
				$sx .= ' x R$ '.number_format($this->line['bn_rf_valor'],2,',','.');
				$sx .= ' = R$ <B>'.number_format($this->line['bn_rf_parcela'] * $this->line['bn_rf_valor'],2,',','.').'</B>';
				}
			$sx .= '<TR><TD><B>'.$sta.' no protocolo '.$proto.'</B>';		
			$sx .= '</table>';
			
			$sr = $ged->filelist();
			if ($ged->total_files > 0) { $sx .= $sr; }
			if ($perfil->valid('#SCR#ADM'))
				{
				$sx .= $ged->upload_botton();
				}			
			$sx .= '</fieldset>';		
			return($sx);
		}

	function alterar_status($de,$para)
		{
			$protocolo = $this->protocolo;
			$historico = '';
			
			$sql = "select * from ".$this->tabela." where bn_codigo = '$protocolo' and bn_status = '$de' ";
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
				if ($para == 'F') { $historico = 'Finalização da ativação da Isenção'; $ope = 'FAT'; }
				$this->historico_inserir($protocolo,$ope,$historico);
				
				$sql = "update ".$this->tabela." set bn_status = '$para' where bn_codigo = '$protocolo' and bn_status = '$de' ";
				$rlt = db_query($sql);
				}
						
		}
		
	function comunica_efetivacao_isencao()
		{
			global $jid;
			$txt = 'bon_isencao_estu';
			$jid = 0;
			$ic = new ic;
			
			$tt = $ic->ic($txt);
			$subj = $tt['nw_titulo'];
			$txt = $tt['nw_descricao'];
			
			$est = $this->beneficiario;
			$pro = $this->professor;
			
			$sql  = "select * from pibic_professor where pp_cracha = '$pro' ";
			$rlt = db_query($sql);
			$pline = db_read($rlt);
			$email1 = trim($pline['pp_email']);
			$email2 = trim($pline['pp_email_1']);	

			$sql  = "select * from pibic_aluno where pa_cracha = '$est' ";
			$rlt = db_query($sql);
			$aline = db_read($rlt);
			$email3 = trim($aline['pa_email']);
			$email4 = trim($aline['pa_email_1']);	
			$aluno = trim($aline['pa_nome']);
			
			$txt = troca($txt,'$aluno',$aluno);

			require("_email.php");
			$email = 'monitoramento@sisdoc.com.br';
			enviaremail($email,'',$subj,$txt);
			$email = 'cip@pucpr.br';
			enviaremail($email,'',$subj,$txt);
			if (strlen($email1) > 0) { enviaremail($email1,'',$subj,$txt); }
			if (strlen($email2) > 0) { enviaremail($email2,'',$subj,$txt); }
			if (strlen($email3) > 0) { enviaremail($email3,'',$subj,$txt); }
			if (strlen($email4) > 0) { enviaremail($email4,'',$subj,$txt); }
			
		}
		
	function isencao_produtividade_ativar_estudante()
		{
			global $dd;
			$vlr = $dd[16];
			$par = $dd[15];
			$proto = trim($this->protocolo);
			$sql = "update bonificacao set 
				bn_rf_valor = $vlr,
				bn_rf_parcela = $par
				where bn_codigo = '$proto'				
			";
			$rlt = db_query($sql);
			
			$this->alterar_status('*','F');
			$this->comunica_efetivacao_isencao();
			return(1);		
		}
		
	function termo_isencao_pdf()
		{
			global $doc,$dis;
			$pdf = new FPDF();
			
			$doc = new docentes;
			$doc->le($this->professor);
			$dis = new discentes;
			$dis->le('',$this->beneficiario);
			
			$textob = 'Protocolo de isenção '.trim($this->protocolo);
			$textob .= ' referente ao projeto '.$this->origem_protocolo;
			/*
			 * Orientação */
			$doc->orientador_da_discente($this->beneficiario);
			$mod = array('D'=>'Doutorado','M'=>'Mestrado');
			$modalidade = $mod[$doc->line['od_modalidade']];
			$ano_ingresso = $doc->line['od_ano_ingresso'];

			$pdf->AddPage();
			$pdf->Image('../img/logo_instituicao.jpg',10,6,20);
			$pdf->SetFont('Arial','B',16);
			
			$pdf->Cell(200,20,'Termo de Isenção para Estudante ',0,0,'C',false);
			$pdf->ln(6);	
			$pdf->Cell(200,20,'de Pós-Graduação Stricto Sensu',0,0,'C',false);

			$Y=47;
			$pdf->SetFont('Arial','B',14);
			$pdf->SetXY(10,$Y);	
			$pdf->Cell(200,0,'Dados do professor orientador',0,0,'L',false);
			$pdf->ln(5);
			/* Dados do professor */
			$Y=50;
			$pdf->SetFont('Arial','',6);
			$pdf->SetXY(10,$Y);
			$pdf->Cell(160,3,'NOME',1,1,'L',false);
			$pdf->SetXY(170,$Y);
			$pdf->Cell(30,3,'CÓD. FUNC.',1,1,'L',false);

			$pdf->SetFont('Arial','',12);
			$pdf->SetXY(10,$Y+3);
			$pdf->Cell(160,5,$doc->pp_nome,1,1,'L',false);
			$pdf->SetXY(170,$Y+3);
			$pdf->Cell(30,5,$doc->pp_cracha,1,1,'L',false);
			
			$Y=58;			
			$pdf->SetFont('Arial','',6);
			$pdf->SetXY(10,$Y);
			$pdf->Cell(30,3,'CPF',1,1,'L',false);
			$pdf->SetXY(40,$Y);
			$pdf->Cell(160,3,'PROGRAMA DE PÓS-GRADUAÇÃO',1,1,'L',false);

			$pdf->SetFont('Arial','',12);
			$pdf->SetXY(10,$Y+3);
			$pdf->Cell(30,5,$doc->pp_cpf,1,1,'L',false);
			$pdf->SetXY(40,$Y+3);
			$pdf->Cell(160,5,$dis->pa_curso,1,1,'L',false);
			
			/* dADOS DO ESTUDANTE */			
			
			$Y=71;
			$pdf->SetFont('Arial','B',14);
			$pdf->SetXY(10,$Y);	
			$pdf->Cell(200,0,'Dados do estudante',0,0,'L',false);
			
			$Y=74;						
			$pdf->SetFont('Arial','',6);
			$pdf->SetXY(10,$Y);
			$pdf->Cell(160,3,'NOME',1,1,'L',false);
			$pdf->SetXY(170,$Y);
			$pdf->Cell(30,3,'CÓD. CRACHÁ',1,1,'L',false);

			$pdf->SetFont('Arial','',12);
			$pdf->SetXY(10,$Y+3);
			$pdf->Cell(160,5,$dis->pa_nome,1,1,'L',false);
			$pdf->SetXY(170,$Y+3);
			$pdf->Cell(30,5,$dis->pa_cracha,1,1,'L',false);
			
			$Y=82;			
			$pdf->SetFont('Arial','',6);
			$pdf->SetXY(10,$Y);
			$pdf->Cell(30,3,'CPF',1,1,'L',false);
			$pdf->SetXY(40,$Y);
			$pdf->Cell(120,3,'MODALIDADE',1,1,'L',false);
			$pdf->SetXY(160,$Y);
			$pdf->Cell(40,3,'ANO INGRESSO',1,1,'L',false);

			$pdf->SetFont('Arial','',12);
			$pdf->SetXY(10,$Y+3);
			$pdf->Cell(30,5,$dis->pa_cpf,1,1,'L',false);
			$pdf->SetXY(40,$Y+3);
			$pdf->Cell(120,5,$modalidade,1,1,'L',false);
			$pdf->SetXY(160,$Y+3);
			$pdf->Cell(40,5,$ano_ingresso,1,1,'C',false);


			/* */
			$Y=94;			
			$pdf->SetFont('Arial','',14);
			$pdf->SetXY(10,$Y);
			$texto = 'Condições Gerais';
			$pdf->Cell(190,5,$texto,0,1,'C',false);
			$Y=105;			
			$pdf->SetFont('Arial','',12);
			$pdf->ln(8);
			$texto = 'Ao aceitar a concessão, que ora lhe é feita, compromete-se o(a) bolsista a dedicar-se às ';
			$texto .= 'atividades pertinentes à bolsa concedida, de acordo com o ato normativo 01/2012 da PRPPG.';
			$pdf->MultiCell(190,5,$texto,0,'J',false);
			
			$texto = 'Compromete-se, ainda, o(a) bolsista a:'.chr(13).chr(10);
			$texto .= 'a) Dedicar-se no mínimo 20 horas semanais ao programa;'.chr(13).chr(10);
			$texto .= 'b) Manter um bom desempenho acadêmico que será atestado pelo(a) orientador(a);'.chr(13).chr(10);
			$texto .= 'c) Observar as determinações do(a) orientador(a) alusivas ao bom desenvolvimento da pesquisa.'.chr(13).chr(10);
			$texto .= chr(13).chr(10);
			$texto .= chr(13).chr(10);
			$texto .= chr(13).chr(10);
			$texto .= $textob;
			$pdf->MultiCell(190,5,$texto,0,'J',false);
			$pdf->ln(10);
			
			$pdf->SetXY(10,230);
			$pdf->Cell(120,3,'__________________________________',0,0,'L',false);			
			$pdf->SetXY(10,236);
			$pdf->Cell(80,3,'Assinatura do Estudante',0,0,'C',false);			
			$pdf->SetXY(10,242);
			$pdf->Cell(80,3,trim($dis->pa_nome),0,0,'C',false);			
					
			$pdf->SetXY(110,230);
			$pdf->Cell(80,3,'__________________________________',0,0,'L',false);			
			$pdf->SetXY(110,236);
			$pdf->Cell(80,3,'Assinatura do Orientador',0,0,'C',false);
			$pdf->SetXY(110,242);
			$pdf->Cell(80,3,trim($doc->pp_nome),0,0,'C',false);			

			$pdf->SetXY(10,260);
			$pdf->Cell(80,3,'__________________________________',0,0,'L',false);			
			$pdf->SetXY(10,266);
			$pdf->Cell(80,3,'Assinatura do Coordenador do Programa',0,0,'C',false);
			$pdf->SetXY(10,272);
			$pdf->Cell(80,3,trim($doc->coordenador_nome),0,0,'C',false);			

			$mes = nomemes(round(date("m")));
			$mes = Uppercase(substr($mes,0,1)).substr($mes,1,30);
			$pdf->SetXY(110,272);
			$pdf->Cell(80,3,'Curitiba, '.date("d").' de '.$mes.' de '.date("Y").'.',0,0,'L',false);			
			
			$pdf->Output();

		}
	function indentifica_isencao($sta)
		{
			if ($sta=='F')
			{
			$sql = "select * from ".$this->tabela."
				inner join captacao on bn_original_protocolo = ca_protocolo
				left join pibic_professor on ca_professor = pp_cracha
				left join pibic_aluno on bn_beneficiario = pa_cracha
				where (bn_status = 'F' or bn_status = 'G') and bn_original_tipo='IPR'
				order by bn_modalide, pp_nome
				";
			} else {
			$sql = "select * from ".$this->tabela."
				inner join captacao on bn_original_protocolo = ca_protocolo
				left join pibic_professor on ca_professor = pp_cracha
				left join pibic_aluno on bn_beneficiario = pa_cracha
				where bn_status = '$sta' and bn_original_tipo='IPR'
				order by bn_modalide, pp_nome
				";				
			}
			$rlt = db_query($sql);
			return($rlt);
		}
	function bonificacao_prof_projetos($sta)
		{
			//$sql = "delete from ".$this->tabela." where bn_original_tipo = 'IPR' ";
			//$rlt = db_query($sql);
			$wh = '';
			if (strlen($sta) > 0)
			{ $wh = " bn_status = '$sta' "; }
			if ($sta == '@')
				{ $wh .= ' and (bn_status isnull)';	}
				
			if (strlen($wh) > 0) { $wh = ' where '.$wh; }
			
			$dd2 = date("Ymd");
				$sql = "select * from captacao 
					left join pibic_professor on ca_professor = pp_cracha
					left join ".$this->tabela." on (bn_original_protocolo = ca_protocolo) 
							and (bn_original_tipo = 'IPR')				
					left join pibic_aluno on bn_beneficiario = pa_cracha
					$wh
					order by bn_modalide, pp_nome ";	
				echo '<TT>'.$sql.'</TT>';					
				$rlt = db_query($sql);
				return($rlt);
				
		}

/* */
	function isencao_proj_mostra_F($rlt)
		{
			//$sql = "ALTER TABLE ".$this->tabela." ADD COLUMN bn_modalide char(1)";
			//$rlt = db_query($sql);
			$sx .= '<table>';
			$sx .= '<TR>';
			$sx .= '<TD colspan=10><h1>'.msg('relatorio_isencoes').'</h1>';
			$sx .= '<TR>';
			$sx .= '<TH>'.msg('protocolo');
			$sx .= '<TH>'.msg('protocolo');
			$sx .= '<TH>'.msg('orientador');
			$sx .= '<TH>'.msg('cracha');
			$sx .= '<TH>'.msg('ativacao');
			$sx .= '<TH>'.msg('estudante');
			$sx .= '<TH>'.msg('parc.');
			$sx .= '<TH>'.msg('valor');
			$sx .= '<TH>'.msg('modalidade');
			$id = 0;
			$xmod = 'x';
			$modt = 0;
			while ($line = db_read($rlt))
				{
					$link = '<A class="lt1" HREF="'.http.'/pibicpr/discente.php?dd0='.$line['id_pa'].'&dd90='.checkpost($line['id_pa']).'">';
					$link2 = '<A class="lt1" href="'.http.'/cip/captacao_detalhe.php?dd0='.$line['id_ca'].'">';
					$id++;
					$ln = $line;
					
					$mod = trim($ln['bn_modalide']);
					if ($mod != $xmod)
						{
							if ($modt > 0)
							{
							$sx .= '<TR><TD align="right" colspan=10>';
							$sx .= '<I>total '.$modt;
							}
							$modt = 0;
							$xmod = $mod;
						}
					
					$sx .= '<TR>';
					$sx .= '<TD>';
					$sx .= $link2;
					$sx .= $line['ca_protocolo'];
					$sx .= '</A>';
					
					$sx .= '<TD>';
					$sx .= $line['bn_codigo'];					
					
					$sx .= '<TD>';
					$sx .= $line['pp_nome'];
					
					$sx .= '<TD>';
					$sx .= $line['bn_beneficiario'];					
					
					$sx .= '<TD>';
					$sx .= $line['bn_data_implementacao'];
					
					$sx .= '<TD>'.$link;
					$sx .= $line['pa_nome'];
					$sx .= '</A>';
					
					$sx .= '<TD align="center">';
					$sx .= number_format($line['bn_rf_parcela'],0).'x';
					$sx .= '<TD align="right">';
					$sx .= number_format($line['bn_rf_valor'],2);
					
					$sx .= '<TD>';
					$sx .= msg('mod_'.$line['bn_modalide']);	
					$modt++;				
				}
			$sx .= '<TR><TD align="right" colspan=10>';
			$sx .= '<I>total '.$modt;
			
			$sx .= '<TR><TD align="right" colspan=10>';
			$sx .= '<I><B>total geral '.$id.'</B>';

			$sx .= '</table>';
			
			if ($id == 0)
				{
					$sx = '';
				}
		
			return($sx);
		}

	function isencao_proj_mostra($rlt)
		{
			global $tab_max;
			$doc = new docentes;
			$sx .= '<table width="'.$tab_max.'" class="table_proj" border=0>';
			//$sx .= '<TR><TH>Nome<TH>Tít.<TH>Produtivade<TH>SS<TH>Cracha<TH>Campus<TH>Escola<TH>Atualizado<TH>Isenção';
			$tot = 0;
			$prod = $doc->produtividade();
			while ($line = db_read($rlt))
				{
					$ln = $line;
					
					$linkp = '<A HREF="http://www2.pucpr.br/reol/cip/captacao_detalhe.php?dd0='.$line['id_ca'].'&dd90='.checkpost($line['id_ca']).'" Target="new" >';
					
					$ano = round(trim($line['ca_vigencia_ini_ano']).strzero($line['ca_vigencia_ini_mes'],2).'01');
					$anof = round(trim($line['ca_vigencia_fim_ano']).strzero($line['ca_vigencia_fim_mes'],2).'01');
					$anop = round(trim($line['ca_vigencia_prorrogacao']));
					if ($anop > 0)
						{ $vgp = '+'.$anop.' meses'; } else { $vgp = ''; }
					
					if ($anof < date("Ymd")) { $vg = '<font color="red">Encerrado</font>'; }
					if ($anof > date("Ymd")) { $vg = '<font color="green">Ativo</font>'; }
					
					$tot++;
					$link = '<A HREF="docentes_detalhe.php?dd0='.$line['id_pp'].'&dd90='.checkpost($line['id_pp']).'">';
					
					$sx .= '<TR>';
					$sx .= '<TD class="lt2" colspan=6><B>'.$linkp;
					$sx .= trim($line['ca_protocolo']).'</A>/'.trim($line['bn_codigo']);
					$sx .= '</B>';
										
					$sx .= '<TR>';
					$sx .= '<TD>';
					$sx .= $line['ca_agencia'];
					$sx .= '<TD>';
					$sx .= $line['ca_descricao'];

					$sx .= '<TD align="right">';
					$sx .= number_format($line['ca_vlr_total'],2);

					$sx .= '<TD align="center">';
					$sx .= $line['pp_update'];
					
					$sx .= '<TD align="center">';
					$sx .= substr($ano,4,2).'/'.substr($ano,0,4);
					$sx .= '-';
					$sx .= substr($anof,4,2).'/'.substr($ano,0,4);
					$sx .= $vgp;
					
					$sx .= '<TD align="center">';
					$sx .= $vg;
					
					//$sx .= '<TD>'.$stt;
					
					
					$sx .= '<TR>';
					$sx .= '<TD rowspan=2>';
					$sx .= trim($line['ca_protocolo']).'/'.trim($line['bn_codigo']);
					$sx .= '<TD>';
					$sx .= $link.$line['pp_nome'].'</A>';
					$sx .= '('.$line['pp_cracha'].')';
					$sx .= '<TD>';
					$sx .= '<NOBR>'.$prod[$line['pp_prod']];
					$sx .= '<TD>';
					if ($line['pp_ss']=='S')
						{
							$sx .= 'Stricto';
						} else {
							$sx .= 'Graduação';
						}
					
					//$sx .= '<TD>=>';
					//$sx .= $line['ca_status'];
					
					$sx .= '<TD align="center" colspan=2 align="right"><nobr>';
					
					$status = $line['bn_status'];
					$stt = $status;
					
					if (strlen($status) > 0)
						{
							$sta = $status;
							if ($status == '#') {$sta = '[Comunicar Pesquisador]'; }
							if ($status == '!') {$sta = '[Aguardar professor]'; }
							if ($status == 'F') {$sta = '[Finalizado]'; }
							$link = 'bonificacao_detalhe.php?dd0='.$line['id_bn'].'&dd90='.checkpost($line['id_bn']);
							$link = '<A HREF="'.$link.'">';
							$sx .= $link.$sta.'</A>';							
						} else { 
							$link = page();
							$link = troca($link,'.php','_1').'.php?dd1='.trim($line['ca_protocolo']).'&dd0='.$line['pp_cracha'];
							$link = '<A HREF="'.$link.'">';
							$sx .= $link.'[Ativar Isenção]'.'</A>';
							//$sx .= ' ('.$stt.')';
						}					
					$sx .= '<TR><TD colspan=5>'.$line['ca_titulo_projeto'].'&nbsp;';
					$sx .= '<TR><TD colspan=10><HR>';
				}
			$sx .= '<TR><TD colspan=8><B>Total de '.$tot.' docentes nesta categoria';
			$sx .= '</table>';
			//print_r($ln);
			return($sx);
		}	

	function isencoes_solicitar()
		{
			$professor = $this->professor;
			$protocolo = $this->protocolo;
			$sql = "update ".$this->tabela."
					set bn_status = 'A'
					where bn_professor = '$professor'
					and bn_original_tipo = 'IPR'
					and bn_codigo = '$protocolo'
					and bn_status = '!' ";	
			$rlt = db_query($sql);
			return(1);
		}
		
	function isencoes_discentes($professor='')
		{ 
				$sql = "select * from ".$this->tabela."
				inner join captacao on bn_original_protocolo = ca_protocolo
				where bn_professor = '$professor'
				and bn_original_tipo = 'IPR'
				and bn_status = '!'
			";
			$rlt = db_query($sql);
			$sx = '';
			while ($line = db_read($rlt))
				{
					$data = $line['bn_data'];
					$status = trim($line['bn_status']);
					$descricao = trim($line['bn_descricao']);
					
					$descricao .= '<BR><font class="lt0"><I>'.trim($line['ca_descricao']).'</I>';
					$descricao .= '<BR>'.trim($line['ca_titulo_projeto']);
					$descricao .= ' ('.$line['ca_protocolo'].')';
					$acao = '&nbsp;';
					
					if ($status == '!')
						{
							$link = '<A HREF="insecao.php?dd0='.$line['id_bn'];
							$link .= '&dd90='.checkpost($line['id_bn']);
							$link .= '&dd1=A">';
							
							$acao = $link.'<nobr>ativar isenção</A>';	
						}				
					
					$sx .= '<TR>';
					$sx .= '<TD>'.stodbr($data);
					$sx .= '<TD>'.$descricao;
					$sx .= '<TD>'.$acao;
				}
			return($sx);
			
		}
		
	function isencao_produtividade_comunicar_pesquisador($bon)
		{
			global $user;
			
			$doc = new docentes;
			$doc->le($bon->professor);
			$nome = $doc->pp_nome;
			$email1 = $doc->pp_email;
			$email2 = $doc->pp_email_1;
			$pag = $doc->pp_pagina;
			$pag = '<A HREF='.$pag.' >'.$pag.'</A>';
			
			$cap = new captacao;
			
			$cap->le($bon->origem_protocolo);
			
			$projeto = $cap->edital;

			if (($bon->status != '#') and ($bon->status != '!') and ($bon->status != '*')) { return(0); }
			//$this->troca_status('#','!');
			//$this->troca_status('*','!');

			$sql = "select * from ic_noticia where nw_ref = 'bon_isencao_prod' ";
			$rlt = db_query($sql);
			
			if ($line = db_read($rlt))
				{
					$texto = trim($line['nw_descricao']);
					$assunto = trim($line['nw_titulo']);		
				}
			
			//$texto = troca($texto,'$link',$pag);
			$texto = troca($texto,'$projeto',$projeto);
			$texto = troca($texto,'$link',$pag);
			
			if (strlen($email1) > 0)
				{
					enviaremail($email1,'',$assunto,$texto);
					echo '<BR>enviar e-mail para '.$email1;
				}
			
			if (strlen($email2) > 0)
				{
					enviaremail($email2,'',$assunto,$texto);
					echo '<BR>enviar e-mail para '.$email2;
				}
			
			$email = 'cip@pucpr.br';	
			enviaremail($email,'',$assunto,$texto);
			echo '<BR>enviar e-mail para '.$email;
			
			$email = 'monitoramento@sisdoc.com.br';	
			enviaremail($email,'',$assunto,$texto);
			echo '<BR>enviar e-mail para '.$email;

			$pp_nome = $doc->pp_nome;
			$pp_cracha = $professor;
			$descricao = trim($pp_nome).' (Isenção de Discente-Produtividade/Captação)';
			$nome = $descricao;
			$valor = 0;
			$tipo = 'IPR';
			$this->historico_inserir($bon->origem_protocolo,'ISB','Comunicado pesquisador por '.$user->user_login);							
			
			/* Mudar historico */
			return(1);						
		}
		

	function isencao_projeto_ativar($professor,$protocolo)
		{
			global $ss,$user;
			$professor = trim($professor);
			if (strlen($professor) != 8) { $this->erro = 'Código do professor inválido'; return(0); }
			//$sql = "delete from ".$this->tabela."
			//	where bn_professor = '$professor'
			//	and bn_original_protocolo = '$protocolo'
			//	and bn_original_tipo = 'IPR'
			//";
			//$rlt = db_query($sql);
			
			$sql = "select * from ".$this->tabela."
				where bn_professor = '$professor'
				and bn_original_protocolo = '$protocolo'
				and bn_original_tipo = 'IPR'
			";
			
			$rlt = db_query($sql);
			if ($rlt = db_read($rlt))
				{
					$prt = trim($rlt['bn_codigo']);
					$id_bn = round($rlt['id_bn']);
					$this->le($id_bn,$prt);
					$this->erro = 'Isenção já está cadastrada';
					return(0);
				}
			echo '<BR>'.date("d/m/Y H:i:s").' Recuperando Docente';			
			$doc = new docentes;
			$doc->le($professor);
			$pp_nome = $doc->pp_nome;
			$pp_cracha = $professor;
			$descricao = trim($pp_nome).' (Isenção de Discente-Projeto de Pesquisa-Captação)';
			$nome = $descricao;
			$valor = 0;
			$tipo = 'IPR';
			$user_login = $_SESSION['user_login'];
			echo '<BR>'.date("d/m/Y H:i:s").' Gerando Histótico';
			$this->historico_inserir($protocolo,'ISC','Liberado isenção por '.$user_login);
			echo '<BR>'.date("d/m/Y H:i:s").' Inserindo isenção';							
			$this->formulario_bonificacao($nome,$pp_cracha,$pp_nome,$descricao,$valor,$protocolo,$tipo);
			$this->erro = 'Isenção Gerada com Sucesso';
			return(1);			
		}
		
	function isencao_produtividade_ativar($professor,$protocolo)
		{
			global $user;
			if (strlen($professor) != 8) { $this->erro = 'Código do professor inválido'; return(0); }
			$sql = "select * from ".$this->tabela."
				where bn_professor = '$professor'
				and bn_original_tipo = 'IPR'
			";
			$rlt = db_query($sql);
			if ($rlt = db_read($rlt))
				{
					$this->erro = 'Isenção já está cadastrada';
					return(0);
				}
				
				
			$doc = new docentes;
			$doc->le($professor);
			$pp_nome = $doc->pp_nome;
			$pp_cracha = $professor;
			$descricao = trim($pp_nome).' (Isenção de Discente-Produtividade)';
			$nome = $descricao;
			$valor = 0;
			$tipo = 'IPR';
			$this->historico_inserir($protocolo,'ISA','Liberado isenção por '.$user->user_login);							
			$this->formulario_bonificacao($nome,$pp_cracha,$pp_nome,$descricao,$valor,$protocolo,$tipo);
			$this->erro = 'Isenção Gerada com Sucesso';
			return(1);			
		}
		
	function bonificacao_prof_mostra($rlt)
		{
			global $tab_max;
			$doc = new docentes;
			$sx .= '<table width="'.$tab_max.'" class="lt1">';
			$sx .= '<TR><TH>Nome<TH>Tít.<TH>Produtivade<TH>SS<TH>Cracha<TH>Campus<TH>Escola<TH>Atualizado<TH>Isenção';
			$tot = 0;
			$prod = $doc->produtividade();
			while ($line = db_read($rlt))
				{
					$tot++;
					$link = '<A HREF="docentes_detalhe.php?dd0='.$line['id_pp'].'&dd90='.checkpost($line['id_pp']).'">';
					$sx .= '<TR '.coluna().'>';
					$sx .= '<TD>';
					$sx .= $doc->link_lattes($line['pp_lattes']).$line['pp_nome'].'</A>';
					$sx .= '<TD>';
					$sx .= $line['pp_titulo'];
					$sx .= '<TD>';
					$sx .= $prod[$line['pp_prod']];
					$sx .= '<TD>';
					$sx .= $line['pp_ss'];
					$sx .= '<TD>';
					$sx .= $link;					
					$sx .= $line['pp_cracha'];
					$sx .= '<TD>';
					$sx .= $line['pp_centro'];
					$sx .= '<TD>';
					$sx .= $line['centro_nome'];
					$sx .= '<TD align="center">';
					$sx .= $line['pp_update'];
					$sx .= '<TD align="center">';
					
					$status = $line['bn_status'];
					
					if (strlen($status) > 0)
						{
							$sta = $status;
							if ($status == '#') {$sta = '[Comunicar Pesquisador]'; }
							if ($status == '!') {$sta = '[Aguardar professor]'; }
							$link = 'bonificacao_detalhe.php?dd0='.$line['id_bn'].'&dd90='.checkpost($line['id_bn']);
							$link = '<A HREF="'.$link.'">';
							$sx .= $link.$sta.'</A>';							
						} else { 
							$link = page();
							$link = troca($link,'.php','_1').'.php?dd0='.$line['pp_cracha'];
							$link = '<A HREF="'.$link.'">';
							$sx .= $link.'[Ativar Isenção]'.'</A>';
						}
				}
			$sx .= '<TR><TD colspan=8><B>Total de '.$tot.' docentes nesta categoria';
			$sx .= '</table>';
			return($sx);
		}		
		
		function bonificacao_prof_produtividade()
			{
			//$sql = "delete from ".$this->tabela." where bn_original_tipo = 'IPR' ";
			//$rlt = db_query($sql);				
				
				$sql= "select * from pibic_professor 
					inner join centro on centro_codigo = pp_escola
					left join ".$this->tabela." on bn_professor = pp_cracha and bn_original_tipo = 'IPR'
					where pp_prod > 0 and pp_ativo = 1 and pp_ss = 'S' 
					order by pp_nome ";
					$rlt = db_query($sql);
				return($rlt);
			}
				
		function resumo_bonificacao_controladoria($dd1=19000101,$dd2=20990101)
			{
				$sql = "select * from ".$this->tabela." 
						left join captacao on bn_original_protocolo = ca_protocolo
						where (bn_valor > 0) and (bn_liberacao >= $dd1 and bn_liberacao <= $dd2)
						and bn_original_tipo = 'PRJ'
						order by 
					bn_status desc, bn_liberacao desc, bn_professor_nome ";
				$rlt = db_query($sql);
				$sx .= '<table width="'.$tab_max.'" class="lt1">';
				$sx .= '<TR><TH>Pesquisador<TH>Órgão de fomento<TH>Valor geral do projeto<TH>Valor Capital<TH>Valor Custeio<TH>Valor Bolsa<TH>Bonificação<TH>% Bonificação<TH>Data Lib.<TH>Tipo<TH>Ini.Vig.<TH>Duraç.<TH>Prorr.';
				$tot = 0;
				$tot1 = 0;
				while ($line = db_read($rlt))
				{
					$vlr = $line['bn_valor'];
					$age = trim($line['ca_agencia']);
					if (substr($age,0,2)== '00') { $age = 'EMPRE'; }
					$tot = $tot + $line['bn_valor'];
					$tot1++;
					$sx .= '<TR '.coluna().'>';
					$sx .= '<TD>';
					$sx .= $line['bn_professor_nome'];
					$sx .= '<TD>';
					$sx .= $age;
										
					$sx .= '<TD align="right">';
					$sx .= number_format($line['ca_vlr_total'],2,',','.');
					$sx .= '<TD align="right">';
					$sx .= number_format($line['ca_vlr_capital'],2,',','.');
					$sx .= '<TD align="right">';
					$sx .= number_format($line['ca_vlr_custeio'],2,',','.');
					$sx .= '<TD align="right">';
					$sx .= number_format($line['ca_vlr_bolsa'],2,',','.');
									
					$sx .= '<TD align="right">';
					$sx .= number_format($line['bn_valor'],2,',','.');
						
					$sx .= '<TD align="right">';
					if ($line['ca_vlr_total'] > 0)
						{
						$por = round($line['bn_valor']*100) / round($line['ca_vlr_total']*100);
						}
					$sx .= number_format($por*100,1,',','.').'%';
					$sx .= '<TD>';
					$sx .= stodbr($line['bn_liberacao']);
					$sx .= '<TD align="center">';
					$sx .= trim($line['bn_original_tipo']);		
					$sx .= '<TD>'.strzero($line['ca_vigencia_ini_mes'],2).'/'.$line['ca_vigencia_ini_ano'];
					$sx .= '<TD align="center">'.$line['ca_duracao'];							
					$sx .= '<TD align="center">'.$line['ca_vigencia_prorrogacao'];
				}
				$sx .= '<TR><TD colspan=1 align="left"><B>Total de '.$tot1.' bonificações.';
				$sx .= '<TD align="right"><B>'.number_format($tot,2,',','.');
				$sx .= '<TR><TD>FA - Fundação Araucária; EMPRE - Empresas';
				$sx .= '</table>';
				return($sx);				
				
			}
		function resumo_bonificacao($dd1=19000101,$dd2=20990101)
			{
				global $tab_max;
//				$sql = "update ".$this->tabela." set bn_liberacao = ".date("Ymd")." where bn_status = 'B' ";
//				$rlt = db_query($sql);
				
				$sql = "select * from ".$this->tabela." 
					where (bn_valor > 0)  and bn_liberacao >= $dd1 and bn_liberacao <= $dd2
					and bn_original_tipo = 'PRJ'
					order by 
					bn_professor_nome, bn_status desc, bn_liberacao desc ";
				$rlt = db_query($sql);
				$sx .= '<table width="'.$tab_max.'" class="lt1">';
				$sx .= '<TR><TH>Pesquisador
						<TH>Protocolo
						<TH>Valor da Bonificação<TH>Dt. Comunicação<TH>Tipo';
				$tot = 0;
				$tot1 = 0;
				$tot3 = 0;
				$xnome = 'x';
				while ($line = db_read($rlt))
				{
					$nome = trim($line['bn_professor_nome']);
					if ($nome != $xnome)
						{
							if ($tot2 > 0)
							{
							$sx .= '<TR><TD colspan=5 align="right">';
							$sx .= number_format($tot2,2,',','.');
							$tot2 = 0;
							}
							$xnome = $nome;
						}
					$tot = $tot + $line['bn_valor'];
					$tot2 = $tot2 + $line['bn_valor'];
					$tot1++;
					$sx .= '<TR>';
					$sx .= '<TD class="tabela01">';
					$sx .= $line['bn_professor_nome'];
					$sx .= '<TD class="tabela01" align="center" width="8%">';
					$sx .= $line['bn_codigo'];
					$sx .= '<TD class="tabela01" align="right" width="15%">';
					$sx .= number_format($line['bn_valor'],2,',','.');
					$sx .= '<TD class="tabela01" align="center" width="15%">';
					$sx .= stodbr($line['bn_liberacao']);	
					$sx .= '<TD class="tabela01" align="center" width="15%">';
					$sx .= $this->tipos(trim($line['bn_original_tipo']));				
				}
				if ($tot2 > 0)
					{
					$sx .= '<TR><TD colspan=5 align="right">';
					$sx .= number_format($tot2,2,',','.');
					$tot2 = 0;
					}
				$sx .= '<TR><TD colspan=1 align="left"><B>Total de '.$tot1.' bonificações.';
				$sx .= '<TD align="right"><B>'.number_format($tot,2,',','.');
				$sx .= '</table>';
				return($sx);
			}
		function tipos($tp)
			{
				if ($tp=='PRJ') { return("Captação"); }
				if ($tp=='PRA') { return("Artigo"); }
				if ($tp=='PRI') { return("Isenção"); }
			}
		function resumo_bonificacao_programas($dd1=19000101,$dd2=20990101)
			{
				global $tab_max;
//				$sql = "update ".$this->tabela." set bn_liberacao = ".date("Ymd")." where bn_status = 'B' ";
//				$rlt = db_query($sql);
				
				$sql = "select * from ".$this->tabela." 
					left join programa_pos_docentes on bn_professor = pdce_docente and pdce_ativo = 1
					left join programa_pos on pdce_programa = pos_codigo
					and bn_original_tipo = 'PRJ'
					where (bn_valor > 0)  and bn_liberacao >= $dd1 and bn_liberacao <= $dd2
					
					order by pos_nome,
					bn_status desc, bn_codigo, bn_liberacao desc, bn_professor_nome ";

				$rlt = db_query($sql);
				$sx .= '<table width="'.$tab_max.'" class="lt1">';
				$sh = '<TR><TH>Pesquisador<TH>Protocolo<TH>Valor da Bonificação<TH>Dt. Comunicação<TH>Tipo';
				$tot = 0;
				$tot1 = 0;
				$xnome = 'x';
				$tot2 = 0;
				while ($line = db_read($rlt))
				{
					$cod = trim($line['bn_codigo']);
					if ($cod != $xcod)
					{
						$nome = trim($line['pos_nome']);
						if ($nome != $xnome)
							{
								if ($tot2 > 0)
									{
										$sx .= '<TR><TD colspan=5 align="right">'.number_format($tot2,2,',','.');		
									}
								$tot2 = 0;
								$sx .= '<TR><TH colspan=5 class="lt3">'.$nome;
								$sx .= $sh;
								$xnome = $nome;
							}	
						$tot = $tot + $line['bn_valor'];
						$tot2 = $tot2 + $line['bn_valor'];
						$tot1++;
						$sx .= '<TR>';
						$sx .= '<TD class="tabela01">';
						$sx .= $line['bn_professor_nome'];
						$sx .= '<TD class="tabela01" align="center">';
						$sx .= $line['bn_codigo'];
						$sx .= '<TD class="tabela01" align="right">';
						$sx .= number_format($line['bn_valor'],2,',','.');
						$sx .= '<TD class="tabela01" align="center">';
						$sx .= stodbr($line['bn_liberacao']);	
						$sx .= '<TD class="tabela01" align="center">';
						$sx .= $this->tipos(trim($line['bn_original_tipo']));	
						$xcod = $cod;			
					}
				}
				if ($tot2 > 0)
					{
						$sx .= '<TR><TD colspan=5 align="right">'.number_format($tot2,2,',','.');		
					}
				$sx .= '<TR><TD colspan=1 align="left"><B>Total de '.$tot1.' bonificações.';
				$sx .= '<TD align="right"><B>'.number_format($tot,2,',','.');
				$sx .= '</table>';
				return($sx);
			}
			
			
		function troca_status($de,$para)
			{
				$sql = "update ".$this->tabela." set bn_status = '".$para."' 
					where bn_status = '".$de."' and bn_codigo = '".$this->protocolo."' ";
				$rlt = db_query($sql);
			}
			
		function libera_pagamento($data)
			{
				$sql = "update ".$this->tabela." set bn_liberacao = '".$data."' 
					where bn_codigo = '".$this->protocolo."' ";
				$rlt = db_query($sql);
			}
			
		function mostar_bonificacao_2()
			{
				$sx .= '<fieldset><legend>Valor solicitado para bonificação</legend>';
				$sx .= '<table width="100%" class="lt3">';
				$sx .= '<TR><TD>Valor da Bonificação <B>'.number_format($this->valor,2,',','.');
				$sx .= '</table>';
				$sx .= '</fieldset>';
				return($sx);	
			}
		
		function le($id=0,$proto='')
			{
				//$sql = "update ".$this->tabela." set				
					//bn_original_protocolo = '0000175',
					//bn_original_tipo = 'PRJ',
					//bn_valor = 15000
				//where id_bn = $id or bn_codigo = '$proto' ";
				//$rlt = db_query($sql);
					
				$sql = "select * from ".$this->tabela." 
					where id_bn = $id or bn_codigo = '$proto' ";
				$rlt = db_query($sql);
				if ($line = db_read($rlt))
					{
						//bn_original_protocolo
						$this->origem_protocolo = $line['bn_original_protocolo'];
						$this->origem_tipo = $line['bn_original_tipo'];
						$this->valor = $line['bn_valor'];
						$this->professor = $line['bn_professor'];
						$this->professor_nome = $line['bn_professor_nome'];
						$this->descricao = $line['bn_descricao'];
						$this->protocolo = $line['bn_codigo'];
						$this->status = $line['bn_status'];
						$this->beneficiario = $line['bn_beneficiario'];
						$this->modalidade = $line['bn_modalidade'];
						$this->line = $line;
					}
				return(1);
			}
		function row()
			{
				global $cdf,$cdm,$masc;
				$cdf = array('id_bn','bn_codigo','bn_original_protocolo','bn_professor_nome','bn_data','bn_valor','bn_status','bn_original_tipo');
				$cdm = array('cod',msg('protocolo'),msg('protocolo'),msg('nome'),msg('data'),msg('valor'),msg('status'),msg('tipo'));
				$masc = array('','','','','D','$R','');
				return(1);				
			}	

		
		function acao($status)
			{
				global $dd,$acao,$cap;
				$ba1 = "gerar formulário de bonificacao";
				
				if ($ba1==$acao)
					{
						$this->bonificar_formento_gerar();
					}
				$sx = '<table width="100%">'.
				$sx .= '<TR>';
				$sx .= '<TD align="center">';
				$sx .= '<form method="pos">';
				$sx .= '<input type="hidden" name="dd0" value="'.$dd[0].'">';
				$sx .= '<input type="hidden" name="dd90" value="'.$dd[90].'">';
				$sx .= '<input type="submit" name="acao" value="'.$ba1.'" style="width:300px; height:50px;">';
				$sx .= '</form>';
				$sx .= '</table>';
				return($sx);
			}
			
		function bonificar_formento_gerar()
			{
				global $cap;
				if (($cap->status == 1) or ($cap->status == 80) or ($cap->status == 12) or ($cap->status == 11))
					{
						$desc = 'Pagamento referente a bonificação de Projeto de pesquisa correspondente a 3% do valor captado, com teto em R$ 15.000,00 previsto no Projeto Estratégico de Atração e Retenção de Talentos. Professor Stricto Sensu, protocolo da bonificacao '.$cap->protocolo;
						$desc .= chr(13).' Valor total do projeto '.number_format($this->valor,2,',','.');
						$nome = 'Bonificação projeto '.$cap->protocolo.' de '.$cap->professor_nome;
						
						$valor = $cap->valor_proponente;
						if (round($valor) == 0)
							{
								echo '<H2><font color="red">Valor na proponente igual a zero</font></h2>';
								return(0);
								exit;
							}
						/* Se o valor sor superior a R$ 500.000,00 reduz o teto para 500.000,00 */
		
						if ($valor > 500000) { $valor = 500000; }
						$valor = (round($valor*100*0.03)/100);
						
						$protocolo = $cap->protocolo;
						$tipo = 'PRJ';
						$ok = $this->formulario_bonificacao($nome,$cap->professor,$cap->professor_nome,$desc,$valor,$protocolo,$tipo);
						if ($ok==1) { $cap->alterar_status(80); }
					} else {
						$erro = '<h3><font color="red">Protocolo não está liberado para bonificação</font></h3>';
						$erro .= '
						<script>
							alert("erro para gerar a bonificacao");
						</script>
						';
						echo $erro;
					}
					
				exit;
			}
						
		function formulario_bonificacao($nome,$pp_cracha,$pp_nome,$descricao,$valor,$protocolo,$tipo)
			{
				echo '<BR>'.date("d/m/Y H:i:s").' Recuperando protocolo';
				$year = date("Y");
				$data = date("Ymd");
				$hora = date("H:i");
				$asql = "select * from ".$this->tabela." where bn_nome = '$nome'
							and bn_original_tipo = '$tipo'
							and bn_original_protocolo = '$protocolo'
				";
				$rlt = db_query($asql);
				if (!($line = db_read($rlt)))
					{
					$sta = '@';
					if ($tipo == 'IPR') { $sta = '!'; }
									
					$sql = "insert into ".$this->tabela."
						(bn_codigo, bn_ano, bn_professor,
						bn_professor_nome, bn_professor_cracha, 
						bn_data, bn_hora, bn_status, 
						bn_descricao, bn_cr, bn_nome,
						bn_valor, bn_aprovador, bn_ordenador,
						bn_liberacao, bn_previsao,
						bn_original_protocolo,bn_original_tipo)
						values 
						('','$year','$pp_cracha',
						'$pp_nome','',
						$data,'$hora','$sta',
						'$descricao','','$nome',
						$valor,'','',
						19000101,19000101,
						'$protocolo','$tipo'
						)";
						$rlt = db_query($sql);
						echo '<BR>'.date("d/m/Y H:i:s").' Inserindo registro da isenção';					
						$this->updatex();
						echo '<BR>'.date("d/m/Y H:i:s").'Updatex';
						$rlt = db_query($asql);
						$ln = db_read($rlt);
						echo '<BR>'.date("d/m/Y H:i:s").'Recupera';
						
						$this->le($ln['id_bn'],$ln['bn_codigo']);
						
						return(1);
					} else {
						echo 'Projeto já bonificado/ISENTADO';
						return(0);
					}			
			}
		
		function historico_inserir($protocolo,$ope,$historico)
			{
				global $user;
				$data = date("Ymd");
				$hora = date("H:i");
				$login = $user->user_id;
								
				$sql = "select * from ".$this->tabela."_historico
						where bnh_data = $data and bnh_ope = '$ope'
						and bnh_protocolo = '$protocolo' ";
				$rlt = db_query($sql);
				if (!($line = db_read($rlt)))
					{
					$sql = "insert into ".$this->tabela."_historico 
					(bnh_data, bnh_hora, bnh_historico,
						bnh_ope, bnh_log, bnh_protocolo)
					values
					($data,'$hora','$historico',
					'$ope','$login','$protocolo')";
					$rlt = db_query($sql);
					}
				return(1);
			}
		
		function historico_mostrar($protocolo,$protocolo_origem)
			{
				$protocolo2 = round($protocolo);
				$sql = "select * from ".$this->tabela."_historico
						where bnh_protocolo = '$protocolo' or bnh_protocolo = '$protocolo2'
						or bnh_protocolo = '$protocolo_origem'
						";
				$rlt = db_query($sql);				
				$sx = '<table width="100%" class="lt1">';
				$sx .= '<TR><TH>Data<TH>Descrição<TH>Ação';
				while ($line = db_read($rlt))
					{
						$sx .= '<TR>';
						$sx .= '<TD class="tabela01" width="10%"><NOBR>'.stodbr($line['bnh_data']);
						
						$sx .= ' '.($line['bnh_hora']);
						$sx .= '<TD class="tabela01" width="80%">'.($line['bnh_historico']);
						$sx .= '<TD class="tabela01" width="10%"><NOBR>'.($line['bnh_ope']);
					}
				$sx .= '</table>';
				return($sx);
				
			}

		function editar($cap) 
			{
				echo '<form action="captacao_ed.php" target="new'.date("msi").'">';
				echo '<input type="hidden" name="dd0" value="'.$cap->id.'">';
				echo '<input type="submit" value="editar" targer="new" class="botao-geral">';
				echo '</form>';
				
			}	
		function lista_para_isentar($ano)
			{
				$sql = "select * from captacao 
					inner join docentes on ca_professor = pp_cracha
					left join agencia_de_fomento on agf_codigo = ca_agencia
					left join ".$this->tabela." on (bn_original_protocolo = ca_protocolo) and (bn_original_tipo = 'IPR') 
					where (ca_participacao = 'C' or ca_participacao = 'P')
					and (ca_status = 80 or ca_status = 81 or ca_status = 11 or ca_status = 13 or ca_status = 1)
					
					order by pp_nome, ca_vigencia_ini_ano
				";
				// ca_status = 10 or
				//and (ca_prj_isen = '1' or ca_prj_isen = '')
				// and ca_participacao = 'C' and ((ca_status = 1) or (ca_status = 80))
				$rlt = db_query($sql);
				$sx = '<table width="98%" border=0 cellspacing=3 cellpadding=1>';
				$sh = '<TR>';
				$sh .= '<TH>Protocolo';
				$sh .= '<TH>Tipo';
				$sh .= '<TH>Edital';
				$sh .= '<TH>Financiador';
				$sh .= '<TH>Edital nome';
				$sh .= '<TH>Início';
				$sh .= '<TH>Fim';
				$sh .= '<TH>Bonificado';
				$sh .= '<TH>Ação';
				$xnome = 'x';
				$id = 0;
				while ($line = db_read($rlt))
				{
					//print_r($line);
					//exit;
					$prj = trim($line['bn_original_protocolo']);
					$tipo = $line['bn_original_tipo'];
					if (strlen($prj) == 0)
					{					
					$id++;
					$nome = trim($line['pp_nome']);
					if ($xnome != $nome)
						{
							$sx .= '<TR><TD colspan=20 class="tabela01"><B>'.$cor.$line['pp_nome'].'<B> ('.trim($line['pp_cracha']).')';
							$xnome = $nome;
							$sx .= $sh;
						}
					$link = '<A HREF="captacao_ed.php?dd0='.$line['id_ca'].'" target="new'.date("msi").'">';
					$sx .= $this->mostra_captacao_isencao($line);
					$sx .= '<BR>'.$tipo.'<BR>'.$prj;
					$sx .= '<TD><form method="post" action="isencao_gerar_2.php?dd0='.$line['id_ca'].'&dd90='.checkpost($line['id_ca']).'">';
					$sx .= '<input type="hidden" name="dd2" value="'.$line['pp_cracha'].'">';
					$sx .= '<input type="hidden" name="dd3" value="'.$line['ca_protocolo'].'">';
					$sx .= '<input type="submit" value="Gerar Isenção" class="botao-geral">';
					//$sx .= '&nbsp;'.$link.'[E]</A>';
					$sx .= '<TD></form>';
										
					$ln = $line;
					}
				}
				$sx .= '<TR><TD colspan=10>';
				$sx .= 'Total de '.$id.' projetos para bonificar';
				$sx .= '</table>';
				
				//$ln);
				return($sx);
			}					
		function lista_para_bonificar_2($ano)
			{
				$ano .= '00';
				$sql = "select * from captacao 
					inner join docentes on ca_professor = pp_cracha
					inner join agencia_de_fomento on agf_codigo = ca_agencia
					
					left join ".$this->tabela." on ((bn_original_protocolo = ca_protocolo) 
							and (bn_original_tipo = 'PRJ'))
					
					where (ca_participacao = 'E' or ca_participacao = 'C' or ca_participacao = 'A' or ca_participacao = 'P' or ca_participacao = '') 
						and ((ca_status = 1) or (ca_status = 80) 
						or (ca_status = 11)
						or (ca_status = 12))
					and ca_vigencia_final_ano >= $ano					
					order by pp_nome, ca_vigencia_ini_ano
				";
				
				$rlt = db_query($sql);
				$sx = '<table width="98%" border=0 cellspacing=3 cellpadding=1>';
				$sh = '<TR><TH>Tipo';
				$sh .= '<TH>Edital';
				$sh .= '<TH>Financiador';
				$sh .= '<TH>Edital nome';
				$sh .= '<TH>Início';
				$sh .= '<TH>Fim';
				$sh .= '<TH>Vlr. Total';
				$sh .= '<TH>Vlr. PUCPR';
				$sh .= '<TH>Ação';
				$xnome = 'x';
				$id = 0;
				while ($line = db_read($rlt))
				{
					$prj = trim($line['bn_original_protocolo']);
					if (strlen($prj) == 0)
					{
					$id++;
					$nome = trim($line['pp_nome']);
					if ($xnome != $nome)
						{
							$sx .= '<TR><TD colspan=20 class="tabela01"><B>'.$cor.$line['pp_nome'].'<B> ('.trim($line['pp_cracha']).')';
							$xnome = $nome;
							$sx .= $sh;
						}
					$link = '<A HREF="captacao_ed.php?dd0='.$line['id_ca'].'" target="new'.date("msi").'">';
					$sx .= $this->mostra_captacao_bonificacao_2($line);
					$sx .= '<TD><form method="post" action="bonificacao_fomento_1.php?dd0='.$line['id_ca'].'&dd90='.checkpost($line['id_ca']).'">';
					$sx .= '<input type="submit" value="Gerar Bonificação" class="botao-geral">';
					//$sx .= '&nbsp;'.$link.'[E]</A>';
					$sx .= '<TD></form>';
										
					$ln = $line;
					}
				}
				$sx .= '<TR><TD colspan=10>';
				$sx .= 'Total de '.$id.' projetos para bonificar';
				$sx .= '</table>';
				
				//$ln);
				return($sx);
			}			

			
		function lista_para_bonificar($ano)
			{
				$sql = "select * from captacao 
					inner join docentes on ca_professor = pp_cracha
					inner join agencia_de_fomento on agf_codigo = ca_agencia
					where ca_vigencia_ini_ano >= $ano
					and ca_participacao = 'C' and ((ca_status = 1) or (ca_status = 80))
					order by pp_nome, ca_vigencia_ini_ano
				";
				
				
				$rlt = db_query($sql);
				$sx = '<table class="lt1" width="98%" border=0 cellspacing=3 cellpadding=1>';
				while ($line = db_read($rlt))
				{
					
					$link = '<A HREF="captacao_ed.php?dd0='.$line['id_ca'].'" target="new'.date("msi").'">';
					$sx .= $this->mostra_captacao_bonificacao($line);
					$sx .= '<TD><form method="post" action="bonificacao_fomento_1.php?dd0='.$line['id_ca'].'&dd90='.checkpost($line['id_ca']).'">';
					$sx .= '<input type="submit" value="Gerar processo de Bonificação">';
					$sx .= '&nbsp;'.$link.'[E]</A>';
					$sx .= '<TD></form>';
					$sx .= '<TR><TD colspan=5 height=1 bgcolor="#000000">';
					
					$ln = $line;
				}
				$sx .= '</table>';
				
				//$ln);
				return($sx);
			}
		function mostra_captacao_isencao($line)
			{
				$pa = $line['ca_participacao'];
				$cap = new captacao;
				$participacao = $cap->tipo_participacao();
				$tipo_participacao = $participacao[$pa];
				$cor = '';
				if ($pa=='O') { $cor = '<font color="blue">'; }
				
				$link = '<A HREF="captacao_detalhe.php?dd0='.$line['id_ca'].'&dd90='.checkpost($line['id_ca']).'" alt="clique para detalhes">';
				 
				$sx .= '<TR valign="top">';
				
				$sx .= '<TD>'.$cor.trim($line['ca_protocolo']);
				$sx .= '<TD>';
				//$sx .= '<TD colspan=4><B>'.$cor.$line['pp_nome'].'<B> ('.trim($line['pp_cracha']).')';
				$cor = '';
				if ($pa=='O') { $cor = '<font color="blue">'; }
				$sx .= $cor.$tipo_participacao;
				$sx .= '<TD>'.$cor.trim($line['ca_edital_nr']);
				$sx .= '/'.$line['ca_edital_ano'];

				$sx .= '<TD>'.$cor.$line['agf_nome'];
				$sx .= '<TD>'.$cor.$link.$line['ca_descricao'];

				$vg = strzero($line['ca_vigencia_ini_mes'],2).'/'.$line['ca_vigencia_ini_ano'];
				$vgf = strzero($line['ca_vigencia_fim_mes'],2).'/'.$line['ca_vigencia_fim_ano'];
				$pro = $line['ca_vigencia_prorrogacao'];
				
				$sx .= '<TD align="center">'.$vg;
				$sx .= '<TD align="center">'.$vgf;
				
				$sx .= '<TD align="right">';
				$sx .= '(verificar)';
				
				
				return($sx);
			}
		function mostra_captacao_bonificacao_2($line)
			{
				$pa = $line['ca_participacao'];
				$cap = new captacao;
				$participacao = $cap->tipo_participacao();
				$tipo_participacao = $participacao[$pa];
				$cor = '';
				if ($pa=='O') { $cor = '<font color="blue">'; }
				
				$link = '<A HREF="captacao_detalhe.php?dd0='.$line['id_ca'].'&dd90='.checkpost($line['id_ca']).'" alt="clique para detalhes">';
				 
				$sx .= '<TR valign="top">';
				$sx .= '<TD>';
				//$sx .= '<TD colspan=4><B>'.$cor.$line['pp_nome'].'<B> ('.trim($line['pp_cracha']).')';
				$cor = '';
				if ($pa=='O') { $cor = '<font color="blue">'; }
				$sx .= $cor.$tipo_participacao;
				$sx .= '<TD>'.$cor.trim($line['ca_edital_nr']);
				$sx .= '/'.$line['ca_edital_ano'];

				$sx .= '<TD>'.$cor.$line['agf_nome'];
				$sx .= '<TD>'.$cor.$link.$line['ca_descricao'];

				$vg = strzero($line['ca_vigencia_ini_mes'],2).'/'.$line['ca_vigencia_ini_ano'];
				$vgf = strzero($line['ca_vigencia_fim_mes'],2).'/'.$line['ca_vigencia_fim_ano'];
				$pro = $line['ca_vigencia_prorrogacao'];
				
				$sx .= '<TD align="center">'.$vg;
				$sx .= '<TD align="center">'.$vgf;
				
				$sx .= '<TD align="right">'.number_format($line['ca_vlr_total'],2,',','.');
				$sx .= '<TD align="right">'.number_format($line['ca_proponente_vlr'],2,',','.');
				
				
				return($sx);
			}
		function mostra_captacao_bonificacao($line)
			{
				$pa = $line['ca_participacao'];
				$cap = new captacao;
				$participacao = $cap->tipo_participacao();
				$tipo_participacao = $participacao[$pa];
				$cor = '';
				if ($pa=='O') { $cor = '<font color="blue">'; }
				
				$link = '<A HREF="captacao_detalhe.php?dd0='.$line['id_ca'].'&dd90='.checkpost($line['id_ca']).'" alt="clique para detalhes">'; 
				$sx .= '<TR>';
				$sx .= '<TD colspan=4><B>'.$cor.$line['pp_nome'].'<B> ('.trim($line['pp_cracha']).')';
				$sx .= '</B> - ' .$tipo_participacao;
				$sx .= '<TD colspan=1>'.$cor.trim($line['ca_edital_nr']);
				$sx .= '/'.$line['ca_edital_ano'];

				$sx .= '<TR>';
				$sx .= '<TD colspan=2>'.$cor.$line['agf_nome'];
				$sx .= '<TD colspan=3>'.$cor.$link.$line['ca_descricao'];
				
				$sx .= '<TR>';
				$sx .= '<TD colspan=1 align="right">Valor do Edital<TD>'.number_format($line['ca_vlr_total'],2,',','.');
				$sx .= '<TD colspan=1 align="right">Valor aplicado na Instituição<TD>'.number_format($line['ca_proponente_vlr'],2,',','.');
				
				
				return($sx);
			}
		function structure()
			{
				$sql = "CREATE TABLE ".$this->tabela."
					( 
					id_bn serial not null,
					bn_codigo char(7),
					bn_original_protocolo char(7),
					bn_original_tipo char(3),
					bn_ano char(4),
					bn_professor char(8),
					bn_professor_nome char(100),
					bn_professor_cracha char(13),
					bn_data integer,
					bn_hora char(8),
					bn_status char(1),
					bn_descricao text,
					bn_cr char(6),
					bn_nome char(100),
					bn_valor float,
					bn_aprovador char(8),
					bn_ordenador char(8),
					bn_liberacao integer,
					bn_previsao integer,
					bn_modalidade char(1),
					bn_rf_parcela integer,
					bn_rf_valor float
					)";
				//$rlt = db_query($sql);
				
				$sql = "CREATE TABLE ".$this->tabela."_historico (
					id_bnh serial not null,
					bnh_protocolo char(7),
					bnh_data integer,
					bnh_hora char(8),
					bnh_historico char(80),
					bnh_ope char(3),
					bnh_log char(8)
					)
				";
				$rlt = db_query($sql);
			}
		function updatex()
			{
				global $base;
				$c = 'bn';
				$c1 = 'id_'.$c;
				$c2 = $c.'_codigo';
				$c3 = 5;
				$sql = "update ".$this->tabela." set $c2 = lpad($c1,$c3,0) where $c2='' ";
				if ($base=='pgsql') { $sql = "update ".$this->tabela." set $c2 = trim(to_char(id_".$c.",'".strzero(0,$c3)."')) where $c2='' "; }
				$rlt = db_query($sql);
			}			
	}
?>
