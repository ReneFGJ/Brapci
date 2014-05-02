<?php
class search
	{
	var $query;
	
	var $sessao = '001122';
		
	function mark_resume()
		{
			global $ssid,$db_public,$user;
			$sql = "select count(*) as total from ".$db_public."usuario_selecao 
				 where sel_sessao = '$ssid' and sel_ativo = 1";
				 
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					return($line['total']);
				}
			return(0);
		}
	
	function mark($art,$vl)
		{
			global $ssid,$db_public,$user;
			if ($vl == 'true') { $vl = 1; } else { $vl = 0; }
			$data = date("Ymd");
			$hora = date("H:i");
			$art = sonumero($art);
			$sql = "select * from ".$db_public."usuario_selecao 
				where sel_sessao = '$ssid' and sel_work = '$art' ";
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					$sql = "update ".$db_public."usuario_selecao set sel_ativo = $vl where id_sel = ".round($line['id_sel']);
					$rlt = db_query($sql);
				} else {
					$sql = "insert into ".$db_public."usuario_selecao 
						(sel_work, sel_sessao, sel_ativo,
						 sel_data, sel_hora, sel_usuario )
						 values
						('$art','$ssid',1,
						$data,'$hora','$user')
					";
					$rlt = db_query($sql);					
				}
		}
		
	function session()
		{
			$se = trim($_SESSION['ssid']);
			if (strlen($se) == 0)
				{
					$se = trim($_SERVER['HTTP_COOKIE']); 
					$se = sonumero($se);
					$se = substr($se,0,10); 
				}
			$_SESSION['ssid'] = $se;
			return($se);				
		}
	function trata_termo_composto($term)
		{
			$asp = 0;
			$sa = '';
			$term = troca($term,'\"','"');
			for ($r=0;$r < strlen($term);$r++)
				{
					$ca = substr($term,$r,1);
					if ($ca == '"') { if ($asp==0) { $asp = 1; } else {$asp = 0; } }
					else 
						{
						/* troca espaco por _ quando termo composto */
						if (($ca==' ') and ($asp==1)) { $ca = '_'; }
						$sa .= $ca;
						}
				}
			return($sa);
		}
	function where($term='',$field='')
		{
			$term = $this->trata_termo_composto($term);
			$term = troca($term,'"','');
			$term = troca($term,'\"','');
			$term = troca($term,' ',';');
			if (strlen($term) > 0) { $terms = splitx(';',$term); }
			$wh = '';
			for ($r=0;$r < count($terms);$r++)
				{
					if (strlen($wh) > 0) { $wh .= ' and '; }
					$wh .= "(".$field." like '%".$terms[$r]."%') ";
				}
			return($wh);
		}
		
	/* result article */	
	function result_total_articles($term='',$datai='',$dataf='')
		{
			global $db_public;
			$term = utf8_decode($term);
			
			$sessao = $this->sessao;
			
			$wh = $this->where(UpperCaseSql($term),'ar_asc');
			if (strlen($datai) > 0) { $wh .= ' and ar_ano >= '.$datai; }
			if (strlen($dataf) > 0) { $wh .= ' and ar_ano <= '.$dataf; }
			$sql = "select count(*) as total from ".$db_public."artigos 
					where ".$wh."
					";

			$rlt = db_query($sql);
			$line = db_read($rlt);
			$total = $line['total'];
			return($total);
		}	
	function result_article($term='',$datai='',$dataf='')
		{
			global $db_public;
			
			$total = $this->result_total_articles($term,$datai,$dataf);
			
			$term = utf8_decode($term);
			
			$sessao = $this->sessao;
			
			$wh = $this->where(UpperCaseSql($term),'ar_asc');
			if (strlen($datai) > 0) { $wh .= ' and ar_ano >= '.$datai; }
			if (strlen($dataf) > 0) { $wh .= ' and ar_ano <= '.$dataf; }
			$sql = "select * from ".$db_public."artigos 
					inner join brapci_journal on ar_journal_id = jnl_codigo
					left join ".$db_public."usuario_selecao on ar_codigo = sel_work and sel_sessao = '".$sessao."'
					where ".$wh."
					";
			$sql .= " order by ar_ano desc ";
			$sql .= " limit 100 offset 0 ";
			
			$rlt = db_query($sql);
			$this->query = $wh;
			
			$sx = chr(13).chr(10);
			/* total */
			$sx .= ', found '.$total;
			
			$sx .= '<div id="result_select">selection</div>';
			$sx .= '<table width="100%" class="lt1">';
			$id = 0;
			while ($line = db_read($rlt))
				{
					$sx .= $this->show_article_mini($line);
				}
			$sx .= '</table>';
			$sx .= chr(13).chr(10);
			$sx .= $this->js;
			$sx .= chr(13).chr(10);	
			return($sx);	
				
		}

	function result_article_key($term_code='')
		{
			global $db_public,$db_base;
			
			//$total = $this->result_total_articles($term,$datai,$dataf);
			
			$term = utf8_decode($term);
			
			$sessao = $this->sessao;
			
			$sql = "select * from ".$db_base."brapci_article_keyword 
					inner join ".$db_public."artigos on ar_codigo = kw_article
					where kw_keyword = '$term_code'
					";
			$sql .= " order by ar_ano desc ";
			$sql .= " limit 100 offset 0 ";
			
			$rlt = db_query($sql);
			
			$sx = chr(13).chr(10);
			/* total */
			$sx .= ', found '.$total;
			
			$sx .= '<div id="result_select">selection</div>';
			$sx .= '<table width="100%" class="lt1">';
			$id = 0;
			$wh = '';
			while ($line = db_read($rlt))
				{
					$sx .= $this->show_article_mini($line);
					if (strlen($wh) > 0) { $wh .= ' or '; }
					$wh .= " ar_codigo = '".trim($line['ar_codigo'])."' ";
				}
			$sx .= '</table>';
			$sx .= chr(13).chr(10);
			$sx .= $this->js;
			$sx .= chr(13).chr(10);
			$this->query = $wh;
				
			return($sx);	
				
		}
	function result_article_auth($term_code='')
		{
			global $db_public,$db_base;
			
			//$total = $this->result_total_articles($term,$datai,$dataf);
			
			$term = utf8_decode($term);
			
			$sessao = $this->sessao;
			
			$sql = "select * from ".$db_base."brapci_article_author 
					inner join ".$db_public."artigos on ar_codigo = ae_article
					where ae_author = '$term_code'
					";
			$sql .= " order by ar_ano desc ";
			$sql .= " limit 100 offset 0 ";
			
			$rlt = db_query($sql);
			
			$sx = chr(13).chr(10);
			/* total */
			$sx .= ', found '.$total;
			
			$sx .= '<div id="result_select">selection</div>';
			$sx .= '<table width="100%" class="lt1">';
			$id = 0;
			$wh = '';
			while ($line = db_read($rlt))
				{
					$sx .= $this->show_article_mini($line);
					if (strlen($wh) > 0) { $wh .= ' or '; }
					$wh .= " ar_codigo = '".trim($line['ar_codigo'])."' ";
				}
			$sx .= '</table>';
			$sx .= chr(13).chr(10);
			$sx .= $this->js;
			$sx .= chr(13).chr(10);
			$this->query = $wh;
				
			return($sx);	
				
		}
	function result_article_selected($session)
		{
			global $db_public,$db_base;
			
			//$total = $this->result_total_articles($term,$datai,$dataf);
			
			$term = utf8_decode($term);
			
			$sessao = $this->sessao;
			
			$sql = "select * from ".$db_public."usuario_selecao 
					inner join ".$db_public."artigos on ar_codigo = sel_work
					where sel_sessao = '$session'
					";
			$sql .= " order by ar_ano desc ";
			$sql .= " limit 100 offset 0 ";
			
			$rlt = db_query($sql);
			
			$sx = chr(13).chr(10);
			/* total */
			$sx .= ', found '.$total;
			
			$sx .= '<div id="result_select">selection</div>';
			$sx .= '<table width="100%" class="lt1">';
			$id = 0;
			$wh = '';
			while ($line = db_read($rlt))
				{
					$sx .= $this->show_article_mini($line);
					if (strlen($wh) > 0) { $wh .= ' or '; }
					$wh .= " ar_codigo = '".trim($line['ar_codigo'])."' ";
				}
			$sx .= '</table>';
			$sx .= chr(13).chr(10);
			$sx .= $this->js;
			$sx .= chr(13).chr(10);
			$this->query = $wh;
				
			return($sx);	
				
		}



		function show_article_mini($line)
			{
			global $id, $email;
			
			$ar = new article;
			$js = '<script>'.chr(13).chr(10);
			$js .= 'function abstractshow(ms)
					{ $(ms).toggle("slow"); }
					
					function mark(ms,ta)
					{
						var ok = ta.checked;
						$.ajax({
  							type: "POST",
  							url: "article_mark.php",
  							data: { dd1: ms, dd2: ok }
						}).done(function( data ) {
							$("#basket").html(data);
						});						
					}
					';
			$js .= '</script>'.chr(13).chr(10);
			$this->js = $js;
				
				
				$link = '<A HREF="article.php?dd0='.$line['ar_codigo'].'&dd90='.checkpost($line['ar_codigo']).'"
					 class="lt1"
					 target="_new'.$line['ar_codigo'].'"
					 >';
					
				$id++;
				$cod = trim($line['ar_codigo']);
					
				$sx .= '<TR valign="top">';
				$sx .= '<TD rowspan=1>';
					
					/* Marcacaoo */
				$selected = round($line['sel_ativo']);
				if ($selected == 1) { $selected = 'checked'; } else { $selected = ''; }
				$jscmd = 'onchange="mark(\'#mt'.trim($cod).'\',this);" ';
				$fm = '<input type="checkbox" name="dd" '.$jscmd.' '.$selected.'>';
				$sx .= $fm;
				
				$sx .= '<TD colspan=1>';
				$sx .= $id.'. ';
				$sx .= $link;
				$sx .= (trim(UpperCase($line['Article_Title'])));
				$sx .= '</A>';
				/* Show */
				
				$jscmd = 'onclick="abstractshow(\'#mt'.trim($cod).'\');" ';
								
				$sx .= '<BR>';
				$sx .= '<img src="img/icone_abstract.png" height="16" style="cursor: pointer;" id="it'.$cod.'" '.$jscmd.' align="left">';
				/* enviar por e-mail */
				if (isset($email))
					{ $sx .= $email->send_to_email($cod,16); }
				
				//$sx .= '<TD colspan=3 class="lt0">';
				$sx .= (trim($line['Author_Analytic']));
				
				/* Volume numero */
				$vol = trim($line['Volume_ID']);
				$num = trim($line['Issue_ID']);
				$pag = trim($line['Pages']);
				$ano = trim($line['ar_ano']);
				
				$v = '';
				if (strlen($vol) > 0) { $v .= ', '.$vol; }
				if (strlen($num) > 0) { $v .= ', '.$num; }
				if (strlen($pag) > 0) { $v .= ', '.$pag; }
				if (strlen($ano) > 0) { $v .= ', '.$ano; }
				
				
				//$sx .= '<BR>'.utf8_encode($ar->mostra_issue($line));
				
				$sx .= '<BR>';
				$sx .= '<B>'.(trim($line['Journal_Title'])).'</B>';
				$sx .= $v;
				
				$tipo = $line['jnl_tipo'];
				$sx .= ' ('.$this->tipo_publicacao($tipo).')';
				
				/* DIV */
				$hid = 'display: none;';
				$sx .= '<div style="text-align: justify; '.$hid.' color: #A0A0A0; line-height:130%; margin: 8px; 8px; 8px; 8px;" id="mt'.$cod.'">';
				$sx .= trim($line['ar_resumo_1']);
				$sx .= '<BR>';
				$keys = trim($line['Idoma']);
				$key = trim($line['ar_keyword_1']);
				$key = trim(troca($key,' /',','));
				if ($keys == 'pt_BR')
					{ $sx .= '<B>Palavras-chave</B>: '.$key; }
					else { $sx .= '<BR><B>Keywords</B>: '.$key; }
				$sx .= '</div>';
			
			return($sx);
		}
	function tipo_publicacao($tp)
		{
			$tipo = trim($tp);
			switch ($tipo)
				{
				case 'J':
					$sx = 'Artigo';
					break;
				case 'L':
					$sx = 'Livro';
					break;
				case 'T':
					$sx = 'Tese de Doutorado';
					break;
				case 'U':
					$sx = 'Dissertação de Mestrado';
					break;
				case 'D':
					$sx = 'Livro didático';
					break;
				case 'E':
					$sx = 'Anais de eventos';
					break;
				default:
					$sx .= $tipo;
					break;
				}
			return($sx);
		}
	function result_journals()
		{
			global $db_base,$db_public;
			$wh = $this->query;
			
			if (strlen($wh)==0) { $wh = '(1 = 1)'; 	}
			$sql = "select count(*) as total, jnl_nome_abrev from ".$db_public."artigos 
					inner join ".$db_base."brapci_journal on jnl_codigo = ar_journal_id
			where $wh ";
			
			$sql .= "group by jnl_nome_abrev order by total desc ";
			$rlt = db_query($sql);
			$sx .= '<table class="lt0" width="160">';
			$sx .= '<TR><Th>'.msg("journal").'<Th>'.msg('quant.');
			
			while ($line = db_read($rlt))
				{
					$sx .= '<TR>';
					$sx .= '<TD>';
					$sx .= trim($line['jnl_nome_abrev']);
					$sx .= '<TD align="center">';
					$sx .= trim($line['total']);
				}
			$sx .= '</table>';
			return($sx);
		}

	function result_year()
		{
			global $db_base,$db_public;
			$wh = $this->query;
			
			if (strlen($wh)==0) { $wh = '(1 = 1)'; 	}
			$sql = "select count(*) as total, ar_ano from ".$db_public."artigos 
			where $wh ";
			
			$sql .= "group by ar_ano order by ar_ano desc, total desc ";
			
			$rlt = db_query($sql);
			$sx .= '<table class="lt0" width="160">';
			$sx .= '<TR><Th>'.msg("year").'<Th>'.msg('quant.');
			
			while ($line = db_read($rlt))
				{
					$sx .= '<TR>';
					$sx .= '<TD align="center">';
					$sx .= trim($line['ar_ano']);
					$sx .= '<TD align="center">';
					$sx .= trim($line['total']);
				}
			$sx .= '</table>';
			return($sx);
		}
	function result_author()
		{
			global $db_base,$db_public;
			$wh = $this->query;
			
			if (strlen($wh)==0) { $wh = '(1 = 1)'; 	}
			$sql = "
			select autor_nome, autor_codigo, sum(total) as total from (
				select count(*) as total, ae_author from ".$db_public."artigos 
				inner join ".$db_base."brapci_article_author on ae_article = ar_codigo 
				where $wh
				group by ae_author 
			) as tabela 
				inner join ".$db_base."brapci_autor on ae_author = autor_codigo
				group by autor_nome, autor_codigo
				order by total desc, autor_nome
				limit 20
			";
					//echo $sql;
			$rlt = db_query($sql);
			$sx .= '<table class="lt0" width="160">';
			$sx .= '<TR><Th>'.msg("author").'<Th>'.msg('quant.');
			
			while ($line = db_read($rlt))
				{
					$sx .= '<TR>';
					$sx .= '<TD align="left">';
					$sx .= trim($line['autor_nome']);
					$sx .= '<TD align="center">';
					$sx .= trim($line['total']);
				}
			$sx .= '</table>';
			return($sx);
		}		
	function result_keyword()
		{
			global $db_base,$db_public;
			$wh = $this->query;
			
			if (strlen($wh)==0) { $wh = '(1 = 1)'; 	}
			$sql = "
			select kw_word,kw_keyword, sum(total) as total from (
				select count(*) as total, kw_keyword from ".$db_public."artigos 
				inner join ".$db_base."brapci_article_keyword on ar_codigo = kw_article
				where $wh
				group by kw_keyword 
			) as tabela 
				inner join ".$db_base."brapci_keyword on kw_keyword = kw_codigo
				where kw_idioma = 'pt_BR'
				group by kw_word, kw_codigo
				order by total desc, kw_word
				limit 40
			";

			$rlt = db_query($sql);
			$sx .= '<table class="lt0" width="160">';
			$sx .= '<TR><Th>'.msg("keyword").'<Th>'.msg('quant.');
			while ($line = db_read($rlt))
				{
						$sx .= '<TR>';
						$sx .= '<TD align="left">';
						$sx .= trim($line['kw_word']);
						$sx .= '<TD align="center">';
						$sx .= trim($line['total']);
				}
			$sx .= '</table>';
			$this->tags_cloud();
			return($sx);
		}	
	function tags_cloud()
		{
			global $db_base,$db_public;
			$wh = $this->query;
			
			if (strlen($wh)==0) { $wh = '(1 = 1)'; 	}
			$sql = "
			select kw_word,kw_keyword, sum(total) as total from (
				select count(*) as total, kw_keyword from ".$db_public."artigos 
				inner join ".$db_base."brapci_article_keyword on ar_codigo = kw_article
				where $wh
				group by kw_keyword 
			) as tabela 
				inner join ".$db_base."brapci_keyword on kw_keyword = kw_codigo
				where kw_idioma = 'pt_BR'
				group by kw_word, kw_codigo
				order by kw_word
			";

			$rlt = db_query($sql);
			$id = 0;
			$ntag='';
			$sz = 20;
			$ntag = '<BR><BR><BR>
					<div style="float: clear;">';
			while ($line = db_read($rlt))
				{
					$size = round(log($line['total'])*10);
					$ntag .= '<div style="float: left">';
					$ntag .= '&nbsp<font style="font-size: '.$size.'pt;">'.trim($line['kw_word']).'</font> ';
					$ntag .= '</div>';
				}
			$ntag .= '</div>
						<BR><BR><BR><BR><BR>
						<div style="float: clear;"></div>';
			$this->tag = $ntag;
			return($sx);			
		}	
	function busca_form()
		{
			global $dd,$messa;
			$chk1='checked';
			$sx = '';
			$sx .= '<form method="get" action="'.page().'">';
			$sx .= '<table width="98%" class="lt1" cellpadding=0 cellspacing=0 >';
			$sx .= '<TR><TD>';
			$sx .= msg('search_caption');
			$sx .= '<BR>';
			$sx .= '<textarea cols=80 rows=5 id="search" name="dd2" />';
			$sx .= $dd[2];
			
			$sx .= '</textarea>';
			$sx .= '<BR>';
			$sx .= '<input type="radio" name="dd3" id="typeid1" class="tp1" value="1" '.$chk1.'>'.msg('search_all').'&nbsp;&nbsp;&nbsp;';
			$sx .= '<input type="radio" name="dd3" id="typeid2" class="tp1" value="2" '.$chk2.'>'.msg('search_author').'&nbsp;&nbsp;&nbsp;';
			$sx .= '<input type="radio" name="dd3" id="typeid3" class="tp1" value="3" '.$chk3.'>'.msg('search_title').'&nbsp;&nbsp;&nbsp;';
			$sx .= '<input type="radio" name="dd3" id="typeid4" class="tp1" value="4" '.$chk3.'>'.msg('search_abstract').'&nbsp;&nbsp;&nbsp;';
			$sx .= '<input type="radio" name="dd3" id="typeid5" class="tp1" value="5" '.$chk3.'>'.msg('search_keywords').'&nbsp;&nbsp;&nbsp;';
			
			/* Delimitacao por data */
			$dtf = ''; for ($r=1900;$r <= date("Y");$r++)
				{
					$chk = '';
					if ($dd[10]==$r) { $chk = 'selected'; }
					$dtf .= '<option value="'.$r.'" '.$chk.'>'.$r.'</option>'; 
				}
			$dti = '<select name="dd10" id="typeid10">'.$dtf.'</select>';

			$dtf = ''; for ($r=date("Y");$r>=1972;$r--)
				{
					$chk = '';
					if ($dd[11]==$r) { $chk = 'selected'; }					
					$dtf .= '<option value="'.$r.'" '.$chk.'>'.$r.'</option>'; 
				}

			$dtf = '<select name="dd11" id="typeid11">'.$dtf.'</select>'; 
			
			$sx .= '<div id="delimit_year">
					'.msg('publicacao_de').' '.$dti.' '.msg('ate').' '.$dtf.'
					</div>
			';

			$sx .= '<BR>';
			$sx .= '<BR>';			
			$sx .= '<input type="submit" name="acao" id="search_bt" value="'.msg("bt_search").'">';
			$sx .= '</form>';
				
			//$sx = utf8_encode($sx);
			
			if (strlen($dd[2]) > 0)
			{			
				$sr .= $this->result_search();
				$sx .= $this->realce($sr,$dd[2]); 
			}		
			return($sx);
		}

		function realce($txt,$term)
			{
			
			$term = troca($term,' ',';');
			$term = troca($term,chr(13),';');
			$term = troca($term,chr(10),'');
			$term = splitx(';',$term);
			 
			$txt = ' '.$txt;
			$txta = uppercasesql($txt);
			for ($rx=0;$rx < count($term); $rx++)
				{
				if (strlen($term[$rx]) > 3)
					{ 
					$txt = $txt;
					$termx = uppercasesql($term[$rx]);
					$terms = trim($term[$rx]);
					$mark_on = '<font style="background-color : Yellow;"><B>';
					$mark_off = '</B></font>';
					while (strpos($txta,$termx) > 0)
						{
						$xpos = strpos($txta,$termx);
						$txt  = substr($txt ,0,$xpos).$mark_on. substr($txt,$xpos,strlen($termx)).                 $mark_off.substr($txt ,$xpos+strlen($termx)  ,strlen($txt));
						$txta = substr($txta,0,$xpos).$mark_on. strzero('0',strlen($termx)).                       $mark_off.substr($txta,$xpos+strlen($termx)  ,strlen($txt));
						}
					}
				}
//		echo '<HR>'.$txt.'<HR>'.$txta.'<HR>';
		return($txt);
	}
	function result_search()
		{
			global $dd,$acao;
			$sx .= '<table border=1 class="lt1" width="100%">';
			$sx .= '<TR valign="top">';
			$sx .= '<TD>';
			$sx .= msg('find').'<B> '.$dd[2].'</B>';
			$sx .= $this->result_article($dd[2],$dd[10],$dd[11]);
			
			$sx .= '<TD width="120">';
			$sa = $this->result_journals();
			$sa .= $this->result_year();
			$sa .= $this->result_author();
			$sa .= $this->result_keyword();
			$sx .= $sa;
			$sx .= '</table>';
			return($sx);			
		}
	function result_search_keyword($key_cod='')
		{
			global $dd,$acao;
			$sx .= '<table border=1 class="lt1" width="100%">';
			$sx .= '<TR valign="top">';
			$sx .= '<TD>';
			$sx .= msg('find').'<B> '.$dd[2].'</B>';
			$sx .= $this->result_article_key($key_cod);
			$sx .= '<TD width="120">';
			if (strlen($this->query) > 0)
				{
					
					$sa = $this->result_journals();
					$sa .= $this->result_year();
					$sa .= $this->result_author();
					$sa .= $this->result_keyword();
					$sx .= $sa;
				}
			$sx .= '</table>';
			return($sx);			
		}
	function result_search_author($key_cod='')
		{
			global $dd,$acao;
			$sx .= '<table border=1 class="lt1" width="100%">';
			$sx .= '<TR valign="top">';
			$sx .= '<TD>';
			$sx .= msg('find').'<B> '.$dd[2].'</B>';
			$sx .= $this->result_article_auth($key_cod);
			$sx .= '<TD width="120">';
			if (strlen($this->query) > 0)
				{
					
					$sa = $this->result_journals();
					$sa .= $this->result_year();
					$sa .= $this->result_author();
					$sa .= $this->result_keyword();
					$sx .= $sa;
				}
			$sx .= '</table>';
			return($sx);			
		}
	function result_search_selected()
		{
			global $dd,$acao;
			$sx .= '<table border=1 class="lt1" width="100%">';
			$sx .= '<TR valign="top">';
			$sx .= '<TD>';
			$sx .= msg('find').'<B> '.$dd[2].'</B>';
			$sx .= $this->result_article_selected($this->session());
			
			$sx .= '<TD width="120">';
			$sa = $this->result_journals();
			$sa .= $this->result_year();
			$sa .= $this->result_author();
			$sa .= $this->result_keyword();
			$sx .= $sa;
			$sx .= '</table>';
			return($sx);			
		}		
	}
?>
















