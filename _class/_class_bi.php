<?php
class bi
	{
	var $d1=19000101;
	var $d2=20500101;
	
	var $ProfSS=0;
	
	var $ano_ini = 0;
	var $ano_fim = 9999;
	
	var $ano = '2013';
	
	var $v1=0;
	var $v2=0;
	var $v3=0;
	var $v4=0;
	var $v5=0;
	var $v6=0;
	var $v7=0;
	var $v8=0;
	var $v9=0;
	
	function captacao_vigente_por_escola()
		{
				$sql = "select * from centro
						where centro_ativo = '1'
						order by centro_nome
				";
				$rlt = db_query($sql);
				$sx = '<table width="100%" class="tabela00">';
				$sx .= '<TR><TH>Nome do programa
							<TH width="10%">Total de projetos
							<TH>Valor total';
				$tot = 0;
				$tota = 0;
				while ($line = db_read($rlt))
					{
						$cod = $line['centro_codigo'];
						
						$sx .= '<TR>';
						
						$sx .= '<TD class="tabela01">';						
						$sx .= $line['centro_nome'];
												
						$this->captacao_vigente_por_centro_pos($cod);

						$sx .= '<TD align="center" class="tabela01">';
						$sx .= number_format($this->v5,0,',','.');
						
						$sx .= '<TD align="right" class="tabela01">';
						$sx .= number_format($this->v1,2,',','.');
						//$sx .= '<TD align="right">';
						//$sx .= number_format($this->v2,2,',','.');
						//$sx .= '<TD align="right">';
						//$sx .= number_format($this->v3,2,',','.');
						//$sx .= '<TD align="right">';
						//$sx .= number_format($this->v4,2,',','.');
						//$sx .= '<TD align="right">';
						//$sx .= number_format($this->v6,2,',','.');
						//$sx .= '<TD align="right">';
						//$sx .= number_format($this->v7,2,',','.');
						$tot = $tot + $this->v1;
						$tota = $tota + $this->v5;
					}
				$sx .= '<TR><TD colspan=10><I>Total de projetos vigentes ('.$tota.') '.number_format($tot,2,',','.');
				$sx .= '</table>';
				return($sx);
			}
		function captacao_vigente_por_centro_pos($dd1)
			{
				$datai = $this->ano_ini;
				$dataf = $this->ano_fim;
				
				$sql = "select * from captacao
				inner join programa_pos_docentes on ca_professor = pdce_docente 
				inner join programa_pos on pos_codigo = pdce_programa
				inner join pibic_professor on pp_cracha = pdce_docente
					where ((pos_centro = '".$dd1."' or ca_escola = '".$dd1."') and (ca_escola = '".$dd1."' or ca_escola isnull))
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
						$tot5++;							
						$prof = $line['pp_cracha'];
						if ($prof != $xprof)
						{
							$sx .= '<TR><TD colspan=4 class="lt3">'.$line['pp_nome'];
							$xprof = $prof;
						}
						
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
					
					$this->v1 = $tot;
					$this->v2 = $tot2;
					$this->v3 = $tot3;
					$this->v4 = $tot4;
					$this->v5 = $tot5;
					$this->v6 = $tot6;
					$this->v7 = $tot7;
					
				return($sx);
			}	
	function pibic_planos_submetidos()
		{
			$rs = new pibic_projetos;
			$sx = '<h3>Planos Submetidos por Campus</h3>';
			$sx .= $rs->resumo_planos_campi();
			
			$sx .= '<h3>Planos Submetidos por Escolas</h3>';
			$sx .= $rs->resumo_planos_centro();
						
			return($sx);
		}
		
		function captacao_vigente_por_programa($anoi=0,$anof=0)
			{
				if ($anoi > 0) { $this->ano_ini = $anoi; }
				if ($anof > 0) { $this->ano_fim = $anof; }
				$sql = "select * from programa_pos 
						where pos_corrente = '1'
						order by pos_nome
				";
				$rlt = db_query($sql);
				$sx = '<table width="100%" class="tabela00">';
				$sx .= '<TR><TH>Nome do programa
							<TH width="10%">Total de projetos
							<TH>Valor total';
				$tot = 0; $tota = 0;
				while ($line = db_read($rlt))
					{
						$cod = $line['pos_codigo'];
						
						$sx .= '<TR>';
						
						$sx .= '<TD class="tabela01">';						
						$sx .= $line['pos_nome'];
												
						$this->captacao_vigente_por_programa_pos($cod);

						$sx .= '<TD align="center" class="tabela01">';
						$sx .= number_format($this->v5,0,',','.');
						
						$sx .= '<TD align="right" class="tabela01">';
						$sx .= number_format($this->v1,2,',','.');
						//$sx .= '<TD align="right">';
						//$sx .= number_format($this->v2,2,',','.');
						//$sx .= '<TD align="right">';
						//$sx .= number_format($this->v3,2,',','.');
						//$sx .= '<TD align="right">';
						//$sx .= number_format($this->v4,2,',','.');
						//$sx .= '<TD align="right">';
						//$sx .= number_format($this->v6,2,',','.');
						//$sx .= '<TD align="right">';
						//$sx .= number_format($this->v7,2,',','.');
						$tot = $tot + $this->v1;
						$tota = $tota + $this->v5;
					}
				$sx .= '<TR><TD colspan=10><I>Total de projetos vigentes ('.$tota.') '.number_format($tot,2,',','.');
				$sx .= '</table>';
				return($sx);
			}
		
		function captacao_vigente_por_programa_pos($dd1)
			{
				$datai = $this->ano_ini;
				$dataf = $this->ano_fim;
				
				$sql = "select * from captacao
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
						$tot5++;							
						$prof = $line['pp_cracha'];
						if ($prof != $xprof)
						{
							$sx .= '<TR><TD colspan=4 class="lt3">'.$line['pp_nome'];
							$xprof = $prof;
						}
						
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
					
					$this->v1 = $tot;
					$this->v2 = $tot2;
					$this->v3 = $tot3;
					$this->v4 = $tot4;
					$this->v5 = $tot5;
					$this->v6 = $tot6;
					$this->v7 = $tot7;
					
				return($sx);
			}
	
	function professor_com_captacao($tp=0)
		{
			$wh = ''; $tit_rel = '% Professores com Projetos de Captação Aprovados / Andamento';
			$cp = "";
			$sql = "select count(*) as total from (
						select 1 as id, pp_cracha from pibic_professor 
						where pp_ss = 'S' and pp_update = '".date("Y")."'
						group by pp_cracha
					) as tabela 
					";
			$rlt = db_query($sql);
			$line = db_read($rlt);
			$ProfSS = $line['total'];
			$this->ProfSS = $ProfSS;
			$sql = "select count(*) as total from (
					select 1 as total, pp_cracha from captacao 
					inner join pibic_professor on pp_cracha = ca_professor
					inner join agencia_de_fomento on agf_codigo = ca_agencia_gr
					where pp_ss = 'S' and ca_vigencia_ini_ano >= ".date("Y")."
					and ca_proponente_vlr > 0 $wh
					group by pp_cracha
					) as tabela
					";
			$rlt = db_query($sql);
			$id = 0;
			if ($line = db_read($rlt))
				{ $ProfCap = $line['total']; }
			
			$razao01 = number_format($ProfCap / $ProfSS * 100,2,',','.').'%';
			
			$sqlp = "
			select count(*) as total_cap, pdce_programa as pos from (
					select 1 as total, pdce_programa from captacao 
					inner join pibic_professor on pp_cracha = ca_professor
					inner join programa_pos_docentes on pp_cracha = pdce_docente
					inner join agencia_de_fomento on agf_codigo = ca_agencia_gr
					where pp_ss = 'S' and ca_vigencia_ini_ano >= ".date("Y")."
					and ca_proponente_vlr > 0 $wh
					group by pdce_programa, pp_cracha
					) as tabela
					group by pdce_programa
			";

			/* Capção por programa */
			$sql = "select count(*) as total, max(total_cap) as total_cap, pdce_programa, pos_nome, centro_nome from (
					select pdce_docente, pdce_programa from programa_pos_docentes 
					where pdce_ativo = 1 
					group by pdce_docente, pdce_programa ) as tabela
					inner join programa_pos on pos_codigo = pdce_programa
					inner join centro on centro_codigo = pos_centro 
					left join (".$sqlp.") as tabela05 on pos = pdce_programa 
					group by pdce_programa, pos_nome, centro_nome
					order by centro_nome, pos_nome
					";
			$rlt = db_query($sql);
			$xesco = 'X';
			while ($line = db_read($rlt))
				{
					$ProfProg = $line['total'];
					$ProfProgCap = $line['total_cap'];
					$razao02 = number_format($ProfProgCap / $ProfProg * 100,2,',','.').'%';
					$esco = trim($line['centro_nome']);
					if ($esco != $xesco)
						{
							$sa .= '<TR><TD colspan=4 class="lt2"><B>'.$esco.'</B>';
							$xesco = $esco;
						}
					$sa .= '<TR>';
					$sa .= '<TD class="tabela01">';
					$sa .= $line['pos_nome'];
					$sa .= '<TD class="tabela01" align="center">&nbsp;';
					$sa .= $line['total'];
					$sa .= '<TD class="tabela01" align="center">&nbsp;';
					$sa .= round($line['total_cap']);
					$sa .= '<TD align="center" class="tabela01">'.$razao02;
				}
			
			/* Captação por Escolas */
			$sqlp = "
			select count(*) as total_cap, pos_centro as pos from (
					select 1 as total, pos_centro from captacao 
					inner join pibic_professor on pp_cracha = ca_professor
					inner join programa_pos_docentes on pp_cracha = pdce_docente
					inner join programa_pos on pos_codigo = pdce_programa
					inner join agencia_de_fomento on agf_codigo = ca_agencia_gr
					where pp_ss = 'S' and ca_vigencia_ini_ano >= ".date("Y")."
					and ca_proponente_vlr > 0 $wh
					group by pp_cracha, pos_centro
					) as tabela
					group by pos_centro
			";
			/* Capção por programa */
			$sql = "select count(*) as total, max(total_cap) as total_cap, centro_nome from (
					select pdce_docente, pdce_programa from programa_pos_docentes 
					where pdce_ativo = 1 
					group by pdce_docente, pdce_programa ) as tabela
					inner join programa_pos on pos_codigo = pdce_programa
					left join (".$sqlp.") as tabela05 on pos = pos_centro
					inner join centro on centro_codigo = pos_centro 
					group by centro_nome
					order by centro_nome
					";
			$rlt = db_query($sql);
			$xesco = 'X';
			$sb = '';
			while ($line = db_read($rlt))
				{
					$ProfProg = $line['total'];
					$ProfProgCap = $line['total_cap'];
					$razao03 = number_format($ProfProgCap / $ProfProg * 100,2,',','.').'%';
					$sb .= '<TR>';
					$sb .= '<TD class="tabela01">';
					$sb .= $line['centro_nome'];
					$sb .= '<TD class="tabela01" align="center">&nbsp;';
					$sb .= $line['total'];
					$sb .= '<TD class="tabela01" align="center">&nbsp;';
					$sb .= round($line['total_cap']);
					$sb .= '<TD align="center" class="tabela01">'.$razao03;
				}
						
			
			
			$sx = '<table class="tabela00" width="100%">';
			
			$sc .= '<TR><TD colspan=4><h3>'.$tit_rel.'</h3>';
			$sc .= '<TR><TH>Descrição<TH>Tot. Prof.<TH>Tot. Prof com Captação<TH>Captação / nº prof.<BR> do program.';
			$sc .= '<TR><TD class="tabela01">Indicador Total
						<TD align="center" class="tabela01" width="15%">'.$ProfSS.'
						<TD align="center" class="tabela01" width="15%">'.$ProfCap.'
						<TD align="center" class="tabela01" width="15%">'.$razao01;
			
			$sc .= '<TR><TD colspan=4 class="lt0">Razão entre o professores com captação (ProfCap) pelo total de professores vinculados a programas de <I>Stricto Sensu</I> (ProfSS) no ano de '.date("Y");
			
			$sx .= $sc;			

			/* Por programas */
			$sx .= '<TR><TD><BR><BR>';
			$sx .= '<TR><TD colspan=4 class="lt4">Resultados por Escolas';
			$sx .= '<TR><TH>Descrição<TH>Tot. Prof.<TH>Tot. Prof com Captação<TH>Captação / nº prof.<BR> do program.';
			$sx .= $sb;
			
			$sx .= '<TR><TD><BR><BR>';
			$sx .= '<TR><TD colspan=4 class="lt4">Resultados por programas';
			$sx .= '<TR><TH>Descrição<TH>Tot. Prof.<TH>Tot. Prof com Captação<TH>Captação / nº prof.<BR> do program.';
			$sx .= $sa;
			
			

			/* Por escolas */
			$sx .= '</table>';
			return($sx);
		}
	function professor_com_captacao_vigente($tp=0)
		{
			$wh = ''; 
			
			$tit_rel = '% Professores com Projetos de Captação Aprovados / Vigentes ';
			$cp = "";
			$sql = "select count(*) as total from (
						select 1 as id, pp_cracha from pibic_professor 
						where pp_ss = 'S' and pp_update = '".date("Y")."'
						group by pp_cracha
					) as tabela 
					";
			$rlt = db_query($sql);
			$line = db_read($rlt);
			$ProfSS = $line['total'];
			$this->ProfSS = $ProfSS;
			$sql = "select count(*) as total from (
					select 1 as total, pp_cracha from captacao 
					inner join pibic_professor on pp_cracha = ca_professor
					inner join agencia_de_fomento on agf_codigo = ca_agencia_gr
					where pp_ss = 'S' and ca_vigencia_ini_ano >= ".$this->ano."
					and ca_proponente_vlr > 0 $wh
					group by pp_cracha
					) as tabela
					";
			$rlt = db_query($sql);
			$id = 0;
			if ($line = db_read($rlt))
				{ $ProfCap = $line['total']; }
			
			$razao01 = number_format($ProfCap / $ProfSS * 100,2,',','.').'%';
			
			$sqlp = "
			select count(*) as total_cap, pdce_programa as pos from (
					select 1 as total, pdce_programa from captacao 
					inner join pibic_professor on pp_cracha = ca_professor
					inner join programa_pos_docentes on pp_cracha = pdce_docente
					inner join agencia_de_fomento on agf_codigo = ca_agencia_gr
					where pp_ss = 'S' and ca_vigencia_ini_ano >= ".$this->ano."
					and ca_proponente_vlr > 0 $wh
					group by pdce_programa, pp_cracha
					) as tabela
					group by pdce_programa
			";

			/* Capção por programa */
			$sql = "select count(*) as total, max(total_cap) as total_cap, pdce_programa, pos_nome, centro_nome from (
					select pdce_docente, pdce_programa from programa_pos_docentes 
					where pdce_ativo = 1 
					group by pdce_docente, pdce_programa ) as tabela
					inner join programa_pos on pos_codigo = pdce_programa
					inner join centro on centro_codigo = pos_centro 
					left join (".$sqlp.") as tabela05 on pos = pdce_programa 
					group by pdce_programa, pos_nome, centro_nome
					order by centro_nome, pos_nome
					";
			$rlt = db_query($sql);
			$xesco = 'X';
			while ($line = db_read($rlt))
				{
					$ProfProg = $line['total'];
					$ProfProgCap = $line['total_cap'];
					$razao02 = number_format($ProfProgCap / $ProfProg * 100,2,',','.').'%';
					$esco = trim($line['centro_nome']);
					if ($esco != $xesco)
						{
							$sa .= '<TR><TD colspan=4 class="lt2"><B>'.$esco.'</B>';
							$xesco = $esco;
						}
					$sa .= '<TR>';
					$sa .= '<TD class="tabela01">';
					$sa .= $line['pos_nome'];
					$sa .= '<TD class="tabela01" align="center">&nbsp;';
					$sa .= $line['total'];
					$sa .= '<TD class="tabela01" align="center">&nbsp;';
					$sa .= round($line['total_cap']);
					$sa .= '<TD align="center" class="tabela01">'.$razao02;
				}
			
			/* Captação por Escolas */
			$sqlp = "
			select count(*) as total_cap, pos_centro as pos from (
					select 1 as total, pos_centro from captacao 
					inner join pibic_professor on pp_cracha = ca_professor
					inner join programa_pos_docentes on pp_cracha = pdce_docente
					inner join programa_pos on pos_codigo = pdce_programa
					inner join agencia_de_fomento on agf_codigo = ca_agencia_gr
					where pp_ss = 'S' and ca_vigencia_ini_ano >= ".$this->ano."
					and ca_proponente_vlr > 0 $wh
					group by pp_cracha, pos_centro
					) as tabela
					group by pos_centro
			";
			/* Capção por programa */
			$sql = "select count(*) as total, max(total_cap) as total_cap, centro_nome from (
					select pdce_docente, pdce_programa from programa_pos_docentes 
					where pdce_ativo = 1 
					group by pdce_docente, pdce_programa ) as tabela
					inner join programa_pos on pos_codigo = pdce_programa
					left join (".$sqlp.") as tabela05 on pos = pos_centro
					inner join centro on centro_codigo = pos_centro 
					group by centro_nome
					order by centro_nome
					";
			$rlt = db_query($sql);
			$xesco = 'X';
			$sb = '';
				
			while ($line = db_read($rlt))
				{
					$ProfProg = $line['total'];
					$ProfProgCap = $line['total_cap'];
					$razao03 = number_format($ProfProgCap / $ProfProg * 100,2,',','.').'%';
					$sb .= '<TR>';
					$sb .= '<TD class="tabela01">';
					$sb .= $line['centro_nome'];
					$sb .= '<TD class="tabela01" align="center">&nbsp;';
					$sb .= $line['total'];
					$sb .= '<TD class="tabela01" align="center">&nbsp;';
					$sb .= round($line['total_cap']);
					$sb .= '<TD align="center" class="tabela01">'.$razao03;
				}
						
			
			
			$sx = '<table class="tabela00" width="100%">';
			
			$sc .= '<TR><TD colspan=4><h3>'.$tit_rel.'</h3>';
			$sc .= '<TR><TH>Descrição<TH>Tot. Prof.<TH>Tot. Prof com Captação<TH>Captação / nº prof.<BR> do program.';
			$sc .= '<TR><TD class="tabela01">Indicador Total
						<TD align="center" class="tabela01" width="15%">'.$ProfSS.'
						<TD align="center" class="tabela01" width="15%">'.$ProfCap.'
						<TD align="center" class="tabela01" width="15%">'.$razao01;
			
			$sc .= '<TR><TD colspan=4 class="lt0">Razão entre o professores com captação (ProfCap) pelo total de professores vinculados a programas de <I>Stricto Sensu</I> (ProfSS) no ano de '.date("Y");
			
			$sx .= $sc;			

			/* Por programas */
			$sx .= '<TR><TD><BR><BR>';
			$sx .= '<TR><TD colspan=4 class="lt4">Resultados por Escolas';
			$sx .= '<TR><TH>Descrição<TH>Tot. Prof.<TH>Tot. Prof com Captação<TH>Captação / nº prof.<BR> do program.';
			$sx .= $sb;
			
			$sx .= '<TR><TD><BR><BR>';
			$sx .= '<TR><TD colspan=4 class="lt4">Resultados por programas';
			$sx .= '<TR><TH>Descrição<TH>Tot. Prof.<TH>Tot. Prof com Captação<TH>Captação / nº prof.<BR> do program.';
			$sx .= $sa;
			
			

			/* Por escolas */
			$sx .= '</table>';
			return($sx);
		}	
	function professor_com_captacao_ag_gov()
		{
			$wh = "and (agf_sigla like 'O%' and agf_ativo = 2) ";
			$tit_rel = 'Captação de Recursos via Agências de Fomento e Governamentais';
			
			$ProfSS = $this->ProfSS;
			
			$sql = "select count(*) as total, sum(ca_proponente_vlr) as ca_proponente_vlr from (
					select 1 as total, max(ca_proponente_vlr) as ca_proponente_vlr, pp_cracha from captacao 
					inner join pibic_professor on pp_cracha = ca_professor
					inner join agencia_de_fomento on agf_codigo = ca_agencia_gr
					where pp_ss = 'S' and ca_vigencia_ini_ano >= ".date("Y")."
					and ca_proponente_vlr > 0 $wh
					group by pp_cracha
					) as tabela
					";
			$rlt = db_query($sql);
			$id = 0;
			if ($line = db_read($rlt))
				{
					$ProfCap = $line['total']; 
					$ValCap = $line['ca_proponente_vlr'];
				}
			
			$razao01 = number_format($ValCap / $ProfSS * 100,2,',','.').'%';
			
			$sqlp = "
			select count(*) as total_cap, sum(ca_proponente_vlr) as ca_proponente_vlr, pdce_programa as pos  from (
					select 1 as total, max(ca_proponente_vlr) as ca_proponente_vlr, pdce_programa from captacao 
					inner join pibic_professor on pp_cracha = ca_professor
					inner join programa_pos_docentes on pp_cracha = pdce_docente
					inner join agencia_de_fomento on agf_codigo = ca_agencia_gr
					where pp_ss = 'S' and ca_vigencia_ini_ano >= ".date("Y")."
					and ca_proponente_vlr > 0 $wh
					group by pdce_programa, pp_cracha
					) as tabela
					group by pdce_programa
			";

			/* Capção por programa */
			$sql = "select count(*) as total, max(total_cap) as total_cap, 
					max(ca_proponente_vlr) as ca_proponente_vlr, pdce_programa, pos_nome, centro_nome from (
					select pdce_docente, pdce_programa from programa_pos_docentes 
					where pdce_ativo = 1 
					group by pdce_docente, pdce_programa ) as tabela
					inner join programa_pos on pos_codigo = pdce_programa
					inner join centro on centro_codigo = pos_centro 
					left join (".$sqlp.") as tabela05 on pos = pdce_programa 
					group by pdce_programa, pos_nome, centro_nome
					order by centro_nome, pos_nome
					";
			$rlt = db_query($sql);
			$xesco = 'X';
			while ($line = db_read($rlt))
				{
					$ProfProg = $line['total'];
					$ProfProgCap = $line['total_cap'];
					$VlrProgCap = $line['ca_proponente_vlr'];
					$razao02 = $VlrProgCap / $ProfProg;
					$esco = trim($line['centro_nome']);
					if ($esco != $xesco)
						{
							$sa .= '<TR><TD colspan=4 class="lt2"><B>'.$esco.'</B>';
							$xesco = $esco;
						}
					$sa .= '<TR>';
					$sa .= '<TD class="tabela01">';
					$sa .= $line['pos_nome'];
					$sa .= '<TD class="tabela01" align="center">&nbsp;';
					$sa .= $line['total'];
					$sa .= '<TD class="tabela01" align="center">&nbsp;';
					$sa .= round($line['total_cap']);
					$sa .= '<TD width="90" align="right" class="tabela01">'.number_format($line['ca_proponente_vlr'],2,',','.');
					$sa .= '<TD width="90" align="right" class="tabela01">'.number_format($razao02,2,',','.');
				}
			
			/* Captação por Escolas */
			$sqlp = "
			select count(*) as total_cap, sum(ca_proponente_vlr) as ca_proponente_vlr, pos_centro as pos from (
					select 1 as total, max(ca_proponente_vlr) as ca_proponente_vlr, pos_centro from captacao 
					inner join pibic_professor on pp_cracha = ca_professor
					inner join programa_pos_docentes on pp_cracha = pdce_docente
					inner join programa_pos on pos_codigo = pdce_programa
					inner join agencia_de_fomento on agf_codigo = ca_agencia_gr
					where pp_ss = 'S' and ca_vigencia_ini_ano >= ".date("Y")."
					and ca_proponente_vlr > 0 $wh
					group by pp_cracha, pos_centro
					) as tabela
					group by pos_centro
			";
			/* Capção por programa */
			$sql = "select count(*) as total, max(total_cap) as total_cap,
					max(ca_proponente_vlr) as ca_proponente_vlr, centro_nome from (
					select pdce_docente, pdce_programa from programa_pos_docentes 
					where pdce_ativo = 1 
					group by pdce_docente, pdce_programa ) as tabela
					inner join programa_pos on pos_codigo = pdce_programa
					left join (".$sqlp.") as tabela05 on pos = pos_centro
					inner join centro on centro_codigo = pos_centro 
					group by centro_nome
					order by centro_nome
					";
			$rlt = db_query($sql);
			$xesco = 'X';
			$sb = '';
			while ($line = db_read($rlt))
				{
					$ProfProg = $line['total'];
					$ProfProgCap = $line['total_cap'];
					$VlrProgCap = $line['ca_proponente_vlr'];
					$razao03 = $VlrProgCap / $ProfProg;
					$sb .= '<TR>';
					$sb .= '<TD class="tabela01">';
					$sb .= $line['centro_nome'];
					$sb .= '<TD class="tabela01" align="center">&nbsp;';
					$sb .= $line['total'];
					$sb .= '<TD class="tabela01" align="center">&nbsp;';
					$sb .= round($line['total_cap']);
					$sb .= '<TD width="90" align="right" class="tabela01">'.number_format($line['ca_proponente_vlr'],2,',','.');
					$sb .= '<TD width="90" align="right" class="tabela01">'.number_format($razao03,2,',','.');
				}
						
			
			
			$sx = '<table class="tabela00" width="100%">';
			
			$sc .= '<TR><TD colspan=4><h3>'.$tit_rel.'</h3>';
			$sc .= '<TR><TH>Descrição<TH>Tot. Prof.<TH>Tot. Prof com Captação<TH>Val. Captação<TH>Razão';
			$sc .= '<TR><TD class="tabela01">Indicador Total
						<TD align="center" class="tabela01">'.$ProfSS.'
						<TD align="center" class="tabela01">'.$ProfCap.'
						<TD align="center" class="tabela01" align="right">'.number_format($ValCap,2,',','.').'
						<TD align="center" class="tabela01">'.number_format($Razao01,2,',','.');
			
			$sc .= '<TR><TD colspan=4 class="lt0"> 
					Razão entre o Valor total de captação dos professores da pós-graduaçãp (ValProfCap) pelo total de professores vinculados a programas de <I>Stricto Sensu</I> (ProfSS) no ano de '.date("Y");
			
			$sx .= $sc;			

			/* Por programas */
			$sx .= '<TR><TD><BR><BR>';
			$sx .= '<TR><TD colspan=4 class="lt4">Resultados por Escolas';
			$sx .= '<TR><TH>Descrição<TH>Tot. Prof.<TH>Tot. Prof com Captação<TH>Captação<TH>Captação por<BR>Professor';
			$sx .= $sb;
			
			$sx .= '<TR><TD><BR><BR>';
			$sx .= '<TR><TD colspan=4 class="lt4">Resultados por programas';
			$sx .= '<TR><TH>Descrição<TH>Tot. Prof.<TH>Tot. Prof com Captação<TH>Captação<TH>Captação por<BR>Professor';
			$sx .= $sa;

			/* Por escolas */
			$sx .= '</table>';

			return($sx);			
		}	
	function mobilidade_discente()
		{
			$sql = "select count(*) as total from docente_orientacao 
					where 
						od_modalidade = 'D' and od_ano_ingresso >= ".(date("Y")-4)."
						or 
						od_modalidade = 'M' and od_ano_ingresso >= ".(date("Y")-2);
			$rlt = db_query($sql);
			$line = db_read($rlt);
			$ProfSS = $line['total'];
			$ProfSS = 1087;
			
			$sql = "select sum(total) as total, sum(visitante) as visitante from (
					select count(*) as total, 0 as visitante  from mobilidade 
					left join mobilidade_tipo on mt_codigo = mb_tipo
					where mb_data_inicio >= ".date("Y")."0000 and
						  mb_data_inicio <= ".date("Ymd")."
						  and mt_internacional = 'I'
						  and mt_publico = 'I'
					union
					select 0, count(*) as visitante  from mobilidade 
							left join mobilidade_tipo on mt_codigo = mb_tipo
					where mb_data_inicio >= ".date("Y")."0000 and
						  mb_data_inicio <= ".date("Ymd")."
						  and mt_internacional = 'I'
						  and mt_publico = 'E'
					) as tabela10										
			";
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
						{
							$ProfMob = $line['total']; 
							$ProfVis = $line['visitante'];
						}
		
			$sx = '<table class="tabela00" width="100%">';
			$sx .= '<TR><TD><BR><BR>';
			$sx .= '<TR><TD colspan=4><h4>Mobilidade Internacional - Discente</h4>';			
			$sx .= '<TR><TH>Mobilidade Internacional
							<TH>Tot. disc. 
							<TH>Tot. disc. <BR><I>outcoming</I>
							<TH>Tot. disc. <BR><I>incoming</I>
							<TH>Tot. disc. / <BR><I>outcoming</I>
							<TH>Tot. disc. / <BR><I>incoming</I>';
														
			$sx .= '<TR>
					<TD class="tabela01">Discentes <I>Stricto Sensu</I> em Mobilidade Internacional';
			$sx .= '<TD width="90" class="tabela01" align="center">'.$ProfSS;
			$sx .= '<TD width="90" class="tabela01" align="center">'.$ProfMob;
			$sx .= '<TD width="90" class="tabela01" align="center">'.$ProfVis;
			if ($ProfSS > 0)
				{
				$sx .= '<TD width="90" class="tabela01" align="center">'.number_format($ProfMob/$ProfSS*100,2,',',',').'%';
				$sx .= '<TD width="90" class="tabela01" align="center">'.number_format($ProfVis/$ProfSS*100,2,',',',').'%';
				}
			$sx .= '</table>';
			
			/*********************
			 * Captação por Escolas 
			 */
			$sqlv = "select 0 as professor, count(*) as visitante, mb_programa 
							from mobilidade 
							left join mobilidade_tipo on mt_codigo = mb_tipo
							where mb_data_inicio >= ".date("Y")."0000 and
							  	mb_data_inicio <= ".date("Ymd")."
						  		and mt_internacional = 'I'
						  		and mt_publico = 'E'
							group by mb_programa
					";
			$sqlp = "select count(*) as professor, 0 as visitante, mb_programa 
							from mobilidade 
							left join mobilidade_tipo on mt_codigo = mb_tipo
							where mb_data_inicio >= ".date("Y")."0000 and
							  	mb_data_inicio <= ".date("Ymd")."
						  		and mt_internacional = 'I'
						  		and mt_publico = 'I'
							group by mb_programa
					";
			$sqlq = "
					select sum(professor) as professor, sum(visitante) as visitante, mb_programa
						from ( $sqlp union $sqlv ) as tabela22 
						group by mb_programa";
								
			//$rlt = db_query($sqlq);
			//while ($line = db_read($rlt))
