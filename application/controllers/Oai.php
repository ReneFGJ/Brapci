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
	}

	// http://agora.emnuvens.com.br/ra/oai?verb=ListIdentifiers&metadataPrefix=oai_dc
	function ListIdentifiers($id = 0) {
		$data['title'] = 'Brapci : OAI-PMH';
		$data['title_page'] = 'ADMIN - OAI';
		$this -> load -> view("header/cab_admin", $data);

		$this -> load -> model('journals');
		$data = $this -> journals -> le($id);
		
		echo $this->oai_resumo();

		$link = $data['jnl_url_oai'];
		$this -> jid = $data['id_jnl'];

		/* part I */
		$link .= '?';

		/* part II */
		$link .= 'verb=ListIdentifiers';

		/* part II */
		$link .= '&metadataPrefix=oai_dc';
		$meth1 = '1';

		// Initiate connection
		switch ($meth1) {
			case '1' :
				$this -> ListIdentifiers_Method_1($link);
				break;
		}
		// Initialize session and set URL.
	}

	function oai_listset($ida, $setSepc, $date) {
		$ida = trim($ida);
		$jid = $this->jid;
		$njid = strzero($jid,7);
		
		$sql = "select * from oai_cache where cache_oai_id = '$ida' ";
		$rlt = $this->db->query ($sql);
		$line = $rlt->result_array();
		
		if (count($line) > 0) {
			/* já existe */
		} else {
			/* Insere na agenda */
			$sql = "insert into oai_cache (
					cache_oai_id, cache_status, cache_journal, 
					cache_prioridade, cache_datastamp, cache_setSpec, 
					cache_tentativas
					) values (
					'$ida','@','$njid',
					'1','$date','$setSepc',
					0
					)";
			$this->db->query($sql);
		}
	}

	function ListIdentifiers_Method_1($url) {
		//echo '<HR>' . $url . '<HR>';
		$rs = $this -> get_web_page($url);

		$xml_rs = $rs['content'];
		$xml = simplexml_load_string($xml_rs);
		$xml = $xml -> ListIdentifiers -> header;

		for ($r = 0; $r < count($xml); $r++) {
			$ida = $xml[$r] -> identifier;
			$date = $xml[$r] -> datestamp;
			$setSpec = $xml[$r] -> setSpec;
			$this -> oai_listset($ida, $setSpec, $date);
			$xxx = $xml[$r];
		}
		
	}
	
	function oai_resumo()
		{
			$sql = "select count(*) as total, cache_status from oai_cache where 1=1 group by cache_status ";
			$rlt = db_query($sql);
			$t = array(0,0,0,0);
			while ($line = db_read($rlt))
				{
					$sta = $line['cache_status'];
					$tot = $line['total'];
					switch($sta)
						{
							case '@':
								$t[0] = $t[0] + $line['total'];
								break; 
							case 'B':
								$t[1] = $t[1] + $line['total'];
								break; 
						}
				}
			$sx = '<table width="500" align="center">';
			$sx .= '<TR align="center" class="lt1" style="background-color: #E0E0E0; ">';
			$sx .= '<td rowspan=2 width="30%" class="lt4">OAI-PMH';
			$sx .= '<TD>para coletar</td>';
			$sx .= '<TD>coletado</td>';
			$sx .= '<TR align="center" class="lt4">';
			$sx .= '<TD width="35%">';
			$sx .= $t[0];
			$sx .= '<TD width="35%">';
			$sx .= $t[1];
			$sx .= '</table>';
			return($sx);
		}

	function get_web_page($url) {
		$options = array(CURLOPT_RETURNTRANSFER => true, // return web page
		CURLOPT_HEADER => false, // don't return headers
		CURLOPT_FOLLOWLOCATION => true, // follow redirects
		CURLOPT_ENCODING => "", // handle all encodings
		CURLOPT_USERAGENT => "spider", // who am i
		CURLOPT_AUTOREFERER => true, // set referer on redirect
		CURLOPT_CONNECTTIMEOUT => 120, // timeout on connect
		CURLOPT_TIMEOUT => 120, // timeout on response
		CURLOPT_MAXREDIRS => 10, // stop after 10 redirects
		);

		$ch = curl_init($url);
		curl_setopt_array($ch, $options);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$content = curl_exec($ch);
		$err = curl_errno($ch);
		$errmsg = curl_error($ch);
		$header = curl_getinfo($ch);
		curl_close($ch);

		$header['errno'] = $err;
		$header['errmsg'] = $errmsg;
		$header['content'] = $content;
		return $header;
	}

}
?>
