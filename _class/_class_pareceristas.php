<?php
class parecerista
	{
		var $id;
		var $nome;
		var $codigo;
		var $instituicao;
		var $instituicao_cod;
		var $email;
		var $email_alt;
		var $status;
		
		/* relatorio */
		var $lista_avaliacoes;
		
		var $tabela = "pareceristas";
		var $tabela_instituicao = "instituicao";
		
		function parecerista_ultimos_aceites()
			{
				global $jid;
				$sql = "select * from ".$this->tabela."
					left join ".$this->tabela_instituicao." on us_instituicao = inst_codigo 
					where us_journal_id = '".$jid."'
					and (us_aceito = 10 or us_aceito = 0)
					and us_ativo > 0		
					order by us_aceito_resp desc	
					";
				$rlt = db_query($sql);
				$sx = '<table class="tabela00" width="100%">';
				$sx .= '<TR><TH>Parecerista<TH>Instituição<TH>Data Aceite<TH>Situação';
				while ($line = db_read($rlt))
					{
						$sx .= '<TR>';
						$sx .= '<TD class="tabela01">';
						$sx .= trim($line['us_nome']);
						
						$sx .= '<TD class="tabela01">';
						$sx .= trim($line['inst_abreviatura']);
						
						$sx .= '<TD class="tabela01" align="center">';
						$sx .= stodbr($line['us_aceito_resp']);
						
						$sx .= '<TD class="tabela01" align="center">';
						$sta = $line['us_aceito'];
						switch ($sta) {
							case '0': 	$sx .= '<font color="red">Não aceito</font>';
										break;
							case '10': 	$sx .= '<font color="green">Aceito</font>';
										break;
							default:
										$sx .= '???? - '.$sta;								
										break;
						}
						$sx .= '<TD class="tabela01">';
						$sx .= trim($line['us_aceito']);
				}
				$sx .= '</table>';
				return($sx);
			}
		
		function alterar_status_avaliador($id,$para)
			{
				$sql = "update ".$this->tabela." set us_aceito = '".$para."',
							us_aceito_resp = ".date("Ymd")." 				 
							where id_us = '".$id."' ";
				$rlt = db_query($sql);
			}
		
		function alterar_status_de_para($de,$para)
			{
				global $jid;
				$sql = "update  ".$this->tabela."
								set us_aceito = ".$para." 
								where us_aceito = ".$de."
								and us_journal_id = '".$jid."'";
				$rlt = db_query($sql);
			}
		
		function row()
			{
				global $cdf,$cdm,$masc,$tabela;
				$tabela = "(select * from pareceristas inner join ".$this->tabela_instituicao." on us_instituicao = inst_codigo ) as tabela ";
				$cdf = array('id_us','us_nome','inst_abreviatura','us_codigo','us_codigo_id','us_instituicao');
				$cdm = array('cod',msg('nome'),msg('instituicao'),msg('codigo'),msg('codigo'),'Instituicao');
				$masc = array('','','','','','','');
				return(1);				
			}
			
		function area_do_conhecomento_professor($cracha)
			{
				global $dd, $acao;
				
				if ((strlen($acao) >0) and (strlen($dd[1]) > 0))
					{
						$sql = "delete from pareceristas_area where id_pa = ".round($dd[1]);
						$rlt = db_query($sql);
					}
				
				$sql = "select * from (				
						select pp_cracha, pp_nome, pp_update, pp_avaliador, pp_curso from pibic_bolsa_contempladas 
						inner join pibic_professor on pb_professor = pp_cracha
						where pp_cracha = '$cracha' 
						group by pp_cracha, pp_nome, pp_update, pp_curso, pp_avaliador	
						) as tabela
						
				";
				$sql .= " left join pareceristas_area on pa_parecerista = pp_cracha ";
				$sql .= " left join ajax_areadoconhecimento on pa_area = a_codigo ";
				$sql .= " order by pp_nome, a_cnpq ";
								
				$rlt = db_query($sql);
				$sx = '<table width="100%" border=0>';
				$sx .= '<H2>Áreas cadastras para avaliação</h2>';
				$sx .= '<TR><TH><TH align="left" width="10%">Área<TH>Ação<TH align="left">Descrição';
				$id = 0;
				$xpp = ''; 
				while ($line = db_read($rlt))
					{
					$pp = $line['pp_cracha'];
					if ($line['a_semic']==1)
						{
						$id++;
						$sx .= '<TR>';
						$sx .= '<TD>';
						$sx .= '<A name="areas">';
						$sx .= '<form method="get" action="'.page().'#areas">';
						$sx .= '<input type="hidden" name="dd0" value="'.$dd[0].'">';
						$sx .= '<input type="hidden" name="dd1" value="'.$line['id_pa'].'">';
						$sx .= '<TD colspan=1 align="center" class="tabela01">'.$line['a_cnpq'];
						$sx .= '<TD class="tabela01" align="left" width="5%">';
						$sx .= '<input type="submit" name="acao" value="desativar" class="botao-geral">';
						$sx .= '<TD class="tabela01">&nbsp;'.$line['a_descricao'];
						$sx .= '<TD></form>';
						}
					}
				$sx .= '<TR><TD colspan=5>Total '.$id;
				$sx .= '</table>';
				return($sx);
			}
		
		function lista_professores_ic_enviar_email()
			{
				global $dd;
				$sql = "select * from (				
						select pp_cracha, pp_nome, pp_update, pp_curso, pp_email, pp_email_1 from pibic_bolsa_contempladas 
						inner join pibic_professor on pb_professor = pp_cracha ";
												
				$sql .= " where pp_titulacao = '002' and pp_update = '".date("Y")."' 
						group by pp_cracha, pp_nome, pp_update, pp_curso, pp_email, pp_email_1
						) as tabela
						
				";
				$sql .= " left join pareceristas_area on pa_parecerista = pp_cracha ";
				$sql .= " left join ajax_areadoconhecimento on pa_area = a_codigo ";
				$sql .= " order by pp_nome, a_cnpq  ";
								
				$rlt = db_query($sql);
				$xpp = ''; 
				$area = '';
				while ($line = db_read($rlt))
					{
						$pp = $line['pp_cracha'];
						if ($pp != $xpp)
						{
							if (strlen($area) > 0)
								{
									$txt = troca($dd[1],'$NOME',trim($nome));
									$txt = troca($txt,'$AREA','<B>'.$area.'</B>');
									$txt = mst($txt);
									echo '<BR>>eviando e-mail para '.$nome;
									$title = 'Avaliador Iniciação Científica - PIBIC/PIBITI';
									enviaremail('pibicpr@pucpr.br','',$title.' (copia)',$txt);
									if (strlen($email1) > 0) { enviaremail($email1,'',$title,$txt); echo $email1; }
									if (strlen($email2) > 0) { enviaremail($email2,'',$title,$txt); echo ', '.$email2; }
									$area = '';
								}
							
							$nome = trim($line['pp_nome']);
							$email1 = trim($line['pp_email']);
							$email2 = trim($line['pp_email_1']);
							
							$xpp = $pp;
						}
						
						$area .= '<BR>'.trim($line['a_cnpq']).' - '.$line['a_descricao'];
					}
				if (strlen($area) > 0)
						{
							$txt = troca($dd[1],'$NOME',trim($nome));
							$txt = troca($txt,'$AREA','<B>'.$area.'</B>');
							$txt = mst($txt);
							
							echo '<BR>>eviando e-mail para '.$nome;
							$title = 'Avaliador Iniciação Científica - PIBIC/PIBITI';
							enviaremail('pibicpr@pucpr.br','',$title.' (copia)',$txt);
							enviaremail('monitoramento@sisdoc.com.br','',$title.' (fim)',$txt);
							
							if (strlen($email1) > 0) { enviaremail($email1,'',$title,$txt); echo $email1; }
							if (strlen($email2) > 0) { enviaremail($email2,'',$title,$txt); echo ', '.$email2;}
							$area = '';
						}					
				return($sx);	
			}

		function lista_professores_ic()
			{
				/*				
				$sql = "update pibic_professor set pp_avaliador = 0";
				$rlt = db_query($sql);
				$sql = "update pibic_professor set pp_avaliador = 1 
						where pp_titulacao = '002' and pp_update = '".date("Y")."' ";
				$rlt = db_query($sql);
				 */
						
				$pb = new pibic_bolsa_contempladas;
				$sql = "select * from (				
						select total, avaliador, pp_cracha, pp_nome, pp_update, pp_avaliador, pp_curso from pibic_bolsa_contempladas 
						inner join pibic_professor on pb_professor = pp_cracha ";
				$sql .= " left join (
								select count(*) as total, pp_avaliador as avaliador 
									from pibic_parecer_".date("Y")." 
									where pp_tipo = 'SUBMI' and substr(pp_protocolo,1,1) = '1'
									group by avaliador
								) as tabela02 on avaliador = pp_cracha ";	
				$sql .= "					
						where pp_titulacao = '002' and pp_update = '".date("Y")."' 
						group by total, pp_cracha, pp_nome, pp_update, pp_curso, pibic_professor.pp_avaliador, avaliador	
						) as tabela
						
				";
				$sql .= " left join pareceristas_area on pa_parecerista = pp_cracha ";
				$sql .= " left join ajax_areadoconhecimento on pa_area = a_codigo ";
				$sql .= " order by pp_nome, a_cnpq ";
								
				$rlt = db_query($sql);
				$sx = '<table width="100%">';
				$sx .= '<H2>Professores Doutores da PUCPR/IC</h2>';
				$sx .= '<TR><TH>Cracha<TH>Nome<TH>Curso<TH>Atualizado<TH>Avaliador';
				$id = 0;
				$xpp = ''; 
				while ($line = db_read($rlt))
					{
						$pp = $line['pp_cracha'];
						if ($pp != $xpp)
						{
							$id++;
							$av = $line['pp_avaliador'];
							if ($av==1) { $av = '<font color="green">'.msg('ativo').'</font>'; }
							else { $av = '<font color="red">'.msg('inativo').'</font>'; }
							$link = '<A HREF="avaliador_professor_detalhe.php?dd0='.$line['pp_cracha'].'" class="link">';
							$sx .= '<TR>';
							$sx .= '<TD class="tabela01" align="center">';
							$sx .= $link;
							$sx .= $line['pp_cracha'];
							$sx .= '</A>';
							$sx .= '<TD class="tabela01">';
							$sx .= $line['pp_nome'];
							$sx .= '<TD class="tabela01">';
							$sx .= $line['pp_curso'];
							$sx .= '<TD class="tabela01" align="center">';
							$sx .= $line['pp_update'];
							$sx .= '<TD class="tabela01" align="center">';
							$sx .= $av;
							$xpp = $pp;
							$sx .= '<TD class="tabela01" align="center">';
							$sx .= $line['total'];
						}
					if ($line['a_semic']==1)
						{
						$sx .= '<TR><TD><TD colspan=2>'.$line['a_cnpq'].' - '.$line['a_descricao'];
						}
					}
				$sx .= '<TR><TD colspan=5>Total '.$id;
				$sx .= '</table>';
				return($sx);
			}
		function resumo_avaliadore_externos()
			{
				global $jid;
				$sta = $this->status();
				
				$sql = "delete from ".$this->tabela." where us_aceito = -1 ";
				//$rlt = db_query($sql);
				
				$sql = "select count(*) as total, us_aceito, us_ativo from (
						select * from ".$this->tabela." 
						left join instituicoes on inst_codigo = us_instituicao
						where us_journal_id = ".round($jid)."
						and us_ativo = 1
						and char_length(trim(us_codigo))=7
						) as tabela 
						group by us_aceito, us_ativo
						"; 	
				$rlt = db_query($sql);
				$t = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
				$tot = 0;	
				while ($line = db_read($rlt))
					{
						$id = round($line['us_aceito']);
						$t[$id] = $t[$id] + $line['total'];
						$tot = $tot + $line['total'];				
					}	
				$sx = '<table width="100%" class="tabela01">';
				$sa = '<TR>'; $sb = '<TR>'; $i = 0;
				for ($r=0;$r < count($t);$r++)
					{
						if ($t[$r] > 0)
							{
							$i++; 
							$link = '<A HREF="pareceristas_lista.php?">';
							$sa .= '<TD width="xx%" align="center">'.$sta[$r];
							$sb .= '<TD class="lt5" align="center">'.$t[$r];
							}
					}
				if ($i > 0)
					{
					$w = round(100/$i);
					$sa = troca($sa,'xx',$w);
					}
				$sx .= $sa.$sb;
				$sx .= '<TR><TD class="lt0" colspan=6>Total de '.$tot.' avaliadores ';
				$sx .= '</table>';
				return($sx);
			}
		function botao_editar()
			{
				$sx = '<form action="pareceristas_editar.php">';
				$sx .= '<input type="hidden" name="dd0" value="'.$this->id.'">';
				$sx .= '<input type="submit" value="editar" class="botao-editar">';
				$sx .= '</form>';
				return($sx);
			}
		function lista_avaliadore_externos_ic()
			{
				global $jid;
				/*				
				$sql = "update pibic_professor set pp_avaliador = 0";
				$rlt = db_query($sql);
				$sql = "update pibic_professor set pp_avaliador = 1 
						where pp_titulacao = '002' and pp_update = '".date("Y")."' ";
				$rlt = db_query($sql);
				 */
						
				$sql = "select * from (
						select * from ".$this->tabela." 
						left join ".$this->tabela_instituicao." on inst_codigo = us_instituicao
						where us_journal_id = ".round($jid)."
						and us_ativo = 1
						) as tabela "; 
						
				$sql .= " left join pareceristas_area on pa_parecerista = us_codigo ";
				$sql .= " left join ajax_areadoconhecimento on pa_area = a_codigo ";
				$sql .= " left join (
								select count(*) as total, pp_avaliador 
									from pibic_parecer_".date("Y")." 
									where pp_tipo = 'SUBMI' and substr(pp_protocolo,1,1) = '1'
									group by pp_avaliador
								) as tabela02 on pp_avaliador = us_codigo ";
				$sql .= " order by us_nome, a_cnpq ";						
				$rlt = db_query($sql);
				
				$sx = '<table width="100%">';
				$sx .= '<H2>Avaliadores Externos/IC</h2>';
				$sx .= '<TR><TH>Cracha<TH>Títul.<TH>Nome<TH>Instituição<TH>Status<TH>Convite<TH>Atualizado<TH>Aceito<TH>Indicado';
				$id = 0;
				
				$xpp = ''; 
				$status = $this->status();
				
				
				while ($line = db_read($rlt))
					{
						$cor = '';
						$pp = trim($line['us_codigo']);
						if (($pp != $xpp) and (strlen($pp) == 7))
						{
							$acc = $line['us_aceito'];
							switch ($acc)
								{
									case '0':
										$cor = ' bgcolor="#FFC0C0" ';
										break;
									case '2':
										$cor = ' bgcolor="#FFFFc0" ';
										break;																			
									case '10':
										$cor = ' bgcolor="#C0FFC0" ';
										break;
										
									case '19':
										$cor = ' bgcolor="#FFFFc0" ';
										break;
									
									default:
										$cor = ' bgcolor="#C0C0C0" ';
										break;
								}
							
							$id++;
							$av = $line['us_ativo'];
							$email = trim($line['us_email']);
							if (strlen($email)==0) { $email = '<font color="red">==SEM EMAIL==</font>'; }
							if ($av==1) { $av = '<font color="green">'.msg('ativo').'</font>'; }
							else { $av = '<font color="red">'.msg('inativo').'</font>'; }
							$link = '<A HREF="parecerista_detalhes.php?dd0='.$line['us_codigo'].'" class="link">';
							$sx .= '<TR '.$cor.'>';
							$sx .= '<TD class="tabela01" align="center">';
							$sx .= $link;
							$sx .= $line['us_codigo'];
							$sx .= '</A>';
							$sx .= '<TD class="tabela01">';
							$sx .= $line['us_titulacao'].' ';
							$sx .= '<TD class="tabela01">';
							$sx .= $line['us_nome'];
							$sx .= '<BR>'.$email;
							$sx .= '('.$line['us_aceito'].')-('.$line['us_ativo'].')';
							$sx .= '<TD class="tabela01">';
							$sx .= $line['inst_nome'];
							$sx .= '<TD class="tabela01" align="center">';
							$sx .= $av;
							$xpp = $pp;
							$sx .= '<TD class="tabela01">'.$line['us_bolsista'];
							$sx .= '<TD class="tabela01">'.$status[$line['us_aceito']].' ('.$line['us_aceito'].')';
							$sx .= '<TD class="tabela01">'.stodbr($line['us_aceito_resp']);
							$sx .= '<TD class="tabela01" align="center">'.$line['total'];
							
						}
					if ($line['a_semic']==1)
						{
						$sx .= '<TR><TD><TD colspan=2>'.$line['a_cnpq'].' - '.$line['a_descricao'];
						}
					}
				$sx .= '<TR><TD colspan=5>Total '.$id;
				$sx .= '</table>';
				return($sx);
			}		
		function cp()
			{
				global $jid;
				$cp = array();
				array_push($cp,array('$H4','id_us','id_us',False,False,''));
				array_push($cp,array('$A4','','Dados pessoais',False,True,''));
				array_push($cp,array('$H8','us_cracha','Usuário do núcleo',False,True,''));
				array_push($cp,array('$S120','us_nome','Nome completo',True,True,''));
				array_push($cp,array('$Q ap_tit_titulo:ap_tit_titulo:select * from apoio_titulacao order by ap_tit_titulo','us_titulacao','Titulacao',True,True,''));
				array_push($cp,array('$Q inst_nome:inst_codigo:select * from '.$this->tabela_instituicao.' order by inst_ordem, inst_nome','us_instituicao','Instituição',True,True,''));
				array_push($cp,array('$S100','us_lattes','Lattes (link) <img src="img/icone_lattes.gif" width="20" height="20" alt="" border="0">',False,True,''));
				array_push($cp,array('$HV','us_niver','19000101',False,True,''));
				array_push($cp,array('$H4','','Filiação',False,True,''));
				array_push($cp,array('$H100','us_nome_pai','Nome pai',False,True,''));
				array_push($cp,array('$H100','us_nome_mae','Nome mae',False,True,''));
				array_push($cp,array('$A4','','Dados para o sistema',False,True,''));
				array_push($cp,array('$H8','us_login','Login',False,True,''));
				array_push($cp,array('$H8','us_senha','senha',False,True,''));
				array_push($cp,array('$O -1:Excluído&10:SIM, Convite Novo Aceito&1:Limbo de aceite&0:NÃO&9:Enviar convite&2:Aguardando aceite do convite&3:inativo temporariamente&11:SIM, aceito convite via e-mail CNPq','us_aceito','Aceito com parecerista',False,True,''));
				array_push($cp,array('$U8','us_lastupdate','us_lastupdate',False,True,''));
				array_push($cp,array('$S100','us_lembrete','Lembrete da senha',False,True,''));
				array_push($cp,array('$H4','','Documentos pessoais',False,True,''));
				array_push($cp,array('$H20','us_cpf','CPF',False,True,''));
				array_push($cp,array('$H20','us_rg','RG',False,True,''));
				array_push($cp,array('$A4','','Formas de contato para contato',False,True,''));
				array_push($cp,array('$T60:5','us_endereco','Endereço',False,True,''));
				array_push($cp,array('$S30','us_fone_1','Fone ',False,True,''));
				array_push($cp,array('$S30','us_fone_2','Fone (cel)',False,True,''));
				array_push($cp,array('$S30','us_fone_3','Fone (fax/rec)',False,True,''));
				array_push($cp,array('$S100','us_email','e-mail',True,True,''));
				array_push($cp,array('$S100','us_email_alternativo','e-mail (alternativo)',False,True,''));
				array_push($cp,array('$O 1:SIM&0:NÃO','us_email_ativo','Enviar e-mail',False,True,''));
				array_push($cp,array('$H4','','Dados trabalistas',False,True,''));
				array_push($cp,array('$O 1:SIM&0:NÃO','us_ativo','Ativo',False,True,''));
				array_push($cp,array('$U8','us_dt_admissao','Dt admissão',False,True,''));
				array_push($cp,array('$U8','us_dt_demissao','Dt demissão',False,True,''));
				array_push($cp,array('$H20','us_vt','Cartão de VT',False,True,''));
				array_push($cp,array('$H20','us_vr','Cartão de VR',False,True,''));
				array_push($cp,array('$O 1:Pareceristas','us_nivel','Tipo',False,True,''));
				array_push($cp,array('$O NÃO:NÃO&Nível 1A:Nível 1A&Nível 1B:Nível 1B&Nível 1C:Nível 1C&Nível 1D:Nível 1D&Nível 2:Nível 2&Nível ??:Nível ??','us_bolsista','Bolsista de produtividade',False,True,''));
				array_push($cp,array('$HV','us_journal_id',$jid,False,True,''));
				array_push($cp,array('$U8','us_aceito_resp',$jid,False,True,''));
				array_push($cp,array('$S8','us_codigo','Código',False,True,''));
				array_push($cp,array('$H8','us_codigo_id','',False,True,''));
				array_push($cp,array('$O : &1:SIM','us_cnpq','Avaliador CNPq',False,True,''));
			return($cp);
			}
			
		function parecerista_externo($ano)
			{
			$sta = $this->status();
			
				$tab = 'pibic_parecer_enviado_pibic_2010';
				$sql = "select * from (";
				$sql .= "select pp_avaliador, substr(a_cnpq,1,1) as area from ".$tab;
				$sql .= " inner join pareceristas_area on pp_avaliador = pa_parecerista ";
				$sql .= " inner join ajax_areadoconhecimento on pa_area = a_codigo ";
				$sql .= " where pp_status <> 'D' group by pp_avaliador, area ";
				$sql .= ") as tabela ";
				$sql .= " inner join ".$this->tabela." on pp_avaliador = us_codigo ";
				$sql .= " inner join ".$this->tabela_instituicao." on us_instituicao = inst_codigo ";
				$sql .= " order by area,us_nome ";
				
			$ar = array('1'=>'Ciências Exatas e da Terra',
						'2'=>'Ciências Biológicas',
						'3'=>'Engenharias',
						'4'=>'Ciências da Saúde',
						'5'=>'Ciências Agrárias',
						'6'=>'Ciências Sociais Aplicadas',
						'7'=>'Ciências Humanas',
						'8'=>'Lingüística, Letras e Artes',
						'9'=>'Outros');				
				$prod = array('NÃO','Nível 1A','Nível 1B','Nível 1C','Nível 1D','Nível 2');
				$prodt = array(0,0,0,0,0);
				
				$rlt = db_query($sql);
				$sx = '<table width="98%" class="lt1">';
				$sx .= '<TR><TH>Nome<TH>Instituição<TH>Área do conhecimento<TH>Nível bolsa PQ do CNPq';
				$tot = 0;
				while ($line = db_read($rlt))
					{
						$inst = trim($line['inst_abreviatura']);
						if (!(substr($inst,0,5) == 'PUCPR'))
							{
							$bolsa = trim($line['us_bolsista']);
							for ($r=0;$r < count($prod);$r++)
								{ if ($bolsa == $prod[$r]) { $prodt[$r] = $prodt[$r]+1; }}
							 
							$tot++;
							$sx .= '<TR '.coluna().'>';
							$sx .= '<TD>'.trim($line['us_nome']);
							$sx .= '<TD>'.trim($line['inst_abreviatura']);
							$sx .= '<TD>'.$ar[trim($line['area'])];
							$sx .= '<TD>'.trim($line['us_bolsista']);
							$sx .= '<TD>'.trim($line['us_aceito']).' '.$sta[$line['us_aceito']];
							}
					}
				$sx .= '<TR><TD colspan=3>Total de avaliadores '.$tot;
				$sx .= '</table>';
				
				$sa = '<table width=500 border=1 class="lt2">';
				$sa .= '<TR><TH>Não<TH>Prod. 1A<TH>Prod. 1B<TH>Prod. 1C<TH>Prod. 1D<TH>Prod. 2';
				$sa .= '<TR align="center">';
				$sa .= '<TD>'.$prodt[0];
				$sa .= '<TD>'.$prodt[1];
				$sa .= '<TD>'.$prodt[2];
				$sa .= '<TD>'.$prodt[3];
				$sa .= '<TD>'.$prodt[4];
				$sa .= '<TD>'.$prodt[5];
				$sa .= '</table>';
				$sx = $sa.$sx;
				print_r($prodt);
				return($sx);
			}
		function parecerista_lista($tipo,$tabela,$area,$pareceres,$ptipo='',$prof='')
			{
				$ara = array();
				$pos = strpos($area,'.00');
				$area = substr($area,0,7);
				if ($tipo == 'E')
				{
					if ($pos > 0) { $area = substr($area,0,$pos); }

					$sql = "select * from pareceristas_area ";
					$sql .= " inner join ajax_areadoconhecimento on pa_area = a_codigo ";
					$sql .= " inner join pareceristas on pa_parecerista = us_codigo ";
					$sql .= " left join ( select count(*) as total, pp_avaliador from ".$pareceres." where pp_protocolo_mae = '' and pp_tipo = '$ptipo' and pp_status <> 'X' group by pp_avaliador ) as tabela on us_codigo = pp_avaliador ";					
					$sql .= " left join ".$this->tabela_instituicao." on us_instituicao = inst_codigo ";
					$sql .= " where a_cnpq like '".substr($area,0,7)."%' ";
					$sql .= " and pa_parecerista <> '".trim($prof)."' ";
					$sql .= " and us_journal_id = 20 and us_aceito = 10 and us_ativo = 1";
					$sql .= " order by a_cnpq, us_nome ";
					$rlt = db_query($sql);
					
					while ($line = db_read($rlt))
						{
							$nome = trim($line['us_nome']);
							//$nome .= '('.trim($line['us_aceito']).')';
							$ins = trim($line['inst_abreviatura']);
							if (!(strpos('   '.$ins,'PUCPR') > 0))
								{
								if (round($line['total']) > 0) 
									{ $nome .= '<B>('.$line['total'].')</B>'; }
								else
									{ $nome .= '--'; }
								if (strlen(trim($line['inst_abreviatura'])) > 0)
									{ $nome .= ' ('.trim($line['inst_abreviatura']).')'; }
									
								array_push($ara,array($line['us_codigo'],$nome,$line['a_cnpq'],$line['a_descricao']));
								}
						}
				}
				
				/* Local */
				if ($tipo == 'L')
					{
										
						$sql = "select * from pareceristas_area ";
						$sql .= " inner join ajax_areadoconhecimento on pa_area = a_codigo ";
						$sql .= " left join pibic_professor on pa_parecerista = pp_cracha ";
						$sql .= " left join ( select count(*) as total, pp_avaliador as pp_ava from ".$pareceres." where pp_protocolo_mae = '' and pp_tipo = '$ptipo' and pp_status <> 'X' group by pp_avaliador ) as tabela on pp_ava = pp_cracha ";						
						//$sql .= " inner join pareceristas on pp_cracha = us_codigo ";
						
						$sql .= " where (a_cnpq like '".substr($area,0,4)."%' ";
						$sql .= " and pp_ativo = 1 ";
						$sql .= " and pp_avaliador = 1  ";
						//$sql .= " and us_journal_id = 20 ";
						$sql .= " and pp_cracha <> '".$prof."' "; 
						$sql .= ") ";
						
						$sql .= " order by a_cnpq, pp_nome ";
						
						$rlt = db_query($sql);
						$cod = "X";
						while ($line = db_read($rlt))
						{
							$gestor = round($line['pp_comite']);
							$cracha = trim($line[pp_cracha]);
							
							if (($gestor == 0) and ($cracha != $cod))
							{
							$nome = trim($line['pp_nome']);
							if ($line['pp_comite'] > 0) { $nome .= '(Local)'; }
							$nome .= '['.$line['pp_comite'].']';
							if (round($line['total']) > 0) { $nome .= '<B>('.$line['total'].')</B>'; }
							array_push($ara,array($line['pp_cracha'],$nome,$line['a_cnpq'],$line['a_descricao']));
							$cod = $cracha;
							}
						}
					}				
				/* GESTOR */
				if ($tipo == 'G')
					{
						$sql = "select * from docentes 	";
						$sql .= " inner join pareceristas_area on pa_parecerista = pp_cracha ";
						$sql .= " inner join ajax_areadoconhecimento on pa_area = a_codigo ";
						$sql .= " left join ( select count(*) as total, pp_avaliador as pp_ava from ".$pareceres." where pp_protocolo_mae = '' and pp_tipo = '$ptipo' and pp_status <> 'X' group by pp_avaliador ) as tabela on pp_ava = pp_cracha ";						
						$sql .= " where pp_avaliador = 1 and pp_comite >= 1 ";
						$sql .= " and a_cnpq like '".substr($area,0,3)."%' ";
						$sql .= " and pp_ativo=1 ";
						$sql .= " and pp_cracha <> '".$prof."' ";
						//$sql .= " and (pp_aceito=1 or pp_aceito = 10) ";
						$sql .= " order by a_cnpq, pp_nome ";
										
						$rlt = db_query($sql);
						$cod = "X";
						while ($line = db_read($rlt))
						{
							$gestor = round($line['pp_comite']);
							$cracha = trim($line[pp_cracha]);
							if (($gestor > 0) and ($cracha != $cod))
							{
							$nome = trim($line['pp_nome']);
							if ($gestor == 2 ) { $nome .= ' (G)';}
							if ($gestor == 1 ) { $nome .= ' (L)';}
							if (round($line['total']) > 0) { $nome .= '<B>('.$line['total'].')</B>'; }
							array_push($ara,array($line['pp_cracha'],$nome,$line['a_cnpq'],$line['a_descricao']));
							$cod = $cracha;
							}
						}

					}
				return($ara);
			}
		function parecerista_sem_area()
			{
				$sta = $this->status();
				$sql = "select * from pareceristas ";
				$sql .= "left join ".$this->tabela_instituicao." on us_instituicao = inst_codigo ";
				$sql .= " left join (select count(*) as total, pa_parecerista from pareceristas_area group by pa_parecerista ) as tabela on pa_parecerista = us_codigo ";
				$sql .= " where us_journal_id = 20 ";
				$sql .= " and us_ativo = 1 ";
				$sql .= " order by us_nome ";
				$rlt = db_query($sql);
				$sx .= '<table width="100%" class="lt1">';
				$sx .= '<TR><TH>Código<TH>Nome<TH>Instituição<TH>Status<TH>Áreas';
				$tot = 0;
				while ($line = db_read($rlt))
				{
					if (round($line['total']) == 0)
					{
					$tot++;
					$sx .= '<TR>';
					$sx .= '<TD>';
					$sx .= $line['us_codigo'];
					$sx .= '<TD>';
					$sx .= $line['us_nome'];
					$sx .= '<TD>';
					$sx .= $line['inst_nome'];
					$sx .= '<TD>';
					$sx .= $sta[$line['us_aceito']];
					$sx .= '<TD>';
					$sx .= round($line['total']);
					}
				}
				$sx .= '<TR><TD colspan=2>Total '.$tot;
				$sx .= '</table>';
				return($sx);
			}
		function pareceristas_lista($tipo)
			{
				global $jid;
				$sql = "select * from pareceristas  ";
				$sql .= " left join ".$this->tabela_instituicao." on us_instituicao = inst_codigo ";
				$sql .= " where us_journal_id = '".$jid."' ";
				$sql .= " and us_ativo = 1 ";
				$sql .= " and us_aceito = '".round($tipo)."' 
							order by us_nome
				";
				//$rlt = db_query($sql);
														
				$rlt = db_query($sql);
				$sx = '<table class="tabela00" width="100%">';
				$sx .= '<TR><TH>Nome<TH>Instituição<TH>Código';
				$id = 0;
				while ($line = db_read($rlt))
					{
						if (strlen(trim($line['us_codigo']))!=8)
						{
						$cor = '<font color="black">';
						if ((strlen(trim($line['us_codigo']))==7) and (trim($line['inst_abreviatura'])=='PUCPR'))
							{ $cor = '<font color="red">'; }
							$id++;
							$ln = $line;
							$sx .= '<TR>';
							$sx .= '<TD class="tabela01">';
							$sx .= $cor;
							$sx .= trim($line['us_nome']);
							$sx .= '</font>';
							$sx .= '<TD class="tabela01">';
							$sx .= trim($line['inst_abreviatura']);
							$sx .= '<TD class="tabela01">';
							$sx .= trim($line['us_codigo']);
							//$sql = "update pareceristas set us_aceito = 9 where id_us = ".$line['id_us'];
							//$qqq = db_query($sql);
						} else {
							//$sql = "update pareceristas set us_aceito = 10 where id_us = ".$line['id_us'];
							//$qqq = db_query($sql);
						}
					}
				$sx .= '<TR><TD colspan=10><B>Total de '.$id.' registros';
				$sx .= '</table>';
				return($sx);
				
			}

		function enviar_convite($tipo,$titulo,$texto_original)
			{
			global $jid;
			
			$sql = "select * from pareceristas  ";
			$sql .= "inner join instituicoes on us_instituicao = inst_codigo ";
			$sql .= " and us_journal_id = '".intval($jid)."' ";
			$sql .= " where us_aceito = 9 ";
			$sql .= " and us_ativo = 1 ";
			$rlt = db_query($sql);
			
			while ($line = db_read($rlt))
				{
				$nome = trim($line['us_titulacao'].' '.$line['us_nome']);
				$instituicao = trim($line['inst_nome']).' ('.trim($line['inst_abreviatura']).')';
				$email_1 = trim($line['us_email']);
				$email_2 = trim($line['us_email_alternativo']);

				$isql = "select * from pareceristas_area ";
				$isql .= "inner join pareceristas on us_codigo = pa_parecerista ";
				$isql .= "inner join ajax_areadoconhecimento on a_codigo = pa_area ";
				$isql .= " where id_us = ".$line['id_us'];
				$isql .= " and us_ativo = 1 ";
				$irlt = db_query($isql);
				$area = '';
				while ($iline = db_read($irlt))
					{
					$area .= $iline['a_cnpq'];
					$area .= '<B>';
					$area .= $iline['a_descricao'];
					$area .= '</B><BR>'.chr(13).chr(10);
					}
//				$link = '<A HREF="http://www2.pucpr.br/reol/pibicpr/parecerista_resposta.php?dd0='.$line['id_us'];
//				$link .= '&dd1='.substr(md5($line['id_us'].$secu),0,8);
//				$link .= '" target="new"><font color=blue>Link para responder este e-mail</A>'.chr(13).chr(10).'<BR>';
//				$link .= 'http://www2.pucpr.br/reol/pibicpr/parecerista_resposta.php?dd0='.$line['id_us'];
//				$link .= '&dd1='.substr(md5($line['id_us'].$secu),0,8);
//				$link .= '</font></A>';
				
				$http = 'http://'.$_SERVER['HTTP_HOST'];
				$chk = substr(md5('pibic'.date("Y").$line['us_codigo']),0,10);
				$link = $http.'/reol/avaliador/acesso.php?dd0='.$line['us_codigo'].'&dd1=1&dd90='.$chk;
				$link = '<A HREF="'.$link.'" target="new">'.$link."</A>";
				
				
				$texto = mst(troca($texto_original,'$nome',$nome));
				$texto = troca($texto,'$instituicao',$instituicao);
				$texto = troca($texto,'$area',''.$area);
				$texto = troca($texto,'$http',$link);
				
				if ($tipo == '9')
					{
					echo '<BR>Enviado para '.$email_1.' '.$email_2;
					if (strlen($email_1) > 0) { enviaremail($email_1,'',$titulo.' - '.$nome,$texto); }
					if (strlen($email_2) > 0) { enviaremail($email_2,'',$titulo.' - '.$nome,$texto); }
					enviaremail('pibicpr@pucpr.br','',$titulo,$texto);
					$sql = "update pareceristas set us_aceito = 19 where us_aceito = 9 and id_us = ".$line['id_us'];
					$xrlt = db_query($sql);
					}								
				//require("parecerista_enviar_email.php");
				}
				echo 'FIM';		
				return(1);
			}
		function enviar_convite_total()
			{
				global $jid;
				$sql = "select count(*) as total, us_aceito from pareceristas  ";
	//			$sql .= "inner join instituicoes on us_instituicao = inst_codigo ";
				$sql .= " where us_ativo <> 0 ";
				$sql .= " and us_journal_id = '".$jid."' 
							and us_aceito = 9 ";
				$sql .= " group by us_aceito";
				$rlt = db_query($sql);
				$line = db_read($rlt);
				return($line['total']);
				
			}
		function parecerista_resumo()
			{
				global $jid;
				
				$sql = "update pareceristas set us_instituicao = '0000012' where us_instituicao = '0000232'";
				$sql .= " and us_journal_id = '".$jid."' ";
//				echo $sql;
				//exit;
//				$rlt = db_query($sql);

				$st = $this->status();
				$sql = "select count(*) as total, us_aceito from pareceristas  ";
				$sql .= " left join ".$this->tabela_instituicao." on us_instituicao = inst_codigo ";
				$sql .= " where us_journal_id = '".$jid."' ";
				$sql .= " and us_ativo = 1 ";
				$sql .= " group by us_aceito";
				
				$sql = "select count(*) as total, us_aceito from pareceristas ";
				$sql .= " where us_journal_id = '".$jid."' ";
				$sql .= " and us_ativo = 1 ";
				$sql .= " group by us_aceito";				
				$rlt = db_query($sql);
				
				$sx = '<table width=500 border=1 cellpadding=4 cellspacing=0 class="tabela30">';
				$sx .= '<TR>';
				$sx .= '<TH>Quant.<TH>Descrição';
				while ($line = db_read($rlt))
					{
						$link = '<A HREF="paraceristas_rel_detalhe.php?dd0='.$line['us_aceito'].'">';
						$sx .= '<TR '.coluna().'>';
						$sx .= '<TD align="center">';
						$sx .= $link.$line['total'].'</A>';
						$sx .= '<TD>';
						$sx .= $st[$line['us_aceito']];
					}
				$sx .= '</table>';
				return($sx);		
			}
			
		function parecerista_acao()
			{
				global $dd,$acao;
				$sta = $this->status;
				$action = array();
				$cmd = array();
				if ($sta == 1)
					{
						array_push($action,'Enviar convite de autalização');
						array_push($cmd,'001'); 
					}
				if ($sta == 9)
					{
						array_push($action,'Enviar convite de autalização');
						array_push($cmd,'001'); 
					}					
				if ($sta == 19)
					{
						array_push($action,'Reenviar convite de autalização');
						array_push($cmd,'001'); 
					}					

				if (strlen($acao) > 0)
					{
						$ccc='';
						for ($r=0;$r < count($action);$r++)
							{
								if ($action[$r] == $acao) { $ccc = $cmd[$r]; }
							}
						if ($ccc = '001') { $this->enviar_email_atualizacao_dados(); }
					}
				if ((count($action) > 0) and (strlen($acao) == 0))
					{
						$sx .= '<form action="'.page().'" method="get"">';
						$sx .= '<input type="hidden" name="dd0" value="'.$dd[0].'">';
						$sx .= '<input type="hidden" name="dd90" value="'.$dd[90].'">';
						$sx .= '<fieldset><legend>Ações</legend>';
						$sx .= '<table width="100%">';
						$sx .= '<TR>';
						for ($r=0;$r < count($action);$r++)
							{
								$sx .= '<TD align="center">';
								$sx .= '<input type="submit" name="acao" value="'.$action[$r].'">';
							}
						$sx .= '</table>';
						$sx .= '</fieldset>';
						$sx .= '</form>';
					}
				return($sx);	
			}
		function aceitar_avalicao()
			{
				$sx = '<table width="100%">';
					$sx .= '<TR>';
					$sx .= '<TD>';
					$sx .= '<form method="post" action="'.page().'">';
					$sx .= '<input type="hidden" value="10" name="dd2">';
					$sx .= '<input type="hidden" value="AXA" name="dd3">';
					$sx .= '<input type="submit" 
									name="dd10" value="Aceitar convite de avaliador" 
									style="background-color:#A0FFA0;" class="botao-redondo">
							</form>';
							
					$sx .= '<TD>';
					$sx .= '<form method="post" action="'.page().'">';
					$sx .= '<input type="hidden" value="0" name="dd2">';
					$sx .= '<input type="hidden" value="AXA" name="dd3">';
					$sx .= '<input type="submit" 
									name="dd10" value="Recusar convite temporariamente" 
									style="background-color:#FFA0A0" class="botao-redondo">
							</form>';
				$sx .= '</table>';
				return($sx);
			}
		function ativar_acesso()
			{
				$sql= "update ".$this->tabela." set us_aceito = 10
						, us_lastupdate = ".date("Ymd")."  
						where us_codigo = '".$this->codigo."' and (us_aceito=19 or us_aceito=9) ";
						
				$rlt = db_query($sql);
				return(True);
			}
		
		function enviar_email_atualizacao_dados()
			{
				global $messa,$secu,$http;
				$http = 'http://'.$_SERVER['HTTP_HOST'];
				$chk = substr(md5('pibic'.date("Y").$this->codigo),0,10);
				$link = $http.'/reol/avaliador/acesso.php?dd0='.$this->codigo.'&dd1=1&dd90='.$chk;
				$link = '<A HREF="'.$link.'" target="new">'.$link."</A>";
				$body = msg('email_atualizacao');
				$body = troca($body,'$link',$link);
				$body = troca($body,'$nome',$this->nome);

				$sx .= '<FONT COLOR=Green>Solicitação de atualização enviado com sucesso!</FONT>';
				$sx .= '<BR><BR>Link de acesso: '.$link;
				$sx .= '<BR><BR>Enviado para ';
				echo $sx;
				
				if (strlen(trim($this->email)) > 0)
					{
						$email = trim($this->email);
						enviaremail($email,'','Atualização de dados',$body); 
						enviaremail('monitoramento@sisdoc.com.br','','Atualização de dados',$body);
						echo $email.' ';
					}

				if (strlen(trim($this->email_alt)) > 0)
					{
						$email = trim($this->email_alt);
						enviaremail($email,'','Atualização de dados',$body);
						echo $email.' '; 
					}
				$sql = "update ".$this->tabela." set us_aceito = 19, us_lastupdate =  ".date("Ymd");
				$sql .= " where id_us = ".$this->id;
				$rlt = db_query($sql);
				return($sx);
				// www2.pucpr.br/reol/editora/../avaliador/acesso.php?dd0=0000881&dd90=71891e23af 
			}
		
		function parecerista_inport($nome,$lattes,$instituicao,$area,$email)
			{				
				$nome_asc = trim(UpperCaseSQL(trim($nome)));
				$sql = "select * from ".$this->tabela." where 
					us_nome_asc = '".$nome_asc."' ";
				$rlt = db_query($sql);
				
				if ($line = db_read($rlt))
					{
						$id = $line['id_us'];
						$sql = "update ".$this->tabela." set ";
						$sql .= " us_lattes = '".$lattes."' ";
						$sql .= ", us_ativo = 1 ";
						$sql .= " where id_us = ".$line['id_us'];
						$rlt = db_query($sql);
					} else {
						if (strlen($email)==0)
							{
								echo 'Não existe e-mail cadastrado';
								exit;
							}
						$sqlx = "insert into ".$this->tabela." 
								(us_nome,us_instituicao,us_ativo,
								us_nome_asc, us_codigo,us_email)
								values
								('$nome','',1,
								'$nome_asc','','')";
						$rlt = db_query($sqlx);
						$rlt = db_query($sql);
						$line = db_read($rlt);
					}
				$pare = $line['us_codigo'];
				$this->le($id);
				$codigo = $this->busca_codigo_cnpq($area);
				$this->area_adiciona($codigo);
				
				$this->enviar_email_atualizacao_dados();
				return(True);
			}
		
		function le($id='')
			{
				global $http;
				if (strlen($id) > 0) { $this->id = $id; }
				$sql = "select * from ".$this->tabela." ";
				$sql .= " left join instituicao on us_instituicao = inst_codigo ";
				$sql .= " where id_us = ".round($this->id)." or us_codigo = '".$id."'";
				$rlt = db_query($sql);
				if ($line = db_read($rlt))
					{
						$avaliador = trim($line['us_codigo']);
						$id = 'pibic'.date("Y");
						$link = $http.'avaliador/acesso.php?dd0='.$avaliador.'&dd90='.checkpost($id.$avaliador);
						$link = '<A HREF='.$link.' target=new_'.date("YmdHis").' >'.$link.'</A>';
						$this->codigo = $line['us_codigo'];
						$this->nome = $line['us_nome'];
						$this->email = $line['us_email'];
						$this->email_alt = $line['us_email_alternativo'];
						$this->instituicao_cod = $line['us_instituicao'];
						$this->instituicao = $line['inst_nome'];
						$this->status = $line['us_aceito'];
						$this->link_avaliador = $link;
						$this->line = $line;
						$this->idx = $line['id_us'];
						
						if (strlen(trim($line['inst_abreviatura'])) > 0)
							{
								$this->instituicao .= ' ('.trim($line['inst_abreviatura']).')';
							}
						return(True);
					}
				return(False);
					
			}
		function busca_codigo_cnpq($area)
			{
				$sql = "select * from ajax_areadoconhecimento where a_cnpq = '".trim($area)."' ";
				$rlt = db_query($sql);
				if ($line = db_read($rlt))
				{
					$cod = $line['a_codigo'];
				} else {
					$cod = '';
				}
				return($cod);
			}
		function security()
			{
				$this->id = $_SESSION['userp_id'];
				$this->nome = $_SESSION['userp_nome'];
				$this->codigo = $_SESSION['userp_cod'];
				$this->us_nivel = $_SESSION['userp_nivel'];
				$this->instituicao = $_SESSION['usp_instituicao'];
				$this->instituicao_cod = $_SESSION['usp_instituicao_cod'];
				return(1);
			}
		function cp_myaccount()
			{
				global $dd,$par;
				$cp = array();
				//$dd[0] = $par->codigo;
				array_push($cp,array('$H8','id_us','','',False,False));
				array_push($cp,array('$H8','us_titulacao','Títulação',False,True));
				array_push($cp,array('$S100','us_nome','Nome completo',False,False));
				array_push($cp,array('$S100','us_email','e-mail',True,True));
				array_push($cp,array('$S100','us_email_alternativo','e-mail (alt.)',False,True));
				array_push($cp,array('$Q inst_nome:inst_codigo:select * from instituicao order by inst_nome','us_instituicao','Instituição',True,True));
				return($cp);
				
				
			}
		function avaliador_autentica()
			{
				
			}
		function area_adiciona($area)
			{
				$sql = "select * from ".$this->tabela."_area 
						where pa_parecerista = '".$this->codigo."' 
						and pa_area = '".$area."' ";
						
				$rlt = db_query($sql);
				if (!($line = db_read($rlt)))
					{
						$parecerista = $this->codigo;
						$data = date("Ymd");
						
						$sql = "insert into ".$this->tabela."_area
							(pa_parecerista, pa_area, pa_update) 
							values
							('$parecerista','$area','$data');
						";
						$rlt = db_query($sql); 
						return(1);
					} else {
						echo '
						<script>
							alert("Já cadastrado");
						</script>
						';
					}
				
				return(0);
			}
			
		function areas_novas()
			{
				global $dd;
				
				/*
				 * Area simples
				 */
					$sx .= '<table width=98% border=1 class="lt1" cellpadding=2 cellspacing=0 >';
					$sql = "select * from ajax_areadoconhecimento 
						left join ".$this->tabela."_area on ((pa_area = a_codigo) and (pa_parecerista = '".$this->codigo."')) 
						where ";
					$sql .= " (a_semic = 1 or a_submit = '1') and not (a_cnpq like 'X%' or a_cnpq like '%-X%') and a_cnpq <> '' ";
					$sql .= " order by a_cnpq ";
					
					$rlt = db_query($sql);
					
					while ($line = db_read($rlt))
						{
							$id = 9;
							$sl = '';
							$style = 'style="display: none; "';
							
							if (substr($line['a_cnpq'],5,2) != '00') { $id = 2; $sln = '</I></font>'; $sl = '<font class="lt2"><I>'; $style = 'style="padding: 0px 0px 0px 20px;" '; }
							if (substr($line['a_cnpq'],5,2) == '00') { $id = 1; $sln = '</I></font>'; $sl = '<A name="'.substr($line['a_cnpq'],0,4).'"><font class="lt2"><I>'; $style = 'style="padding: 0px 0px 0px 20px;" '; }
							if (substr($line['a_cnpq'],2,2) == '00') { $id = 1; $sln = '</B></font>'; $sl = '<font class="lt2"><B>'; $sx .= '<TR><TD>'; $style = ''; }
							
							if ((strlen($sl) > 0) and ($id == 1))	
								{
								$area = trim($line['a_codigo']);
								if (strlen($sy) > 0) { $sx .= $sy . '</div>'; $sy = ''; }
								$sx .= '<div id="nm'.trim($line['a_codigo']).'" '.$style.'>';								
								$sx .= $sl;
								$sx .= trim($line['a_cnpq']);
								if ($id > 0) 
									{
										$idc = trim($line['a_codigo']);
										
										$sx .= '&nbsp;';
										$sx .= '<span id="b'.$idc.'" style="cursor: pointer;">';
										$sx .= '(+)';
										$sx .= '</span>';
										$jv .= '$("#b'.$idc.'").click(function() 
													{
														/* alert(\'clicou\'); */ 
														$(this).slideUp();
														$("#nm'.$idc.'e").fadeIn(\'slow\'); 
													}
												); '.chr(13).chr(10);
										$sy = '';
										
										
									}
/* */
									if (($ativo == 0) and (substr($line['a_cnpq'],2,2) != '00'))
										{
											$link = '&nbsp;<a name="bt" href="#'.substr($line['a_cnpq'],0,4).'" class="area" ';
											$link .= 'onclick="adicionar_area(\''.$line['a_codigo'].'\',this);"';
											$link .= '>(adicionar área)</a>';
										} else {
											$link = $slb;
											$link = '&nbsp;<span style="color: green">(área ativa)</span>';
											$link = $sla;
										}
									$sx .= $link;
									$sx .= '&nbsp;';
/* */																		
									$sx .= '&nbsp;';
									$sx .= trim($line['a_descricao']);
									$sx .= $sln;
									$sx .= '</div>';
									$sx .= chr(13);
								} else {
									$ativo = round($line['pa_update']);
									if ($ativo > 0) { $sla = '<span style="area_ativa">'; $slb = '</span>'; }
									if (substr($line['a_cnpq'],8,2)=='00') { $sla .= ''; $slb = ''.$slb; } 
									 
									if (strlen($sy)==0) 
										{ $sy .= '<div id="nm'.$idc.'e" style="display: none; padding: 0px 0px 0px 30px;">' . chr(13); }
									else 
										{ $sy .= '<BR>'; }
										
									$sy .= '<TT>'.$sla;								 
									 
									$sy .= trim($line['a_cnpq']);
									
									if ($ativo == 0)
										{
											$link = '&nbsp;<a name="bt" href="#'.substr($line['a_cnpq'],0,4).'" class="area" ';
											$link .= 'onclick="adicionar_area(\''.$line['a_codigo'].'\',this);"';
											$link .= '>(adicionar área)</a>';
										} else {
											$link = $slb;
											$link = '&nbsp;<span style="color: green">(área ativa)</span>';
											$link = $sla;
										}
									$sy .= $link;
									$sy .= '&nbsp;';
									$sy .= trim($line['a_descricao']);
									$sy .= $slb;
									$sy .= '</TT>';
									$sy .= chr(13);
									}
						}
					$sx .= '</table>'.chr(13);
					$sx .= '<script>'.chr(13).chr(10);
					$sx .= $jv;
					$sx .= 'function adicionar_area(cod,nm)'.chr(13);
					$sx .= "
							{					 
							$.ajax({
  									url: 'my_account_area.php?dd1=ADD&dd2='+cod,
  									context: document.body,
  									cache: false
								}).done(function(html) 
									{
									$('#areascadastradas').html(html);
									$(nm).slideUp();
									alert('Ativado');
  								});
							} ";	
					$sx .= '</script>'.chr(13).chr(10);
					$sx .= '
						<style>
						.active
							{
							color: Red; 
							cursor: pointer;
							text-decoration: none;
							}						
						.area
							{
							color: Blue; 
							cursor: pointer;
							text-decoration: none;
							}
						.area:hover
							{
							color: #5050FF; 
							cursor: pointer;
							text-decoration: underline;
							}							
						</style>';
				return($sx);					
			}			
		function area_excluir($id)
			{
				global $dd,$secu,$edit;
				if (($dd[12]=='DEL') 
					and ($dd[11] == checkpost($dd[10])) 
					and ($edit == 1))
					{
						$sql = "delete from ".$this->tabela."_area where id_pa = ".round($dd[10]);
						$rlt = db_query($sql);
					} else {
						$sx .= 'Erro de código';
					}			
				return($sx);
			}
		function mostra_areas()
			{
				global $date,$edit,$dd,$acao;
				
				if ($dd[12]=='DEL')
					{
						$this->area_excluir($dd[0]);
					}

				$sxz .= '<table class="half_internal_content" border=1>
						<TR><TD>
				';
				$sxz .= '<font color="red"><B>';								
				$sxz .= 'Nenhuma área do conhecimento cadastrada, seleciona uma das áreas abaixo.';
				$sxz .= '</table>';
				$sql = "select * from ".$this->tabela."_area 
						left join ajax_areadoconhecimento on pa_area = a_codigo
							where pa_parecerista  = '".$this->codigo."' 
							order by a_cnpq
							";	
				$rlt = db_query($sql);
				
				while ($line = db_read($rlt))
					{
						$sx .= '<tr>
									<td class="tabela01">
									<i class="icon-barcode icon-large icon_color_padrao"></i>&nbsp;&nbsp;&nbsp;'.$line['a_cnpq'].'
									</td>
									<td class="tabela01">
									<i class="icon-bookmark icon-large icon_color_padrao"></i>&nbsp;&nbsp;&nbsp;'.$line['a_descricao'].'
									</td>
									<td class="tabela01">
									<i class="icon-refresh icon-large icon_color_padrao"></i>&nbsp;&nbsp;&nbsp;'.stodbr($line['pa_update']).'
									</td>
									
									<td class="tabela01">
									<i class="icon-refresh icon-large icon_color_padrao"></i>&nbsp;&nbsp;&nbsp;<A HREF="'.page().'?dd0='.$dd[0].'&dd12=DEL&dd11='.checkpost($line['id_pa']).'&dd10='.$line['id_pa'].'">excluir</A></I>
									</td>';																											
					}

				if (strlen($sx) == 0)
					{
						$sx = $sxz;
					} else {
						$sxx .= '<Table width="100%" border=0 class="tabela30 lt1">';
						$sxx .= '<TR><TD class="tabela30t" colspan=4>Áreas associadas ao parecerista';
						$sxx .= '<TR><TH>'.msg('codigo').'<TH>'.msg('area').'<TH>'.msg('update');
						if (strlen($edit) > 0)
							{
								$sxx .= '<TH>'.msg('acao');
							}
						$sxx .= $sx; 
						$sxx .= '</table>';
						$sx = $sxx;
					}
				$sx .= '</table>';
				return($sx);
			}
		function area_mostra_adiciona()
			{
				global $dd;
				
				$sql = "select * from  ajax_areadoconhecimento 
							where a_semic = 1 or a_submit = '1'
							order by a_cnpq ";
				$rlt = db_query($sql);
				$sq = '';
				while ($line = db_read($rlt))
					{
						$sq .= '<option value="'.$line['a_codigo'].'">';
						$sq .= trim($line['a_cnpq']).' - ';
						$sq .= trim($line['a_descricao']);
						$sq .= '</option>'.chr(13);
					}
				$sx .= '<BR>';
				$sx .= '<div id="new_area_div" style="display: block;">';
					$sx .= '<form>'.chr(13);
					$sx .= '<input type="hidden" value="'.$dd[0].'" name="dd0">';				
					$sx .= '<input type="hidden" value="'.$dd[90].'" name="dd90">';
					$sx .= '<input type="hidden" value="ADD" name="dd12">';
					
					$sx .= '<h3>Associar área ao avaliador</h3>';
					$sx .= '<Table>';
					$sx .= '<TR valign="top"><TD>';
	
						$sx .= '<select name="dd10" style="height: 25px;">'.chr(13);
						$sx .= '<option value="">:: seleciona área ::</option>'.chr(13);
						$sx .= $sq;
						$sx .= '</select>'.chr(13);
						
					$sx .= '<TD>';
					
					$sx .= '<input type="submit" value=" adicionar >>" name="acao" style="height: 25px;">';
					$sx .= '</table>';
					$sx .= '</form>'.chr(13);
				$sx .= '</div>';
				
				$sx .= '<script>
							$("#new_area").click(function() {
								$("new_area_div").fadeOut();
								alert("OLA");
							});
							
						</script>
				';
				return($sx);				
			}
		function area_mostra($line)
			{
				global $date,$edit,$dd,$acao,$page;
				if (strlen($page) == 0) { $page = 'my_account.php'; }
				$link = '<A HREF="'.$page.'?dd0='.$dd[0].'&dd90='.$dd[90].'&dd10='.$line['id_pa'].'&dd11='.checkpost($line['id_pa']).'&dd12=DEL" class="link">';
				$area = trim($line['a_cnpq']);				
				$sx .= '<TR '.coluna().'>';
				$sx .= '<TD class="tabela00" width="15%">';
				$sx .= $sl.$line['a_cnpq'];
				$sx .= '<TD class="tabela00" width="70%">';
				$sx .= $sl.$line['a_descricao'];
				if (round($line['pa_update']) > 0)
					{ $sx .= '<TD align="center" class="tabela00">'; }
				$sx .= $date->stod($line['pa_update']);
				if ($edit == 1)
					{
						$sx .= '<TD align="center" class="tabela00">';
						$sx .= $link.'[excluir]</A>';
					} 
						
				return($sx);									
			}
		function resumo_avaliacoes()
			{
				global $jid;
				$sql = "select * from reol_parecer_enviado ";
				$sql .= "inner join submit_documento on pp_protocolo = doc_protocolo ";
				$sql .= "where (pp_data >= 20000101) ";
				$sql .= " and pp_avaliador = '".$this->codigo."' ";
				// doc_journal_id = '".strzero($jid,7)."' "; $sql .= "and 
				$sql .= " order by pp_data desc ";
				$rlt = db_query($sql);
				
				$sr = '';
				$tot1 = 0;
				$tot2 = 0;
				$tot3 = 0;
				while ($line = db_read($rlt))
					{
						$status = $line['pp_status'];
						$tot4++;
						$linka = '<a href="#" onclick="newxy2(\'parecer_declinar.php?dd0='.$line['id_pp'].'\',600,400);" class="link">';
						$linkv = '<a href="subm.php?dd0='.$line['id_doc'].'" title="ver submissão" target="_new_'.$line['id_doc'].'" class="link">';
						switch ($status)
							{
							case 'I': $font = '<font color="black">'; $status = '<font color="green">'.$linka.'Declinar</A></font>'; $tot2++; break;
							case 'C': $font = '<font color="grey">';$status = '<font color="brown">Declinado</font>'; $tot3++; break;
							case 'D': $font = '<font color="grey">';$status = '<font color="brown">Declinado</font>'; $tot3++; break;
							case 'B': $font = '<font color="blue">';$status = '<font color="brown">Finalizado</font>'; $tot1++; break;
							case 'J': $font = '<font color="blue">';$status = '<font color="brown">Finalizado</font>'; $tot1++; break;
							case 'X': $font = '<font color="grey">';$status = '<font color="grey">Cancelado</font>'; break;
							}
						$titulo = LowerCase($line['doc_1_titulo']);
						$titulo = UpperCase(substr($titulo,0,1)).substr($titulo,1,strlen($titulo));
						$sr .= '<TR><TD class="tabela01"  align="center">'.$font.stodbr($line['pp_data']).'</font>';
						$sr .= '<TD align="center">'.$status;
						$sr .= '<TD class="tabela01" align="center">'.$linkv.$font.$line['doc_protocolo'].'</font></A>';
						$sr .= '<TD class="tabela01">'.$font.$titulo.'</font>';
					}
				if ($tot1==0) { $tot1 = '-'; }
				if ($tot2==0) { $tot2 = '-'; }
				if ($tot3==0) { $tot3 = '-'; }
				if ($tot4==0) { $tot4 = '-'; }
				$sx = '<table width="200" class="tabela30">';
				$sx .= '<TR><TD class="tabela30" align="center" colspan=2 bgcolor="#F0F0F0"><B>Resumo do avaliador</B>';
				$sx .= '<TR><TD class="tabela30" align="right">Indicados:    <TD class="tabela30 lt3" align="center"><B>'.$tot4.'</B>';
				$sx .= '<TR><TD class="tabela30" align="right">Avaliados:    <TD class="tabela30 lt3" align="center"><B>'.$tot1.'</B>';
				$sx .= '<TR><TD class="tabela30" align="right">Em avaliação: <TD class="tabela30 lt3" align="center"><B>'.$tot2.'</B>';
				$sx .= '<TR><TD class="tabela30" align="right">Declinados:   <TD class="tabela30 lt3" align="center"><B>'.$tot3.'</B>';
				$sx .= '</table>';
				
				$sr = '<table width="100%" class="tabela30">
						<TR><TH>Indicação<TH>Ação<TH>Protocolo<TH>Título do documento
						'.$sr.'</table>';
				$this->lista_avaliacoes = $sr;
				return($sx);
			}

		function resumo_avaliador()
			{
				$ira = array(0,0,0,0,0);
				$ir = array();
				for ($r=date("Y");$r >= 2011;$r--)
					{
						$ira[0] = $r;
						array_push($ir,$ira);
					}
				
				$sx .= '<Table width="400" border=0 class="tabela30t" align="right">';
				$sx .= '<TR><TD colspan=5 align="center" class="tebalas_titles">Resumo do avaliador</TD></TR>'.chr(13);
				$sx .= '<TR align="center" class="tabela30">
							<TH width="10%" class="tabela30">ano
							<TH width="20%" class="tabela30">indicados
							<TH width="20%" class="tabela30">Avaliados
							<TH width="20%" class="tabela30">Em avaliação
							<TH width="20%" class="tabela30">Declinados';

				/*							
				$sql = "select pp_status from pibic_parecer_".(date("Y"))." where pp_avaliador = '".$this->codigo."'";
				$sql .= " union ";
				$sql .= "select pp_status from pibic_parecer_".(date("Y")-1)." where pp_avaliador = '".$this->codigo."'";
				$sql .= " union ";
				$sql .= "select pp_status from pibic_parecer_".(date("Y")-2)." where pp_avaliador = '".$this->codigo."'";
				$sql .= " union ";
				$sql .= "select pp_status from pibic_parecer_".(date("Y")-3)." where pp_avaliador = '".$this->codigo."'";
				$sql .= " union ";
				 */
				 
				$sql = "select pp_data, pp_status from reol_parecer_enviado where pp_avaliador = '".$this->codigo."' ";
				$sql .= " union ";
				$sql .= "select pp_data, pp_status from submit_parecer_2013 where pp_avaliador = '".$this->codigo."' ";
				$rlt = db_query($sql);
				
				while ($line = db_read($rlt))
					{
						$ano = round(substr($line['pp_data'],0,4));
						$pos = (date("Y")-$ano);
						$status = trim($line['pp_status']);
						
						$posx = 5;
						
						switch ($status)
							{
								case '@': $posx = 1; break;
								case 'A': $posx = 3; break;
								case 'B': $posx = 2; break;
								case 'C': $posx = 2; break;
								case 'D': $posx = 4;  break;
							}
						$ir[$pos][$posx] = $ir[$pos][$posx] + 1;
						}
				
				for ($r=0;$r < count($ir);$r++)
					{
					$sx .= '<TR '.coluna().' class="tabela10_tr">
							<TD align="center" class="tabela30">'.$ir[$r][0].'</td>
							<TD align="center" class="tabela30">'.$ir[$r][1].'</td>
							<TD align="center" class="tabela30">'.$ir[$r][2].'</td>
							<TD align="center" class="tabela30">'.$ir[$r][3].'</td>
							<TD align="center" class="tabela30">'.$ir[$r][4].'</td>
							';
					}
				$sx .= '</table>';
				return($sx);
			}
			
		function mostra_email($email)
			{
				if (strlen(trim($email)) > 0)
					{
					$img_email = '<img src="'.http.'img/icone_email.png" height="20">';
					$sx = '<div class="lt1" style="float: left;">'.$img_email.' '.$email.'&nbsp;&nbsp;&nbsp;</div>';
					}
				return($sx);	
			}

		function mostra_link_avaliador($link)
			{
				global $http;
				
				$link = 'a';
				if (strlen(trim($link)) > 0)
					{
						/*
						 * http://www2.pucpr.br/reol/avaliador/acesso.php?dd0=0001645&dd90=dd784c4e01
						 */
					$img_link = '<A HREF="'.$http.'avaliador/acesso.php?dd0='.$this->codigo.'&dd90='.checkpost($this->codigo).'" target="_new">
									<img src="'.$http.'img/icone_link.png" height="10" border=0 >
								';
					
					$sx = '<div class="lt1" style="float: left; cursor: pointer; ">
							'.$img_link.' '.'Avaliador'.'&nbsp;&nbsp;&nbsp;
							</A>
							</div>';
					}
				return($sx);	
			}
			
		function mostra_dados()
			{
				global $edit;
				
				$sx = '';
				$sx .= '
						<table style="width: 370px; float: left;" border=5>
							<TR valign="top"><TD>
								<i class="icon-user icon-large icon_color_padrao"></i>
								<div class="tabela00_sub">
								<span class="lt2">'.$this->nome.'<BR>
							<div class="lt1">'.$this->instituicao.'</div>							
							<!-- email -->
								<span class="lt0">Cod: '.$this->codigo.'</span>
									<br>
									<span class="lt1">

									<!--EMAIL PARECERISTA-->
									<br>'.$this->mostra_email($this->email).'</span>
									<br>'.$this->mostra_email($this->email_alt).'</span>
						

									<div class="link_avaliador">


										<!--LINK PARECERISTA AVALIADOR-->
										<a href="#"><i class="icon-link"></i>
											&nbsp;<span class="lt1">'.$this->mostra_link_avaliador($this->linking).'</span></a>
									</div>
								</div>
							</table>';
				return($sx);
			}

		function mostra_dados_grande()
			{
				global $edit;
				$linkz = '<A HREF="'.http.'avaliador/acesso.php?dd0='.$this->codigo.'&dd90='.checkpost($this->codigo).'">';
				$link = '<a href="javascript:newxy(\'my_account_ed.php?dd0='.$this->idx.'&dd90='.checkpost($this->id).'\',700,400);" class="ed">editar dados</A>';
				
				$sx .= '<style>'.chr(13);
				$sx .= '.ed {font-size:11px; color=red; font-decoration: none; }'.chr(13);
				$sx .= '</style>'.chr(13);
				
				$sx .= '<div style="width:100%">';
				$sx .= '<table width="100%" border=0 align="center" class="tabela00">';
				$sx .= '<TR valign="top"><TD class="lt0" colspan=4>nome do avaliador';
				$sx .= '<TD rowspan=4 width=80>'.$this->resumo_avaliacoes();
				$sx .= '<TR><TD class="tabela01" colspan=2><B>'.$this->nome;
				
				$sx .= '<TR ><TD class="tabela01">
						<font class="lt0">instituíção:</font>
						'.$this->instituicao;;
				$sx .= '<BR><font class="lt0">código:</font>
						'.$this->codigo;
				$sx .= '<BR><font class="lt0">email</font>: '.$this->email;
				$sx .= '<BR><font class="lt0">email</font>: '.$this->email_alt;
				$sx .= '<TR><TD>';
				//$sx .= $linkz.'Link do avaliador'.'</A>';
				$sx .= '</table>';
				$sx .= '<BR>'.$link;
				
				//$sx .= '<table width="100%" cellpadding=0 cellspacing=0>';
				//$sx .= '<TR><TD>';
				
				/* areas de avaliacao */
				//$sx .= '<div id="areas">';
				//if (strlen($edit) > 0)
				//	{
				//		$sx .= '<font class="lt1">'.msg('areas_instructions').'</font>';
				//	}
				//$sx .= '<TR><TD>Áreas de avaliação cadastradas';					
				//$sx .= $this->mostra_areas();
				//$sx .= '</div>';
								
				//$sx .= '</table>';
				//$sx .= '</div>';		

				return($sx);
			}
		
		function status()
			{		
				$ar = array(
					'1'=>'Convite aceito',
					'2'=>'Aguardando resposta',
					'3'=>'Recusado temporariamente',
					'9'=>'Enviar convite',
					'-1'=>'Excluir',
					'0'=>'Não, convite não aceito',
					'19'=>'Convite novo enviado',
					'10'=>'SIM, Convite Novo Aceito',
					'11'=>'Convite aceito via CNPq'
					);
				return($ar);
			}
		function login()
			{
				
			}
		function login_set($id='')
			{
				global $dd;
				$id = trim($id);
				if (strlen($id)==8)
					{
						$sql = "select 
							'Pontifícia Universidade Católica do Paraná' as inst_nome,
							'0000455' as inst_codigo,
							pp_cracha as us_codigo,
							pp_nome as us_nome,
							id_pp as id_su
							
						from pibic_professor 
						where (pp_cracha = '".$id."')";
					} else {
						$sql = "select * from ".$this->tabela." 
						left join instituicao on inst_codigo = us_instituicao
						where (id_us = ".round($id).") or (us_codigo = '".$id."')";				
					}					
					$rlt = db_query($sql);
				if ($line = db_read($rlt))
				{
					$this->id = $line['id_su'];
					$this->nome = $line['us_nome'];
					$this->codigo = trim($line['us_codigo']);
					$this->instituicao = $line['inst_nome'];
					$this->instituicao_cod = $line['inst_codigo'];

					$_SESSION['userp_id'] = $this->codigo;
					$_SESSION['userp_nome'] = $this->nome;
					$_SESSION['userp_cod'] = $this->codigo;
					$_SESSION['usp_nivel'] = $this->nivel;
					$_SESSION['usp_instituicao'] = $this->instituicao;
					$_SESSION['usp_instituicao_cod'] = $this->instituicao_cod;
				} else {
					echo 'Código não localizado, o suporte técnico foi informado.<BR>Em breve entraremos em contato.';
					enviaremail('monitoramento@sisdoc.com.br','','Erro de acesso','Erro de acesso '.$dd[0].'=='.$dd[90]);
					exit;
				}
				return(1);
			}
				
		function structure()
			{
			$sql = "CREATE TABLE pareceristas
				( 
				id_us serial NOT NULL, 
				us_nome char(120), 
				us_nome_asc char(120),
				us_login char(15), 
				us_titulacao char(10), 
				us_instituicao char(7), 
				us_lattes char(100), 
				us_senha char(100), 
				us_ativo int2, 
				us_lastupdate int8, 
				us_lembrete char(100), 
				us_nivel int2, 
				us_niver int8, 
				us_cpf char(20), 
				us_rg char(20), 
				us_fone_1 char(30), 
				us_fone_2 char(30), 
				us_fone_3 char(30), 
				us_nome_pai char(100), 
				us_nome_mae char(100), 
				us_dt_admissao int8, 
				us_dt_demissao int8, 
				us_vt char(20), 
				us_vr char(20), 
				us_email char(100), 
				us_email_alternativo char(100), 
				us_cracha char(15), 
				us_endereco text, 
				us_codigo char(7), 
				us_email_ativo int2, 
				us_journal_id int4, 
				us_bolsista char(10), 
				us_aceito int4, 
				us_aceito_resp int4 DEFAULT 0, 
				ap_ordem int4 DEFAULT 0
				);";
			$rlt = db_query($sql);
			
			$sql = "CREATE TABLE pareceristas_area
				( 
				id_pa serial NOT NULL, 
				pa_parecerista char(7), 
				pa_area char(7), 
				pa_update int8 
				); ";
			$rlt = db_query($sql);				
			return(1);
			}
			
		function updatex()
			{
				global $base;
				$c = 'us';
				$c1 = 'id_'.$c;
				$c2 = $c.'_codigo';
				$c3 = 7;
				$sql = "update ".$this->tabela." set $c2 = lpad($c1,$c3,0) where $c2='' ";
				if ($base=='pgsql') { $sql = "update ".$this->tabela." set $c2 = trim(to_char(id_".$c.",'".strzero(0,$c3)."')) where $c2='' "; }
				$rlt = db_query($sql);
				
				$c = 'us';
				$c1 = 'id_'.$c;
				$c2 = $c.'_codigo_id';
				$c3 = 7;
				$sql = "update ".$this->tabela." set $c2 = lpad($c1,$c3,0) where $c2='' ";
				if ($base=='pgsql') { $sql = "update ".$this->tabela." set $c2 = trim(to_char(id_".$c.",'".strzero(0,$c3)."')) where $c2='' or $c2 isnull"; }
				$rlt = db_query($sql);
				
				$this->ASC_update();
			}
						
		function ASC_update()
			{
				$sql = "select * from ".$this->tabela." where ((us_nome_asc = '' or us_nome_asc isnull) and us_nome <> '') ";
				$rlt = db_query($sql);
				while ($line = db_read($rlt))
				{
					$sql = "update ".$this->tabela." set us_nome_asc = '".UpperCaseSql($line['us_nome'])."' where id_us = ".$line['id_us'];
					$rltx = db_query($sql);
				}
			}			
	}
?>
