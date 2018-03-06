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
DEFINE('BASE_PUBLIC',"brapc607_public.");

class home extends CI_Controller {
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

	function help() {
		form_sisdoc_getpost();

		$this -> session -> userdata('search');
		$this -> load -> view("header/cab");
		$this -> load -> view("brapci/content");
		$this -> load -> view("brapci/help");
		$this -> load -> view("header/foot");
	}

	function logout() {
		$user = /* Salva session */
		$data = array('user' => '', 'email' => '', 'image' => '');
		$this -> session -> set_userdata($data);
		redirect(base_url('index.php/home'));
	}

	function index()
		{
			$this->pag();
		}
	function pag($pag='') {
		/* Model */
		$this -> load -> model('Search');
		$this -> load -> model('Autorities');
		$this -> load -> model('Keywords');

		$this -> Search -> session();

		$this -> session -> userdata('search');

		$this -> load -> view("header/cab");
		
		if (strlen($pag) > 0)
			{
				$_POST['dd5'] = $_SESSION['anoi'];
				$_POST['dd6'] = $_SESSION['anof'];
				$_POST['acao'] = 'search';
				$_POST['dd3'] = $_SESSION['type'];
				$_POST['dd4a'] = $_SESSION['dd4a'];
				$_POST['dd4b'] = $_SESSION['dd4b'];
				$_POST['dd4c'] = $_SESSION['dd4c'];
				$_POST['dd4d'] = $_SESSION['dd4d'];
				$_POST['dd4e'] = $_SESSION['dd4e'];
				$_POST['dd4f'] = $_SESSION['dd4f'];
			}

		/* Dados do Get */
		$ano_ini = round(substr(get("dd5"), 0, 4));
		$ano_fim = round(substr(get("dd6"), 0, 4));
		if ($ano_ini < 1900) { $ano_ini = 1972;
		}
		if ($ano_fim < $ano_ini) { $ano_fim = (date("Y") + 1);
		}

		/* data */
		$data = array();
		$acao = get("acao");
		$dd3 = get("dd3");
		$dd4 = '';
		switch($dd3) {
			case '0' :
				$dd4 = get("dd4a");
				break;
			case '1' :
				$dd4 = get("dd4b");
				break;
			case '2' :
				$dd4 = get("dd4c");
				break;
			case '3' :
				$dd4 = get("dd4d");
				break;
			case '4' :
				$dd4 = get("dd4e");
				break;
			case '5' :
				$dd4 = get("dd4f");
				break;
		}
		$data['ano_min'] = 1972;
		$data['ano_max'] = date("Y") + 1;
		$data['anoi'] = $ano_ini;
		$data['anof'] = $ano_fim;
		$data['pag'] = $pag;

		//$this -> load -> view("brapci/content");
		$this -> load -> view("brapci/search_form_2017", $data);

		/* Busca */
		/* Tipos de Busca */
		$tipo = get('dd3');
		$data['dd4'] = $dd4;
		if ((strlen($acao) > 0) and (strlen($dd4) > 0)) {
			/* REGISTRA BUSCA */
			$session = $_SESSION['bp_session'];
			$this -> Search -> registra_consulta($tipo, $ano_ini, $ano_fim, $dd4, $pag);

			switch ($tipo) {
				case '0' :
					$telax = $this -> Search -> busca_form($data);
					$data = array('tela' => $telax['tela1'], 'tela2' => $telax['tela2']);
					break;
				case '1' :
					$telax = $this -> Search -> busca_form_autor($data);
					$data = array('tela' => $telax['tela1'], 'tela2' => $telax['tela2']);
					break;
				case '2' :
					$telax = $this -> Search -> busca_form_title($data);
					$data = array('tela' => $telax['tela1'], 'tela2' => $telax['tela2']);
					break;
				case '3' :
					$telax = $this -> Search -> busca_form_keyword($data);
					$data = array('tela' => $telax['tela1'], 'tela2' => $telax['tela2']);
					break;
				case '4' :
					$telax = $this -> Search -> busca_form_abstract($data);
					$data = array('tela' => $telax['tela1'], 'tela2' => $telax['tela2']);
					break;
				case '5' :
					/* REFERENCIAS */
					$telax = $this -> Search -> busca_form_cited($data);
					$data = array('tela' => $telax['tela1'], 'tela2' => $telax['tela2'], 'tela3' => $telax['tela3']);
					break;
				default :
					$tela = '';
					$data = array('tela' => $tela);
					break;
			}

			/* Mostra resultado */
			$this -> load -> view("brapci/search_result", $data);
		} else {
			/* Mostra resultado */
			$this -> load -> view("brapci/jumbo", $data);
		}
		/* Mostra rodape */
		$this -> load -> view("header/foot");
	}

