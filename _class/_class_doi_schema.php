<?php
class doi
	{
		var $datahora;
		var $batch_id='10.7213';
		
		var $link = 'http://www2.pucpr.br/reol/index.php/';
		var $doi_id;
		var $depositor_name = 'PUCPR';
		//var $depositor_email = 'editora.champagnat@pucpr.br';
		var $depositor_email = 'monitoramento@sisdoc.com.br';
		var $registrant = 'PUCPR';
		
		var $journal_title = 'Fisioterapia e Movimento';
		var $journal_abbre = 'fisiot.mov.';
		var $journal_ISSN = '0000-0000';
		var $journal_coden = 'applab';
		
		var $cited;
		
		var $article_title;
		var $authors = array();
		
		var $year;
		var $volume;
		var $issue;
		
		var $erro;
		
		function tab($x)
			{
				$x = round($tab);
				$sx = '';
				while ($x > 0)
					{
						$sx .= chr(15);
						$x--;	
					}
				return($sx);
					
			}

		function PIPE_query()
			{
				/* ISSN      | JOURNAL       | AUTHOR   | VOL | ISSUE | PAGE | YEAR | TYPE | KEY
				 *  10838155 | Urban Ecosyst | Joikmaki | 3   |   1   |  21  | 1999 |      | k2
				 * |Revista Brasileira de Orientação Profissional|Sparta|6|2|45|2005||ensino 
				 */
			}
			
		function XML_query()
			{
				$cr = chr(13).chr(10);
				$sx = '<?xml version = "1.0" encoding="UTF-8"?>'.$cr;
				$sx .= '<doi_batch version="4.3.0" xmlns="http://www.crossref.org/schema/4.3.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.crossref.org/schema/4.3.0 http://www.crossref.org/schema/deposit/crossref4.3.0.xsd">'.$cr;
				$sx .= '<head>'.$cr;
				$sx .= '<doi_batch_id>'.md5(date("Ymdhis")).'</doi_batch_id>'.$cr;
      			$sx .= '	<email_address>rene@sisdoc.com.br</email_address>'.$cr;
				$sx .= '	<timestamp>'.date('YmdHi').'</timestamp>';
      			$sx .= '	<depositor>'.$cr;                  
      			$sx .= '		<name>pucpr</name>'.$cr;                  
      			$sx .= '		<email_address>monitoramento@sisdoc.com.br</email_address>'.$cr;                                 
      			$sx .= '	</depositor>'.$cr;  
				$sx .= '	<registrant>WEB-FORM</registrant>';                
				$sx .= '</head>'.$cr;
				$sx .= '<body>'.$cr;
				$sx .= '	<journal>'.$cr;
				$sx .= '	<journal_metadata>'.$cr;  
				$sx .= '         <journal_title>Fisioterapia em Movimento</journal_title>'.$cr;                  
				$sx .= '         <author match="null">Ribeiro</author>'.$cr;
				$sx .= '         <volume>24</volume>'.$cr;
				$sx .= '         <first_page>211</first_page>'.$cr;
				$sx .= '         <year>2011</year>'.$cr;
				$sx .= '      </query>'.$cr;      
				$sx .= '   </body>'.$cr;
				$sx .= '</query_batch>'.$cr;
				echo '<Pre>'.$sx.'</pre>';
				return(1);
			}
		
		function xml_doi_issue($id)
			{
				global $article;
				$this->erro = array();
				$idx = 0;
				$sql = "select * from articles where article_issue = ".round($id);
				$sql .= " and article_doi <> '' ";
				$rlt = db_query($sql);
				if ($line = db_read($rlt))
				{
					$idx = $line['id_article'];
				}

				if ($idx > 0)
				{
					$article->le($idx);
					/* Head */
					$sx .= $this->xml_head();
					
					/* Body */
					$sx .= '<body>'.chr(13).chr(10);
					
					/* Modelo Journal */
					$cr = chr(13).chr(10);	
					$sx .= '<journal>'.$cr;
					/* Dados */				
					$sx .= $this->xml_journal_metadata();
					$sx .= $this->xml_journal_issue();
					
					
					$sql = "select * from articles where article_issue = ".round($id);
					$sql .= " and article_doi <> '' ";
					$sql .= " order by article_pages ";
					$yrlt = db_query($sql);
					while ($yline = db_read($yrlt))
						{
						$doi = trim($line['article_doi']);
						if (strlen($doi) > 0)
							{
							$article->le($yline['id_article']);
							$sx .= $this->xml_journal_additional();
							}
						}
	
					//$sx .= $this->xml_doi_citation();			
					$sx .= '</journal>'.$cr;	
	
					/* Fim do Body */			
					$sx .= '</body>'.chr(13).chr(10);
					
					$sx .= $this->xml_foot();
					if (count($this->erro) > 0) { $sx = $this->erro_mostra(); }
					return($sx);
				}
			}		
				
		function erro_mostra()
			{
				$erro = $this->erro;
				$sx = '';
				$sx = '<msg><erros>Total de Erros '.count($erro).'</erros>'.chr(13);
				for ($r=0;$r < count($r);$r++)
					{
						$sx .= '<erro_'.$r.'>';
						$sx .= $erro[$r];
						$sx .= '</erro_'.$r.'>'.chr(13);
					}
				$sx .= '</msg>';
				return($sx);
			}
		function xml_doi()
			{
				$this->erro = array();
				/* Head */
				$sx .= $this->xml_head();
				
				/* Body */
				$sx .= '<body>'.chr(13).chr(10);
				
				/* Modelo Journal */
				$sx .= $this->xml_journal();
				
				/* Fim do Body */			
				$sx .= '</body>'.chr(13).chr(10);
				
				$sx .= $this->xml_foot();
				if (count($this->erro) > 0) { $sx = $this->erro_mostra(); }
				return($sx);
			}		
		
		function xml_foot()
			{
				$sx = '</doi_batch>'.chr(13).chr(10);
				return($sx);
			}
		/* Atualiza data e hora da consulta & registro */
		function datahora_atualiza()
			{
				$this->datahora = date("YmdHis");
				return($this->datahora);
			}
			
		/* XML Head */
		function xml_head()
			{
				$batch_id = $this->batch_id;
				$tab = chr(15);
				$cr = chr(13).chr(10);
				/* Codificação de caracteres do arquivo 
				 * Modelo UTF-8
				 */
				$sx = '<?xml version="1.0" encoding="UTF-8"?>'.$cr;
				/* Versão do XML Schema 4.3.0 */
				$sx .= '<doi_batch version="4.3.0" xmlns="http://www.crossref.org/schema/4.3.0" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.crossref.org/schema/4.3.0 http://www.crossref.org/schema/deposit/crossref4.3.0.xsd">'.$cr;
				
				/* Cabeçalho do registro DOI */
				
				$sx .= '<head>'.$cr;
				/* Identificação da consulta */
				$sx .= $this->tab(1).'<doi_batch_id>'.md5(date("Ymdhis")).'</doi_batch_id>'.$cr;
				/* Data de atualizada da consulta */
				$sx .= $this->tab(1).'<timestamp>'.$this->datahora_atualiza().'</timestamp>'.$cr;
				/* Informações sobre o depositante */
				$sx .= $this->tab(1).'<depositor>'.$cr;
				$sx .= $this->tab(2).'<name>'.$this->depositor_name.'</name>'.$cr;
				//$sx .= $this->tab(2).'<email_address>'.$this->depositor_email.'</email_address>'.$cr;
				$sx .= $this->tab(2).'<email_address>monitoramento@sisdoc.com.br</email_address>'.$cr;
				$sx .= $this->tab(1).'</depositor>'.$cr;
				$sx .= $this->tab(1).'<registrant>'.$this->depositor_name.'</registrant>'.$cr;
				$sx .= '</head>'.$cr;
				return($sx);
				}

		/* Dados sobre a publicação */
		function xml_journal()
			{
				$cr = chr(13).chr(10);	
				$sx .= '<journal>'.$cr;
				/* Dados */				
				$sx .= $this->xml_journal_metadata();
				$sx .= $this->xml_journal_issue();
				$sx .= $this->xml_journal_additional();
				//$sx .= $this->xml_doi_citation();			
				$sx .= '</journal>'.$cr;	
				return($sx);
			}
		function xml_journal_metadata()
				{
					global $article;
					$batch_id = $this->batch_id;
					$journal_title = $article->journal_title;
					$journal_abbre = $article->journal_abbrev;
					$journal_ISSN = $article->journal_ISSN;
					$journal_coden = $article->issue_nr;
					$batch_id = $this->$batch_id;				
					$cr = chr(13).chr(10);
					/* journal_metadata */
					$sx .= $this->tab(1).'<journal_metadata language="en">'.$cr;
					$sx .= $this->tab(2).'<full_title>'.utf8_encode($journal_title).'</full_title>'.$cr;
					$sx .= $this->tab(2).'<abbrev_title>'.utf8_encode($journal_abbre).'</abbrev_title>'.$cr;
					$sx .= $this->tab(2).'<issn media_type="print">'.$journal_ISSN.'</issn>'.$cr;
					$sx .= $this->tab(2).'<doi_data>'.$cr;
					$sx .= $this->tab(3).'<doi>'.$this->batch_id.'/'.$article->journal_path.'</doi>'.$cr;
					$sx .= $this->tab(3).'<resource>http://www2.pucpr.br/reol/index.php/'.$article->journal_path.'</resource>';
					$sx .= $this->tab(2).'</doi_data>'.$cr;
					$sx .= $this->tab(1).'</journal_metadata>'.$cr;
					return($sx);
				}
		function xml_journal_issue()
				{
					global $article;
					$cr = chr(13).chr(10);
					
					/* journal_issue */
					$sx .= $this->tab(1).'<journal_issue>'.$cr;
					$sx .= $this->tab(2).'<publication_date>'.$cr;
					$sx .= $this->tab(3).'<month>01</month>'.$cr;
					$sx .= $this->tab(3).'<year>'.$article->issue_year.'</year>'.$cr;
					$sx .= $this->tab(2).'</publication_date>'.$cr;
					
					$sx .= $this->tab(2).'<journal_volume>'.$cr;
					$sx .= $this->tab(3).'<volume>'.$article->issue_vol.'</volume>'.$cr;
					$sx .= $this->tab(2).'</journal_volume>	'.$cr;
								
					$sx .= $this->tab(2).'<issue>'.$article->issue.'</issue>'.$cr;
					$sx .= $this->tab(2).'<doi_data>'.$cr;
					$sx .= $this->tab(3).'<doi>'.$this->batch_id.'/'.$article->journal_path.'.'.$article->issue.'</doi>'.$cr;
					$sx .= $this->tab(3).'<resource>http://www2.pucpr.br/reol/index.php/'.$article->journal_path.'?dd1='.$article->issue.'</resource>';
					$sx .= $this->tab(2).'</doi_data>'.$cr;

					$sx .= $this->tab(1).'</journal_issue>'.$cr;
					
					return($sx);
				}
		function xml_journal_article()
				{
					$cr = chr(13).chr(10);
					
					/* journal_article */
					
					$sx .= $this->tab(1).'<journal_article publication_type="full_text">'.$cr;
					$sx .= $this->tab(2).'<titles>'.$cr;
					$sx .= $this->tab(3).'<title>'.utf8_encode($this->article_title).'</title>'.$cr;
					$sx .= $this->tab(2).'</titles>'.$cr;
					
					/* journal_article - contributors **/
					//$sx .= $this->xml_journal_author();
					
					/* Dados sobre a publicação */
					$sx .= $this->tab(2).'<publication_date media_type="print">'.$cr;
					$sx .= $this->tab(3).'<month>'.$this->year.'</month>'.$cr;
					$sx .= $this->tab(3).'<year>'.$this->year.'</year>'.$cr;
					$sx .= $this->tab(2).'</publication_date>'.$cr;
					
					/* Dados sobre a paginação */
					$sx .= $this->tab(2).'<pages>'.$cr;
					$sx .= $this->tab(3).'<first_page>'.$this->page_ini.'</first_page>'.$cr;
					$sx .= $this->tab(3).'<last_page>'.$this->page_fim.'</last_page>'.$cr;
					$sx .= $this->tab(2).'</pages>'.$cr;
					
					/* Dados sobre o item publicado */
					//$sx .= $this->tab(2).'<publisher_item>'.$cr;
					//$sx .= $this->tab(3).'<identifier id_type="pii">'.$this->doi_sufix.'</identifier>'.$cr;
					//$sx .= $this->tab(2).'</publisher_item>'.$cr;
					
					/* Dados sobre o DOI */
					$sx .= $this->fmt_xml_doi_data();
					
					$sx .= '</journal_article>';
					return($sx);
					}					
					
			function fmt_xml_doi_data($tp='')
				{
					global $article;
					
					$cr = chr(13).chr(10);
					$link_doi = $this->link.$article->journal_path.'?dd1='.$article->id_article.'&amp;dd99=view';
					
					if (strlen(trim($article->link_doi)) > 0)
						{
							$link_doi = trim($article->link_doi);
							$link_doi = troca($link_doi,'&','&amp;');
						}
					
					/* Dados sobre o DOI */
					$sx .= $this->tab(2).'<doi_data>'.$cr;
					$sx .= $this->tab(3).'<doi>'.$article->article_doi.'</doi>'.$cr;
					/* Modelo simplificado */
					if ($tp != 'S') 
						{ $sx .= $this->tab(3).'<timestamp>'.$this->datahora_atualiza().'</timestamp>'.$cr; }
					$sx .= $this->tab(3).'<resource>'.$link_doi.'</resource>'.$cr;
					$sx .= $this->tab(2).'</doi_data>'.$cr;
					return($sx);					
				}
				
			function xml_journal_author()
				{
					$cr = chr(13).chr(10);
					
					$authors = $this->authors;
					$sx .= '<contributors>'.$cr;
					
					/* Relação dos autores */
					for ($au = 0;count($authors);$au++)
						{
							$pos = 'additional';
							if ($au == 0) { $pos = 'first'; }

							/* Posição do autor */
							$sx .= '<person_name sequence="additional" contributor_role="author">'.$cr;
							
							/* Nome do autor */ 
							$sx .= '<given_name>'.utf8_encode($this->autor_abrevia($author[$au][0])).'</given_name>'.$cr;
							
							/* Sobrenome do autor */
							$sx .= '<surname>'.utf8_encode($author[$au][1]).'</surname>'.$cr;
							
							$sx .= '</person_name>'.$cr;
						}
						
						//$sx .= '<organization sequence="additional" contributor_role="author">'.utf8_encode($author[$au][3]).'</organization>'.$cr;
					$sx .= '</contributors>'.$cr;
				return($sx);
				}
			/** Fim do autores */

	function autor_abrevia($nome)
		{
			if (strlen($nome) > 35)
				{ $nome = substr($nome,0,35); }
			return($nome);
			
			$nome .= ' ';
			$nome = troca($nome,' Di ','');
			$nome = troca($nome,' de ','');
			$nome = troca($nome,' da ','');
			$nome = troca($nome,' e ','');
			
			$up = uppercasesql($nome);
			$rs = "";
			$abrev = 0;
			
			for ($ra=0;$ra < strlen($nome);$ra++)
				{
					$ca = substr($up,$ra,1);
					$cs = substr($nome,$ra,1);
					
					if ($ca == chr(32))
						{ $abrev = 1; }
					else {
						{
							if ($abrev == 0) { $rs .= $cs; }
							if ($abrev == 1) { $rs .= ' '.$cs.' '; $abrev++; }
						}
					}
						
				}
					if (strlen($rs)==0) { $rs = trim(substr(trim($nome),0,35));}		
				return(trim($rs));	
		}		
	function xml_journal_additional()
		{
			global $article;
			$cr = chr(13).chr(10);
			$sx .= '<journal_article publication_type="full_text">'.$cr;
			/* Título do arquigo - redundância */
			$sx .= '<titles>'.$cr;
			$sx .= '<title>'.utf8_encode($article->article_title).'</title>'.$cr;
			$sx .= '</titles>'.$cr;
			
			/* Contributors */
			$sx .= '<contributors>'.$cr;
			
			$autores = $article->autores;
			for ($r=0;$r < count($autores);$r++)
				{
				$nba = nbr_autor($autores[$r][0],1);
				$nbb = '';
				if (strpos($nba,',') > 0)
					{
						$nbb = $this->autor_abrevia(trim(substr($nba,strpos($nba,',')+1,strlen($nba))));
						$nba = substr($nba,0,strpos($nba,','));
					}
				if ($r==0) { $sx .= '<person_name sequence="first" contributor_role="author">'.$cr; }
				else { $sx .= '<person_name sequence="additional" contributor_role="author">'.$cr; ; }
				
				$sx .= '<given_name>'.utf8_encode($nbb).'</given_name>'.$cr;
				$sx .= '<surname>'.utf8_encode($nba).'</surname>'.$cr;
				//$sx .= '<suffix></suffix>'.$cr;
				$sx .= '</person_name>'.$cr;
				}			
			$sx .= '</contributors>'.$cr;
			
			/* Dados sobre a publicação - redundância */
			
			$sx .= '<publication_date media_type="print">'.$cr;
			$sx .= '<year>'.$article->issue_year.'</year>'.$cr;
			$sx .= '</publication_date>'.$cr;
			
			$sx .= '<pages>'.$cr;
			$sx .= '<first_page>'.$article->page_ini.'</first_page>'.$cr;
			$sx .= '</pages>'.$cr;
				
			//$sx .= '<publisher_item>'.$cr;
			//$sx .= '<identifier id_type="pii">'.$article->doi_sufix.'</identifier>'.$cr;
			//$sx .= '</publisher_item>'.$cr;
			
			/* DOI simplificado */
			//$sx .= $this->fmt_xml_doi('S');
			$sx .= $this->fmt_xml_doi_data();
			$sx .= '</journal_article>'.$cr;
			
			if (strlen(trim($article->page_ini))==0) 
				{ $this->adiciona_erro('Artigo ('.$article->article_title.') sem pagina inicial'); }
			return($sx);			
			}
		function adiciona_erro($msg)
			{
					$erro = $this->erro;
					array_push($erro,$msg);
					$this->erro = $erro;
					return(1);			
			}
			
		function xml_doi_citation()
			{
				$cited = $this->cited;
				$cr = chr(13).chr(10);
				
				/** Lista de Citações */				
				$sx .= '<citation_list>'.$cr;
				$nc = 1;
				

				for ($na=0;$na < count($cited) ;$na++)
					{
					$line = $cited[$na];
					$ref = $line['m_ref'];
					$sx .= '<citation key="key-'.$this->doi_prefix.'/'.$this->doi_sufix.'-'.$nc.'">'.$cr;
					$xdoi = trim($line['m_bdoi']);
					
					if (strlen($xdoi) > 0)
						{
							$sx .= $this->xml_doi_citation_doi($xdoi);
						} else {
							$tipo = trim($line['m_tipo']);
							if ($tipo == 'ARTIC')
								{
									$sx .= $this->xml_doi_citation_article($line);									
								} else {
									//xml_doi_article
									$sx .= $this->xml_doi_citation_unstructured($ref);								
								}
						}
					$nc++;
					}
				$sx .= '</citation>'.chr(13).chr(10);
				return($sx);
			}	
		/* DOI Estruturado e reconhecido */
		function xml_doi_citation_doi($ct)
			{
				$doi = troca($doi,'doi:','');
				$doi = troca($doi,'http://','');
				$sx .= '<doi>'.$ct.'</doi>'.chr(13).chr(10);
				return($sx);
			}

		/* DOI Estruturado e reconhecido */
		function xml_doi_citation_unstructured($ct)
				{
				$ct = troca($ct,'&',' and ');
				$doi .= '<unstructured_citation>';
				$doi .= utf8_encode($ct);
				$doi .= '</unstructured_citation>'.chr(13).chr(10);
				return($doi);
				}

		/* Livro sem DOI */				
		function xml_doi_citation_book($ct)
			{
				
			}
			
		/* Artigo de Journal */
		function xml_doi_citation_article($line)
			{
				$journal = utf8_encode(trim($line['mj_abrev']));
				if (strlen($journal)==0) { $journal = trim($line['mj_nome']); }
				if (substr($journal,strlen($journal)-1,1) == ',') { $journal = substr($journal,0,strlen($journal)-1); }
				
				$autores = trim($line['m_ref']);
				$autores = substr($autores,0,strpos($autores,'('));
				
				$ano = trim($line['m_ano']);
				$mref = utf8_encode($line['m_ref']);
				
				/** APARA **/
				$vol = substr($mref,strpos($mref,'('.$ano.')')+10,500);
				$num = substr($vol,strpos($vol,'(')+1,5);
				$num = substr($num,0,strpos($num,')'));
				$vol = substr($vol,strpos($vol,'(')-5,20);
				$vol = sonumero(substr($vol,0,strpos($vol,'(')));
				
				$pag = substr($mref,strpos($mref,'('.$num.')')+4,strlen($mref));
				$pag = substr($pag,strpos($pag,',')+1,strlen($pag));
				$pag = sonumero(substr($pag,0,strpos($pag,'-')));
				$cr = chr(13).chr(10);
				$issn = '';
				
				$autores = troca($autores,'&',' and ');
				
				$sx .= '<issn>'.$issn.'</issn>'.$cr;
				$sx .= '<journal_title>'.$journal.'</journal_title>'.$cr;
				$sx .= '<author>'.$autores.'</author>'.$cr;
				$sx .= '<volume>'.$vol.'</volume>'.$cr;
				$sx .= '<issue>'.$num.'</issue>'.$cr;
				$sx .= '<first_page>'.$pag.'</first_page>'.$cr;
				$sx .= '<cYear>'.$ano.'</cYear>'.$cr;
				return($sx);
			}

			 	
	}
?>