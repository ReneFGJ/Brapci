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

class oai_pmh extends CI_model {
	var $issue;
	function repository_list() {
		$sql = "select * from brapci_journal 
						where jnl_status <> 'X'
						AND jnl_url_oai <> ''	
						order by jnl_nome
						";
		$rlt = db_query($sql);
		$sx = '<h1>'.msg('oai_journals').'</h1>';
		while ($line = db_read($rlt)) {
			$last = $line['jnl_last_harvesting'];
			$url = $line['jnl_url'];
			//print_r($line);
			//exit;
			$link = '<A HREF="' . trim($line['jnl_url']) . '" target="_new">';
			$link_oai = base_url('index.php/oai/Identify/' . $line['id_jnl']);
			

			$sx .= '<a href="'.$link_oai.'" class="link lt2">';
			$sx .= '<div style="float: left; width: 300px; height: 100px; border: 1px solid #888; margin: 0px 10px 5px 0px; border-radius: 10px; padding: 5px 10px;">';
			$sx .= '<img src="' . base_url('img/icone_oai.png') . '" height="32" border=0 title="Coleta OAI-PMH" align="right">';			
			$sx .= $line['jnl_nome'];
			$sx .= $line['jnl_token'];
			$sx .= '<br><br><font class="lt1">'.msg('last_update').': '.stodbr($line['jnl_last_harvesting']).'</font>';

			$sx .= '</div>';
			$sx .= '</a>';
		}
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

	/** Altera Status **/
	function altera_status_chache($id, $sta) {
		$sql = "update oai_cache set cache_status = '$sta' where id_cache = $id ";
		$this -> db -> query($sql);
		return (1);
	}

	/* SetSepc */
	function save_setspec($set, $tema, $jid) {
		$jid = strzero($jid, 7);
		$sql = "select * from oai_listsets where ls_setspec = '$set' and ls_journal = '$jid' ";
		$rlt = db_query($sql);
		if ($line = db_read($rlt)) {
			$sql = "update oai_listsets set ls_equal = '$tema' where id_ls = " . round($line['id_ls']);
			$this -> db -> query($sql);
			return ('');
		} else {
			$data = date("Ymd");
			$sql = "insert into oai_listsets (
							ls_setspec, ls_setname, ls_setdescription,
							ls_journal, ls_status, ls_data,
							ls_equal, ls_tipo, ls_equal_ed
							) values (
							'$set','$set','',
							'$jid','A','$data',
							'$tema','S','')";
			$rlt = $this -> db -> query($sql);
		}
		return ('');
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
			$idc = $line['id_cache'];
			$file_id = strzero($line['id_cache'], 7);
			$file_id = 'ma/oai/' . $file_id . '.xml';
			if (file_exists($file_id)) {
				$xml = load_file_local($file_id);
				/* Le XML */
				$article = $this -> process_le_xml($xml, $file_id);

				/*********************** registro deleted *******************/
				if ($article['status'] == 'deleted') {
					$this -> altera_status_chache($idc, 'X');
					echo '<meta http-equiv="refresh" content="5">';
					return ('');
				}

				$article['file'] = $file_id;
				/* Processa dados */

				/* Recupera Issue */
				$article['issue_id'] = strzero($this -> recupera_issue($article, $jid), 7);
				$article['issue_ver'] = $this -> issue;

				/* Recupera ano */
				$source = $article['sources'][0]['source'];
				$article['ano'] = $this -> recupera_ano($source);

				/* Recupera Journals ID */
				$article['journal_id'] = strzero($jid, 7);

				/* Titulo principal */
				$titulo = utf8_decode($article['titles'][0]['title']);
				$titulo = utf8_decode(substr($titulo, 0, 44));
				$titulo = UpperCaseSql($titulo);

				/* Valida se existe article cadastrado */
				$sql = "select * from brapci_article where ar_edition = '" . $article['issue_id'] . "' 
						and 
						(ar_titulo_1 like '$titulo%' or ar_titulo_2 like '$titulo%')
				";
				$article['section'] = '';
				$rlt = db_query($sql);
				if ($line = db_read($rlt)) {
					/* Existe */
					$this -> altera_status_chache($idc, 'C');
					$this -> load -> view("oai/oai_process", $article);
				} else {
					if ($article['issue_id'] != '0000000') {

						/* Bloqueado */
						if ($article['issue_id'] == '9999999') {
							$this -> altera_status_chache($idc, 'F');
						} else {
							/* processa e grava dados */
							$ids = $this -> recupera_section($article['setSpec'], $article['journal_id']);
							$article['section'] = $ids;

							if (strlen($ids) == 0) {
								$data = array();
								$data['setspec'] = $article['setSpec'];

								$data['links'] = $article['links'];

								$sql = "select * from brapci_section order by se_descricao ";
								$rlt = db_query($sql);
								$sx = '<table width="100%" class="tabela01"><tr valign="top"><td>';
								$id = 0;
								while ($line = db_read($rlt)) {
									if ($id > 10) { $sx .= '</td><td>';
										$id = 0;
									}
									$sx .= '<a href="' . base_url('index.php/oai/setspec/' . $jid . '/' . $line['se_codigo'] . '/' . $article['setSpec']) . '">' . $line['se_descricao'] . '</a><br>';
									$id++;
								}
								$sx .= '</table>';
								$data['opcoes'] = $sx;
								$this -> load -> view('oai/oai_setname', $data);
								return (0);
							}

							$this -> load -> model('articles');
							$article['codigo'] = $this -> articles -> insert_new_article($article);

							/* Arquivos */
							for ($r = 0; $r < count($article['links']); $r++) {
								$link = $article['links'][0]['link'];
								$this -> articles -> insert_suporte($article['codigo'], $link, $article['journal_id']);
							}

							/* Autores */
							$this -> load -> model('authors');
							$authors = '';
							for ($r = 0; $r < count($article['authors']); $r++) {
								$au = $article['authors'][$r]['name'];
								if (strpos($au, ';') > 0) { $au = substr($au, 0, strpos($au, ';'));
								}
								$authors .= trim($au) . chr(13) . chr(10);
							}
							$this -> authors -> save_AUTHORS($article['codigo'], $authors);

							/* Salva Keywords */
							$this -> load -> model('keywords');
							$authors = '';
							$keys = array();
							if (isset($article['keywords'])) {
								for ($r = 0; $r < count($article['keywords']); $r++) {
									$ido = $article['keywords'][$r]['idioma'];
									if ($ido == 'pt-BR') { $ido = 'pt_BR';
									}
									if ($ido == 'en-US') { $ido = 'en';
									}
									$au = $article['keywords'][$r]['term'];
									if (isset($keys[$ido])) {
										$keys[$ido] .= $au . ';';
									} else {
										$keys[$ido] = $au . ';';
									}
								}
							}
							foreach ($keys as $key => $value) {
								$this -> keywords -> save_KEYWORDS($article['codigo'], $value, $key);
							}
							$this -> altera_status_chache($idc, 'B');
							/**************** FIM DO PROCESSAMENTO ***************************************/
						}
					} else {
						$jid = $article['journal_id'];
					}
					//exit;
				}

				$this -> load -> view("oai/oai_process", $article);

			} else {
				$this -> altera_status_chache($idc, '@');
				echo 'ERROR';
			}
		}
	}

	function recupera_ano($s) {
		//$s = trim(sonumero($s));
		$ano = '';
		for ($r = (date("Y") + 1); $r > 1940; $r--) {
			if (strpos($s, trim($r)) > 0) {
				if (strlen($ano) == 0) {
					return ($r);
				}
			}
		}
		return ($ano);
	}
	/******************************************************************************
	 * RECUPERA NUMERO ************************************************************
	 ******************************************************************************/
	function recupera_nr($s) {
		$nr = '';
		if (strpos($s, 'n.')) { $nr = substr($s, strpos($s, 'n.'), strlen($s));
		}
		if (strpos($s, 'No ')) { $nr = substr($s, strpos($s, 'No ') + 3, strlen($s));
		}
		if (strlen($nr) > 0) {
			if (strpos($nr, ',') > 0) { $nr = substr($nr, 0, strpos($nr, ','));
			}
			if (strpos($nr, '-') > 0) { $nr = substr($nr, 0, strpos($nr, '-'));
			}			
			if (strpos($nr, '(') > 0) { $nr = substr($nr, 0, strpos($nr, '('));
			}			
			$nr = troca($nr, 'n. ', '');
			$nr = troca($nr, ' ', 'x');
			if (strpos($nr, 'x') > 0) { $nr = substr($nr, 0, strpos($nr, 'x'));
			}
			$nr = troca($nr, 'x', '');
			$nr = troca($nr, 'n.', '');
			$nr = trim($nr);
		}
		return ($nr);
	}
	/******************************************************************************
	 * RECUPERA VOLUME ************************************************************
	 ******************************************************************************/
	function recupera_vol($s) {
		$vl = '';
		if (strpos($s, 'v.')) { $vl = substr($s, strpos($s, 'v.'), strlen($s));
		}
		if (strpos($s, 'Vol ')) { $vl = substr($s, strpos($s, 'Vol ') + 4, strlen($s));
		}
		if (strpos($s, 'Vol. ')) { $vl = substr($s, strpos($s, 'Vol. ') + 5, strlen($s));
		}		

		if (strlen($vl) > 0) {
			if (strpos($vl, ',') > 0) { $vl = substr($vl, 0, strpos($vl, ','));
			}
			$vl = troca($vl, 'v. ', '');
			if (strpos($vl, ' ') > 0) { $vl = substr($vl, 0, strpos($vl, ' '));
			}
			$vl = troca($vl, 'v.', '');
			$vl = trim($vl);
		}
		return ($vl);
	}

	function recupera_section($sec, $jid) {
		$sql = "select * from oai_listsets where ls_setspec = '$sec' and ls_journal = '$jid'";
		$rlt = db_query($sql);
		if ($line = db_read($rlt)) {
			$rsec = trim($line['ls_equal']);
		} else {
			$data = array();
			return ('');
			$rsec = '';
		}
		return ($rsec);
	}

	function recupera_issue($rcn, $jid) {
		$issue = $rcn['sources'];
		for ($r = 0; $r < count($issue); $r++) {
			$si = $issue[$r]['source'];
			$ano = $this -> recupera_ano($si);
			$nr = $this -> recupera_nr($si);
			$vol = $this -> recupera_vol($si);
			/* Trata issue */
			$jid = strzero($jid, 7);

			$sql = "select * from brapci_edition where 
									ed_vol = '$vol'
									and ed_nr = '$nr'
									and ed_ano = '$ano' 
									and ed_journal_id = '$jid' ";
			$rlt = db_query($sql);
			//echo '<HR>'.$sql.'<hr>';
			$sx = "v. $vol, n. $nr, $ano";
			$this -> issue = $sx;

			if ($line = db_read($rlt)) {
				$eds = $line['ed_status'];
				if ($eds == 'A') {
					return ($line['id_ed']);
				} else {
					return ('9999999');
				}
			} else {
				return (0);
			}
		}

	}

	function process_le_xml($xml_rs, $file) {
		$dom = new DOMDocument;
		$dom = new DOMDocument;
		
		/* Arquivo vazio */
		$fr = fopen($file,'r');
		$st = fread($fr,512);
		fclose($fr);
		
		echo $file;
		if (strlen($st)==0)
		{
			exit;
			$doc['status'] = 'deleted';
			return ($doc);
		}
		$dom -> load($file);

		/* Array */
		$doc = array();

		/* Header */
		$headers = $dom -> getElementsByTagName('header');
		$status = '';
		foreach ($headers as $header) {
			//$setSpec = $header -> nodeValue;
			if (isset($header -> attributes -> getNamedItem('status') -> value)) {
				$status = $header -> attributes -> getNamedItem('status') -> value;
			}
		}

		/* Registro deletado, nao processar */
		if ($status == 'deleted') {
			echo '<br>'.$status;
			$doc['status'] = 'deleted';
			return ($doc);
		} else {
			$doc['status'] = 'active';
		}

		/* setSpec */
		$headers = $dom -> getElementsByTagName('setSpec');
		$size = ($headers->length);
		/* Header inválido */
		if ($size < 1)
			{
				$doc['status'] = 'deleted';
				return ($doc);
				exit;
			}
		
		foreach ($headers as $header) {
			$setSpec = $header -> nodeValue;
		}
		$doc['setSpec'] = $setSpec;

		/* setSpec */
		$idf = '';
		$headers = $dom -> getElementsByTagName('identifier');
		foreach ($headers as $header) {
			if (strlen($idf) == 0) {
				$idf = $header -> nodeValue;
			}
		}
		$doc['idf'] = $idf;

		$nodes = $dom -> getElementsByTagName('metadata');

		/* Recupeda dados */
		foreach ($nodes as $node) {

			/* Recupera titulos */
			$titles = $node -> getElementsByTagName("title");
			$id = 0;
			foreach ($titles as $title) {
				$value = $title -> nodeValue;
				$value = troca($value, "'", "´");
				$lang = $title -> attributes -> getNamedItem('lang') -> value;
				if ($lang == 'pt-BR') { $lang = 'pt_BR';
				}
				if ($lang == 'en-US') { $lang = 'en';
				}

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
				$value = troca($title -> nodeValue, "'", '´');
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
				if ($lang == 'pt-BR') { $lang = 'pt_BR';
				}
				if ($lang == 'en-US') { $lang = 'en';
				}
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
				if ($lang == 'pt-BR') { $lang = 'pt_BR';
				}
				if ($lang == 'en-US') { $lang = 'en';
				}
				$dt = array();

				$value = troca($value, '  ', ' ');
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
		$sx = '<h1>Record to harvesting</h1>';
		while ($line = db_read($rlt)) {
			$link = '<A HREF="' . base_url('index.php/oai/Harvesting/' . $line['cache_journal']) . '">';
			$sx .= '' . $link . $line['jnl_nome'] . '</A>';
			$sx .= ' (' . $line['total'] . ')<BR>';
		}
		return ($sx);
	}

	function oai_resumo_to_progress() {
		$sql = "select count(*) as total, cache_journal, jnl_nome from oai_cache 
					inner join brapci_journal on jnl_codigo = cache_journal
						where cache_status = 'A'
						group by cache_journal, jnl_nome
						order by jnl_nome ";
		$rlt = db_query($sql);
		$t = array(0, 0, 0, 0);
		$sx = '<br><br><h1>Record to process</h1>';
		while ($line = db_read($rlt)) {
			$link = '<A HREF="' . base_url('index.php/oai/Harvesting/' . $line['cache_journal']) . '">';
			$sx .= '' . $link . $line['jnl_nome'] . '</A>';
			$sx .= ' (' . $line['total'] . ')<BR>';
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
				default :
					$t[3] = $t[3] + $line['total'];
					break;
			}
		}
		$sx = '<table width="600" align="center">';
		$sx .= '<TR align="center" class="lt1" style="background-color: #E0E0E0; ">';
		$sx .= '<td rowspan=2 width="30%" class="lt4">OAI-PMH';
		$sx .= '<TD>para coletar</td>';
		$sx .= '<TD>coletado</td>';
		$sx .= '<TD>processado</td>';
		$sx .= '<TD>total</td>';
		$sx .= '<TR align="center" class="lt4">';
		$sx .= '<TD width="15%">';
		$sx .= number_format($t[0], 0, ',', '.');
		$sx .= '<TD width="15%">';
		$sx .= number_format($t[2], 0, ',', '.');
		$sx .= '<TD width="15%">';
		$sx .= number_format(($t[1] + $t[3]), 0, ',', '.');
		$sx .= '<TD width="15%">';
		$sx .= number_format(($t[0] + $t[1] + $t[2] + $t[3]), 0, ',', '.');
		$sx .= '</table>';
		return ($sx);
	}



	function doublePDFlink() {
		$sql = "select * from (
						SELECT bs_adress, count(*) as total, max(id_bs) as id 
							FROM `brapci_article_suporte` 
						WHERE bs_type = 'URL' 
						 	and bs_adress like 'http%'
						 	and (bs_status ='A' or bs_status = '@')
						 	and bs_adress <> ''
						 group by bs_adress
					) as tabela
				where total > 1
				";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) > 0) {
			for ($r = 0; $r < count($rlt); $r++) {
				$line = $rlt[$r];
				$adress = $line['bs_adress'];
				$id = $line['id'];
				$sql = "update brapci_article_suporte 
						set bs_status = 'D' 
					WHERE bs_adress = '$adress' 
							and id_bs <> $id ";
				$xrlt = $this -> db -> query($sql);
			}
		} else {
			return (0);
		}
	}
	
	function fileExistPDFlink($pag=0)
		{
			$sz = 30;
			$OFFSET = ($pag * 100);
			$data = date("Ymd");
			$sql = "select * from brapci_article_suporte 
					WHERE bs_update <> '$data'
					and bs_type = 'PDF'
					order by id_bs 
					LIMIT 100 OFFSET $OFFSET
					
					";
			$rlt = $this->db->query($sql);
			$rlt = $rlt->result_array();
			$sx = '';
			for ($r=0;$r < count($rlt);$r++)
				{
					$line = $rlt[$r];
					$sx .= '<br>';
					$sx .= ($r+$pag * 100).'. ';
					$file = $line['bs_adress']; 
					$sx .= $file;
					if (file_exists($file))
					{
						$sx .= ' <b><font color="green">OK</font></b>'.cr();
					} else {
						$sx .= ' <b><font color="red">file not found</font></b>'.cr();
					}
				}
				 if (count($rlt) > 0)
				 	{
				 		$sx .= '<META http-equiv="refresh" content="5;URL='.base_url('index.php/admin/fileexist_pdf/'.($pag+1)).'">';
				 	}
			return($sx);
		}

	function totalPDFharvesting() {
		$sql = "select count(*) as total from (
						SELECT `bs_article` as art, count(*) as total FROM `brapci_article_suporte` WHERE bs_type = 'URL' group by bs_article
						   )
						   as tebela
						 inner join brapci_article_suporte on art = bs_article
						 where total = 1 and bs_adress like 'http%'
						 and bs_status ='A' or bs_status = '@'
						 and art <> '' 
					limit 1";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) > 0) {
			return ($rlt[0]['total']);
		} else {
			return (0);
		}

	}

	function nextPDFharvesting() {
		$sql = "select * from (
							SELECT `bs_article` as art, count(*) as total 
							FROM `brapci_article_suporte` 
							WHERE bs_type = 'URL' group by bs_article
						   )
						   as tebela
						 inner join brapci_article_suporte on art = bs_article
						 where total = 1 and bs_adress like 'http%'
						 and bs_status ='A' or bs_status = '@'
						 and art <> '' 
					order by art desc
					limit 1";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) > 0) {
			$id = $rlt[0]['id_bs'];
			$sql = "update brapci_article_suporte set bs_status = 'T' where id_bs = " . $id;
			$this -> db -> query($sql);
			return ($rlt[0]);
		} else {
			return (0);
		}

	}
	
	function nextPDFconvert() {
		$data = date("Ymd");
		$sql = "select * from brapci_article_suporte where bs_status = 'T'
					and bs_update <> $data
					limit 1";
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array();
		if (count($rlt) > 0) {
			$id = $rlt[0]['id_bs'];
			$sql = "update brapci_article_suporte set bs_status = 'U', bs_update = $data 
						where id_bs = " . $id;
			$this -> db -> query($sql);
			return ($rlt[0]);
		} else {
			return (0);
		}

	}	

}
?>
