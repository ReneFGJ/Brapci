<?php
class search extends CI_model {

	var $sessao = '';
	var $js = '';
	var $ssid = '';

	function __construct() {
		global $db_public;

		$db_public = 'brapci_publico.';
		parent::__construct();
		$this -> load -> database();
		$this -> load -> helper('form');
		$this -> load -> helper('url');
		$this -> load -> library('session');
		$this -> lang -> load("app", "portuguese");
		$this -> ssid = '1508130000';
		$this -> sessao = '1508130000';
	}

	function metodo_pontos($titulo, $resumo, $keyword, $rla) {

		$pt = 0;
		$pt1 = 0;
		$pt2 = 0;
		$pt3 = 0;
		$totr = 0;

		for ($r = 0; $r < count($rla); $r++) {
			$ttt = trim($rla[$r]);
			if (strpos(' ' . $titulo, $ttt) > 0) { $pt1++;
			}
			if (strpos(' ' . $resumo, $ttt) > 0) { $pt2++;
			}
			if (strpos(' ' . $keyword, $ttt) > 0) { $pt3++;
			}

		}

		$total = count($rla);
		//echo "<BR>==><B>($pt1)</B>, <B>($pt2)</B> e ($pt3) = ($total)";
		//echo $titulo;

		$pt1 = ($pt1 == count($rla));
		$pt2 = ($pt2 == count($rla));
		$pt3 = ($pt3 == count($rla));

		$pt = ($pt1 * 4) + ($pt2 * 1) + ($pt3 * 2);
		if ($pt > 0) { $srt = ' <A HREF="'.base_url('index.php/about.php#pontos').'" target="_new"><img src="' . base_url('img/star_' . $pt . '.png') . '" alt="" border="0" align="absmiddle"></A>';
			$mst = true;
		} else { $srt = '';
			$mst = false;
			$totr--;
		}
		$smetodo = 'Metodo 1';
		return ($srt);
	}

	function result_cited() {
		/* m_works */
		$wh = troca($this -> query, 'ar_codigo', 'm_work');
		$sql = "select * from mar_works where " . $wh;
		$sql .= " order by m_ano, m_ref ";
		$rlt = db_query($sql);
		$sx = '<font class="lt1">';

		$xano = '0';
		$id = 0;
		$to = 0;
		while ($line = db_read($rlt)) {
			$id++;
			$to++;
			$ano = $line['m_ano'];
			if ($xano != $ano) {
				$id = 1;
				$sx .= '<hr><B>' . $ano . '</B><HR>';
				$xano = $ano;
			}
			$sx .= $id . '. ' . trim($line['m_ref']);
			if (strlen($line['m_bdoi']) > 0) {
				$sx .= ' <font color="blue">' . $line['m_bdoi'] . '</font>';
			}
			$sx .= '<BR>';
		}
		$sx .= 'Total de ' . $to . ' referencias';
		return ($sx);

	}

	function selections() {
		global $db_public;
		$sx = '';
		$sql = "select * from
						( select max(sel_data) as lastupdate, sel_sessao, count(*) as total  
							from " . $db_public . "usuario_selecao
						  	group by sel_sessao) as tabela
						left join " . $db_public . "usuario_estrategia on e_session = sel_sessao
						order by total desc 			
			";
		echo $sql;
		$rlt = db_query($sql);
		$sx .= '<table width="100%">';
		$sx .= '<TR><TH>Descricao<TH>Selecao<TH>Atualizacao';
		while ($line = db_read($rlt)) {
			$link = '<A HREF="' . base_url('index.php/home/selection/' . $line['sel_sessao']) . '">';
			$sx .= '<TR>';
			$sx .= '<TD class="tabela01">' . $line['e_descricao'];
			$sx .= '<TD class="tabela01" align="center">' . $link . $line['sel_sessao'] . '</A>';
			$sx .= '<TD class="tabela01" align="center">' . $line['total'];
			$sx .= '<TD class="tabela01" align="center">' . stodbr($line['lastupdate']);
		}
		$sx .= '</table>';
		return ($sx);
	}

	function mark_resume() {
		global $ssid, $db_public, $user;
		$sql = "select count(*) as total from " . $db_public . "usuario_selecao 
				 where sel_sessao = '$ssid' and sel_ativo = 1";

		$rlt = db_query($sql);
		if ($line = db_read($rlt)) {
			return ($line['total']);
		}
		return (0);
	}

	function mark($art, $vl) {
		global $ssid, $db_public, $user;

	}

	function session_set($ss) {
		$ss = array('ssid' => $ss);
		$this -> session -> set_userdata($ss);
		return (1);
	}

	function session() {
		$ss = $this -> session -> userdata('ssid');
		$sa = array('ssid' => $ss);
		$this -> session -> set_userdata($sa);
		return ($ss);
	}

	function trata_termo_composto($term) {
		$asp = 0;
		$sa = '';
		$term = troca($term, '\"', '"');
		for ($r = 0; $r < strlen($term); $r++) {
			$ca = substr($term, $r, 1);
			if ($ca == '"') {
				if ($asp == 0) { $asp = 1;
				} else {$asp = 0;
				}
			} else {
				/* troca espaco por _ quando termo composto */
				if (($ca == ' ') and ($asp == 1)) { $ca = '_';
				}
				$sa .= $ca;
			}
		}
		return ($sa);
	}

