<?php
class artigo
	{
		var $id;
		var $protocolo;
		var $docente;
		var $issn;
		var $vol;
		var $ano;
		var $numero;
		
		var $bon_A1 = 1200;
		var $bon_A2 = 1000;
		var $bon_Q1 = 1500;
		var $bon_IC = 2000;
		var $bon_ExR= 3000;
		
		var $tabela = 'artigo';
		var $result = 0;
		
		function resultado_busca($sx)
			{
				
				$sql = "select * from ".$this->tabela."
						inner join pibic_professor on pp_cracha = ar_professor
						where asc7(pp_nome) like '%".UpperCaseSql($sx)."%'
						order by pp_nome
						";
				$rlt = db_query($sql);
				$sx = '<table>';
				$xprof = '';
				while ($line = db_read($rlt))
				{
					$prof = trim($line['pp_nome']);
					if ($prof != $xprof)
						{
							$xprof = $prof;
							$sx .= '<TR><TD colspan=5 class="lt3">';
							$sx .= $prof;
						}
					$sx .= $this->mostra_artigo_lista($line,1);
				}
				$sx .= '</table>';
				return($sx);
			}
			
		
		function busca()
			{
				global $dd,$acao;
				
				if ((strlen($dd[20]) > 0) and (strlen($acao) > 0))
					{
						return($this->resultado_busca($dd[20]));
						$this->result = 1;
					}
				$sx = '<form method="post" action="'.page().'">';
				$sx .= '<input type="text" size="40" name="dd20">';
				$sx .= '<input type="submit" value="busca>>" name="acao">';
				$sx .= '</form>';
				return($sx);
			}
		
		function export_to_excel()
			{
				$st = $this->status();
								
				$sql = "select * from ".$this->tabela." 
						inner join pibic_professor on pp_cracha = ar_professor
						left join centro on centro_codigo = pp_escola
						and ar_status <> 0
						order by pp_centro, centro_nome, pp_nome
				";
				$rlt = db_query($sql);
				
				$sx = '<table style="font-family: Arial; size: 12px;">';
				$sx .= '<TR><TD>Protocolo
						<TD>Status
						<TD>Cracha
						<TD>Tit.
						<TD>Nome
						<TD>SS
						<TD>Centro
						<TD>Escola
						<TD>Titulo do artigo
						<TD>ISSN
						<TD>Revista
						<TD>ISSN
						<TD>Ano
						<TD>Q1
						<TD>ExR
						<TD>Qualis
						<TD>email
						<TD>email alternativo
						<TD>data';
						
				while ($line = db_read($rlt))
					{
						$sx .= '<TR>';
						$sx .= '<TD>'.$line['ar_protocolo'];
						$sx .= '<TD>'.$st[$line['ar_status']];
						$sx .= '<TD>'.$line['pp_cracha'];
						$sx .= '<TD>'.$line['pp_escolaridade'];
						$sx .= '<TD>'.$line['pp_nome'];
						$sx .= '<TD>'.$line['pp_ss'];
						$sx .= '<TD>'.$line['pp_centro'];
						$sx .= '<TD>'.$line['centro_nome'];
						$sx .= '<TD>'.$line['ar_titulo'];
						$sx .= '<TD>'.$line['ar_issn'];
						$sx .= '<TD>'.$line['ar_ano'];
						$sx .= '<TD>'.$line['ar_journal'];
						$sx .= '<TD>'.$line['ar_q'];
						$sx .= '<TD>'.$line['ar_er'];
						$sx .= '<TD>'.$line['ar_a'];
						$sx .= '<TD>'.$line['ar_tipo'];
						$sx .= '<TD>'.$line['pp_email'];
						$sx .= '<TD>'.$line['pp_email_1'];
						$sx .= '<TD>'.$line['ar_update'];						
						$sx .= '<TD>'.$line['ar_q'];
						$sx .= '<TD>'.$line['ar_er'];
						$sx .= '<TD>'.$line['ar_a'];						
					}
				$sx .= '</table>';
				return($sx);
			}
		
		function normaliza_issn($line)
			{
				$issn_old = $issn;
				$issn = trim(uppercasesql($line['ar_issn']));
				
				$issn_dv = substr($issn,strlen($issn)-1,1);
				$issn = sonumero($issn);
				
				$issn = substr($issn,0,4).'-'.substr($issn,4,3).$issn_dv;
				
				if ($issn != $issn_old)
					{
						$sql = "update ".$this->tabela." 
							set ar_issn = '".$issn."' where ar_issn = '".$issn_old."' 
						";
						$rlt = db_query($sql);
					}
				
			}
		
		function resumo_issn()
			{
				$st = $this->status();
				$sql = "select * from ".$this->tabela."
							inner join pibic_professor on pp_cracha = ar_professor 
							where ar_status <> 0 and ar_status <> 9
						order by ar_issn, ar_titulo, ar_professor
					";
				$rlt = db_query($sql);
				$sx = '<h1>Artigos cadastrados</h1>';
				$sx .= '<table width="100%" class="tabela00">';
				$xissn = '';
				$xtitle = '';
				while ($line = db_read($rlt))
					{
						$cor = '';
						$issn = trim($line['ar_issn']);
						$title = substr(uppercasesql(trim($line['ar_titulo'])),0,10);
						if (($issn == $xissn) and ($title == $xtitle)) 
							{ $cor = '<font color="red">'; }
						$xissn = $issn;
						$xtitle = $title;
						
						$this->normaliza_issn($line);
						$link = '<A HREF="artigo_detalhe.php?dd0='.$line['id_ar'].'">';
						$sx .= '<TR>';
						$sx .= '<TD><NOBR>';
						$sx .= $link;
						$sx .= $line['ar_protocolo'];
						$sx .= '</A>';
						
						/* */
						$sx .= '<TD><NOBR>';
						$sx .= $line['ar_issn'];
						$sx .= '<TD>';
						$sx .= $cor;
						$sx .= $line['ar_titulo'];
						$sx .= '</font>';
						
						$sx .= '<TD><NOBR>';
						$sx .= $cor;
						$sx .= $line['pp_nome'];
						$sx .= ' ';
						$sx .= '('.$line['ar_professor'].')';
						$sx .= '</font>';

						$sx .= '<TD><nobr>';
						$sx .= $st[$line['ar_status']];
						$sx .= ' ('.$line['ar_status'].')';
					}
				$sx .= '</table>';
				return($sx);
			}
		
		function mostra_artigo()
			{
				global $editar;
				$sql = "select * from ".$this->tabela." 
					where ar_professor = '".$this->docente."' 
				";
				$rlt = db_query($sql);
				$sx = '';
				$sx .= '<fieldset>';
				$sx .= '<h2>Artigos</h2>';
				//$sx .= '<font class="lt1">'.msg('captacao_pesq_inf').'</font>';
				$sx .= '<table width="100%" class="tabela00">';
				$sx .= '<TR><TH>Protocolo<TH>Título<TH>ISSN<TH>Periódico<TH>Status';
				if ($editar==1) { $sx .= '<TH>ação'; }
				$xano = 0;
				$tot = 0;
				while ($line = db_read($rlt))
					{
						$tot++;
						$ano = $line['ca_vigencia_ini_ano'];
						if ($xano != $ano)
							{
								$sx .= '<TR class="lt1"><TD colspan=7><B>'.$ano;
								$xano = $ano;
							}
						$editar = 0;
						$sta = round($line['ca_status']);
						if (($sta==0) or ($sta==8)) { $editar = 1; }
						$sx .= $this->mostra_artigo_lista($line);
					}
				if ($tot == 0)
					{
						$sx .= '<TR><TD colspan=9><font class="lt4"><center><font color="orange">sem projetos cadastrado';
					}
				$sx .= '</table>';
				$sx .= '</fieldset>';
				return($sx);				
			}

		function mostra_artigo_lista($line,$tipo=0)
			{
				global $editar,$user,$perfil;
				
				$sss = $this->status();

				$sta = round($line['ar_status']);
				$sx .= '<TR '.coluna().' valign="top">';
				$sx .= '<TD class="tabela01">';
				
				if ($sta != 0)
					{
						$link = '<A HREF="'.http.'/cip/artigo_detalhe.php?dd0='.$line['id_ar'].'&dd90='.checkpost($line['id_ca']).'" target="nw'.$line['id_ca'].'">';
					} else {
						$link = '<A HREF="'.http.'/cip/artigo_novo.php?pag=1&dd0='.$line['id_ar'].'&dd90='.checkpost($line['id_ca']).'" target="nw'.$line['id_ca'].'">';						
					}
				$sx .= $link.$line['ar_protocolo'].'</A>';
				
				$sx .= '<TD class="tabela01">'.$cor;
				$sx .= $link.$line['ar_titulo'].'</A>';
				$sx .= '<TD class="tabela01">'.$cor;
				$sx .= $line['ar_issn'];
				$sx .= '<TD class="tabela01">'.$cor;
				$sx .= $line['ar_journal'];
				$sx .= '<BR><font class="lt0">Atualizado '.stodbr($line['ca_update']);

				$sx .= '<TD align="center" class="tabela01">'.$sss[$line['ar_status']];
 
				
				if (($editar==1) and ($user->user_cracha == trim($line['ca_professor'])))
					{
						$sx .= '<TD class="tabela01">'.$cor;
						$sx .= '<A HREF="'.http.'/cip/captacao_novo.php?dd0='.$line['id_ca'].'&pag=1&dd90='.checkpost($line['id_ca']).'">';
						$sx .= 'editar';
						$sx .= '</A>';
					}
				
				return($sx);
			}		
		
	function acoes_11()
		{
			global $dd;
			$this->alterar_status('11');
			echo 'Alterado';
			
		}
	function comunicar_professor($ict)
		{
			global $dd,$email_adm, $admin_nome,$http;
			$ic = new ic;
			$ms = $ic->ic($ict);
			
			$professor = $this->autor_nome;
			$protocolo = $this->protocolo;
			$valor = number_format($this->line['ar_v1']+$this->line['ar_v2']+$this->line['ar_v3']+$this->line['ar_v4']+$this->line['ar_v5'],2,',','.');
			
			$titulo = trim($ms['nw_titulo']).' - '.$professor.' - '.$protocolo;
			$texto = $ms['nw_descricao'];
			$texto = troca($texto,'$MOTIVO',$dd[5]);
			$texto = troca($texto,'$professor',$professor);
			$texto = troca($texto,'$protocolo',$protocolo);
			$texto = troca($texto,'$valor',$valor);
			
			$texto = '<img src="'.$http.'img/email_cip_header.png" ><BR>' 
							. $texto;
			$texto .= '<BR><BR><img src="'.$http.'img/email_cip_foot.png" >';
			$texto = mst($texto);
			
			$email1 = trim($this->line['pp_email']);
			$email2 = trim($this->line['pp_email_1']);
			
			$eamil3 = trim($email_adm);
			$email3 = 'renefgj@gmail.com';
			
			enviaremail('cip@pucpr.br','',$titulo.' [copia]',$texto);
			enviaremail($email3,'',$titulo,$texto);
			if (strlen($email1) > 0) { enviaremail($email1,'',$titulo,$texto); }
			if (strlen($email2) > 0) { enviaremail($email2,'',$titulo,$texto); }
		}
	function pagamentos()
		{
			$v1 = $this->line['ar_v1'];
			$v2 = $this->line['ar_v2'];
			$v3 = $this->line['ar_v3'];
			$v4 = $this->line['ar_v4'];
			$v5 = $this->line['ar_v5'];
			
			$sql = "alter table artigo add column ar_v5 float default 0 ";
															
			$sx = '<table border=0 width="100%" class="tabela00">';
			$sx .= '<TR><TD align="right">Artigo A1  <TD align="left"><B>'.number_format($v1,2).'<B>';
			$sx .= '<TR><TD align="right">Artigo A2  <TD align="left"><B>'.number_format($v2,2).'<B>';
			$sx .= '<TR><TD align="right">Artigo Q1  <TD align="left"><B>'.number_format($v3,2).'<B>';
			$sx .= '<TR><TD align="right">Artigo ExR <TD align="left"><B>'.number_format($v4,2).'<B>';
			$sx .= '<TR><TD align="right">Artigo CI  <TD align="left"><B>'.number_format($v5,2).'<B>';
			$sx .= '<TR><TD align="right"><I>Total</I><TD align="left" class="tabela01"><B>'.number_format($v1+$v2+$v3+$v4+$v5,2).'<B>';
			
			$sx .= '<TR><TD colspan=1>';
			$sx .= '<TD>';
			$link = ' onclick="newxy2(\'artigo_pagamento.php?dd0='.$this->line['id_ar'].'&dd90='.checkpost($this->line['id_ar']).'\',400,400);" ';
			$sx .= '<input type="button" value="editar" class="submit-geral" '.$link.' >';
			$sx .= '</table>';
			return($sx);
		}
	/* LIberado pela diretoria de pesquisa */
	function acoes_22()
		{
			global $dd,$hd,$nw;
			$sta = $this->status();
			$action = 'A22';
			$historico = $sta[22].' - '.$nw->user_login;
			$this->alterar_status('22');
			$this->historico_inserir($this->protocolo,$action,$historico);
			echo 'Alterado';
			redirecina(page().'?dd0='.$dd[0]);
			exit;			
		}
	/* Cancelar solicitação */
	function acoes_9()
		{
			global $dd,$hd,$nw;
			$sta = $this->status();
			$action = 'A09';
			$historico = $sta[9].' - '.$nw->user_login;
			$this->comunicar_professor('bon_artigo_cancela');
			$this->alterar_status('9');
			$this->historico_inserir($this->protocolo,$action,$historico);
			redirecina(page().'?dd0='.$dd[0]);
			exit;			
		}		
	/* Cancelar solicitação */
	function acoes_23()
		{
			global $dd,$hd,$nw;
			$sta = $this->status();
			$action = 'A23';
			$historico = $sta[23].' - '.$nw->user_login;
			$this->comunicar_professor('bon_art_indeferido');
			$this->alterar_status('23');
			$this->historico_inserir($this->protocolo,$action,$historico);
			redirecina(page().'?dd0='.$dd[0]);
			exit;			
		}		
	/* Cancelar solicitação */
	function acoes_25()
		{
			global $dd,$hd,$nw;
			$sta = $this->status();
			$action = 'A25';
			$historico = $sta[25].' - '.$nw->user_login;
			$this->comunicar_professor('bon_comunicacao_arti');
			$this->alterar_status('25');
			$this->historico_inserir($this->protocolo,$action,$historico);
			redirecina(page().'?dd0='.$dd[0]);
			exit;			
		}		
	/* Devlvoer para correções */
	function acoes_8()
		{
			global $dd,$hd,$nw;
			$sta = $this->status();
			$action = 'A08';
			$historico = $sta[8].' - '.$nw->user_login;
			$this->comunicar_professor('bon_artigo_correcoes');
			$this->alterar_status('8');
			$this->historico_inserir($this->protocolo,$action,$historico);
			$this->alterar_status('0');
			redirecina(page().'?dd0='.$dd[0]);
			exit;			
		}		
	/* Cancelar solicitação */
	function acoes_10()
		{
			global $dd,$hd,$nw;
			$sta = $this->status();
			$action = 'A10';
			$historico = $sta[15].' - '.$nw->user_login;
			$this->alterar_status('10');
			$this->historico_inserir($this->protocolo,$action,$historico);
			redirecina(page().'?dd0='.$dd[0]);
			exit;			
		}		

	/* Gerado formulário */
	function acoes_24()
		{
			global $dd,$hd,$nw,$bon;
			
			$this->gerar_formulario_pagamento();
			$bon->updatex();
			
			$sta = $this->status();
			$action = 'A24';
			$historico = $sta[24].' - '.$nw->user_login;
			$this->alterar_status('24');
			$this->historico_inserir($this->protocolo,$action,$historico);
			echo 'Alterado';
			redirecina(page().'?dd0='.$dd[0]);
			exit;			
		}		
	/* Cancelar solicitação */
	function acoes_90()
		{
			global $dd,$hd,$nw;
			$sta = $this->status();
			$action = 'A90';
			$historico = $sta[90].' - '.$nw->user_login;
			//$this->comunicar_professor('bon_comunicacao_arti');
			$this->alterar_status('90');
			$this->historico_inserir($this->protocolo,$action,$historico);
			redirecina(page().'?dd0='.$dd[0]);
			exit;			
		}		

	function gerar_formulario_pagamento()
		{
			
			$data = date("Ymd");
			$professor = $this->line['ar_professor'];
			$professor_nome = trim($this->autor_nome);
			$art_titulo = trim($this->line['ar_titulo']);
			$art_journal = trim($this->line['ar_journal']);
			$protocolo = 'AR'.substr($this->protocolo,2,5);
			$valor = $this->line['ar_v1'] + $this->line['ar_v2'] + $this->line['ar_v3'] + $this->line['ar_v4'] + $this->line['ar_v5'];
			$tipo = 'BNI';
			
			$titulo = 'Repasse de Artigo ';
			 
			$texto = 'Repasse referente ao artigo científico intitulado <B>'.$art_titulo.'</B> publicado no periódico <B>'.$art_journal.'</B>, protocolo do CIP, nº '.$protocolo.', artigo ';
			if ($this->line['ar_v1'] > 0) { $texto .= '[Qualis A1] '; $titulo .= '[Qualis A1] '; }
			if ($this->line['ar_v2'] > 0) { $texto .= '[Qualis A2] '; $titulo .= '[Qualis A2] ';  }
			if ($this->line['ar_v3'] > 0) { $texto .= '[Q1 Scimago] '; $titulo .= '[Q1 Scimago] '; }
			if ($this->line['ar_v4'] > 0) { $texto .= '[ExR Scimago] '; $titulo .= '[ExR Scimago] '; }
			if ($this->line['ar_v5'] > 0) { $texto .= '[Colaboração Internacional] '; $titulo .= '[Colaboração Internacional] '; }
			$titulo .= ' - Ato normativo';
			
			$texto .= ' em Filosofia/Teologia: subcomissão Filosofia';
			$texto .= ', conforme Ato Normativo 02/2013.';
			$texto .= chr(13).chr(10).chr(13).chr(10).'Professor do Programa de Pós-Graduação em Filosofia- PPGF.';
			$descricao = $texto;	
			
			$sql = "select * from bonificacao ";
			$sql .= " where bn_original_protocolo = '".$protocolo."' and bn_original_tipo = '$tipo' ";
			$rlt = db_query($sql);
	
			if (!($line = db_read($rlt)))
				{
					$sql = "insert into bonificacao 
						(
						bn_codigo, bn_professor, bn_professor_nome,
						bn_professor_cracha, bn_data, bn_hora,
						bn_status, bn_descricao, bn_nome, bn_valor,
						bn_aprovador, bn_ordenador, bn_liberacao,
						bn_previsao, bn_original_protocolo, bn_original_tipo, 
						bn_renuncia, bn_data_implementacao,
						bn_beneficiario, bn_rf_parcela, bn_rf_valor, 
						bn_modalide, crp_data_1_str, crp_data_2_str
						) values (
						'','$professor','$professor_nome',
						'$professor',$data,'0',
						'A','$descricao','$titulo',$valor,
						'','',$data,
						$data,'$protocolo','$tipo',
						'0',$data,
						'$professor','1','0',
						'','',''
						);
					";
					$rlt = db_query($sql);
					return(1);
				} else {
					print_r($line);
					echo 'Formulário já cadastrado';
				}
				return(0);
		}
	function acoes()
		{
			global $dd,$acao;
			$st = trim($this->line['ar_status']);
			
			if ((strlen($acao) > 0) and (strlen($dd[1]) > 0))
				{
					switch ($dd[1])
						{
						case '1': 
							if ($st != '1')
								{
								$this->acoes_22(); 
								//$this->historico('11');
								}
							break;							
						case '10': 
							if ($st != '10')
								{
								$this->acoes_10(); 
								//$this->historico('11');
								}
							break;							
						case '11': 
							if ($st != '11')
								{
								$this->acoes_11(); 
								//$this->historico('11');
								}
							break;
						case '22': 
							if ($st != '22')
								{
								$this->acoes_22(); 
								$this->historico('22');
								}
							break;
						case '23': 
							if ($st != '23')
								{
								$this->acoes_23(); 
								$this->historico('23');
								}
							break;
						case '24': 
							if ($st != '24')
								{
								$this->acoes_24(); 
								$this->historico('24');
								}
							break;
						case '9': 
							if ($st != '9')
								{
								$this->acoes_9(); 
								//$this->historico('9');
								}
							break;
						case '25': 
							//if ($st != '25')
								{
								$this->acoes_25(); 
								//$this->historico('9');
								}
							break;
						case '8': 
							if ($st != '8')
								{
								$this->acoes_8(); 
								//$this->historico('9');
								}
							break;	
						case '10': 
							if ($st != '10')
								{
								$this->acoes_10(); 
								//$this->historico('9');
								}
							break;																																		
						case '90':
							echo '==============='; 
							if ($st != '90')
								{
								$this->acoes_90(); 
								//$this->historico('9');
								}
							break;					
						default:
							echo '-não localizado->executa acao '.$dd[1];																													
						}
					
				}
			
			$acoes = array();
			switch ($st)
				{
				case '8': /* Cadastrado */
					array_push($acoes,array('10','Acatar para análise'));
					array_push($acoes,array('9','Cancelar'));
					break;					
				case '0': /* Cadastrado */
					array_push($acoes,array('10','Acatar para análise'));
					array_push($acoes,array('9','Cancelar'));
					break;					
				case '10': /* Cadastrado */
					array_push($acoes,array('8','Devolvido ao professor para correções'));
					array_push($acoes,array('11','Com bonificação'));
					array_push($acoes,array('13','Sem bonificação'));
					array_push($acoes,array('9','Cancelar'));
					break;
				case '11': /* Cadastrado */
					array_push($acoes,array('8','Devolvido ao professor para correções'));
					array_push($acoes,array('22','Validado pelo(a) Diretor(a) de Pesquisa'));
					array_push($acoes,array('23','Indeferido  pelo(a) Diretor(a) de Pesquisa'));
					array_push($acoes,array('9','Cancelado'));
					break;					
				case '1': /* Cadastrado */
					array_push($acoes,array('8','Devolvido ao professor para correções'));
					array_push($acoes,array('22','Validado pelo(a) Diretor(a) de Pesquisa'));
					array_push($acoes,array('23','Indeferido  pelo(a) Diretor(a) de Pesquisa'));
					array_push($acoes,array('9','Cancelado'));
					break;	
				case '22': /* Cadastrado */
					array_push($acoes,array('24','Gerado formulário de pagamento'));
					array_push($acoes,array('1','Reencaminhado para Diretor(a) de Pesquisa'));
					array_push($acoes,array('8','Devolvido ao professor para correções'));				
					break;										
				case '23': /* Cadastrado */
					array_push($acoes,array('22','Validado pelo(a) Diretor(a) de Pesquisa'));
					break;										
				case '24': /* Cadastrado */
					array_push($acoes,array('1','Reencaminhado para Diretor(a) de Pesquisa'));
					array_push($acoes,array('25','Comunicado pesquisador'));
					array_push($acoes,array('90','Finalizar'));
					break;										
				case '25': /* Cadastrado */
					array_push($acoes,array('1','Reencaminhado para Diretor(a) de Pesquisa'));
					array_push($acoes,array('25','Comunicado pesquisador'));
					array_push($acoes,array('90','Finalizar'));
					break;
				case '90':
					array_push($acoes,array('1','Reencaminhado para Diretor(a) de Pesquisa'));										
				default:
					
					echo '-->'.$st;
					break;
				}
			$sx = '<form method="post" action="'.page().'">
					<input type="hidden" name="dd0" value="'.$this->line['id_ar'].'">
					';
			$sx .= 'Observações<BR>';
			if (strlen($acao) == 0)
				{
					$dd[5] = $this->line['ar_obs'];
				}
			$sx .= '<textarea name="dd5" rows=6 cols=60>'.$dd[5].'</textarea>';
			$sx .= '<BR><BR>Ações<BR>';
			for ($r=0;$r < count($acoes);$r++)
				{
					$sx .= '<input type="radio" name="dd1" value="'.$acoes[$r][0].'"> ';
					$sx .= $acoes[$r][1];
					$sx .= '<BR>';
				}
			$sx .= '<BR>';
			$sx .= '<input type="submit" name="acao" value="encaminhar >>>" class="botao-geral">';
			$sx .= '</form>';
			if (count($acoes)==0) { $sx = ''; }
			return($sx);
		}
		
	function alterar_status($sta)
		{
			$sql = "update ".$this->tabela."
				set ar_status = $sta
				where ar_protocolo = '".($this->protocolo)."'";
			$rlt = db_query($sql);
		}				

	function enviar_email_coordenador()
		{
			global $ss;
			require_once("../_class/_class_programa_pos.php");
			$pos = new programa_pos;
			require_once("../_class/_class_ic.php");
			$ic = new ic;
			$ic = $ic->ic("ART_FASE_10");
			$assunto = $ic['nw_titulo'];
			$texto = $ic['nw_descricao'];
			$texto .= $this->mostra();
			$texto .= '<BR><BR><font style="font-size:8px">'.'ART_FASE_10</font>';
			$email = $pos->coordenador_do_professor($ss->user_cracha);
			$email = array();
			array_push($email,'cip@pucpr.br');
			//array_push($email,$email_adm);
			for ($r=0;$r < count($email);$r++)
			{
				enviaremail($email[$r],'',$assunto,$texto);	
				echo '<BR>Enviar e-mail para '.$email[$r];
			}
			echo '<BR><BR>';
			echo $this->mostra();
			echo '<H2>Artigo cadastrado/alterado com sucesso</h2>';	
		}
	function mostra_status($status)
		{
			$sta = array(0=>'Em cadastro',10=>'Cadastrado','11'=>'Em análise',8=>'Correção do professor');
			return($sta[$status]);
		}
	function lista_artigos($sta,$cracha)
		{
			if (strlen($cracha) > 0)
				{
					$wh = " where ar_professor = '".$cracha."' ";
					if (strlen($sta) > 0 )
						{ $wh .= ' and ar_status = '.$sta;	}
				} else {
					if (strlen($sta) > 0 )
						{ $wh .= ' where ar_status = '.$sta;	}					
				}
			$sql = "select * from ".$this->tabela."
					inner join pibic_professor on pp_cracha = ar_professor
				$wh 
				order by pp_nome, ar_tipo, ar_status
			";
			$rlt = db_query($sql);
			$sx = '<table width="100%" class="tabela00">';
			$sx .= '<TR><TH>protocolo<th>ISSN
						<th>Journal
						<TH>Título do artigo
						<TH>Status';
			$tot = 0;
			$xprof = '';
			while ($line = db_read($rlt))
			{
				$prof = trim($line['pp_nome']);
				if ($xprof != $prof)
					{
						$sx .= '<TR><TD colspan=5 class="lt4">'.$prof;
						$xprof = $prof;
					}
				$tot++;
				$sx .= $this->mostra_artigo_row($line);
			}
			$sx .= '<TR><TD colspan=5>'.msg('total').' '.$tot;			
			$sx .= '</table>';
			return($sx);
			
		}
	function total_artigos_validar($professor='')
		{
			global $art;
			$sql = "select * from artigo
					inner join 
						( 
						select pdce_programa, pdce_docente 
							from programa_pos_docentes 
							where pdce_ativo = 1
							group by pdce_programa, pdce_docente
						) as tabela on ar_professor = pdce_docente
					inner join programa_pos on pdce_programa = pos_codigo
					inner join pibic_professor on pp_cracha = ar_professor
					where pos_coordenador = '$professor'
					and ar_status = 10
					order by pp_nome, ar_titulo
			 ";						 
			$rlt = db_query($sql);
						
			$id = 0;
			$sx = '';
			$sx .= '<h3>Captações para validação do Coordenador</h3>';
			$sx .= '<table class="tabela00" width="100%">';
			$sx .= '<TR><TH>Journal / Revista
					<TH colspan=1>ISSN
					<TH>Título do artigo
					<TH>Solicitação
					<TH><I>Status</I>
					<TH>Docente';
			while ($line = db_read($rlt))
				{
					$id++;
					$sx .= $art->mostra_artigo_coordenador_lista($line);
					$sx .= '<TD>';
					$sx .= '<A HREF="artigo_validar_coordenador.php?dd0='.$line['id_ar'].'&dd90='.checkpost($line['id_ar']).'" class="botao-geral">';
					$sx .= '<font color="white">validar</font>';
					$sx .= '</A>';
					$ln = $line;					
				}
			if ($id == 0)
				{ $sx .= '<TR><TD colspan=10 class="lt4"><font color="red">Nenhum projeto para validar</font>'; }
			$sx .= '</table>';				
			
			return($sx);
		}
		function mostra_artigo_coordenador_lista($line)
			{
				global $editar;
				$sss = $this->status();
				
				$pa = trim($line['ca_participacao']);
				$participacao = 'A';
				
				$cor = '';
			
				if ($pa == 'O')
					{ $cor = '<font color="blue">'; }
				if ($line['ca_status']==9)
					{ $cor = '<font color="red">'; }
				$sta = round($line['ar_status']);
				
				$sx .= '<TR '.coluna().' valign="top">';

				$sx .= '<TD align="left" class="tabela01">'.$cor;
				$sx .= $line['ar_journal'];
				
				$sx .= '<BR><font class="lt0">Atualizado '.stodbr($line['ar_update']);

				$sx .= '<TD align="left" class="tabela01">'.$cor;
				$sx .= $line['ar_issn'];

				$sx .= '<TD align="left" class="tabela01">'.$cor;
				$sx .= $line['ar_titulo'];

				$sx .= '<TD align="center" class="tabela01">'.$cor.$sss[$sta].'';
				
				$status = $line['ca_status'];
				if (($editar==1) and ($status==10))
					{
						$sx .= '<TD align="center" class="tabela01">'.$cor;
						$sx .= '<A HREF="javascript:newxy2(\'artigo_coordenador_popup.php?dd0='.$line['id_ca'].'&dd90='.checkpost($line['id_ca']).'\',770,600);">';
						$sx .= 'validar';
						$sx .= '</A>';
					} else {
						$sx .= '<TD align="center" class="tabela01">-';
					}
				$sx .= '<TD align="left" class="tabela01">'.$cor;
				$sx .= trim($line['pp_nome']);
				
				//$sx .= '>>'.$line['ca_status'];
				//print_r($line);
				//echo '<HR>';
				return($sx);
			}		
	function mostra_artigo_row($line)
		{
				$id = $line['id_ar'];
				$link = '<A HREF="artigo_detalhe.php?dd0='.$id.'" class="link">';
				$sx .= '<TR>';
				$sx .= '<TD class="tabela01">';
				$sx .= $link;
				$sx .= trim($line['ar_protocolo']);
				$sx .= '</A>';
				$sx .= '<TD class="tabela01">';
				$sx .= trim($line['ar_issn']);
				$sx .= '<TD class="tabela01">';
				$sx .= trim($line['ar_journal']);
				$sx .= '<TD class="tabela01">';
				$sx .= trim($line['ar_titulo']);
				$sx .= '<TD class="tabela01"><nobr>';
				$sx .= $this->mostra_status($line['ar_status']);
				return($sx);
		}
	function resumo($cracha)
		{
			global $http;	
			if (strlen($cracha) > 0)
				{
					$wh = " where ar_professor = '".$cracha."' ";
				}
			$sql = "select count(*) as total, ar_tipo, ar_status from ".$this->tabela."
				$wh 
				group by ar_tipo, ar_status
				order by ar_tipo, ar_status
			";
			
			$rlt = db_query($sql);
			$api = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
			$apb = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
			while ($line = db_read($rlt))
				{
					$sta = round($line['ar_status']);
					$total = $line['total'];
					switch ($sta) {
						case 0:
							$api[9] = $api[9] + $line['total'];
							break;
						case 10:
							$api[0] = $api[0] + $line['total'];
							break;
						case 8:
							$api[8] = $api[8] + $line['total'];
							break;
						case 1:
							$api[6] = $api[6] + $line['total'];
							break;
						case 11:
							$api[1] = $api[1] + $line['total'];
							break;
						case 22:
							$api[22] = $api[22] + $line['total'];
							break;
						case 24:
							$api[24] = $api[24] + $line['total'];
							break;
						case 23:
							$api[23] = $api[23] + $line['total'];
							break;
						case 90:
							$api[25] = $api[25] + $line['total'];
							break;
						default:
							echo '==>'.$sta;
							$api[11] = $api[11] + $line['total'];
							break;
					}
				}
			$sx = '<table class="tabela00" width="100%" align="center">';
			$sx .= '<TR><TD><TD colspan=10><center><h2>Cadastro de Artigos Bonificáveis</h2>';
			$sx .= '<TR>';
			$sx .= '<TH>';
			$sx .= '<TH width="10%">Em cadastro pelo professor';
			$sx .= '<TH width="10%">Para correção do professor';
			$sx .= '<TH width="10%">Em validação do coordenador';
			$sx .= '<TH width="10%">(Outros 1)';
			$sx .= '<TH width="10%">Análise do(a) Diretor(a) de Pesquisa';
			$sx .= '<TH width="10%">(outros 2)';
			$sx .= '<TH width="10%">Não bonificados para finalizar';
			$sx .= '<TH width="10%">Liberação da Secretaria';
			$sx .= '<TH width="10%">Comunicar o professor';
			$sx .= '<TH width="10%">Finalizado';
			$sx .= '<TH width="10%">Indeferido';
			
			$link9 = '<A HREF="'.$http.'cip/artigo_listar.php?dd0=0&dd90='.checkpost(0).'">';
			$link8 = '<A HREF="'.$http.'cip/artigo_listar.php?dd0=8&dd90='.checkpost(8).'">';
			$link0 = '<A HREF="'.$http.'cip/artigo_listar.php?dd0=10&dd90='.checkpost(10).'">';
			$link1 = '<A HREF="'.$http.'cip/artigo_listar.php?dd0=11&dd90='.checkpost(11).'">';
			$link3 = '<A HREF="'.$http.'cip/artigo_listar.php?dd0=11&dd90='.checkpost(9).'">';
			$link6 = '<A HREF="'.$http.'cip/artigo_listar.php?dd0=1&dd90='.checkpost(1).'">';
			$link22 = '<A HREF="'.$http.'cip/artigo_listar.php?dd0=22&dd90='.checkpost(22).'">';
			$link24 = '<A HREF="'.$http.'cip/artigo_listar.php?dd0=24&dd90='.checkpost(24).'">';
			$link90 = '<A HREF="'.$http.'cip/artigo_listar.php?dd0=90&dd90='.checkpost(90).'">';
			$link23 = '<A HREF="'.$http.'cip/artigo_listar.php?dd0=23&dd90='.checkpost(23).'">';
			
			$sx .= '<TR>';
			$sx .= '<TD align="right">Artigos';
			$sx .= '<TD class="tabela01" align="center">'.$link9.$api[9].'</A>';
			$sx .= '<TD class="tabela01" align="center">'.$link8.$api[8].'</A>';
			$sx .= '<TD class="tabela01" align="center">'.$link0.$api[0].'</A>';
			$sx .= '<TD class="tabela01" align="center">'.$link1.$api[1].'</A>';
			$sx .= '<TD class="tabela01" align="center">'.$link6.$api[6].'</A>';
			$sx .= '<TD class="tabela01" align="center">'.$link2.$api[2].'</A>';
			$sx .= '<TD class="tabela01" align="center">'.$link3.$api[3].'</A>';
			$sx .= '<TD class="tabela01" align="center">'.$link22.$api[22].'</A>';
			$sx .= '<TD class="tabela01" align="center">'.$link24.$api[24].'</A>';
			$sx .= '<TD class="tabela01" align="center">'.$link90.$api[25].'</A>';
			$sx .= '<TD class="tabela01" align="center">'.$link23.$api[23].'</A>';
					
			$sx .= '</table>';
			return($sx);
		}

		function status()
			{
				$ar = array(
				''=>'Não validado',
				'0'=>'Em cadastro',
				'80'=>'Em processo de bonificação',
				'81'=>'Bonificado',
				'99'=>'Não validado pelo coordenador',
				'98'=>'Validado, sem bonificação',
				'10'=>'Validado pelo professor',
				
				'90'=>'Não validado pelo coordenador',
				
				'1'=>'Com bonificação',
				'2'=>'Sem bonificação',
				
				'11'=>'Com bonificação e isenção',
				'12'=>'Com bonificação e sem isenção',
				
				'13'=>'Sem bonificação e com isenção',
				'14'=>'Sem bonificação e sem isenção',
				
				'15'=>'Aceito trabalho pela coordenação',
				
				'22'=>'Validado pelo(a) Diretor(a) de Pesquisa',
				'23'=>'Indeferido  pelo(a) Diretor(a) de Pesquisa',
				'24'=>'Gerado formulario de pagamento',
				'25'=>'Comunicado professor',
				'26'=>'Marcado bonificação ',
				
				'89'=>'Processo finalizado',
				
				'8'=>'Devolvido ao professor para correções',			
				'9'=>'Cancelado');
				return($ar);
			}
		
		function mostra_referencia($line)
			{
				$jl .= '<B>'.trim($line['ar_journal']).'</B>';
				$ti .= trim($line['ar_titulo']).'. ';
				$is .= trim($line['ar_issn']);
				
				if (strlen($is) > 0) { $is = ', ISSN: '.$is; }
				$an = trim($line['ar_ano']);
				if (strlen($an) > 0) { $an = ', '.$an; }
				$nr = trim($line['ar_num']);
				if (strlen($nr) > 0) { $nr = ', n. '.$nr.''; }
				$vl = trim($line['ar_vol']);
				if (strlen($vl) > 0) { $vl = ', v. '.$vl.''; }

				$pag = trim($line['ar_pags']);
				if (strlen($pag) > 0) { $pag = ', p. '.$pag.''; }
				
				$sx = $ti.$jl.$vl.$nr.$pag.$an.$is;
				return($sx);
			}
		function historico_inserir($protocolo,$ope,$historico)
			{
				global $user, $dd;
				$text = $dd[5];
				
				$protocolo = 'AR'.strzero(round($protocolo),5);
				$data = date("Ymd");
				$hora = date("H:i");
				$login = $user->user_id;
				$historico = substr($historico,0,80);								
				$sql = "select * from bonificacao_historico
						where bnh_data = $data and bnh_ope = '$ope'
						and bnh_protocolo = '$protocolo' ";
				$rlt = db_query($sql);
				if (!($line = db_read($rlt)))
					{
					$sql = "insert into bonificacao_historico 
					(bnh_data, bnh_hora, bnh_historico,
						bnh_ope, bnh_log, bnh_protocolo, bnh_descricao)
					values
					($data,'$hora','$historico',
					'$ope','$login','$protocolo','$text')";
					$rlt = db_query($sql);
					} else {
						$sql = "update bonificacao_historico 
								set bnh_historico = '$historico',
								bnh_descricao = '$text'
								where id_bnh = ".$line['id_bnh'];
						$rlt = db_query($sql);	
						
					}
				return(1);
			}
	function validacao_pelo_coordenador()
		{
			global $dd, $acao, $ss;
			$sta = $this->status();
			
			if (strlen($acao) > 0)
				{
					$sql = "update artigo set 
							ar_status = ".round($dd[3]).", 
							ar_coordenador = ".$ss->user_cracha.",
							ar_comentario = '".$dd[2]."' 
							where id_ar = ".round($this->id);
					
					$rlt = db_query($sql);
					$this->historico_inserir($this->protocolo,'VAC','Validado por '.trim($ss->user_login).' - '.$sta[round($dd[3])]);					
					redirecina('../cip/artigos_validar.php');					
				} else {
					$sql = "select * from artigo 
							where id_ar = ".round($this->id);
					$rlt = db_query($sql);
					$line = db_read($rlt);
					$dd[2] = $line['ar_comentario'];
				}
					
			$sx = '<form method="post">';
			$sx .= '<h3>Avaliação do coordenador</h3>';
			$sx .= '<input type="hidden" name="dd0" value="'.$dd[0].'">';
			$sx .= '<input type="hidden" name="dd90" value="'.$dd[90].'">';
			$sx .= '<font class="lt0">Comentários do coordenador</font><BR>';
			$sx .= '<textarea name="dd2" rows=80 style="width: 100%; height: 70px;">'.$dd[2].'</textarea>';
			$sx .= '<BR><BR><font class="lt0">Informe o tipo de ação para o projeto:</font><BR>';
			
			/* Projeto como coordenador */
			$sx .= '<input TYPE="RADIO" name="dd3" value="8">'.$sta[8].'<BR>';
			$sx .= '<input TYPE="RADIO" name="dd3" value="1">'.$sta[1].'<BR>';
			$sx .= '<input TYPE="RADIO" name="dd3" value="2">'.$sta[2].'<BR>';
			
			$sx .= '<input TYPE="RADIO" name="dd3" value="9">'.$sta[9].'<BR>';
			$sx .= '<BR><BR><input TYPE="submit" name="acao" value="encaminhar >>>" class="botao-geral">';	
			$sx .= '</form>';
			return($sx);
		}
		function historico_mostrar($protocolo,$protocolo_origem)
			{								
				$protocolo2 = round($protocolo);
				$protocolo = 'AR'.strzero(round($protocolo),5);
				$sql = "select * from bonificacao_historico
						where bnh_protocolo = '$protocolo' 
						or bnh_protocolo = '$protocolo'
						";
				
				$rlt = db_query($sql);				
				$sx = '<table width="100%" class="lt1">';
				$sx .= '<TR><TH>Data<TH>Descrição<TH>Ação';
				while ($line = db_read($rlt))
					{
						$sx .= '<TR>';
						$sx .= '<TD class="tabela01" width="10%"><NOBR>'.stodbr($line['bnh_data']);
						
						$sx .= ' '.($line['bnh_hora']);
						$sx .= '<TD class="tabela01" width="80%"><B>'.($line['bnh_historico']).'</B>';
						$sx .= '<TD class="tabela01" width="10%"><NOBR>'.($line['bnh_ope']);
						
						$text = trim($line['bnh_descricao']);
						if (strlen($text) > 0)
							{
								$sx .= '<TR><TD><TD>'.mst($text);
							}
					}
				$sx .= '</table>';
				return($sx);
				
			}
		function mostra()	
			{
				global $ged;
				//$this->updatex();
				$line = $this->line;
				
				$status = $line['ar_status'];
				
				$sta = $this->status();
				$sx .= '<fieldset><legend>'.msg("artigo").'</legend>';
				$sx .= '<table width="100%" class="lt0" border=0>';
				$sx .= '<TR><TD class="tabela01 lt1" colspan=1>&nbsp;'.$line['ar_journal'];
				$sx .= '    <TD class="tabela01 lt1" align="right" colspan=1>&nbsp;'.$sta[$status];
				/* Dados */
				$vol = trim($line['ar_vol']);
				$num = trim($line['ar_num']);
				$ano = trim($line['ar_ano']);
				$pag = trim($line['ar_pags']);
				

				$sx .= '<TR><TD align="center" class="tabela00 lt4" colspan=2>'.$line['ar_titulo'];
				
				$sx .= '<TR><TD colspan=2 class="lt2">'.$this->mostra_referencia($line);
				
				$sx .= '<TR><TD width="50%">
							<TD width="50%" class="lt1">							
							';
				$sx .= 'Qualis: <B>'.$line['ar_a'].'</B>';
				$sx .= '<BR>Scopus Q: <B>'.$line['ar_q'].'</B>';
				$sx .= '<BR>Excelence Rate: <B>'.$line['ar_er'].'</B>';				
				
				$sx .= '<TR><TD align="center" class="tabela00 lt4" colspan=2>';
				
				/* Arquivos */
				//require_once("_ged_artigo_ged_documento.php");				
				$ged->protocol = $this->protocolo;
				$sx .= $ged->file_list();
				
				$sx .= '</table>';
				$sx .= '</fieldset>';		
						
				return($sx);
			}
		function le($id)
			{
				$sql = "select * from ".$this->tabela." 
						inner join pibic_professor on pp_cracha = ar_professor
						where id_ar = ".$id." ";
				$rlt = db_query($sql);
				
				if ($line = db_read($rlt))
					{
						$this->id = $line['id_ar'];
						$this->protocolo = trim($line['ar_protocolo']);
						$this->autor = trim($line['ar_professor']);
						$this->autor_nome = trim($line['pp_nome']);
						$this->line = $line;
					}
				return($sx);
			}
		
		
		function cp_coordenador_editar()
			{				/** Status **/
				$sss = $this->status();
				$ops = ' 0:'.$sss[0];
				$ops .= '&1:'.$sss[1];
				$ops .= '&9:'.$sss[9];
				$ops .= '&10:'.$sss[10];				
				$ops .= '&99:'.$sss[99];
				$ops .= '&98:'.$sss[98];
				
				$cp = array();
							
				array_push($cp,array('$H8','id_ar','',False,True));
				array_push($cp,array('$H8','ar_professor','',True,True));
				array_push($cp,array('$O '.$ops,'ar_status','Status',True,True));
				
				array_push($cp,array('$T60:3','ca_comentario','Comentário do coordenador',False,True));
				array_push($cp,array('$U8','ca_update','',True,True));
				return($cp);							
			}

		function cp_01()
			{
				global $dd,$acao,$ss,$nw;
				$user = $ss->user_cracha;

				$cp = array();
				array_push($cp,array('$H8','id_ar','',False,True));
				
				array_push($cp,array('$A','',msg('local_de_publicacao'),False,True));
				array_push($cp,array('$S9','ar_issn',msg('ISSN'),True,True));
				array_push($cp,array('$S80','ar_journal',msg('journal_name'),True,True));
				array_push($cp,array('$M','','<font class=lt0>* informe o título original publicado.</font>',False,True));
								
				array_push($cp,array('$A','',msg('titulo_do_artigo'),False,True));
				array_push($cp,array('$T80:4','ar_titulo','',True,True));
				
				array_push($cp,array('$A','',msg('dados_do_artigo'),False,True));
				array_push($cp,array('$S100','ar_doi',msg('DOI'),False,True));
				
				array_push($cp,array('$[2010-'.(date("Y")+1).']','ar_ano',msg('year_published'),True,True));
				array_push($cp,array('$S3','ar_vol',msg('vol'),True,True));
				array_push($cp,array('$S3','ar_num',msg('number'),True,True));
				array_push($cp,array('$S10','ar_pags',msg('pages'),True,True));			
				
								
				if ($dd[0]==0)
					{
						array_push($cp,array('$U8','ar_update',$user,True,True));
						array_push($cp,array('$HV','ar_professor',$user,True,True));
						array_push($cp,array('$HV','ar_status','0',True,True));	
					}
				
				return($cp);
			}

		function cp_02()
			{
				$cp = array();
				//$this->structure();
				$ex_tips = 'O Excellence Rate corresponde a 10% de um conjunto de periódicos mais citados em suas respecticas áreas científicas. É uma medida de alta qualidade de produção de instituições de pesquisa.';
				$ex_tips .= '<BR><font color=blue >Necessário anexar um PDF do SCIMago com a área do Excellence Rate, no próximo passo (3)</font>';
				
				$ex_tips_A1 = '<font color=blue >Necessário anexar um PDF com o PrintScreen da tela do Qualis, no próximo passo (3)</font>';
				
				$ex_tips_Q1 = '<font color=blue >Necessário anexar um PDF com o PrintScreen da tela do Periódico com o Q1 na área específica, no próximo passo (3)</font>';
				
				array_push($cp,array('$H8','id_ar','',False,True));
				
				array_push($cp,array('${','','Bonificação',False,True));
				
				array_push($cp,array('$A','','Estrato WebQualise',False,True));
				array_push($cp,array('$O : &A1:A1&A2:A2&NC:Outro qualis','ar_A','Qualis da publicação',True,True));				
				array_push($cp,array('$M','',$ex_tips_A1,False,True));
				
				array_push($cp,array('$A','','Classificação SCImago',False,True));
				array_push($cp,array('$O : &Q1:Q1&Q2:Q2&Q3:Q3&Q4:Q4&--:Não classificado no SCImago','ar_Q','Classificação SCImago',True,True));				
				array_push($cp,array('$M','',$ex_tips_Q1,False,True));

				array_push($cp,array('$A','','Excellence Rate Report - SCImago',False,True));
				array_push($cp,array('$O : &ER:Excellence Rate (ExR)&--:Não é ExR no SCImago','ar_er','Excellence Rate Report',True,True));
				array_push($cp,array('$M','',$ex_tips,False,True));
				
				array_push($cp,array('$A','','Colaboração com outras instituições',False,True));
				$op = 'NÃO:NÃO, Pesquisa realizada somente na instituição';
				$op .= '&NAC:SIM, Pesquisa realizada em colaboração com instituções brasileiras';
				$op .= '&NAE:SIM, Pesquisa realizada em com técnicos/equipamentos de instituções brasileiras';
				$op .= '&INT:SIM, Pesquisa realizada em colaboração com instituções internacionais';
				$op .= '&INE:SIM, Pesquisa realizada em com técnicos/equipamentos de instituções internacionais';
				$op .= '&NAP:SIM, Pesquisa realizada em cooperação técnica com empresas brasileiras';
				$op .= '&INP:SIM, Pesquisa realizada em cooperação técnica com empresas internacionais';
				array_push($cp,array('$O : &'.$op,'ar_colaboracao','Tipo',True,True));
				array_push($cp,array('$M','',$ex_tips,False,True));

				array_push($cp,array('$A','','Artigo resultado de pesquisa financiada/patrocinada?',False,True));
				$op = 'NÃO:NÃO, sem financiamento';
				$op .= '&SIN:SIM, Por agência de fomento/empresa nacional';
				$op .= '&SII:SIM, Por agência de fomento/empresa internacional';
				array_push($cp,array('$O : &'.$op,'ar_sponsor','Finaciamento/Patrocínio',True,True));
				array_push($cp,array('$M','',$ex_tips,False,True));

				array_push($cp,array('$A','','Participação de alunos como autores da artigo',False,True));
				$op = 'NÃO:NÃO';
				$op .= '&SIP:SIM, alunos de Pós-Graduação';
				$op .= '&SIG:SIM, alunos de Graduação';
				$op .= '&SIG:SIM, alunos de Graduação e Pós-Graduação';
				array_push($cp,array('$O : &'.$op,'ar_estudante','Estudantes',True,True));
				array_push($cp,array('$M','',$ex_tips,False,True));
		
				array_push($cp,array('$A','','Observações',False,True));
				array_push($cp,array('$T80:3','ar_obs','Obs',False,True));		
				array_push($cp,array('$}','','',False,True));
				
				//ar_Q
				
				array_push($cp,array('$HV','ar_status','0',True,True));					
				return($cp);				
			}
		function cp_03()
			{
				$cp = array();
				$tips = 'Para efeito comprobatório é necessário cópia do artigo e das impressões das telas do WEBQualis e do SCImago.<BR>';
				$tips .= 'Em caso de dúvidas, consulte o Passo-a-passo';
				
				array_push($cp,array('$H8','id_ar','',False,True));
				
				array_push($cp,array('$A','','Instruções do Upload de arquivos',False,True));
				array_push($cp,array('$M','',$tips,False,True));
				
				array_push($cp,array('$HV','ar_status','0',True,True));
				
				array_push($cp,array('$HV','','',True,True));		
							
				array_push($cp,array('$B8','','Continuar >>	',False,True));						
				return($cp);				
			}
		function cp_04()
			{
				$cp = array();
				array_push($cp,array('$H8','id_ar','',False,True));
				array_push($cp,array('$HV','ar_status','0',True,True));
				array_push($cp,array('$O : &S:SIM','','Atesto que as informações aqui postadadas são verdadeiras e confirmo envio para validação do coordenador',True,True));	
							
				array_push($cp,array('$B8','','Continuar >>	',False,True));	
				
				array_push($cp,array('$HV','','',True,True));					
				return($cp);				
			}

		function cp_editar()
			{
				global $dd;
				
				
				/** Status **/
				$sss = $this->status();

				$ops = ' 0:'.$sss[0];
				$ops .= '&10:'.$sss[10];
				$ops .= '&9:'.$sss[9];
				global $dd,$acao;
				
				$cp = array();
				array_push($cp,array('$H8','id_ar','',False,True));
				array_push($cp,array('$H8','ca_professor','',True,True));
				
				/****/
				$dd[2] = substr($dd[24],4,2);
				$dd[3] = substr($dd[24],0,4);
				$mf1 = substr($dd[24],4,2);
				$mf2 = substr($dd[24],0,4);
				$dur = round($dd[25]);
				for ($r=0;$r < ($dur-1);$r++)
					{
						$mf1++;
						if ($mf1 > 12)
							{
								$mf1=1;
								$mf2++;
							}
					}
				$dd[4] = $mf1; 
				$dd[5] = $mf2;
				
				array_push($cp,array('$H8','ca_vigencia_ini_mes','',True,True));
				array_push($cp,array('$H8','ca_vigencia_ini_ano','',True,True));
				array_push($cp,array('$H8','ca_vigencia_fim_mes','',True,True));
				array_push($cp,array('$H8','ca_vigencia_fim_ano','',True,True));
				
				array_push($cp,array('$O C:Coordenador&P:Coordenador na Instituição&O:Colaborador','ca_participacao','Participação',False,True));
				
				array_push($cp,array('${','','Descrição',False,True));
				array_push($cp,array('$T60:4','ca_titulo_projeto','Título do projeto',True,True));
				
				array_push($cp,array('$Q agf_nome:agf_codigo:select * from agencia_de_fomento where agf_ativo = 1 order by agf_ordem, agf_nome','ca_agencia','Financiador',True,True));
				array_push($cp,array('$S250','ca_descricao','Nome do edital',True,True));
				array_push($cp,array('$S10','ca_edital_nr','Edital Número',True,True));
				array_push($cp,array('$[2004-'.date("Y").']','ca_edital_ano','Edital Ano',True,True));
				array_push($cp,array('$S20','ca_processo','Processo/Convênio*',True,True));
				array_push($cp,array('$M','','<font class=lt0>* informe "NA" caso não se aplique.</font>',False,True));
				array_push($cp,array('$}','','Descrição',False,True));
				
				
				array_push($cp,array('${','','Vigência',False,True));
				array_push($cp,array('$O '.$op,'ca_vigencia_final_ano','Início da vigência',True,True));
				array_push($cp,array('$O '.$oo,'ca_duracao','Duração',True,True));
				array_push($cp,array('$O '.$oo,'ca_vigencia_prorrogacao','Renovação',True,True));
				array_push($cp,array('$}','','Vigência',False,True));
				
				array_push($cp,array('${','','Instituição Proponente',False,True));
				//array_push($cp,array('$Q ','ca_duracao','Duração',True,True));
				//array_push($cp,array('$Q inst_nome:inst_codigo:select inst_codigo, substring(trim(inst_nome) from 1 for 50) || chr(47) || trim(inst_abreviatura) as inst_nome from instituicao where inst_abreviatura <> \'\' order by inst_nome','ca_proponente','Instituição proponente',True,True));
				array_push($cp,array('$HV','ca_proponente','0000455',True,True));				
				array_push($cp,array('$N8','ca_proponente_vlr','Valor aplicado na PUCPR',True,True));
				array_push($cp,array('$M','','<font class=lt0>O valor aplicado refere-se a quantidade de recursos que serão aplicados na PUCPR, podendo ser qualquer uma das modalidades, capital, custeio ou bolsas, informando qual o valor total.</font>',False,True));
				array_push($cp,array('$}','','Vigência',False,True));
				
				array_push($cp,array('$O '.$ops,'ca_status','Status',True,True));


				array_push($cp,array('$T60:3','ca_contexto','Obs',False,True));				
				array_push($cp,array('$U8','ca_update','',True,True));
				
				//$sql = "ALTER TABLE ".$this->tabela." ADD COLUMN ca_update int ";
				//$rlt = db_query($sql);
				return($cp);
			}


		function captacao_status()
			{
				$sta = $this->status();
				$sql = "select count(*) as total, ca_status from ".$this->tabela." group by ca_status ";
				$rlt = db_query($sql);
				
				while ($line = db_read($rlt))
					{
						echo '<BR>'.$sta[$line['ca_status']].' = '.$line['total'];
					}
				return($sx);
			}
			
		function structure()
			{
				$sql = "alter table artigo drop column ar_a ";
				$rlt = db_query($sql);
				$sql = "alter table artigo add column ar_a char(2)";
				$rlt = db_query($sql);
				return(0);
				$sql = "alter table artigo add column ar_obs text";
				$rlt = db_query($sql);
				return(0);
				$sql = "alter table artigo alter column ar_Q char(2)";
				$rlt = db_query($sql);
				$sql = "alter table artigo add column ar_ER char(1)";
				$rlt = db_query($sql);
				return(1);

				$sql = "DROP TABLE artigo";
				$rlt = db_query($sql);
				
				$sql = "CREATE TABLE artigo
					(
					id_ar serial NOT NULL,
					ar_titulo text, 
					ar_protocolo char(7),
					ar_professor char ( 8 ),
					ar_issn char (9),
					ar_ano char ( 4 ), 
					ar_vol char ( 4 ),
					ar_num char ( 4 ),
					ar_pags char ( 12 ),
					ar_journal char ( 80 ),
					ar_status integer,
					ar_data integer,
					ar_hora integer,
					ar_update integer
				)";
				$rlt = db_query($sql);
			}
		function updatex()
			{
				global $base;
				$c = 'ar';
				$c1 = 'id_'.$c;
				$c2 = $c.'_protocolo';
				$c3 = 7;
				$sql = "update ".$this->tabela." set $c2 = lpad($c1,$c3,0) where $c2='' ";
				if ($base=='pgsql') { $sql = "update ".$this->tabela." set $c2 = trim(to_char(id_".$c.",'".strzero(0,$c3)."')) where $c2='' "; }
				$sql = "update ".$this->tabela." set $c2 = trim(to_char(id_".$c.",'".strzero(0,$c3)."')) "; 
				$rlt = db_query($sql);
				return(0);
			}			
	}
?>