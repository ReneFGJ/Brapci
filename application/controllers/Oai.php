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
		$db_public = 'brapci_publico.';
	}

	function coletar_cited($id = 0) {
		$sql = "select * from brapci_article_suporte where id_bs = '$id'";
		$rlt = db_query($sql);
		if ($line = db_read($rlt)) {
			$link = trim($line['bs_adress']);

			/* Expept */
			$sc = 'http://agora.emnuvens.com.br/ra/';
			if (substr($link, 0, strlen($sc)) == $sc) { $link = troca($link, 'http:', 'https:');
			}
			$arti = trim($line['bs_article']);
			$jour = trim($line['bs_journal_id']);
			$stat = trim($line['bs_status']);
		}

		/* Arquivo não localizado */
		if (strlen($link) == 0) {
			echo 'Erro no ID';
			return ('');
		}
		
		/* Methods */

		if (strlen($link) > 0) {

			/* Try One */
			/* If "/view/" in link */
			if (strpos($link, '/view/')) {
				$txt = load_page($link);
				$txt = $txt['content'];
				
				/* Method 1 - articleCitations */
				$sc = '<div id="articleCitations">';
				if (strpos($txt,$sc) > 0)
					{
						$txtr = substr($txt,strpos($txt,$sc),strlen($txt));
						$txtr = substr($txtr,0,strpos($txtr,'</div>'));
						$txtr = strip_tags($txtr);
						
						$txtr = troca($txtr,'$','|');
						$txtr = troca($txtr,chr(13),'$');
						$txtr = troca($txtr,chr(10),'$');
						$txtr = troca($txtr,chr(15),'');
						$txtr = troca($txtr,chr(9),'');
						$citeds = splitx('$',$txtr);
						
						for ($r=0;$r < count($citeds);$r++)
							{
								echo '<BR>'.$citeds[$r];
							}
					}
			}
		}		
	}

	function coletar_pdf($id = 0) {
		$sql = "select * from brapci_article_suporte where id_bs = '$id'";
		$rlt = db_query($sql);
		if ($line = db_read($rlt)) {
			$link = trim($line['bs_adress']);

			/* Expept */
			$sc = 'http://agora.emnuvens.com.br/ra/';
			if (substr($link, 0, strlen($sc)) == $sc) { $link = troca($link, 'http:', 'https:');
			}
			$arti = trim($line['bs_article']);
			$jour = trim($line['bs_journal_id']);
			$stat = trim($line['bs_status']);
		}

		/* Arquivo não localizado */
		if (strlen($link) == 0) {
			echo 'Erro no ID';
			return ('');
		}

		/* Status já coletado */
		if ($stat == 'B') {
			echo '<font color="red">Já coletado</font>';
			return ('');
		}

		/* Methods */

		if (strlen($link) > 0) {

			/* Try One */
			/* If "/view/" in link */
			if (strpos($link, '/view/')) {
				$txt = load_page($link);
				$txt = $txt['content'];

				$comp = 'citation_pdf_url';
				if (strpos($txt, $comp) > 0) {
					/* Garda link original */
					$link_original = $link;
					/* Modifica parametros do link */
					$link = substr($txt, strpos($txt, $comp), 120);
					$link = substr($link, strpos($link, 'http'), strlen($link));
					$link = substr($link, 0, strpos($link, '"'));
					if (strpos($link, 'download')) {

					} else {
						/* Method 2 */
						echo 'ops, error ' . $comp;
						return ('');
					}
				} else {
					echo '<font color="red">Invalid link</font>';
					return ('');
				}
			} else {
				echo 'Link inválido';
				return ('');
			}

			/* Try End */
			$download = 0;
			if (strpos($link, 'download')) {
				$download = 1;
			}

			/* Realizad o download */
			if ($download == 1) {
				/* Prepara o nome do arquivo */
				$filename = '_repositorio';
				$this -> check_dir($filename);
				$filename .= '/' . date("Y");
				$this -> check_dir($filename);
				$filename .= '/' . date("m");
				$this -> check_dir($filename);
				$filename .= '/pdf_' . substr(md5(date("Ymdis")), 10, 10) . '_' . $arti . '.pdf';

				/* Conclui processo */
				$this -> download_and_save($link, $filename);
				/* Novo registro do PDF */
				$sql = "insert brapci_article_suporte 
						(
						bs_status, bs_article, bs_type, 
						bs_adress, bs_journal_id, bs_update
						) values (
						'B','$arti','PDF',
						'$filename','$jour'," . date("Ymd") . ')';
				$this -> db -> query($sql);

				/* Atualiza registro anterior */
				$sql = "update brapci_article_suporte set bs_status = 'B' where id_bs = '$id'";
				$this -> db -> query($sql);
				echo '<font color="green">Successful!!!</font>';
				echo '
				<script>
				location.reload();
				</script>				
				';
				exit;
			}

		}
	}

	function check_dir($dir) {
		if (is_dir($dir)) {
			return (1);
		} else {
			mkdir($dir);
			$rlt = fopen($dir . 'index.php', 'w+');
			fwrite($rlt, '<TT>Acesso negado</tt>');
			fclose($rlt);
		}
	}

	function download_and_save($link, $file_name) {
		$txt = $txt = load_page($link);
		$txt = $txt['content'];
		$fl = fopen($file_name, 'w+');
		fwrite($fl, $txt);
		fclose($fl);
		return (1);
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
		if ($id > $max) { $end = 1;
		}

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

		$this -> load -> view('oai/oai_content', $data);

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
