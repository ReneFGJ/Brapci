<?php
class authority extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this -> lang -> load("app", "portuguese");
		$this -> lang -> load("skos", "portuguese");
		$this -> load -> library('form_validation');
		$this -> load -> database();
		$this -> load -> helper('form');
		$this -> load -> helper('form_sisdoc');
		$this -> load -> helper('url');
		$this -> load -> library('session');

		date_default_timezone_set('America/Sao_Paulo');
		/* Security */
		//		$this -> security();
	}

	function cab_old($title = '') {
		$data = array();
		$data['title_page'] = ':: sisDOC :: SKOS';
		$this -> load -> view("header/header", $data);
		$this -> load -> view("wese/cab");
		$this -> load -> view("wese/cab_menu");
	}

	function cab() {
		/* Model */
		$this -> load -> model('Search');
		$this -> load -> view('header/cab');
	}

	function foot() {
		/* Mostra rodape */
		$this -> load -> view("header/foot");
	}

	function security() {
		$user = $this -> session -> userdata('user');
		$email = $this -> session -> userdata('email');
		$nivel = $this -> session -> userdata('nivel');
		if (round($nivel) < 1) {
			redirect(base_url('index.php/social/login'));
		}
	}

	function person() {
		$this->load->model('autorities');
		$this -> cab();
		$this -> load -> view('authority/search', null);

		$acao = get("acao");
		$search = get("search");


		if ((strlen($acao) > 0) and (strlen($search) > 0)) {
			$data['content'] = $this -> autorities -> search_person($search);
			$this -> load -> view('content', $data);
		}
	}

}
?>