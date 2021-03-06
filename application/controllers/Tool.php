<?php
// This file is part of the Brapci Software.
//
// Copyright 2015, UFPR. All rights reserved. You can redistribute it and/or modify
// Brapci under the terms of the Brapci License as published by UFPR, which
// restricts commercial use of the Software.
//
// Brapci is distributed in the hope that it will be useful, but WITHOUT ANY
// WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
// PARTICULAR PURPOSE. See the ProEthos License for more details.
//
// You should have received a copy of the Brapci License along with the Brapci
// Software. If not, see
// https://github.com/ReneFGJ/Brapci/tree/master//LICENSE.txt
/* @author: Rene Faustino Gabriel Junior <renefgj@gmail.com>
 * @date: 2015-12-01
 */

class tool extends CI_Controller {
	function __construct() {
		global $db_public;

		$db_public = 'brapc607_public.';
		parent::__construct();
		$this -> lang -> load("app", "portuguese");
		$this -> load -> library('form_validation');
		$this -> load -> database();
		$this -> load -> helper('form');
		$this -> load -> helper('form_sisdoc');
		$this -> load -> helper('url');
		$this -> load -> library('session');

		date_default_timezone_set('America/Sao_Paulo');
	}

	function index() {
		/* Model */
		$this -> load -> model('Search');
		$this -> load -> view("header/cab");

		/* Mostra rodape */
		$this -> load -> view("header/foot");
	}

	function wordtools() {
		/* Model */
		$this -> load -> model('Search');
		$this -> load -> view("header/cab");

		$form = new form;
		$cp = array();
		array_push($cp, array('$H8', '', '', false, true));
		array_push($cp, array('$T80:20', '', 'Texto', true, true));
		array_push($cp, array('$T80:20', '', 'Texto', true, true));
		array_push($cp, array('$B8', '', 'Processar dados>>>', false, true));
		$tela = $form -> editar($cp, '');

		if ($form -> saved > 0) {
			$dd1 = get("dd1");
			$dd2 = get("dd2");
			$tela .= $this -> Search -> wcount($dd1, $dd2);
		}

		$data['content'] = $tela;
		$data['title'] = '';
		$this -> load -> view('content', $data);

		/* Mostra rodape */
		$this -> load -> view("header/foot");
	}

	function matrix() {
		/* Model */
		$this -> load -> model('Search');
		$this -> load -> model('tools');
		$this -> load -> view("header/cab");

		$tela = $this -> tools -> file_update();

		/* le arquivo se existe */
		$tx = $this -> tools -> readfile();
		if (strlen($tx) > 0) {
			$tela .= '<h3>conteúdo</h3>';
			$tx = $this -> tools -> tratar($tx);
			$authors = $this -> tools -> export_cols($tx, 1);
			$tela .= '<pre>' . $authors . '</pre>';

		}
		/* Monta tela */
		$data['content'] = $tela;
		$this -> load -> view('content', $data);

		/* Mostra rodape */
		$this -> load -> view("header/foot");
	}

	function change01() {
		/* Model */
		$this -> load -> model('Search');
		$this -> load -> model('tools');

		$data['link'] = base_url('index.php/tool/change01');
		$tela = $this -> tools -> file_update($data);

		/* le arquivo se existe */
		$tx = $this -> tools -> readfile();

		if (strlen($tx) > 0) {
			$tela .= '<h3>conteúdo</h3>';
			$tx = $this -> tools -> tratar_xls($tx);
			$this -> tools -> download_file($tx);
			return ('');
		}
		/* Monta tela */
		$this -> load -> view("header/cab");
		$data['content'] = $tela;
		$this -> load -> view('content', $data);

		/* Mostra rodape */
		$this -> load -> view("header/foot");
	}

	function change02() {
		/* Model */
		$this -> load -> model('Search');
		$this -> load -> model('tools');

		$data['link'] = base_url('index.php/tool/change02');
		$tela = $this -> tools -> file_update($data);

		/* le arquivo se existe */
		$tx = $this -> tools -> readfile();

		if (strlen($tx) > 0) {
			$tx = $this -> tools -> gerar_nomes_xls($tx);
			$this -> tools -> download_file($tx);
			return ('');
		}
		/* Monta tela */
		$this -> load -> view("header/cab");
		$data['content'] = $tela;
		$this -> load -> view('content', $data);

		/* Mostra rodape */
		$this -> load -> view("header/foot");
	}

