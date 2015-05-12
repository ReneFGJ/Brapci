<?php

Class Pesquisas extends CI_model {
	
	function cp()
		{
			$cp = array();
			array_push($cp,array('$H8','id_w','',False,True));
			array_push($cp,array('$T80:4','w_title','Título',TRUE,True));
			array_push($cp,array('$T80:4','w_author','Autores',TRUE,True));
			array_push($cp,array('$T80:8','w_abstract','Resumo',True,True));
			array_push($cp,array('$T80:2','w_keyword','Palavras-chave',True,True));
			
			array_push($cp,array('$S4','w_year','Year',True,True));
			array_push($cp,array('$S2','w_volume','Vol.',True,True));
			array_push($cp,array('$S2','w_number','Num.',True,True));
			array_push($cp,array('$S2','w_publisehd','Publicação',True,True));
			return($cp);
		}
	
	function row($obj) {
		$obj -> fd = array('id_pq', 'pq_titulo', 'pq_data');
		$obj -> lb = array('ID', 'Nome do autor', 'Tipo');
		$obj -> mk = array('', 'L', 'C', 'C', 'C', 'C');
		return ($obj);
	}

	function le($id) {
		$sql = "select * from " . $this -> tabela . " where id_pq = " . round($id);
		$query = $this -> db -> query($sql);
		$row = $query -> result_array();
		$this->line = $row[0];
		return($this->line);
	}

}
?>