////				{
					//print_r($line);
				//}
			//exit;
			$sqlp = "
			select sum(professor) as professor, sum(professores) as  professores,
				sum(visitante) as  visitante, centro_nome
			from centro
				inner join programa_pos on centro_codigo = pos_centro and pos_corrente = '1'
			left join (
				$sqlq
				) as tebela22 on pos_codigo = mb_programa
			left join 
					(
						select pdce_programa, count(*) as professores
						from ( select pdce_programa, pdce_docente
							from programa_pos_docentes 
							where pdce_ativo = 1
							group by pdce_programa, pdce_docente
							) as tabela09 group by pdce_programa 
					) as tebala06 on pdce_programa = pos_codigo
			group by centro_nome
			order by centro_nome				  
			";	
				
			$rlt = db_query($sqlp);
			$xesco = 'X';
			$sc = '';
			$sc = '<table class="tabela00" width="100%">';
			$sc .= '<TR><TH>Mobilidade Internacional
							<TH>Tot. disc. 
							<TH>Tot. disc. <BR><I>outcoming</I>
							<TH>Tot. disc. <BR><I>incoming</I>
							<TH>Tot. disc. / <BR><I>outcoming</I>
							<TH>Tot. disc. / <BR><I>incoming</I>';
			while ($line = db_read($rlt))
				{
					$ProfVis = $line['visitante'];
					$ProfProg = $line['professores'];
					$total = $line['professor'];
					$razao02 = $total / $ProfProg;
					$razao03 = $ProfVis / $ProfProg;
					
					$esco = trim($line['centro_nome']);
					$sc .= '<TR>';
					$sc .= '<TD class="tabela01">';
					$sc .= $line['centro_nome'];
					$sc .= '<TD width="90" align="center" class="tabela01">'.$ProfProg;
					$sc .= '<TD width="90" align="center" class="tabela01">'.$total;
					$sc .= '<TD width="90" align="center" class="tabela01">'.$ProfVis;
					$sc .= '<TD width="90" align="center" class="tabela01">'.number_format($razao02*100,1,',','.').'%';
					$sc .= '<TD width="90" align="center" class="tabela01">'.number_format($razao03*100,1,',','.').'%';
					}
			$sc .= '</table>';
			$sx .= $sc;			
			
			/************************** 
			 * Captação por Programas 
			 */
			$sqlp = "
			select max(total) as total, max(professores) as  professores,
				pos_nome, centro_nome
			from centro
				inner join programa_pos on centro_codigo = pos_centro and pos_corrente = '1'
			left join (
				select count(*) as total, mb_programa 
						from mobilidade 
						left join mobilidade_tipo on mt_codigo = mb_tipo
						where mb_data_inicio >= ".date("Y")."0000 and
						  	mb_data_inicio <= ".date("Ymd")."
						  	and mt_internacional = 'I'
						  	and mt_publico = 'D'
						group by mb_programa
					) as tebela02 on pos_codigo = mb_programa
			left join 
					(
						select pdce_programa, count(*) as professores
						from ( select pdce_programa, pdce_docente
							from programa_pos_docentes 
							where pdce_ativo = 1
							group by pdce_programa, pdce_docente
							) as tabela09 group by pdce_programa 
					) as tebala06 on pdce_programa = pos_codigo
			group by pos_nome, centro_nome
			order by centro_nome, pos_nome					  
			";	

			$sqlp = "
			select sum(professor) as professor, sum(professores) as  professores,
				sum(visitante) as  visitante, centro_nome, pos_nome
			from centro
				inner join programa_pos on centro_codigo = pos_centro and pos_corrente = '1'
			left join (
				$sqlq
				) as tebela22 on pos_codigo = mb_programa
			left join 
					(
						select pdce_programa, count(*) as professores
						from ( select pdce_programa, pdce_docente
							from programa_pos_docentes 
							where pdce_ativo = 1
							group by pdce_programa, pdce_docente
							) as tabela09 group by pdce_programa 
					) as tebala06 on pdce_programa = pos_codigo
			group by centro_nome, pos_nome
			order by centro_nome, pos_nome				  
			";	
				
			$rlt = db_query($sqlp);
			$xesco = 'X';
			$sb = '';
			$sb = '<table class="tabela00" width="100%">';
			$sb .= '<TR><TH>Mobilidade Internacional
							<TH>Tot. disc. 
							<TH>Tot. disc. <BR><I>outcoming</I>
							<TH>Tot. disc. <BR><I>incoming</I>
							<TH>Tot. disc. / <BR><I>outcoming</I>
							<TH>Tot. disc. / <BR><I>incoming</I>';
			while ($line = db_read($rlt))
				{
					$ProfProg = $line['professores'];
					$total = $line['professor'];
					$ProfVis = $line['visitante'];
					if ($ProfProg > 0) { $razao02 = $total / $ProfProg; }
					$esco = trim($line['centro_nome']);
					if ($esco != $xesco)
						{
							$sb .= '<TR><TD colspan=4 class="lt2"><B>'.$esco.'</B>';
							$xesco = $esco;
						}
					$sb .= '<TR>';
					$sb .= '<TD class="tabela01">';
					$sb .= $line['pos_nome'];
					$sb .= '<TD width="90" align="center" class="tabela01">'.$ProfProg;
					$sb .= '<TD width="90" align="center" class="tabela01">'.$total;
					$sb .= '<TD width="90" align="center" class="tabela01">'.$ProfVis;
					$sb .= '<TD width="90" align="center" class="tabela01">'.number_format($razao02*100,1,',','.').'%';
					$sb .= '<TD width="90" align="center" class="tabela01">'.number_format($razao03*100,1,',','.').'%';
				}
			$sb .= '</table>';
			$sx .= $sb;
			/* Capção por programa */

			return($sx);
		}
	function mobilidade_docente()
		{
			$ProfSS = $this->ProfSS;
			$sql = "select sum(total) as total, sum(visitante) as visitante from (
					select count(*) as total, 0 as visitante  from mobilidade 
					inner join (
						select pp_cracha from pibic_professor where pp_ss = 'S' and pp_update = '".date("Y")."' 
						) as tabela01 on pp_cracha = mb_docente
					left join mobilidade_tipo on mt_codigo = mb_tipo
					where mb_data_inicio >= ".date("Y")."0000 and
						  mb_data_inicio <= ".date("Ymd")."
						  and mt_internacional = 'I'
						  and mt_publico = 'D'
					union
					select 0, count(*) as visitante  from mobilidade 
							left join mobilidade_tipo on mt_codigo = mb_tipo
					where mb_data_inicio >= ".date("Y")."0000 and
						  mb_data_inicio <= ".date("Ymd")."
						  and mt_internacional = 'I'
						  and mt_publico = 'V'
					) as tabela10										
			";
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
						{
							$ProfMob = $line['total']; 
							$ProfVis = $line['visitante'];
						}
		
			$sx = '<table class="tabela00" width="100%">';
			$sx .= '<TR><TD><BR><BR>';
			$sx .= '<TR><TD colspan=4><h4>Mobilidade Internacional - Docente</h4>';			
			$sx .= '<TR><TH>Mobilidade Internacional
							<TH>Tot. Prof. 
							<TH>Tot. Prof. <BR><I>outcoming</I>
							<TH>Tot. Prof. <BR><I>incoming</I>
							<TH>Razão
							<TH>Razão';
														
			$sx .= '<TR>
					<TD class="tabela01">Professores <I>Stricto Sensu</I> em Mobilidade Internacional';
			$sx .= '<TD width="90" class="tabela01" align="center">'.$ProfSS;
			$sx .= '<TD width="90" class="tabela01" align="center">'.$ProfMob;
			$sx .= '<TD width="90" class="tabela01" align="center">'.$ProfVis;
			if ($ProfSS > 0)
				{
				$sx .= '<TD width="90" class="tabela01" align="center">'.number_format($ProfMob/$ProfSS*100,2,',',',').'%';
				$sx .= '<TD width="90" class="tabela01" align="center">'.number_format($ProfVis/$ProfSS*100,2,',',',').'%';
				}
			$sx .= '</table>';
			
			/*********************
			 * Captação por Escolas 
			 */
			$sqlv = "select 0 as professor, count(*) as visitante, mb_programa 
							from mobilidade 
							left join mobilidade_tipo on mt_codigo = mb_tipo
							where mb_data_inicio >= ".date("Y")."0000 and
							  	mb_data_inicio <= ".date("Ymd")."
						  		and mt_internacional = 'I'
						  		and mt_publico = 'V'
							group by mb_programa
					";
			$sqlp = "select count(*) as professor, 0 as visitante, mb_programa 
							from mobilidade 
							left join mobilidade_tipo on mt_codigo = mb_tipo
							where mb_data_inicio >= ".date("Y")."0000 and
							  	mb_data_inicio <= ".date("Ymd")."
						  		and mt_internacional = 'I'
						  		and mt_publico = 'D'
							group by mb_programa
					";
			$sqlq = "
					select sum(professor) as professor, sum(visitante) as visitante, mb_programa
						from ( $sqlp union $sqlv ) as tabela22 
						group by mb_programa";
								
			//$rlt = db_query($sqlq);
			//while ($line = db_read($rlt))
