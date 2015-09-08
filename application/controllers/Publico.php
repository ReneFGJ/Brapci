<?php
class publico extends CI_controller {
	var $page_load_type = 'J';
	var $jid = '';

	function __construct() {
		global $db_public, $session;

		parent::__construct();
		$this -> load -> library('form_validation');
		$this -> load -> database();
		$this -> load -> helper('form');
		$this -> load -> helper('form_sisdoc');
		$this -> load -> helper('url');
		$this -> load -> helper('xml');
		/* $this -> lang -> load("app", "portuguese"); */
		$this -> load -> library('session');
		$session = date("1508130000");
		
		$db_public = 'brapci_publico.';
	}

	function mark($id = 0, $ts = 0) {
		global $db_public, $session;
		$dd1 = substr($this -> input -> post('dd1'), 3, 10);
		$dd2 = ($this -> input -> post('dd2'));

		if (strlen($dd1) > 0) {
			$email = $_SESSION['email'];
			$sql = "select * from users where us_email = '$email' ";
			$rlt = $this -> db -> query($sql);
			$rlt = $rlt -> result_array();

			$line = $rlt[0];
			$user = $line['us_codigo'];

			$sql = "select * from " . $db_public . "usuario_selecao where sel_work = '" . $dd1 . "' ";
			$rlt = $this -> db -> query($sql);
			$rlt = $rlt -> result_array();
			if (count($rlt) > 0)
				{
					$line = $rlt[0];
				} else {
					$rlt = array();
				}
			$data = date("Ymd");
			$hora = date("H:i");
			
			if (count($rlt) == 0) {
				$sql = "insert into " . $db_public . "usuario_selecao 
					(
					sel_work, sel_sessao, sel_ativo, 
					sel_data, sel_hora, sel_usuario	
					) values (
					'$dd1','$session','1',
					$data,'$hora','$user'
					)";
				$rlt = $this -> db -> query($sql);
			} else {
				if ($dd2 == 'false')
				{
					$at = 0;
				} else {
					$at = 1;
				}
				$line = $rlt[0];
				$sql = "update ".$db_public."usuario_selecao set sel_ativo = $at where id_sel = ".$line['id_sel'];
				$rlt = $this -> db -> query($sql);
			}
		}

		$sql = "select count(*) as total from " . $db_public . "usuario_selecao 
					where sel_sessao = '$session' 
							and sel_ativo = 1 ";
		
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		$line = $rlt[0];
		$total = $line['total'];
		
		echo '<img src="'.base_url('img/icone_my_library.png').'" height="20">';
		echo $total;
	}

	function index($id = 0, $ts = 0) {
		echo 'ok';
	}

}
?>