	function where($term = '', $field = '') {
		global $dd;
		$term = $this -> trata_termo_composto($term);
		$term = troca($term, '\"', '');
		$term = troca($term, '"', '');
		$term = troca($term, '(', '#');
		$term = troca($term, '#', ';(;');
		$term = troca($term, ')', '#');
		$term = troca($term, '#', ';);');
		$term = troca($term, ' ', ';');
		if (strlen($term) > 0) { $terms = splitx(';', $term);
		}
		$wh = '';
		$pre = '';
		$par = 0;
		$bor = 0;
		for ($r = 0; $r < count($terms); $r++) {
			$term = $terms[$r];

			if (strlen($term) > 2) {
				$term = troca($term, '_', ' ');
				if (($term == 'NOT') or ($term == 'AND')) {
					$pre = ' ' . UpperCase($term) . ' ';
				} else {
					if ((strlen($wh) > 0) and ($bor == 0)) { $wh .= ' AND ';
					}
					$wh .= $pre;
					if (substr($term, 0, 1) == '-') {
						$wh .= " NOT (" . $field . " like '%" . substr($term, 1, strlen($term)) . "%') ";
					} else {
						$wh .= " (" . $field . " like '%" . $term . "%') ";
					}

					$pre = '';
					$bor = 0;
				}
			} else {
				if ($term == 'OR') { $pre = ' OR ';
				}
				if (trim($term) == '(') { $bor = 1;
					$par++;
					$wh .= $pre . ' ' . $term;
					$pre = '';
				}
				if (trim($term) == ')') { $bor = 1;
					$par--;
					$wh .= $pre . ' ' . $term;
					$pre = '';
				}
				//$wh .= $term;
			}
		}
		$wh = '(' . $wh . ')';
		$wh = troca($wh, 'AND  AND', 'AND');
		for ($r = 0; $r < $par; $r++) { $wh .= ')';
		}

		$tps = array('srcid', 'srcid2', 'srcid6', 'srcid8');
		$tpf = array('J', 'E', 'T', 'D');
		$sqlw = '';
		for ($r = 0; $r < count($tps); $r++) {
			//$id = $SESSION[$tps[$r]];
			$id = 1;
			if ($id == 1) {
				if (strlen($sqlw) > 0) { $sqlw .= ' or ';
				}
				$sqlw .= " (jnl_tipo = '" . $tpf[$r] . "') ";
			}
		}
		if (strlen($sqlw) > 0) { $wh .= ' AND (' . $sqlw . ' ) ';
		}
		return ($wh);
	}

	/* result article */
	function result_total_articles($term = '', $datai = '', $dataf = '') {
		global $db_public, $SESSION;
		//$term = utf8_decode($term);

		$sessao = $this -> sessao;

		$wh = $this -> where(UpperCaseSql($term), 'ar_asc');
		if (strlen($datai) > 0) { $wh .= ' and (ar_ano >= ' . $datai . ' )';
		}
		if (strlen($dataf) > 0) { $wh .= ' and (ar_ano <= ' . $dataf . ' )';
		}

		/* Origem do documentos */
		if (strlen($SESSION['srcid']) == 0) {
			/* Todos os tipo */
			$wh .= '';
		} else {
			$wha = '';
			if ((strlen($SESSION['srcid1']) == 0) or ($SESSION['srcid1'] == '1')) {
				$wha .= " Call_Number = '1' ";
			}
			if ((strlen($SESSION['srcid2']) == 0) or ($SESSION['srcid2'] == '1')) {
				if (strlen($wha) > 0) { $wha .= ' or ';
				} $wha .= " Call_Number = '2' ";
			}
			if ((strlen($SESSION['srcid3']) == 0) or ($SESSION['srcid3'] == '1')) {
				if (strlen($wha) > 0) { $wha .= ' or ';
				} $wha .= " Call_Number = '3' ";
			}
			if ((strlen($SESSION['srcid4']) == 0) or ($SESSION['srcid4'] == '1')) {
				if (strlen($wha) > 0) { $wha .= ' or ';
				} $wha .= " Call_Number = '4' ";
			}
			if ((strlen($SESSION['srcid5']) == 0) or ($SESSION['srcid5'] == '1')) {
				if (strlen($wha) > 0) { $wha .= ' or ';
				} $wha .= " Call_Number = '5' ";
			}
			if ((strlen($SESSION['srcid6']) == 0) or ($SESSION['srcid6'] == '1')) {
				if (strlen($wha) > 0) { $wha .= ' or ';
				} $wha .= " Call_Number = '6' ";
			}
			if ((strlen($SESSION['srcid7']) == 0) or ($SESSION['srcid7'] == '1')) {
				if (strlen($wha) > 0) { $wha .= ' or ';
				} $wha .= " Call_Number = '7' ";
			}
			if ((strlen($SESSION['srcid8']) == 0) or ($SESSION['srcid8'] == '1')) {
				if (strlen($wha) > 0) { $wha .= ' or ';
				} $wha .= " Call_Number = '8' ";
			}
		}
		if (strlen($wha) > 0) { $wh .= ' and (' . $wha . ')';
		}

		$sql = "select count(*) as total from " . $db_public . "artigos 
					inner join brapci_journal on jnl_codigo = ar_journal_id
					where " . $wh . "
					";
		$rlt = db_query($sql);
		$line = db_read($rlt);
		$total = $line['total'];
		return ($total);
	}

