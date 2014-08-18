<?php
function trata_xml($text)
	{
		$text = troca($text,'&#56256;&#57300;','f');
		$text = troca($text,'&','e');
		$text = utf8_encode($text);
		return($text);
	}

class doaj
	{
		var $editor = 'Editora Universitária Champagnat (PUCPR)';
	function xml_doaj_issue($id)
		{
		global $article;

		$sx = '<?xml version="1.0" encoding="UTF-8"?>'.$cr;
		$sx .= '<records>'.$cr;
		
				$this->erro = array();
				$idx = 0;
				$sql = "select * from articles where article_issue = ".round($id);
				$rlt = db_query($sql);
				if ($line = db_read($rlt))
				{
					$idx = $line['id_article'];
				}

				if ($idx > 0)
				{
					$article->le($idx);
					/* Head */			
					$sql = "select * from articles 
							inner join issue on article_issue = id_issue
							where article_issue = ".round($id);
					$sql .= " and article_publicado <> 'X' ";
					$sql .= " order by article_pages ";
					$yrlt = db_query($sql);
					while ($yline = db_read($yrlt))
						{
						$doi = trim($line['article_title']);
						if (strlen($doi) > 0)
							{
							$article->le($yline['id_article']);
							$sx .= $this->xml_issue();
							}
						}
		  			$sx .= '</records>'.$cr;	
					return($sx);
				}
		}
		
	function xml_issue()
		{
			$editor = 'Editora Universitária Champagnat';
			global $article;
			$cr = chr(13).chr(10);
			$publicado = ($article->publicado);
			$publicado = substr($publicado,0,4).'-'.substr($publicado,4,2).'-'.substr($publicado,6,2);
			$aceite = $article->article_dt_aceite;
			/* Idioma */
			$lang1 = trim($article->article_idioma);
			$lang1 = troca($lang1,'pt_BR','por');
			$lang1 = troca($lang1,'en','eng');
			$lang1 = troca($lang1,'fr','fre');
			$lang1 = troca($lang1,'es','spa');
			

			$lang2 = trim($article->article_2_idioma);
			$lang2 = troca($lang2,'pt_BR','por');
			$lang2 = troca($lang2,'en','eng');
			$lang2 = troca($lang2,'fr','fre');
			$lang2 = troca($lang2,'es','spa');
			
			$autores = $article->autores;
						
				$sx .= '<record>'.$cr;
    			$sx .= '<language>eng</language>'.$cr;
    			$sx .= '<publisher>'.trata_xml($this->editor).'</publisher>'.$cr;
    			$sx .= '<journalTitle>'.trata_xml($article->journal_title).'</journalTitle>'.$cr;
    			$sx .= '<issn>'.sonumero($article->journal_ISSN).'</issn>'.$cr;
				$sx .= '<publicationDate>'.$publicado.'</publicationDate>'.$cr;
    			$sx .= '<volume>'.$article->issue_vol.'</volume>'.$cr;
    			$sx .= '<issue>'.$article->issue_nr.'</issue>'.$cr;
    			$sx .= '<startPage>'.$article->page_ini.'</startPage>'.$cr;
    			$sx .= '<endPage>'.$article->page_fim.'</endPage>'.$cr;
    			$sx .= '<doi>'.$article->article_doi.'</doi>'.$cr;
    			$sx .= '<publisherRecordId>'.$article->id_article.'</publisherRecordId>'.$cr;
    			$sx .= '<documentType>article</documentType>'.$cr;
    			
				$sx .= '<title language="'.$lang1.'">'.nocr(trata_xml($article->article_title)).'</title>'.$cr;
    			$sx .= '<title language="'.$lang2.'">'.nocr(trata_xml($article->article_2_title)).'</title>'.$cr;
    			$sx .= '<authors>'.$cr;
				for ($r=0;$r < count($autores);$r++)
				{
      				$sx .= '<author>'.$cr;
      				$sx .= ' <name>'.trata_xml($autores[$r][0]).'</name>'.$cr;
        			$sx .= '<email>'.recupera_email($autores[$r][1]).'</email>'.$cr;
        			$sx .= '<affiliationId>'.($r+1).'</affiliationId>'.$cr;

						$sa = trim(troca($autores[$r][1].$cr,';',' '));
						$sa = trata_xml($sa);
		      			$sxa .= '<affiliationName affiliationId="'.($r+1).'">'.$cr;
						$sxa .= $sa;
      					$sxa .= '</affiliationName>'.$cr;

      				$sx .= '</author>'.$cr;
				}
    			$sx .= '</authors>'.$cr;
    			$sx .= '<affiliationsList>'.$cr;
				$sx .= $sxa;
    			$sx .= '</affiliationsList>'.$cr;
				
				/* Resumo - 1 idioma */
				$sx .= '<abstract language="'.$lang1.'">';
				$sx .= trata_xml(trim($article->article_abstract));
				$sx .= '</abstract>'.$cr;

//				$sx .= '<abstract language="'.$lang2.'">';
//				$sx .= trata_xml(trim($article->article_2_abstract));
//				$sx .= '</abstract>'.$cr;

				$sx .= '<fullTextUrl format="pdf">';
      			$sx .= 'http://www2.pucpr.br/reol/index.php/URBE/pdf?dd1='.$article->id_article;
    			$sx .= '</fullTextUrl>'.$cr;
    			$sx .= '<keywords language="'.$lang1.'">'.$cr;
				/* Palavras chave */
				$key = ($article->article_keywords);
				$keys = array();
				if (strpos($key,'.') > 0) { $keys = splitx('.',$key); }
				if (strpos($key,';') > 0) { $keys = splitx(';',$key); }
				for ($r=0;$r < count($keys);$r++)
					{
						$sx .= '<keyword>';   
						$sx .= trata_xml($keys[$r]);
						$sx .= '</keyword>'.$cr; 
					}
    			$sx .= '</keywords>'.$cr;
				
				/* Resumo - 2 idioma */
//    			$sx .= '<keywords language="'.$lang2.'">'.$cr;
//				/* Palavras chave */
//				$key = ($article->article_2_keywords);
//				$keys = array();
//				if (strpos($key,'.') > 0) { $keys = splitx('.',$key); }
//				if (strpos($key,';') > 0) { $keys = splitx(';',$key); }
//				for ($r=0;$r < count($keys);$r++)
//					{
//						$sx .= '<keyword>';   
//						$sx .= trata_xml($keys[$r]);
//						$sx .= '</keyword>'.$cr; 
//					}
//    			$sx .= '</keywords>'.$cr;
				
  				$sx .= '</record>'.$cr;
				return($sx);
		}
		
	}
?>
