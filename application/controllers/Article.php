<?php
/* @author: Rene Faustino Gabriel Junior <renefgj@gmail.com>
 * @date: 2015-04-25
 */

class article extends CI_Controller {
	function __construct() {
		global $db_public;
		
		$db_public = 'brapci_publico.';
		parent::__construct();
		$this -> load -> library('form_validation');
		$this -> load -> database();
		$this -> load -> helper('form');
		$this -> load -> helper('form_sisdoc');
		$this -> load -> helper('url');
		$this -> load -> library('session');
		/* $this -> lang -> load("app", "portuguese"); */
		date_default_timezone_set('America/Sao_Paulo');
	}

	function index() {
		global $dd;
		$this->load->view("brapci/article");
	}
	
	function view($id='',$chk='') {
		global $dd;
		
		$this->load->model("metodologia");
		
		$user_nivel = $this -> session -> userdata('nivel');
				
		form_sisdoc_getpost();
	
		$this->session->userdata('search');
		
		$this -> load -> view("header/cab");

		$this -> load -> view("brapci/content");
		$this->load->model("articles");
		$data = $this->articles->le($id);
		$data['user_nivel'] = $user_nivel;
		
		$metodologia = $this->metodologia->le($id);
		$data['metodologia'] = $this->metodologia->mostra($metodologia);
		
		$this->load->view("brapci/article",$data);
	}
}
?>
		