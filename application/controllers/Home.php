<?php
/* @author: Rene Faustino Gabriel Junior <renefgj@gmail.com>
 * @date: 2015-04-25
 */

class home extends CI_Controller {
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

	function index() {
		global $dd;
		/* Model */
		$this -> load -> model('Search');
		
		$this -> Search -> session();

		form_sisdoc_getpost();

		$this -> session -> userdata('search');

		$this -> load -> view("header/cab");
		
		/* data */
		$data = array();
		$data['dd1'] = $this->input->get("dd1");
		$data['dd2'] = $this->input->get("dd2");
		$data['dd3'] = $this->input->get("dd3");
		$data['ano_min'] = 1972;
		$data['ano_max'] = date("Y")+1;
		$data['anoi'] = $data['ano_min'];
		$data['anof'] = date("Y")+1;
		
		/* Já existe data */
		if (strlen($data['dd3']))
			{
				$sa = sonumero($data['dd3']);
				$data['anoi'] = substr($sa,0,4);
				$data['anof'] = substr($sa,4,4);
						
			}
		

		$this -> load -> view("brapci/content");
		$this -> load -> view("brapci/search_form",$data);

		/* Busca */
		$tela = $this -> Search -> busca_form();
		$data = array('tela' => $tela);

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
		$this -> load -> view("brapci/search_form");
		
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
		$this -> load -> view("brapci/search_form");
		
		
		$data['tela'] = $this -> Search -> session_set($id);
				
		$data = array();
		$data['tela'] = $this -> Search -> result_search_selected($session);

		/* Mostra resultado */
		$this -> load -> view("brapci/search_result", $data);

		/* Mostra rodape */
		$this -> load -> view("header/foot");
	}

}
?>
