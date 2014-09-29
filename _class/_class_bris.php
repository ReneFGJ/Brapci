<?php
class bris {
	var $iffa2 = 0;
	var $iffa3 = 0;
	var $iffa5 = 0;
	var $idh = 0;

	var $keys;

	function citacoes_do_autor_grafico($autor) {
		$ano = $this -> anos;
		$sql = "select * from bris_autor 
					where au_codigo = '$autor' 
					and au_ano >= (" . (date("Y") - $ano) . ")
					order by au_ano
					";
		$rlt = db_query($sql);
		/* Cria grafico */
		$multi= 5;
		$sx = '<Table width="500" class="lt0">';
		$sx .= '<TR><TD colspan=15 ><h2>Citações recebidas por ano</h2>';
		while ($line = db_read($rlt)) {
			$vlr2 = $line['au_autocitacao'];
			$vlr = $line['au_citacoes'] - $vlr2;
			
			$size = $vlr * $multi;
			$size2 = $vlr2 * $multi;
			$s1 .= '<td align="center" style="border-top: 1px solid #404040;">' . $line['au_ano'];
			$s2 .= '<td align="center" style="border-top: 1px solid #404040;">' . ($vlr + $vlr2);
			$s3 .= '<td align="center">';
			if ($size > 0) { $s3 .= '<img src="img/nada_azul.png" width="20" height="' . $size . '" title="Citacoes - '.$vlr.'"><BR>';
			}
			if ($size2 > 0) { $s3 .= '<img src="img/nada_vermelho.png" width="20" height="' . $size2 . '" title="Autocitação - '.$vlr2.'">';
			}
		}
		$sx .= '<TR valign="bottom">' . $s3 . '<TD height="100">';
		$sx .= '<TR>' . $s2;
		$sx .= '<TR>' . $s1;
		$sx .= '<TR><TD colspan=40 class="lt0">';
		$sx .= '<img src="img/nada_azul.png" width="10"> Citações recebidas<BR>';
		$sx .= '<img src="img/nada_vermelho.png" width="10"> Autocitações<BR>';
		$sx .= '</table>';
		return ($sx);
	}

	function processa_citacoes_do_autor() {
		$sql = "select * from bris_autor 
					where au_processado = 0 
					order by au_ano desc
					limit 50";
		$rlt = db_query($sql);
		$id = 0;
		$sx = '';
		while ($line = db_read($rlt)) {
			$id++;
			$id = $line['id_au'];
			$autor = $line['au_codigo'];
			$ano = $line['au_ano'];
			$sx .= '<BR>[' . $autor . ']';

			$cit = $this -> citacoes_do_autor($autor, $ano);
			$cited = $cit[0];
			$cited_auto = $cit[1];
			$sql = "update bris_autor set 
					au_processado = 1,
					au_citacoes = $cited,
					au_autocitacao = $cited_auto
					where id_au = " . round($id);
			$sx .= ' - ' . $sql;
			$rrr = db_query($sql);
		}
		$sql = "select count(*) as total from bris_autor where au_processado = 0 ";
		$rlt = db_query($sql);
		$line = db_read($rlt);
		echo '<h1>' . $line['total'] . '</h1>';
		echo $sx;
		return ($id);
	}

	function citacoes_do_autor($autor, $ano) {
		global $db_public;
		$sql = "
			select a1,a2, ae_author from (
			select brapci_article.ar_codigo as a1, citado.ar_codigo as a2
				FROM brapci_article
				INNER JOIN brapci_edition on ar_edition = ed_codigo and ed_ano = '$ano' 
				INNER JOIN brapci_article_author as citante on ar_codigo = citante.ae_article 
				INNER JOIN mar_works on ar_codigo = m_work 
    			INNER JOIN brapci_article as citado on m_bdoi = citado.ar_bdoi 
				INNER JOIN brapci_article_author as au_citado on citado.ar_codigo = au_citado.ae_article 
				WHERE ed_status <> 'X' and m_ano <= $ano and m_status <> 'X' and m_bdoi <> ''
    			and au_citado.ae_author = '$autor'
			) as resultado 
			left join brapci_article_author on ae_article = a1 and ae_author = '$autor'
			group by a1,a2	
			";
		$rlt = db_query($sql);
		$auto = 0;
		$cited = 0;
		while ($line = db_read($rlt)) {
			$autor2 = trim($line['ae_author']);
			if (strlen($autor2) > 0) {
				$auto = $auto + 1;
				$cited = $cited + 1;
			} else {
				$cited = $cited + 1;
			}
		}
		$rs = array($cited, $auto);
		return ($rs);
	}

	function journal_fi_issn($jnl) {
		$sql = "select * from bris_rank 
						where rk_journal = '" . $jnl . "' 
					order by rk_ano desc	
					";
		$rlt = db_query($sql);
		$sx = '<table class="tabela00 lt1">';
		$sx .= '<TR class="lt0"><TH>Ano</TH>
						<TH>FI2</TH>
						<TH>FI3</TH>
						<TH>FI5</TH>
						<TH>II</TH>
						<TH>Índice h</TH>';
		while ($line = db_read($rlt)) {
			$sx .= '<tr align="center">';
			$sx .= '<TD width="40" align="center">' . $line['rk_ano'];
			$sx .= '<TD width="60">' . number_format($line['rk_fi2'], 4, ',', '.') . '</td>';
			$sx .= '<TD width="60">' . number_format($line['rk_fi3'], 4, ',', '.') . '</td>';
			$sx .= '<TD width="60">' . number_format($line['rk_fi5'], 4, ',', '.') . '</td>';
			$sx .= '<TD width="60">' . number_format($line['rk_ii'], 4, ',', '.') . '</td>';
			$sx .= '<TD width="60">' . number_format($line['rk_h'], 0, ',', '.') . '</td>';
			$sx .= '</tr>';
		}
		$sx .= '</table>';
		return ($sx);
	}

	function journal_fi($ano = '', $tipo = 'J') {
		$cr = chr(13) . chr(10);
		$sql = "select * from bris_rank
						inner join brapci_journal on rk_journal = jnl_codigo 
						where rk_ano = '$ano' and jnl_tipo = '$tipo'
						order by rk_fi2 desc
					";
		$rlt = db_query($sql);
		$sx .= '<table width="98%" class="tabela00 lt1">';
		$sx .= '<thead>';
		$sx .= '<TR align="center"><TH align="center">Pos</TH>
						<TH align="center">Título<TH>ISSN</TH>
						<TH width="8%" align="center">FI (2anos)</TH>
						<TH width="8%" align="center">FI (3anos)</TH>
						<TH width="8%" align="center">FI (5anos)</TH>
						<TH width="8%" align="center">Impacto Imediato</TH>
					</tr>' . $cr;
		$sx .= '</thead>' . $cr;
		$sx .= '<tbody>' . $cr;
		$id = 0;
		while ($line = db_read($rlt)) {
			$link = '<A HREF="bris_journal_view.php?dd0=' . $line['id_jnl'] . '">';
			$id++;
			$sx .= '<TR>' . $cr;
			$sx .= '<TD align="center">' . $id . '.' . '</td>' . $cr;
			$sx .= '<TD>';
			$sx .= $link;
			$sx .= $line['jnl_nome'];
			$sx .= '</A>' . '</td>' . $cr;
			$sx .= '<TD align="center">';
			$sx .= $line['jnl_issn_impresso'] . '</td>' . $cr;
			$sx .= '<TD align="center">';
			$sx .= number_format($line['rk_fi2'], 4, ',', '.') . '</td>' . $cr;
			$sx .= '<TD align="center">';
			$sx .= number_format($line['rk_fi3'], 4, ',', '.') . '</td>' . $cr;
			$sx .= '<TD align="center">';
			$sx .= number_format($line['rk_fi5'], 4, ',', '.') . '</td>' . $cr;
			$sx .= '<TD align="center">';
			$sx .= number_format($line['rk_ii'], 4, ',', '.') . '</td>' . $cr;
			$sx .= '</tr>' . $cr;
		}
		$sx .= '</tbody>' . $cr;
		$sx .= '</table>' . $cr;
		return ($sx);
	}

	function fluxo_citacao($ano1 = '', $ano2 = '') {
		$ano1 = '2012';
		$ano2 = '2013';
		$sql = "select count(*) as total, cit_jnl_citado, cit_jnl_citante from bris_cited 
						where cit_ano_pub = '$ano1' or cit_ano_pub = '$ano2' 
						group by cit_jnl_citado, cit_jnl_citante
					";
		$rlt = db_query($sql);
		while ($line = db_read($rlt)) {
			echo '' . $line['cit_jnl_citado'] . ';' . $line['cit_jnl_citante'] . ';' . $line['total'];
			echo '<BR>';
		}
		return (1);
	}