	/* Trata os registro informados para busca
	 *
	 *
	 *
	 *
	 */
	function tratar_term($t) {
		/* Termo composto - polilexico */
		$t = troca($t, "\\", '');
		$t = troca($t, "&quot;", '"');
		$as = 0;
		$tr = '';
		for ($r = 0; $r < strlen($t); $r++) {
			$ch = substr($t, $r, 1);
			switch ($ch) {
				case '"' :
					if ($as == 0) { $as = 1;
					} else { $as = 0;
					}
					break;
				default :
					if (($as == 1) and ($ch == ' ')) { $ch = '_';
					}
					$tr .= $ch;
			}
		}
		return ($tr);
	}

	/* REALIZA BUSCA NO SISTEMA
	 *
	 *
	 *
	 *
	 *
	 */
	function result_article($term = '', $datai = '', $dataf = '') {
		global $db_public, $dd, $SESSION;

		$term = $this -> tratar_term($term);

		$total = $this -> result_total_articles($term, $datai, $dataf);

		//$term = utf8_decode($term);

		$sessao = $this -> sessao;

		$wh = $this -> where(UpperCaseSql($term), 'ar_asc');
		if (strlen($datai) > 0) { $wh .= ' and ar_ano >= ' . $datai;
		}
		if (strlen($dataf) > 0) { $wh .= ' and ar_ano <= ' . $dataf;
		}

		/* Origem do documentos */
		if (strlen($SESSION['srcid']) == 0) {
			/* Todos os tipo */
			$wh .= '';
		} else {
			$wha = '';
			if ($SESSION['srcid1'] == '1') { $wha .= " Call_Number = '1' ";
			}
			if ($SESSION['srcid2'] == '1') {
				if (strlen($wha) > 0) { $wha .= ' or ';
				} $wha .= " Call_Number = '2' ";
			}
			if ($SESSION['srcid3'] == '1') {
				if (strlen($wha) > 0) { $wha .= ' or ';
				} $wha .= " Call_Number = '3' ";
			}
			if ($SESSION['srcid4'] == '1') {
				if (strlen($wha) > 0) { $wha .= ' or ';
				} $wha .= " Call_Number = '4' ";
			}
			if ($SESSION['srcid5'] == '1') {
				if (strlen($wha) > 0) { $wha .= ' or ';
				} $wha .= " Call_Number = '5' ";
			}
			if ($SESSION['srcid6'] == '1') {
				if (strlen($wha) > 0) { $wha .= ' or ';
				} $wha .= " Call_Number = '6' ";
			}
			if ($SESSION['srcid7'] == '1') {
				if (strlen($wha) > 0) { $wha .= ' or ';
				} $wha .= " Call_Number = '7' ";
			}
			if ($SESSION['srcid8'] == '1') {
				if (strlen($wha) > 0) { $wha .= ' or ';
				} $wha .= " Call_Number = '8' ";
			}
		}
		if (strlen($wha) > 0) { $wh .= ' and (' . $wha . ')';
		}

		$sql = "select * from " . $db_public . "artigos 
					inner join brapci_journal on ar_journal_id = jnl_codigo
					left join " . $db_public . "usuario_selecao on ar_codigo = sel_work and sel_sessao = '" . $sessao . "'
					where " . $wh . "
					";
		$sql .= " order by ar_ano desc ";
		$sql .= " limit 500 offset 0 ";
		$rlt = db_query($sql);

		$this -> query = $wh;

		$sx = chr(13) . chr(10);
		/* total */
		$sx .= ', ' . $this -> lang -> line('form_found') . ' <B>' . $total . '</B> ' . $this -> lang -> line('form_records');

		$sx .= '<div id="result_select">selection</div>';
		$sx .= '<table width="100%" class="lt1">';
		$id = 0;
		while ($line = db_read($rlt)) {
			$sx .= $this -> show_article_mini($line);
		}
		$sx .= '</table>';
		$sx .= chr(13) . chr(10);
		$sx .= $this -> js;
		$sx .= chr(13) . chr(10);
		return ($sx);

	}

	function result_article_key($term_code = '') {
		global $db_public, $db_base;

		//$total = $this->result_total_articles($term,$datai,$dataf);

		//$term = utf8_decode($term);

		$sessao = $this -> sessao;

		$sql = "select * from " . $db_base . "brapci_article_keyword 
					inner join " . $db_public . "artigos on ar_codigo = kw_article
					where kw_keyword = '$term_code'
					";
		$sql .= " order by ar_ano desc ";
		$sql .= " limit 100 offset 0 ";

		$rlt = db_query($sql);

		$sx = chr(13) . chr(10);
		/* total */
		$sx .= ', found ' . $total;

		$sx .= '<div id="result_select">selection</div>';
		$sx .= '<table width="100%" class="lt1">';
		$id = 0;
		$wh = '';
		while ($line = db_read($rlt)) {
			$sx .= $this -> show_article_mini($line);
			if (strlen($wh) > 0) { $wh .= ' or ';
			}
			$wh .= " ar_codigo = '" . trim($line['ar_codigo']) . "' ";
		}
		$sx .= '</table>';
		$sx .= chr(13) . chr(10);
		$sx .= $this -> js;
		$sx .= chr(13) . chr(10);
		$this -> query = $wh;

		return ($sx);

	}

