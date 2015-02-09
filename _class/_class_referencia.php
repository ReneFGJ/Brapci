<?php
class referencia
	{
		var $ref_type;
		var $ref_type_name;
		
		function exportar_ref($line)
			{
				global $http;
				
				$sx = '<div id="export">'.chr(13);
				$sx .= '<fieldset><legend class="lt1">Exportar refer�ncias para</legend>';
				
				$link_abnt = 'style="cursor: pointer;" onclick="newxy2(\''.$http.'article_ref_show.php?dd0='.trim($line['ar_codigo']).'&dd1=ABNT\',800,300);" ';
				$sx .= '<IMG src="'.$http.'/img/logo/logo_abnt.png" height="20" border=0 '.$link_abnt.'>';
				
				$link_endnote = 'style="cursor: pointer;" onclick="newxy2(\''.$http.'article_ref_show.php?dd0='.trim($line['ar_codigo']).'&dd1=ENDNOTE\',800,300);" ';
				$sx .= '<IMG src="'.$http.'/img/logo/logo_endnote.gif" height="20" border=0 '.$link_endnote.'>';
				$sx .= '</fieldset>';
				$sx .= '</div>'.chr(13);
				return($sx);
			}
		function abnt($art)
			{
				$sx = '';
				$autores = $art->autores_row;
				for ($r=0;$r < count($autores);$r++)
					{
						if (strlen($sx) > 0) { $sx .= '; '; }
						$sx .= $autores[$r];
					}
				$sx .= '. ';

				$sx .= $art->title.'. ';
				$sx .= '<B>'.trim($art->line['jnl_nome']).'</B>';
				$vol = $art->line['ed_vol'];
				$nr = $art->line['ed_nr'];
				$ano = $art->line['ar_ano'];
				if (strlen($vol) > 0) { $sx .= ', v. '.$vol; }
				if (strlen($nr) > 0) { $sx .= ', n. '.$vol; }
				if (strlen($ano) > 0) { $sx .= ', '.$ano; }
				$sx .= '.';
				return($sx);
			}
		function show_ref($art,$type='ABNT')
			{
				switch ($type)
					{
					case 'ENDNOTE':
						$xml = $this->endnote($art);
						header ("Content-Type:text/xml");
						echo utf8_encode($xml);
						break;
					case 'ABNT':
						echo $this->abnt($art);
						break;						
					}
			}
		function type_of_work($type)
			{
				$sx = 'ARTICLE';
				switch ($type)
					{
						case 'E': 
							$this->ref_type_name = 'Conference Proceedings';
							$this->ref_type = '3';
							break;
						case 'J': 
							$this->ref_type_name = 'Journal Article';
							$this->ref_type = '0';
							break;		
						default:			
							$this->ref_type_name = 'Generic ';
							$this->ref_type = '31';
							break;		
					}
				return($sx);
			}
		/*
		 *  0 | Journal Article       
		 *  1 | Book                  
		 *  2 | Thesis                
		 *  3 | Conference Proceedings
		 *  4 | Personal Communication
 		 *  5 | Newspaper Article     
 		 *  6 | Computer Program      
 		 *  7 | Book Section          
 		 *  8 | Magazine Article      
 		 *  9 | Edited Book           
		 *  10 | Report                
		 *  11 | Map                   
		 *  12 | Audiovisual Material  
		 *  13 | Artwork               
		 *  14 | (not defined)
		 *  15 | Patent                
		 *  16 | Electronic Source     
		 *  17 | Bill                  
		 *  18 | Case                  
		 *  19 | Hearing               
		 *  20 | Manuscript            
		 *  21 | Film or Broadcast     
		 *  22 | Statute               
		 *  23 | (not defined)
		 *  24 | (not defined)
		 *  25 | Figure                
		 *  26 | Chart or Table        
		 *  27 | Equation              
		 *  28 | (not defined)
		 *  29 | (not defined)
		 *  30 | (not defined)
		 *  31 | Generic
		 */
		function endnote($art)
			{
				global $xml_endnote;
				$line = $art->line;
				$this->type_of_work($line['jnl_tipo']);
				
				if (strlen($abstract)==0) { $abstract = $art->resumo_alt; }
				
				$cr = chr(13).chr(10);
				if (!(isset($xml_endnote))) { $xml_endnote = 1; }
				if ($xml_endnote == 1)
					{
						$xml = '<?xml version="1.0"?>'.$cr;
						$xml .= '<XML>'.$cr;
						$xml .= '<RECORDS>';
					}
					$xml .= '<RECORD>'.$cr;
					$xml .= '<REFERENCE_TYPE>'.$this->ref_type.'</REFERENCE_TYPE>'.$cr;
					$xml .= '<REF-TYPE>'.$this->ref_type.'</REF-TYPE>'.$cr;
					ini_set('display_errors', 1);
					ini_set('error_reporting', 7);
        			$xml .= '<REFNUM>'.strzero($line['id_ar'],10).'</REFNUM>'.$cr;
        			$xml .= '<AUTHORS>'.$cr;

					for ($r=0;$r < count($art->autores_row);$r++)
						{
							
            				$xml .= '<AUTHOR>'.$art->autores_row[$r].'</AUTHOR>'.$cr;
						}
            		$xml .= '</AUTHORS>'.$cr;
        			$xml .= '<YEAR>'.$line['ed_ano'].'</YEAR>'.$cr;
        			$xml .= '<TITLE>'.trim($line['ar_titulo_1']).'</TITLE>'.$cr;
					$xml .= '<PERIODICAL>';
					$xml .= '<TITLE>'.trim($line['jnl_nome']).'</TITLE>';
					$xml .= '</PERIODICAL>'.$cr;
        			//$xml .= '<SECONDARY_AUTHORS>'.$cr;
            		//$xml .= '<SECONDARY_AUTHOR>lastname1, firstname1</SECONDARY_AUTHOR>'.$cr;
            		//$xml .= '<SECONDARY_AUTHOR>lastname1, firstname1</SECONDARY_AUTHOR>'.$cr;
        			//$xml .= '</SECONDARY_AUTHORS>'.$cr;
					if (strlen(trim($line['ar_titulo_2'])) > 0)
						{
        					$xml .= '<SECONDARY_TITLE>'.trim($line['jnl_nome']).'</SECONDARY_TITLE>'.$cr;
						}
        			$xml .= '<PLACE_PUBLISHED></PLACE_PUBLISHED>'.$cr;
        			$xml .= '<PUBLISHER>'.trim($line['jnl_nome']).'</PUBLISHER>'.$cr;
        			$xml .= '<VOLUME>'.trim($line['ed_vol']).'</VOLUME>'.$cr;
        			$xml .= '<NUMBER_OF_VOLUMES></NUMBER_OF_VOLUMES>'.$cr;
        			$xml .= '<NUMBER>'.trim($line['ed_nr']).'</NUMBER>'.$cr;
					 
					$pagi = $line['ar_pg_inicial'];
					$pagf = $line['ar_pg_final'];
					$pag = $art->show_page($pagi,$pagf);
					
        			$xml .= '<PAGES>'.$pag.'</PAGES>'.$cr;
        			$xml .= '<SECTION>'.$section.'</SECTION>'.$cr;
        			$xml .= '<DATE>'.trim($line['ed_nr']).'</DATE>'.$cr;
					
					$type = $this->ref_type_name;
        			$xml .= '<TYPE_OF_WORK>'.$type.'</TYPE_OF_WORK>'.$cr;
        			$xml .= '<ISBN>'.$line['jnl_issn'].'</ISBN>'.$cr;
        			$xml .= '<ORIGINAL_PUB>'.trim($line['jnl_nome']).'</ORIGINAL_PUB>'.$cr;
        			$xml .= '<REPRINT_EDITION></REPRINT_EDITION>'.$cr;
        			$xml .= '<REVIEWED_ITEM></REVIEWED_ITEM>'.$cr;
        			$xml .= '<CUSTOM1></CUSTOM1>'.$cr;
        			$xml .= '<CUSTOM2></CUSTOM2>'.$cr;
        			$xml .= '<CUSTOM3></CUSTOM3>'.$cr;
        			$xml .= '<CUSTOM4></CUSTOM4>'.$cr;
        			$xml .= '<CUSTOM5></CUSTOM5>'.$cr;
        			$xml .= '<CUSTOM6></CUSTOM6>'.$cr;
        			$xml .= '<ACCESSION_NUMBER></ACCESSION_NUMBER>'.$cr;
        			$xml .= '<CALL_NUMBER></CALL_NUMBER>'.$cr;
        			$xml .= '<LABEL></LABEL>'.$cr;
        			$xml .= '<KEYWORDS>'.$cr;
					$keys = $art->keyword_array;
					
					for ($r=0;$r < count($keys);$r++)
						{
            			$xml .= '<KEYWORD>'.$keys[$r].'</KEYWORD>'.$cr;
            			}
        			$xml .= '</KEYWORDS>'.$cr;
        			$xml .= '<ABSTRACT>'.$abstract.'</ABSTRACT>'.$cr;
        			//$xml .= '<NOTES>notes</NOTES>'.$cr;
        			$xml .= '<URL>'.$art->link.'</URL>'.$cr;
        			$xml .= '<AUTHOR_ADDRESS></AUTHOR_ADDRESS>'.$cr;
        			$xml .= '<CAPTION>'.trim($line['jnl_nome']).'</CAPTION>'.$cr;
    				$xml .= '</RECORD>'.$cr;
		
					$xml .= '</RECORDS>'.$cr;
					$xml .= '</XML>'.$cr;	
					
					$xml = troca($xml,'&','#amp;');
					$xml = troca($xml,'#amp;','&amp;');			
			return($xml);
			}
		function mostra_artigo_lista($line)
			{
				$titulo = trim($line['ar_titulo_1']);
				
				$pagi = trim($line['ar_pg_inicial']);
				$pagf = trim($line['ar_pg_final']);
				if (strlen($pagi) > 0)
					{
						$pag = $pagi;
						if (strlen($pagf) > 0) { $pag.= '-'.$pagf; } 
					} else {
						if (strlen($pagf) > 0) { $pag.= $pagf; }						
					} 
				if (strlen($pag) > 0) { $pag = 'p. '.$pag.''; }
				
				/* */
				$link = '<A HREF="article.php?dd0='.$line['id_ar'].'&dd90='.checkpost($line['id_ar']).'" class="link lt1" target="_new">';
				$pag = '';
				$sx = $link.$titulo.' '.$pag.trim($year).'</A>';
				$sx .= '<BR><i>'.$line['Author_Analytic'].'</A>';
				return($sx);
				
			}
		function mostra_referencia($line)
			{
				$titulo = trim($line['ar_titulo_1']);
				$journal = '<B>'.trim($line['jnl_nome_abrev']).'</B>';
				$vol = trim($line['ed_vol']);
				if (strlen($vol) > 0) { $vol = 'v. '.$vol; }
				$nr = trim($line['ed_nr']);
				if (strlen($nr) > 0) { $nr = 'n. '.$nr; }
				$year = trim($line['ed_periodo']);
				$pagi = trim($line['ar_pg_inicial']);
				$pagf = trim($line['ar_pg_final']);
				if (strlen($pagi) > 0)
					{
						$pag = $pagi;
						if (strlen($pagf) > 0) { $pag.= '-'.$pagf; } 
					} else {
						if (strlen($pagf) > 0) { $pag.= $pagf; }						
					} 
				if (strlen($pag) > 0) { $pag = 'p. '.$pag.', '; }
				
				$sx = $titulo.'. '.$journal.', '.$vol.' '.$nr.', '.$pag.trim($year);
				return($sx);
			}
	}