	function change03() {
		/* Model */
		$this -> load -> model('Search');
		$this -> load -> model('tools');

		$data['link'] = base_url('index.php/tool/change03');
		$tela = $this -> tools -> file_update($data);

		/* le arquivo se existe */
		$tx = $this -> tools -> readfile();

		if (strlen($tx) > 0) {
			$tx = $this -> tools -> gerar_matriz_xls($tx);
			$this -> tools -> download_file($tx);
			return ('');
		}
		/* Monta tela */
		$this -> load -> view("header/cab");
		$data['content'] = $tela;
		$this -> load -> view('content', $data);

		/* Mostra rodape */
		$this -> load -> view("header/foot");
	}

	function change04() {
		$this -> load -> model('Search');
		/* Model */
		$this -> load -> model('tools');

		$data['link'] = base_url('index.php/tool/change03');
		$tela = $this -> tools -> file_update($data);

		/* le arquivo se existe */
		$tx = $this -> tools -> readfile();

		if (strlen($tx) > 0) {
			$tx = $this -> tools -> gerar_matriz_pajek($tx);
			$this -> tools -> download_file($tx, '.net');
			return ('');
		}
		/* Monta tela */
		$this -> load -> view("header/cab");
		$data['content'] = $tela;
		$this -> load -> view('content', $data);

		/* Mostra rodape */
		$this -> load -> view("header/foot");
	}

	function from_to() {
		/* Model */
		$this -> load -> model('Search');
		$this -> load -> view("header/cab");

		$form = new form;
		$cp = array();
		array_push($cp, array('$H8', '', '', false, true));
		array_push($cp, array('$T80:20', '', 'Texto Original', true, true));
		array_push($cp, array('$T80:20', '', 'Matrix de troca', true, true));
		array_push($cp, array('$B8', '', 'Processar dados>>>', false, true));
		$tela = $form -> editar($cp, '');

		if ($form -> saved > 0) {
			$dd1 = get("dd1");
			$dd2 = get("dd2");

			$dd2 = troca($dd2, '=>', '¢');
			$dd2 = troca($dd2, ';', '¢');
			$dd2 = troca($dd2, '"', '');
			$dd2 = troca($dd2, chr(13), ';');
			$dd2 = troca($dd2, chr(10), '');
			$words = splitx(';', $dd2);
			$wk = array();
			for ($r = 0; $r < count($words); $r++) {
				$ln = $words[$r];
				$ln = troca($ln, '¢', ';');
				while (strpos($ln, '  ')) { $ln = troca($ln, '  ', ' ');
				}
				$ln = splitx(';', $ln);
				if (isset($ln[1])) {
					$wd = UpperCaseSql($ln[0]);
					$wd = mb_convert_encoding($wd, "UTF-8", "ASCII");
					$wd = UpperCaseSql($wd);
					$nm = trim($ln[1]);
					$wk[$wd] = $nm;
				}
			}
			/* LINHAS */
			$lns = troca($dd1, chr(10), '');
			$lns = troca($lns, chr(13), ';');
			$lns = splitx(';', $lns);
			$dd1 = '';
			for ($n = 0; $n < count($lns); $n++) {
				$t = $lns[$n];
				if (strpos($t,'-'))
					{
						$t = substr($t,0,strpos($t,'-'));
					}
				$tn = UpperCaseSql($t);
				$tn = mb_convert_encoding($t, "UTF-8", "ASCII");
				$tn = trim(UpperCaseSql($tn));
				while (strpos($tn, '  ')) { $tn = troca($ln, '  ', ' ');
				}
				$tm = '';
				for ($i=0;$i < strlen($tn);$i++)
					{
						if (ord($tn[$i]) < 128) { $tm .= $tn[$i]; }
					}
				$tn = $tm;
				$q = $t;
				if (isset($wk[$tn])) { $q = $wk[$tn];
				}
				/* echo '<br>' . $tn . '=>' . $q . '<br>';
				for ($i = 0; $i < strlen($tn); $i++) {
					echo '<tt>' . str_pad(dechex(ord($tn[$i])), 2, '0', STR_PAD_LEFT) . '</tt>';
				}
				
				foreach ($wk as $key => $value) {
					echo '<br><tt>';
					for ($i = 0; $i < strlen($tn); $i++) {
						echo str_pad(dechex(ord($key[$i])), 2, '0', STR_PAD_LEFT);
					}
					echo $key;
					echo '</tt><br>';
				}

				echo '<hr>';
				 */
				$dd1 .= $q . chr(13);
			}
			$tela = '<h3>Resultado</h3><textarea rows=15 class="form-control">' . $dd1 . '</textarea><br>' . $tela;
		}
		//exit;
		$data['content'] = $tela;
		$data['title'] = '';
		$this -> load -> view('content', $data);

		/* Mostra rodape */
		$this -> load -> view("header/foot");
	}

}
?>
