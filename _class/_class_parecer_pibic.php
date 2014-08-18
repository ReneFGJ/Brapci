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
	
	var $total_indicacoes;



	var $tabela = "pibic_parecer_2013";
	
	function avaliacoes_indicadas($professor,$ano)
		{
			$this->tabela = "pibic_parecer_".$ano;
			$sql = "select * from ".$this->tabela."
					where pp_avaliador = '".trim($professor)."' 
					order by pp_tipo, pp_data
			";
			$rlt = db_query($sql);
			
			$sx .= '<table class="tabela00" width="100%">';
			$xtipo = '';
			$status = array('@'=>'<font color="blue">Não avaliado</font>','A'=>'Avaliador','B'=>'Avaliador e liberado declaração','D'=>'<font color="orange">Declinado</font>');
			
			while ($line = db_read($rlt))
			{
				$link = '';
				if ($line['pp_status']=='@')
					{
						$link = '<A HREF="#" onclick="newxy2(\'parecer_declinar.php?dd0='.$line['id_pp'].'&dd1='.$this->tabela.'\',300,200);">declinar</A>';
					}
				if ($line['pp_status']=='D')
					{
						$link = '<A HREF="#" onclick="newxy2(\'parecer_alterar_status.php?dd0='.$line['id_pp'].'&dd1='.$this->tabela.'\',300,200);">alterar status</A>';
					}					
				$tipo = trim($line['pp_tipo']);
				if ($tipo != $xtipo)
					{
						$sx .= '<TR><TD colspan=10 class="lt4">';
						$sx .= msg('tipo_'.$tipo);
						$xtipo = $tipo;
					}
				$dtp = $line['pp_parecer_data'];
				if ($dtp < 20100101) { $dtp = ''; } else {
					$dtp .= ' - '.$line['pp_parecer_hora'];
				}
				$sx .= '<TR>';
				$sx .= '<TD class="tabela01" align="center">'.$line['pp_protocolo'];
				$sx .= '<TD>'.$line['pp_tipo'];
				$sx .= '<TD class="tabela01" align="center">'.$status[$line['pp_status']];
				$sx .= '<TD class="tabela01" align="center">'.stodbr($line['pp_data']);
				$sx .= '<TD class="tabela01" align="center">'.$dtp;
				$sx .= '<TD class="tabela01" align="center">'.$link;
			}
			$sx .= '</table>';
			return($sx);
		}
	
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

	function declinar($id)
		{
			$sql = "select * from ".$this->tabela." where id_pp = ".round($id);
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
				$sql = "update ".$this->tabela." set pp_status = 'D' 
						where pp_status = '@' 
						and id_pp = ".round($id);
				$rlt = db_query($sql);
				}
			return(1);
		}

	function avaliacoes_mudar_deadline($tipo,$data)
		{
			$sql = "update ".$this->tabela." set pp_data = ".round($data)." where pp_tipo = '".$tipo."' and pp_status = '@' ";
			$rlt = db_query($sql);	
			return(1);
		}

	function enviar_email_indicacao($proto,$avaliador,$rtipo,$trocas=array())
		{
			global $pb,$http;
			
			$par = $avaliador;
			$nome = $par->nome;
			$titulo_trabalho = $pb->pb_titulo_projeto;
			$professor = $pb->pb_professor_nome;
			$protocolo = $pb->pb_protocolo;

			$aluno = $pb->pb_est_nome;
			$instituicao = $par->instituicao;
			$email = array();
			array_push($email,$par->email);
			array_push($email,$par->email_alt);
			array_push($email,'monitoramento@sisdoc.com.br');
			array_push($email,$admin_email);
			$linka = $par->link_avaliador;
			
			$ic = new ic;
			$icname = 'ic_'.$rtipo."_indicacao";
			$line = $ic->ic($icname);
			
			$email_copia = 'monitoramento@sisdoc.com.br';
			$titulo = trim($line['nw_titulo']);
			$texto = mst(trim($line['nw_descricao'])).'<BR><BR>'.$icnane;

			$texto = troca($texto,'$LINK',$linka);
			$texto = troca($texto,'$link',$linka);
			$texto = troca($texto,'$nome',$nome);
			$texto = troca($texto,'$avaliador',$nome);
			$texto = troca($texto,'$titulo',$titulo_trabalho);
			$texto = troca($texto,'$aluno',$aluno);
			$texto = troca($texto,'$professor',$professor);
			$texto = troca($texto,'$protocolo',$protocolo);
			
			$texto = troca($texto,'$INSTITUICAO',$instituicao);
			
			
			for ($r=0;$r < count($trocas);$r++)
				{
					$de   = $trocas[$r][0];
					$para = $trocas[$r][1];
					$texto = troca($texto,$de,$para);
				}

			$texto = '<img src="'.$http.'img/email_ic_header.png"><BR>'.$texto;
			$texto .= '<BR><BR><img src="'.$http.'img/email_ic_foot.png"><BR>'.$icname;
										
			for ($r=0;$r < count($email);$r++)
				{
					$email_send = trim($email[$r]);
					if (strlen($email_send) > 0)
						{
							 enviaremail($email_send,'',$titulo,$texto);
							 //echo '<BR>E-mail enviado para '.$email_send;
						}
				}
			echo '<font color="green">Indicado!</font>';
			return(True);
		}
	
	
	function valor_avaliacao($nt)
		{
			switch ($nt)
				{
				case 20: $sx = '<font color="green">Excelente</font>'; break;
				case 17: $sx = '<font color="green">Muito bom</font>'; break;
				case 15: $sx = '<font color="brown">Bom</font>'; break;
				case 12: $sx = '<font color="brown">Regular</font>'; break;
				case 7: $sx = '<font color="red">Ruim</font>'; break;
				case 1: $sx = '<font color="red">Muito ruim</font>'; break;
				}
			return($sx);			
		}
	function valor_cronograma($nt)
		{
			switch ($nt)
				{
				case 10: $sx = '<font color="green">adequado</font>'; break;
				case 7: $sx = '<font color="brown">parcialmente adequado</font>'; break;
				case 1: $sx = '<font color="red">indequado</font>'; break;
				}
			return($sx);			
		}		
	function valor_avaliacao_plano($nt)
		{
			switch ($nt)
				{
				case 15: $sx = '<font color="green">Excelente</font>'; break;
				case 13: $sx = '<font color="green">Muito bom</font>'; break;
				case 10: $sx = '<font color="brown">Bom</font>'; break;
				case 7: $sx = '<font color="brown">Regular</font>'; break;
				case 5: $sx = '<font color="red">Ruim</font>'; break;
				case 1: $sx = '<font color="red">Muito ruim</font>'; break;
				}
			return($sx);			
		}		
	function visualizar_parecer($protocolo)
		{
			$crt = array();
			$crt[1] = 'Critério 1: Relevância do projeto do orientador e contribuição para a formação do aluno.';
			$crt[2] = 'Critério 2: Coerência do projeto do orientador de acordo com os itens: Introdução, Objetivo, Método e Referências Bibliográficas.';
			$crt[3] = 'Critério 3: Coerência e adequação entre a capacitação e a experiência do professor orientador proponente e a realização do projeto, considerando as informações curriculares apresentadas.';
			$crt[4] = '';
			$crt[5] = 'Critério 4: Coerência entre o projeto do orientador e o plano de trabalho do aluno, considerando a contribuição para a formação do discente.';
			$crt[6] = 'Critério 5: Roteiro de atividades do aluno considerando a sua adequação ao processo de iniciação científica.';
			$crt[7] = 'Critério 6: Adequação do cronograma para a execução da proposta.';

			$this->tabela = "pibic_parecer_".date("Y");
			
			$sql = "select * from ".$this->tabela." 
					where (pp_protocolo_mae = '$protocolo' 
						or pp_protocolo = '$protocolo')
					and pp_status <> 'D' and pp_status <> '@' 
					order by pp_protocolo, pp_avaliador
					";
			$rlt = db_query($sql);
			
			$xava = '';
			$quali = '';
			$av = 1;
			$av1 = 1;
			
			$sa = 0;
			
			$vlr = array(0,0,0,0,0,0,0,0);
			while ($line = db_read($rlt))
				{
					$proto = $line['pp_protocolo'];
					$pp01 = $line['pp_p01'];
					$pp02 = $line['pp_p02'];
					$pp03 = $line['pp_p03'];
					
					$pp05 = $line['pp_p05'];
					$pp06 = $line['pp_p06'];
					$pp07 = $line['pp_p07'];	
					$proto = $line['pp_protocolo'];		 
					
					$aval = trim($line['pp_avaliador']);
					if (substr($proto,0,1)=='1')
						{
							$quali .= mst(trim($line['pp_abe_01'])).'<HR>';
							$xava = $aval;
							
							$rs1 .= '<BR>Avaliador '.($av).': <B>'.$this->valor_avaliacao($pp01).'</B>';
							$rs2 .= '<BR>Avaliador '.($av).': <B>'.$this->valor_avaliacao($pp02).'</B>';
							$rs3 .= '<BR>Avaliador '.($av).': <B>'.$this->valor_avaliacao($pp03).'</B>';
							$av++;
						} else {
							if ($proto != $xproto)
								{
									$av1 = 1;
									$xproto = $proto;
									
								}
											
							$rs4 .= '<BR>Protocolo:'.$proto.', avaliador '.$av1.': '.$this->valor_avaliacao_plano($pp05).'</B>';
							$rs5 .= '<BR>Protocolo:'.$proto.', avaliador '.$av1.': '.$this->valor_avaliacao_plano($pp06).'</B>';
							$rs6 .= '<BR>Protocolo:'.$proto.', avaliador '.$av1.': '.$this->valor_cronograma($pp07).'</B>';
							$av1++;
						}
				}

				$sx .= '<h3>Projeto do professor</h3>';

				$sx .= '<BR><B>'.$crt[1].'</B>';
				$sx .= $rs1;
				$sx .= '<BR><BR><B>'.$crt[2].'</B>';
				$sx .= $rs2;
				$sx .= '<BR><BR><B>'.$crt[3].'</B>';
				$sx .= $rs3;
				
				$sx .= '<h3>Plano(s) de aluno(s)</h3>';								

				$sx .= '<B>'.$crt[5].'</B>';
				$sx .= $rs4;
				$sx .= '<BR><BR><B>'.$crt[6].'</B>';
				$sx .= $rs5;	
				$sx .= '<BR><BR><B>'.$crt[7].'</B>';
				$sx .= $rs6;										
			$sx .= '<BR><BR><B>Parecer qualitativo</B><BR><BR>';
			$sx .= $quali;
			echo $sx;
					
		}
	
	
	function calcular_edital()
		{	
			$sql = 'select * from '.$this->tabela."
					left join pibic_projetos on pp_protocolo_mae = pj_codigo
					where pp_tipo = 'SUBMP' and pp_status = 'B'
					order by pp_protocolo_mae, pp_protocolo 
				";
			$rlt = db_query($sql);
			
			$sx = '<table width="100%" class="tabela00">';
			$id = 0;
			$n1 = 0;
			$nota = 0;
			$prj = 0;
			$xprtom = '';
			while ($line = db_read($rlt))
			{
				$id = $line['id_pj'];
				$link = '<A HREF="pibic_projetos_detalhes.php?dd0='.$id.'&dd90='.checkpost($id).'" target="new">';
				$id++;
				$prot = $line['pp_protocolo'];
				$protm = $line['pp_protocolo_mae'];
				if ($prot != $xprot)
					{
						if (strlen($xprot) > 0)
						{
						if ($n1 > 1)
							{
							$media = $nota / $n1;
							if (($n1 >=2) and ($media < 65)) { $font = '<font color="red">'; }
							else { $font = '<font color="grey">'; }
							}
						if ($n1 == 1 ) { $font = '<font color="blue">'; }
						
						$sx .= '<TD colspan=1 align="left" class="tabela01">';
						$sx .= $font;
						$sx .= 'Nota: ';
						$sx .= $nota;
						$sx .= ' ('.$n1.')';
						$sx .= '</font>';
						}
						
						$n1 = 0;
						$xprot = $prot;
						$nota = 0;
					}
				if ($protm != $xprotm)
					{
						$sx .= '<TR><TD colspan=6 class="lt3"><B>'.$link.$protm.'</A></B>';
						$xprotm = $protm;
					}
				$sx .= '<tr>';
				$sx .= '<td class="tabela01" width="7%">';
				$sx .= $line['pp_protocolo'];
				$sx .= '<td class="tabela01">';
				$sx .= $line['pp_p01'];
				$sx .= '<td class="tabela01">';
				$sx .= $line['pp_p02'];
				$sx .= '<td class="tabela01">';
				$sx .= $line['pp_p03'];
				$sx .= '<td class="tabela01">';
				$sx .= $line['pp_p04'];
				$sx .= '<td class="tabela01">';
				$sx .= $line['pp_p05'];
				$sx .= '<td class="tabela01">';
				$sx .= $line['pp_p06'];
				$sx .= '<td class="tabela01">';
				$sx .= $line['pp_p07'];
				$sx .= '<td class="tabela01">';
				$sx .= $line['pp_p08'];
				$sx .= '<TR><TD><td class="tabela01" colspan=8>';
				$sx .= $line['pp_abe_01'];
									
				$n = round($line['pp_p01']) + round($line['pp_p01']) + round($line['pp_p01']);
				$n = $n + round($line['pp_p05']) + round($line['pp_p06']) + round($line['pp_p07']);
				$nota = $nota + $n;
				$n1++;
			}
			$sx .= '<TR><TD>'.$id;
			$sx .= '</table>';
			return($sx);
		}
	
	function row()
			{
				global $cdf,$cdm,$masc;
				$cdf = array("id_pp","pp_nrparecer","id_pp","pp_protocolo","pp_tipo","pp_data","pp_status","pp_avaliador");
				$cdm = array('cod',msg('Parecer'),msg('Mae'),msg('centro'),msg('curso'));
				$masc = array('','','','','','','','');
				return(1);				
			}		
	

	function avaliacao_row($line)
		{
				$link = '<A HREF="javascript:newxy2(\'parecer_declinar.php?dd0='.$line['id_pp'].'&dd90='.checkpost($line['id_pp']).'&dd1='.$this->tabela.'&dd2=DECLINAR\',400,200);">';
				$linkA = '<A HREF="javascript:newxy2(\'parecer_visualizar.php?dd0='.$line['id_pp'].'&dd90='.checkpost($line['id_pp']).'&dd1='.$this->tabela.'\',700,500);">';
				$linkp = '<A HREF="'.http.'pibicpr/pibic_projetos_detalhes.php?dd0='.$line['id_pj'].'&dd90='.checkpost($line['id_pj']).'" target="_new">';
				$sta = trim($line['pp_status']);
				if ($sta=='@') {$sta = $link.'declinar'.'</A>'; }
				if ($sta=='B') {$sta = 'avaliado'; }
				if ($sta=='D') {$sta = '<font color="red">declinado<font>'; }
				$sx = '<TR>';
				$sx .= '<TD class="tabela01" align="center">';
				$sx .= $linkp;
				$sx .= trim($line['pp_protocolo']);
				$sx .= '</A>'; 
				$sx .= '<TD class="tabela01" align="center">';
				$sx .= trim($line['pp_protocolo_mae']); 
				$sx .= '<TD class="tabela01" align="center">';
				$sx .= $sta; 
				$sx .= '<TD class="tabela01">';
				$sx .= trim($line['pj_titulo']);
				$sx .= '<TD class="tabela01">';
				$sx .= trim($line['pj_professor']);
				$sx .= '<TD class="tabela01" align="center">';
				$sx .= trim($line['pp_tipo']);
				$sx .= '<TD class="tabela01" align="center">';
				$sx .= stodbr($line['pp_data']);
				$sx .= '<TD class="tabela0" align="center">';
				$txt = trim($line['pp_abe_14']);
				if (strlen($txt) > 0)
					{
						$dialog = '<A HREF="#">';
						$dialog = '<IMG SRC="'.http.'img/icone_blog.png" height="20" title="'.$txt.'">';
						$dialog .= '</A>'; 
					}
				$sx .= $dialog;
				//echo '<HR>';
				//print_r($line); 
				return($sx);
			
		}
	
	function avaliacoes_pelo_avaliador($avaliador)
		{
			$this->tabela = 'pibic_parecer_'.date("Y");
			$sql = "select * from pibic_parecer_".date("Y")."
					inner join pibic_projetos on pj_codigo = pp_protocolo
					where pp_avaliador = '$avaliador'
			";
			$rlt = db_query($sql);
			$sx .= '<table class="tabela00" width="100%">';
			$sx .= '<TR><TH>Protocolo<TH><TH>Status<TH>Título<TH>Professor<TH>Tipo';
			while ($line = db_read($rlt))
				{
					$sx .= $this->avaliacao_row($line);
				}
			$sx .= '</table>';
			echo $sx;
			return($sx);
		}
	
	function parecer_abertos_submissao($dd1=20100101,$dd2=20500101,$tipo='',$status)
		{
			global $jid;
			
			$sql = "select * from ".$this->tabela;
			$sql .= " inner join pareceristas on pp_avaliador = us_codigo ";
			$sql .= " inner join pibic_projetos on pp_protocolo = pj_codigo ";
			$sql .= " where us_journal_id = ".round($jid);
			$sql .= " and pp_status = '$status' ";
			//if (strlen($tipo) > 0)
			//	{ $sql .= " and pp_tipo = '".$tipo."' "; }
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
					if ($line['pp_status'] != '@') {
						 $link = 'AVALIADO';
						if ($line['pp_status'] == 'D') { $link = 'DECLINADO'; } 
						}
					$linkv = '<A HREF="pibic_projetos_detalhes.php?dd0='.$line['pp_protocolo'].'&dd90='.checkpost($line['pp_protocolo']).'" target="_new'.$line['id_pp'].'">';
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
					$sx .= $linkv.$line['pp_protocolo'].'</A>';
					
					$sx .= '<TD class="tabela01">';
					$sx .= $line['pj_titulo'];
					
					$sx .= '<TD class="tabela01">';
					$sx .= $line['pp_tipo'];					
					
					$ln = $line;
				}
			$sx .= '<TR><TD colspan=10><B>'.msg('total').' '.$id.'</B>';
			$sx .= '</table>';
			//print_r($ln);
			return($sx);
		}	
	
	function parecer_abertos($dd1=20100101,$dd2=20500101,$tipo='',$status)
		{
			global $jid;
			$sql = "select * from ".$this->tabela;
			$sql .= " inner join pareceristas on pp_avaliador = us_codigo ";
			$sql .= " inner join submit_documento on pp_protocolo = doc_protocolo ";
			$sql .= " where us_journal_id = ".round($jid);
			$sql .= " and pp_status = '$status' ";
			//if (strlen($tipo) > 0)
			//	{ $sql .= " and pp_tipo = '".$tipo."' "; }
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
					
					$sx .= '<TD class="tabela01">';
					$sx .= $line['pp_tipo'];					
					
					$ln = $line;
				}
			$sx .= '<TR><TD colspan=10><B>'.msg('total').' '.$id.'</B>';
			$sx .= '</table>';
			//print_r($ln);
			return($sx);
		}	
	
	function avaliadores($proto)
		{
			$cp = "id_pp as id, pp_protocolo, pp_tipo, pp_avaliador, pp_status, pp_data, pp_parecer_data";
			$sql = "
				select * from (
				select $cp from pibic_parecer_".date("Y")." where pp_protocolo = '$proto' and pp_status <> 'D'
				union
				select $cp from pibic_parecer_".(date("Y")-1)." where pp_protocolo = '$proto' and pp_status <> 'D'
				) as tabela
				left join pibic_professor on pp_cracha = tabela.pp_avaliador
				order by pp_data desc 
			";
			$rlt = db_query($sql);
			$sx = '<table class="tabela00" width="100%">';
			$sx .= '<TR><TH>Protocolo<TH>tipo<TH>Avaliador<TH>Status<TH>Indicação<TH>Avaliação';
			
			while ($line = db_read($rlt))
			{
				//echo '<BR>Nota: '.$line['pp_p01'];
				$id = $line['id'];
				$link = '<A HREF="parecer_view.php?dd0='.$id.'&dd90='.checkpost($id).'" targer="_NEW_'.$id.'">';
				if (trim($line['pp_tipo'])<> 'RFIN') { $link = ''; }
				$sx .= '<TR>';
				$sx .= '<TD class="tabela01" align="center">';
				$sx .= $line['pp_protocolo'];
				$sx .= '<TD class="tabela01" align="center">';
				$sx .= $line['pp_tipo'];
				$sx .= '<TD class="tabela01" align="center">';
				$sx .= $line['pp_avaliador'];
				$sx .= '<TD class="tabela01">';
				$sx .= $line['pp_nome'];
				$sx .= '<TD class="tabela01" align="center">';
				$sta = $line['pp_status'];
				if ($sta=='@') { $sta = 'indicado'; }
				if ($sta=='A') { $sta = 'lido, não finalizado'; }
				if ($sta=='B') { $sta = $link.'avaliado'.'</A>'; }
				$sx .= $sta;
				$sx .= '<TD class="tabela01" align="center">';
				$sx .= stodbr($line['pp_data']);
				$sx .= '<TD class="tabela01" align="center">';
				if ($line['pp_parecer_data'] > 0)
					{
						$sx .= stodbr($line['pp_parecer_data']);	
					} else {
						$sx .= '-';
					}
			}
			$sx .= '</table>';
			return($sx);
		}
	
	function cancelar_avaliacoes($tipo)
		{
			echo 'Item suspenso';
			exit;
			$this->reativar_avaliacoes_canceladas($tipo);
			exit;
			$sql = "update ".$this->tabela." set pp_status = 'B' where pp_status = '@' and pp_tipo = '$tipo' ";
			$rlt = db_query($sql);
			
			$sql = "select * from pibic_bolsa_contempladas where pb_relatorio_parcial_nota = -90 ";
			$rlt = db_query($sql);
			while ($line = db_read($rlt))
				{
					print_r($line);
					echo '<HR>';
				}
			
			$sql = "update pibic_bolsa_contempladas 
					set pb_relatorio_parcial_nota = -99
					where pb_relatorio_parcial_nota = -90 ";
			$rlt = db_query($sql);			
		}
	function reativar_avaliacoes_canceladas($tipo)
		{
			$sql = "select * from ".$this->tabela." 
				where 
					pp_status = 'B' 
					and (pp_abe_01 = '' or pp_abe_01 isnull)
					and pp_tipo = '$tipo'
					";
			$rlt = db_query($sql);
			$sql = "";
			$id = 0;
			while ($line=db_read($rlt))
				{
					$id++;
					$sql .= "update ".$this->tabela." set pp_status = '@' where id_pp = ".$line['id_pp'].';'.chr(13).chr(10);
					$sql .= "update pibic_bolsa_contempladas set pb_relatorio_parcial_nota = -90 where pb_protocolo = '".$line['pp_protocolo']."';".chr(13).chr(10);
				}
			echo 'Total-->';
			$rlt = db_query($sql);
			echo $id;			
		}		
	
	function acompanhamento_indicados($tipo)
		{
			$sql = "select tbl.id_pp as id_ppa, * from ".$this->tabela." as tbl 
					inner join pibic_professor on tbl.pp_avaliador = pp_cracha
					inner join pibic_bolsa_contempladas on pp_protocolo = pb_protocolo
					where pp_tipo = '$tipo'
					order by pp_nome, pp_protocolo
			";
			$rlt = db_query($sql);
			$xnome = 'x';
			$sx = '<table width="100%">';
			$tot1 = 0;
			$tot2 = 0;
			$tot3 = 0;
			$tot4 = 0;
			while ($line = db_read($rlt))
				{
					$tot1++;
					$nome = trim($line['pp_nome']);
					if ($xnome != $nome)
						{
						$sx .= '<TR>';
						$sx .= '<TD class="tabela01" colspan=10>';
						$sx .= $line['pp_nome'];
						//print_r($line);
						$xnome = $nome;
						}
					$sx .= '<TR id="h'.$line['id_ppa'].'" valign="top">';
					$sx .= '<TD>';
					$sta = $line['pp_status'];
					$link = '<span class="link" onclick="mostra_div('.$line['id_ppa'].')">';
					if ($sta == 'B') { $sta = 'Avaliado'; $tot2++; }
					if ($sta == 'D') { $sta = '<font color="orange">Declinou</font>'; $tot4++; }
					if ($sta == '@') { $sta = $link.'<font color="blue">Aberto</font></span>'; $tot3++; }
					$sx .= $sta;
					$sx .= '<TD>';
					$sx .= $line['pp_protocolo'];
					$sx .= '<TD>';
					$sx .= $line['pb_titulo_projeto'];
					$sx .= '<div id="j'.$line['id_ppa'].'" style="display: none;">';
					$sx .= '<input type="button" value="declinar" class="botao-geral" onclick="declinar('.$line['id_ppa'].',\''.$line['pp_protocolo'].'\');">';
					$sx .= '</div>'.chr(13);
					$ln = $line;
				}
			$sx .= '<TR><TD colspan=10>Total de '.$tot1.' projetos indicados';
			$sx .= '</table>';
			
			$sa = '<table width="400" class="tabela00">';
			$sa .= '<TR><TH align="center" width="20%">Avaliados
						<TH align="center" width="20%">Abertos
						<TH align="center" width="20%">Declinados
						<TH align="center" width="20%">Total indicados
						<th ALIGN="CENTER" width="20%">% avaliador';
			$sa .= '<TR><TD class="tabela01" align="center">'.$tot2;
			$sa .= '    <TD class="tabela01" align="center">'.$tot3;
			$sa .= '    <TD class="tabela01" align="center">'.$tot4;
			$sa .= '    <TD class="tabela01" align="center">'.$tot1;
			if ($tot1 > 0)
				{
				$sa .= '    <TD class="tabela01" align="center">'.number_format($tot2/$tot1*100,1).'%';
				}
			$sa .= '</table>';
			
			switch($tipo)
				{
				case 'RFIN': $arq = 'rf'; break;
				case 'RPAR': $arq = 'rp'; break;
				case 'RPAC': $arq = 'rpc'; break;
				case 'RFNR': $arq = 'rfr'; break;
				case 'RPAJ': $arq = 'rp'; break;
				}
			$sx .= '
			<script>
			function mostra_div(id)
				{
					var idj = "#j"+id;
					$(idj).show();
				}
			function declinar(id,protocolo)
				{
					var trs = "#TR"+id;
					var tri = "#j"+id;
					$(tri).show();
					var file = "'.$arq.'_indicar_avaliador_ajax.php?dd5="+protocolo+"&dd4='.$this->tabela.'&dd2=DECLINAR&dd3="+id+"&dd0="+id;
					var file = "gestao_indicar_avaliador_ajax.php?dd5="+protocolo+"&dd4='.$this->tabela.'&dd2=DECLINAR&dd3="+id+"&dd0="+id;
					
					var jqxhz = $.ajax( file )
						.done(function(dados) 
							{ $( tri ).html(dados); })
						.fail(function() { alert("error#declinar#"); 
					});															
				}
			</script>
			';
			return($sa.$sx);
		}
	
	function inserir_idicacao_avaliacao($protocolo,$professor,$tipo)
		{
			$data = date("Ymd");
			$sql = "select * from ".$this->tabela." where
				pp_tipo = '$tipo' and 
				pp_avaliador = '$professor' and
				pp_protocolo = '$protocolo' ";
			$rlt = db_query($sql);
			
			if ($line = db_read($rlt))
				{
					$sql = "update ".$this->tabela."
							set pp_status = '@'
							where
							pp_tipo = '$tipo' and 
							pp_avaliador = '$professor' and
							pp_protocolo = '$protocolo' ";
					$rlt = db_query($sql);					
				} else {
					$sql = "insert into ".$this->tabela." 
						(pp_nrparecer, pp_tipo, pp_protocolo,
						pp_protocolo_mae, pp_avaliador, pp_revisor,
						pp_status, pp_pontos, pp_pontos_pp,
						pp_data, pp_data_leitura, pp_hora,
						pp_parecer_data, pp_parecer_hora 
						) values (
						'','$tipo','$protocolo',
						'','$professor','',
						'@',0,0,
						$data,19000101,'',
						19000101,'');
						";
					$rlt = db_query($sql);
				}
		}

	function avaliador_idicar_form($protocolo,$area,$tipo)
		{
			global $dd,$acao;
			$area = substr($area,0,5);
			
			$sql = "select * from ajax_areadoconhecimento
					left join pareceristas_area on pa_area = a_codigo
					left join pibic_professor on pa_parecerista = pp_cracha
					left join 
						(
							select pp_avaliador as pp_ava, count(*) as total from pibic_parecer_".date("Y")."
							where pp_tipo = '$tipo' and pp_status <> 'D'
							group by pp_avaliador 
						) as tabela on pp_cracha = pp_ava
					where a_cnpq like '".$area."%' and pp_avaliador = 1 and pp_update = '".date("Y")."'
					order by  pp_nome ";

			$rlt = db_query($sql);
			$sx = '<table width="100%">';
			$xnome = "X";
			while ($line = db_read($rlt))
				{
					$nome = $line['pp_nome'];
					if ($nome != $xnome)
					{
					$ln = $line;
					$tot = $line['total'];
					if ($tot == 0) { $tot = ' - '; }
					$sx .= '<TR>';
					$sx .= '<TD class="tabela01">&nbsp;';
					$sx .= $line['pp_nome'];
					$sx .= '<TD class="tabela01" align="center" width="30">';
					$sx .= $tot;
					$sx .= '<TD class="tabela01" align="center">&nbsp;';
					$sx .= $line['a_cnpq'];
					$sx .= '<TD class="tabela01">&nbsp;';
					$sx .= $line['a_descricao'];					
					$sx .= '<TD class="tabela01" align="right" width="50">';
					$sx .= '<input type="button" class="botao-geral" 
									value="indicar" 
									onclick="indicar(\''.trim($line['pp_cracha']).'\',\''.($protocolo).'\');"
									>';
					$sx .= '<TD class="tabela01">&nbsp;';
					$sx .= $line['pp_avaliador'];
					$xnome = $nome;	
					}				
				}
			$sx .= '</table>';

			$sx .= '
				<script>
					function indicar(cracha,protocolo)
						{						
						var tri = "#TRI"+protocolo;
						/* $(tri).hide(); */	

						var file = "gestao_indicar_avaliador_ajax.php?dd0="+protocolo+"&dd1='.$dd[1].'&dd2='.$tipo.'&dd10="+cracha+"&dd90=INDICAR";
						var jqxhz = $.ajax( file )
							.done(function(dados) 
								{ $( tri ).html(dados); })
							.fail(function() { alert("error#2#"); });					
						}
				</script>
			';
			echo $sx;
		}
	function avaliador_do_rparcial()
		{
			$sql = "select * from ".$this->tabela." 
					inner join pibic_professor on ".$this->tabela.".pp_avaliador = pp_cracha
					where pp_protocolo = '".$this->protocolo."' and pp_status = 'B'";
			$rlt = db_query($sql);
			$sx = '<table width="100%">';
			while ($line = db_read($rlt))
				{
					$bg = ' bgcolor="#E0FFE0" ';
					$sx .= '<TR '.$bg.'>';
					$sx .= '<TD>Avaliador do relatório parcial:';
					$sx .= '<td>'.$line['pp_avaliador'];
					$sx .= '<TD>'.$line['pp_nome'];
					$sx .= '<TD>'.$line['pp_status'];
				}
			$sx .= '</table>';
			return($sx);
		}
	function avaliador_idicar_correcao_form($protocolo,$area,$tipo)
		{
			global $dd,$acao;
			$area = substr($area,0,5);
			
			$sql = "select * from ajax_areadoconhecimento
					left join pareceristas_area on pa_area = a_codigo
					left join pibic_professor on pa_parecerista = pp_cracha
					left join 
						(
							select pp_avaliador as pp_ava, count(*) as total from pibic_parecer_".date("Y")."
							where pp_tipo = 'RPAR' and pp_protocolo = '$protocolo' and (pp_status = 'B')
							group by pp_avaliador 
						) as tabela on pp_cracha = pp_ava
					where a_cnpq like '".$area."%' and pp_avaliador = 1 and pp_update = '".date("Y")."'
							and total > 0
					order by  pp_nome ";
			
			$rlt = db_query($sql);
			$sx = '<table width="100%">';
			$xnome = "X";
			while ($line = db_read($rlt))
				{
					$nome = $line['pp_nome'];
					if ($nome != $xnome)
					{
					$ln = $line;
					$tot = $line['total'];
					if ($tot == 0) { $tot = ' - '; }
					$sx .= '<TR>';
					$sx .= '<TD class="tabela01">&nbsp;';
					$sx .= $line['pp_nome'];
					$sx .= '<TD class="tabela01" align="center" width="30">';
					$sx .= $tot;
					$sx .= '<TD class="tabela01" align="center">&nbsp;';
					$sx .= $line['a_cnpq'];
					$sx .= '<TD class="tabela01">&nbsp;';
					$sx .= $line['a_descricao'];					
					$sx .= '<TD class="tabela01" align="right" width="50">';
					$sx .= '<input type="button" class="botao-geral" value="indicar" onclick="indicar(\''.trim($line['pp_cracha']).'\',\''.($protocolo).'\');">';
					$sx .= '<TD class="tabela01">&nbsp;';
					$sx .= $line['pp_avaliador'];
					$xnome = $nome;	
					}				
				}
			$sx .= '</table>';
			
			$sx .= '
				<script>
					function indicar(cracha,protocolo)
						{						
						var tri = "#TRI"+protocolo;
						/* $(tri).hide(); */	

						var file = "rp_indicar_avaliador_ajax.php?dd3=RPAC&dd0="+protocolo+"&dd1="+cracha+"&dd2=1";
						var jqxhz = $.ajax( file )
							.done(function(dados) 
								{ $( tri ).html(dados); })
							.fail(function() { alert("error#3#"); });					
						}
				</script>
			';
			echo $sx;
			//echo '<HR>';
			//print_r($ln);
			//echo '<HR>';
		}	

	
	function table_exists($name)
		{
			$sql = "
			SELECT count(*) as total
				FROM information_schema.tables
				WHERE table_name = '$name';
			";
			$rlt = db_query($sql);
			$line = db_read($rlt);
			$total = round($line['total']);
			if ($total == 0)
				{
					$this->tabela = $name;
					$this->structure();
				}
			return(1);
		}
	
	function resumo_avaliacoes($protocolo='')
		{
			$tabela1 = "pibic_parecer_".(date("Y")-1);
			$tabela2 = "pibic_parecer_".date("Y");
			
			$this->table_exists($tabela2);
			
			$cp = 'pp_status, pp_parecer_data, pp_parecer_hora, pp_tipo, 
					pp_data, pp_p01, pp_abe_01, pp_abe_02, pp_abe_03,
					pp_abe_04, pp_abe_05, pp_abe_06, pp_abe_07';
			$sql = "select $cp from ".$tabela1." 
						where pp_protocolo = '$protocolo'
				union
					select $cp from ".$tabela2." 
						where pp_protocolo = '$protocolo'
				limit 20 ";
			
			$rlt = db_query($sql);
			$sx .= '<table width="100%" class="lt1">';
			$sx .= '<TR><TH>St<TH>Data<TH>Tipo<TH>Indicado<TH>Avaliação<TH>Descrição';
			while ($line = db_read($rlt))
				{
					$status = trim($line['pp_status']);
					$sx .= '<TR valign="top" '.coluna().'>';
					$sx .= '<TD>';
					$sx .= $status;
					$sx .= '<TD>';
					$sx .= stodbr($line['pp_parecer_data']);
					$sx .= ' '.$line['p_parecer_hora'];
					$sx .= '<TD>';
					$sx .= trim($line['pp_tipo']);
					$sx .= '<TD>';
					$sx .= stodbr($line['pp_data']);
					$sx .= '<TD>';
					$sx .= $line['pp_p01'];
					$sx .= '<TD>';
					$sx .= '<ul>';
					$sx .= '<li>'.$line['pp_abe_01'].'</li>';
					$sx .= '<li>'.$line['pp_abe_02'].'</li>';
					$sx .= '<li>'.$line['pp_abe_03'].'</li>';
					$sx .= '<li>'.$line['pp_abe_04'].'</li>';
					$sx .= '<li>'.$line['pp_abe_05'].'</li>';
					$sx .= '<li>'.$line['pp_abe_06'].'</li>';
					$sx .= '<li>'.$line['pp_abe_07'].'</li>';
					$sx .= '</UL>';					
					$ln = $line;
				}
			$sx .= '</table>';
			$sql = "select * from ".$tabela1." 
						where pp_protocolo = '$protocolo' ";
			$rlt = db_query($sql);
			$line = db_read($rlt);
			//print_r($line);
			return($sx);
		}
	
	function cancel_avaliation()
		{
			$sql = "select * from ".$this->tabela." 
					where pp_status = '@'
			";
			$rlt = db_query($sql);
			$sqlu = "";
			$tot = 0;
			while ($line = db_read($rlt))
				{
					$tot++;
					$sqlu .= "update ".$this->tabela." set pp_status = 'D' where id_pp = ".$line['id_pp']." and pp_status = '@'; ".chr(13).chr(10);
				}
			$rlt = db_query($sql);
			$sx = '<CENTER><BR><BR><BR>';
			$sx .= $tot.' avaliações declinadas';
			if (strlen($sqlu) > 0)
				{ $rlt = db_query($sqlu); }
			$sx .= '<BR><BR><BR>';
			return($sx);
		}
	
	function calcula_edital_avaliacoes()
		{
			
			$sql = "
				select * from (
				select count(*) as total, pp_protocolo,	pp_protocolo_mae, 			
				sum(to_number('0'||pp_p01,'999')) as p01, 
				sum(to_number('0'||pp_p02,'999')) as p02, 
				sum(to_number('0'||pp_p03,'999')) as p03,
				sum(to_number('0'||pp_p05,'999')) as p04, 
				sum(to_number('0'||pp_p06,'999')) as p05, 
				sum(to_number('0'||pp_p07,'999')) as p06,
				sum(to_number('0'||pp_p04,'999')) as pe
				from ".$this->tabela." as t1
				where pp_tipo = 'SUBMP' and (pp_status ='A' or pp_status ='B')						
				group by pp_protocolo,	pp_protocolo_mae
				) as tabela
				inner join pibic_projetos on pp_protocolo_mae = pj_codigo
				inner join pibic_professor on pp_cracha = pj_professor
				where pj_status <> '!' and pj_status <> 'X'
				order by pp_protocolo_mae, pp_protocolo
				;
			";
			$rlt = db_query($sql);
			$sx .= '<h3>Gerar dados para o edital</h3>';
			$sx .=  '<table width="100%" class="lt1">';
			
			$aq = array(0,0,0);
			$ar = array(0,0,0);
			while ($line = db_read($rlt))
			{
				$bonificacao = 0;
				$penalidade = 0;
				//print_r($line);
				//if ($tot >= 10) { exit; }
				$centro = trim($line['pp_centro']);
				if ($centro == 'DOUTORANDO')
					{ $est = 1; } else {$est = 0; }
				$prod = round($line['pp_prod']);
				$titu = trim($line['pp_titulacao']);
							
				/* Caso seja doutorando ativar como mestre */
				if ($est == 1 ) { $titu = '001'; }
				$ss = $line['pp_ss'];
				$estrategica = substr(trim($line['pj_area_estra']),0,1);
				$area = '';
				$resp = substr(trim($line['pj_area']),0,1);
					if (($resp == '2') or ($resp == '4'))
						{ $area = "V"; }
					if ($resp == '5')
						{ $area = "A"; }
					if (($resp == '1') or ($resp == '3'))
						{ $area = "E"; }
					if (($resp == '7') or ($resp == '8'))
						{ $area = "H"; }
					if (($resp == '6'))		
						{ $area = "S"; }
		
				$moda = trim($line['doc_edital']);
				$id = 0;
				if ($moda == 'PIBITI') { $id = 1; }
				if ($moda == 'PIBICE') { $id = 2; }
				$aq[$id] = $aq[$id] + 1;
				if ($soma < 60) { $ar[$id] = $ar[$id] + 1; }
				$tot++;
				$ln = $line;
				
				$ava_estra = ($line['pe']-$total);
				if ($ava_estra == 0) {$ava_estra = 1; } 
					else {$ava_estra = 0; }
				
				if ($estrategica != '9') { $ava_estra = 0; }
				if ($titu == '001') { $ava_estra = 0; }	
				
				
				
				/*** Bonificacao de títulação do professor */
				if ($titu == '002') { $bonificacao = $bonificacao + 2; } /* Dr */
				if ($titu == '003') { $bonificacao = $bonificacao + 2; } /* Dra */
				if ($titu == '006') { $bonificacao = $bonificacao + 2; } /* PhD */
				
				/*** Bonificacao por Stricto Sensu */
				if ($ss == 'S') { $bonificacao = $bonificacao + 2; } /* Strico Sensu */
				
				/*** Produtividade */
				if ($prod > 0) { $bonificacao = $bonificacao + 4; } /* Produtividade */
				
				
				
				$total = $line['total'];
				$idp = $line['pp_protocolo_mae'];
				$linkp = '<A HREF="pibic_projetos_detalhes.php?dd0='.$idp.'&dd90='.checkpost($idp).'" class="link" target="_new">';
				$sx .= '<TR>';
				$sx .=  '<TD align="center">';
				$sx .=  $line['pp_protocolo'];
				$sx .=  '<TD align="center">';
				$sx .=  $linkp.$line['pp_protocolo_mae'].'</A>';

				$sx .=  '<TD align="left">';
				$sx .=  trim($line['pp_nome']);
				$sx .=  '('.$line['pj_professor'].')';				

				$sx .=  '<TD align="center">';
				$sx .=  $line['doc_edital'];		
						
				$sx .=  '<TD align="center">';
				$sx .=  $line['edital'];							

				$sx .=  '<TD align="center">'.$line['p01'];
				$sx .=  '<TD align="center">'.$line['p02'];
				$sx .=  '<TD align="center">'.$line['p03'];

				$sx .=  '<TD align="center">'.$line['p04'];
				$sx .=  '<TD align="center">'.$line['p05'];
				$sx .=  '<TD align="center">'.$line['p06'];
				
				$sx .=  '<TD align="center">'.$ava_estra;
				
				$sx .=  '<TD align="center">'.$area;
				$sx .=  '<TD align="center">'.$estrategica;
				$sx .=  '<TD align="center">'.$titu;
				$sx .=  '<TD align="center">'.$ss;
				$sx .=  '<TD align="center">['.$est.']';
				$sx .=  '<TD align="center">{'.$prod.'}';
				$sx .=  '<TD align="center">+'.round($bonificacao);
				$sx .=  '<TD align="center">-'.round($penalidade);
				
				
				$soma = round($line['p01'])+
						round($line['p02'])+
						round($line['p03'])+
						round($line['p04'])+
						round($line['p05'])+
						round($line['p06']);
				$soma = round($soma / $total);
				$font = '<font color="black">';
				if ($soma < 65) { $toti++; $font = '<font color="red">';}
				$sx .=  '<TD align="center">'.$font.$soma.'</font>';
				
				$sx .=  '<TD align="center">'.$line['sta'];
				$sx .=  '<TD align="center">'.$font.$line['total'].'</font>';
				
				$sqlx .= 'update pibic_submit_documento set doc_nota = '.($soma + round($bonificacao) - round($penalidade)) ;
				$sqlx .= ", doc_avaliacoes = ".$line['total'];
				$sqlx .= ", doc_estrategica = '".$estrategica."' ";
				$sqlx .= ", doc_area = '".$area."' ";
				$sqlx .= ", doc_ava_estrategico = '".$ava_estra."' ";
				$sqlx .= ", doc_doutorando = '".$est."' ";
				$sqlx .= ", doc_bonificacao = '".round($bonificacao)."' ";
				$sqlx .= ", doc_penalidade = '".round($penalidade)."' ";
				$sqlx .= " where doc_protocolo = '".$line['pp_protocolo']."'; ";
				$sqlx .= chr(13).chr(10);
				//echo $sqlx;
				//exit;
			}
			$sx .=  '<tr><Td colspan=5>'.$tot.', '.$toti.' não aprovados';
			$sx .=  '</table>';
			/* RESUMO */
			$sa = '<table class="lt1">';
			$sa .= '<TR><TH>planos<TH>PIBIC<TH>PIBITI<TH>PIBIC_EM';
			$sa .= '<TR align="center"><TD>'.($ar[0]+$ar[1]+$ar[2]);
			$sa .=     '<TD>'.$ar[0];
			$sa .=     '<TD>'.$ar[1];
			$sa .=     '<TD>'.$ar[2];
			$sa .= '<TR align="center"><TD>'.($aq[0]+$aq[1]+$aq[2]);
			$sa .=     '<TD>'.$aq[0];
			$sa .=     '<TD>'.$aq[1];
			$sa .=     '<TD>'.$aq[2];
			$sa .= '</table>';
			echo $sa;
			echo $sx;
			$rlt = db_query($sqlx);
			//print_r($ln);
			return(1);
		}
	
	function mostra_avaliacao($id)
		{
			global $dd;
			if (strlen($dd[1]) > 0)
				{ $this->tabela = $dd[1]; }
			$sql = "select * from ".$this->tabela; 
			$sql .= " where id_pp = ".round($id);
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					$protocolo = trim($line['pp_protocolo']);
					echo '<font class="lt3">';
					echo '<BR>Critério 1:'.$line['pp_p01'].' pts';
					echo '<BR>Critério 2:'.$line['pp_p02'].' pts';
					echo '<BR>Critério 3:'.$line['pp_p03'].' pts';
					echo '<BR>Critério 4:'.$line['pp_p04'];
					$tota = $line['pp_p01']+$line['pp_p02']+$line['pp_p03'];
					echo '<HR>';
					echo $line['pp_abe_01'];
					echo '<HR>';
					
					$sql = "select * from ".$this->tabela;
					$sql .= " where pp_protocolo_mae = '".$protocolo."' ";
					
					$xrlt = db_query($sql);
					while ($xline = db_read($xrlt))
						{
							echo '<BR>Protocolo:'.$xline['pp_protocolo'].' '.$xline['pp_avaliador'];
							echo '<BR>Critério 4:'.$xline['pp_p06'].' pts';
							echo '<BR>Critério 5:'.$xline['pp_p07'].' pts';
							echo '<BR>Critério 6:'.$xline['pp_p08'].' pts';
							$tot = $xline['pp_p06']+$xline['pp_p07']+$xline['pp_p08'];
							echo '<BR><B>Total=';
							echo ($tot+$tota).' pts';
							echo '</B>';
							echo '<BR>';
						}
				}
			//parecer_cp_modelo_pp
		//parecer_cp_modelo_pl
			
		}
	function mostra_indicacao_avaliador($avaliador)
		{
			$sta = $this->array_status();
			$sql = "select * from ".$this->tabela;
			$sql .= " where pp_avaliador = '".$avaliador."' ";
			$sql .= " and pp_protocolo like '1%' ";
			$rlt = db_query($sql);
			$tot = 0;
			while ($line = db_read($rlt))
			{
				$sx .= '<TR '.coluna().'>';
				$sx .= '<TD>';
				$sx .= trim($line['pp_protocolo']);
				$sx .= '<TD>';
				$sx .= $sta[trim($line['pp_status'])];
				$sx .= '<TD>';
				$sx .= stodbr($line['pp_data']);
				$sx .= '<TD>';
				$sx .= stodbr($line['pp_data_leitura']);
				$sx .= '<TD>';
				$sx .= stodbr($line['pp_parecer_data']);
				$sx .= '<TD>';
				$sx .= trim($line['pp_parecer_hora']);
			}
			$sh = '<TR><TH>Protocolo<TH>Status<TH>Prev.Avaliação';
			$sx .= '<table width="100%" class="lt1">'.$sh.$sx.'</table>';
			
			return($sx);
		}
	
	function resumo_avaliacao_detalhes($dd1,$dd2)
		{
			$sql = "select count(*) as total, pp_status from ".$this->tabela." 
				group by pp_status ";
			//echo $sql;
			$rlt = db_query($sql);
			echo '<table width="500" cellpadding=4 cellspacing=0 border=0>';
			echo '<TR><TH>Status<TH>Total';
			$ar = array();
			while ($line = db_read($rlt))
				{
					$total = $total + $line['total'];
					array_push($ar,$line);
				}
				
			for ($r=0;$r < count($ar);$r++)
				{
					$line = $ar[$r];
					$sta = $line['pp_status'];
					if ($sta == '@') { $sta = 'Em avaliação'; }
					if ($sta == 'A') { $sta = 'Avaliado'; }
					if ($sta == 'D') { $sta = 'Declinou'; }					
					$sx .= '<TR>';
					$sx .= '<TD>';
					$sx .= $sta;
					$sx .= '<TD align="right">';
					$sx .= $line['total'];
					$sx .= '<TD align="right">';
					$sx .= number_format($line['total']/$total*100,1).'%';
				}
			echo $sx;
			echo '</table>';
		}
	function mostra_notas($nota)
		{
			if ($nota == 0)
				{ $nota = ' - '; }
			return($nota);
		}
	function cor($x)
		{
			$cor = '#FFFFFF';
			if ($x == 0) { $cor = '#F8F8F8'; }
			if ($x == 1) { $cor = '#F0F0F0'; }
			if ($x == 2) { $cor = '#E8E8E8'; }
			if ($x == 3) { $cor = '#E0E0E0'; }
			if ($x == 4) { $cor = '#D8D8D8'; }
			if ($x == 5) { $cor = '#D0D0D0'; }
			if ($x == 6) { $cor = '#C8C8C8'; }
			if ($x == 7) { $cor = '#C0C0C0'; }
			if ($x == 8) { $cor = '#B8B8B8'; }
			if ($x == 9) { $cor = '#B0B0B0'; }
			if ($x ==10) { $cor = '#A8A8A8'; }
			if ($x ==11) { $cor = '#A0A0A0'; }
			if ($x ==12) { $cor = '#989898'; }
			if ($x ==13) { $cor = '#909090'; }
			if ($x ==14) { $cor = '#888888'; }
			if ($x ==15) { $cor = '#808080'; }
			if ($x ==16) { $cor = '#787878'; }
			if ($x ==17) { $cor = '#707070'; }
			
			return($cor);
		}
	function parecer_indicacao_row()
		{
			global $perfil;
			$sta = array('@'=>'Não avaliado','A'=>'Finalizado','X'=>'Cancelado','D'=>'Declinou');
			$sql = "select * from ".$this->tabela."
				left join pareceristas on pp_avaliador = us_codigo
				where ((pp_protocolo = '".$this->protocolo."') or (pp_protocolo_mae = '".$this->protocolo."')) ";
			//$sql .= " and (pp_status <> 'D') and (pp_status <> '@') "; 
			$sql .= " order by pp_avaliador, pp_status, pp_protocolo_mae, pp_protocolo ";
			
			$rlt = db_query($sql);
			$sx .= '<table width="100%" class="lt1">';
			$sx .= '<TR><TH>ID<TH>Protocolo<TH>Tipo<TH>Coment.';
			$sx .= '<TH>Tipo<TH>Crit. 1<TH>Crit. 2<TH>Crit. 3';
			$sx .= '<TH>Estrat.';
			$sx .= '<TH>Crit. 4 <TH>Crit. 5<TH>Crit. 6';
			$cor = 0;
			$sy = '';
			$sr = '';
			while ($line = db_read($rlt))
			{
				$bgcor = $this->cor($cor); 
				$cor++;
				$tipo = 'Projeto de pesquisa do professor';
				
				if (strlen(trim($line['pp_protocolo_mae'])) > 0) 
					{ $tipo = 'Plano do aluno';	} else 
					{
						if ($sr != trim($line['pp_abe_01']))
						{
							if (strlen($sy) > 0)
								{ $sy .= '<HR>'; }
							$sy .= trim($line['pp_abe_01']);
							$sr =  trim($line['pp_abe_01']);
						}
					}
				$link = '<A HREF="javascript:newxy2(\'parecer_declinar.php?dd0='.$line['id_pp'].'&dd1='.$this->tabela.'&dd90='.checkpost($line['id_pp']).'&dd1='.$this->tabela.'&dd2=DECLINAR\',400,200);">';
				$linkA = '<A HREF="javascript:newxy2(\'parecer_visualizar.php?dd0='.$line['id_pp'].'&dd90='.checkpost($line['id_pp']).'&dd1='.$this->tabela.'\',700,500);">';
				$sx .= '<TR bgcolor="'.$bgcor.'">';
				$sx .= '<TD align="center">';
				$sx .= $line['id_pp'];
				$sx .= '<TD align="center">';
				$sx .= $line['pp_protocolo'];
				$sx .= ' '.$line['pp_status'];
				
				$txt = trim($line['pp_abe_14']);
				if (strlen($txt) > 0)
					{
						$dialog = '<A HREF="#">';
						$dialog = '<IMG SRC="'.http.'img/icone_blog.png" height="15" title="'.$txt.'">';
						$dialog .= '</A>'; 
					} else { $dialog = ''; }
			
				$sx .= '<TD align="center">';
				$sx .= $dialog;
				if (($perfil->valid('#CPI#ADM')))
					{
						$sx .= '<TD align="center">';
						$sx .= '<A HREF="#" title="'.trim($line['us_nome']).'">';
						$sx .= $line['pp_avaliador'];
						$sx .= '</A>';
						$sx .= '&nbsp;';
						
					}
				$sx .= '<TD align="center">';
				$sx .= $line['pp_tipo'];
				$sx .= '<TD>';
				$sx .= $tipo;
				$sx .= '<TD align="center" width="6%">'.$this->mostra_notas($line['pp_p01']);
				$sx .= '<TD align="center" width="6%">'.$this->mostra_notas($line['pp_p02']);
				$sx .= '<TD align="center" width="6%">'.$this->mostra_notas($line['pp_p03']);
				$sx .= '<TD align="center" width="6%">'.$line['pp_p04'];
				$sx .= '<TD align="center" width="6%">'.$this->mostra_notas($line['pp_p05']);
				$sx .= '<TD align="center" width="6%">'.$this->mostra_notas($line['pp_p06']);
				$sx .= '<TD align="center" width="6%">'.$this->mostra_notas($line['pp_p07']);
				$sx .= '<TD align="center" width="6%">'.$this->mostra_notas($line['pp_p08']);
				if (($perfil->valid('#CPI#ADM')))
					{
						$sx .= '<TD align="center" width="6%">ED';		
					}
			}
			$sx .= '<TR><TD colspan=5>';
			$sx .= $sy;
			$sx .= '</table>';
			return($sx);
		}		
	
	function parecer_indicacao()
		{
			global $user_nivel,$user_login;
			$sta = array('@'=>'Não avaliado','A'=>'Finalizado','X'=>'Cancelado','D'=>'Declinou');
			$sql = "select * from ".$this->tabela."
				inner join pareceristas on pp_avaliador = us_codigo
				inner join instituicao on us_instituicao = inst_codigo
				where pp_protocolo = '".$this->protocolo."'  
			";
			
			$rlt = db_query($sql);
			$sx .= '<table width="100%" class="lt1">';
			$sx .= '<TR><TH>Avaliador<TH>Instituição<TH>Dt.Avaliação<TH>Status';
			while ($line = db_read($rlt))
			{
				$link = '<A HREF="javascript:newxy2(\'parecer_declinar.php?dd0='.$line['id_pp'].'&dd90='.checkpost($line['id_pp']).'&dd1='.$this->tabela.'&dd2=DECLINAR\',400,200);">';
				$linkA = '<A HREF="javascript:newxy2(\'parecer_visualizar.php?dd0='.$line['id_pp'].'&dd90='.checkpost($line['id_pp']).'&dd1='.$this->tabela.'\',700,500);">';
				if (($user_nivel == 9) and (($user_login = 'RENE') or ($user_login = 'cleybe.vieira')))
					{
					$linkC = '<A HREF="javascript:newxy2(\'parecer_declinar.php?dd0='.$line['id_pp'].'&dd90='.checkpost($line['id_pp']).'&dd1='.$this->tabela.'&dd2=DECLINAR\',400,200);">';
					}
				$sx .= '<TR>';
				$sx .= '<TD>';
				$sx .= $line['us_nome'];
				$sx .= '('.$line['us_codigo'].')';
				$sx .= '<TD>';
				$sx .= $line['inst_abreviatura'];
				$sx .= '<TD>';
				$sx .= stodbr($line['pp_data']);
				$sx .= '<TD>';
				if ($line['pp_status'] == '@')
					{ $sx .= $link; }
				if ($line['pp_status'] == 'A')
					{ $sx .= $linkC.'C</A> '.$linkA; }
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
					$this->line = $line;
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
				}
					$sx .= $line['total'];
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
				$opc .= '15:excelente<BR> ';
				$opc .= '&13:muito bom<BR> ';
				$opc .= '&10:bom<BR> ';
				$opc .= '&7:regular<BR> ';
				$opc .= '&5:ruim<BR> ';
				$opc .= '&1:muito ruim ';
				
				$cap = '<B>Critério 5</B>: Coerência entre o projeto do orientador e o plano de trabalho do aluno, considerando a contribuição para a formação do discente.';		
				array_push($cp,array('$R '.$opc,'pp_p06',$cap,True,True));

				/** Segunda questão **/
				$opc = '';
				$opc .= '15:excelente<BR> ';
				$opc .= '&13:muito bom<BR> ';
				$opc .= '&10:bom<BR> ';
				$opc .= '&7:regular<BR> ';
				$opc .= '&5:ruim<BR> ';
				$opc .= '&1:muito ruim ';
				
				$cap = '<B>Critério 6</B>: Roteiro de atividades do aluno considerando a sua adequação ao processo de iniciação científica.';		
				array_push($cp,array('$R '.$opc,'pp_p07',$cap,True,True));

				/** Terceira questão **/
				$opc = '';
				$opc .= '10:adequado<BR> ';
				$opc .= '&5:parcialmente adequado<BR> ';
				$opc .= '&1:inadequado ';
				
				$cap = '<B>Critério 7</B>: Adequação do cronograma para a execução da proposta.';		
				array_push($cp,array('$R '.$opc,'pp_p08',$cap,True,True));

				/** Quarta questão **/
				$opc = '';
				$opc .= '1:SIM. Recomendo que o projeto seja direcionado para o Programa PIBITI.<BR> ';
				$opc .= '&0:NÃO<BR> ';
				$opc .= '&2:Tenho dúvida, peço que o comitê gestor analise.';
				
				$cap = '<B>Critério 8</B>: Critério de Inovação Tecnológica: O projeto se enquadra dentro da área de inovação e tecnologia? ';		
				array_push($cp,array('$R '.$opc,'pp_p12',$cap,True,True));
				
				/** Quinta questão */
				
				return($cp);								
		}	
	
	function parecer_cp_modelo_pp($mod='',$area='',$estrategica='')
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
						$opc .= '20:excelente<BR> ';
						$opc .= '&17:muito bom<BR> ';
						$opc .= '&15:bom<BR> ';
						$opc .= '&12:regular<BR> ';
						$opc .= '&7:ruim<BR> ';
						$opc .= '&1:muito ruim ';
						
						$opc_sn .= '1:Sim<BR>&2:Não';
						$cap = 'Critério 1</B>: Relevância do projeto do orientador e contribuição para a formação do aluno.';		
						array_push($cp,array('$R '.$opc,'pp_p02',$cap,True,True));
					
						/** Segunda questão **/
						$cap = 'Critério 2</B>: Coerência do projeto do orientador de acordo com os itens: Introdução, Objetivo, Método e Referências Bibliográficas.';
						array_push($cp,array('$R '.$opc,'pp_p03',$cap,True,True));
		
						/** Terceira questão **/
						$cap = 'Critério 3</B>: Coerência e adequação entre a capacitação e a experiência do professor orientador proponente e a realização do projeto, considerando as informações curriculares apresentadas.';
						array_push($cp,array('$R '.$opc,'pp_p04',$cap,True,True));
						
						/** Quarta questão **/
						$opc = '';
						$opc .= '1:SIM<BR> ';
						$opc .= '&0:NÃO<BR> ';
						$opc .= '&2:Tenho dúvida, peço que o comitê de Ética analise a obrigatoriedade';
					
						$cap = '<B>Critério 4</B>: Este projeto envolve seres humanos ou animais e, portanto, deve ser analisado pelo Comitê de Ética (CEP) ou Comitê de Ética no Uso de Animais (CEUA), respectivamente ?';		
						array_push($cp,array('$R '.$opc,'pp_p09',$cap,True,True));
						
		
						if (substr($estrategica,0,4) != '9.00')
							{
								$sql = "select * from ajax_areadoconhecimento where a_cnpq = '".trim($estrategica)."' ";
								$xrlt = db_query($sql);
								if ($xline = db_read($xrlt))
									{
										$area_nome = trim($xline['a_descricao']);
									}
							/** Área Estratégica **/
								$cap = 'Área Estratégica</B>: Este projeto foi assinalado pelo professor proponente como tendo aderência a área estratégica (<b>'.$this->mostra_area($estrategica).'</B>) da PUCPR. O projeto se enquadra na área assinalada?';
								array_push($cp,array('$R '.$opc_sn,'pp_p05',$cap,True,True));
							} else {
								array_push($cp,array('$HV','pp_p05','0',True,True));								
							}
						
						/** Planos de Aluno **/

				/** fecha Field **/
				return($cp);			
			}

	/* Mostra área */
		function mostra_area($area='')
			{
				$sql = "select * from ajax_areadoconhecimento where a_cnpq = '".$area."' ";
				$rlt = db_query($sql);
				if ($line = db_read($rlt))
					{
						return(trim($line['a_descricao']));
					}
				return('(não identificada) '.$area);
			}


	/*
	 * Verifica os avaliadores deste relatorio
	 */
	function avaliador_do_relatorio($tipo)
		{
			global $dd,$acao;
			$sql = "select ".$this->tabela.".id_pp as idp, * from ".$this->tabela." 
				inner join pibic_professor on ".$this->tabela.".pp_avaliador = pp_cracha
				where pp_protocolo ='".$this->protocolo."'
				; ";
			$this->$total_indicacoes = 0;
			$rlt = db_query($sql);
			$sx .= '<table class="tabela01" width="100%" bgcolor="#F0FFFF">';
			$sx .= '<TR><TH>Avaliador<TH>Relatório<TH>Status';
			while ($line = db_read($rlt))
			{
				$rtipo = trim($line['pp_tipo']);
				$sx .= '<TR bgcolor="#F0FFF0">';
				$sx .= '<TD>'.$line['pp_nome'];
				$sx .= '<TD>'.$line['pp_tipo'];
				$sta = $line['pp_status'];
				
				
				$linkd = '<span id="aa'.$line['id_pp'].'" class="botao-geral" onclick="declinar(\''.$line['idp'].'\',\''.$this->protocolo.'\');">Declinar</span>';
				switch ($sta)
					{
					//case '@': $sx .= '<TD align="center">Indicado'; break;
					case '@': 
						$sx .= '<TD align="center">'.$linkd; 
						if ($tipo == $rtipo) { $this->total_indicacoes = $this->total_indicacoes + 1; }
						break;
					case 'B': 
						$sx .= '<TD align="center">Avaliou'; 
						if ($tipo == $rtipo) { $this->total_indicacoes = $this->total_indicacoes + 1; }
						break;
					case 'D': 
						$sx .= '<TD align="center">Declinou'; 
						break;
					}				
			}
			$sx .= '</table>';
			$sx .= '
			<script>
			function declinar(id,protocolo)
				{
					var trs = "#TRI"+protocolo;
					
					var file = "gestao_indicar_avaliador_ajax.php?dd0="+protocolo+"&dd2='.$rtipo.'&dd4='.$this->tabela.'&dd90=DECLINAR&dd3="+id;
					var jqxhz = $.ajax( file )
						.done(function(dados) 
							{ $( trs ).html(dados); })
						.fail(function() { alert("error#5#"); 
					});															
				}
			</script>
			';
			return($sx);
		}

	function resumo_avaliador($parecerista,$tipo='RPAR')
		{
			if ($tipo == 'SUBMI')
				{
					$sql = "select * from ".$this->tabela." ";
					$sql .= "left join pibic_projetos on pp_protocolo = pj_codigo ";
					$sql .= " where pp_avaliador = '".$parecerista."' ";
					$sql .= " and pp_status = '@'
							  and pp_tipo = '$tipo' ";
				} else {
					$sql = "select * from ".$this->tabela." ";
					$sql .= "left join pibic_bolsa_contempladas on pp_protocolo = pb_protocolo ";
					$sql .= " where pp_avaliador = '".$parecerista."' ";
					$sql .= " and pp_status = '@' and pp_tipo = '$tipo' and pb_status <> 'C' ";
					$sql .= " order by id_pp desc, pp_protocolo ";					
				}									
//			echo $sql;
//			echo '<HR>';
			$rlt = db_query($sql);
			$sx .= '<div><table width="97%" align="center" class="lt1" border=0 >'.chr(13);
			
			while ($line = db_read($rlt))
				{
					$tot++; 
					$sx .= $this->mostra_mini($line);			
				}
			$sx .= '</table></div>'.chr(13);
			return(array($tot,$sx));			
		}
	function resumo_avaliador_pendencia($parecerista)
		{
			$sql = "select * from ".$this->tabela." ";
			$sql .= "left join pibic_projetos on pj_codigo = pp_protocolo  ";
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
			$titulo_plano = trim($line['pj_titulo']);
			if (strlen($titulo_plano)==0)
				{ $titulo_plano = trim($line['pb_titulo_projeto']); }
				
			$orientador = $line['pp_nome'];
			$estudante = $line['pa_nome'];
			$ano = $line['pb_ano'];
			$status = $line['pp_status'];
			$id = $line['id_pp'];
			$tipo = trim($line['pp_tipo']);
			$chk = checkpost($id);
			$link = '<A HREF="avaliacao_pibic_'.$tipo.'.php?dd0='.$id.'&dd90='.$chk.'" >';
			
			if ($tipo=='A')
				{
				$link = '<A HREF="avaliacao_pibic_submit.php?dd0='.$id.'&dd90='.$chk.'" >';
				}
				
			$cor="red";
			switch ($tipo)
				{
				case 'RPAC': $ntipo = 'Correção Relatório Parcial'; break;
				case 'SUBMI': $ntipo = 'Projeto IC/IT'; break;
				}
			$sx .= '<TR><TD>';
			$sx .= "<TR valign=top ><TD rowspan=2 width=90>
					<img src=../editora/img_edicao/$img   height=80>
					<TD>Protocolo: <B>".$link.$protocolo."</A></B> - (".$ntipo.")</TD>
					
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
	function cp()
		{
			$cp = array();
			array_push($cp,array('$H8','id_pp',"",false,True));
			array_push($cp,array('$S8',"pp_protocolo","Protocolo",True,True));
			array_push($cp,array('$S8',"pp_avaliador","Avaliador",True,True));
			array_push($cp,array('$S1',"pp_status","Status",True,True));
			array_push($cp,array('$T80:6',"pp_abe_01","Qualitativa",True,True));
			array_push($cp,array('$S5',"pp_tipo","Tipo",True,True));
			return($cp);
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
				pp_abe_10 text,
				pp_abe_11 text,
				pp_abe_12 text,
				pp_abe_13 text,
				pp_abe_14 text,
				pp_abe_15 text,
				pp_abe_16 text,
				pp_abe_17 text,
				pp_abe_18 text,
				pp_abe_19 text
				); ";
			$rlt = db_query($sql);
		}	
	}
}
?>
