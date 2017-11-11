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

class DGZ extends CI_Controller {

	var $idj = 4;

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

	function index() {
		$this -> load -> view("header/cab");

		$this -> session -> userdata('search');
		$this -> load -> view("header/cab");

		/* */
		$this -> load -> model('journals');

		$data = array();
		$data = $this -> journals -> le($this->idj);
		$journal = $data['jnl_codigo'];

		/* */
		$this -> load -> model('editions');
		$data['edicoes'] = $this -> editions -> editions($journal,'2');
		if (!isset($data['issue_view'])) {
			$data['issue_view'] = '';
		}

		/* VIEW */
		$this -> load -> view("brapci/journal_editions", $data);

		$this -> load -> view("header/foot");
	}

}
?>
