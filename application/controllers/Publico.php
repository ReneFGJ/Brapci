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
		global $db_public;
		$dd1 = substr($this->input->post('dd1'),3,10);
		$dd2 = ($this->input->post('dd2'));
		echo $dd1;
		$sql = "select * from ".$db_public."usuario_selecao where sel_work = '".$dd1."' ";
		$rlt = $this->db->query($sql);
		$rlt = $rlt->result_array();
		$data = date("Ymd");
		$hora = date("H:i");
		print_r($_SESSION);
		$user = '';
		if (count($rlt) == 0)
		{
			$sql = "insert into ".$db_public."usuario_selecao 
					(
					sel_work, sel_sessao, sel_ativo, 
					sel_data, sel_hora, sel_usuario	
					) values (
					'$dd1','','1',
					$data,'$hora','$user'
					)";
			echo $sql;
		}
		
		echo $sql;
	}
	function index($id = 0, $ts = 0) {
		echo 'ok';
	}
	

}
?>