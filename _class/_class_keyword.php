<?php
class keyword {
	var $tabela = "brapci_keyword";

	function cp() {
		$tp = $this->tipos_descritores();
		$op = ' : ';
		$ky = array_keys($tp);
		for ($r=0;$r < count($ky);$r++)
			{				
				$key = $ky[$r];
				$op .= '&'.$key.':'.$tp[$key];
			}
		$cp = array();
		array_push($cp, array('$H8', 'id_kw', '', False, True));
		array_push($cp, array('$S80', 'kw_word', 'Termo', True, True));
		array_push($cp, array('$S5', 'kw_idioma', 'Idioma', True, True));
		array_push($cp, array('$S10', 'kw_codigo', 'Codigo', False, True));
		array_push($cp, array('$S10', 'kw_use', 'Alias', False, True));
		array_push($cp, array('$O 0:Não&1:SIM', 'kw_hidden', 'Oculto', False, True));
		array_push($cp, array('$O'.$op, 'kw_tipo', 'Tipo', False, True));

		return ($cp);
	}
	
	function tipos_descritores()
		{
			$tp = array();
			
			$tp['T'] = 'Termo de indexação';
			$tp['G'] = 'Termo geográfico';			
			$tp['D'] = 'Data (Ano, Mes, Dia, Século, Década)';
			$tp['A'] = 'Autoridades (pessoa, empresas, instituições)';
			$tp['N'] = 'Não catalogado';
			$tp['W'] = 'Dúvida';
			return($tp);
		}

	function agrupar_termo($nome, $lang, $tipo) {
		$sql = "select kw_codigo, kw_word from brapci_keyword as t1 
						where kw_word = '" . $nome . "' and kw_idioma='" . $lang . "'
						group by kw_codigo, kw_word
						order by kw_codigo
					";
		$rlt = db_query($sql);
		$ta = '';
		$to = array();

		while ($line = db_read($rlt)) {
			$tc = $line['kw_codigo'];
			if (strlen($ta) == 0) { $ta = $tc;
			}
			array_push($to, $tc);
		}
		$it = 0;
		for ($r = 0; $r < count($to); $r++) {
			$tt = $to[$r];
			if ($ta != $tt) {
				$it++;
				$sql = "update brapci_article_keyword set kw_keyword = '$ta' where kw_keyword = '$tt' ";
				$rlt = db_query($sql);
			}
		}
		/* Executa limpeza */
		$this -> remove_termo($nome, $lang, $tipo);
	}

	function remove_termo($nome, $lang, $tipo) {
		$max = 50;
		if ($tipo > $max) {
			$xsql = "delete from brapci_keyword 
						where kw_word = '" . $nome . "' and kw_idioma='" . $lang . "'
					";
			$xrlt = db_query($xsql);
		} else {
			$sql = "select * from brapci_keyword as t1 
						left join brapci_article_keyword as t2 on t1.kw_codigo = t2.kw_keyword
						where kw_word = '" . $nome . "' and kw_idioma='" . $lang . "' and kw_article is null
						limit $max
					";
			$rlt = db_query($sql);
			$xsql = 'delete from ' . $this -> tabela . ' where ';
			$idx = 0;
			while ($line = db_read($rlt)) {
				$id = $line['id_kw'];
				$ka = trim($line['kw_article']);
				if (strlen($ka) == 0) {
					if ($idx > 0) { $xsql .= ' or ';
					}
					$xsql .= " (id_kw = " . round($id) . ') ';
					$idx++;
				} else {
					echo '<BR>In use by ' . $this -> show_article_admin_link($ka);
					echo ' - ' . $line['kw_keyword'];
				}
			}
			if ($idx > 0) {
				$xrlt = db_query($xsql);
			}
		}
	}

	function show_article_admin_link($id) {
		$sx = '<A HREF="article_view.php?dd0=' . $id . '&dd90=' . checkpost($id) . '">';
		$sx .= $id;
		$sx .= '</A>';
		return ($sx);
	}

