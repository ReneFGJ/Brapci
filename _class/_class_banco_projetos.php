<?php
class projetos
	{
		var $id;
		var $protocolo;
		var $title;
		
		var $tabela = "banco_projetos";
		
		function updatex()
			{
				global $base;
				$c = 'doc';
				$c1 = 'id_'.$c;
				$c2 = $c.'_codigo';
				$c3 = 7;
				$sql = "update ".$this->tabela." set $c2 = lpad($c1,$c3,0) where $c2='' ";
				if ($base=='pgsql') { $sql = "update ".$this->tabela." set $c2 = trim(to_char(id_".$c.",'".strzero(0,$c3)."')) where $c2='' "; }
				$rlt = db_query($sql);
			}
		
		function projeto_tipo($tipo='')
			{
						switch ($tipo)
							{
							case 'P': $tipo = 'Pesquisa'; break;
							case 'E': $tipo = 'Extensão'; break;
							case 'D': $tipo = 'Desenvolvimento'; break;
							case 'O': $tipo = 'Outra'; break;
							}
					return($tipo);				
			}
		function projeto_ano($a1,$a2)
			{
				if (($a1 > 0) and ($a2 > 0)) { $sx = $a1.'-'.$a2; }
				if (($a1 > 0) and ($a2 < 1900)) { $sx = $a1.'-Atual'; }
				return($sx);
			}
		function lista_pesquisadores_excel($anoi = 0, $anof = 0)
			{
				$anoi = 2013;
				$anof = 2013;
				$wh = "and ( 
						((doc_ano_inicio <= ".$anoi.") and doc_ano_encerramento = 0) 
						or (doc_ano_encerramento >= $anof)
						)
						";
				//$whs = " pp_ss = 'S' and pp_update <> '2014' ";
				$whs = "(1 = 1)";
				$sql = "select pp_nome from ".$this->tabela." 
							left join pibic_professor on doc_professor = pp_cracha
							where pp_ativo = '1' and
							$whs 
							$wh
							group by pp_nome
							order by pp_nome
						";
				$rlt = db_query($sql);
				$sx .= '<table width="100%" class="tabela01">';
				$sx .= '<TR><TH>Pesquisador';
				$sx .= 
				$xnome = '';
				$id = 0;
				$tot1 = 0;
				$tot2 = 0;
				$tot3 = 0;
				$tot4 = 0;
				while ($line = db_read($rlt))
					{
						$id++;
						$nome = trim($line['pp_nome']);
						$sx .= '<TR>
								<TD class="tabela01">'.$nome;	
					}
				$sx .= '<TR><TD colspan=10>Total de '.$id.' pesquisadores';
				$sx .= '</table>';
				return($sx);
			}			
		function lista_projetos_excel($anoi = 0, $anof = 0)
			{
				$anoi = 2013;
				$anof = 2013;
				$wh = "and ( 
						((doc_ano_inicio <= ".$anoi.") and doc_ano_encerramento = 0) 
						or (doc_ano_encerramento >= $anof)
						)
						";
				//$whs = " pp_ss = 'S' and pp_update <> '2014' ";
				$whs = "(1 = 1)";
				$sql = "select * from ".$this->tabela." 
							left join pibic_professor on doc_professor = pp_cracha
							where pp_ativo = '1' and
							$whs 
							$wh
							order by pp_nome, doc_1_titulo
						";
				$rlt = db_query($sql);
				$sx .= '<table width="100%" class="tabela01">';
				$sx .= '<TR><TH>Pesquisador
							<TH>e-mail
							<TH>Título do projeto
							<TH>Tipo
							<TH>Início
							<TH>Conclusão
							<TH>Status';
				$sx .= 
				$xnome = '';
				$id = 0;
				$tot1 = 0;
				$tot2 = 0;
				$tot3 = 0;
				$tot4 = 0;
				while ($line = db_read($rlt))
					{
						$tipo = $line['doc_tipo'];
						if ($tipo == 'P') { $tot1++; }
						if ($tipo == 'E') { $tot2++; }
						if ($tipo == 'O') { $tot3++; }
						
						$id++;
						$nome = trim($line['pp_nome']);
						$tipo = $this->projeto_tipo(trim($line['doc_tipo']));
						$sx .= '<TR>
								<TD class="tabela01">'.$nome;
						$sx .= '<TD>'.$line['pp_email'];
								
						$sx .= '<TD class="tabela01">';
						$sx .= trim($line['doc_1_titulo']);
						
						$sx .= '<TD class="tabela01">';
						$sx .= $tipo
						;
						$anoi = round($line['doc_ano_inicio']);
						$anof = round($line['doc_ano_encerramento']);
						
						$sx .= '<TD class="tabela01">';
						$sx .= $anoi;
						
						$sx .= '<TD class="tabela01">';
						if ($anof == 0) { $anof = ''; }
						$sx .= $anof;
						$sx .= '<TD align="center" class="tabela01">';
						
						$status = $line['doc_situacao'];
						if ($status == 'A') { $toa1++; }
						if ($status == 'C') { $toa2++; }
						if ($status == 'D') { $toa3++; }
						switch ($status)
							{
								case 'A': $sx .= '<font color="green">Ativo</font>'; break;
								case 'F': $sx .= '<font color="orange">Encerrado</font>'; break;
								case 'C': $sx .= '<font color="orange">Concluido</font>'; break;
								case 'D': $sx .= '<font color="red">Desativado</font>'; break;
							}
						
					}
				$sx .= '<TR><TD colspan=10>Total de '.$id.' projetos';
				$sx .= '<TABLE with="400">
						<TR><TD>Pesquisa<TD>'.$tot1.'<TD>Ativos<TD>'.$toa1.'</tr>
						<TR><TD>Extensão<TD>'.$tot2.'<TD>Concluídos<TD>'.$toa2.'</tr>
						<TR><TD>Outros<TD>'.$tot3.'<TD>Cancelados<TD>'.$toa3.'</tr>
						</table>';	
				$sx .= '</table>';
				return($sx);
			}			
		function lista_projetos($anoi = 0, $anof = 0)
			{
				$anoi = 2013;
				$anof = 2013;
				$wh = "and ( 
						((doc_ano_inicio <= ".$anoi.") and doc_ano_encerramento = 0) 
						or (doc_ano_encerramento >= $anof)
						)
						";
				//$whs = " pp_ss = 'S' and pp_update <> '2014' ";
				$whs = "(1 = 1)";
				$sql = "select * from ".$this->tabela." 
							left join pibic_professor on doc_professor = pp_cracha
							where pp_ativo = '1' and
							$whs 
							$wh
							order by pp_nome, doc_1_titulo
						";
				$rlt = db_query($sql);
				$sx .= '<table width="100%" class="tabela01">';
				$xnome = '';
				$id = 0;
				$tot1 = 0;
				$tot2 = 0;
				$tot3 = 0;
				$tot4 = 0;
				while ($line = db_read($rlt))
					{
						$tipo = $line['doc_tipo'];
						if ($tipo == 'P') { $tot1++; }
						if ($tipo == 'E') { $tot2++; }
						if ($tipo == 'O') { $tot3++; }
						
						$id++;
						$nome = trim($line['pp_nome']);
						$tipo = $this->projeto_tipo(trim($line['doc_tipo']));
						if ($xnome != $nome)
							{
								$sx .= '<TR><TD class="lt3"><B>'.$nome.'</B>';
								$xnome = $nome;
							}
						$sx .= '<TR>';
						$sx .= '<TD class="tabela01">';
						$sx .= trim($line['doc_1_titulo']);
						$sx .= '<BR>';
						$sx .= '<font class="lt0">Tipo: <B>'.$tipo.'</B></font> ';
						$anoi = round($line['doc_ano_inicio']);
						$anof = round($line['doc_ano_encerramento']);
						
						$sx .= $this->projeto_ano($anoi,$anof);
						$sx .= '<TD align="center" class="tabela01">';
						
						$status = $line['doc_situacao'];
						if ($status == 'A') { $toa1++; }
						if ($status == 'C') { $toa2++; }
						if ($status == 'D') { $toa3++; }
						switch ($status)
							{
								case 'A': $sx .= '<font color="green">Ativo</font>'; break;
								case 'F': $sx .= '<font color="orange">Encerrado</font>'; break;
								case 'C': $sx .= '<font color="orange">Concluido</font>'; break;
								case 'D': $sx .= '<font color="red">Desativado</font>'; break;
							}
						
					}
				$sx .= '<TR><TD colspan=10>Total de '.$id.' projetos';
				$sx .= '<TABLE with="400">
						<TR><TD>Pesquisa<TD>'.$tot1.'<TD>Ativos<TD>'.$toa1.'</tr>
						<TR><TD>Extensão<TD>'.$tot2.'<TD>Concluídos<TD>'.$toa2.'</tr>
						<TR><TD>Outros<TD>'.$tot3.'<TD>Cancelados<TD>'.$toa3.'</tr>
						</table>';	
				$sx .= '</table>';
				return($sx);
			}
		function projeto_inserir($professor,$titulo,$tipo,$status,$anoi,$anof,$fina,$inst)
			{
				$data = date("Ymd");
				$anoi = round(substr($anoi,0,4));
				$anof = round(substr($anof,0,4));
				
				if ($status == 'Em Andamento') { $status = 'A'; }
				if ($status == 'Finalizado') { $status = 'F'; }
				if ($status == 'Concluido') { $status = 'C'; }
				if ($status == 'Desativado') { $status = 'D'; }
				
				if ($tipo == 'Pesquisa') { $tipo = 'P'; }
				if ($tipo == 'Extensão') { $tipo = 'E'; }
				if ($tipo == 'Desenvolvimento') { $tipo = 'D'; }
				if ($tipo == 'Outra') { $tipo = 'O'; }
				
				$sql = "select * from ".$this->tabela." where doc_professor = '$professor' ";
				echo '<HR>'.$sql.'<HR>';
				
				$rlt = db_query($sql);
				$ok = 0;
				$t1 = uppercasesql(trim($titulo));
				while ($line = db_read($rlt))
					{
						$t2 = uppercasesql(trim($line['doc_1_titulo']));
						if ($t1==$t2) { $ok = $line['id_doc']; }
						echo '<BR>'.$t1;
						echo '<BR><B>'.$t2.'</B>';
						echo '<HR>';
					}
				if ($ok > 0)
					{  		
						echo '<BR>Já cadastrado';
						
					} else {
						$sql = "insert into ".$this->tabela." 
								(
									doc_codigo, doc_professor, doc_ano, 
									doc_tipo, doc_situacao, doc_1_titulo,
									doc_2_titulo, doc_banco_projetos,
									
									doc_objetivo, doc_resumo, doc_keyowrks,
									doc_1_idioma, doc_2_idioma, doc_protocolo,
									doc_protocolo_mae, doc_id, doc_data,
									
									doc_hora, doc_aluno, doc_dt_atualizado,									
									doc_ano_inicio, doc_ano_encerramento, doc_status,
									doc_autor_principal, doc_grupo, doc_linha,
									
									doc_cep, doc_cep_data, doc_ceua,
									doc_ceua_data, doc_aprovado_externamente, doc_area,
									doc_area_estrategica  
									 
								) values (
									'', '$professor','$anoi',
									'$tipo','$status','$titulo',
									'',1,
									
									'','','',
									'pt_BR','','',
									'','',$data,
									
									'','',$data,
									$anoi,$anof,1,
									'$professor','','',
									
									'',0,'',
									0,'0','',
									''
								)
						";
						$rlt = db_query($sql);
						$this->updatex();
					}
			}
		
		function le()
			{
				
			}
		function form_autor()
			{
				global $dd;
				$ed=1;
				$titulo = $this->title;
				$sx = '';
				$sx .= '<style>'.chr(13);
				$sx .= ' #pp '.chr(13);
				$sx .= ' { '.chr(13);
				$sx .= '  background-color: #F0FFFF; border: 1px solid Black; '.chr(13);
				$sx .= ' } '.chr(13);
				$sx .= '</style>'.chr(13);
				
				$sx .= '<div id="pp">'.chr(13);
				$sx .= '<form method="post" action="'.page().'">';
				$sx .= '<table width="100%" cellspancing=0 cellpadding=1 border=5>'.chr(13);
				$sx .= '<TR class="lt0"><TD colspan=4>'.msg('project_title');
				if ($ed==1)
				{
					$sx .= '<TR class="lt2">';
					$sx .= sget('dd11','$T80:4',True,True);
				} else {
					$sx .= '<TR class="lt2"><TD colspan=4><B>'.$this->title;
				}

				$sx .= '<TR class="lt0"><TD colspan=4>'.msg('project_protocolo');

				$sx .= '<TR class="lt0"><TD colspan=4>'.msg('project_atualizado');

				$sx .= '<TR class="lt0"><TD colspan=4>'.msg('project_area');

				$sx .= '<TR class="lt0"><TD colspan=1>'.msg('project_grupo');
				$sx .= '<TD colspan=1>'.msg('project_linha');
				$sx .= '<TR class="lt0"><TD colspan=1><input type="submit" value="'.msg("project_submit").'">';
				$sx .= '</table>';
				$sx .= '</div>'.chr(13);
				$sx .= '</form>';
				$sx .= '<script>'.chr(13);
				$sx .= ' $("#pp").corner(); '.chr(13);
				$sx .= '</script>'.chr(13);
				return($sx);
			}
		function strucuture()
			{
				$sql = "CREATE TABLE banco_projetos 
					( 
					id_doc serial NOT NULL, 
					doc_codigo char(8), 
					doc_ano char(4),
					doc_professor char(8),
					doc_tipo char(1),
					doc_situacao char(1),
					
					doc_1_titulo text, 
					doc_2_titulo text, 
					doc_banco_projetos int2,
 
					doc_objetivo text,
					doc_resumo text,
					doc_keyowrks char (255), 
 
 					doc_1_idioma char(5) DEFAULT '', 
					doc_2_idioma char(5),
					 
					doc_protocolo char(7), 
					doc_protocolo_mae char(7),
					doc_id char(20),
					 
					doc_aluno char(8), 
					doc_data int4 DEFAULT 0, 
					doc_hora char(5), 

					doc_dt_atualizado int4 DEFAULT 19000101, 
					doc_ano_inicio int4, 
					doc_ano_encerramento int4,
					doc_status char(1),
					
					doc_autor_principal char(8),
					doc_grupo char(7), 
					doc_linha char(7),
		 
					doc_cep char(15),
					doc_cep_data int8,
					doc_ceua char(15),
					doc_ceua_data int8, 
					
					doc_aprovado_externamente char(1), 
					doc_area char(10), 
 
					doc_area_estrategica char(10)
					)";
				$rlt = db_query($sql); 
			}
	}
