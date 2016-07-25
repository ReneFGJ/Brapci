<?php
class user_drh extends CI_Model {
	var $table = 'user_drh';

	function le($id, $fld = 'id') {
		$sql = "select * from " . $this -> table;
		$sql .= " left join _filiais ON usd_empresa = id_f "; 
		$sql .= ' where usd_id_us = ' . round($id);

		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) == 0) {
			$sql = "insert into " . $this -> table . " (usd_id_us) values ($id)";
			$rlt = $this -> db -> query($sql);

			return ( array());
		} else {
			return ($rlt[0]);
		}
	}

	function cp() {
		$cp = array();
		array_push($cp, array('$H8', 'id_usd', '', False, True));
		array_push($cp, array('$S80', 'usd_nome_pai', 'Nome do pai', True, True));
		array_push($cp, array('$S80', 'usd_nome_mae', 'Nome da mãe', True, True));
		return ($cp);
	}

}
