<?php
/* @author: Rene Faustino Gabriel Junior <renefgj@gmail.com>
 * @date: 2015-04-25
 */

class journal extends CI_Controller {
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
		global $dd;
		
		form_sisdoc_getpost();
	
		$this->session->userdata('search');		
		$this -> load -> view("header/cab");
		$this -> load -> view("brapci/content");
		
		/* */
		$this->load->model('journals');
		
		$data = array();
		$data['tela'] = $this->journals->show_publish();
		$data['titulo'] = 'Publicações disponíveis';
		
		$this -> load -> view("show",$data);
		
		$this -> load -> view("header/foot");
	}
	
	function issue($id=0) {
		/* Models */
		$this->load->model('journals');
		$this->load->model('editions');

	
		global $dd;	
		form_sisdoc_getpost();
		
		$this -> load -> view("header/cab");
		$this -> load -> view("brapci/content");
		
		$this->load->model('editions');
		$data = $this->editions->le($id);
		
		$journal = $data['jnl_codigo'];
		
		$this->session->userdata('search');		
		
		$data = array();
		$data = $this->journals->le($journal);	
		
		/* Le trabalhos */
		$data['issue_view'] = $this->editions->issue_view($id,0);
		
		/* */				
		$data['edicoes'] = $this->editions->editions($journal); 
		
		/* VIEW */
		$this -> load -> view("brapci/journal_editions",$data);
		
				
		$this -> load -> view("header/foot");
	}	
	
	function view($id=0) {
		global $dd;
		
		form_sisdoc_getpost();
	
		$this->session->userdata('search');		
		$this -> load -> view("header/cab");
		$this -> load -> view("brapci/content");
		
		/* */
		$this->load->model('journals');
		
		$data = array();
		$data = $this->journals->le($id);
		$journal = $data['jnl_codigo'];
		
		/* */
		$this->load->model('editions');		
		$data['edicoes'] = $this->editions->editions($journal); 
		
		/* VIEW */
		$this -> load -> view("brapci/journal_editions",$data);
		
				
		$this -> load -> view("header/foot");
	}	
}	
?>