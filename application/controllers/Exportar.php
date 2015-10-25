<?php

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
