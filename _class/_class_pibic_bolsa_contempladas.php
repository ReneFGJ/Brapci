<?php

    /**
     * Caixa Central
	 * @author Rene Faustino Gabriel Junior <monitoramento@sisdoc.com.br>
	 * @copyright Copyright (c) 2013 - sisDOC.com.br
	 * @access public
     * @version v0.14.06
	 * @package Bolsas PIBIC Contempladas
	 * @subpackage classe
    */
class pibic_bolsa_contempladas
	{
		var $id_pb;
		var $pb_ano;
		var $pb_edital;
		var $pb_professor;
		var $pb_professor_nome;
		var $pb_professor_centro;
		var $pb_prof_email_1;
		var $pb_prof_email_2;
		
		var $pb_est_nome;
		var $pb_est_curso;
		var $pb_aluno_email1;
		var $pb_aluno_email2;
		
		var $pb_data_ativacao;
		var $pb_bolsa;
		var $pb_bolsa_nome;
		var $pb_programa;
		
		var $pb_status;
		var $pb_status_nome;
			
		var $pb_protocolo;
		var $pb_protocolo_mae;
		
		var $pb_titulo;
		var $pb_titulo_en;
		
		var $pb_idioma_resumo;
		var $pb_area;
		var $pb_area_esp;
		
		var $pb_rp_data;
		var $pb_rp_nota;

		var $pb_rp_data_reenvio;
		var $pb_rp_nota_2;

		var $pb_rf_data;
		var $pb_rf_data_reenvio;
		var $pb_rf_status;
		
		var $pb_rs_data;
		var $pb_rs_data_reenvio;
		var $pb_rs_status;
		
		var $semic_valida;
		var $semic_valida_status;
		
		var $pb_semic_idioma;
		var $pb_semic_area;
		var $pb_semic_area_descricao;
		var $pb_semic_curso_descricao;
		
		var $resumo;
		var $keywords;
		
		var $tabela = 'pibic_bolsa_contempladas';
		var $tabela_ged = 'pibic_ged_documento';
		var $tipo = 'RELAP';
		var $pg_valida = 'pa_relatorio_parcial_ajax.php';
		var $autores_semic;
		
		function atualiza_publicacao($proto,$rs,$txt)
			{		
				if (strlen($proto) > 0)
				
				{
				$sql = "update ".$this->tabela." set 
					pb_publicacao = '$rs',
					pb_publicacao_desc = '$txt'
				where pb_protocolo = '$proto'
				";
				$rlt = db_query($sql);
				}
			}
		
		function recupera_ano_ativo()
			{
				$mes = date("m");
				if ($mes <= 7)
					{
						$ano = (date("Y")-1);
					} else {
						$ano = (date("Y"));
					}
				return($ano);
			}
		
		function guia_estudante($ano1,$ano2)
			{
				$sql = "select * from ".$this->tabela." 
						inner join pibic_professor on pb_professor = pp_cracha
						inner join pibic_aluno on pb_aluno = pa_cracha
						inner join pibic_bolsa_tipo on pbt_codigo = pb_tipo
						left join apoio_titulacao on ap_tit_codigo = pp_titulacao
						left join centro on centro_codigo = pp_escola
						left join ajax_areadoconhecimento on pb_semic_area = a_cnpq
						where pb_ano = '$ano1' or pb_ano = '$ano2' 
						order by centro_nome, pp_curso, pp_nome
						";
				$rlt = db_query($sql);
				
				echo '<H1>Guia do Estudante '.$ano1.' - '.$ano2.'<h2>';
				$sx = '<table>';
				$sx .= '<TR><TH>Escola
							<TH>Curso
							<TH>Plano
							<TH>Ano
							<TH>Professor
							<TH>Campus
							<TH>Edital
							<TH>Modalidade
							<TH>Estudande
							<TH>??
							<TH>Curso
							<TH>??
							<TH>??
							<TH>Status
							<TH>email
							<TH>email alternativo
							<TH>Titulação
							<TH>SS
							<TH>e-mail (est.)
							<TH>e-mail alt (est.)
							<TH>área CNPq
							<TH>CNPq Descrição						
							';
				$tot = 0;
				while ($line = db_read($rlt))
					{
						$tot++;
						$sx .= '<TR>';
						$sx .= '<TD>';
						$sx .= $line['centro_nome'];
						
						$sx .= '<TD>';
						$sx .= $line['pp_curso'];
						
						$sx .= '<TD>';
						$sx .= $line['pb_protocolo'];

						$sx .= '<TD>';
						$sx .= $line['pb_ano'];

						$sx .= '<TD>';
						$sx .= $line['pp_nome'];
						
						$sx .= '<TD>';
						$sx .= $line['pp_centro'];
						
						$sx .= '<TD>';
						$sx .= $line['pbt_edital'];

						$sx .= '<TD>';
						$sx .= $line['pbt_descricao'];
												
						//$sx .= '<TD>';
						//$sx .= $line['pa_codigo'];
																								
						$sx .= '<TD>';
						$sx .= $line['pa_nome'];
																														$sx .= '<TD>';
						$sx .= '<TD>';
						$sx .= $line['pa_curso'];

						$sx .= '<TD>';
						$sx .= $line['pb_colegio'];
												
						$sx .= '<TD>';
						$sx .= $line['pb_colegio_orientador'];

						$sx .= '<TD>';
						$sx .= $line['pb_status'];

						$sx .= '<TD>';
						$sx .= $line['pp_email'];

						$sx .= '<TD>';
						$sx .= $line['pp_email_1'];

						$sx .= '<TD>';
						$sx .= $line['ap_tit_titulo'];

						$sx .= '<TD>';
						$sx .= $line['pp_ss'];
						
						$sx .= '<TD>';
						$sx .= $line['pa_email'];

						$sx .= '<TD>';
						$sx .= $line['pa_email_1'];		
										
						$sx .= '<TD><nobr>';
						$sx .= $line['a_cnpq'];
						$sx .= '</nobr>';	
						$sx .= '<TD><nobr>';
						$sx .= $line['a_descricao'];
						$sx .= '</nobr>';																	
					}
				$sx .= '</table>';
				$sx .= $tot.' total';
				return($sx);
				
			}
		
		function relatorio_parcial_nao_entregue($ano='',$tipo='IC',$tipo='RPAR')
			{
				global $ic,$dd,$http;
				
				$fld = 'pb_relatorio_parcial';
				if ($tipo == 'RPAC') { $fld = 'pb_relatorio_parcial_nota = 2 and pb_relatorio_parcial_correcao'; }
				
				if (isset($ic))
					{ $email_enviar = 1; } else { $email_enviar = 0; }
				$wh = " and (pbt_edital = 'PIBIC' or pbt_edital = 'PIBITI' or pbt_edital = 'IS')";
				$sql .= "select *
							from ".$this->tabela." 
							inner join pibic_bolsa_tipo on pbt_codigo = pb_tipo
							inner join pibic_aluno on pb_aluno = pa_cracha 
							inner join pibic_professor on pb_professor = pp_cracha
							left join apoio_titulacao on ap_tit_codigo = pp_titulacao
							where (pb_status <> 'C' and pb_ano = '".$ano."' and $fld < 20000101) 
							$wh
							order by pp_nome
							";
				$rlt = db_query($sql);
				$sx = '<table width="100%" class="tabela00">';
				$id = 0;
				$xprof = '';
				while ($line = db_read($rlt))
				{					
					$prof = trim($line['pp_nome']);
					if ($prof != $xprof)
						{
							$xprof = $prof;
							$sx .= '<TR><TD colspan=10 class="lt3">'.$prof;
						}
					$id++;
					$sx .= $this->mostra_simples($line);
					
					$email1 = trim($line['pp_email']);
					$email2 = trim($line['pp_email_1']);
					$email3 = '';
					if (strlen($email1.$email2)==0) { $email3 = '<font color="red">SEM E-MAIL INFORMADO</font>'; }
					$sx .= '<TR><TD><TD>'.$email3;
					
					if ($email_enviar==1)
						{
						if ($ic->enviar_email==1)
							{
							$titulo_projeto = trim($line['pb_titulo_projeto']);
							$titulo_projeto = troca($titulo_projeto,chr(13),' ');
							$aluno = trim($line['pa_nome']);
							$professor = trim($line['pp_nome']);
							$protocolo = trim($line['pb_protocolo']);

							$sx .= '<TR><TD><TD>enviado e-mail para '.$email1.' '.$email2;
							
							$texto = $ic->texto;
							$titulo = $ic->titulo;
							
							$texto = troca($texto,'$TITULO',$titulo_projeto);
							$texto = troca($texto,'$ALUNO',$aluno);
							$texto = troca($texto,'$PROTOCOLO',$protocolo);
							$texto = troca($texto,'$PROFESSOR',$professor);
							$texto = mst($texto);
							$texto = '<IMG SRC="'.$http.'img/email_ic_header.png"><BR><BR>'.$texto.'<BR><BR><BR><img src="'.$http.'img/email_ic_foot.png">';
							
							if (1==1)
								{
								$email = 'pibicpr@pucpr.br';
								enviaremail($email,'',$titulo.' '.$protocolo,$texto.' <BR><BR>Copia para '.$email1.' '.$email2);
	
								$email = $email1;
								if (strlen($email) > 0) { enviaremail($email,'',$titulo.' '.$protocolo,$texto); }
								
								$email = $email2;
								if (strlen($email) > 0) { enviaremail($email,'',$titulo.' '.$protocolo,$texto); }
								}
							}
						}
					
				}
				$sx .= '<tr><td colspan=5><I>Total de '.$id.' projetos</I></td></tr>';
				$sx .= '</table>';
				return($sx);
			}
		function relatorio_bolsas_suspensas($ano='',$tipo='IC')
			{
				$wh = " and (pbt_edital = 'PIBIC' or pbt_edital = 'PIBITI' or pbt_edital = 'IS')";
				$sql .= "select *
							from ".$this->tabela." 
							inner join pibic_bolsa_tipo on pbt_codigo = pb_tipo
							inner join pibic_aluno on pb_aluno = pa_cracha 
							inner join pibic_professor on pb_professor = pp_cracha
							left join apoio_titulacao on ap_tit_codigo = pp_titulacao
							where (pb_status = 'S' and pb_ano = '".$ano."') 
							$wh
							order by pp_nome
							";
				$rlt = db_query($sql);
				$sx = '<h1>Relatório Parcial nóo Entregue</h1>';
				$sx . '<table width="100%" class="tabela00">';
				$id = 0;
				$xprof = '';
				while ($line = db_read($rlt))
				{
					$prof = trim($line['pp_nome']);
					if ($prof != $xprof)
						{
							$xprof = $prof;
							$sx .= '<TR><TD colspan=10 class="lt3">'.$prof;
						}
					$id++;
					$sx .= $this->mostra_simples($line);
				}
				$sx .= '<tr><td colspan=5><I>Total de '.$id.' projetos</I></td></tr>';
				$sx .= '</table>';
				return($sx);
			}		
		function docentes_demitidos_com_orientacao($ano='')
			{
				if (strlen($ano) == 0)	{ $ano = date("Y");	}
				if (date("m") < 8)
					{
						$wh = " and (pb_ano = '".(date("Y")-1)."') ";
					} else {
						$wh = " and (pb_ano = '".(date("Y"))."') ";
					}	
				$sql .= "select *
							from ".$this->tabela." 
							inner join pibic_bolsa_tipo on pbt_codigo = pb_tipo
							inner join pibic_professor on pb_professor = pp_cracha
							left join apoio_titulacao on ap_tit_codigo = pp_titulacao
							where pb_status <> 'C' and pp_update <> '".date("Y")."'";
				$sql .= $wh;
				$sql .= " 
						order by pp_nome
					";
					
				$rlt = db_query($sql);
				$id = 0;
				$xnome = '';
				$sx = '<table>';
				while ($line = db_read($rlt))
					{
						$nome = trim($line['pp_nome']);
						$id++;
						if ($nome != $xnome)
							{
								$sx .= '<TR><TD colspan=10><H3>'.$nome.'</h3>';
								$xnome = $nome;
							}
						$sx .= $this->mostra_simples($line);
					}
				$sx .= '</table>';
				return($sx);
			}
		
		function indicador_professor_orientador($ano=0,$edital='')
			{
				$sx .= '<table>';
				$sx .= '<TR>';
				$sx .= '<TD>'.$this->indicador_professor_projetos($ano,$edital);
				$sx .= '<TD>'.$this->indicador_professor_ss($ano,$edital);
				$sx .= '</table>';
				return($sx);
			}
		
		function indicador_professor_projetos($ano,$edital)
			{
				$sql = "select pp_ss, pp_titulacao, ap_tit_titulo, count(*) as total from (
						select *
						from ".$this->tabela." 
						inner join pibic_bolsa_tipo on pbt_codigo = pb_tipo
						inner join pibic_professor on pb_professor = pp_cracha
						left join apoio_titulacao on ap_tit_codigo = pp_titulacao
						where pb_status <> 'C' and pb_ano = '".$ano."'
						) as tabela 
						group by pp_ss, pp_titulacao, ap_tit_titulo
					";	
				$rlt = db_query($sql);
				$totg = 0;
				while ($line = db_read($rlt))
					{
						$ss = '';
						if ($line['pp_ss']=='S') { $ss = ' stricto sensu'; }
						$sr .= ','.chr(13);
						$sr .= "['".$line['ap_tit_titulo'].' '.$ss."', ".$line['total'].'] ';
						$st .= '<TR><TD class="tabela01">'.$line['ap_tit_titulo'].' '.$ss.'<td align="center" class="tabela01">'.$line['total'];
						$totg = $totg + $line['total'];
					}
				$sx = '
    			<script type="text/javascript">
      				google.load("visualization", "1", {packages:["corechart"]});
      				google.setOnLoadCallback(drawChart);
      				function drawChart() {
        				var data = google.visualization.arrayToDataTable([
          				[\'Titulação\', \'Professores\']
          				'.$sr.'
        			]);

        		var options = {
          			title: \'Professores orientadores por projetos\'
        			};

			    var chart = new google.visualization.PieChart(document.getElementById(\'piechart2\'));
        			chart.draw(data, options);
      			}
    		</script>
		    <div id="piechart2" style="width: 350px; height: 250px;"></div>
			';
			$sx .= '<table width="350">';
			$sx .= '<TR><TH>Titulação<TH>Projetos';
			$sx .= $st;
			$sx .= '<TR><TD><TH>'.$totg;
			$sx .= '</table>';					
			return($sx);				
			}
		
		function indicador_professor_ss($ano=0,$edital='')
			{
				//$sql= "update pibic_professor set pp_titulacao = '002' where pp_titulacao = '003' ";
				//$rlt = db_query($sql);
									
				$sql = "select pp_ss, pp_titulacao, ap_tit_titulo, count(*) as total, sum(projetos) as projetos from (
						select pp_ss, pp_titulacao, ap_tit_titulo, count(*) as projetos, pp_cracha
						from ".$this->tabela." 
						inner join pibic_bolsa_tipo on pbt_codigo = pb_tipo
						inner join pibic_professor on pb_professor = pp_cracha
						left join apoio_titulacao on ap_tit_codigo = pp_titulacao
						where pb_status <> 'C' and pb_ano = '".$ano."'
						group by pp_ss, pp_titulacao, ap_tit_titulo, pp_cracha
						) as tabela 
						group by pp_ss, pp_titulacao, ap_tit_titulo
					";	
				$rlt = db_query($sql);
				$totg = 0;
				while ($line = db_read($rlt))
					{
						$ss = '';
						if ($line['pp_ss']=='S') { $ss = ' stricto sensu'; }
						$sr .= ','.chr(13);
						$sr .= "['".$line['ap_tit_titulo'].' '.$ss."', ".$line['total'].'] ';
						$st .= '<TR><TD class="tabela01">'.$line['ap_tit_titulo'].' '.$ss.'<td align="center" class="tabela01">'.$line['total'];
						$totg = $totg + $line['total'];
					}
				$sx = '
    			<script type="text/javascript">
      				google.load("visualization", "1", {packages:["corechart"]});
      				google.setOnLoadCallback(drawChart);
      				function drawChart() {
        				var data = google.visualization.arrayToDataTable([
          				[\'Titulação\', \'Professores\']
          				'.$sr.'
        			]);

        		var options = {
          			title: \'Titulação dos professores orientadores\'
        			};

			    var chart = new google.visualization.PieChart(document.getElementById(\'piechart\'));
        			chart.draw(data, options);
      			}
    		</script>
		    <div id="piechart" style="width: 350px; height: 250px;"></div>
			';		
			$sx .= '<table width="350">';
			$sx .= '<TR><TH>Titulação<TH>Quant.';
			$sx .= $st;
			$sx .= '<TR><TD><TH>'.$totg;
			$sx .= '</table>';
			return($sx);					
				
			}
		function alunos_ativos_ic($ano='',$tipo=1)
			{
				if($tipo==1)
					{
						$wh = "and (pp_centro = 'DOUTORANDO' or pp_centro = '' or pp_centro = 'PUC CURITIBA' or pp_centro = 'PUC PR CAMPUS CURITIBA' or pp_centro = 'PUCPR CAMPUS SJP')";
						$ind = 0.34; /* Alterado em 07/03/2014 */
						$ind = 0.0662;
					}
				if($tipo==2)
					{
						$wh = "and not (pp_centro = 'DOUTORANDO' or pp_centro = '' or pp_centro = 'PUC CURITIBA' or pp_centro = 'PUC PR CAMPUS CURITIBA' or pp_centro = 'PUCPR CAMPUS SJP')";
						$ind = 0.34; /* Alterado em 07/03/2014 */
						$ind = 0.2645; 
					}
				if (strlen($ano=='')) { $ano = date("Y"); }
				$sql = "
					select * from (
					select pb_aluno, pbt_descricao, pp_centro, pb_tipo from ".$this->tabela." 
					inner join pibic_bolsa_tipo on pbt_codigo = pb_tipo
					inner join pibic_professor on pb_professor = pp_cracha 
					where pb_ano = '".$ano."' and pb_status = 'A'
					and (pb_tipo <> 'S' and pb_tipo <> 'K' and pb_tipo <> '4')
					$wh 
					group by pb_aluno, pbt_descricao, pp_centro, pb_tipo
					) as tabela 
					left join pibic_aluno on pb_aluno = pa_cracha
					where pa_nome <> ''
					order by pp_centro, pa_nome
				";
				
				$rlt = db_query($sql);
				
				$sx .= '<table>';
				$sx .= '<TR bgcolor="#000000">
						<TH>SEQ
						<th>NOME
						<TH>TIPO
						<TH>GENERO
						<TH>DATA DE<br>NASCIMENTO
						<TH>CPF<br>MATRICULA
						<th>CAPITAL<br>SEGURADO
						<th>CUSTO
						<th>MODALIDADE
						<th>CAMPUS';			
				$id = 0;
				$tot = 0;
				
				$vlr = 10000;
				$xcpf = '';
				while ($line = db_read($rlt))
				{
					$cpf = $line['pa_cpf'];
					$font = '<font color="black">';
					$rp = 0;
					$vlr2 = $vlr;
					$ind2 = $ind;
					if ($xcpf == $cpf) { $font = '<font color="red">'; $rp=1; $vlr2 = 0; $ind2 = 0;}
					else { $id++; }
					$xcpf = $cpf;
					$sx .= '<TR>';
					$sx .= '<TD align="center">';
					if ($rp==0) { $sx .= $id; } else { $sx .= '&nbsp;'; }
					$sx .= '<TD>';
					$sx .= $font;
					$sx .= UpperCase(trim($line['pa_nome']));
					$sx .= '</font>';
					$sx .= '<TD align="center">';
					$sx .= 'T';
					$sx .= '<TD align="center">';
					$sx .= trim($line['pa_genero']);
					$sx .= '<TD align="center">';
					$sx .= stodbr($line['pa_nasc']);
					$sx .= '<TD>CPF';
					$sx .= sonumero(trim($line['pa_cpf']));
					$sx .= '<TD align="right">';
					$sx .= number_format($vlr2,2,',','.');
					$sx .= '<TD align="right">';
					$sx .= number_format($ind2,4,',','.');
					$sx .= '<TD align="left">';
					//$sx .= $line['pb_tipo'].'-';
					$sx .= trim($line['pbt_descricao']);
					$sx .= '<TD align="left">';
					$centro = trim($line['pp_centro']);
					$centro = troca($centro,'PUCPR CAMPUS','');
					$centro = troca($centro,'PUC PR CAMPUS','');
					$centro = trim($centro);
					$sx .= $centro;
					$tot = $tot + $ind2;
				}
				$sx .= '<TR><TD colspan=5>Total: '.$id;
				$sx .= ', valor de desenbolso '.number_format($tot,2,',','.');
				$sx .= '</table>';
				return($sx);
			}
		
		function set($professor)
			{
				$this->pb_professor = $professor;
				return(1);
			}
		
		function lista_ic($professor='',$status='',$ano='')
			{
				$sql = "select * from ".$this->tabela."
					left join pibic_parecer_".date("Y")." on pp_protocolo = pb_protocolo and pp_tipo='RFIN' and pp_status = 'B'
					inner join pibic_aluno on pb_aluno = pa_cracha 
					inner join pibic_professor on pb_professor = pp_cracha
 					inner join pibic_bolsa_tipo on pbt_codigo = pb_tipo
 					left join ajax_areadoconhecimento on pb_semic_area = a_cnpq
 					left join semic_ic_trabalho on sm_codigo = pb_protocolo
 					left join centro on pp_escola = centro_codigo 
					where pb_professor = '".$professor."' 
						and pb_ano = '".$ano."'
						and pb_status = '".$status."'
					order by pb_ano desc, pb_status
					";										
				$rlt = db_query($sql);
				$sx .= '<h1>Orientações IC</h1>';
				$sx .= '<table width="98%" class="tabela00" align="center">';
				$id = 0;
				while ($line=db_read($rlt))
					{
						$id++;
						//print_r($line);
						//$this->le($line['pb_protocolo']);
						$sx .= $this->mostra_simples($line);
					}
				$sx .= '<TR><TD colspan=10><i>Total de '.$id.' orientação(ões)';
				$sx .= '</table>';			
				return($sx);	
			}

		function resumo($tipo='')
			{
				if (strlen($this->pb_professor) > 0)
					{ $wh = " where pb_professor = '".$this->pb_professor."'"; }
				if (strlen($tipo) > 0)
					{
						$wh = " where pbt_edital = '".$tipo."' ";
					}

				$sql = "select pb_status, pb_ano, count(*) as total from ".$this->tabela." 
							inner join pibic_bolsa_tipo on pbt_codigo = pb_tipo 
						 	$wh
							group by pb_status, pb_ano
							order by pb_ano desc, pb_status
				";
				$rlt = db_query($sql);
				$bs = array();
				$ba = array('',0,0,0,0,0,0);
				while ($line = db_read($rlt))
					{
						$ano = trim($line['pb_ano']);
						if (strlen($ano) > 0)
						{
						$total = $line['total'];
						$status = $line['pb_status'];
						$id = -1;
						for ($r=0;$r < count($bs);$r++)
							{
								if ($bs[$r][0]==$ano) { $id = $r; }
							}
						if ($id == -1)
							{
								array_push($bs,$ba);
								$id = count($bs)-1;
								$bs[$id][0] = $ano;
							}
						
						$ids = 5;
						switch ($status)
							{
							case 'A': $ids = 1; break;
							case 'F': $ids = 2; break;
							case 'C': $ids = 4; break;
							case 'S': $ids = 3; break;
							}
						$bs[$id][$ids] = $bs[$id][$ids] + $total;
						} 
					}
				$sx .= '<h1>Resumo IC - Orientações '.$tipo.'</h1>';
				$sx .= '<table class="tabela00" width="100%">';
				$sx .= '<TR><TH width="4%">ano
								<TH width="22%">Ativas
								<TH width="22%">Finalizadas
								<TH width="22%">Suspensas
								<TH width="22%">Canceladas';
				$ss = array('','A','F','S','C');
				for ($r=0;$r < count($bs);$r++)
					{
						$sx .= '<TR>
								<TD align="right">'.$bs[$r][0];
						for ($y=1;$y <= 4;$y++)
							{
								$link = '<A HREF="ic_lista.php?dd2='.$bs[$r][0].'&dd1='.$ss[$y].'" class="link">';
								$sx .= '<TD class="tabela01 lt5" align="center">&nbsp;';
								if ($bs[$r][$y] > 0) { $sx .= $link.$bs[$r][$y].'</A>'; }
								else { $sx .= '&nbsp;'; }
								$sx .= '&nbsp;';
							}
					}
				$sx .= '</table>';
				return($sx);
			}
		
		
		function mostra_projetos_escolas($escola)
			{
				$curn = array();
				$curt = array();
				$sql = "select * from ".$this->tabela."
					left join pibic_parecer_".date("Y")." on pp_protocolo = pb_protocolo and pp_tipo='RFIN' and pp_status = 'B'
					inner join pibic_aluno on pb_aluno = pa_cracha 
					inner join pibic_professor on pb_professor = pp_cracha
 					inner join pibic_bolsa_tipo on pbt_codigo = pb_tipo
 					left join ajax_areadoconhecimento on pb_semic_area = a_cnpq
 					left join semic_ic_trabalho on sm_codigo = pb_protocolo
 					left join centro on pp_escola = centro_codigo 
					where pp_status = 'B' and pb_status <> 'C'
					and centro_codigo = '$escola'
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
						if (in_array($curso,$curn) > 0)
							{
								$r = array_search($curso,$curn);
								$curt[$r] = $curt[$r] + 1;
							} else {
								array_push($curn,$curso);
								array_push($curt,1);
							}
							
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
								$sx .= '<TR><TD colspan=5><h4>'.$pro.'</h4>';
								$xpro = $pro;
							}
						$sx .= '<TR class="tabela01"><TD>';
						$sx .= $this->mostra_simples($line);							
					}
				$sx .= '<TR><TD>Total '.$tot;
				$sx .= '</table>';
				
				$sa .= '<table class="tabela00">';
				$sa .= '<TR><TD>Curso<TD>Frequóncia';
				for ($ra = 0;$ra < count($curn);$ra++)
					{
						$sa .= '<TR><TD>'.$curn[$ra].'<TD align="center">'.$curt[$ra];
					}
				$sa .= '</table>';
				return($sa.'<BR>'.$sx);
			}
		
		function best_work()
			{
				$wh = "and pp_p01 = '20' ";
				$sx = $this->show_works($wh);
				return($sx);
			}
		function work_aproved()
			{
				$wh = "and (pp_p01 = '20' or pp_p01 = '10' or pp_p01 = '5' or pp_p01 = '2') ";
				$sx = $this->show_works($wh);
				return($sx);				
			}
		function show_works($wh)
			{
				$sql = "select * from pibic_parecer_".date("Y")."
					inner join ".$this->tabela." on pp_protocolo = pb_protocolo
					inner join pibic_aluno on pb_aluno = pa_cracha 
					inner join pibic_professor on pb_professor = pp_cracha
 					inner join pibic_bolsa_tipo on pbt_codigo = pb_tipo
 					left join semic_ic_trabalho on sm_codigo = pb_protocolo
 					left join centro on pp_escola = centro_codigo 
					where pp_status = 'B' and pp_tipo = 'RFIN' and pb_status <> 'C'
					$wh
					order by centro_nome, pp_nome					
				";
				$rlt = db_query($sql);
				$sx = '<table class="tabela00">';
				$tot = 0;
				$xesc = 'X';
				$xpro = 'X';
				while ($line = db_read($rlt))
					{
						$esc = $line['centro_nome'];
						$pro = $line['pp_nome'];
						if ($xesc != $esc)
							{
								$sx .= '<TR><TD colspan=5><h1>'.$esc.'</h1>';
								$xesc = $esc;
							}
						if ($xpro != $pro)
							{
								$sx .= '<TR><TD colspan=5><h4>'.$pro.'</h4>';
								$xpro = $pro;
							}
						
						$tot++;
						$sx .= '<TR class="tabela01"><TD>';
						$sx .= $this->mostra_simples($line);
						
						$sx .= '<TR><TD><TD class="lt0">';
						$sx .= $line['sm_rem_01'].'. ';
						$sx .= $line['sm_rem_02'].'. ';
						$sx .= $line['sm_rem_03'].'. ';
						$sx .= $line['sm_rem_04'].'. ';
						$sx .= $line['sm_rem_05'].'. ';
						//$sx .= $line['sm_rem_01'].'. ';
					}
				$sx .= '<TR><TD>Total '.$tot;
				$sx .= '</table>';
				return($sx);
			}


		function resumo_bolsas_escolas_detalhado($ano=2013,$modalidade='PIBIC',$tipo='1')
			{
			$cp = 'pbt_edital, pbt_descricao, pb_ano, centro_nome';
			$sql = "select * from pibic_bolsa_contempladas
				inner join pibic_professor on pb_professor = pp_cracha
				inner join pibic_aluno on pb_aluno = pa_cracha 
 				inner join pibic_bolsa_tipo on pbt_codigo = pb_tipo
 				left join centro on pp_escola = centro_codigo
 				where pb_status <> 'C' and pb_ano = '".$ano."' 
 				and pbt_edital = '$modalidade'
 				"; 
 			$sql .= " order by centro_nome, pbt_edital desc, pp_nome, pbt_descricao, pb_ano desc ";
			$rlt = db_query($sql);
			
			$sx .= '<table class="tabela00" width="100%" align="center">';
			$sx .= '<TR><TH>Edital<TH>Modalidade<TH>Total';

			$tot = 0;
			$ano = 0;
			$xmod = 'x';
			$xcentro = 'x';
			$totx = 0;
			while ($line = db_read($rlt))				
				{
					$centro = $line['centro_nome'];
					$mod = trim($line['pbt_descricao']);
					
					if ($xcentro != $centro)
						{
							if ($totx > 0)
								{ $sx .= '<TR><TD colspan=10 align="right"><I>Total parcial '.$totx.'</I>'; $totx = 0; }
							$sx .= '<TR><TD colspan=10><span class="lt4">'.$centro.'</span>';
							$xcentro = $centro;
							$sx .= '<TR><TH>ed<TH>Modalidade<TH>Orientador<TH>Estudante<TH>Protocolo';
						}
					
						$sx .= '<TR>';
						$sx .= '<TD class="tabela01" align="left">'.$line['pbt_edital'];
						$sx .= '<TD class="tabela01" align="left">'.$line['pbt_descricao'];
						
						if ($tipo=='1')
							{
							$sx .= '<TD class="tabela01" align="left">'.$line['pp_nome'];
							$sx .= ' ('.trim($line['pp_cracha']).')';
							$sx .= '<TD class="tabela01" align="left">'.$line['pa_nome'];
							$sx .= ' ('.trim($line['pa_cracha']).')';
							$sx .= '<TD class="tabela01" align="center">'.$line['pb_protocolo'];
							}
						if ($tipo=='2')
							{
							$sx .= '<TD class="tabela01" align="left">'.$line['pp_nome'];
							$sx .= ' ('.trim($line['pp_cracha']).')';
							$sx .= '<TD class="tabela01" align="left">'.$line['pp_email'];
							$email = trim($line['pp_email_1']);
							if (strlen($email) > 0) { $sx .= '; '.$email; }
							$sx .= '<TD class="tabela01" align="center">'.$line['pb_protocolo'];
							}
						if ($tipo=='3')
							{
							$sx .= '<TD class="tabela01" align="left">'.$line['pa_nome'];
							$sx .= ' ('.trim($line['pa_cracha']).')';
							$sx .= '<TD class="tabela01" align="left">'.$line['pa_email'];
							$email = trim($line['pa_email_1']);
							if (strlen($email) > 0) { $sx .= '; '.$email; }
							$sx .= '<TD class="tabela01" align="center">'.$line['pb_protocolo'];
							}
						$xmod = $mod;
						
					$tot = $tot + 1;
					$totx = $totx + 1;
				}
			if ($totx > 0)
				{ $sx .= '<TR><TD colspan=10 align="right"><I>Total parcial '.$totx.'</I>'; $totx = 0; }
			if ($tot > 0)
				{ $sx .= '<TR><TD colspan=10 align="right"><B><I>Total geral '.$tot.'</I></B>'; }
				
			$sx .= '</table>';
			echo $sx;
			}			
		
		function resumo_bolsas_escolas($ano=2013,$modalidade='PIBIC')
			{
			$cp = 'pbt_edital, pbt_descricao, pb_ano, centro_nome';
			$sql = "select count(*) as total, $cp from pibic_bolsa_contempladas
				inner join pibic_professor on pb_professor = pp_cracha 
 				inner join pibic_bolsa_tipo on pbt_codigo = pb_tipo
 				left join centro on pp_escola = centro_codigo
 				where pb_status <> 'C' and pb_ano = '".$ano."' 
 				and pbt_edital = '$modalidade'
 				group by $cp"; 
 			$sql .= " order by centro_nome, pbt_edital desc, pbt_descricao, pb_ano desc ";
			$rlt = db_query($sql);
			
			$sx .= '<table class="tabela00" width="100%" align="center">';
			$sx .= '<TR><TH>Edital<TH>Modalidade<TH>Total';

			$tot = 0;
			$ano = 0;
			$xmod = 'x';
			$xcentro = 'x';
			$totx = 0;
			$sv = '';
			while ($line = db_read($rlt))				
				{
					$centro = $line['centro_nome'];
					$mod = trim($line['pbt_descricao']);
					
					if ($xcentro != $centro)
						{
							if ($totx > 0)
								{
									$sx .= '<TR><TD colspan=10 align="right"><I>Total parcial '.$totx.'</I>'; 
									$sv .= ','.chr(13).'[ \''.trim($escola).'\', '.$totx.']';
									$totx = 0;
								}
							$sx .= '<TR><TD colspan=10><span class="lt4">'.$centro.'</span>';
							$xcentro = $centro;
						}
					
					if ($mod != $xmod)
						{						
							$sx .= '<TR>';
							$sx .= '<TD class="tabela01" align="left">'.$line['pbt_edital'];
							$sx .= '<TD class="tabela01" align="left">'.$line['pbt_descricao'];
							$xmod = $mod;
							$ano = date("Y");
						}
					$sx .= '<TD class="tabela01" align="center">'.$line['total'];
					$tot = $tot + $line['total'];
					$totx = $totx + $line['total'];
					$escola = $line['centro_nome'];
				}
				if ($totx > 0)
					{
						$sx .= '<TR><TD colspan=10 align="right"><I>Total parcial '.$totx.'</I>'; 
						$sv .= ','.chr(13).'[ \''.trim($escola).'\', '.$totx.']';
						$totx = 0;
					}			
				if ($tot > 0)
				{ $sx .= '<TR><TD colspan=10 align="right"><B><I>Total geral '.$tot.'</I></B>'; }
				
			$sx .= '</table>';
			
			$sg = '
			<script type="text/javascript" src="https://www.google.com/jsapi"></script>
    		<script type="text/javascript">
      			google.load("visualization", "1", {packages:["corechart"]});
      			google.setOnLoadCallback(drawChart);
      			function drawChart() {
        		var data = google.visualization.arrayToDataTable([
          		[\'Escolas\', \'Bolsas\']
          		'.$sv.'
        		]);
		
	        var options = {
    	      	title: \'Implementação de Bolsas\',
          		hAxis: {title: \'Escola\', titleTextStyle: {color: \'red\'}}
        		};
	
	        	var chart = new google.visualization.ColumnChart(document.getElementById(\'chart_div_01\'));
        		chart.draw(data, options);
      		}
    		</script>
    		<div id="chart_div_01" style="width: 900px; height: 500px;"></div>
			';			
			echo $sg.$sx;
			}		

	function resumo_bolsas_campi($ano=2013,$modalidade='PIBIC')
			{
			$cp = 'pbt_edital, pbt_descricao, pb_ano, pp_centro';
			$sql = "select count(*) as total, $cp from pibic_bolsa_contempladas
				inner join pibic_professor on pb_professor = pp_cracha 
 				inner join pibic_bolsa_tipo on pbt_codigo = pb_tipo
 				left join centro on pp_escola = centro_codigo
 				where pb_status <> 'C' and pb_ano = '".$ano."' 
 				and pbt_edital = '$modalidade'
 				group by $cp"; 
 			$sql .= " order by pp_centro, pbt_edital desc, pbt_descricao, pb_ano desc ";
			$rlt = db_query($sql);
			
			$sx .= '<table class="tabela00" width="100%" align="center">';
			$sx .= '<TR><TH>Edital<TH>Modalidade<TH>Total';

			$tot = 0;
			$ano = 0;
			$xmod = 'x';
			$xcentro = 'x';
			$totx = 0;
			$sv = '';
			while ($line = db_read($rlt))				
				{
					$centro = $line['pp_centro'];
					$mod = trim($line['pbt_descricao']);
					
					if ($xcentro != $centro)
						{
							if ($totx > 0)
								{
									$sx .= '<TR><TD colspan=10 align="right"><I>Total parcial '.$totx.'</I>'; 
									$sv .= ','.chr(13).'[ \''.trim($escola).'\', '.$totx.']';
									$totx = 0;
								}
							$sx .= '<TR><TD colspan=10><span class="lt4">'.$centro.'</span>';
							$xcentro = $centro;
						}
					
					if ($mod != $xmod)
						{						
							$sx .= '<TR>';
							$sx .= '<TD class="tabela01" align="left">'.$line['pbt_edital'];
							$sx .= '<TD class="tabela01" align="left">'.$line['pbt_descricao'];
							$xmod = $mod;
							$ano = date("Y");
						}
					$sx .= '<TD class="tabela01" align="center">'.$line['total'];
					$tot = $tot + $line['total'];
					$totx = $totx + $line['total'];
					$escola = $line['pp_centro'];
				}
				if ($totx > 0)
					{
						$sx .= '<TR><TD colspan=10 align="right"><I>Total parcial '.$totx.'</I>'; 
						$sv .= ','.chr(13).'[ \''.trim($escola).'\', '.$totx.']';
						$totx = 0;
					}			
				if ($tot > 0)
				{ $sx .= '<TR><TD colspan=10 align="right"><B><I>Total geral '.$tot.'</I></B>'; }
				
			$sx .= '</table>';
			
			$sg = '
			<script type="text/javascript" src="https://www.google.com/jsapi"></script>
    		<script type="text/javascript">
      			google.load("visualization", "1", {packages:["corechart"]});
      			google.setOnLoadCallback(drawChart);
      			function drawChart() {
        		var data = google.visualization.arrayToDataTable([
          		[\'Escolas\', \'Bolsas\']
          		'.$sv.'
        		]);
		
	        var options = {
    	      	title: \'Implementação de Bolsas\',
          		hAxis: {title: \'Escola\', titleTextStyle: {color: \'red\'}}
        		};
	
	        	var chart = new google.visualization.ColumnChart(document.getElementById(\'chart_div_01\'));
        		chart.draw(data, options);
      		}
    		</script>
    		<div id="chart_div_01" style="width: 900px; height: 500px;"></div>
			';			
			echo $sg.$sx;
			}
		
		function resumo_bolsas($ano=2013)
			{
			$cp = 'pbt_edital, pbt_descricao, pb_ano';
			$sql = "select count(*) as total, $cp from pibic_bolsa_contempladas 
 				inner join pibic_bolsa_tipo on pbt_codigo = pb_tipo
 				where pb_status <> 'X' 
 				group by $cp 
 				order by pbt_edital desc, pbt_descricao, pb_ano desc
 				 ";
			$rlt = db_query($sql);
			
			$sx .= '<table class="tabela00" width="600" align="center">';
			$sx .= '<TR><TH>Edital<TH>Modalidade';
			for ($r=date("Y");$r >=2008;$r--)
				{ $sx .= '<TH align="center">'.$r; }
			$tot = 0;
			$ano = 0;
			$xmod = 'x';
			while ($line = db_read($rlt))				
				{
					$mod = $line['pbt_descricao'];
					if ($mod != $xmod)
						{
							$sx .= '<TR>';
							$sx .= '<TD class="tabela01" align="left">'.$line['pbt_edital'];
							$sx .= '<TD class="tabela01" align="left">'.$line['pbt_descricao'];
							$xmod = $mod;
							$ano = date("Y");
						}
					$sx .= '<TD class="tabela01" align="center">'.$line['total'];
					$tot = $tot + $line['total'];
				}
			$sx .= '</table>';
			echo $sx;
			}

		function relatorio_nao_entregue($edital,$modalidade,$ano='')
		{
			$sql = "select * from pibic_bolsa_contempladas 
			 	inner join pibic_professor on pp_cracha = pb_professor 
 				inner join pibic_aluno on pa_cracha = pb_aluno 
 				inner join pibic_bolsa_tipo on pbt_codigo = pb_tipo
 				where pb_ano = '".$ano."' and
 					(pbt_edital = 'IS' or pbt_edital = 'PIBIC' or pbt_edital = 'PIBITI')
 				 ";

			//$sql .= " and pbt_edital = '".$edital."' ";
			$sql .= " and pb_relatorio_final < 20100101 ";
			$sql .= " and pb_status <> 'C' ";
			if (strlen($modalidade) > 0)
				{ $sql .= " and pb_tipo = '".$modalidade."' "; }
			$sql .= " order by pp_nome, pa_nome, pb_protocolo  ";
			$rlt = db_query($sql);
			
			$tot = 0;
			$nid = 0;
			$pxa = 'x';
			$xprof = 'x';
			while ($line = db_read($rlt))
				{
				$status = trim($line['pb_status']);
				switch ($status)
					{
					case 'A': $status = '<font color="blue">Ativo</font>'; break;
					case 'S': $status = '<font color="brow">Suspenso</font>'; break;
					case 'C': $status = '<font color="red">Cancelado</font>'; break;
					
					}
				$prof = trim($line['pp_nome']);
				if ($prof != $xprof)
					{
						$sx .= '<TR><TD colspan=5><BR><h3>'.$prof.'</h3>';
						$xprof = $prof;
					}
				$pa = ($line['pa_nome']);
				if ($pxa == $pa)
					{ $font = '<font color="red">' ;}
				else
					{ $font = '<font color="black">'; }
				$tot++;
				$area = trim($line['pb_area_conhecimento']);
				switch($area)
					{
						case 'S': $area = 'Sociais Aplicada'; break;
						case 'A': $area = 'Cióncias Agrórias'; break;
						case 'V': $area = 'Cióncias da Saóde'; break;
						case 'H': $area = 'Cióncias Humanas'; break;
						case 'E': $area = 'Cióncias Exatas'; break;
						 
					}	
				$prof_nome = $line['pp_nome'];
				$estu_nome = $font.$line['pa_nome'].'</font>';

				$sx .= '<TR >';
				//$sx .= '<TD align="center">'.$edital.'/'.$ano.'</TD>';
				$lattes_prof = '<A HREF="'.$line['pp_lattes'].'">lattes</A>';
				$lattes_aluno = '<A HREF="'.$line['pa_lattes'].'">lattes</A>';
				$sx .= '<TD class="tabela01">';
				$sx .= '<A HREF="pibic_detalhe.php?dd0='.$line['pb_protocolo'].'&dd90='.checkpost($line['pb_protocolo']).'" target="_new">';
				$sx .= $line['pb_protocolo'];
				$sx .= '</A>';
				
				$sx .= '<TD class="tabela01" colspan=2>'.$line['pb_titulo_projeto'].'</TD>';
				$sx .= '<TD class="tabela01">'.$area.'</TD>';	
				
				$sx .= '<TR><TD>';
				$sx .= '<TD class="tabela01">'.$line['pa_nome'];
				$sx .= '<TD class="tabela01">'.trim($line['pbt_descricao']).'/'.trim($line['pbt_edital']);
				$sx .= '<TD class="tabela01">'.$status;
				$sx .= '</TR>';
		}

		$sa = '<H3>Bolsas implementadas</H3>';
		$sa .= '<table width="98%" class="tabela00">';
		$sa .= '<TR><TH>Protoc.</TH>';
		$sa .= '<TH>Orientador / Orientado</TH>';
		$sa .= '<TH>Título do projeto do bolsista / Bolsa</TH>';
		$sa .= '<TH>órea do conhecimento</TH>';
		$sa .= '</TR>';
		$sa .= $sx;
		$sa .= '<TR><TD colspan="10" align="left">Total de '.$tot.' bolsas implementadas.</TD></TR>';		
		$sa .= '</table>';

	return($sa);	
	}
		
	function resumo_nao_entregue($edital,$modalidade,$ano='')
		{
			$sql = "select * from pibic_bolsa_contempladas 
			 	inner join pibic_professor on pp_cracha = pb_professor 
 				inner join pibic_aluno on pa_cracha = pb_aluno 
 				inner join pibic_bolsa_tipo on pbt_codigo = pb_tipo
 				where pb_ano = '".$ano."' and
 					(pbt_edital = 'IS' or pbt_edital = 'PIBIC' or pbt_edital = 'PIBITI')
 				 ";

			//$sql .= " and pbt_edital = '".$edital."' ";
			$sql .= " and pb_resumo < 20100101 ";
			$sql .= " and pb_status <> 'C' ";
			if (strlen($modalidade) > 0)
				{ $sql .= " and pb_tipo = '".$modalidade."' "; }
			$sql .= " order by pp_nome, pa_nome, pb_protocolo  ";
			$rlt = db_query($sql);
			
			$tot = 0;
			$nid = 0;
			$pxa = 'x';
			$xprof = 'x';
			while ($line = db_read($rlt))
				{
				$status = trim($line['pb_status']);
				switch ($status)
					{
					case 'A': $status = '<font color="blue">Ativo</font>'; break;
					case 'S': $status = '<font color="brow">Suspenso</font>'; break;
					case 'C': $status = '<font color="red">Cancelado</font>'; break;
					
					}
				$prof = trim($line['pp_nome']);
				if ($prof != $xprof)
					{
						$sx .= '<TR><TD colspan=5><BR><h3>'.$prof.'</h3>';
						$xprof = $prof;
					}
				$pa = ($line['pa_nome']);
				if ($pxa == $pa)
					{ $font = '<font color="red">' ;}
				else
					{ $font = '<font color="black">'; }
				$tot++;
				$area = trim($line['pb_area_conhecimento']);
				switch($area)
					{
						case 'S': $area = 'Sociais Aplicada'; break;
						case 'A': $area = 'Cióncias Agrórias'; break;
						case 'V': $area = 'Cióncias da Saóde'; break;
						case 'H': $area = 'Cióncias Humanas'; break;
						case 'E': $area = 'Cióncias Exatas'; break;
						 
					}	
				$prof_nome = $line['pp_nome'];
				$estu_nome = $font.$line['pa_nome'].'</font>';

				$sx .= '<TR >';
				//$sx .= '<TD align="center">'.$edital.'/'.$ano.'</TD>';
				$lattes_prof = '<A HREF="'.$line['pp_lattes'].'">lattes</A>';
				$lattes_aluno = '<A HREF="'.$line['pa_lattes'].'">lattes</A>';
				$sx .= '<TD class="tabela01">';
				$sx .= '<A HREF="pibic_detalhe.php?dd0='.$line['pb_protocolo'].'&dd90='.checkpost($line['pb_protocolo']).'" target="_new">';
				$sx .= $line['pb_protocolo'];
				$sx .= '</A>';
				
				$sx .= '<TD class="tabela01" colspan=2>'.$line['pb_titulo_projeto'].'</TD>';
				$sx .= '<TD class="tabela01">'.$area.'</TD>';	
				
				$sx .= '<TR><TD>';
				$sx .= '<TD class="tabela01">'.$line['pa_nome'];
				$sx .= '<TD class="tabela01">'.trim($line['pbt_descricao']).'/'.trim($line['pbt_edital']);
				$sx .= '<TD class="tabela01">'.$status;
				$sx .= '</TR>';
		}

		$sa = '<H3>Bolsas implementadas</H3>';
		$sa .= '<table width="98%" class="tabela00">';
		$sa .= '<TR><TH>Protoc.</TH>';
		$sa .= '<TH>Orientador / Orientado</TH>';
		$sa .= '<TH>Título do projeto do bolsista / Bolsa</TH>';
		$sa .= '<TH>órea do conhecimento</TH>';
		$sa .= '</TR>';
		$sa .= $sx;
		$sa .= '<TR><TD colspan="10" align="left">Total de '.$tot.' bolsas implementadas.</TD></TR>';		
		$sa .= '</table>';

	return($sa);	
	}

		function relatorio_implementadas($edital,$modalidade,$ano='')
		{
			$sql = "select * from pibic_bolsa_contempladas 
			 	inner join pibic_professor on pp_cracha = pb_professor 
 				inner join pibic_aluno on pa_cracha = pb_aluno 
 				inner join pibic_bolsa_tipo on pbt_codigo = pb_tipo
 				where pb_ano = '".$ano."'
 				 ";

			$sql .= " and pbt_edital = '".$edital."' ";
			$sql .= " and pb_status <> 'C' ";
			if (strlen($modalidade) > 0)
				{ $sql .= " and pb_tipo = '".$modalidade."' "; }
			$sql .= " order by pa_nome, pp_nome, pb_protocolo  ";
			$rlt = db_query($sql);
			
			$tot = 0;
			$nid = 0;
			$pxa = 'x';
			while ($line = db_read($rlt))
				{
				$pa = ($line['pa_nome']);
				if ($pxa == $pa)
					{ $font = '<font color="red">' ;}
				else
					{ $font = '<font color="black">'; }
				$tot++;
				$area = trim($line['pb_area_conhecimento']);
				switch($area)
					{
						case 'S': $area = 'Sociais Aplicada'; break;
						case 'A': $area = 'Cióncias Agrórias'; break;
						case 'V': $area = 'Cióncias da Saóde'; break;
						case 'H': $area = 'Cióncias Humanas'; break;
						case 'E': $area = 'Cióncias Exatas'; break;
						 
					}	
				$prof_nome = $line['pp_nome'];
				$estu_nome = $font.$line['pa_nome'].'</font>';

				$sx .= '<TR >';
				//$sx .= '<TD align="center">'.$edital.'/'.$ano.'</TD>';

				$sx .= '<TD class="tabela01">'.$prof_nome.'</TD>';
				$sx .= '<TD>'.$line['pb_status'];
				$sx .= '<TD class="tabela01">'.$line['pp_cpf'].'</TD>';
				$sx .= '<TD class="tabela01">'.$line['pp_lattes'].'</TD>';
				
				$sx .= '<TD class="tabela01">'.$area.'</TD>';
				
				$sx .= '<TD class="tabela01">'.$line['pb_titulo_projeto'].'</TD>';
			
				$sx .= '<TD class="tabela01">'.$line['pa_cpf'].'</TD>';
				$sx .= '<TD class="tabela01">'.$line['pa_lattes'].'</TD>';
				$sx .= '<TD class="tabela01">'.$line['pa_nome'].'</TD>';
				
				$sx .= '</TR>';
		}

		$sa = '<H3>Bolsas implementadas</H3>';
		$sa .= '<table width="98%" class="tabela00">';
		$sa .= '<TR><TH>Nome do Orientador</TH>';
		$sa .= '<TH>CPF do Orientador</TH>';
		$sa .= '<TH>Link do Curróculo Lattes do Orientador</TH>';
		$sa .= '<TH>órea do conhecimento</TH>';
		$sa .= '<TH>Título do projeto do bolsista</TH>';
		$sa .= '<TH>CPF do bolsista</TH>';
		$sa .= '<TH>Link do Curróculo Lattes do Bolsista</TH>';
		$sa .= '<TH>Nome do bolsista</TH>';
		$sa .= '</TR>';
		$sa .= $sx;
		$sa .= '<TR><TD colspan="10" align="left">Total de '.$tot.' bolsas implementadas.</TD></TR>';		
		$sa .= '</table>';

	return($sa);	
	}	

	function resumo_bolsas_campi_detalhado($ano=2013,$modalidade='PIBIC',$tela='',$tipo='1')
			{
			echo '-->'.$tipo;
			$cp = 'pbt_edital, pbt_descricao, pb_ano, centro_nome';
			$sql = "select * from pibic_bolsa_contempladas
				inner join pibic_professor on pb_professor = pp_cracha
				inner join pibic_aluno on pb_aluno = pa_cracha 
 				inner join pibic_bolsa_tipo on pbt_codigo = pb_tipo
 				left join centro on pp_escola = centro_codigo
 				where pb_status <> 'C' and pb_ano = '".$ano."' 
 				and pbt_edital = '$modalidade'
 				"; 
 			$sql .= " order by pp_centro, pbt_edital desc, pbt_descricao, pb_ano desc, pb_protocolo ";
			$rlt = db_query($sql);
			
			$sx .= '<table class="tabela00" width="100%" align="center">';
			$sx .= '<TR><TH>Edital
						<TH>Modalidade
						<TH>Orientador
						<TH>Aluno
						<TH>Total';

			$tot = 0;
			$ano = 0;
			$xmod = 'x';
			$xcentro = 'x';
			$totx = 0;
			while ($line = db_read($rlt))				
				{
					$proto = $line['pb_protocolo'];
					if ($proto == $xproto) { $font = '<font color="red">'; } else { $font = ''; }
					$xproto = $proto;
					$centro = $line['pp_centro'];
					$mod = trim($line['pbt_descricao']);
					
					if ($xcentro != $centro)
						{
							if ($totx > 0)
								{ $sx .= '<TR><TD colspan=10 align="right"><I>Total parcial '.$totx.'</I>'; $totx = 0; }
							$sx .= '<TR><TD colspan=10><span class="lt4">'.$centro.'</span>';
							$xcentro = $centro;
						}
					
						$sx .= '<TR>';
						$sx .= '<TD class="tabela01" align="left">'.$line['pbt_edital'];
						$sx .= '<TD class="tabela01" align="left">'.$font.$line['pbt_descricao'];
						
						if ($tipo=='1')
							{
							$sx .= '<TD class="tabela01" align="left">'.$line['pp_nome'];
							$sx .= ' ('.trim($line['pp_cracha']).')';
							$sx .= '<TD class="tabela01" align="left">'.$line['pa_nome'];
							$sx .= ' ('.trim($line['pa_cracha']).')';
							$sx .= '<TD class="tabela01" align="center">'.$line['pb_protocolo'];
							}
						if ($tipo=='2')
							{
							$sx .= '<TD class="tabela01" align="left">'.$line['pp_nome'];
							$sx .= ' ('.trim($line['pp_cracha']).')';
							$sx .= '<TD class="tabela01" align="left">'.$line['pp_email'];
							$sx .= '<TD class="tabela01" align="center">'.$line['pb_protocolo'];
							}
						if ($tipo=='3')
							{
							$sx .= '<TD class="tabela01" align="left">'.$line['pa_nome'];
							$sx .= ' ('.trim($line['pa_cracha']).')';
							$sx .= '<TD class="tabela01" align="left">'.$line['pa_email'];
							$sx .= '<TD class="tabela01" align="center">'.$line['pb_protocolo'];
							}						
						$xmod = $mod;
						
					$tot = $tot + 1;
					$totx = $totx + 1;
				}
			if ($totx > 0)
				{ $sx .= '<TR><TD colspan=10 align="right"><I>Total parcial '.$totx.'</I>'; $totx = 0; }
			if ($tot > 0)
				{ $sx .= '<TR><TD colspan=10 align="right"><B><I>Total geral '.$tot.'</I></B>'; }
				
			$sx .= '</table>';
			echo $sx;
			}		
		
	function altera_titulo($idx='',$tit_pt='',$tit_en='')
		{
			if (strlen($tit_pt) > 10)
			{
			$sql = "update ".$this->tabela." set
				pb_titulo_plano = '".$tit_pt."',
				pb_titulo_plano_en = '".$tit_en."'
				where pb_protocolo = '".$idx."'";
			$rlt = db_query($sql);
			}
			return(1);
		}
		
	
	function mostra_titulo()
		{
			$sx = '<fieldset>';
			$sx .= '<legend>Título da pesquisa do aluno</legend>';
			$sx .= '<font class="lt4">'.$this->pb_titulo_plano.'</font>';
			
			$sx .= '</fieldset>';
			return($sx);
		}
	function gerar_atividade_rr()
		{
			$ati = new atividade;
			
			$cod = 'RRS';
			$descricao = "Validação Título e colaboradores para o SEMIC";
			$limite = 20130701;
			$protocolo = '';
			$docente = '';
			$discente = '';

			$sql = "select (pb_semic_ratificado > 20100101) as valid, 
					count(*) as total, pb_ano, pbt_edital
				from ".$this->tabela." 
				 inner join pibic_bolsa_tipo on pbt_codigo = pb_tipo
					where (pb_status <> 'C')
					and (
						(pb_ano = '".(date("Y")-1)."' and (pbt_edital = 'PIBIC' or pbt_edital = 'PIBITI' or pbt_edital = 'IS'))
						or
						(pb_ano = '".(date("Y"))."' and (pbt_edital = 'PIBICE'))
						)
					group by pb_ano, pbt_edital, valid
					order by pbt_edital
					";
			$rlt = db_query($sql);
			while ($line = db_read($rlt))
				{
						$protocolo = '';
						$docente = '';
						$discente = '';
						print_r($line);
						exit;
					
				}		
			
//			$ati->inserir_atividade($cod='',$descricao='',$docente='',$discente='',$limite,$protocolo)
		}
	function relatorio_bolsas($ano=2012)
		{
			$sql = "select * from pibic_bolsa_contempladas 
				left join pibic_bolsa_tipo on pbt_codigo = pb_tipo
				left join pibic_aluno on pb_aluno = pa_cracha 
				left join pibic_professor on pb_professor = pp_cracha
				 
				";
		//$sql .= "left join pibic_edital on pb_protocolo = pee_protocolo ";
		if (strlen($dd[0]) > 0)
			{
				$sql .= " where (pb_tipo = '".$dd[0]."' )";
			} else {
				$sql .= " where (pb_tipo = 'C' or 
							pb_tipo = 'I' or 
							pb_tipo = 'P' or 
							pb_tipo = 'N' or
							pb_tipo = 'Z' or
							pb_tipo = 'C' or
							pb_tipo = 'E' or
							pb_tipo = 'U' or
							pb_tipo = '2' or
							pb_tipo = 'I' or
							pb_tipo = 'M' or
							pb_tipo = 'A' or
							pb_tipo = 'G' or
							pb_tipo = 'B' or
							pb_tipo = 'V' or
							pb_tipo = 'O' or
							pb_tipo = 'F'
							) ";
			}
			$sql .= " and pb_status <> 'C' ";
			$sql .= " and pb_ano = '2012' ";
			$sql .= "order by pa_curso, pp_nome";
			$rlt = db_query($sql);

			$sz = "";
			$sc = "";
			$sp = "";
			$tot0 = 0;
			$tot1 = 0;
			$tot2 = 0;
			while ($line = db_read($rlt))
				{
				$tot2++;
				$centro = $line['pa_centro'];
				$curso = $line['pa_curso'];
				$prof  = $line['pp_cracha'];
				$tipo = $line['pb_tipo'];
				$doc_protocolo = $line['pb_protocolo'];
				$tipo_d = trim($line['pbt_descricao']);
			
				$col = '';
				$sx .= '<TR valign="top"><TD>'.$line['pp_nome'].'<TD>'.$line['pa_cracha'].'</TD>';
			
				$sx .= '<TD>'.$line['pbt_edital'];
				$sx .= '<TD>'.$line['pb_ano'];

				$sx .= '<TD>';
				$sx .= $line['pa_nome'];
				$sx .= '<TD>'.$line['pp_cracha'];
				$sx .= '<TD>';
				$sx .= $line['pb_titulo_projeto'];
				$sx .= '<TD>';
				$sx .= $tipo_d;
				$sx .= '<TD>';
				$sx .= $doc_protocolo;
				$sx .= '<TD>';
				$sx .= $line['pa_curso'];
				
				$sx .= '<TD>';
				if ($line['pb_ativacao'] != '19000101')
				{
					$sx .= 'Data ativação '.stodbr($line['pb_ativacao']);
				} else {
					$sx .= '&nbsp;';
				}
			$sx .= '</TR>';
			}
		$sx .= '<TR><TD colspan=10>Total '.$tot2.' projetos ativos';
		$sa = '<table border="0" class="tabela00">';
		$sa .= '<TR>';
		$sa .= '<TH>Orientador</TH>';
		$sa .= '<TH>Cod.Funcional</TH>';
		$sa .= '<TH>Edital</TH>';
		$sa .= '<TH>Ano</TH>';
		$sa .= '<TH>Aluno</TH>';
		$sa .= '<TH>Cracha</TH>';
		$sa .= '<TH>Título do projeto do aluno</TH>';
		$sa .= '<TH>IC</TH>';
		$sa .= '<TH>Protocolo</TH>';
		$sa .= '<TH>Curso</TH>';
		$sa .= '<TH>Ativação</TH>';
		$sa .= '</TR>';
		$sa .= $sx;
		$sa .= '</table>';
		return($sa);
		}	
	
	function acoes()
		{
			global $dd,$acao;
			$status = $this->pb_status;
			/* Relatorio Pacial */
			$rpar = $this->pb_rp;
			$rpar_nota = round($this->pb_rp_nota);
			/* Relatorio Pacial */
			$fpar = $this->pb_rf;
			$fpar_nota = $this->pb_rf_nota;			
			$acao = array();
			
			switch ($status)
				{
				case 'A':
						array_push($acao,array('010','Suspender bolsa*'));
						array_push($acao,array('020','Substituição do aluno*'));
						array_push($acao,array('050','Substituição do professor'));
						array_push($acao,array('021','Alterar modalidade da IC*'));
						array_push($acao,array('022','Alterar Título do plano do aluno'));
						
						/* Cancelar submissóo do relatório parcial */
						
						if (($rpar > 0) and ($rpar_nota < 1))
							{
								array_push($acao,array('030','Renviar para orientador relatório parcial para resubmissóo'));		
							}
//					break;
				case 'C':
						array_push($acao,array('011','Reativar bolsa'));
						break;
				case 'S':
						array_push($acao,array('011','Reativar bolsa'));
						break;
				}
			array_push($acao,array('','Entrega de relatório'));
			array_push($acao,array('100','__Alterar dados do relatório parcial'));
			array_push($acao,array('200','__Alterar dados do relatório final'));
			
			$sx = '<fieldset><legend>'.msg("actions").'</legend>';
			$sx .= '<UL>';
			for ($r=0;$r < count($acao);$r++)
				{
					$txt = $_GET['dd20_'.$acao[$r][0]];
					if (strlen($txt) == 0)
						{
							$txt = msg('acao_'.$acao[$r][0]);
							$txt .= chr(13).'<BR><BR>Protocolo: '.$this->pb_protocolo;
						}
					if (strlen($dd[20])==0) {  msg('acao_'.$ac); }
					
					$js = ' onclick="ajax_acao(\''.$acao[$r][0].'\');" ';
					if (strlen(trim($acao[$r][0]))==0) { $js = ''; }
					/* Caption */
					$sxf = '';
					$cap = trim($acao[$r][1]);
					if (substr($cap,0,2) == '__')
						{ $sx .= '<UL>'; $sxf = "</UL>"; $cap = substr($cap,2,strlen($cap)); }
						
					$sx .= '<LI><div class="link" id="acta'.$acao[$r][0].'" '.$js.'>'.$cap.'</div>';
					$sx .= '<div id="tl'.$acao[$r][0].'" style="display: none; ">wait...</div>';
					$sx .= '</li>';
					$sx .= $sxf;
				}
			$sx .= '</UL>';
			$sx .= '* Não implementado';
			$sx .= '</fieldset>';
			/* Java script */
			$sx .= '
				<script>
					function ajax_acao(id)
						{
							var obj = "#tl" + id;
							$( obj ).toggle();
							
							$.ajax({
								type: "POST",
								url: "ic_pb_ajax.php",
								data: { dd0: "'.$this->pb_protocolo.'", dd1: id, dd89: obj }
								})
									.done(function( msg ) {
										$( obj ).html(msg);
									});
						}
				</script>
			';
			return($sx);
		}
	function comunicar_pesquisador($assunto,$texto)
		{
			$email1 = trim($this->pb_prof_email_1);
			$email2 = trim($this->pb_prof_email_2);
			$email3 = trim($this->pb_aluno_email1);
			$email4 = trim($this->pb_aluno_email2);
			
			echo '<BR><BR>Enviando para '.$email1.' '.$email2.' '.$email3.' '.$email4;
			$txt = $this->mostar_dados();
			$txt .= '<BR><BR>'.$texto;
			enviaremail('pibicpr@pucpr.br','','[IC] '.$this->pb_protocolo.' '.$assunto,$txt);			
			enviaremail('monitoramento@sisdoc.com.br','','[IC] '.$this->pb_protocolo.' '.$assunto,$txt);
			enviaremail('renefgj@gmail.com','','[IC] '.$this->pb_protocolo.' '.$assunto,$txt);			
			if (strlen($email1) > 0) { enviaremail($email1,'','[IC] '.$this->pb_protocolo.' '.$assunto,$txt); }
			if (strlen($email2) > 0) { enviaremail($email2,'','[IC] '.$this->pb_protocolo.' '.$assunto,$txt); }
			if (strlen($email3) > 0) { enviaremail($email3,'','[IC] '.$this->pb_protocolo.' '.$assunto,$txt); }
			if (strlen($email4) > 0) { enviaremail($email4,'','[IC] '.$this->pb_protocolo.' '.$assunto,$txt); }
			echo '<BR>';
		}
	function liberar_resubmissao_relatorio_parcial()
		{
			$sql = "update ".$this->trabalho." set pb_rp = 0, rp_pb_nota = 0 where pb_protocolo = '".$this->pb_protocolo."' ";
			echo $sql;
			exit;
			$rlt = db_query($sql);
			return(1);
		}

	function actions($ac)
		{
			global $user,$dd,$form,$acao,$http,$pb;
			$ph = new pibic_historico;
			
			$proto = $this->pb_protocolo;
			$acao = $_POST['acao'];
			
			/* Alterar Título de Trabalho */
			if ($ac == '200')
				{
					if (strlen($acao) > 0)
						{
							$dd[3] = utf8_decode($dd[3]);
							$dd[4] = utf8_decode($dd[4]);
							$dd[5] = utf8_decode($dd[5]);							
						}
					$cp = array();
					array_push($cp,array('$HV','pb_protocolo',$dd[0],True,True));
					array_push($cp,array('$HV','',$dd[1],False,True));
					array_push($cp,array('$HV','',$dd[2],False,True));
					array_push($cp,array('$O : &1:Reabrir para postagem do relatório','','Ação',True,True));
					array_push($cp,array('$HV','pb_relatorio_final','0',True,True));
					array_push($cp,array('$HV','pb_relatorio_final_nota','-1',True,True));
					array_push($cp,array('$O : &1:SIM','','Confirmar',True,True));
					array_push($cp,array('$B8','','Atualizar >>',False,False));
					$tela = $form->editar($cp,$this->tabela);
					if ($form->saved > 0)
						{
							$this->le('',$dd[0]);
							
									echo '<BR>Gerando Histórico';											
									$historico = 'Reenviado para postagem do relatório final';
									$ph->inserir_historico($dd[0],200,$historico,$dd[3],'','RFR');

									echo '<BR>Recuperando mensagem do sistema';
									$ic = new ic;
									$nw = $ic->ic('ic_reenvio_realtorio');
									
									$texto = $nw['nw_descricao'];
									$texto .= '<BR><BR>'.$historico.'<BR><BR>';
									$titulo = $nw['nw_titulo'];
									
									$texto = '<IMG SRC="'.$http.'img/email_ic_header.png"><BR><BR>'.mst($texto).'<BR><BR><BR><img src="'.$http.'img/email_ic_foot.png">';
									
									echo '<BR>Comunicando pesquisador<BR>';
									/* Comunicar novo orientador */
									$this->comunicar_pesquisador('Reabertura para reenvio de relatório',$historico);					
									
									echo '<font color="green">Operação realizada com sucesso!</font>';
									exit;
									
							$sx = 'Saved';
						} else {
							$sx = $tela;		
						}
					
					return($sx);				
				}


			/* Alterar Título de Trabalho */
			if ($ac == '021')
				{
					$cp = array();
					
					if (strlen($acao) > 0)
						{
							$dd[5] = utf8_decode($dd[5]);							
						}
					array_push($cp,array('$HV','pb_protocolo',$dd[0],True,True));
					array_push($cp,array('$HV','',$dd[1],False,True));
					array_push($cp,array('$H8','pb_tipo','',False,False));
					array_push($cp,array('$HV','',$dd[2],False,True));
					array_push($cp,array('$Q pbt_descricao:pbt_codigo:select * from pibic_bolsa_tipo','pb_tipo','Nova bolsa',True,True));
					//array_push($cp,array('$Q a_descricao:a_codigo:select * from ajax_areadoconhecimento where a_semic = \'1\'','pb_semic_area','Data de submissão',True,True));
					array_push($cp,array('$T80:6','','Justificativa',True,True));
					array_push($cp,array('$B8','','Atualizar >>',False,False));
					$tela = $form->editar($cp,'');
					
					if ($form->saved > 0)
						{
							
							print_r($dd);
							echo 'salvo';
							exit;
							$this->le('',$dd[0]);
							if ($dd[3] != $dd[4])
								{
									$sql = "select * from pibic_bolsa_tipo where pbt_codigo = '".$dd[4]."' ";
									$rrr = db_query($sql);
									if ($line = db_read($rlt)) { $mod1 = $line['pbt_descricao']; }
											
									$sql = "select * from pibic_bolsa_tipo where pbt_codigo = '".$dd[3]."' ";
									$rrr = db_query($sql);
									if ($line = db_read($rlt)) { $mod2 = $line['pbt_descricao']; }
									
									echo '<BR>Gerando Histórico';											
									$historico = 'Troca de modalidade de bolsa<BR>De:<B><I>'.$mod1.'</I></B><BR>Para:<B>'.$mod2.'</B><BR><BR>Motivo:'.$dd[5];
									$ph->inserir_historico($dd[0],021,$historico,$dd[3],'','SUB');

									echo '<BR>Recuperando mensagem do sistema';
									$ic = new ic;
									$nw = $ic->ic('ic_troca_modalidade');
									
									$texto = $nw['nw_descricao'];
									$texto .= '<BR><BR>'.$historico.'<BR><BR>';
									$titulo = $nw['nw_titulo'];
									
									$texto = '<IMG SRC="'.$http.'img/email_ic_header.png"><BR><BR>'.mst($texto).'<BR><BR><BR><img src="'.$http.'img/email_ic_foot.png">';
									
									echo '<BR>Comunicando pesquisador<BR>';
									/* Comunicar novo orientador */
									$this->comunicar_pesquisador('Troca de modalidade de Iniciação, Tecnológica e Inovação',$historico);					
									
									echo '<font color="green">Substituição realizada com sucesso!</font>';
									exit;
								}
							$sx = 'Saved';
						} else {
							$sx = $tela;		
						}
					
					return($sx);				
				}

			/* Alterar Título de Trabalho */
			if ($ac == '022')
				{
					$cp = array();
					if (strlen($acao) > 0)
						{
							$dd[3] = utf8_decode($dd[3]);
							$dd[4] = utf8_decode($dd[4]);
							$dd[5] = utf8_decode($dd[5]);							
						}
					array_push($cp,array('$HV','pb_protocolo',$dd[0],True,True));
					array_push($cp,array('$HV','',$dd[1],False,True));
					array_push($cp,array('$HV','',$dd[2],False,True));
					array_push($cp,array('$T80:6','pb_titulo_projeto','Novo título',True,True));
					array_push($cp,array('$H8','pb_titulo_projeto','',False,False));
					//array_push($cp,array('$Q a_descricao:a_codigo:select * from ajax_areadoconhecimento where a_semic = \'1\'','pb_semic_area','Data de submissão',True,True));
					array_push($cp,array('$T80:6','','Justificativa',True,True));
					array_push($cp,array('$B8','','Atualizar >>',False,False));
					$tela = $form->editar($cp,$this->tabela);
					
					if ($form->saved > 0)
						{
							$this->le('',$dd[0]);
							echo $dd[3];
							echo '<BR>'.$dd[4];
							if ($dd[3] != $dd[4])
								{
									echo '<BR>Gerando Histórico';											
									$historico = 'Troca de título do plano<BR>De:<B><I>'.$dd[4].'</I></B><BR>Para:<B>'.$dd[3].'</B><BR><BR>Motivo:'.$dd[5];
									$ph->inserir_historico($dd[0],022,$historico,$dd[3],'','TIT');

									echo '<BR>Recuperando mensagem do sistema';
									$ic = new ic;
									$nw = $ic->ic('ic_troca_titulo');
									
									$texto = $nw['nw_descricao'];
									$texto .= '<BR><BR>'.$historico.'<BR><BR>';
									$titulo = $nw['nw_titulo'];
									
									$texto = '<IMG SRC="'.$http.'img/email_ic_header.png"><BR><BR>'.mst($texto).'<BR><BR><BR><img src="'.$http.'img/email_ic_foot.png">';
									
									echo '<BR>Comunicando pesquisador<BR>';
									/* Comunicar novo orientador */
									$this->comunicar_pesquisador('Troca de título de trabalhos',$historico);					
									
									echo '<font color="green">Substituição realizada com sucesso!</font>';
									exit;
								}
							$sx = 'Saved';
						} else {
							$sx = $tela;		
						}
					
					return($sx);				
				}
			
			/* Relatório Parcial */
			if ($ac == '100')
				{
					$cp = array();
					array_push($cp,array('$HV','pb_protocolo',$dd[0],True,True));
					array_push($cp,array('$HV','',$dd[1],False,True));
					array_push($cp,array('$HV','',$dd[2],False,True));
					array_push($cp,array('$D8','pb_relatorio_parcial','Data de submissão',True,True));
					array_push($cp,array('$O : &1:Pendência&2:Aprovado&0:Não avaliado','pb_relatorio_parcial_nota','Nota',True,True));
					//array_push($cp,array('$Q a_descricao:a_codigo:select * from ajax_areadoconhecimento where a_semic = \'1\'','pb_semic_area','Data de submissão',True,True));
					array_push($cp,array('$B8','','Atualizar >>',False,False));

					$tela = $form->editar($cp,$this->tabela);
					
					if ($form->saved > 0)
						{
							$sx = 'Saved';
						} else {
							$sx = $tela;		
						}
					
					return($sx);				
				}
			/*
			 * Substituição de professor
			 */
			if ($ac == '050')
				{					
					$cp = array();
					array_push($cp,array('$HV','pb_protocolo',$dd[0],True,True));
					array_push($cp,array('$HV','',$dd[1],False,True));
					array_push($cp,array('$HV','',$dd[2],False,True));
					array_push($cp,array('$S8','pb_professor','Código do professor',True,True));
					array_push($cp,array('$T60:2','','Motivo da troca',True,True));
					//array_push($cp,array('$Q a_descricao:a_codigo:select * from ajax_areadoconhecimento where a_semic = \'1\'','pb_semic_area','Data de submissão',True,True));
					array_push($cp,array('$B8','','efetuar troca >>',False,False));

					$tela = $form->editar($cp,'');
					
					if ($form->saved > 0)
						{
							$motivo = utf8_decode($dd[4]);
							 
							$pp = new docentes;
							$pp->le(trim($dd[3]));
							$o2 = $pp;
							$orientador2 = trim($pp->pp_nome);
							
							
							if (strlen($orientador2)==0)
								{
									$SX = '<FONT COLOR="RED">Código do novo orientador inválido '.$dd[3].'</font>';
								} else {
									$this->le('',$dd[0]);
									
									$this->troca_orientador($pb->pb_protocolo,$pb->pb_professor,$o2->pp_cracha);
									
									$orientador1 = trim($this->pb_professor);
									$pp->le($orientador1);
									$o1 = $pp;
									$orientador1 = trim($pp->pp_nome);
											
									$historico = 'Troca de orientador<BR>De:'.$orientador1.'<BR>Para:'.$orientador2.'<BR>Motivo:'.$motivo;
									$ph->inserir_historico($dd[0],050,$historico,$dd[3],$aluno2,'TPF');


									/* Comunicar novo orientador */
									$email1 = $o2->pp_email;
									$email2 = $o2->pp_email_1;
									
									$ic = new ic;
									$nw = $ic->ic('ic_troca_orientador');
									
									$texto = $nw['nw_descricao'];
									$titulo = $nw['nw_titulo'];
									
									/* Trocas */
									$texto = troca($texto,'$NOME',$o2->pp_nome);
									$texto = troca($texto,'$TITULO',$pb->pb_titulo_plano);
									$texto = troca($texto,'$PROTOCOLO',$pb->pb_protocolo);
									$texto = troca($texto,'$ALUNO',$pb->pb_aluno);
									$texto = troca($texto,'$MOTIVO',$historico);
									
									$texto = '<IMG SRC="'.$http.'img/email_ic_header.png"><BR><BR>'.mst($texto).'<BR><BR><BR><img src="'.$http.'img/email_ic_foot.png">';
									
									$email = 'renefgj@gmail.com';
									if (strlen($email) > 0) { enviaremail($email,'',$titulo,$texto); }
									
									$email = 'pibicpr@pucpr.br';
									if (strlen($email) > 0) { enviaremail($email,'',$titulo,$texto); }
									if (strlen($email1) > 0) { enviaremail($email1,'',$titulo,$texto); }
									if (strlen($email2) > 0) { enviaremail($email2,'',$titulo,$texto); }
									
									
									echo '<font color="green">Substituição realizada com sucesso!</font>';
									exit;
								}
						} else {
							$sx = $tela;		
						}
					
					return($sx);				
				}
			/* Renvio para o professor */
			if (($ac == '030') and ($dd[2]=='1'))
				{
					$historico = 'Rencaminhado projeto para correção do professor';
					$hist = msg('acao_'.$ac.'h');
					$hist = $historico;
					$motivo = '000';
					
					$aluno1 = '';
					$aluno2 = '';
					$his = new pibic_historico;
					$this->liberar_resubmissao_relatorio_parcial();
					$his->inserir_historico($proto,$ac,$hist,$aluno1,$aluno2,$motivo);
					$this->liberar_resubmissao_relatorio_parcial();
					$this->comunicar_pesquisador('Liberação de relaório parcial',$historico);
					redirecina(page().'?dd0='.$dd[0].'&dd90='.$dd[90]);
					exit;					
				}
			/* Suspencao de bolsa */
			if (($ac == '010') and ($dd[2]=='1'))	
				{
					//print_r($_GET);
					//$proto,$acao,$historico,$aluno1,$aluno2,$motivo)
					$historico = $_GET['dd20'.$ac];
					$hist = msg('acao_'.$ac.'h');
					$motivo = '401';
					
					$aluno1 = '';
					$aluno2 = '';
					$his = new pibic_historico;
					$his->inserir_historico($proto,$ac,$hist,$aluno1,$aluno2,$motivo);
					$this->alterar_status('S');
					$this->comunicar_pesquisador('Suspensóo de bolsa',$historico);
					redirecina(page().'?dd0='.$dd[0].'&dd90='.$dd[90]);
					exit;
				}
			if (($ac == '011') and ($dd[2]=='1'))	
				{
					//print_r($_GET);
					//$proto,$acao,$historico,$aluno1,$aluno2,$motivo)
					$historico = $_GET['dd20'.$ac];
					$hist = msg('acao_'.$ac.'h');
					$motivo = '000';
					
					$aluno1 = '';
					$aluno2 = '';
					$his = new pibic_historico;
					$his->inserir_historico($proto,$ac,$hist,$aluno1,$aluno2,$motivo);
					$this->alterar_status('A');
					$this->comunicar_pesquisador('Reativação de bolsa',$historico);
					redirecina(page().'?dd0='.$dd[0].'&dd90='.$dd[90]);
					exit;
				}				
		}

	function troca_orientador($protocolo,$o1,$o2)
		{
			$sql = "update ".$this->tabela." set pb_professor = '".$o2."'
					where pb_professor = '".$o1."' and pb_protocolo = '".$protocolo."'";
			$rlt = db_query($sql);
			return(1);
		}
	function alterar_status($para)
		{
			$proto = $this->pb_protocolo;
			$sql = "update ".$this->tabela." set pb_status = '$para'
					where pb_protocolo = '$proto' ";
			$rlt = db_query($sql);
		}
	function search($busca)
		{
		global $link;
		$this->link = 'pibic_detalhe.php';
		if (strlen(trim($busca)) > 0)
			{
			$busca = uppercase(trim($busca));
			$sql = "select * from pibic_bolsa_contempladas ";
			$sql .= "left join pibic_aluno on pb_aluno = pa_cracha ";
			$sql .= "left join pibic_professor on pb_professor = pp_cracha ";
			$sql .= "left join pibic_submit_documento on doc_protocolo = pb_protocolo_mae ";
			$sql .= "left join pibic_bolsa_tipo on pbt_codigo = pb_tipo ";
			$sql .= " where ";
				$wh .= "(pb_titulo_projeto_asc like '%".$busca."%')"; 
				$wh .= ' OR ';
		 		$wh .= "(pp_nome_asc like '%".$busca."%')";
				$wh .= ' OR ';
		 		$wh .= "(pa_nome_asc like '%".$busca."%')";
				$wh .= ' OR ';
		 		$wh .= "(pb_protocolo like '%".$busca."%')";
				$wh .= ' OR ';
		 		$wh .= "(pb_protocolo_mae like '%".$busca."%')";
			}
			$sql .= $wh;
			$sql .= " order by pb_ano desc ";
			$rlt = db_query($sql);
			
			$sr = '';
			$it = 0;
			$sx = '<table width="100%">';
			while ($line = db_read($rlt))
				{
					$link = $this->link;
					$sx .= $this->mostra_registro($line);
				}
			//$sx = realce($sx,array(trim($busca)));
			$sx .= '</table>';
			return($sx);			
		}
	function mostra_search($line)
		{
			
		}
	function docentes_inativos_com_orientacoes($ano)
		{
			$sql = "select * from ".$this->tabela." 
				inner join pibic_professor on pb_professor = pp_cracha
				inner join pibic_bolsa_tipo on pbt_codigo = pb_tipo
				left join pibic_aluno on pb_aluno = pa_cracha
				where pb_status = 'A'
				order by pp_nome
			";
			$rlt = db_query($sql);
			$id = 0;
			$sx = '<table class="tabela00">';
			$sx .= '<TR><TD colspan=10><h2>Professores desligados com orientações</h2>';
			$sx .= '<TR><TH>Professor<TH>Centro<TH>Modalidade<TH>Estudante<TH>Ano';
			while ($line = db_read($rlt))
			{
				$xano = $line['pp_update'];
				if (($ano != $xano) or ($line['pp_ativo']==0))
					{
					$id++;
					$sx .= '<TR>';
					$sx .= '<TD class="tabela01">';
					$sx .= $line['pp_nome'];
					$sx .= '<TD class="tabela01">';
					$sx .= $line['pp_centro'];					
//					$sx .= '<TD class="tabela01">';
//					$sx .= $line['pb_protocolo'];
					$sx .= '<TD class="tabela01">';
					$sx .= $line['pbt_descricao'];
					$sx .= '<TD class="tabela01">';
					$sx .= $line['pa_nome'];
					$sx .= '<TD class="tabela01">';
					$sx .= $xano;
//					print_r($line);
//					exit;
					}
			}
			$sx .= '<TR><TD colspan=5>Total de '.$id.' projetos com professores desligados';
			$sx .= '</table>';
			return($sx);
		}
		
	function bolsa_duplicatas($ano=2012)
		{
				$sql = "
					select * from ".$this->tabela." 
				 	inner join pibic_bolsa_tipo on pbt_codigo = pb_tipo
				 	left join pibic_aluno on pb_aluno = pa_cracha
					where (pb_status <> 'C') and pb_ano = '$ano'
					and (pbt_edital = 'PIBIC' or pbt_edital = 'PIBITI' or pbt_edital = 'IS')
					order by pa_nome, pbt_auxilio
					 ";
				$sx .= '<table width="100%">';
				$sx .= '<TR><TD colspan=5><h2>Bolsas ativas para pagamento</h2>';
				$sx .= '<TR>
						<TH width="5%">Protocolo
						<Th width="5%">Cracha<TH width="50%">Nome
						<TH width="10%" align="left">Data ativação
						<TH width="20%" align="left">Bolsa
						<TH width="10%" align="left">Benefócio';
				$rlt = db_query($sql);
				$id = 0;
				$tot = 0;
				$xcra = 'x';
				$idx = 0;
				while ($line = db_read($rlt))
					{
						$idx++;
						$cra = $line['pb_aluno'];
						$cor = '<font color="red">';

								
								$tot = $tot + $line['pbt_auxilio'];
								$sxa = '<TR>';
								$sxa .= '<TD class="tabela01">';
								$sxa .= $cor.$line['pb_protocolo'];								
								$sxa .= '<TD class="tabela01">';
								$sxa .= $cor.$line['pb_aluno'];
								$sxa .= '<TD class="tabela01">';
								$sxa .= $cor.$line['pa_nome'];
							
								$sxa .= '<TD class="tabela01" align="center">';
								$sxa .= $cor.stodbr($line['pb_ativacao']);
						
								$sxa .= '<TD class="tabela01">';
								$sxa .= $cor.$line['pbt_descricao'];
								$sxa .= '<TD class="tabela01" align="right">';
								$sxa .= $cor.number_format($line['pbt_auxilio'],2,',','.');

						if ($xcra == $cra)
							{
								$id++;
								$sx .= $sxb.$sxa;
							} 
						$sxb = $sxa;							
						$xcra = $cra;
						}
				$sx .= '<TR class="lt1"><TD colspan=10>';
				$sx .= '<B><I>'.msg("total").' '.msg('bolsas_duplicadas').' ('.$id.') de ('.$idx.' analisadas)';
				$sx .= '</table>';
				return($sx);
		}		
		
	function ativos_por_bolsa($bolsa,$credito,$ano=2012,$tipo='')
		{
			global $LANG;
				$banco = new hsbc;
				
				$wh = "pb_tipo = '$bolsa' and ";
				if ($bolsa == '*') { $wh = ''; }
				$sql = "
					select * from ".$this->tabela." 
				 	inner join pibic_bolsa_tipo on pbt_codigo = pb_tipo 
				 	left join pibic_aluno on pb_aluno = pa_cracha 
				 	 
					where (pb_status <> 'C' and pb_status <> 'F' and pb_status <> 'S')
					and $wh pb_ano = '$ano'
					order by pb_colegio, pa_nome
					 ";
				// left join pibic_pagamentos on (pg_cpf = pa_cpf and (pg_vencimento >= ".date("Ym01")." and pg_vencimento < ".date("Ym99")."))
				
				$sx .= '<table width="100%">';
				$sx .= '<TR><TD colspan=5><h2>Bolsas ativas para pagamento - '.$ano.'</h2>';
				$sx .= '<TR><TD colspan=10>Data para cródito: '.($credito);
				$sx .= '<TR><Th width="10%">Cracha<TH>CPF<TH width="30%">Nome
						<TH width="10%" align="left">Data ativação
						<TH width="20%" align="left">Bolsa
						<TH width="10%" align="left">Benefócio
						<TH width="3%" align="left">Banco
						<TH width="3%" align="left">Mod.
						<TH width="3%" align="left">Ag.
						<TH width="3%" align="left">Conta
						<TH width="5%" align="left">tipo';


				$rlt = db_query($sql);
				$id = 0;
				$tot = 0;
				$xcra = 'x';
				$id1= 0;
				$tot1 = 0;
				$xcap = 'x';
				while ($line = db_read($rlt))
					{
						$vlr = round($line['pg_valor']);
						$ok = 1;
						if (($tipo=='N') and ($vlr > 0)) { $ok = 0; }		
						
						if ($ok==1)
						{
						$cap = trim($line['pb_colegio']);
						if ((strlen($cap) > 0) and ($cap != $xcap))
							{ $sx .= '<TR><TD colspan=10><B>'.$cap.'</b></td></tr>'; $xcap = $cap; }
						$link = http.'/pibicpr/discente.php?dd0='.$line['id_pa'].'&dd90='.checkpost($line['id_pa']);
						$link = '<A HREF="'.$link.'">';
						$cra = $line['pb_aluno'];
						$cor = '';
						if ($xcra == $cra)
							{
								$cor = '<font color="red">'; 
							}
						$sx .= '<TR>';
						$sx .= '<TD class="tabela01" align="center">';
						$sx .= $link;
						$sx .= $cor.$line['pb_aluno'];
						$sx .= '</A>';
						$sx .= '<TD class="tabela01" align="center">';
						$sx .= $cor.$line['pa_cpf'];
						
						$sx .= '<TD class="tabela01">';
						$sx .= $cor.$line['pa_nome'];
						
						$sx .= '<TD class="tabela01" align="center">';
						$sx .= $cor.stodbr($line['pb_ativacao']);
						
						$sx .= '<TD class="tabela01">';
						$sx .= $cor.$line['pbt_descricao'];
						$sx .= '<TD class="tabela01" align="right">';
						$sx .= $cor.number_format($line['pbt_auxilio'],2,',','.');
						
						$sx .= '<TD class="tabela01" align="center">';
						$sx .= '<NOBR>'.trim($line['pa_cc_banco']);
						$sx .= '<TD class="tabela01" align="center">';
						$mod = trim($line['pa_cc_mod']);
						$sx .= $mod;
						$sx .= '<TD class="tabela01" align="center">';
						$sx .= '<NOBR>'.trim($line['pa_cc_agencia']);
						$sx .= '<TD class="tabela01" align="center">';
						$sx .= '<NOBR>'.trim($line['pa_cc_conta']);
						$xcra = $cra;
						
						
						$dv = $banco->checadv($line['pa_cc_agencia'],$line['pa_cc_conta'],trim($line['pa_cc_banco']),$mod);
						if (cpf($line['pa_cpf']) != '1') { $dv = '<font color="red">CPF(erro)</font>'; }
						$dv2 = $dv;					
						
						
						if ($line['pb_status']=='S')
						{
							$dv = '<font color="red">Suspensa</font>';
						} else {
							if ($dv == 'ok')
							{
							if (strzero(round($line['pa_cc_conta']),7) == '0000000')
								{ $dv = 'ORDEM'; }
								else { $dv = 'CC'; }
							}
								
						}
						$sx .= '<TD class="tabela01" align="center"><NOBR>'.$dv;
						
						/* */
						if ($dv2 != 'ok')
							{
								$id1++;
								$tot1 = $tot1 + $line['pbt_auxilio'];
							} else {
								$id++;
								$tot = $tot + $line['pbt_auxilio'];								
							}
						$sx .= '<TD class="tabela01" align="center">';
						$sx .= trim($line['pg_valor']);
						}
							
					}
				$sx .= '<TR class="lt1"><TD colspan=10>';
				$sx .= '<B><I>'.msg("total").' de nóo creditados '.number_format($tot1,2,',','.').' ('.$id1.')';
				$sx .= '<TR class="lt1"><TD colspan=10 class="lt3">';
				$sx .= '<B><I>'.msg("total").' para pagamento '.number_format($tot,2,',','.').' ('.$id.')';
				$sx .= '<TR><TD colspan=3>';
				$sx .= '<BR><font color="red">SUSPENSA</font> - Bolsa Suspensa</font>';
				$sx .= '<BR><font color="red">ERRO</font> - Conta invólida</font>';
				$sx .= '<BR><font color="red">ERRO DV</font> - Digito verificador da conta nóo confere</font>';
				$sx .= '<BR><font color="red">ERRO AG</font> - Agóncia invólida</font>';
				$sx .= '<BR><font color="red">ERRO CC</font> - Conta Corrente invólida</font>';
				
				$sx .= '<TD align="center" colspan=4>';
				$sx .= '<BR><BR>_________________________________________________';
				$sx .= '<BR>Coordenador da Iniciação Cientófica';
				$sx .= '<BR><BR><BR>';
				$data = new date;
				$mes = $data->month_name(round(date("m")),'1'); 
				$sx .= 'Curitiba, '.date("d").' de '.$mes.' de '.date("Y");
				$sx .= '</table>';
				return($sx);
		}
		
	function semic_area_de_apresentacao($ano=0,$mod='')
		{
			$wh = " and (pbt_edital = 'PIBIC' or pbt_edital = 'PIBITI' or pbt_edital = 'IS') ";
			if (strlen($mod) > 0)
				{
					$wh = " and (pbt_edital = '$mod') ";
				}
			if ($ano == 0) { $ano = (date("Y")-1); }
			$sql = "
				select * from (
				select count(*) as total, pb_semic_area
					from ".$this->tabela." 
				 	inner join pibic_bolsa_tipo on pbt_codigo = pb_tipo
					where (pb_status <> 'C')
					and pb_ano = '".($ano)."' and pb_relatorio_parcial > 20100101
					$wh
					and pb_semic_idioma = 'en_US'
					group by pb_semic_area
				) as tabela 
				inner join ajax_areadoconhecimento on a_cnpq = pb_semic_area
					order by pb_semic_area
					";
			$rlt = db_query($sql);
			
			$sx .= '<table>';
			$sx .= '<TR><TD colspan=5><h2>Áreas de Apresentação SEMIC Internacional</h2>';
			$sx .= '<TR><Th width="10%">Total<TH width="10%">CNPq
					<TH width="80%" align="left">Descrição da órea';

			while ($line = db_read($rlt))
				{
					$sx .= '<TR>';
					$sx .= '<TD align="center">';
					$sx .= $line['total'];
					$sx .= '<TD>';
					$sx .= $line['a_cnpq'];
					$sx .= '<TD>';
					$sx .= $line['a_descricao'];
				}
			$sx .= '</table>';

			return($sx);
		}
		
	function semic_area_de_apresentacao_geral($ano=0,$mod='')
		{
			$wh = " and (pbt_edital = 'PIBIC' or pbt_edital = 'PIBITI' or pbt_edital = 'IS') ";
			if (strlen($mod) > 0)
				{
					$wh = " and (pbt_edital = '$mod') ";
				}
						
			if ($ano == 0) { $ano = (date("Y")-1); }
			$sql = "
				select * from (
				select count(*) as total, pb_semic_area
					from ".$this->tabela." 
				 	inner join pibic_bolsa_tipo on pbt_codigo = pb_tipo
					where (pb_status <> 'C')
					and pb_ano = '".($ano)."' and pb_relatorio_parcial > 20100101
					$wh
					group by pb_semic_area
				) as tabela 
				inner join ajax_areadoconhecimento on a_cnpq = pb_semic_area
					order by pb_semic_area
					";
			$rlt = db_query($sql);
			
			$sx .= '<table>';
			$sx .= '<TR><TD colspan=5><h2>óreas de Apresentação SEMIC Geral</h2>';
			$sx .= '<TR><Th width="10%">Total<TH width="10%">CNPq
					<TH width="80%" align="left">Descrição da órea';

			while ($line = db_read($rlt))
				{
					$sx .= '<TR>';
					$sx .= '<TD align="center">';
					$sx .= $line['total'];
					$sx .= '<TD>';
					$sx .= $line['a_cnpq'];
					$sx .= '<TD>';
					$sx .= $line['a_descricao'];
				}
			$sx .= '</table>';

			return($sx);
		}
		
	function acompanhamento_idioma($mod='')
		{
			$wh = " and (pbt_edital = 'PIBIC' or pbt_edital = 'PIBITI' or pbt_edital = 'IS') ";
			if (strlen($mod) > 0)
				{
					$wh = " and (pbt_edital = '$mod') ";
				}
				
			$sql = "select count(*) as total, pb_semic_idioma
				from ".$this->tabela." 
				 inner join pibic_bolsa_tipo on pbt_codigo = pb_tipo
					where (pb_status <> 'C')
					and pb_ano = '".(date("Y")-1)."' and pb_relatorio_parcial > 20100101
					$wh
					group by pb_semic_idioma
					order by pb_semic_idioma
					";
			$rlt = db_query($sql);
			$sx = '<BR>';
			$sx .= '<table>';
			$sx .= '<TR><TD colspan=5><h2>Idioma de Apresentação</h2>';
			$sx .= '<TR><TD class="lt3">';
			while ($line = db_read($rlt))
				{
					$flag = trim($line['pb_semic_idioma']);
					$sx .= '<img src="'.http.'img/img_flag_'.$flag.'.png">';
					$sx .= '('.$line['total'].')';
					$sx .= '&nbsp;&nbsp;&nbsp;';
				}
			$sx .= '</table><BR>';	
			return($sx);		
		}
	function acompanhamento_avaliacao_relatorio_parcial()
		{
			$sql = "select 'IC' as pp_tipo, count(*) as total, pp_status
					from pibic_parecer_".date("Y")."
					where pp_tipo = 'RPAR' and pp_status <> 'X'
					group by pp_status
					";
			$rlt = db_query($sql);
			$xtp = 'x';
			
			$ar = array();
			$ar1 = array();
			$ar2 = array();
			$tot0 = 0;
			$tot1 = 0;
			while ($line = db_read($rlt))
				{
					$tot0 = $tot0 + $line['total'];
					$tp = trim($line['pp_tipo']);
					$vl = UpperCase(trim($line['pp_status']));
					$x=-1;
		
					for ($r=0;$r < count($ar);$r++)
						{ if (trim($ar[$r]) == $tp) { $x = $r; } }
						
					if ($vl=='@')
						{ $tp0 = $line['total']; $tp1 = 0; } else 
						{ $tp0 = 0; $tp1 = $line['total']; }
					$tot1 = $tot1 + $tp0;
					if ($x==-1)
						{
							array_push($ar,$tp);
							array_push($ar1,$tp0);
							array_push($ar2,$tp1);
						} else {
							$ar1[$x] = $ar1[$x] + $tp0;
							$ar2[$x] = $ar2[$x] + $tp1; 
						}					
				}
				
				/* Grafico */
				$sx = '<table>';
				$sx .= '<TR><TD colspan=5><h2>Avaliação do relatório Parcial</h2>';
				$sx .= '<TR><TH>Modalidade<TH>ó entregue<TH>Entregue<TH>Grófico';
				$sx .= '<TH>Percentual';
				$sx .= '<TH rowspan=10 width="30" class="tabela01">Enviado<h2>';
				if ($tot0 > 0)
					{
						$sx .= '<center>';
						$sx .= number_format((1-($tot1/$tot0))*100,1,',','.').'%';
					}
				$sx .= '</h2>';
				$sx .= $tot1.'/'.$tot0;
				for ($r=0;$r < count($ar);$r++)
					{
						$sx .= '<TR>';
						$sx .= '<TD>';
						$sx .= $ar[$r];

						$sx .= '<TD align="center">';
						$sx .= $ar1[$r];

						$sx .= '<TD align="center">';
						$sx .= $ar2[$r];
						
						$sx .= '<TD>';
						$sz1 = $ar1[$r] + $ar2[$r];
						$st1 = round(500 * $ar1[$r] / $sz1) ;
						$st2 = 500 - $st1;
						$sx .= '<img src="'.http.'img/gr_green.png" height="20" width="'.$st2.'">';
						$sx .= '<img src="'.http.'img/gr_metter.png" height="20" width="'.$st1.'">';
						$sx .= '<TD align="right">';
						$sx .= number_format((100 * $ar2[$r] / $sz1),2,',','.').'%';
					} 
				$sx .= '</table>';
				return($sx);
		}

	function acompanhamento_avaliacao_relatorio_aprovacao()
		{
			$sql = "select 'IC' as pp_tipo, count(*) as total, pp_p01
					from pibic_parecer_".date("Y")."
					where pp_tipo = 'RPAR' and pp_status = 'B'
					group by pp_p01
					";
			$rlt = db_query($sql);
			$xtp = 'x';
			
			$ar = array();
			$ar1 = array();
			$ar2 = array();
			$tot0 = 0;
			$tot1 = 0;
			while ($line = db_read($rlt))
				{
					$tot0 = $tot0 + $line['total'];
					$tp = trim($line['pp_tipo']);
					$vl = UpperCase(trim($line['pp_p01']));
					$x=-1;
		
					for ($r=0;$r < count($ar);$r++)
						{ if (trim($ar[$r]) == $tp) { $x = $r; } }
						
					if ($vl=='2')
						{ $tp0 = $line['total']; $tp1 = 0; } else 
						{ $tp0 = 0; $tp1 = $line['total']; }
					$tot1 = $tot1 + $tp0;
					if ($x==-1)
						{
							array_push($ar,$tp);
							array_push($ar1,$tp0);
							array_push($ar2,$tp1);
						} else {
							$ar1[$x] = $ar1[$x] + $tp0;
							$ar2[$x] = $ar2[$x] + $tp1; 
						}					
				}
				
				/* Grafico */
				$sx = '<table>';
				$sx .= '<TR><TD colspan=5><h2>Índice de aprovação dos relatórios parciais</h2>';
				$sx .= '<TR><TH>Modalidade<TH>aprovado<TH>ó Aprovado<TH>Grófico';
				$sx .= '<TH rowspan=10 width="30" class="tabela01">Aprovação<h2>';
				if ($tot0 > 0)
					{
						$sx .= '<center>';
						$sx .= number_format((1-($tot1/$tot0))*100,1,',','.').'%';
					}
				$sx .= '</h2>';
				$sx .= $tot1.'/'.$tot0;
				for ($r=0;$r < count($ar);$r++)
					{
						$sx .= '<TR>';
						$sx .= '<TD>';
						$sx .= $ar[$r];

						$sx .= '<TD align="center">';
						$sx .= $ar2[$r];

						$sx .= '<TD align="center">';
						$sx .= $ar1[$r];
						
						$sx .= '<TD>';
						$sz1 = $ar1[$r] + $ar2[$r];
						$st1 = round(500 * $ar1[$r] / $sz1) ;
						$st2 = 500 - $st1;
						$sx .= '<img src="'.http.'img/gr_green.png" height="20" width="'.$st2.'">';
						$sx .= '<img src="'.http.'img/gr_metter.png" height="20" width="'.$st1.'">';
						//$sx .= '<TD align="right">';
						//$sx .= number_format((100 * $ar2[$r] / $sz1),2,',','.').'%';
					} 
				$sx .= '</table>';
				return($sx);
		}

	function acompanhamento_relatorio_ratificacao()
		{
			$sql = "update ".$this->tabela." set pb_semic_ratificado = 0 where pb_semic_ratificado isnull ";
			$rlt = db_query($sql);
			
			$sql = "update ".$this->tabela." set pb_semic_ratificado_status = 0 where pb_semic_ratificado_status isnull ";
			$rlt = db_query($sql);
			 
			$sql = "select (pb_semic_ratificado > 20100101) as valid, 
					count(*) as total, pb_ano, pbt_edital
				from ".$this->tabela." 
				 inner join pibic_bolsa_tipo on pbt_codigo = pb_tipo
					where (pb_status <> 'C')
					and (
						(pb_ano = '".(date("Y")-1)."' and (pbt_edital = 'PIBIC' or pbt_edital = 'PIBITI' or pbt_edital = 'IS'))
						or
						(pb_ano = '".(date("Y"))."' and (pbt_edital = 'PIBICE'))
						)
					group by pb_ano, pbt_edital, valid
					order by pbt_edital
					";
			$rlt = db_query($sql);
			$xtp = 'x';
			
			$ar = array();
			$ar1 = array();
			$ar2 = array();
			$ar3 = array();
			$tot0 = 0;
			$tot1 = 0;
			while ($line = db_read($rlt))
				{
					$tot0 = $tot0 + $line['total'];
					$tp = trim($line['pbt_edital']);
					$vl = UpperCase(trim($line['valid']));
					$x=-1;
		
					for ($r=0;$r < count($ar);$r++)
						{ if (trim($ar[$r]) == $tp) { $x = $r; } }
						
					if ($vl=='F')
						{ $tp0 = $line['total']; $tp1 = 0; } else 
						{ $tp0 = 0; $tp1 = $line['total']; }
					$tot1 = $tot1 + $tp0;
					if ($x==-1)
						{
							array_push($ar,$tp);
							array_push($ar1,$tp0);
							array_push($ar2,$tp1);
						} else {
							$ar1[$x] = $ar1[$x] + $tp0;
							$ar2[$x] = $ar2[$x] + $tp1; 
						}					
				}
				
				/* Grafico */
				$sx = '<table>';
				$sx .= '<TR><TD colspan=5><h2>Ratificação dos Planos para o SEMIC</h2>';
				$sx .= '<TR><TH>Modalidade<TH>ó entregue<TH>Entregue<TH>Grófico';
				$sx .= '<TH>Percentual';
				$sx .= '<TH rowspan=10 width="30" class="tabela01">Enviado<h2>';
				if ($tot0 > 0)
					{
						$sx .= '<center>';
						$sx .= number_format((1-($tot1/$tot0))*100,1,',','.').'%';
					}
				$sx .= '</h2>';
				$sx .= $tot1.'/'.$tot0;
				for ($r=0;$r < count($ar);$r++)
					{
						$sx .= '<TR>';
						$sx .= '<TD>';
						$sx .= $ar[$r];

						$sx .= '<TD align="center">';
						$sx .= $ar1[$r];

						$sx .= '<TD align="center">';
						$sx .= $ar2[$r];
						
						$sx .= '<TD>';
						$sz1 = $ar1[$r] + $ar2[$r];
						$st1 = round(500 * $ar1[$r] / $sz1) ;
						$st2 = 500 - $st1;
						$sx .= '<img src="'.http.'img/gr_green.png" height="20" width="'.$st2.'">';
						$sx .= '<img src="'.http.'img/gr_metter.png" height="20" width="'.$st1.'">';
						$sx .= '<TD align="right">';
						$sx .= number_format((100 * $ar2[$r] / $sz1),2,',','.').'%';
					} 
				$sx .= '</table>';
				return($sx);
		}	

	function acompanhamento_relatorio_final($mod='')
		{
			if (strlen($mod)==0)
				{
					$wh = " and (pbt_edital = 'PIBIC' or pbt_edital = 'PIBITI' or pbt_edital = 'IS') ";
				} else {
					$wh = " and (pbt_edital = '$mod') ";
				}
			$sql = "select (pb_relatorio_final > 20100101) as valid, 
					count(*) as total, pb_ano, pbt_edital
				from ".$this->tabela." 
				 inner join pibic_bolsa_tipo on pbt_codigo = pb_tipo
					where (pb_status <> 'C')
					and pb_ano = '".(date("Y")-1)."' 
					$wh
					group by pb_ano, pbt_edital, valid
					order by pbt_edital
					";
			$rlt = db_query($sql);
			$xtp = 'x';
			
			$ar = array();
			$ar1 = array();
			$ar2 = array();
			$tot0 = 0;
			$tot1 = 0;
			while ($line = db_read($rlt))
				{
					$tot0 = $tot0 + $line['total'];
					$tp = trim($line['pbt_edital']);
					$vl = UpperCase(trim($line['valid']));
					$x=-1;
		
					for ($r=0;$r < count($ar);$r++)
						{ if (trim($ar[$r]) == $tp) { $x = $r; } }
						
					if ($vl=='F')
						{ $tp0 = $line['total']; $tp1 = 0; } else 
						{ $tp0 = 0; $tp1 = $line['total']; }
					$tot1 = $tot1 + $tp0;
					if ($x==-1)
						{
							array_push($ar,$tp);
							array_push($ar1,$tp0);
							array_push($ar2,$tp1);
						} else {
							$ar1[$x] = $ar1[$x] + $tp0;
							$ar2[$x] = $ar2[$x] + $tp1; 
						}					
				}
				
				/* Grafico */
				$sx = '<table>';
				$sx .= '<TR><TD colspan=5><h2>Entrega do relatório Final</h2>';
				$sx .= '<TR><TH>Modalidade<TH>ó entregue<TH>Entregue<TH>Grófico';
				$sx .= '<TH>Percentual';
				$sx .= '<TH rowspan=10 width="30" class="tabela01">Enviado<h2>';
				if ($tot0 > 0)
					{
						$sx .= '<center>';
						$sx .= number_format((1-($tot1/$tot0))*100,1,',','.').'%';
					}
				$sx .= '</h2>';
				$sx .= $tot1.'/'.$tot0;
				for ($r=0;$r < count($ar);$r++)
					{
						$sx .= '<TR>';
						$sx .= '<TD>';
						$sx .= $ar[$r];

						$sx .= '<TD align="center">';
						$sx .= $ar1[$r];

						$sx .= '<TD align="center">';
						$sx .= $ar2[$r];
						
						$sx .= '<TD>';
						$sz1 = $ar1[$r] + $ar2[$r];
						$st1 = round(500 * $ar1[$r] / $sz1) ;
						$st2 = 500 - $st1;
						$sx .= '<img src="'.http.'img/gr_green.png" height="20" width="'.$st2.'">';
						$sx .= '<img src="'.http.'img/gr_metter.png" height="20" width="'.$st1.'">';
						$sx .= '<TD align="right">';
						$sx .= number_format((100 * $ar2[$r] / $sz1),2,',','.').'%';
					} 
				$sx .= '</table>';
				return($sx);
		}	

	function acompanhamento_resumo()
		{
			$sql = "select (pb_resumo > 20100101) as valid, 
					count(*) as total, pb_ano, pbt_edital
				from ".$this->tabela." 
				 inner join pibic_bolsa_tipo on pbt_codigo = pb_tipo
					where (pb_status <> 'C')
					and pb_ano = '".(date("Y")-1)."' 
					and (pbt_edital = 'PIBIC' or pbt_edital = 'PIBITI' or pbt_edital = 'IS')
					group by pb_ano, pbt_edital, valid
					order by pbt_edital
					";
				
			$rlt = db_query($sql);
			$xtp = 'x';
			
			$ar = array();
			$ar1 = array();
			$ar2 = array();
			$tot0 = 0;
			$tot1 = 0;
			while ($line = db_read($rlt))
				{
					$tot0 = $tot0 + $line['total'];
					$tp = trim($line['pbt_edital']);
					$vl = UpperCase(trim($line['valid']));
					$x=-1;
		
					for ($r=0;$r < count($ar);$r++)
						{ if (trim($ar[$r]) == $tp) { $x = $r; } }
						
					if ($vl=='F')
						{ $tp0 = $line['total']; $tp1 = 0; } else 
						{ $tp0 = 0; $tp1 = $line['total']; }
					$tot1 = $tot1 + $tp0;
					if ($x==-1)
						{
							array_push($ar,$tp);
							array_push($ar1,$tp0);
							array_push($ar2,$tp1);
						} else {
							$ar1[$x] = $ar1[$x] + $tp0;
							$ar2[$x] = $ar2[$x] + $tp1; 
						}					
				}
				
				/* Grafico */
				$sx = '<table>';
				$sx .= '<TR><TD colspan=5><h2>Entrega do relatório Final</h2>';
				$sx .= '<TR><TH>Modalidade<TH>ó entregue<TH>Entregue<TH>Grófico';
				$sx .= '<TH>Percentual';
				$sx .= '<TH rowspan=10 width="30" class="tabela01">Enviado<h2>';
				if ($tot0 > 0)
					{
						$sx .= '<center>';
						$sx .= number_format((1-($tot1/$tot0))*100,1,',','.').'%';
					}
				$sx .= '</h2>';
				$sx .= $tot1.'/'.$tot0;
				for ($r=0;$r < count($ar);$r++)
					{
						$sx .= '<TR>';
						$sx .= '<TD>';
						$sx .= $ar[$r];

						$sx .= '<TD align="center">';
						$sx .= $ar1[$r];

						$sx .= '<TD align="center">';
						$sx .= $ar2[$r];
						
						$sx .= '<TD>';
						$sz1 = $ar1[$r] + $ar2[$r];
						$st1 = round(500 * $ar1[$r] / $sz1) ;
						$st2 = 500 - $st1;
						$sx .= '<img src="'.http.'img/gr_green.png" height="20" width="'.$st2.'">';
						$sx .= '<img src="'.http.'img/gr_metter.png" height="20" width="'.$st1.'">';
						$sx .= '<TD align="right">';
						$sx .= number_format((100 * $ar2[$r] / $sz1),2,',','.').'%';
					} 
				$sx .= '</table>';
				return($sx);
		}
	function acompanhamento_relatorio_parcial()
		{
			$sql = "select (pb_relatorio_parcial > 20100101) as valid, 
					count(*) as total, pb_ano, pbt_edital
				from ".$this->tabela." 
				 inner join pibic_bolsa_tipo on pbt_codigo = pb_tipo
					where (pb_status <> 'C')
					and pb_ano = '".(date("Y")-1)."' 
					and (pbt_edital = 'PIBIC' or pbt_edital = 'PIBITI' or pbt_edital = 'IS')
					group by pb_ano, pbt_edital, valid
					order by pbt_edital
					";
			$rlt = db_query($sql);
			$xtp = 'x';
			
			$ar = array();
			$ar1 = array();
			$ar2 = array();
			$tot0 = 0;
			$tot1 = 0;
			while ($line = db_read($rlt))
				{
					$tot0 = $tot0 + $line['total'];
					$tp = trim($line['pbt_edital']);
					$vl = UpperCase(trim($line['valid']));
					$x=-1;
		
					for ($r=0;$r < count($ar);$r++)
						{ if (trim($ar[$r]) == $tp) { $x = $r; } }
						
					if ($vl=='F')
						{ $tp0 = $line['total']; $tp1 = 0; } else 
						{ $tp0 = 0; $tp1 = $line['total']; }
					$tot1 = $tot1 + $tp0;
					if ($x==-1)
						{
							array_push($ar,$tp);
							array_push($ar1,$tp0);
							array_push($ar2,$tp1);
						} else {
							$ar1[$x] = $ar1[$x] + $tp0;
							$ar2[$x] = $ar2[$x] + $tp1; 
						}					
				}
				
				/* Grafico */
				$sx = '<table>';
				$sx .= '<TR><TD colspan=5><h2>Entrega do relatório Parcial</h2>';
				$sx .= '<TR><TH>Modalidade<TH>ó entregue<TH>Entregue<TH>Grófico';
				$sx .= '<TH>Percentual';
				$sx .= '<TH rowspan=10 width="30" class="tabela01">Enviado<h2>';
				if ($tot0 > 0)
					{
						$sx .= '<center>';
						$sx .= number_format((1-($tot1/$tot0))*100,1,',','.').'%';
					}
				$sx .= '</h2>';
				$sx .= $tot1.'/'.$tot0;
				for ($r=0;$r < count($ar);$r++)
					{
						$sx .= '<TR>';
						$sx .= '<TD>';
						$sx .= $ar[$r];

						$sx .= '<TD align="center">';
						$sx .= $ar1[$r];

						$sx .= '<TD align="center">';
						$sx .= $ar2[$r];
						
						$sx .= '<TD>';
						$sz1 = $ar1[$r] + $ar2[$r];
						$st1 = round(500 * $ar1[$r] / $sz1) ;
						$st2 = 500 - $st1;
						$sx .= '<img src="'.http.'img/gr_green.png" height="20" width="'.$st2.'">';
						$sx .= '<img src="'.http.'img/gr_metter.png" height="20" width="'.$st1.'">';
						$sx .= '<TD align="right">';
						$sx .= number_format((100 * $ar2[$r] / $sz1),2,',','.').'%';
					} 
				$sx .= '</table>';
				return($sx);
		}		
	function acompanhamento_relatorio_parcial_correcao()
		{
			//$sql = "update ".$this->tabela." set pb_relatorio_parcial_correcao_nota = 0 where pb_ano = '2013' ";
			//$rlt = db_query($sql);
			
			$sql = "select (pb_relatorio_parcial_correcao > 20100101) as valid, 
					count(*) as total, pb_ano, pbt_edital
				from ".$this->tabela." 
				 inner join pibic_bolsa_tipo on pbt_codigo = pb_tipo
					where (pb_status <> 'C')
					and pb_ano = '".(date("Y")-1)."' 
					and (pbt_edital = 'PIBIC' or pbt_edital = 'PIBITI' or pbt_edital = 'IS')
					and (pb_relatorio_parcial_nota = 2)
					group by pb_ano, pbt_edital, valid
					order by pbt_edital
					";				
			$rlt = db_query($sql);
			$xtp = 'x';
			
			$ar = array();
			$ar1 = array();
			$ar2 = array();
			$tot0 = 0;
			$tot1 = 0;
			while ($line = db_read($rlt))
				{
					$tot0 = $tot0 + $line['total'];
					$tp = trim($line['pbt_edital']);
					$vl = UpperCase(trim($line['valid']));
					$x=-1;
		
					for ($r=0;$r < count($ar);$r++)
						{ if (trim($ar[$r]) == $tp) { $x = $r; } }
						
					if ($vl=='F')
						{ $tp0 = $line['total']; $tp1 = 0; } else 
						{ $tp0 = 0; $tp1 = $line['total']; }
					$tot1 = $tot1 + $tp0;
					if ($x==-1)
						{
							array_push($ar,$tp);
							array_push($ar1,$tp0);
							array_push($ar2,$tp1);
						} else {
							$ar1[$x] = $ar1[$x] + $tp0;
							$ar2[$x] = $ar2[$x] + $tp1; 
						}					
				}
				
				/* Grafico */
				$sx = '<table>';
				$sx .= '<TR><TD colspan=5><h2>Entrega do relatório Parcial</h2>';
				$sx .= '<TR><TH>Modalidade<TH>ó entregue<TH>Entregue<TH>Grófico';
				$sx .= '<TH>Percentual';
				$sx .= '<TH rowspan=10 width="30" class="tabela01">Enviado<h2>';
				if ($tot0 > 0)
					{
						$sx .= '<center>';
						$sx .= number_format((1-($tot1/$tot0))*100,1,',','.').'%';
					}
				$sx .= '</h2>';
				$sx .= $tot1.'/'.$tot0;
				for ($r=0;$r < count($ar);$r++)
					{
						$sx .= '<TR>';
						$sx .= '<TD>';
						$sx .= $ar[$r];

						$sx .= '<TD align="center">';
						$sx .= $ar1[$r];

						$sx .= '<TD align="center">';
						$sx .= $ar2[$r];
						
						$sx .= '<TD>';
						$sz1 = $ar1[$r] + $ar2[$r];
						$st1 = round(500 * $ar1[$r] / $sz1) ;
						$st2 = 500 - $st1;
						$sx .= '<img src="'.http.'img/gr_green.png" height="20" width="'.$st2.'">';
						$sx .= '<img src="'.http.'img/gr_metter.png" height="20" width="'.$st1.'">';
						$sx .= '<TD align="right">';
						$sx .= number_format((100 * $ar2[$r] / $sz1),2,',','.').'%';
					} 
				$sx .= '</table>';
				return($sx);
		}		
	function fixa_area($proto,$area)
		{
			$proto = trim($proto);
			if (strlen($proto) > 0)
			{
				$sql = "update ".$this->tabela." set pb_semic_area = '$area' 
					where pb_protocolo = '$proto' ";
				$rlt = db_query($sql);
			}
			return(1);
		}
	function postar_relatorio_parcial($proto)
		{
			if (strlen($proto) > 5)
			{
			$data = date("Ymd");
			$sql = "update ".$this->tabela." 
					set pb_relatorio_parcial = $data,
					pb_relatorio_parcial_nota = -99
					where pb_protocolo = '$proto'
					";
			$rlt = db_query($sql);
			}
			return(1);
		}
	function postar_relatorio_parcial_correcao($proto)
		{
			if (strlen($proto) > 5)
			{
			$data = date("Ymd");
			$sql = "update ".$this->tabela." 
					set pb_relatorio_parcial_correcao = $data,
					pb_relatorio_parcial_correcao_nota = -99
					where pb_protocolo = '$proto'
					";
			$rlt = db_query($sql);
			}
			return(1);	
		}
	function postar_resumo($proto)
		{
			if (strlen($proto) > 5)
			{
			$data = date("Ymd");
			$sql = "update ".$this->tabela." 
					set pb_resumo = $data,
					pb_resumo_nota = -99
					where pb_protocolo = '$proto'
					";
			$rlt = db_query($sql);

			$hist = 'Postagem do resumo';
			$motivo = '061';
			$ac = 061;
			$aluno1 = '';
			$aluno2 = '';
			$his = new pibic_historico;
			$his->inserir_historico($proto,$ac,$hist,$aluno1,$aluno2,$motivo);
			}
			return(1);
		}		
	function postar_relatorio_final($proto)
		{
			if (strlen($proto) > 5)
			{
			$data = date("Ymd");
			$sql = "update ".$this->tabela." 
					set pb_relatorio_final = $data,
					pb_relatorio_final_nota = -99
					where pb_protocolo = '$proto'
					";
			$rlt = db_query($sql);
			}
			return(1);
		}
	function postar_relatorio_resumo($proto)
		{
			if (strlen($proto) > 5)
			{
			$data = date("Ymd");
			$sql = "update ".$this->tabela." 
					set pb_resumo = $data,
					pb_resumo_nota = -99
					where pb_protocolo = '$proto'
					";
			$rlt = db_query($sql);
			}
			return(1);
		}				
		
		
	function fixa_idioma($proto,$idioma)
		{
			$proto = trim($proto);
			if (strlen($proto) > 0)
			{
				$sql = "update ".$this->tabela." set pb_semic_idioma = '$idioma' 
					where pb_protocolo = '$proto' ";
				$rlt = db_query($sql);
			} else {
				echo 'Protocolo nóo informado';
			}
			return(1);
		}		
	function grafico_orientacoes($arr)
		{
			global $gr;
			$ai = array();
			if (date("m") > 8)
				{ array_push($ai,array(date("Y"),0,0,0,0,0,0,0)); }
			array_push($ai,array(date("Y")-1,0,0,0,0,0,0,0));
			array_push($ai,array(date("Y")-2,0,0,0,0,0,0,0));
			array_push($ai,array(date("Y")-3,0,0,0,0,0,0,0));
			array_push($ai,array(date("Y")-4,0,0,0,0,0,0,0));
			
			for ($r=0;$r < count($arr);$r++)
				{
					$line = $arr[$r];
					$edital = trim($line['pbt_edital']);
					$ano = round($line['pb_ano']);
					$ok = 0;
					for ($y=0;$y < count($ai);$y++)
						{
							if ($ai[$y][0]==$ano)
								{
									if ($edital == 'CSF') { $ai[$y][4] = $ai[$y][4] +1; }
									if ($edital == 'IS') { $ai[$y][1] = $ai[$y][1] +1; }
									if ($edital == 'PIBIC') { $ai[$y][1] = $ai[$y][1] +1; }
									if ($edital == 'PIBITI') { $ai[$y][2] = $ai[$y][2] +1; }
									if ($edital == 'PIBICE') { $ai[$y][3] = $ai[$y][3] +1; }
								}
						}
				}
					/* legenda */
					$hg = 12;
					$legend  = '<img src="'.http.'img/img_icone_boneco_01.png" height="'.$hg.'" title="PIBIC">'.chr(13);
					$legend .= 'PIBIC, ';
					$legend .= '<img src="'.http.'img/img_icone_boneco_02.png" height="'.$hg.'" title="PIBIC">'.chr(13);
					$legend .= 'PIBITI, ';
					$legend .= '<img src="'.http.'img/img_icone_boneco_03.png" height="'.$hg.'" title="PIBIC">'.chr(13);
					$legend .= 'PIBIC Jr';					
					
					$sx = $gr->grafico_bonecos($ai,'orientacoes_ic',$legend);
						
					return($sx);
				}

		
	function discentes_ic($cracha)
		{
			$cp = 'pb_protocolo, pb_protocolo_mae, pb_aluno, pb_professor,';
			$cp .= 'pb_titulo_projeto, pb_status, pb_ano, pa_nome, pb_aluno, ';
			$cp .= 'pbt_descricao, pbt_edital';
			$sql = "select $cp from ".$this->tabela." 
					inner join pibic_aluno on pa_cracha = pb_aluno
					inner join pibic_bolsa_tipo on pbt_codigo = pb_tipo
					where pb_aluno = '".$cracha."' 
					and pb_status <> 'C'
					order by pb_ano desc, pb_protocolo
			";
			$rlt = db_query($sql);
			$ar = array();
			while ($line = db_read($rlt))
			{
				array_push($ar,$line);
			}
			return($ar);
		}

	function orientacoes_ic($cracha)
		{
			$cp = 'pb_protocolo, pb_protocolo_mae, pb_aluno, pb_professor,';
			$cp .= 'pb_titulo_projeto, pb_status, pb_ano, pa_nome, pb_aluno, ';
			$cp .= 'pbt_descricao, pbt_edital';
			$sql = "select $cp from ".$this->tabela." 
					inner join pibic_aluno on pa_cracha = pb_aluno
					inner join pibic_bolsa_tipo on pbt_codigo = pb_tipo
					where pb_professor = '".$cracha."' 
					and pb_status <> 'C'
					order by pb_ano desc, pb_protocolo
			";
			$rlt = db_query($sql);
			$ar = array();
			while ($line = db_read($rlt))
			{
				array_push($ar,$line);
			}
			return($ar);
		}
		
	function mostra_contratos($protocolo)
		{
			$sql = "select * from pibic_documento where doc_dd0 = '$protocolo' ";
			$rlt = db_query($sql);
			
			while ($line = db_read($rlt))
				{
					$contrato = trim($line['doc_texto_asc']);
					$xfile = http.'pibic/contrato/'.substr($line['doc_data'],0,4).'/';
					
					$sx .= '<BR><A HREF="'.$xfile.$contrato.'" title="contrato">Contrato</A>'; 
				}
			return($sx);
			
		}
				
	function mostra_orientacoes_ic($arr,$docs=0,$contrato=0)
		{
			$xano = 9999;
			if (count($arr)==0) { return(''); }
			$sx = '<table width="100%" class="tabela01">';
			for ($r=0;$r < count($arr);$r++)
				{
					$line = $arr[$r];
					$ano = $line['pb_ano'];
					if ($xano != $ano)
						{
							$sx .= '<TR><TD colspan=2>';
							$sx .= '<h2>'.$ano.'</h2>';
							$xano = $ano;
						}
					$sx .= '<TR valign="top">';
					$sx .= '<TD>';
					$sx .= trim($line['pb_protocolo']);
					$sx .= '<TD>';
					$sx .= '<B>'.trim($line['pb_titulo_projeto']).'</B>';
					$sx .= '<BR>';
					$sx .= '<I>'.$line['pa_nome'].'</I>';
					$sx .= ' ('.trim($line['pbt_edital']).'-'.trim($line['pbt_descricao']).')';
					
					$sx .= $this->mostra_contratos(trim($line['pb_protocolo']));
				}
			if (count($arr) ==0)
				{
					$sx .= '<TR><TD0>';
					$sx .= '<center>';
					$sx .= msg('not_found');
				}
			$sx .= '</table>';
			return($sx);
		}
	function relatorio_centro_estudante($ano)
		{
			$cp = 'pbt_edital, pbt_descricao, ';
			$cp = 'pa_centro, pbt_edital ';
			$sql = "select count(*) as total, $cp from ".$this->tabela." 
					 inner join pibic_bolsa_tipo on pbt_codigo = pb_tipo 
					 inner join pibic_aluno on pa_cracha = pb_aluno
			 		 where pb_ano = '$ano' and pb_status <> 'C' 
			 		 group by $cp
			 		 order by $cp
			 		 ";
			$rlt = db_query($sql);
			$sx = '<table class="tabela01" width="80%" border=0>';
			$sx .= '<TR><TH>'.msg('bolsa').' '.$ano.'';
			$xcentro = "X";
			$tot = 0;
			$tot1 = 0;
			while ($line = db_read($rlt))					
				{
					
				$centro = trim($line['pa_centro']);
				if ($centro != $xcentro)
					{
						if ($tot1 > 0)
							{
								$sx .= '<TR><TD colspan=3 align="right">';
								$sx .= '<I>Sub-total '.$tot1;		
							}
						$sx .= '<TR><TD colspan=4><h3>';
						$sx .= $centro;
						$sx .= '</h3>';
						$xcentro = $centro;
						$tot1 = 0;
					}				
				$edital = trim($line['pbt_edital']);
				$total = $line['total'];
				$total = round($total / 20);
				$sx .= '<TR>';
				$sx .= '<TD>';
				$sx .= $line['pbt_edital'].chr(13);
				$sx .= '<TD align="left">';
				$sx .= $line['curso_nome'].chr(13);
				$sx .= '<TD align="left">';
				
				$sx .= $line['total'].chr(13);
				$tot1 = $tot1 + $line['total'];
				$tot = $tot + $line['total'];
				}
				if ($tot1 > 0)
					{
						$sx .= '<TR><TD colspan=4 align="right">';
						$sx .= '<I>Sub-total '.$tot1;		
					}
				if ($tot > 0)
					{
						$sx .= '<TR><TD colspan=4 align="right">';
						$sx .= '<I>Total '.$tot;		
					}
				$sx .= '</table>';				
			return($sx);
		}		
		
	function relatorio_escola_estudante($ano)
		{
			$cp = 'pbt_edital, pbt_descricao, ';
			$cp = 'pbt_edital, centro_nome, curso_nome ';
			$sql = "select count(*) as total, $cp from ".$this->tabela." 
					 inner join pibic_bolsa_tipo on pbt_codigo = pb_tipo 
					 inner join pibic_aluno on pa_cracha = pb_aluno
					 left join curso on pa_curso_cod = curso_codigo
					 left join centro on curso_centro = centro_codigo
			 		 where pb_ano = '$ano' and pb_status <> 'C' 
			 		 group by $cp
			 		 order by $cp
			 		 ";
			$rlt = db_query($sql);
			$sx = '<table class="tabela01">';
			$sx .= '<TR><TH>'.msg('bolsa');
			$xcentro = "X";
			$tot = 0;
			$tot1 = 0;
			while ($line = db_read($rlt))					
				{
				$centro = trim($line['centro_nome']);
				if ($centro != $xcentro)
					{
						if ($tot1 > 0)
							{
								$sx .= '<TR><TD colspan=3 align="right">';
								$sx .= '<I>Sub-total '.$tot1;		
							}
						$sx .= '<TR><TD colspan=4><h3>';
						$sx .= $centro;
						$sx .= '</h3>';
						$xcentro = $centro;
						$tot1 = 0;
					}
				$edital = trim($line['pbt_edital']);
				$total = $line['total'];
				$total = round($total / 20);
				$sx .= '<TR>';
				$sx .= '<TD>';
				$sx .= $line['pbt_descricao'].chr(13);
				$sx .= '<TD>';
				$sx .= $line['pbt_edital'].chr(13);
				$sx .= '<TD align="left">';
				$sx .= $line['curso_nome'].chr(13);
				$sx .= '<TD align="left">';
				
				$sx .= $line['total'].chr(13);
				$tot1 = $tot1 + $line['total'];
				$tot = $tot + $line['total'];
				}
				if ($tot1 > 0)
					{
						$sx .= '<TR><TD colspan=4 align="right">';
						$sx .= '<I>Sub-total '.$tot1;		
					}
				if ($tot > 0)
					{
						$sx .= '<TR><TD colspan=4 align="right">';
						$sx .= '<I>Total '.$tot;		
					}
				$sx .= '</table>';				
			return($sx);
		}
		
	function relatorio_escola_professor($ano)
		{
			$cp = 'pbt_edital, pbt_descricao';
			$cp = 'pbt_edital';
			$sql = "select count(*) as total, $cp from ".$this->tabela." 
					 inner join pibic_bolsa_tipo on pbt_codigo = pb_tipo 
			 		 where pb_ano = '$ano' and pb_status <> 'C' 
			 		 group by $cp
			 		 order by $cp
			 		 ";
			
			$rlt = db_query($sql);
			
			$sx = '<table class="tabela01">';
			$sx .= '<TR><TH>'.msg('bolsa');
			while ($line = db_read($rlt))
				{
				$edital = trim($line['pbt_edital']);
				$total = $line['total'];
				$total = round($total / 20);
				$sx .= '<TR>';
				$sx .= '<TD>';
				$sx .= $line['pbt_descricao'].chr(13);
				$sx .= '<TD>';
				$sx .= $line['pbt_edital'].chr(13);
				$sx .= '<TD align="right">';
				$sx .= $line['total'].chr(13);
				$sx .= '<TD align="left">';
				$sx .= '<img src="'.http.'/iconografico.php?dd0=PSN&dd1='.trim($line['pbt_edital']).'&dd2='.$total.'&dd50=400&dd51=26&dd52=5&dd5=A0A0A0">'.chr(13);						
				}
			$sx .= '</table>';
			return($sx);
		}
		
	function orienacao_pibic_programa($programa_pos,$areas,$programa_pos_anoi=1900,$programa_pos_anof=2999)
		{	
			$sql = "select * from (			
						select count(*) as total, pb_professor, pbt_edital, pb_ano 
						from pibic_bolsa_contempladas 
						inner join pibic_bolsa_tipo on pbt_codigo = pb_tipo
						inner join 
							(select pdce_docente, pdce_programa from programa_pos_docentes group by pdce_docente, pdce_programa) as tabela04 on pdce_docente = pb_professor
						where (pb_status <> 'C' and pb_status <> '@' )
						and (pb_ano >= '$programa_pos_anoi' and pb_ano <= '$programa_pos_anof')
						and pdce_programa = '".$programa_pos."'
						group by pb_professor, pbt_edital, pb_ano
						) as tabela
						inner join pibic_professor on pp_cracha = pb_professor
						order by pb_ano, pp_nome, pbt_edital
					";
			$rlt = db_query($sql);
			
			$tt1 = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
			$toa = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
			$tot = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
			$xprof = 'x';
			$xano = '0000';
			$sx = '<table width="704" class="lt0" cellpadding=0 cellspacing=2 border=1>';
			$sh = '<TR><TH>Prof.<TH>Ano<TH>PIBIC<TH>PIBITI<TH>PIBIC_EM<TH>Inclusão<TH>NA<TH>CSF<TH>Outros';
			while($line = db_read($rlt))
				{

					$id = 6;
					$edital = trim($line['pbt_edital']);
					$total = $line['total'];
					$ano = $line['pb_ano'];
					$link = '<A HREF="../cip/docentes_detalhe.php?dd0='.trim($line['pb_professor']).'&dd90='.checkpost(trim($line['pb_professor'])).'" targer="new">';
					$prof = trim($line['pp_nome']);
					$prof .= ' ('.$link.trim($line['pb_professor']).'</A>)';
					if ($edital == 'PIBIC') { $id = 0; }
					if ($edital == 'PIBITI') { $id = 1; }
					if ($edital == 'PIBIC_EM') { $id = 2; }
					if ($edital == 'PIBICE') { $id = 2; }
					if ($edital == 'CSF') { $id = 4; }
								
					if ($prof != $xprof)
						{
							$sa = '<TR '.coluna().'><TD>'.$xprof;
							$sa .= '<TD align="center">'.$line['pb_ano'];
							$tt = 0;
							for ($r=0;$r <= 6;$r++)
								{ $sa .= '<TD align="center" width="7%">'; $sa .= $totp[$r]; $tt = $tt + $totp[$r]; }
							$totp = $to1;
							$xprof = $prof;
							if ($tt > 0) { $sx .= $sa; }
						}
					if ($xano != $ano)
						{ $sx .= '<TR><TD colspan=10><h4><center>'.$ano.'</H4>'; $xano = $ano;
							$sx .= $sh;
						}					


					$totp[$id] = $totp[$id] + $total;
					$tot[$id] = $tot[$id] + $total;
				}

				$sa = '<TR '.coluna().'><TD>'.$xprof;
				$sa .= '<TD align="center">'.$line['pb_ano'];
				$tt = 0;
				for ($r=0;$r <= 6;$r++)
					{ $sa .= '<TD align="center" width="7%">'; $sa .= $totp[$r]; $tt = $tt + $totp[$r]; }
					$totp = $to1;
					$xprof = $prof;
				if ($tt > 0) { $sx .= $sa; }

				$sa = '<TR '.coluna().'><TD><B>Total';
				$sa .= '<TD align="center">';
				$tt = 0;
				for ($r=0;$r <= 6;$r++)
					{ $sa .= '<TD align="center" width="7%"><B>'; $sa .= $tot[$r]; $tt = $tt + $tot[$r]; }
					$totp = $to1;
					$xprof = $prof;
				if ($tt > 0) { $sx .= $sa; }


			$sx .= '</table>';
			return($sx);
			
		}		
		
	function orientacao_pibic($programa_pos,$areas,$programa_pos_anoi=1900,$programa_pos_anof=2999)
		{
			$sql = "select * from pibic_bolsa_contempladas
					    inner join pibic_bolsa_tipo on pbt_codigo = pb_tipo
						inner join ( select pdce_docente from programa_pos_docentes where pdce_programa = '".$programa_pos."' group by pdce_docente) as docentes2 on pdce_docente = pb_professor ";					    					  
			$sql .= " where ";
			$sql .= " (pb_status <> 'C' and pb_status <> '@' )";
			$sql .= " and (pb_ano >= '$programa_pos_anoi' and pb_ano <= '$programa_pos_anof')";
			$sql .= " order by pb_ano, pbt_edital ";
			//$sql .= " group by pb_ano, pbt_codigo ";
			
			$rlt = db_query($sql);
			
			$ori = array(0,0,0,0,0,0,0,0,0,0);
			$pb = array();
			for ($r=1970;$r <= date("Y");$r++)
				{
					array_push($pb,$ori);
					$pb[$r-1970][0] = $r;
				}
			$sx = '';
			while ($line = db_read($rlt))
				{
					$edital = trim($line['pbt_edital']);
					$ano = round(trim($line['pb_ano']));
					$pos = ($ano-1970);
					
					$id=8;
					if ($edital=='PIBIC') { $id=1; }
					if ($edital=='PIBITI') { $id=2; }
					if ($edital=='PIBIC_EM') { $id=3; }
					if ($edital=='IS') { $id=4; }
					if ($edital=='CSF') { $id=5; }
					
					$pb[$pos][$id] = (($pb[$pos][$id]) + 1);
					//echo '<BR>'.$pos.'-'.$id.'='.$pb[$pos][$id];
					//$sx .= '<TR>';
					//$sx .= '<TD>';
					//$sx .= $edital;
					//$sx .= '<TD>';
					//$sx .= $ano;
					//$sx .= '<TD>'.$line['pb_titulo_projeto']; 
					//$sx .= '<TD>';
					//$sx .= $line['pb_professor'];
				}
			$tot1=0;
			$tot2=0;
			$tot3=0;
			$tot4=0;
			$tot5=0;
			$tot6=0;
			$tot7=0;
			$tot8=0;
			for ($r=0;$r <= count($pb);$r++)
				{
					
					$tot = $pb[$r][1]+$pb[$r][2]+$pb[$r][3]+$pb[$r][4]+$pb[$r][5]+$pb[$r][8];
					
					if ($tot > 0)
						{
							$sb .= '<TR>';
							$sb .= '<TD align="center" class="tabela01">';
							$sb .= $pb[$r][0];
							$sb .= '<TD align="center" class="tabela01">';
							$sb .= $pb[$r][1];
							$sb .= '<TD align="center" class="tabela01">';
							$sb .= $pb[$r][2];
							$sb .= '<TD align="center" class="tabela01">';
							$sb .= $pb[$r][3];
							$sb .= '<TD align="center" class="tabela01">';
							$sb .= $pb[$r][4];
							$sb .= '<TD align="center" class="tabela01">';
							$sb .= $pb[$r][5];
							$sb .= '<TD align="center" class="tabela01">';
							$sb .= $pb[$r][8];
							$tot1=$tot1+$pb[$r][1];
							$tot2=$tot2+$pb[$r][2];
							$tot3=$tot3+$pb[$r][3];
							$tot4=$tot4+$pb[$r][4];
							$tot5=$tot5+$pb[$r][5];
							$tot6=$tot6+$pb[$r][6];
							$tot7=$tot7+$pb[$r][7];
							$tot8=$tot8+$pb[$r][8];
						}
					
				}
			$sa = '<table class="tabela00" width="704" border=0>';
			$sa .= '<TR align="center"><TH>ano';
			$sa .= '<TH width="10%">PIBIC<TH width="10%">PIBITI<TH width="10%">PIBIC_EM(Jr)
					<TH width="10%">Inclusão<TH width="10%">NA<TH width="10%">Outros';
			$sa .= $sb;
			$sa .= '<TR align="center"><TH>Total<TH width="10%">'.$tot1.'<TH width="10%">'.$tot2.'<TH width="10%">'.$tot3.'
					<TH width="10%">'.$tot4.'<TH width="10%">'.$tot5.'<TH width="10%">'.$tot8;
			$sa .= '</table>';			
			
			
			$sc = '<table width="100%" class="tabela00">'.$sx.'</table>';
			return($sa.''.$sc);
		}		 
		
		function indicador_orientandos($ano='',$tipo='')
			{
				$wh = " and ((pbt_edital = 'PIBIC') or (pbt_edital = 'PIBITI')) ";
				$ano = date("Y");
				$sql = "select count(*) as total, pb_professor, pb_ano,
						ap_tit_titulo
						from ".$this->tabela."
						inner join pibic_professor on pb_professor = pp_cracha
						inner join pibic_bolsa_tipo on pb_tipo = pbt_codigo
						left join apoio_titulacao on pp_titulacao =  ap_tit_codigo
						where pb_status <> 'C'
						$wh
						group by pb_professor, pb_ano, ap_tit_titulo
				";
				$rlt = db_query($sql);
				$ano = array();
				$mst = array();
				$dor = array();
				
				for ($r=2006;$r <= date("Y");$r++)
					{
						array_push($ano,$r);
						array_push($mst,0);
						array_push($dor,0);
					}
				$totp = 0;				
				while ($line = db_read($rlt))
					{
						$totp = $totp + $line['total'];
						
						$ano = $line['pb_ano']-2006;
						if ($ano < 0) { $ano = 0; }
						$tit = trim($line['ap_tit_titulo']);
						$prof = trim($line['pb_professor']);
						$total = $line['total'];
	
						$mss = 0;
						if ($tit == 'Esp.') { $mss = 1; }
						if ($tit == 'Msc.') { $mss = 1; }
						if ($tit == 'Dr.') { $mss = 2; }
						if ($tit == 'Dra.') { $mss = 2; }
						if ($mss == 0) { $mss = 1; }
						
						if ($mss == 1) { $mst[$ano] = $mst[$ano] + 1; }
						if ($mss == 2) { $dor[$ano] = $dor[$ano] + 1; }
					}
				
				$st = '<table width="705" class="lt1" align="center" cellpadding=0 cellspacing=2 border=1>';
				$st .= '<TR><TH>Ano';
				$sm = '<TR><TD>Mestres';
				$sd = '<TR><TD>Doutores';
				$sx = '
    <script type="text/javascript" src="http://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load(\'visualization\', \'1\', {packages: [\'corechart\']});
    </script>
    <script type="text/javascript">
      function drawVisualization() {
        // Create and populate the data table.
        var data = google.visualization.arrayToDataTable([
        [\'Ano\',   \'Mestre\', \'Doutor(a)\'],
      ';
	  for ($r=1;$r < count($mst);$r++)
	  		{
	  			$totp = $mst[$r] + $dor[$r];
	  			$st .= '<TH>'.(2006+$r);
				$sm .= '<TD align="center">'.$mst[$r];
				if ($totp  > 0) { $sm .= ' ('.number_format($mst[$r] / $totp * 100,1,',','.').'%)'; }			
				$sd .= '<TD align="center">'.$dor[$r];
				if ($totp > 0) { $sd .= ' ('.number_format($dor[$r] / $totp * 100,1,',','.').'%)'; }
	  			if ($r > 1) { $sx .= ', '.chr(13).chr(10); }
          		$sx .= '[\''.($r + 2006).'\','.$mst[$r].','.$dor[$r];     
          		$sx .= '  ]';
			}
      $sx .= '])';
	  $sx .= '
        // Create and draw the visualization.
        new google.visualization.ColumnChart(document.getElementById(\'visualization\')).
            draw(data,
                 {title:"Titulação dos pesquisadores em Iniciação Cientófica",
                  width:700, height:300,
                  vAxis: {title: "Prof."},
                  hAxis: {title: "Ano"}}
            );
      }
      google.setOnLoadCallback(drawVisualization);
	</script>
    <div id="visualization" style="width: 700px; height: 300px;"></div>';
	
	$sx .= $st . $sd . $sm . '</table>';
	return($sx);
				
	}
		function indicador_bolsa_completadas($filtro='',$tipo='')
			{
				$filtro = UpperCaseSql($filtro);
				
				$cp = "pb_ano, count(*) as total ";
				$sql = "select * from ".$this->tabela." 
						inner join pibic_professor on pp_cracha = pb_professor
						inner join pibic_aluno on pa_cracha = pb_aluno ";
				if ($tipo == 'E')
					{
						$sql .= " where Upper(ASC7(pa_curso)) like '%$filtro%' ";
					} else {
						$sql .= " where Upper(ASC7(pp_curso)) like '%$filtro%' ";
					}
					$sql .= " and pb_status <> 'C' order by pp_nome, pb_ano ";

				$rlt = db_query($sql);
				$xprof = 'x';
				$totp=0;
				
				$sx = '<table width="100%" class="lt1">';
				$tot = array();
				for ($r=2006;$r <= date("Y");$r++)
					{ array_push($tot,0); }
				while ($line = db_read($rlt))
					{
						$ano = $line['pb_ano'] - 2006;
						$tot[$ano] = $tot[$ano] + 1; 
						$prof = $line['pp_cracha'];
						if ($prof != $xprof)
							{
								$totp++;
								$sx .= '<TR><TD colspan=4 class="lt3"><B>';
								$sx .= $line['pp_nome'];
								$sx .= '<I>('.trim($line['pp_curso']).')</I>';
								$xprof = $prof;
							}

						$sx .= '<TR valign="top" '.coluna().'>';
						$sx .= '<TD>'.$line['pb_ano'];
						$sx .= '<TD>'.$line['pb_titulo_projeto'];
						$sx .= '<BR><B>'.$line['pa_nome'].'</B> ';
						$sx .= '('.trim($line['pa_curso']).')';
					}
				$sx .= '</table>';
				
				for ($r=0; $r < count($tot);$r++)
					{
						$st .= '<Th align="center" class="lt1"><center><B>'.(2006+$r);
						$sr .= '<Th align="center" class="lt4"><center>'.$tot[$r];
					}
				$sa = '<table cellpadding=3 cellspacing=0 border=1 width="'.(count($tot) * 60).'" class="lt2">';
				$sa .= '<TR><TD class="lt3" colspan="'.(count($tot)).'"><B><I><center>Resumo dos projetos / Orientador';
				IF ($tipo == 'E')
					{
						$sa .= '<TH rowspan=2><center>Tot.Estudantes';
					} else {
						$sa .= '<TH rowspan=2><center>Tot.Orientadores';
					}
				$sa .= '<TR align="center">'.$st.'';
				$sa .= '<TR align="center">'.$sr.'<TH class="lt4"><center>'.$totp;
				$sa .= '</table>';
				return($sa.$sx);
			}
		
		function rel_curso_bolsas($ano)
			{
				$sql = "select * from pibic_bolsa_contempladas 
				 	left join pibic_aluno on pb_aluno = pa_cracha 

				 	left join pibic_bolsa_tipo on pb_tipo = pbt_codigo 
					left join pibic_status on pb_status = ps_codigo 								
					where pb_status <> 'C' 
					and (pb_ano = '$ano')
					
					order by pa_curso
				";
				$rlt = db_query($sql);
				$ts = '';
				$tot = 0;
				$totc = 0;
				$xcurso = 'x';
				while ($line = db_read($rlt))
					{
						$curso = trim($line['pa_curso']);
						if ($curso != $xcurso)
							{
								if ($totc > 0)
								{
									$ts .= '<TR><TD0 align="right">Total do curso '.$totc;
								}
								$totc=0;
								$xcurso = $curso;
							}
						if (strpos($curso,'(') > 0)
							{ $curso = substr($curso,0,strpos($curso,'(')); }
						if (strlen(trim($line['pa_nome'])) > 0)
						{
							$ln = $line;
							$ts .= '<TR>';
							$ts .= '<TD>';
							$ts .= $curso;
							$ts .= '<TD>';
							$ts .= $line['pa_nome'];
							$ts .= '<TD>';
							$ts .= $line['pa_cracha'];
							$ts .= '<TD>';
							$ts .= $line['pbt_edital'];							
							$tot++;
							$totc++;
						}
					}
				echo '<table class="lt0">';
				echo '<TR><TD colspan=6 class="lt3">';
				echo 'Discentes envolvidos em pesquisa na Instituição';
					
				echo '<TR><TD colspan=6 class="lt0">';
				echo 'Legenda: PIBIC - Iniciação Cientófica; PIBITI - Iniciação Tecnológica; PIBIC_EM - Iniciação Jónior (Ens. Módio); CSF - Cióncia sem fronteiras';
				echo '<TR><TH width="20%">Curso<TH>Nome do Discente<TH>Código<TH>Modalidade';
				echo $ts;
				echo '<TR><TD colspan=5>Total '.$tot.' IC ativo';
				echo '</table>';
			}
		
		function mostra_simples($line)
			{
			$centro = $line['pa_centro'];
			$curso = $line['pa_curso'];
			$prof  = $line['pp_cracha'];
			$tipo = trim($line['pbt_descricao']);
			$edital = trim($line['pbt_edital']);
			$doc_protocolo = $line['pb_protocolo'];
			$area = trim($line['a_descricao']);
			$link = '<A HREF="pibic_detalhe.php?dd0='.trim($doc_protocolo).'&dd90='.checkpost($doc_protocolo).'">';
			$idioma = trim($line['pb_semic_idioma']);
			
			$sx .= '<TR '.coluna().' >';
			$sx .= '<TD width="50">'.$bolsa_img.'</TD>';
			$sx .= '<TD>';
			$sx .= 'Aluno '.$link.'<B>'.trim($line['pa_nome']).'</B></A> ('.$line['pp_cracha'].')';
			$sx .= ' '.trim($curso);
			$sx .= '<BR>';
			$sx .= $line['pb_titulo_projeto'];
			$sx .= '<BR>';
			$sx .= 'Bolsa: <B>'.$edital.'/'.$tipo.'</B> ';
			
			if (strlen($idioma) > 0)
				{
					$sx .= '<img src="'.http.'/img/img_flag_'.$idioma.'.png" height="12">';
				}
			if (strlen($area) > 0)
				{
					$sx .= ' #'.$area.' - ';
				}			
			
			$sx .= ' Protocolo: '.$link.$doc_protocolo.'</A>&nbsp;&nbsp;&nbsp;&nbsp;';
			if ($line['pb_ativacao'] != '19000101')
				{
				$sx .= 'Data ativação '.stodbr($line['pb_ativacao']);
				}

			$sx .= '</TR>';
			return($sx);
			}
		
		function semic_areas()
			{
				$tela = $this->pb_semic_curso_descricao;
				$sql = "select * from ".$this->tabela;
				$sql .= " left join pibic_bolsa_tipo on pb_tipo = pbt_codigo ";
				$sql .= " left join pibic_status on pb_status = ps_codigo ";
				$sql .= " left join docentes on pb_professor = pp_cracha ";
				$sql .= " left join pibic_aluno on pb_aluno = pa_cracha ";
				$sql .= " left join ajax_areadoconhecimento on a_cnpq = pb_semic_area ";
				//$sql .= " left join pibic_areas on psa_codigo = pse_area ";
				$sql .= " where (pb_status <> 'C')";
				$sql .= " and pb_ano = '2012' "; 
				$sql .= " order by a_cnpq ";

				$rlt = db_query($sql);
				$xarea = 'X';
				$tot = 0;
				$sx .= '<table>';
				while ($line = db_read($rlt))
					{
						$area = trim($line['a_cnpq']);
						if ($area != $xarea)
							{
								$sx .= '<TR><TD colspan=5>'.$area.' '.$line['a_descricao'];
								$xarea = $area;
							}
						$sx .= $this->mostra_simples($line);
					}
				$sx .= '</table>';
				return($sx);
			}
		/** Trocas */
		function relatorio_trocas($ano='',$bolsa='')
			{
				global $dd;
				$sql = "update pibic_bolsa_historico set bh_acao = 92
						where bh_historico like '%roca de modalidade de bolsa para o protocolo%' 
						 ";
				$rlt = db_query($sql);
				
				if (strlen($bolsa) > 0)
					{ $tipo = " and (pb_tipo = '$bolsa' )"; }
				
				$sql = "select * from pibic_bolsa_historico
						inner join ".$this->tabela." on bh_protocolo = pb_protocolo
				 			left join pibic_aluno on pb_aluno = pa_cracha 
						 	left join docentes on pb_professor = pp_cracha
				 			left join pibic_bolsa_tipo on pb_tipo = pbt_codigo 
							left join pibic_status on pb_status = ps_codigo									  
							where (bh_acao = 92) 
							and (pb_ano = '$ano')
							and (bh_data >= ".brtos($dd[2])." and bh_data <= ".brtos($dd[3]).")
							$tipo
						order by bh_data
				";
				
				$xrlt = db_query($sql);
				$sx .= '<table width="95%" class="lt1" width="100%" border=1 cellspacing=0 cellpadding=2>';
				$sx .= '<TR><TD colspan=5 align="center" class="lt4">';
				$sx .= 'Histórico de troca de modalidade';
				while ($xline = db_read($xrlt))
					{
						$sx .= '<TR><TD colspan=5>'.stodbr($xline['bh_data']);
						$tot++;
						$sx .= $this->mostra_registro($xline);
					}
				$sx .= '<TR><TD colspan=5>total de '.$tot.' trocas de modalidades';
										
				$sx .= '</table>';
				echo $sx;
				return($sx);

			}
			
		function bolsas_anteriores()
			{
				$sql = "select * from ".$this->tabela."
					left join pibic_professor on pb_professor = pp_cracha 
					left join pibic_bolsa_tipo on pb_tipo = pbt_codigo
					where pb_aluno = '".$this->pb_aluno."' 
					and pb_status <> 'X' and (pb_aluno <> 'PIBICJR')
					order by pb_ano desc
					";
				$rlt = db_query($sql);
				$sx .= '<fieldset><legend>Histórico do Estudante nos Programas de Iniciação Cientófica</legend>';
				$sx .= '<table width=99% class="lt1">';
				$sx .= '<TR><TD colspan=5>Participação do estudante nos programas de Iniciação Cientófica';
				$sx .= '<TR><TH>Protocolo<TH>Título<TH>Ano<TH>Modalidade<TH>Status<TH>Orientador';
				$tot = 0;
				while ($line = db_read($rlt))
					{
						$tot++;
						$sta = trim($line['pb_status']);
						$cor = '';
						if ($sta == 'A') { $sta = '<font color="green"><B>Ativo</B></font>'; $cor = ' bgcolor="#F0F0FF" '; }
						if ($sta == 'F') { $sta = '<font color="#505050"><B>Finalizado</B></font>'; $cor = ' bgcolor="#FFF0F0" '; }
						if ($sta == 'C') { $sta = '<font color="orange">Cancelado</font>'; }
						$link = '<A HREF="pibic_bolsas_contempladas.php?dd0='.$line['id_pb'].'">';
						$sx .= '<TR '.$cor.'>';
						$sx .= '<TD align="center">';
						$sx .= $link;
						$sx .= $line['pb_protocolo'];
						
						$sx .= '<TD>';
						$sx .= $line['pb_titulo_projeto'];
						
						$sx .= '<TD align="center">';
						$sx .= $line['pb_ano'];
						
						$sx .= '<TD align="left">';
						$sx .= trim($line['pbt_descricao']);
						
						$sx .= '<TD align="center">';
						$sx .= $sta;

						$sx .= '<TD>';
						$sx .= $line['pp_nome'];
					}
				$sx .= '</table>';
				$sx .= '</fieldset>';
				if ($tot = 0)
					{ $sx = ''; }
				return($sx);
			}
		/** CANCELAMENTO **/
		function relatorio_cancelamento($ano='',$bolsa='')
			{							
				$sql = "update pibic_bolsa_historico set bh_acao = 91
						where bh_historico like '%ancelamento de contrato%' 
						 ";
				$rlt = db_query($sql);				
				
				/** Listagem */	

				
				/* DETALHES */
				$sql = "select * from pibic_bolsa_contempladas 
				 	left join discentes on pb_aluno = pa_cracha 
				 	left join docentes on pb_professor = pp_cracha
				 	left join pibic_bolsa_tipo on pb_tipo = pbt_codigo 
					left join pibic_status on pb_status = ps_codigo 								
					where pb_status = 'C' 
					and (pb_tipo = 'F')
					and (pb_ano = '$ano')
					
					order by pb_protocolo
				";
				$tot = 0;
				$xrlt = db_query($sql);
				$sx .= '<table width="95%" class="lt1" width="100%" border=1 cellspacing=0 cellpadding=2>';
				$sx .= '<TR><TD colspan=5 align="center" class="lt4">';
				$sx .= 'Histórico de cancelamento';
				while ($xline = db_read($xrlt))
					{
						$tot++;
						$sx .= $this->mostra_registro($xline);
					}
				$sx .= '<TR><TD colspan=5>total de '.$tot.' cancelamentos';
				$sx .= '</table>';
				echo $sx;				
				
			}

		function relatorio_substituicoes($ano='',$bolsa='')
			{
				
				$sql = "update pibic_bolsa_historico set bh_acao = 90
						where bh_historico like '%troca de aluno%' 
						 ";
				$rlt = db_query($sql);
				
				if (strlen($bolsa) > 0)
					{ $tipo = " and (pb_tipo = '$bolsa' )"; }
					
				$sql = "select * from pibic_bolsa_historico
						inner join ".$this->tabela." on bh_protocolo = pb_protocolo			  
							where (bh_acao = 90) 
							and (pb_ano = '$ano')
							$tipo
						order by bh_data
				";
				
				$rlt = db_query($sql);
				$sx .= '<table width="95%" class="lt1" width="100%" border=1 cellspacing=0 cellpadding=2>';
				$sx .= '<TR><TD colspan=5 align="center" class="lt4">';
				$sx .= 'Histórico de substituição';
				while ($line = db_read($rlt))
					{
						$sx .= '<TR '.coluna().'>';
						$sx .= '<TD>';
						$sx .= $line['bh_protocolo'];
						$sx .= '<TD>';
						$sx .= stodbr($line['bh_data']);
						$sx .= '<TD>';
						$sx .= $line['bh_historico'];
						$sx .= '<TD>';
						$sx .= $line['bh_acao'];
						
						//print_r($line);
						//echo '<HR>';
					}
				$sx .= '</table>';
				echo $sx;
				return($sx);
				
				
			}
		
		function mostra_relatorios_status()
			{
				$nt = array('-','-','-','-','-','-','-','-');
				$ci = array('off','off','off','off','off','off','off','off','off','off','off','off','off','off');
				$ns = array('-','aprovado','não aprovado','nao avaliado','','','','','','-','');
				$ni = array('off','a','x','off','off','off','off','off','off','off','off','off','off','off','off');
				$msgc = array('-','-','-','-','-','-','-','-','-','-','-','-','-');
				/*** Relatorio Parcial */
				if ($this->pb_rp > 20000101) { $msgc[1] = 'Postado em '.stodbr($this->pb_rp); }
				$nt[1] = $ns[round($this->pb_rp_nota)];
				$ci[1] = $ni[round($this->pb_rp_nota)];
				
				/*** Relatorio Parcial Correção */
				if ($this->pb_pr_2 > 20000101) { $msgc[2] = 'Postado em '.stodbr($this->pb_pr_2); }
				$nt[2] = $ns[round($this->$pb_rp_nota_2)];
				$ci[2] = $ni[round($this->$pb_rp_nota_2)];
				
				/*** Relatorio Parcial Correção */
				if ($this->pb_rp_data_reenvio > 20000101) { $msgc[2] = 'Postado em '.stodbr($this->pb_rp_data_reenvio); }
				$nt[2] = $ns[round($this->pb_rp_data_reenvio_nota)];
				$ci[2] = $ni[round($this->pb_rp_data_reenvio_nota)];
				
				/*** Relatorio Parcial */
				if ($this->pb_rf > 20000101) 
					{ $msgc[3] = 'Postado em '.stodbr($this->pb_rf); }
				$nt[3] = $ns[round($this->pb_rf_nota)];
				$ci[3] = $ni[round($this->pb_rf_nota)];		

				/*** Relatorio Parcial */
				//print_r($this);
				if ($this->pb_resumo > 20000101) { $msgc[5] = 'Postado em '.stodbr($this->pb_rf); }
				$nt[5] = $ns[round($this->pb_resumo_nota)];
				$ci[5] = $ni[round($this->pb_resumo_nota)];							

				$sx .= '
				<TABLE width="704" border="1" cellpadding="4" cellspacing="0">
				<TR class="lt2" bgcolor="#F0F0F0" align="center">
					<TD width="17%">Relatório Parcial</Td>
					<TD width="17%">Correção R. Parcial </TD>
					<TD width="17%">Relatório Final</Td>
					<TD width="17%">Correção R. Final</Td>
					<TD width="17%">Resumo</TH>
					<TD width="17%">Correção Resumo</Td>
				</TR>
				
				<TR align="center">
				<TD><img src="img/icone_relatorio_parcial_'.$ci[1].'.png" width="64" height="64" alt="" border="0"></TD>
				<TD><img src="img/icone_relatorio_parcial_'.$ci[2].'.png" width="64" height="64" alt="" border="0"></TD>
				<TD><img src="img/icone_relatorio_parcial_'.$ci[3].'.png" width="64" height="64" alt="" border="0"></TD>
				<TD><img src="img/icone_relatorio_parcial_'.$ci[4].'.png" width="64" height="64" alt="" border="0"></TD>
				<TD><img src="img/icone_relatorio_parcial_'.$ci[5].'.png" width="64" height="64" alt="" border="0"></TD>
				<TD><img src="img/icone_relatorio_parcial_'.$ci[6].'.png" width="64" height="64" alt="" border="0"></TD>
				<TR>
					<TD align="center" class="lt1">'.$msgc[1].'</TD>
					<TD align="center" class="lt1">'.$msgc[2].'</TD>
					<TD align="center" class="lt1">'.$msgc[3].'</TD>
					<TD align="center" class="lt1">'.$msgc[4].'</TD>
					<TD align="center" class="lt1">'.$msgc[5].'</TD>
					<TD align="center" class="lt1">'.$msgc[6].'</TD>
				</TR>
				<TR class="lt1" align="center">
					<TD>'.$nt[1].'</TD>
					<TD>'.$nt[2].'</TD>
					<TD>'.$nt[3].'</TD>
					<TD>'.$nt[4].'</TD>
					<TD>'.$nt[5].'</TD>
					<TD>'.$nt[6].'</TD>
				</TR>
				</TABLE>';	
				echo $sx;			
			}
		
		function mostra_docentes_orientacoes($docente)
			{
				$sql = "select * from ".$this->tabela." 
					left join pibic_bolsa_tipo on pb_tipo = pbt_codigo				
					where pb_professor = '".$docente."' 
					order by pb_ano desc
					";
				$rlt = db_query($sql);
				$sx = '<fieldset><legend>'.msg('orientacoes_pibic').'</legend>';
				$sx .= '<table width=100% cellpadding=2 cellspacing=0 border=1 class=lt0>';
				$sx .= '<TR>';
				$sx .= '<TH width="5%">'.msg('protocolo');
				$sx .= '<TH width="70%">'.msg('titulo');
				$sx .= '<TH width="10%">'.msg('modalidade');
				$sx .= '<TH width="5%">'.msg('ano');
				$sx .= '<TH width="5%">'.msg('status');
			
				while ($line = db_read($rlt))
					{
						$sx .= '<TR '.coluna().'>';
						$sx .= '<TD>';
						$sx .= $line['pb_protocolo'];
						$sx .= '<TD>';
						$sx .= $line['pb_titulo_projeto'];
						$sx .= '<TD><nobr>';
						$sx .= $line['pbt_descricao'];
						$sx .= '<TD>';
						$sx .= $line['pb_ano'];
						$sx .= '<TD>';
						$sx .= $line['pb_status'];
					}
				$sx .= '</table>';
				return($sx);
			}
		
		function mostra_registro($line)
			{
				global $link, $http;
				$linka = '<A HREF="'.$link.'?dd0='.$line['pb_protocolo'].'&dd90='.checkpost($line['pb_protocolo']).'">';
				
				
				$status = trim($line['pb_status']);
	
				if ($status == '@') { $status = '<font color="#ff8000">Nóo implementado</font>'; $bgc = '#f5f5f5';}
				if ($status == 'A') { $status = '<font color="green">Ativo</font>';  $bgc = '#d2fad1';}
				if ($status == 'B') { $status = '<font color="#0000ff">Encerrado</font>';  $bgc = '#faebef';}
				if ($status == 'C') { $status = '<font color="#ff0000">**Cancelado**</font>';  $bgc = '#ffe1e1';}
				$bgc = '';
				$aluno_cod = trim($line['pb_aluno']);
				$aluno_nome = trim($line['pa_nome']);
				
				$docente_cod = trim($line['pb_professor']);
				$docente_nome = trim($line['pp_nome']);
			
				$link = '';
		
				$bolsa = trim($line['pbt_descricao']);
				$bolsa_img = '<img src="'.$http.'img/'.trim($line['pbt_img']).'" border="0">';
				
				$style = ' style="border-top: 1px solid #202020; padding-top: 15px;" ';
				
				$sr .= '<TR valign="top"  bgcolor="'.$bgc.'">';
				$sr .= '<TD rowspan=5 '.$style.' >'.$this->mostra_imagem_tipo_edital($line['pbt_edital']);
			
				$sr .= '<TD width="10" '.$style.'>'.$it.'</TD>';
				$sr .= '<TD colspan="3" '.$style.'><B>'.$linka.trim($line['pb_titulo_projeto']);
				$sr .= '<TD colspan="1" align="right" '.$style.'><B>'.$linka.$line['pb_protocolo'];
				$sr .= '</TR>';

				$sr .= '<TR bgcolor="'.$bgc.'">';
				$sr .= '<TD colspan="2" align="right"><I>Estudante</TD><TD colspan="3">'.$aluno_nome.' ('.$aluno_cod.')';
				$sr .= '</TR>';

				$sr .= '<TR bgcolor="'.$bgc.'">';
				$sr .= '<TD colspan="2" align="right"><I>Docente</TD><TD colspan="3">'.$docente_nome.' ('.$docente_cod.')';
				$sr .= '</TR>';
	
				$sr .= '<TR bgcolor="'.$bgc.'">';
				$sr .= '<TD colspan="2" align="right"><I>Bolsa </TD><TD colspan="2">'.$bolsa_img. ' '.$bolsa.' / <B>'.$line['pb_ano'].'</B>';
				$sr .= '<TD align="right"><B>'.$status.'</TD>';
				$sr .= '</TR>';

				$sr .= '<TR bgcolor="'.$bgc.'"><TD align="right"><I>Relatórios</I></TD>';
				$sr .= '<TD colspan="6">';
		
				$fld = "pb_relatorio_parcial";
				$sr .= 'Parcial: '.$this->mostra_dados_relatorio($line[$fld],$line[$fld.'_nota']);
				
				$sr .= '</TR>';				
				return($sr);
			}
		function mostra_imagem_tipo_edital($tipo)
			{
				global $http;
				$tipo = lowercase(trim($tipo));
				switch ($tipo)
					{
					case 'pibiti': $img = 'logo_ic_pibiti.png'; break;
					case 'pibic': $img = 'logo_ic_pibic.png'; break;
					case 'pibic_em': $img = 'logo_ic_pibic_em.png'; break;
					default: $img = $tipo; break; 
					}
				
				$sx .= '<img src="'.$http.'img/'.$img.'" width="80">';
				return($sx);
			}
		
		function mostra_dados_relatorio($data,$nota)
				{
					$sx = '';
					if ($data > 20000000)
					{
					switch ($nota)
						{
						case '0': $sx = 'não avaliado'; break;
						case '1': $sx = '<font color="#404080"><B>aprovado</B></font>'; break;
						case '0': $sx = '<font color="#800000"><B>pendente</B></font>'; break;
						case '-1': $sx = '<B>enviado para correção</B>';
						case '-90': $sx = '<B>postado, aguardando avaliação</B>'; break;
						case '-99': $sx = '<B>postado, aguardando avaliador</B>'; break;
						case '0': $sx = 'não avaliado'; break;
						default:
							$sx = '- -'.$nota;
						}
					} else {
						$sx = 'não postado';
					}
					return($sx);
				}
		
		function cp_csf()
			{
				$cp = array();
				array_push($cp,array('$H4','id_pb','id_pb',False,True,''));
				array_push($cp,array('$T50:2','pb_titulo_projeto','Titulo',True,True,''));
				array_push($cp,array('$HV','pb_titulo_projeto_asc',uppercasesql($dd[1]),False,True,''));

				array_push($cp,array('$T50:2','pb_titulo_plano','Título Plano',False,True,''));
				array_push($cp,array('$T50:2','pb_fomento','Aprovação externa',False,True,''));
				array_push($cp,array('$S10','pb_codigo','Código da Bolsa',False,False,''));
				array_push($cp,array('$Q pbt_descricao:pbt_codigo:select * from pibic_bolsa_tipo order by pbt_descricao','pb_tipo','Bolsa',True,True,''));
				array_push($cp,array('$S1','pb_status','Status',False,True,''));
				//array_push($cp,array('$S10','pb_status','Status',False,True,''));
				array_push($cp,array('$S10','pb_contrato','Contrato',False,True,''));

				array_push($cp,array('$S8','pb_aluno','Aluno (Cracha)',False,True,''));


				array_push($cp,array('${','','Cióncia Sem Fronteira',False,True,''));
				array_push($cp,array('$S100','pb_aluno_nome','Nome do Tutor',False,True,''));
				array_push($cp,array('$S60','pb_colegio','Nome do Universidade',False,True,''));
				array_push($cp,array('$S60','pb_colegio_orientador','Pais de destino',False,True,''));
				array_push($cp,array('$}','','',False,True,''));

				array_push($cp,array('$S8','pb_professor','Professor (Cracha)',False,True,''));

				array_push($cp,array('$S7','pb_protocolo','Protocolo',False,True,''));
				array_push($cp,array('$HV','pb_protocolo_mae','',False,True,''));

				array_push($cp,array('$HV','pb_data',19000101,False,True,''));
				array_push($cp,array('$H5','pb_hora','Hora',False,True,''));

				array_push($cp,array('$D8','pb_data_ativacao','Data (ativação)',False,True,''));
				array_push($cp,array('$D8','pb_data_encerramento','Data (encerramento)',False,True,''));
				
				array_push($cp,array('$HV','pb_relatorio_parcial','0',False,True,''));
				array_push($cp,array('$HV','pb_relatorio_parcial_nota','0',False,True,''));
				array_push($cp,array('$HV','pb_relatorio_final','0',False,True,''));
				array_push($cp,array('$HV','pb_relatorio_final_nota','0',False,True,''));
				array_push($cp,array('$HV','pb_resumo','0',False,True,''));
				array_push($cp,array('$HV','pb_resumo_nota','0',False,True,''));
				array_push($cp,array('$HV','pb_semic','0',False,True,''));

				array_push($cp,array('$O 1:SIM&0:NóO','pb_ativo','Ativo',False,True,''));
				array_push($cp,array('$D8','pb_ativacao','Data ativação',False,True,''));
				array_push($cp,array('$D8','pb_desativacao','Data finalização',False,True,''));
				array_push($cp,array('$H8','','Contrato',False,True,''));

				array_push($cp,array('$H8','pb_area_conhecimento','Area conhecimento',False,True,''));
				

				array_push($cp,array('$[2012-'.date("Y").']','pb_ano','Ano',False,True,''));
//array_push($cp,array('$S6','pb_edital','Edital',False,True,''));

				array_push($cp,array('$HV','pibic_resumo_text','Resumo',False,True,''));
				array_push($cp,array('$HV','pibic_resumo_colaborador','Autores',False,True,''));
				array_push($cp,array('$H8','pibic_resumo_keywork','Palavras-Chave',False,True,''));

				return($cp);
				
			}
		function le($id='',$protocolo='')
			{
				if (strlen($id) > 0) {$this->id_pb = $id; }
				if (strlen($protocolo) > 0) {$this->id_pb = ''; $this->pb_protocolo = $protocolo; }
				$sql = "select * from ".$this->tabela;
				$sql .= " left join pibic_bolsa_tipo on pb_tipo = pbt_codigo ";
				$sql .= " left join pibic_status on pb_status = ps_codigo ";
				$sql .= " left join docentes on pb_professor = pp_cracha ";
				$sql .= " left join pibic_aluno on pb_aluno = pa_cracha ";
				$sql .= " left join ajax_areadoconhecimento on a_cnpq = pb_semic_area ";
				//$sql .= " left join pibic_areas on psa_codigo = pse_area ";
				if (strlen($protocolo) > 0)
					{ $sql .= " where pb_protocolo = '".$protocolo."' "; } else 
					{ $sql .= " where id_pb = '".round($id)."' "; }
				$sql .= " order by pb_status ";

				$rlt = db_query($sql);
				if ($line = db_read($rlt))
					{
					$this->autores_semic = trim($line['pibic_resumo_colaborador']);
					$this->id_pb = trim($line['id_pb']);
					$this->pb_aluno = trim($line['pb_aluno']);
					$this->pb_aluno_email1 = trim($line['pa_email']);
					$this->pb_aluno_email2 = trim($line['pa_email_1']);
					$this->pb_est_curso = trim($line['pa_curso']);
					$this->pb_est_nome = trim($line['pa_nome']);
					
					$this->pb_professor = trim($line['pb_professor']);
					$this->pb_professor_nome = trim($line['pp_nome']);
					$this->pb_professor_centro = trim($line['pp_centro']);
					$this->pb_prof_email_1 = trim($line['pp_email']);
					$this->pb_prof_email_2 = trim($line['pp_email_1']);
					
					$this->pb_protocolo = trim($line['pb_protocolo']);
					$this->pb_protocolo_mae = trim($line['pb_protocolo_mae']);
					$this->pb_tipo = trim($line['pb_tipo']);
					$this->pb_tipo_nome = trim($line['pbt_descricao']);
					$this->pb_programa = trim($line['pbt_edital']);
					$this->pb_data = trim($line['pb_data']);
					$this->pb_hora = trim($line['pb_hora']);
					$this->pb_ativo = trim($line['pb_ativo']);
					$this->pb_ativacao = trim($line['pb_ativacao']);
					$this->pb_desativacao = trim($line['pb_desativacao']);
					$this->pb_contrato = trim($line['pb_contrato']);
					$this->pb_titulo_projeto = trim($line['pb_titulo_projeto']);
					$this->pb_titulo_plano = trim($line['pb_titulo_projeto']);
					$this->pb_titulo_en = trim($line['pb_titulo_plano_en']);
					
					$this->pb_fomento = trim($line['pb_fomento']);
					$this->pb_status = trim($line['pb_status']);
					$this->pb_status_nome = trim($line['ps_nome']);
					$this->pb_area_conhecimento = trim($line['pb_area_conhecimento']);
					$this->pb_codigo = trim($line['pb_codigo']);
					$this->pb_data_ativacao = trim($line['pb_data_ativacao']);
					$this->pb_data_enceramento = trim($line['pb_data_enceramento']);
					$this->pb_rp = trim($line['pb_relatorio_parcial']);
					$this->pb_rp_nota = trim($line['pb_relatorio_parcial_nota']);
					$this->pb_rf = trim($line['pb_relatorio_final']);
					$this->pb_rf_nota = trim($line['pb_relatorio_final_nota']);
					$this->pb_resumo = trim($line['pb_resumo']);
					$this->pb_resumo_nota = trim($line['pb_resumo_nota']);
					$this->pibic_resumo_text = trim($line['pibic_resumo_text']);
					$this->pibic_resumo_colaborador = trim($line['pibic_resumo_colaborador']);
					$this->pibic_resumo_keywork = trim($line['pibic_resumo_keywork']);
					$this->pb_ano = trim($line['pb_ano']);
					$this->pb_semic = trim($line['pb_semic']);
					$this->pb_semic_idioma = trim($line['pb_semic_idioma']);
					$this->pb_semic_area = trim($line['pb_semic_area']);
					$this->pb_semic_area_descricao = trim($line['a_cnpq']);
					$this->pb_semic_curso_descricao = trim($line['a_descricao']);
					$this->pb_rp_data_reenvio = round($line['pb_relatorio_parcial_correcao']);
					$this->pb_rp_data_reenvio_nota = round($line['pb_relatorio_parcial_correcao_nota']);
					$this->resumo = trim($line['pibic_resumo_text']);
					$this->keyword = trim($line['pibic_resumo_keywork']);
					
					$this->semic_valida = trim($line['pb_semic_ratificado']);
					$this->semic_valida_status = trim($line['pb_semic_ratificado_status']);

					$this->pb_titulo_projeto_asc = trim($line['pb_titulo_projeto_asc']);
					
					$this->line = $line;
					return(1);
					
					}
			return(0);
			}

		function mostra_autores_submetidos()
			{
				$sx .= '<fieldset><legend>Autores</legend>';
				$sx .= '<div style="text-align: right">';
				$autores = mst($this->autores_semic);
				require("autores_limpa.php");
				$sx .= $autores;
				$sx .= '</div>';
				$sx .= '</fieldset>';
				return($sx);
			}

		function mostra_resumo()
			{
				$sx .= '<fieldset><legend>Resumo</legend>';
				$sx .= '<div style="text-align: justify">';
				$sx .= $this->resumo;
				$sx .= '<BR><BR>';
				$sx .= '<B>Palavras-Chave</B>: '.$this->keyword;
				$sx .= '</div>';
				$sx .= '</fieldset>';
				return($sx);
			}
		function mostar_dados()
			{				
				/* 
				 * 
				 */
				$sx .= '<fieldset class="fieldset01">';
				$sx .= '<legend class="legend01">'.msg('data_main').'</legend>';
				$sx .= '<table width="100%" class="tabela00">';
				$sx .= '<TR class="lt0">';
				
				$sx .= '<TR><TD class="lt0">&nbsp;';
				
				$sx .= '<TD class="lt0" align="right">';
				$sx .= msg('protocol');
				$sx .= '<TD width=16% ><B>'.$this->pb_protocolo.' / '.$this->pb_protocolo_mae;
				
				$sx .= '<TD width="400" rowspan=10 >';
				
					$sx .= '<table width="100%" border=0 cellspan=0 cellpadding=0 style="background-color: #F0F0F0;">';
					$sx .= '<TR>';
					$sx .= '<TD align="right" class="lt0">'.msg('year');
					$sx .= '<TD class="lt1" width=60% ><B>'.$this->pb_ano;
					
					$sx .= '<TR>';
					$sx .= '<TD align="right" class="lt0">'.msg('programa');
					$sx .= '<TD class="lt1" ><B>'.$this->pb_programa;
					
					$sx .= '<TR>';
					$sx .= '<TD align="right">'.msg('bolsa');
					$sx .= '<TD class="lt1" ><B>'.$this->pb_tipo_nome;
					
					$sx .= '<TR>';
					$sx .= '<TD align="right" class="lt0">'.msg('status');
					$sx .= '<TD class="lt1" ><B>'.$this->pb_status_nome;

					$sx .= '<TR>';
					$sx .= '<TD class="lt0" align="right">'.msg('active');
					$sx .= '<TD class="lt1" ><B>'.stodbr($this->pb_ativacao);

					if ($this->pb_rp_data_reenvio > 20100101)
						{	
							$sx .= '<TR>';
							$sx .= '<TD class="lt0" align="right">'.msg('rel_parcial');
							$sx .= '<TD class="lt1"><B>'.$this->mostra_data_relatorio($this->pb_rp,$this->pb_rp_nota);

							$sx .= '<TR>';
							$sx .= '<TD class="lt0" align="right">'.msg('rel_parcial_correcao');
							$sx .= '<TD class="lt1"><B>'.$this->mostra_data_relatorio($this->pb_rp_data_reenvio,$this->pb_rp_data_reenvio_nota);

						} else {
							$sx .= '<TR>';
							$sx .= '<TD class="lt0" align="right">'.msg('rel_parcial');
							$sx .= '<TD class="lt1"><B>'.$this->mostra_data_relatorio($this->pb_rp,$this->pb_rp_nota);
						}
					$sx .= '<TR><TD><TH>';
					$sx .= '<TR>';
					$sx .= '<TD class="lt0" align="right">'.msg('rel_final');
					$sx .= '<TD class="lt1"><B>'.$this->mostra_data_relatorio($this->pb_rf,$this->pb_rf_nota);

					$sx .= '<TR>';
					$sx .= '<TD class="lt0" align="right">'.msg('rel_resumo');
					$sx .= '<TD class="lt1"><B>'.$this->mostra_data_relatorio($this->pb_resumo,$this->pb_resumo_nota);
	
					$sx .= '<TR>';
					$sx .= '<TD class="lt0" align="right">'.msg('semic');
					$sx .= '<TD class="lt1"><B>'.$this->mostra_data_relatorio($this->pb_semic,'');
	
					$sx .= '<TR>';
					$sx .= '<TD class="lt0" align="right">'.msg('semic_idioma');
					$sx .= '<TD class="lt1"><B>'.$this->mostra_idioma($this->pb_semic_idioma);
	
					$sx .= '<TR>';
					$sx .= '<TD class="lt0" align="right"><NOBR>'.msg('semic_area');
					$sx .= '<TD class="lt1"><B>'.$this->pb_semic_area;

				
					$sx .= '</table>';
					
				$sx .= '<TR>';
				$sx .= '<TD class="lt0" align="left" colspan=3>';
				$sx .= msg('title_project');
				$sx .= '<BR><font class="lt3">';
				$sx .= '<B>'.$this->pb_titulo_plano.'</B>';
				
				$sx .= '<TR class="lt0">';
				$sx .= '<TD>'.msg('teacher');
				//$sx .= '<TD>'.msg('centro');
				$sx .= '<font class="lt1">';
				$sx .= '<BR><B>'.$this->pb_professor_nome.' ('.$this->pb_professor.')';;
				//$sx .= '<TD colspan=3><B>'.$this->pb_professor_centro;

				$sx .= '<TR class="lt0">';
				$sx .= '<TD>'.msg('student');
				$sx .= '<font class="lt1">';
				$sx .= '<BR><B>'.$this->pb_est_nome.' ('.$this->pb_aluno.')';
				
				$sx .= '<TR class="lt0">';
				$sx .= '<TD>'.msg('course');
				$sx .= '<font class="lt1">';
				$sx .= '<BR><B>'.$this->pb_est_curso;




				$sx .= '</table>';
				$sx .= '</fieldset>';				
				return($sx);
			}
	function mostra_data_relatorio($d1,$n1)
		{
			$d1 = round($d1);
			if ($d1 <= 19000101) 
				{
					$sx .= '<B><font color="a0a0a0">'.msg('not_post').'</B>';
				} else {
					$sx = '<B>'.stodbr($d1).'</B>';
					
					$sa = '';
					if ($n1 < 1) { $sx .= ' '.msg('not_avalied'); }
					if ($n1 == 1) { $sx .= ' '.msg('<font color="#303080">aprovado</font>'); }
					if ($n1 == 2) { $sx .= ' '.'<font color="red">'.msg('pendente').'</font>'; }
					if ($n1 == 3) { $sx .= ' '.msg('reprovado'); }
				}
			return($sx);
		}
		function bolsa_detalhes()
			{}
		function bolsa_arquivos()
			{
								
			}
		function bolsa_relatorio_parcial_resumo($ano = '',$status = '',$tipo='')
			{
				$rsp = array();
				$sql = " select * from (
					SELECT count(*) as total, pb_ano, pb_tipo, pb_status FROM 
					pibic_bolsa_contempladas ";
					/* Parametros */
					if (strlen($ano) > 0) { $psql1 = " pb_ano = '".$ano."' "; }
					if (strlen($status) > 0) { $psql2 = " pb_status = '".$status."' "; }
					if (strlen($tipo) > 0) { $psql3 = " pb_tipo = '".$tipo."' "; }
					if (strlen($psql1.$psq2.$psql3) > 0)
						{
							if (strlen($psql1) > 0) { $sql .= 'where '.$psql1;
								if (strlen($psql2) > 0 ) { $sql .= ' and '.$psql2; }  
								if (strlen($psql3) > 0 ) { $sql .= ' and '.$psql3; }  								
								}
							if (strlen($psql2) > 0) { $sql .= 'where '.$psql2;  
								if (strlen($psql3) > 0 ) { $sql .= ' and '.$psql3; }  								
								}
							if (strlen($psql3) > 0) { $sql .= 'where '.$psql3; }
						}
					$sql .= "
					group by pb_ano, pb_tipo, pb_status
					) as tabela 
					left join pibic_bolsa_tipo on pb_tipo = pbt_codigo
					left join pibic_status on pb_status = ps_codigo
					order by pb_ano desc, pbt_edital, pbt_ordem, ps_codigo
					";
				$rlt = db_query($sql);				
				while ($line = db_read($rlt))
					{
						array_push($rsp,array('pbt_edital'=>$line['pbt_edital'],
						'pb_ano'=>$line['pb_ano'],
						'pb_tipo'=>$line['pb_tipo'],
						'pb_status'=>$line['pb_status'],
						'total'=>$line['total'],
						'bolsa'=>$line['pbt_descricao'],
						'img'=>$line['pbt_img'],
						'aux'=>$line['pbt_auxilio'],
						'status'=>$line['ps_nome']));
					}
				return($rsp);
			}

		/*************** CAMPOS PARA ENTREGA DE RELATORIO PARCIAL */
		function bolsa_relatorio_parcial_entrega()
			{
			//if (($this->pb_relatorio_parcial == 19000101) or ($this->pb_relatorio_parcial == 19000102))
				{
					$sx = '<fieldset>';
					$sx .= '<legend>'.msg('rel_parcial').'</legend>';
					$sx .= '<div style="text-align: justify;"><font class="lt1"><font style="line-height: 150%;">'.msg_alert(msg('rel_parc_instruction')).'<font></div>';
					$sx .= '<table width="100%"><TR valign="top">';
					$sx .= '<TD width="25%">';
					$sx .= $this->semic_idioma();
					$sx .= '<TD width="75%">';
					$sx .= $this->semic_area_do_conhecimento();
					$sx .= '<TR>';
					$sx .= '<TD width="100%" colspan=2>';
					$sx .= $this->bolsa_arquivos_row();
					$sx .= '<TR>';
					$sx .= '<TD width="100%" colspan=2>';
					$sx .= $this->bolsa_relatorio_parcial_pendecias();
					$sx .= '</table>';
					$sx .= '</fieldset>';
				}
			return($sx);				
			}

		/*************** CAMPOS PARA ENTREGA DE RELATORIO PARCIAL */
		function bolsa_relatorio_parcial_correcao_entrega()
			{
			//if (($this->pb_relatorio_parcial == 19000101) or ($this->pb_relatorio_parcial == 19000102))
				{
					$sx = '<fieldset>';
					$sx .= '<legend>'.msg('rel_parcial_cor').'</legend>';
					$sx .= '<div style="text-align: justify;"><font class="lt1"><font style="line-height: 150%;">'.msg_alert(msg('rel_parc_cor_ins')).'<font></div>';
					$sx .= '<table width="100%"><TR valign="top">';
					$sx .= '<TR>';
					$sx .= '<TD width="100%" colspan=2>';
					$sx .= $this->bolsa_arquivos_lista('RELPC');
					$sx .= '<TR>';
					$sx .= '<TD width="100%" colspan=2>';
					$sx .= $this->bolsa_rp_checklist();
					$sx .= '</table>';
					$sx .= '</fieldset>';
				}
			return($sx);				
			}

		function bolsa_relatorio_parcial_pendecias()
			{
				global $dd;
				$sx .= '<fieldset id="semic_files"><legend class="lt1"><I>'.msg('valida').'</I></legend>';
				$sx .= '<font class="lt2">';

				$sx .= '<A name="id03">';
				$sx .= '<font class="lt2">';

				//$sx .= $this->mostra_area_do_conhecimento();
				$frame = "sm_valida";
				$sx .= '<div id="'.$frame.'">';
				$sx .= '<script type="text/javascript">'.chr(13);
				$sx .= '    var tela01 = $.ajax( "'.$this->pg_valida.'?dd1='.$dd[1].'&dd2=validar&dd3='.$frame.'&dd90='.$dd[90].'" ) .done(function(data) { $("#'.$frame.'").html(data); }) .fail(function() { alert("error"); }) .always(function(data) { $("#'.$frame.'").html(data); }); '.chr(13);
				$sx .= '</script>'.chr(13);				
				$sx .= '</div>';
				

				$sx .= '<div id="sm_valida">';
				$sx .= '</div>';
				
						
				/**** Normal ***/				
				$sx .= '</fieldset>';
				return($sx);							
			}



/**
 * Relatório Final
 */
 		function bolsa_relatorio_final_lista()
			{
				$sql = "select * from pibic_bolsa_contempladas 
					left join pibic_aluno on pb_aluno = pa_cracha 
					left join pibic_professor on pb_professor = pp_cracha
					left join pibic_submit_documento on doc_protocolo = pb_protocolo_mae "; 
				//$sql .= "left join pibic_edital on pb_protocolo = pee_protocolo ";
				$sql .= " where pb_professor = '".$this->pb_professor."' "; 
					//$sql .= " and ((pb_relatorio_final = 0) or (pb_relatorio_final  isnull)) 
				$sql .= " and (pb_status <> '@' and pb_status <> 'C' ) "; 
				$sql .= " and pb_ano = '".(date("Y")-1)."' ";
 				$sql .= "and pb_relatorio_final < 20000000 
 					order by pa_nome limit 1000"; 
				$rlt = db_query($sql);
				
				$ss .= '<TABLE width="100%" class="lt1" border="0">';
				$ss .= '<TR><TD>';
				$ss .= '<font color="red">Prazo de 06/ago./2012 ató <B>12/ago./2012</B> as 23h59</font>';
				$ss .= '<BR><BR>';
				$ss .= 'Clique no plano de trabalho do aluno para submeter atividade.<BR>';
				$ss .= '<UL>';
				
				$to = 0;
				while ($line = db_read($rlt))
					{
					$to++;
					$bolsa = $line['pb_tipo'];
					require('../pibicpr/bolsa_tipo.php');
					$ttp = LowerCase($line['pb_titulo_projeto']);
					$ttp = UpperCase(substr($ttp,0,1)).substr($ttp,1,strlen($ttp));	
					$bolsa = $line['pb_codigo'];
					$aluno = $line['pa_nome'];
					$status = $line['pb_status'];
					$link = '<a href="pa_relatorio_final_entrega.php?dd1='.trim($line['pb_protocolo']).'&dd0='.$line['id_pb'].'&dd90='.checkpost($line['pb_protocolo']).'">';
					$ss .= '<LI><B>'.$link.$ttp.'</A></B>';
					$ss .= '<BR>Aluno: '.$aluno;
					$ss .= '<BR>Contrato ativo: '.$bolsa.' ('.$bolsa_nome.')';
					$ss .= '</LI><BR>';
					}
				$ss .= '</UL>';
				$ss .= '</TD></TR>';
				$ss .= '</table></fieldset>';
				return($ss);
			}
 
 		function bolsa_relatorio_final_resumo($ano = '',$status = '',$tipo='')
			{
				$rsp = array();
				$sql = " select * from (
					SELECT count(*) as total, pb_ano, pb_tipo, pb_status FROM 
					pibic_bolsa_contempladas ";
					/* Parametros */
					if (strlen($ano) > 0) { $psql1 = " pb_ano = '".$ano."' "; }
					if (strlen($status) > 0) { $psql2 = " pb_status = '".$status."' "; }
					if (strlen($tipo) > 0) { $psql3 = " pb_tipo = '".$tipo."' "; }
					if (strlen($psql1.$psq2.$psql3) > 0)
						{
							if (strlen($psql1) > 0) { $sql .= 'where '.$psql1;
								if (strlen($psql2) > 0 ) { $sql .= ' and '.$psql2; }  
								if (strlen($psql3) > 0 ) { $sql .= ' and '.$psql3; }  								
								}
							if (strlen($psql2) > 0) { $sql .= 'where '.$psql2;  
								if (strlen($psql3) > 0 ) { $sql .= ' and '.$psql3; }  								
								}
							if (strlen($psql3) > 0) { $sql .= 'where '.$psql3; }
						}
					$sql .= "
					group by pb_ano, pb_tipo, pb_status
					) as tabela 
					left join pibic_bolsa_tipo on pb_tipo = pbt_codigo
					left join pibic_status on pb_status = ps_codigo
					order by pb_ano desc, pbt_edital, pbt_ordem, ps_codigo
					";
				$rlt = db_query($sql);				
				while ($line = db_read($rlt))
					{
						array_push($rsp,array('pbt_edital'=>$line['pbt_edital'],
						'pb_ano'=>$line['pb_ano'],
						'pb_tipo'=>$line['pb_tipo'],
						'pb_status'=>$line['pb_status'],
						'total'=>$line['total'],
						'bolsa'=>$line['pbt_descricao'],
						'img'=>$line['pbt_img'],
						'aux'=>$line['pbt_auxilio'],
						'status'=>$line['ps_nome']));
					}
				return($rsp);
			}

		/*************** CAMPOS PARA ENTREGA DE RELATORIO PARCIAL */
		function bolsa_relatorio_final_entrega()
			{
			//if (($this->pb_relatorio_parcial == 19000101) or ($this->pb_relatorio_parcial == 19000102))
				{
					$sx = '<fieldset>';
					$sx .= '<legend>'.msg('rel_final').'</legend>';
					$sx .= '<div style="text-align: justify;"><font class="lt1"><font style="line-height: 150%;">'.msg(msg('rel_final_instruction')).'<font></div>';
					$sx .= '<table width="100%"><TR valign="top">';
					$sx .= '<TD width="25%">';
					$sx .= $this->semic_idioma();
					$sx .= '<TD width="75%">';
					$sx .= $this->semic_area_do_conhecimento();
					$sx .= '<TR>';
					$sx .= '<TD width="100%" colspan=2>';
					$sx .= $this->bolsa_arquivos_row();
					$sx .= '<TR>';
					$sx .= '<TD width="100%" colspan=2>';
					$sx .= $this->bolsa_relatorio_final_pendecias();
					$sx .= '</table>';
					$sx .= '</fieldset>';
				}
			return($sx);				
			}

		/*************** CAMPOS PARA ENTREGA DE RELATORIO PARCIAL */
		function bolsa_relatorio_final_correcao_entrega()
			{
			//if (($this->pb_relatorio_parcial == 19000101) or ($this->pb_relatorio_parcial == 19000102))
				{
					$sx = '<fieldset>';
					$sx .= '<legend>'.msg('rel_parcial_cor').'</legend>';
					$sx .= '<div style="text-align: justify;"><font class="lt1"><font style="line-height: 150%;">'.msg_alert(msg('rel_parc_cor_ins')).'<font></div>';
					$sx .= '<table width="100%"><TR valign="top">';
					$sx .= '<TR>';
					$sx .= '<TD width="100%" colspan=2>';
					$sx .= $this->bolsa_arquivos_lista('RELAF');
					$sx .= '<TR>';
					$sx .= '<TD width="100%" colspan=2>';
					$sx .= $this->bolsa_rf_checklist();
					$sx .= '</table>';
					$sx .= '</fieldset>';
				}
			return($sx);				
			}

		function bolsa_relatorio_final_pendecias()
			{
				global $dd;
				$sx .= '<fieldset id="semic_files"><legend class="lt1"><I>'.msg('valida').'</I></legend>';
				$sx .= '<font class="lt2">';

				$sx .= '<A name="id03">';
				$sx .= '<font class="lt2">';

				//$sx .= $this->mostra_area_do_conhecimento();
				$frame = "sm_valida";
				$sx .= '<div id="'.$frame.'">';
				$sx .= '<script type="text/javascript">'.chr(13);
				$sx .= '    var tela01 = $.ajax( "pa_relatorio_final_ajax.php?dd1='.$dd[1].'&dd2=validar&dd3='.$frame.'&dd90='.$dd[90].'" ) .done(function(data) { $("#'.$frame.'").html(data); }) .fail(function() { alert("error"); }) .always(function(data) { $("#'.$frame.'").html(data); }); '.chr(13);
				$sx .= '</script>'.chr(13);				
				$sx .= '</div>';
				

				$sx .= '<div id="sm_valida">';
				$sx .= '</div>';
				
						
				/**** Normal ***/				
				$sx .= '</fieldset>';
				return($sx);							
			}
 
 /*
  */
		function bolsa_rp_checklist()
			{
				global $dd;
				
				$ged = new ged;
				$ged->protocol = $dd[1];
				$ged->tabela = 'pibic_ged_documento';
				$ok = $ged->file_type_exists('RELPC');
				$sx .= '<fieldset id="semic_files"><legend class="lt1"><I>'.msg('valida').'</I></legend>';
				$sx .= '<font class="lt2">';

				$sx .= '<A name="id03">';
				$sx .= '<font class="lt2">';

				//$sx .= $this->mostra_area_do_conhecimento();
				$frame = "sm_valida";
				$sx .= '<div id="sm_valida">';
					if ($ok==0)
						{
							$sx .= '<center><h2><font color="red">';
							$sx .= msg('erro_falta_arquivo');
							$sx .= '</font></h2></center>';
						} else {
							$sx .= msg('post_instrution').chr(13);
							$sx .= '<form action="pa_relatorio_parcial_correcao_entrega_fim.php">'.chr(13);
							$sx .= '<input type="hidden" name="dd0" value="'.$dd[0].'">'.chr(13);
							$sx .= '<input type="hidden" name="dd1" value="'.$dd[1].'">'.chr(13);
							$sx .= '<input type="hidden" name="dd90" value="'.$dd[90].'">'.chr(13);
														
							$sx .= '<center>'.chr(13);
							$sx .= '<input type="submit" value="'.msg('post_report').'">'.chr(13);
							$sx .= '</form>'.chr(13);
							$sx .= 'RP';
						}
				$sx .= '</div>';
				
						
				/**** Normal ***/				
				$sx .= '</fieldset>';
				return($sx);							
			}


		function bolsa_rf_checklist()
			{
				global $dd;
				
				$ged = new ged;
				$ged->protocol = $dd[1];
				$ged->tabela = 'pibic_ged_documento';
				$ok = $ged->file_type_exists('RELAF');
				$sx .= '<fieldset id="semic_files"><legend class="lt1"><I>'.msg('valida').'</I></legend>';
				$sx .= '<font class="lt2">';

				$sx .= '<A name="id03">';
				$sx .= '<font class="lt2">';

				//$sx .= $this->mostra_area_do_conhecimento();
				$frame = "sm_valida";
				$sx .= '<div id="sm_valida">';
					if ($ok==0)
						{
							$sx .= '<center><h2><font color="red">';
							$sx .= msg('erro_falta_arquivo');
							$sx .= '</font></h2></center>';
						} else {
							$sx .= msg('post_instrution').chr(13);
							$sx .= '<form action="pa_relatorio_final_correcao_entrega_fim.php">'.chr(13);
							$sx .= '<input type="hidden" name="dd0" value="'.$dd[0].'">'.chr(13);
							$sx .= '<input type="hidden" name="dd1" value="'.$dd[1].'">'.chr(13);
							$sx .= '<input type="hidden" name="dd90" value="'.$dd[90].'">'.chr(13);
														
							$sx .= '<center>'.chr(13);
							$sx .= '<input type="submit" value="'.msg('post_report').'">'.chr(13);
							$sx .= '</form>'.chr(13);
							$sx .= '<RF>';
						}
				$sx .= '</div>';
				
						
				/**** Normal ***/				
				$sx .= '</fieldset>';
				return($sx);							
			}

		function filelist()
			{
				global $messa;
				require_once('_class_ged.php');
				$ged = new ged;
				
				if (strlen(trim($this->pb_protocolo_mae)) > 0)
					{
					$sx .= msg('file_projeto');
					$ged->tabela = $this->tabela_ged;
					$ged->protocol = $this->pb_protocolo_mae;
					$sx .= $ged->filelist();
					}

				if (strlen(trim($this->pb_protocolo)) > 0)
					{
					$sx .= msg('file_plano');
					$ged->tabela = $this->tabela_ged;
					$ged->protocol = $this->pb_protocolo;
					$sx .= $ged->filelist();
					}
					
				return($sx);
			}
			
		function bolsa_arquivos_lista($tipox)
			{
				global $dd;
				$tipo = $tipox;
				require("_ged_config.php");
				$ged_up_doc_type  = $tipox;
				$sx .= '<fieldset id="semic_files"><legend class="lt1"><I>'.msg('post').'</I></legend>';
				$sx .= '<font class="lt2">';
				$sx .= '<A name="id03">';
				$sx .= '<font class="lt2">';

				// Busca arquivos
				$frame = "sm_file_row";
				$sx .= '<div id="'.$frame.'">';
				$sx .= $ged->file_list();
				$sx .= '</div>';
						
				$link = 'ged_upload.php';
				$link .= '?dd0='.$dd[0];
				$link .= '&dd1='.$dd[1];
				$link .= '&dd3='.$dd[2];
				$link .= '&dd2='.$tipo;
				$link .= '&dd90='.checkpost($dd[0].$dd[1].$dd[2].$dd[3].$secu);
				/* chamada da janela popup */
				$sx .= newwin($link,600,400);
				$sx .= '<font class="lt0">postar novo arquivo</A>'.chr(13);
				
				/**** Normal ***/				
				$sx .= '</fieldset>';
				return($sx);				
			}

		function bolsa_arquivos_row()
			{
				global $dd;
				$sx .= '<fieldset id="semic_files"><legend class="lt1"><I>'.msg('post').'</I></legend>';
				$sx .= '<font class="lt2">';

				$sx .= '<A name="id03">';
				$sx .= '<font class="lt2">';

				// Busca arquivos
				$frame = "sm_file_row";
				$sx .= '<div id="'.$frame.'">';
				$sx .= '<script type="text/javascript">'.chr(13);
				$sx .= '    var tela01 = $.ajax( "pa_relatorio_final_ajax.php?dd1='.$dd[1].'&dd2=files&dd3='.$frame.'&dd90='.$dd[90].'" ) .done(function(data) { $("#'.$frame.'").html(data); }) .fail(function() { alert("error"); }) .always(function(data) { $("#'.$frame.'").html(data); }); '.chr(13);
				$sx .= '</script>'.chr(13);				
				$sx .= '</div>';
				

				$sx .= '<div id="sm_file">';
				$sx .= '</div>';
				
				$tipo = $this->tipo;
				$link = 'ged_upload.php';
				$link .= '?dd0='.$dd[0];
				$link .= '&dd1='.$dd[1];
				$link .= '&dd2='.$dd[2];
				$link .= '&dd3='.$tipo;
				$link .= '&dd90='.checkpost($dd[0].$dd[1].$dd[2].$dd[3].$secu);
				/* chamada da janela popup */
				$sx .= newwin($link,600,400);
				$sx .= '<font class="lt0"><IMG SRC="img/button_submit.png" border=0 alt="postar novo arquivo"></A>'.chr(13);
				
				/**** Normal ***/				
				$sx .= '</fieldset>';
				return($sx);				
			}

	/** 
	 * Valida órea do conhecimento
	 */

		function valida_relatorio_parcial()
			{
				$ok = 0;
				$sql = "select count(*) as total from pibic_ged_documento ";
				$sql .= " where doc_dd0='".$this->pb_protocolo."' ";
				$sql .= " and doc_tipo = 'RELAP' ";
				$sql .= " and doc_ativo = 1 ";
				$rlt = db_query($sql);
				$line = db_read($rlt);
				$cod = $line['total'];
//				if (($this->pb_relatorio_parcial < 20000101) and ($cod == 1))
//					{
//						$sql = "update ".$this->tabela." set pb_relatorio_parcial = ".date("Ymd")." where id_pb = ".$this->id_pb;
//						$rlt = db_query($sql); 
//					}
					
				if (($this->pb_relatorio_parcial > 20000101) and ($cod == 0))
					{
						//$sql = "update ".$this->tabela." set pb_relatorio_parcial = 19000101 where id_pb = ".$this->id_pb;
						//$rlt = db_query($sql); 
					}

				if ($cod > 0) { $ok = 1; }
				return($ok);				
			}

		function valida_relatorio_final()
			{
				$ok = 0;
				$sql = "select count(*) as total, doc_tipo from pibic_ged_documento ";
				$sql .= " where doc_dd0='".$this->pb_protocolo."' ";
				$sql .= " and doc_tipo = 'RELAF' ";
				$sql .= " group by doc_tipo ";
				
				$rlt = db_query($sql);
				$line = db_read($rlt);
				$cod = round($line['total']);
					
				if (($this->pb_relatorio_final > 20000101) and ($cod == 0))
					{
						$sql = "update ".$this->tabela." set pb_relatorio_final = 19000101 where id_pb = ".$this->id_pb;
						$rlt = db_query($sql); 
					}

				if ($cod > 0) { $ok = 1; }
				return($ok);				
			}


		/**
		 * Idioma disponóveis
		 */
		function idioma()
			{
				$rsti = array('pt_BR'=>'Portugues',
					'en'=>'Inglós',
					'en_US'=>'Inglós',
					//'es'=>'Espanhol',
					//'fr'=>'Frances'
					);
				return($rsti);
			}
		function idioma_mst($nm)
			{
				$rs = $this->idioma();
				$sx = $rs[$nm];
				return($sx);
			}
		 
		/**
		 * Descreve o nome do idioma pelo seu código
		 * @param string $idioma
		 */
		function mostra_idioma($idioma='')
		{
			if (strlen($idioma) == 0) { $idioma = $this->pb_semic_idioma; }
			$sx = $this->idioma_mst($idioma);
			if (strlen($sx)==0) { $sx = '<B><font color="FF0000">'.msg('not_defined'); }
			return($sx);
		}

		function valida_idioma()
			{
				$rt = $this->idioma();
				$keys = array_keys($rt);
				$ok = 0;
				for ($r=0;$r < count($keys);$r++)
					{
						$id = $keys[$r];	
						if (trim($this->pb_semic_idioma) == $id) 
							{ $ok = 1; }
					}
				return($ok);				
			}
		
		/**
		 * Grava novo idioma
		 */
		function grava_idioma()
			{
				$sql = "update ".$this->tabela." set ";
				$sql .= " pb_semic_idioma = '".$this->pb_semic_idioma."'";
				$sql .= " where id_pb = ".$this->id_pb;
				$rlt = db_query($sql);
				return(1);
			}
		/**
		 * Mostra o idioma seleciona com caixa de apresentação
		 */
		function semic_idioma()
			{
				global $dd;
			
				$sx .= '<fieldset '.$sty.' id="semic_idioma"><legend class="lt1"><I><nobr>'.msg('semic_idioma').'</I></legend>';
				$sx .= '<A name="id01">';
				$sx .= '<font class="lt2">';
				$sx .= msg('semic_idioma').':';
				$sx .= '<div id="sm_idioma">';
				$sx .= '</div>';
				$sx .= '<a href="#id01" id="ap_id_alterar"><font class="lt0">alterar</A>';
				$sx .= '<script>'.chr(13);
				/**** Alterar **/
				$sx .= '$("#ap_id_alterar").click(function () { ';
				$sx .= '    var tela01 = $.ajax( "'.$this->pg_valida.'?dd1='.$dd[1].'&dd2=idioma_alterar&dd90='.$dd[90].'" ) .done(function(data) { $("#sm_idioma").html(data); }) .fail(function() { alert("error"); }) .always(function(data) { $("#sm_idioma").html(data); }); '.chr(13);
				$sx .= ' } );'.chr(13);
			
				$sx .= '</script>'.chr(13);
				$sx .= '<script type="text/javascript">'.chr(13);
				$sx .= '    var tela01 = $.ajax( "'.$this->pg_valida.'?dd1='.$dd[1].'&dd2=idioma&dd90='.$dd[90].'" ) .done(function(data) { $("#sm_idioma").html(data); }) .fail(function() { alert("error"); }) .always(function(data) { $("#sm_idioma").html(data); }); '.chr(13);
				$sx .= '</script>'.chr(13);				
				$sx .= '</fieldset>';
				return($sx);
			}

	/** 
	 * Valida órea do conhecimento
	 */

		function valida_area()
			{
				$ok = 0;
				$cod = round($this->pb_semic_area);
				if ($cod > 0) { $ok = 1; }
				return($ok);				
			}


		function semic_area_do_conhecimento()
			{
				global $dd;
				$sx .= '<fieldset id="semic_area"><legend class="lt1"><I><nobr>'.msg('semic_area').'</nobr></I></legend>';
				$sx .= '<A name="id02">';
				$sx .= '<font class="lt2">';

				//$sx .= $this->mostra_area_do_conhecimento();
				
				$sx .= '<div id="sm_area">';
				$sx .= '</div>';
				
				$sx .= '<a href="#id02" id="ap_ar_alterar"><font class="lt0">alterar</A>';
				$sx .= '<script>'.chr(13);
				
				/**** Alterar **/
				$sx .= '$("#ap_ar_alterar").click(function () { ';
				$sx .= '    var tela01 = $.ajax( "'.$this->pg_valida.'?dd1='.$dd[1].'&dd2=area_alterar&dd90='.$dd[90].'" ) .done(function(data) { $("#sm_area").html(data); }) .fail(function() { alert("error"); }) .always(function(data) { $("#sm_area").html(data); }); '.chr(13);
				$sx .= ' } );'.chr(13);

				$sx .= '</script>'.chr(13);
				$sx .= '<script type="text/javascript">'.chr(13);
				$sx .= '    var tela01 = $.ajax( "'.$this->pg_valida.'?dd1='.$dd[1].'&dd2=area&dd90='.$dd[90].'" ) .done(function(data) { $("#sm_area").html(data); }) .fail(function() { alert("error"); }) .always(function(data) { $("#sm_area").html(data); }); '.chr(13);
				$sx .= '</script>'.chr(13);				

				$sx .= '</fieldset>';
				return($sx);
			}
		function mostra_area_do_conhecimento()
			{
				$area = $this->pb_semic_area_descricao;
				$curs = $this->pb_semic_curso_descricao;
				if (strlen($area)==0) { $area = '<font color="red">'.msg('not_defined').'</font>'; }
				if (strlen($curs)==0) { $curs = '<font color="red">'.msg('not_defined').'</font>'; }
				$sx .= msg('area').': <B>'.$area.'</B>';
				$sx .= '<BR>';
				$sx .= msg('curso').': <B>'.$curs.'</B>';
				return($sx);
			}
		/**
		 * Grava area do conhecimento
		 */
		function grava_area()
			{
				$sql = "update ".$this->tabela." set ";
				$sql .= " pb_semic_area = '".$this->pb_semic_area."'";
				$sql .= " where id_pb = ".$this->id_pb;
				$rlt = db_query($sql);
				return(1);
			}


		function bolsa_relatorio_parcial_tarefas($ano='')
			{
				if (strlen($ano)==0) 
				{
					$ano = date("Y");
					if (date("m") < 7) { $ano = (date("Y")-1); }
				}
				$sql = "select * from ".$this->tabela;
				$sql .= " left join discentes on pb_aluno = pa_cracha ";
				$sql .= " left join pibic_bolsa_tipo on pb_tipo = pbt_codigo ";
				$sql .= " left join pibic_status on pb_status = ps_codigo ";
				$sql .= " where pb_professor = '".$this->pb_professor."' ";
				$sql .= " and pb_relatorio_parcial <= 19000101 ";
				$sql .= " and pb_ano = '".$ano."'";
				
				$rlt = db_query($sql);
				while ($line = db_read($rlt))
					{
						$link = 'ic_relatorio_parcial_entrega.php';
						$link .= '?dd1='.$line['pb_protocolo'];
						$link .= '&dd90='.checkpost($line['pb_protocolo']);
						$link = '<A HREF="'.$link.'">'.msg('rel_parc_post')."<A>";
						$sx .= '<TR>';
						$sx .= '<TD>';
						$sx .= msg('protocol').': ';
						$sx .= trim($line['pb_protocolo']).'/'.trim($line['pb_ano']);

						$sx .= '<TR>';		
						$sx .= '<TD><B>';
						$sx .= $line['pb_titulo_projeto'].'</B>';
						
						$sx .= '<TR>';
						$sx .= '<TD>';
						$sx .= $line['pa_nome'];
						
						$sx .= '<TR>';
						$sx .= '<TD>';	
						$sx .= $line['pbt_descricao'];

						$sx .= '<TR>';
						$sx .= '<TD><I>Status: <B>';	
						$sx .= $line['ps_nome'].'</B>';

						$sx .= '<TR>';
						$sx .= '<TD>';
						$sx .= $link;
						$sx .= '<TR><TD><HR>';
					}
				if (strlen($sx) > 0)
					{
						$sh = '<TR><TH class="lt4" bgcolor="#f0f0f0">'.msg('rel_parcial');
						$sx = '<table class="lt1" align="center" width="100%">'.$sh.$sx.'</table>';
					}
				return($sx);
			}

		function bolsa_relatorio_parcial_correcao_tarefas($ano='')
			{
				if (strlen($ano)==0) 
				{
					$ano = date("Y");
					if (date("m") < 7) { $ano = (date("Y")-1); }
				}
				$sql = "select * from ".$this->tabela;
				$sql .= " left join discentes on pb_aluno = pa_cracha ";
				$sql .= " left join pibic_bolsa_tipo on pb_tipo = pbt_codigo ";
				$sql .= " left join pibic_status on pb_status = ps_codigo ";
				$sql .= " where pb_professor = '".$this->pb_professor."' ";
				$sql .= " and pb_ano = '".$ano."'";
				$sql .= " and pb_relatorio_parcial_nota = 2 ";
				
				$rlt = db_query($sql);
				
				$tot = 0;
				while ($line = db_read($rlt))
					{
						$rp = round('0'.trim($line['pb_relatorio_parcial_correcao']));
						if ($rp <= 20000101)
							{
							$link = 'pa_relatorio_parcial_correcao_entrega.php';
							$link .= '?dd1='.$line['pb_protocolo'];
							$link .= '&dd90='.checkpost($line['pb_protocolo']);
							$link = '<A HREF="'.$link.'">'.msg('rel_pco_post')."<A>";
							$sx .= '<TR>';
							$sx .= '<TD>';
							$sx .= msg('protocol').': ';
							$sx .= trim($line['pb_protocolo']).'/'.trim($line['pb_ano']);
	
							$sx .= '<TR>';		
							$sx .= '<TD><B>';
							$sx .= $line['pb_titulo_projeto'].'</B>';
							
							$sx .= '<TR>';
							$sx .= '<TD>';
							$sx .= $line['pa_nome'];
						
							$sx .= '<TR>';
							$sx .= '<TD>';	
							$sx .= $line['pbt_descricao'];

							$sx .= '<TR>';
							$sx .= '<TD><I>Status: <B>';	
							$sx .= $line['ps_nome'].'</B>';

							$sx .= '<TR>';
							$sx .= '<TD>';
							$sx .= $link;
							$sx .= '<TR><TD><HR>';
							$tot++;
							}
					}
				if (strlen($sx) > 0)
					{
						$sh = '<TR><TH class="lt4" bgcolor="#f0f0f0">'.msg('rel_parcial_corre');
						$sx = '<table class="lt1" align="center" width="100%">'.$sh.$sx.'</table>';
					}
				return($sx);
			}

		function bolsa_relatorio_parcial_avaliacao()
			{}
		function bolsa_relatorio_parcial_reprovacao()
			{}
		function bolsa_relatorio_parcial_reenvio()
			{}
		function bolsa_relatorio_parcial_reavaliacao()
			{}
		function bolsa_relatorio_parcial_tarefas_ativas()
			{}
			
		/** Resumo das bolsas ativoas */
		function bolsas_resumo_ativas()
			{
				$rsp = array();
				$sql = "select count(*) as total, pb_ano, pbt_codigo, pbt_nome from pibic_bolsa_contempladas ";
				$sql .= " left join pibic_submit_documento on doc_protocolo = pb_protocolo_mae ";
				$sql .= " left join pibic_bolsa_tipo on pbt_codigo = pb_tipo ";
				$sql .= " where (pb_status = 'A') ";
				$sql .= " and doc_protocolo <> pb_protocolo_mae ";
				$sql .= " and pb_ano = '".$pb_ano."' ";
				$sql .= " group by pbt_codigo, pbt_nome, pb_ano ";
				$sql .= " order by pb_ano, pb_nome, pb_status ";
				$rlt = db_query($sql);				
				while ($line = db_read($rlt))
					{
						array_push($rsp,array($line['pbt_codigo'],$line['pbt_nome'],$line['pb_ano'],$line['total']));
					}
				return($rsp);					
			}
		function bolsa_substituir_estudante()
			{}
		function bolsa_substituir_professor()
			{}
		function bolsa_cancelar()
			{}
		function bolsa_reativar()
			{}
		function bolsa_ativar()
			{}
		function updatex()
			{}
	}
