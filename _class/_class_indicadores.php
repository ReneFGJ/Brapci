<?php
$sql = "CREATE OR REPLACE FUNCTION isnull(text, text) RETURNS text AS 'SELECT (CASE (SELECT $1 
    is null) WHEN true THEN $2 ELSE $1 END) AS RESULT' LANGUAGE 'sql';";
//$rlt = db_query($sql);
//echo $sql;
class indicador
	{
		var $indicador;
		var $ano = '2009'; /* ano de avaliacao */
		function indicador_001($tipo = 3)
			{
				//$this->structure();
				/* VALIDACAO DAS ESCOLAS DOS PROFESSORES */
				$sql = 
					"
					select pp_cracha, pp_nome, pp_escola, pp_curso
					from pibic_bolsa_contempladas 
					inner join pibic_professor on pb_professor = pp_cracha				
					where (trim(pp_escola) = '') or (pp_escola is null)
					";
				$rlt = db_query($sql);
				$id = 0;
				$sx = '<table width="100%" class="lt1_tb">';
				while ($line = db_read($rlt))
					{
						$id++;
						$sx .= '<TR>';
						$sx .= '<TD>'.$line['pp_nome'];
						$sx .= '('.trim($line['pp_cracha']).')';
						$sx .= '<TD>'.$line['pp_curso'];
						$sx .= '<TD>'.$line['pp_escola'];
					}
				
				$sx .= '<TR><TD colspan=5>Total de '.$id.' registros';
				$sx .= '</table>';
				if ($id > 0)
					{
						echo '<font color="red">ERRO, Professor sem escola</font>, ajuste antes de continuar';
						echo $sx;
						exit;	
					}
				/* pesquisadores envolvidos em IC por área do conhecimento */
				$ano = $this->ano;
				$cp = ' pb_ano, pp_escola, centro_nome, pbt_edital ';
				$wh = " where (pb_status <> 'C' and pb_status <> '') and pb_ano = '$ano' ";
								
				$ext = '';
				
				if (($tipo == 1) or ($tipo == 3) or ($tipo == 4))
					{
						$cp = ' pb_ano, pp_escola, centro_nome, pb_professor, pbt_descricao, pbt_edital '; 
						$ext = '';
						$wh = " where pb_status <> 'C' and pb_ano = '$ano' ";
					}
				if (($tipo == 10))
					{
						$cp = ' pb_ano, pp_centro, pp_escola, centro_nome, pb_professor, pbt_descricao, pbt_edital '; 
						$ext = '';
						$wh = " where pb_status <> 'C' and pb_ano = '$ano' ";
					}					
				if ($tipo == 2) { $cp = ' pb_ano, pp_escola, centro_nome '; $ext = 'pbt_edital, ';}
				
				if (($tipo == 3) or ($tipo == 4))
					{
					$sql = 
					"
					select pb_ano, pp_escola, centro_nome, sum(total) as projeto, count(*) as total, pbt_edital
					from (
					select $cp, count(*) as total from pibic_bolsa_contempladas 
					inner join pibic_professor on pb_professor = pp_cracha
					left join centro on centro_codigo = pp_escola
					left join pibic_bolsa_tipo on pbt_codigo = pb_tipo
					$wh
					group by $cp 
					limit 5000
					) as tabela 
					group by pb_ano, pp_escola, centro_nome, pbt_edital
					order by pbt_edital, centro_nome
					";
					}
					
				if ($tipo == 5)
					{
					$sql = 
					"
					select pb_ano, pp_escola, centro_nome, pbt_descricao, sum(total) as projeto, count(*) as total
					from (
					select $cp, pbt_descricao, count(*) as total from pibic_bolsa_contempladas 
					inner join pibic_professor on pb_professor = pp_cracha 
					left join centro on centro_codigo = pp_escola
					left join pibic_bolsa_tipo on pbt_codigo = pb_tipo
					$wh
					group by $cp, pbt_descricao
					order by $ext pb_ano desc, centro_nome
					limit 1000
					) as tabela 
					group by pb_ano, centro_nome, pbt_descricao, pp_escola
					order by pbt_descricao, centro_nome
					";
					}	

					
				if ($tipo == 6)
					{
					$sql = 
					"
					select pb_ano,pbt_descricao, pbt_edital as centro_nome, count(*) as total
					from pibic_bolsa_contempladas 
					left join pibic_bolsa_tipo on pbt_codigo = pb_tipo
					$wh
					group by  pb_ano,pbt_descricao, centro_nome
					order by pbt_descricao
					limit 1000
					";
					}
					
				if ($tipo == 7)
					{
					$sql = 
					"
					select la_ano as pb_ano, lt_titulo as centro_nome , centro_nome as pbt_descricao, count(*) as total
					from 
						(select la_tipo, la_ano, la_periodico, la_vol, la_pag, la_professor from lattes_artigos where la_pag <> '1' group by la_tipo, la_ano, la_periodico, la_vol, la_pag, la_professor ) as tabela
					left join lattes_tipo on lt_codigo = la_tipo
					inner join pibic_professor on la_professor = pp_cracha
					left join centro on centro_codigo = pp_escola
					where la_ano = '$ano' 
					group by  pb_ano, pbt_descricao, centro_nome, lt_titulo
					order by  pbt_descricao, centro_nome, pbt_descricao 
					limit 1000
					";
					}			
					

				if ($tipo == 8)
					{
					$sql = 
					"
					select la_ano as pb_ano, la_tipo as pbt_descricao, lt_titulo as centro_nome, count(*) as total
					from 
						(select la_tipo, la_ano, la_periodico, la_vol, la_pag, la_professor from lattes_artigos where la_pag <> '1' group by la_tipo, la_ano, la_periodico, la_vol, la_pag, la_professor ) as tabela
					left join lattes_tipo on lt_codigo = la_tipo
					inner join pibic_professor on la_professor = pp_cracha
					where la_ano = '$ano' 
					group by  pb_ano,pbt_descricao, centro_nome
					order by centro_nome, pbt_descricao 
					limit 1000
					";
					}
					
				if ($tipo == 9)
					{
					$sql = 
					"
					select pb_ano, pa_curso  as centro_nome, count(*) as total from pibic_bolsa_contempladas 
					inner join pibic_aluno on pb_aluno = pa_cracha and pa_cracha <> ''
					left join pibic_bolsa_tipo on pbt_codigo = pb_tipo
					where pb_ano = '$ano' and pb_status <> 'C'
					group by pa_curso, pb_ano
					order by centro_nome
					limit 5000
					";
					}
					
				if ($tipo == 10)
					{
					$sql = 
					"
					select pb_ano, pp_escola, centro_nome as pbt_descricao, pbt_descricao as centro_nome, sum(total) as projeto, count(*) as total
					from (
					select $cp, pbt_descricao, count(*) as total from pibic_bolsa_contempladas 
					inner join pibic_professor on pb_professor = pp_cracha 
					left join centro on centro_codigo = pp_escola
					left join pibic_bolsa_tipo on pbt_codigo = pb_tipo
					$wh and pb_status <> 'C'
					group by $cp, pbt_descricao
					order by $ext pb_ano desc, centro_nome
					limit 1000
					) as tabela 
					group by pb_ano, centro_nome, pbt_descricao, pp_escola
					order by pbt_descricao, centro_nome
					";
					}						

				if ($tipo == 11)
					{
					$sql = "update pibic_professor set pp_centro = 'PUC CURITIBA' where pp_centro = 'ESCOLA DE EDUCAÇÃO E HUMANIDADES' ";
					$rlt = db_query($sql);
						
					$cp = 'pp_centro, pb_ano, pbt_edital';
					$sql = 
					"
					select pb_ano, pp_centro, pbt_edital, sum(total) as projeto, count(*) as total
					from (
						select $cp, count(*) as total from pibic_bolsa_contempladas 
						inner join pibic_professor on pb_professor = pp_cracha 
						left join pibic_bolsa_tipo on pbt_codigo = pb_tipo
						$wh and pb_status <> 'C'
						group by $cp
					) as tabela 
					group by pb_ano, pp_centro, pbt_edital
					order by pp_centro
					";
					}				
						
				$rlt = db_query($sql);

				$indicador = strzero($tipo,7);
				$this->indicador = $indicador;
				$sql = "delete from indicadores_data where idt_indicador = '$indicador' and idt_data = '$ano' "; 
				//$sql = "delete from indicadores_data where 1=1 ";
				$rrr = db_query($sql);
	
				$sx = '<table width="700" class="lt1" align="center">';
				$tot = 0;
				$tota = 0;
				$it = 0;
				$sx .= '<TR><TH>Ano<TH>Escola<TH>Docentes<TH>Projetos';
				$tot1 = 0; $tot2 = 0; $tot3 = 0; $tot4 = 0;
				while ($line = db_read($rlt))
					{
						$tot = $tot + $line['total'];
						$tota = $tota + $line['projeto'];
						$nome = trim($line['centro_nome']);
						$nome .= ' '.trim($line['pbt_descricao']);
						$name = $line['pbt_edital'];
						
						if (($tipo ==11)) 
								{
									$nome = trim($line['pp_centro']); 
									if ($nome != $xnome)
										{
											$sx .= '<TR><TH colspan=10 align="left">'.$nome;
											$xnome = $nome;
										}	
								}
						if (($tipo ==5) or ($tipo ==10) or ($tipo == 6) or ($tipo == 7)) 
								{ $nome = trim($line['pbt_descricao']).'-'.$nome; }
						if (($tipo ==3) and ($xname != $name) ) 
								{
									if ($tot1 > 0)
										{
										$sx .= '<TR><TD colspan=2 align="right"><B>Totais</B>';
										$sx .= '<TD align="center">'.$tot2;
										$sx .= '<TD align="center">'.$tot1;
										}
									$sx .= '<TR><TD class="lt4" colspan=10><HR><B>'.$line['pbt_edital'].'</B>';
									$xname = $name; 
									$tot1 = 0;
									$tot2 = 0;
								}
														
						$it++;
						$sx .= '<TR '.coluna().'>';
						$sx .= '<TD class="tabela01">';
						$sx .= $line['pb_ano'];
						$sx .= '/'.$line['pbt_edital'];
												
						$sx .= '<TD class="tabela01">';
						//$sx .= $line['pp_escola'];
						//$sx .= '<TD>';
						$sx .= $nome;
						$sx .= '<TD align="center" class="tabela01">';
						$sx .= $line['total'];
						$sx .= '<TD align="center" class="tabela01">';
						$sx .= $line['projeto'];
												
						$data = $line['pb_ano'];
						$var1 = $nome;
						$total = round($line['total']);
						if ($tipo == 4) { $total = $line['projeto']; }
						if ($tipo == 5) { $total = $line['projeto']; }
						if ($tipo == 10) { $total = $line['projeto']; }
						$this->insert_data($data,$var1,$total);
						$tot1 = $tot1 + $line['projeto'];
						$tot2 = $tot2 + $line['total'];
					}
				if ($tot1 > 0)
					{
					$sx .= '<TR><TD colspan=2 align="right"><B>Totais</B>';
					$sx .= '<TD align="center">'.$tot2;
					$sx .= '<TD align="center">'.$tot1;
					}


				$sx .= '<TR><TD colspan=5>Total de '.$it.' com '.$tot;
				$sx .= ', '.$tota.' projetos';
				$sx .= '</table>';
				return($sx);
				
			}
		
		function indicador_criar($nome,$metodologia,$periodicidade)
			{
				if (strlen($periodicidade)==0) { $periodicidade = -1; }
				$sql = "insert into ".$this->tabela." 
					(
						id_nome, id_metodologia, id_periodicidade, 
						id_lastupdate, id_codigo, id_ativo
					) values (
						'$nome','$metodologia','$periodicidade',
						19000101,'',1
					)
				";
				echo '<BR>'.$sql;
				$rlt = db_query($sql);
			}
			
		function insert_data($data,$cap1,$var1)
			{
				$cap1 = substr($cap1,0,60);
				$update = date("Ymd");
				$indicador = $this->indicador;
				$sql = "
				insert into indicadores_data 
					(idt_indicador, idt_update, idt_ativo,
					idt_var1, idt_data, 
					idt_total)
					values
					(
					'$indicador',$update,1,
					'$cap1',$data,
					$var1
					)
				"; 
				$rlt = db_query($sql);
			}
		function structure()
			{
				$sql = "
				CREATE TABLE indicadores
					(
						id_id serial NOT NULL,
						id_nome char(100),
						id_metodologia text,
						id_periodicidade integer,
						id_lastupdate integer,
						id_codigo char(5),
						id_ativo integer
					)			
				";
				//$rlt = db_query($sql);
				
				$sql = "
				CREATE TABLE indicadores_data
					(
						id_idt serial NOT NULL,
						idt_indicador char(7),
						idt_update integer,
						idt_var1 char(60),
						idt_data integer,
						idt_total float,
						idt_ativo integer
					)
				";
				$rlt = db_query($sql);
				
				$sql = "
				CREATE TABLE indicadores_nota
					(
						id_idn serial NOT NULL,
						idt_indicador char(7),
						idt_data integer,
						idt_texto text,
						idt_ativo integer
					)
				";
				$rlt = db_query($sql);				
				return(1);
			}	
	}
?>