	function download($id=0)
		{
			$this->load->model('articles');
			$this->load->model('search');
			$this->Search->registra_download($id);
			$id = round($id);
			$this->articles->view_pdf($id);
		}

	/* Tutorial */
	function tutorial($id = 0) {
		$id = 1;
		$this -> load -> view("header/cab");
		$this -> load -> view("brapci/content");

		$this -> load -> view("tutorial/tutorial");
		$this -> load -> view("tutorial/tutorial_" . strzero($id, 3));

		/* Mostra rodape */
		$this -> load -> view("header/foot");
	}

	function selection_save($id) {
		global $dd;
		/* Model */
		$this -> load -> model('Search');

		$this -> load -> view("header/cab");

		$this -> load -> view("brapci/content");
		//$this -> load -> view("brapci/search_form");

		$data = array();
		$data['tela'] = $this -> Search -> save_session();

		/* Mostra resultado */
		$this -> load -> view("brapci/search_result", $data);

		/* Mostra rodape */
		$this -> load -> view("header/foot");
	}

	function selection_send_email($id) {
		global $dd;
		/* Model */
		$this -> load -> model('Search');

		$this -> load -> view("header/cab");

		$this -> load -> view("brapci/content");
		//$this -> load -> view("brapci/search_form");

		$data = array();
		$data['tela'] = $this -> Search -> save_session();

		/* Mostra resultado */
		$this -> load -> view("brapci/search_result", $data);

		/* Mostra rodape */
		$this -> load -> view("header/foot");
	}

	function selections() {
		global $dd;
		/* Model */
		$this -> load -> model('Search');

		$this -> load -> view("header/cab");

		$this -> load -> view("brapci/content");
		//$this -> load -> view("brapci/search_form");

		$data = array();
		$data['tela'] = $this -> Search -> selections();

		/* Mostra resultado */
		$this -> load -> view("brapci/search_result", $data);

		/* Mostra rodape */
		$this -> load -> view("header/foot");
	}

	function selection($id) {
		
		/* Model */
		$this -> load -> model('Search');
		$session = $this -> Search -> session();
		
		$this -> load -> view("header/cab");
		


		$this -> load -> view("brapci/content");
		//$this -> load -> view("brapci/search_form");

		/* Export Selected */
		$data['session'] = $id;
		$this -> load -> view("brapci/session_cab", $data);

		$data['tela'] = $this -> Search -> session_set($id);
		
		$data = array();
		/* Usuario logado */
		
		if ((isset($_SESSION['email'])) and (strlen($_SESSION['email']) > 0)) {
			$data['tela'] = $this -> Search -> result_search_selected($session);
		} else {
			$data['tela'] = '';
		}
		
		//$data['tela'] = $this -> Search -> result_search_selected($session);

		/* Mostra resultado */
		$this -> load -> view("brapci/search_result", $data);

		/* Mostra rodape */
		$this -> load -> view("header/foot");
	}

	function cited() {
		/* Model */
		$this -> load -> model('Search');
		$session = $this -> Search -> session();

		$this -> load -> view("header/cab");

		$this -> load -> view("brapci/content");
		//$this -> load -> view("brapci/search_form");

		$data['content'] = '<h1>Em construção, aguarde!</h1>';
		/* Mostra resultado */
		$this -> load -> view("content", $data);

		/* Mostra rodape */
		$this -> load -> view("header/foot");
	}

	function selection_xls($id) {
		/* Model */
		$this -> load -> model('Search');
		$session = $this -> Search -> session();

		$data['tela'] = $this -> Search -> session_set($id);

		$data = array();
		$data['content'] = utf8_decode($this -> Search -> result_search_selected_xls($session));
		$data['filename'] = 'xls_selection_' . date("YmdHis") . '.xls';
		$this -> load -> view("content_xls", $data);
	}

}
?>
