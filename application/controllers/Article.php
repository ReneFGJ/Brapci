<?php
/* @author: Rene Faustino Gabriel Junior <renefgj@gmail.com>
 * @date: 2015-04-25
 */

class article extends CI_Controller {
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
		$this->load->view("brapci/article");
	}
	
	function view($id='',$chk='') {
		global $dd;
		
		form_sisdoc_getpost();
	
		$this->session->userdata('search');
		
		$this -> load -> view("header/cab");

		$this -> load -> view("brapci/content");
		$this->load->model("articles");
		$data = $this->articles->le($id);
		
		$this->load->view("brapci/article",$data);
	}
}
?>
		