<?php
class oai_pmh extends CI_model {
	function repository_list() {
		$sql = "select * from brapci_journal 
						where jnl_status <> 'X'
						order by jnl_nome
						";
		$rlt = db_query($sql);
		$sx = '';
		$sx .= '<table width="100%" class="lt1">';
		while ($line = db_read($rlt)) {
			$link = '<A HREF="' . trim($line['jnl_url']) . '" target="_new">';
			$link_oai = '<A HREF="' . base_url('index.php/oai/Identify/' . $line['id_jnl']) . '">';

			$sx .= '<TR>';
			$sx .= '<td>';
			$sx .= $line['jnl_nome'];
			$sx .= '<td>';
			$sx .= $line['jnl_token'];
			$sx .= '<td>';
			$sx .= stodbr($line['jnl_last_harvesting']);
			$sx .= '<td align="center">';
			$sx .= $line['jnl_artigos_indexados'];

			/* OAI */
			$sx .= '<td align="center">';
			if (strlen(trim($line['jnl_url_oai'])) > 0) {
				$sx .= $link_oai;
				$sx .= '<img src="' . base_url('img/icone_oai.png') . '" height="16" border=0 title="Link da coleta OAI">';
				$sx .= '</A>';
			} else {
				$sx .= '-';
			}

			/* Site */
			$sx .= '<td align="center">';
			if (strlen(trim($line['jnl_url'])) > 0) {
				$sx .= $link;
				$sx .= '<img src="' . base_url('img/icone_url.png') . '" height="16" border=0 title="Site da publicação">';
				$sx .= '</A>';
			} else {
				$sx .= '-';
			}
			$ln = $line;
		}
		$sx .= '</table>';
		return ($sx);
	}

