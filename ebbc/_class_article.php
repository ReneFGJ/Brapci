<?php
class article
	{
	var $journal_id;
	var $title;
	var $title_alt;
	var $autores;
	var $resumo;
	var $resumo_alt;
	var $keyword;
	var $keyword_alt;
	var $codigo;
	var $bdoi;
	var $pagi;
	var $pagf;
	var $idioma_1;
	var $idioma_2;
	var $issue;
	var $tipo;
	var $pdf;
	var $pdfc;
	var $tabela = 'brapci_article';
	
	var $page_load_type = 'C';
	
	function show_pdf()
		{
			$art = $this->codigo;
			$this->pdf = '';
			$sql = "select * from brapci_article_suporte
					where bs_article = '".$art."'
					and (bs_type = 'PDF') and (bs_status = 'A')
					";
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					$file = trim($line['bs_adress']);
					if (file_exists($file))
						{
							$this->pdf = $file;
						}
				}
			if (strlen($this->pdf) > 0)
			{
				$linka = ' onclick="$(\'#pdf\').toggle();" ';	
				$sx = '<div id="pdf">
						<div id="pdf_frame">
							<iframe SRC="'.$this->pdf.'" style="width: 99%; height: 95%;"></iframe>
						</div>
					<div style="float: right;">
						<span id="download" '.$linka.' class="link">fechar</span> | 
						<span id="download" '.$linkb.' class="link">download</span>&nbsp;</div>
					</div>
				';
			}
			return($sx);
		}

	function seek_google()
		{
			$link = "http://scholar.google.com.br/scholar?q=".utf8_encode($this->title);
			$sx .= '[ <A HREF="'.$link.'" target="_new'.date("Ymdhis").'">'.msg('seek_google_scholar').'</a> ]';
			return($sx);
		}
	
	function referencia_abnt($line)
		{
			$local = $line['ar_local'];
			$ano = $line['ar_ano'];
			$vol = $line['ar_vol'];
			$nr = $line['ar_nr'];
			$journal = $line['Journal_Title'];
			$autors = $line['Author_Analytic'];
			$title = $line['ar_titulo_1'];
			$sx = $autors.'. ';
			$sx .= $title.'. ';
			$sx .= '<B>'.$journal.'</B>';
			$sx .= ', v. '.$vol;
			$sx .= ', '.$line['Issue_ID'];
			$sx .= ', '.$ano;
			return($sx);
		}
	
	function article_arquivos()
		{
			global $repositorio;
			
			$art = $this->codigo;
			$sql = "select * from brapci_article_suporte
					where bs_article = '".$art."'
					and (bs_type = 'URL' or bs_type = 'PDF')
					";
			$rlt = db_query($sql);
			$sx = '<table width="100%" class="tabela00">';
			$sx .= '<TR><TH>Arquivos dispon�veis
						<TH>Status
						<TH>Tipo';
			$sxt = '</table>';
			$id = 0;
			while ($line = db_read($rlt))
			{
				$id++;
				$link = '<A HREF="'.trim($line['bs_adress']).'" target="_new" class="link">';
				$xlink = substr(trim($line['bs_adress']),0,1);
				if ($xlink == '/')
					{
						$link = $repositorio.trim($line['bs_adress']);
						$sx = '';
						$sx .= '<A HREF="'.$link.'" target="_blank" class="submit-geral">DOWNLOAD PDF</A>';
						$sxt = '';		
					} else {
						$sx .= '<TR>';
						$sx .= '<TD class="tabela01">';
						$sx .= $link.$line['bs_adress'].'</A>';
						$sx .= '<TD class="tabela01" align="center">';
						$sx .= $line['bs_status'];
						$sx .= '<TD class="tabela01" align="center">';
						$sx .= $line['bs_type'];
					}
				
			}
			$sx .= $sxt;
			if ($id == 0) { $sx = ''; } 
			return($sx);
		}
	
	function article_referencias()
		{
			$art = $this->codigo;
			$sql = "select * from mar_works where m_work = '".$art."'";
			$rlt = db_query($sql);
			$sx .= '<P class="resumo">';
			$id = 0;
			while ($line = db_read($rlt))
				{
					$id++;
					$sx .= $line['m_ref'];
					$sx .= '<BR><BR>';
				}
			$sx .= '</P>';
			if ($id > 0)
				{
					$sx = '<BR><BR><B>Refer�ncias</B><BR><BR>'.$sx;
				}
			return($sx);
		}
	function updatex()
			{
				$c = 'ar';
				$c1 = 'id_'.$c;
				$c2 = $c.'_codigo';
				$c3 = 10;
				$sql = "update ".$this->tabela." set $c2 = lpad($c1,$c3,0) where $c2='' ";
				$rlt = db_query($sql);
			}	
	
	function cp_new()
		{
			global $dd;
			if (strlen($dd[4])==0) { $dd[4] = 'en'; }
			$cp = array();
			array_push($cp,array('$H8','id_ar','',False,True));
			array_push($cp,array('$HV','ar_journal_id',strzero($dd[1],7),True,True));
			array_push($cp,array('$HV','ar_edition',strzero($dd[2],7),True,True));

			array_push($cp,array('$T80:6','ar_titulo_1',msg('work_title'),True,True));
			array_push($cp,array('$O pt_BR:Portugu�s&us:Ingl�s&es:Espanhol','ar_idioma_1',msg('idioma'),True,True));
			array_push($cp,array('$O us:Ingl�s&pt_BR:Portugu�s&es:Espanhol','ar_idioma_2',msg('idioma').' '.msg('alternative'),True,True));
			array_push($cp,array('$HV','ar_codigo','',False,True));
			array_push($cp,array('$HV','ar_status','@',True,True));
			array_push($cp,array('$Q se_descricao:se_codigo:select * from brapci_section order by se_descricao','ar_tipo',msg("session"),True,True));
			return($cp);
			
		}
	
	function le($id)
		{
			$sql = "select * from ".$this->tabela." where id_ar = ".round($id);
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					$this->title = $line['ar_titulo_1'];
					$this->title_alt = $line['ar_titulo_2'];
					$this->resumo = $line['ar_resumo_1'];
					$this->resumo_alt = $line['ar_resumo_2'];
					$this->keyword = $this->recupera_keywords($line['ar_codigo'],$line['ar_idioma_1']);
					$this->keyword_alt = $this->recupera_keywords($line['ar_codigo'],$line['ar_idioma_2']);
					$this->codigo = $line['ar_codigo'];
					$this->bdoi = $line['article_bdoi'];
					$this->pagi = $line['ar_pg_inicial'];
					$this->pagf = $line['ar_pg_final'];					
					$this->idioma_1 = $line['ar_idioma_1'];					
					$this->idioma_2 = $line['ar_idioma_2'];
					$this->issue = $line['ar_edition'];
					$this->tipo = $line['ar_tipo'];
					$this->journal_id = $line['ar_journal_id'];
					$this->autores = $this->autores($line['ar_codigo']);
					$this->line = $line;
					}
			return(1);
		}

	function autores()
		{
			global $base;
			$sql = "select * from brapci_article_author
					inner join brapci_autor on ae_author = autor_codigo
					left join instituicoes on ae_instituicao = inst_codigo 
					where ae_article = '".$this->codigo."'  
					order by ae_position
			 ";
			 $autores = "";
			 $rlt = db_query($sql);
			 $bio = '';
			 $xbio = '';
			 $id = 0;
			 while ($line = db_read($rlt))
			 	{
			 		if (strlen($autores) > 0) { $autores .= '; '; }
			 		$id++;
					$bio = '';
					if ($line['ae_aluno']=='1') {$bio .= msg('Student').'. '; }
					if ($line['ae_professor']=='1') {$bio .= msg('Teacher').'. '; }
					if ($line['ae_ss']=='1') {$bio .= msg('Stricto Sensu').'. '; }
					if ($line['ae_mestrato']=='1') {$bio .= msg('Master').'. '; }
					if ($line['ae_doutorado']=='1') {$bio .= msg('PhD').'. '; }
					if ($line['ae_profissional']=='1') {$bio .= msg('prof').'. '; }
					
					$bio .= ' '.trim($line['inst_nome']);

					$bio .= trim($line['autor_bio']);
					if (strlen($bio) > 5)
						{
						$xbio .= '<sup>'.$id.'</sup>'.$bio.'<BR>';
			 			if (strlen($autores) > 0) { $autores .= ', '; }
						$link = '<A HREF="#" alt = "'.$bio.'" title="'.$bio.'">';
						} else { $link = ''; }
			 		$autores .= trim($line['autor_nome']).'<sup>'.$link.$id.'</A></sup>';
					
			 	}
			if (strlen($autores) > 0) { $autores .= '.'; }
			return($autores.'<BR>'.$xbio);
		}
	function recupera_keywords($article,$idioma='')
		{
					$sql = "select * from brapci_article_keyword
							inner join brapci_keyword on kw_keyword = kw_codigo
							where kw_article = '".$article."'
							"; 						
					if (strlen($idioma) > 0) { $sql .= " and kw_idioma = '".$idioma."' "; }
					$rlt = db_query($sql);
					$keys = '';
					while ($line = db_read($rlt))
						{
							if (strlen($keys) > 0) { $keys .= '. '; }
							$keys .= trim($line['kw_word']);
						}			
					return($keys);
		}
		
	function display_keyword($idioma)
		{
			$keys = 'Palavras-chave:';
			if ($idioma == 'en') { $keys = 'Keywords:'; }
			return($keys);
		}
	function mostra()
		{
			$sx = '<h2>'.$this->title.'</h2>';
			$sx .= '<h3><I>'.$this->title_alt.'</I></h3>';
			
			$sx .= '<div class="autores">';
			$sx .= $this->autores;
			$sx .= '</div>';
			
			$sx.= '<BR><BR>';
			$sx .= '<div class="resumo">';
			$sx .= $this->show_pdf();
			$sx .= $this->resumo;
			$sx .= '</div>';
			$sx .= '<div class="keywords">';
			$sx .= '<B>'.$this->display_keyword($this->idioma_1).'</B> ';
			$sx .= $this->keyword;
			$sx .= '</div>';

			$sx.= '<BR><BR>';
			$sx .= '<div class="resumo">';
			$sx .= $this->resumo_alt;
			$sx .= '</div>';
			$sx .= '<div class="keywords">';
			$sx .= '<B>'.$this->display_keyword($this->idioma_2).'</B> ';
			$sx .= $this->keyword_alt;
			$sx .= '</div>';
			$sx.= '<BR><BR>';

			return($sx);
		}
		
	function article_issue($id)
		{
			global $db_public, $db_base;
			$ref = new referencia;
			$sql = "select * from ".$this->tabela."
					left join brapci_journal on jnl_codigo = ar_journal_id
					left join brapci_edition on ed_codigo = ar_edition
					left join brapci_section on se_codigo = ar_tipo 
					left join ".$db_public."artigos on ".$this->tabela.".ar_codigo = artigos.ar_codigo
					where ar_edition = '".strzero(round($id),7)."' and ar_pg_inicial <> ''
					order by se_ordem, ar_pg_inicial ";
			$rlt = db_query($sql);
			$sx .= '<table width="99%" class="lt1" cellpadding=0 cellspacing=2 >';
			$xsection = 'x';
			while ($line = db_read($rlt))
				{
					$this->journal_id = $line['ar_journal_id'];
					$section = trim($line['se_descricao']);
					if ($xsection != $section)
						{
							$sx .= '<TR><TD class="lt1" class="issue_td"><B>'.$section.'</B>';
							$xsection = $section;
						}
					$sx .= '<TR>';
					$sx .= '<TD>';
					$sx .= $ref->mostra_artigo_lista($line);
					$pag = '';
					$pagi = $line['ar_pg_inicial'];
					$pagf = $line['ar_pg_final'];
					if (strlen($pagi) > 0) 
					{
						if (strlen($pagf) > 0)
							{
								$pag = 'p. '.$pagi.'-'.$pagf;
							} else {
								$pag = 'p. '.$pagi;
							}
					}
					$sx .= '<TD><nobr>'.$pag;
				}
			$sx .= '</table>';				
			return($sx);
		}	
	function page_load($page)
		{
		global $host_install;
		if ($this->page_load_type != 'F')
			{	
			$ch = curl_init();
			curl_setopt ($ch, CURLOPT_URL, $page);
			curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 5);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
			$contents = curl_exec($ch);
			if (curl_errno($ch)) {
  				echo curl_error($ch);
  				echo "\n<br />";
  				$contents = '';
				$this->ok = 0;
			} else {
		  		curl_close($ch);
				$this->ok = 1;
				}
			if (!is_string($contents) || !strlen($contents)) {
				echo "Failed to get contents.";
				$contents = '';
				$this->ok = 0;
				}
			} else {
				$rlt = fopen($page,'r+');
				$contents = '';
				while (!(feof($rlt)))
					{
						$contents .= fread($rlt,1024);
					}
		  		fclose($rlt);
				$this->ok = 1;
			}
			
			

		return($contents);
	}
			
	function article_list($line)
		{
			$sx = '';
			$sx .= '<TR>';
			$sx .= '<TD>';
			$sx .= $line['ar_titulo_1'];
			
			return($sx);
		}
	
	function checkdir($dir)
		{
			$dir = troca($dir,'/',';');
			$fld = splitx(';',$dir);
			$dir = '';
			for ($r=0;$r < count($fld);$r++)
				{
					if ($r > 0) { $dir .= '/'; }
					$dir .= $fld[$r];
					if (!(is_dir($dir)))
						{ mkdir($dir); }
				}
		}
		
	function coleta_e_salva_pdf($url,$article,$id,$journal)
		{
			$dt = date("Ymd");
			$url = trim($url);
			$filename='../_repositorio/'.substr($dt,0,4)."/".substr($dt,4,2);
			
			$this->checkdir($filename);
			$filename .= '/pdf_'.checkpost($id).'_'.strzero($id,7).'.pdf';
			$file_name .= substr($filename,3,strlen($filename));
			
			$sx = $this->page_load($url);
			
			$tipo = substr($sx,1,3);
			
			if ($tipo == 'PDF')
				{
					$rlt = fopen($filename,'w');
					fwrite($rlt,$sx);
					fclose($rlt);
					$data = date("Ymd");
					
					$sql = "insert into brapci_article_suporte
							(
								bs_status, bs_adress, bs_type,
								bs_article, bs_update, bs_journal_id
							) values (
								'A','$file_name','PDF',
								'$article',$data,'$journal'
							) 
							";							
					$rlt = db_query($sql);
					return(1);
				}
			$this->pdfc = $sx;
			return(0);
			
		}
		
	function inport_pdf($id=0)
		{
			$sql = "select count(*) as total from brapci_article_suporte 
						where bs_type='PDF' and bs_status = '@'
						$wh
						";
			$rlt = db_query($sql);
			while ($line = db_read($rlt))
				{
					echo '<BR>Faltam '.$line['total'].' PDF para coletar';
					echo '<BR>';
				}
			
			$sql = "select * from brapci_article_suporte where bs_type='PDF' and bs_status = '@' limit 1";
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					$dt = $line['bs_adress'];
					$id = $line['id_bs'];
					$sql = "update brapci_article_suporte 
							set bs_status = '!' 
							where id_bs = ".$id;
					$xxx = db_query($sql);
					
				$filename='_repositorio/'.substr($dt,1,4)."/".substr($dt,6,2);

				$this->checkdir($filename);
				$filename .= '/pdf_'.checkpost($id).'_'.strzero($id,7).'.pdf';

				echo $filename;
								
				$site = 'http://www.brapci.ufpr.br/download.php?dd0='.$id;
				
				if (!(file_exists($filename)))
				{			
					$sx = $this->page_load($site);
					
					if ((strpos($sx,'Repostando erro ao administrador') > 0) or (strlen($sx) < 1024))
						{
							echo '<BR><BR><HR width="30%"><font color="white"> ERRO - '.$id.'<HR width="30%">';
						} else {
							echo '<BR><BR><HR width="30%"><font color="green"> DOWNLOAD - '.$id.'<HR width="30%">';
							$rlt = fopen($filename,'w');
							fwrite($rlt,$sx);
							fclose($rlt);
							
							$sql = "update brapci_article_suporte 
									set bs_status = 'A' ,
									bs_adress = '".$filename."'
									where id_bs = ".$id;
							$rlt = db_query($sql);
						}
				} else {
					echo '<BR><BR><HR width="30%"><font color="blue"> SKIP - '.$id.'<HR width="30%">';				
							$sql = "update brapci_article_suporte 
									set bs_status = 'A' ,
									bs_adress = '".$filename."'
									where id_bs = ".$id;
							$rlt = db_query($sql);
				}
			}
		}
	}
?>
