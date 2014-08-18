<?php
class lattes
	{
		var $link;
		var $nome;
		var $cracha;
		
		var $docentes = "pibic_professor";
		var $discentes = "pibic_aluno";
		
		var $erros = '';
		
		function busca_professor_cpf($cpf)
			{
				$sql = "select * from ".$this->docentes." where pp_cpf = '".$cpf."' and pp_ativo = 1";
				$rlt = db_query($sql);
				
				if ($line = db_read($rlt))
					{
						//print_r($line);
						return($line['pp_cracha']);
					}
			}
		
		function delete_records_from($ano=9999)
			{
				$sql = "delete from lattes_artigos where la_ano >= '".$ano."' "; 
				$rlt = db_query($sql);
				$line = db_read($rlt);
				return(1);			
			}
		
		function delete_file($arq)
			{
				$arq = 'tmp/'.$arq;
				echo $arq;
				if (file_exists($arq))
					{
					unlink($arq);
					echo '<BR>Arquivo excluido: '.$arq;
					return(1);
					}
				echo '<BR>ERRO!';
				return(0);
			}
		
		function next_file()
			{
				$tps = array();
				array_push($tps,'LIVRO');
				array_push($tps,'EVENT');
				array_push($tps,'LVORG');
				array_push($tps,'CAPIT');
				array_push($tps,'ARTIG');
				array_push($tps,'PROJE');
				
				$file = '';
				for ($r=0;$r < count($tps);$r++)
					{
						for ($y=0;$y < 500;$y++)
							{
								$arq = $tps[$r].'_'.strzero($y,4);
								if (file_exists('tmp/'.$arq))
									{ return($arq); } 
							}
					}
					
			}
		
		function arquivos_salva_quebrado($ln,$tipo)
			{
				$lnh = $ln[0];
				$arq = 0;
				$pos = 0; $open = 0;
				$cr = chr(13);
				
				for ($r=1;$r < count($ln);$r++)
					{
						if (($pos==0) or ($pos > 49))
							{
							if ($open==1) { fclose($farq); }
							$farq = fopen('tmp/'.$tipo.'_'.strzero($arq++,4),'w');
							echo '<BR>Salvando... '.$tipo.'_'.strzero($arq,4);
							fwrite($farq,$lnh.$cr);
							$open = 1;
							$pos = 0;
							}
						$pos++;
						fwrite($farq,$ln[$r].$cr);
					}
				if ($open==1) { fclose($farq); }
			}
		
		function tipo_obra($ln)
			{
				$tp = '';
				if (strpos($ln,'"Título do Livro";"ISBN";"Ano Publicação";') > 0) { $tp = 'LIVRO'; }
				if (strpos($ln,'"Título da Obra Publicada";"ISBN";"Ano Publicação";"') > 0) { $tp = 'LVORG'; }
				if (strpos($ln,'"Título do Trabalho";"Evento";"ISBN"') > 0) { $tp = 'EVENT'; }
				if (strpos($ln,'"Título";"ISBN";"Ano Publicação";"DSC_IDIOMA";') > 0) { $tp = 'CAPIT'; }
				
				if (strpos($ln,'"Título do Projeto";') > 0) { $tp = 'PROJE'; }
				
				if (strpos($ln,'"Tipo da Produção";"Idioma";"Ano";"Título do Artigo";') > 0) { $tp = 'ARTIG'; }
				return($tp);
			}
		
		function recupera_lattes($link)
			{
				$link = troca($link,'.jsp','.do');
				
				echo '-'.$link.'-';
				echo '<BR>Mapeando';
				echo '<UL>';
				if (substr($link,0,5) == 'http:')
					{
						echo '<LI>link ok</LI>';
						/* Valida método 1 */
						$mt = 'http://lattes.cnpq.br/';
						if (substr($mt,0,strlen($mt))==$mt)
							{
								$this->recupera_lattes_metodo_1($link);
							}
					}
				
				/* Metodo 1 */
				echo '</UL>';
			}
	function salva_foto($link,$destino='')
		{
			$sr = $this->read_link_fopen($link);
			echo '-->'.strlen($sr);
		}
	function recupera_link_foto($cnt)
		{
			$sf = 'http://servicosweb.cnpq.br/wspessoa/servletrecuperafoto';
			$pos = strpos($cnt,$sf);
			if ($pos > 0)
				{
					$link_foto = substr($cnt,$pos,200);
					$link_foto = substr($link_foto,0,strpos($link_foto,'"'));
					echo '<LI>Salvando foto '.($this->salva_foto($link_foto,''));
				} else {
					echo 'Erro no link';
				}
			return($link_foto);
		}
	function recupera_lattes_metodo_1($link)
		{
			echo '<LI>Metodo 1 - lendo com CURL '.$link.'</Li>';
			$cnt = $this->read_link_curl($link);
			/* recupera novo link */
			$sf = '<p>The document has moved <a href="';
			$link = substr($cnt,strpos($cnt,$sf)+strlen($sf),200);
			$link = substr($link,0,strpos($link,'"'));
			echo '<LI>Metodo 1 - novo link '.$link.'</Li>';
			echo '<LI>Metodo 1 - lendo com fopen '.$link.'</Li>';
			$cnt = $this->read_link_fopen($link);
			/* Curriculo */
			
			/* Link da foto */
			$foto = $this->recupera_link_foto($cnt);
			echo '<LI>Link da foto '.$foto.'</LI>';
			exit;
		}
	function read_link_curl($url)
		{
			$ch = curl_init();
			curl_setopt ($ch, CURLOPT_URL, $url);
			curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 5);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
			$contents = curl_exec($ch);
			if (curl_errno($ch)) {
  				echo curl_error($ch);
	 				echo "\n<br />";
  				$contents = '';
			} else {
	 				curl_close($ch);
			}
			if (!is_string($contents) || !strlen($contents)) {
				echo "Failed to get contents.";
				$contents = '';
			}
			if (strpos($contents,'encoding="UTF-8"') > 0)
				{
					$contents = troca($contents,'encoding="UTF-8"','encoding="ISO-8859-1"');
					$contents = utf8_decode($contents);
				}
			return($contents);				
		}		
		
	function read_link_fopen($url)
		{
			$flt = fopen($url,'r') or die("erro de link");
			$contents = '';
			while (!(feof($flt)))
				{
					$contents .= fread($flt,1024);
				}
			fclose($flt);
			return($contents);				
		}		


		function grafico_mostra($mx=array(),$div='div00',$size=300,$titulo='',$xarc='',$yarc='')
			{
				$sx = '
    				<script type="text/javascript" src="https://www.google.com/jsapi"></script>
    				';
				$sx .= '
				<script type="text/javascript">
      				google.load("visualization", "1", {packages:["corechart"]});
      				google.setOnLoadCallback(drawChart);
      				function drawChart() {
        				var data = google.visualization.arrayToDataTable([
          				[\'Ano\', \'Quant.\'],
          			';
					for ($r=0;$r < count($mx)-1; $r++)
						{
							if ($r > 0) { $sx .= ', '.chr(13); }
							$sx .= "['".$mx[$r][0]."' , ".round($mx[$r][1])."]";
						}
          		$sx .= '
          				]);

        			var options = {		
          				title: \''.$titulo.'\',
          				hAxis: {title: \'Ano\', titleTextStyle: {color: \'red\'}}
        				};

        			var chart = new google.visualization.ColumnChart(document.getElementById(\''.$div.'\'));
        			chart.draw(data, options);
      				}
    			</script>
    			<div id="'.$div.'" style="width: '.$size.'px; height: '.round($size*.65).'px;"></div>
    			';
				return($sx);
			}		
		
		function grafico_qualis($mx=array(),$div='div00',$size=300)
			{
				$qual = array('A1','A2','B1','B2','B3','B4','B5','C');
				$sx = '
    				<script type="text/javascript" src="https://www.google.com/jsapi"></script>
    				';
				$sx .= '
				<script type="text/javascript">
      				google.load("visualization", "1", {packages:["corechart"]});
      				google.setOnLoadCallback(drawChart);
      				function drawChart() {
        				var data = google.visualization.arrayToDataTable([
          				[\'Ano\', \'Qualis\'],
          			';
					for ($r=0;$r < count($mx)-1; $r++)
						{
							if ($r > 0) { $sx .= ', '.chr(13); }
							$sx .= "['".$qual[$r]."' , ".round($mx[$r])."]";
						}
          		$sx .= '
          				]);

        			var options = {		
          				title: \'Artigos por qualis\',
          				hAxis: {title: \'Ano\', titleTextStyle: {color: \'red\'}}
        				};

        			var chart = new google.visualization.ColumnChart(document.getElementById(\''.$div.'\'));
        			chart.draw(data, options);
      				}
    			</script>
    			<div id="'.$div.'" style="width: '.$size.'px; height: '.round($size*.65).'px;"></div>
    			';
				return($sx);
			}
		
		function indice_h($docente)
			{
				$tott = 57;
				$totc = 537;
				$idh = '15';
				if ($docente != '88888951')
					{
						$tott = 0;
						$totc = 0;
						$idh = '-';
					}
				$datac = '01/07/2012';
				$sx .= '<table class="tabela00">';
				$sx .= '<TR><TD align="center" width="40">';
				$sx .= '<font class="link">Fator H</font><BR>';
				$sx .= '<font class="lt4">';
				$sx .= $idh;
				$sx .= '<TD>';
				$sx .= 'Total trabalhos: '.($tott);
				$sx .= '<BR>Total citações: '.($totc);
				$sx .= '<BR>Atualizado: '.($datac);
				$sx .= '</table>';
				return($sx);						
			}
		
		function qualis($docente,$area)
			{
				
			}
		
		function jcr($docente)
			{
				$sql = "select 1, la_jcr as jcr from lattes_artigos 
						where la_professor = '$docente'
						and la_tipo = 'A' and la_jcr <> ''
				";
				$rlt = db_query($sql);
				$tot = 0;
				$jcr = 0;
				$min = 1000;
				$max = 0;
				while ($line = db_read($rlt))
					{
						$tot++;
						
						$jcra = round(troca('0'.$line['jcr'],',','.')*1000)/1000;
						if ($jcra < $min) { $min = $jcra; }
						if ($jcra > $max) { $max = $jcra; }
						$jcr = $jcr + $jcra;
					}
				if ($min == 1000) { $min = 0; }
				$sx = '';
				if ($tot > 0)
					{
					$jcr = $jcr / $tot;
					$sx = '<TABLE border=0 width="100%" class="tabela00">';
					$sx .= '<TR align="center">';
					$sx .= '<TD width="15%" ><font class="link">JCR (médio)</font><BR>';
					$sx .= '<font class="lt4">'.number_format($jcr,3);
					$sx .= '<TD width="15%" align="left"><NOBR>
							Artigos com JCR: ';
					$sx .= ''.number_format($tot,0);
					$sx .= '<NOBR><BR>JCR (mín): ';
					$sx .= ''.number_format($min,3);					
					$sx .= '<BR>JCR (máx):';
					$sx .= ''.number_format($max,3);	
					$sx .= '</table>';
					}
				return($sx);
			}
		
		function recupera_producao($docente,$tipo='A',$dd1=1900,$dd2=2099)
			{
				$sql = "select count(*) as total, la_ano, la_tipo from lattes_artigos 
						where la_professor = '$docente'
						and la_tipo = '$tipo'
						group by la_ano, la_tipo
						order by la_ano, la_tipo
				";
				$ar = array();
				$rlt = db_query($sql);
				while ($line = db_read($rlt))
					{
						array_push($ar,array($line['la_ano'],$line['total']));
					}
				return($ar);
			}
		
		function row()
			{
				global $cdf,$cdm,$masc;
				$cdf = array('id_la','la_professor','la_titulo','la_tipo','la_ano');
				$cdm = array('cod',msg('nome'),msg('titulo'),msg('tipo'),msg('ano'));
				$masc = array('','','','','','','');
				return(1);				
			}		
		
		function http_lattes_site($site)
			{
				$rlt = fopen($site,'r');
				$sx = '';
				while (!(eof($rlt)))
					{
						echo '.';
						$s = fread($rlt,1024);
						echo $s;
					}
				fclose($rlt);
				echo 'FUN';
				return($sx);
			}
		function formula_equivalencia()
			{
				$ar = array('A1','A2','B1','B2','B3','B4','B5','C','NC');
				$eq = $this->equivalencia();
				$sx = 'Equiv. = ';
				for ($r=0;$r < count($eq);$r++)
					{
						if ($r > 0) { $sx .= ' + ';}
						$sx .= $ar[$r].'x'.$eq[$r]; 
					}
				return($sx);
			}
			
		function equivalencia()
			{
				return(array(1,0.85,0.7,0.5,0.2,0.1,0.05,0));
			}

		function resumo_best_productions($programa,$areas,$anoi=19000101,$anof=20990101)
			{
				$sx = '<table width="94%" class="lt0">';
				$sx .= '<TR><TH>ISSN<TH>Journal<TH>Estr.<TH>FI';
				$sql = "select *						
							from lattes_artigos
							inner join programa_pos_docentes on pdce_docente = la_professor
							inner join programa_pos on pdce_programa = pos_codigo
							inner join pibic_professor on la_professor = pp_cracha
							left join lattes_journals on j_codigo = la_periodico
							left join qualis_estrato on (j_issn = eq_issn) and (pos_avaliacao_1 = eq_area)
							
							where (((eq_estrato like 'A%')) and (la_pag <> '1'))
							and (la_ano >= '$anoi' and la_ano <= '$anof')
							and pdce_programa = '$programa'
							and la_tipo = 'A'
							order by la_ano desc, eq_estrato, la_jcr desc, la_periodico, la_vol, la_pag";
					
					$rlt = db_query($sql);
					$at = array(0, 0,0, 0,0,0,0,0, 0);
					$ar = array($at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at);
					$xissn = 'x';
					$xvol = 'x';
					$xpag = 'x';
					$xano = 'x';
					
					$ano = "X";
					while ($line = db_read($rlt))
					{
						$ref = $this->abnt_referencia($line);

						$ok = 1;
						$issn = trim($line['j_issn']);
						$vol = trim($line['la_vol']);
						$pag = round(sonumero(trim($line['la_pag'])));
						$ano = trim($line['la_ano']);
						
						if ($ano != $xano)
							{
								$sx .= '<TR><TD class="lt3" colspan=5>'.$ano;
								$xano = $ano;
							}
						
						if (($issn == $xissn) and ($vol == $xvol)
							and ($ano == $xano) and ($xpag == $pag))
							{ $ok = 0; }
						
						$xissn = trim($line['j_issn']);
						$xvol = trim($line['la_vol']);
						$xpag = round(sonumero(trim($line['la_pag'])));
						$xano = trim($line['la_ano']);
													
							
						$estrato = trim($line['eq_estrato']);
						$id = 8;
						
						$cor = '';
						if ($ok==0) { $cor = '<font color="red">'; }
						
						if ($estrato == 'A1') { $id = 0; }
						if ($estrato == 'A2') { $id = 1; }	
						
						/** Elimina duplicados */
						if ($ok==1)
							{					
								if ((round($xano) >= 2007) and (strlen($estrato) > 0))
								{
								$fi = $line['la_jcr'];
								$sx .= '<TR >';
								$sx .= '<TD><nobr>'.$cor.$line['j_issn'];
								$sx .= '<TD>'.$ref;
								$sx .= '<TD align="center">'.$line['eq_estrato'];
								$sx .= '<TD align="right">'.$line['la_jcr'];
								}
							}
					}
					$sx .= '<TR><TD colspan=7><center>';
				$sx .= '</table>';
				return($sa.$sx);				
	
			}	
					
		function abnt_referencia($line)
			{
				$ref = trim($line['ja_autores_nomes']);
				$jor = trim($line['j_name']);
				$ano = trim($line['la_ano']);
				$tit = trim($line['la_titulo']);
				$vol = trim($line['la_vol']);
				$pag = 'p. '.trim($line['la_pag']);
				if (strlen($vol) > 0) { $vol = 'v. '.$vol; }
				$referencia = $ref.'. '.$tit.'. <B>'.$jor.'</B>, '.$vol.' '.$pag.', '.$ano;
				return($referencia);
			}
		function resumo_qualis_ss_geral($tp=0,$areas='',$anoi=1900,$anof=2099)
			{
				$anos = 40;
				$sx = '<table width="94%" class="tabela00">';
				$sx .= '<TR><TH>ISSN<TH>Journal<TH><TH>Estr.<TH>Ano<TH>Vol.<TH>Pag.<TH>Autor';
				$sql = "select pp_nome, pdce_programa, j_name, eq_estrato, eq_ano, j_issn, la_ano, 
							la_periodico, la_vol, la_pag, la_professor 
							
							from lattes_artigos
							inner join programa_pos_docentes on pdce_docente = la_professor and pdce_ativo = 1
							inner join programa_pos on pdce_programa = pos_codigo
							inner join pibic_professor on la_professor = pp_cracha
							left join lattes_journals on j_codigo = la_periodico
							left join qualis_estrato on (j_issn = eq_issn) and (pos_avaliacao_1 = eq_area)
												
							group by pp_nome, pdce_programa, j_name, eq_estrato, eq_ano, j_issn, la_ano, la_periodico, la_vol, la_pag, la_professor
							order by la_ano desc, eq_estrato, la_periodico, la_vol, la_pag";
					
					$rlt = db_query($sql);
					$at = array(0, 0,0, 0,0,0,0,0, 0);
					$ar = array();
					for ($y=0;$y < 100;$y++)
						{
							array_push($ar,$at);
						}
					$xissn = 'x';
					$xvol = 'x';
					$xpag = 'x';
					$xano = 'x';
					
					while ($line = db_read($rlt))
					{
						$ano = round($line['la_ano']);
						if (($ano >= $anoi) and ($ano <= $anof))
						{
						$ref = $line['la_autores'];
						$ok = 1;
						$issn = trim($line['j_issn']);
						$vol = trim($line['la_vol']);
						$pag = round(sonumero(trim($line['la_pag'])));
						$ano = trim($line['la_ano']);
						
						if (($issn == $xissn) and ($vol == $xvol)
							and ($ano == $xano) and ($xpag == $pag))
							{ $ok = 0; }
						
						$xissn = trim($line['j_issn']);
						$xvol = trim($line['la_vol']);
						$xpag = round(sonumero(trim($line['la_pag'])));
						$xano = trim($line['la_ano']);
													
							
						$estrato = trim($line['eq_estrato']);
						$id = 8;
						
						$cor = '';
						if ($ok==0) { $cor = '<font color="red">'; }
						
						if ($estrato == 'A1') { $id = 0; }
						if ($estrato == 'A2') { $id = 1; }
						
						if ($estrato == 'B1') { $id = 2; }
						if ($estrato == 'B2') { $id = 3; }
						if ($estrato == 'B3') { $id = 4; }
						if ($estrato == 'B4') { $id = 5; }
						if ($estrato == 'B5') { $id = 6; }
						
						if ($estrato == 'C') { $id = 7; }
						
						
						/** Elimina duplicados */
						if ($ok==1)
							{
							$ano = round($line['la_ano']);
							$ano = ($ano-(date("Y"))+$anos);
							if ($ano >=0)
								{ $ar[$ano][$id] = $ar[$ano][$id] + 1; }
							}
						
						if ((round($xano) >= 1970) and (strlen($estrato) > 0))
						{
						$fi = $line['la_jcr'];
						$sx .= '<TR >';
						$sx .= '<TD class="tabela01"><NOBR>'.$cor.$line['j_issn'];
						$sx .= '<TD class="tabela01">'.$cor.$line['j_name'];
						$sx .= '<B>'.$line['j_titulo'];
						$sx .= '<TD class="tabela01">'.$cor.$line['pp_nome'];
						//$sx .= '<TD>'.$line['la_titulo'];
						$sx .= '<TD class="tabela01">'.$cor.$line['qa_descricao'];
						$sx .= '<TD class="tabela01">'.$cor.$line['eq_estrato'];
						$sx .= '<TD class="tabela01">'.$cor.$line['la_ano'];
						$sx .= '<TD class="tabela01">v. '.$line['la_vol'];
						$sx .= '<TD class="tabela01"><nobr>p. '.$line['la_pag'];
						$sx .= '<TD class="tabela01">'.$cor.$line['la_professor'];
						$sx .= '<TD class="tabela01">'.$cor.$line['pdce_programa'];
						$ln = $line;
						}
						}
					}
					$sx .= '<TR><TD colspan=7><center>';
					//print_r($ln);
					
					$sa = '<table width="100%" class="tabela00">';
					$sa .= '<TR align="center"><TH>ano<TH>A1<TH>A2<TH>B1<TH>B2<TH>B3<TH>B4<TH>B5<TH>C<TH>s.q<TH>Total';
					for ($rq=($anos + 1);$rq >= 0;$rq--)
						{
							if (count($ar[$rq]) > 0)
							{
								$tt = 0;
								$tte= 0;
								$multe = $this->equivalencia();
								$sc = '<TR><TD align="center" class="tabela01">'.(date('Y')+$rq-$anos);
								for ($y=0;$y < count($ar[$rq]);$y++)
								{
									$sc .= '<TD width="8%" align="center" class="tabela01">';
									$mt = $ar[$rq][$y];
									$tt = $tt + $mt;
									$tte = $tte + $multe[$y] * $mt;
									if ($mt == 0) { $mt = '&nbsp;'; }
									$sc .= $mt;
								}
								
								$sc .= '<TD width="8%" align="center" class="tabela01"><B>'.$tt;
								$sc .= '<TD width="8%" align="center" class="tabela01"><B>'.$tte;
								
								if ($tt > 0)
									{ $sa .= $sc; }
							}
						}
					$sa .= '</table>';
				$sx .= '</table>';
				$this->matrix = $ar;
				if ($tp==0) { return($sa.$sx); }
				if ($tp==1) { return($sa); }				
			}

		function resumo_qualis_ss_tipo_geral($tp=0,$areas='',$anoi=1900,$anof=2099,$tipo='')
			{
				$anos = 40;
				$sx = '<table width="94%" class="tabela00">';
				$sx .= '<TR><TH>Referencia';
				
				$sql = "select *
							
							from lattes_artigos
							inner join programa_pos_docentes on pdce_docente = la_professor
							inner join programa_pos on pdce_programa = pos_codigo
							inner join pibic_professor on la_professor = pp_cracha
							where la_tipo = '$tipo'							
							order by la_ano desc, la_titulo ";
					
					$rlt = db_query($sql);
					$at = 0;
					$ar = array();
					for ($y=0;$y < 100;$y++)
						{
							array_push($ar,$at);
						}
					$xref = 'x';
					
					while ($line = db_read($rlt))
					{
						$ano = round($line['la_ano']);
						if (($ano >= $anoi) and ($ano <= $anof))
							{
							$ref = $line['la_titulo'];
							$idioma = trim($line['la_idioma']);
							$ano = trim($line['la_ano']);
							$autores = trim($line['ja_autores_nomes']);
							$pag = round(sonumero(trim($line['la_pag'])));
							
							if ($ref != $xref)
								{
								$sx .= '<TR>';
								$sx .= '<TD>';
								$sx .= $autores.'. ';
								$sx .= '<B>'.$ref;
								$sx .= ', '.$ano;
							
								$xano = ($ano-(date("Y"))+$anos);
								$ar[$xano] = $ar[$xano] + 1;
								$xref = $ref;
								} 
							}
						}
	
					$sx .= '<TR><TD colspan=7><center>';
	
				$sx .= '</table>';
				$this->matrix = $ar;
				
				$st .= '<table class="tabela00" width="400">';
				$st .= '<TR><TH>Ano<TH>Quat.';
				$tot = 0;
				for ($r=0;$r < count($ar);$r++)
					{
						if ($ar[$r] > 0)
							{
							
							$st .= '<TR>';
							$st .= '<TD class="tabela01" align="center">';
							$st .= date("Y")+$r-$anos;
							$st .= '<TD class="tabela01" align="center">';
							$st .= $ar[$r];
							$tot = $tot + $ar[$r];
							}
							
					}
					$st .= '<TR><TD align="right"><B>Total';
					$st .= '<TD align="center" class="tabela01" ><B>'.$tot;
					$st .= '</table>';
					
				$this->st = $st;				

				if ($tp==0) { return($sa.$sx); }
				if ($tp==1) { return($sa); }				
			}


		function grafico_publicacao($programa='',$areas='',$anoi=1970,$anof=2999)
			{
				if ($anof < 1900) { $anof = date("Y"); }
				$sql = "select la_ano, count(*) as total from
						( select la_ano from lattes_artigos 
							inner join lattes_journals on j_codigo = la_periodico
							group by la_ano, la_periodico, la_vol, la_pag
						) as tabela01
						
						group by la_ano
						order by la_ano
				";
				$rlt = db_query($sql);
				$sa = '';
				$st = '<table class="tabela00" width="100">';
				$st .= '<TR><TH>Ano<TH>Total';
				while ($line = db_read($rlt))
					{
						$ano = round($line['la_ano']);
						if (($ano >= $anoi) and ($ano <= $anof))
							{
							$st .= '<TR><TD class="tabela01" align="center">'.$ano;
							$st .= '<TD class="tabela01" align="center">'.$line['total'];
							if (strlen($sa) > 0) { $sa .= ', '.chr(13); }
							$sa .= '["'.$line['la_ano'].'", '.$line['total'].']';
							}
					}
				$st .= '</table>';
				
				$sx = '
				    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    					<script type="text/javascript">
      						google.load("visualization", "1", {packages:["corechart"]});
      						google.setOnLoadCallback(drawChart);
      						function drawChart() {
        					var data = google.visualization.arrayToDataTable([
          					["Ano", "Artigos"],
          					'.$sa.'
        					]);
			        var options = {
          				title: \'Produção Científica na Instituição\'
        				};
		
			        var chart = new google.visualization.ColumnChart(document.getElementById(\'chart_div\'));
        				chart.draw(data, options);
      				}
    			</script>
    			<table>
    			<TR vlign="top"><TD>
			    <div id="chart_div" style="width: 900px; height: 500px;"></div>
			    <TD>'.$st.'</table>
				';
				return($sx);
			}
		
		function resumo_qualis_ss($programa,$areas,$anoi=1990,$anof=2999,$tp=0,$producao='A')
			{
				$ano = date("Y");
				$sx = '<table width="100%" class="tabela00">';
				$sx .= '<TR><TH>ISSN<TH>Journal<TH><TH>Estr.<TH>Ano<TH>Vol.<TH>Pag.<TH>Autor';
				for ($r=0;$r < count($areas);$r++)
				{
					$area = $areas[$r];
					$wh_areas = " and (eq_area = '".$area."' ) ";
								
					$sql = "select j_name, eq_estrato, eq_ano, j_issn, la_ano, 
							la_periodico, la_vol, la_pag, la_professor, la_tipo,
							pp_nome, la_titulo, la_nul
							
							from lattes_artigos
							inner join programa_pos_docentes on pdce_docente = la_professor
							inner join pibic_professor on pdce_docente = pp_cracha
							left join lattes_journals on j_codigo = la_periodico
							left join qualis_estrato on (j_issn = eq_issn) $wh_areas and (eq_ano = '".$ano."')";
					$wh = "	where pdce_programa = '$programa'
							and (la_ano >= '$anoi' and la_ano <= '$anof')
							and (la_tipo = '".$producao."') "; 
					$sql .= $wh . "
							group by j_name, eq_estrato, eq_ano, j_issn, la_ano, la_periodico, la_vol, la_pag, la_professor, la_tipo, pp_nome, la_titulo, la_nul
							order by la_ano desc, eq_estrato, la_periodico, la_vol, la_pag, la_professor ";
					
					$rlt = db_query($sql);
					
					$at = array(0,0,0,0,0,0,0,0,0);
					$ar = array($at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at);
					$xissn = 'x';
					$xvol = 'x';
					$xpag = 'x';
					$xano = 'x';
					
					$xano = 0;
					$xest = 'x';
					
					while ($line = db_read($rlt))
					{
						$ok = 1;
						$issn = trim($line['j_issn']);
						$vol = trim($line['la_vol']);
						$pag = round(sonumero(trim($line['la_pag'])));
						$ano = trim($line['la_ano']);
						
						if (($issn == $xissn) and ($vol == $xvol)
							and ($ano == $xano) and ($xpag == $pag))
							{ $ok = 0; }
						
						$xissn = trim($line['j_issn']);
						$xvol = trim($line['la_vol']);
						$xpag = round(sonumero(trim($line['la_pag'])));
						$xano = trim($line['la_ano']);
													
							
						$estrato = trim($line['eq_estrato']);
						$id = 8;
						
						$cor = '';
						if ($ok==0) { $cor = '<font color="red">'; }
						
						if ($estrato == 'A1') { $id = 0; }
						if ($estrato == 'A2') { $id = 1; }
						
						if ($estrato == 'B1') { $id = 2; }
						if ($estrato == 'B2') { $id = 3; }
						if ($estrato == 'B3') { $id = 4; }
						if ($estrato == 'B4') { $id = 5; }
						if ($estrato == 'B5') { $id = 6; }
						
						if ($estrato == 'C') { $id = 7; }
						
						
						/** Elimina duplicados */
						if ($ok==1)
							{
							$ano = round($line['la_ano']);
							$ano = ($ano-(date("Y"))+35);
							if ($ano >=0)
								{ $ar[$ano][$id] = $ar[$ano][$id] + 1; }
							}
						
						//if (substr($line['eq_estrato'],0,1)=='A')
						{						
						if ($xano != $line['la_ano'])
							{ $sx .= '<TR><TD class="lt3" colspan=2><B>'.$line['la_ano']; $xano = $line['la_ano'].'</B>'; }

						if ($xest != $line['eq_estrato'])
							{ $sx .= '<TR><TD class="lt2" colspan=2><B>'.$line['la_ano'].' - Estrato '.$line['eq_estrato'].'</B>'; $xest = $line['eq_estrato']; }

						$fi = $line['la_jcr'];
						$sx .= '<TR valign="top" >';
						$sx .= '<TD>'.$cor.$line['j_issn'];
						$sx .= '<TD><B>'.$cor.$line['j_name'].'</B>';
						$sx .= '<BR><I>'.$line['la_titulo'].'</I>';
						$sx .= '<TD>'.$cor.$line['qa_descricao'];
						$sx .= '<TD>'.$cor.$line['eq_estrato'];
						$sx .= '<TD><nobr>'.$line['la_vol']
									.'('.$line['la_nul'].')'
									.', '.$line['la_ano'];
						$sx .= '<TD><NOBR>p. '.$line['la_pag'];
						$sx .= '<TR><TD><TD colspan=10>';
						$sx .= $cor.$line['pp_nome'].' ('.$line['la_professor'].')';
						$sx .= '<TD>'.$cor.$line['id_la'];
						}
					}
					$sx .= '<TR><TD colspan=7><center>';
					$sa = '<table width="100%" class="tabela00">';
					$sa .= '<TR align="center"><TH>ano<TH>A1<TH>A2<TH>B1<TH>B2<TH>B3<TH>B4<TH>B5<TH>C<TH>s.q<TH>Total<TH>Equiv.';
					$at = array(0,0,0,0,0,0,0,0,0);
					for ($rq=35;$rq >= 0;$rq--)
						{
							if (count($ar[$rq]) > 0)
							{
								$tt = 0;
								$tte= 0;
								$multe = $this->equivalencia();
																
								$sq = '<TR><TD align="center" class="tabela01">'.(date('Y')+$rq-35);
								for ($y=0;$y < count($ar[$rq]);$y++)
								{
									$sq .= '<TD width="8%" align="center" class="tabela01">';
									$mt = $ar[$rq][$y];
									$at[$y] = $at[$y] + $mt;
									  
									$tt = $tt + $mt;
									if ($mt == 0) { $mt = '&nbsp;'; }
									$sq .= $mt;
									$tte = $tte + $multe[$y] * $mt; 
								}
								$sq .= '<TD width="8%" align="center" class="tabela01"><B>'.$tt;
								$sq .= '<TD width="8%" align="center" class="tabela01"><B>'.number_format($tte,2,',','.');
								if ($tt > 0) { $sa .= $sq; }
							}
						}
					if ($tp==3)
						{
							$sa .= '<TR><TD><B>Total</B>';
							for ($y=0;$y < 11;$y++)
								{
								$sa .= '<TD class="tabela01" align="center">';
								$sa .= $at[$y];
								}
						}
					$sa .= '</table>';
				}
				$sx .= '</table>';
				
				
				/* Grafico Pizza */
				$grp = "['Estrato Qualis','Artigos'] ";
				$sql = "select eq_estrato, count(*) as total
							from lattes_artigos
							inner join programa_pos_docentes on pdce_docente = la_professor
							left join lattes_journals on j_codigo = la_periodico
							left join qualis_estrato on (j_issn = eq_issn) $wh_areas ";
				$sql .= $wh. " group by eq_estrato ";
				$rlt = db_query($sql);
				$grp = "['Estrato','Total']";
				while ($line = db_read($rlt))
					{
						$grp .= ', '.chr(13).chr(10);
						$grp .= "['".trim($line['eq_estrato'])."', ";
						$grp .= $line['total']."] ";
					}				
				
				if ($tp==0) { return($sa.$sx); }		
				if ($tp==1) { return($sa); }
				if ($tp==3) { return($sa); }
			}

		function resumo_qualis_discente_ss($programa,$areas,$anoi=1990,$anof=2999)
			{
				$sx = '<table width="94%" class="lt0">';
				$sx .= '<TR><TH>ISSN<TH>Journal<TH><TH>Estr.<TH>Ano<TH>Vol.<TH>Pag.<TH>Autor';
				for ($r=0;$r < count($areas);$r++)
				{
					$area = $areas[$r];
					$wh_areas = " and (eq_area = '".$area."' ) ";
								
					$sql = "select j_name, eq_estrato, eq_ano, j_issn, la_ano, 
							la_periodico, la_vol, la_pag, la_professor, la_tipo,
							pa_nome
							
							from lattes_artigos
							inner join docente_orientacao on od_aluno = la_professor
							inner join pibic_aluno on od_aluno = pa_cracha
							left join lattes_journals on j_codigo = la_periodico
							left join qualis_estrato on (j_issn = eq_issn) $wh_areas ";
					$wh = "	where od_programa = '$programa'
							and (la_ano >= '$anoi' and la_ano <= '$anof')
							and la_tipo = 'A' "; 
					$sql .= $wh . "
							group by j_name, eq_estrato, eq_ano, j_issn, la_ano, la_periodico, la_vol, la_pag, la_professor, la_tipo, pa_nome
							order by la_ano desc, eq_estrato, la_periodico, la_professor, la_vol, la_pag";
					
					$rlt = db_query($sql);
					$at = array(0,0,0,0,0,0,0,0,0);
					$ar = array($at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at,$at);
					$xissn = 'x';
					$xvol = 'x';
					$xpag = 'x';
					$xano = 'x';
					
					$xano = 0;
					$xest = 'x';
					
					while ($line = db_read($rlt))
					{
						$ok = 1;
						$issn = trim($line['j_issn']);
						$vol = trim($line['la_vol']);
						$pag = round(sonumero(trim($line['la_pag'])));
						$ano = trim($line['la_ano']);
						
						if (($issn == $xissn) and ($vol == $xvol)
							and ($ano == $xano) and ($xpag == $pag))
							{ $ok = 0; }
						
						$xissn = trim($line['j_issn']);
						$xvol = trim($line['la_vol']);
						$xpag = round(sonumero(trim($line['la_pag'])));
						$xano = trim($line['la_ano']);
													
							
						$estrato = trim($line['eq_estrato']);
						$id = 8;
						
						$cor = '';
						if ($ok==0) { $cor = '<font color="red">'; }
						
						if ($estrato == 'A1') { $id = 0; }
						if ($estrato == 'A2') { $id = 1; }
						
						if ($estrato == 'B1') { $id = 2; }
						if ($estrato == 'B2') { $id = 3; }
						if ($estrato == 'B3') { $id = 4; }
						if ($estrato == 'B4') { $id = 5; }
						if ($estrato == 'B5') { $id = 6; }
						
						if ($estrato == 'C') { $id = 7; }
						
						
						/** Elimina duplicados */
						if ($ok==1)
							{
							$ano = round($line['la_ano']);
							$ano = ($ano-(date("Y"))+35);
							if ($ano >=0)
								{ $ar[$ano][$id] = $ar[$ano][$id] + 1; }
							}
						
						//if (substr($line['eq_estrato'],0,1)=='A')
						{						
						if ($xano != $line['la_ano'])
							{ $sx .= '<TR><TD class="lt3" colspan=2>'.$line['la_ano']; $xano = $line['la_ano']; }

						if ($xest != $line['eq_estrato'])
							{ $sx .= '<TR><TD class="lt2" colspan=2>'.$line['la_ano'].' - Estrato '.$line['eq_estrato']; $xest = $line['eq_estrato']; }

						$fi = $line['la_jcr'];
						$sx .= '<TR >';
						$sx .= '<TD>'.$cor.$line['j_issn'];
						$sx .= '<TD>'.$cor.$line['j_name'];
						//$sx .= '<TD>'.$line['la_titulo'];
						$sx .= '<TD>'.$cor.$line['qa_descricao'];
						$sx .= '<TD>'.$cor.$line['eq_estrato'];
						$sx .= '<TD>'.$cor.$line['la_ano'];
						$sx .= '<TD>v. '.$line['la_vol'];
						$sx .= '<TD>p. '.$line['la_pag'];
						$sx .= '<TD>'.$cor.$line['pa_nome'].' ('.$line['la_professor'].')';
						$sx .= '<TD>'.$cor.$line['la_tipo'];
						}
					}
					$sx .= '<TR><TD colspan=7><center>';
					$sa = '<table width="740" class="lt1" cellpadding=0 cellspacing=2 border=1 >';
					$sa .= '<TR align="center"><TH>ano<TH>A1<TH>A2<TH>B1<TH>B2<TH>B3<TH>B4<TH>B5<TH>C<TH>s.q<TH>Total<TH>Equiv.';
					$at = array(0,0,0,0,0,0,0,0,0);
					for ($rq=35;$rq >= 0;$rq--)
						{
							if (count($ar[$rq]) > 0)
							{
								$tt = 0;
								$tte= 0;
								$multe = $this->equivalencia();
																
								$sq = '<TR><TD align="center">'.(date('Y')+$rq-35);
								for ($y=0;$y < count($ar[$rq]);$y++)
								{
									$sq .= '<TD width="8%" align="center">';
									$mt = $ar[$rq][$y];
									$at[$y] = $at[$y] + $mt;
									  
									$tt = $tt + $mt;
									if ($mt == 0) { $mt = '&nbsp;'; }
									$sq .= $mt;
									$tte = $tte + $multe[$y] * $mt; 
								}
								$sq .= '<TD width="8%" align="center"><B>'.$tt;
								$sq .= '<TD width="8%" align="center"><B>'.number_format($tte,2,',','.');
								if ($tt > 0) { $sa .= $sq; }
							}
						}
					$sa .= '</table>';
				}
				$sx .= '</table>';		

				return($sa.$sx);				
			}



		function resumo_qualis($professor,$areas=array(),$tipo=1)
			{
				if (count($areas)==0)
					{
					$areas = array();
					$sql = "select pos_avaliacao_1 from programa_pos_docentes
							left join programa_pos on pdce_programa = pos_codigo 
							where pdce_docente = '".$professor."' and pdce_ativo = 1
							group by pos_avaliacao_1 ";
					$rlt = db_query($sql);
					while ($line = db_read($rlt))
						{
							array_push($areas,trim($line['pos_avaliacao_1']));
						}
					}
				if ($tipo == 1) { $sx = '<table width="200" class="lt0">'; }
				if ($tipo != 1) { $sx = '<table width="600" class="tabela01">'; }
				for ($r=0;$r < count($areas);$r++)
				{
					$area = $areas[$r];
					$wh_areas = " and (eq_area = '".$area."' ) ";
					$sql = "select * from lattes_artigos ";
					$sql .= " left join lattes_journals on j_codigo = la_periodico ";
					$sql .= " left join qualis_estrato on (j_issn = eq_issn) ".$wh_areas;
					$sql .= " left join qualis_area on qa_codigo = eq_area ";
					$sql .= " where la_professor = '$professor' and la_tipo = 'A' ";
					$sql .= " order by la_ano desc ";
					$rlt = db_query($sql);
					$at = array(0,0,0,0,0,0,0,0,0);
					$max = 1;
							
					while ($line = db_read($rlt))
					{
						$ano = round($line['la_ano']);
						if ($ano >= 2006)
						{
						$estrato = trim($line['eq_estrato']);
						$id = 8;
						if ($estrato == 'A1') { $id = 0; }
						if ($estrato == 'A2') { $id = 1; }
						
						if ($estrato == 'B1') { $id = 2; }
						if ($estrato == 'B2') { $id = 3; }
						if ($estrato == 'B3') { $id = 4; }
						if ($estrato == 'B4') { $id = 5; }
						if ($estrato == 'B5') { $id = 6; }
						
						if ($estrato == 'C') { $id = 7; }
						
						$at[$id] = $at[$id] + 1;
						if ($at[$id] > $max) { $max = $at[$id]; } 			
						}
					}
					$sx .= '<TR align="center"><Td colspan=8 align="center" rowspan=3>
								<font class="link">Estratos<BR>Qualis</font>';
					$sx .= '<TR valign="bottom">';
					$tot = 0;
					for ($rq=0;$rq < count($at);$rq++)
						{
							$size = $at[$rq]*2;
							$sx .= '<TD width="8%" align="center">';
							$sx .= $at[$rq].'<BR>';
							$tot = $tot + $at[$rq];
							if ($tipo==1)
								{
								$sx .= '<img src="'.http.'img/gr_0'.$rq.'.png" width="20" height="'.$size.'">';
								}
						}
					if ($tipo != 1)
						{
							$sx .= '<TD align="center" class="tabela01 lt4" width="8%">';
							if ($tot == 0)
								{									
									$sx .= '<font color="red">';
								} else {
									if ($tot > 20)
										{ $sx .= '<font color="blue">'; }
									else
										{ $sx .= '<font color="black">'; }
								}
								$sx .= $tot.'<BR>';
								$sx .= '</font>';
								
						}
				}
				$sx .= '<TR align="center"><Td>A1<Td>A2<Td>B1<Td>B2<Td>B3<Td>B4<Td>B5<Td>C<Td>nc';
				$sx .= '</table>';
				$this->total = $tot;
				return($sx);
				
			}
		
		function mostra_lattes_producao($professor)
			{
				
				$sx .= '<fieldset><legend>Produção do professor</legend>';
				$sql = "select * from lattes_artigos
							inner join programa_pos_docentes on pdce_docente = la_professor and pdce_ativo = 1 
							left join lattes_journals on j_codigo = la_periodico 
							left join programa_pos on pdce_programa = pos_codigo
							left join qualis_estrato on (j_issn = eq_issn) and (pos_avaliacao_1 = eq_area)
							where la_professor = '$professor' order by la_ano desc, id_la desc
						";
				$rlt = db_query($sql);
				
				$sx .= '<div style="text-align: left;">';
				$xano = 0;
				$xid = "X";
				while ($line = db_read($rlt))
					{
						$id = $line['id_la'];
						if ($id != $xid)
						{
						$journal = $line['j_name'];
						if (strpos($journal,'(') > 0)
							{ $journal = substr($journal,0,strpos($journal,'(')); }
						$ano = $line['la_ano'];
						if ($ano != $xano)
							{
								if ($xano > 0) { $sx .= '</UL>'; }
								$sx .= '<B>'.$ano.'</B>'; 
								$sx .= '<UL>';
								$xano = $ano; 
							}
						$tipo = trim($line['la_tipo']);
						/// ARTIGO CIENTÌFICO
						$sx .= '<TABLE width="100%" border=0><TR valign="top"><TD class="tabela01"><UL>';
												
						if ($tipo == 'A')
						{
							$sx .= '<LI>';
							$sx .= trim($line['ja_autores_nomes']);
							$sx .= ' ';
							$sx .= trim($line['la_titulo']).'.';
							$sx .= ' ';
							$sx .= '<B>'.$journal.'</B>';
							$vol = trim($line['la_vol']);
							$num = trim($line['la_num']);
							$pag = trim($line['la_pag']);
							if (strlen($vol) > 0) { $sx .= ', v.'.$vol; }
							if (strlen($num) > 0) { $sx .= ', n.'.$num; }
							if (strlen($pag) > 0) { $sx .= ', p. '.$pag; }
							$sx .= ', '.trim($line['la_ano']);
							$sx .= '.';
							$jcr = trim($line['la_jcr']);
							if (strlen($jcr) > 0)
								{
									$sx .= '(<font color="red">JCR '.$line['la_jcr'].'</font>)';
								}
							$sx .= '</LI>';
						}
						/// LIVRO
						if ($tipo == 'L')
						{
							$sx .= '<LI>';
							$sx .= trim($line['ja_autores_nomes']);
							$sx .= ' ';
							$sx .= '<B>'.trim($line['la_titulo']).'</B>.';
							$sx .= ' ';
							$sx .= $journal;
							$vol = trim($line['la_vol']);
							$num = trim($line['la_num']);
							$pag = trim($line['la_pag']);
							if (strlen($vol) > 0) { $sx .= ', v.'.$vol; }
							if (strlen($num) > 0) { $sx .= ', n.'.$num; }
							if (strlen($pag) > 0) { $sx .= ', p. '.$pag; }
							$sx .= ', '.trim($line['la_ano']);
							$sx .= '.';
							$sx .= '</LI>';
						}
						if ($tipo == 'O')
						{
							$sx .= '<LI>';
							$sx .= trim($line['ja_autores_nomes']);
							$sx .= ' (org) ';
							$sx .= '<B>'.trim($line['la_titulo']).'</B>.';
							$sx .= ' ';
							$sx .= $journal;
							$vol = trim($line['la_vol']);
							$num = trim($line['la_num']);
							$pag = trim($line['la_pag']);
							if (strlen($vol) > 0) { $sx .= ', v.'.$vol; }
							if (strlen($num) > 0) { $sx .= ', n.'.$num; }
							if (strlen($pag) > 0) { $sx .= ', p. '.$pag; }
							$sx .= ', '.trim($line['la_ano']);
							$sx .= '.';
							$sx .= '</LI>';
						}	
						if ($tipo == 'E')
						{
							$sx .= '<LI>';
							$sx .= trim($line['ja_autores_nomes']);
							$sx .= ''.trim($line['la_titulo']).'.';
							$sx .= ' ';
							$sx .= UPPERCASE($journal);
							$vol = trim($line['la_vol']);
							$num = trim($line['la_num']);
							$pag = trim($line['la_pag']);
							if (strlen($vol) > 0) { $sx .= ', v.'.$vol; }
							if (strlen($num) > 0) { $sx .= ', n.'.$num; }
							if (strlen($pag) > 0) { $sx .= ', p. '.$pag; }
							$sx .= ', '.trim($line['la_ano']);
							$sx .= '.';
							$sx .= '</LI>';
						}												
						if ($tipo == 'C')
						{
							$sx .= '<LI>';
							$sx .= trim($line['ja_autores_nomes']);
							$sx .= ''.trim($line['la_titulo']).'.';
							$sx .= ' ';
							$sx .= UPPERCASE($journal);
							$vol = trim($line['la_vol']);
							$num = trim($line['la_num']);
							$pag = trim($line['la_pag']);
							if (strlen($vol) > 0) { $sx .= ', v.'.$vol; }
							if (strlen($num) > 0) { $sx .= ', n.'.$num; }
							if (strlen($pag) > 0) { $sx .= ', p. '.$pag; }
							$sx .= ', '.trim($line['la_ano']);
							$sx .= '. (capítulo de livro)';
							$sx .= '</LI>';
						}
						$sx .= '</UL>';
						$sx .= '<TD width="15" class="tabela01">';
						$sx .= ''.$line['eq_estrato'].'';
//						$sx .= '<BR>'.$line['eq_area'];
						$sx .= '</table>';
						$xid = $id;
					}
				}
							
				$sx .= '</div>';
				$sx .= '</fieldset>';
				return($sx);
			}
		
		function linhas($ln)
			{
				$lns = array();
				$ln .= chr(13);
				while (strpos($ln,chr(13)) > 0)
					{
						$pos = strpos($ln,chr(13));
						$ln1 = trim(substr($ln,0,$pos));
						
						if (strlen($ln1) > 0) { array_push($lns,$ln1); }
						$ln = substr($ln,$pos+1,strlen($ln));						
					}
				return($lns);
			}
		function campos($ln)
			{
				$lns = array();
				$ln .= ';';
				while (strpos($ln,'";') > 0)
					{
						$pos = strpos($ln,'";');
						$ln1 = trim(substr($ln,0,$pos));
						$ln1 = troca($ln1,'"','');
						$ln1 = troca($ln1,';','');
						array_push($lns,$ln1);
						$ln = substr($ln,$pos+1,strlen($ln));						
					}
				return($lns);				
			}
			

		function inport_artigos($ln)
			{
				$lns = $this->linhas($ln);
				echo '<HR>A=='.count($lns).'==A<HR>';
				$nome = 'x';
				echo '<table width="704"><TR><TD>Processamento<TR><TD><TT>';
				for ($r=0;$r < count($lns);$r++)
					{
						$cp = $this->campos($lns[$r]);
						$tp = $this->tipo_publicacao($cp[1]);
						if ($tp == 'A') { $ok = $this->inport_artigo($cp); }
					}
				echo '</table>';
			}
		function inport_eventos($ln)
			{
				//$sql = "delete from lattes_artigos"; $rlt = db_query($sql);
				//$this->strucuture();
				$lns = $this->linhas($ln);
				$nome = 'x';
				echo '<table width="704"><TR><TD>Processamento<TR><TD><TT>';
				for ($r=0;$r < count($lns);$r++)
					{
						$cp = $this->campos($lns[$r]);
						$ok = $this->inport_evento($cp);
					}
				echo '</table>';
			}				
		function inport_projetos($ln)
			{
				//$sql = "delete from lattes_artigos"; $rlt = db_query($sql);
				//$this->strucuture();
				$lns = $this->linhas($ln);
				$nome = 'x';
				echo '<table width="704"><TR><TD>Processamento<TR><TD><TT>';
				for ($r=0;$r < count($lns);$r++)
					{
						$cp = $this->campos($lns[$r]);
						$ok = $this->inport_projeto($cp);
					}
				echo '</table>';
			}				
		function inport_livros_capitulos($ln)
			{
				//$sql = "delete from lattes_artigos"; $rlt = db_query($sql);
				//$this->strucuture();
				$lns = $this->linhas($ln);
				$nome = 'x';
				echo '<table width="704"><TR><TD>Processamento<TR><TD><TT>';
				for ($r=0;$r < count($lns);$r++)
					{
						$cp = $this->campos($lns[$r]);
						$ok = $this->inport_livro_capitulo($cp);
					}
				echo '</table>';
			}	
		function inport_livros($ln)
			{
				//$sql = "delete from lattes_artigos"; $rlt = db_query($sql);
				//$this->strucuture();
				$lns = $this->linhas($ln);
				$nome = 'x';
				echo '<table width="704"><TR><TD>Processamento<TR><TD><TT>';
				for ($r=0;$r < count($lns);$r++)
					{
						$cp = $this->campos($lns[$r]);
						$ok = $this->inport_livro($cp);
					}
				echo '</table>';
			}	
		function inport_livros_organizados($ln)
			{
				//$sql = "delete from lattes_artigos"; $rlt = db_query($sql);
				//$this->strucuture();
				$lns = $this->linhas($ln);
				$nome = 'x';
				echo '<table width="704"><TR><TD>Processamento<TR><TD><TT>';
				for ($r=0;$r < count($lns);$r++)
					{
						$cp = $this->campos($lns[$r]);
						$ok = $this->inport_livro_organizado($cp);
					}
				echo '</table>';
			}		
		function busca_periodico($nome,$issn)
			{
				$nome = trim(substr($nome,0,100));
				if (strlen($nome) == 0) { echo $nome.' não encontrado'; return(''); }
				if ((strlen($issn)==9) and ($issn != '0000-0000'))
				{
					$sql = "select * from lattes_journals
					where j_issn = '$issn' or j_name = '$nome' 
					";
				} else {
					$sql = "select * from lattes_journals
					where j_name = '$nome' 
					";				
				}
				$rlt = db_query($sql);
				if (!($line = db_read($rlt)))
					{
						$xsql = "insert into lattes_journals
							(j_name, j_prioritaria, j_abbrev,
							j_issn,j_type,j_year_start,
							j_year_end, j_obs, j_use,
							j_codigo, j_idioma, j_cidade,
							j_estado, j_regiao, j_pais
							) values (
							'$nome',0,'',
							'$issn','J',0,
							0,'','',
							'','','',
							'','','')
						";
						$rlt = db_query($xsql);
						$this->updatex_journal();
						$rlt = db_query($sql);
						$line = db_read($rlt);
					}
				$codigo = $line['j_codigo'];
				return($codigo);
			}

			
		function inport_livro_organizado($cp)
			{
				//$sql = "delete from lattes_artigos where la_tipo = 'L'";
				//$rlt = db_query($sql);
				//exit;
				$professor = trim($this->busca_professor($cp[0]));
				if (strlen($professor) ==0) { echo UpperCaseSql($cp[0]).' não localizado<BR>'; return(false); }
				$this->updatex_journal();
				$issn = $this->formata_issn($cp[2]);
				$periodico = '';
				$ano = $cp[3];
				$pagina = substr(trim($cp[6]),0,8);
				$autores = round($cp[18]);
				$titulo = trim($cp[1]);
				$idioma = $this->idioma($cp[4]);
				$autor_ordem = $cp[10];
				$doi = '';
				$jcr = '';
				$qualis = '';
				$circulacao = '';
				$editora = $this->busca_periodico($cp[7],$issn);
				
				$autores_nome = '';
				for ($r=12;$r < count($cp);$r++)
					{
						if (strlen($autores_nome) > 0) { $autores_nome .= '; '; }
						$autores_nome .= $cp[$r];
					}
				
				$relevante = 0;
				$vol = substr($cp[9],0,5);
				$num = substr($cp[10],0,5);		
				
				
				
				//if (strlen(trim($cp[12])) > 0) {
				//		$pagn = trim($cp[12]);
				//		$pags = (7-strlen($pagina));
				//		$pagn = substr($pagn,strlen($pagn)-$pags,$pags);
				//		$pagina .= '-'.$pagn; 
				//	}
				$sql = "select * from lattes_artigos 
						where la_professor = '$professor'
						and la_ano = '$ano'
						and ja_autores = $autores
						and la_periodico = '$editora'
						and la_pag = '$pagina'
						";
				$rlt = db_query($sql);
				if (!($line = db_read($rlt)))
				{						
				$sql = "insert into lattes_artigos (
						la_professor, la_titulo, la_tipo,
						la_idioma, la_ano, la_autor_ordem,
						la_relevante, la_periodico, la_vol,

						la_nul, la_pag, la_doi,
						la_jcr, ja_qualis, ja_circulacao,
						ja_autores, ja_autores_nomes
						) values (
						'$professor','$titulo','O',
						'$idioma','$ano','$autor_ordem',
						'$relevante','$editora','$vol',
						
						'$num','$pagina','$doi',
						'$jcr','$qualis','$circulacao',
						$autores,'$autores_nome') 
					";
					$rlt = db_query($sql);
				}
				return(True);
			}			
			
		function inport_livro($cp)
			{
				//$sql = "delete from lattes_artigos where la_tipo = 'L'";
				//$rlt = db_query($sql);
				//exit;
				
				$professor = trim($this->busca_professor($cp[0]));
				if (strlen($professor) ==0) { echo UpperCaseSql($cp[0]).' não localizado<BR>'; return(false); }
				$this->updatex_journal();
				$issn = $this->formata_issn($cp[2]);
				$periodico = '';
				$ano = $cp[3];
				$pagina = substr(trim($cp[6]),0,8);
				$autores = round($cp[18]);
				$titulo = trim($cp[1]);
				$idioma = $this->idioma($cp[4]);
				$autor_ordem = $cp[10];
				$doi = '';
				$jcr = '';
				$qualis = '';
				$circulacao = '';
				$editora = $this->busca_periodico($cp[7],$issn);
				
				$autores_nome = '';
				for ($r=12;$r < count($cp);$r++)
					{
						if (strlen($autores_nome) > 0) { $autores_nome .= '; '; }
						$autores_nome .= $cp[$r];
					}
				
				$relevante = 0;
				$vol = substr($cp[9],0,5);
				$num = substr($cp[10],0,5);		
				
				if (strlen($professor) ==0) { echo UpperCaseSql($cp[0]).' não localizado<BR>'; return(false); }
				
				//if (strlen(trim($cp[12])) > 0) {
				//		$pagn = trim($cp[12]);
				//		$pags = (7-strlen($pagina));
				//		$pagn = substr($pagn,strlen($pagn)-$pags,$pags);
				//		$pagina .= '-'.$pagn; 
				//	}
				$sql = "select * from lattes_artigos 
						where la_professor = '$professor'
						and la_ano = '$ano'
						and ja_autores = $autores
						and la_periodico = '$editora'
						and la_pag = '$pagina'
						";
				$rlt = db_query($sql);
				if (!($line = db_read($rlt)))
				{						
				$sql = "insert into lattes_artigos (
						la_professor, la_titulo, la_tipo,
						la_idioma, la_ano, la_autor_ordem,
						la_relevante, la_periodico, la_vol,

						la_nul, la_pag, la_doi,
						la_jcr, ja_qualis, ja_circulacao,
						ja_autores, ja_autores_nomes
						) values (
						'$professor','$titulo','L',
						'$idioma','$ano','$autor_ordem',
						'$relevante','$editora','$vol',
						
						'$num','$pagina','$doi',
						'$jcr','$qualis','$circulacao',
						$autores,'$autores_nome') 
					";
					$rlt = db_query($sql);
				}
				return(True);
			}
		function inport_livro_capitulo($cp)
			{
				//$sql = "delete from lattes_artigos where la_tipo = 'L'";
				//$rlt = db_query($sql);
				//exit;
				$professor = trim($this->busca_professor($cp[0]));
				if (strlen($professor) ==0) { echo UpperCaseSql($cp[0]).' não localizado<BR>'; return(false); }
				
				$titulo = trim($cp[1]);
				$issn = $this->formata_issn($cp[2]);
				$ano = $cp[3];
				$idioma = $this->idioma($cp[4]);
				$vol = substr($cp[5],0,5);
				$editora = $this->busca_periodico($cp[7],$issn);								
				$this->updatex_journal();
				$periodico = '';
				$pagina = substr(trim($cp[6]),0,8);
				$autores = round($cp[18]);
				
				$autor_ordem = $cp[10];
				$doi = $cp[11];
				$jcr = '';
				$qualis = '';
				$circulacao = '';
				
				
				$autores_nome = '';
				for ($r=12;$r < count($cp);$r++)
					{
						if (strlen($autores_nome) > 0) { $autores_nome .= '; '; }
						$autores_nome .= $cp[$r];
					}
				
				$relevante = 0;
				
				$num = '';		
				
				if (strlen($professor) ==0) { echo UpperCaseSql($cp[0]).' não localizado<BR>'; return(false); }
				
				//if (strlen(trim($cp[12])) > 0) {
				//		$pagn = trim($cp[12]);
				//		$pags = (7-strlen($pagina));
				//		$pagn = substr($pagn,strlen($pagn)-$pags,$pags);
				//		$pagina .= '-'.$pagn; 
				//	}
				$sql = "select * from lattes_artigos 
						where la_professor = '$professor'
						and la_ano = '$ano'
						and ja_autores = $autores
						and la_periodico = '$editora'
						and la_pag = '$pagina'
						";
				$rlt = db_query($sql);
				if (!($line = db_read($rlt)))
				{						
				$sql = "insert into lattes_artigos (
						la_professor, la_titulo, la_tipo,
						la_idioma, la_ano, la_autor_ordem,
						la_relevante, la_periodico, la_vol,

						la_nul, la_pag, la_doi,
						la_jcr, ja_qualis, ja_circulacao,
						ja_autores, ja_autores_nomes
						) values (
						'$professor','$titulo','C',
						'$idioma','$ano','$autor_ordem',
						'$relevante','$editora','$vol',
						
						'$num','$pagina','$doi',
						'$jcr','$qualis','$circulacao',
						$autores,'$autores_nome') 
					";
					$rlt = db_query($sql);			
				}
				return(True);
			}
			
		function inport_artigo($cp)
			{
				
				$professor = trim($this->busca_professor($cp[0]));
				if (strlen($professor) ==0) { $professor = trim($this->busca_aluno(trim($cp[0]))); }
				if (strlen($professor) ==0) { echo '<BR>'.UpperCaseSql(trim($cp[0])).' não localizado<BR>'; return(false); }
				$this->updatex_journal();
				
				//echo $professor;
				$issn = $this->formata_issn($cp[8]);
				$periodico = $this->busca_periodico($cp[7],$issn);
				$ano = $cp[3];
				$pagina = substr(trim($cp[11]),0,8);
				$autores = round($cp[18]);
				$titulo = trim($cp[4]);
				$idioma = $this->idioma($cp[2]);
				$autor_ordem = $cp[5];
				$doi = substr($cp[14],0,50);
				$jcr = $cp[15];
				$qualis = $cp[16];
				$circulacao = substr($cp[17],0,1);
				$autores_nome = '';
				for ($r=19;$r < count($cp);$r++)
					{
						if (strlen($autores_nome) > 0) { $autores_nome .= '; '; }
						$autores_nome .= $cp[$r];
					}
				
				$relevante = $cp[6];
				$vol = substr($cp[9],0,5);
				$num = substr($cp[10],0,5);		
				
				if (strlen($professor) ==0) { echo UpperCaseSql($cp[0]).' não localizado<BR>'; return(false); }
				
				//if (strlen(trim($cp[12])) > 0) {
				//		$pagn = trim($cp[12]);
				//		$pags = (7-strlen($pagina));
				//		$pagn = substr($pagn,strlen($pagn)-$pags,$pags);
				//		$pagina .= '-'.$pagn; 
				//	}
				$sql = "select * from lattes_artigos 
						where la_professor = '$professor'
						and la_ano = '$ano'
						and ja_autores = $autores
						and la_periodico = '$periodico'
						and la_pag = '$pagina'
						";
				$rlt = db_query($sql);
				
				//echo '<BR>-('.$ano.')--'.$titulo.'';
				if (!($line = db_read($rlt)))
				{						
				$sql = "insert into lattes_artigos (
						la_professor, la_titulo, la_tipo,
						la_idioma, la_ano, la_autor_ordem,
						la_relevante, la_periodico, la_vol,

						la_nul, la_pag, la_doi,
						la_jcr, ja_qualis, ja_circulacao,
						ja_autores, ja_autores_nomes
						) values (
						'$professor','$titulo','A',
						'$idioma','$ano','$autor_ordem',
						'$relevante','$periodico','$vol',
						
						'$num','$pagina','$doi',
						'$jcr','$qualis','$circulacao',
						$autores,'$autores_nome') 
					";
					$rlt = db_query($sql);
				}
				return(True);
			}
		function inport_projeto($cp)
			{
					global $i;
					$cpf = sonumero($cp[0]);
					$titulo = $cp[2];
					$status = $cp[3];
					$tipo = $cp[4];
					$anoi = $cp[5];
					$anof = $cp[6];
					$inst = $cp[7];
					$fina = $cp[8];
					
					if (strlen($cpf) > 8)
						{
							echo '<BR>CPF:'.$cpf . ' - '.$cp[1];
							echo '<BR><B>'.$titulo.'</B>';
							echo '<BR>Tipo: '.$tipo;
							echo '<BR>Situação: '.$status;
							echo '<BR>Tipo: '.$tipo;
							echo '<BR>Ano '.$anoi.'-'.$anof;
							echo '<BR>Financiamento: '.$fina;
							echo '<BR>Instituição: '.$inst;
							
							$prof = trim($this->busca_professor_cpf($cpf));
							
							if (strlen($prof) < 8)
								{
									$this->erros .= '<BR>Docente não localizado '.$cpf.' - '.$nome;
									echo '<BR><font color="Red">Docente não localizado</font>';
								} else {
									/* processar */
									echo ' - <font color="green">OK</font>';
									$bp = new projetos;
									$bp->projeto_inserir($prof,$titulo,$tipo,$status,$anoi,$anof,$fina,$inst);
								}
							
							echo '<HR>';
						} else {
							$this->erros .= '<BR>CPF não localizado:'.$cpf.'-'.$cp[1];
						}
					
					if (!(isset($i))) { $i = 0; }
					$i++;
					//if ($i > 450) { exit; }
					return(True);
			}


		function inport_evento($cp)
			{
				
				$titulo = trim($cp[1]);
				$professor = trim($this->busca_professor($cp[0]));
				if (strlen($professor) ==0) { echo UpperCaseSql($cp[0]).' não localizado<BR>'; return(false); }
				$this->updatex_journal();
								
				$issn = $this->formata_issn($cp[3]);
				$periodico = $this->busca_periodico($cp[2],$issn);
				$ano = $cp[4];
				$vol = substr($cp[5],0,5);
				
				$pagina = substr(trim($cp[6]),0,8);
				$autores = round('0');
				
				$idioma = '';
				$autor_ordem = $cp[9];
				$doi = substr($cp[10],0,50);
				$jcr = '';
				$qualis = '';
				$circulacao = '';
				$autores_nome = '';
				for ($r=11;$r < count($cp);$r++)
					{
						if (strlen($autores_nome) > 0) { $autores_nome .= '; '; }
						$autores_nome .= $cp[$r];
					}
				
				$relevante = '0';
				
				$num = substr($cp[8],0,5);		
				
				if (strlen($professor) ==0) { echo UpperCaseSql($cp[0]).' não localizado<BR>'; return(false); }
				
				//if (strlen(trim($cp[12])) > 0) {
				//		$pagn = trim($cp[12]);
				//		$pags = (7-strlen($pagina));
				//		$pagn = substr($pagn,strlen($pagn)-$pags,$pags);
				//		$pagina .= '-'.$pagn; 
				//	}
				$sql = "select * from lattes_artigos 
						where la_professor = '$professor'
						and la_ano = '$ano'
						and ja_autores = $autores
						and la_periodico = '$periodico'
						and la_pag = '$pagina'
						";
				$rlt = db_query($sql);
				if (!($line = db_read($rlt)))
				{						
				$sql = "insert into lattes_artigos (
						la_professor, la_titulo, la_tipo,
						la_idioma, la_ano, la_autor_ordem,
						la_relevante, la_periodico, la_vol,

						la_nul, la_pag, la_doi,
						la_jcr, ja_qualis, ja_circulacao,
						ja_autores, ja_autores_nomes
						) values (
						'$professor','$titulo','E',
						'$idioma','$ano','$autor_ordem',
						'$relevante','$periodico','$vol',
						
						'$num','$pagina','$doi',
						'$jcr','$qualis','$circulacao',
						$autores,'$autores_nome') 
					";
					$rlt = db_query($sql);
				}
				return(True);
			}

		function idioma($idioma)
			{
				$tp = array(
					'Português'=>'pt_BR',
					'Ingês'=>'en'
				);
				$rs = $tp[$idioma];
				return($rs);
			}
		function formata_issn($issn)
			{
				while (strlen($issn) < 8) {$issn = '0'.$issn; }
				$issn = substr($issn,0,4).'-'.substr($issn,4,4);
				return($issn);
			}
		function tipo_publicacao($tipo)
			{
				$tipo = trim($tipo);
				
				$tp = array(
							'Artigo publicado em periódicos - Completo'=>'A',
							'Livro'=>'L'
							);
				$rs = $tp[$tipo];
				return($tp[$tipo]);
			}
		function busca_professor($nome)
			{
				$sql = "select * from ".$this->docentes." where (pp_nome_asc like '".upperCaseSql($nome)."%'
						or pp_nome_lattes = '".UpperCaseSql($nome)."') and pp_ativo = 1 
				";
				$rlt = db_query($sql);
				if ($line = db_read($rlt))
				{
					return($line['pp_cracha']);
				} else {
						$sql = "select * from ".$this->discentes." where pa_nome_asc like '".upperCaseSql($nome)."%'
							or pa_nome_lattes = '".UpperCaseSql($nome)."' 
						";
						$rlt = db_query($sql);
						if ($line = db_read($rlt))
							{return($line['pa_cracha']); }
					
					return(''); 
				}
			}
		function busca_aluno($nome)
			{
				//$sql = "alter table ".$this->discentes." add column pa_nome_lattes char(100)";
				//$rlt = db_query($sql);
				$sql = "select * from ".$this->discentes." where pa_nome_asc like '".upperCaseSql($nome)."%'
						or pa_nome_lattes = '".UpperCaseSql($nome)."' 
				";
				$rlt = db_query($sql);
				if ($line = db_read($rlt))
				{
					echo '<BR>'.$line['pa_nome'].' - '.$line['pa_cracha'].' ok';
					return(trim($line['pa_cracha']));
				} else {
					return(''); 
				}
			}			
			
		function updatex_journal()
			{
				global $base;
				$c = 'j';
				$c1 = 'id_'.$c;
				$c2 = $c.'_codigo';
				$c3 = 7;
				$sql = "update lattes_journals set $c2 = lpad($c1,$c3,0) where $c2='' ";
				if ($base=='pgsql') { $sql = "update lattes_journals set $c2 = trim(to_char(id_".$c.",'".strzero(0,$c3)."')) where $c2='' "; }
				$rlt = db_query($sql);
			}
						
		function strucuture()
			{
				$sql = "CREATE TABLE lattes_tipo 
					(
						id_lt serial NOT NULL,
						lt_codigo char(1),
						lt_titulo char(60)
					)";
				$rlt = db_query($sql);
				
				$sql = "insert into lattes_tipo (lt_codigo, lt_titulo) values ('A','Artigos Científicos')";
				$rlt = db_query($sql);
				$sql = "insert into lattes_tipo (lt_codigo, lt_titulo) values ('L','Livros publicados')";
				$rlt = db_query($sql);
				$sql = "insert into lattes_tipo (lt_codigo, lt_titulo) values ('C','Capítulo de livros publicados')";
				$rlt = db_query($sql);
				$sql = "insert into lattes_tipo (lt_codigo, lt_titulo) values ('E','Trabalhos apresentados em eventos')";
				$rlt = db_query($sql);
				$sql = "insert into lattes_tipo (lt_codigo, lt_titulo) values ('O','Outras produções')";
				$rlt = db_query($sql);
				exit;
				
								
				$sql = "CREATE TABLE lattes_artigos 
					(
						id_la serial NOT NULL,
						la_professor char(8),
						la_titulo text,
						la_tipo char(1),
						la_idioma char(5),
						la_ano char(4),
						la_autor_ordem char(2),
						la_relevante char(1),
						la_periodico char(7),
						la_vol char(5),
						la_nul char(5),
						la_pag char(8),
						la_doi char(50),
						la_jcr char(10),
						ja_qualis char(2),
						ja_circulacao char(1),
						ja_autores integer,
						ja_autores_nomes text
					)";
				$rlt = db_query($sql);
				
				$sql = "CREATE TABLE lattes_journals
					(
						id_j serial NOT NULL,
						j_name char(100),
						j_prioritaria integer,
						j_abbrev char(40),
						j_issn char(9),
						j_type char(1),
						j_year_start integer,
						j_year_end integer,
						j_obs text,
						j_use char(7),
						j_codigo char(7),
						j_idioma char(5),
						j_cidade char(7),
						j_estado char(5),
						j_regiao char(5),
						j_pais char(5)
					)"; 
				$rlt = db_query($sql);
			}
	}