	function mostra_fi($ano = '') {
		if (strlen($ano) == 0) {
			$sql = "select max(rk_ano) as ano from bris_rank ";
			$rlt = db_query($sql);
			$line = db_read($rlt);
			$ano = $line['ano'];
		}
		$sql = "select * from bris_rank 
						inner join brapci_journal on rk_journal = jnl_codigo
						where rk_ano = '$ano' and jnl_status = 'A'
						and jnl_tipo = 'J' and jnl_issn_impresso <> ''
					order by rk_h desc, jnl_nome
			";
		$rlt = db_query($sql);
		$sx .= '<table class="tabela00" width="100%">';
		$sx .= '<TR><TH>Pos<TH>ISSN<TH>Revista<TH>Índice h';
		$sx .= '<TH>Tot.<BR>Docs.<BR>(' . $ano . ')';
		$sx .= '<TH>Total<BR>Docs.<BR>(2anos)';
		$sx .= '<TH>Total<BR>Docs.<BR>(3anos)';
		$sx .= '<TH>Total<BR>Docs.<BR>(5anos)';
		$sx .= '<TH><I>iFI</I><BR>(2anos)';
		$sx .= '<TH><I>iFI</I><BR>(3anos)';
		$sx .= '<TH><I>iFI</I><BR>(5anos)';
		$sx .= '<TH>iII<BR>';
		$pos = 0;
		while ($line = db_read($rlt)) {
			$pos++;
			$sx .= '<TR>';
			$sx .= '<TD align="center">' . $pos;
			$sx .= '<TD>';
			$sx .= $line['jnl_issn_impresso'];
			$sx .= '<TD>';
			$sx .= $line['jnl_nome'];
			$sx .= '<TD align="center">';
			$sx .= $line['rk_h'];
			$sx .= '<TD align="center">';
			$sx .= $line['rk_art0'];
			$sx .= '<TD align="center">';
			$sx .= $line['rk_art2'];
			$sx .= '<TD align="center">';
			$sx .= $line['rk_art3'];
			$sx .= '<TD align="center">';
			$sx .= $line['rk_art5'];
			$sx .= '<TD align="center">';
			$sx .= number_format($line['rk_fi2'], 4, ',', '.');
			$sx .= '<TD align="center">';
			$sx .= number_format($line['rk_fi3'], 4, ',', '.');
			$sx .= '<TD align="center">';
			$sx .= number_format($line['rk_fi5'], 4, ',', '.');
			$sx .= '<TD align="center">';
			$sx .= number_format($line['rk_ii'], 4, ',', '.');

		}
		return ($sx);
	}

	function calcula_meia_vida($ano, $journal = '') {
		$sql = "SELECT count(*) as total, m_ano FROM `mar_works`
						inner join brapci_article on m_work = ar_codigo
						where ar_ano = '2013' and m_ano <= $ano
						and ar_journal_id = '$journal' and m_status <> 'X' 
						and ar_status <> 'X'
						group by m_ano
						order by m_ano desc
					";
		$m1 = array();

		$rlt = db_query($sql);
		$tot = 0;
		while ($line = db_read($rlt)) {
			$total = $line['total'];
			$tot = $tot + $total;
			$xano = $ano - $line['m_ano'];
			array_push($m1, array($xano, $total));
		}
		$vm = 0;
		$mt = ($tot / 2);
		$ma = 0;
		for ($r = 0; $r < count($m1); $r++) {
			$ma = $ma + $m1[$r][1];
			//echo '<BR>'.$m1[$r][1].'---'.$mt.'---'.$ma;
			if ($ma > $mt) {
				return ($r);
			}
		}
		return ($vm);
	}

	function calcula_fator_impacto_revista($codigo = '') {
		/* total de artigos publicados */
		$sql = "select * from bris_rank ";
		$rlt = db_query($sql);
		$sql = "";
		while ($line = db_read($rlt)) {
			$a0 = $line['rk_art0'];
			$a2 = $line['rk_art2'];
			$a3 = $line['rk_art3'];
			$a5 = $line['rk_art5'];

			$c0 = $line['rk_ct0'];
			$c1 = $line['rk_ct1'];
			$c2 = $line['rk_ct2'];
			$c3 = $line['rk_ct3'];
			$c4 = $line['rk_ct4'];
			$c5 = $line['rk_ct5'];

			$f0 = 0;
			$f2 = 0;
			$f3 = 0;
			$f5 = 0;
			echo '<BR>c1:' . $c1;
			echo ', c2:' . $c2;
			echo ', c3:' . $c3;
			echo ', c4:' . $c4;
			echo ', c5:' . $c5;

			echo ', a2:' . $a2;
			echo ', a3:' . $a3;
			echo ', a5:' . $a5;
			if ($a0 > 0) { $f0 = ($c0) / $a0;
			}
			if ($a2 > 0) { $f2 = ($c1 + $c2) / $a2;
			}
			if ($a3 > 0) { $f3 = ($c1 + $c2 + $c3) / $a3;
			}
			if ($a5 > 0) { $f5 = ($c1 + $c2 + $c3 + $c4 + $c5) / ($a5);
			}
			echo ' f2:' . $f2;
			echo ' f3:' . $f3;
			echo ' f5:' . $f5;
			$sql = "update bris_rank set
							rk_fi0 = $f0,
							rk_fi2 = $f2,
							rk_fi3 = $f3,
							rk_fi5 = $f5
							where id_rk = " . $line['id_rk'] . ';' . chr(13) . chr(10);
			$rr = db_query($sql);
		}
	}

