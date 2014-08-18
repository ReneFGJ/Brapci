<?php
class pibic_edital
	{
	var $tabela = "pibic_edital";
	var $ano = 2013;
	
	function show_protocolo_professor($cracha)
		{
			$sql = "select * from pibic_submit_documento 
						left join pibic_bolsa on pb_protocolo = doc_protocolo
																			
					where  doc_autor_principal = '".$cracha."' and doc_ano = '".date("Y")."'
					and pp_ano = '".date("Y")."'
					order by id_doc desc  
			";
			$rlt = db_query($sql);
			$to = 0;
			$sx = '<table width="100%" class="tabela00">';
			$sx .= '<TR><TH>proto</th><TH>Projeto</th><TH>Título do plano</th><TH>Res.';
			while ($line = db_read($rlt))
				{
					$to++;
					$bolsa_img = '<IMG SRC="../pibicpr/img/logo_bolsa_' . $line['pb_tipo'] . '.png" border=0 height="16" >';
					
					$id = $line['doc_protocolo_mae'];
					$link = '<A href="parecer.php?dd0='.$id.'&dd90='.checkpost($id).'">';
					$sx .= '<tr valign="top">';
					$sx .= '<TD class="tabela01">'.$link.$line['doc_protocolo_mae'].'</a>';
					$sx .= '<TD class="tabela01">'.$link.$line['doc_protocolo'].'</a>';
					$sx .= '<TD class="tabela01">'.$line['doc_1_titulo'];
					$sx .= '<TD class="tabela01">'.$bolsa_img;
				}
			$sx .= '</table>';
			if ($to == 0) { $sx = ''; }
			return($sx);
		}
	
	function grafico_titulacao($ano='',$bolsa='',$modalidade='')
		{
			$cps = "ap_tit_titulo, pp_ss, pp_prod";
			
			//$wh = " and (pb_tipo <> 'R' and pb_tipo <> 'X') ";
			if (strlen($bolsa) > 0) { $wh .= " and pb_tipo = '$bolsa' "; }
			if ($bolsa == 'R') { $wh = " and (pb_tipo = 'R' or pb_tipo = 'X') "; }
			if ($modalide == 'PIBICE') { $modalidade = 'PIBIC_EM'; }
			if (strlen($modalidade) > 0) { $wh .= " and pbt_edital = '$modalidade' "; }
			
			
			//$wh = " and (pb_tipo <> 'R') ";
			/* NOVO */
			$wh = '';
			if (strlen($bolsa) > 0) { $wh .= " and pb_tipo = '$bolsa' "; }
			if ($bolsa == 'R') { $wh = " and (pb_tipo = 'R' or pb_tipo = 'X') "; }
			if ($modalidade == 'PIBICE') { $modalidade = 'PIBIC_EM'; }
			if (strlen($modalidade) > 0) 
				{
					if ($modalidade == 'PIBITI')
						{
							//$wh .= " and doc_edital = '$modalidade' ";
							$wh .= " and (doc_edital = '$modalidade' or pb_vies = '1')";
						} else {
							$wh .= " and doc_edital = '$modalidade' ";
						} 
				}			
			
			$sql = "select $cps, count(*) as total from pibic_bolsa 
					inner join pibic_professor on pp_cracha = pb_professor 
					left join apoio_titulacao on ap_tit_codigo = pp_titulacao 
					inner join pibic_bolsa_tipo on pbt_codigo =  pb_tipo 
					inner join pibic_submit_documento on pb_protocolo = doc_protocolo
					where pp_ano = '$ano' $wh
					and pb_ativo = 1 
					group by $cps 
					";
			$rlt = db_query($sql);
			
			$tit = array(0,0,0);
			$pp = array(0,0);
			$ss = array(0,0);
			
			while ($line = db_read($rlt))
			{
				$titu = UpperCase(substr($line['ap_tit_titulo'],0,2));
				$prod = round($line['pp_prod']);
				$stri = trim($line['pp_ss']);
				$total = $line['total'];
				
				if ($titu == 'DR') 
					{
						$tit[0] = $tit[0] + $total; 
					}
				else { $tit[1] = $tit[1] + $total; }
				
				if ($stri == 'S') { $ss[0] = $ss[0] + $total; }
				else { $ss[1] = $ss[1] + $total; }
				
				if ($prod > 0) { $pp[0] = $pp[0] + $total; }
				else { $pp[1] = $pp[1] + $total; }								
			}	

			$sx .= '
    		<script type="text/javascript" src="https://www.google.com/jsapi"></script>
    		
    		<script type="text/javascript">
      				google.load("visualization", "1", {packages:["corechart"]});
      				google.setOnLoadCallback(drawChart);
      				function drawChart() {
        			var data = google.visualization.arrayToDataTable([
          			[\'titulação\', \'total\'],
          			[\'Doutor\','.$tit[0].'],
          			[\'Mestre\', '.$tit[1].'],
        		]);
		        var options = { title: \'Planos de Trabalho distribuidos para Mestre ou Doutor\' };
		        var chart = new google.visualization.PieChart(document.getElementById(\'chart_div_1\'));
        			chart.draw(data, options);
      		}
    		</script>

    		<script type="text/javascript">
      				google.load("visualization", "1", {packages:["corechart"]});
      				google.setOnLoadCallback(drawChart);
      				function drawChart() {
        			var data = google.visualization.arrayToDataTable([
          			[\'titulação\', \'total\'],
          			[\'stricto sensu\','.$ss[0].'],
          			[\'docentes da graduação\', '.$ss[1].'],
        		]);
		        var options = { title: \'Planos para Docentes stricto sensu / graduação\' };
		        var chart = new google.visualization.PieChart(document.getElementById(\'chart_div_2\'));
        			chart.draw(data, options);
      		}
    		</script>

    		<script type="text/javascript">
      				google.load("visualization", "1", {packages:["corechart"]});
      				google.setOnLoadCallback(drawChart);
      				function drawChart() {
        			var data = google.visualization.arrayToDataTable([
          			[\'titulação\', \'total\'],
          			[\'Produtividade (CNPq/Fundação Araucária)\','.$pp[0].'],
          			[\'Sem bolsa produtividade\', '.$pp[1].'],
        		]);
		        var options = { title: \'Planos para Professores Produtividade\' };
		        var chart = new google.visualization.PieChart(document.getElementById(\'chart_div_3\'));
        			chart.draw(data, options);
      		}
    		</script>

			';			
			$sx .= '<table>';
			$sx .= '<TR>';
			$sx .= '<TD colspan=3>';
			$sx .= '<div id="chart_div_3b" style="width: 900px; height: 400px; display: none;" ></div>';
			$sx .= '<TR>';
			$sx .= '<TD><div id="chart_div_1" style="width: 300px; height: 200px;" ></div>';
			$sx .= '<TD><div id="chart_div_2" style="width: 300px; height: 200px;" ></div>';
			$sx .= '<TD><div id="chart_div_3" style="width: 300px; height: 200px;" ></div>';
			$sx .= '</table>';
			
			return($sx);
		}

	function edital_resumo_area($ano='',$bolsa='',$modalidade='')
		{
			$ano = round($ano);
			
			//$wh = " and (pb_tipo <> 'R') ";
			if (strlen($bolsa) > 0) { $wh .= " and pb_tipo = '$bolsa' "; }
			if ($bolsa == 'R') { $wh = " and (pb_tipo = 'R' or pb_tipo = 'X') "; }
			if ($modalidade == 'PIBICE') { $modalidade = 'PIBIC_EM'; }
			if (strlen($modalidade) > 0) 
				{
					if ($modalidade == 'PIBITI')
						{
							//$wh .= " and doc_edital = '$modalidade' ";
							$wh .= " and (doc_edital = '$modalidade' or pb_vies = '1')";
						} else {
							$wh .= " and doc_edital = '$modalidade' ";
						} 
				}
			
			
			$doc = new docentes;
			$prod = $doc->produtividade();
					
			$sx .= '<h3>Distribuição de bolsas por mérito</h3>';
			
			$cps = "*";
			$sql = "select $cps from pibic_bolsa 
					inner join pibic_professor on pp_cracha = pb_professor 
					left join apoio_titulacao on ap_tit_codigo = pp_titulacao 
					inner join pibic_bolsa_tipo on pbt_codigo =  pb_tipo 
					inner join pibic_submit_documento on pb_protocolo = doc_protocolo
					where pp_ano = '$ano' $wh
					and pb_ativo = 1 
					order by doc_area, doc_nota desc, pp_centro, pp_nome_asc, pbt_edital, pbt_auxilio desc, pb_tipo 
					";
			//echo $sql;
			$rlt = db_query($sql);
			$sx .= '<table class="tabela00" width="100%">';
			$id = 0;
			$sx .= '<TR>';
			$sx .= '<TH>Protocolo submissão
					<TH>Modalidade
					<TH>Tit.
					<TH>Orientador / Docente
					<TH>Produtividade
					<TH>stricto sensu
					<TH>nota';
			$t_ss = 0;
			$t_prod = 0;
			$t_prof = 0;
			$xprof = '';
			$xcampus = 'X';
			while ($line = db_read($rlt))
			{
				//print_r($line); exit;
				$campus = ($line['pp_centro']);
				$campus = trim($line['doc_area']);
				
				if ($xcampus != $campus)
					{
						if ($campus =='A') { $campus_nome = 'Ciências Agrárias'; }
						if ($campus =='V') { $campus_nome = 'Ciências da Vida'; }
						if ($campus =='E') { $campus_nome = 'Ciências Exatas'; }
						if ($campus =='H') { $campus_nome = 'Ciências Humanas'; }
						if ($campus =='S') { $campus_nome = 'Ciências Sociais Aplicadas'; }
						$sx .= '<TR><TD colspan=5 class="lt2"><B>';
						$sx .= $campus_nome;
						$sx .= '</B>';
						$xcampus = $campus;
					}
				
				$prof = $line['pp_cracha'];
				if ($prof != $xprof) { $t_prof++; $xprof = $prof; }
				$mar = $line['doc_protocolo_mae'];
				$link = '<A HREF="parecer.php?dd0='.$mar.'&dd90='.checkpost($mar).'" target="_new" class="link" title="clique aqui para visualizar o projeto e parecer">';
				/* pb_tipo */
				$id++;
				$sx .= '<TR>';
				$sx .= '<TD class="tabela01" align="center">';
				$sx .= $link;
				$sx .= $line['pb_protocolo'];
				$sx .= '</a>';
				$sx .= '<TD class="tabela01">';
				
				if ((trim($line['pbt_edital']) == 'PIBIC') and ($modalidade == 'PIBITI'))
					{
						if ($line['doc_nota'] < 65)
							{
								$sx .= 'Reprovado';
							} else {
								$sx .= 'Qualificado para PIBITI';
							}
					} else {
						$sx .= $line['pbt_descricao'];		
					}
				
				
				$sx .= '<TD class="tabela01">';
				$sx .= $line['ap_tit_titulo'];
				
				/* Docente */
				$link = '<A HREF="docente.php?dd0='.$line['id_pp'].'">';				
				$sx .= '<TD class="tabela01">';
				$sx .= $link;
				$sx .= $line['pp_nome'];
				$sx .= '</A>';
				$sx .= '<TD class="tabela01">';
				$ppr = trim($prod[$line['pp_prod']]);
					if (strlen($ppr) > 0) { $t_prod++; }
				$sx .= $ppr;
				$sx .= '<TD class="tabela01" align="center">';
				$ss = trim($line['pp_ss']);
				if ($ss=='S') { $sx .= 'SIM'; $t_ss++; } else {$sx .= '-';  }
				$sx .= '<TD class="tabela01" align="center"><nobr>';
				$sx .= ($line['doc_nota'] - $line['doc_bonificacao']);
				$sx .= '+'.$line['doc_bonificacao'];
				$sx .= '-'.$line['doc_penalidade'];
				
				$sx .= '<TD class="tabela01" align="center">';
				
				if ($line['pb_ativacao'] > 20000101)
					{
						$dt = $line['pb_ativacao'];
						$sx .= 'ativado';
					}
				
				
				$lattes = trim($line['pp_lattes']);
				$ln = $line;
			}
			/* Total de professores */
			$sql = "select count(*) as total from (
					select pp_cracha from pibic_bolsa 
					inner join pibic_professor on pp_cracha = pb_professor 
					left join apoio_titulacao on ap_tit_codigo = pp_titulacao 
					inner join pibic_bolsa_tipo on pbt_codigo =  pb_tipo 
					inner join pibic_submit_documento on pb_protocolo = doc_protocolo
					where pp_ano = '$ano' $wh
					and pb_ativo = 1 
					group by pp_cracha ) as tabela
					";			
			$rlt = db_query($sql);
			$line = db_read($rlt);
			$t_prof = $line['total'];
			
			/* Total de reprovados */
			$t_repro = $this->planos_reprovados($wh,$ano);	
			$t_repro_prof = $this->planos_reprovados_professor($wh,$ano);
			$t_repro_SS = $this->planos_reprovados_ss($wh,$ano);	
			$t_repro_prod = $this->planos_reprovados_professor_prod($wh,$ano);
			
			$sx .= '<TR><TD colspan=5>'.$id.' planos de trabalho foram distribuídos para '.$t_prof.' professores.';
			$sx .= '<BR>'.$t_ss.' planos de trabalhos distribuídos para docentes do stricto sensu.';
			$sx .= '<BR>'.$t_prod.' distribuídos para bolsista PQ ou DT.';
			$sx .= '<BR>'.$t_repro.' planos de trabalho foram reprovados, estando distribuídos entre '.$t_repro_prof.' professores.';
			$sx .= '<BR>'.$t_repro_SS.' plano de trabalhos reprovado submetido por docente do stricto sensu.';
			$sx .= '<BR>'.$t_repro_prod.' planos de trabalho reprovado distribuídos para bolsista PQ ou DT.';
			//$sx .= '<TR><TD colspan=10>total 1.'.$id.' planos de aluno distribuidas para 2.'.$t_prof.' docentes, ';
			//$sx .= ' destes 3.'.$t_ss.' planos trabalho distribuidos para de professores do stricto sensu e 4.'.$t_prod.' com bolsas produtividade.';
			$sx .= '</table>';
			
			return($sx);
		}	

	function planos_reprovados($wh,$ano)
		{
			$sql = "select count(*) as total from (
					select pp_cracha, doc_protocolo from pibic_bolsa 
					inner join pibic_professor on pp_cracha = pb_professor 
					left join apoio_titulacao on ap_tit_codigo = pp_titulacao 
					inner join pibic_bolsa_tipo on pbt_codigo =  pb_tipo 
					inner join pibic_submit_documento on pb_protocolo = doc_protocolo
					where pp_ano = '$ano' $wh
					and pb_ativo = 1 and doc_nota < 65
					group by pp_cracha, doc_nota, doc_protocolo ) as tabela
					";			
			$rlt = db_query($sql);
			$line = db_read($rlt);
			$total = $line['total'];
			return($total);
		}
	function planos_reprovados_professor($wh,$ano)
		{
			$sql = "select count(*) as total from (
					select pp_cracha from pibic_bolsa 
					inner join pibic_professor on pp_cracha = pb_professor 
					left join apoio_titulacao on ap_tit_codigo = pp_titulacao 
					inner join pibic_bolsa_tipo on pbt_codigo =  pb_tipo 
					inner join pibic_submit_documento on pb_protocolo = doc_protocolo
					where pp_ano = '$ano' $wh
					and pb_ativo = 1 and doc_nota < 65
					group by pp_cracha, doc_nota ) as tabela
					";			
			$rlt = db_query($sql);
			$line = db_read($rlt);
			$total = $line['total'];
			return($total);
		}
	function planos_reprovados_ss($wh,$ano)
		{
			$sql = "select count(*) as total from (
					select pp_cracha from pibic_bolsa 
					inner join pibic_professor on pp_cracha = pb_professor 
					left join apoio_titulacao on ap_tit_codigo = pp_titulacao 
					inner join pibic_bolsa_tipo on pbt_codigo =  pb_tipo 
					inner join pibic_submit_documento on pb_protocolo = doc_protocolo
					where pp_ano = '$ano' $wh and pp_ss = 'S'
					and pb_ativo = 1 and doc_nota < 65
					group by pp_cracha, doc_nota ) as tabela
					";			
			$rlt = db_query($sql);
			$line = db_read($rlt);
			$total = $line['total'];
			return($total);
		}
	function planos_reprovados_professor_prod($wh,$ano)
		{
			$sql = "select count(*) as total from (
					select pp_cracha from pibic_bolsa 
					inner join pibic_professor on pp_cracha = pb_professor 
					left join apoio_titulacao on ap_tit_codigo = pp_titulacao 
					inner join pibic_bolsa_tipo on pbt_codigo =  pb_tipo 
					inner join pibic_submit_documento on pb_protocolo = doc_protocolo
					where pp_ano = '$ano' $wh and pp_ss = 'S'
					and pb_ativo = 1 and doc_nota < 65 and pp_prod > 0
					group by pp_cracha, doc_nota ) as tabela
					";			
			$rlt = db_query($sql);
			$line = db_read($rlt);
			$total = $line['total'];
			return($total);
		}				
	function edital_resumo($ano='',$bolsa='',$modalidade='')
		{
			$ano = round($ano);
			
			$wh = " and (pb_tipo <> 'R' and pb_tipo <> 'X') ";
			if (strlen($bolsa) > 0) { $wh .= " and pb_tipo = '$bolsa' "; }
			if ($bolsa == 'R') { $wh = " and (pb_tipo = 'R' or pb_tipo = 'X') "; }
			if (strlen($modalidade) > 0) { $wh .= " and pbt_edital = '$modalidade' "; }
			
			
			$doc = new docentes;
			$prod = $doc->produtividade();
					
			$sx .= '<h3>Distribuição de bolsas por mérito</h3>';
			
			$cps = "*";
			$sql = "select $cps from pibic_bolsa 
					inner join pibic_professor on pp_cracha = pb_professor 
					left join apoio_titulacao on ap_tit_codigo = pp_titulacao 
					inner join pibic_bolsa_tipo on pbt_codigo =  pb_tipo 
					inner join pibic_submit_documento on pb_protocolo = doc_protocolo
					where pp_ano = '$ano' $wh
					and pb_ativo = 1 
					order by pp_centro, pp_nome_asc, pbt_edital, pbt_auxilio desc, pb_tipo 
					";
			
			$rlt = db_query($sql);
			$sx .= '<table class="tabela00" width="100%">';
			$id = 0;
			$sx .= '<TR>';
			$sx .= '<TH>Protocolo submissão
					<TH>Modalidade
					<TH>Tit.
					<TH>Orientador / Docente
					<TH>Produtividade
					<TH>stricto sensu';
			$t_ss = 0;
			$t_prod = 0;
			$t_prof = 0;
			$xprof = '';
			$xcampus = 'X';
			while ($line = db_read($rlt))
			{
				$campus = ($line['pp_centro']);
				
				if ($xcampus != $campus)
					{
						$sx .= '<TR><TD colspan=5 class="lt2"><B>';
						$sx .= $campus;
						$sx .= '</B>';
						$xcampus = $campus;
					}
				
				$prof = $line['pp_cracha'];
				if ($prof != $xprof) { $t_prof++; $xprof = $prof; }
				$mar = $line['doc_protocolo_mae'];
				$link = '<A HREF="parecer.php?dd0='.$mar.'&dd90='.checkpost($mar).'" target="_new" class="link" title="clique aqui para visualizar o projeto e parecer">';
				/* pb_tipo */
				$id++;
				$sx .= '<TR>';
				$sx .= '<TD class="tabela01" align="center">';
				$sx .= $link;
				$sx .= $line['pb_protocolo'];
				$sx .= '</a>';
				$sx .= '<TD class="tabela01">';
				$sx .= $line['pbt_descricao'];
				$sx .= '<TD class="tabela01">';
				$sx .= $line['ap_tit_titulo'];
				
				/* Docente */
				$link = '<A HREF="docente.php?dd0='.$line['id_pp'].'">';				
				$sx .= '<TD class="tabela01">';
				$sx .= $link;
				$sx .= $line['pp_nome'];
				$sx .= '</A>';
				$sx .= '<TD class="tabela01">';
				$ppr = trim($prod[$line['pp_prod']]);
					if (strlen($ppr) > 0) { $t_prod++; }
				$sx .= $ppr;
				$sx .= '<TD class="tabela01" align="center">';
				$ss = trim($line['pp_ss']);
				if ($ss=='S') { $sx .= 'SIM'; $t_ss++; } else {$sx .= '-';  }
				$sx .= '<TD class="tabela01" align="center">';
				
				if ($line['pb_ativacao'] > 20000101)
					{
						$dt = $line['pb_ativacao'];
						$sx .= 'ativado';
					}
				
				
				$lattes = trim($line['pp_lattes']);
				$ln = $line;
			}
			$sx .= '<TR><TD colspan=10>total '.$id.' planos de aluno distribuidas para '.$t_prof.' docentes, destes '.$t_ss.' de stricto sensu e '.$t_prod.' com bolsas produtividade.';
			$sx .= '</table>';
			
			return($sx);
		}	

	function edital_resumo_professor($ano='',$professor,$edital='')
		{
			$ano = round($ano);
			
			$doc = new docentes;
			$prod = $doc->produtividade();
			if (strlen($edital) > 0)
				{
					$wh = " and pbt_edital = '$edital' ";
				}					
						
			$cps = "*";
			$sql = "select $cps from pibic_bolsa 
					inner join pibic_professor on pp_cracha = pb_professor 
					left join apoio_titulacao on ap_tit_codigo = pp_titulacao 
					inner join pibic_bolsa_tipo on pbt_codigo =  pb_tipo 
					inner join pibic_submit_documento on pb_protocolo = doc_protocolo
					where pp_cracha = '$professor' and pp_ano = '$ano' $wh
					and pb_ativo = 1 
					order by pp_centro, pp_nome, pbt_edital, pbt_auxilio desc, pb_tipo 
					";
			//echo $sql;
			$rlt = db_query($sql);
			$sx .= '<table class="tabela00" width="100%">';
			$id = 0;
			$sx .= '<TR>';
			$sx .= '<TH>Protocolo submissão
					<TH>Modalidade
					<TH>Tit.
					<TH>Orientador / Docente
					<TH>Produtividade
					<TH>stricto sensu';
			$t_ss = 0;
			$t_prod = 0;
			$t_prof = 0;
			$xprof = '';
			
			while ($line = db_read($rlt))
			{

				$prof = $line['pp_cracha'];
				if ($prof != $xprof) { $t_prof++; $xprof = $prof; }
				$mar = $line['doc_protocolo_mae'];
				$link = '<A HREF="parecer.php?dd0='.$mar.'&dd90='.checkpost($mar).'" target="_new" class="link" title="clique aqui para visualizar o projeto e parecer">';
				/* pb_tipo */
				$id++;
				$sx .= '<TR>';
				$sx .= '<TD class="tabela01" align="center">';
				$sx .= $link;
				$sx .= $line['pb_protocolo'];
				$sx .= '</a>';
				$sx .= '<TD class="tabela01">';
				$sx .= $line['pbt_descricao'];
				$sx .= '<TD class="tabela01">';
				$sx .= $line['ap_tit_titulo'];
				$sx .= '<TD class="tabela01">';
				$sx .= $line['pp_nome'];
				$sx .= '<TD class="tabela01">';
				$ppr = trim($prod[$line['pp_prod']]);
					if (strlen($ppr) > 0) { $t_prod++; }
				$sx .= $ppr;
				$sx .= '<TD class="tabela01" align="center">';
				$ss = trim($line['pp_ss']);
				if ($ss=='S') { $sx .= 'SIM'; $t_ss++; } else {$sx .= '-';  }
				$sx .= '<TD class="tabela01">';
				
				$lattes = trim($line['pp_lattes']);
				$ln = $line;
			}
			if ($id==0)
				{
					$sx .= '<TR><TD colspan=10>nenhum projeto disponível';
				}	
			$sx .= '</table>';
			$sx .= '<BR>';
			
			return($sx);
		}	

	}
?>
