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

class brapci extends CI_Controller {
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
		$this -> load -> view('header/cab');
	}

	function about() {
		/* Models */
		$this -> load -> model('journals');

		$this -> load -> view('header/cab');
		$this -> load -> view("brapci/content");

		$data = array();
		$data['journals_list'] = $this -> journals -> show_publish('j');
		$this -> load -> view('brapci/brapci_sobre_pt', $data);

		$this -> load -> view('about/versao', $data);
		$this -> load -> view('about/versao_v0_15_48', $data);

		/* Mostra rodape */
		$this -> load -> view("header/foot");
	}

	function indicators() {
		/* Models */
		$this -> load -> model('journals');
		$this -> load -> model('indicadores');

		$this -> load -> view('header/cab');
		$this -> load -> view("brapci/content");

		$data = array();
		$sx = $this -> indicadores -> indicador_producao_ano();
		$data['content'] = $sx;
		$this -> load -> view('content', $data);

		$sx = $this -> indicadores -> indicador_producao_journals_ano();
		$data['content'] = $sx;
		$this -> load -> view('content', $data);

		/* Mostra rodape */
		$this -> load -> view("header/foot");
	}

	function indicator_authors() {
		/* Models */
		$this -> load -> model('journals');
		$this -> load -> model('indicadores');

		$this -> load -> view('header/cab');
		$this -> load -> view("brapci/content");

		/* Mostra rodape */
		$this -> load -> view("header/foot");
	}

	function colection() {
		/* Models */
		$this -> load -> model('journals');

		$this -> load -> view('header/cab');
		$this -> load -> view("brapci/content");

		$data = array();

		/* Mostra rodape */
		$this -> load -> view("header/foot");
	}

}