	function result_article_auth($term_code = '') {
		global $db_public, $db_base;

		//$total = $this->result_total_articles($term,$datai,$dataf);

		//$term = utf8_decode($term);

		$sessao = $this -> sessao;

		$sql = "select * from " . $db_base . "brapci_article_author 
					inner join " . $db_public . "artigos on ar_codigo = ae_article
					where ae_author = '$term_code'
					";
		$sql .= " order by ar_ano desc ";
		$sql .= " limit 100 offset 0 ";

		$rlt = db_query($sql);

		$sx = chr(13) . chr(10);
		/* total */
		$sx .= ', found ' . $total;

		$sx .= '<div id="result_select">selection</div>';
		$sx .= '<table width="100%" class="lt1">';
		$id = 0;
		$wh = '';
		while ($line = db_read($rlt)) {
			$sx .= $this -> show_article_mini($line);
			if (strlen($wh) > 0) { $wh .= ' or ';
			}
			$wh .= " ar_codigo = '" . trim($line['ar_codigo']) . "' ";
		}
		$sx .= '</table>';
		$sx .= chr(13) . chr(10);
		$sx .= $this -> js;
		$sx .= chr(13) . chr(10);
		$this -> query = $wh;

		return ($sx);

	}

	function result_article_selected($session, $pag = 1) {
		global $db_public;
		$offset = 100 * (round($pag) - 1);
		$total = 0;
		//$total = $this->result_total_articles($term,$datai,$dataf);

		//$term = utf8_decode($term);

		$sql = "select * from " . $db_public . "usuario_selecao 
					inner join " . $db_public . "artigos on ar_codigo = sel_work
					left join brapci_journal on jnl_codigo = ar_journal_id
					left join brapci_journal_tipo on jnl_tipo = jtp_codigo
					left join brapci_section on ar_tipo = se_codigo
					where sel_sessao = '$session' and sel_ativo = 1 
					";

		$sql .= " order by ar_ano desc ";
		$sql .= " limit 300 offset $offset ";

		$this -> query = " sel_sessao = '$session' and sel_ativo = 1 ";

		$rlt = db_query($sql);

		$sx = chr(13) . chr(10);
		/* total */
		$sx .= ', found ' . $total;

		$sx .= '<div id="result_select">selection</div>';
		$sx .= '<table width="100%" class="lt1">';
		$id = 0;
		$wh = '';
		while ($line = db_read($rlt)) {

			$sx .= $this -> show_article_mini($line);
			if (strlen($wh) > 0) { $wh .= ' or ';
			}
			$wh .= " ar_codigo = '" . trim($line['ar_codigo']) . "' ";
		}
		$sx .= '</table>';
		$sx .= chr(13) . chr(10);
		$sx .= $this -> js;
		$sx .= chr(13) . chr(10);
		$this -> query = $wh;

		return ($sx);

	}

	function result_cited_selected($session) {
		global $db_public, $db_base, $pag;
		$pag = $_GET['pag'];

		$offset = 100 * (round($pag));

		//$total = $this->result_total_articles($term,$datai,$dataf);

		//$term = utf8_decode($term);

		$sessao = $this -> sessao;

		$sql = "select * from " . $db_public . "usuario_selecao 
					inner join mar_works on m_work = sel_work
					where sel_sessao = '$sessao' and sel_ativo = 1 
					";

		$sql .= " order by  m_ref, m_ano desc ";
		//$sql .= " limit 300 offset $offset ";
		$rlt = db_query($sql);

		$sx = chr(13) . chr(10);
		/* total */
		$sx .= ', found ' . $total;

		$sx .= '<div id="result_select">selection</div>';
		$sx .= '<table width="100%" class="lt1">';
		$id = 0;
		$wh = '';
		while ($line = db_read($rlt)) {
			$id++;
			$sx .= '<BR>';
			$sx .= $line['m_ref'];
			//if (strlen($wh) > 0) { $wh .= ' or '; }
			//$wh .= " ar_codigo = '".trim($line['ar_codigo'])."' ";
		}
		$sx .= '<tr><TD>' . $id . ' total';
		$sx .= '</table>';

		return ($sx);

	}

	function send_to_email($article, $size = 32) {
		global $user_email;
		$sx = '';
		if (strlen($user_email) > 0) {
			$link = nwin('article_send_email.php?dd0=' . $cod . '&dd99=' . checkpost($cod), 200, 200);
			$sx = '<img src="img/icone_send_to_email_off.png" 
							border=0 title="' . msg('send_to_email') . '" 
							height="' . $size . '"
							onmouseover="this.src=\'img/icone_send_to_email.png\'"
							onmouseout="this.src=\'img/icone_send_to_email_off.png\'"
							' . $link . '
							>';
		}
		return ($sx);
	}

