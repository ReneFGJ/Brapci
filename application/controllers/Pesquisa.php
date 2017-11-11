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
class Pesquisa extends CI_controller {
	var $tabela = 'brapci_pesquisa.pesquisa';
	var $tabela_work = 'brapci_pesquisa.works';
	var $line = array();

	function __construct() {
		global $db_public;

		parent::__construct();
		$this -> load -> library('form_validation');
		$this -> load -> database();
		$this -> load -> helper('form');
		$this -> load -> helper('form_sisdoc');
		$this -> load -> helper('url');
		$this -> load -> helper('xml');
		/* $this -> lang -> load("app", "portuguese"); */
		$this -> load -> library('session');
		$db_public = 'brapc607_public.';
		date_default_timezone_set('America/Sao_Paulo');
	}

	function index() {
		global $base;
		$base = 'brapci_pesquisa';
		$data['title'] = 'Pesquisa';
		$data['title_page'] = 'Pesquisa';
		$this -> load -> view("header/cab_admin", $data);

		$this -> load -> view("pesquisa/content", $data);
	}

	function edit($id = 0) {
		$base = 'brapci_pesquisa';
		$data['title'] = 'Pesquisa';
		$this -> load -> view("header/cab_admin", $data);

		$this -> load -> model('pesquisa/pesquisas');
		$data['acao_editar'] = '';

		/* Form */
		$form = new form;
		$form -> id = $id;
		$form -> cp = $this -> pesquisas -> cp();
		$form -> tabela = $this -> tabela_work;
		/* form */
		$tela['tela'] = form_edit($form);
		$tela['title'] = 'Fontes';
		$this -> load -> view('form/form', $tela);

	}

	public function row($id = 1) {
		$this -> load -> model('pesquisa/pesquisas');

		$data['title'] = 'Pesquisa';
		$data['title_page'] = 'Pesquisa';
		$this -> load -> view("header/cab_admin", $data);

		$form = new form;
		$form -> tabela = $this -> tabela;
		$form -> see = true;
		$form = $this -> pesquisas -> row($form);

		$tela['tela'] = row($form, $id);
		$url = base_url('index.php/pesquisas');
		$tela['tela'] .= form_botton_new($url, 'Novo registro');

		$tela['title'] = 'Documentos';

		$this -> load -> view('form/form', $tela);
		//$this -> load -> view('header/foot');
	}

	function view($id = 0) {

		$data['title'] = 'Pesquisa';
		$data['title_page'] = 'Pesquisa';
		$this -> load -> view("header/cab_admin", $data);

		if ($id > 0) {
			$this -> load -> model('pesquisa/pesquisas');
			$row = $this -> pesquisas -> le($id);
		}
		$url = base_url('index.php/pesquisa');
		$tela = array();
		$tela['tela'] = '';
		$tela['tela'] .= form_botton_new($url, 'Novo registro');

		$tela['title'] = 'Pesquisas: ' . $row['pq_titulo'];

		$this -> load -> view('form/form', $tela);
	}

}
?>