	function indice_h_revista($ano) {
		$sql = "select * from (
						select max(rv_h) as rv_h, rv_codigo from bris_revista
						group by rv_codigo
						) as tabela
					inner join brapci_journal on rv_codigo = jnl_codigo
					where rv_h > 0
					order by rv_h desc
					";
		$rlt = db_query($sql);
		$sx .= '<table width="100%">';
		while ($line = db_read($rlt)) {
			$link = '<A HREF="index_about_journal.php?dd0=' . $line['jnl_codigo'] . '" target="_new">';
			$sx .= '<TR>';
			$sx .= '<TD align="center">';
			$sx .= $line['rv_h'];
			$sx .= '<TD>';
			$sx .= $link;
			$sx .= $line['jnl_issn_impresso'];
			$sx .= '</A>';
			$sx .= '<TD>';
			$sx .= $line['jnl_nome'];
		}
		$sx .= '</table>';
		return ($sx);
	}

	function calcular_indice_h_revista($codigo) {
		$ano = date("Y");
		$data = date("Ymd");

		$sql = "select count(*) as total, ar_codigo from mar_works
						inner join brapci_article on m_bdoi = ar_bdoi
					where ar_journal_id = '$codigo' and ar_status <> 'X' and m_bdoi <> ''
					group by ar_codigo
					order by total desc
			";
		$rlt = db_query($sql);
		$h = 0;
		$i = 0;
		$sx .= '<table class="tabela00">';
		$sx .= '<TR><TH>Índice h<TH>Journal';
		while ($line = db_read($rlt)) {
			$i++;
			$total = $line['total'];
			if ($total >= $i) { $h = $i;
			}
			$sx .= '<TR><TD>' . $i;
			$sx .= '<TD>' . $total;
		}
		$sx .= '<TR><TD colspan=2>h=' . $h;
		$sx .= '</table>';

		$sql = "select * from bris_revista where rv_codigo = '$codigo' 
						order by rv_ano desc ";
		$rlt = db_query($sql);
		if ($line = db_read($rlt)) {
			$data = date("Ymd");
			$sql = "update bris_revista set 
							rv_h = $h, 
							rv_update = $data
								where id_rv = " . $line['id_rv'];
			$xxx = db_query($sql);
		} else {
			$sql = "insert into bris_revista 
					(
						rv_codigo, 	rv_ano, rv_artigos, 
						rv_cr_i, 	rv_cr_2, 	rv_cr_3, 
						rv_cr_5, rv_tcr, rv_update, rv_h
					) values (
						'$codigo','$ano',0,
						0,0,0,
						0,0,$data,$h)
					";
			$xxx = db_query($sql);
		}
		$this -> idh = $h;
		return ($sx);

	}

	function indice_h_autor($ano) {
		$sql = "select * from (
						select max(au_h) as au_h, au_codigo from bris_autor
						group by au_codigo
						) as tabela
					inner join brapci_autor on autor_codigo = au_codigo
					where au_h > 0
					order by au_h desc
					";
		$rlt = db_query($sql);
		$sx .= '<table width="100%">';
		while ($line = db_read($rlt)) {
			$link = '<A HREF="author_view.php?dd0=' . $line['autor_codigo'] . '" target="_new">';
			$sx .= '<TR>';
			$sx .= '<TD align="center">';
			$sx .= $line['au_h'];
			$sx .= '<TD>';
			$sx .= $link;
			$sx .= $line['autor_nome'];
			$sx .= '</A>';
		}
		$sx .= '</table>';
		return ($sx);
	}

	function calcular_indice_h_autor($codigo) {
		$sql = "select cit_citado, cit_ano_art, count(*) as total from bris_cited 
						inner join brapci_article_author on ae_article = cit_citado
					where ae_author = '$codigo'
					group by cit_citado, cit_ano_art
					order by total desc
			";
		$rlt = db_query($sql);
		$h = 0;
		$i = 0;
		$sx .= '<table class="tabela00">';
		$sx .= '<TR><TH>Índice h<TH>Autor';
		while ($line = db_read($rlt)) {
			$i++;
			$total = $line['total'];
			if ($total >= $i) { $h = $i;
			}
			$sx .= '<TR><TD>' . $i;
			$sx .= '<TD>' . $total;
		}
		$sx .= '<TR><TD colspan=2>h=' . $h;
		$sx .= '</table>';

		$sql = "select * from bris_autor where au_codigo = '$codigo' 
						order by au_ano desc ";
		$rlt = db_query($sql);
		if ($line = db_read($rlt)) {
			$data = date("Ymd");
			$sql = "update bris_autor set 
							au_h = $h, 
							au_update = $data
								where id_au = " . $line['id_au'];
			$xxx = db_query($sql);
			echo '<BR>' . $sql;
		}

	}

	function artigo_mais_citado() {
		$sh = new publications;
		$sql = "select * from (
						select count(*) as total, m_bdoi from mar_works
						where m_bdoi <> '' and m_status <> 'X'
						group by m_bdoi
						order by total desc
						limit 100
						) as tabela
					inner join brapci_article on m_bdoi = ar_bdoi
					limit 100
			 ";
		$rlt = db_query($sql);
		$sx .= '<table width="100%">';
		while ($line = db_read($rlt)) {
			$sx .= $sh -> mostra_article_row($line);
			$sx .= '<TD align="center" class="lt4">';
			$sx .= $line['total'] . '<BR><font class="lt0">citações</font>';
		}
		$sx .= '</table>';
		return ($sx);
	}

	function calcula_citacoes_por_artigo() {
		/* Zera todas as citações */
		$sql = "update brapci_article set at_citacoes = 0 where at_citacoes <> 0 ";
		$rlt = db_query($sql);

		$sql = "select m_bdoi, count(*) as total from mar_works 
						where m_bdoi <> '' and m_status <> 'X' 
						group by m_bdoi ";
		$rlt = db_query($sql);
		while ($line = db_read($rlt)) {
			$art = trim($line['m_bdoi']);
			$total = $line['total'];
			$sql = "update brapci_article set at_citacoes = $total where ar_bdoi = '$art' ";
			$rrr = db_query($sql);
		}

		/* Zera todas as citações */
		$sql = "update bris_data set db_cited_recebidas = 0 where db_cited_recebidas <> 0 ";
		$rlt = db_query($sql);

		/* Calcula por fasciculo */
		$sql = "select sum(at_citacoes) as total, ar_edition
						from brapci_article where at_citacoes > 0
						group by ar_edition ";
		$rlt = db_query($sql);
		while ($line = db_read($rlt)) {
			$art = trim($line['ar_edition']);
			$total = $line['total'];
			$sql = "update bris_data set db_cited_recebidas = $total where db_fasciculo = '$art' ";
			$rrr = db_query($sql);
		}

	}

	function journal_fasciculos_articles($id) {
		$this -> calcula_citacoes_por_artigo();
		$sql = "select * from brapci_article 
					where ar_edition = '" . $id . "' 
					and ar_status <> 'X'	
					";
		$rlt = db_query($sql);
		while ($line = db_read($rlt)) {
			print_r($line);
			echo '<HR>';
		}

	}

	function show_keyswords() {
		$ky = $this -> keys;
		$sx .= '<table>';
		for ($r = 0; $r < count($ky); $r++) {
			$sx .= '<TR>';
			$sx .= '<TD>';
			$sx .= trim($ky[$r][0]);
			$sx .= '<TD>';
			$sx .= trim($ky[$r][1]);
		}
		$sx .= '</table>';
		return ($sx);
	}

	function show_cloud($type = '1') {
		$sx = '<div id="wordcloud1" class="wordcloud">' . chr(13) . chr(10);
		$ky = $this -> keys;
		for ($r = 0; $r < count($ky); $r++) {
			$vlr = $ky[$r][1];
			$vl = (int)(log($vlr) * 20);
			$sx .= '<span data-weight="' . ($vl) . '">' . $ky[$r][0] . '</span>' . chr(13) . chr(10);
		}
		$sx .= '</div>';
		return ($sx);
	}

	function indicador_keys($ano1 = '', $ano2 = '') {
		echo $ano1 . '--' . $ano2;
		if (strlen($ano1) == 0) {
			return (0);
		}
		if (strlen($ano2) == 0) { $ano2 = $ano1;
		}
		$sql = "
			select kw_word, total, kw_idioma from (
			SELECT kw_keyword, count(*) as total FROM `brapci_article_keyword`
			inner join brapci_article on ar_codigo = kw_article
			inner join brapci_journal on ar_journal_id = jnl_codigo
			where (ar_ano >= '$ano1' and ar_ano <= '$ano2') and ar_status <> 'X' and jnl_tipo = 'J'
			
			group by kw_keyword
			) as tabela01
			inner join brapci_keyword on kw_codigo = kw_keyword
			where total > 1 and kw_idioma = 'pt_BR' and kw_hidden = 0
			order by total desc				
		";
		$rlt = db_query($sql);
		$ky = array();
		$it = 0;
		while ($line = db_read($rlt)) {
			$key = trim($line['kw_word']);
			$tot = $line['total'];
			$it++;
			if (strlen($key) > 0) {

				array_push($ky, array($key, $tot));
			}
		}
		$this -> keys = $ky;
		return ($it);
	}

	function fi_journal($ano = '', $tipo = '3') {
		$ano = round($ano);

		/* Quantidade de publicações do ano */
		$wh = ' and ar_ano < ' . $ano . ' and ar_ano >= ' . ($ano - $tipo);
		if ($tipo == '0') { $wh = " and ar_ano = '$ano' ";
		}
		$sql = "    select total, ar_journal_id from (
						SELECT count(*) as total, ar_journal_id FROM brapci_article
						inner join brapci_edition on ar_edition = ed_codigo 
						inner join brapci_section on ar_section = se_codigo 
						where (se_tipo = 'B' and ar_status <> 'X')
    						$wh
						group by ar_journal_id
						) as tabela 
	    	    	  ";
		$rlt = db_query($sql);
		while ($line = db_read($rlt)) {
			$total = $line['total'];
			echo '<BR>' . $total;
			$this -> rank_atualiza($line['ar_journal_id'], $ano, $total, $tipo);
		}

	}

	function rank_atualiza($journal, $ano, $artigos, $tipo) {
		$t0 = 0;
		$t2 = 0;
		$t3 = 0;
		$t5 = 0;
		$f2 = 0;
		$f3 = 0;
		$f5 = 0;
		$q0 = 0;
		$q1 = 0;
		$q2 = 0;
		$q3 = 0;
		$q4 = 0;
		$q5 = 0;

		if ($tipo == '0') { $t0 = $artigos;
		}
		if ($tipo == '2') { $t2 = $artigos;
		}
		if ($tipo == '3') { $t3 = $artigos;
		}
		if ($tipo == '5') { $t5 = $artigos;
		}

		if ($tipo == '10') { $q0 = $artigos;
		}
		if ($tipo == '11') { $q1 = $artigos;
		}
		if ($tipo == '12') { $q2 = $artigos;
		}
		if ($tipo == '13') { $q3 = $artigos;
		}
		if ($tipo == '14') { $q4 = $artigos;
		}
		if ($tipo == '15') { $q5 = $artigos;
		}

		if ($tipo == '20') { $mv = $artigos;
		}

		$sql = "select * from bris_rank where rk_journal = '$journal' and rk_ano = '$ano' ";
		$rlt = db_query($sql);
		if ($line = db_read($rlt)) {
			$sql = "update bris_rank set ";
			if ($tipo == '3') { $sql .= "rk_art3 = " . $artigos;
			}
			if ($tipo == '2') { $sql .= "rk_art2 = " . $artigos;
			}
			if ($tipo == '5') { $sql .= "rk_art5 = " . $artigos;
			}
			if ($tipo == '0') { $sql .= "rk_art0 = " . $artigos;
			}

			if ($tipo == '10') { $sql .= "rk_ct0 = " . $artigos;
			}
			if ($tipo == '11') { $sql .= "rk_ct1 = " . $artigos;
			}
			if ($tipo == '12') { $sql .= "rk_ct2 = " . $artigos;
			}
			if ($tipo == '13') { $sql .= "rk_ct3 = " . $artigos;
			}
			if ($tipo == '14') { $sql .= "rk_ct4 = " . $artigos;
			}
			if ($tipo == '15') { $sql .= "rk_ct5 = " . $artigos;
			}
			if ($tipo == '20') { $sql .= "rk_mv = " . $artigos;
			}
			if ($tipo == '21') { $sql .= "rk_h = " . $artigos;
			}
			$sql .= " where id_rk = " . $line['id_rk'];

			$rlt = db_query($sql);
		} else {
			$sql = "insert into bris_rank 
					(	
					rk_journal , rk_ano , 
					rk_art2, rk_art3, rk_art5, rk_art0,
					rk_fi2, rk_fi3, rk_fi5, 
					rk_h, rk_issn
					) values (
					'$journal','$ano','$t2','$t3','$t5', '$t0',
					'$f2','$f3','$f4',
					0,'')";
			$rlt = db_query($sql);
		}
		echo '<BR><HR>' . $sql;
	}

	function grupos_iap($ano1 = '', $ano2 = '') {
		$wh = ' and ar_ano >= ' . $ano1 . ' and ar_ano <= ' . $ano2;
		$sql = "    select total, autor_codigo, autor_nome from (
						SELECT count(*) as total, ae_author FROM brapci_article_author
						inner join brapci_article on ar_codigo = ae_article
						inner join brapci_edition on ar_edition = ed_codigo 
						inner join brapci_section on ar_section = se_codigo 
						where (se_tipo = 'B' and ar_status <> 'X')
    						$wh
						group by ae_author
						) as tabela 
						inner join brapci_autor on ae_author = autor_codigo
						order by total desc
	    	    	  ";
		$rlt = db_query($sql);
		$sx = '<table width="100%" class="tabela00">';
		$tot = 0;
		$rank = 0;
		$xtota = 0;
		while ($line = db_read($rlt)) {
			$tot++;
			$tota = $line['total'];
			if ($tota != $xtota) { $rank++;
				$xtota = $tota;
			}
			$link = '<A HREF="author_view.php?dd0='.$line['autor_codigo'].'" class="bris_link" target="new_autor">';
			$sx .= '<TR>';
			$sx .= '<TD align="center" width="20">';
			$sx .= $rank;
			$sx .= '<TD align="center" width="20">';
			$sx .= $line['total'];
			$sx .= '<TD>';
			$sx .= $link.$line['autor_nome'].'</A>';
		}
		$sx .= '<TR><TD colspan=2>Total ' . $tot . ' autores';
		$sx .= '</table>';
		return ($sx);
	}

	function indicador_cc($ano1 = '', $ano2 = '') {
		$wh = ' and ar_ano >= 1900 ';
		$sql = "select count(*) as total, ar_ano, autores from (
    				select count(*) as autores, ae_article, ar_ano FROM brapci_article 
	    				inner join brapci_article_author on ar_codigo = ae_article 
	    	    	    inner join brapci_edition on ar_edition = ed_codigo 
	    	    	    inner join brapci_section on ar_section = se_codigo 
	    	    	    where (se_tipo = 'B' and ar_status <> 'X')
	    	    	    $wh
	    	    	    group by ae_article, ar_ano
    	    	    ) as tabela01
    	    	    group by ar_ano, autores
    	    	    order by ar_ano desc, autores ";

		$rlt = db_query($sql);
		$ar = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
		$rs = array();
		$xano = '';
		while ($line = db_read($rlt)) {
			$ano = $line['ar_ano'];
			if ($ano != $xano) {
				array_push($rs, $ar);
				$xano = $ano;
				$pos = (count($rs) - 1);
				$rs[$pos][0] = $ano;
			}
			$aut = round($line['autores']);
			$rs[$pos][$aut] = $line['total'];
		}
		$sx = '<table class="tabela00" border=1>';
		$sx .= '<TR><TH rowspan=2>Ano<TH colspan=20 align="center">quantidade de coautores</th>
				<TH align="center" colspan=5>Indicadores
				</tr>';
		$sx .= '<TR><TH>única<TH>2<TH>3<TH>4<TH>5<TH>6<TH>7<TH>8<TH>9<TH>10';
		$sx .= '<TH>11<TH>12<TH>13<TH>14<TH>15<TH>16<TH>17<TH>18<TH>19<TH>20';
		$sx .= '<TH>total<TH>autores<TH>iCI<TH>iDC<TH>iCC';
		for ($r = 1; $r < count($rs); $r++) {
			$sx .= '<TR>';
			$sx .= '<TD>' . $rs[$r][0];
			$iCC = 0;
			$iIC = 0;
			$iDC = 0;
			$tot = 0;
			for ($y = 1; $y < 21; $y++) {
				$tot = $tot + $rs[$r][$y];
				$iIC = $iIC + $rs[$r][$y] * $y;
				$iCC = $iCC + $rs[$r][$y] / $y;
				if ($y > 1) { $iDC = $iDC + $rs[$r][$y];
				}

				$sx .= '<TD width="30" align="center">' . $rs[$r][$y];
			}
			$sx .= '<TD width="30" align="center">' . number_format($tot, 0, ',', '.');
			$sx .= '<TD width="30" align="center">' . number_format($iIC, 0, ',', '.');
			$iIC = $iIC / $tot;
			$iDC = ($iDC / $tot);
			$iCC = 1 - ($iCC / $tot);
			$sx .= '<TD width="30">' . number_format($iIC, 4, ',', '.');
			$sx .= '<TD width="30">' . number_format($iDC, 4, ',', '.');
			$sx .= '<TD width="30">' . number_format($iCC, 4, ',', '.');

		}
		$sx .= '</table>';
		return ($sx);
	}

	function indicador_pa($ano = '') {
		$tipo = '1';
		$ano = '1972';
		$ano = date("Y");
		$sql = "SELECT count(*) as total, ed_ano from (
		select ed_ano FROM brapci_article 
		left join brapci_edition on ar_edition = ed_codigo 
		inner join brapci_section on ar_section = se_codigo
		";
		if ($tipo == '1') { $sql .= " where (se_tipo = 'B' and ar_status <> 'X' )";
		}
		if ($tipo == '2') { $sql .= " where (ar_status <> 'X' )";
		}
		if (($tipo != '1') and ($tipo != '2')) { $sql .= " where (se_tipo = '" . $tipo . "' and ar_status <> 'X' )";
		}

		if (strlen($ano1) > 0) { $sql .= " and ( ed_ano >= '" . $ano1 . "') ";
		}
		if (strlen($ano2) > 0) { $sql .= " and ( ed_ano <= '" . $ano2 . "') ";
		}
		$sql .= ") as tabela ";
		$sql .= " group by ed_ano ";
		$sql .= " order by ed_ano";
		$rlt = db_query($sql);
		$tot = 0;

		//ar_section = 'ARTIG' and
		$rlt = db_query($sql);
		$sx .= '<Table>';
		$google = '';
		while ($line = db_read($rlt)) {
			$total = $line['total'];
			$ano = $line['ed_ano'];
			if (strlen($google) > 0) { $google .= ', '; }
			$google .= " ['$ano', $total ] ";
			$sx .= '<TR><TD>' . $line['ed_ano'];
			$sx .= '    <TD>' . $line['total'];
		}
		$sx .= '</Table>';
		$this->google_data = $google;
		return ($sx);
	}

	function artigos_ano_citacao($ano, $tipo) {
		$sql = "select m_ano, count(*) as total from mar_works
					inner join brapci_article on m_work = ar_codigo 
						where ar_ano = '$ano' and m_tipo = '$tipo' and m_status <> 'X'
						group by m_tipo, m_ano
						order by m_ano desc, m_tipo";
		$rlt = db_query($sql);
		echo $sql;
		$ar = array();
		$an = array();

		$max = 10;
		while ($line = db_read($rlt)) {
			if ($max < $line['total']) { $max = $line['total'];
			}

			array_push($an, $line['m_ano']);
			array_push($ar, $line['total']);
		}
		$sx .= '<table width="98%">';
		$tot = 0;
		for ($r = 0; $r < count($an); $r++) {
			$tot = $tot + $ar[$r];
			$sx .= '<TR><TD>' . $an[$r];
			$sx .= '<TD align="center">' . $ar[$r];
			$sx .= '<TD>';
		}
		$sx .= '<TR><TD colspan=2><I>Total ' . $tot . '</I></TD></TR>';
		$sx .= '</table>';
		return ($sx);
		print_r($an);
		print_r($ar);

	}

	function tipologia_fontes($anoi, $anof) {
		/* LINKS */
		$sql = "update mar_works set m_tipo = 'LINK' where m_tipo = 'LINK0' ";
		$rlt = db_query($sql);

		/* LEI */
		$sql = "update mar_works set m_tipo = 'LEI' where m_tipo = 'LEI00' ";
		$rlt = db_query($sql);

		/* NC */
		$sql = "update mar_works set m_tipo = 'NC' where m_tipo = 'NC000' ";
		$rlt = db_query($sql);

		$sql = "select m_tipo , ar_ano, count(*) as total from mar_works
					inner join brapci_article on m_work = ar_codigo and m_status <> 'X' 
						where ar_ano >= $anoi and ar_ano <= $anof  
						group by m_tipo
						order by ar_ano, m_tipo";
		$rlt = db_query($sql);
		$sx = '<table class="tabela00" width="98%" align="center">';
		while ($line = db_read($rlt)) {
			$tot = $tot + $line['total'];
			$tipo = $line['m_tipo'];
			$ano = $line['ar_ano'];
			$total = $line['total'];
			$link = '<A HREF="indicador_tipologia_ano.php?dd0=' . $ano . '&dd1=' . $tipo . '">';
			$sa .= '<TD width="6%">' . $link . $tipo . '</A>';
			$sb .= '<TD align="center" class="tabela01">' . $total;
		}
		$sx .= '<TR align="center">' . $sa;
		$sx .= '<TD><B>Total</B>';
		$sx .= '<TR>' . $sb;
		$sx .= '<TD align="center" class="tabela01"><B>' . $tot . '</B>';
		$sx .= '</table>';
		return ($sx);
	}

	function mostra_indice_h($autor) {

	}

	function mostra_iffa($autor, $ano = '') {
		if (strlen($ano) > 0) { $wh = " and au_ano = '$ano' ";
		}
		$sql = "select * from bris_autor 
					where au_codigo = '$autor'
					$wh 
					order by au_ano desc ";
		$rlt = db_query($sql);
		if ($line = db_read($rlt)) {
			$fi2 = $line['au_cr_2'];
			$this -> iffa2 = number_format($fi2, 4, ',', '.');
			$fi2 = $line['au_cr_3'];
			$this -> iffa3 = number_format($fi3, 4, ',', '.');
			$fi2 = $line['au_cr_5'];
			$this -> iffa5 = number_format($fi5, 4, ',', '.');
			$h = $line['au_h'];
			$this -> idh = number_format($h, 0, ',', '.');
		}
	}

	function indicador_autor($autor) {
		$ano = '2013';

		/* iCPA */
		$icpa = $this -> mostra_icpa($autor);

		/* IFFA */
		$this -> mostra_iffa($autor, $ano);

		$sx = '<table width="200">';
		$sx .= '<TR><TH colspan=2 align="center">Indicador</TH></TR>';

		$sx .= '<TR><TD WIDTH="50%"><TD value="50%">';
		$sx .= '<TR><TD align="right">iCPA' . $icpa;

		$sx .= '<TR><TD WIDTH="50%"><TD value="50%">';
		$sx .= '<TR><TD align="right">FI (2 anos)<TD align="center">' . $this -> iffa2;

		$sx .= '<TR><TD WIDTH="50%"><TD value="50%">';
		$sx .= '<TR><TD align="right">FI (3 anos)<TD align="center">' . $this -> iffa3;

		$sx .= '<TR><TD WIDTH="50%"><TD value="50%">';
		$sx .= '<TR><TD align="right">FI (5 anos)<TD align="center">' . $this -> iffa5;

		$sx .= '<TR><TD WIDTH="50%"><TD value="50%">';
		$sx .= '<TR><TD align="right">Índice h<TD align="center">' . $this -> idh;

		$sx .= '</table>';
		return ($sx);
	}

	function citacoes_por_ano() {
		$sql = "select count(*) as total, m_bdoi, ar_codigo from mar_works
					inner join brapci_article on m_bdoi = ar_bdoi 
						where m_bdoi <> '' group by m_bdoi, ar_codigo ";
		$rlt = db_query($sql);

		while ($line = db_read($rlt)) {
			$total = $line['total'];
			$artigo = $line['ar_codigo'];
			$this -> atualiza_citacoes($artigo, $total, '0000', '');
		}
	}

	function citacoes_por_ano_base() {
		$sql = "delete from bris_cited where 1=1 ";
		$rlt = db_query($sql);

		$sql = "select ed_ano, substr(m_bdoi,1,4) as cited,
						m_work, t2.ar_codigo as origem,
						t1.ar_journal_id as jnl1,
						t2.ar_journal_id as jnl2  
						from mar_works
					inner join brapci_article as t1 on m_work = t1.ar_codigo 
    				inner join brapci_edition on t1.ar_edition = ed_codigo
    				inner join brapci_article as t2 on m_bdoi = t2.ar_bdoi
					where m_bdoi <> '' 
					group by ed_ano, m_bdoi, t1.ar_codigo, t2.ar_codigo, jnl1, jnl2
					order by ed_ano desc
				";
		$rlt = db_query($sql);

		while ($line = db_read($rlt)) {
			$ano = $line['ed_ano'];
			$ano2 = $line['cited'];

			$citante = $line['m_work'];
			$citado = $line['origem'];

			$jnl1 = $line['jnl2'];
			$jnl2 = $line['jnl1'];
			$this -> isere_citacao_relacao($ano, $citante, $ano2, $citado, $jnl1, $jnl2);
		}
	}

	function isere_citacao_relacao($a1, $c1, $a3, $c2, $j1, $j2) {
		$data = date("Ymd");
		$sql = "insert into bris_cited (cit_ano_pub, cit_ano_art, cit_publico, cit_citado, cit_update, cit_jnl_citado, cit_jnl_citante) 
						values
						('$a1','$a3','$c1','$c2',$data, '$j1', '$j2');
			";
		$rlt = db_query($sql);
	}

	function atualiza_citacoes($artigo, $total, $ano, $autor = '') {
		$data = date("Ymd");

		$sql = "select * from bris_article 
						where ca_artigo = '" . $artigo . "' and ca_autor = '$autor'
			";
		$rlt = db_query($sql);
		if ($line = db_read($rlt)) {
			$sql = "update bris_article set  
						ca_citacoes = $total,
						ca_update = $data
						where id_ca = " . $line['id_ca'];
		} else {
			$sql = "insert into bris_article
							(
								ca_artigo, ca_ano, ca_citacoes,
								ca_update, ca_autor 
							) values (
								'$artigo','$ano','$total',
								$data, '$autor'
							)					
					";
			$rlt = db_query($sql);
		}
	}

	function ranking_author($ano = '', $anof = '') {
		if (strlen($anof) == 0) { $anof = $ano;
		}
		$sql = "select sum(au_artigos) as au_artigos, autor_nome, autor_codigo from bris_autor
						left join brapci_autor on au_codigo = autor_codigo 
						where au_ano >= '$ano' and au_ano <= $anof
						group by autor_nome, autor_codigo
						order by au_artigos desc
						limit 200
						";
		$rlt = db_query($sql);

		$sx .= '<table width="100%" align="center" border=0 class="tabela00" cellpadding=0 cellspacing=0 > ';
		$sx .= '<TR>
						<TH>Autor
						<TH>Trabalhos
						<TH>índice h
						<TH>Citações recebidas
						<TH>Autocitações
						<TH>Citações sem autocitação
			';
		while ($line = db_read($rlt)) {
			$link = '<A HREF="author_view.php?dd0=' . $line['autor_codigo'] . '" target="_new" class="bris_link">';
			$sx .= '<TR>';
			$sx .= '<TD align="left" class="tabela01">';
			$sx .= $link . $line['autor_nome'] . '</A>';
			$sx .= '<TD width="8%" align="center" class="tabela01">';
			$sx .= $line['au_artigos'];
			$sx .= '<TD width="8%" align="center" class="tabela01">' . $h;
			$sx .= '<TD width="8%" align="center" class="tabela01">' . $ii;
			$sx .= '<TD width="8%" align="center" class="tabela01">' . $fi2;
			$sx .= '<TD width="8%" align="center" class="tabela01">' . $fi3;
		}
		$sx .= '</table>';
		return ($sx);
	}

	function ranking_author_create($ano = '') {
		/* Metodo 23-09-2014 */
		$sql = "
				SELECT count(*) as total, ae_author  
				FROM brapci_article 
					INNER JOIN brapci_edition on ar_edition = ed_codigo
					INNER JOIN brapci_article_author on ar_codigo = ae_article
					INNER JOIN brapci_journal on ae_journal_id = jnl_codigo
		
				WHERE ar_status <> 'X' and ed_ano = '$ano' 
				and ae_author = '0002870'
					
				GROUP BY ae_author
				ORDER BY total desc, ae_author
				";
		$rlt = db_query($sql);

		while ($line = db_read($rlt)) {
			print_r($line);
			echo '<HR>';
			$autor = trim($line['ae_author']);
			$total = trim($line['total']);
			$this -> atualiza_producao($autor, $ano, $total);
		}

		/* Atualiza citacoes */

	}

	function atualiza_producao($autor, $ano, $total) {
		$sql = "select * from bris_autor
						where au_codigo = '$autor'
						and au_ano = '$ano'
					";
		$rlt = db_query($sql);
		$data = date("Ymd");
		if (strlen($autor) > 0) {
			if ($line = db_read($rlt)) {
				$sql = "update bris_autor set 
									au_artigos = '$total',
									au_update = $data
								where id_au = " . $line['id_au'];
			} else {
				$sql = "insert into bris_autor 
								(
								au_codigo, au_ano, au_artigos,
								au_cr_i, au_cr_2, au_cr_3,
								au_cr_5, au_tcr, au_update
								) values (
								'$autor','$ano',$total,
								0,0,0,
								0,0,$data)
						";
			}
			$rlt = db_query($sql);
		}
		return (1);

	}

	function journal_cited_by_years($ano = 0, $tipo = 'S', $anos) {
		if ($tipo == 'A') { $wh2 = '';
		} else { $wh2 = " and ((m_tipo = 'ARTIC') or (m_tipo = 'PERIO')) ";
		}

		$wh2 = " and ((m_tipo = 'LIVRO') or (m_tipo = 'CAPIT')) ";

		if ($tipo == 'S') { $wh3 = " where (m_processar = '$tipo' ) ";
		}
		if ($tipo == 'O') { $wh3 = " where (m_processar <> 'S' ) ";
		}

		//$wh2 = " and ((m_tipo = 'LIVRO') or (m_tipo = 'CAPIT')) ";
		$sql = "select sum(total) as total, m_ano from (
					SELECT * from (
						SELECT m_ano, m_journal, count(*) as total FROM mar_works 
						INNER JOIN brapci_article on m_work = ar_codigo
						INNER JOIN brapci_edition on ed_codigo = ar_edition
					WHERE ed_ano = '$ano' and m_status <> 'X'
						$wh2
						GROUP BY m_journal, m_ano
    				) as tabela
					LEFT JOIN mar_journal on m_journal = mj_codigo
					 $wh3 ) as tabela
					 group by m_ano
					 order by m_ano 
					  ";
		$rlt = db_query($sql);

		$sx = '<h3>Ano Base: ' . $ano . ' (' . $anos . ' anos)</h3>';

		$sx .= '<table width="50%" align="center" border=0 class="tabela00" cellpadding=0 cellspacing=0 > ';
		$tot = 0;
		$id = 0;
		$max = 100;
		while ($line = db_read($rlt)) {
			$id++;
			$tot = $tot + $line['total'];
			$vano = $line['total'];
			if ($vano > 0) {
				$wdt = (int)(($vano / $max) * 200);
			}
			if ($line['m_ano'] > (date("Y") - 50)) {
				$rr .= '<TR><TD>' . $line['m_ano'] . '<TD>' . $line['total'];
				$r1 .= '<TD ><font class="lt0">' . substr($line['m_ano'], 2, 2) . '</font>';
				$r2 .= '<TD class="tabela01" align="center"><font style="font-size:8px;">' . $line['total'] . '</font>';
				$r3 .= '<TD class="tabela01 lt0" width="3%">' . '<img src="img/nada_verde.png" width="18" height="' . $wdt . '">';
			}
		}
		$sx .= '<TR valign="bottom"><TD>' . $r3;
		$sx .= '<TR><TD class="lt0"><NOBR>Qtda. citações</NOBR>' . $r2;
		$sx .= '<TR><TD class="lt0"><NOBR>ano da ref. citada</NOBR>' . $r1;
		$sx .= '<TR><TD colspan=10><I>Total ' . $tot . '</I>';
		$sx .= '</table>';
		$sx .= '<BR><BR>';
		$sx .= '<table border=1 class="lt0">' . $rr . '</table>';
		return ($sx);
	}

	function journal_cited_by_year($ano = 0, $tipo = 'S', $anos) {
		if ($tipo == 'A') { $wh2 = '';
		} else { $wh2 = " and ((m_tipo = 'ARTIC') or (m_tipo = 'PERIO')) ";
		}

		if ($anos > 0) {
			$wh2 .= ' and (';
			$id = 0;
			for ($r = ($ano - 1); $r > ($ano - 1 - $anos); $r--) {
				if ($id > 0) { $wh2 .= ' or ';
				}
				$wh2 .= '(m_ano = ' . $r . ')';
				$id++;
			}
			$wh2 .= ')';
		}
		if ($tipo == 'S') { $wh3 = " where (m_processar = '$tipo' ) ";
		}
		if ($tipo == 'O') { $wh3 = " where (m_processar <> 'S' ) ";
		}

		//$wh2 = " and ((m_tipo = 'LIVRO') or (m_tipo = 'CAPIT')) ";
		$sql = "SELECT * from (
						SELECT ed_ano,m_journal, count(*) as total FROM mar_works 
						INNER JOIN brapci_article on m_work = ar_codigo
						INNER JOIN brapci_edition on ed_codigo = ar_edition
					WHERE ed_ano = '$ano' and m_status <> 'X'
						$wh2
						GROUP BY ed_ano , m_journal
    				) as tabela
					LEFT JOIN mar_journal on m_journal = mj_codigo
					 $wh3
					ORDER BY total desc, ed_ano desc, mj_nome ";
		$rlt = db_query($sql);

		$sx = '<h3>Ano Base: ' . $ano . ' (' . $anos . ' anos)</h3>';

		$sx .= '<table width="100%" class="tabela00">';
		$sx .= '<TR><TH>Pos<TH>Citações<TH>Publicação<TH>Tipo';
		$tot = 0;
		$id = 0;
		while ($line = db_read($rlt)) {
			$id++;
			$tot = $tot + $line['total'];
			$sx .= '<TR>';
			$sx .= '<TD class="tabela01" align="center">';
			$sx .= $id . '.';
			$sx .= '<TD class="tabela01" align="center">';
			$sx .= $line['total'];
			$sx .= '<TD class="tabela01">';
			$nome = trim($line['mj_nome']);
			$nome = troca($nome, ',', ' ');
			$nome = troca($nome, '.', ' ');
			$nome = trim($nome);
			$sx .= $nome;
			$sx .= '<TD class="tabela01">';
			$sx .= $line['mj_tipo'];
			$sx .= '<TD class="tabela01" align="center">';
			$sx .= $line['m_processar'];

			$sx .= '<TD class="tabela01" align="center">';
			$sx .= $line['m_journal'];
			$sx .= '<TD class="tabela01" align="center">';
			$sx .= $line['mj_use'];
		}
		$sx .= '<TR><TD colspan=10><I>Total ' . $tot . '</I>';
		$sx .= '</table>';
		return ($sx);
	}

	function journal_fasciculos($jid, $ano = 0) {
		$sql = "
					SELECT * FROM bris_data 
					inner join brapci_journal on db_journal = jnl_codigo
					inner join brapci_edition on db_fasciculo = ed_codigo
					where db_journal = '" . strzero($jid, 7) . "' and db_ano = '" . $ano . "'
					order by db_ano desc, ed_vol desc, ed_nr desc
			";
		$rlt = db_query($sql);
		$sx = '<table width="100%">';
		$sx .= '<TR class="lt0"><TH width="10%">ISSN
						<TH width="65%">Journal Name
						<TH width="5%">Ano
						<TH width="5%">Vol.
						<TH width="5%">Nr.
						<TH width="5%">Artigos
						<TH width="5%">Citações concedidas
						<TH width="5%">Citações recebidas
						<TH width="5%">média art./fasc.
						<TH width="5%">média ref./artigos';
		while ($line = db_read($rlt)) {
			$link = '<A HREF="index_edition_journal.php?dd0=' . $line['ed_codigo'] . '&dd1=' . $jid . '" target="_new_issue">';
			$n1 = $line['db_fasciculo'];
			$n2 = $line['db_artigos'];
			$m2 = $line['db_cited_concedidas'];
			$sx .= '<TR>';
			$sx .= '<TD align="center" class="tabela01">' . $line['jnl_issn_impresso'];
			$sx .= '<TD align="left" class="tabela01">' . $line['jnl_nome'];
			$sx .= '<TD align="center" class="tabela01">' . $link . $line['db_ano'] . '</A>';
			$sx .= '<TD align="center" class="tabela01">' . $link . $line['ed_vol'] . '</A>';
			$sx .= '<TD align="center" class="tabela01">' . $link . $line['ed_nr'] . '</A>';

			$sx .= '<TD align="center" class="tabela01">' . $line['db_artigos'];
			$sx .= '<TD align="center" class="tabela01">' . $line['db_cited_concedidas'];
			$sx .= '<TD align="center" class="tabela01">' . $line['db_cited_recebidas'];
			$media = '-';
			if ($n1 > 0) {
				$media = $n2 / $n1;
				$media = number_format($media, 1, ',', '.');
			}
			$sx .= '<TD align="center" class="tabela01">' . $media;

			$media = '-';
			if ($n1 > 0) {
				$media = $m2 / $n2;
				$media = number_format($media, 1, ',', '.');
			}
			$sx .= '<TD align="center" class="tabela01">' . $media;
		}
		$sx .= '</table>';
		return ($sx);
	}

	function journal_anos($jid) {
		$sql = "select * from 
					(
						SELECT count(*) as fasciculo, db_ano, sum(db_artigos) as db_artigos,
							sum(db_cited_concedidas) as db_cited_concedidas, sum(db_cited_recebidas) as db_cited_recebidas, 
							db_journal
					    	FROM bris_data 
					    	group by db_journal, db_ano) as tabela
					inner join brapci_journal on db_journal = jnl_codigo
					where db_journal = '" . strzero($jid, 7) . "'
					order by db_ano desc
			";
		$rlt = db_query($sql);
		$sx = '<table width="100%">';
		$sx .= '<TR class="lt0"><TH width="10%">ISSN
						<TH width="65%">Journal Name
						<TH width="5%">Ano
						<TH width="5%">Fasciculos
						<TH width="5%">Artigos
						<TH width="5%">Citações concedidas
						<TH width="5%">Citações recebidas
						<TH width="5%">média art./fasc.
						<TH width="5%">média ref./artigos';
		while ($line = db_read($rlt)) {
			$link = '<A HREF="index_issue_journal.php?dd0=' . $line['db_ano'] . '&dd1=' . $jid . '" target="_new_issue">';
			$n1 = $line['fasciculo'];
			$n2 = $line['db_artigos'];
			$m2 = $line['db_cited_concedidas'];
			$sx .= '<TR>';
			$sx .= '<TD align="center" class="tabela01">' . $line['jnl_issn_impresso'];
			$sx .= '<TD align="left" class="tabela01">' . $line['jnl_nome'];
			$sx .= '<TD align="center" class="tabela01">' . $link . $line['db_ano'] . '</A>';

			$sx .= '<TD align="center" class="tabela01">' . $line['fasciculo'];
			$sx .= '<TD align="center" class="tabela01">' . $line['db_artigos'];
			$sx .= '<TD align="center" class="tabela01">' . $line['db_cited_concedidas'];
			$sx .= '<TD align="center" class="tabela01"> ' . $line['db_cited_recebidas'];
			$media = '-';
			if ($n1 > 0) {
				$media = $n2 / $n1;
				$media = number_format($media, 1, ',', '.');
			}
			$sx .= '<TD align="center" class="tabela01">' . $media;

			$media = '-';
			if ($n1 > 0) {
				$media = $m2 / $n2;
				$media = number_format($media, 1, ',', '.');
			}
			$sx .= '<TD align="center" class="tabela01">' . $media;
		}
		$sx .= '</table>';
		return ($sx);
	}

	function journal_fasciculos_insert($jid) {
		$sql = "select ar_edition as fasciculos, sum(total) as artigos, ed_ano
					from (
						select count(*) as total, ar_edition, ed_ano
							from brapci_article
						inner join brapci_edition on ar_edition = ed_codigo
						where ar_journal_id = '" . strzero($jid, 7) . "' and
							(ar_status <> 'X') and
							((ar_tipo is null) or ar_tipo = '' or ar_tipo = 'ARTIG' or ar_tipo = 'REVIS' or ar_tipo = 'COMUN' or ar_tipo = 'RELAT')
						group by ar_edition, ed_ano
					) as tabela
					group by ed_ano, fasciculos
					order by ed_ano desc
					";
		$rlt = db_query($sql);
		while ($line = db_read($rlt)) {
			$ano = $line['ed_ano'];
			$fas = $line['fasciculos'];
			$art = $line['artigos'];
			$jou = strzero($jid, 7);
			$this -> journal_data($ano, $fas, $art, $jou);
		}

		$sql = "
				SELECT count(*) as refs, ed_ano, ar_edition
				FROM brapci_article
					left join mar_works on m_work = ar_codigo
					inner join brapci_edition on ar_edition = ed_codigo
					WHERE ar_journal_id = '" . strzero($jid, 7) . "' and m_status <> 'X'
				group by ed_ano, ar_edition		
			";
		$rlt = db_query($sql);
		while ($line = db_read($rlt)) {
			$ano = $line['ed_ano'];
			$art = $line['refs'];
			$jou = strzero($jid, 7);
			$edition = $line['ar_edition'];
			$this -> journal_citacoes($ano, $jou, $art, $edition);
		}
	}

	function journal_citacoes($ano, $jid, $refs = 0, $edition) {
		$sql = "select * from bris_data 
					where db_journal = '$jid' and db_ano = '$ano' and db_fasciculo = '$edition'";
		$rlt = db_query($sql);
		$data = date("Ymd");
		if ($line = db_read($rlt)) {
			$sql = "update bris_data set 
								db_cited_concedidas = " . $refs . "
							where id_db = " . $line['id_db'];
			$rlt = db_query($sql);
		}
		return (1);
	}

	function journal_data($ano, $fasciculo, $artigos, $jid, $refs = 0) {
		$sql = "select * from bris_data 
					where db_journal = '$jid' and db_ano = '$ano' and db_fasciculo = '$fasciculo' ";
		$rlt = db_query($sql);
		$data = date("Ymd");
		if ($line = db_read($rlt)) {
			$sql = "update bris_data set 
								db_artigos = '" . $artigos . "',
								db_update = " . $data . "
							where id_db = " . $line['id_db'];
		} else {
			$sql = "insert into bris_data
							(
								db_journal, db_ano, db_fasciculo,
								db_artigos, db_update, db_cited_concedidas
							) values (
								'$jid','$ano','$fasciculo',
								'$artigos','$data','0'
							)
					";
		}
		$rlt = db_query($sql);
		//echo '<HR>'.$sql;
	}

	function journal_ranking($ano = 2013) {
		$sx .= '<table>';
		$sx .= '<TR>';
		$sx .= '<TH>ISSN';
		$sx .= '<TH>Journal name';
		$sx .= '<TH class="lt0">Cidade/Região';
		$sx .= '<TH width="5%" class="lt0">BRIS';
		$sx .= '<TH width="5%" class="lt0">Fator de Impacto<BR>2 anos';
		$sx .= '<TH width="5%" class="lt0">Fator de Impacto<BR>3 anos';
		$sx .= '<TH width="5%" class="lt0">Fator de Impacto<BR>5 anos';
		$sx .= '<TH width="5%" class="lt0">Impacto Imediato';
		$sx .= '<TH width="5%" class="lt0">Índice h';
		$sx .= '<TH width="5%" class="lt0">Meia Vida';
		$sx .= '<TH width="5%" class="lt0">Auto citação';

		$sql = "select * from brapci_journal
					left join bris_rank on rk_journal = jnl_codigo 
					left join ajax_cidade on jnl_cidade = cidade_codigo
					where jnl_tipo = 'J' and rk_ano = '$ano'
					order by jnl_nome 
				";

		$rlt = db_query($sql);
		$it = 0;
		while ($line = db_read($rlt)) {
			$link = '<A HREF="index_about_journal.php?dd0=' . $line['id_jnl'] . '" target="_new" class="bris_link">';
			$it++;
			$sx .= '<TR>';
			$sx .= '<TD class="tabela01" align="center"><nobr>';
			$sx .= $line['jnl_issn_impresso'] . '</nobr>';
			$sx .= '<TD class="tabela01">';
			$sx .= $link . $line['jnl_nome'] . '</A>';

			$sx .= '<TD class="tabela01">';
			$sx .= $line['cidade_nome'];

			$sx .= '<TD class="tabela01" align="center">';
			$sx .= number_format($bris, 4, ',', '.');

			$sx .= '<TD class="tabela01" align="center">';
			$sx .= number_format($line['rk_fi2'], 4, ',', '.');

			$sx .= '<TD class="tabela01" align="center">';
			$sx .= number_format($line['rk_fi3'], 4, ',', '.');

			$sx .= '<TD class="tabela01" align="center">';
			$sx .= number_format($line['rk_fi5'], 4, ',', '.');

			$sx .= '<TD class="tabela01" align="center">';
			$sx .= number_format($line['rk_ii'], 4, ',', '.');

			$sx .= '<TD class="tabela01" align="center">';
			$sx .= number_format($line['rk_h'], 0, ',', '.');

			$sx .= '<TD class="tabela01" align="center">';
			if ($line['rk_mv'] == 0) {
				$sx .= '-';
			} else {
				$sx .= number_format($line['rk_mv'], 0, ',', '.');
			}
			$sx .= '<TD class="tabela01" align="center">';
			$sx .= number_format($line['rk_ii'], 0, ',', '.');
		}
		$sx .= '<TR><TD colspan=10>Total de ' . $it . ' periódicos';
		$sx .= '</table>';
		return ($sx);
	}

	function grupos_icpa_mostra($ano, $tipo) {
		$sql = "select * from bris_icpa 
						inner join brapci_autor on icpa_autor = autor_codigo
						where icpa_ano = '$ano'
						and icpa_indice = '$tipo' 
						and autor_tipo = 'A'
					order by autor_nome ";
		$tot = 0;
		$rlt = db_query($sql);
		$sx = '<table class="tabela00" width="100%">';
		$sx .= '<tr><th width="50%">Nome do pesquisador
					<TH width="10%">Revistas<BR>publicadas
					<TH width="10%">Total artigos
					<TH width="10%">Vmax
					<TH width="10%">iCPA %
					<TH width="10%">iCPA
					';
		while ($line = db_read($rlt)) {
			$tot++;
			$link = '<A HREF="author_view.php?dd0=' . $line['autor_codigo'] . '" target="_new" class="bris_link">';
			$sx .= '<TR>';
			$sx .= '<TD>';
			$sx .= $link;
			$sx .= trim($line['autor_nome']) . '</A>';
			$sx .= '<TD align="center">';
			$sx .= $line['icpa_revistas'];
			$sx .= '<TD align="center">';
			$sx .= $line['icpa_artigos'];
			$sx .= '<TD align="center">';
			$sx .= $line['icpa_maximo'];
			$sx .= '<TD align="center">';
			$sx .= number_format(100 * $line['icpa_maximo'] / $line['icpa_artigos'], 1, ',', '.') . '%';
			$sx .= $this -> fmt_icpa($line['icpa_indice']);
		}
		$sx .= '<TR><TD colspan="10">';
		$sx .= '<i>Total ' . $tot . '</i>';
		$sx .= '</table>';
		return ($sx);
	}

	function fmt_icpa($id) {
		switch ($id) {
			case '0' :
				$sx .= '<TD bgcolor="white" align="center" width="80">NC</TD>';
				break;
			case '1' :
				$sx .= '<TD bgcolor="#C0FFC0" align="center" width="80">BAIXO</TD>';
				break;
			case '2' :
				$sx .= '<TD bgcolor="#C0C0FF" align="center" width="80">MÉDIO</TD>';
				break;
			case '3' :
				$sx .= '<TD bgcolor="#FFC0C0" align="center" width="80">ALTO</TD>';
				break;
		}
		return ($sx);
	}

	function mostra_icpa($autor = '') {
		$sql = "select * from bris_icpa where icpa_autor = '$autor' order by icpa_ano limit 1";
		$rlt = db_query($sql);
		$line = db_read($rlt);
		$id = $line['icpa_indice'];
		$sx = $this -> fmt_icpa($id);
		$this -> autor_icpa = $id;
		return ($sx);
	}

	function indicador_imv($ano, $tipo = '') {
		$sx .= '<table>';
		$sx .= '<TR><TD>';
		$sx .= $this -> indicador_imv_open_data($ano, $ano, '0');
		$sx .= '<TD>';
		$sx .= $this -> indicador_imv_open_data($ano, $ano, '2');
		$sx .= '</table>';
		return ($sx);
	}

	function indicador_imv_open_data($ano = '', $ano2 = '2013', $tipo = '1') {
		$wh = " and (m_tipo = 'ARTIC' or m_tipo = 'LIVRO') ";
		$wh = '';
		/* Somente artigos */
		if ($tipo == '2') { $wh = " and (m_tipo = 'ARTIC') ";
		}
		$sql = "SELECT count(*) as total, ar_ano, m_ano, m_tipo FROM mar_works 
					inner join brapci_article on m_work = ar_codigo
					WHERE (ar_ano = $ano or ar_ano = $ano2) 
					and ar_tipo = 'ARTIG' and ar_status <> 'X'
					$wh
					group by m_ano, m_tipo, ar_ano
					order by m_ano desc, m_tipo, ar_ano";
		$rlt = db_query($sql);
		$arti = array();
		$livr = array();
		$anais = array();
		$tese = array();
		$xano = round($ano);
		if ($ano2 > $xano) { $xano = $ano;
		}

		for ($r = 0; $r <= 57; $r++) {
			array_push($livr, 0);
			array_push($arti, 0);
			array_push($anais, 0);
			array_push($tese, 0);
		}
		while ($line = db_read($rlt)) {
			$ano1 = round($line['m_ano']);
			$ano2 = round($line['ar_ano']);

			$dif = $ano2 - $ano1;
			if (($dif > 50) and ($dif < 100)) {
				$dif = $dif - 50;
				$dif = (int)($dif / 10) + 50;
			}
			/* Acima de 100 anos e inferior a 300 */
			if (($dif >= 100) and ($dif <= 300)) { $dfi = 56;
			}
			/* Fonte com problemas de data */
			if (($dif < -2) and ($dif > 300)) { $dfi = 57;
			}

			$tp = $line['m_tipo'];
			$total = $line['total'];
			/* Todas as fontes */
			if ($tp == 0) { $tp = 'ALL';
			}
			if ($dif >= 0 and $dif <= 80) {
				switch ($tp) {
					case 'ALL' :
						$arti[$dif] = $arti[$dif] + $total;
						break;
					case 'ARTIC' :
						$arti[$dif] = $arti[$dif] + $total;
						break;
					case 'LIVRO' :
						$livr[$dif] = $livr[$dif] + $total;
						break;
					case 'ANAIS' :
						$anais[$dif] = $anais[$dif] + $total;
						break;
					case 'TESE' :
						$tese[$dif] = $tese[$dif] + $total;
						break;
					case 'DISSE' :
						$tese[$dif] = $tese[$dif] + $total;
						break;
				}
			}
		}
		$sx = '<table class="tabela00" width="400">';
		switch ($tipo) {
			case '0' :
				$sx .= '<TR><TH>Ano<TH>Citações';
				break;
			case '1' :
				$sx .= '<TR><TH>Ano<TH>Artigo<TH>Livro<TH>Anais<TH>Tese & Dissertação';
				break;
			case '2' :
				$sx .= '<TR><TH>Ano<TH>Artigo';
				break;
		}

		$sz = '20%';
		for ($r = 0; $r < count($livr); $r++) {
			$anos = $r;
			if ($anos == 0) { $anos = 'imediato';
			}
			if ($r > 50) {
				$anos = ($r - 50) * 10 + 40;
				$anos = ($anos) . '-' . ($anos + 9);
			}
			if ($r == 56) { $anos = '>= 100';
			}
			$sx .= '<TR class="tabela01" align="center">';
			$sx .= '<TD width="' . $sz . '">' . $anos;
			$sx .= '<TD width="' . $sz . '">' . $arti[$r];
			if ($tipo == '1') {
				$sx .= '<TD width="' . $sz . '">' . $livr[$r];
				$sx .= '<TD width="' . $sz . '">' . $anais[$r];
				$sx .= '<TD width="' . $sz . '">' . $tese[$r];
			}
		}
		$sx .= '</table>';
		return ($sx);
	}

	function grupos_icpa($ano = '') {
		if (strlen($ano) == 0) {
			$sql = "select max(icpa_ano) as ano from bris_icpa ";
			$rlt = db_query($sql);
			$line = db_read($rlt);
			$ano = $line['ano'];
		}
		$sql = "select count(*) as total, sum(icpa_artigos) as artigos, icpa_indice 
							from bris_icpa 
							group by icpa_indice 
							order by icpa_indice ";
		$rlt = db_query($sql);
		$icpa = array(0, 0, 0, 0);
		$icpap = array(0, 0, 0, 0);
		$tot1 = 0;
		$tot2 = 0;
		while ($line = db_read($rlt)) {
			$tp = round($line['icpa_indice']);
			$icpa[$tp] = $icpa[$tp] + $line['artigos'];
			$icpap[$tp] = $icpap[$tp] + $line['total'];
			$tot1 = $tot1 + $line['artigos'];
			$tot2 = $tot2 + $line['total'];
		}
		/* totais */
		$sx = '<table width="100%">';
		$sx .= '<TR>
					<th WIDTH="20%">iCPA
					<Th width="15%" colspan=2>NC</th>
					<Th width="15%" colspan=2>BAIXO</th>
					<Th width="15%" colspan=2>MÉDIO</th>
					<Th width="15%" colspan=2>ALTO</th>
					<Th width="20%">TOTAL</th>';

		$sx .= '<TR>
						<TD><B>Autores</B></TD>
						<td align="center" class="tabela01">' . number_format($icpap[0]) . '</TD>
						<td align="center" class="tabela01">' . number_format(100 * ($icpap[0] / $tot2), 1, ',', '.') . '%</TD>
						
						<td align="center" class="tabela01">' . number_format($icpap[1]) . '</TD>
						<td align="center" class="tabela01">' . number_format(100 * ($icpap[1] / $tot2), 1, ',', '.') . '%</TD>
						
						<td align="center" class="tabela01">' . number_format($icpap[2]) . '</TD>
						<td align="center" class="tabela01">' . number_format(100 * ($icpap[2] / $tot2), 1, ',', '.') . '%</TD>
						
						<td align="center" class="tabela01">' . number_format($icpap[3]) . '</TD>
						<td align="center" class="tabela01">' . number_format(100 * ($icpap[3] / $tot2), 1, ',', '.') . '%</TD>
						
						<td align="center" class="tabela01">' . number_format($tot2) . '</TD>
			';

		$linka = '<A HREF="indicador_icpa_detalhe.php?dd0=' . $ano . '&dd1=0" class="link lt0">detalhes</A>';
		$linkb = '<A HREF="indicador_icpa_detalhe.php?dd0=' . $ano . '&dd1=1" class="link lt0">detalhes</A>';
		$linkc = '<A HREF="indicador_icpa_detalhe.php?dd0=' . $ano . '&dd1=2" class="link lt0">detalhes</A>';
		$linkd = '<A HREF="indicador_icpa_detalhe.php?dd0=' . $ano . '&dd1=3" class="link lt0">detalhes</A>';
		$sx .= '<TR>
						<TD>&nbsp;</TD>
						<td align="center" colspan=2></TD>						
						<td align="center" colspan=2>' . $linkb . '</TD>						
						<td align="center" colspan=2>' . $linkc . '</TD>						
						<td align="center" colspan=2>' . $linkd . '</TD>
			';
		$sx .= '</table>';
		return ($sx);
	}

	function verificar_artigo_sem_ano() {
		$total = 0;
		$sql = "select count(*) as total from brapci_article 
						where (ar_ano = 0 or ar_ano is null)
							and ar_status <> 'X'
						";
		$rlt = db_query($sql);
		if ($line = db_read($rlt)) {
			$total = $line['total'];
		}
		return ($total);
	}

	function calcula_icpa($ano = 1900) {
		$total = $this -> verificar_artigo_sem_ano();
		if ($total > 0) {
			echo '<font color="err">Existe Artigos sem ano</font>';
			exit ;
		}

		/* excluir dados do ano base */
		$sql = "delete from bris_icpa where icpa_ano = " . $ano;
		$rrr = db_query($sql);

		$ano2 = ($ano - 9);
		/* Calcula dispersão dos autores */
		$sql = "select * from (
					select max(art) as maximo, sum(art) as artigos, ae_author from (
						select count(*) as art, ae_journal_id, ae_author from brapci_article_author 
						inner join brapci_article on ae_article = ar_codigo
						inner join brapci_section on ar_section = se_codigo and se_tipo = 'B'
						where (ar_ano <= $ano and ar_ano >= $ano2) and ar_status <> 'X' and 
							(ar_section <> 'EDITO' and ar_section <> 'RESEN')
						group by ae_journal_id, ae_author
						) as tabela 
						group by ae_author
				) as tabela01
				inner join (
						select count(*) as revistas, ae_author as ar_author from (
						SELECT ae_journal_id, ae_author FROM brapci_article_author
						inner join brapci_article on ae_article = ar_codigo
						inner join brapci_section on ar_section = se_codigo and se_tipo = 'B'
						where (ar_ano <= $ano and ar_ano >= $ano2) and ar_status <> 'X' and 
							(ar_section <> 'EDITO' and ar_section <> 'RESEN')
						group by ae_journal_id, ae_author) as tabela03
						where ae_author <> '' and ae_journal_id <> '0000000'
						group by ar_author				
				) as tabela02 on ae_author = ar_author
						order by maximo desc, ae_author, artigos desc
			";
		$rlt = db_query($sql);
		$sx = '';
		$idt = 0;
		$idz = 0;
		$min = 10;
		while ($line = db_read($rlt)) {
			$idt++;
			$autor = trim($line['ae_author']);
			$max = trim($line['maximo']);
			$art = trim($line['artigos']);
			$rev = trim($line['revistas']);

			$idc = (int)(100 * ($max / $art));
			$id = 0;
			if ($idc <= 35) { $id = 1;
			}
			if ($idc > 35) { $id = 2;
			}
			if ($idc > 50) { $id = 3;
			}
			if ($art < $min) { $id = 0;
				$idz++;
			}
			echo '<BR>[' . $id . '/' . $idc . '] ' . $max . '--' . $art . '--' . $rev . '--' . $autor;
			$sql = "insert into bris_icpa 
						(icpa_autor, icpa_ano, icpa_revistas, icpa_artigos, icpa_indice, icpa_maximo )
						values 
						('$autor','$ano','$rev','$art','$id', $max)			
					";
			$xxx = db_query($sql);
		}
		$sx .= '<BR>Gerado ' . $idt . ' registros, ' . $idz . ' com menos de 3 artigos';
		$sx .= '<BR><BR>';
		return ($sx);
	}

}
?>
