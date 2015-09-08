<?php
class publico extends CI_controller {
	var $page_load_type = 'J';
	var $jid = '';

	function __construct() {
		global $db_public;

		parent::__construct();
		$this -> load -> library('form_validation');
		$this -> load -> database();
		$this -> load -> helper('form');
		$this -> load -> helper('form_sisdoc');
		$this -> load -> helper('url');
		$this -> load -> helper('xml');
		/* $this -> lang -> load("app", "portuguese"); */
		$this -> load -> library('session');
		$db_public = 'brapci_publico.';
	}

	function mark($id = 0, $ts = 0) {
		echo 'ok';
	}

}
?>