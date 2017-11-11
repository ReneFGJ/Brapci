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

class sparql extends CI_Controller {
	function __construct() {
		global $db_public;

		$db_public = 'brapc607_public.';
		parent::__construct();
		$this -> lang -> load("app", "portuguese");
		$this -> load -> library('form_validation');
		$this -> load -> database();
		$this -> load -> helper('form');
		$this -> load -> helper('form_sisdoc');
		$this -> load -> helper('sparqllib');
		$this -> load -> helper('url');
		$this -> load -> library('session');
		date_default_timezone_set('America/Sao_Paulo');

	}

	function cab() {
		$data = array();
		$data['title'] = 'Brapci : Sparql';
		$data['title_page'] = 'SARPQL';
		$this -> load -> view("header/cab_admin", $data);
		$this -> load -> view("brapci/content_simple");
	}

	function index() {
		$this->load->model('sparqls');
		$this -> cab();

		$tela = $this->sparqls->sample();
		//$tela = $this->sparqls->find_country();
		$data['content'] = $tela;
		$this->load->view("content",$data);
		
		/* Mostra rodape */
		$this -> load -> view("header/foot_admin");
	}

}
?>
