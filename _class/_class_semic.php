<?php;
    /**
     * Caixa Central
	 * @author Rene Faustino Gabriel Junior <monitoramento@sisdoc.com.br>
	 * @copyright Copyright (c) 2013 - sisDOC.com.br
	 * @access public
     * @version v0.13.24
	 * @package Semic
	 * @subpackage classe
    */
    
class semic
	{
		
	var $jid;
	var $id;
	var $journal;
	var $path;
	var $evento = 'SEMIC22';
	var $mostra = 'MP16';
	
	var $erro = '';
	
	var $status;
	var $line;
	
	var $resumo_01;
	var $resumo_02;
	var $key_01;
	var $key_02;
	
	var $revisor;
	
	var $tabela = "semic_trabalho";
	var $tabela_autor = "semic_trabalho_autor";
	
	
	function pibic_semic_participacao($tipo='',$modalidade='')
		{
			$sql = "update pibic_semic_avaliador_notas set av_status = 9 where av_area = 'iPPGIa' ";
			$rlt = db_query($sql);

			$wh = '';
			$modalidade = 'O';
			if (strlen($modalidade) > 0)
				{ $wh .= " where av_tipo_trabalho='".$modalidade."' ";}
				
			$sql = "select * from (			
						select count(*) as total, max(av_status) as av_status, av_area, av_numtrab, 
						max(av_final) as av_final
						from pibic_semic_avaliador_notas
						$wh
						group by av_area, av_numtrab
						order by av_area, av_numtrab
					) as tabela
					where (av_final < 40 or av_status < 1)
					";
			echo $sql;
			$rlt = db_query($sql);
			$sx .= '<h1>Trabalhos sem apresentação / sem avaliação</h1>';
			while ($line = db_read($rlt))
			{
				$cod = trim($line['av_area']);
				$wor = strzero(trim($line['av_numtrab']),2);
				$codp = trim($line['']);
				$sx .= $cod.$wor.$codp.'---'.$line['av_status'].' ['.$line['av_final'].']';
				$sx .= '<BR>';
			}
			return($sx);
		}
	
	function lista_declaracoes()
		{
			$sql = "select doc_tipo from pibic_documento 
				where doc_data > ".date("Y").'0000
				group by doc_tipo
				';
			$rlt = db_query($sql);
			
			while ($line = db_read($rlt))
				{
					
					print_r($line);
				}
		}
	
	function autor_tipo($funcao)
		{
			switch ($funcao)
					{
					case "0": $funcao = "Discente"; $est=1; break;
					case "1": $funcao = "Orientador"; $ori=1; break;
					case "2": $funcao = "Co-orientador"; break;
					case "3": $funcao = "Colaborador"; break;
					case "7": $funcao = "Mestrando de Pós-Graduação"; break;
					case "8": $funcao = "Doutorando de Pós-Graduação"; break;
					case "4": $funcao = "Pibic Junior"; break;
					case "5": $funcao = "Supervisor Pibic Junior"; break;
					case "6": $funcao = "Escola"; break;
					case "9": $funcao = "Orientador"; $ori=1; break;
					}
			return($funcao);		
		}	
	
	function convert_to_article()
		{
			$id = $this->recupera_jid_do_semic();
		}
	
	function mostra_projetos_area($ano='')
		{
			global $dd;
			if (strlen($dd[1]) > 0)
				{
					if ($dd[1]=='PIBIC')
						{ $wh = " and ( pbt_edital = 'PIBIC' or pbt_edital = 'IS') "; }
					if ($dd[1]=='PIBITI')
						{ $wh = " and ( pbt_edital = 'PIBITI' ) "; }
					if ($dd[1]=='PIBICE')
						{ $wh = " and ( pbt_edital = 'PIBICE' ) "; }
				}
				$sql = "select pb_semic_area, a_cnpq, a_descricao, count(*) as total,
					pbt_edital
					from pibic_bolsa_contempladas
					inner join 
						(select pp_protocolo from pibic_parecer_".date("Y")." where pp_tipo='RFIN' and pp_status = 'B' group by pp_protocolo) as tabela20 on pp_protocolo = pb_protocolo 
					inner join pibic_aluno on pb_aluno = pa_cracha 
					inner join pibic_professor on pb_professor = pp_cracha
 					inner join pibic_bolsa_tipo on pbt_codigo = pb_tipo
 					left join ajax_areadoconhecimento on pb_semic_area = a_cnpq
 					left join semic_ic_trabalho on sm_codigo = pb_protocolo
 					left join centro on pp_escola = centro_codigo 
					where pb_status <> 'C'
					and pb_ano = '".(date("Y")-1)."'
					$wh
					group by pb_semic_area, a_cnpq, a_descricao, pbt_edital
					order by pb_semic_area, a_cnpq					
				";
			$rlt = db_query($sql);
			$sx .= '<table class="tabela00">';
			$tot = 0;
			$total = 0;
			while ($line = db_read($rlt))
				{
					$area = $line['pb_semic_area'];
					$ar = substr($area,0,1);
					if ($ar != $xar)
						{
							if ($tot > 0)
								{
									$sx .= '<TR><TD colspan=2 ><B>Total '.$tot.'</B>';	
									$tot = 0;	
								}
							$sx .= '<TR><TD colspan=2><h3>'.$area.' - '.$line['a_descricao'].'</h3>';
							$sx .= '<TR><TH>Total<TH>Área<TH>Modalidade';
							$xar = $ar;
						}
							$sx .= '<TR><TD align="center" class="tabela01">'.$line['total'].'</TD>
									<TD class="tabela01">'.$area.' - '.$line['a_descricao'].'</TD>';
							$sx .= '<TD class="tabela01">'.$line['pbt_edital'].'</TD>';														
						
					$tot = $tot + $line['total'];
					$total = $total + $line['total'];
				}
			if ($tot > 0)
					{
						$sx .= '<TR><TD colspan=2><B>Total '.$tot.'</B>';	
						$tot = 0;	
					}
			$sx .= '<TR><TD colspan=2><B>Total geral '.$total.'</B>';					
			$sx .= '</table>';
			echo $sx;
		}
	
	function gravar_resumo_ingles($text,$login)
		{
			$sql = "update ".$this->tabela." set 
				sm_resumo_02 = '".$text."',
				sm_revisor = '".$login."', 	
				sm_status = 'D'		
			where id_sm = ".$this->id;	
			$rlt = db_query($sql);
			return(1);		
		}
	
	function postar_resumo_ingles()
		{
			global $dd;
			$sx .= '<div style="display:none;" id="resumos_post">';
			$sx .= '<form method="post" action="'.page().'">';
			$sx .= 'Insirar o <I>abstract</I> abaixo:<BR>';
			$sx .= '<textarea cols=100 rows=10 name="dd10"  style="width:100%">'.$dd[10].'</textarea>';
			$sx .= '<BR>';
			$sx .= '<input type="hidden" name="dd0" value="'.$dd[0].'">
					<input type="hidden" name="dd90" value="'.$dd[90].'">
					<input type="hidden" name="pag" value="1">
					<input type="submit" class="botao-geral" value="'.('enviar revisão').'" id="bt_post">';
			$sx .= '</form>';
			$sx .= '</div>';
			
			$sx .= '<input type="button" class="botao-geral" value="'.('postar revisão').'" id="bt_revisao">';
			$sx .= '
				<script>
				$("#bt_revisao").click(function() {
					$("#resumos_post").fadeIn();
					$("#bt_revisao").hide();
					
				})
				</script>
			';
			return($sx);
				
		}	
	
	function semic_bolsa_localiza($proto)
		{
			if (strlen($proto)==7)
				{
				//$sql = "delete from ".$this->tabela." where sm_codigo = '".$proto."' ";
				//$rlt = db_query($sql);
				//$sql = "delete from ".$this->tabela."_autor where sma_protocolo = '".$proto."' ";
				//$rlt = db_query($sql);
				
				$sql = "select * from ".$this->tabela." where sm_codigo = '".$proto."' ";
				$rlt = db_query($sql);
				if ($line = db_read($rlt))
					{ return(1); } 
					else
					{
						 
						$sql = "select * from pibic_bolsa_contempladas 
							inner join pibic_bolsa_tipo on pbt_codigo = pb_tipo
							inner join pibic_professor on pb_professor = pp_cracha
							inner join pibic_aluno on pb_aluno = pa_cracha
							where pb_protocolo = '".$proto."'";
						$rlt = db_query($sql);
						$line = db_read($rlt);
						
						$proto = $line['pb_protocolo'];
						$tit1 = LowerCase(trim($line['pb_titulo_projeto']));

						$tit1b = substr($tit1,1,strlen($tit1));
						$tit1a = UpperCase(substr($line['pb_titulo_projeto'],0,1));
						$tit1 = $tit1a.$tit1b;	
						$tit1 = troca($tit1,chr(13),'');
						$tit1 = troca($tit1,chr(10),'');					
						
						$docente = $line['pb_professor'];
						$aluno = $line['pb_aluno'];
						$moda = $line['pbt_edital'];
						if ($moda == 'PIBIC_EM') { $moda = 'PIBICE'; }
						$ano = $line['pb_ano'];
						$data = date("Ymd");
						$modalidade = $line['pb_tipo'];
						$aluno_nome = $line['pa_nome'];
						$professor_nome = $line['pp_nome'];
						
						$sql = "insert into ".$this->tabela."
							( sm_codigo, sm_titulo, sm_titulo_en,
							sm_docente, sm_discente, sm_edital,
							sm_ano, sm_lastupdate, sm_status,
							sm_modalidade 
							) values (
							'$proto','$tit1','',
							'$docente','$aluno','$moda',
							'$ano',$data,'@',
							'$modalidade')
						";
						$rlt = db_query($sql);
						
						/* Insere o Professor */
						$sql = "insert into ".$this->tabela."_autor
								(sma_protocolo, sma_nome, sma_funcao,
								sma_instituicao, sma_ativo, sma_titulacao)
								values
								('$proto','$professor_nome','9',
								'PUCPR',1,'')";
						$rlt = db_query($sql);
						
						/* Insere o Aluno */
						$sql = "insert into ".$this->tabela."_autor
								(sma_protocolo, sma_nome, sma_funcao,
								sma_instituicao, sma_ativo, sma_titulacao)
								values
								('$proto','$aluno_nome','0',
								'PUCPR',1,'')";
						$rlt = db_query($sql);					
					}
				} else {
					echo 'Erro de código de protocolo';
					exit;
				}
		}
	
	function semic_le($id)
		{
			$sql = "select * from ".$this->tabela." where id_sm = ".round($id);
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					$this->line = $line;
					$this->status = $line['sm_status'];
					$this->revisor = $line['sm_revisor'];
					$this->resumo_01 = $line['sm_resumo_01'];
					$this->resumo_02 = $line['sm_resumo_02'];
					$this->key_01 = $line['sm_rem_06'];
					$this->key_02 = $line['sm_rem_16'];
					$this->id = $line['id_sm'];						
				}
			$tot = 0;
			return($tot);
		}
		
	function resumo_corrigir($prof)
		{
			$sql = "select count(*) as total from ".$this->tabela." 
				where sm_docente = '".$prof."'
				and (sm_status = 'C' or sm_status = '@')
				";
			$rlt = db_query($sql);
			$line = db_read($rlt);
			$total = $line['total'];
			return($total);
			
		}
	function comunicar_pesquisador($texto='',$prof='')
		{
			global $jid;
			$jid = '';
			$doc = new docentes;
			$doc->le($prof,$prof);
			$email1 = $doc->pp_email;
			$email1 = $doc->pp_email_1;
			
			$ic = new ic;
			$tela = $ic->ic('semic_devolve');
			
			$titulo = $tela['nw_titulo'].' '.strzero($this->line['id_sm'],7);
			$texto = $tela['nw_descricao']. '<BR><BR><B>'.$texto.'</B>';
			$texto .= '<BR><BR>Protocolo: '.strzero($this->line['id_sm'],7);
			
			enviaremail($email1,'',$titulo,$texto);
		}
		
	function semic_adm_acao()
		{
			global $dd,$acao;
			if (strlen($dd[0]) > 0)
			{
				$this->semic_le($dd[0]);
				if (strlen($acao) == 0) {$dd[2] = $this->line['sm_obs']; }
				
				if ((strlen($acao) > 0) and (strlen($dd[1]) > 0))
					{
						if ($dd[1]=='C')
							{
								$prof = $this->line['sm_docente'];
								$this->comunicar_pesquisador($dd[2],$prof);
								echo '<HR>Enviado para correções<HR>';
							}

						$sql = "update ".$this->tabela." set sm_status = '".$dd[1]."'
								, sm_obs = '".$dd[2]."'
								where id_sm = ".$dd[0];
						$rlt = db_query($sql);
						
						
						redirecina(page().'?dd0='.$dd[0].'&dd90='.$dd[90]);
						exit;						
					}
				$sx = '<form method="post" action="'.page().'">';
				$sx .= '<input type="hidden" name="dd0" value="'.$dd[0].'">';
				$sx .= '<input type="hidden" name="dd90" value="'.$dd[90].'">';
				$sx .= '<fieldset><legend>Ações</legend>';
				$sx .= '<table width="100%" class="tabela00">';
				$sx .= '<TR><TD>Comentários e observações';
				$sx .= '<TR><TD><textarea name="dd2" cols=80 rows=5>'.$dd[2].'</textarea>';
				if ($this->line['sm_status']=='@')
					{
					$sx .= '<TR><TD><input type="radio" name="dd1" value="A"> aceitar submissão';
					}				
				if ($this->line['sm_status']=='A')
					{
					$sx .= '<TR><TD><input type="radio" name="dd1" value="X"> Cancelar submissão';
					$sx .= '<TR><TD><input type="radio" name="dd1" value="B"> aceitar submissão';
					$sx .= '<TR><TD><input type="radio" name="dd1" value="C"> enviar para correções';
					}
				if ($this->line['sm_status']=='B')
					{
					$sx .= '<TR><TD><input type="radio" name="dd1" value="X"> Cancelar submissão';
					$sx .= '<TR><TD><input type="radio" name="dd1" value="B"> aceitar submissão';
					$sx .= '<TR><TD><input type="radio" name="dd1" value="C"> enviar para correções';
					}					
				if ($this->line['sm_status']=='D')
					{
					$sx .= '<TR><TD><input type="radio" name="dd1" value="B"> Indicar para nova revisão';
					$sx .= '<TR><TD><input type="radio" name="dd1" value="B"> aceitar submissão';
					}					
				if ($this->line['sm_status']=='E')
					{
					$sx .= '<TR><TD><input type="radio" name="dd1" value="B"> Indicar para nova revisão';
					$sx .= '<TR><TD><input type="radio" name="dd1" value="B"> aceitar submissão';
					}					
				if ($this->line['sm_status']=='X')
					{
					$sx .= '<TR><TD><input type="radio" name="dd1" value="A"> Reenviar para análise';
					$sx .= '<TR><TD><input type="radio" name="dd1" value="B"> aceitar submissão';
					}
				if ($this->line['sm_status']=='@')
					{
					$sx .= '<TR><TD><input type="radio" name="dd1" value="A"> Enviar para análise';
					$sx .= '<TR><TD><input type="radio" name="dd1" value="B"> aceitar submissão';
					$sx .= '<TR><TD><input type="radio" name="dd1" value="X"> Cancelar submissão';
					}
				if ($this->line['sm_status']=='C')
					{
					$sx .= '<TR><TD><input type="radio" name="dd1" value="A"> Enviar para análise';
					}
				$sx .= '</table>';
				$sx .= '<input type="submit" name="acao" value="'.msg('save').'">';
				$sx .= '</fieldset>';
				$sx .= '</form>';
			}
			return($sx);
		}		
	
	function semic_resumo_checar($id)
		{
			
				$sql = "select * from ".$this->tabela." where id_sm = ".round($id)." or sm_codigo = '".$id."'";
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					if (strlen($line['sm_rem_01'])==0) { $erro .= 'Falta <B>Introdução</B>.<BR>'; }
					if (strlen($line['sm_rem_02'])==0) { $erro .= 'Falta(m) o(s) <B>Objetivo(s)</B>.<BR>'; }
					if (strlen($line['sm_rem_03'])==0) { $erro .= 'Falta o <B>Método</B>.<BR>'; }
					//if (strlen($line['sm_rem_04'])==0) { $erro .= 'Falta os <B>Resultados</B>.<BR>'; }
					//if (strlen($line['sm_rem_05'])==0) { $erro .= 'Faltam as <B>Conclusões ou considerações</B>.<BR>'; }
					if (strlen($line['sm_rem_06'])==0) { $erro .= 'Faltam as <B>Palavras-chave</B>.<BR>'; }

					if (strlen($line['sm_rem_11'])==0) { $erro .= 'Falta <B>Introdução</B> em Inglês.<BR>'; }
					if (strlen($line['sm_rem_12'])==0) { $erro .= 'Falta(m) o(s) <B>Objetivo(s)</B> em Inglês.<BR>'; }
					if (strlen($line['sm_rem_13'])==0) { $erro .= 'Falta o <B>Método</B> em Inglês.<BR>'; }
					//if (strlen($line['sm_rem_14'])==0) { $erro .= 'Falta os <B>Resultados</B> em Inglês.<BR>'; }
					//if (strlen($line['sm_rem_15'])==0) { $erro .= 'Faltam as <B>Conclusões ou considerações</B> em Inglês.<BR>'; }
					if (strlen($line['sm_rem_16'])==0) { $erro .= 'Faltam as <B>Palavras-chave</B> em Inglês.<BR>'; }
					
					$rs1 = trim($line['sm_rem_01']).' ';
					$rs1 .= trim($line['sm_rem_02']).' ';
					$rs1 .= trim($line['sm_rem_03']).' ';
					$rs1 .= trim($line['sm_rem_04']).' ';
					$rs1 .= trim($line['sm_rem_05']).' ';
					$rs1 = troca($rs1,' ',';');
					$total = count(splitx(';',$rs1));
					
					if ($total < 150) { $erro .= '<BR>O Resumo tem menos de 150 palavras'; }
					if ($total > 600) { $erro .= '<BR>O Resumo tem mais de 500 palavras'; }
					
					$rs1 = trim($line['sm_rem_11']).' ';
					$rs1 .= trim($line['sm_rem_12']).' ';
					$rs1 .= trim($line['sm_rem_13']).' ';
					$rs1 .= trim($line['sm_rem_14']).' ';
					$rs1 .= trim($line['sm_rem_15']).' ';
					$rs1 = troca($rs1,' ',';');
					$total = count(splitx(';',$rs1));

					if ($total < 150) { $erro .= '<BR>O Resumo em inglês tem menos de 150 palavras'; }
					if ($total > 600) { $erro .= '<BR>O Resumo em inglês tem mais de 500 palavras'; }
				}
			if (strlen($erro) > 0)
				{
					$this->erro = 'do resumo é obrigatório<BR></font><BR>'.$erro.'Este item ';
					return('');
				}
			return('ok');			
		}
		
	function mostra_ingles()
		{
			global $edit,$ed2;
			$idx=$this->id;
			$ed2 = 'semic_';
			if ($edit==1)
				{
				$link0T = '<A HREF="#" onclick="newxy2(\'abstract_'.$ed2.'ed.php?dd0='.$idx.'&dd1=resumo_02\',600,400);">';
				}
			$sx .= '<BR><BR><i>Abstract</I><BR>';
			$sx .= '<div style="text-align: justify">';
			$sx .= '<font color="#000000">';
			$sx .= $link0T.$this->resumo_02.'</A>';
			$sx .= '<BR><BR>';
			$sx .= '<B>Keywords: </B>'.$this->key_02;
			$sx .= '</font>';
			$sx .= '<BR><BR>Revisado por '.$this->revisor;
			$sx .= '</div>';
			return($sx);
		}
	function semic_mostrar($id,$ed2='')
		{
			global $edit;
			$idx = $id;
			if ($this->tabela == 'semic_trabalho')
				{
				$sql = "select * from ".$this->tabela." 
					left join programa_pos on sm_programa = pos_codigo
					left join centro on pos_centro = centro_codigo
					left join pibic_bolsa_tipo on pbt_codigo = sm_modalidade
					where id_sm = ".round($id). " or sm_codigo = '".$id."'";
				} else {
				$sql = "select * from ".$this->tabela." 
					left join centro on sm_programa = centro_codigo
					left join pibic_bolsa_tipo on pbt_codigo = sm_modalidade
					where id_sm = ".round($id). " or sm_codigo = '".$id."'";					
				}
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					$this->status = $line['sm_status'];
					$this->revisor = $line['sm_revisor'];
					$this->resumo_01 = $line['sm_resumo_01'];
					$this->resumo_02 = $line['sm_resumo_02'];
					$this->key_01 = $line['sm_rem_06'];
					$this->key_02 = $line['sm_rem_16'];
					$this->id = $line['id_sm'];	
					$cod = $line['sm_codigo'];
					if (strlen($cod) > 0)
						{ $wha = " sma_protocolo = '".$cod."' "; }
					else
						{ $wha = " sma_protocolo = '".strzero($id,7)."' "; }
					$sql = "update ".$this->tabela_autor." set sma_funcao = '9' where sma_funcao = '1' ";
					$rlt = db_query($sql);
					
					$autores = '';
					$autores2 = '';
					$sql = "select * from ".$this->tabela_autor." 
							where $wha
							and sma_ativo = 1 
							order by sma_funcao, id_sma 
						";
					
					$rlt = db_query($sql);
					$id = 1;
					while ($xline = db_read($rlt))
						{
							$func = trim($xline['sma_instituicao']);
							$funcao = trim($xline['sma_funcao']);
							
							switch ($funcao)
								{
								case "0": $funcao = "Discente"; $est=1; break;
								case "1": $funcao = "Orientador"; $ori=1; break;
								case "2": $funcao = "Co-orientador"; break;
								case "3": $funcao = "Colaborador"; break;
								case "7": $funcao = "Mestrando de Pós-Graduação"; break;
								case "8": $funcao = "Doutorando de Pós-Graduação"; break;
								case "4": $funcao = "Pibic Junior"; break;
								case "5": $funcao = "Supervisor Pibic Junior"; break;
								case "6": $funcao = "Escola"; break;
								case "9": $funcao = "Orientador"; $ori=1; break;
								
								}
							$link = '<A HREF="#ID'.$id.'" title="'.$funcao.', '.$func.'" class="link">';
							
							$autores .= trim($xline['sma_nome']).$link.'<SUP>'.$id.'</SUP></A> ';
							$autores .= trim($xline['sma_tipo']);
							
							if (strlen($autores2) > 0) { $autores2 .= ', '; }
							$autores2 .= '<SUP>'.$id.'</SUP><A TAG="#ID'.$id.'"></A>';
							$autores2 .= $funcao.', '.$func.'';
							$id++; 
						}
						$autores2 .= ', ';

					if ($edit==1)
						{
							$link0T = '<A HREF="#" onclick="newxy2(\'abstract_'.$ed2.'ed.php?dd0='.$idx.'&dd1=titulo\',600,400);">';
							$link1T = '<A HREF="#" onclick="newxy2(\'abstract_'.$ed2.'ed.php?dd0='.$idx.'&dd1=titulo_en\',600,400);">';
						}

					
					$sx .= '<center><font class="lt4">'.$link0T.$line['sm_titulo'].'</A></font></center>';
					$sx .= '<center><font class="lt3"><I>'.$link1T.$line['sm_titulo_en'].'</A></I></font></center>';
					
					$obs = trim($line['sm_obs']);
					if (strlen($obs) > 0)
						{
						$sx .= '<HR>';
						$sx .= '<font color="red">'.mst($obs).'</font>';
						$sx .= '<HR>';
						}	
					$sx .= '<BR>';
					$sx .= $autores;
					
					/* Escola e Programa */
					$escola = trim($line['centro_nome']);
					$programa = trim($line['pos_nome']);
					if (strlen($programa) > 0)
						{
							$sx .= '<BR>'.$escola.', '.$programa;
						} else {
							$sx .= '<BR>'.$escola;
						}
					
					$acad = trim($line['sm_formacao']);
					if (strlen($acad) > 0)
						{
						if ($acad=='D') { $acad = 'Doutorado'; }
						if ($acad=='M') { $acad = 'Mestrado'; }
						$sx .= ', modalidade: <B>'.$acad.'</B>';							
						}

					$moda = trim($line['sm_modalidade']);
					if (strlen($moda) > 2)
						{
						$sx .= ', tipo da pesquisa: <B>'.lowercase($moda).'</B>';
						} else {
							$sx .= ', Modalidade: <B>'.trim($line['pbt_descricao']).'</B>';	
						}
						
					
					if ($edit==1)
						{
							$link01 = '<A HREF="#" onclick="newxy2(\'abstract_'.$ed2.'ed.php?dd0='.$idx.'&dd1=rem_01\',600,400);">';
							$link02 = '<A HREF="#" onclick="newxy2(\'abstract_'.$ed2.'ed.php?dd0='.$idx.'&dd1=rem_02\',600,400);">';
							$link03 = '<A HREF="#" onclick="newxy2(\'abstract_'.$ed2.'ed.php?dd0='.$idx.'&dd1=rem_03\',600,400);">';
							$link04 = '<A HREF="#" onclick="newxy2(\'abstract_'.$ed2.'ed.php?dd0='.$idx.'&dd1=rem_04\',600,400);">';
							$link05 = '<A HREF="#" onclick="newxy2(\'abstract_'.$ed2.'ed.php?dd0='.$idx.'&dd1=rem_05\',600,400);">';
							$link06 = '<A HREF="#" onclick="newxy2(\'abstract_'.$ed2.'ed.php?dd0='.$idx.'&dd1=rem_06\',600,400);">';

							$link11 = '<A HREF="#" onclick="newxy2(\'abstract_'.$ed2.'ed.php?dd0='.$idx.'&dd1=rem_11\',600,400);">';
							$link12 = '<A HREF="#" onclick="newxy2(\'abstract_'.$ed2.'ed.php?dd0='.$idx.'&dd1=rem_12\',600,400);">';
							$link13 = '<A HREF="#" onclick="newxy2(\'abstract_'.$ed2.'ed.php?dd0='.$idx.'&dd1=rem_13\',600,400);">';
							$link14 = '<A HREF="#" onclick="newxy2(\'abstract_'.$ed2.'ed.php?dd0='.$idx.'&dd1=rem_14\',600,400);">';
							$link15 = '<A HREF="#" onclick="newxy2(\'abstract_'.$ed2.'ed.php?dd0='.$idx.'&dd1=rem_15\',600,400);">';
							$link16 = '<A HREF="#" onclick="newxy2(\'abstract_'.$ed2.'ed.php?dd0='.$idx.'&dd1=rem_16\',600,400);">';

							$linkx = '</A>';
						}
					
					$resumo = $link01.'<B>Introdução:</B> '.$linkx.$line['sm_rem_01'];
					$resumo .= $link02.' <B>Objetivos:</B> '.$linkx.$line['sm_rem_02'];
					$resumo .= $link03.' <B>Método:</B> '.$linkx.$line['sm_rem_03'];
					if (strlen(trim($line['sm_rem_04'])) > 0)
						{
							$resumo .= $link04.' <B>Resultados:</B> '.$linkx.$line['sm_rem_04'];
						}
					if (strlen(trim($line['sm_rem_05'])) > 0)
						{
						$resumo .= $link05.' <B>Conclusão:</B> '.$linkx.$line['sm_rem_05'];
						}
					
					$abstract = $link11.'<B>Introduction:</B> '.$linkx.$line['sm_rem_11'];
					$abstract .= $link12.' <B>Objectives:</B> '.$linkx.$line['sm_rem_12'];
					$abstract .= $link13.' <B>Methods:</B> '.$linkx.$line['sm_rem_13'];
					if (strlen(trim($line['sm_rem_14'])) > 0)
						{
							$abstract .= $link14.' <B>Results:</B> '.$linkx.$line['sm_rem_14'];
						}
					if (strlen(trim($line['sm_rem_15'])) > 0)
						{
							$abstract .= $link15.' <B>Conclusion:</B> '.$linkx.$line['sm_rem_15'];
						}
					
					$sx .= '<BR><BR>';

					$sx .= '<div style="text-align: justify">';
					$sx .= $resumo;
					$sx .= '<BR><BR>';
					$sx .= $link06.'<B>Palavras-chave</B>: '.$linkx.$line['sm_rem_06'];
					$sx .= '</div>';
					
					$sx .= '<BR><BR>';
					
					$sx .= '<div style="text-align: justify">';
					$sx .= $abstract;
					$sx .= '<BR><BR>';
					$sx .= $link16.'<B>Palavras-chave</B>: '.$linkx.$line['sm_rem_16'];
					$sx .= '</div>';
				}
			$sx .= '<div style="text-align: justify">';
			$sx .= '<BR><BR><BR>'.$autores2;
			$sx .= '</div>';
			$sx = '<TR><TD colspan=2>'.$sx;
			return($sx);
			
		}
	
	function semic_valida_autores($id)
		{
			/* Valida autores */
		$sql = "select * from ".$this->tabela_autor." 
					where sma_protocolo = '".strzero($id,7)."'
					and sma_ativo = 1 
					order by sma_funcao, sma_nome 
				";
		$rlt = db_query($sql);
		$est = 0;
		$ori = 0;
		while ($line = db_read($rlt))
			{
			$id = $line['id_sma'];
			$funcao = trim($line['sma_funcao']);
			switch ($funcao)
				{
				case "0": $funcao = "Discente"; $est=1; break;
				case "1": $funcao = "Orientador"; $ori=1; break;
				case "2": $funcao = "Co-orientador"; break;
				case "3": $funcao = "Colaborador"; break;
				case "9": $funcao = "Orientador"; $ori=1; break;
				}
			}
			if (($est==1) and ($ori==1)) { return(1); }
			return(0);
		}
	
	function submit_list($status,$tipo='',$page='')
		{
			global $ss;
			$cracha = $ss->user_cracha;
			$wh = " and sm_docente = '$cracha' ";
			if ($tipo == '*') { $wh = ''; }
			$inner = '';
			if (trim($this->tabela) == 'semic_ic_trabalho')
				{
					//$inner = " 
//						left join pibic_parecer_".date("Y")." on pp_protocolo = sm_codigo and pp_tipo = 'RFIN' 
						//inner join pibic_bolsa_contempladas on pp_protocolo = pb_protocolo
					//";
					$inner2 = " 
						left join pibic_parecer_".date("Y")." on pp_protocolo = sm_codigo and pp_tipo = 'RFIN' 
						inner join pibic_bolsa_contempladas on pp_protocolo = pb_protocolo
					";
									}
			$sql = "select * from ".$this->tabela."
						 $inner
						 where sm_status = '$status'
						 $wh
						 order by id_sm desc
					";
			
			$rlt = db_query($sql);
			$sx .= '<table width="100%" class="tabela00">';
			$sx .= '<TR><TH width="60">Protocolo';
			$sx .= '<TH>Título';
			$sx .= '<TH width="80">Relatório';
			$sx .= '<TH>idioma';
			$sx .= '<TH width="80"><I>Status</I>';
			$tot = 0;			
			$xidm = 'x';
			while ($line = db_read($rlt))
			{
				$idm = $line['id_sm'];
				if ($idm != $xidm)
				{
				$xidm = $idm;
				$sta = trim($line['sm_status']);
				$edit = 0;
				$view = 0;
				$av = $line['pp_p01'];
				switch ($sta)
					{
					case "@": $sta = 'Em submissão'; $edit=1; $view=1; break;
					case "A": $sta = 'Submetido'; $edit=0; $view=1; break;
					case "B": $sta = 'Aceito'; $edit=0; $view=1; break;
					case "C": $sta = 'Para correção'; $edit=1; $view=1; break;
					case "D": $sta = 'Revisado'; $edit=1; $view=1; break;
					case "X": $sta = '**Cancelado**'; $edit=0; $view=1; break;
					}
									
				if ($view==1)
					{ $linkv = '<A HREF="semic_submissao_detalhe.php?dd0='.$line['id_sm'].'&pag=1&dd90='.checkpost($line['id_sm']).'" class="link">'; }				
				if (($view==1) and ($tipo=='*'))
					{ $linkv = '<A HREF="'.$page.'?dd0='.$line['id_sm'].'&pag=1&dd90='.checkpost($line['id_sm']).'" class="link">'; }
				$sx .= '<TR>';
				$sx .= '<TD class="tabela01" align="center">'.strzero($line['id_sm'],7);
				$sx .= '<TD class="tabela01 lt3">';
				$sx .= $linkv.$line['sm_titulo'].'</A>';
				$sx .= '<TD class="tabela01">';
				$sx .= $line['sm_autores'];
				$sx .= '&nbsp;';
				if ($av >= 10)
					{ $sx .= 'Aprovado'; }
				else
					{ $sx .= $av; }
				
				$sx .= '<TD class="tabela01" align="center">';
				$sx .= '<IMG SRC="'.http.'img/img_flag_'.$line['pb_semic_idioma'].'.png" height="15">';
				
				$sx .= '<TD class="tabela01">';
				
				$sx .= $sta;
				
				if ($view==1)
					{ $linkv = '<A HREF="semic_submissao_detalhe.php?dd0='.$line['id_sm'].'&pag=1&dd90='.checkpost($line['id_sm']).'" class="link">'; }				
				
				if (($edit==1) and (strlen($tipo)==0))
					{
						$link = '<A HREF="semic_submissao_novo.php?dd0='.$line['id_sm'].'&pag=1&dd90='.checkpost($line['id_sm']).'" class="link">';
						$linkc = '<A HREF="semic_submissao_cancelar.php?dd0='.$line['id_sm'].'&pag=1&dd90='.checkpost($line['id_sm']).'" class="link">';
						$sx .= '<TD width="50">';
						$sx .= $link;
						$sx .= 'editar';
						$sx .= '</A>';

						$sx .= '<TD width="50">';
						$sx .= $linkc;
						$sx .= 'cancelar';
						$sx .= '</A>';						
						
					}
					$tot++;
				}
			}
			$sx .= '<TR><TD colspan=4>Total '.$tot;
			$sx .= '</table>';
			return($sx);
		}
	
	function submit_resume_mostra()
		{
			global $ss;
			$cracha = trim($ss->user_cracha);
			$sql = "select count(*) as total, sm_status 
						from ".$this->tabela."
						 where sm_docente = '$cracha'
						 group by sm_status
					";
			$rlt = db_query($sql);
			
			$total = array(0,0,0,0,0,0,0,0);
			while ($line = db_read($rlt))
				{
					print_r($line);
					$tot = $line['total'];
					$status = trim($line['sm_status']);
					if (strlen($status)==0) { $status ='@'; }
					switch ($status)
						{
						case '@': $total[0] = $total[0] + $tot; break;
						case 'A': $total[1] = $total[1] + $tot; break;
						case 'B': $total[2] = $total[2] + $tot; break;
						case 'C': $total[3] = $total[3] + $tot; break;
						case 'D': $total[4] = $total[4] + $tot; break;
						case 'X': $total[5] = $total[5] + $tot; break;				
						}
				}
			$sx .= '<table class="tabela00" width="100%">';
			$sx .= '<TR><TD colspan=5>';
			$sx .= '<h1>Resumo das Submissões</h2>';
			$sx .= '<TR>';
			$sx .= '<TH width="17%">Em submissão';
			$sx .= '<TH width="17%">Submetidos';
			$sx .= '<TH width="17%">Em análise';
			$sx .= '<TH width="17%">Para correções';
			$sx .= '<TH width="17%">Publicado';
			$sx .= '<TH width="17%">Cancelado';
			
			if ($total[0] > 0) { $link0 = '<A HREF="semic_submissao_lista.php?dd1=@">'; }
			if ($total[1] > 0) { $link1 = '<A HREF="semic_submissao_lista.php?dd1=A">'; }
			if ($total[2] > 0) { $link2 = '<A HREF="semic_submissao_lista.php?dd1=B">'; }
			if ($total[3] > 0) { $link3 = '<A HREF="semic_submissao_lista.php?dd1=C">'; }
			if ($total[4] > 0) { $link4 = '<A HREF="semic_submissao_lista.php?dd1=D">'; }
			if ($total[5] > 0) { $link5 = '<A HREF="semic_submissao_lista.php?dd1=X">'; }
			
			$sx .= '<TR>';
			$sx .= '<TD class="tabela01 lt5" align="center">'.$link0.$total[0].'</a>';
			$sx .= '<TD class="tabela01 lt5" align="center">'.$link1.$total[1].'</a>';
			$sx .= '<TD class="tabela01 lt5" align="center">'.$link2.$total[2].'</a>';
			$sx .= '<TD class="tabela01 lt5" align="center">'.$link3.$total[3].'</a>';
			$sx .= '<TD class="tabela01 lt5" align="center">'.$link4.$total[4].'</a>';
			$sx .= '<TD class="tabela01 lt5" align="center">'.$link5.$total[5].'</a>';
			$sx .= '</table>';
			
			return($sx);
		}
	function submit_resume_mostra_adms($page='')
		{
			global $ss;
			$cracha = $ss->user_cracha;
			$sql = "select count(*) as total, sm_status 
						 from ".$this->tabela."
						 group by sm_status
					";
			echo $sql;
			$rlt = db_query($sql);
			
			$total = array(0,0,0,0,0,0,0,0);
			while ($line = db_read($rlt))
				{
					$tot = $line['total'];
					$status = trim($line['sm_status']);
					if (strlen($status)==0) { $status ='@'; }
					switch ($status)
						{
						case '@': $total[0] = $total[0] + $tot; break;
						case 'A': $total[1] = $total[1] + $tot; break;
						case 'B': $total[2] = $total[2] + $tot; break;
						case 'C': $total[3] = $total[3] + $tot; break;
						case 'D': $total[4] = $total[4] + $tot; break;
						case 'X': $total[5] = $total[5] + $tot; break;				
						}
				}
			$sx .= '<table class="tabela00" width="100%">';
			$sx .= '<TR><TD colspan=5>';
			$sx .= '<h1>Resumo das Submissões</h2>';
			$sx .= '<TR>';
			$sx .= '<TH width="17%">Em submissão';
			$sx .= '<TH width="17%">Submetidos';
			$sx .= '<TH width="17%">Para revisão do Inglês';
			$sx .= '<TH width="17%">Para correções do professor';
			$sx .= '<TH width="17%">Revisado';
			$sx .= '<TH width="17%">Cancelado';
			
			if ($total[0] > 0) { $link0 = '<A HREF="'.$page.'?dd1=@">'; }
			if ($total[1] > 0) { $link1 = '<A HREF="'.$page.'?dd1=A">'; }
			if ($total[2] > 0) { $link2 = '<A HREF="'.$page.'?dd1=B">'; }
			if ($total[3] > 0) { $link3 = '<A HREF="'.$page.'?dd1=C">'; }
			if ($total[4] > 0) { $link4 = '<A HREF="'.$page.'?dd1=D">'; }
			if ($total[5] > 0) { $link5 = '<A HREF="'.$page.'?dd1=X">'; }
			
			$sx .= '<TR>';
			$sx .= '<TD class="tabela01 lt5" align="center">'.$link0.$total[0].'</a>';
			$sx .= '<TD class="tabela01 lt5" align="center">'.$link1.$total[1].'</a>';
			$sx .= '<TD class="tabela01 lt5" align="center">'.$link2.$total[2].'</a>';
			$sx .= '<TD class="tabela01 lt5" align="center">'.$link3.$total[3].'</a>';
			$sx .= '<TD class="tabela01 lt5" align="center">'.$link4.$total[4].'</a>';
			$sx .= '<TD class="tabela01 lt5" align="center">'.$link5.$total[5].'</a>';
			$sx .= '</table>';
			
			return($sx);
		}	
	function remover_autor($protocolo,$id)
		{
			$sql = "update ".$this->tabela_autor." set sma_ativo = 0 
					where id_sma = ".round($id)." and sma_protocolo = '".$protocolo."'";
			$rrr = db_query($sql);
			return(0);			
		}
	
	function adicionar_autor($protocolo,$nome,$funcao,$instituicao)
		{
			$nome = uppercase($nome);
			$instituicao = substr($instituicao,0,15);
			if (strlen($funcao) == 0)
				{
					echo '<TR><TD colspan=4><font color="red">';
					echo 'Participação do autor é necessária';
					return(0);					
				}
			//if (strlen($instituicao) == 0)
//				{
					//echo '<TR><TD colspan=4><font color="red">';
					//echo 'Instituição do autor é necessária';
					//return(0);					
				//}
				
			if (strlen($nome) > 0)
			{
			$sql = "select * from ".$this->tabela_autor."
					where sma_protocolo = '$protocolo'
					and sma_nome = '".$nome."' 
					and sma_ativo = 1 ";
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					echo '<TR><TD colspan=4><font color="red">';
					echo 'Autor já cadastrado';
					return(0);
				} else {
					$sql = "insert into ".$this->tabela_autor."
						( 
							sma_protocolo, sma_nome, sma_funcao,
							sma_instituicao, sma_ativo
						) values (
							'$protocolo','$nome','$funcao',
							'$instituicao', 1
						)
						";
					$rlt = db_query($sql);
					return(1);
				}
			}
		}
	
	function structure()
		{
			$sql = "
			CREATE TABLE ".$this->tabela_autor."
				(
				id_sma serial NOT NULL,
				sma_protocolo char(7),
				sma_nome char(100),
				sma_funcao char(1),
				sma_instituicao char(15),
				sma_titulacao char(3),
				sma_ativo integer
				)
			";
			$rlt = db_query($sql);
			exit;
			
			$sql = "
			CREATE TABLE ".$this->tabela."
				(
				id_sm serial NOT NULL,
				
				sm_codigo char(7),
				sm_titulo text,
				sm_titulo_en text,
				sm_programa char(7),
				sm_status char(1),
				
				sm_curso char(7),
				sm_docente char(8),
				sm_discente char(8),
				sm_colaboradores text,
				
				sm_autores text,
				sm_edital char(6),
				sm_ano char(4),
				sm_lastupdate integer,
				
				sm_resumo_01 text,
				sm_resumo_02 text,

				sm_rem_01 text,
				sm_rem_02 text,
				sm_rem_03 text,
				sm_rem_04 text,
				sm_rem_05 text,
				sm_rem_06 text,
				
				sm_rem_11 text,
				sm_rem_12 text,
				sm_rem_13 text,
				sm_rem_14 text,
				sm_rem_15 text,
				sm_rem_16 text								
				)
			";
			$rlt = db_query($sql);
			return(1);
		}
	/* SUBMISSÂO MOSTRA DE PESQUISA */
	function cp_01()
		{
			global $ss;
			$docente = $ss->user_cracha;
			
			$sql_pos = "select pos_codigo, pos_nome from programa_pos where pos_corrente = '1' order by pos_nome";
			$cp = array();
			array_push($cp,array('$H8','id_sm','',False,False));
			array_push($cp,array('$T80:3','sm_titulo','Título do trabalho',True,True));
			array_push($cp,array('$T80:3','sm_titulo_en','Título do trabalho em inglês',True,True));
			
			array_push($cp,array('$Q pos_nome:pos_codigo:'.$sql_pos,'sm_programa','Programa de Pós-Graduação',True,True));
			
			$docente = $ss->user_cracha;
			array_push($cp,array('$HV','sm_docente',$docente,True,True));
			array_push($cp,array('$HV','sm_status','@',True,True));
			
			array_push($cp,array('$HV','sm_edital','MOSTRA',False,True));
			array_push($cp,array('$HV','sm_ano',date("Y"),False,True));
			array_push($cp,array('$HV','sm_lastupdate',date("Ymd"),False,True));
			$mod = ' : ';
			$mod .= '&Projeto de pesquisa:Projeto de pesquisa';
			$mod .= '&Em andamento:Proj. em andamento';
			$mod .= '&Trabalho concluído:Trabalho concluído';
			
			array_push($cp,array('$O M:Mestrado&D:Doutorado'.$modp,'sm_formacao','Formação',True,True));
			array_push($cp,array('$O '.$mod,'sm_modalidade','Categoria',True,True));
			//$sql = "ALTER TABLE ".$this->tabela." add column sm_formacao char(20)";
			//$rlt = db_query($sql);
			return($cp);			
		}

	function cp_01A()
		{
			global $ss;
			$docente = $ss->user_cracha;
			$sql_centro = "select centro_codigo, centro_nome from centro where centro_ativo = '1' and centro_meta_01 > 0 order by centro_nome";
			$cp = array();
			array_push($cp,array('$H8','sm_codigo','',True,True));
			array_push($cp,array('$T80:3','sm_titulo','Título do trabalho',True,True));
			array_push($cp,array('$T80:3','sm_titulo_en','Título do trabalho em inglês',True,True));
			
			array_push($cp,array('$Q centro_nome:centro_codigo:'.$sql_centro,'sm_programa','Escola',True,True));
			
			$docente = $ss->user_cracha;
			array_push($cp,array('$HV','sm_docente',$docente,True,True));
			array_push($cp,array('$HV','sm_status','@',True,True));
			
			array_push($cp,array('$HV','sm_edital','SEMIC',False,True));
			array_push($cp,array('$HV','sm_ano',date("Y"),False,True));
			array_push($cp,array('$HV','sm_lastupdate',date("Ymd"),False,True));

			//$sql = "ALTER TABLE ".$this->tabela." add column sm_formacao char(20)";
			//$rlt = db_query($sql);
			return($cp);			
		}
		
	function cp_02()
		{
			global $dd,$acao;
			$id = $dd[0];
			$cp = array();
			$rst = function_002($id);
			$dd[3] = $rst;
			
			array_push($cp,array('$H8','id_sm','',False,False));
			array_push($cp,array('$M','',msg('autores_instrucoes'),False,True));
			array_push($cp,array('$FC001','','Autores',False,True));
			array_push($cp,array('$H8','','',False,True));
			array_push($cp,array('$HV','sm_status','@',False,True));
			
			if (($rst != 1))
				{
					array_push($cp,array('$M','','<font color="red">O nome de um discente e um orientado são requeridos!.</font>',True,True));		
				}
			return($cp);			
		}

	function cp_02A()
		{
			global $dd,$acao;
			$id = $dd[0];
			$cp = array();
			$rst = function_002($id);
			$dd[3] = $rst;
			
			array_push($cp,array('$H8','sm_codigo','',True,True));
			array_push($cp,array('$M','',msg('autores_instrucoes'),False,True));
			array_push($cp,array('$FC001','','Autores',False,True));
			array_push($cp,array('$H8','','',False,True));
			array_push($cp,array('$HV','sm_status','@',False,True));
			
			if (($rst != 1))
				{
					array_push($cp,array('$M','','<font color="red">O nome de um discente e um orientado são requeridos!.</font>',True,True));		
				}
			return($cp);			
		}
	function cp_03()
		{
			$cp = array();
			array_push($cp,array('$H8','id_sm','',False,False));
			array_push($cp,array('$M8','','Abstract in English',False,False));
			
			array_push($cp,array('$T80:4','sm_rem_01','Introdução',True,True));
			array_push($cp,array('$T80:4','sm_rem_02','Objetivos',True,True));
			array_push($cp,array('$T80:4','sm_rem_03','Método',True,True));
			array_push($cp,array('$T80:4','sm_rem_04','Resultados (opcional para projetos)',False,True));
			array_push($cp,array('$T80:5','sm_rem_05','Conclusão (opcional para projeto)',False,True));
			
			array_push($cp,array('$KEYWORDS','sm_rem_06','Palavras-chave',False,True));
			return($cp);			
		}
		
	function cp_03A()
		{
			$cp = array();
			array_push($cp,array('$H8','sm_codigo','',True,True));
			array_push($cp,array('$M8','','Abstract in English',False,False));
			
			array_push($cp,array('$T80:4','sm_rem_01','Introdução',True,True));
			array_push($cp,array('$T80:4','sm_rem_02','Objetivos',True,True));
			array_push($cp,array('$T80:4','sm_rem_03','Método',True,True));
			array_push($cp,array('$T80:4','sm_rem_04','Resultados (opcional para projetos)',False,True));
			array_push($cp,array('$T80:5','sm_rem_05','Conclusão (opcional para projeto)',False,True));
			
			array_push($cp,array('$KEYWORDS','sm_rem_06','Palavras-chave',False,True));
			return($cp);			
		}		
			
	function cp_04()
		{
			$cp = array();
			array_push($cp,array('$H8','id_sm','',False,False));
			array_push($cp,array('$M8','','Abstract in English',False,False));
			
			array_push($cp,array('$T80:4','sm_rem_11','Introduction',True,True));
			array_push($cp,array('$T80:4','sm_rem_12','Objectives',True,True));
			array_push($cp,array('$T80:4','sm_rem_13','Methods',True,True));
			array_push($cp,array('$T80:4','sm_rem_14','Results (opcional para projetos)',False,True));
			array_push($cp,array('$T80:5','sm_rem_15','Conclusion (opcional para projetos)',False,True));
			
			array_push($cp,array('$KEYWORDS','sm_rem_16','Keywords',False,True));
			return($cp);			
		}	
	function cp_04A()
		{
			$cp = array();
			array_push($cp,array('$H8','sm_codigo','',True,True));
			array_push($cp,array('$M8','','Abstract in English',False,False));
			
			array_push($cp,array('$T80:4','sm_rem_11','Introduction',True,True));
			array_push($cp,array('$T80:4','sm_rem_12','Objectives',True,True));
			array_push($cp,array('$T80:4','sm_rem_13','Methods',True,True));
			array_push($cp,array('$T80:4','sm_rem_14','Results (opcional para projetos)',False,True));
			array_push($cp,array('$T80:5','sm_rem_15','Conclusion (opcional para projetos)',False,True));
			
			array_push($cp,array('$KEYWORDS','sm_rem_16','Keywords',False,True));
			return($cp);			
		}	


	function cp_05()
		{
			global $dd;
			$declaracao = '<H3>Declaração de submissão</H3>';
			$declaracao .= 'Declaro que o projeto acima descrito será publicado nos anais da Mostra de Pesquisa e que os autores registrados fizeram ou fazem parte da equipe de pesquisa.';
			$id = function_002($dd[0]);
			$idx = $this->semic_resumo_checar($dd[0]);
			if (strlen($idx)==0)
				{ $id = ''; echo 'ZEROU'; }			
			$dd[3] = $id;
			$cp = array();
			array_push($cp,array('$H8','id_sm','',False,False));
			array_push($cp,array('$FC003','','111',False,True));
			array_push($cp,array('$FC002','','222',False,True));
			array_push($cp,array('$H8','','',True,True));
			array_push($cp,array('$M8','',$declaracao,False,True));
			array_push($cp,array('$DECLA','','Declaração',True,True));
			array_push($cp,array('$HV','sm_status','A',True,True));
			array_push($cp,array('$M','',$this->erro,False,True));
			//array_push($cp,array('$M8','',$this->erro,False,True));
			return($cp);			
		}	
	function cp_05A()
		{
			global $dd;
			$declaracao = '<H3>Declaração de submissão</H3>';
			$declaracao .= 'Declaro que o projeto acima descrito será publicado nos anais da Mostra de Pesquisa e que os autores registrados fizeram ou fazem parte da equipe de pesquisa.';
			$id = function_002($dd[0]);
			$idx = $this->semic_resumo_checar($dd[0]);
			if (strlen($idx)==0)
				{ $id = ''; echo 'ZEROU'; }			
			$dd[3] = $id;
			$cp = array();
			array_push($cp,array('$H8','sm_codigo','',True,True));
			array_push($cp,array('$FC003','','111',False,True));
			array_push($cp,array('$FC002','','222',False,True));
			array_push($cp,array('$H8','','',True,True));
			array_push($cp,array('$M8','',$declaracao,False,True));
			array_push($cp,array('$DECLA','','Declaração',True,True));
			array_push($cp,array('$HV','sm_status','A',True,True));
			array_push($cp,array('$M','',$this->erro,False,True));
			//array_push($cp,array('$M8','',$this->erro,False,True));
			return($cp);			
		}	
		
	
	function email_avaliadores($tp=0)
		{
			$semic = $this->evento;
			$mostra = $this->mostra;
			
			$sql = "
					select * from (
					select psa_p01 from pibic_semic_avaliador	
					where psa_final > 0
					and ((psa_p05 = '$semic') or (psa_p05 = '$mostra'))
					group by psa_p01
					) as tabela 
					left join pareceristas on trim(to_char(id_us,'".strzero(0,7)."')) = psa_p01
					order by psa_p01
				";
			$rlt = db_query($sql);
			$id = 0;
			while ($line = db_read($rlt))
			{
				$id++;
				$email = trim($line['us_email']);
				$email1 = trim($line['us_email_alternativo']);
				if (strlen($email) > 0) { echo $email.'; '; }
				if (strlen($email1) > 0) { echo $email1.'; '; }
			}	
			echo '<BR>total '.$id;
		}
	function cronograma_avaliacao()
		{
			$sql = "select * from (
						select *,trim(trim(psa_p04)||trim(psa_p02)) as codigo from pibic_semic_avaliador
						left join pareceristas on trim(to_char(id_us,'".strzero(0,7)."')) = psa_p01
						order by us_nome
					) as tabela
					left join semic_programacao on sp_codigo = codigo 
					where psa_p03 = 'Oral'
					order by sp_data, sp_sala, sp_hora, us_nome
					";
			$rlt = db_query($sql);
			$sx .= '<table width="100%" class="lt1" border=1 cellpadding=2 cellspacing=0 >';
			while ($line = db_read($rlt))
				{
					$time = trim($line['sp_hora']);
					$sala = trim($line['sp_sala']);
				if ($line['sp_data'] > 20110101)
					{
					if (($time != $xtime) or ($sala != $xsala))
						{
						$usn = $line['us_nome'];
						if ($sala != $xsala)
							{
							$xusn = $usn;					
							$sx .= '<TR>';
							$sx .= '<TD colspan=5>';
							$sx .= substr($line['sp_data'],6,2).' de novembro de 2012 - ';
							$sx .= ' Sala '.$sala;
							}
							$sx .= '<TR>';
							$sx .= '<TD>';
							$sx .= $time;
							$sx .= ' '.$line['codigo'];
							$xtime = $time;
							$xsala = $sala;						
						}
					$sx .= '<TD>';
					$sx .= $line['us_nome'];
					}	
				}
			$sx .= '</table>';
			echo $sx;
			exit;
		}

	function ficha_de_avaliacao_por_avaliador()
		{
			$sql = "select count(*) as total, psa_p01, us_nome, id_us from pibic_semic_avaliador ";
			$sql .= " left join  pareceristas  on trim(to_char(id_us,'".strzero(0,7)."')) = psa_p01
						and (psa_p05 = 'SEMIC21' or psa_p05 = 'MP15')
						group by psa_p01, us_nome, id_us 
						order by us_nome
					";
			$rlt = db_query($sql);
			$id = 0;
			$sx .= '<table width="100%" class="lt1" border=1 cellpadding=2 cellspacing=0 >';
			while ($line = db_read($rlt))
				{
					if (strlen(trim($line['id_us'])) > 0)
					{
					$id++;
					$link = '<A HREF="'.page().'?dd0='.$line['id_us'].'" target="new">';
					$link2 = '<A HREF="semic_declaracao_tipo_1.php?dd0='.$line['id_us'].'" target="new">';
					$link3 = '<A HREF="semic_declaracao_tipo_2.php?dd0='.$line['id_us'].'" target="new2">';					
					$sx .= '<TR>';
					$sx .= '<TD>';
					$sx .= $link;
					$sx .= $line['us_nome'];
					$sx .= '<TD>';
					$sx .= $line['total'];
					$sx .= '<TD>';
					$sx .= $link2.'[declaração]</A>';
					$sx .= '<TD>';
					$sx .= $link3.'[enviar e-mail]</A>';
					}
				}
			$sx .= '<TR><TD>Total '.$id;
			$sx .= '</table>';
			echo $sx;
			exit;
		}
	
	function mostra_ficha_de_avaliacao_por_avaliador($avaliador=0)
		{
			$evento = $this->evento;
			$mostra = $this->mostra;
			$sql = "select * from (";			
			$sql .= "select trim(trim(psa_p04)||trim(psa_p02)) as codigo, * from pibic_semic_avaliador ";
			$sql .= " left join  pareceristas  on trim(to_char(id_us,'".strzero(0,7)."')) = psa_p01 ";
			$sql .= " left join  instituicoes  on us_instituicao = inst_codigo ";
			$sql .= " left join  pibic_documento on (doc_dd0 = us_codigo) ";
			$sql .= ") as tabela ";			
			$sql .= " left join articles on codigo = article_ref ";				
			$sql .= " where (psa_p05 = '$evento' or psa_p05 = '$mostra') ";
			$sql .= " and id_us = ".round($avaliador);
			$sql .= " order by us_nome, psa_p03, article_ref ";
			
			//$sql .= " limit 100 ";
			$rlt = db_query($sql);
			return($rlt);			
		}
		
	
	function comunitar_avaliador_externo_enviar($avaliador,$assunto,$texto)
		{
			global $dd,$secu;
			echo '---';
			$rlt = $this->mostra_ficha_de_avaliacao_por_avaliador($avaliador);
			//semic_programacao on sp_codigo = article_ref		
			$mt = array();
			$sql1 = "";
			$sql2 = "";
			
			$pos1 = '';
			$pos2 = '';
			$poster = array();
			$poster2 = array();
			$poster3 = array();
			
			while ($line = db_read($rlt))
				{
					$email = trim($line['us_email']);
					$email1 = trim($line['us_email_alternativo']);
					$nome = trim($line['us_nome']);
										
					$trab = trim($line['psa_p04']).trim($line['psa_p02']);
					$moda = trim($line['psa_p03']);
					array_push($mt,array($trab,$moda));
					if ($moda == 'Poster')
						{
							array_push($poster,trim($line['psa_p04']));
							array_push($poster2,$trab);
							array_push($poster3,'');
							$sql2 .= "1";
						} else {
							if (strlen($sql1) > 0) { $sql1 .= ' or '; }
							$sql1 .= "(sp_codigo = '$trab')";
						}
				}
			echo '<HR>'.$nome.'<HR>';
			if (strlen($sql1) > 0)
			{
			$ttt='';
			$sql = "select * from semic_programacao
					inner join articles on sp_codigo = article_ref
					where (".$sql1.") and article_publicado <> 'X' ";
					
			$sql .= " order by sp_data, sp_hora ";
			$rlt = db_query($sql);
			$xdata = 'x';
			while ($line = db_read($rlt))
				{
					$data = $line['sp_data'];
					$hora = $line['sp_hora'];
					$data = substr($data,6,2).'/11/'.substr($data,0,4);
					if ($xdata != $data)
						{
							$ttt .= '<BR><B>'.$data.' '.$hora.'</B> (Oral)<BR>';
							$xdata = $data;
						}
					$ln = $line;
					$id = $line['id_article'];
					$secu = 'reol';
					$link = 'http://www2.pucpr.br/reol/semic/trabalho.php?dd0='.$id.'&dd90='.checkpost($id).'&dd10=view.html';	
					$ttt .= '<A HREF="'.$link.'" target="new'.$id.'">';
					$ttt .= $line['sp_codigo'].'</A>';
					$ttt .= '&nbsp;';
				}
			$txt .= $ttt.'<BR>';
			}
			
			if (strlen($sql2) > 0)
			{
				$ttt = '';
				/* 17/30 */
				/* 08/11/2012 */
				$pa2 = array(
					'PPGIa','ARQUR','MAT', 'CCOMP', 'FIS', 'ECV', 'ELE', 'MEC', 'EQ', 'ESAN', 'EPR', 'EBIO', 'PPGEM', 'PPGEPS', 'PPGTU', 'PPGIA', 'PPGTS', 'ADM', 'PLAN', 'DES', 'DIR', 'COMUM', 'COMUN', 'ARQU', 'TUR', 'PPAD', 'PPGD', 'PPGE', 'PPGF', 'PPGT', 
					'iPPGIa','iARQUR','iMAT', 'iCCOMP', 'iFIS', 'iECV', 'iELE', 'iMEC', 'iEQ', 'iESAN', 'iEPR', 'iEBIO', 'iADM', 'iPLAN', 'iDES', 'iDIR', 'iCOMUM', 'iCOMUN', 'iARQU', 'iTUR'
				);
				
				/* 06/11/2012 */
				$pa1 = array(
					'BIOG', 'BIOTEC', 'GEN', 'ZOO', 'ECO', 'MORF', 'FISIOL', 'BIOQ', 'BIOF', 'FARM', 'IMU', 'MICRO', 'PARA', 'MED', 'ODO', 'ENF', 'NUT', 'SC', 'FISIO', 'EF', 'BOTO', 'PPGCS', 'PPGO' , 'AGRO', 'FLORE', 'MEDVET', 'CTA', 'PPGCA',  'EDU', 'PSICO', 'CPOLI', 'TEO', 'LING', 'LETR', 'ART', 'FILO', 'SOCIO', 'ANTRO', 'HIST',
					'iBIOG', 'iBIOTEC', 'iGEN', 'iZOO', 'iECO', 'iMORF', 'iFISIOL', 'iBIOQ', 'iBIOF', 'iFARM', 'iIMU', 'iMICRO', 'iPARA', 'iMED', 'iODO', 'iENF', 'iNUT', 'iSC', 'iFISIO', 'iEF', 'iBOTO', 'iAGRO', 'iFLORE', 'iMEDVET', 'iCTA',  'iEDU', 'iPSICO', 'iCPOLI', 'iTEO', 'iLING', 'iLETR', 'iART', 'iFILO', 'iSOCIO', 'iANTRO', 'iHIST'
					);
				$tt1 = '';
				$tt2 = '';
				$tt3 = '';
				$tt4 = '';
				$ok = 0;
				for ($r=0;$r < count($poster);$r++)
					{
						$link = '<A HREF="http://www2.pucpr.br/reol/semic/sumario_geral.php?idioma=pt_BR#'.UpperCase($poster[$r]).'" target="new">';
						
						for ($r1=0;$r1 < count($pa1);$r1++)
							{
							 	if ($poster[$r] == trim($pa1[$r1])) 
							 	{
							 		if (strlen($tt2) > 0) { $tt2 .= ', '; }
									$tt2 .= $link.$poster2[$r].'</A>';
									$ok = 1;
								} 
								  
							}
							for	($r1=0;$r1 < count($pa2);$r1++)
								{
									if ($poster[$r] == trim($pa2[$r1])) 
									{
										if (strlen($tt1) > 0) { $tt1 .= ', '; }
										$tt1 .= $link.$poster2[$r].'</A>';
										$ok = 1; 
									}
								}										
						if ($ok==0)
							{
								if (strlen($tt4) > 0) { $tt4 .= ', '; }
								$tt4 .= $poster2[$r];
							}
					}

				$txt1 = '<BR><B>Sessão de Poster</B> 06/11/2012 das 15h 30min as 17h e 30min.';
				$txt2 = '<BR><B>Sessão de Poster</B> 08/11/2012 das 8h 30min às 10h e 30min.';
				$txt3 = '<BR><B>Sessão de Poster</B> 06/11/2012 das 15h 30min as 17h e 30min. <B>Internacional</B>';
				$txt3 = '<BR><B>Sessão de Poster</B>';
				
				if (strlen($tt1) > 0)
					{$ttt .= $txt1.'<BR>'.$tt1.'<BR>'; }
				if (strlen($tt2) > 0)
					{$ttt .= $txt2.'<BR>'.$tt2.'<BR>'; }
				if (strlen($tt3) > 0)
					{$ttt .= $txt3.'<BR>'.$tt3.'<BR>'; }
				if (strlen($tt4) > 0)
					{$ttt .= $txt4.'<BR>'.$tt4.'<BR>'; }
				
			
			$txt .= $ttt.'<BR>';
			}
			if (strlen($tt4) > 0)
				{
					echo '<HR>ERRO<HR>';
					echo $tt4;
				}
			echo $nome;
			echo '<BR>'.$email;
			echo '<BR>'.$email1;
			$texto = troca($texto,'$nome',$nome);
			//$texto = troca($texto,'$avaliador',$nome);
			$texto = troca($texto,'$secoes',$txt);
			$texto .= '<BR><BR>'.$email;
			$texto .= '<BR>'.$email1;
			
			//enviaremail('monitoramento@sisdoc.com.br','',$assunto.' - '.$nome,$texto);
			enviaremail('pibicpr@pucpr.br','',$assunto,$texto);
				
			if (strlen($email) > 0) { enviaremail($email,'',$assunto,$texto); echo '<BR>enviado para '.$email; }
			if (strlen($email1) > 0) { enviaremail($email1,'',$assunto,$texto); echo '<BR>enviado para '.$email1; }
			return('');
			
			
		}
	
	function comunitar_avaliador_externo_row()
		{
			global $tab_max;
			$evento = $this->evento;
			$mostra = $this->mostra;
			$sql = "select * from pibic_semic_avaliador ";
			$sql .= " left join  pareceristas  on us_codigo = psa_p01 ";
			$sql .= " left join  instituicoes  on us_instituicao = inst_codigo ";
			$sql .= " left join  pibic_documento on (doc_dd0 = us_codigo) and (doc_tipo = 'D2') ";	
			$sql .= " where (psa_p05 = '$evento' or psa_p05 = '$mostra') ";
			$sql .= " order by us_nome ";		
			$rlt = db_query($sql);
			$sx .= '<table width="'.$tab_max.'" class="lt1">';
			$xnome = "X";
			while ($line = db_read($rlt))
				{
					$nome = trim($line['us_nome']);
					if ($xnome != $nome)
					{
						$sx .= '<TR>';
						$sx .= '<TD>';
						$sx .= trim($line['us_nome']);
						$sx .= '<TD>';
						$sx .= trim($line['inst_abreviatura']);
						$sx .= '<TD>';
						$page = page();
						$page = troca($page,'.php','_comunica_php');
						$page = troca($page,'_php','.php');
						$page .= '?dd0='.$line['id_us'].'&dd1='.$evento.'&dd2='.$mostra;
						$sx .= '<input type="button" onclick="newxy2(\''.$page.'\',600,400);" value="enviar">';
						$xnome = $nome;
					}
				}
			$sx .= '</table>';
			return($sx);

		}
	
	function busca($termo)
		{
			global $jid,$LANG,$dd;
			$sql = "select * from index
					inner join articles on ix_article = id_article 
					left join sections on article_section = section_id
					where ix_asc like '%".UpperCaseSql($termo)."%' 
					and articles.journal_id = $jid 
					order by section_area, sections.seq, identify_type, article_ref
					";
			$sx = $this->mostra_trabalho_linha($sql);
			return($sx);
		}					
	
	
	function page_index_create()
		{
			global $jid,$LANG;
			$sql = "select ix_word, ix_idioma from index where journal_id = $jid 
					group by ix_word, ix_idioma 
					order by ix_word ";
			$rlt = db_query($sql);
			
			$pt = array();
			$en = array();
			while ($line = db_read($rlt))
				{
					$ptx = trim($line['ix_word']);			
					if (strlen($ptx) > 0)
					{
						$id = trim($line['ix_idioma']);
						
						if ($id == 'pt_BR') { array_push($pt,trim($line['ix_word'])); }
						if ($id == 'en') { array_push($en,trim($line['ix_word'])); }
					}
				}
			$medi = round(count($pt)/2);
			$s1 = '';
			$s2 = '';
			$in = 'X';
			for ($r=0;$r < count($pt);$r++)
				{
				$wd = $pt[$r];
				$wx = UpperCaseSql(substr($wd,0,1));
				$href="";
				$wc = '';
				if ($wx != $in)
					{
						$wc = '<B>'.$wx.'</B><BR>';
						$in = $wx;
					}
					
					$href = '<A HREF="busca.php?idioma=pt_BR&dd1='.UpperCaseSql($wd).'&view.html" class="art2">';
					$wd = $wc.$href.$wd.'</A>';
					
					
					if ($r < $medi)
						{ $s1 .=  $wd.'<BR>'; }
					else 
						{ $s2 .= $wd.'<BR>'; }
				}
			 
			$sx = '<table class="tabela01" width="100%">';
			$sx .= '<TR valign="top"><TD>'.$s1;
			$sx .= '    <TD>'.$s2;
			$sx .= '</table>';
			$file = '../semic/indice_onomastico_pt.php';
			echo $file;
			$rlt = fopen($file,'w');
			fwrite($rlt,$sx);
			fclose($rlt);
			
			/* */
			$s1 = '';
			$s2 = '';
			$in = 'X';
			for ($r=0;$r < count($en);$r++)
				{
				$wd = $en[$r];
				$wx = UpperCaseSql(substr($wd,0,1));
				$href="";
				$wc = '';
				if ($wx != $in)
					{
						$wc = '<B>'.$wx.'</B><BR>';
						$in = $wx;
					}
					$href = '<A HREF="busca.php?idioma=en&dd1='.UpperCaseSql($wd).'&view.html" class="art2">';
					$wd = $wc.$href.$wd.'</A>';
					
					
					if ($r < $medi)
						{ $s1 .=  $wd.'<BR>'; }
					else 
						{ $s2 .= $wd.'<BR>'; }
				}
			 
			$sx = '<table class="tabela01" width="100%">';
			$sx .= '<TR valign="top"><TD>'.$s1;
			$sx .= '    <TD>'.$s2;
			$sx .= '</table>';
			$file = '../semic/indice_onomastico_en.php';
			echo $file;
			$rlt = fopen($file,'w');
			fwrite($rlt,$sx);
			fclose($rlt);
						
			return(1);
			
		}
	
	function create_index()
		{
			global $jid;
			$at = new article;
			$at->reindex();
			echo 'FIM';
		}
	
	
	function lista_de_trabalhos_to_site()
		{
			global $dd;
			$dd[1] = 'PIBIC';
			$dd[2] = 'N';
			
			$fl = array('01','02','03','04','05','11','12','13','20');
			$in = array('N' ,'N' ,'N' ,'N' ,'N' ,'S' ,'S' ,'S','N' );
			$md = array('PIBIC' ,'PIBITI' ,'PIBIC_EM' ,'POS-G' ,'CSF' ,'PIBIC' ,'PIBITI' ,'PIBIC_EM','EVO' );
			$id = array('pt_BR','en');
			
			for ($y=0;$y < count($id);$y++)
				{
				$dd[3] = $id[$y];
				for ($r=0;$r < count($fl);$r++)
					{
						echo '<BR>Exportando '.$md[$r];
						$dd[1] = $md[$r];
						$dd[2] = $in[$r];
						$file = '../semic/sumario_'.$fl[$r];
						if ($id[$y] == 'en') { $file .= '_en'; }
						$fa = fopen($file.'.php','w');
						echo ' ('.$file.')';
						$tela = $this->lista_de_trabalhos();
						$tela = troca($tela,'N - CSF','Ciência sem fronteiras');
						$tela = troca($tela,'- POS-G','');
						
						fwrite($fa,$tela);
						fclose($fa);					
					}
			}
			echo 'Exportação concluída';
			return(1); 			
		}
	function programacao_tabalho($jid)
		{
			$dd1 = date("Y").'0000';
			$dd2 = date("Y").'1299';
			$sql = "select * from articles 
					left join semic_programacao on sp_codigo = article_ref
					where journal_id = $jid
					and article_publicado <> 'X'
					order by article_ref
					
			";
			
			$rlt = db_query($sql);
			$sx .= '<H2>Trabalhos sem programação</H2>';
			$sx .= '<table class="lt0">';
			while ($line = db_read($rlt))
			{
				$cor = '';
				if ((strlen(trim($line['sp_data']))==0) and (substr($line['article_ref'],0,2) != 'PP'))
				{
					$cor = '<font color="red">'; 
					$sx .= '<TR valign="top">';
					$sx .= '<TD>';
					$sx .= $cor;
					$sx .=  $line['article_ref'];
					$sx .= '<TD><nobr>';
					$sx .= $cor;
					$sx .=  stodbr($line['sp_data']);
					$sx .=  ' '.$line['sp_hora'];
					$sx .= '<TD><nobr>';
					$sx .= $cor;
					$sx .=  'Sala '.$line['sp_sala'];
					$sx .= '<TD>';
					$sx .=  $line['article_title'];
				}
			}
			$sx .= '</table>';
			echo 'FIM';
			return($sx);
		}	
	
	function programacao_agenda()
		{
			$dd1 = date("Y").'0000';
			$dd2 = date("Y").'1299';
			$sql = "select * from semic_programacao 
					left join articles on sp_codigo = article_ref
					where sp_data > $dd1 and sp_data < $dd2
					order by sp_data, sp_hora, sp_sala
			";
			$rlt = db_query($sql);
			$sx .= '<H2>Programação sem trabalhos</H2>';
			$sx .= '<table class="lt0">';
			while ($line = db_read($rlt))
			{
				$cor = '';
				if (strlen(trim($line['article_title']))==0)
				{
					$cor = '<font color="red">'; 
					$sx .= '<TR valign="top">';
					$sx .= '<TD>';
					$sx .= $cor;
					$sx .=  $line['sp_codigo'];
					$sx .= '<TD><nobr>';
					$sx .= $cor;
					$sx .=  stodbr($line['sp_data']);
					$sx .=  ' '.$line['sp_hora'];
					$sx .= '<TD><nobr>';
					$sx .= $cor;
					$sx .=  'Sala '.$line['sp_sala'];
					$sx .= '<TD>';
					$sx .=  $line['article_title'];
				}
			}
			$sx .= '</table>';
			return($sx);
		}
	
	function strucuture_programacao()
		{
			$sql = "DROP TABLE semic_programacao";
			$rlt = db_query($sql);
			
			$sql = "CREATE TABLE semic_programacao 
				(
					id_sp serial not null,
					sp_data integer,
					sp_hora char(5),
					sp_sala char(6),
					sp_codigo char(10),
					sp_avaliador_1 char(8),
					sp_avaliador_2 char(8),					
					sp_avaliador_3 char(8),					
					sp_avaliador_4 char(8),					
					sp_avaliador_5 char(8),					
					sp_avaliador_6 char(8),					
					sp_avaliador_7 char(8),
					sp_avaliador_1_sta char(1),
					sp_avaliador_2_sta char(1),
					sp_avaliador_3_sta char(1),
					sp_avaliador_4_sta char(1),
					sp_avaliador_5_sta char(1),
					sp_avaliador_6_sta char(1),
					sp_avaliador_7_sta char(1)
				)
				";
			$rlt = db_query($sql);
			return(1);
		}
	
	function programacao_inserir($data,$hora,$trabalho,$sala)
		{
			$sql = "select * from semic_programacao 
					where sp_codigo = '$trabalho' 
					
			";
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					$sql = "update semic_programacao set 
							sp_data = $data,
							sp_hora = '$hora',
							sp_sala = '$sala'
							where sp_codigo = '$trabalho'
							"; 
					$rlt = db_query($sql);							
				} else {
					
					$sql = "insert into semic_programacao
						(sp_data, sp_hora, sp_codigo, sp_sala,
						sp_avaliador_1,sp_avaliador_2,sp_avaliador_3,
						sp_avaliador_4,sp_avaliador_5,sp_avaliador_6,
						sp_avaliador_7,
						sp_avaliador_1_sta,sp_avaliador_2_sta,sp_avaliador_3_sta,
						sp_avaliador_4_sta,sp_avaliador_5_sta,sp_avaliador_6_sta,
						sp_avaliador_7_sta
						) values (
						$data,'$hora','$trabalho','$sala',
						'','','',
						'','','',
						'',
						'','','',
						'','','',
						''
						)
					";
					//echo $sql;
					$rlt = db_query($sql);
					//echo 'OK';
				}
				return(1);
		}
	
	function importar_programacao($txt,$zera)
		{
			if ($zera == '1')
				{
					$this->strucuture_programacao();
					echo 'ZERADO!<BR>';
				}
			$txt = troca($txt,chr(13),'#');
			$txt = troca($txt,'	','@;');
			$txt = troca($txt,'intervalo','INTERVALO');
		
			$ln = splitx('#',$txt);
			echo '-->'.count($ln);
			echo '<--<BR>';
			for ($rx=0;$rx < count($ln);$rx++)
				{
					$col = $ln[$rx];
					echo '<BR>==>'.$col;
					$col = splitx(';',$col);
					$data = troca($col[0],'@','');
					$hora = troca($col[1],'@','');
					echo $data.' '.$hora.'<BR>';
					if (count($col) > 2)
					{
						for ($y = 2;$y < count($col);$y++)
							{
								$sala = ($y-1);
								$trab = troca($col[$y],'@','');
								$ok = 1;
								if ($trab=='INTERVALO') { $ok = 0; }
								if (strlen($trab)==0) { $ok = 0; }
								if ($ok == 1)
									{
									$this->programacao_inserir($data,$hora,$trab,$sala);
									}
							}
					}
				}
		}
	
	function sumario_geral()
		{
			$sx = '';
			$sx .= '<ul class="pesquisador">';
			$sx .= '<LI><A HREF="'.page().'?dd1=PIBIC&dd2=N#PIBIC">Iniciação Científica (PIBIC)</A></LI>';
			$sx .= '<LI><A HREF="'.page().'?dd1=PIBITI&dd2=N#PIBITI">Iniciação Tecnológica (PIBITI)</A></LI>';
			$sx .= '<LI><A HREF="'.page().'?dd1=PIBIC_EM&dd2=#PIBIC_EM">Iniciação Científica Junior (PIBIC_EM)</A></LI>';
			$sx .= '<LI><A HREF="'.page().'?dd1=POS-G&dd2=N#POS-G">Mostra de Pesquisa da Pós-Graduação</A></LI>';
			$sx .= '<LI><A HREF="'.page().'?dd1=CSF&dd2=N#CSF">Ciência sem fronteiras</A></LI>';
			$sx .= '<BR><BR>';
			$sx .= '<LI><A HREF="'.page().'?dd1=PIBIC&dd2=S#iPIBIC">Iniciação Científica Internacional (iPIBIC)</A></LI>';
			$sx .= '<LI><A HREF="'.page().'?dd1=PIBITI&dd2=S#iPIBITI">Iniciação Tecnológica Internacional (iPIBITI)</A></LI>';
			$sx .= '<LI><A HREF="'.page().'?dd1=PIBIC_EM&dd2=S#iPIBIC_EM">Iniciação Científica Junior (iPIBIC_EM)</A></LI>';
			$sx .= '</UL>';
			
			$sx .= $this->resumo_artigos_publicados();
			return($sx);
		}
		
	function resumo_artigos_publicados()
		{
			global $jid,$dd;
					
//			$sql = "select count(*) as total, article_internacional, article_modalidade from articles
//					left join sections on article_section = section_id 
//					where article_publicado <> 'X'
//					and articles.journal_id = $jid
//					group by article_internacional, article_modalidade
//					order by article_modalidade, article_internacional
//					";
//			$rlt = db_query($sql);
//			$sx .= '<table>';
//			while ($line = db_read($rlt))
//				{
//					$sx .= '<TR><TD>'.$line['article_modalidade'];
//					$sx .= '<TD>'.$line['article_internacional'];
//					$sx .= '<TD>'.$line['total'];
//					
//				}
//			$sx .= '</table>';
			return($sx);
		}
		
	function mostra_area($x)
		{
			$x = trim($x);
			$sx = $x;
			if ($x=='A') { $sx = 'Ciências Exatas'; }
			if ($x=='B') { $sx = 'Ciências da Saúde'; }
			if ($x=='C') { $sx = 'Ciências Agrárias'; }
			if ($x=='D') { $sx = 'Ciências Sociais Aplicadas'; }
			if ($x=='E') { $sx = 'Ciências Humanas'; }
			if ($x=='P') { $sx = 'Mostra de Pós-graduação'; }
			return($sx);
		}
		
	function lista_de_trabalhos_sql($todos=0)
		{
			global $jid,$dd;
			
			if (strlen($dd[1]) >0)
			{ $modalidade = trim($dd[1]); }
			if (trim($dd[1])=='PIBIC')
				{ $todos = 1; }
			$internacional = trim($dd[2]);
			$sx = '<A NAME="TRABALHOS">';
			$sql = "select * from articles
					left join sections on article_section = section_id
					where articles.journal_id = $jid and (article_publicado <> 'X' and article_publicado <> '@')";
			if ($todos != 1)
				{ $sql .= " and article_modalidade = '$modalidade' "; }
			if (trim($dd[1])=='PIBIC')				
				{ $sql .= " and (article_modalidade = '$modalidade' or article_modalidade = 'IS' ) "; }
			if (strlen($internacional) > 0)
				{ $sql .= " and article_internacional = '$internacional' "; }
			$sql .= " order by	article_internacional, identify_type, article_seq  
			";
			// article_internacional, seq, article_seq
			return($sql);	
		}
	
	function lista_de_trabalhos_to_word($todos='')
		{
			global $jid,$dd;
			$sql = "select * from articles
					left join semic_programacao on sp_codigo = article_ref
					left join sections on article_section = section_id					
					where articles.journal_id = $jid and article_publicado <> 'X' ";
			$sql .= " order by article_internacional, article_modalidade, section_area, sections.seq, sp_data, sp_hora, identify_type, article_ref ";
			
			if ($todos == 'pos')
				{			$sql = "select * from articles
								left join sections on article_section = section_id					
								where articles.journal_id = $jid and article_publicado <> 'X' ";
							$sql .= " and article_ref like 'PP%' ";
							$sql .= " order by article_internacional, article_modalidade, section_area, sections.seq, identify_type, article_ref ";					
				}
			$ido = 'pt_BR';
			if (strlen($dd[3]) == 'en') { $ido = 'en'; }
			
			$rlt = db_query($sql);
			$sec = '';
			$xmoda = 'x';
			$xarea = 'x';
			if (strlen($dd[50]) == 0)
			{
			$sx .= '
			<style>
			h1
				{
					font-family:Verdana, Geneva, sans-serif;
					font-size: 15px;
					color: #000000;
					line-height: 100%;
					margin: 0px 0px 0px 0px;
					padding: 0px 0px 0px 0px;
				}
			h2
				{
					
					font-family: Arial;
					font-size: 15px;
					color: #000000;
					line-height: 100%;
				}
			h3
				{
					font-family: Arial;
					font-size: 15px;
					color: #000000;
					line-height: 100%;
				}
			h4
				{
					
					font-family: Arial;
					font-size: 15px;
					color: #000000;
					line-height: 100%;
				}
			h5
				{
					font-family: Arial;
					font-size: 15px;
					color: #000000;
					line-height: 100%;
				}
			h6
				{
					font-family: Arial;
					font-size: 15px;
					color: #000000;
					line-height: 100%;
				}														
			</style>';
			$dd[50] = '1';
			}
			$xdata="X";
			$tot = 0;
			while ($line = db_read($rlt))
				{
					$tot++;
					$narea = trim($line['section_area']);
					$moda = trim($line['article_modalidade']);
					
					$data = stodbr($line['sp_data']);
					$sala = $line['sp_sala'];
					$data = substr($data,0,2).' DE NOVEMBRO';
					$hora = $line['sp_hora'];
					$hora = troca($hora,':','h');
					$cap = $data.' '.$hora.' - SALA '.$sala;
					
					if ($xarea != $narea)
						{
							$sx .= '<h1>'.$this->mostra_area($narea).' - '.$moda.'</h1>';
							$xarea = $narea;
							$xdata = "X";
						}
					
					if ($moda != $xmoda)
						{
							$tot3 = 0;												
							$tot2=0;
							$xmoda = $moda;
							//$sx .= '<HR>'.$moda.'<HR>';
						}

					$section = trim($line['title']);
					if ($sec != $section)
						{
							$sec = $section;
							$sx .= '<h2>'.$section.'</h2>';
							//$sx .= '<B>'.$line['identify_type'].' '.$section.'</B>';
							$tot3 = 0;
							$xdata = "X";
						}

			if ($todos != 'pos')
			{											
					if ($data != $xdata)
						{
							$xdata = $data;
							$sx .= '<H6>'.$cap.'</H6>	';
						}
			}	

					$sx .= '<h3>'.$line['article_ref'];
					$sx .= '<h4>';
					$cp = 'article_title';
					if ($line['article_internacional'] == 'S') { $cp = 'article_2_title'; }
					//if ($ido == 'en') { $cp = 'article_2_title'; }
						
					$sx .= trim($line[$cp]);		
					
					$sx .= '</h4></nobr>';
					$sx .= '<h5>'.$line['article_autores'].'</h5>';
				}			
			$sx .= 'Total '.$tot;		
			return($sx);	
		}

	function lista_de_trabalhos()
		{
			global $jid,$dd;
			$sql = $this->lista_de_trabalhos_sql();
			echo '<HR>';
			echo $sql;
			$sx = $this->mostra_trabalho_linha($sql);
			return($sx);
		}
			
	function mostra_trabalho_linha($sql)
		{
			global $jid,$dd;			
			$rlt = db_query($sql);
			$sec = '';
			$tot1=0;
			$tot2=0;
			$tot3=0;
			$tot4=0;
			$xmoda = 'x';
			$xarea = 'x';
			
			$ido = 'pt_BR';
			if (strlen($dd[3]) == 'en') { $ido = 'en'; }
			$idx = 0;
			while ($line = db_read($rlt))
				{
					$id = $line['id_article'];
					if ($idx != $id)
						{
						$idx = $id;
						$narea = trim($line['section_area']);
						$moda = trim($line['article_modalidade']);
					
						if ($xarea != $narea)
							{
								if ($moda == 'POS-G')
									{ $moda = 'Doutorado / Mestrado'; }
								$sx .= '<BR><BR>';
								$sx .= '<div class="titulo_conteudo">'.$this->mostra_area($narea).' - '.$moda.'</div>';
								$xarea = $narea;
							}
						
						if ($moda != $xmoda)
							{
								$tot3 = 0;												
								$tot2=0;
								$xmoda = $moda;
								//$sx .= '<HR>'.$moda.'<HR>';
							}
	
						$section = trim($line['title']);
						if ($sec != $section)
							{
								$sec = $section;
								$sx .= '<BR>';
								$sx .= '<div class="titulo_area">'.$section.'</div>';
								//$sx .= '<B>'.$line['identify_type'].' '.$section.'</B>';
								$tot3 = 0;
							}
						
						$id = $line['id_article'];
						$link = 'trabalho.php?dd0='.$id.'&dd90='.checkpost($id).'&dd10=view.html';
						
						$sx .= '<font class="art1">'.$line['article_ref'].'</font>';
						$sx .= '<div class="trabalho">';
						$sx .= '<a href="'.$link.'" class="art2"><B>';
						
						$cp = 'article_title';
						if (trim($line['article_internacional']) == 'S') { $cp = 'article_2_title'; }
						echo '<BR>---->'.trim($line['article_internacional']);
						if (trim($dd[3]) == 'en') { $cp = 'article_2_title'; }
	
						$title = trim($line[$cp]);
						if (strlen($title) == 0) { $title = trim($line['article_title']); }
						echo $cp.'--'.$title;
						//if (substr($title,0,2) == Substr(UpperCase($title),0,2))
						//{ $title = substr($title,0,1).Lowercase($title,1,strlen($title)); }
				
						$sx .= $title;
	
						$sx .= '</B></A><BR>';
						$sx .= '<font class="art3"><I>'.$line['article_autores'].'</I></font><BR>&nbsp;</div>';
						$tot1++;
						$tot2++;
						$tot3++;
					}
				}
			
			$sx .= '<BR><font class="art0">Total de '.$tot2.' trabalhos</font>';					
			return($sx);
		}	
	function section_limpar()
		{
			global $jid;
			$sql = "select * from sections 
					where journal_id = $jid
					order by abbrev
			";
			$rlt = db_query($sql);
			$xsig = 'X';
			while ($line = db_read($rlt))
				{
					$sig = trim($line['abbrev']);
					$sigid = trim($line['section_id']);					
					if ($sig == $xsig)
						{
							$sql = "update articles set article_section = ".$sigidx."
								where article_section = ".$line['section_id'];
							
							$rltx = db_query($sql); echo '<BR>'.$sql;
							
							$sql = "delete from sections where section_id =".$line['section_id'];
							$rltx = db_query($sql); echo '<BR>'.$sql; 
	
						} else {
							$sigidx = trim($line['section_id']);
							$xsig = $sig;
							echo '<BR>'.$line['abbrev'];
							echo '--'.$line['section_id'];
						}
				//print_r($line);
				}
			exit;
			
		}
	
	function projetos_sem_semic($ano)
		{
			global $jid;
			$sql = "select * from pibic_bolsa_contempladas 
					left join articles on pb_protocolo = article_protocolo_original
					left join sections on article_section = section_id
					left join pibic_bolsa_tipo on pb_tipo = pbt_codigo
					left join pibic_professor on pp_cracha = pb_professor
					where pb_ano = '$ano' and pb_status <> 'C'
				order by article_apresentacao, sections.seq, article_modalidade desc, title, article_title
			";
			$rlt = db_query($sql);
			$tot = 0;
			$sx .= '<H1>Resumos - Projetos ativos não publicados no SEMIC</H1>';
			$sx .= '<img src="../img/icone_star_2.jpg" height="24"> Prioridade na revisão';
			$sx .= '<table width="100%" class="lt1" border=1 >';
			$sx .= '<TR><TH>Protocolo<TH width="50%">Título<TH>Protocolo';
			$sx .= '<TH width="30%">Professor';
			$upx = '';
			$secaox = '';
			$tote = 0;
			$nr = 1;
			while ($line = db_read($rlt))
				{
					$sit = $line['pb_status'];
					$proto2 = trim($line['article_protocolo_original']);
					$protocolo = trim($line['pb_protocolo']);
					if ($proto2 != $protocolo)
					{
						$tipo = ' ('.trim($line['article_modalidade']).')';
						$titulo = trim($line['pb_titulo_projeto']);
						$sx .= '<TR>';
						$sx .= '<TD><NOBR>';		
						$sx .= trim($line['pbt_descricao']);
						$sx .= '<TD>';
						$sx .= $cor;
						$sx .= $titulo;
						$sx .= $tipo;
						$sx .= '<TD>';
						$sx .= $protocolo;
						$sx .= '<TD>';		
						$sx .= trim($line['pp_nome']);
					}
				}
			$sx .= '<TR><TD colspan=4>total de '.$tot.' resumos prontos para serem revisados em seu Inglês';
			$sx .= '</table>';
			return($sx);
		}
	
	function reorganizar_trabalhos($ano)
		{
			global $jid;
			//$sql = "alter table articles add column article_internacional char(1)";
			//$rlt = db_query($sql);
			//$sql = "alter table articles add column article_autores text";
			//$rlt = db_query($sql);
			$sql = "select * from articles 
					left join sections on article_section = section_id
					left join pibic_bolsa_contempladas on pb_protocolo = article_protocolo_original
					left join pibic_professor on pp_cracha = pb_professor
					where articles.journal_id = $jid
					
					and article_publicado <> 'X'

				order by article_apresentacao, sections.seq, article_modalidade, pp_nome, title, article_title
			";
			$rlt = db_query($sql);
			$tot = 0;
			$sx .= '<H1>Resumos - Reogranização de trabalhos</H1>';
			$sx .= '<img src="../img/icone_star_2.jpg" height="24"> Prioridade na revisão';
			$sx .= '<table width="100%" class="lt1" border=0 >';
			$sx .= '<TR><TH>Protocolo<TH width="25">en<TH>Título';
			$upx = '';
			$secaox = '';
			$tote = 0;
			$nr = 1;
			$sqlu = "";
			while ($line = db_read($rlt))
				{
					$sit = $line['pb_status'];
					$idioma = trim($line['article_apresentacao']);
					$tipo = ' ('.trim($line['article_modalidade']).')';
					$titulo = trim($line['article_title']);
					$autor = trim($line['article_author']);
					$autores = mst_autor($autor,1);
					$autores = troca($autores,' ,',',');
					$autores = nbr($autores);
						
					$idioma_img = $idioma;
					$pre = '';
					$internacional = 'N';
					if ($idioma == 'en')
					{
						$pre = 'i';
						$internacional = 'S';
						$titulo = trim($line['article_2_title']);
						$idioma_img = '<img src="../img/icone_star_2.jpg" height="18">';
						$tote++;	
					}
					
					$secao = trim($line['abbrev']).' '.trim($line['title']);
					if ($secao != $secaox)
						{
							$idy = trim($line['identify_type']);
							$sx .= '<TR><TD colspan=4 class="lt3"><B><I>'.$idy.' '.$secao;
							$secaox = $secao;
							$nr = 1;
						}
					$up = trim(uppercasesql($line['article_title']));
					$up = troca($up,chr(13),'');
					$up = troca($up,chr(10),'');
					//if (round($line['article_revisado']) != 1)
						{
						$cor = '';
						if ($up == $upx)
							{ $cor = '<font color="red">'; }
						$upx =  $up;
						$link = page();
						$link = troca($link,'.php','_revisar.php');
						$link .= '?dd0='.$line['id_article'];
						$link .= '&dd90='.checkpost($line['id_article']);
						$tot++;
						$sx .= '<TR '.coluna().'>';
						$sx .= '<TD>';
						$ex  = '';

						$mod = trim($line['article_modalidade']);
						if ($mod == 'PIBITI') { $ex = 'T'; }
						if ($mod == '') { $ex = 'jr'; $mod = 'PIBIC_EM'; }
						if ($mod == 'PIBIC_EM') { $ex = 'jr'; $mod = 'PIBIC_EM'; }
						if (substr(trim($line['abbrev']),0,2) == 'PP') { $ex  = ''; $mod = 'POS-G'; }
						$nrf = $pre.trim($line['abbrev']).strzero($nr,2).$ex;
						
						$sx .= '<font style="font-size:16px;">'.$nrf;
						
						$sx .= '<TD>';
						$sx .= '<A HREF="'.$link.'">';
						$sx .= $cor;
						$sx .= strzero($line['id_article'],7);
						$sx .= '</A>';
						$sx .= '<TD>';
						$sx .= $idioma_img;
						$sx .= '<TD>';
						$sx .= $cor;
						$sx .= $titulo;
						$sx .= $tipo;
						$sx .= '<TD>';
						$sx .= $cor;
						$sx .= trim($line['article_publicado']);
						
						$sx .= '<TR><TD><TD colspan=3>'.$autores;
						
						$asc = uppercasesql($autores.' '.trim($line['article_1_title']).trim($line['article_2_title']));
						
//						$sqlu .= "update articles set article_ref = '".$nrf."'
//							, article_autores = '$autores'
//							, article_busca = '$asc' 
//							, article_modalidade = '$mod'
//							, article_internacional = '$internacional' 
//						
//						where id_article = ".$line['id_article'].';'.chr(13).chr(10);

						$sqlu .= "update articles set "; 
							  //article_ref = '".$nrf."'
						$sqlu .= " article_autores = '$autores'
							, article_busca = '$asc' 						
						where id_article = ".$line['id_article'].';'.chr(13).chr(10);

						if ($sit == 'C')
							{
								$sx .= '<TR><TD colspan=4>** CANCELADO **';
							}
												
						}
						$nr++;
				}
			$sx .= '<TR><TD colspan=4>total de '.$tot.' resumos prontos para serem revisados em seu Inglês';
			$sx .= '</table>';
			if (strlen($sqlu) > 0)
				{
					 $rlt = db_query($sqlu);
				}
				
			return($sx);
		}			
	
	function trabalhos_da_pos_para_publicacao($jid=0)
		{
			$jid = strzero(round($jid),7);
			$sql = "select * from submit_documento 
				where doc_journal_id = '$jid'
			";
			$rlt = db_query($sql);
			while ($line = db_read($rlt))
				{
					print_r($line);
					exit;
				}
		}
	
	function recupera_jid_do_semic($ano=0)
		{
		if ($ano==0)
			{ $semic = 'semic'.(date("Y")-1992); }
			else 
			{ $semic = 'semic'.($ano-1992); }
		
		$sql = "select * from journals where path = '$semic'";
		$rlt = db_query($sql);
		if ($line = db_read($rlt))
			{
				$jid = trim($line['journal_id']);
				$journal = trim($line['title']);
				$path = $semic;
			} else {
				//echo 'Não foi localizado a path do journal  <b>'.$semic.'</B>';
				return(0);
				exit;
			}
			$this->jid = $line['journal_id'];
			$this->journal = $line['title'];
			$this->path = $semic;
			return($line['journal_id']);
		}
	function publica_semic($id='')
		{
			$sql = "select * from pibic_bolsa_contempladas 
					left join ajax_areadoconhecimento on a_cnpq = pb_semic_area
					where pb_protocolo = '$id' and pb_status <> 'C' ";
			
			$rlt = db_query($sql);
			$line = db_read($rlt);
			
			$data = date("Ymd");
			$sql = "update pibic_bolsa_contempladas 
				set pb_semic = $data 
				where id_pb = ".$line['id_pb'];
			$rlt = db_query($sql);
			return(0);
		}
	function publica_resumo_semic_bolsa($id='')
		{
		$semic = 'semic'.(date("Y")-1992);
		
		$jid = $this->recupera_jid_do_semic();
		
		/* Phase II - Recupera dados da bolsa */
			$sql = "select * from pibic_bolsa_contempladas 
					left join ajax_areadoconhecimento on a_cnpq = pb_semic_area
					where pb_protocolo = '$id' and pb_status <> 'C' ";
			$rlt = db_query($sql);
			
			if ($line = db_read($rlt))
				{
					$proj_tiulo = $line['pb_titulo_projeto'];
					$ts = new tesauro;
					$proj_tiulo = $ts->padroniza_titulo($proj_tiulo,0);
					
					$resumo = $line['pibic_resumo_text'];
					$professor = $line['pb_professor'];
					$aluno = $line['pb_aluno'];
					$keys = trim($line['pibic_resumo_keywork']);
					$keys = troca($keys,';','.');
					$keys = troca($keys,' .','.');
					
					$idio = trim($line['pb_semic_idioma']);
					$protocolo = $line['pb_protocolo'];
					$autores = $line['pibic_resumo_colaborador'];
					$secao_nome = $line['a_descricao'];
					
					$area = trim($line['pb_semic_area']);
					$area = substr($area,0,4);
					
					/* Busca Edição */
					$iss = new issue;
					$issue = $iss->ultima_edicao_publicada($jid);
					
					/* Limpa autores */
					require("autores_limpa.php");
					
					/* Busca sessão */
					$sect = new sections;
					$seccao = $sect->section($jid,$secao_nome,substr($area,0,4));
					
					/* Titulo */
					$art = new article;
					$art->article_publicar($jid,$proj_tiulo,$autores,$seccao,$resumo,$keys,$id,'SEMIC',$professor,$issue);
					//print_r($line);
				} else {
					echo 'Erro ao localizar o arquivo original';
				}		
		}

	function resumos_sem_ingles($ano)
		{
			global $jid;
			$sql = "select * from articles 
					left join sections on article_section = section_id
					inner join pibic_professor on pp_cracha = article_author_pricipal
					where articles.journal_id = $jid
					and (article_2_abstract = '')
					and article_publicado <> 'X'
				order by sections.seq, pp_nome, title, article_title 
			";
			$rlt = db_query($sql);
			$tot = 0;
			$sx .= '<H1>Resumos aguardando submissão do Inglês</H1>';
			$sx .= '<table width="100%" class="lt1">';
			$sx .= '<TR><TH>Protocolo<TH>Título';
			$upx = '';
			$secaox = '';
			while ($line = db_read($rlt))
				{
					$secao = trim($line['abbrev']).' '.trim($line['title']);
					if ($secao != $secaox)
						{
							$sx .= '<TR><TD colspan=3 class="lt3"><B><I>'.$secao;
							$secaox = $secao;
						}
					$up = trim(uppercasesql($line['article_title']));
					$up = troca($up,chr(13),'');
					$up = troca($up,chr(10),'');
					if (round($line['article_revisado']) != 1)
						{
						$cor = '';
						if ($up == $upx)
							{ $cor = '<font color="red">'; }
						$upx =  $up;
						$link = page();
						$link = troca($link,'.php','_revisar.php');
						$link .= '?dd0='.$line['id_article'];
						$link .= '&dd90='.checkpost($line['id_article']);
						$tot++;
						$sx .= '<TR '.coluna().'>';
						$sx .= '<TD>';
						$sx .= '<A HREF="'.$link.'">';
						$sx .= $cor;
						$sx .= strzero($line['id_article'],7);
						$sx .= '</A>';
						$sx .= '<TD>';
						$sx .= $cor;
						$sx .= trim($line['article_title']);
						
						$sx .= '<TD>';
						$sx .= $cor;
						$sx .= trim($line['article_publicado']);
						
						$sx .= '<TR '.coluna().'>';
						$sx .= '<TD>';
						$sx .= '<TD>';						
						$sx .= trim($line['pp_nome']);
												
						}
				}
			$sx .= '<TR><TD colspan=2>total de '.$tot.' resumos prontos para serem revisados em seu Inglês';
			$sx .= '</table>';
			return($sx);
		}

	function resumos_para_revisao($ano)
		{
			global $jid;
			$sql = "select * from articles 
					left join sections on article_section = section_id
					where articles.journal_id = $jid
					and (article_2_abstract <> '')
					and article_publicado <> 'X'
				order by article_apresentacao, sections.seq, article_modalidade desc, title, article_title
			";
			$rlt = db_query($sql);
			$tot = 0;
			$sx .= '<H1>Resumos aguardando revisão de Inglês</H1>';
			$sx .= '<img src="../img/icone_star_2.jpg" height="24"> Prioridade na revisão';
			$sx .= '<table width="100%" class="lt1" border=0 >';
			$sx .= '<TR><TH>Protocolo<TH width="25">en<TH>Título';
			$upx = '';
			$secaox = '';
			$tote = 0;
			while ($line = db_read($rlt))
				{
					$idioma = trim($line['article_apresentacao']);
					$tipo = ' ('.trim($line['article_modalidade']).')';
					$titulo = trim($line['article_title']);
					$idioma_img = $idioma;
					if ($idioma == 'en')
					{
						$titulo = trim($line['article_2_title']);
						$idioma_img = '<img src="../img/icone_star_2.jpg" height="18">';
						$tote++;	
					}
					
					$secao = trim($line['abbrev']).' '.trim($line['title']);
					if ($secao != $secaox)
						{
							$sx .= '<TR><TD colspan=4 class="lt3"><B><I>'.$secao;
							$secaox = $secao;
						}
					$up = trim(uppercasesql($line['article_title']));
					$up = troca($up,chr(13),'');
					$up = troca($up,chr(10),'');
					if (round($line['article_revisado']) != 1)
						{
						$cor = '';
						if ($up == $upx)
							{ $cor = '<font color="red">'; }
						$upx =  $up;
						$link = page();
						$link = troca($link,'.php','_revisar.php');
						$link .= '?dd0='.$line['id_article'];
						$link .= '&dd90='.checkpost($line['id_article']);
						$tot++;
						$sx .= '<TR '.coluna().'>';
						$sx .= '<TD>';
						$sx .= '<A HREF="'.$link.'">';
						$sx .= $cor;
						$sx .= strzero($line['id_article'],7);
						$sx .= '</A>';
						$sx .= '<TD>';
						$sx .= $idioma_img;
						$sx .= '<TD>';
						$sx .= $cor;
						$sx .= $titulo;
						$sx .= $tipo;
						$sx .= '<TD>';
						$sx .= $cor;
						$sx .= trim($line['article_publicado']);
						
												
						}
				}
			$sx .= '<TR><TD colspan=4>total de '.$tot.' resumos prontos para serem revisados em seu Inglês';
			$sx .= '</table>';
			return($sx);
		}
		
	function relatorio_aprovados_para_publicacao($ano)
		{
			$sql = "select * from pibic_bolsa_contempladas 
					where pb_relatorio_final_nota > -100
					and pb_ano = '$ano' and pb_status <> 'C'
			";
			$rlt = db_query($sql);
			$tot = 0;
			$sx .= '<H1>Resumos aprovados para publicação</H1>';
			$sx .= '<table width="100%" class="lt1">';
			$sx .= '<TR><TH>Protocolo<TH>Título';
			while ($line = db_read($rlt))
				{
					if (round($line['pb_semic']) < 20010101)
					{
					$link = page();
					$link = troca($link,'.php','_publicar.php');
					$link .= '?dd0='.$line['pb_protocolo'];
					$link .= '&dd90='.checkpost($line['pb_protocolo']);
					$tot++;
					$sx .= '<TR '.coluna().'>';
					$sx .= '<TD>';
					$sx .= '<A HREF="'.$link.'">';
					$sx .= trim($line['pb_protocolo']);
					$sx .= '</A>';
					$sx .= '<TD>';
					$sx .= trim($line['pb_titulo_projeto']);
					}
				}
			$sx .= '<TR><TD colspan=2>total de '.$tot.' resumos prontos para serem avaliados';
			$sx .= '</table>';
			return($sx);
		}
		
	function area_resumo_gr($dd1='',$dd2='')
		{
			global $dd;
			if (strlen($dd1) == 0) { $dd1 = date("Y")-1; }
			if (strlen($dd2) == 0) { $dd2 = "PIBIC"; }
			
			$sql = "select sum(total) as total, pb_semic_area from (";
			$sql .= "select 1 as total, substr(pb_semic_area,1,1) as pb_semic_area from pibic_bolsa_contempladas ";
			$sql .= " left join pibic_bolsa_tipo on pb_tipo = pbt_codigo ";
			$sql .= " where (pb_status <> 'C')  ";		
			if (strlen(trim($dd1)) > 0)
				{ $sql .= " and (pb_ano = '".$dd1."') "; }
			if (strlen(trim($dd2)) > 0)
				{ $sql .= " and (pbt_edital = '".$dd2."') "; }
			$sql .= ") as tabela ";
			$sql .= " group by pb_semic_area ";
			$sql .= " order by pb_semic_area ";
			$rlt = db_query($sql);
			
			$toti=0;
			$ar = array('1'=>'Ciências Exatas e da Terra',
						'2'=>'Ciências Biológicas',
						'3'=>'Engenharias',
						'4'=>'Ciências da Saúde',
						'5'=>'Ciências Agrárias',
						'6'=>'Ciências Sociais Aplicadas',
						'7'=>'Ciências Humanas',
						'8'=>'Lingüística, Letras e Artes',
						'9'=>'Outros');
			$rlt = db_query($sql);
			$sx .= '<table>';
			$tot = 0;
			while ($line = db_read($rlt))
				{
					$tot = $tot + $line['total'];
					$sx .= '<TR><TD>'.$line['pb_semic_area'].'-'.$ar[$line['pb_semic_area']];
					$sx .= '<TD align="right">'.$line['total'];
				}	
			$sx .= '<TR><TD>Total<TD>'.$tot;
			$sx .= '</table>';
			return($sx);		
		}
	function area_resumo($tp='')
		{
		global $dd;
		$fld1 = "pb_relatorio_parcial";	
		$fld2 = "pb_relatorio_final";	
		/***** Calcula Gráfico ****/
		/** Idiomas **/
		$sql = "select count(*) as total, pb_semic_area, a_descricao from pibic_bolsa_contempladas ";
		$sql .= " left join ajax_areadoconhecimento on pb_semic_area = a_cnpq ";
		$sql .= " where ((".$fld1." > 20000101) or (".$fld2." > 20000101)) and (pb_status = 'A')  ";		
		if (strlen(trim($dd[1])) > 0)
			{ $sql .= " and (pb_ano = '".$dd[1]."') "; }
		if (strlen(trim($dd[2])) > 0)
			{ $sql .= " and (pb_tipo = '".$dd[2]."') "; }
		$sql .= " group by pb_semic_area, a_descricao ";
		$sql .= " order by pb_semic_area ";
		$rlt = db_query($sql);
		$toti=0;
		
		$sx = '<table width="100%">';
		$sx .= '<TR><TH>área<TH>descrição<TH>total';
		$tot = 0;
		while ($line = db_read($rlt))
			{
				$tot++;
				$sx .= '<TR '.coluna().'>';
				$sx .= '<TD>';
				$sx .= $line['pb_semic_area'];
				$sx .= '<TD>';
				$sx .= $line['a_descricao'];
				$sx .= '<TD align="center">';
				$sx .= $line['total'];
			}		
		$sx .= '<TR><TD colspan=3 align="right"><B><I>Total '.$tot;
		$sx .= '</table>';
		return($sx);	
		}

	function area_detalhe()
		{
		global $dd;
		$fld1 = "pb_relatorio_parcial";	
		$fld2 = "pb_relatorio_final";	
		/***** Calcula Gráfico ****/
		/** Idiomas **/
		$sql = "select * from pibic_bolsa_contempladas ";
		$sql .= " left join ajax_areadoconhecimento on pb_semic_area = a_cnpq ";
		$sql .= " where ((".$fld1." > 20000101) or (".$fld2." > 20000101)) and (pb_status = 'A')  ";		
		if (strlen(trim($dd[1])) > 0)
			{ $sql .= " and (pb_ano = '".$dd[1]."') "; }
		if (strlen(trim($dd[2])) > 0)
			{ $sql .= " and (pb_tipo = '".$dd[2]."') "; }
		$sql .= " order by pb_semic_area ";
		$rlt = db_query($sql);
		$toti=0;
		
		$sh = '<TR>';
		$sh .= '<TH>'.msg('protocol');
		$sh .= '<TH>'.msg('title');
		$idi = 'xx';
		$tot = 0;
		$tot1 = 0;
		while ($line = db_read($rlt))
			{
				$idm = trim($line['pb_semic_area']);
				
				if ($idi != $idm)
					{
						if ($tot1 > 0)
						{ $sx .= '<TR><TD colspan=4 align=right><I>subtotal '.$tot1; }
						$tot1 = 0;
						$idi = $idm;
						$sx .= '<TR class="lt3"><TD colspan=4><B><I>'.$idm.' '.$line['a_descricao']; 
					}
				$sx .= '<TR '.coluna().'>';
				$sx .= '<TD>'.$line['pb_protocolo'];
				$sx .= '<TD>'.$line['pb_titulo_projeto'];
				$sx .= '<TD>'.$line['pb_professor'];
				$sx .= '<TD>'.$line['pb_aluno'];
				$tot = $tot + 1;
				$tot1 = $tot1 + 1;
			}
		if ($tot1 > 0)
			{ $sx .= '<TR><TD colspan=4 align=right><I>subtotal '.$tot1; }
		if ($tot > 0)
			{ $sx .= '<TR><TD colspan=4 align=right><B><I>total '.$tot; }
			
		echo '<table class="lt1" width="'.$tab_max.'">';
		echo $sh.$sx;
			
		echo '</table>';
		return(1);	
		}
	function idiomas_resumo()
		{
		global $dd;
		$fld1 = "pb_relatorio_parcial";	
		$fld2 = "pb_relatorio_final";	
		/***** Calcula Gráfico ****/
		/** Idiomas **/
		$sql = "select count(*) as total, pb_semic_idioma from pibic_bolsa_contempladas ";
		$sql .= " where ((".$fld1." > 20000101) or (".$fld2." > 20000101)) and (pb_status = 'A')  ";		
		if (strlen(trim($dd[1])) > 0)
			{ $sql .= " and (pb_ano = '".$dd[1]."') "; }
		if (strlen(trim($dd[2])) > 0)
			{ $sql .= " and (pb_tipo = '".$dd[2]."') "; }
				$sql .= " group by pb_semic_idioma ";
		$rlt = db_query($sql);
		$data_idioma = array();
		$toti=0;

		/*** Acertas possíveis erros de envio **/
		//$sql = "update pibic_bolsa_contempladas set pb_semic_idioma = 'pt_BR' where ((pb_semic_idioma = '') or (pb_semic_idioma isnull)) and  (".$fld1." > 20120101) ";
		//echo $sql;
		//$xrlt = db_query($sql);
		
		//echo $sql;
		while ($line = db_read($rlt))
			{
				$toti=$toti+$line['total'];
				array_push($data_idioma,array($line['total'],$line['pb_semic_idioma']));
			}
		$si .= '<center>Idiomas de apresentação</center>';
		$si .= '<table width="300" cellpadding="0" cellpagind=0 class="lt1">';
		$si .= '<TR><Th>cod<Th>idioma<TH>total<TH>perc';
		for ($r=0;$r < count($data_idioma);$r++)
			{
				$si .= '<TR>';
				$si .= '<TD>';
				$idi = trim($data_idioma[$r][1]);
				$si .= $idi;
				$si .= '<TD>';
				$si .= $idioma[$idi];
				$si .= '<TD align="center">';
				$si .= $data_idioma[$r][0];
				$si .= '<TD align="center">';
				if ($toti > 0)
					{ $si .= number_format(($data_idioma[$r][0])/$toti*100,1).'%'; }
				else { $si .= '-'; }			}
		$si .= '</table>';
		return($si);
	}
	function idiomas_detalhe()
		{
		global $dd;
		$fld1 = "pb_relatorio_parcial";	
		$fld2 = "pb_relatorio_final";	
		/***** Calcula Gráfico ****/
		/** Idiomas **/
		$sql = "select * from pibic_bolsa_contempladas ";
		$sql .= " where ((".$fld1." > 20000101) or (".$fld2." > 20000101)) and (pb_status = 'A')  ";		
		if (strlen(trim($dd[1])) > 0)
			{ $sql .= " and (pb_ano = '".$dd[1]."') "; }
		if (strlen(trim($dd[2])) > 0)
			{ $sql .= " and (pb_tipo = '".$dd[2]."') "; }
		$sql .= " order by pb_semic_idioma ";
		$rlt = db_query($sql);
		$data_idioma = array();
		$toti=0;

		$sh = '<TR>';
		$sh .= '<TH>'.msg('protocol');
		$sh .= '<TH>'.msg('title');
		$idi = 'xx';
		$tot = 0;
		$tot1 = 0;
		while ($line = db_read($rlt))
			{
				$idm = trim($line['pb_semic_idioma']);
				
				if (strlen(trim($idm))==0)
					{
						$sql = "update pibic_bolsa_contempladas set pb_semic_idioma = 'pt_BR' where id_pb = ".$line['id_pb'];
						$xrlt = db_query($sql);
					}
				if ($idi != $idm)
					{
						if ($tot1 > 0)
						{ $sx .= '<TR><TD colspan=4 align=right><I>subtotal '.$tot1; }
						$tot1 = 0;
						$idi = $idm;
						$sx .= '<TR class="lt3"><TD colspan=4><B><I>'.$idm; 
					}
				$sx .= '<TR '.coluna().'>';
				$sx .= '<TD>'.$line['pb_protocolo'];
				$sx .= '<TD>'.$line['pb_titulo_projeto'];
				$sx .= '<TD>'.$line['pb_professor'];
				$sx .= '<TD>'.$line['pb_aluno'];
				$tot = $tot + 1;
				$tot1 = $tot1 + 1;
			}
		if ($tote > 0)
			{ $sx .= '<TR><TD colspan=4 align=right><I>subtotal em Inglês '.$tote; }
		if ($tot1 > 0)
			{ $sx .= '<TR><TD colspan=4 align=right><I>subtotal '.$tot1; }
		if ($tot > 0)
			{ $sx .= '<TR><TD colspan=4 align=right><B><I>total '.$tot; }
			
		echo '<table class="lt1" width="'.$tab_max.'">';
		echo $sh.$sx;
			
		echo '</table>';
		return(1);
	}

}

