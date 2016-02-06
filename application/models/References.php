<?php
class references extends CI_model {

	function cited($ln, $fnt = 'ABNT') {
		$sx = '';
		switch ($fnt) {
			case 'ABNT' :
				$sx .= $this -> abnt($ln);
				break;
			default :
				$sx .= $this -> abnt($ln);
				break;
		}
		return ($sx);
	}

	function abnt($ln) {
		$title = LowerCase($ln['ar_titulo_1']);
		$title = Uppercase(substr($title, 0, 1)) . substr($title, 1, strlen($title));

		$journal = $ln['jnl_nome_abrev'];
		$journal = $ln['jnl_nome'];

		$autor = troca($ln['authores_row'], chr(13), ';') . ';';
		$au = splitx(';', $autor);
		$autor = '';

		$vol = trim($ln['ed_vol']);
		$num = trim($ln['ed_nr']);
		$ano = trim($ln['ed_ano']);
		$vol = trim($ln['ed_vol']);
		$pgi = trim($ln['ar_pg_inicial']);
		$pgf = trim($ln['ar_pg_final']);
		$doi = trim($ln['ar_doi']);
		$tipo = trim($ln['jnl_tipo']);
		$link = ' Disponível em: &lt;' . base_url('v/'.$ln['id_ar']) . '&gt;.';
		$acesso = ' Acesso em: ' . date("d") . ' ' . msg('mes_' . date("m") . 'a') . ' ' . date("Y") . '.';
		$pag = '';

		/* Total de Autores */
		if (count($au) <= 3) {
			for ($r = 0; $r < count($au); $r++) {
				$aut = trim($au[$r]);
				if (strlen($aut) > 0) {
					if (strlen($autor) > 0) {
						$autor .= '; ';
					}
					$autor .= nbr_autor($aut, 5);
				}
			}
		} else {
					$autor .= nbr_autor($au[0], 5);
					$autor .= ' et al.';
		}

		/* Volume */
		if (strlen($vol) > 0) { $vol = ', v. ' . $vol;
		}
		/* Number */
		if (strlen($num) > 0) { $num = ', n. ' . $num;
		}
		/* Pagina */
		if (strlen($pgi) > 0) { $pag = ', p. ' . $pgi;
			if (strlen($pgf) > 0) { $pag .= '-' . $pgf;
			}
		}
		/* Ano */
		if (strlen($ano) > 0) { $ano = ', ' . $ano;
		}
		/* DOI */
		if (strlen($doi) > 0) { $doi .= '. DOI:<a href="#">' . $doi . '</a>';
		}

		switch ($tipo) {
			case 'J' :
				$sx = $autor . '. ' . trim($title) . '. <b>' . $journal.'</b>' . $vol . $num . $pag . $ano . $doi . '.';
				$sx .= $link;
				$sx .= $acesso;
				break;
			default :
				$sx = $autor . '. ' . trim($title) . '. <b>' . $journal.'</b>' . $vol . $num . $pag . $ano . $doi . '.';
				break;
		}

		$sx = troca($sx, '.. ', '. ');

		return ($sx);
		exit ;
	}

}
?>
