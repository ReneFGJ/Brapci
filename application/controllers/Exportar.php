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
class Exportar extends CI_controller {
	var $tabela_journals = 'brapci_journal';
	var $tabela_editions = 'brapci_edition';
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
		$db_public = 'brapci_publico.';
		date_default_timezone_set('America/Sao_Paulo');
	}

	function cab() {
		$data['title'] = 'Brapci : Admin';
		$data['title_page'] = 'ADMIN';
		$this -> load -> view("header/cab_admin", $data);
	}

	function index() {
		$this->cab();
	}

	function publico($id = 0) {
		$exp = 150;
		$data['title'] = 'Brapci : Admin';
		$data['title_page'] = 'ADMIN - EXPORTAÇÃO';
		$this -> load -> view("header/cab_admin", $data);

		$this -> load -> model('export');
		/* Gerar arquivo */
		if (trim($id) == 'file') {
			echo 'OPS'; exit;
			$this -> export -> exporta_texto();
			$data['tela'] = '<center><h1><font color="green">Finalizado com sucesso!</font></h1></center>';
			$this -> load -> view('form/form', $data);
			return (0);
		}

		/* Mostrar */
		
		$data['tela'] = '';
		$data['titulo'] = 'Exportando';
		if ($id == 0) {
			$data['tela'] .= '<BR>Zerando base';
			$this -> export -> zera_public();
		}
		$total = $this -> export -> export_public($id, $exp);
		
		$this->load->view('show',$data);

		if ($total > 0) {
			$sending = '<center><img src="' . base_url('img/sending.gif') . '"></center>';
			$data['tela'] .= $sending;

			$data['tela'] .= '<BR>Exportando ' . $total . ' registros, já foram enviados ' . round($id) . ' registros';
			$this -> load -> view('form/form', $data);

			$url = base_url('index.php/exportar/publico') .'/'. ($id + $exp);
			echo ' <meta http-equiv="refresh" content="3;' . $url . '">';
		} else {
			$url = base_url('index.php/exportar/publico/file');
			echo ' <meta http-equiv="refresh" content="3;' . $url . '">';
		}
	}

}
