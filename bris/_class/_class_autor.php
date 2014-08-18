<?php
class autor
	{
	var $nome;
	var $line;
	
	var $anos = 15;
	
	function resumo_producao()
		{
			$sx .= '<table width="100%">';
			$sx .= '<TR><TH>Trabalhos produzidos<TH>Citações recebidas';
			$sx .= '<TR align="center"><TD>'.$this->total_producao;
			$sx .= '<TD>'.$this->total_citacoes;
			$sx .= '</table>';
			return($sx);
		}

	function autor_revistas_publicacoes_cinco_ano($autor)
		{
			$ano1 = date("Y");
			$ano2 = $ano1 - 9;
			$sql = "select count(*) as total, ae_journal_id, jnl_nome, jnl_tipo from brapci_article_author
					inner join brapci_journal on ae_journal_id = jnl_codigo 
					inner join brapci_article on ae_article = ar_codigo
					inner join brapci_section on ar_section = se_codigo and se_tipo = 'B'
					where ae_author = '$autor' and (ar_section <> 'EDITO' and ar_section <> 'RESEN')
					and jnl_tipo = 'J' and ar_status <> 'X'
					and (ar_ano >= $ano2 and ar_ano <= $ano1)
					group by ae_journal_id, jnl_nome, jnl_tipo
			";
			$rlt = db_query($sql);
			$id = 0;
			while ($line = db_read($rlt))
				{
					if ($id > 0) { $sd .= ', '.chr(13).chr(10); }
					$id++;
					$sd .= "['".trim($line['jnl_nome'])."', " .
							$line['total']."] ";
				}
			$sx = '
    			<script type="text/javascript">
      			function drawVisualization2() {
        			// Create and populate the data table.
        			var data = google.visualization.arrayToDataTable([
          			[\'Revista\', \'Publicações\'],
					'.$sd.'
        			]);
      
        			// Create and draw the visualization.
        			new google.visualization.PieChart(document.getElementById(\'visualization2\')).
            			draw(data, {title:"Concetração da produção de '.$ano2.'-'.$ano1.'"});
			      }
      				google.setOnLoadCallback(drawVisualization2);
    			</script>
			    <div id="visualization2" style="width: 600px; height: 400px;"></div>
			';
			return($sx);
		}
	
	function autor_revistas_publicacoes($autor)
		{
			$sql = "select count(*) as total, ae_journal_id, jnl_nome, jnl_tipo from brapci_article_author
					inner join brapci_journal on ae_journal_id = jnl_codigo 
					inner join brapci_article on ae_article = ar_codigo
					where ae_author = '$autor' and (ar_section <> 'EDITO' and ar_section <> 'RESEN')
					and jnl_tipo = 'J' and ar_status <> 'X'
					group by ae_journal_id, jnl_nome, jnl_tipo
			";
			$rlt = db_query($sql);
			$id = 0;
			while ($line = db_read($rlt))
				{
					if ($id > 0) { $sd .= ', '.chr(13).chr(10); }
					$id++;
					$sd .= "['".trim($line['jnl_nome'])."', " .
							$line['total']."] ";
				}
			$sx = '
    			<script type="text/javascript" src="//www.google.com/jsapi"></script>
    			<script type="text/javascript">
      			google.load(\'visualization\', \'1\', {packages: [\'corechart\']});
    			</script>
    			<script type="text/javascript">
      			function drawVisualization() {
        			// Create and populate the data table.
        			var data = google.visualization.arrayToDataTable([
          			[\'Revista\', \'Publicações\'],
					'.$sd.'
        			]);
      
        			// Create and draw the visualization.
        			new google.visualization.PieChart(document.getElementById(\'visualization\')).
            			draw(data, {title:"Concetração da produção"});
			      }
      				google.setOnLoadCallback(drawVisualization);
    			</script>
			    <div id="visualization" style="width: 600px; height: 200px;"></div>
			';
			return($sx);
		}
	
	function grafico_minhas_citacoes()
		{
			$ano = $this->anos;
			$vlr = $this->dados_cited;
			//$vlr2 = $this->dados_evento;
			$sx = '<Table width="500" class="lt0">';
			$sx .= '<TR><TD colspan=15 >citações recebidas por ano ';
			for ($r=0;$r <= $ano;$r++)
				{
					$size = $vlr[$r] * 5;
					$size2 = $vlr2[$r] * 5;
					$s1 .= '<td align="center" style="border-top: 1px solid #404040;">'.(date("Y")+$r-$ano);
					$s2 .= '<td align="center" style="border-top: 1px solid #404040;">'.($vlr[$r] + $vlr2[$r]);
					$s3 .= '<td align="center">';
							if ($size > 0)
								{ $s3 .= '<img src="img/nada_verde.png" width="20" height="'.$size.'"><BR>'; }
							if ($size2 > 0)
								{ $s3 .= '<img src="img/nada_azul.png" width="20" height="'.$size2.'">'; }
				}
			$sx .= '<TR valign="bottom">'.$s3.'<TD height="100">';
			$sx .= '<TR>'.$s2;
			$sx .= '<TR>'.$s1;
			$sx .= '<TR><TD colspan=40 class="lt0">';
			$sx .= '<img src="img/nada_verde.png" width="10"> Citações recebidas no ano<BR>';
			//$sx .= '<img src="img/nada_azul.png" width="10"> Evento<BR>';
			$sx .= '</table>';
			return($sx);
		}

	
	function citacoes_meus_artigos($autor)
		{
			$sql = "select count(*) as cited, cit_ano_pub from brapci_article_author
						inner join bris_cited on cit_citado = ae_article
						where ae_author = '$autor'
						group by cit_ano_pub
			";
			
			$rlt = db_query($sql);
			$pub = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);

			$ano = $this->anos;
			$anoi = date("Y");
			while ($line = db_read($rlt))
			{
				$cit = $line['cited'];
				$anoz = $anos - ($anoi - $line['cit_ano_pub']) + $ano;
				$pub[$anoz] = $pub[$anoz] + $cit;
			}
			$this->dados_cited = $pub;
		}
	function le($id)
		{
			$sql = "select * from brapci_autor 
						where autor_codigo = '$id'
			";
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					$this->nome = trim($line['autor_nome']);
					$this->line = $line;
					$this->codigo = $line['autor_codigo'];
				}
		}
	function mostra()
		{
			$sx .= '<table width="100%">';
			$sx .= '<TR><TD class="lt0">NOME DO PESQUISADOR';
			$sx .= '<TR><TD>'.trim($this->line['autor_nome']);
			$sx .= '</table>';
			return($sx);
		}
	function grafico()
		{
			$ano = $this->anos;
			$vlr = $this->dados;
			$vlr2 = $this->dados_evento;
			$sx = '<Table width="500" class="lt0">';
			$sx .= '<TR><TD colspan=15 >publicações por ano ';
			for ($r=0;$r <= $ano;$r++)
				{
					$size = $vlr[$r] * 5;
					$size2 = $vlr2[$r] * 5;
					$s1 .= '<td align="center" style="border-top: 1px solid #404040;">'.(date("Y")+$r-$ano);
					$s2 .= '<td align="center" style="border-top: 1px solid #404040;">'.($vlr[$r] + $vlr2[$r]);
					$s3 .= '<td align="center">';
							if ($size > 0)
								{ $s3 .= '<img src="img/nada_verde.png" width="20" height="'.$size.'"><BR>'; }
							if ($size2 > 0)
								{ $s3 .= '<img src="img/nada_azul.png" width="20" height="'.$size2.'">'; }
				}
			$sx .= '<TR valign="bottom">'.$s3.'<TD height="100">';
			$sx .= '<TR>'.$s2;
			$sx .= '<TR>'.$s1;
			$sx .= '<TR><TD colspan=40 class="lt0">';
			$sx .= '<img src="img/nada_verde.png" width="10"> Artigo<BR>';
			$sx .= '<img src="img/nada_azul.png" width="10"> Evento<BR>';
			$sx .= '</table>';
			return($sx);
		}
	function publicoes_dos_autor($autor)
		{
			global $db_public;
			$art = new article;
					
			$sql = "select * 						
						FROM brapci_article_author						
						INNER join ".$db_public."artigos on ar_codigo = ae_article
						left join brapci_journal on jnl_codigo = ar_journal_id
						LEFT join bris_article on ca_artigo = ar_codigo
					where ae_author = '$autor'					
					order by ar_ano desc, ar_vol desc, ar_nr desc	
				";
			$rlt = db_query($sql);
			$wh = '';
			$pub = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
			$eve = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
			$anos = 15;
			$this->anos = $anos;
			$anoi = date("Y");
			$total = 0;
			$tcit = 0;
			while ($line = db_read($rlt))
				{
					$tp = $line['jnl_tipo'];
					$total = $total + 1;
					$ano = $anos - ($anoi - $line['ar_ano']);
					switch ($tp)
						{
							case 'J':
								$pub[$ano] = $pub[$ano] + 1;
								break;
							case 'E':
								$eve[$ano] = $eve[$ano] + 1;
								break;
							default:
								echo '['.$tp.']';
								exit;
								break;	
						}
					
					$cit = round($line['ca_citacoes']);
					$link = '<A HREF="/article.php?dd0='.$line['ar_codigo'].'" >VIEW</A>';
					$sx .= $art->referencia_abnt($line);
					$sx .= '';
					if ($cit > 0)
						{
							$sx .= ' (<font color="blue">'.$cit.' citações</font>)';
						}
					$tcit = $tcit + $cit;
					$sx .= '&nbsp&nbsp[ '.$link.' ]';
					$sx .= '<BR><BR>';
				}
			$this->dados = $pub;
			$this->dados_evento = $eve;
			$this->total_producao = $total;
			$this->total_citacoes = $tcit;

			$br = new bris;
			$ano = '0000';
			$br->atualiza_producao($autor,$ano,$total);
						
			return($sx);			
		}		
	}
?>
