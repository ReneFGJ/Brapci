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

		$db_public = 'brapci_publico.';
		date_default_timezone_set('America/Sao_Paulo');
	}

	function selected() {
		$this -> load -> model('Search');
		echo $this -> Search -> selected();
	}

	function mark($id = 0, $ts = 0) {
		global $db_public;

		/* Marcar */
		$dd1 = substr(get('dd1'), 3, 10);
		$dd2 = (get('dd2'));
		
		$session = $_SESSION['bp_session'];

		$user = '';

		if (strlen($dd1) > 0) {
			$sql = "select * from " . $db_public . "usuario_selecao 
						where sel_work = '" . $dd1 . "' 
								and sel_sessao = '$session' ";
			$rlt = $this -> db -> query($sql);
			$rlt = $rlt -> result_array();
			if (count($rlt) > 0) {
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
				if ($dd2 == 'false') {
					$at = 0;
				} else {
					$at = 1;
				}
				$line = $rlt[0];
				$sql = "update " . $db_public . "usuario_selecao set sel_ativo = $at where id_sel = " . $line['id_sel'];
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

		echo '<img src="' . base_url('img/icone_my_library.png') . '" height="20">';
		echo ' ' . $total . ' ' . msg('SELECTED');
	}

	function index($id = 0, $ts = 0) {
		echo 'ok';
	}

}
?>