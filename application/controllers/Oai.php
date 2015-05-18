<?php
class oai extends CI_controller {
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
		$db_public = 'brapci_public.';
	}

	function index() {
		$data['title'] = 'Brapci : OAI-PMH';
		$data['title_page'] = 'ADMIN - OAI';
		$this -> load -> view("header/cab_admin", $data);
		$img = 'lg_oai.jpg';

		$this -> load -> model('oai_pmh');

		$data['content'] = $this -> oai_pmh -> repository_list();

		$this -> load -> view("oai/oai_content", $data);
	}

	function Harvest($id = 0) {
		$end = 0;
		
		/* Max */
		$sql = "select max(id_jnl) as max from brapci_journal ";
		$rlt = db_query($sql);
		$line = db_read($rlt);
		$fim = $line['max'];		
		if ($id > $max) { $end = 1; }

		/* Start */
		if ($id == 0) { $id = 1;
		}

		/* Meta refresh */
		if ($end == 0) {
			$url = base_url('oai/Harvest/' . ($id + 1));
			$data['content'] = '<meta http-equiv="refresh" content="5;' . $url . '">(R)';
			$this -> load -> view('oai/oai_content', $data);

			$this -> ListIdentifiers($id);
		}
		/* */
	}

	function Harvesting($id = 0) {
		$this -> load -> model('oai_pmh');
		$data['title'] = 'Brapci : OAI-PMH';
		$data['id'] = $id;
		$data['title_page'] = 'ADMIN - OAI - Harvesting';
		$this -> load -> view("header/cab_admin", $data);

		if ($id > 0) {
			/* Coleta */

			$this -> load -> view('oai/oai_verbs', $data);

			$this -> load -> model('journals');
			$data = $this -> journals -> le($id);
			$data['id'] = $id;

			$link = $data['jnl_url_oai'];
			$this -> jid = $data['id_jnl'];

			$this -> load -> view('brapci/journal', $data);

			$this -> load -> model('oai_pmh');
			$data['content'] = $this -> oai_pmh -> oai_resumo($id);
			$this -> load -> view('oai/oai_content.php', $data);

			$data['content'] = $this -> oai_pmh -> coleta_oai_cache_next($id);
			$this -> load -> view('oai/oai_content.php', $data);
		} else {
			/* List journals to harvesting */
			$this -> load -> model('oai_pmh');
			$data['content'] = $this -> oai_pmh -> oai_resumo_to_harvesing($id);
			$this -> load -> view('oai/oai_content.php', $data);

			$data['content'] = '<BR><BR><A href="' . base_url('oai/Harvest') . '">Harvesting all journals</A>';
			$this -> load -> view('oai/oai_content.php', $data);
		}

	}

	function Identify($id = 0) {
		$data['title'] = 'Brapci : OAI-PMH';
		$data['title_page'] = 'ADMIN - OAI';
		$this -> load -> view("header/cab_admin", $data);

		$this -> load -> model('journals');
		$data = $this -> journals -> le($id);
		$data['id'] = $id;

		$link = $data['jnl_url_oai'];
		$this -> jid = $data['id_jnl'];

		/* part I */
		$link .= '?';

		/* part II */
		$link .= 'verb=Identify';

		// Initiate connection
		$xml_rt = load_page($link);

		$xml_rs = $xml_rt['content'];
		$xml = simplexml_load_string($xml_rs);
		//$xml = $xml->identify;

		foreach ($xml as $element) {
			foreach ($element as $key => $val) {
				$v1 = $key;
				$v2 = trim($val);
				if (isset($data[$v1])) {
					$data[$v1] .= '; ' . $v2;
				} else {
					$data[$v1] = $v2;
				}

			}
		}

		// Initialize session and set URL.
		$this -> load -> view('oai/oai_verbs', $data);
		$this -> load -> view('brapci/journal', $data);
		$this -> load -> view('oai/oai_identify', $data);

		$this -> load -> model('oai_pmh');
		$data['content'] = $this -> oai_pmh -> oai_resumo($id);
		$this -> load -> view('oai/oai_content.php', $data);

	}

	
	function ListIdentifiers($id = 0) {
		$data['title'] = 'Brapci : OAI-PMH';
		$data['title_page'] = 'ADMIN - OAI';
		$data['id'] = $id;

		$this -> load -> view("header/cab_admin", $data);

		$this -> load -> view('oai/oai_verbs', $data);

		$this -> load -> model('journals');
		$data = $this -> journals -> le($id);
		$this -> load -> view('brapci/journal', $data);

		$link = $data['jnl_url_oai'];
		$this -> jid = $data['id_jnl'];

		if ((strlen($link) == 0) or ($data['jnl_status'] == 'X')) {
			return ('');
		}

		/* part I */
		$link .= '?';
		$link .= 'verb=ListIdentifiers';
		$link .= '&metadataPrefix=oai_dc';
		$data['content'] = $link;
		
		$this->load->view('oai/oai_content',$data);

		$meth1 = '1';

		$this -> load -> model('oai_pmh');

		// Initiate connection
		switch ($meth1) {
			case '1' :
				$this -> oai_pmh -> ListIdentifiers_Method_1($link);
				break;
		}
	}

}
?>
