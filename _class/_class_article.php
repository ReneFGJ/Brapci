<?php
class article
	{
	var $id_article;
	var $article_title;
	var $article_abstract;
	var $article_keywords;
	var $article_idioma;
	var $article_2_title;
	var $article_2_abstract;
	var $article_2_keywords;
	var $article_2_idioma;
	var $article_dt_envio;
	var $article_dt_aceite;
	var $article_pages;
	var $page_ini;
	var $page_fim;
	var $article_publicado;
	var $article_author;
	var $article_author_array;	
	var $article_issue;
	var $article_seq;
	var $article_section;
	var $journal_id;
	var $article_dt_revisao;
	var $article_cod;
	var $article_doi;
	var $journal_abbrev;
	var $journal_title;
	var $journal_ISSN;
	var $issue_vol;
	var $issue_nr;
	var $publicado;
	var $link_doi;
	
	var $modalidade;
	var $sigla;
	var $area;
	
	var $doi_prefix = '10.7213';		
	var $tabela = 'articles';
	
	function pdf()
		{
		global $path;
		$id = $this->id_article;
		$sx = '<DIV id="pdf_text">';
		$sx .= msg("full_text of").' PDF';
		$sx .= '</div>';
		$sx .= '</A>';
		
		$sx .= '
			<script>
				$("#pdf_text").click(function() 
					{
						var url = "'.http.'index.php/'.$path.'?dd99=pdf&dd1='.$id.'";
						window.open(url,\'newwin\',\'scrollbars=no,resizable=no,width=200,height=200,top=10,left=10\'); 
					});					
			</script>';		
		
		return($sx);
		}
	
	function reindex()
		{
			global $jid;
			$sql = "delete from index where journal_id = ".round($jid);
			$rrr = db_query($sql);			
			
			$sql = "select count(*) as total from articles where article_publicado <> 'X' and journal_id = $jid";
			$rlt = db_query($sql);
			$line = db_read($rlt);
			echo $line['total'].' total<HR>';
			
			$sql = "select * from articles where article_publicado <> 'X' and journal_id = $jid";
			$rlt2 = db_query($sql);
			while ($line = db_read($rlt2))
				{
					$this->index($line);		
				}
			echo 'FIM';
			$this->save_index();
			return(1);
		}
	
	function save_index($jid,$idioma='pt_BR')
		{
			$sql = "select ix_word, ix_asc from index
					where journal_id = $jid and ix_idioma = '$idioma'
					group by ix_word, ix_asc
					order by ix_asc
			";
			$rlt = db_query($sql);
			$sx = '';
			$sxa = 'X';
			$xltr = '';
			while ($line = db_read($rlt))
				{
					
					$termo = trim($line['ix_word']);
					if (strlen($termo) > 4)
					{
					$termo_asc = UpperCaseSQL(trim($line['ix_word']));
					
					$ltr = substr($termo_asc,0,1);
					if ($ltr != $xltr)
						{
							$xltr = $ltr;
							$sx .= '<h3>'.$ltr.'</h3>';
						}
					
					$sx .= '<A HREF="'.$page.'?dd1='.$termo_asc.'">'.$termo.'</A><BR>';
					}
				}
			return($sx);
		}
	
	function index($line)
		{
			global $jid;
			$article = $line['id_article'];
			$jid = $line['journal_id'];
			$keys1 = trim($line['article_keywords']);
			$id1 = trim($line['article_idioma']);
			
			$keys2 = trim($line['article_2_keywords']);
			$id2 = trim($line['article_2_idioma']);
			

			$sqlu = '';

			$keys1 = troca($keys1,'<I>','');
			$keys1 = troca($keys1,'</I>','');
			$keys1 = troca($keys1,'<B>','');
			$keys1 = troca($keys1,'</B>','');
			
			$keys1 = troca($keys1,'(','');
			$keys1 = troca($keys1,')','');
			$keys1 = troca($keys1,'.',';');
			$wds = splitx(';',$keys1);			
			for ($rt=0;$rt < count($wds);$rt++)
				{
				$wd = lowercase($wds[$rt]);
				if (strlen($wd) > 40)
					{
						$wd = substr($wd,0,40);
						while ((substr($wd,strlen($wd),1) != ' ') and (strlen($wd) > 0)) { $wd = substr($wd,0,strlen($wd)-1); }
					}
				$wd_asc = uppercasesql($wd);
				if (strlen($wd_asc) > 0)
					{
					$id = $id1;
					$sqlu .= "insert into index (ix_word,ix_article,ix_idioma,ix_asc,journal_id)
							values
							('$wd',$article,'$id','$wd_asc',$jid); ".chr(13);
					}
				}
			
			$keys2 = troca($keys2,'(','');
			$keys2 = troca($keys2,')','');
			$keys2 = troca($keys2,'.',';');
			$wds = splitx(';',$keys2);			
			for ($rt=0;$rt < count($wds);$rt++)
				{
				$wd = lowercase($wds[$rt]);
				if (strlen($wd) > 40)
					{
						$wd = substr($wd,0,40);
						while ((substr($wd,strlen($wd),1) != ' ') and (strlen($wd) > 0)) { $wd = substr($wd,0,strlen($wd)-1); }
					}
				$wd_asc = uppercasesql($wd);
				$id = $id2;
				if (strlen($wd_asc) > 0)
					{				
						$sqlu .= "insert into index (ix_word,ix_article,ix_idioma,ix_asc,journal_id)
							values
							('$wd',$article,'$id','$wd_asc',$jid); ".chr(13);
					}
				}
			echo '<BR>Article '.$article.' indexado.';
			if (strlen(trim($sqlu)) > 0)
			{
				echo ' (save)';
				$ttt = db_query($sqlu);
				echo $sqlu;
			}
			return(1);			
		}

	function google_metadados()
		{
			print_r($this);
				$cr = chr(13).chr(10);
				$hd = '<meta name="citation_title" content="The testis isoform of the phosphorylase kinase catalytic subunit (PhK-T) plays a critical role in regulation of glycogen mobilization in developing lung">';
				for ($r=0;$r < count($authors);$r++)
					{
					$hd .= $cr. '<meta name="citation_author" content="Liu, Li">';
					}
					 
				$hd .= $cr. '<meta name="citation_publication_date" content="1996/05/17">';
				$hd .= $cr. '<meta name="citation_journal_title" content="Journal of Biological Chemistry">';
				$hd .= $cr. '<meta name="citation_volume" content="271">';
				$hd .= $cr. '<meta name="citation_issue" content="20">';
				$hd .= $cr. '<meta name="citation_firstpage" content="11761">';
				$hd .= $cr. '<meta name="citation_lastpage" content="11766">';
				$hd .= $cr. '<meta name="citation_pdf_url" content="http://www.example.com/content/271/20/11761.full.pdf">';
		}
	function mostrar_artigo()
		{
			$sx .= '<h2>'.$this->article_title.'</h2>';
			$sx .= '<BR>';
			
			$sx .= '<div style="text-align:right;">';
			$sx .= mst_autor($this->article_author,2);
			$sx .= '</div>';
			
			$sx .= '<BR>';
			$sx .= '<div style="text-align:justify;">';
			$sx .= '<P>'.$this->article_abstract.'</P>';
			$sx .= '</div>';
			if (strlen($this->article_keywords) > 0)
				{
				$sx .= '<BR>';
				$sx .= '<B>Palavras-chave</B>: '.$this->article_keywords;
				}
				
			$sx .= '<BR>';
			
			$abts = $this->article_2_abstract;
			$abts = troca($abts,'Introduction:','<B>Introduction</B>:');
			$abts = troca($abts,'Objectives:','<B>Objectives</B>:');
			$abts = troca($abts,'Results:','<B>Results</B>:');
			$abts = troca($abts,'Conclusion:','<B>Conclusion</B>:');
			$abts = troca($abts,'Methods:','<B>Methods</B>:');
			
			$abts = troca($abts,'Objective:','<B>Objective</B>:');
			$abts = troca($abts,'Result:','<B>Result</B>:');
			$abts = troca($abts,'Conclusion:','<B>Conclusion</B>:');
			$abts = troca($abts,'Method:','<B>Method</B>:');
			
			$tit2 = $this->article_2_title;
			if (substr($tit2,0,2) == Substr(UpperCase($tit2),0,2))
			{ $tit2 = substr($tit2,0,1).Lowercase($tit2,1,strlen($tit2)); }
			
			if (strlen($tit2) > 0)
				{
				$sx .= '<BR><BR>';
				$sx .= '<h2>'.$tit2.'</h2>';
				$sx .= '<BR>';
				}
			
			if (strlen(trim($abts)) > 0)
				{
				$sx .= '<div style="text-align:justify;">';
				$sx .= '<P>'.$abts.'</P>';
				$sx .= '</div>';
				}
			
			if (strlen(trim($this->article_2_keywords)) > 5)
				{
				$sx .= '<BR>';
				$sx .= '<B>Keywords</B>: '.$this->article_2_keywords;
				$sx .= '<BR>';
				$sx .= '<BR>';
				}


			$sx .= mst_autor($this->article_author,3);
			$sx .= '<BR><BR>';
			
			return($sx);
		}
	

	function cp_review($tp)
		{
			$cp = array();
			array_push($cp,array('$H4','id_article','id_article',False,False,''));
			array_push($cp,array('$H8','','',False,True,''));
			array_push($cp,array('$H8','article_issue','Journal',True,True,''));
			array_push($cp,array('$H8','','',False,True,''));
			array_push($cp,array('$H8','','',False,True,''));
			array_push($cp,array('$H8','','',False,True,''));
			if ($tp=='1')
				{ array_push($cp,array('$T60:2','article_title','Título original',False,True,'')); }
			if ($tp=='2')
				{ array_push($cp,array('$T60:2','article_2_title','Título alternativ',False,True,'')); }
			if ($tp=='3')
				{ array_push($cp,array('$T60:15','article_abstract','Resumo',False,True,'')); }
			if ($tp=='4')
				{ array_push($cp,array('$T60:2','article_keywords','Palavras Chave',False,True,'')); }
			if ($tp=='5')
				{ array_push($cp,array('$T60:15','article_2_abstract','Resumo Inglês',False,True,'')); }
			if ($tp=='6')
				{ array_push($cp,array('$T60:2','article_2_keywords','Kwywords',False,True,'')); }
			if ($tp=='7')
				{ array_push($cp,array('$T60:2','article_title','Título original',False,True,'')); }
				
			//array_push($cp,array('$O 9:Não revisado&1:Revisado','article_revisado','Revisado',True,True,''));
			return($cp);
		}
	function cp_simple()
		{
			$cp = array();
			array_push($cp,array('$H4','id_article','id_article',False,False,''));
			array_push($cp,array('$B8','','Salvar',False,True,''));
			array_push($cp,array('$H8','article_issue','Journal',True,True,''));
			array_push($cp,array('$H8','','',False,True,''));
			array_push($cp,array('$H8','','',False,True,''));
			array_push($cp,array('$T60:2','article_title','Título original',False,True,''));
			array_push($cp,array('$T60:4','article_author','Autor',False,True,''));			
			array_push($cp,array('$T60:15','article_abstract','Resumo',False,True,'')); 
			array_push($cp,array('$T60:2','article_keywords','Palavras Chave',False,True,''));

			array_push($cp,array('$T60:2','article_2_title','Título alternativ',False,True,'')); 
			array_push($cp,array('$T60:15','article_2_abstract','Resumo Inglês',False,True,'')); 
			array_push($cp,array('$T60:2','article_2_keywords','Kwywords',False,True,''));
				
			array_push($cp,array('$O 9:Não revisado&1:Revisado','article_revisado','Revisado',False,True,''));
			array_push($cp,array('$S20','article_pages',msg('pages'),False,True,''));
			array_push($cp,array('$B8','','Salvar Dados',False,True,''));
			return($cp);
		}

	function cp_article($tp)
		{
			$cp = array();
			array_push($cp,array('$H4','id_article','id_article',False,False,''));
			array_push($cp,array('$H8','','',False,True,''));
			array_push($cp,array('$H8','article_issue','Journal',True,True,''));
			array_push($cp,array('$H8','','',False,True,''));
			array_push($cp,array('$H8','','',False,True,''));
			array_push($cp,array('$H8','','',False,True,''));
			if ($tp=='1')
				{ array_push($cp,array('$T60:2','article_title','Título original',False,True,'')); }
			if ($tp=='2')
				{ array_push($cp,array('$T60:2','article_2_title','Título alternativ',False,True,'')); }
			if ($tp=='3')
				{ array_push($cp,array('$T60:15','article_abstract','Resumo',False,True,'')); }
			if ($tp=='4')
				{ array_push($cp,array('$T60:2','article_keywords','Palavras Chave',False,True,'')); }
			if ($tp=='5')
				{ array_push($cp,array('$T60:15','article_2_abstract','Resumo Inglês',False,True,'')); }
			if ($tp=='6')
				{ array_push($cp,array('$T60:2','article_2_keywords','Kwywords',False,True,'')); }
			if ($tp=='7')
				{ array_push($cp,array('$T60:2','article_title','Título original',False,True,'')); }
				
			array_push($cp,array('$O 9:Não revisado&1:Revisado','article_revisado','Revisado',False,True,''));
			return($cp);
		}



	function resumos_semic($ano,$ori,$link)
		{
			$sql = "select * from ".$this->tabela."
				where article_author_pricipal = '$ori' 
			";
			$rlt = db_query($sql);
			echo '<h2>Trabalhos no SEMIC '.($ano+1).'</h2>';
			echo '<div style="text-align: justify;">';
			echo 'Prezado professor orientador, abaixo estão relacionado o(s) trabalho(s) que será(ão) apresentado no XX SEMIC. Observe os campos marcados com X (vermelho) carecem de complemento. Para acessar o trabalho para editá-lo, basta clicar em seu título, e na próxima tela, editar os campos necessários.
					<BR>Em caso de dúvida entre em contato com pibicpr@pucpr.br.
			';
			echo '</div>';
			echo '<table width="100%" class="lt1">';
			echo '<TR><TD>';
			while ($line = db_read($rlt))
				{
					$link = '<A HREF="semic_trabalho.php?dd0='.$line['id_article'].'">';
					$link2 = '<A HREF="../index.php/SEMIC20?dd1='.$line['id_article'].'&dd99=view" target="wn'.$link['id_article'].'">';
					$ft = array(0,0,0,0,0,0);
					$abst1 = trim($line['article_abstract']);
					$abst2 = trim($line['article_2_abstract']);
					$tit1 = trim($line['article_title']);
					$tit2 = trim($line['article_2_title']);
					$key1 = trim($line['article_keywords']);
					$key2 = trim($line['article_2_keywords']);
					
					$ft[0] = round(strlen($tit1) > 0);
					$ft[1] = round(strlen($abst1) > 0);
					$ft[2] = round(strlen($key1) > 0);
					
					$ft[3] = round(strlen($tit2) > 0);
					$ft[4] = round(strlen($abst2) > 0);
					$ft[5] = round(strlen($key2) > 0);
					echo '<TR>';
					echo '<TD colspan=3><B>'.$link.$line['article_title'];
					$fc = array();
					array_push($fc,'Título em portugues');
					array_push($fc,'Resumo em portugues');
					array_push($fc,'Palavras-chave em portugues');
					array_push($fc,'Título em Inglês');
					array_push($fc,'Resumo em Inglês');
					array_push($fc,'Palavras-chave em Inglês');
					for ($r=0;$r < count($ft);$r++)
						{
							$vl = $ft[$r];
							echo '<TR class="lt0"><TD width="15"><TD colspan=2>';
							if ($vl == 1)
								{ echo '<img src="img/icone_check.jpg">'; } else {
									echo '<img src="img/icone_nocheck.jpg">';
								}
							echo $fc[$r];
						}
					echo '<TR><TD colspan=4><center>';
					echo '<TR><TD colspan=4>'.$link2.'ver trabalho publicado</A>';
					echo '<HR width="50%" size=1>';
					
				}
			echo '</table>';
		}
	
	function mostrar()
		{
			global $editar;
			$page = page();
			$page = troca($page,'.php','_pop.php');
			$link1 = '<BR><A HREF="javascript:newxy2(\''.$page.'?dd91=1&dd0='.$this->id_article.'&dd90='.checkpost($this->id_article).'\',600,400);">editar</A>';
			$link2 = '<BR><A HREF="javascript:newxy2(\''.$page.'?dd91=2&dd0='.$this->id_article.'&dd90='.checkpost($this->id_article).'\',600,400);">editar</A>';
			$link3 = '<BR><A HREF="javascript:newxy2(\''.$page.'?dd91=3&dd0='.$this->id_article.'&dd90='.checkpost($this->id_article).'\',600,400);">editar</A>';
			$link4 = '<BR><A HREF="javascript:newxy2(\''.$page.'?dd91=4&dd0='.$this->id_article.'&dd90='.checkpost($this->id_article).'\',600,400);">editar</A>';
			$link5 = '<BR><A HREF="javascript:newxy2(\''.$page.'?dd91=5&dd0='.$this->id_article.'&dd90='.checkpost($this->id_article).'\',600,400);">editar</A>';
			$link6 = '<BR><A HREF="javascript:newxy2(\''.$page.'?dd91=6&dd0='.$this->id_article.'&dd90='.checkpost($this->id_article).'\',600,400);">editar</A>';
			$link7 = '<BR><A HREF="javascript:newxy2(\''.$page.'?dd91=7&dd0='.$this->id_article.'&dd90='.checkpost($this->id_article).'\',600,400);">editar</A>';
			

			$tit2 = trim($this->article_2_title);
			$abst2 = trim($this->article_2_abstract);
			$key2 = trim($this->article_2_keywords);
			
					if (strlen($abst2) == 0) { $abst2 = '<B><font color="red">(ERRO) Necessário informar o resumo em Inglês</font>'; }
					if (strlen($tit2) == 0) { $tit2 = '<B><font color="red">(ERRO) Necessário informar o título do trabalho em Inglês</font>'; }
					if (strlen($key2) == 0) { $key2 = '<B><font color="red">(ERRO) Necessário informar as palavras-chave em Inglês</font>'; }
			
			$sx .= '<table width="100%" class="lt1">
					<TR><TD>Título original
						<TD width="60" align="right">Protocolo
					<TR><TD class="lt3"><B>'.$this->article_title.$link1.'
						<TD class="lt3" align="right"><B>'.strzero($this->id_article,7).'
					<TR><TD>Título em Inglês
					<TR><TD class="lt3">'.$tit2.$link2.'
					<TR><TD>&nbsp;
					<TR><TD>Resumo
					<TR><TD class="lt3" colspan=2><P>'.$this->article_abstract.$link3.'
					<TR><TD class="lt3"><B>Palavras-Chave</B>: '.$this->article_keywords.$link4.'
					<TR><TD>&nbsp;
					<TR><TD>Abstract
					<TR><TD class="lt3" colspan=2><P>'.$abst2.$link5.'
					<TR><TD class="lt3"><B>Keyword:</B> '.$key2.$link6.'

			';
			return($sx);
		}
	
	function article_publicar($journal,$titulo,$autores,$seccao,$resumo,$keywords,$protocolo,$origem,$autor='',$issue)
		{
			$data2 = date("Ymd");
			$data = date("Y0801");
			$sql = "select * from ".$this->tabela."
				where article_protocolo_original = '$protocolo'
					and article_origem = '$origem'
				limit 1
			";
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					$sql = "update ".$this->tabela;
					$sql .= " set ";
					$sql .= " article_author = '".$autores."', 
							article_revisado = 9,
							article_title = '$titulo'
							where article_protocolo_original = '$protocolo'							
							and article_origem = '$origem'
					";
					$rlt = db_query($sql);
					echo "Atualizado";
				} else {
					$sql = "insert into ".$this->tabela;
					$sql .= "(
					article_title, article_abstract, article_keywords, article_idioma,
					article_2_title, article_2_abstract, article_2_keywords, article_2_idioma,
					article_3_title, article_3_abstract, article_3_keywords, article_3_idioma,
					
					article_dt_envio, article_dt_aceite, article_pages, 
					article_publicado, article_author, article_issue, 
					article_seq, article_section, journal_id,
					 
					article_dt_revisao, article_cod, article_doi, 
					article_author_pricipal, article_revisado, 
					article_protocolo_original, article_origem
					) values (
					'$titulo','$resumo','$keywords','pt_BR',
					'','','','',
					'','','','',
					$data,$data2,'',
					'S','$autores',$issue,
					1,$seccao,$journal,
					
					$data2,'','',
					'$autor',9,
					'$protocolo','$origem'
					)";
					$rlt = db_query($sql);
				}
				
		}

	function cp()
		{
		}
	function le($id)
		{
			if (strlen($id) > 0) { $this->id_article = $id; }
			$sql = "select * from articles ";
			$sql .= " inner join journals on journals.journal_id = articles.journal_id ";
			$sql .= " inner join issue on article_issue = id_issue ";
			$sql .= " where id_article = ".round(sonumero($this->id_article));
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					//print_r($line);
					$this->id_article = $line['id_article'];
					$this->article_title = nocr(trim($line['article_title']));
					$this->article_abstract = $line['article_abstract'];
					$this->article_keywords = trim($line['article_keywords']);
					$this->article_idioma = $line['article_idioma'];
					$this->article_2_title = nocr($line['article_2_title']);
					$this->article_2_abstract = $line['article_2_abstract'];
					$this->article_2_keywords = $line['article_2_keywords'];
					$this->article_2_idioma = $line['article_2_idioma'];
					$this->article_dt_envio = $line['article_dt_envio'];
					$this->article_dt_aceite = $line['article_dt_aceite'];
					$this->article_publicao = $line['article_publicao'];
					$this->article_author = $line['article_author'];
					$this->article_author_array = $this->article_autor($line['article_author']);
					$this->article_issue = $line['article_issue'];
					$this->article_seq = $line['article_seq'];
					$this->article_section = $line['article_section'];
					$this->journal_id = $line['journal_id'];
					$this->article_dt_revisao = $line['article_dt_revisao'];
					$this->article_cod = $line['article_cod'];
					$this->article_doi = trim($line['article_doi']);
					$this->journal_abbrev = trim($line['title']);
					$this->journal_title = trim($line['jn_title']);
					$this->article_publicado = trim($line['article_publicado']);
					$this->journal_ISSN = substr(trim($line['journal_issn']),0,9);
					$this->journal_path = trim($line['path']);
					$this->publicado = $line['issue_dt_publica'];
					$this->link_doi = $line['article_doi_link'];

					/* Pages */
					$this->article_pages = $line['article_pages'];
					$pag = $this->article_pages;
					if (strpos($pag,'-') > 0)
						{
							$pagi = substr($pag,0,strpos($pag,'-'));
							$pagf = substr($pag,strpos($pag,'-')+1,5);
							$this->page_ini = trim($pagi);
							$this->page_fim = trim($pagf);
						} else {
							$this->page_ini = trim($pag);
							$this->page_fim = '';
						}
					
					$this->issue_vol = trim($line['issue_volume']);
					$this->issue_nr = trim($line['issue_number']);
					$this->issue_year = trim($line['issue_year']);
					$this->issue = trim($line['id_issue']);
					
					$this->issue = trim($line['id_issue']);
					
					$this->modalidade = trim($line['article_modalidade']);
					$this->sigla = trim($line['article_ref']);
					$this->area = trim($line['article_area']);
					
					/** DOI */
					$dois = $this->article_doi;
					$dois = substr($dois,strpos($dois,'/')+1,strlen($dois));
					$this->doi_sufix = trim($dois); 
					
					$autores = $line['article_author'];
					$this->autores = ext_autor($autores);					
					
					return(1);
				} else {
					return(0);
				}
		}
	function article_autor($autores)
		{
			$at = new autor;
			$au = $at->mst_autor($autores,'1');
			return($au);
		}
	function acess_grafico()
		{
			$sx = '<meta http-equiv="content-type" content="text/html; charset=utf-8"/>'.chr(13);
			$sx .= '
			<script type="text/javascript" src="http://www.google.com/jsapi"></script>
    		<script type="text/javascript">
      			google.load(\'visualization\', \'1\', {packages: [\'charteditor\']});
    		</script>
		    <script type="text/javascript">
    			var wrapper;
	 
			    function init() {
      			wrapper = new google.visualization.ChartWrapper({
        			dataSourceUrl: \'https://spreadsheets.google.com/spreadsheet/tq?key=tnxuU73jT7eIL-aZke85e3A&pub=1&range=A1:E13\',
			        containerId: \'visualization\',
        			chartType: \'LineChart\'
      			});
      			wrapper.draw();
    			}
    
    			function openEditor() {
      			// Handler for the "Open Editor" button.
      			var editor = new google.visualization.ChartEditor();
      			google.visualization.events.addListener(editor, \'ok\',
        		function() {
          			wrapper = editor.getChartWrapper();
          			wrapper.draw(document.getElementById(\'visualization\'));
      			});
      			editor.openDialog(wrapper);
    			}
    			google.setOnLoadCallback(init);
		    </script>
		  	<body style="font-family: Arial;border: 0 none;">
    			<input type=\'button\' onclick=\'openEditor()\' value=\'Open Editor\'>
    			<div id=\'visualization\' style="width:600px;height:400px">
    		';
			return($sx); 
		}
	function doi_create()
		{
			global $dd;
			if ((1==1) and (strlen($this->artcile_doi)==0) and ($this->article_publicado == 'S'))
			{
				$doi = trim($this->journal_abbrev);
				$doi = troca($doi,' ','');
				$doi = troca($doi,'/','');
				$doi = LowerCaseSql($doi);
				
				if (substr($doi,strlen($doi)-1,1) != '.') { $doi .= '.'; }
				$doi .= $this->id_article;
				$this->article_doi = $this->doi_prefix.'/'.$doi;
				
				$sql = "update ".$this->tabela." set article_doi = '".$this->article_doi."' where id_article = ".$this->id_article;
				$rlt = db_query($sql);
				echo $sql;
			}
			return($doi); 
		}
		
	function artcile_doi_hhtp()
		{
			if (strlen($this->article_doi) > 0)
				{
					$doi = trim($this->article_doi);
					if (substr($doi,0,4) != 'http') { $doi = 'http://dx.doi.org/'.$doi; }
					$sx .= $doi;
				} else {
					$sx .= 'sem registro de DOI';
				}
			return($sx);
		}
	function artcile_doi()
		{
			if (strlen($this->article_doi) > 0)
				{
					$doi = trim($this->article_doi);
					$doi = troca($doi,'http://dx.doi.org/','');
					$sx .= $doi;
				} else {
					$sx .= 'sem registro de DOI';
				}
			return($sx);
		}
		
	
	function article_head()
		{
			//$this->doi_create();
		$sx .= '<table align="center" width="800" cellpadding="0" cellspacing="0">';
		$sx .= '<TR><TD>';
			$sx .= '<fieldset><legend>';
			$sx .= '&nbsp;<B>Article</B>&nbsp;';
			$sx .= '</legend>';
			$sx .= '<table align="center" width="800" cellpadding="0" cellspacing="0">';
				$sx .= '<TR><TD align=right colspan=4 >DOI: '.$this->artcile_doi();
				
				$sx .= '<TR valign="top"><TD class="lt0" colspan=4>TITLE</TD>';
				$sx .= '<TD align="right" rowspan=15><img src="img/icone_article.png" height="150" alt="" border="1"></TD>';
				$sx .= '<TR><TD class="lt2" colspan=4><B>'.$this->article_title.'</B></TD></TR>';
				
				/* Autores */
				$aut = 0;

				for ($r=0;$r < count($this->article_author_array);$r++)
					{
					if ($aut==0) { $sx .= '<TR><TD>Authors:</TD>'; } else { $sx .= '<TR><TD>&nbsp;</TD>'; }
					$aut++;
					$sx .= '<TD colspan="3">'.$this->article_author_array[$r][0];
//					$sx .= '<TD>'.$this->article_author_array[$r][1];
//					$sx .= '<TD>'.$this->article_author_array[$r][2];
					}
				
			$sx .= '</table>';
			$sx .= '</fieldset>';
		$sx .= '</TD></TR>';
		$sx .= '</table>';
		return($sx);
		}

	function updatex()
		{
		}
	}
?>