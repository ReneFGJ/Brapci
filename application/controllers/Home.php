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
		
		date_default_timezone_set('America/Sao_Paulo');
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
		
		/* Dados do Get */
		$ano_ini = round(substr(get("dd3"),0,4));
		$ano_fim = round(substr(get("dd3"),5,4));
		if ($ano_ini < 1900) { $ano_ini = 1972; }
		if ($ano_fim < $ano_ini) { $ano_fim = (date("Y")+1); }
		
		/* data */
		$data = array();
		$data['dd1'] = get("dd1");
		$data['dd2'] = get("dd2");
		$data['dd3'] = get("dd3");
		$data['ano_min'] = 1972;
		$data['ano_max'] = date("Y")+1;
		$data['anoi'] = $ano_ini;
		$data['anof'] = $ano_fim;
		
		$this -> load -> view("brapci/content");
		$this -> load -> view("brapci/search_form",$data);

		/* Busca */
		$tela = $this -> Search -> busca_form($data);
		$data = array('tela' => $tela);

		/* Mostra resultado */
		$this -> load -> view("brapci/search_result", $data);

		/* Mostra rodape */
		$this -> load -> view("header/foot");
	}

	/* Tutorial */
	function tutorial($id = 0)
		{
		$id = 1;
		$this -> load -> view("header/cab");
		$this -> load -> view("brapci/content");
		
		$this -> load -> view("tutorial/tutorial");
		$this -> load -> view("tutorial/tutorial_".strzero($id,3));	
		
		/* Mostra rodape */
		$this -> load -> view("header/foot");
		}
		
	function selection_save($id) {
		global $dd;
		/* Model */
		$this -> load -> model('Search');

		$this -> load -> view("header/cab");

		$this -> load -> view("brapci/content");
		//$this -> load -> view("brapci/search_form");
			
		$data = array();
		$data['tela'] = $this -> Search -> save_session();

		/* Mostra resultado */
		$this -> load -> view("brapci/search_result", $data);

		/* Mostra rodape */
		$this -> load -> view("header/foot");
	}
	
	function selection_send_email($id) {
		global $dd;
		/* Model */
		$this -> load -> model('Search');

		$this -> load -> view("header/cab");

		$this -> load -> view("brapci/content");
		//$this -> load -> view("brapci/search_form");
			
		$data = array();
		$data['tela'] = $this -> Search -> save_session();

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
		//$this -> load -> view("brapci/search_form");
			
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
		//$this -> load -> view("brapci/search_form");	
		
		/* Export Selected */
		$data['session'] = $id;
		$this -> load -> view("brapci/session_cab",$data);		
		
		$data['tela'] = $this -> Search -> session_set($id);
				
		$data = array();
		/* Usuario logado */
		if (isset($_SESSION['email']))
			{
				
				$data['tela'] = $this -> Search -> result_search_selected($session);		
			}
		
		
		$data['tela'] = $this -> Search -> result_search_selected($session);

		/* Mostra resultado */
		$this -> load -> view("brapci/search_result", $data);

		/* Mostra rodape */
		$this -> load -> view("header/foot");
	}
	
	function cited() {
		/* Model */
		$this -> load -> model('Search');
		$session = $this -> Search -> session();

		$this -> load -> view("header/cab");

		$this -> load -> view("brapci/content");
		//$this -> load -> view("brapci/search_form");	
		
		$data['content'] = '<h1>Em construção, aguarde!</h1>';
		/* Mostra resultado */
		$this -> load -> view("content", $data);

		/* Mostra rodape */
		$this -> load -> view("header/foot");
	}
	
	function selection_xls($id) {
		/* Model */
		$this -> load -> model('Search');
		$session = $this -> Search -> session();
		
		$data['tela'] = $this -> Search -> session_set($id);
				
		$data = array();
		$data['content'] = $this -> Search -> result_search_selected_xls($session);
		$data['filename'] = 'xls_selection_'.date("YmdHis").'.xls';
		$this -> load -> view("content_xls", $data);
	}	

}
?>
