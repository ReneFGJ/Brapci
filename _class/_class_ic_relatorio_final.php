<?php
class ic_relatorio_final
	{
	var $protocolo;
	var $professor;
	
	var $tabela = "pibic_parecer_2011";
	function verificar_sem_avaliacao()
		{
				$sql = "
						select pb_protocolo, total, pb_status from pibic_bolsa_contempladas 
						left join (
						 select 1 as total, pp_protocolo from pibic_parecer_".date("Y")." where (pp_status = '@' or pp_status= 'A' or pp_status = 'B') and pp_tipo = 'RFIN' group by pp_protocolo
						 ) as tabela00 on pb_protocolo = pp_protocolo
						 inner join pibic_bolsa_tipo on pbt_codigo = pb_tipo
						where pb_ano = '".(date("Y")-1)."' and pb_status <> 'C' 
							and (pbt_edital = 'PIBIC' or pbt_edital = 'PIBITI' or pbt_edital = 'IS')
							
				";
				$rlt = db_query($sql);
				$id = 0;
				while ($line = db_read($rlt))
					{
						$total = round($line['total']);
						if ($total == 0)
							{
								$sql = "update pibic_bolsa_contempladas set pb_relatorio_final_nota = 0 where pb_protocolo = '".$line['pb_protocolo']."'";
								echo $sql;
								$rrr = db_query($sql);
								$id++;
							}
					}
				echo $id;

		}
	
	function relatorio_final_reprovado($ano='2010')
		{
			$pb = new pibic_bolsa_contempladas;
				$sql = "select * from ".$pb->tabela."
					left join pibic_parecer_".date("Y")." on pp_protocolo = pb_protocolo and pp_tipo='RFIN' and pp_status = 'B' 
					inner join pibic_aluno on pb_aluno = pa_cracha 
					inner join pibic_professor on pb_professor = pp_cracha
 					inner join pibic_bolsa_tipo on pbt_codigo = pb_tipo
 					left join ajax_areadoconhecimento on pb_semic_area = a_cnpq
 					left join semic_ic_trabalho on sm_codigo = pb_protocolo
 					left join centro on pp_escola = centro_codigo 
					where pb_status <> 'C' and (pp_p01 = '-1')
					and pb_ano = '".(date("Y")-1)."'
					order by centro_nome, pp_nome, pa_nome					
				";
				$rlt = db_query($sql);
					
				$sx = '<table class="tabela00">';
				$tot = 0;
				$xesc = 'X';
				$xpro = 'X';
				while ($line = db_read($rlt))
					{
						$curso = trim($line['pa_curso']);	
						$tot++;
						$esc = $line['centro_nome'];
						$pro = $line['pp_nome'];
						if ($xesc != $esc)
							{
								$sx .= '<TR><TD colspan=5><h1>'.$esc.'</h1>';
								$xesc = $esc;
							}
						if ($xpro != $pro)
							{
								$sx .= '<TR><TD>&nbsp;';
								$sx .= '<TR><TD colspan=5><h4>'.$pro.'</h4>';
								$xpro = $pro;
							}
						$sx .= '<TR class="tabela01"><TD>';
						$sx .= $pb->mostra_simples($line);							
					}
				$sx .= '<TR><TD>Total '.$tot;
				$sx .= '</table>';

				return($sx);
		}
	
	function idicacao_avaliador_correcao_email($tst)
		{
			global $dd;
			require_once("../_class/_class_ic.php");
			$ic = new ic;
			$mmm = $ic->ic('RFIM_AVALIADOR');
			print_r($mm);
			$email_titulo = $mmm['nw_titulo'];
			$email_texto = $mmm['nw_descricao'];
			require("../pibicpr/_email.php");			
			$this->tabela = "pibic_parecer_".date("Y");
			
			/* ZERA INDICAÇÕES DE AVALIAçÂO */
			if (1==0)
			{
				$sql = "delete from ".$this->tabela." where pp_tipo = 'RPAC' ";
				$rlt = db_query($sql);
			
				$sql = "update pibic_bolsa_contempladas 
						set pb_relatorio_final_nota = -99
						where pb_relatorio_final_nota = -90 ";			
				$rlt = db_query($sql);
			}
			
			$sql = "update ".$this->tabela." set pp_data = 20130814 where pp_tipo = 'RFIN' ";
			$rlt = db_query($sql);
			
			$cp = $this->tabela.".pp_avaliador as ava_cracha, ";
			$cp .= "avaliador.pp_nome as ava_nome, ";
			$cp .= "*";
			
			$cp = 'pp_avaliador as avaliador, ';
			$cp .= 'count(*) as protocolo ';
			//$cp .= 'avaliadores.pp_nome as avaliador_nome ';
			
			$sql= "
				select * from (
				select $cp from ".$this->tabela." where pp_tipo = 'RPAC' 
					and (pp_status = '@' or pp_status = 'A')
					group by pp_avaliador
				) as tabela
				
				inner join pibic_professor as avaliadores on avaliador = avaliadores.pp_cracha
				order by pp_nome					
			";
			//inner join pibic_professor on ".$this->tabela.".pp_avaliador = pp_cracha
			
			$rlt = db_query($sql);
			
			$enviaremail = $dd[10];
			
			$sx = '<table width="100%">';
			$sx .= '<TR><TH colspan=5><H2>Relatório de Indicações de avaliadores</h2>';
			$sx .= '<TR><TH width="5%">total<TH width="30%">Avaliador
						<TH width="5%">Craha<TH width="40%">Curso
						<TH width=20%>Centro';
			$id = 0;
			$idt = 0;
			while ($line = db_read($rlt))
				{
					$link = http.'avaliador/acesso.php?dd0='.($line['pp_cracha']);
					$link .= '&dd90='.checkpost($line['pp_cracha']);
					$link = '<A HREF="'.$link.'" target="_new">'.$link.'</A>';
					$id++;
					$idt = $idt + $line['protocolo'];
					$proto = $line['protocolo'];
					$avali = $line['avaliador'];
					$avali = $line['pp_nome'];
					$statu = $line['pp_status'];
					$email1 = trim($line['pp_email']);
					$email2 = trim($line['pp_email_1']);
					$ln = $line;
					$sx .= '<TR>';
					$sx .= '<TD>'.$proto;
					$sx .= '<TD>'.$avali;
					
					$sx .= '<TD>'.$line['avaliador'];
					$sx .= '<TD>'.$line['pp_curso'];
					$sx .= '<TD>'.$line['pp_centro'];
					
					$sx .= '<TR><TD><TD colspan=5>'.$link;
					$email1 = trim($line['pp_email']);
					$email2 = trim($line['pp_email_1']);
					
					$texto = $email_texto;
					$texto = troca($texto,'$link',$link);
					$texto = troca($texto,'$nome',$avali);
					
					if (($dd[10] == -1) and ($id < 10))
						{
							//$sx .= '<BR>enviando email para monitoramento@sisdoc.com.br';
							//enviaremail('monitoramento@sisdoc.com.br','',$email_titulo,$texto);
						}
					if ($dd[10] == 1)
						{
							if (strlen($email1) > 0 ) { enviaremail($email1,'',$email_titulo,$texto); }
							if (strlen($email2) > 0 ) { enviaremail($email2,'',$email_titulo,$texto); }
							enviaremail('pibicpr@pucpr.br','',$email_titulo,$texto);
							if (strlen($email1) > 0) { $sx .= '<BR>'.$email1 .'[send]'; }
							if (strlen($email2) > 0) { $sx .= '<BR>'.$email2 .'[send]';  }
							//enviaremail('monitoramento@sisdoc.com.br','',$email_titulo,$texto);
						}
					
				}
			$sx .= '<TR><TD colspan=10>Total '.$id.' avaliadores com '.$idt.' avaliações.';
			$sx .= '</table>';
			return($sx);
		}	

	function zera_declinados_cancelados($ttipo='')
		{
			$this->tabela = "pibic_parecer_".date("Y");
			$sql = "
					select * from ".$this->tabela." 
						inner join pibic_bolsa_contempladas on pp_protocolo = pb_protocolo
						where pp_tipo = '$ttipo' 
					and (pp_status = '@' or pp_status = 'A')
			";
			$rlt = db_query($sql);
			while ($line = db_read($rlt))
				{
					$lb = trim($line['pb_status']);
					if ($lb=='C') 
						{
							$sql = "update ".$this->tabela." set pp_status = 'D' where id_pp = ".$line['id_pp'];
							//echo '<BR>'.$sql;
							$xxx = db_query($sql);
						}
				}			
		}
	function idicacao_avaliador_email($tst,$tipo='')
		{
			global $dd;
			$ttipo = 'RFIN';
			/* */
			$this->zera_declinados_cancelados($ttipo);
			
			if (strlen($tipo) > 0) { $ttipo = $tipo; }
			require_once("../_class/_class_ic.php");
			$ic = new ic;
			$mmm = $ic->ic('ic_RFIN_indicacao');
			print_r($mm);
			$email_titulo = $mmm['nw_titulo'];
			$email_texto = mst($mmm['nw_descricao']);
			require("../pibicpr/_email.php");			
			$this->tabela = "pibic_parecer_".date("Y");
			
			/* ZERA INDICAÇÕES DE AVALIAçÂO */
			if (1==0)
			{
				$sql = "delete from ".$this->tabela." where pp_tipo = '$ttipo' ";
				$rlt = db_query($sql);
			
				$sql = "update pibic_bolsa_contempladas 
						set pb_relatorio_final_nota = -99
						where pb_relatorio_final_nota = -90 ";			
				$rlt = db_query($sql);
			}
			
			$sql = "update ".$this->tabela." set pp_data = 20140818 where pp_tipo = '$ttipo' ";
			$rlt = db_query($sql);
			
			$cp = $this->tabela.".pp_avaliador as ava_cracha, ";
			$cp .= "avaliador.pp_nome as ava_nome, ";
			$cp .= "*";
			
			$cp = 'pp_avaliador as avaliador, ';
			$cp .= 'count(*) as protocolo ';
			//$cp .= 'avaliadores.pp_nome as avaliador_nome ';
			
			$sql= "
				select * from (
				select $cp from ".$this->tabela." where pp_tipo = '$ttipo' 
					and (pp_status = '@' or pp_status = 'A')
					group by pp_avaliador
				) as tabela
				
				inner join pibic_professor as avaliadores on avaliador = avaliadores.pp_cracha
				order by pp_nome					
			";
			//inner join pibic_professor on ".$this->tabela.".pp_avaliador = pp_cracha
			
			$rlt = db_query($sql);
			
			$enviaremail = $dd[10];
			
			$sx = '<table width="100%">';
			$sx .= '<TR><TH colspan=5><H2>Relatório de Indicações de avaliadores</h2>';
			$sx .= '<TR><TH width="5%">total<TH width="30%">Avaliador
						<TH width="5%">Craha<TH width="40%">Curso
						<TH width=20%>Centro';
			$id = 0;
			$idt = 0;
			echo '<HR>'.$email_texto.'<HR>';
			while ($line = db_read($rlt))
				{
					$link = http.'avaliador/acesso.php?dd0='.($line['pp_cracha']);
					$link .= '&dd90='.checkpost($line['pp_cracha']);
					$link = '<A HREF="'.$link.'" target="_new">'.$link.'</A>';
					$id++;
					$idt = $idt + $line['protocolo'];
					$proto = $line['protocolo'];
					$avali = $line['avaliador'];
					$avali = $line['pp_nome'];
					$statu = $line['pp_status'];
					$email1 = trim($line['pp_email']);
					$email2 = trim($line['pp_email_1']);
					$ln = $line;
					$sx .= '<TR>';
					$sx .= '<TD>'.$proto;
					$sx .= '<TD>'.$avali;
					
					$sx .= '<TD>'.$line['avaliador'];
					$sx .= '<TD>'.$line['pp_curso'];
					$sx .= '<TD>'.$line['pp_centro'];
					
					$sx .= '<TR><TD><TD colspan=5>'.$link;
					$email1 = trim($line['pp_email']);
					$email2 = trim($line['pp_email_1']);
					
					$texto = $email_texto;
					$texto = troca($texto,'$link',$link);
					$texto = troca($texto,'$nome',$avali);
					
					if (($dd[10] == -1) and ($id < 10))
						{
							$sx .= '<BR>enviando email para renefgj@gmail.com';
							//enviaremail('renefgj@gmail.com','','xxx','xxxx');
							enviaremail('renefgj@gmail.com','',$email_titulo,$texto);
							echo $sx;
							exit;
						}

					if ($dd[10] == 1)
						{
							if (strlen($email1) > 0 ) { enviaremail($email1,'',$email_titulo.'-'.trim($line['pp_nome']),$texto); $sx .= '<BR>'.$email1 .' [send]'; }
							if (strlen($email2) > 0 ) { enviaremail($email2,'',$email_titulo.'-'.trim($line['pp_nome']),$texto); $sx .= '<BR>'.$email2 .' [send]';}
							enviaremail('pibicpr@pucpr.br','',$email_titulo,$texto);
							////enviaremail('monitoramento@sisdoc.com.br','',$email_titulo,$texto);
						}
					
				}
			$sx .= '<TR><TD colspan=10>Total '.$id.' avaliadores com '.$idt.' avaliações.';
			$sx .= '</table>';
			return($sx);
		}	
	
	function idicacao_avaliador($mod='')
		{
			$ano = (date("Y")-1);
			if (strlen($mod) > 0)
				{
					$wh = " and (pbt_edital = '$mod') ";
				}
			$sql = "select * from pibic_bolsa_contempladas
					left join docentes on pb_professor = pp_cracha
					left join ajax_areadoconhecimento on a_cnpq = pb_semic_area  
					left join pibic_bolsa_tipo on pb_tipo = pbt_codigo
					left join pibic_aluno on pb_aluno = pa_cracha
					where (pb_relatorio_final_nota = -99 or pb_relatorio_final_nota = 99 or pb_relatorio_final_nota = 0)
					and pb_ano = '$ano' and (pbt_edital = 'PIBITI' or pbt_edital = 'PIBIC')
					and pb_status <> 'C'
					$wh
					order by a_cnpq, pp_nome
			";
			$rlt = db_query($sql);
			$sx = '<table width="100%" class="tabela00" border=0>';
			$xarea = 'x';
			$id = 0;
			while ($line = db_read($rlt))
				{
					$id++;
					$idx = trim($line['pb_protocolo']);
					$area = $line['a_cnpq'];
					if ($area != $xarea)
						{
							$sx .= '<TR class="lt2">';
							$sx .= '<TD class="tabela01" colspan=10>';
							$sx .= '<B>'.$line['a_cnpq'].' - '.$line['a_descricao'].'</B>';
							$xarea = $area;
						}
					$sx .= '<TR id="TR'.$idx.'">';
					$sx .= '<TD width="5%" align="center">';
					$sx .= '<span onclick="indicar_avaliador(\''.$idx.'\')" class="link">';
					$sx .= $line['pb_protocolo'];
					$sx .= '</span>';
					$sx .= '<TD>';
					$sx .= $line['pbt_edital'];
					$sx .= '<TD>';
					$sx .= $line['pp_nome'];
					$sx .= '<TD>';
					$sx .= $line['pa_nome'];
					$sx .= '<TD>';
					$sx .= $line['pa_curso'];					
					$sx .= chr(13);
					$sx .= '<TR id="TRI'.$idx.'" style="display:none;"><TD colspan=10>';
				}
			$sx .= '<TR><TD>Total '.$id.' indicações a efetivar';
			$sx .= '</table>'.chr(13);
			$sx .= '
					<script>
					function indicar_avaliador(id)
						{
							var trs = "#TR"+id;
							var tri = "#TRI"+id;
							$(tri).show();
							var file = "gestao_indicar_avaliador_ajax.php?dd1=ic&dd2=RFIN&dd0="+id;
							var jqxhz = $.ajax( file )
								.done(function(dados) 
									{ $( tri ).html(dados); })
								.fail(function() { alert("error#1"); });
						}
					</script>
			';
			return($sx);
		}

	function idicacao_avaliador_reprovados()
		{
			$pb = new pibic_bolsa_contempladas;
				$sql = "select * from ".$pb->tabela."
					left join pibic_parecer_".date("Y")." on pp_protocolo = pb_protocolo and pp_tipo='RFIN' and pp_status = 'B' 
					inner join pibic_aluno on pb_aluno = pa_cracha 
					inner join pibic_professor on pb_professor = pp_cracha
 					inner join pibic_bolsa_tipo on pbt_codigo = pb_tipo
 					left join ajax_areadoconhecimento on pb_semic_area = a_cnpq
 					left join semic_ic_trabalho on sm_codigo = pb_protocolo
 					left join centro on pp_escola = centro_codigo 
					where pb_status <> 'C' and (pp_p01 = '-1')
					and pb_ano = '".(date("Y")-1)."'
					order by centro_nome, pp_nome, pa_nome					
				";
			$rlt = db_query($sql);
			$sx = '<table width="100%" class="tabela00" border=0>';
			$xarea = 'x';
			$id = 0;
			while ($line = db_read($rlt))
				{
					$id++;
					$idx = trim($line['pb_protocolo']);
					$area = $line['a_cnpq'];
					if ($area != $xarea)
						{
							$sx .= '<TR class="lt2">';
							$sx .= '<TD class="tabela01" colspan=10>';
							$sx .= '<B>'.$line['a_cnpq'].' - '.$line['a_descricao'].'</B>';
							$xarea = $area;
						}
					$sx .= '<TR id="TR'.$idx.'">';
					$sx .= '<TD width="5%" align="center">';
					$sx .= '<span onclick="indicar_avaliador(\''.$idx.'\')" class="link">';
					$sx .= $line['pb_protocolo'];
					$sx .= '</span>';
					$sx .= '<TD>';
					$sx .= $line['pbt_edital'];
					$sx .= '<TD>';
					$sx .= $line['pp_nome'];
					$sx .= '<TD>';
					$sx .= $line['pa_nome'];
					$sx .= '<TD>';
					$sx .= $line['pa_curso'];					
					$sx .= chr(13);
					$sx .= '<TR id="TRI'.$idx.'" style="display:none;"><TD colspan=10>';
				}
			$sx .= '<TR><TD>Total '.$id.' indicações a efetivar';
			$sx .= '</table>'.chr(13);
			$sx .= '
					<script>
					function indicar_avaliador(id)
						{
							var trs = "#TR"+id;
							var tri = "#TRI"+id;
							$(tri).show();
							var file = "rfr_indicar_avaliador_ajax.php?dd0="+id;
							var jqxhz = $.ajax( file )
								.done(function(dados) 
									{ $( tri ).html(dados); })
								.fail(function() { alert("error#2"); });
						}
					</script>
			';
			return($sx);
		}

	
	function prazo_encerrado()
		{
			$sx = '<center>';
			$sx .= '<h1>Prazo encerrado</h1>';
			$sx .= '</center>';
			return($sx);
		}
	
	function valida_dados_crp($tipo='')
		{
			global $pb,$ged,$dd;
			$area = trim($pb->pb_semic_area);
			$idioma = trim($pb->pb_semic_idioma);
			$proto = $pb->pb_protocolo;
			
			/* Relatorio Final */
			$sql = "select count(*) as total from ".$ged->tabela."
						where doc_tipo = 'RELAF'
						and doc_dd0 = '$proto'
						and (doc_status = '@' or doc_status = 'A')
						and doc_ativo = 1
						";

			$rlt = db_query($sql);
			$relatorio = 0;
			while($line = db_read($rlt))
				{
					$relatorio = $line['total'];
				}
				
			$sx .= '<table width="100%" border=0 class="tabela00">';
			$sx .= '<TR valign="top">';
			$sx .= '<TH>'.msg('campo');
			$sx .= '<TH>'.msg('situacao');
			$sx .= '<TH>'.msg('acao');
			if ($relatorio == 0)
				{
					$sx .= '<TR>';
					$sx .= '<TD class="tabela01">Correção do relatório final';
					$sx .= '<TD class="tabela01" align="center"><font color="red"><B>'.msg('not_posted');
				} else {
					$sx .= '<TR>';
					$sx .= '<TD class="tabela01">Correção do relatório final';
					$sx .= '<TD class="tabela01" align="center"><font color="green"><B>'.msg('posted');					
				}
			$sx .= '<TD rowspan=3 class="tabela01" width="70%" valign="middle">';
			/* TUDO VALIDADO */
				if ((strlen($area) > 0) and (strlen($idioma) > 0) and ($relatorio > 0))
					{
						$sx .= '<center>';
						$sx .= '<form action="atividade_IC3_fim.php">';
						$sx .= '<input type="hidden" name="dd0" value="'.$dd[0].'">';
						$sx .= '<input type="hidden" name="dd90" value="'.checkpost($dd[0]).'">';
						$sx .= '<input value="Finalizar Reenvio do Relatório Final" class="botao-finalizar" type="submit">';
						$sx .= '</form>';
					}
			
			if (strlen($area) == 0)
				{
					$sx .= '<TR>';
					$sx .= '<TD class="tabela01">'.msg("semic_area");
					$sx .= '<TD class="tabela01" align="center"><font color="red"><B>'.msg('not_defined');
				} else {
					$sx .= '<TR>';
					$sx .= '<TD class="tabela01">'.msg("semic_area");
					$sx .= '<TD class="tabela01" align="center"><font color="green"><B>'.msg('defined');					
				}
			if (strlen($idioma) == 0)
				{
					$sx .= '<TR>';
					$sx .= '<TD class="tabela01">'.msg("semic_idioma");
					$sx .= '<TD class="tabela01" align="center"><font color="red"><B>'.msg('not_defined');
				} else {
					$sx .= '<TR>';
					$sx .= '<TD class="tabela01">'.msg("semic_idioma");
					$sx .= '<TD class="tabela01" align="center"><font color="green"><B>'.msg('defined');					
				}								
			$sx .= '</table>';
			return($sx);
		}
	function valida_dados_correcoes($tipo='')
		{
			global $pb,$ged,$dd;
			$area = trim($pb->pb_semic_area);
			$idioma = trim($pb->pb_semic_idioma);
			$proto = $pb->pb_protocolo;
			
			/* Relatorio Final */
			$sql = "select count(*) as total from ".$ged->tabela."
						where doc_tipo = 'RFC'
						and doc_dd0 = '$proto'
						and (doc_status = '@' or doc_status = 'A')
						and doc_ativo = 1
						";
			
			$rlt = db_query($sql);
			$relatorio = 0;
			while($line = db_read($rlt))
				{
					$relatorio = $line['total'];
				}
				
			$sx .= '<table width="100%" border=0 class="tabela00">';
			$sx .= '<TR valign="top">';
			$sx .= '<TH>'.msg('campo');
			$sx .= '<TH>'.msg('situacao');
			$sx .= '<TH>'.msg('acao');
			if ($relatorio == 0)
				{
					$sx .= '<TR>';
					$sx .= '<TD class="tabela01">Correção do relatório Final';
					$sx .= '<TD class="tabela01" align="center"><font color="red"><B>'.msg('not_posted');
				} else {
					$sx .= '<TR>';
					$sx .= '<TD class="tabela01">'.msg("relatorio_final");
					$sx .= '<TD class="tabela01" align="center"><font color="green"><B>'.msg('posted');					
				}
			$sx .= '<TD rowspan=3 class="tabela01" width="70%" valign="middle">';
			/* TUDO VALIDADO */
				if ((strlen($area) > 0) and (strlen($idioma) > 0) and ($relatorio == 1))
					{
						$sx .= '<center>';
						$sx .= '<form action="atividade_IC3_fim.php">';
						$sx .= '<input type="hidden" name="dd0" value="'.$dd[0].'">';
						$sx .= '<input type="hidden" name="dd90" value="'.checkpost($dd[0]).'">';
						$sx .= '<input value="Finalizar envio do Relatório Final" class="botao-finalizar" type="submit">';
						$sx .= '</form>';
					}
			
			if (strlen($area) == 0)
				{
					$sx .= '<TR>';
					$sx .= '<TD class="tabela01">'.msg("semic_area");
					$sx .= '<TD class="tabela01" align="center"><font color="red"><B>'.msg('not_defined');
				} else {
					$sx .= '<TR>';
					$sx .= '<TD class="tabela01">'.msg("semic_area");
					$sx .= '<TD class="tabela01" align="center"><font color="green"><B>'.msg('defined');					
				}
			if (strlen($idioma) == 0)
				{
					$sx .= '<TR>';
					$sx .= '<TD class="tabela01">'.msg("semic_idioma");
					$sx .= '<TD class="tabela01" align="center"><font color="red"><B>'.msg('not_defined');
				} else {
					$sx .= '<TR>';
					$sx .= '<TD class="tabela01">'.msg("semic_idioma");
					$sx .= '<TD class="tabela01" align="center"><font color="green"><B>'.msg('defined');					
				}								
			$sx .= '</table>';
			return($sx);
		}
	function valida_dados($tipo='')
		{
			global $pb,$ged,$dd;
			$area = trim($pb->pb_semic_area);
			$idioma = trim($pb->pb_semic_idioma);
			$proto = $pb->pb_protocolo;
			
			/* Relatorio Final */
			$sql = "select count(*) as total from ".$ged->tabela."
						where doc_tipo = 'RELAF'
						and doc_dd0 = '$proto'
						and (doc_status = '@' or doc_status = 'A')
						and doc_ativo = 1
						";

			$rlt = db_query($sql);
			$relatorio = 0;
			while($line = db_read($rlt))
				{
					$relatorio = $line['total'];
				}
	
			$sx .= '<table width="100%" border=0 class="tabela00">';
			$sx .= '<TR valign="top">';
			$sx .= '<TH>'.msg('campo');
			$sx .= '<TH>'.msg('situacao');
			$sx .= '<TH>'.msg('acao');
			if ($relatorio == 0)
				{
					$sx .= '<TR>';
					$sx .= '<TD class="tabela01">Relatório Final';
					$sx .= '<TD class="tabela01" align="center"><font color="red"><B>'.msg('not_posted');
				} else {
					$sx .= '<TR>';
					$sx .= '<TD class="tabela01">'.msg("relatorio_final");
					$sx .= '<TD class="tabela01" align="center"><font color="green"><B>'.msg('posted');					
				}
			$sx .= '<TD rowspan=3 class="tabela01" width="70%" valign="middle">';
			/* TUDO VALIDADO */
				if ((strlen($area) > 0) and (strlen($idioma) > 0) and ($relatorio > 0))
					{
						$sx .= '<center>';
						$sx .= '<form action="atividade_IC3_fim.php">';
						$sx .= '<input type="hidden" name="dd0" value="'.$dd[0].'">';
						$sx .= '<input type="hidden" name="dd90" value="'.checkpost($dd[0]).'">';
						$sx .= '<input value="Finalizar envio do Relatório Final" class="botao-finalizar" type="submit">';
						$sx .= '</form>';
					}
			
			if (strlen($area) == 0)
				{
					$sx .= '<TR>';
					$sx .= '<TD class="tabela01">'.msg("semic_area");
					$sx .= '<TD class="tabela01" align="center"><font color="red"><B>'.msg('not_defined');
				} else {
					$sx .= '<TR>';
					$sx .= '<TD class="tabela01">'.msg("semic_area");
					$sx .= '<TD class="tabela01" align="center"><font color="green"><B>'.msg('defined');					
				}
			if (strlen($idioma) == 0)
				{
					$sx .= '<TR>';
					$sx .= '<TD class="tabela01">'.msg("semic_idioma");
					$sx .= '<TD class="tabela01" align="center"><font color="red"><B>'.msg('not_defined');
				} else {
					$sx .= '<TR>';
					$sx .= '<TD class="tabela01">'.msg("semic_idioma");
					$sx .= '<TD class="tabela01" align="center"><font color="green"><B>'.msg('defined');					
				}								
			$sx .= '</table>';
			return($sx);
		}
function semic_crp($ged)
		{
			$sx = '';
			$this->protocolo = $ged->protocol;
			$sx .= '<table width="100%" border=0 class="tabela00">';
			
			$sx .= '<TR>';
			$sx .= '<TD width="100%" colspan=2>';
			//$sx .= $this->valida_s_crp();
			
			$sx .= '<TR>';
			$sx .= '<TD width="100%" colspan=2>';
			$sx .= $this->form_titulo();

			$sx .= '<TR valign="top">';
			$sx .= '<TD width="50%">';
			$sx .= $this->form_area_conhecimento();
			$sx .= '<TD width="50%">';
			$sx .= $this->form_idioma_apresentacao();
			
			$sx .= '<TR>';
			$sx .= '<TD width="100%" colspan=2>';
			$sx .= $ged->filelist();
						
			
			//$sx .= '<TR>';
			//$sx .= '<TD width="100%" colspan=2>';
			//$sx .= $this->form_comite_etica();
			
			$sx .= '</table>';
			return($sx);
		}

function form_crp($ged)
		{
			$sx = '';
			$this->protocolo = $ged->protocol;
			$sx .= '<table width="100%" border=0 class="tabela00">';
			
			$sx .= '<TR>';
			$sx .= '<TD width="100%" colspan=2>';
			$sx .= $this->valida_dados_crp();
						
			$sx .= '<TR>';
			$sx .= '<TD width="100%" colspan=2>';
			$sx .= $this->form_upload_arquivo($ged,'RELAF');
			
			$sx .= '<TR>';
			$sx .= '<TD width="50%">';
			$sx .= $this->form_area_conhecimento();
			$sx .= '<TD width="50%">';
			$sx .= $this->form_idioma_apresentacao();
			
			//$sx .= '<TR>';
			//$sx .= '<TD width="100%" colspan=2>';
			//$sx .= $this->form_comite_etica();
			
			$sx .= '</table>';
			return($sx);
		}
	
	function form($ged)
		{
			$sx = '';
			$this->protocolo = $ged->protocol;
			$sx .= '<table width="100%" border=0 class="tabela00">';
			
			$sx .= '<TR>';
			$sx .= '<TD width="100%" colspan=2>';
			$sx .= $this->valida_dados();
						
			$sx .= '<TR>';
			$sx .= '<TD width="100%" colspan=2>';
			$sx .= $this->form_upload_arquivo($ged,'RELAF');
			
			$sx .= '<TR>';
			$sx .= '<TD width="50%">';
			$sx .= $this->form_area_conhecimento();
			$sx .= '<TD width="50%">';
			$sx .= $this->form_idioma_apresentacao();
			
			//$sx .= '<TR>';
			//$sx .= '<TD width="100%" colspan=2>';
			//$sx .= $this->form_comite_etica();
			
			$sx .= '</table>';
			return($sx);
		}

	function form2($ged)
		{
			$sx = '';
			$this->protocolo = $ged->protocol;
			$sx .= '<table width="100%" border=0 class="tabela00">';
			
			$sx .= '<TR>';
			$sx .= '<TD width="100%" colspan=2>xx';
			$sx .= $this->valida_dados_correcoes();
						
			$sx .= '<TR>';
			$sx .= '<TD width="100%" colspan=2>';
			$sx .= $this->form_upload_arquivo($ged,'RFC');
			
			$sx .= '<TR>';
			$sx .= '<TD width="50%">';
			$sx .= $this->form_area_conhecimento();
			$sx .= '<TD width="50%">';
			$sx .= $this->form_idioma_apresentacao();
			
			//$sx .= '<TR>';
			//$sx .= '<TD width="100%" colspan=2>';
			//$sx .= $this->form_comite_etica();
			
			$sx .= '</table>';
			return($sx);
		}
	function lista_correcoes_pendentes($id_pesq)
		{
				global $tab_max;

				$sql = "select * from pibic_bolsa_contempladas ";
				$sql .= "left join pibic_aluno on pb_aluno = pa_cracha ";
				$sql .= "left join pibic_professor on pb_professor = pp_cracha 
						inner join pibic_bolsa_tipo on pbt_codigo = pb_tipo 
				";
				//$sql .= "left join pibic_submit_documento on doc_protocolo = pb_protocolo_mae ";
				//$sql .= "left join pibic_edital on pb_protocolo = pee_protocolo ";
				$sql .= " where pb_professor = '".$id_pesq."' ";
				$sql .= " and ((pb_relatorio_final_nota = 2) or (pb_relatorio_final  isnull)) ";
				$sql .= " and (pb_status <> '@' and pb_status <> 'C' ) ";
				$sql .= " and pb_ano = '".(date("Y")-1)."' ";
				$sql .= " order by pa_nome";
				$SQL .= " limit 1000 ";
				
				$rlt = db_query($sql);
				
				$sx .= '<table width="100%" align="center" border=0 class="tabela00">';
				$id = 0;
				
				while ($line = db_read($rlt))
				{
					$id++;
					$sx .= '<TR valign="top">';
					$sx .= '<TD class="tabela01">';
					$ttp = trim($line['pb_titulo_projeto']);
					$bolsa = $line['pb_codigo'];
					$bolsa_nome = $line['pbt_descricao'];
					$aluno = $line['pa_nome'];
					$status = $line['pb_status'];
					$link = '<a href="atividade_IC5_acao.php?dd0='.trim($line['pb_protocolo']).'&dd1='.$line['id_pb'].'&dd90='.checkpost(trim($line['pb_protocolo'])).'">';
					$sx .= ''.$link.$ttp.'</A>';
					$sx .= '<BR>';
					$sx .= $aluno;
					$sx .= '<BR>Modalidade: '.$bolsa.' ('.$bolsa_nome.'/'.$line['pb_ano'].')';
					$sx .= '<TD >';
					$sx .= '<form action="atividade_IC5_acao.php">';
					$sx .= '<input type="hidden" name="dd0" value="'.trim($line['pb_protocolo']).'">';
					$sx .= '<input type="hidden" name="dd1" value="'.trim($line['id_pb']).'">';
					$sx .= '<input type="hidden" name="dd90" value="'.checkpost(trim($line['pb_protocolo'])).'">';
					$sx .= '<input type="submit" value="Entregar" class="botao-geral">';
					$sx .= '</form>';
				}
				$sx .= '</table>';
				if ($id == 0)
					{
					$sx = '<font class="lt2"><B>'.msg('nenhuma_atividade').'</font>';
					}
				if ($id==0) { $sx = ''; }
				return($sx);
		}
	
	function lista_relatorios_pendentes($id_pesq)
		{
				global $tab_max;
				$sql = "update pibic_bolsa_contempladas
							set pb_relatorio_final = 0, pb_relatorio_final_nota = 0
							where (pb_ano = '".(date("Y")-1)."') and
								  (pb_relatorio_final isnull)
							";
				//$rlt = db_query($sql);
				$sql = "update pibic_bolsa_contempladas
							set pb_resumo = 0, pb_resumo_nota = 0 
							where (pb_ano = '".(date("Y")-1)."') and
								  (pb_resumo isnull)
							";
				//$rlt = db_query($sql);
				
				
				$sql = "select * from pibic_bolsa_contempladas ";
				$sql .= "left join pibic_aluno on pb_aluno = pa_cracha ";
				$sql .= "left join pibic_professor on pb_professor = pp_cracha 
						inner join pibic_bolsa_tipo on pbt_codigo = pb_tipo 
				";
				//$sql .= "left join pibic_submit_documento on doc_protocolo = pb_protocolo_mae ";
				//$sql .= "left join pibic_edital on pb_protocolo = pee_protocolo ";
				$sql .= " where pb_professor = '".$id_pesq."' ";
				//$sql .= " and ((pb_relatorio_final = 0) or (pb_relatorio_final  isnull)) ";
				$sql .= " and pb_status <> '@' and pb_status <> 'C' ";
				$sql .= " and (pb_ano = '".(date("Y")-1)."' or (pbt_edital = 'PIBIC_EM' and pb_ano = '".(date("Y"))."')) ";
				$sql .= " and pb_relatorio_final < 20000000 ";
				$sql .= " order by pa_nome";
				$SQL .= " limit 1000 ";
				$rlt = db_query($sql);
				
				$sx .= '<table width="100%" align="center" border=0 class="tabela00">';
				$id = 0;
				
				while ($line = db_read($rlt))
				{
					$id++;
					$sx .= '<TR valign="top">';
					$sx .= '<TD class="tabela01">';
					$ttp = trim($line['pb_titulo_projeto']);
					$bolsa = $line['pb_codigo'];
					$bolsa_nome = $line['pbt_descricao'];
					$aluno = $line['pa_nome'];
					$status = $line['pb_status'];
					$link = '<a href="atividade_IC3_acao.php?dd0='.trim($line['pb_protocolo']).'&dd1='.$line['id_pb'].'&dd90='.checkpost(trim($line['pb_protocolo'])).'">';
					$sx .= ''.$link.$ttp.'</A>';
					$sx .= '<BR>';
					$sx .= $aluno;
					$sx .= '<BR>Modalidade: '.$bolsa.' ('.$bolsa_nome.'/'.$line['pb_ano'].')';
					$sx .= '<TD >';
					$sx .= '<form action="atividade_IC3_acao.php">';
					$sx .= '<input type="hidden" name="dd0" value="'.trim($line['pb_protocolo']).'">';
					$sx .= '<input type="hidden" name="dd1" value="'.trim($line['id_pb']).'">';
					$sx .= '<input type="hidden" name="dd90" value="'.checkpost(trim($line['pb_protocolo'])).'">';
					$sx .= '<input type="submit" value="Entregar" class="botao-geral">';
					$sx .= '</form>';
				}
				$sx .= '</table>';
				if ($id == 0)
					{
					$sx = '<font class="lt2"><B>'.msg('nenhuma_atividade').'</font>';
					}
				return($sx);
		}

	function lista_resumos_pendentes($id_pesq='')
		{
				global $tab_max;
				
				$sql = "select * from pibic_bolsa_contempladas ";
				$sql .= "left join pibic_aluno on pb_aluno = pa_cracha ";
				$sql .= "left join pibic_professor on pb_professor = pp_cracha 
						inner join pibic_bolsa_tipo on pbt_codigo = pb_tipo 
				";
				//$sql .= "left join pibic_submit_documento on doc_protocolo = pb_protocolo_mae ";
				//$sql .= "left join pibic_edital on pb_protocolo = pee_protocolo ";
				$sql .= " where pb_professor = '".$id_pesq."' ";
				//$sql .= " and ((pb_resumo = 0) or (pb_resumo  isnull)) ";
				$sql .= " and ((pb_status <> '@' and pb_status <> 'C'  ";
				$sql .= " and pb_ano = '".(date("Y")-1)."' ) 
					or (pb_ano = '".date("Y")."' and pbt_edital = 'PIBIC_EM') 
					)
				";
				$sql .= " order by pa_nome";
				$rlt = db_query($sql);
				
				$sx .= '<table width="100%"  align="center" class="tabela00">';
				$id = 0;
				
				while ($line = db_read($rlt))
				{
					$nota = round($line['pb_resumo_nota']);
					if ($nota == 0)
					{
						$id++;
						$sx .= '<TR valign="top">';
						
						$sx .= '<TD class="tabela01" width="60">';
						$sx .= trim($line['pb_protocolo']);	
										
						$sx .= '<TD class="tabela01">';
						$ttp = trim($line['pb_titulo_projeto']);
						$bolsa = $line['pb_codigo'];
						$bolsa_nome = $line['pbt_descricao'];
						$aluno = $line['pa_nome'];
						$status = $line['pb_status'];
						$link = '<a href="atividade_IC4_acao.php?pag=1&dd0='.trim($line['pb_protocolo']).'&dd1='.$line['id_pb'].'&dd90='.checkpost(trim($line['pb_protocolo'])).'">';
						$sx .= ''.$link.$ttp.'</A>';
						$sx .= '<BR>';
						$sx .= $aluno;
						$sx .= '<BR>Modalidade: '.$bolsa.' ('.$bolsa_nome.'/'.$line['pb_ano'].')';
						$sx .= '<TD >';
						$sx .= '<form action="atividade_IC4_acao.php">';
						$sx .= '<input type="hidden" name="pag" value="1">';
						$sx .= '<input type="hidden" name="dd0" value="'.trim($line['pb_protocolo']).'">';
						$sx .= '<input type="hidden" name="dd1" value="'.trim($line['id_pb']).'">';
						$sx .= '<input type="hidden" name="dd90" value="'.checkpost(trim($line['pb_protocolo'])).'">';
						$sx .= '<input type="submit" value="Postar" class="botao-geral">';
						$sx .= '</form>';
					}
				}
				$sx .= '</table>';
				if ($id == 0)
					{
					$sx = '<font class="lt2"><B>'.msg('nenhuma_atividade').'</font>';
					}
				return($sx);
		}

	function lista_relatorios_pendentes_correcoes($id_pesq)
		{
				global $tab_max;
				$sql = "select * from pibic_bolsa_contempladas ";
				$sql .= "left join pibic_aluno on pb_aluno = pa_cracha ";
				$sql .= "left join pibic_professor on pb_professor = pp_cracha 
						inner join pibic_bolsa_tipo on pbt_codigo = pb_tipo 
				";
				//$sql .= "left join pibic_submit_documento on doc_protocolo = pb_protocolo_mae ";
				//$sql .= "left join pibic_edital on pb_protocolo = pee_protocolo ";
				$sql .= " where pb_professor = '".$id_pesq."' ";
				//$sql .= " and ((pb_relatorio_final = 0) or (pb_relatorio_final  isnull)) ";
				$sql .= " and (pb_status <> '@' and pb_status <> 'C' ) ";
				$sql .= " and pb_ano = '".(date("Y")-1)."' ";
				$sql .= " 
							and pb_relatorio_final_nota = 2
						";
				$sql .= " order by pa_nome";
				$SQL .= " limit 1000 ";
				
				$rlt = db_query($sql);
				$sx = '<center>';
				$sx .= '<table width="88%" align="center" border=0 class="tabela01">';
				$id = 0;
				
				while ($line = db_read($rlt))
				{
					$rpc = round($line['pb_relatorio_final_correcao']);
					if ($rpc < 20100101)
					{
					$id++;
					$sx .= '<TR valign="top">';
					$sx .= '<TD>';
					$ttp = trim($line['pb_titulo_projeto']);
					$bolsa = $line['pb_codigo'];
					$bolsa_nome = $line['pbt_descricao'];
					$aluno = $line['pa_nome'];
					$status = $line['pb_status'];
					$link = '<a href="atividade_IC3_acao.php?dd0='.trim($line['pb_protocolo']).'&dd1='.$line['id_pb'].'&dd90='.checkpost(trim($line['pb_protocolo'])).'">';
					$sx .= ''.$link.$ttp.'</A>';
					$sx .= '<BR>';
					$sx .= $aluno;
					$sx .= '<BR>Modalidade: '.$bolsa.' ('.$bolsa_nome.'/'.$line['pb_ano'].')';
					$sx .= '<TD>';
					$sx .= '<form action="atividade_IC3_acao.php">';
					$sx .= '<input type="hidden" name="dd0" value="'.trim($line['pb_protocolo']).'">';
					$sx .= '<input type="hidden" name="dd1" value="'.trim($line['id_pb']).'">';
					$sx .= '<input type="hidden" name="dd90" value="'.checkpost(trim($line['pb_protocolo'])).'">';
					$sx .= '<input type="submit" value="Entregar" class="botao-geral">';
					$sx .= '</form>';
					}
				}
				$sx .= '</UL>';
				if ($id == 0)
					{
					$sx .= '<TR><TD colspan=5>';
					$sx .= '<h3>'.msg('nenhuma_atividade');
					}
				return($sx);
		}
	
	function lista_validacoes_semic($id_pesq)
		{
				global $tab_max;
				$sql = "select * from pibic_bolsa_contempladas ";
				$sql .= "left join pibic_aluno on pb_aluno = pa_cracha ";
				$sql .= "left join pibic_professor on pb_professor = pp_cracha 
						inner join pibic_bolsa_tipo on pbt_codigo = pb_tipo 
				";
				//$sql .= "left join pibic_submit_documento on doc_protocolo = pb_protocolo_mae ";
				//$sql .= "left join pibic_edital on pb_protocolo = pee_protocolo ";
				$sql .= " where pb_professor = '".$id_pesq."' ";
				//$sql .= " and ((pb_relatorio_final = 0) or (pb_relatorio_final  isnull)) ";
				$sql .= " and (pb_status <> '@' and pb_status <> 'C' ) ";
				$sql .= " and pb_ano = '".(date("Y")-1)."' ";
				$sql .= " 
							and pb_semic_ratificado < 20000101
						";
				$sql .= " order by pa_nome";
				$SQL .= " limit 1000 ";
				
				$rlt = db_query($sql);
				$sx = '<center>';
				$sx .= '<table width="88%" align="center" border=0 class="tabela01">';
				$id = 0;
				
				while ($line = db_read($rlt))
				{
					$rpc = round($line['pb_semic_ratificado']);
					if ($rpc < 20100101)
					{
					$id++;
					$sx .= '<TR valign="top">';
					$sx .= '<TD>';
					$ttp = trim($line['pb_titulo_projeto']);
					$bolsa = $line['pb_codigo'];
					$bolsa_nome = $line['pbt_descricao'];
					$aluno = $line['pa_nome'];
					$status = $line['pb_status'];
					$link = '<a href="atividade_RRS_acao.php?dd0='.trim($line['pb_protocolo']).'&dd1='.$line['id_pb'].'&dd90='.checkpost(trim($line['pb_protocolo'])).'">';
					$sx .= ''.$link.$ttp.'</A>';
					$sx .= '<BR>';
					$sx .= $aluno;
					$sx .= '<BR>Modalidade: '.$bolsa.' ('.$bolsa_nome.'/'.$line['pb_ano'].')';
					$sx .= '<TD>';
					$sx .= '<form action="atividade_RRS_acao.php">';
					$sx .= '<input type="hidden" name="dd0" value="'.trim($line['pb_protocolo']).'">';
					$sx .= '<input type="hidden" name="dd1" value="'.trim($line['id_pb']).'">';
					$sx .= '<input type="hidden" name="dd90" value="'.checkpost(trim($line['pb_protocolo'])).'">';
					$sx .= '<input type="submit" value="Entregar" class="botao-geral">';
					$sx .= '</form>';
					}
				}
				$sx .= '</UL>';
				if ($id == 0)
					{
					$sx .= '<TR><TD colspan=5>';
					$sx .= '<h3>'.msg('nenhuma_atividade');
					}
				return($sx);
		}

	function form_upload_arquivo($ged)
		{
		global $ged;
			//$sql = "update ".$ged->tabela."
			//		set doc_status = 'B' 
			//		where doc_tipo <> 'RELAP'
			//";
			//$rlt = db_query($sql);
			$sx = '<div id="geds">';
			$sx .= '<div>';	
			$file = 'atividade_IC3_ajax.php?dd0='.$ged->protocol.'&dd1='.$ged.'&dd90=ged';
			$sx .= '
				<script>
					var jqxhr = $.ajax( "'.$file.'" )
					.done(function(dados) { var tela=1;  $("#geds").html(dados); })
					.fail(function() { alert("error#3"); })
				</script>';
			return($sx);	
		}
	function mostra_titulo($pb)
		{
			$sx = '<fieldset>';
			$sx .= '<legend class="legend01">Título da pesquisa do aluno</legend>';
			$sx .= '<BR>'.msg('semic_tit_por').'<BR>';
			$sx .= '<textarea id="titulo_id" class "lt4" rows=3 cols=80 style="width:98%;">'.$pb->pb_titulo_plano.'</textarea>';
			
			$sx .= '<BR><BR>'.msg('semic_tit_eng').'<BR>';
			$sx .= '<textarea id="titulo_en" class "lt4" rows=3 cols=80 style="width:98%;">'.$pb->pb_titulo_en.'</textarea>';
			
			$sx .= '<BR>'.msg('semic_tit_inf').'<BR>';
			$sx .= '<input type="button" class="botao-confirmar" id="botao_titulo" value="Ratificar ou retificar título">';
			
			$sx .= '</fieldset>';
			
			$file = 'atividade_IC3_ajax.php?dd0='.$dd[0].'&dd90=titulo&dd2=save';
			
			$sx .= '
				<script>
				$("#botao_titulo").click(function() {
					var titulo = $("#titulo_id").val();
					var titulo_2 = $("#titulo_en").val();
					var protocolo = "'.$pb->pb_protocolo.'"; 
					var jqxhz = $.ajax({ 
						url: "'.$file.'",
						data: { dd7: protocolo, dd4: titulo, dd5: "titulo", dd6: titulo_2  } } )
					.done(function(dados) { var tela=2;  $("#titulo").html(dados); })
					.fail(function() { alert("error#4"); });
				});					
				</script>';			
			return($sx);
		}		
	function form_titulo()
		{
			$sx = '<div id="titulo">';
			$sx .= '<div>';	
			$file = 'atividade_IC3_ajax.php?dd0='.$this->protocolo.'&dd90=titulo';
			$sx .= '
				<script>
					var jqxhz = $.ajax( "'.$file.'" )
					.done(function(dados) { var tela=2;  $("#titulo").html(dados); })
					.fail(function() { alert("error#5"); })
				</script>';
			return($sx);
		}
			
	function form_area_conhecimento()
		{
			$sx = '<div id="area">';
			$sx .= '<div>';	
			$file = 'atividade_IC3_ajax.php?dd0='.$this->protocolo.'&dd90=area';
			$sx .= '
				<script>
					var jqxhz = $.ajax( "'.$file.'" )
					.done(function(dados) { var tela=2;  $("#area").html(dados); })
					.fail(function() { alert("error#6"); })
				</script>';
			return($sx);
		}
		function form_area_conhecimento_mostra()
			{			
			global $pb,$dd;
			
			$sql = "select * from ajax_areadoconhecimento 
					where a_semic = 1
					order by a_cnpq
					";
			$rlt = db_query($sql);
			$area = trim($pb->pb_semic_area);
			
			$sa = '<select id="area_cnpq" class="form01">';
			$sa .= '<option value="">:: Não definido ::</option>';
			while ($line = db_read($rlt))
				{
				$vlr = $line['a_cnpq'];
				$disabled = '';
				
				if (substr($vlr,2,2)=='00') { $disabled = ' disabled '; }
				$selected = '';
				if (trim($line['a_cnpq'])==$area) { $selected = 'selected'; }
				
				$sa .= '<option value="'.$vlr.'" '.$disabled.' '.$selected.'>';
				$sa .= $line['a_cnpq'].' - '.trim($line['a_descricao']);
				$sa .= '</option>';
				}
			$sa .= '</select>';
			
			$sx = '<fieldset class="fieldset01">';
			$sx .= '<legend class="legend01">'.msg('semic_area').'</legend>';
			$sx .= '<div id="aba1-aluno" style="display:inline;">';
				$sx .= '<div id="area-de-conhecimento">';
				$sx .= '<P>'.msg('semic_area_info').'</br>';
				$sx .= $sa;
				$sx .= '<input type="button" class="botao-confirmar" id="botao_area" value="Confirmar">';
				$sx .= '</p>';
				$sx .= '</div>';			
			$sx .= '</div>';
			$sx .= '</fieldset>';
			
			$file = 'atividade_IC3_ajax.php?dd0='.$dd[0].'&dd90=area&dd2=save';
			$sx .= '
				<script>
				$("#botao_area").click(function() {
					var area = $("#area_cnpq  option:selected").val();
					var jqxhz = $.ajax({ 
						url: "'.$file.'",
						data: { dd4: area, dd5: "area" } } )
					.done(function(dados) { var tela=2;  $("#area").html(dados); })
					.fail(function() { alert("error#7"); });
				});					
				</script>';			
			
			
			return($sx);
		}
	function form_idioma_apresentacao()
		{
			$sx = '<div id="idioma">';
			$sx .= '<div>';	
			$file = 'atividade_IC3_ajax.php?dd0='.$this->protocolo.'&dd90=idioma';
			$sx .= '
				<script>
					var jqxhz = $.ajax( "'.$file.'" )
					.done(function(dados) { var tela=2;  $("#idioma").html(dados); })
					.fail(function() { alert("error#8"); })
				</script>';
			return($sx);
		}

	function form_idioma_apresentacao_mostra()
		{
			global $pb,$dd;
			
			$idioma = $pb->pb_semic_idioma;
			if ($idioma == 'pt_BR') { $op1 = 'selected'; }
			if ($idioma == 'en_US') { $op2 = 'selected'; }
			
			$sa = '<select id="idioma_apresentacao" class="form01">';
			$sa .= '<option value="">:: Não definido ::</option>';
			$sa .= '<option value="pt_BR" '.$op1.'>Portugues</option>';
			$sa .= '<option value="en_US" '.$op2.'>Inglês</option>';
			$sa .= '</select>';
			
			$sx = '<fieldset class="fieldset01">';
			$sx .= '<legend class="legend01">'.msg('semic_idioma').'</legend>';
			$sx .= '<div id="aba1-aluno" style="display:inline;">';
				$sx .= '<div id="area-de-conhecimento">';
				$sx .= '<P>'.msg('semic_idioma_info').'<BR>';
				$sx .= $sa;
				$sx .= '<input type="button" class="botao-confirmar" id="botao_idioma" value="Confirmar">';
				$sx .= '</p>';
				$sx .= '</div>';			
			$sx .= '</div>';
			$sx .= '</fieldset>';	
			
			$file = 'atividade_IC3_ajax.php?dd0='.$dd[0].'&dd90=idioma&dd2=save';
			$sx .= '
				<script>
				$("#botao_idioma").click(function() {
					var area = $("#idioma_apresentacao  option:selected").val();
					var jqxhz = $.ajax({ 
						url: "'.$file.'",
						data: { dd4: area, dd5: "idioma" } } )
					.done(function(dados) { var tela=2;  $("#idioma").html(dados); })
					.fail(function() { alert("error#9"); });
				});					
				</script>';						
			return($sx);
		}
	function form_comite_etica()
		{
			$nrt = $this->ethic_parecer;
			$nrt = $this->ethic_parecer_justify;
			$sx = '<fieldset class="fieldset01">';
			$sx .= '<legend class="legend01">'.msg('ic_etica').'</legend>';
			$sx .= '<div id="area-de-conhecimento">';
			$sx .= '<P>'.msg('ic_etica_info').'<BR>';
			$sx .= '<input type="text" width="20" maxsize=20 value="'.$nrt.'" id = "ethic-protocol"  class="form01">';
			
			$sx .= '<BR><BR>'.msg('frin_ethic_justify').'<BR>';
			$sx .= '<textarea rows=3 cols=60 id="ethic-protocol-justify" class="form01">';
			$sx .= $nrj;
			$sx .= '</textarea>';
			$sx .= '<BR>';
			$sx .= '<button class="botao-confirmar">Confirmar</button>';
			$sx .= '</p>';
			$sx .= '</div>';
			$sx .= '</fieldset>';
			return($sx);
		}
	}
?>