	function remove_duplicates() {
		$sql = "select * from (
						select kw_word, kw_idioma, count(*) as total from brapci_keyword 
							group by kw_word, kw_idioma
						) as tabela
					where total > 1 
					order by total desc, kw_word, kw_idioma ";
		$rlt = db_query($sql);
		$sx = '<table width="100%" class="tabela00">';
		$sx .= '<TR><TH>ação</TH><TH>idioma</TH><TH>incidência</TH><TH>termo</th></tr>';
		while ($line = db_read($rlt)) {
			$total = $line['total'];
			$sx .= '<TR>';
			$sx .= '<TD width="160" class="tabela01" align="center">';
			$sx .= '<A HREF="' . page() . '?dd1=' . trim($line['kw_word']) . '&dd2=' . trim($line['kw_idioma']) . '&dd3=' . $total . '&dd10=DEL' . '&dd90=' . checkpost($line['kw_word'] . date("Ymd")) . '" class="link lt1">excluir</A>';

			$sx .= ' | ';
			$sx .= '<A HREF="' . page() . '?dd1=' . trim($line['kw_word']) . '&dd2=' . trim($line['kw_idioma']) . '&dd3=' . $total . '&dd10=AGRUPAR' . '&dd90=' . checkpost($line['kw_word'] . date("Ymd")) . '" class="link lt1">agrupar</A>';

			$sx .= '<TD width="80" class="tabela01" align="center">';
			$sx .= $line['kw_idioma'];

			$sx .= '<TD width="80" class="tabela01" align="center">';
			$sx .= $line['total'];

			$sx .= '<TD class="tabela01">';
			$sx .= $line['kw_word'];

		}
		$sx .= '</table>';
		return ($sx);
	}

	function remove_termo_todos_nao_usados() {
		$sql = "SELECT * FROM `brapci_keyword` as t1 
				left join brapci_article_keyword as t2 on t1.kw_codigo = t2.kw_keyword 
				WHERE t2.kw_keyword is null
				limit 100";
		$rlt = db_query($sql);
		$sx = '<table class="tabela00" width="100%">';
		$tot = 0; /* Total */
		while ($line = db_read($rlt)) {
			$tot++;
			$sql = "delete from " . $this -> tabela . " where kw_codigo = '" . $line['kw_codigo'] . "'";
			echo '<BR>'.$sql;
			$xrlt = db_query($sql);
		}
	return($tot);

	}

	function remove_keyword_not_used() {

		$sql = "SELECT * FROM `brapci_keyword` as t1 
				left join brapci_article_keyword as t2 on t1.kw_codigo = t2.kw_keyword 
				WHERE t2.kw_keyword is null
				limit 100";
		$rlt = db_query($sql);
		$sx = '<table class="tabela00" width="100%">';
		while ($line = db_read($rlt)) {
			$sx .= '<TR>';
			$sx .= '<TD width="160" class="tabela01" align="center">';
			$sx .= '<A HREF="' . page() . '?dd1=' . trim($line['kw_word']) . '&dd2=' . trim($line['kw_idioma']) . '&dd3=' . $total . '&dd10=DEL' . '&dd90=' . checkpost($line['kw_word'] . date("Ymd")) . '" class="link lt1">excluir</A>';

			$sx .= '<TD>'.$line['kw_codigo'];
			$sx .= '<TD>' . $line['kw_word'];
			
			$sx .= '<TD>' . $line['id_kw'];

			$sx .= '<TD>not used';
			$codigo = trim($line['codigo']);
		}
		$sx .= '</table>';
		return ($sx);
	}

	function row() {
		global $cdf, $cdm, $masc;
		$idcp = 'kw';
		$cdf = array('id_' . $idcp, $idcp . '_word', $idcp . '_codigo', $idcp . '_use', 'kw_tipo', $idcp . '_idioma');
		$cdm = array('Código', 'Nome', 'Citação', 'Codigo', 'Tipo', 'Alias');
		$masc = array('', '', '', '', '', '', '', '', '', '', '');
	}

	function recupera_keyword($id, $idioma) {
		if (round($id) == 0) {
			return ('');
		}
		$id = strzero($id, 10);
		$sql = "select * from brapci_article_keyword 
						inner join brapci_keyword on kw_keyword = kw_codigo
						where kw_article = '" . $id . "' and kw_idioma = '$idioma'";
		$rlt = db_query($sql);
		$sx = '';
		while ($line = db_read($rlt)) {
			if (strlen($sx) > 0) { $sx .= '. ';
			}
			$sx .= trim($line['kw_word']);
		}
		return ($sx);
	}

	function save_keyword_article_v2($id, $keys, $idioma) {
		if (round($id) == 0) {
			return ('');
		}
		$id = strzero($id, 10);

		$sql = "select * from brapci_article_keyword 
						inner join brapci_keyword on kw_keyword = kw_codigo
						where kw_article = '" . $id . "' and kw_idioma = '$idioma'";
		$rlt = db_query($sql);
		while ($line = db_read($rlt)) {
			$sqlx = "delete from brapci_article_keyword where id_ak = " . $line['id_ak'];
			$xrlt = db_query($sqlx);
		}

		for ($rx = 0; $rx < count($keys); $rx++) {
			$nome_ref = $keys[$rx];

			$cod = $this -> keyword_find($nome_ref, $idioma);
			$sql = "insert into brapci_article_keyword
							(
							kw_article, kw_keyword, kw_ord
							) value (
							'$id','$cod',$rx)
							";
			$rlt = db_query($sql);
		}
		return (1);
	}

	function save_keyword_article($id, $keys) {
		$sql = "delete from brapci_article_keyword where kw_article = '$id'";
		$rlt = db_query($sql);

		for ($r = 0; $r < count($keys); $r++) {
			$nome_ref = $keys[$r][0];
			$idioma = $keys[$r][1];
			$cod = $this -> keyword_find($nome_ref, $idioma);
			$sql = "insert into brapci_article_keyword
							(
							kw_article, kw_keyword, kw_ord
							) value (
							'$id','$cod',$r)
							";
			$rlt = db_query($sql);
		}
		return (1);
	}

	function insert_keyword_in_article($article, $key_text, $idioma) {
		$key_text = troca($key_text, '. ', ';');
		';';
		$key_text = troca($key_text, ', ', ';');
		';';
		$ky = splitx(';', $key_text);
		print_r($ky);
		echo '<BR>' . $idioma . '<HR>';
		if ($idioma == '') { echo '<font color="red">Sem Idioma</font>';
			return ('');
		}
		$keys = array();
		for ($r = 0; $r < count($ky); $r++) {
			array_push($keys, array($ky[$r], $idioma));
		}

		if (count($ky) > 0) {
			$this -> save_keyword_article($article, $keys);
		}
	}

	function show_keyword($idioma = 'pt-BR', $key = 'A') {
		$wh = " and kw_word_asc like '$key%' ";
		$sql = "select kw_word, kw_codigo, kw_use from brapci_keyword 
						where kw_use = kw_codigo
						and kw_idioma = '$idioma'
						$wh
						and kw_word <> ''
					order by kw_word_asc
			";
		$rlt = db_query($sql);

		$sx = '';
		while ($line = db_read($rlt)) {
			$keyc = trim($line['kw_codigo']);
			$link = '<A HREF="busca_keyword.php?dd1=' . $keyc . '" class="link lt1">';
			$sx .= '<BR>' . $link . utf8_encode(trim($line['kw_word'])) . '</A>' . chr(13) . chr(10);
		}
		return ($sx);

	}

	function keyword_insert($key, $idioma) {
		$key1 = UpperCaseSql($key);
		$sql = "insert into brapci_keyword 
					(kw_word, kw_word_asc, kw_codigo,
					kw_use, kw_idioma, kw_tipo,
					kw_hidden 
					) values (
					'$key', '$key1', '',
					'', '$idioma','N',
					0)";
		$rlt = db_query($sql);
		$this -> updatex_keys();
		$rs = $this -> keyword_find($key, $idioma);
		return ($rs);
	}

	function updatex_keys() {
		$c = 'kw';
		$c1 = 'id_' . $c;
		$c2 = $c . '_codigo';
		$c5 = $c . '_use';
		$c3 = 10;
		$sql = "update brapci_keyword set 
						$c2 = lpad($c1,$c3,0) , 
						$c5 = lpad($c1,$c3,0)
						where $c2='' ";
		$rlt = db_query($sql);
		return (1);
	}

	function keyword_find($key, $idioma) {
		$key = substr($key, 0, 100);
		$key_asc = UpperCaseSql($key);
		$sql = "select * from brapci_keyword 
					where kw_word_asc = '$key_asc'
					and kw_idioma = '$idioma'";
		$rlt = db_query($sql);
		if ($line = db_read($rlt)) {
			return (trim($line['kw_use']));
		} else {
			return ($this -> keyword_insert($key, $idioma));
		}
	}

}
