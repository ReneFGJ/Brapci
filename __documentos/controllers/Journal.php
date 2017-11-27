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
		date_default_timezone_set('America/Sao_Paulo');
		
	}
	function index() {
		global $dd;
		
		form_sisdoc_getpost();
	
		$this->session->userdata('search');		
		$this -> load -> model('Search');
		$this -> load -> view("header/cab");
		
		/* */
		$this->load->model('journals');
		
		$data = array();
		$data['tela'] = $this->journals->show_publish();
		$data['titulo'] = 'Publicações disponíveis';
		
		$data['content'] = $this -> load -> view("show",$data, true);
		$data['title'] = '';
		$this->load->view('content',$data);
		
		$this -> load -> view("header/foot");
	}
	
	function issue($id=0) {
		redirect('http://www.brapci.inf.br/index.php/journal/issue/'.$id);
		exit;
		/* Models */
		$this -> load -> model('Search');
		$this->load->model('journals');
		$this->load->model('editions');

	
		global $dd;	
		form_sisdoc_getpost();
		
		$this -> load -> view("header/cab");
		
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
		redirect('http://www.brapci.inf.br/index.php/journal/view/'.$id);
		exit;		
		global $dd;
		
		form_sisdoc_getpost();
		
		$this -> load -> model('Search');	
		$this->session->userdata('search');		
		$this -> load -> view("header/cab");
	
		/* */
		$this->load->model('journals');
		
		$data = array();
		$data = $this->journals->le($id);
		$journal = $data['jnl_codigo'];
		
		/* */
		$this->load->model('editions');		
		$data['edicoes'] = $this->editions->editions($journal); 
			if (!isset($data['issue_view']))
				{
					$data['issue_view'] = '';
				}
		
		/* VIEW */
		$this -> load -> view("brapci/journal_editions",$data);
		
				
		$this -> load -> view("header/foot");
	}	
}	
?>