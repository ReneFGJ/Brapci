<?php

class dicionario extends CI_Controller {
	function __construct() {
		global $db_public;

		$db_public = 'brapci_publico.';
		parent::__construct();
		$this -> load -> library('form_validation');
		$this -> load -> database();
		$this -> load -> helper('form');
		$this -> load -> helper('form_sisdoc');
		$this -> load -> helper('url');
		$this -> load -> library('session');
		$this -> lang -> load("app", "portuguese");
		date_default_timezone_set('America/Sao_Paulo');
	}

	function index() {

	}

	public function inport_dictionary() {
		echo "IMPORTAÇÂO DE DICIONÁRIO";
		$file = fopen("D:\projeto\Brapci\__documentos\pt-PT.dic", 'r+');
		$s = '';
		while (!(feof($file))) {
			$s .= fread($file, 2048);
			$s = troca($s, '[', '<');
			$s = troca($s, ']', '>');
		}
		fclose($file);
		$s = strip_tags($s);

		$s = troca($s, chr(13), '');
		$s = troca($s, chr(10), '');
		$s = troca($s, chr(15), '');
		$s = troca($s, chr(9), '#');

		$s = troca($s, '/', '<');
		$s = troca($s, '#', '>¢');
		$s = troca($s, '¢', '#');
		$s = strip_tags($s);

		$s = troca($s, '>', '');

		$term = splitx("#", $s);

		$sql = "INSERT INTO dicionario (dc_termo, dc_idioma, dc_codigo, dc_use, dc_auto) values ";
		$id = 0;
		for ($r = 5; $r < count($term); $r++) {
			if ((int)($r / 5) == ($r / 5)) {
				if ($id > 0) {
					$this -> db -> query($sql.';');
					$sql = "INSERT INTO dicionario (dc_termo, dc_idioma, dc_codigo, dc_use, dc_auto) VALUES ";
					$id = 0;
				}
			}
			$termo = (UpperCase($term[$r]));
			if ($id > 0) { $sql .= ', '; }
			$sql .= " ('$termo','pt_BR','','',1) " . chr(13) . chr(10);
			$id++;
		}
		if ($id > 0) {
			$this -> db -> query($sql.';');
			$sql = "";
		}
	}

}
