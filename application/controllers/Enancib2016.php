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

class enancib2016 extends CI_Controller {
	function __construct() {
		global $db_public;

		$db_public = 'brapc607_public.';
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

	function cab($id = 0) {
		$this -> load -> view("enancib/header");
		$data['logo'] = base_url('img/enancib/logo_enancib17.jpg');
		$this -> load -> view("enancib/cab", $data);
	}

	function index($ed = 0) {
		$this -> load -> model('journals');
		$this -> load -> model('editions');
		$issue = 1383;
		if ($ed == 0) {
			$ed = 17;
			$issue = 1383;
		}
		$this -> cab($ed);

		$this -> load -> model('editions');
		$data = $this -> editions -> le($issue);

		$journal = $data['jnl_codigo'];

		$this -> session -> userdata('search');

		$data = array();
		$data = $this -> journals -> le($journal);

		/* Le trabalhos */
		$data['table'] = $this -> editions -> issue_view_bootstrap($issue, 0);
		$data['issue_view'] = $this -> load->view('enancib/table',$data,true);

		/* */
		$data['edicoes'] = '';

		/* VIEW */
		$this -> load -> view("brapci/journal_editions", $data);
	}

}
?>