	function show_article_mini($line) {
		global $id, $email, $dd;
		$sx = '';
		$js = '<script>' . chr(13) . chr(10);
		$js .= 'function abstractshow(ms)
					{ $(ms).toggle("slow"); }
					
				function mark(ms,ta)
					{
						alert("Consulta");
						var ok = ta.checked;
						$.ajax({
  							type: "POST",
  							url: "' . base_url('index.php/public/mark/') . '",
  							data: { dd1: ms, dd2: ok }
						}).done(function( data ) {
							$("#basket").html(data);
						});						
					}
					';
		$js .= '</script>' . chr(13) . chr(10);
		$this -> js = $js;

		$link = '<A HREF="' . base_url('index.php/article/view/' . $line['ar_codigo'] . '/' . checkpost_link($line['ar_codigo'])) . '"
					 class="lt1"
					 target="_new' . $line['ar_codigo'] . '"
					 >';

		$id++;
		$cod = trim($line['ar_codigo']);

		$sx .= '<TR valign="top">';
		$sx .= '<TD rowspan=1>';

		/* Marcacao */
		$selected = round($line['sel_ativo']);
		if ($selected == 1) { $selected = 'checked';
		} else { $selected = '';
		}
		$jscmd = 'onchange="mark(\'#mt' . trim($cod) . '\',this);" ';
		$fm = '<input type="checkbox" name="ddq" ' . $jscmd . ' ' . $selected . '>';
		$sx .= $fm;

		$sx .= '<TD colspan=1>';
		$sx .= $id . '. ';
		$sx .= $link;
		$sx .= (trim(UpperCase($line['Article_Title'])));

		$sx .= '</A>';
		/* Show */

		$jscmd = 'onclick="abstractshow(\'#mt' . trim($cod) . '\');" ';

		$sx .= '<BR>';
		$sx .= '<img src="' . base_url('img/icone_abstract.png') . '" height="16" style="cursor: pointer;" id="it' . $cod . '" ' . $jscmd . ' align="left">';
		/* enviar por e-mail */
		if (isset($email)) { $sx .= $this -> send_to_email($cod, 16);
		}

		//$sx .= '<TD colspan=3 class="lt0">';
		$sx .= (trim($line['Author_Analytic']));

		/* Volume numero */
		$vol = trim($line['Volume_ID']);
		$num = trim($line['Issue_ID']);
		$pag = trim($line['Pages']);
		$ano = trim($line['ar_ano']);

		$v = '';
		if (strlen($vol) > 0) { $v .= ', ' . $vol;
		}
		if (strlen($num) > 0) { $v .= ', ' . $num;
		}
		if (strlen($pag) > 0) { $v .= ', ' . $pag;
		}
		if (strlen($ano) > 0) { $v .= ', ' . $ano;
		}

		//$sx .= '<BR>'.utf8_encode($ar->mostra_issue($line));

		$sx .= '<BR>';
		$sx .= '<B>' . (trim($line['Journal_Title'])) . '</B>';
		$sx .= $v;

		$tipo = $line['jnl_tipo'];
		$sect = $line['ar_tipo'];
		$sx .= ' (' . $this -> tipo_publicacao($tipo).'-' .$sect. ')';

		/* DIV */
		$hid = 'display: none;';
		$sx .= '<div style="text-align: justify; ' . $hid . ' color: #A0A0A0; line-height:130%; margin: 8px; 8px; 8px; 8px;" id="mt' . $cod . '">';
		$abst1 = trim($line['ar_resumo_1']);
		$abst2 = trim($line['ar_resumo_2']);

		if (strlen($abst1) == 0) {
			$resumo = $abst2;
		} else {
			$resumo = $abst1;
			if (strlen($abst2) > 0) {
				$resumo .= '<BR><BR>' . $abst2;
			}
		}
		$sx .= $resumo;
		$sx .= '<BR>';
		$keys = trim($line['Idioma']);
		$key = trim($line['ar_keyword_1']);
		$key .= ' ' . trim($line['ar_keyword_2']);
		$key = trim(troca($key, ' /', ','));
		if ($keys == 'pt_BR') { $sx .= '<B>Palavras-chave</B>: ' . $key;
		} else { $sx .= '<BR><B>Keywords</B>: ' . $key;
		}

		/* Pontos */

		$sx .= '</div>';

		/* Para a marcacao de pontos */
		$titulo = trim(UpperCaseSQL($line['Article_Title']));
		$titulo .= trim(UpperCaseSQL($line['Title_2']));

		$resumo = trim(UpperCaseSQL($line['ar_resumo_1']));
		$resumo .= ' ' . trim(UpperCaseSQL($line['ar_resumo_2']));

		$keyword = trim(UpperCaseSQL($line['Keywords']));
		$keyword .= trim(UpperCaseSQL($line['ar_keyword_1']));
		$keyword .= trim(UpperCaseSQL($line['ar_keyword_2']));

		$termos = $dd[2] . ' ';
		$termos = troca($termos, ' ', ';');
		$termos = splitx(";", UpperCaseSql($termos));

		$sx .= $this -> metodo_pontos($titulo, $resumo, $keyword, $termos);

		return ($sx);
	}

	function tipo_publicacao($tp) {
		$tipo = trim($tp);
		$sx = '';
		switch ($tipo) {
			case 'A' :
				$sx = 'Tese';
				break;
			case 'B' :
				$sx = 'Dissertacao';
				break;
			case 'J' :
				$sx = 'Revista';
				break;
			case 'L' :
				$sx = 'Livro';
				break;
			case 'T' :
				$sx = 'Tese de Doutorado';
				break;
			case 'U' :
				$sx = 'Dissertaçao de Mestrado';
				break;
			case 'D' :
				$sx = 'Livro diático';
				break;
			case 'E' :
				$sx = 'Anais de eventos';
				break;
			default :
				$sx .= $tipo;
				break;
		}
		return ($sx);
	}
	function tipo_secoes() {
		$sql = "select * from brapci_section ";
		$data = array();
		$rlt = db_query($sql);
		while ($line = db_read($rlt))
			{
				$sec = trim($line['se_codigo']);
				$data[$sec] = trim($line['se_descricao']);
			}
		return($data);
	}	

