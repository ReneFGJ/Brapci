<?php
class issue
	{
	var $tabela = 'issue';
	var $capa;
	var $legenda;
	var $sumary_tipe = 1;
	
	function publication_list($jid=0)
		{
			$ed = array();
			$sql = "select * from issue 
						where journal_id = ".round($jid)."
						and issue_published = 1 and issue_status = 'S'
						order by 	issue_year desc,
						 			issue_volume desc, 
						 			issue_number desc
						
			";	
			$rlt = db_query($sql);
			while ($line = db_read($rlt))
				{ array_push($ed,$line); }
			return($ed);
		}
	
	function issue_mostra($id)
		{
			global $art,$jid;
			$sql = "select * from issue 
						left join journals on journals.journal_id = issue.journal_id
						where id_issue = ".round($id)."
			";
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					$title = trim($line['jn_title']);
					$vol = trim($line['issue_volume']);
					$num = trim($line['issue_number']);
					$ano = trim($line['issue_year']);
					$this->capa = http.'public/'.$jid.'/capas/'.trim($line['issue_capa']);
					
					$sx .= $title;
					if (strlen($vol) > 0)
						{ $sx .= ', v.'.$vol; }
					if (strlen($num) > 0)
						{ $sx .= ', n.'.$num; }
					if (strlen($ano) > 0)
						{ $sx .= ', '.$ano.'.'; }
				}
			return('<h3>'.$sx.'</h3>');
		}
	
	function sumary($issue=0)
		{
			$issue = round($issue);
			
			$sql = "select * from issue
					inner join articles on article_issue = id_issue
					inner join sections on article_section = section_id
					where id_issue = $issue 
					order by seq, seq_area, article_seq
			";
			
			$rlt = db_query($sql);
			$secx = 'secao';
			$sx = '<table width="650" border=0 >';
			while ($line = db_read($rlt))
				{
					$ab = round($line['abstracts_disabled']);
					$sec = trim($line['title']);
					if ($secx != $sec)
						{
							$sx .= '<TR><TD>'.$this->session_show_name($sec);
							$secx = $sec; 
						}
					$sx .= '<TR><TD>';
					$sx .= $this->article_sumary_show($line,$ab);
				}
			$sx .= '</table>';
			$sx .= '
				<script>
				function abstractshow(id)
					{
						var pm = $("#it"+id);
						pm.toggleClass(\'td_minus\');
						if(pm.hasClass(\'td_minus\')){
							$("#it"+id).slideDown("slow");
						}else{
							$("#it"+id).slideUp("slow");
						}
					}
				</script>
			';
			return($sx);
		}
		
	function read_more($ln)
		{
			global $path;
			$sx = '<div class="pdf" style="width: 100px;">';
			$sx .= '<A HREF="'.http.'pb/index.php/'.$path.'?dd1='.$ln['id_article'].'&dd99=view&dd98=pb">';
			$sx .= '<nobr>'.msg("read_more");
			$sx .= '</A></div>';
			return($sx); 
		}
	function editar($ln)
	{
		global $edit_mode;
		if ($edit_mode==1)
			{
				$sx .= '&nbsp;<A HREF="#" onclick="newxy2(\''.http.'editora/article_ed.php?dd0='.$ln['id_article'].'\',800,600);" class="editmode">';
				$sx .= msg('editar');
				$sx .= '</A>';
			}	
		return($sx);	
	}

	function pdf_link($ln=0,$art_pdf='')
		{
			global $path;
			$sx = '<a target="_BLANK" ';
			$sx .= 'onclick="javascript:newxy(\'';
			$sx .= http.'index.php/'.$path;
			$sx .= '?dd1='.$ln['id_article'].'&dd99=pdf\',180,20);';
			$sx .= '" >'.$art_pdf.'</A>';
			
			$sx = '';
			$sql = "select * from articles_files where article_id = ".round($ln['id_article']);
			
			$rrr = db_query($sql);
			while ($line = db_read($rrr))
				{
					$tp = trim($line['fl_type']);
					$idio = trim($line['fl_idioma']);
					switch ($idio)
						{
						case 'pt_BT': $art_pdf = 'PDF (Português)'; break;
						case 'en_US': $art_pdf = 'PDF (English)'; break;
						default:
							$art_pdf = 'PDF (Português)'; break;
						}	
					if (strlen($sx) > 0) { $sx .= '<BR>'; }
					
					$sx .= '<a target="_BLANK" ';
					$sx .= 'onclick="javascript:newxy(\'';
					$sx .= http.'index.php/'.$path;
					$sx .= '?dd1='.trim($ln['id_article']).'&dd2='.trim($line['id_fl']).'&dd3='.trim($line['fl_idioma']).'&dd99=pdf\',180,20);';
					$sx .= '" ><nobr>'.$art_pdf.'</nobr></A>';					
				}			
			return($sx);
		}
	
	function article_sumary_show($ln,$ab)
		{
			global $edit_mode;	
			$sx = '';
			$id = trim($ln['id_article']);
			$art_title = trim(UpperCase($ln['article_title']));
			
			$art_abstract = $ln['article_abstract'];
			$art_keys = trim($ln['article_keywords']);
			$art_pages = trim($ln['article_pages']);
			$art_autor = mst_autor(trim($ln['article_author']),1);
			if ($edit_mode==1) { $art_autor .= '<BR>'.$this->editar($ln); }
			
			if (strlen($art_pages) > 0)
				{ $art_pages = '<td class="pags"><small>Págs&nbsp;'.$art_pages.'&nbsp;</small>'; }
			$art_pdf = 'PDF';
			
			if (strlen($art_keys) > 0)
				{
					$art_keys = '<div class="palavras" ><B>'.msg('keywords').'</B>: '.$art_keys.'</div>';
				} else {
					$art_keys = '';
				}
			
			$sx .= '
				<div class="artigo">
                    <div class="div_titulo">
                        <table width="100%" class="titulo_pdf" border=0 cellpadding=0 cellspacing=0>
                        	<tr>
                            <td class="td_titulo" onclick="abstractshow(\''.$id.'\');" >'.$art_title.'</td>
                            '.$art_pages.'
                            <td class="pdf" align="left">'.$this->pdf_link($ln,$art_pdf).'</td>
						</tr></table>
                    </div>
                    <div class="artigo_autor">'.$art_autor.'</div>
                                        
                    <div style="display: none;" class="resumo_palavra" id="it'.$id.'">';
			if ($ab==1) { $sx .= '<b>RESUMO</b><br>'; }
			$sx .= '
                        <table width="600">
                        	<TR><TD><div class="resumoz">
                        	'.$art_abstract.'
                        	</div>
                        </table>
                        
                        '.$art_keys.'
                        <BR>'.$this->read_more($ln).'
                    </div>
                </div>
			';
			return($sx);
		}

	function session_show_name($sec)
		{
			$sx .= '<h1>'.UpperCase($sec).'</h1>';
			return($sx);
		}
	
	function ultima_edicao_publicada($journal)
		{
			$sql = "select * from ".$this->tabela;
			$sql .= " where 
						and issue_published = 1 and issue_status = 'S'
						and journal_id = $journal
						order by issue_year desc, issue_volume desc, issue_number desc
			";
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					return($line['id_issue']);
				}
			return(-1);
		}
	
	}
?>
