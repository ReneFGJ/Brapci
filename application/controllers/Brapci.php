<?php
class brapci extends CI_Controller {
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
		
	}

	function index() {
		$this -> load -> view('header/cab');
	}

	function about() {
		/* Models */
		$this->load->model('journals');
		
		$this -> load -> view('header/cab');
		$this -> load -> view("brapci/content");
		
		$data = array();
		$data['journals_list'] = $this->journals->show_publish();
		$this->load->view('brapci/brapci_sobre_pt',$data);
		
		/* Mostra rodape */
		$this -> load -> view("header/foot");
	}

}