	function result_journals() {
		global $db_base, $db_public;
		$wh = $this -> query;

		$sx = '';

		if (strlen($wh) == 0) { $wh = '(1 = 1)';
		}
		$sql = "select count(*) as total, jnl_nome_abrev from " . $db_public . "artigos 
					inner join " . $db_base . "brapci_journal on jnl_codigo = ar_journal_id
			where $wh ";

		$sql .= "group by jnl_nome_abrev order by total desc ";
		$rlt = db_query($sql);
		$sx .= '<table class="lt0" width="160">';
		$sx .= '<TR><Th>' . msg("journal") . '<Th>' . msg('quant.');

		while ($line = db_read($rlt)) {
			$sx .= '<TR>';
			$sx .= '<TD>';
			$sx .= trim($line['jnl_nome_abrev']);
			$sx .= '<TD align="center">';
			$sx .= trim($line['total']);
		}
		$sx .= '</table>';
		return ($sx);
	}

	function result_year() {
		global $db_base, $db_public;
		$wh = $this -> query;

		$sx = '';

		if (strlen($wh) == 0) { $wh = '(1 = 1)';
		}
		$sql = "select count(*) as total, ar_ano from " . $db_public . "artigos 
				inner join brapci_journal on jnl_codigo = ar_journal_id
			where $wh ";

		$sql .= "group by ar_ano order by ar_ano desc, total desc ";

		$rlt = db_query($sql);
		$sx .= '<table class="lt0" width="160">';
		$sx .= '<TR><Th>' . msg("year") . '<Th>' . msg('quant.');

		while ($line = db_read($rlt)) {
			$sx .= '<TR>';
			$sx .= '<TD align="center">';
			$sx .= trim($line['ar_ano']);
			$sx .= '<TD align="center">';
			$sx .= trim($line['total']);
		}
		$sx .= '</table>';
		return ($sx);
	}

	function result_author() {
		global $db_base, $db_public;
		$wh = $this -> query;

		$sx = '';

		if (strlen($wh) == 0) { $wh = '(1 = 1)';
		}
		$sql = "
			select autor_nome, autor_codigo, sum(total) as total from (
				select count(*) as total, ae_author from " . $db_public . "artigos 
				inner join " . $db_base . "brapci_article_author on ae_article = ar_codigo
				inner join brapci_journal on jnl_codigo = ar_journal_id 
				where $wh
				group by ae_author 
			) as tabela 
				inner join " . $db_base . "brapci_autor on ae_author = autor_codigo
				
				group by autor_nome, autor_codigo
				order by total desc, autor_nome
				limit 20
			";
		$rlt = db_query($sql);
		$sx .= '<table class="lt0" width="160">';
		$sx .= '<TR><Th>' . msg("author") . '<Th>' . msg('quant.');

		while ($line = db_read($rlt)) {
			$sx .= '<TR>';
			$sx .= '<TD align="left">';
			$sx .= trim($line['autor_nome']);
			$sx .= '<TD align="center">';
			$sx .= trim($line['total']);
		}
		$sx .= '</table>';
		return ($sx);
	}

	function result_author_network() {
		global $db_base, $db_public;
		$wh = $this -> query;

		$sql = "select * from ".$db_public."artigos where ".$wh;
		$rlt = db_query($sql);
		$sx = '';
		while ($line = db_read($rlt)) {
			$sx .= '<BR>';
		 	$sx .= trim($line['Author_Analytic']).';';
		 	$key = troca(trim($line['Keywords']),'/',';');
		 	//$sx .= $key;
		}
		return ($sx);
	}
	
	function result_keyword_network() {
		global $db_base, $db_public;
		$wh = $this -> query;
		$wh = troca($wh,'ar_codigo','kw_article');

		$sql = "select * from brapci_article_keyword
				inner join brapci_keyword on kw_keyword = kw_codigo and kw_idioma = 'pt_BR'
				where ".$wh."
				order by kw_article
				";
		$rlt = db_query($sql);
		$sx = '';
		$xart = '';
		while ($line = db_read($rlt)) {
			$art = $line['kw_article'];
			if ($art != $xart)
				{
					$sx .= '<BR>';	
					$xart = $art;	
				}			
		 	$sx .= trim($line['kw_word']).';';
		 	//$sx .= $key;
		}
		$sx .= '<HR>TOTAL:'.$this->result_keyword_total();
		return ($sx);
	}	

