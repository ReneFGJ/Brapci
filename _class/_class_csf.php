<?php
class csf
	{
		var $protocolo;
		var $line;
		var $tabela = 'csf_inscricoes';
		
		function  lista_inscritos($todos=0,$d1=0,$d2=20509999,$local='')
			{
				$sql = "select * from ".$this->tabela." 
					where icsf_data > 20130901
				";
				$rlt = db_query($sql);
				while ($line = db_read($rlt))
					{
						
						print_r($line);
						echo '<HR>';
					}
			}
		
		function csf_pais()
			{
				$sql = "select count(*) as total, pa_curso from pibic_bolsa_contempladas 
						inner join pibic_bolsa_tipo on pb_tipo = pbt_codigo
						inner join pibic_aluno on pb_aluno = pa_cracha
						where pbt_edital = 'CSF' and pb_status <> 'C'
						group by pa_curso			
				";
				$rlt = db_query($sql);
				while ($line = db_read($rlt))
					{
						print_r($line);
						echo '<HR>';
					}
				$sql = "select count(*) as total, pb_colegio_orientador from pibic_bolsa_contempladas 
						inner join pibic_bolsa_tipo on pb_tipo = pbt_codigo
						inner join pibic_aluno on pb_aluno = pa_cracha
						where pbt_edital = 'CSF' and pb_status <> 'C'
						group by pb_colegio_orientador				
				";
				$rlt = db_query($sql);
				$pp = array();
				$tt = 0;
				$tot = 0;
				while($line = db_read($rlt))
					{
						$total = $line['total'];
						$tt++;
						$tot = $tot + $total;
						
						$pais = UpperCase(trim($line['pb_colegio_orientador']));
						if ($pais == 'COREIA DO SUL') { $pais = 'CORÉIA DO SUL'; }
						switch ($pais)
							{
							case 'BÉLGICA': 		$continente = 2; break;
							case 'PORTUGAL': 		$continente = 2; break;
							case 'ESPANHA': 		$continente = 2; break;
							case 'ALEMANHA': 		$continente = 2; break;
							case 'IRLANDA': 		$continente = 2; break;
							case 'FRANÇA': 			$continente = 2; break;
							case 'ITÁLIA': 			$continente = 2; break;
							case 'REINO UNIDO': 	$continente = 2; break;
							case 'HUNGRIA': 		$continente = 3; break;
							case 'ESCÓCIA': 		$continente = 2; break;
							case 'HUNGRIA': 		$continente = 2; break;
							case 'HOLANDA': 		$continente = 2; break;
							case 'CORÉIA DO SUL': 	$continente = 3; break;
							case 'AUSTRÁLIA': 		$continente = 4; break;
							case 'CHINA': 			$continente = 3; break;
							case 'HONG KONG': 		$continente = 3; break;
							case 'ESTADOS UNIDOS':	$continente = 5; break;
							default: $continente = 1; break;
							}
						array_push($pp,array($pais,$total,$continente));
					}
				$sx = '<?php
				$paises_total = '.round($tt).';
				$processo_total = '.(round($tot*1.8)).';
				$estudantes_total = '.(round($tot)).';
				?>
				';
				$rlt = fopen("../semic/dados_paises.php",'w');
				fwrite($rlt,$sx);
				fclose($rlt);
			}
		
		function estudantes_csf()
			{
				$sql = "select * from pibic_bolsa_contempladas 
						inner join pibic_bolsa_tipo on pb_tipo = pbt_codigo
						inner join pibic_aluno on pb_aluno = pa_cracha
						where pbt_edital = 'CSF' and pb_status <> 'C'					
				";
				$rlt = db_query($sql);
				$sx .= '<h3>Discentes Ciência sem Fronteiras</h3>';
				$sx .= '<table width="100%" class="tabela00">';
				$sx .= '<TR>';
				$sx .= '<TH>Escola';
				$sx .= '<TH>Total';

				
				$id = 0;
				
				while ($line = db_read($rlt))
					{
						$id++;
						$nome = trim($line['pa_nome']);
						$curso = $line['pb_colegio'];
						$pais = $line['pb_colegio_orientador'];
						$estra = $line['pb_area_estrategica'];
						$codigo = $line['pa_cracha'];
						$curso2 = $line['pa_curso'];
						
						//print_r($line);
						//echo '<HR>';
						$sx .= '<TR>';
						$sx .= '<TD class="tabela01">'.$codigo;
						$sx .= '<TD class="tabela01">'.$nome;
						$sx .= '<TD class="tabela01">'.$pais;
						$sx .= '<TD class="tabela01">'.$estra;
						$sx .= '<TD class="tabela01">'.$curso;
						$sx .= '<TD class="tabela01">'.$curso2;
						
						$sql .= "insert into csf_alunos 
								(scf_codigo, scf_nome, scf_pais, scf_estrategica, scf_curso, scf_universidade)
								values
								('$codigo','$nome','$pais','$estregica','$curso','$universidade');
						<BR>";
					}
				$sx .= '<TR><TD colspan=6>Total '.$id;
				$sx .= '</table>';
				echo $sql;
				$this->csf_pais();
				return($sx);
			}		
		
		function estudantes_em_viagem_centro()
			{
				$sql = "select count(*) as total, pa_centro, pb_ano from pibic_bolsa_contempladas 
						inner join pibic_bolsa_tipo on pb_tipo = pbt_codigo
						inner join pibic_aluno on pb_aluno = pa_cracha
						where pbt_edital = 'CSF' and pb_status <> 'C'
						and pb_ano = '2013'
						group by pa_centro, pb_ano
						order by pa_centro, pb_ano
						
				";
				$rlt = db_query($sql);
				$sx .= '<h3>Discentes Ciência sem Fronteiras</h3>';
				$sx .= '<table width="100%" class="tabela00">';
				$sx .= '<TR>';
				$sx .= '<TH>Escola';
				$sx .= '<TH>Total';

				
				$id = 0;
				$xnome = "X";
				while ($line = db_read($rlt))
					{
						$id++;
						$nome = trim($line['pa_centro']);
						$sx .= '<TR>';
						$sx .= '<TD class="tabela01">'.$nome;
						$sx .= '<TD class="tabela01" align="center">';
						$sx .= $line['total'];
					}
				$sx .= '<TR><TD colspan=6>Total '.$id;
				$sx .= '</table>';
				
				return($sx);
			}
			
		
		function estudantes_em_viagem_campus()
			{
				$sql = "select count(*) as total, pa_centro, pb_ano from pibic_bolsa_contempladas 
						inner join pibic_bolsa_tipo on pb_tipo = pbt_codigo
						inner join pibic_aluno on pb_aluno = pa_cracha
						where pbt_edital = 'CSF' and pb_status <> 'C'
						and pb_ano = '2013'
						group by pa_centro, pb_ano
						order by pa_centro, pb_ano
						
				";
				$rlt = db_query($sql);
				$sx .= '<h3>Discentes Ciência sem Fronteiras</h3>';
				$sx .= '<table width="100%" class="tabela00">';
				$sx .= '<TR>';
				$sx .= '<TH>Escola';
				$sx .= '<TH>Total';

				
				$id = 0;
				$xnome = "X";
				while ($line = db_read($rlt))
					{
						$id++;
						$nome = trim($line['pa_centro']);
						$sx .= '<TR>';
						$sx .= '<TD class="tabela01">'.$nome;
						$sx .= '<TD class="tabela01" align="center">';
						$sx .= $line['total'];
					}
				$sx .= '<TR><TD colspan=6>Total '.$id;
				$sx .= '</table>';
				return($sx);
			}
		
		
		function cp_dados()
			{
				$cp = array();
				array_push($cp,array('$H8','id_pb','',False,True));
				array_push($cp,array('$S1','pb_status','Status',False,True));
				return($cp);
			}
		
		function le($id)
			{
				$sql = "select * from pibic_bolsa_contempladas 
						inner join pibic_bolsa_tipo on pb_tipo = pbt_codigo
						inner join pibic_aluno on pb_aluno = pa_cracha
						where pb_protocolo = '".$id."' or id_pb = ".round($id);
				$rlt = db_query($sql);
				
				if ($line = db_read($rlt))
					{
						$this->protocolo = $line['pb_protocolo'];
						$this->line = $line;
						return(1);
					}
				return(0);				
			}
		function mostra_status($sta)
			{
				switch ($sta)
					{
					case 'A': $sx .= 'em viagem'; break;
					case 'B': $sx .= 'retornou'; break;
					case 'C': $sx .= 'cancelado'; break;
					case 'D': $sx .= 'desistiu'; break;
					}
				return($sx);
			}
		function mostra_dados()
			{
				global $perfil;
				$line = $this->line;
				
				$sx .= '<fieldset><legend>Dados da viagem</legend>';
				$sx .= '<table width="100%">';
				$sx .= '<TR valign="top">
						<TD width="60%"><font class="lt0">título do plano</font><BR>';
				$sx .= '<font class="lt3">'.$line['pb_titulo_projeto'].'</font>';
				
				$sx .= '<BR><BR>Situação: <B><font class="lt4">';
				$sx .= $this->mostra_status($line['pb_status']);
				$sx .= '</font></B>';
				$sx .= '<TD width="40%" bgcolor="#E0E0E0">';
				$sx .= 'Protocolo: <B>'.$line['pb_protocolo'].'</B>';
				$sx .= '<BR>Edital: <B>'.$line['pbt_descricao'].'/'.$line['pb_ano'].'</B>';
				$sx .= '<BR>Saída: <B>'.stodbr($line['pb_ativacao']).'</B>';
				$sx .= '<BR>Retorno / Previsão: <B>'.stodbr($line['pb_desativacao']).'</B>';
				$sx .= '<BR>Pais: <B>'.$line['pb_colegio_orientador'].'</B>';
				$sx .= '<BR>Universidade: <B>'.$line['pb_colegio'].'</B>';
				$sx .= '<TD width="60%">';
				$sx .= '</table>';
				if ($perfil->valid('#SPI#CPI'))
					{
						$link = '<A HREF="csf_ed.php?dd0='.$line['id_pb'].'&dd90='.checkpost($line['id_pb']).'" class="link">';
						$sx .= $link.'editar'.'</A>';
					}
				$sx .= '</fieldset>';
				return($sx);
			}
		
		function estudantes_em_viagem()
			{
				$sql = "select * from pibic_bolsa_contempladas 
						inner join pibic_bolsa_tipo on pb_tipo = pbt_codigo
						inner join pibic_aluno on pb_aluno = pa_cracha
						left join curso on pa_curso_cod = curso_codigo
						left join centro on curso_centro = centro_codigo
						where pbt_edital = 'CSF' and pb_status <> 'C'
						and pb_ano = '2013'
						order by pa_nome 
						
				";
				$rlt = db_query($sql);
				$sx .= '<h3>Discentes Ciência sem Fronteiras</h3>';
				$sx .= '<table width="100%" class="tabela00">';
				$sx .= '<TR>';
				$sx .= '<TH>Protocolo';
				$sx .= '<TH>Status';
				
				$sx .= '<TH>Discente';
				$sx .= '<TH>Ano';
				
				$sx .= '<TH>Curso';
				$sx .= '<TH>Escola';
				$sx .= '<TH>Universidade';
				$sx .= '<TH>País';
				
				$sx .= '<TH>Área estratégica';
								
				
				
				$id = 0;
				$xnome = "X";
				while ($line = db_read($rlt))
					{
						$nome = trim($line['pa_nome']);
						$font = '<font>';
						if ($nome == $xnome) { $font = '<font color="red">'; }
						$link = $line['pb_protocolo'];
						$link = '<A HREF="csf_detalhe.php?dd0='.$link.'&dd90='.checkpost($link).'" class="link">';
						$id++;
						$sx .= '<TR>';
						$sx .= '<TD class="tabela01">'.$link.$line['pb_protocolo'].'</A>';
						
						$sta = $line['pb_status'];
						$sx .= '<TD class="tabela01">';
						$sx .= $this->mostra_status($sta);
												
						$sx .= '<TD class="tabela01">'.$font.$line['pa_nome'].'</font>';
						$sx .= '<TD class="tabela01">'.$line['pb_ano'];
						
						$sx .= '<TD class="tabela01">'.($line['curso_nome']);
						
						$sx .= '<TD class="tabela01">'.($line['centro_nome']);
												
						$sx .= '<TD class="tabela01">'.$line['pb_colegio'];												
						$sx .= '<TD class="tabela01">'.$line['pb_area_estrategica'];
						$sx .= '<TD class="tabela01">'.$line['pb_colegio_orientador'];						
						//print_r($line);
						//exit;
						

						
						$ln = $line;
					}
				$sx .= '<TR><TD colspan=6>Total '.$id;
				$sx .= '</table>';
				return($sx);
			}
		
		
		function depoimento_mostra($nome,$data=19000101,$texto='',$foto='',$pais='')
			{
				$sx = '<table width="100%" class="lt1">';
				
				$sx .= '<TR valign="top">';
				
				$sx .= '<TD rowspan=3 >';
				
				$sx .= '<A HREF="img_post/'.$foto.'" targer="new'.$data.'" >'; 
				$sx .= '<img src="img_post/'.$foto.'" width="100" border=0>';
				$sx .= '</A>';
				
				$sx .= '<TD>';
				
				$sx .= '<B>'.$nome;
				$sx .= ' - '.$pais;
				$sx .= '<TR><TD>'.$texto;
				$sx .= '<TR><TD>'.stodbr($data);
				$sx .= '</table>';
				return($sx);
			}
		
		function total_inscricoes()
			{
				$sql= "select count(*) as total from ".$this->tabela."  ";
				$rlt = db_query($sql);
				$line = db_read($rlt);
				return($line['total']);				
			}
		
		function estudante_perfil()
			{
				$sql = "select count(*) as total, pa_curso from pibic_bolsa_contempladas 
						inner join pibic_aluno on pb_aluno = pa_cracha
						where (pb_tipo = 'K' or pb_tipo = 'S' or pb_tipo = 'T' or pb_tipo = 'W')
						and pb_ativo = 1 and (pb_status <> 'C' and pb_status <> '@')
						group by pa_curso
						order by pa_curso 
						 ";
				$rlt = db_query($sql);
				$st = '';
				$sv = '';
				$col = 99;
				
				$curso=array();
				$curson=array();
				while ($line = db_read($rlt))
					{
					$total = $line['total'];
					$paisn = trim($line['pa_curso']);
					if (strlen($paisn) > 0)
						{
						if (strlen($st) > 0) { $st .= ', '.chr(13).chr(10); $sv .= ', '; }
						$st .= "['$paisn', $total]";
						$sv .= "$total";

						if ($col > 1) { $col = 0; $sq .= '<TR>'; }
						$sq .= '<TD align="left">'.msg($paisn).'<TD align="center">'.$line['total'];
						$col++;						
						}
					}
				$st = "['Bolsista Prouni',16],".chr(13).chr(10);
				$st .= "['Bolsa PUCPR',3],".chr(13).chr(10);
				$st .= "['FIES',2],".chr(13).chr(10);
				$st .= "['Sem bolsa',28]".chr(13).chr(10);
				$sx .= '
				     <script type="text/javascript" src="http://www.google.com/jsapi"></script>
    				<script type="text/javascript">
      					google.load(\'visualization\', \'1\', {packages: [\'corechart\']});
    				</script>
    				<script type="text/javascript">
      					function drawVisualization() {
        				// Create and populate the data table.
        				var data = google.visualization.arrayToDataTable([
          				[\''.msg('curso').'\', \''.msg('estudantes').'\'],
          				';
				$sx .= $st;
         		$sx .= ']);
       					 new google.visualization.PieChart(document.getElementById(\'visualization\')).
            				draw(data, {title:"Perfil dos Estudantes em Intercâmbio"});
      					}
      				google.setOnLoadCallback(drawVisualization);
    			</script>
  				<div id="visualization" style="width: 750px; height: 400px;"></div>';
				
				$sx .= '<BR><BR><H2>'.msg('perfil_dos_estudantes').'</H2>';
				$sx .= '<table width=750 align=center class="lt0" cellpadding=3 cellspacing=0 border=1>';
				$sx .= '<TR>';
				$sx .= '<TH width=35% align="left">'.msg('pais').'<TH width=13%>'.msg('estudantes');
				$sx .= '<TH width=35% align="left">'.msg('pais').'<TH width=13%>'.msg('estudantes');
				$sx .= $sq.'</table>';
								
    			return($sx);
				
			}
		
		function estudante_area()
			{
				$sql = "select count(*) as total, pa_curso from pibic_bolsa_contempladas 
						inner join pibic_aluno on pb_aluno = pa_cracha
						where (pb_tipo = 'K' or pb_tipo = 'S' or pb_tipo = 'T' or pb_tipo = 'W')
						and pb_ativo = 1 and (pb_status <> 'C' and pb_status <> '@')
						group by pa_curso
						order by pa_curso 
						 ";
				$rlt = db_query($sql);
				$st = '';
				$sv = '';
				$col = 99;
				
				$curso=array();
				$curson=array();
				while ($line = db_read($rlt))
					{
					$total = $line['total'];
					$paisn = trim($line['pa_curso']);
					if (strlen($paisn) > 0)
						{
						if (strlen($st) > 0) { $st .= ', '.chr(13).chr(10); $sv .= ', '; }
						$st .= "['$paisn', $total]";
						$sv .= "$total";

						if ($col > 1) { $col = 0; $sq .= '<TR>'; }
						$sq .= '<TD align="left">'.$paisn.'<TD align="center">'.$line['total'];
						$col++;						
						}
					}
				$st = "['Ciência da Computação e tecnologia da informação',1],".chr(13).chr(10);
				$st .= "['Engenharias e demais áreas tecnológicas',20],".chr(13).chr(10);
				$st .= "['Biologia, ciências biomédicas e saúde',14],".chr(13).chr(10);
				$st .= "['Industria criativa',4],".chr(13).chr(10);
				$st .= "['Biotecnologia',4],".chr(13).chr(10);
				$st .= "['Energias renováveis',3],".chr(13).chr(10);
				$st .= "['Ciências extas e da terra',2],".chr(13).chr(10);
				$st .= "['Novas Tec. E engenharia construtiva',1]".chr(13).chr(10);
				$sx .= '
				     <script type="text/javascript" src="http://www.google.com/jsapi"></script>
    				<script type="text/javascript">
      					google.load(\'visualization\', \'1\', {packages: [\'corechart\']});
    				</script>
    				<script type="text/javascript">
      					function drawVisualization() {
        				// Create and populate the data table.
        				var data = google.visualization.arrayToDataTable([
          				[\'Cruso\', \'Estudantes\'],
          				';
				$sx .= $st;
         		$sx .= ']);
       					 new google.visualization.PieChart(document.getElementById(\'visualization\')).
            				draw(data, {title:"Estudantes por Cursos"});
      					}
      				google.setOnLoadCallback(drawVisualization);
    			</script>
  				<div id="visualization" style="width: 750px; height: 400px;"></div>';
				
				$sx .= '<BR><BR><H2>àreas estratégicas dos Estudantes da PUCPR</H2>';
				$sx .= '<table width=750 align=center class="lt0" cellpadding=3 cellspacing=0 border=1>';
				$sx .= '<TR>';
				$sx .= '<TH width=35% align="left">Pais<TH width=13%>Estudantes';
				$sx .= '<TH width=35% align="left">Pais<TH width=13%>Estudantes';
				$sx .= $sq.'</table>';
								
    			return($sx);
				
			}
		
		function estudante_curso_pie()
			{
				$sql = "select count(*) as total, pa_curso from pibic_bolsa_contempladas 
						inner join pibic_aluno on pb_aluno = pa_cracha
						where (pb_tipo = 'K' or pb_tipo = 'S' or pb_tipo = 'T' or pb_tipo = 'W')
						and pb_ativo = 1 and (pb_status <> 'C' and pb_status <> '@')
						group by pa_curso
						order by pa_curso 
						 ";
				$rlt = db_query($sql);
				$st = '';
				$sv = '';
				$col = 99;
				
				$curso=array();
				$curson=array();
				while ($line = db_read($rlt))
					{
					$total = $line['total'];
					$paisn = msg(trim($line['pa_curso']));
					if (strlen($paisn) > 0)
						{
						if (strlen($st) > 0) { $st .= ', '.chr(13).chr(10); $sv .= ', '; }
						$st .= "['$paisn', $total]";
						$sv .= "$total";

						if ($col > 0) { $col = 0; $sq .= '<TR>'; }
						$sq .= '<TD align="left">'.$paisn.'<TD align="center">'.$line['total'];
						$col++;						
						}
					}
				$sx .= '
				     <script type="text/javascript" src="http://www.google.com/jsapi"></script>
    				<script type="text/javascript">
      					google.load(\'visualization\', \'1\', {packages: [\'corechart\']});
    				</script>
    				<script type="text/javascript">
      					function drawVisualization() {
        				// Create and populate the data table.
        				var data = google.visualization.arrayToDataTable([
          				[\'Cruso\', \'Estudantes\'],
          				';
				$sx .= $st;
         		$sx .= ']);
       					 new google.visualization.PieChart(document.getElementById(\'visualization\')).
            				draw(data, {title:"Estudantes por Cursos"});
      					}
      				google.setOnLoadCallback(drawVisualization);
    			</script>
  				<div id="visualization" style="width: 750px; height: 400px;"></div>';
				
				$sx .= '<BR><BR><H2>Cursos dos Estudantes da PUCPR que estão estudando em outros países</H2>';
				$sx .= '<table width=750 align=center class="lt0" cellpadding=3 cellspacing=0 border=1>';
				$sx .= '<TR>';
				$sx .= '<TH width=35% align="left">Pais<TH width=13%>Estudantes';
				$sx .= '<TH width=35% align="left">Pais<TH width=13%>Estudantes';
				$sx .= $sq.'</table>';
								
    			return($sx);
		}
								
		function estudante_curso()
			{
				$sql = "select count(*) as total, pa_curso from pibic_bolsa_contempladas 
						inner join pibic_aluno on pb_aluno = pa_cracha
						where (pb_tipo = 'K' or pb_tipo = 'S' or pb_tipo = 'T' or pb_tipo = 'W')
						and pb_ativo = 1 and (pb_status <> 'C' and pb_status <> '@')
						group by pa_curso
						order by pa_curso 
						 ";
				$rlt = db_query($sql);
				$st = '';
				$sv = '';
				$col = 99;
				while ($line = db_read($rlt))
					{
					$total = $line['total'];
					$paisn = msg(trim($line['pa_curso']));
					if (strlen($paisn) > 0)
						{
						if (strlen($st) > 0) { $st .= ', '; $sv .= ', '; }
						$st .= "'$paisn'";
						$sv .= "$total";

						if ($col > 0) { $col = 0; $sq .= '<TR>'; }
						$sq .= '<TD>'.$paisn.'<TD align="center">'.$line['total'];
						$col++;						
						}
					}
				$sx .= '				 
					<script type="text/javascript" src="http://www.google.com/jsapi"></script>
    				<script type="text/javascript">
      					google.load(\'visualization\', \'1\');
    				</script>
    				<script type="text/javascript">
      					function drawVisualization() {
        				var wrapper = new google.visualization.ChartWrapper({
          				chartType: \'ColumnChart\',
          				dataTable: [['.$st.'],
                      		['.$sv.']],
          					options: {\'title\': \'Estudantes por países\'},
          					containerId: \'visualization\'
        					});
        					wrapper.draw();
      					}
      				google.setOnLoadCallback(drawVisualization);
    			</script>
  				<div id="visualization" style="width: 750px; height: 400px;"></div>';
				
				$sx .= '<BR><BR><H2>'.msg('cursos_dos_estudantes').'</H2>';
				//Cursos dos Estudantes da PUCPR que estão estudando em outros países
				$sx .= '<table width=750 align=center class="lt0" cellpadding=3 cellspacing=0 border=1>';
				$sx .= '<TR>';
				$sx .= '<TH width=35% align="left">'.msg('curso').'<TH width=13%>'.msg('estudantes');
				$sx .= $sq.'</table>';
								
    			return($sx);
		}
		function estudante_escola()
			{
				$sql = "select count(*) as total, pa_curso, pa_escola 
						from pibic_bolsa_contempladas 
							inner join pibic_aluno on pb_aluno = pa_cracha
							left join centro on centro_codigo = pa_escola
							where (pb_tipo = 'K' or pb_tipo = 'S' or pb_tipo = 'T' or pb_tipo = 'W')
							and pb_ativo = 1 and (pb_status <> 'C' and pb_status <> '@')
							group by pa_curso, pa_escola
						order by pa_curso 
						 ";
						 echo $sql;
				$rlt = db_query($sql);
				echo 'ok';
				$st = '';
				$sv = '';
				$col = 99;
				while ($line = db_read($rlt))
					{
						print_r($line).'<HR>';
					$total = $line['total'];
					$paisn = msg(trim($line['curso_centro']));
					if (strlen($paisn) > 0)
						{
						if (strlen($st) > 0) { $st .= ', '; $sv .= ', '; }
						$st .= "'$paisn'";
						$sv .= "$total";

						if ($col > 0) { $col = 0; $sq .= '<TR>'; }
						$sq .= '<TD>'.$paisn.'<TD align="center">'.$line['total'];
						$col++;						
						}
					}
				$sx .= '				 
					<script type="text/javascript" src="http://www.google.com/jsapi"></script>
    				<script type="text/javascript">
      					google.load(\'visualization\', \'1\');
    				</script>
    				<script type="text/javascript">
      					function drawVisualization() {
        				var wrapper = new google.visualization.ChartWrapper({
          				chartType: \'ColumnChart\',
          				dataTable: [['.$st.'],
                      		['.$sv.']],
          					options: {\'title\': \'Estudantes por países\'},
          					containerId: \'visualization\'
        					});
        					wrapper.draw();
      					}
      				google.setOnLoadCallback(drawVisualization);
    			</script>
  				<div id="visualization" style="width: 750px; height: 400px;"></div>';
				
				$sx .= '<BR><BR><H2>'.msg('cursos_dos_estudantes').'</H2>';
				//Cursos dos Estudantes da PUCPR que estão estudando em outros países
				$sx .= '<table width=750 align=center class="lt0" cellpadding=3 cellspacing=0 border=1>';
				$sx .= '<TR>';
				$sx .= '<TH width=35% align="left">'.msg('curso').'<TH width=13%>'.msg('estudantes');
				$sx .= $sq.'</table>';
								
    			return($sx);
		}						
		function world_onde_curso_universidade()
			{
				$sql = "select * from pibic_bolsa_contempladas 
						inner join pibic_aluno on pb_aluno = pa_cracha
						where (pb_tipo = 'K' or pb_tipo = 'S' or pb_tipo = 'T' or pb_tipo = 'W')
						and pb_ativo = 1 and (pb_status <> 'C' and pb_status <> '@')
						order by pa_curso, pb_colegio_orientador, pb_colegio
						 ";
				$rlt = db_query($sql);
				$sx .= '<center><h2>'.msg('undergraduate course').'</h2>';
				$sx .= '<table class="lt1" width="100%">';
				$xpais = 'X';
				while ($line = db_read($rlt))
					{
						$pais = msg(trim($line['pa_curso']));
						if ($pais != $xpais)
							{ $sx .= '<TR bgcolor="#C0C0C0"><TD colspan=5 class="lt3">'.$pais; $xpais = $pais; }
						$sx .= '<TR '.coluna().'>';
						$sx .= '<TD>'.$line['pb_colegio'];
						
						//$sx .= '<TD>'.$line['pa_nome'];
						$sx .= '<TD>'.$line['pb_colegio_orientador'];
						$sx .= '<TD>'.substr($line['pa_nome'],0,strpos($line['pa_nome'],' '));
					}
				$sx .= '</table>';
				return($sx);			
			}							
		
		function world_onde_pais_universidade()
			{
				$sql = "select * from pibic_bolsa_contempladas 
						inner join pibic_aluno on pb_aluno = pa_cracha
						where (pb_tipo = 'K' or pb_tipo = 'S' or pb_tipo = 'T' or pb_tipo = 'W')
						and pb_ativo = 1 and (pb_status <> 'C' and pb_status <> '@')
						order by pb_colegio_orientador, pb_colegio
						 ";
				$rlt = db_query($sql);
				$sx .= '<center><h2>'.msg('country').'</h2>';
				$sx .= '<table class="lt1">';
				$xpais = 'X';
				while ($line = db_read($rlt))
					{
						$pais = trim($line['pb_colegio_orientador']);
						if ($pais != $xpais)
							{ $sx .= '<TR bgcolor="#C0C0C0"><TD colspan=5 class="lt3">'.$pais; $xpais = $pais; }
						$sx .= '<TR '.coluna().'>';
						$sx .= '<TD>'.$line['pb_colegio'];
						$sx .= '-'.$line['id_pb'];
						
						//$sx .= '<TD>'.$line['pa_nome'];
						$sx .= '<TD>'.$line['pa_curso'];
						$sx .= '<TD>'.substr($line['pa_nome'],0,strpos($line['pa_nome'],' '));
						//$sx .= '<TD>'.$line['pa_nome'];
					}
				$sx .= '</table>';
				return($sx);			
			}	
		
		function world_mapa_onde()
			{
				$sql = "select count(*) as total, pb_colegio_orientador from pibic_bolsa_contempladas 
						inner join pibic_aluno on pb_aluno = pa_cracha
						where (pb_tipo = 'K' or pb_tipo = 'S' or pb_tipo = 'T' or pb_tipo = 'W')
						and pb_ativo = 1  and (pb_status <> 'C' and pb_status <> '@')
						group by pb_colegio_orientador
						order by total desc
						 ";
				$rlt = db_query($sql);
				$st = "'País'";
				$sv = "0";
				$col = 99;
				while ($line = db_read($rlt))
					{
					$total = $line['total'];
					$paisn = trim($line['pb_colegio_orientador']);
					if (strlen($paisn) > 0)
						{
						if (strlen($st) > 0) { $st .= ', '; $sv .= ', '; }
						$st .= "'$paisn'";
						$sv .= "$total";

						if ($col > 2) { $col = 0; $sq .= '<TR>'; }
						$sq .= '<TD>'.$paisn.'<TD align="center">'.$line['total'];
						$col++;						
						}
					}
				$sx .= '				 
					<script type="text/javascript" src="http://www.google.com/jsapi"></script>
    				<script type="text/javascript">
      					google.load(\'visualization\', \'1\');
    				</script>
    				<script type="text/javascript">
      					function drawVisualization() {
        				var wrapper = new google.visualization.ChartWrapper({
          				chartType: \'ColumnChart\',
          				dataTable: [['.$st.'],
                      		['.$sv.']],
          					options: {\'title\': \'Estudantes por países\'},
          					containerId: \'visualization\'
        					});
        					wrapper.draw();
      					}
      				google.setOnLoadCallback(drawVisualization);
    			</script>
  				<div id="visualization" style="width: 750px; height: 400px;"></div>';
				
				$sx .= '<BR><BR><H2>Estudantes da PUCPR por países</H2>';
				$sx .= '<table width=600 align=center class="lt0" cellpadding=3 cellspacing=0 border=1>';
				$sx .= '<TR>';
				$sx .= '<TH width=20%>Pais<TH width=13%>Estudantes';
				$sx .= '<TH width=20%>Pais<TH width=13%>Estudantes';
				$sx .= '<TH width=20%>Pais<TH width=13%>Estudantes';
				$sx .= $sq.'</table>';
								
    			return($sx);
		}
		
		function world_mapa_estudantes()
			{
				
				$sql = "select count(*) as total, pb_colegio_orientador from pibic_bolsa_contempladas 
						inner join pibic_aluno on pb_aluno = pa_cracha
						where (pb_tipo = 'K' or pb_tipo = 'S'  or pb_tipo = 'T' or pb_tipo = 'W')
						and pb_ativo = 1 and (pb_status <> 'C' and pb_status <> '@')
						group by pb_colegio_orientador
						order by pb_colegio_orientador
						 ";
				$rlt = db_query($sql);
				$st = '';
				$col = 99;
				while ($line = db_read($rlt))
					{
					$paisn = trim($line['pb_colegio_orientador']);
					$pais = uppercasesql(trim($line['pb_colegio_orientador']));
					
					if ($pais == 'EUA') { $pais = 'United States'; }
					if ($pais == 'BELGICA') { $pais = 'Belgium'; }
					if ($pais == 'Hong Kong') { $pais = 'HK'; }
					if ($pais == 'ESCOCIA') { $pais = 'United Kingdom'; }
					if (strlen($pais) > 0)
						{
							if ($col > 2) { $col = 0; $sq .= '<TR>'; }
							$sq .= '<TD>'.$paisn.'<TD align="center">'.$line['total'];
							$col++;
							if (strlen($st) > 0) { $st .= ', '.chr(13).chr(10); }
							$st .= '[\''.$pais.'\','.$line['total'].']';
						}
					}
				$sx .= '
  					<script type="text/javascript" src="http://www.google.com/jsapi"></script>
  					<script type="text/javascript">
    				google.load(\'visualization\', \'1\', {packages: [\'geochart\']});
    			    function drawVisualization() {
						var options = {
        				backgroundColor: \'#FF0000\',
        				displayMode: \'markers\',
        				colorAxis: {minValue: 0,  colors: [\'#FF0000\', \'#00FF00\']}
      					};      	
					var data = google.visualization.arrayToDataTable([
        				[\'País\', \'Estudantes\'],
        				'.$st.'
      				]);
      			 var geochart = new google.visualization.GeoChart(
          				document.getElementById(\'visualization\'));
      					geochart.draw(data, {width: 740, height: 347});
    				}
			    google.setOnLoadCallback(drawVisualization);
  				</script>
	
				<div id="visualization" style="z-index:0;"></div>
				<div style="text-align: justify">Mapa</div>
			
				<style>
					#visualization
					{
					width: 740px;
					border: 2px solid #C0C0C0;
					}
				</style>';
				
				$sx .= '<BR><BR><H2>Estudantes da PUCPR por países</H2>';
				$sx .= '<table width=600 align=center class="lt0" cellpadding=3 cellspacing=0 border=1>';
				$sx .= '<TR>';
				$sx .= '<TH width=20%>Pais<TH width=13%>Estudantes';
				$sx .= '<TH width=20%>Pais<TH width=13%>Estudantes';
				$sx .= '<TH width=20%>Pais<TH width=13%>Estudantes';
				$sx .= $sq.'</table>';
				return($sx);				
			}
		
		
		function total_bolsistas()
			{
				$sql = "select count(*) as total from pibic_bolsa_contempladas 
						inner join pibic_aluno on pb_aluno = pa_cracha
						where (pb_tipo = 'K' or pb_tipo = 'S' or pb_tipo = 'T' or pb_tipo = 'W')
						and pb_ativo = 1 and (pb_status <> 'C' and pb_status <> '@')
						 ";
				$rlt = db_query($sql);
				if ($line = db_read($rlt))
					{
						$total = $line['total'];
					}
				return($total);				
			}
		
		function lista_bolsistas()
			{
				$sql = "select * from pibic_bolsa_contempladas 
						inner join pibic_aluno on pb_aluno = pa_cracha
				where (pb_tipo = 'K' or pb_tipo = 'S' or pb_tipo = 'T' or pb_tipo = 'W')
				and pb_ativo = 1 and (pb_status <> 'C' and pb_status <> '@')
				order by pa_nome
				 ";
				$rlt = db_query($sql);
				$sx  = '<table width="100%" class="lt0" cellpadding=0 cellspacing=4 border=0 >' ;
				$sx .= '<TR><TH>Nome do estudante<TH>Curso<TH>Estudando';
				$tot = 0;
				while ($line = db_read($rlt))
					{
						$tot++;
						$curso = trim($line['pa_curso']);
						if (strpos($curso,'(') > 0)
							{ $curso = substr($curso,0,strpos($curso,'(')); }
						$sx .= '<TR '.coluna().'>';
						$sx .= '<TD>';
						$sx .= trim($line['pa_nome']);
						$sx .= '<TD>';
						$sx .= $curso;
						$sx .= '<TD>';
						$sx .= trim($line['pb_colegio_orientador']);
					}
				$sx .= '<TR><TD colspan=4>Total de '.$tot.' estudantes em Intercâmbio.';
				$sx  .= '</table>';
				return($sx);
			}
		
		function inscricao_csf($estudante,$edital,$passaport,$periodo,$idioma,$bolsa,$permanecia=12,$nota=0)
			{
				$sql = "ALTER TABLE ".$this->tabela." ADD column icsf_nota char(5)";
				//$rlt = db_query($sql);
				$idioma = substr($idioma,0,100);
				$data = date("Ymd");
				$hora = date("H:i");
				$sql= "select * from ".$this->tabela." where icsf_estudante = '$estudante' ";
				$rlt = db_query($sql);
				if (!($line = db_read($rlt)))
				{				
				$sql = "insert into ".$this->tabela." (
						icsf_estudante, icsf_edital, icsf_data ,
						icsf_hora, icsf_periodo, icsf_idioma_test, 
						icsf_passaport, icsf_bolsa ,
						icsf_ativo , icsf_status ,icsf_permanecia,
						icsf_nota
						
						) values (
						'$estudante','$edital',$data,
						'$hora','$periodo','$idioma',
						'$passaport','$bolsa',
						1,'A',$permanecia,
						$nota
						)";
				$rlt = db_query($sql);
				return(1);						
				} else {
					$id = round($line['id_icsf']);
					$sql = "update ".$this->tabela." 
						set icsf_edital = '$edital',
						icsf_data = $data,
						icsf_hora = '$hora',
						icsf_periodo = '$periodo',
						icsf_idioma_test = '$idioma',
						icsf_passaport = '$passaport',
						icsf_bolsa = '$bolsa',
						icsf_ativo = '1',
						icsf_status = 'A',
						icsf_nota = $nota,
						icsf_permanecia = '$permanecia'
						where id_icsf = $id
					";
					$rlt = db_query($sql);
					return(2);
				}	
			}
		function strucuture()
			{
				$sql = "CREATE TABLE ".$this->tabela."
					(
						id_icsf serial not null,
						icsf_estudante char(8),
						icsf_edital char(7),
						icsf_data integer,
						icsf_hora char(5),
						icsf_periodo char(2),
						icsf_idioma_test char(100),
						icsf_passaport char(1),
						icsf_bolsa char(6),
						icsf_ativo integer,
						icsf_status char(1),
						icsf_permanecia integer
					)
				";
				$rlt = db_query($sql);		
			}
	
	}
?>
