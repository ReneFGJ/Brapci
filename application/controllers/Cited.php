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
class cited extends CI_Controller {
	var $tabela_journals = 'brapci_journal';
	var $tabela_editions = 'brapci_edition';
	function __construct() {
		global $db_public;

		parent::__construct();
		$this -> lang -> load("app", "portuguese");
		$this -> load -> library('form_validation');
		$this -> load -> database();
		$this -> load -> helper('form');
		$this -> load -> helper('form_sisdoc');
		$this -> load -> helper('url');
		$this -> load -> helper('xml');
		$this -> load -> library('session');
		$db_public = 'brapci_publico.';
		date_default_timezone_set('America/Sao_Paulo');
	}

	function security() {
		$user = $this -> session -> userdata('user');
		$email = $this -> session -> userdata('email');
		$nivel = $this -> session -> userdata('nivel');
		if (round($nivel) < 1) {
			redirect(base_url('index.php/social/login'));
		}
	}

	function cab() {
		$this -> load -> model('Search');
		$data = array();
		$data['title'] = 'Brapci : Admin - Citações';
		$data['title_page'] = 'ADMIN - Citações';
		$this -> load -> view("header/cab_admin", $data);
		$this -> security();
	}

	function index() {
		$this -> load -> model('articles');
		$this -> load -> model('oai_pmh');

		$this -> cab();
		$tela = $this -> articles -> resumo();
		$data['content'] = $tela;
		$this -> load -> view('content', $data);

		$menu = array();
		array_push($menu, array(msg('Cited'), msg('Coletar referencias'), 'ITE', '/cited/harvesting'));
		$data = array();
		$data['menu'] = $menu;
		$data['title_menu'] = msg('Cited');
		$this -> load -> view('header/main_menu', $data);
	}

	function harvesting($id = 0) {
		$data = array();
		$this -> load -> model('articles');

		$data = $this -> articles -> le($id);

		/* CITED */
		$chk = get("dd3");
		$cited = get("dd2");

		if (strlen($chk) > 0) {
			echo 'SAVED';
		}

		$this -> cab();
		$this -> load -> view('brapci/article_simple', $data);
		$this -> load -> view('cited/cited_import');

		$this -> load -> view('header/foot_admin', $data);

	}
	
	function find_bdoi($id = 0) {
		$data = array();
		$this -> load -> model('articles');
		$this -> load -> model('cites');

		$this -> cab();
		$tela = $this->cites->bdoi_find();
		$data['content'] = $tela;
		$this->load->view('content',$data);

		$this -> load -> view('header/foot_admin', $data);

	}

}
?>