	function result_keyword_total() {
		global $db_base, $db_public;
		$wh = $this -> query;
		$wh = troca($wh,'ar_codigo','kw_word');

		$sql = "select count(*) as total, kw_word from brapci_article_keyword
				inner join brapci_keyword on kw_keyword = kw_codigo and kw_idioma = 'pt_BR'
				where ".$wh."
				group by kw_word
				";

		$rlt = db_query($sql);
		$sx = '';
		$xart = '';
		while ($line = db_read($rlt)) {
			print_r($line);
			exit;
				$sx .= '<BR>';	
			 	$sx .= trim($line['kw_word']).';';
				$sx .= trim($line['total']).';';
		 	//$sx .= $key;
		}
		return ($sx);
	}
	function result_keyword() {
		global $db_base, $db_public;
		$wh = $this -> query;

		$sx = '';

		if (strlen($wh) == 0) { $wh = '(1 = 1)';
		}
		$sql = "
			select kw_word,kw_keyword, sum(total) as total from (
				select count(*) as total, kw_keyword from " . $db_public . "artigos 
				inner join " . $db_base . "brapci_article_keyword on ar_codigo = kw_article
				inner join brapci_journal on jnl_codigo = ar_journal_id
				where $wh
				group by kw_keyword 
			) as tabela 
				inner join " . $db_base . "brapci_keyword on kw_keyword = kw_codigo
				where kw_idioma = 'pt_BR'
				group by kw_word, kw_codigo
				order by total desc, kw_word
				limit 40
			";

		$rlt = db_query($sql);
		$sx .= '<table class="lt0" width="160">';
		$sx .= '<TR><Th>' . msg("keyword") . '<Th>' . msg('quant.');
		while ($line = db_read($rlt)) {
			$sx .= '<TR>';
			$sx .= '<TD align="left">';
			$sx .= trim($line['kw_word']);
			$sx .= '<TD align="center">';
			$sx .= trim($line['total']);
		}
		$sx .= '</table>';
		$this -> tags_cloud();
		return ($sx);
	}

	function tags_cloud() {
		global $db_base, $db_public;
		$wh = $this -> query;

		$sx = '';

		if (strlen($wh) == 0) { $wh = '(1 = 1)';
		}
		$sql = "
			select kw_word,kw_keyword, sum(total) as total from (
				select count(*) as total, kw_keyword from " . $db_public . "artigos 
				inner join " . $db_base . "brapci_article_keyword on ar_codigo = kw_article
				inner join brapci_journal on jnl_codigo = ar_journal_id
				where $wh
				group by kw_keyword 
			) as tabela 
				inner join " . $db_base . "brapci_keyword on kw_keyword = kw_codigo
				where kw_idioma = 'pt_BR'
				group by kw_word, kw_codigo
				order by kw_word
			";

		$rlt = db_query($sql);
		$id = 0;
		$ntag = '';
		$sz = 20;
		$ntag = '<BR><BR><BR>
					<div style="float: clear;">';
		while ($line = db_read($rlt)) {
			$size = round(log($line['total']) * 10);
			$ntag .= '<div style="float: left">';
			$ntag .= '&nbsp<font style="font-size: ' . $size . 'pt;">' . trim($line['kw_word']) . '</font> ';
			$ntag .= '</div>';
		}
		$ntag .= '</div>
						<BR><BR><BR><BR><BR>
						<div style="float: clear;"></div>';
		$this -> tag = $ntag;
		return ($sx);
	}

	function registra_consulta($txt) {
		global $db_public, $user;
		$data = date("Ymd");
		$hora = date("H:i:s");
		$usr = $user -> codigo;
		$session = $SESSION['ssid'];
		$sql = "insert into " . $db_public . "query 
			(
			q_session, q_query, q_data,
			q_hora, q_user
			) values (
			'$session','$txt',$data,
			'$hora','$usr'
			)";
		$rlt = db_query($sql);
	}

	function busca_form() {
		global $dd, $SESSION;

		$SESSION = array();
		$SESSION['ssid'] = '';
		$SESSION['srcid'] = '1';
		for ($r = 0; $r < 100; $r++) {
			$SESSION['srcid' . $r] = '1';
		}

		$sx = '';
		/* registra consulta */

		if (strlen($dd[1])) {
			//$this -> registra_consulta($dd[2]);
		}

		$sx .= '<TR><TD colspan=2 >';
		if (strlen($dd[1]) > 0) {
			$sr = $this -> result_search($dd);
			$sx .= $this -> realce($sr, $dd[2]);
		}
		return ($sx);
	}

	function delimitacao_por_journals() {
		global $SESSION;
		$sx = '';
		$sql = "select * from brapci_journal_tipo where jtp_ativo = 1
							order by jtp_ordem ";
		$rlt = db_query($sql);

		while ($line = db_read($rlt)) {
			$fld = 'srcid' . trim($line['id_jtp']);
			if (!isset($SESSION[$fld])) { $SESSION[$fld] = '';
			}
			$vl = $SESSION[$fld];
			$check = '';
			if ((strlen($vl) == 0) or ($vl == '1')) { $check = 'checked';
			}
			$jscmd = 'onclick="mark_type(this,\'id' . trim($line['id_jtp']) . '\');" ';
			$sx .= '<input id="tp' . trim($line['id_jtp']) . '" type="checkbox" ' . $jscmd . ' name="dd13" value="1" ' . $check . '>
							&nbsp;' . $line['jtp_descricao'] . '<BR>';
		}
		$sx .= '
					<script>
					function mark_type(ta,tp)
					{
						var ok = ta.checked;
						var ms = tp;
						$.ajax({
  							type: "POST",
  							url: "article_type_mark.php",
  							data: { dd1: ms, dd2: ok }
						})
						.done(function( data ) {
							$("#delimit_type").html(data);
						})
						.erro(function( data ) {
							alert("erro "+data);
						});						
					}
					</script>
				';
		return ($sx);

	}

