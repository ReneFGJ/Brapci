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

class indicador extends CI_Controller {
	function __construct() {
		global $db_public;

		$db_public = 'brapci_publico.';
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

	function index($tp = '') {
		redirect(base_url('index.php/indicador/report/' . $tp));
	}

	function report($tp = '',$arg='') {
		/* Model */
		$this -> load -> model('Search');
		$this -> load -> model('indicadores');
		$this -> Search -> session();

		$this -> load -> view("header/cab");

		/* Busca */

		/* Mostra resultado */
		$data = array();
		$tit = '';
		$tela = $this -> load -> view('grapho/grapho_header.php', null, true);
		switch ($tp) {
			case '1' :
				$tela .= $this -> indicadores -> indicador_producao_journals_ano($arg);
				break;
			case '2' :
				$tela .= $this -> indicadores -> indicador_autores_producao($arg);
				break;
			case '3' :
				$tela .= $this -> indicadores -> colaboracao($arg);
				break;
			case '4':
				$tela .= $this -> indicadores -> indicador_producao_ano($arg);
				break;							
			default :
				$tela = $this -> load->view('grapho/menu_indicators',null,true);
				break;
		}

		$data['title'] = $tit;
		$data['content'] = $tela;
		$this -> load -> view('content', $data);

		/* Mostra rodape */
		$this -> load -> view("header/foot");
	}

}
?>

