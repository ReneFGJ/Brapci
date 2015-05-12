<?php
class security_set extends CI_model {
	function login_set() {
		$newdata = array('nw_user' => 'admin', 'email' => 'admin@brapci.inf.br', 'nw_id' => '1');
		$this -> session -> set_userdata($newdata);
	}
	function security()
		{
			print_r($this->session);
		}

}