	function delimitacao_por_data() {
		/* Delimitacao por data */
		global $dd, $SESSION;
		$sx = '';
		$dtf = '';
		for ($r = 1900; $r <= date("Y"); $r++) {
			$chk = '';
			if ($dd[10] == $r) { $chk = 'selected';
			}
			$dtf .= '<option value="' . $r . '" ' . $chk . ' >' . $r . '</option>';
		}
		$dti = '<select name="dd10" id="typeid10">' . $dtf . '</select>';

		$dtf = '';
		for ($r = date("Y"); $r >= 1972; $r--) {
			$chk = '';
			if ($dd[11] == $r) { $chk = 'selected';
			}
			$dtf .= '<option value="' . $r . '" ' . $chk . '>' . $r . '</option>';
		}

		$dtf = '<select name="dd11" id="typeid11">' . $dtf . '</select>';
		$sx .= msg('publicacao_de') . ' ' . $dti . ' ' . msg('ate') . ' ' . $dtf;
		return ('<NOBR>' . $sx . '</nobr>');
	}

	function realce($txt, $term) {
		$sx = '';
		$term = troca($term, ' ', ';');
		$term = troca($term, chr(13), ';');
		$term = troca($term, chr(10), '');
		$term = splitx(';', $term);

		$txt = ' ' . $txt;
		$txta = uppercasesql($txt);
		for ($rx = 0; $rx < count($term); $rx++) {
			if (strlen($term[$rx]) > 2) {
				$txt = $txt;
				$termx = uppercasesql($term[$rx]);
				$terms = trim($term[$rx]);
				$mark_on = '<font style="background-color : Yellow;"><B>';
				$mark_off = '</B></font>';
				while (strpos($txta, $termx) > 0) {
					$xpos = strpos($txta, $termx);
					$txt = substr($txt, 0, $xpos) . $mark_on . substr($txt, $xpos, strlen($termx)) . $mark_off . substr($txt, $xpos + strlen($termx), strlen($txt));
					$txta = substr($txta, 0, $xpos) . $mark_on . strzero('0', strlen($termx)) . $mark_off . substr($txta, $xpos + strlen($termx), strlen($txt));
				}
			}
		}
		return ($txt);
	}

	function result_search($post = array()) {
		global $dd, $acao;
		$sx = '';
		$sx .= '<table border=1 class="lt1" width="100%">';
		$sx .= '<TR valign="top">';
		$sx .= '<TD>';
		$sx .= $this -> lang -> line('form_query') . '<B> ' . $dd[1] . '</B> ';
		/* realiza busca */
		$sx .= $this -> result_article($dd[1], $dd[20], $dd[21]);

		$sx .= '<TD width="120">';
		$sa = $this -> result_journals();
		$sa .= $this -> result_year();
		$sa .= $this -> result_author();
		$sa .= $this -> result_keyword();
		$sx .= $sa;
		$sx .= '</table>';
		return ($sx);
	}

	function result_search_keyword($key_cod = '') {
		global $dd, $acao;
		$sx = '';
		$sx .= '<table border=1 class="lt1" width="100%">';
		$sx .= '<TR valign="top">';
		$sx .= '<TD>';
		$sx .= $this -> lang -> line('form_found') . ' <B> ' . $dd[2] . '</B>';
		$sx .= $this -> result_article_key($key_cod);
		$sx .= '<TD width="120">';
		if (strlen($this -> query) > 0) {

			$sa = $this -> result_journals();
			$sa .= $this -> result_year();
			$sa .= $this -> result_author();
			$sa .= $this -> result_keyword();
			$sx .= $sa;
		}
		$sx .= '</table>';
		return ($sx);
	}

	function result_search_author($key_cod = '') {
		global $dd, $acao;
		$sx = '';
		$sx .= '<table border=1 class="lt1" width="100%">';
		$sx .= '<TR valign="top">';
		$sx .= '<TD>';
		$sx .= msg('find') . '<B> ' . $dd[2] . '</B>';
		$sx .= $this -> result_article_auth($key_cod);
		$sx .= '<TD width="120">';
		if (strlen($this -> query) > 0) {

			$sa = $this -> result_journals();
			$sa .= $this -> result_year();
			$sa .= $this -> result_author();
			$sa .= $this -> result_keyword();
			$sx .= $sa;
		}
		$sx .= '</table>';
		return ($sx);
	}

	function result_search_selected() {
		global $dd, $acao;
		$sx = '<table border=1 class="lt1" width="100%">';
		$sx .= '<TR valign="top">';
		$sx .= '<TD>';
		$sx .= msg('find') . '<B> ' . $dd[2] . '</B>';
		$sx .= $this -> result_article_selected($this -> session());

		$sx .= '<TD width="120">';
		$sa = $this -> result_journals();
		$sa .= $this -> result_year();
		$sa .= $this -> result_author();
		$sa .= $this -> result_keyword();
		$sx .= $sa;
		$sx .= '</table>';

		$sx .= $this -> result_author_network();
		$sx .= '<HR>';
		$sx .= $this -> result_keyword_network();
		return ($sx);
	}

}
?>