////				{
					//print_r($line);
				//}
			//exit;
			$sqlp = "
			select sum(professor) as professor, sum(professores) as  professores,
				sum(visitante) as  visitante, centro_nome
			from centro
				inner join programa_pos on centro_codigo = pos_centro and pos_corrente = '1'
			left join (
				$sqlq
				) as tebela22 on pos_codigo = mb_programa
			left join 
					(
						select pdce_programa, count(*) as professores
						from ( select pdce_programa, pdce_docente
							from programa_pos_docentes 
							where pdce_ativo = 1
							group by pdce_programa, pdce_docente
							) as tabela09 group by pdce_programa 
					) as tebala06 on pdce_programa = pos_codigo
			group by centro_nome
			order by centro_nome				  
			";	
				
			$rlt = db_query($sqlp);
			$xesco = 'X';
			$sc = '';
			$sc = '<table class="tabela00" width="100%">';
			$sc .= '<TR><TH>Mobilidade Internacional
							<TH>Tot. Prof. 
							<TH>Tot. Prof. <BR><I>outcoming</I>
							<TH>Tot. Prof. <BR><I>incoming</I>
							<TH>Razão
							<TH>Razão';
			while ($line = db_read($rlt))
				{
					$ProfVis = $line['visitante'];
					$ProfProg = $line['professores'];
					$total = $line['professor'];
					$razao02 = $total / $ProfProg;
					$razao03 = $ProfVis / $ProfProg;
					
					$esco = trim($line['centro_nome']);
					$sc .= '<TR>';
					$sc .= '<TD class="tabela01">';
					$sc .= $line['centro_nome'];
					$sc .= '<TD width="90" align="center" class="tabela01">'.$ProfProg;
					$sc .= '<TD width="90" align="center" class="tabela01">'.$total;
					$sc .= '<TD width="90" align="center" class="tabela01">'.$ProfVis;
					$sc .= '<TD width="90" align="center" class="tabela01">'.number_format($razao02*100,1,',','.').'%';
					$sc .= '<TD width="90" align="center" class="tabela01">'.number_format($razao03*100,1,',','.').'%';
					}
			$sc .= '</table>';
			$sx .= $sc;			
			
			/************************** 
			 * Captação por Programas 
			 */
			$sqlp = "
			select max(total) as total, max(professores) as  professores,
				pos_nome, centro_nome
			from centro
				inner join programa_pos on centro_codigo = pos_centro and pos_corrente = '1'
			left join (
				select count(*) as total, mb_programa 
						from mobilidade 
						left join mobilidade_tipo on mt_codigo = mb_tipo
						where mb_data_inicio >= ".date("Y")."0000 and
						  	mb_data_inicio <= ".date("Ymd")."
						  	and mt_internacional = 'I'
						  	and mt_publico = 'D'
						group by mb_programa
					) as tebela02 on pos_codigo = mb_programa
			left join 
					(
						select pdce_programa, count(*) as professores
						from ( select pdce_programa, pdce_docente
							from programa_pos_docentes 
							where pdce_ativo = 1
							group by pdce_programa, pdce_docente
							) as tabela09 group by pdce_programa 
					) as tebala06 on pdce_programa = pos_codigo
			group by pos_nome, centro_nome
			order by centro_nome, pos_nome					  
			";	

			$sqlp = "
			select sum(professor) as professor, sum(professores) as  professores,
				sum(visitante) as  visitante, centro_nome, pos_nome
			from centro
				inner join programa_pos on centro_codigo = pos_centro and pos_corrente = '1'
			left join (
				$sqlq
				) as tebela22 on pos_codigo = mb_programa
			left join 
					(
						select pdce_programa, count(*) as professores
						from ( select pdce_programa, pdce_docente
							from programa_pos_docentes 
							where pdce_ativo = 1
							group by pdce_programa, pdce_docente
							) as tabela09 group by pdce_programa 
					) as tebala06 on pdce_programa = pos_codigo
			group by centro_nome, pos_nome
			order by centro_nome, pos_nome				  
			";	
				
			$rlt = db_query($sqlp);
			$xesco = 'X';
			$sb = '';
			$sb = '<table class="tabela00" width="100%">';
			$sb .= '<TR><TH>Mobilidade Internacional por Escolas';
			$sb .= '<TR><TH>Mobilidade Internacional
							<TH>Tot. Prof. 
							<TH>Tot. Prof. <BR><I>outcoming</I>
							<TH>Tot. Prof. <BR><I>incoming</I>
							<TH>Razão
							<TH>Razão';
			while ($line = db_read($rlt))
				{
					$ProfProg = $line['professores'];
					$total = $line['professor'];
					$ProfVis = $line['visitante'];
					if ($ProfProg > 0) { $razao02 = $total / $ProfProg; }
					$esco = trim($line['centro_nome']);
					if ($esco != $xesco)
						{
							$sb .= '<TR><TD colspan=4 class="lt2"><B>'.$esco.'</B>';
							$xesco = $esco;
						}
					$sb .= '<TR>';
					$sb .= '<TD class="tabela01">';
					$sb .= $line['pos_nome'];
					$sb .= '<TD width="90" align="center" class="tabela01">'.$ProfProg;
					$sb .= '<TD width="90" align="center" class="tabela01">'.$total;
					$sb .= '<TD width="90" align="center" class="tabela01">'.$ProfVis;
					$sb .= '<TD width="90" align="center" class="tabela01">'.number_format($razao02*100,1,',','.').'%';
					$sb .= '<TD width="90" align="center" class="tabela01">'.number_format($razao03*100,1,',','.').'%';
				}
			$sb .= '</table>';
			$sx .= $sb;
			/* Capção por programa */

			return($sx);
	
		}
	function producao_docente($anoi=0,$anof=0)
		{
			if ($anoi==0) { $anoi = date("Y"); }
			if ($anof==0) { $anof = date("Y"); }
			$lattes = new lattes;
			$tit_rel = 'Nível de Produção Qualificada PPG';
			$areas = array($clx->area_avaliacao_codigo);
			$sql = "select * from programa_pos 
						inner join centro on pos_centro = centro_codigo
						inner join campus on pos_campus = campus_codigo
						where pos_corrente = '1'
						order by campus_nome, centro_nome, pos_nome
			 ";
			$rlt = db_query($sql);
			$sx = '<table class="tabela00" width="100%">';
			$sx .= '<TR><TD colspan=8><h3>'.$tit_rel.'</h3>';
			$xcampus = '';
			$xcentro = '';
			while ($line = db_read($rlt))
				{
					$areas = array($line['pos_avaliacao_1']);
					$campus = trim($line['campus_nome']);
					$centro = trim($line['centro_nome']);
					if ($campus != $xcampus)
						{
							$sx .= '<TR><TD colspan=4><TD class="lt3" colspan=15><B>'.trim($line['campus_nome']).'</B>';
							$xcampus = $campus;
						}
					if ($centro != $xcentro)
						{
							$sx .= '<TR><TD colspan=4><TD class="lt2" colspan=15><B>'.trim($line['centro_nome']).'</B>';
							$xcentro = $centro;
						}
					$programa_pos = $line['pos_codigo'];
					$programa_pos_anoi = $anoi;
					$programa_pos_anof = $anof;
					$sx .= '<TR><TD colspan=4><TD class="tabela00" colspan=5>'.$line['pos_nome'];
					$sx .= '<TR><TD colspan=4><TD>'.$lattes->resumo_qualis_ss($programa_pos,$areas,$programa_pos_anoi,$programa_pos_anof,1);
					}
			$sx .= '<TR><TD colspan=4><TD colspan=5>'.$lattes->formula_equivalencia();
			$sx .= '</table>';
			return($sx);
			
			
		}
	}
?>

 