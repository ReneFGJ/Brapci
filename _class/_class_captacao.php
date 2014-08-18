<?php
class captacao
	{
		var $id;
		var $protocolo;
		var $docente;
		var $participacao;
		var $financiador;
		var $edital;
		var $edital_nr;
		var $edital_ano;
		var $contexto;
		var $comentario;
		var $status;
		var $professor;
		var $professor_nome;
		var $total;
		var $titulo;
		var $valor_proponente;
		var $vigencia;
		var $proj_inst;
		
		var $grafico;
		
		var $tabela = 'captacao';
		
	function captacao_listar($sta)
		{
			$wh = 'ca_status = '.round($sta);
			/* Combos */
			if ($sta == 1) { $wh = '(ca_status = 1 or ca_status = 11 or ca_status = 12 or ca_status = 13) '; }
			if ($sta == 2) { $wh = '(ca_status = 2 or ca_status = 14) '; }
			
			$sql = "select * from ".$this->tabela." where 
				$wh ";
			
			$rlt = db_query($sql);
			
			switch ($sta)
				{
				case 1: $sx .= '<h3>Para bonificar</h3>'; break;
				case 2: $sx .= '<h3>Sem bonificar e sem isenção</h3>'; break;
				case 89: $sx .= '<h3>Finalizado</h3>'; break;
				case 80: $sx .= '<h3>Em processo de bonificação</h3>'; break;
				case 8: $sx .= '<h3>Devolvido ao professor para correções</h3>'; break;
				default:
						$sx .= '<h3>Sem título - '.$sta.'</h3>';
				}
			$sx .= '<table width="100%">';
			$id = 0;			
			while ($line = db_read($rlt))
				{
					$id++;
					$sx .= $this->mostra_captacao_lista($line);		
				}
			$sx .= '<TR><TD colspan=10>Total de '.$id.' registros';
			$sx .= '</table>';
			return($sx);
		}
		
	function lista_projetos_vinculados()
		{
			$sql = "select * from ".$this->tabela." 
						inner join pibic_professor on pp_cracha = ca_professor
						where ca_vinculo = '".$this->protocolo."' ";
			$rlt = db_query($sql);
			$sx .= '<table width="100%" class="tabela00">';
			$xprof = '';
			$id = 0;
			while ($line = db_read($rlt))
				{
					$id++;
					$prof = trim($line['pp_nome']);
					if ($prof != $xprof)
						{
							$sx .= '<TR><TD colspan="4"><B>'.$prof;
							$xprof = $prof;
						}
					$sx .= $this->mostra_captacao_lista($line);
				}
			$sx .= '</table>';
			if ($id == 0) { $sx = ''; }
			else 
				{
					$sx = '<H3>Desembramento</h3>'.$sx;
				}
			echo $sx;
			return($sx);			
		}
		
	function resumo_captacao($cracha)
		{	
			if (strlen($cracha) > 0)
				{
					$wh = " where ar_professor = '".$cracha."' ";
				}
			$sql = "select count(*) as total, ca_status from ".$this->tabela."
				$wh 
				group by ca_status
				order by ca_status
			";
			
			//$sql = "select * from ".$this->tabela;
			$rlt = db_query($sql);
			$api = array(0,0,0,0,0,0,0,0,0,0,0,0);
			$apb = array(0,0,0,0,0,0,0,0,0,0,0,0);
			while ($line = db_read($rlt))
				{
					$sta = round($line['ca_status']);
					$total = $line['total'];

					switch ($sta) {
						case 0:
							$api[0] = $api[0] + $total;
							break;	
						/* Para bonificar */					
						case 1:
							$api[1] = $api[1] + $total;
							break;
						case 11:
							$api[1] = $api[1] + $total;
							break;
						case 12:
							$api[1] = $api[1] + $total;
							break;	
							
						/* Somente isenção */
						case 13:
							$api[1] = $api[1] + $total;
							break;														
													
						case 89:
							$api[7] = $api[7] + $total;
							break;
						case 80:
							$api[6]  = $api[6] + $line['total'];
							break;	
						case 2:
							$api[5] = $api[5] + $line['total'];
							break;
						case 14:
							$api[5] = $api[5] + $line['total'];
							break;							
						case 10:
							$api[2] = $api[2] + $line['total'];
							break;
							
						case 8:
							$api[8] = $api[8] + $line['total'];
							break;	
						/* Cancelado */
						case 9:
							$api[9] = $api[9] + $line['total'];
							break;													
						default:
							//echo ' -->'.$sta;
							break;
					}
				}
			//print_r($api);
			$sx = '<table class="tabela00" width="700" align="center">';
			//$sx .= '<TR><TD><TD colspan=4><center><h2>Cadastro de Artigos Bonificáveis</h2>';
			$sx .= '<TR>';
			$sx .= '<TH>';
			$sx .= '<TH width="15%">Em cadastro';
			$sx .= '<TH width="15%">Devolvido para correções';
			$sx .= '<TH width="15%">Aguardando validação coordenador';
			$sx .= '<TH width="15%">Para bonificar e ou isentar';
			$sx .= '<TH width="15%">Sem bonificados';
			
			$sx .= '<TH width="15%">Em processo de bonificação';
			$sx .= '<TH width="15%">Finalizados';
			
			$link9 = '<A HREF="captacao_listar.php?dd0=0&dd90='.checkpost(0).'">';
			$link8 = '<A HREF="captacao_listar.php?dd0=8&dd90='.checkpost(0).'">';
			$link0 = '<A HREF="captacao_listar.php?dd0=10&dd90='.checkpost(10).'">';
			$link1 = '<A HREF="captacao_listar.php?dd0=1&dd90='.checkpost(1).'">';
			
			$link5 = '<A HREF="captacao_listar.php?dd0=2&dd80='.checkpost(2).'">';
			$link6 = '<A HREF="captacao_listar.php?dd0=80&dd80='.checkpost(80).'">';
			$link7 = '<A HREF="captacao_listar.php?dd0=89&dd90='.checkpost(89).'">';
			
			$sx .= '<TR>';
			$sx .= '<TD align="right">Captações';
			$sx .= '<TD class="tabela01" align="center">'.$link9.$api[0].'</A>';
			$sx .= '<TD class="tabela01" align="center">'.$link8.$api[8].'</A>';
			$sx .= '<TD class="tabela01" align="center">'.$link0.$api[2].'</A>';
			$sx .= '<TD class="tabela01" align="center">'.$link1.$api[1].'</A>';
			
			$sx .= '<TD class="tabela01" align="center">'.$link5.$api[5].'</A>';
			$sx .= '<TD class="tabela01" align="center">'.$link6.$api[6].'</A>';
			$sx .= '<TD class="tabela01" align="center">'.$link7.$api[7].'</A>';
					
			$sx .= '</table>';
			return($sx);
		}		
		
		function categoriazacao_tipos_fomente()
			{
				$sql = "update captacao set ca_agencia_gr = '' where ca_agencia_gr = '00026' ";
				$xrlt = db_query($sql);
								
				$sql = "select * from ".$this->tabela." ";
				$rlt = db_query($sql);
				while ($line = db_read($rlt))
					{
						$tipo = trim($line['ca_agencia']);
						if (($tipo=='FA') or ($tipo=='CAPES') or ($tipo=='CNPq') or ($tipo=='FINEP') or ($tipo=="SETI"))	
							{
								$sql = "update captacao set ca_agencia_gr = '00026' where id_ca = ".$line['id_ca'];
								$xrlt = db_query($sql);
							}
						if ($tipo=='EMPRE')	
							{
								$sql = "update captacao set ca_agencia_gr = '00024' where id_ca = ".$line['id_ca'];
								$xrlt = db_query($sql);
							}						
					}
				return('');
			}
		
		function historico_inserir($protocolo,$ope,$historico)
			{
				global $user;
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
						bnh_ope, bnh_log, bnh_protocolo)
					values
					($data,'$hora','$historico',
					'$ope','$login','$protocolo')";
					$rlt = db_query($sql);
					} else {
						$sql = "update bonificacao_historico 
								set bnh_historico = '$historico'
								where id_bnh = ".$line['id_bnh'];
						$rlt = db_query($sql);	
						
					}
				return(1);
			}		
		
		function atualiza_vigencias($dd0)
			{
				$ddo = 378;
				$sql = "select * from captacao 
					where id_ca = ".round($dd0);
				$rlt = db_query($sql);
				if ($line = db_read($rlt))
				{
					if (round($line['ca_vigencia_fim_mes'])==0)
					{
						
						$ano = substr($line['ca_vigencia_final_ano'],0,4);
						$mes = substr($line['ca_vigencia_final_ano'],4,2);
						$data = $line['ca_vigencia_final_ano'].'01';
						$tp = $line['ca_duracao'];
						if ($tp > 1) { $tp = $tp - 1; }
						
						$data = DateAdd('m',$tp,$data);
						
						$ano1 = substr($data,0,4);
						$mes1 = substr($data,4,2);
						
						$sql = "update captacao set 
								ca_vigencia_ini_ano = '$ano',
								ca_vigencia_ini_mes = '$mes',
								ca_vigencia_fim_ano = '$ano1',
								ca_vigencia_fim_mes = '$mes1'
								where id_ca = ".round($line['id_ca']);
						//echo $sql;
						//exit;
						$xrlt = db_query($sql);
					}
				}				
			}
		
		function historico_mostrar($protocolo,$protocolo_origem)
			{
				$protocolo2 = round($protocolo);
				$sql = "select * from bonificacao_historico
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
	function recupera_mensagem($tp)
		{
			$sql = "select * from ic_noticia where nw_ref = '".$tp."' ";
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					$titulo = trim($line['nw_titulo']);
					$text = mst(trim($line['nw_descricao']));
				} else {
					echo '<font color="red">Mensagem não cadastrada para '.$tp.'</font>';
					echo '<BR>';
					echo 'Não é possível continuar - erro fatal';
					exit;
				}
			return(array($titulo,$text));			
		}
	function comunica_professor($tp='',$texto)
		{
			global $email_adm, $admin_nome, $http;
			
			$mm = $this->recupera_mensagem($tp);
			
			$titulo = $mm[0];
			$text = $mm[1];

			$text = troca($text,'$COMENTARIO',mst($texto));
			$text = troca($text,'$ASS',$admin_nome.'<BR>'.$email_adm);
			$text = troca($text,'$PROJETO',$this->titulo);
			$text = troca($text,'$PROTOCOLO',$this->protocolo);
			
			$sql = "select * from pibic_professor where pp_cracha = '".$this->professor."'";
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					$email1 = trim($line['pp_email']);
					$email2 = trim($line['pp_email_1']);
				}
				echo '<HR>'.$http.'<HR>';
			$text = '<table width="640" border=0><TR><TD>'.$text;
			$font = '<font style="font-family: "Arial Narrow", Arial, Tahoma; font-size: 13px;">';
			$text = '<img src="'.$http.'img/email_cip_header.png" width="640"><BR>'.$text;
			$text .= '<BR><BR><BR><img src="'.$http.'img/email_cip_foot.png" width="640">';
			$text .= '</table>';
			$email_tst = 'monitoramento@sisdoc.com.br';

			enviaremail('monitoramento@sisdoc.com.br','',$titulo.' (cópia)',$text);
			
			if (strlen($email_adm) > 0) { enviaremail($email_adm,'',$titulo,$text); echo 'enviado para '.$email_adm; }
			if (strlen($email1) > 0) { enviaremail($email1,'',$titulo,$text); echo 'enviado para '.$email1; }
			if (strlen($email2) > 0) { enviaremail($email2,'',$titulo,$text); }
			
			
			return(1);
		}
	function validacao_pela_diretoria()
		{
			global $dd, $acao, $ss;
			$sta = $this->status();
			$sts = $this->status;
			$sx = '';
			
			if ($sts = '10' or $sts == '1' or $sts=='2' or $sts=='3' or $sts=='4' or $sts=='98'  or $sts=='99')
			{			
			if (strlen($acao) > 0)
				{
					$tip = 'VAD';
					if (trim($dd[3]) == '89')
						{ $tip = 'FIM'; }
					$his = 'Encaminhado por '.trim($ss->user_login).' como '.$sta[round($dd[3])];
					$this->historico_inserir($this->protocolo,$tip,$his);
					
					/* Comunica professor se for '@' */
					if ($dd[3] == '8') { $this->comunica_professor('IC_CAP_DEV',$dd[2]); }
					
					$sql = "update captacao set 
							ca_status = ".round($dd[3]).", 
							ca_coordenador = ".$ss->user_cracha.",
							ca_comentario_direcao = '".$dd[2]."' 
							where id_ca = ".round($this->id);
					$rlt = db_query($sql);

					redirecina(page().'?dd0='.$dd[0].'&dd90='.$dd[90]);					
				} else {
					$sql = "select * from captacao 
							where id_ca = ".round($this->id);
					$rlt = db_query($sql);
					$line = db_read($rlt);
					$dd[2] = $line['ca_comentario'];
				}
			
				
			$sx = '<form method="post">';
			$sx .= '<h3>Avaliação da Diretoria de Pesquisa / Secretaria</h3>';
			$sx .= '<input type="hidden" name="dd0" value="'.$dd[0].'">';
			$sx .= '<input type="hidden" name="dd90" value="'.$dd[90].'">';
			$sx .= '<font class="lt0">Comentários da diretoria de pesquisa</font><BR>';
			$sx .= '<textarea name="dd2" rows=80 style="width: 100%; height: 70px;">'.$dd[2].'</textarea>';
			$sx .= '<BR><BR><font class="lt0">Informe o tipo de ação para o projeto:</font><BR>';
			
			/* Projeto como coordenador */
			$sx .= '<input TYPE="RADIO" name="dd3" value="8">'.$sta[8].'<BR>';
			$sx .= '<input TYPE="RADIO" name="dd3" value="11">'.$sta[11].'<BR>';
			$sx .= '<input TYPE="RADIO" name="dd3" value="12">'.$sta[12].'<BR>';
			$sx .= '<input TYPE="RADIO" name="dd3" value="13">'.$sta[13].'<BR>';
			$sx .= '<input TYPE="RADIO" name="dd3" value="14">'.$sta[14].'<BR>';
			
			
			$sx .= '<input TYPE="RADIO" name="dd3" value="89">'.$sta[89].'<BR>';
			$sx .= '<input TYPE="RADIO" name="dd3" value="14">'.$sta[14].'<BR>';
			
			$sx .= '<input TYPE="RADIO" name="dd3" value="9">'.$sta[9].'<BR>';
			$sx .= '<BR><BR><input TYPE="submit" name="acao" value="encaminhar >>>" class="botao-geral">';	
			$sx .= '</form>';
			}
			return($sx);
		}
		
	function validacao_pelo_coordenador()
		{
			global $dd, $acao, $ss;
			$sta = $this->status();
			
			if (strlen($acao) > 0)
				{
					$sql = "update captacao set 
							ca_status = ".round($dd[3]).", 
							ca_coordenador = ".$ss->user_cracha.",
							ca_comentario = '".$dd[2]."' 
							where id_ca = ".round($this->id);
							
					$rlt = db_query($sql);
					$this->historico_inserir($this->protocolo,'VAC','Validado por '.trim($ss->user_login).' como '.$sta[round($dd[3])]);					
					redirecina('../cip/captacaoes_validar.php');					
				} else {
					$sql = "select * from captacao 
							where id_ca = ".round($this->id);
					$rlt = db_query($sql);
					$line = db_read($rlt);
					$dd[2] = $line['ca_comentario'];
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
		
	function total_captacoes_validar($professor='')
		{
			global $cap;
			$sql = "select * from captacao
					inner join 
						( 
						select pdce_programa, pdce_docente from programa_pos_docentes group by pdce_programa, pdce_docente
						) as tabela on ca_professor = pdce_docente
					inner join programa_pos on pdce_programa = pos_codigo
					inner join pibic_professor on pp_cracha = ca_professor
					where pos_coordenador = '$professor'
					and ca_status = 10
			 ";
			$rlt = db_query($sql);
			$id = 0;
			$sx = '';
			$sx .= '<h3>Captações para validação do Coordenador</h3>';
			$sx .= '<table class="tabela00" width="100%">';
			$sx .= '<TR><TH>Orgão<TH colspan=2>Edital<TH>Início<TH>Duração<TH>Prorrogação<TH>Participação<TH>Vlr. Total<TH>Status<TH><TH>Pesquisador';
			while ($line = db_read($rlt))
				{
					$id++;
					$sx .= $cap->mostra_captacao_coordenador_lista($line);
					$sx .= '<TD>';
					$sx .= '<A HREF="captacaoes_validar_coordenador.php?dd0='.$line['id_ca'].'&dd90='.checkpost($line['id_ca']).'" class="botao-geral">';
					$sx .= '<font color="white">validar</font>';
					$sx .= '</A>';
					$ln = $line;					
				}
			if ($id == 0)
				{ $sx .= '<TR><TD colspan=10 class="lt4"><font color="red">Nenhum projeto para validar</font>'; }
			$sx .= '</table>';				
			
			return($sx);
		}
		
		function coordenador_do_professor($professor)
			{
			$sql = "
					select * from programa_pos_docentes
						inner join programa_pos on pdce_programa = pos_codigo
						inner join pibic_professor on pp_cracha = pos_coordenador
					where pp_cracha = '$professor'						
			 ";
			 $rlt = db_query($sql);
			 $email = array();
			 while ($line = db_read($rlt))
			 	{
			 		$email1 = trim($line['pp_email']);
					if (strlen($email1) > 0) { array_push($email,trim($email1)); }
					$email1 = trim($line['pp_email_1']);
					if (strlen($email1) > 0) { array_push($email,trim($email1)); }			 		
			 	}
			return($email);
			}		
		
		function enviar_email_coordenador()
			{
				global $ss,$email_adm,$http;
				require_once("../_class/_class_ic.php");
				$ic = new ic;
				$ic = $ic->ic("IC_CAP_CAD");
				
				$prof = new docentes;
				$prof->le($this->professor);
				
				$assunto = $ic['nw_titulo'];
				$texto = $ic['nw_descricao'];
				
				$texto .= $prof->mostra_dados(0);
				$texto .= $this->mostra();
				$texto .= '<BR><BR><font style="font-size:8px">'.'IC_CAP_CAD</font>';

				$email = $this->coordenador_do_professor($ss->user_cracha);

				$email = array();
				array_push($email,'monitoramento@sisdoc.com.br');
				array_push($email,$email_adm);
				
				/* formata corpo */
				$text = $texto;
				$text = '<table width="640" border=0><TR><TD>'.$text;
				$font = '<font style="font-family: "Arial Narrow", Arial, Tahoma; font-size: 13px;">';
				$text = '<img src="'.$http.'img/email_cip_header.png" width="640"><BR>'.$text;
				$text .= '<BR><BR><BR><img src="'.$http.'img/email_cip_foot.png" width="640">';
				$text .= '</table>';
				
				for ($r=0;$r < count($email);$r++)
				{
					enviaremail($email[$r],'',$assunto,$text);	
				}
				echo '<BR><BR>';
				echo $this->mostra();
				echo '<H2>Projeto cadastrado/alterado com sucesso</h2>';
				exit;
				
			}
		

		function captacao_total_professor($professor='',$tipo='')
			{
				$sql = "update ".$this->tabela." set ca_academico = '1' where ca_academico isnull ";
				$rlt = db_query($sql);
				
				$sql = "select count(*) as total, 
					sum(round(ca_proponente_vlr)) as proponete,
					sum(round(ca_vlr_total)) as valor, 
					min(ca_vigencia_ini_ano) as ano
				
					from ".$this->tabela." 
					where ca_professor = '".$professor."' 
					and ca_status <> 9 
					";
				if ($tipo == '1')
					{
						$sql .= " and (ca_academico = '1' or (ca_academico isnull) )";
					}
				if ($tipo == '2')
					{
						$sql .= " and ca_insticional = '1' ";
					}
				$rlt = db_query($sql);
				$line = db_read($rlt);
				return(array($line['total'],$line['proponete'],$line['valor'],$line['ano']));
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
				
				'1'=>'Com bonificação',
				'2'=>'Sem bonificação',
				
				'11'=>'Com bonificação e com isenção',
				'12'=>'Com bonificação e sem isenção',
				'13'=>'Sem bonificação e com isenção',
				'14'=>'Sem bonificação e sem isenção',
				
				'89'=>'Processo finalizado',
				
				'8'=>'Devolvido ao professor para correções',			
				'9'=>'Cancelado');
				return($ar);
			}
		

		function captacao_geral($dd1=190001,$tp='001',$tp2=0)
			{
				$comp = '';
				$cpo = 'centro_nome';
				if ($tp == '001') { $cpo = 'centro_nome'; }
				
				$ano1 = substr($dd1,0,4);
				$wh = '';
				if ($tp2==1) { $wh .= " and ca_agencia <> 'EMPRE' "; }
				if ($tp2==2) { $wh .= " and ca_agencia = 'EMPRE' "; }
				
				$sql = "select *
						 from ".$this->tabela." 
						 left join pibic_professor on ca_professor = pp_cracha
						 left join centro on centro_codigo = pp_escola
						 $comp
						where ca_vigencia_ini_ano <= $ano1 
						and ca_vigencia_fim_ano >= $ano1
						and (ca_status = 98 or ca_status = 1 or ca_status = 80 or ca_status = 81)
						and ca_participacao = 'C' 
						$wh
						order by $cpo
					";

				if ($tp == '002') {
					$cpo = 'pos_nome';
					$sql = "select * from (select pp_cracha, pp_nome, pos_nome, pp_escola
						 	from programa_pos_docentes 
						 	inner join programa_pos on pdce_programa = pos_codigo 
						 	left join pibic_professor on pdce_docente = pp_cracha
						 	group by $cpo, pp_cracha, pp_nome, pos_nome, pp_escola
						 	order by $cpo, pp_nome) as tabela01
						 	inner join ".$this->tabela." on pp_cracha = ca_professor
						 	left join centro on centro_codigo = pp_escola
							where ca_vigencia_ini_ano <= $ano1 
							and ca_vigencia_fim_ano >= $ano1
							and (ca_status = 98  or ca_status = 1 or ca_status = 80 or ca_status = 81)
							and ca_participacao = 'C' 
							$wh
							order by $cpo
						 	";					
					}
	
					
				$rlt = db_query($sql);
				$sa = '<table class="lt0">';
				$sa .= '<TR><TH>'.msg("escola");
				$sa .= '<TH>'.msg("pesquisador");
				$sa .= '<TH>'.msg("fomento");
				$sa .= '<TH>'.msg("descricao");
				$sa .= '<TH>'.msg("convenio_contrato");
				$sa .= '<TH>'.msg("edital");
				$sa .= '<TH>'.msg("edital_ano");
				$sa .= '<TH>'.msg("vig_ini");
				$sa .= '<TH>'.msg("vig_fim");
				$sa .= '<TH>'.msg("prorro");
				$sa .= '<TH>'.msg("vlr_total");
				$sa .= '<TH>'.msg("vlr_capt");
				$sa .= '<TH>'.msg("vlr_cust");
				$sa .= '<TH>'.msg("vlr_bols");
				$sa .= '<TH>'.msg("vlr_outr");
				$sa .= '<TH>'.msg("vlr_pucpr");
				$sa .= '<TH>'.msg("status");
				$sb = '<table class="lt2">';
				$sb .= '<TR><TH>'.msg($cpo).'<TH>'.msg('quant').'<TH>'.msg('total');
				$capx = 'x';
				$tot = 0;
				$tot1 = 0;
				$tot2 = 0;
				$tot3 = 0;
				while ($line = db_read($rlt))
					{
						$cap = $line[$cpo];
						if ($cap != $capx)
							{
								if ($tot1 > 0)
								{
									$sb .= '<TR><TD>'.$capx;
									$sb .= '<TD align="center">'.$tot2;
									$sb .= '<TD align="right">'.number_format($tot1,2,',','.');
								}
								$sa .= '<TR><TD class="lt2" colspan=10><B>'.$cap;
								$capx = $cap;
								$tot1 = 0;
								$tot2 = 0;
							}
						//print_r($line);
						//echo '<HR>';
						$vlr = $line['ca_proponente_vlr'];
						if ($vlr == 0) { $vlr = $line['ca_vlr_total']; }
						$tot = $tot + $vlr;
						$tot1 = $tot1 + $vlr;
						$tot2++;
						$tot3++;
						
						$sa .= '<TR>';
						$sa .= '<TD>';
						$sa .= $line['centro_nome'];						
						$sa .= '<TD>';
						$sa .= $line['pp_nome'];
						$sa .= '<TD>';
						$sa .= $line['ca_agencia'];
						$sa .= '<TD>';
						$sa .= $line['ca_descricao'];
						$sa .= '<TD>';
						$sa .= $line['ca_processo'];
						$sa .= '<TD>';
						$sa .= $line['ca_edital_nr'];
						$sa .= '<TD>';
						$sa .= $line['ca_edital_ano'];
						$sa .= '<TD>';
						$sa .= strzero($line['ca_vigencia_ini_mes'],2);
						$sa .= '-';
						$sa .= $line['ca_vigencia_ini_ano'];
						$sa .= '<TD>';
						$sa .= strzero($line['ca_vigencia_fim_mes'],2);
						$sa .= '-';
						$sa .= $line['ca_vigencia_fim_ano'];
						$sa .= '<TD>';
						$sa .= $line['ca_vigencia_prorrogacao'];
						$sa .= '<TD>';
						$sa .= number_format($line['ca_vlr_total'],2,',','.');
						$sa .= '<TD align="right">';
						$sa .= number_format($line['ca_vlr_capital'],2,',','.');
						$sa .= '<TD align="right">';
						$sa .= number_format($line['ca_vlr_custeio'],2,',','.');
						$sa .= '<TD align="right">';
						$sa .= number_format($line['ca_vlr_bolsa'],2,',','.');
						$sa .= '<TD>';
						$sa .= number_format($line['ca_vlr_outros'],2,',','.');
						//$sa .= '<TD>';
						//$sa .= $line['ca_proponente'];
						$sa .= '<TD align="right">';
						$sa .= number_format($vlr,2,',','.');
						$sa .= '<TD>';
						$sa .= $line['ca_status'];
						$sa .= '<TD>';
						$sa .= $line['ca_titulo_projeto'];
	
					}
				$sb .= '<TR><TD>'.$capx;
				$sb .= '<TD align="center">'.$tot2;
				$sb .= '<TD align="right">'.number_format($tot1,2,',','.');
				$sb .= '<TR><TD>'.msg('total_geral');
				$sb .= '<TD align="center">'.$tot3;
				$sb .= '<TD align="right"><B>'.number_format($tot,2,',','.');
				$sb .= '</table>';
				$sa .= '</table>';
				
				$sa = $sb.'<BR><BR>'.$sa;
				return($sa);
				
			}

		function captacao_agencia_ano($dd1=19000101,$dd2=20990101,$tp=1,$detalhe=0,$agencia='')
			{
				$stt = $this->status();
				$anoi = substr($dd1,0,4);
				$anof = substr($dd2,0,4);
				if (strlen($agencia) > 0)
					{ $where_age = " and (ca_agencia = '$agencia') "; }
				$sql = "select sum(round(ca_proponente_vlr)) as ca_proponente_vlr,
					 		sum(round(ca_vlr_total)) as ca_vlr_total,
					 		count(*) as projetos,
				 			ca_agencia
						 from ".$this->tabela." 
						where ca_vigencia_ini_ano >= $anoi 
						and ca_vigencia_ini_ano <= $anof
						and (ca_status = 98 or ca_status = 1 or ca_status = 80 or ca_status = 81)
						and ca_participacao = 'C' 
						group by ca_agencia
					order by ca_proponente_vlr
					";
					$rlt = db_query($sql);
					$dd1 = '';
					while ($line = db_read($rlt))
					{
						$nome = $line['ca_agencia'];
						$valor = $line['ca_proponente_vlr'];
						$dd1 .= ','.chr(13).chr(10)."['$nome',$valor]";
						$sr .= '<TR '.coluna().'>';
						$sr .= '<TD>'.$nome;
						$sr .= '<TD align="right">'.number_format($valor,2,',','.');
					}
					
					$sx = '
					    <script type="text/javascript" src="http://www.google.com/jsapi"></script>
    					<script type="text/javascript">
      					google.load(\'visualization\', \'1\', {packages: [\'corechart\']});
    					</script>
    					<script type="text/javascript">
      						function drawVisualization() {
        				// Create and populate the data table.
        				var data = google.visualization.arrayToDataTable([
          					[\'Agencia\', \'Hours per Day\']'.$dd1.'
        				]);
      
			        // Create and draw the visualization.
        			new google.visualization.PieChart(document.getElementById(\'visualization\')).
            		draw(data, {title:"Fomento a Pesquisa - Financiadores '.$anoi.'-'.$anof.'"});
      				}
	      		      google.setOnLoadCallback(drawVisualization);
    			</script>
				<div id="visualization" style="width: 600px; height: 400px;"></div>					
				';
				$sr = '<table width="500">'.$sr.'</table>';
				
				echo $sx;
				echo $sr;
	
			}

		function mostra_captacao_row($line)
			{
				$stt = $this->status();
				$sx .= '';
				$sx .= '<TR '.coluna().'>';
				$sx .= '<TD>'.$line['ca_agencia'];
				$sx .= '<TD>'.$line['ca_professor'];
				$sx .= '<TD>'.$line['pp_nome'];
				$sx .= '<TD>'.trim($line['ca_edital_nr']).'/'.trim($line['ca_edital_ano']);
				$sx .= '<TD align="right">'.number_format($line['ca_vlr_total'],2,',','.');
				$sx .= '<TD align="right">'.number_format($line['ca_proponente_vlr'],2,',','.');				
				$sx .= '<TD>'.$line['ca_vigencia_ini_ano'];
				$sx .= '<TD>'.$line['ca_vigencia_fim_ano'];
				$sx .= '<TD>'.$stt[$line['ca_status']];
				$sx .= '<TD>'.$line['ca_status'];
				return($sx);
			}
			
			
		function projetos_vigentes_inicio($dd1=19000101,$dd2=20990101,$tp=1,$detalhe=0,$agencia='',$status='',$programa='',$institut=0)
			{
				$wh = 'and (ca_status = 98 or ca_status = 1 or ca_status = 80 or ca_status = 81 or ca_status > 10)';
				if ($status == '2') { $wh = 'and (ca_status <> 9)'; }
				if ($status == '2') { $wh = 'and (ca_status = 98 or ca_status = 81 or ca_status = 80)'; }
				if ($status == '1') { $wh = 'and (ca_status <> 9)'; }
				if ($status == '')  { $wh = 'and (ca_status = 10 or ca_status = 1 or ca_status = 80 
												or ca_status = 81  or ca_status > 50) '; }
				/* da captação total, retira os projetos desmembrados */
				if ($tp == 1)
					{ $wh .= " and (ca_desmembramento <> '1' or ca_desmembramento isnull) "; }
				
				//$wh = 'and (ca_status <> 9)';
				/* Detalhes */
				$sd = '';
				
				$stt = $this->status();
				
				$anoi = substr($dd1,0,4);
				//$anoi = 2008;
				$anof = substr($dd2,0,4);
				if (strlen(trim($programa)) > 0)
					{
						$tables_programas = '
						inner join programa_pos_docentes on ca_professor = pdce_docente and pdce_ativo = 1
						inner join programa_pos on pos_codigo = pdce_programa
						';
						// 
						//
						//inner join pibic_professor on pp_cracha = pdce_docente
						$where_age = " and (pos_codigo = '".$programa."')";					
					} else {
						if ($institut==1)
						{
							$institucional = " and (ca_insticional = '1') ";
						} else {
							$institucional = "";
						}
					}
				
				if (strlen($agencia) > 0)
					{ $where_age = " and (ca_agencia = '$agencia') "; }
				$sql = "select round(ca_proponente_vlr*100)/100 as ca_proponente_vlr,

							    round(ca_vlr_capital*100)/100 as ca_vlr_capital,
							    round(ca_vlr_custeio*100)/100 as ca_vlr_custeio,
							    round(ca_vlr_bolsa*100)/100 as ca_vlr_bolsa,
							    round(ca_vlr_outros*100)/100 as ca_vlr_outros,
							    ca_protocolo,
							    agf_nome, agf_codigo, ca_agencia_gr, agf_sigla,				
				
					 			round(ca_vlr_total*100)/100 as ca_vlr_total,
					 			ca_vigencia_ini_ano,
					 			ca_vigencia_fim_ano,
					 			ca_status,
					 			ca_agencia, ca_professor, ca_edital_nr, ca_edital_ano,
					 			pp_nome
						 from ".$this->tabela." 
					left join pibic_professor on ca_professor = pp_cracha
					$tables_programas
					left join agencia_de_fomento on agf_codigo = ca_agencia_gr
					where ca_vigencia_ini_ano >= $anoi 
					and ca_vigencia_ini_ano <= $anof
					$wh
					and (ca_participacao = 'C' or ca_participacao = 'E' or ca_participacao = 'P' or ca_participacao = '' or ca_participacao isnull )
					$institucional 
					$where_age
					order by pp_nome, ca_protocolo, ca_vigencia_ini_ano,pp_nome
					";
					
					//echo '<PRE>'.$sql.'</PRE>';
					//echo '<HR>'.$tp.'<HR>';
	
				$tot1 = array();
				$tot2 = array();
				$tot3 = array();
				$tot4 = array();
				
				$tot10 = array();
				$tot11 = array();
				$tot12 = array();
				$tot13 = array();
				
				array_push($tot10,0);
				array_push($tot11,0);
				array_push($tot12,0);
				array_push($tot13,0);				
				
				for ($r=$anoi;$r <= $anof;$r++)
					{
						array_push($tot1,$r);
						array_push($tot2,0);
						array_push($tot3,0);
						array_push($tot4,0);
					}
				$rlt = db_query($sql);
				$st = '';
				
				$of = 0;
				$ofi = 0;
				$emp = 0;
				$empi = 0;
				$og = 0;
				$ogi = 0;
				$nc = 0;
				
				$vof = 0;
				$vofi = 0;
				$vemp = 0;
				$vempi = 0;
				$vog = 0;
				$vogi = 0;
				$vnc = 0;		
				
				$xprof = 'x';		
				$xprotoo = '';
				while ($line = db_read($rlt))
					{
						$proto = $line['ca_protocolo'];
						if ($xproto != $proto)
						{
						$xproto = $proto;
						$prof = trim($line['pp_nome']);
						if ($prof != $xprof)
							{
								$sd .= '<TR><TD colspan=10><B>'.$line['pp_nome'].'</B>';
								$xprof = $prof;
							}
						$sd .= '<TR><TD>'.$line['ca_protocolo'];
						$sd .= '<TD>'.$line['ca_vigencia_ini_ano'];
						$sd .= '<TD>'.$line['ca_vigencia_fim_ano'];
						$sd .= '<TD>'.$line['ca_proponente_vlr'];
						if ($line['ca_vlr_total'] > $line['ca_proponente_vlr'])	
							{ $cor = '<font color="blue">'; } else { $cor = ''; }
						$sd .= '<TD>'.$cor.$line['ca_vlr_total'];
						$sd .= '<TD>'.$line['ca_status'];
						//print_r($line);
						//echo '<HR>';
						/* Tipo de Agencias */
						$ag = trim($line['agf_sigla']);
						$vl = round(trim($line['ca_proponente_vlr'])/1000);
						switch ($ag)
							{
								case ('OF'): $of = $of + 1; $vof = $vof + $vl; break;
								case ('OFI'): $ofi = $ofi + 1; $vofi = $vofi + $vl; break;
								case ('EMP'): $emp = $emp + 1; $vemp = $vemp + $vl; break;
								case ('EMPI'): $empi = $empi + 1; $vempi = $vempi + $vl; break;
								case ('OG'): $og = $og + 1; $vog = $vog + $vl; break;
								case ('OGI'): $ogi = $ogi + 1; $vogi = $vogi + $vl; break;
							}
						
						$ano1 = round(substr($line['ca_vigencia_ini_ano'],0,4));
						$ano2 = round(substr($line['ca_vigencia_fim_ano'],0,4));
						$tota = round($line['ca_proponente_vlr']);
						$totb = round($line['ca_vlr_total']);
						
						$totx1 = round($line['ca_vlr_capital']);
						$totx2 = round($line['ca_vlr_custeio']);
						$totx3 = round($line['ca_vlr_bolsa']);
						$totx4 = round($line['ca_vlr_outros']);
						
						/* Metodologia 1 */
						if ($tp==1)
						{
							for ($r=$ano1;$r <= $ano2;$r++)
								{
									$pos = $r - $anoi;
									//echo '<BR><font class="lt0">'.$ano1.'-'.$pos.'-'.$tota.'-'.$totb;
									$tot2[$pos] = $tot2[$pos] + $totb; 
									$tot3[$pos] = $tot3[$pos] + $tota; 
									$tot4[$pos] = $tot4[$pos] + 1;
								}
						}
						/* Metodologia 3 */
						if ($tp==3)
						{
							$totb1 = $totb / (($ano2-$ano1)+1);
							$tota1 = $tota / (($ano2-$ano1)+1);
														
							for ($r=$ano1;$r <= $ano2;$r++)
								{
									$pos = $r - $anoi;
									//echo '<BR><font class="lt0">'.$ano1.'-'.$pos.'-'.$tota.'-'.$totb;
									$tot2[$pos] = $tot2[$pos] + $totb1; 
									$tot3[$pos] = $tot3[$pos] + $tota1; 
									$tot4[$pos] = $tot4[$pos] + 1;								
								}
						}						
						/* Metodologia 1 */
						if ($tp==2)
						{
							$pos = $ano1 - $anoi;
							$tot2[$pos] = $tot2[$pos] + $totb; 
							$tot3[$pos] = $tot3[$pos] + $tota; 
							$tot4[$pos] = $tot4[$pos] + 1;
						}
						/* Rubricas */
						$tot10[0] = $tot10[0] + $totx1;
						$tot11[0] = $tot11[0] + $totx2;
						$tot12[0] = $tot12[0] + $totx3;
						$tot13[0] = $tot13[0] + $totx4;						
						
						$st .= $this->mostra_captacao_row($line);
						}

					}
					$width = round(100/(2+($anof-$anoi)));
					
					$sx = '<table class="tabela00" width="100%">';
					$sh = '<TR><TH width="22%">Ano';
					$sh .= '<TH width="26%">Total dos recursos';
					$sh .= '<TH width="26%">Aplicado na PUCPR';
					if ($tp == 1) { $sh .= '<TH width="26%">Projetos ativos'; }
					if ($tp == 2) { $sh .= '<TH width="26%">Projetos iniciados'; }
					$sd1 = '';
					$sd2 = '';
					$sd3 = '';
					$sd4 = '';
					$sdtit = '';
					for ($r=0;$r < count($tot1);$r++)
						{
							$s1 .= '<TR>';
							$s1 .= '<TD align="center" class="tabela01">';
							$s1 .= $tot1[$r];							

							$s1 .= '<TD align="right" class="tabela01">';
							$s1 .= number_format($tot2[$r],2,',','.');							

							$s1 .= '<TD align="right" class="tabela01">';
							$s1 .= number_format($tot3[$r],2,',','.');							

							$s1 .= '<TD align="right" class="tabela01" align="center">';
							$s1 .= number_format($tot4[$r],0,',','.');
							$sd1 .= ",".round($tot3[$r])."";
							$sdtit .= ",'".($anoi+$r)."'";	
							if (strlen($sd2) > 0) { $sd2 .= ', '.chr(13); }
							if (strlen($sd3) > 0) { $sd3 .= ', '.chr(13); }
							$sd3 .= '["'.($anoi+$r).'", '.round($tot3[$r]/1000).', '.round(($tot2[$r]-$tot3[$r])/1000).'] ';	
							$sd2 .= '["'.($anoi+$r).'", '.round($tot3[$r]/1000).', '.round($tot3[$r]/1000).'] ';
						}
					$sx .= $sh.$s1;
					$sx .= '</table>';

					$sd4 .= '["Capital", '.round($tot10[0]/1000).'], '.chr(13);					
					$sd4 .= '["Custeio", '.round($tot11[0]/1000).'], '.chr(13);					
					$sd4 .= '["Bolsas", '.round($tot12[0]/1000).'], '.chr(13);					
					$sd4 .= '["Serviços/Consultoria/Acessoria", '.round($tot13[0]/1000).'] '.chr(13);					
					
					/* Grafico do Google */
					$sg .= '
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
	  	  
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          [\'Captação (Ano)\', \'Proponente PUCPR\',\'Tendência\'],
          '.$sd2.'
        ]);
		

		

        var options = {
          title : \'Captação na instituição\',
          vAxis: {title: "Captação (em mil reais)"},
          hAxis: {title: "Anos"},
          seriesType: "bars",
          series: {1: {type: "line"}}
        };

        var chart = new google.visualization.ComboChart(document.getElementById(\'chart_div\'));
        chart.draw(data, options);
      }
    </script>
    <div id="chart_div" style="width: 1024px; height: 500px;"></div>
					';										
					
					/* 
					 * 
					 * Grafico do Google 
					 * 
					 * 
					 * */
					$sg2 .= '
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          [\'Captação (Ano)\', \'Proponente PUCPR\',\'Captação total\'],
          '.$sd3.'
        ]);

        var options = {
          title: "Participação da PUCPR nos projetos de captação",
          vAxis: {title: "Captação (em mil reais)"},
          isStacked: true
        };

        var chart = new google.visualization.SteppedAreaChart(document.getElementById(\'chart_div_2\'));
        chart.draw(data, options);
      }
    </script>
    <div id="chart_div_2" style="width: 350px; height: 200px;"></div>
					';										

					/* 
					 * 
					 * Grafico do Google 
					 * 
					 * 
					 * */
		$sg3 .= '
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          [\'Tipo\', \'Valor total\'],
          '.$sd4.'
        ]);

		var options = {
          title: \'Valor das Rubricas de Captação\'
        };


        var chart = new google.visualization.PieChart(document.getElementById(\'chart_div_3\'));
        chart.draw(data, options);
      }
    </script>
    <div id="chart_div_3" style="width: 350px; height: 200px;"></div>
					';										

					/* 
					 * 
					 * Grafico do Google 
					 * 
					 * 
					 * */
		$sg4 .= '
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          [\'Tipo\', \'Total de projetos\'],
          ["Agência de Fomento", '.$of.'],
          ["Agência de Fomento Internacional", '.$ofi.'],
          ["Orgão Governamental", '.$og.'],
          ["Orgão Governamental (Internacional)", '.$ogi.'],
          ["Empresa", '.$emp.'],
          ["Empresa Internacional", '.round($empi).']
        ]);

		var options = {
          title: \'Projetos por fonte dos recursos\'
        };


        var chart = new google.visualization.PieChart(document.getElementById(\'chart_div_4\'));
        chart.draw(data, options);
      }
    </script>
    <div id="chart_div_4" style="width: 350px; height: 200px;"></div>
					';										

		$sg5 .= '
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          [\'Tipo\', \'Valor proponente\'],
          ["Agência de Fomento", '.$vof.'],
          ["Agência de Fomento Internacional", '.$vofi.'],
          ["Orgão Governamental", '.$vog.'],
          ["Orgão Governamental (Internacional)", '.$vogi.'],
          ["Empresa", '.$vemp.'],
          ["Empresa Internacional", '.round($vempi).']
        ]);

		var options = {
          title: \'Projetos por fonte dos recursos (em mil R$)\',
          colors:["#6060FF","#0000FF","green","#C0FFC0","red","#FFC0C0","#FFC0C0"]
        };


        var chart = new google.visualization.PieChart(document.getElementById(\'chart_div_5\'));
        chart.draw(data, options);
      }
    </script>
    <div id="chart_div_5" style="width: 350px; height: 200px;"></div>
					';	
					
	$this->sg2 = $sg2;
	$this->sg3 = $sg3;
	$this->sg4 = $sg4;
	$this->sg5 = $sg5;
	$this->sg6 = $sg6;
	$this->sg7 = $sg7;
	
							
					$sx = $sg . $sx;
					echo $sx;
					echo $sc;
					if ($detalhe==1)
						{
							echo '<HR>';
							echo '<table border=1>';
							echo $sd;
							echo '</table>';
							echo '<HR>';
						}
			}
		function cp_obs_diretoria()
			{
				$cp = array();
				array_push($cp,array('$H8','id_ca','',False,True));	
				array_push($cp,array('$T60:3','ca_obs_adm','Obs Administrativa',False,True));
				return($cp);
			}
		function cp()
			{
				global $dd;
				//$sql = "alter table ".$this->tabela." add column ca_escola char(7) ";
				//$rlt = db_query($sql);
				$sql = "update ".$this->tabela." set ca_professor = '00000000' where ca_protocolo = '0000605' ";
				$rlt = db_query($sql);
				
				$this->atualiza_vigencia();
				$op = ' : ';
				for ($r=2006;$r <= (date("Y")+10);$r++)
					{
						for ($y=1;$y <= 12;$y++)
						{
							$op .= '&'.trim(strzero($r,4).strzero($y,2)).':'.strzero($y,2).'/'.$r; 
						} 
					}
					
					
				/* Duração */
				$oo = ' 0:não aplicado';
				for ($r=1;$r < 8 * 12;$r++) { $oo .= '&'.trim($r).':'.$this->vigencia($r); }
				
				
				/** Status **/
				$sss = $this->status();
				$ops = ' 0:'.$sss[0];
				for ($r=1;$r<=99;$r++)
					{
						$ct = trim($sss[$r]);
						if (strlen($ct) > 0)
							{ $ops .= '&'.$r.':'.$sss[$r]; }					
					}
				global $dd,$acao;
				
				$cp = array();
				array_push($cp,array('$H8','id_ca','',False,True));
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
				
				array_push($cp,array('$O C:Coordenador&O:Colaborador&P:Coordenador na Instituição&E:Coordenador no Programa/Escola','ca_participacao','Participação',False,True));
				
				array_push($cp,array('${','','Descrição',False,True));
				array_push($cp,array('$T60:4','ca_titulo_projeto','Título do projeto',False,True));
				
				array_push($cp,array('$Q agf_nome:agf_codigo:select * from agencia_de_fomento where agf_ativo = 1 order by agf_ordem, agf_nome','ca_agencia','Financiador',True,True));
				array_push($cp,array('$S250','ca_descricao','Nome do edital',True,True));
				array_push($cp,array('$S10','ca_edital_nr','Edital Número',True,True));
				array_push($cp,array('$[2004-'.date("Y").']','ca_edital_ano','Edital Ano',True,True));
				array_push($cp,array('$S20','ca_processo','Processo/Convênio*',True,True));
				array_push($cp,array('$M','','<font class=lt0>* informe "NA" caso não se aplique.</font>',False,True));
				
				array_push($cp,array('$}','','Descrição',False,True));
				
				array_push($cp,array('${','','Recursos',False,True));
				array_push($cp,array('$N8','ca_vlr_total','Valor total',True,True));
				array_push($cp,array('$N8','ca_vlr_capital','Valor de capital',True,True));
				array_push($cp,array('$N8','ca_vlr_custeio','Valor de custeio',True,True));
				array_push($cp,array('$N8','ca_vlr_bolsa','Valor de bolsa',True,True));
				array_push($cp,array('$N8','ca_vlr_Outros','Outros valores',True,True));
				array_push($cp,array('$}','','Recursos',False,True));
				
				array_push($cp,array('${','','Vigência',False,True));
				array_push($cp,array('$O '.$op,'ca_vigencia_final_ano','Início da vigência',True,True));
				array_push($cp,array('$O '.$oo,'ca_duracao','Duração',True,True));
				array_push($cp,array('$O '.$oo,'ca_vigencia_prorrogacao','Renovação',True,True));
				array_push($cp,array('$}','','Vigência',False,True));
				
				array_push($cp,array('${','','Instituição Proponente',False,True));
				//array_push($cp,array('$Q ','ca_duracao','Duração',True,True));
				array_push($cp,array('$Q inst_nome:inst_codigo:select inst_codigo, substring(trim(inst_nome) from 1 for 50) || chr(47) || trim(inst_abreviatura) as inst_nome from instituicoes where inst_abreviatura <> \'\' order by inst_nome','ca_proponente','Instituição proponente',True,True));				
				array_push($cp,array('$N8','ca_proponente_vlr','Valor aplicado na PUCPR',True,True));
				array_push($cp,array('$M','','<font class=lt0>O valor aplicado refere-se a quantidade de recursos que serão aplicados na PUCPR, podendo ser qualquer uma das modalidades, capital, custeio ou bolsas, informando qual o valor total.</font>',False,True));
				array_push($cp,array('$}','','Vigência',False,True));
				
				array_push($cp,array('$Q agf_nome:agf_codigo:select * from agencia_de_fomento where agf_ativo = 2 order by agf_ordem, agf_nome','ca_agencia_gr','Financiador Tipo',True,True));
				
				
				array_push($cp,array('$O '.$ops,'ca_status','Status',True,True));
				array_push($cp,array('$T60:3','ca_contexto','Obs',False,True));
				
				array_push($cp,array('$T60:3','ca_obs_adm','Obs Administrativa',False,True));
				array_push($cp,array('$C8','ca_prj_boni','Bonificado',False,False));
				array_push($cp,array('$C8','ca_prj_isen','Isentado',False,False));
				
				array_push($cp,array('$H8','ca_protocolo','',False,True));	
				
				array_push($cp,array('$C8','ca_insticional','Institucional',False,True));
				array_push($cp,array('$C8','ca_academico','Acadêmico',False,True));
				array_push($cp,array('$C8','ca_empresa','Empresa',False,True));
				array_push($cp,array('$C8','ca_proj_pesq','Projeto de pesquisa',False,True));
				array_push($cp,array('$C8','ca_internacional','Internacional ',False,True));
				array_push($cp,array('$C8','ca_multi','Multiinstitucional',False,True));
				array_push($cp,array('$C8','ca_multi_descricao','Multiinstitucional (detalhes)',False,True));
				
				
				array_push($cp,array('$S8','ca_vinculo','Vinculo de projeto institucional (protocolo)',False,True));
				
				array_push($cp,array('$C','ca_desmembramento','Desmembramento de Projeto de Coordenação Institucional (Recursos para infraestrutura, entre outros)',False,True));
				$ag .= ' : ';
				$ag .= '&A:Orgão Governamental (repasse de recursos via or. Gov. ou Fomente)';
				$ag .= '&E:Empresa';
				$ag .= '&G:Orgão de Fomento (repasse de recursos via or. Gov. ou Fomente)';
				
				$ag .= '&B:Orgão Governamental (Internacional)';
				$ag .= '&F:Empresa  (Internacional)';
				$ag .= '&H:Orgão de Fomento (Internacional)';					
				
				array_push($cp,array('$O '.$ag,'ca_tipo_fomento','Categorização do fomento (origem dos recursos)',False,True));		
				array_push($cp,array('$Q centro_nome:centro_codigo:select * from centro where centro_ativo = 1 order by centro_nome','ca_escola','Vinculo a escola',True,True));
				array_push($cp,array('$Q pos_nome:pos_codigo:select * from programa_pos where pos_ativo = 1 order by pos_nome','ca_programa','Vinculo ao programa',True,True));
				return($cp);
			}		
		
		function alterar_status($sta)
			{
				$sql = "update ".$this->tabela."
					set ca_status = $sta
					where id_ca = ".round($this->id);
				$rlt = db_query($sql);
			}
		
		function mostra()	
			{
				//$this->updatex();
				$line = $this->line;
				$sta = $this->status();
				$sx .= '<fieldset><legend>'.msg("captacao").'</legend>';
				$sx .= '<table width="100%" class="lt0" border=0>';
				
				$sx .= '<TR><TD class="lt0" colspan=3>TITULO DO PROJETO';
				$sx .= '<TD class="lt0" colspan=3>Projeto';
				$sx .= '<TR><TD class="lt1" colspan=3><B>'.$this->titulo;
				$sx .= '	<TD class="lt1" colspan=1><B>'.$this->protocolo;
				//$sx .= '('.trim($this->status).')';
				
				$sx .= '<TR><TD>Participação';
				$sx .= '<TD>Edital nr. / Ano';
				$sx .= '<TD>Processo / Convênio';
				$sx .= '<TD>Status';
				
				$sx .= '<TR><TD class="lt1"><B>'.$this->participacao.'</B>';
				$sx .= '<TD class="lt1"><B>'.$this->edital_nr.' / '.$this->edital_ano.'</B>';
				$sx .= '<TD class="lt1"><B>'.$this->ca_processo;
				$sx .= '<TD class="lt1"><B><nobr>';
				$sx .= $sta[$this->status].' ('.$this->status.')';
				
				$sx .= '<TR><TD colspan=2>Financiador';
				$sx .= '<TD colspan=2>Vigência';
				$sx .= '<TR><TD class="lt1" colspan=2><B>'.$this->financiador.'</B>';
				$sx .= '<TD class="lt1" colspan=2><B>'.$this->vigencia.'</B>';

				$sx .= '<TR><TD colspan=2>Edital / nome do patrocinador';

				$sx .= '<TR><TD class="lt1" colspan=3><B>'.$this->edital.'</B>';
				
				$sx .= '<TR><TD colspan=2>Vinculado';
				$sx .= '<TR><TD colspan=2 class="lt1"><B>'.$line['pos_nome'].'</B>';
				
				$sx .= '<B>'.$line['centro_nome'].'</B>';

				if ($this->proj_inst==1)
					{
						$sx .= '<TD class="lt2"><font class="link">Projeto Institucional</font>';
					}

				$sx .= '</table>';
				$sx .= '</fieldset>';
				
				$sx .= '<fieldset><legend>'.msg("recursos").'</legend>';
				$sx .= '<table width="100%" class="lt0">';
				$sx .= '<TR><TD align="right">Valor total&nbsp;';
				$sx .= '<TD class="lt1"><B>'.number_format($this->total,2,',','.').'</B>';				

				$sx .= '<TR><TD align="right">Valor de capital&nbsp;';
				$sx .= '<TD class="lt1"><B>'.number_format($this->capital,2,',','.').'</B>';

				$sx .= '<TD align="right">Valor de custeio&nbsp;';
				$sx .= '<TD class="lt1"><B>'.number_format($this->custeio,2,',','.').'</B>';

				$sx .= '<TR><TD align="right">Valor de bolsa&nbsp;';
				$sx .= '<TD class="lt1"><B>'.number_format($this->bolsa,2,',','.').'</B>';
				
				$sx .= '<TD align="right">Outros valores&nbsp;';
				$sx .= '<TD class="lt1"><B>'.number_format($this->outros,2,',','.').'</B>';
								
				$sx .= '</table>';
				
				$sx .= '<table width="100%" class="lt0">';
				
				$sx .= '<TR><TD align="right">Instituição proponente';
				$sx .= '<TD class="lt1"><B>'.$this->instituicao.'</B>';

				$sx .= '<TR><TD align="right">Valores aplicado na instituição (PUCPR)&nbsp;';
				$sx .= '<TD class="lt1"><B>'.number_format($this->total2,2,',','.').'</B>';


				$sx .= '</table>';
				$sx .= '</fieldset>';				
				
				$sx .= '<fieldset><legend>'.msg("vigencia").'</legend>';
				$sx .= '<table width="100%" class="lt0" border=0>';
				$sx .= '<TR><TD class="lt0" align="right">início: <font class="lt1"><B>';
				$sx .= $this->inicio.'</font>';
				$sx .= '<TD class="lt0" align="right">duração: <font class="lt1"><B>';
				$sx .= $this->duracao.'</font>';
				$sx .= '<TD class="lt0" align="right">prorrogação: <font class="lt1"><B>';
				$sx .= $this->prorrogacao.'</font>';
				$sx .= '</table>';
				$sx .= '</fieldset>';				

				$sx .= '<fieldset><legend>'.msg("sobre_o_projeto").'</legend>';
				$sx .= '<table width="100%" class="lt0">';
				$sx .= '<TR><TD class="lt1"><font class="lt0">sobre o projeto</font><BR>';
				$sx .= '<TR><TD class="lt1" height=20><B>'.$this->contexto.'</B>';
				$sx .= '<TR><TD class="lt1"><BR><font class="lt0">comentários do coordenador</font><BR>';				
				$sx .= '<TR><TD class="lt1" height=20><B>'.$this->comentario.'</B>';
				$sx .= '<TR><TD class="lt1"><BR><font class="lt0">comentários da direção de pesquisa</font><BR>';				
				$sx .= '<TR><TD class="lt1" height=20><B>'.$this->comentario_direcao.'</B>';
				
				/* Comentários administração */
				$sa = $this->comentario_adm;
				if (strlen($sa) > 0)
					{
						$sx .= '<TR><TD class="lt1"><BR><font class="lt0">comentários administrativos</font><BR>';				
						$sx .= '<TR><TD class="lt1" height=20><B>'.mst($sa).'</B>';						
					}
				$sx .= '<BR>'.$this->editar_observacao_diretoria();	
				$sx .= '</table>';
				$sx .= '</fieldset>';				
				return($sx);
			}
		function editar_observacao_diretoria()
			{
				global $ss,$perfil;
				$sa = ''; 	
				if (($perfil->valid('#ADM#SCR#COO')))
					{
						$sa = '<A href="#" onclick="newxy2(\'captacao_editar_comentario_diretorio.php?dd0='.$this->id.'&dd90='.checkpost($this->id).'\',600,400);" class="link">';
						$sa .= 'editar comentários';
						$sa .= '</A>';
					}
				return($sa);
			}
		function le($id)
			{
				$sql = "select * from ".$this->tabela." 
						left join agencia_de_fomento on ca_agencia = agf_codigo
						left join instituicoes on ca_proponente = inst_codigo
						left join programa_pos on ca_programa = pos_codigo
						left join centro on ca_escola = centro_codigo
						left join pibic_professor on ca_professor = pp_cracha
						where id_ca = ".$id." ";
				$rlt = db_query($sql);
				
				if ($line = db_read($rlt))
					{
						$coa = trim($line['ca_participacao']);
						if ($coa == 'C') { $coa = 'Coordenador'; }
						if ($coa == 'O') { $coa = 'Colaborador'; }
						if ($coa == 'P') { $coa = 'Coordenador na Instituição'; }
						if ($coa == 'E') { $coa = 'Coordenador no Programa/Escola'; }
						
						$this->participacao = $coa;
						$this->id = $line['id_ca'];
						$this->professor = $line['ca_professor'];
						$this->professor_nome = $line['pp_nome'];
						$this->protocolo = $line['ca_protocolo'];
						$this->edital = $line['ca_descricao'];
						$this->edital_nr = $line['ca_edital_nr'];
						$this->edital_ano = $line['ca_edital_ano'];
						$this->ca_processo = $line['ca_processo'];
						$this->financiador = $line['agf_nome'];
						$this->total = $line['ca_vlr_total'];
						$this->capital = $line['ca_vlr_capital'];
						$this->custeio = $line['ca_vlr_custeio'];
						$this->bolsa = $line['ca_vlr_bolsa'];
						$this->outros = $line['ca_vlr_outros'];
						$this->total2 = $line['ca_proponente_vlr'];
						$this->instituicao = $line['inst_nome'];
						$this->status = $line['ca_status'];
						$this->titulo = trim($line['ca_titulo_projeto']);
						$this->valor_proponente = $line['ca_proponente_vlr'];
						$this->proj_inst = $line['ca_insticional'];
						
						$this->line = $line;
						
						$this->inicio = strzero($line['ca_vigencia_ini_mes'],2).'/'.$line['ca_vigencia_ini_ano'];
						$this->fim = strzero($line['ca_vigencia_fim_mes'],2).'/'.$line['ca_vigencia_fim_ano'];
						$this->duracao = $line['ca_duracao'] . ' mês(es)';
						$this->prorrogacao = $line['ca_vigencia_prorrogacao'];
						if ($this->prorrogacao == 0) { $this->prorrogacao = '-'; }
						
						$this->vigencia = $this->inicio.' - '.$this->fim;
						if ($line['ca_duracao'] > 0)
							{ $this->vigencia .= ' +'.$this->prorrogacao; }
						
						$this->contexto = $line['ca_contexto'];
						$this->comentario = $line['ca_comentario'];
						$this->comentario_direcao = $line['ca_comentario_direcao'];
						$this->comentario_adm = $line['ca_obs_adm'];
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
							
				array_push($cp,array('$H8','id_ca','',False,True));
				array_push($cp,array('$H8','ca_professor','',True,True));
				array_push($cp,array('$O '.$ops,'ca_status','Status',True,True));
				
				array_push($cp,array('$T60:3','ca_comentario','Comentário do coordenador',False,True));
				array_push($cp,array('$U8','ca_update','',True,True));
				return($cp);							
			}

		function cp_01()
			{
				global $dd,$acao,$ss,$nw;
				$user = $ss->user_cracha;
				$qsql = "select * from (select pdce_programa, pdce_docente from programa_pos_docentes group by pdce_programa, pdce_docente) as tabela inner join programa_pos on pdce_programa = pos_codigo where pdce_docente = '".$ss->user_cracha."' ";
				$qrlt = db_query($qsql);
				$opa = '';
				while ($line = db_read($qrlt))
				{
					if (strlen($opa) > 0) { $opa .= '&'; }
					$opa .= trim($line['pos_codigo']).':';
					$opa .= trim($line['pos_nome']);
				}
				$opa .= '& : --sem vinculo com a pós-graduação--';
				$op = ' : ';
				for ($r=date("Y");$r > 2001;$r--)
					{ for ($y=1;$y < 12;$y++) {
							$op .= '&'.trim(strzero($r,4).strzero($y,2)).':'.strzero($y,2).'/'.$r; 
						} }					
				/* Duração */
				$oo = ' 0:não aplicado';
				for ($r=1;$r < 8 * 12;$r++) { $oo .= '&'.trim($r).':'.$this->vigencia($r); }

				$cp = array();
				if ((strlen($dd[0])==0) and (strlen($acao)==0)) { $dd[4]='1'; }
				array_push($cp,array('$H8','id_ca','',False,True));
				
				array_push($cp,array('${','','<h3>Participação</h3>',False,True));
				
				$msg = 'Sua participação neste projeto de pesquisa, perante a instituição é de:';
				array_push($cp,array('$R C:Coordenador geral do projeto&P:Coordenador na Instituição&O:Colaborador&E:Coordenador no Programa/Escola','ca_participacao',$msg,True,True));
				
				array_push($cp,array('$A','','Perfil do projeto',False,True));
				array_push($cp,array('$C','ca_academico','Projeto Acadêmico (Projeto de pesquisa, eventos, entre outros)',False,True));
				array_push($cp,array('$C','ca_insticional','Projeto de Coordenação Institucional (Recursos para infraestrutura, entre outros)',False,True));
				array_push($cp,array('$C','ca_desmembramento','Desmembramento de Projeto de Coordenação Institucional (Recursos para infraestrutura, entre outros)',False,True));
				//array_push($cp,array('$C','ca_proj_pesq','Projeto de pesquisa (quando não está relacionado a bolsas, apoio a eventos)',False,False));
								
				array_push($cp,array('$A','','Descrição',False,True));
				array_push($cp,array('$T60:4','ca_titulo_projeto','Título do projeto',True,True));
				array_push($cp,array('$O '.$opa,'ca_programa','Programa de Pós:',False,True));
				
				array_push($cp,array('$A','','Vigência do projeto',False,True));
				array_push($cp,array('$O '.$op,'ca_vigencia_final_ano','Início da vigência',True,True));
				array_push($cp,array('$O '.$oo,'ca_duracao','Duração',True,True));
				array_push($cp,array('$O '.$oo,'ca_vigencia_prorrogacao','Renovação',True,True));
				
				array_push($cp,array('$C','ca_multi','Projeto Multinstitucional'));
				array_push($cp,array('$M','','Projeto que envolve mais de uma instituição de pesquisa, podendo ser nacional ou internacional'));
				array_push($cp,array('$C','ca_internacional','Projeto Internacional'));
				array_push($cp,array('$M','','Projeto com cooperação internacional'));

				array_push($cp,array('$A','','Financiador',False,True));
				array_push($cp,array('$Q agf_nome:agf_codigo:select * from agencia_de_fomento where agf_ativo = 2 order by agf_ordem, agf_nome','ca_agencia_gr','Financiador Tipo',True,True));
				array_push($cp,array('$Q agf_nome:agf_codigo:select * from agencia_de_fomento where agf_ativo = 1 order by agf_ordem, agf_nome','ca_agencia','Financiador',True,True));				
				array_push($cp,array('$S200','ca_descricao','Nome do Edital / Nome da empresa',True,True));
				array_push($cp,array('$S10','ca_edital_nr','Edital Número / Outro Documento',True,True));
				array_push($cp,array('$M','','<font class=lt0>* informe "NA" caso não se aplique.</font>',False,True));
				array_push($cp,array('$[2004-'.date("Y").']','ca_edital_ano','Edital Ano',True,True));
				array_push($cp,array('$S20','ca_processo','Processo/Convênio*',True,True));
				array_push($cp,array('$M','','<font class=lt0>* informe "NA" caso não se aplique.</font>',False,True));
				array_push($cp,array('$}','','Descrição',False,True));
				
				if ($dd[0]==0)
					{
						array_push($cp,array('$U8','ca_update',$user,True,True));
						array_push($cp,array('$HV','ca_professor',$user,True,True));
						array_push($cp,array('$HV','ca_status','0',True,True));	
					}
				
				return($cp);
			}

		function cp_02()
			{
				$cp = array();
				array_push($cp,array('$H8','id_ca','',False,True));
				
				array_push($cp,array('${','','Recursos',False,True));
				array_push($cp,array('$N8','ca_vlr_total','Valor total',True,True));
				array_push($cp,array('$N8','ca_vlr_capital','Valor de capital',True,True));
				array_push($cp,array('$N8','ca_vlr_custeio','Valor de custeio',True,True));
				array_push($cp,array('$N8','ca_vlr_bolsa','Valor de bolsa',True,True));
				array_push($cp,array('$N8','ca_vlr_outros','Outros valores',True,True));
				array_push($cp,array('$}','','Recursos',False,True));
				
				array_push($cp,array('${','','Instituição Proponente',False,True));
				//array_push($cp,array('$Q ','ca_duracao','Duração',True,True));
				//array_push($cp,array('$Q inst_nome:inst_codigo:select inst_codigo, substring(trim(inst_nome) from 1 for 50) || chr(47) || trim(inst_abreviatura) as inst_nome from instituicao where inst_abreviatura <> \'\' order by inst_nome','ca_proponente','Instituição proponente',True,True));
				array_push($cp,array('$HV','ca_proponente','0000455',True,True));				
				array_push($cp,array('$N8','ca_proponente_vlr','Valor aplicado na PUCPR',True,True));
				array_push($cp,array('$M','','<font class=lt0>O valor aplicado refere-se a quantidade de recursos que serão aplicados na PUCPR, podendo ser qualquer uma das modalidades, capital, custeio ou bolsas, informando qual o valor total.</font>',False,True));
				array_push($cp,array('$}','','Vigência',False,True));
				
				array_push($cp,array('$T60:3','ca_contexto','Obs',False,True));		
				array_push($cp,array('$HV','ca_status','0',True,True));					
				return($cp);				
			}
		function cp_03()
			{
				$cp = array();
				array_push($cp,array('$H8','id_ca','',False,True));
				array_push($cp,array('$HV','ca_status','0',True,True));	
							
				array_push($cp,array('$B8','','Continuar >>	',False,True));						
				return($cp);				
			}
		function cp_04()
			{
				$cp = array();
				array_push($cp,array('$H8','id_ca','',False,True));
				array_push($cp,array('$HV','ca_status','0',True,True));
				array_push($cp,array('$O : &S:SIM','','Atesto que as informações aqui postadadas são verdadeiras e confirmo envio para validação do coordenador',True,True));	
							
				array_push($cp,array('$B8','','Continuar >>	',False,True));						
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
				array_push($cp,array('$H8','id_ca','',False,True));
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
				
				array_push($cp,array('$O C:Coordenador&P:Coordenador na Instituição&O:Colaborador&E:Coordenador no Programa/Escola','ca_participacao','Participação',False,True));
				
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
			
		function captacao_vigencia_bonificacao($anoi,$anof,$tipo='')
			{
				global $editar;
				$cpo = 'centro_nome';
				$tipo = trim($tipo);
				$void = 0; $wh = '';
				if (strlen($tipo) >3)
					{
						if ($tipo != '9999')
						{	
						 	$wh = " and ca_agencia_gr = '$tipo' ";
						} else {
							$void = 1;
						} 
						 
					}
					
				$sql = "select * from ".$this->tabela." 
					inner join docentes on ca_professor = pp_cracha
					left join centro on centro_codigo = pp_escola
					left join bonificacao on ca_protocolo = bn_original_protocolo
					where 					   
					   (ca_vigencia_ini_ano >= $anoi or ca_vigencia_fim_ano >= $anoi)
					   and
					   (ca_vigencia_ini_ano <= $anof or ca_vigencia_fim_ano <= $anof)
						$wh					   
					and ca_status <> 9
					order by centro_nome, pp_nome, ca_protocolo, bn_original_tipo, ca_vigencia_ini_ano
				";
				
				$ara = array();
				$art = array();
				$arr = array();
				for ($ar = $ano;$ar < (date("Y")+2);$ar++)
					{
						array_push($ara,$ar);
						array_push($art,0);
						array_push($arr,0);	
					}
				$rlt = db_query($sql);
				$sx = '';
				$sx .= '<fieldset><legend>'.msg('captacao').'</legend>';
				
				/****/
				$sa = '<table class="lt0" width="100%">';
				$sa .= '<TR>';
				$sa .= '<TH>'.msg("projeto");
				$sa .= '<TH>'.msg("fomento");
				$sa .= '<TH>'.msg("descricao");
				$sa .= '<TH>'.msg("convenio_contrato");
				$sa .= '<TH>'.msg("edital");
				$sa .= '<TH>'.msg("vig_ini");
				$sa .= '<TH>'.msg("vig_fim");
				$sa .= '<TH>'.msg("prorro");
				$sa .= '<TH>'.msg("vlr_total");
				$sa .= '<TH>'.msg("vlr_pucpr");
				$sa .= '<TH>'.msg("vlr_bonificacao");
				$sa .= '<TH>'.msg("vlr_pago");
				$sa .= '<TH colspan=2>'.msg("status");
				
				$sb = '<table class="tabela00" width="100%">';
				$sb .= '<TR><TH>'.msg($cpo).'<TH>'.msg('quant').'<TH>'.msg('total');
				$sc = '<table class="tabela00" width="100%">';
				$sc .= '<TR><TH>'.msg($cpo).'<TH>'.msg('quant').'<TH>'.msg('total');				
				$capx = 'x';
				$tot = 0;
				$tot1 = 0;
				$tot2 = 0;
				$tot3 = 0;
				
				$profx = "X";
				$xproto = "X";
				while ($line = db_read($rlt))
					{
					$proto = $line['ca_protocolo'];
					if (($void==1) and (strlen(trim($line['ca_agencia_gr']))==0) or ($void == 0))
					{
						
						if ($xproto != $proto)
						{
							$xproto = $proto;
							$cap = $line[$cpo];
							$prof = $line['pp_nome'];
							if ($cap != $capx)
								{
									if ($tot1 > 0)
									{
									$sb .= '<TR><TD class="tabela01">'.$capx;
									$sb .= '<TD align="center" class="tabela01">'.$tot2;
									$sb .= '<TD align="right" class="tabela01">'.number_format($tot1,2,',','.');
									}
									$sa .= '<TR><TD class="lt2" colspan=10 class="tabela01"><B>'.$cap;
									$capx = $cap;
									$tot1 = 0;
									$tot2 = 0;
								}
							if ($prof != $profx)
								{
									if ($tot11 > 0)
									{
										$sc .= '<TR><TD class="tabela01">'.$profx;
										$sc .= '<TD align="center" class="tabela01">'.$tot12;
										$sc .= '<TD align="right" class="tabela01">'.number_format($tot11,2,',','.');
									}
									$sa .= '<TR><TD class="lt1" colspan=10 class="tabela01"><B>'.$prof;
									$profx = $prof;
									$tot11 = 0;
									$tot12 = 0;
								}
	
						//print_r($line);
						//echo '<HR>';
						$vlr = $line['ca_proponente_vlr'];
						$cor = '';
						if ($vlr == 0) { $vlr = $line['ca_vlr_total']; $cor = '<font color="red">'; }
						
						if ($vlr > 500000)
							{ $vlr = 500000; }
						$bonif = $vlr * 0.03;						
						
						$tot = $tot + $vlr;
						$tot1 = $tot1 + $vlr;
						$tot2++;
						$tot3++;
						$tot11 = $tot11 + $vlr;
						$tot12++;
						$tot13++;						
						
						/* Link captacao */
						$link = '<A HREF="'.http.'/cip/captacao_detalhe.php?dd0='.$line['id_ca'].'&dd90='.checkpost($line['id_ca']).'" target="new_'.date("Ihs").'">';
						
						$sa .= '<TR valign="top">';
						$title = trim($line['ca_titulo_projeto']);
						if (strlen($title) > 0)
							{
							$sa .= '<TD rowspan=2 class="tabela01">';
							$sa .= $link.$line['ca_protocolo'].'</A>';						
							$sa .= '<TD colspan=10>';
							$sa .= '<B>'.$title.'</B>';
							$sa .= '<TR valign="top">';
							} else {
							$sa .= '<TD rowspan=1>';
							$sa .= $link.$line['ca_protocolo'].'</A>';
							}						
						$sa .= '<TD class="tabela01">';
						$sa .= $line['ca_agencia'];
						$sa .= '<TD class="tabela01">';
						$sa .= $line['ca_agencia_gr'];
						$sa .= '<TD class="tabela01">';

						$sa .= $line['ca_descricao'];
						$sa .= '<TD class="tabela01">';
						$sa .= $line['ca_processo'];
						$sa .= '<TD class="tabela01">';
						$sa .= $line['ca_edital_nr'];
						$sa .= '<TD class="tabela01"><nobr>';
						$sa .= strzero($line['ca_vigencia_ini_mes'],2);
						$sa .= '-';
						$sa .= $line['ca_vigencia_ini_ano'];
						$sa .= '<TD class="tabela01"><nobr>';
						$sa .= strzero($line['ca_vigencia_fim_mes'],2);
						$sa .= '-';
						$sa .= $line['ca_vigencia_fim_ano'];
						$sa .= '<TD class="tabela01"><nobr>';
						$sa .= $line['ca_vigencia_prorrogacao'];
						$sa .= '<TD class="tabela01">';
						$sa .= $cor;
						$sa .= number_format($line['ca_vlr_total'],2,',','.');
						$sa .= '<TD align="right" class="tabela01">';
						$sa .= number_format($vlr,2,',','.');
						$sa .= '<TD align="right" class="tabela01">';
						$sa .= number_format($bonif,2,',','.');	
						$sa .= '<TD align="right" class="tabela01">';
						$sa .= number_format($line['bn_valor'],2,',','.');	
											
						//$sa .= '<TD>';
						//$sa .= $line['ca_status'];
						//$sa .= '<TD>';
						//$sa .= $line['bn_original_tipo'];
						//$sa .= '<TD>';
						//$sa .= $line['bn_status'];						
	
					}
					$tp = trim($line['bn_original_tipo']);
					if ($tp == 'IPR')
						{ $sa .= '<TD><IMG title="isenção de bolsa" SRC="../img/icone_academic.png" height="15">';}
					if ($tp == 'PRJ')
						{ $sa .= '<TD><IMG title="bonificação 3%" SRC="../img/label_3percente_green.png" height="15">';}
					}
				}
				$sb .= '<TR><TD>'.$capx;
				$sb .= '<TD align="center">'.$tot2;
				$sb .= '<TD align="right">'.number_format($tot1,2,',','.');
				$sb .= '<TR><TD>'.msg('total_geral');
				$sb .= '<TD align="center">'.$tot3;
				$sb .= '<TD align="right"><B>'.number_format($tot,2,',','.');
				$sc .= '<TR><TD>'.msg('total_geral');
				$sc .= '<TD align="center">'.$tot13;
				$sc .= '<TD align="right"><B>'.number_format($tot,2,',','.');
				$sc .= '</table>';
				$sb .= '</table>';
				$sa .= '</table>';
				
				$sx = $sb.'<BR>'.$sc.'<BR><BR>'.$sa;				
				
				/****/
				return($sx);				
				
			}
			
		function captacao_ano_detalhe($ano=2000,$programa='')
			{
				$cp = "*";
				$sql = "select $cp from ".$this->tabela." 
					inner join docentes on ca_professor = pp_cracha
					where ca_vigencia_ini_ano = $ano and ca_participacao = 'C'
					and (ca_status = 98 or ca_status = 1 or ca_status = 80 or ca_status = 81)
					";
				if (strlen($programa) > 0)
					{ $sql .= " and ca_programa = '$programa' "; }
				$sql .= "
					and ca_status <> 9
					order by pp_nome, ca_vigencia_ini_ano
				";
				$rlt = db_query($sql);
				$sx = '<H2>Captação de projetos de pesquisa - '.$ano;
				$sx .= '<BR><BR>';
				$sx .= '<table class="ibge">';
				$sx .= '<TR><TH>Fomento<TH>Edital / Empresa<TH>Início<TH>Duração<TH>Prorrogação<TH>Participação<TH>Vlr.Bruto<TH>Vlr.Proponente';
				$pp = "X";
				$to1 = 0;
				$to2 = 0;
				$to3 = 0;
				while ($line = db_read($rlt))
				{
					$to1++;
					$to2 = $to2 + $line['ca_vlr_total'];
					$to3 = $to3 + $line['ca_proponente_vlr'];
					$prof = $line['pp_nome'];
					if ($prof != $pp)
						{ $sx .= '<TR><TD colspan=10><B>'.$prof; $pp = $prof; }
					$sx .= $this->mostra_captacao_recursos($line);
				}
				$sx .= '<TR class="ibge_total"><TD align="right" colspan=6><B>Total de '.$to1.' projeto(s)&nbsp;';
				$sx .= '<TD align="right"><B>'.number_format($to2,2,',','.');
				$sx .= '<TD align="right"><B>'.number_format($to3,2,',','.');
				$sx .= '</table>';
				return($sx);
			}
			
		function captacao_ano_agencia($ano=2003,$agencia='')
			{
				global $editar;
				$cp = "ca_edital_codigo, ca_vigencia_ini_ano, ca_agencia, sum(ca_proponente_vlr) as ca_proponente_vlr, sum(ca_vlr_total) as ca_vlr_total ";
				$sql = "select $cp from ".$this->tabela." 
					inner join docentes on ca_professor = pp_cracha
					where ca_vigencia_ini_ano >= $ano and ca_participacao = 'C'";
				if (strlen($programa) > 0)
					{ $sql .= " and ca_edital_codigo = '$agencia' "; }
				$sql .= "
					and (ca_status = 98 or ca_status = 1 or ca_status = 80 or ca_status = 81)
					group by ca_edital_codigo, ca_vigencia_ini_ano, ca_agencia
					order by ca_edital_codigo,ca_vigencia_ini_ano
				";
				$rlt = db_query($sql);
				$ara = array();
				$arp = array();
				$art = array();
				for ($r=$ano;$r<=(date("Y")+2);$r++)
					{
						Array_push($ara,0);
						Array_push($arp,0);
						Array_push($arp,0);
					}
				while ($line = db_read($rlt))
					{
						$anoc = $line['ca_vigencia_ini_ano'];
						$vlrt = $line['ca_vlr_total'];
						$vlra = $line['ca_proponente_vlr'];
						
						$anoi = ($anoc - $ano);
						$ara[$anoi] = $ara[$anoi] + $vlrt;
						$arp[$anoi] = $arp[$anoi] + $vlra;
						$art[$anoi] = $art[$anoi] + 1; 
					}
				$sx = '<H2>Captação de projetos de pesquisa - '.$agencia;
				$sx .= '<table class="ibge">';
				$sh = '<TR><TH>tipo / ano';
				$sn2 = '<TR align="center"><TD align="left">Captação bruta';
				$sn3 = '<TR align="center"><TD align="left">Captação aplicada na proponente';
				$sn1 = '<TR align="center"><TD align="left">Total de captaçães';
				$sgd = '';
				$ws = 80;
				for ($r=0;$r < count($ara);$r++)
					{
						$page = page();
						$page = troca($page,'.php','_detalhe').'.php';
						
						$mano = ($ano+$r);
						$link = '<A HREF="'.$page.'?dd1='.$mano.'&dd2='.$programa.'" alt="clique para detalhes">'; 
						$vlr1 = round($ara[$r]/1);
						$vlr2 = round($arp[$r]/1);
						$sh .= '<TH width="'.$ws.'">'.$link.$mano;
						$sn1 .= '<TD>'.$art[$r];
						$sn2 .= '<TD>'.number_format($ara[$r]/1000,2,',','.');
						$sn3 .= '<TD>'.number_format($arp[$r]/1000,2,',','.');
						if (strlen($sgd) > 0) { $sgd .= ','.chr(13).chr(10); }
						$sgd .= "['$mano',  $vlr1, $vlr2]";
					}
				$sx .= $sh.chr(13);
				$sx .= $sn1.chr(13);
				$sx .= $sn2.chr(13);
				$sx .= $sn3.chr(13);
				$sx .= '<TR><TD colspan=4 class="lt0">* Valores expressos em milhares';
				$sx .= '</table>';
				
				/* Grafico */
				$cr = chr(13).chr(10);
				$sg = '<script type="text/javascript" src="https://www.google.com/jsapi"></script>'.$cr;
				$sg .= '<script type="text/javascript">'.$cr;
				$sg .= 'google.load("visualization", "1", {packages:["corechart"]});'.$cr;
				$sg .= 'google.setOnLoadCallback(drawChart);'.$cr;
				$sg .= 'function drawChart() {'.$cr;
				$sg .= 'var data = google.visualization.arrayToDataTable(['.$cr;
				
				$sg .= '[\'Ano\', \'Cap. Bruta\', \'Aplic. Proponente\'],'.$cr;
				$sg .= $sgd.$cr;
				$sg .= ']);'.$cr;
				$sg .= 'var options = {'.$cr;
				$sg .= 'title: \'Captação de Projetos de Pesquisa\','.$cr;
				$sg .= 'hAxis: {title: \'Ano\',  titleTextStyle: {color: \'red\'}}'.$cr;
				$sg .= '};'.$cr;
				$sg .= 'var chart = new google.visualization.AreaChart(document.getElementById(\'chart_div_cap1\'));'.$cr;
				$sg .= 'chart.draw(data, options);'.$cr;
				$sg .= '}'.$cr;
				$sg .= '</script>'.$cr;
				$sg .= '<div id="chart_div_cap1" style="width: 900px; height: 500px;"></div>'.$cr;	
				
				$sx .= $sg;			
				return($sx);
			}			

		function captacao_ano($ano=2000,$programa='')
			{
				global $editar;
				$cp = "ca_vigencia_ini_ano, ca_agencia, ca_proponente_vlr, ca_vlr_total ";
				$sql = "select $cp from ".$this->tabela." 
					inner join docentes on ca_professor = pp_cracha
					where ca_vigencia_ini_ano >= $ano and ca_participacao = 'C'";
				if (strlen($programa) > 0)
					{ $sql .= " and ca_programa = '$programa' "; }
				$sql .= "
					and (ca_status = 98 or ca_status = 1 or ca_status = 80 or ca_status = 81)
					order by pp_nome, ca_vigencia_ini_ano
				";
				$rlt = db_query($sql);
				$ara = array();
				$arp = array();
				$art = array();
				for ($r=$ano;$r<=(date("Y")+2);$r++)
					{
						Array_push($ara,0);
						Array_push($arp,0);
						Array_push($arp,0);
					}
				while ($line = db_read($rlt))
					{
						$anoc = $line['ca_vigencia_ini_ano'];
						$vlrt = $line['ca_vlr_total'];
						$vlra = $line['ca_proponente_vlr'];
						
						$anoi = ($anoc - $ano);
						$ara[$anoi] = $ara[$anoi] + $vlrt;
						$arp[$anoi] = $arp[$anoi] + $vlra;
						$art[$anoi] = $art[$anoi] + 1; 
					}
				$sx = '<H2>Captação de projetos de pesquisa';
				$sx .= '<table class="ibge">';
				$sh = '<TR><TH>tipo / ano';
				$sn2 = '<TR align="center"><TD align="left">Captação bruta';
				$sn3 = '<TR align="center"><TD align="left">Captação aplicada na proponente';
				$sn1 = '<TR align="center"><TD align="left">Total de captaçães';
				$sgd = '';
				$ws = 80;
				for ($r=0;$r < count($ara);$r++)
					{
						$page = page();
						$page = troca($page,'.php','_detalhe').'.php';
						
						$mano = ($ano+$r);
						$link = '<A HREF="'.$page.'?dd1='.$mano.'&dd2='.$programa.'" alt="clique para detalhes">'; 
						$vlr1 = round($ara[$r]/1);
						$vlr2 = round($arp[$r]/1);
						$sh .= '<TH width="'.$ws.'">'.$link.$mano;
						$sn1 .= '<TD>'.$art[$r];
						$sn2 .= '<TD>'.number_format($ara[$r]/1000,2,',','.');
						$sn3 .= '<TD>'.number_format($arp[$r]/1000,2,',','.');
						if (strlen($sgd) > 0) { $sgd .= ','.chr(13).chr(10); }
						$sgd .= "['$mano',  $vlr1, $vlr2]";
					}
				$sx .= $sh.chr(13);
				$sx .= $sn1.chr(13);
				$sx .= $sn2.chr(13);
				$sx .= $sn3.chr(13);
				$sx .= '<TR><TD colspan=4 class="lt0">* Valores expressos em milhares';
				$sx .= '</table>';
				
				/* Grafico */
				$cr = chr(13).chr(10);
				$sg = '<script type="text/javascript" src="https://www.google.com/jsapi"></script>'.$cr;
				$sg .= '<script type="text/javascript">'.$cr;
				$sg .= 'google.load("visualization", "1", {packages:["corechart"]});'.$cr;
				$sg .= 'google.setOnLoadCallback(drawChart);'.$cr;
				$sg .= 'function drawChart() {'.$cr;
				$sg .= 'var data = google.visualization.arrayToDataTable(['.$cr;
				
				$sg .= '[\'Ano\', \'Cap. Bruta\', \'Aplic. Proponente\'],'.$cr;
				$sg .= $sgd.$cr;
				$sg .= ']);'.$cr;
				$sg .= 'var options = {'.$cr;
				$sg .= 'title: \'Captação de Projetos de Pesquisa\','.$cr;
				$sg .= 'hAxis: {title: \'Ano\',  titleTextStyle: {color: \'red\'}}'.$cr;
				$sg .= '};'.$cr;
				$sg .= 'var chart = new google.visualization.AreaChart(document.getElementById(\'chart_div_cap1\'));'.$cr;
				$sg .= 'chart.draw(data, options);'.$cr;
				$sg .= '}'.$cr;
				$sg .= '</script>'.$cr;
				$sg .= '<div id="chart_div_cap1" style="width: 900px; height: 500px;"></div>'.$cr;	
				
				$sx .= $sg;			
				return($sx);
			}
			
		function captacao_vigencia($ano=2000)
			{
				global $editar;
				$sql = "select * from ".$this->tabela." 
					inner join docentes on ca_professor = pp_cracha
					where ca_vigencia_ini_ano >= $ano
					and ca_status <> 9
					order by pp_nome, ca_vigencia_ini_ano
				";
				$ara = array();
				$art = array();
				$arr = array();
				for ($ar = $ano;$ar < (date("Y")+2);$ar++)
					{
						array_push($ara,$ar);
						array_push($art,0);
						array_push($arr,0);	
					}
				$rlt = db_query($sql);
				$sx = '';
				$sx .= '<fieldset><legend>'.msg('captacao').'</legend>';
				$sx .= '<table width="100%" class="lt1" cellpadding=3 cellspacing=0 border=1>';
				$st .= '<TR><TH>Pesquisador<TH>Início<TH>Vg<TH>Captacao<TH>St<TH>2009<TH>2010<TH>2011<TH>2012<TH>2013';
				while ($line = db_read($rlt))
				{
					$duracao = $line['ca_duracao'];
					if  ($duracao == 0) { $duracao = 24; }
					$ln = $line;
					$ano_ini = $line['ca_vigencia_ini_ano'];
					$ano_fim = $line['ca_vigencia_final_ano'];
					$captacao = $line['ca_vlr_total'];
					$status = $line['ca_status'];

					$st .= '<TR>';
					$st .= '<TD>';
					$st .= trim($line['pp_nome']);
					$st .= '<TD>';
					$st .= $line['ca_vigencia_ini_ano'].'/'.strzero($line['ca_vigencia_ini_mes'],2);
					$st .= '<TD align="center">';
					$st .= $duracao;					
					$st .= '<TD align="right">';
					$st .= number_format($captacao,2,',','.');
					$st .= '<TD align="center">';
					$st .= $status;					
				
					$xano = $line['ca_vigencia_ini_ano'];
					$vano = $arr;
					$anos = round($duracao / 12);
					if ($anos == 0) { $anos = 1; }
					$rateio = $captacao / $anos;
					$anoi = $xano - $ano;
					for ($rr=$anoi;$rr <= ($anoi+$anos)-1;$rr++)
						{
							$vano[$rr] = $rateio;
							$atr[$rr] = $atr[$rr] + $rateio;
						}
					for ($rr=0;$rr < count($arr);$rr++)
						{
							$st .= '<TD align="right" width="7%">';
							$st .= number_format($vano[$rr],2,',','.');
						}
					
					

				}
				for ($r=0;$r < count($atr);$r++)
					{
						$sa .= '<TD>'.($ano + $r);
						$sb .= '<TD>'.number_format($atr[$r],2,',','.');
					}
				$sx .= '<TR>'.$sa;
				$sx .= '<TR>'.$sb;
				$sx .= '</table>';
				
				$sx .= '<table border=1 class="lt1">';
				$sx .= $st;
				$sx .= '</table>';
				
				$sx .= '</fieldset>';
				return($sx);				
				
			}
		
		function last_update()
			{
				global $editar;
				$sql = "select * from ".$this->tabela." 
					inner join docentes on ca_professor = pp_cracha
					where ca_update > 20120705
					order by ca_update desc, pp_nome
				";
				$rlt = db_query($sql);
				$sx = '';
				$sx .= '<fieldset><legend>'.msg('captacao').'</legend>';
				$sx .= '<table width="100%" class="lt1" cellpadding=3 cellspacing=0 border=1>';
				$sx .= '<TR><TH>Protocolo<TH>Agência<TH>Edital<TH>Início<TH>Duração<TH>Prorrogação<TH>Participação<TH>Vlr. Total<TH>status';
				if ($editar==1) { $sx .= '<TH>ação'; }
				$xano = 0;
				$tot = 0;
				$xpro = 'x';
				while ($line = db_read($rlt))
					{
						$tot++;
						$ano = stodbr($line['ca_update']);
						$prof = trim($line['pp_cracha']);
						if ($xano != $ano)
							{
								$sx .= '<TR class="lt1"><TD colspan=7><B>'.$ano;
								$xano = $ano;
							}
						if ($xpro != $prof)
							{
								$sx .= '<TR class="lt1"><TD colspan=7><B>'.$line['pp_nome'];
								$xano = $ano;
								$xpro = $prof;								
							}
						$sx .= $this->mostra_captacao_lista($line);
					}
				if ($tot == 0)
					{
						$sx .= '<TR><TD colspan=9><font class="lt4"><center><font color="orange">sem projetos cadastrado';
					}
				$sx .= '<TR><TD colspan=10>Total de '.$tot.' atualizaçães';
				$sx .= '</table>';
				$sx .= '</fieldset>';
				return($sx);				
			}
		
		function atualiza_vigencia()
			{
				global $dd;
				if (strlen($dd[0]) > 0)
				{
					$sql = "select * from ".$this->tabela." where id_ca = ".$dd[0];
					$rlt = db_query($sql);
					$line = db_read($rlt);
				
					$mm = strzero($line['ca_vigencia_ini_mes'],2);
					$ma = strzero($line['ca_vigencia_ini_ano'],4);
					
					$md = 0;
					/****/
					$m1 = $line['ca_vigencia_ini_mes'];
					$m2 = $line['ca_vigencia_ini_ano'];
					$m3 = $line['ca_vigencia_fim_mes'];
					$m4 = $line['ca_vigencia_fim_ano'];
					$md = 0;
	
					if ($m2 < $m4)
						{
							$md = ($m4 - $m2) * 12 - $m1 + $m3 + 1;
						}
					
					$sql = "update ".$this->tabela." set ca_vigencia_final_ano = ".$ma.$mm;
					$sql .= ", ca_duracao  = ".$md;
					$sql .= " where  id_ca = ".$dd[0];
					//$rlt = db_query($sql);
	//				echo $sql;
	//				print_r($line);
				}
			}

		function captacao_para_valiadacao_email($texto,$tipo=1)
			{
				global $dd;
				
				$prof = new docentes;
				$sql = "select pos_coordenador, count(*) as total, pp_nome, pp_email, pp_email_1 from ".$this->tabela." 
				inner join programa_pos_docentes on ca_professor = pdce_docente 
				inner join programa_pos on pos_codigo = pdce_programa
				inner join pibic_professor on pp_cracha = pos_coordenador
					where (ca_status = 10 or ca_status = 1)
					group by  pp_nome, pos_coordenador, pp_email, pp_email_1
				";	
				$rlt = db_query($sql);	
				while ($line = db_read($rlt))
				{
					$prof->le($line['pos_coordenador']);
					$xlink = $prof->pp_pagina;
					$link = '<A HREF="'.$xlink.'" target="new">';
					$link .= $xlink;
					$link .= '</a>';
					$ttt = troca($texto,'$nome',trim($line['pp_nome']));
					$ttt = troca($ttt,'$link',$link);
					$em1 = trim($line['pp_email']);
					$em2 = trim($line['pp_email_1']);
					echo '<table WIDTH="700"><tr><td>';
					if ($tipo==1)
						{
							if ($dd[1]=='S')
							{
							enviaremail('cip@pucpr.br','','(cópia) Validação de captação',$ttt);
							if (strlen($em1) > 0) 
								{ enviaremail($em1,'','Validação de captação',$ttt); echo '<BR>enviado para '.$em1; }
							if (strlen($em2) > 0) 
								{ enviaremail($em2,'','Validação de captação',$ttt); echo '<BR>enviado para '.$em2; }
							} else {
								echo $ttt;
								echo '<HR>';
							}	
						}
					echo '<HR>para enviar dd1=S<HR>';
					
				}		
			}
		function mostra_captacao_programas($dd1,$datai=2000,$dataf=2999)
			{
				$sql = "select * from ".$this->tabela." 
				inner join programa_pos_docentes on ca_professor = pdce_docente 
				inner join programa_pos on pos_codigo = pdce_programa
				inner join pibic_professor on pp_cracha = pdce_docente
					where (pos_codigo = '".$dd1."')
					and (ca_programa = '".$dd1."' or ca_programa isnull)
					and (ca_participacao <> 'O')
					 
					and (ca_status = 10 or ca_status = 1 or ca_status = 80
					or ca_status = 81  or ca_status > 50)
					and (ca_vigencia_ini_ano >= $datai and ca_vigencia_ini_ano <= $dataf)
					and pdce_ativo = 1
					order by pp_nome, ca_vigencia_ini_ano desc, ca_vigencia_ini_mes desc, ca_descricao
				";
				/* or ca_status = 9 */
				$rlt = db_query($sql);
				$sx .= '<table width="100%" class="lt1" cellpadding=3 cellspacing=0 border=1>';
				$sx .= '<TR><TH>Agência<TH colspan=2>Edital<TH>Início<TH>Duração<TH>Prorrogação<TH>Participação<TH>Vlr. Total<TH>Vlr. Proponente<TH>Insti-<BR>tucional';
				
				$xprof = 'x';
				$tot = 0;
				$tot2 = 0;
				$tot3 = 0;
				$tot4 = 0;
				$tot7 = 0;
				$totc = 0;
				$totd = 0;
				$tote = 0;
				$totf = 0;
				$xproto = "X";
				while ($line = db_read($rlt))
					{
					$inst = trim($line['ca_insticional']);
					$proto = $line['ca_protocolo'];
					$tipo = trim($line['ca_participacao']);
					if ($xproto != $proto)
						{
						$prof = $line['pp_cracha'];
						if ($prof != $xprof)
						{
							$sx .= '<TR><TD colspan=4 class="lt3">'.$line['pp_nome'];
							$xprof = $prof;
						}
						$sx .= $this->mostra_captacao_lista($line,2);
						
						if ($inst == '1')
							{ $tot3 = $tot3 + $line['ca_proponente_vlr']; $tote++;	}
						else {
							$tot7 = $tot7 + $line['ca_proponente_vlr']; $totf++;	
							if ($tipo == 'C')
								{ $tot = $tot + $line['ca_proponente_vlr'];	$tot4 = $tot4 + $line['ca_vlr_total']; $totc++;}
							if ($tipo == 'O')
								{ $tot2 = $tot2 + $line['ca_proponente_vlr']; $totd++;	}
							if ($tipo == 'E')
								{ $tot = $tot + $line['ca_proponente_vlr'];	$tot4 = $tot4 + $line['ca_vlr_total']; $totc++;}
							}
						}
						$xproto = $proto;
					}
				$sx .= '<TR><TD colspan=10>';
					$sx .= '<table class="tabela00">' ;
					$sx .= '<TR><TH width="220">Descrição<TH width="30">Projetos<TH width="120">Captação';
					$sx  .= '<TR><TD class="tabela01"><sup>(1)</sup> Projetos em colaboração<TD align="center" class="tabela01">'.$totd;
					$sx .= '<TD align="right" class="tabela01">'.number_format($tot2,2,',','.');

					$sx  .= '<TR><TD class="tabela01"><sup>(2)</sup> Projetos na instituição<TD align="center" class="tabela01">'.$totc;
					$sx .= '<TD align="right" class="tabela01">'.number_format($tot4,2,',','.');

					$sx  .= '<TR><TD class="tabela01"><sup>(3)</sup> Projetos aplicado na instituição<TD align="center" class="tabela01">'.$totc;
					$sx .= '<TD align="right" class="tabela01">'.number_format($tot,2,',','.');

					$sx  .= '<TR><TD class="tabela01"><sup>(4)</sup> Projetos institucionais<TD align="center" class="tabela01">'.$tote;
					$sx .= '<TD align="right" class="tabela01">'.number_format($tot3,2,',','.');

					$sx  .= '<TR><TD class="tabela01"><sup>(4)</sup> Total de Projetos (-instituicionais)<TD align="center" class="tabela01">'.$totf;
					$sx .= '<TD align="right" class="tabela01">'.number_format($tot7,2,',','.');


					$sx .= '<TR><TD align="right" colspan=3 class="lt0"><SUP>(4)</SUP> Valores incorporados nos itens <SUP>(2)</SUP> e <SUP>(3)</SUP>.';
					$sx .= '</table>';
				
				$sx .= '</table>';
				return($sx);
				
			}
		function mostra_captacao_programas_vigentes($dd1,$datai=2000,$dataf=2999)
			{
				$sql = "select * from ".$this->tabela." 
				inner join programa_pos_docentes on ca_professor = pdce_docente 
				inner join programa_pos on pos_codigo = pdce_programa
				inner join pibic_professor on pp_cracha = pdce_docente
					where (pos_codigo = '".$dd1."')
					and (ca_programa = '".$dd1."' or ca_programa isnull)
					and (ca_participacao <> 'O')
					and (ca_status = 10 or ca_status = 1 or ca_status = 80
					or ca_status = 81  or ca_status > 50) and
						(
							(ca_vigencia_ini_ano <= $datai and ca_vigencia_fim_ano >= $datai)
							or 	
							(ca_vigencia_ini_ano >= $datai and ca_vigencia_ini_ano <= $dataf)
							or 													
							(ca_vigencia_ini_ano >= $dataf and ca_vigencia_ini_ano <= $dataf)
						)
					and pdce_ativo = 1
					order by pp_nome, ca_vigencia_ini_ano desc, ca_vigencia_ini_mes desc, ca_descricao
				";
				/* or ca_status = 9 */
				$rlt = db_query($sql);
				$sx .= '<table width="100%" class="lt1" cellpadding=3 cellspacing=0 border=1>';
				$sx .= '<TR><TH>Agência<TH colspan=2>Edital<TH>Início<TH>Duração<TH>Prorrogação<TH>Participação<TH>Vlr. Total<TH>Vlr. Proponente<TH>Insti-<BR>tucional';
				
				$xprof = 'x';
				$tot = 0;
				$tot2 = 0;
				$tot3 = 0;
				$tot4 = 0;
				$tot7 = 0;
				$totc = 0;
				$totd = 0;
				$tote = 0;
				$totf = 0;
				$xproto = "X";
				
				while ($line = db_read($rlt))
					{
					$inst = trim($line['ca_insticional']);
					$proto = $line['ca_protocolo'];
					$tipo = trim($line['ca_participacao']);

					if ($xproto != $proto)
						{
						$prof = $line['pp_cracha'];
						if ($prof != $xprof)
						{
							$sx .= '<TR><TD colspan=4 class="lt3">'.$line['pp_nome'];
							$xprof = $prof;
						}
						$sx .= $this->mostra_captacao_lista($line,2);
						
						if ($inst == '1')
							{ $tot3 = $tot3 + $line['ca_proponente_vlr']; $tote++;	}
						else {
							$tot7 = $tot7 + $line['ca_proponente_vlr']; $totf++;	
							if ($tipo == 'C')
								{ $tot = $tot + $line['ca_proponente_vlr'];	$tot4 = $tot4 + $line['ca_vlr_total']; $totc++;}
							if ($tipo == 'O')
								{ $tot2 = $tot2 + $line['ca_proponente_vlr']; $totd++;	}
							if ($tipo == 'E')
								{ $tot = $tot + $line['ca_proponente_vlr'];	$tot4 = $tot4 + $line['ca_vlr_total']; $totc++;}
							}
						}
						$xproto = $proto;
					}
				$sx .= '<TR><TD colspan=10>';
					$sx .= '<table class="tabela00">' ;
					$sx .= '<TR><TH width="220">Descrição<TH width="30">Projetos<TH width="120">Captação';
					$sx  .= '<TR><TD class="tabela01"><sup>(1)</sup> Projetos em colaboração<TD align="center" class="tabela01">'.$totd;
					$sx .= '<TD align="right" class="tabela01">'.number_format($tot2,2,',','.');

					$sx  .= '<TR><TD class="tabela01"><sup>(2)</sup> Projetos na instituição<TD align="center" class="tabela01">'.$totc;
					$sx .= '<TD align="right" class="tabela01">'.number_format($tot4,2,',','.');

					$sx  .= '<TR><TD class="tabela01"><sup>(3)</sup> Projetos aplicado na instituição<TD align="center" class="tabela01">'.$totc;
					$sx .= '<TD align="right" class="tabela01">'.number_format($tot,2,',','.');

					$sx  .= '<TR><TD class="tabela01"><sup>(4)</sup> Projetos institucionais<TD align="center" class="tabela01">'.$tote;
					$sx .= '<TD align="right" class="tabela01">'.number_format($tot3,2,',','.');

					$sx  .= '<TR><TD class="tabela01"><sup>(4)</sup> Total de Projetos (-instituicionais)<TD align="center" class="tabela01">'.$totf;
					$sx .= '<TD align="right" class="tabela01">'.number_format($tot7,2,',','.');


					$sx .= '<TR><TD align="right" colspan=3 class="lt0"><SUP>(4)</SUP> Valores incorporados nos itens <SUP>(2)</SUP> e <SUP>(3)</SUP>.';
					$sx .= '</table>';
				
				$sx .= '</table>';
				return($sx);
				
			}
		function mostra_captacao_programas_v2($dd1,$datai=2000,$dataf=2999)
			{
				$sql = "select * from ".$this->tabela." 
				inner join programa_pos_docentes on ca_professor = pdce_docente 
				inner join programa_pos on pos_codigo = pdce_programa
				inner join pibic_professor on pp_cracha = pdce_docente
				left join agencia_de_fomento on agf_codigo = ca_agencia_gr
					where pos_codigo = '".$dd1."' 
					and (ca_status = 10 or ca_status = 1 or ca_status = 80
					or ca_status = 81  or ca_status > 50)
					and (ca_vigencia_ini_ano >= $datai and ca_vigencia_ini_ano <= $dataf)
					and pdce_ativo = 1
					order by pp_nome, ca_vigencia_ini_ano desc, ca_vigencia_ini_mes desc, ca_descricao
				";
				/* or ca_status = 9 */
				$rlt = db_query($sql);
				$sx .= '<table width="100%" class="lt1" cellpadding=3 cellspacing=0 border=1>';
				
				$xprof = 'x';
				$tot = 0;
				$tot2 = 0;
				$tot3 = 0;
				$tot4 = 0;
				$totc = 0;
				$totd = 0;
				$tote = 0;
				$xproto = "X";
				
				$sponsor = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
				$quant = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
				
				$finan = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
				$qf = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
				
				while ($line = db_read($rlt))
					{
					$inst = trim($line['ca_insticional']);
					$proto = $line['ca_protocolo'];
					$tipo = trim($line['ca_participacao']);
					$valor = $line['ca_proponente_vlr'];
					$ano = $line['ca_vigencia_ini_ano'];
					$grp = trim($line['ca_agencia_gr']);
					$agencia = $line['agf_nome'];
					
					$age = trim($line['ca_agencia']);
					
					switch ($age)
						{
							case 'CNPq': 	$finan[0] = $finan[0] + $valor; $qf[0] = $qf[0] + 1; break;
							case 'CAPES':	$finan[1] = $finan[1] + $valor; $qf[1] = $qf[1] + 1; break;
							case 'AG': 		$finan[2] = $finan[2] + $valor; $qf[2] = $qf[2] + 1; break;
							case 'FA': 		$finan[3] = $finan[3] + $valor; $qf[3] = $qf[3] + 1; break;
							case 'FINEP': 	$finan[4] = $finan[4] + $valor; $qf[4] = $qf[4] + 1; break;
							case 'MCTI': 	$finan[2] = $finan[2] + $valor; $qf[5] = $qf[5] + 1; break;
							case 'OPAS': 	$finan[6] = $finan[6] + $valor; $qf[6] = $qf[6] + 1; break;
							case 'EMPRE':	$finan[7] = $finan[7] + $valor; $qf[7] = $qf[7] + 1; break;
							default:		$finan[8] = $finan[8] + $valor; $qf[8] = $qf[8] + 1; break; 						
						}
					
					switch ($grp)
						{
							case '00026': /* Agência de fomento */
									$sponsor[1] = $sponsor[1] + $valor;
									$quant[1] = $quant[1] + 1;
									break;
							case '00022': /* Orgáo governamental */
									$sponsor[2] = $sponsor[2] + $valor;
									$quant[2] = $quant[2] + 1;
									break;
							case '00022': /* Orgáo governamental */
									$sponsor[3] = $sponsor[3] + $valor;
									$quant[3] = $quant[3] + 1;
									break;
							case '': /* Não categorizado */
									$sponsor[4] = $sponsor[4] + $valor;
									$quant[4] = $quant[4] + 1;
									break;

							case '00027': /* Agência de fomento internacional */
									$sponsor[6] = $sponsor[6] + $valor;
									$quant[6] = $quant[6] + 1;
									break;
							case '00023': /* Orgáo governamental internacional  */
									$sponsor[7] = $sponsor[7] + $valor;
									$quant[7] = $quant[7] + 1;
									break;
							case '00022': /* Orgáo governamental internacional  */
									$sponsor[8] = $sponsor[8] + $valor;
									$quant[8] = $quant[8] + 1;
									break;
							}				
					}
				//$sx .= '<table width="100%" class="tabela00" border=0>';
				//$sx .= '<TR><Th>Ano<TH width="6%">Agência de Fomento<TH>Orgão governamentais<TH>Empresas<TH>Não Categorizados
				//					<TH>Agências de Fomento Internacionais<TH>Orgão governamentais Internacionais<TH>Empresas Internacionais';
				//$sx .= '<TR><TD width="6%">'.$datai.'-'.$dataf;
				//$sx .= '<TD class="tabela01" align="center" width="15%">'.number_format($sponsor[1],2,',','.');
				//$sx .= '<TD class="tabela01" align="center" width="15%">'.number_format($sponsor[2],2,',','.');;
				//$sx .= '<TD class="tabela01" align="center" width="15%">'.number_format($sponsor[3],2,',','.');;
				//$sx .= '<TD class="tabela01" align="center" width="15%">'.number_format($sponsor[4],2,',','.');;
			
				//$sx .= '<TD class="tabela01" align="center" width="15%">'.number_format($sponsor[6],2,',','.');;
				//$sx .= '<TD class="tabela01" align="center" width="15%">'.number_format($sponsor[7],2,',','.');;
				//$sx .= '<TD class="tabela01" align="center" width="15%">'.number_format($sponsor[8],2,',','.');;
				
				//$sx .= '</table>';
				
				/* Agências */
				$sx .= '<table width="100%" class="tabela00">';
				$sx .= '<TR><Th>Ano<TH>CNPq<TH>CAPES<TH>Agências Governamentais / Ministérios<TH>Fundação Araucária
							<TH>FINEP<TH>OPAS<TH>Outros';
				//<TH>Empresas<TH>Outras';
				$sx .= '<TR><TD width="6%">'.$datai.'-'.$dataf;
				$sx .= '<TD class="tabela01" align="center" width="10%">'.number_format($finan[0],2,',','.').' ('.$qf[0].' prj)';
				$sx .= '<TD class="tabela01" align="center" width="10%">'.number_format($finan[1],2,',','.').' ('.$qf[1].' prj)';;
				$sx .= '<TD class="tabela01" align="center" width="10%">'.number_format($finan[2],2,',','.').' ('.$qf[2].' prj)';;
				$sx .= '<TD class="tabela01" align="center" width="10%">'.number_format($finan[3],2,',','.').' ('.$qf[3].' prj)';;
				$sx .= '<TD class="tabela01" align="center" width="10%">'.number_format($finan[4],2,',','.').' ('.$qf[4].' prj)';;
				//$sx .= '<TD class="tabela01" align="center" width="10%">'.number_format($finan[5],2,',','.');;
				$sx .= '<TD class="tabela01" align="center" width="10%">'.number_format($finan[6],2,',','.').' ('.$qf[6].' prj)';;
				//$sx .= '<TD class="tabela01" align="center" width="10%">'.number_format($finan[7],2,',','.');;
				$sx .= '<TD class="tabela01" align="center" width="10%">'.number_format($finan[8],2,',','.').' ('.$qf[8].' prj)';
				
				$sx .= '</table>';				
				return($sx);
				
			}
		function mostra_captacao_todos($datai=2000,$dataf=2999)
			{
				$sql = "select * from ".$this->tabela." 
				inner join programa_pos_docentes on ca_professor = pdce_docente 
				inner join programa_pos on pos_codigo = pdce_programa
				inner join pibic_professor on pp_cracha = pdce_docente
					where (ca_status = 10 or ca_status = 1 or ca_status = 80
					or ca_status = 81  or ca_status > 50)
					and (ca_vigencia_ini_ano >= $datai and ca_vigencia_ini_ano <= $dataf)
					and pdce_ativo = 1
					order by pp_nome, ca_vigencia_ini_ano desc, ca_vigencia_ini_mes desc, ca_descricao
				";
				/* or ca_status = 9 */
				$rlt = db_query($sql);
				$sx .= '<table width="100%" class="lt1" cellpadding=3 cellspacing=0 border=1>';
				$sx .= '<TR><TH>Agência<TH colspan=2>Edital<TH>Início<TH>Duração<TH>Prorrogação<TH>Participação<TH>Vlr. Total<TH>Vlr. Proponente<TH>Insti-<BR>tucional';
				
				$xprof = 'x';
				$tot = 0;
				$tot2 = 0;
				$tot3 = 0;
				$tot4 = 0;
				$totc = 0;
				$totd = 0;
				$tote = 0;
				$xproto = "X";
				while ($line = db_read($rlt))
					{
					$inst = trim($line['ca_insticional']);
					$proto = $line['ca_protocolo'];
					$tipo = trim($line['ca_participacao']);
					if ($xproto != $proto)
						{
						$prof = $line['pp_cracha'];
						if ($prof != $xprof)
						{
							$sx .= '<TR><TD colspan=4 class="lt3">'.$line['pp_nome'];
							$xprof = $prof;
						}
						$sx .= $this->mostra_captacao_lista($line,2);
						
						if ($tipo == 'C')
							{ $tot = $tot + $line['ca_proponente_vlr'];	$tot4 = $tot4 + $line['ca_vlr_total']; $totc++;}
						if ($tipo == 'O')
							{ $tot2 = $tot2 + $line['ca_proponente_vlr']; $totd++;	}
						if ($inst == '1')
							{ $tot3 = $tot3 + $line['ca_proponente_vlr']; $tote++;	}
						}
						$xproto = $proto;
					}
				$sx .= '<TR><TD colspan=10>';
					$sx .= '<table class="tabela00">' ;
					$sx .= '<TR><TH width="220">Descrição<TH width="30">Projetos<TH width="120">Captação';
					$sx  .= '<TR><TD class="tabela01"><sup>(1)</sup> Projetos em colaboração<TD align="center" class="tabela01">'.$totd;
					$sx .= '<TD align="right" class="tabela01">'.number_format($tot2,2,',','.');

					$sx  .= '<TR><TD class="tabela01"><sup>(2)</sup> Projetos na instituição<TD align="center" class="tabela01">'.$totc;
					$sx .= '<TD align="right" class="tabela01">'.number_format($tot4,2,',','.');

					$sx  .= '<TR><TD class="tabela01"><sup>(3)</sup> Projetos aplicado na instituição<TD align="center" class="tabela01">'.$totc;
					$sx .= '<TD align="right" class="tabela01">'.number_format($tot,2,',','.');

					$sx  .= '<TR><TD class="tabela01"><sup>(4)</sup> Projetos institucionais<TD align="center" class="tabela01">'.$tote;
					$sx .= '<TD align="right" class="tabela01">'.number_format($tot3,2,',','.');

					$sx .= '<TR><TD align="right" colspan=3 class="lt0"><SUP>(4)</SUP> Valores incorporados nos itens <SUP>(2)</SUP> e <SUP>(3)</SUP>.';
					$sx .= '</table>';
				
				$sx .= '</table>';
				return($sx);
				
			}

		function mostra_captacao_coordenador()
			{
				global $editar;
	
				$sql = "select * from ".$this->tabela." 
				inner join programa_pos_docentes on ca_professor = pdce_docente 
				inner join programa_pos on pos_codigo = pdce_programa
				inner join pibic_professor on pp_cracha = pdce_docente
					where pos_coordenador = '".$this->docente."' 
					and (ca_status = 10 or ca_status = 1 or ca_status = 80
					or ca_status = 81 or ca_status = 9)
					order by ca_vigencia_ini_ano desc, ca_vigencia_ini_mes desc, ca_descricao
				";
				echo $sql;
				$rlt = db_query($sql);
				$sx = '';
				$sx .= '<fieldset><legend>'.msg('captacao').'</legend>';
				$sx .= '<font class="lt1">'.msg('captacao_inf').'</font>';
				$sx .= '<table width="100%" class="lt1" cellpadding=3 cellspacing=0 border=1>';
				$sx .= '<TR><TH>Agência<TH colspan=2>Edital<TH>Início<TH>Duração<TH>Prorrogação<TH>Participação<TH>Vlr. Total';
				if ($tipo==0) { $sx .= '<TH>status'; }
				if ($editar==1) { $sx .= '<TH>ação'; }
				$sx .= '<TH>Pesquisador';
				$xano = 0;
				$tot = 0;
				$ixd = 0;
				while ($line = db_read($rlt))
					{
						if ($ixd != $line['id_ca'])
						{
							$ixd = $line['id_ca'];
							$tot++;
							$ano = $line['ca_vigencia_ini_ano'];
							if ($xano != $ano)
								{
									$sx .= '<TR class="lt1"><TD colspan=7><B>'.$ano;
									$xano = $ano;
								}
							$sx .= $this->mostra_captacao_coordenador_lista($line);
						}
					}
				if ($tot == 0)
					{
						$sx .= '<TR><TD colspan=9><font class="lt4"><center><font color="orange">sem projetos cadastrado';
					}
				$sx .= '</table>';
				$sx .= '</fieldset>';
				if ($tot == 0)
					{ $sx = ''; }
				return($sx);				
			}
	

		/* Captação */
		function mostra_captacao()
			{
				global $editar;
				$sql = "select * from ".$this->tabela." 
					where ca_professor = '".$this->docente."' 
					order by ca_vigencia_ini_ano desc, ca_vigencia_ini_mes desc, ca_descricao
				";
				$rlt = db_query($sql);
				$sx = '';
				$sx .= '<fieldset>';
				$sx .= '<h2>Captação de Recursos</h2>';
				//$sx .= '<font class="lt1">'.msg('captacao_pesq_inf').'</font>';
				$sx .= '<table width="100%" class="tabela00">';
				$sx .= '<TR><TH>Agência<TH>Edital<TH>Descricação<TH>Início<TH>Duração<TH>Prorrogação<TH>Participação<TH>Vlr. Total<TH>status<TH>Insti-<BR>tucional.';
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
						$sx .= $this->mostra_captacao_lista($line);
					}
				if ($tot == 0)
					{
						$sx .= '<TR><TD colspan=9><font class="lt4"><center><font color="orange">sem projetos cadastrado';
					}
				$sx .= '</table>';
				$sx .= '</fieldset>';
				return($sx);				
			}
		function tipo_participacao()
			{
				$ca = array('C'=>'Coordenador','O'=>'Colaborador','P'=>'Coordenador na Instituição','E'=>'Coordenador no Programa/Escola');
				return($ca);
			}
		function vigencia($data)
			{
				$sr = $data.' meses';
				if ($data == 1) { $sr = '1 mês '; }
				if (round($data/12) == ($data/12)) { $sr = round($data/12) . ' anos'; }
				if ($data == 12) { $sr = '1 ano'; }
				if ($data == 0) { $sr = '-'; }
				return($sr);
			}

		function mostra_captacao_recursos($line)
			{
				global $editar;
				$link = '<A HREF="'.http.'/cip/captacao_detalhe.php?dd0='.$line['id_ca'].'&dd90='.checkpost($line['id_ca']).'">';
				$participacao = $this->tipo_participacao();
				$cor2 = ''; $cor1 = '';
				if ($line['ca_proponente_vlr'] > $line['ca_vlr_total']) { $cor2 = '<font color="red">'; }
				if ($line['ca_duracao'] ==  0) { $cor1 = '<font color="red">'; }
				
				$sx .= '<TR '.coluna().' valign="top">';
				$sx .= '<TD>';
				$sx .= $line['ca_agencia'];
				$sx .= '<TD>'.$link;
				$sx .= $line['ca_descricao'];

				$sx .= '<TD align="center">'.$cor1;
				$sx .= strzero($line['ca_vigencia_ini_mes'],2);
				$sx .= '/';
				$sx .= strzero($line['ca_vigencia_ini_ano'],2);

				$sx .= '<TD align="center">'.$cor1;
				$sx .= $this->vigencia($line['ca_duracao']);

				$sx .= '<TD align="center">'.$cor1;
				$sx .= $this->vigencia($line['ca_vigencia_prorrogacao']);

				$sx .= '<TD align="center">';
				$pa .= trim($line['ca_participacao']);
				$sx .= $participacao[$pa];

				$sx .= '<TD align="right">'.$cor2;
				$sx .= number_format($line['ca_vlr_total'],2,',','.');

				$sx .= '<TD align="right">'.$cor2;
				$sx .= number_format($line['ca_proponente_vlr'],2,',','.');

				
				return($sx);
			}

		function mostra_captacao_lista($line,$tipo=0)
			{
				global $editar,$user,$perfil;
				
				$sss = $this->status();
				$pa = trim($line['ca_participacao']);
				$participacao = $this->tipo_participacao();
				$valor = $line['ca_vlr_total'];
				$valor_pro = $line['ca_proponente_vlr'];
				$desmembramento = trim($line['ca_desmembramento']);
				$cor = '';
				$info = '';
				if ($pa == 'O')
					{ $cor = '<font color="blue">'; }
				if ($desmembramento == '1')
					{ $cor = '<font color="#008080">'; }
				if ($line['ca_status']==9)
					{ $cor = '<font color="red">'; $info = ' ** CANCELADO **'; }
				
				$sss = $this->status();

				$sta = round($line['ca_status']);
				$sx .= '<TR '.coluna().' valign="top">';
				$sx .= '<TD class="tabela01">';
				$link = '<A HREF="'.http.'/cip/captacao_detalhe.php?dd0='.$line['id_ca'].'&dd90='.checkpost($line['id_ca']).'" target="nw'.$line['id_ca'].'">';
				$sx .= $link.$line['ca_protocolo'].'</A>';
				$sx .= '<TD class="tabela01">'.$cor;
				$sx .= $line['ca_agencia'];
				$sx .= '<TD class="tabela01">'.$cor;
				$sx .= $line['ca_descricao'];
				$sx .= '<BR><font class="lt0">Atualizado '.stodbr($line['ca_update']);
				$sx .= $info;

				$sx .= '<TD class="tabela01" align="center">'.$cor;
				$sx .= strzero($line['ca_vigencia_ini_mes'],2);
				$sx .= '/';
				$sx .= strzero($line['ca_vigencia_ini_ano'],2);

				$sx .= '<TD align="center" class="tabela01">'.$cor;
				$sx .= $this->vigencia($line['ca_duracao']);

				$sx .= '<TD class="tabela01" align="center">'.$cor;
				$sx .= $this->vigencia($line['ca_vigencia_prorrogacao']);

				$sx .= '<TD class="tabela01" align="center">'.$cor;
				$sx .= $participacao[$pa];

				$sx .= '<TD class="tabela01" align="right">'.$cor;
				$sx .= number_format($valor,2,',','.');
				
				$sx .= '<TD class="tabela01" align="right">'.$cor;
				$sx .= number_format($valor_pro,2,',','.');				
				
				if ($tipo==0)
					{
						$sx .= '<TD class="tabela01" align="center">'.$cor.$sss[$sta].'';
					}
					
				$ins = $line['ca_insticional'];
				if ($ins == 1)
					{
						$sx .= '<TD class="tabela01" align="center">SIM'; } 
					else {
						if ($desmembramento == '1')
							{ $sx .= '<TD>DESM.'; }
						else
							{ $sx .= '<TD class="tabela01">&nbsp;'; }
					} 
				
				if (($editar==1) and ($user->user_cracha == trim($line['ca_professor'])))
					{
						$sx .= '<TD class="tabela01">'.$cor;
						$sx .= '<A HREF="'.http.'/cip/captacao_novo.php?dd0='.$line['id_ca'].'&pag=1&dd90='.checkpost($line['id_ca']).'">';
						$sx .= 'editar';
						$sx .= '</A>';
					}
				
				return($sx);
			}

		function mostra_captacao_coordenador_lista($line)
			{
				global $editar;
				$sss = $this->status();
				$pa = trim($line['ca_participacao']);
				$participacao = $this->tipo_participacao();
				
				$cor = '';
			
				if ($pa == 'O')
					{ $cor = '<font color="blue">'; }
				if ($line['ca_status']==9)
					{ $cor = '<font color="red">'; }
				$sta = round($line['ca_status']);
				$sx .= '<TR '.coluna().' valign="top">';
				$sx .= '<TD  class="tabela01">'.$cor;
				$sx .= $line['ca_agencia'];
				$sx .= '<TD colspan=2 class="tabela01">';
				$sx .= $line['ca_descricao'];
				$sx .= '<BR><font class="lt0">Atualizado '.stodbr($line['ca_update']);

				$sx .= '<TD align="center" class="tabela01">'.$cor;
				$sx .= strzero($line['ca_vigencia_ini_mes'],2);
				$sx .= '/';
				$sx .= strzero($line['ca_vigencia_ini_ano'],2);

				$sx .= '<TD align="center" class="tabela01">'.$cor;
				$sx .= $this->vigencia($line['ca_duracao']);

				$sx .= '<TD align="center" class="tabela01">'.$cor;
				$sx .= $this->vigencia($line['ca_vigencia_prorrogacao']);

				$sx .= '<TD align="center" class="tabela01">'.$cor;
				
				$sx .= $participacao[$pa];

				$sx .= '<TD align="right" class="tabela01">'.$cor;
				$sx .= number_format($line['ca_vlr_total'],2,',','.');
				$sx .= '<TD align="center" class="tabela01">'.$cor.$sss[$sta].'';
				
				$status = $line['ca_status'];
				if (($editar==1) and ($status==10))
					{
						$sx .= '<TD align="center" class="tabela01">'.$cor;
						$sx .= '<A HREF="javascript:newxy2(\'captacao_coordenador_popup.php?dd0='.$line['id_ca'].'&dd90='.checkpost($line['id_ca']).'\',770,600);">';
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
		function structure()
			{

				//$sql = "DROP TABLE captacao";
				//$rlt = db_query($sql);
				
				$sql = "CREATE TABLE captacao
					(
					id_ca serial NOT NULL,
					ca_titulo_projeto text, 
					ca_protocolo char(7),
					ca_professor char ( 8 ),
					ca_edital_codigo char (5),
					ca_agencia char ( 5 ), 
					ca_participacao char ( 1 ),
					ca_descricao char (250),
					ca_contexto text,
					ca_processo char (25),
					ca_edital_nr char ( 10 ),
					ca_edital_ano char (4),
					ca_ano char(4),
					ca_vigencia_ini_mes int,
					ca_vigencia_ini_ano int,
					ca_vigencia_fim_mes int,
					ca_vigencia_fim_ano int,
					ca_vigencia_prorrogacao int,
					ca_duracao int,
					ca_vigencia_final_mes int,
					ca_vigencia_final_ano int,
					ca_vlr_total float,
					ca_vlr_capital float,
					ca_vlr_custeio float,
					ca_vlr_bolsa float,
					ca_vlr_outros float,
					ca_proponente char (8),
					ca_proponente_vlr float,
					ca_status int,
					ca_comentario text,
					ca_comentario_direcao text,
					ca_update int,
					ca_coordenador int,
					ca_ativo int
				)";
				$rlt = db_query($sql);
			}
		function updatex()
			{
				global $base;
				$c = 'ca';
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