	function oai_listset($ida, $setSepc, $date) {
		$ida = trim($ida);
		$jid = $this -> jid;
		$njid = strzero($jid, 7);

		$sql = "select * from oai_cache where cache_oai_id = '$ida' ";
		$rlt = $this -> db -> query($sql);
		$line = $rlt -> result_array();

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
			$this -> db -> query($sql);
		}
	}

	function ListIdentifiers_Method_1($url) {
		$rs = load_page($url);

		$xml_rs = $rs['content'];
		$xml = simplexml_load_string($xml_rs);

		$xml = $xml -> ListIdentifiers -> header;

		for ($r = 0; $r < count($xml); $r++) {
			$ida = $xml[$r] -> identifier;
			$date = $xml[$r] -> datestamp;
			$setSpec = $xml[$r] -> setSpec;
			$this -> oai_listset($ida, $setSpec, $date);
		}
		return (0);

	}

	/** PROCESS */
	function process_oai($jid = 0) {
		$wh = ' 1 = 1 ';
		if ($jid > 0) { $wh = " cache_journal = '" . strzero($jid, 7) . "' ";
		}
		$sql = "select * from oai_cache 
						where cache_status = 'A'
						and $wh
						order by cache_tentativas
						limit 1
					";
		$rlt = db_query($sql);
		if ($line = db_read($rlt)) {
			$file_id = strzero($line['id_cache'], 7);
			$file_id = 'ma/oai/' . $file_id . '.xml';
			if (file_exists($file_id)) {
				$xml = load_file_local($file_id);
				/* Le XML */
				$article = $this -> process_le_xml($xml, $file_id);
				/* Processa dados */
				$this->load->view("oai/oai_process",$article);
				
				/* Recupera Issue */
				$issue = $this->recupera_issue($article);
				
			} else {
				echo 'ERROR';
			}
		}
	}
	
	function recupera_issue($issue)
		{
			
		}
	function process_le_xml($xml_rs, $file) {
		$dom = new DOMDocument;
		$dom = new DOMDocument;
		$dom -> load($file);

		/* Array */
		$doc = array();

		$nodes = $dom -> getElementsByTagName('metadata');

		/* Recupeda dados */
		foreach ($nodes as $node) {

			/* Recupera titulos */
			$titles = $node -> getElementsByTagName("title");
			$id = 0;
			foreach ($titles as $title) {
				$value = $title -> nodeValue;
				$lang = $title -> attributes -> getNamedItem('lang') -> value;
				$dt = array();
				$dt['title'] = $value;
				$dt['idioma'] = $lang;
				$doc['titles'][$id] = $dt;
				$id++;
			}
			/* Recupera autores */
			$titles = $node -> getElementsByTagName("creator");
			$id = 0;
			foreach ($titles as $title) {
				$value = $title -> nodeValue;
				$dt = array();
				$dt['name'] = $value;
				$doc['authors'][$id] = $dt;
				$id++;
			}
			/* Recupera KeyWorkds */
			$titles = $node -> getElementsByTagName("subject");
			$id = 0;
			foreach ($titles as $title) {
				$value = $title -> nodeValue;
				$lang = $title -> attributes -> getNamedItem('lang') -> value;
				$dt = array();
				$dt['term'] = $value;
				$dt['idioma'] = $lang;
				$doc['keywords'][$id] = $dt;
				$id++;
			}
			/* Recupera Resumos */
			$titles = $node -> getElementsByTagName("description");
			$id = 0;
			foreach ($titles as $title) {
				$value = $title -> nodeValue;
				$lang = $title -> attributes -> getNamedItem('lang') -> value;
				$dt = array();
				$value = troca($value,'&nbsp;','xxx');
				$value = troca($value,chr(10),'x');
				$value = troca($value,chr(13),'x');
				$value = troca($value,chr(15),'x');
				$value = troca($value,chr(12),'x');
				$value = troca($value,chr(42),'x');
								
				$value = troca($value,'  ',' ');
				$dt['content'] = $value;
				$dt['idioma'] = $lang;
				$doc['abstract'][$id] = $dt;
				$id++;
			}

			/* link */

			$titles = $node -> getElementsByTagName("identifier");
			$id = 0;
			foreach ($titles as $title) {
				$value = $title -> nodeValue;
				$dt = array();
				$dt['link'] = $value;
				$doc['links'][$id] = $dt;
				$id++;
			}

			/* Source */
			$titles = $node -> getElementsByTagName("source");
			$id = 0;
			foreach ($titles as $title) {
				$value = $title -> nodeValue;
				$dt = array();
				$dt['source'] = $value;
				$doc['sources'][$id] = $dt;
				$id++;
			}
			return ($doc);
		}
		return ( array());
	}

	function coleta_oai_cache_next($id) {
		$jid = strzero($id, 7);
		$sql = "select * from oai_cache
					inner join brapci_journal on jnl_codigo = cache_journal
					where cache_journal = '$jid'
					and cache_status = '@'
			";
		$rlt = db_query($sql);

		$sr = 'nothing to harvesting';

		if ($line = db_read($rlt)) {
			$url = trim($line['jnl_url_oai']);
			$ido = trim($line['cache_oai_id']);
			$idr = $line['id_cache'];

			/* Atualiza registro de coleta */
			$sql = "update oai_cache set cache_tentativas = cache_tentativas + 1 where id_cache = " . $id;
			$this -> db -> query($sql);

			/* Method 1 */
			$link = $url . '?verb=GetRecord';
			$link .= '&metadataPrefix=oai_dc';
			$link .= '&identifier=' . $ido;
			$xml_rt = load_page($link);
			$xml = $xml_rt['content'];

			$sr = '<BR><font color="grey">Cache:</font> ' . $ido . ' <font color="green">coletado</font>';

			$file = 'ma/oai/' . strzero($idr, 7) . '.xml';
			$f = fopen($file, 'w+');
			fwrite($f, $xml);
			fclose($f);

			$sql = "update oai_cache set cache_status='A' where id_cache = " . $idr;
			$this -> db -> query($sql);

			/* Meta refresh */
			$sr .= '<meta http-equiv="refresh" content="3">';
		}
		return ($sr);

	}

	function oai_resumo_to_harvesing() {
		$sql = "select count(*) as total, cache_journal, jnl_nome from oai_cache 
					inner join brapci_journal on jnl_codigo = cache_journal
						where cache_status = '@'
						group by cache_journal, jnl_nome
						order by jnl_nome ";
		$rlt = db_query($sql);
		$t = array(0, 0, 0, 0);
		$sx = '';
		while ($line = db_read($rlt)) {
			$link = '<A HREF="' . base_url('index.php/oai/Harvesting/' . $line['cache_journal']) . '">';
			$sx .= '<BR>' . $link . $line['jnl_nome'] . '</A>';
			$sx .= ' (' . $line['total'] . ')';
		}
		return ($sx);
	}

	function oai_resumo($jid = 0) {
		$wh = ' 1 = 1 ';
		if ($jid > 0) { $wh = " cache_journal = '" . strzero($jid, 7) . "' ";
		}

		$sql = "select count(*) as total, cache_status from oai_cache 
						where $wh 
						group by cache_status ";
		$rlt = db_query($sql);
		$t = array(0, 0, 0, 0);
		while ($line = db_read($rlt)) {
			$sta = $line['cache_status'];
			$tot = $line['total'];
			switch($sta) {
				case '@' :
					$t[0] = $t[0] + $line['total'];
					break;
				case 'B' :
					$t[1] = $t[1] + $line['total'];
					break;
				case 'A' :
					$t[2] = $t[2] + $line['total'];
					break;
			}
		}
		$sx = '<table width="500" align="center">';
		$sx .= '<TR align="center" class="lt1" style="background-color: #E0E0E0; ">';
		$sx .= '<td rowspan=2 width="30%" class="lt4">OAI-PMH';
		$sx .= '<TD>para coletar</td>';
		$sx .= '<TD>para processar</td>';
		$sx .= '<TD>coletado</td>';
		$sx .= '<TR align="center" class="lt4">';
		$sx .= '<TD width="23%">';
		$sx .= $t[0];
		$sx .= '<TD width="23%">';
		$sx .= $t[2];
		$sx .= '<TD width="23%">';
		$sx .= $t[1];
		$sx .= '</table>';
		return ($sx);
	}

}
?>
