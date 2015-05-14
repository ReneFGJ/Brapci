<?php
class oai extends CI_controller {
	var $page_load_type = 'J';
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
		$db_public = 'brapci_public.';
	}

	function index() {
		$data['title'] = 'Brapci : OAI-PMH';
		$data['title_page'] = 'ADMIN - OAI';
		$this -> load -> view("header/cab_admin", $data);
		$img = 'lg_oai.jpg';
	}

	// http://agora.emnuvens.com.br/ra/oai?verb=ListIdentifiers&metadataPrefix=oai_dc
	function ListIdentifiers($id = 0) {
		$data['title'] = 'Brapci : OAI-PMH';
		$data['title_page'] = 'ADMIN - OAI';
		$this -> load -> view("header/cab_admin", $data);

		$this -> load -> model('journals');
		$data = $this -> journals -> le($id);

		$link = $data['jnl_url_oai'];

		/* part I */
		$link .= '?';

		/* part II */
		$link .= 'verb=ListIdentifiers';

		/* part II */
		$link .= '&metadataPrefix=oai_dc';

		$url = 'https://agora.emnuvens.com.br/ra/oai?verb=ListIdentifiers&metadataPrefix=oai_dc';

		// Initiate connection

		echo $link . '<HR>';
		// Initialize session and set URL.
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		//curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_CAINFO, getcwd() . "/CAcerts/BuiltinObjectToken-EquifaxSecureCA.crt");

		// Set so curl_exec returns the result instead of outputting it.
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		// Get the response and close the channel.
		$response = curl_exec($ch);
		curl_close($ch);

	}

	function page_load($page) {
		global $host_install;
		if ($this -> page_load_type != 'F') {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $page);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$contents = curl_exec($ch);
			if (curl_errno($ch)) {
				echo curl_error($ch);
				echo "\n<br />";
				$contents = '';
				$this -> ok = 0;
			} else {
				curl_close($ch);
				$this -> ok = 1;
			}
			if (!is_string($contents) || !strlen($contents)) {
				echo "Failed to get contents.";
				$contents = '';
				$this -> ok = 0;
			}
		} else {
			$rlt = fopen($page, 'r+');
			$contents = '';
			while (!(feof($rlt))) {
				$contents .= fread($rlt, 1024);
			}
			fclose($rlt);
			$this -> ok = 1;
		}
		return ($contents);
	}

}
?>
