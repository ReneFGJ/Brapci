<?php
/* @author: Rene Faustino Gabriel Junior <renefgj@gmail.com>
 * @date: 2015-04-25
 */

class home extends CI_Controller {
	function __construct() {
		global $db_public;
		
		$db_public = 'brapci_public.';
		parent::__construct();
		$this -> load -> library('form_validation');
		$this -> load -> database();
		$this -> load -> helper('form');
		$this -> load -> helper('form_sisdoc');
		$this -> load -> helper('url');
		$this -> load -> library('session');
		/* $this -> lang -> load("app", "portuguese"); */
	}

	function index() {
		global $dd;
		
		form_sisdoc_getpost();
	
		$this->session->userdata('search');
		
		$this -> load -> view("header/cab");

		$this -> load -> view("brapci/content");
		$this -> load -> view("brapci/search_form");

		/* Busca */
		$this -> load -> model('Search');
		
		$tela = $this -> Search -> busca_form();
		$data = array('tela' => $tela);

		$this -> load -> view("brapci/search_result",$data);
		$this -> load -> view("header/foot");
	}

}
?>
