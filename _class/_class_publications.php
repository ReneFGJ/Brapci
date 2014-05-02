<?php
class publications
	{
	var $autor_instituition;
	var $line;
	var $status;
	
	var $tabela = 'brapci_journal';
	
	function resume($jid)
		{
			$sql = "SELECT count(*) as total, cache_status 
						FROM oai_cache 
						WHERE cache_journal = '".strzero($jid,7)."'
						group by cache_status
						order by cache_status ";
			$rlt = db_query($sql);
			$sx = '<table align="right" width="100" class="tabela01">';
			
			while ($line = db_read($rlt))
				{
					$sta = $line['cache_status'];
					switch ($sta)
						{
						case '@': $txt = 'Coletar'; break;
						case 'A': $txt = 'Processamento';
								  $link = 'http://www.brapci.inf.br/man/oai_processar.php?dd1='.$jid;
								  $link = '<A HREF="'.$link.'">';
									 break;
						case 'B': $txt = 'Finalizados'; break;
						default: $txt = '?? - '.$sta; break;
						}
					$sx .= '<TR><TD align="center" class="lt0">'.$txt;
					$sx .= '<TR><TD align="center" class="lt4">'.$link.$line['total'].'</A>';
				}
			$link = 'http://www.brapci.inf.br/man/oai_harvesting.php?dd5=1&dd1='.$jid;
			$link = '<A HREF="'.$link.'" class="link">OAI-PMH Harvesting</A>';
			$sx .= '<TR><TD>'.$link;
			$sx .= '</table>';
			return($sx);
		}	
	
	/*
	 * Acoes para a publicacao
	 */
	 
	function acao_executa($acao,$verbo)
		{
			if ($verbo=='ALTERA_STATUS')
				{
					$sql = "update brapci_article set ar_status = '".$acao."' 
							where ar_codigo = '".$this->line['ar_codigo']."'					
					";
					$rlt = db_query($sql);
					redirecina(page().'?dd0='.$this->line['ar_codigo'].'&dd90='.checkpost($line['ar_codigo']));
				}
		}
	function acoes()
		{
			global $dd;
			
			$this->acao_executa($dd[10],$dd[11]);
			
			$status = $this->line['ar_status'];
			$line = $this->line;
			
			$acao = array();
			switch ($status)
				{
				case 'A':
						array_push($acao,array('B','Enviar para revisão'));
						array_push($acao,array('X','Cancelar trabalho'));
						break;
				case 'B':
						array_push($acao,array('F','Finalizar revisão'));
						array_push($acao,array('A','Devolver para edição'));
						array_push($acao,array('C','Enviar para o "limbo"'));
						array_push($acao,array('X','Cancelar trabalho'));
						break;
				case 'C':
						array_push($acao,array('F','Finalizar revisão'));
						array_push($acao,array('A','Devolver para edição'));
						array_push($acao,array('X','Cancelar trabalho'));
						break;
				default:
					break;
				}
			
			$sx = '';
			for ($r=0;$r < count($acao);$r++)
				{
					$sx .= '<input type="button" value="'.$acao[$r][1].'"
								onlick="window.location=\''.$acao[$r][0].'&dd11=ALTERA_STATUS&DD90='.checkpost($line['ar_codigo']).'\';"
								>&nbsp;';
				}
			return($sx);
		} 
	
	function cp()
		{
			$cp = array();
			array_push($cp,array('$H8','id_jnl','',False,True));
			array_push($cp,array('$H8','jnl_codigo','Codigo',False,True));
			
			array_push($cp,array('$A8','','Dados da publicação',False,True));
			array_push($cp,array('$S200','jnl_nome','Nome da publicação',True,True));
			array_push($cp,array('$S40','jnl_nome_abrev','Abreviatura',False,True));
			array_push($cp,array('$S14','jnl_issn_impresso','ISSN',False,True));
			array_push($cp,array('$S14','jnl_issn_eletronico','ISSN eletrônico',False,True));
			array_push($cp,array('$S20','jnl_patch','Atalho no sistema',False,True));
			
			$sql = "select * from brapci_journal_tipo where jtp_ativo = 1 order by jtp_ordem";
			array_push($cp,array('$Q jtp_descricao:jtp_codigo:'.$sql,'jnl_tipo','Tipo de publicação',True,True));
			
			array_push($cp,array('$A8','','Sobre a publicação',False,True));
			array_push($cp,array('$[1950-'.date("Y").']','jnl_ano_inicio','Ano inicial',False,True));
			array_push($cp,array('$[1950-'.date("Y").']','jnl_ano_final','Ano final',False,True));
			array_push($cp,array('$O 1:SIM&0:Descontinuada','jnl_vinc_vigente','Vigente',True,True));
			array_push($cp,array('$O A:Ativa&B:Descontinuada&X:Cancelada','jnl_status','Status',True,True));
			array_push($cp,array('$T60:7','jnl_obs','Observação',False,True));
			
			array_push($cp,array('$A8','','LINKS',False,True));
			array_push($cp,array('$S120','jnl_url','Link',False,True));
			
			
			array_push($cp,array('$A8','','OAI',False,True));
			array_push($cp,array('$S200','jnl_url_oai','OAI Link',False,True));
			array_push($cp,array('$S30','jnl_token','Codigo',False,True));
			array_push($cp,array('$O 1:SIM&0:NÃO','jnl_oai_from','Harvesting seletivo',True,True));
			return($cp);
		}
	
	function type($id)
		{
			$sql = "select * from brapci_journal_tipo where jtp_codigo = '".$id."'";
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					return(trim($line['jtp_descricao']));
				}
			return('');
		}
	
	function journal_name($id)
		{
			$sql = "select * from brapci_journal where
					jnl_codigo = '".strzero($id,7)."'";
			$rlt = db_query($sql);
			$sx = '';
			if ($line = db_read($rlt))
				{
					$sx = '<h2>';
					$sx .= trim($line['jnl_nome']);
					$sx .= '</h2>';
					$this->type = $line['jnl_tipo'];
				}
			return($sx);
				 
		}
	function section_option_form($tp='')
		{
			$sql = "select * from brapci_section
					where se_ativo = 1
					order by se_descricao 
					";
			$rlt = db_query($sql);
			while ($line = db_read($rlt))
				{
					$sel = '';
					if ($tp == trim($line['se_codigo'])) { $sel = 'selected'; }
					$sx .= '<option value="'.trim($line['se_codigo']).'" '.$sel.'>';
					$sx .= trim($line['se_descricao']);
					$sx .= '</option>';
					$sx .= chr(13);
				}
			return($sx);
		}
		
	function show_references($article)
		{
			global $editar;
			$sql = "select * from mar_works
					left join mar_tipo on mt_codigo = m_tipo
					 where m_work = '$article' 
					 and m_status <> 'X'
					 order by id_m, mt_ordem, m_ref ";
			$rlt = db_query($sql);
			$sx = '<div class="references" id="cited">';
			$sx .= '<B>Referências</B><BR><BR>';
			$tps = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
			$tpi = array('NC'=>0,
						'ARTIC'=>1,'LIVRO'=>2,'CAPIT'=>3,'ANAIS'=>4,
						'TESE'=>5,'DISSE'=>6,'TCC'=>7,
						'LINK'=>8,'NORMA'=>9,'RELAT'=>10,
						'JORNA'=>11,'LEI'=>12,''=>0);
						
			$xtipo='x';
			$tot = 0;
			while ($line = db_read($rlt))
				{
					$tot++;
					$work = trim($line['m_work']);
					$tipo = $line['m_tipo'];
					if ($xtipo != $tipo)
						{
							$sx .= '<h5>'.$line['mt_descricao'].'</h5>';
							$xtipo = $tipo;
						}
					$sta = trim($line['m_status']);
					$cor = '<font color="black">';
					$link = '';
					$linka = '';
					if ($editar==1)
						{
						$link = '<A HREF="#" onclick="newxy2(\'article_ref_edit.php?dd0='.$line['id_m'].'\',800,200);">';
						$linka = '</A>';							
						switch ($sta)
							{
							case 'Z': 	
								$cor = '<font color="red">'; break;
							}
						}
					
					$ids = round($tpi[$tipo]);
					$bdoi = trim($line['m_bdoi']);
					$ref = $line['m_ref'];
					$ref = troca($ref,'<','&lt;');
					$ref = troca($ref,'>','&gt;');
					$sx .= $link.$cor.$ref.'</font>'.$linka;
					$sx .= '('.$sta.')';
					if (strlen($bdoi) > 0)
						{
							$sx .= ' (';
							$sx .= '<font color="blue">';
							$sx .= $bdoi;
							$sx .= '</font>';
							$sx .= ')';
						}
					$sx .= '<BR><BR>';
					/* Calcula referencias */
					$tps[$ids] = $tps[$ids] + 1; 					
				}
			if ($editar==1)
					{
					$link = '<A HREF="#" class="link" onclick="newxy2(\'article_ref_edit.php?dd1='.$work.'\',800,200);">';
					
					$sx .= '<A name="cited">
							<BR>
							<a href="#cited" class="link" id="cited_edit">editar referências</A></font>';
					$sx .= ' | ';
					$sx .= $link.'<font class="link">nova referências</A></font>';
					}
			$sx .= '
					<script>
					$("#cited_edit").click(function(){
						$.ajax("article_cited_ajax.php?dd0='.round($this->article_id).'&dd89=cited_edit")
						 	.done(function(data) { $("#cited").html(data); })
							.fail(function() { alert("error"); });
					});						
					</script>
					';						

			$sx .= '<BR><BR>';
			$sx .= '<font class="lt0">';
			$sx .= ' ('.$tps[1].') '.msg('articles');
			$sx .= ', ('.$tps[2].') '.msg('books');
			$sx .= ', ('.$tps[3].') '.msg('books_capitulo');
			$sx .= ', ('.$tps[4].') '.msg('eventos');
			$sx .= ', ('.$tps[5].') '.msg('tese');
			$sx .= ', ('.$tps[6].') '.msg('dissertacao');
			$sx .= ', ('.$tps[7].') '.msg('tcc');
			$sx .= ', ('.$tps[0].') '.msg('nc');
			$sx .= '</div>';
			return($sx);
		}
	function show_author($article,$tp=1)
		{
			global $editar;
			$sx = '';
			/* Show Author */
			$sql = "select * from  brapci_article_author
					inner join brapci_autor on autor_codigo = ae_author 
					where ae_article = '".$article."' 
					order by ae_position
					";
			$rrr = db_query($sql);
			$sa = '';
			while ($rline = db_read($rrr))
				{
					if (strlen($sa) > 0) { $sa .= '; '; }
					$sa .= '<I>'.trim($rline['autor_nome_citacao']).'</I>';
				}
			$sx .= $sa;		
			if ($editar==1)
				{
				$sx .= '<BR><font class="link"><a href="#" class="link" id="author">editar autor</A></font>';
				}
			$sx .= '
					<script>
					$("#author").click(function(){
						$.ajax("article_author_edit_ajax.php?dd0='.round($this->article_id).'&dd89=author_edit")
						 	.done(function(data) { $("#authors").html(data); })
							.fail(function() { alert("error"); });
					});
					</script>
					';	
			return($sx);	
		}
	function show_files($art)
		{
			$sql = "select * from brapci_article_suporte 
					where bs_article = '$art'
			";
			$rlt = db_query($sql);
			$sx = '<table class="tabela00" width="100%">';
			$sx .= '<TR><TD width="20"><B>Arquivos</B>';
			while ($line = db_read($rlt))
				{
					$link = '<A HREF="'.trim($line['bs_adress']).'" target="_new">';
					if ($line['bs_type']!='URL') { $link = ''; }
					$sx .= '<TR>';
					$sx .= '<TD class="tabela01">';
					$sx .= $line['bs_type'];
					$sx .= '<TD class="tabela01">';
					$sx .= $link;
					$sx .= $line['bs_adress'];
					$sx .= '</A>';
				}	
			$sx .= '</table>';
			return($sx);		
						
		}
	function show_keywords_words($art,$idio)
		{
			$sql = "select * from brapci_article_keyword
					inner join brapci_keyword on kw_keyword = kw_codigo
					where kw_article = '$art' and kw_idioma = '$idio'
					order by kw_ord
					";
			$rlt = db_query($sql);
			$tot = 0;
			while ($line = db_read($rlt))
				{
					if ($tot > 0) { $sx .= '; ';}
					$sx .= trim($line['kw_word']);
					$tot++;
				}
			return($sx);
		}
	function updatex_keys()
		{
				$c = 'kw';
				$c1 = 'id_'.$c;
				$c2 = $c.'_codigo';
				$c5 = $c.'_use';
				$c3 = 10;
				$sql = "update brapci_keyword set 
						$c2 = lpad($c1,$c3,0) , 
						$c5 = lpad($c1,$c3,0)
						where $c2='' ";
				$rlt = db_query($sql);
				return(1);
		}
	function updatex()
		{
				$c = 'jnl';
				$c1 = 'id_'.$c;
				$c2 = $c.'_codigo';
				$c3 = 7;
				$sql = "update ".$this->tabela." set 
						$c2 = lpad($c1,$c3,0) 
						where $c2='' ";
				$rlt = db_query($sql);
				return(1);
		}		
	function consulta_keyword($key,$idio)
		{
					$key = trim(troca($key,'.',''));
					$key_asc = uppercasesql($key);					
					$sqlq = "select * from brapci_keyword 
							where kw_word_asc = '".$key_asc."'
							and kw_idioma = '".$idio."' ";
					$rlt = db_query($sqlq);
					if ($line = db_read($rlt))
						{
							$codigo = $line['kw_use'];
						} else {
							
							$sql = "insert into brapci_keyword 
									(kw_word, kw_word_asc, kw_codigo,
									kw_use, kw_idioma, kw_tipo,
									kw_hidden
									) values (
									'$key','$key_asc','',
									'','$idio','N',
									0
									)";
							$rlt = db_query($sql);
							$this->updatex_keys();
							$rlt = db_query($sqlq);
							$line = db_read($rlt);
							$codigo = $line['kw_use'];
						}
					return($codigo);			
		}
	function keyword_delete($art)
		{
			$sql = "delete from brapci_article_keyword
					where kw_article = '$art'
			";
			$rlt = db_query($sql);
			return(0);
		}
	
	function keyword_save($art,$keys,$idio)
		{
			$keys = troca($keys,',',';');
			$key = splitx(';',$keys.';');
			$keyx = array();
			$sql = "";
			$sql .= "insert into brapci_article_keyword 
							(kw_article, kw_keyword, kw_ord) 
							values ";			
			for ($r=0;$r < count($key);$r++)
				{
					$codigo = $this->consulta_keyword($key[$r],$idio);
					array_push($keyx,$codigo);
					if ($r > 0) { $sql .= ', '; }
					$sql .= "('$art','$codigo',$r) ".chr(13);				
				}
			if (count($key) > 0) { $rlt = db_query($sql); }
			return(1);
		}
	function show_keywords($art,$idio)
		{
			$sx = $this->keyword_title($idio).' ';
			$sx .= $this->show_keywords_words($art,$idio);
			return($sx);
		}
	function keyword_title($idi)
		{
			if ($idi == 'pt_BR') { return('<b>Palavras-chave</b>:');}
			if ($idi == 'en') { return('<b>Keywords</b>:');}
			if ($idi == 'us') { return('<b>Keywords</b>:');}
			return('abstract - '.$idi);
		}
	function abstract_title($idi)
		{
			if ($idi == 'pt_BR') { return('RESUMO');}
			if ($idi == 'en') { return('ABSTRACT');}
			if ($idi == 'us') { return('ABSTRACT');}
			return('abstract - '.$idi);
		}
		
	function js_editar()
		{
			global $editar;
			if ($editar == 1)
				{

				}
			return($sx);
		}
	function show_title($line)
		{
			global $editar;
			$sx .= '<div id="titles">';
			$sx .= '<font class="article_title">';
			$sx .= trim($line['ar_titulo_1']);
			$sx .= '</font>';
				
			/* Título alternativo */
			$tit_alt = trim($line['ar_titulo_2']);
			if (strlen($tit_alt) > 0)
				{
					$sx .= '<BR>';
					$sx .= '<font class="article_title_alt">';
					$sx .= $tit_alt;
					$sx .= '</font>';							
				}
			if ($editar==1)
				{
				$sx .= '<BR><font class="link"><a href="#" class="link" id="title_edit">editar</A></font>';
				}
			$sx .= '</div>';
			
			$sx .= '
					<script>
					$("#title_edit").click(function(){
						$.ajax("article_edit_ajax.php?dd0='.round($this->article_id).'&dd89=title_edit")
						 	.done(function(data) { $("#titles").html(data); })
							.fail(function() { alert("error"); });
					});
					</script>
					';			
			return($sx);			
		}
	function show_abstract($line)
		{
			global $editar;
					for ($r=1;$r <= 3;$r++)
					{
						$id = $line['ar_codigo'];
						$abst = trim($line['ar_resumo_'.$r]);
						$idio = trim($line['ar_idioma_'.$r]);

						if (strlen($abst) > 0)
							{
								$sx .= '<font class="abstract_title">'.$this->abstract_title($idio).'</font>';
								$sx .= '<BR>';
								
								$sx .= trim($abst);
						
								$sk = '<BR><BR>';
								$sk .= '<font class="keyword_title">'.$this->show_keywords($id,$idio).'</font>';
						
								$sx .= $sk;
								$sx .= '<BR><BR>';
							}						
					}
					if ($editar==1)
						{
						$sx .= '<BR><font class="link"><a href="#" class="link" id="abstract_edit">editar abstract</A></font>';
						}
					
			return($sx);								
		}
	function show_session($line)
		{
			$sx .= '<div id="session_name">';
			$sx .= trim($line['se_descricao']);
			$sx .= '</div>';
			return($sx);
		}

	function le($id)
		{
			$sql = "select * from brapci_article
						left join brapci_journal_tipo on ar_tipo = jtp_codigo
						left join brapci_section on ar_tipo = se_codigo
					where id_ar = ".round($id);
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					$this->id = $line['id_ar'];
					$this->line = $line;
					$this->status = $line['ar_status'];
					return(1);
				}			
			return(0);
		}
	function status($sta)
		{
			switch ($sta)
				{
				case 'A' : $sx .= 'Artigo recebido';
					break;
				case 'B' : $sx .= 'Em primeira revisão';
					break;
				case 'X' : $sx .= 'Cancelado';
					break;
				}
			return($sx);
		}
	function show_article($id)
		{
			global $editar;
			$issue = new issue;
			$this->le($id);
			$line = $this->line;
			
					$sx .= '<img src="../img/subm_bar_'.trim($line['ar_status']).'.png" title="'.$this->status($this->line['ar_status']).'" align="right">';

					$issue_id = $line['ar_edition'];
					$sx .= '<BR>';
					$sx .= $this->show_session($line);
					
					$sx .= $issue->issue_legend($issue_id);
					$sx .= $issue->issue_go($issue_id);

					$sx .= '<BR><BR>';
					
					
					/* titles */
					$sx .= '<font class="lt5">';
					$sx .= $this->show_title($line);
					$sx .= '</font>';

					/* Authores */
					$article = trim($line['ar_codigo']);
					$sx .= '<BR>';
					$sx .= '<div id="authors">';
					$sx .= $this->show_author($article);
					$sx .= '</div>';
					$sx .= '<BR><BR>';
					
					/* abstract */
					$sx .= '<div id="abstract">';
					$sx .= $this->show_abstract($line);
					$sx .= '</div>';
					$sx .= '
					<script>
					$("#abstract_edit").click(function(){
						$.ajax("article_abstract_edit_ajax.php?dd0='.round($this->article_id).'&dd89=abstract_edit")
						 	.done(function(data) { $("#abstract").html(data); })
							.fail(function() { alert("error"); });
					});
					</script>
					';
																
			$sx .= $this->js_editar();
			return($sx);
		}
	function mostra_article_row($line)
		{
			$link = '<A HREF="article_view.php?dd0='.$line['ar_codigo'].'">';
			$sx .= '<TR valign="top"><TD>';
			$sx .= '<div  class="article_lista">';
			$sx .= $link;
			$sx .= trim($line['ar_titulo_1']);
			$sx .= '</A>';
			$sx .= '<BR>';
		
			/* recupera autores */
			$article = trim($line['ar_codigo']);
			$sx .= $this->show_author($article);

			$sx .= '</div>';
			$sx .= '<TD align="right">';
			$pagi = round($line['ar_pg_inicial']);
			$pagf = round($line['ar_pg_final']);
			if (($pagi > 0) or ($pagf > 0))
				{
					$sx .= '<nobr>p. '.trim($line['ar_pg_inicial']);
					if ($pagf > 0) { $sx .= '-'.$pagf; }		
				}
			$sx .= '<TD>';
			$sta = $line['ar_status'];
			$sx .= '<img src="../img/process_status_'.$sta.'.png" height="20">';
			$sx .= '<BR><BR>';
			return($sx);
		}
	function list_issue_articles($issue)
		{
			$sql = "select *,
					CONVERT(ar_pg_inicial, SIGNED INTEGER) as pagi from brapci_article
					left join brapci_section on ar_tipo = se_codigo 
					where ar_edition = '$issue'
					order by se_ordem, ar_section, pagi
			";

			$sx = '<div class="sumario">';
			$sx .= '<table width="100%" class="lt1">';
			$rlt = db_query($sql);
			$xsec = 'x';
			$tot = 0;
			while ($line = db_read($rlt))
				{
					$tot++;
					$sec = trim($line['se_descricao']);
					if ($sec != $xsec)
						{
							$sx .= '<TR valign="top"><TD colspan=2>';
							$sx .= '<h3>'.$sec.'</h3>';
							$xsec = $sec;
						}
					$sx .= $this->mostra_article_row($line);
				}
			$sx .= '<TR><TD colspan=2>';
			$sx .= '<font class="lt0">'.msg('total').' '.$tot.' '.msg('works').'.';
			$sx .= '</table>';
			$sx .= '</div>';
			return($sx);
		}
	function list_issue($jnl)
		{
			$jnl = strzero($jnl,7);
			$sql = "select * from brapci_edition 
					where ed_journal_id = '".$jnl."' 
					order by ed_ano desc, ed_vol, ed_nr
					";
			$rlt = db_query($sql);
			$xano = "xxxx";
			$sx = '<table class="tabela00 lt1">';
			$sx .= '<TR><TH>'.msg('year').'<TH colspan=10>'.msg("issue");
			while ($line = db_read($rlt))
				{
					$link = '<A class="lt1" HREF="publication_issue.php?dd0='.$line['ed_codigo'].'">';
					$ano = trim($line['ed_ano']);
					if ($ano != $xano)
						{
							$sx .= '<TR>';
							$sx .= '<TD style="background-color: #C0C0FF">'.$ano;
							$xano = $ano;
						}
					$sx .= '<TD align="center">';
					$sx .= $link;
					$sx .= 'v. '.$line['ed_vol'].', ';
					$sx .= 'n. '.$line['ed_nr'];
					$sx .= '</A>';
				}
			$sx .= '</table>';
			echo $sx;
			return($sn);
			
		}
	function list_row($tp) 
		{
			$sql = "select * from brapci_journal 
					where jnl_tipo = '$tp' 
					and jnl_status = 'A'
					order by jnl_nome
					";
			$rlt = db_query($sql);
			$sx = '<table class="tabela00 lt1" style="width: auto;">';
			while ($line = db_read($rlt))
				{
					$sx .= $this->mostra_row($line);
				}		
			$sx .= '</table>';
			return($sx);
		}
	function mostra_row($line)
		{
			$link = '<A HREF="publications_details.php?dd0='.$line['id_jnl'].'">';
			$sx .= '<TR>';
			$sx .= '<TD>';
			$sx .= $link;
			$sx .= trim($line['jnl_nome']);
			$sx .= '</A>';
			return($sx);
		}
	}
?>
