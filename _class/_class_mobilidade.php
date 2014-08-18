<?php
class mobilidade
	{
		var $tabela = "mobilidade";
		var $tabela_tipo = "mobilidade_tipo";
		
		function mostra_status($st)
			{
				if ($st == 'A') { $st = 'Ativo'; }
				if ($st == 'X') { $st = 'Cancelado'; }
				if ($st == 'B') { $st = 'Finalizado'; }
				return($st);
			}
			
		function lista_visitante($tipo='',$a1=1990,$a2=2999,$ed=1)
			{
				//$this->strucuture();
				if (strlen($tipo) > 0)
					{ $wh .= "where mt_publico = '$tipo' ";}
				
				$sql = "select * from ".$this->tabela."
						inner join ".$this->tabela_tipo." on mt_codigo = mb_tipo 
						left join pibic_aluno on pa_cracha = mb_discente
						left join ajax_pais on pais_codigo = mb_pais
						left join pibic_professor on pp_cracha = mb_docente and mb_docente <> ''
						left join programa_pos on pos_codigo = mb_programa 
						$wh
						order by pos_nome
				";
				$rlt = db_query($sql);
				$sx = '<table width="100%" class="tabela00">';
				$sx .= '<TR><TH width="30%">Visitante
							<TH width="20%">Mobilidade
							<TH>Origem
							<TH width="5%">D.Inicial
							<TH width="5%">D.Final
							<TH>Dias
							<TH>Status';
				$id = 0;
				$npos = 'X';
				while ($line = db_read($rlt))
				{
					$di = round(substr($line['mb_data_inicio'],0,4));
					if (($di >= $a1) and ($di <= $a2))
					{					
						$pos = trim($line['pos_nome']);
						if (($pos != $npos) and (strlen($pos) > 0))
							{
								$sx .= '<TR><TD class="lt3" colspan="10"><B>';
								$sx .= $pos;
								$sx .= '</B>';
								$npos = $pos;
							}
						$id++;
						$sx .= '<TR>';
						$sx .= '<TD class="tabela01">';
						$sx .= trim($line['mb_nome']);
						$sx .= '&nbsp;<TD class="tabela01">';
						$sx .= trim($line['mt_descricao']);
						$sx .= '<TD class="tabela01">';
						$pais = trim($line['pais_nome']);
						if ((strlen($pais) > 0) and ($pais != 'Brasil'))
							{
								$sx .= $pais;
							} else {
								$sx .= trim($line['mb_local']);		
							}
						
						
						$sx .= '<TD class="tabela01" align="center">';
						$sx .= stodbr($line['mb_data_inicio']);
						$sx .= '<TD class="tabela01" align="center">';
						$sx .= stodbr($line['mb_data_fim']);
						$sx .= '&nbsp;<TD class="tabela01">';
						$sx .= trim($line['mb_dias']);
											
						$sx .= '<TD class="tabela01">';
						$sx .= $this->mostra_status($line['mb_status']);
						if ($ed == 1)
							{
								$sx .= '<TD class="tabela00" align="center">';
								if ($tipo == 'V')
									{
										$link = '<A HREF="mobilidade_visitante_ed.php?dd0='.$line['id_mb'].'&dd90='.checkpost($line['id_mb']).'">';
									}
	
								$sx .= $link.'editar</A>';					
								$ln = $line;
							}
					}
				}
				if ($id > 0)
				{
					$sx .= '<TR><TD colspan=5> '.mst('total').$id;
				} else { $sx .= '<TR><TD colspan=5> '.msg('without_records'); }
				$sx .= '</table>';
				return($sx);
			}
		
		function lista_mobilidade($tipo='',$a1=1990,$a2=2999,$ed=1)
			{
				//$this->strucuture();
				$sx .= '<H3>'.$a1.' - '.$a2.'</h3>';
				if (strlen($tipo) > 0)
					{ $wh .= "where mt_publico = '$tipo' and mb_status <> 'X' ";}
					else 
					{ $wh .= "where mb_status <> 'X' "; }
				
				$sql = "select * from ".$this->tabela."
						inner join ".$this->tabela_tipo." on mt_codigo = mb_tipo 
						left join pibic_aluno on pa_cracha = mb_discente
						left join pibic_professor on pp_cracha = mb_docente and mb_docente <> ''
						left join programa_pos on pos_codigo = mb_programa 
						$wh
						order by pos_nome
				";
				$rlt = db_query($sql);
				$sx = '<table width="100%" class="tabela00">';
				$sx .= '<TR><TH width="30%">Docente<TH width="30%">Discente
							<TH width="20%">Mobilidade<TH width="5%">D.Inicial
							<TH width="5%">D.Final<TH>Status';
				$id = 0;
				$npos = 'X';
				while ($line = db_read($rlt))
				{
					$di = round(substr($line['mb_data_inicio'],0,4));
					if (($di >= $a1) and ($di <= $a2))
					{
					$pos = trim($line['pos_nome']);
					if (($pos != $npos) and (strlen($pos) > 0))
						{
							$sx .= '<TR><TD class="lt3" colspan="10"><B>';
							$sx .= $pos;
							$sx .= '</B>';
							$npos = $pos;
						}
					$id++;
					$sx .= '<TR>';
					$sx .= '<TD class="tabela01">';
					$sx .= trim($line['pp_nome']);
					$sx .= '&nbsp;<TD class="tabela01">';
					$sx .= trim($line['pa_nome']);
					$sx .= '&nbsp;<TD class="tabela01">';
					$sx .= trim($line['mt_descricao']);

					$sx .= '<TD class="tabela01" align="center">';
					$sx .= stodbr($line['mb_data_inicio']);
					$sx .= '<TD class="tabela01" align="center">';
					$sx .= stodbr($line['mb_data_fim']);
					
					$sx .= '<TD class="tabela01">';
					$sx .= $this->mostra_status($line['mb_status']);
					if ($ed == 1)
						{
						$sx .= '<TD class="tabela00" align="center">';
						if ($tipo == 'I')
							{
							$link = '<A HREF="mobilidade_discente_ed.php?dd0='.$line['id_mb'].'&dd90='.checkpost($line['id_mb']).'">';
							}
						if ($tipo == 'D')
							{
							$link = '<A HREF="mobilidade_docente_ed.php?dd0='.$line['id_mb'].'&dd90='.checkpost($line['id_mb']).'">';
							}
						$sx .= $link.'editar</A>';					
						$ln = $line;
						}
					}
				}
				if ($id > 0)
					{
						$sx .= '<TR><TD colspan=5>'.mst('total').$id;
					} else { $sx .= '<TR><TD colspan=5>'.msg('without_records'); }
				$sx .= '</table>';
				return($sx);
			}
			
		function cp_docente()
			{
				//$sql = "alter table ".$this->tabela." add column mb_estado char(2)";
				//$rlt = db_query($sql);
				
				$cp = array();
				array_push($cp,array('$H8','id_mb','',False,True));
				array_push($cp,array('$Q pp_nome:pp_cracha:select * from pibic_professor where pp_ativo = 1 and pp_ss = \'S\' order by pp_nome','mb_docente','Docente',True,True));
				array_push($cp,array('$H8','mb_discente','',False,True));
				array_push($cp,array('$Q pos_nome:pos_codigo:select * from programa_pos where pos_ativo=1 order by pos_nome ','mb_programa','Programa',True,True));
				array_push($cp,array('$H8','mb_escola','',False,True));
				array_push($cp,array('$Q mt_descricao:mt_codigo:select * from mobilidade_tipo where mt_publico=\'D\'','mb_tipo','Modalidade',True,True));
				array_push($cp,array('$CITY','mb_pais','Pais',True,True));
				array_push($cp,array('$UF','mb_estado','Estado (se no Brasil)',True,True));
				array_push($cp,array('$S60','mb_local','Universidade/Local',True,True));				
				array_push($cp,array('$U8','mb_update','',False,True));
				array_push($cp,array('$D8','mb_data_inicio','Data Inicial',False,True));
				array_push($cp,array('$D8','mb_data_fim','Data final',False,True));
				array_push($cp,array('$T60:3','mb_observacao','Obs',False,True));
				array_push($cp,array('$O : &A:Ativo&B:Concluído&X:Cancelado','mb_status','Status',True,True));
				return($cp);
			}

		function cp_visitante()
			{
				$cp = array();
				array_push($cp,array('$H8','id_mb','',False,True));
				array_push($cp,array('${','','Dados do visitante',False,True));
				array_push($cp,array('$S60','mb_nome','Nome do visitante',True,True));
				array_push($cp,array('$S20','mb_cpf','CPF/Passport',False,True));
				array_push($cp,array('$D8','mb_nasc','Data nascimento',False,True));
				array_push($cp,array('$S12','mb_cracha','Cracha',False,True));
				array_push($cp,array('$}','','Dados do visitante',False,True));
				array_push($cp,array('$S12','mb_cr_curso','CR Curso',False,True));
				array_push($cp,array('$H8','mb_docente','Docente',False,True));
				array_push($cp,array('$H8','mb_discente','',False,True));
				array_push($cp,array('$Q pos_nome:pos_codigo:select * from programa_pos where pos_ativo=1 order by pos_nome ','mb_programa','Programa',True,True));
				array_push($cp,array('$H8','mb_escola','',False,True));
				array_push($cp,array('$Q mt_descricao:mt_codigo:select * from mobilidade_tipo where mt_publico=\'V\'','mb_tipo','Modalidade',True,True));
				array_push($cp,array('$CITY','mb_pais','Pais',True,True));
				array_push($cp,array('$UF','mb_estado','Estado (se no Brasil)',True,True));
				array_push($cp,array('$S60','mb_local','Universidade/Local',True,True));				
				array_push($cp,array('$U8','mb_update','',False,True));
				array_push($cp,array('$D8','mb_data_inicio','Data Inicial',False,True));
				array_push($cp,array('$D8','mb_data_fim','Data final',False,True));
				array_push($cp,array('$I8','mb_dias','Dias',True,True));
				array_push($cp,array('$T60:3','mb_observacao','Obs',False,True));
				
				array_push($cp,array('$O : &A:Ativo&B:Concluído&X:Cancelado','mb_status','Status',True,True));
				return($cp);		
			}
		function cp_estrangeiro()
			{
				$cp = array();
				array_push($cp,array('$H8','id_mb','',False,True));
				array_push($cp,array('${','','Dados do estudante estrangeiro',False,True));
				array_push($cp,array('$S60','mb_nome','Nome completo',True,True));
				array_push($cp,array('$S20','mb_cpf','CPF/Passport',False,True));
				array_push($cp,array('$D8','mb_nasc','Data nascimento',False,True));
				array_push($cp,array('$S12','mb_cracha','Cracha',False,True));
				array_push($cp,array('$}','','Dados do visitante',False,True));
				//array_push($cp,array('$S12','mb_cr_curso','CR Curso',False,True));
				array_push($cp,array('$H8','mb_docente','Docente',False,True));
				array_push($cp,array('$H8','mb_discente','',False,True));
				array_push($cp,array('$Q pos_nome:pos_codigo:select * from programa_pos where pos_ativo=1 order by pos_nome ','mb_programa','Programa',True,True));
				array_push($cp,array('$H8','mb_escola','',False,True));
				array_push($cp,array('$Q mt_descricao:mt_codigo:select * from mobilidade_tipo where mt_publico=\'E\'','mb_tipo','Modalidade',True,True));
				array_push($cp,array('$CITY','mb_pais','Pais',True,True));
				array_push($cp,array('$UF','mb_estado','Estado (se no Brasil)',True,True));
				array_push($cp,array('$S60','mb_local','Universidade/Local',False,True));				
				array_push($cp,array('$U8','mb_update','',False,True));
				array_push($cp,array('$D8','mb_data_inicio','Data Inicial',False,True));
				array_push($cp,array('$D8','mb_data_fim','Data final',False,True));
				//array_push($cp,array('$I8','mb_dias','Dias',True,True));
				array_push($cp,array('$T60:3','mb_observacao','Obs',False,True));
				
				array_push($cp,array('$O : &A:Ativo&B:Concluído&X:Cancelado','mb_status','Status',True,True));
				return($cp);		
			}

		function cp_discente()
			{
				$cp = array();
				array_push($cp,array('$H8','id_mb','',False,True));
				array_push($cp,array('$S8','mb_discente','Discente',True,True));
				array_push($cp,array('$S8','mb_docente','Orientador',False,True));
				array_push($cp,array('$Q pos_nome:pos_codigo:select * from programa_pos where pos_ativo=1 order by pos_nome ','mb_programa','Programa',True,True));
				array_push($cp,array('$H8','mb_escola','',False,True));
				array_push($cp,array('$Q mt_descricao:mt_codigo:select * from mobilidade_tipo where mt_publico=\'I\'','mb_tipo','Modalidade',True,True));
				array_push($cp,array('$CITY','mb_pais','Pais',True,True));
				array_push($cp,array('$UF','mb_estado','Estado (se no Brasil)',True,True));
				array_push($cp,array('$S60','mb_local','Universidade/Local',True,True));								
				array_push($cp,array('$U8','mb_update','',False,True));
				array_push($cp,array('$D8','mb_data_inicio','Data Inicial',False,True));
				array_push($cp,array('$D8','mb_data_fim','Data final',False,True));
				array_push($cp,array('$T60:3','mb_observacao','Obs',False,True));
				array_push($cp,array('$O A:Ativo&B:Concluído&X:Cancelado','mb_status','Status',True,True));
				return($cp);
				
			}

		function row()
			{
				global $cdf,$cdm,$masc;
				$cdf = array('id_mt','mt_codigo','mt_descricao','mt_internacional','mt_publico');
				$cdm = array('cod',msg('codigo'),msg('descricao'),msg('abrangencia'),msg('publico'));
				$masc = array('','','','','','','','');
				return(1);				
			}
		function cp_tipo()
			{
				$cp = array();
				array_push($cp,array('$H8','id_mt','',False,True));
				array_push($cp,array('$H8','mt_codigo','',False,True));
				array_push($cp,array('$S60','mt_descricao','Descrição',True,True));
				array_push($cp,array('$T60:3','mt_obs','Obs',False,True));
				array_push($cp,array('$O : &I:Internacional&N:Nacional','mt_internacional','Tipo',True,True));
				array_push($cp,array('$O D:Docente&I:Discente&V:Prof. Visitante&E:Aluno estrangeiro','mt_publico','Público',True,True));
				return($cp);
			}
		function updatex()
			{
				global $base;
				$c = 'mt';
				$c1 = 'id_'.$c;
				$c2 = $c.'_codigo';
				$c3 = 5;
				if ($base=='pgsql') { $sql = "update ".$this->tabela_tipo." set $c2 = trim(to_char(id_".$c.",'00000')) where $c2 = '' "; }
				$rlt = db_query($sql);				
			}
		function strucuture()
			{				
				$sql = "create table mobilidade
					(
						id_mb serial not null,
						mb_discente char(8),
						mb_docente char(8),
						mb_programa char(8),
						mb_escola char(7),
						mb_tipo char(5),
						mb_update integer,
						mb_data_inicio integer,
						mb_data_fim integer,
						mb_observacao text,
						mb_status char(1),
						mb_pais char(7),
						mb_estado char(2),
						mb_local char(60),
						mb_nome char(60),
						mb_cpf char(20),
						mb_dias integer,
						mb_linha char(7),
						mb_nasc integer,
						mb_cracha char(12),
						mb_cr_curso char(12)
					)
				";
				$rlt = db_query($sql);
				
				$sql = "create table mobilidade_tipo
					(
						id_mt serial not null,
						mt_codigo char(5),
						mt_descricao char(60),
						mt_obs text,
						mt_internacional char(1),
						mt_publico char(1)	
					)
				";
				$rlt = db_query($sql);
			}
	}
