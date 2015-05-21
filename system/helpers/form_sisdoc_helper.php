<?php
/**
 * CodeIgniter
 * sisDOC Labs
 *
 * @package	CodeIgniter
 * @author	Rene F. Gabriel Junior <renefgj@gmail.com>
 * @copyright Copyright (c) 2006 - 2015, sisDOC
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	http://codeigniter.com
 * @since	Version 2.1.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter Form Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		Rene F. Gabriel Junior <renefgj@gmail.com>
 * @link		http://www.sisdoc.com.br/CodIgniter
 */
$dd = array();

function load_page($url) {
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

function strzero($ddx, $ttz) {
	$ddx = round($ddx);
	while (strlen($ddx) < $ttz) { $ddx = "0" . $ddx;
	}
	return ($ddx);
}

function UpperCase($d) {
	$d = strtoupper($d);
	$d = troca($d, 'ç', 'Ç');
	$d = troca($d, 'ñ', 'Ñ');

	$d = troca($d, 'ã', 'Ã');
	$d = troca($d, 'ä', 'Ä');
	$d = troca($d, 'à', 'À');
	$d = troca($d, 'á', 'Á');
	$d = troca($d, 'â', 'Â');

	$d = troca($d, 'ë', 'Ë');
	$d = troca($d, 'è', 'È');
	$d = troca($d, 'é', 'É');
	$d = troca($d, 'ê', 'Ê');

	$d = troca($d, 'ï', 'Ï');
	$d = troca($d, 'ì', 'Ì');
	$d = troca($d, 'í', 'Í');
	$d = troca($d, 'î', 'Î');

	$d = troca($d, 'õ', 'Õ');
	$d = troca($d, 'ö', 'Ö');
	$d = troca($d, 'ò', 'Ò');
	$d = troca($d, 'ó', 'Ó');
	$d = troca($d, 'ô', 'Ô');

	$d = troca($d, 'ü', 'Ü');
	$d = troca($d, 'ù', 'Ù');
	$d = troca($d, 'ú', 'Ú');
	$d = troca($d, 'û', 'Û');

	return $d;
}

function msg($x) {
	return ($x);
}

function db_query($sql) {
	global $dbn;
	$dbn = 0;
	$CI = &get_instance();
	$query = $CI -> db -> query($sql);
	return ($query -> result());
}

/*
 * http://www.kathirvel.com/php-convert-or-cast-array-to-object-object-to-array/
 */
function object_to_array($object) {
	return (array)$object;
}

/*
 * http://www.kathirvel.com/php-convert-or-cast-array-to-object-object-to-array/
 */
function array_to_object($array) {
	return (object)$array;
}

/*
 * Rene
 */

function stodbr($data = 0) {
	if ($data < 19100101) {
		return ('');
	} else {
		$dt = substr($data, 6, 2) . '/' . substr($data, 4, 2) . '/' . substr($data, 0, 4);
		return ($dt);
	}
}

function form_sisdoc_getpost() {
	global $dd, $acao;

	$CI = &get_instance();
	$post = $CI -> input -> post();
	$get = $CI -> input -> get();
	$vars = array_merge($get, $post);

	if (!isset($vars['acao'])) { $acao = '';
	} else { $acao = troca($vars['acao'], "'", '´');
	}

	for ($k = 0; $k < 100; $k++) {
		$varf = 'dd' . $k;
		if (isset($vars[$varf])) {

			$varf = $vars[$varf];
			$dd[$k] = post_security($varf);
		} else {
			$dd[$k] = '';
		}
	}
	return (true);
}

function post_security($s) {
	$s = troca($s, '<', '&lt;');
	$s = troca($s, '>', '&gt;');
	$s = troca($s, '"', '&quot;');
	//$s = troca($s,'/','&#x27;');
	$s = troca($s, "'", '&#x2F;');
	return ($s);
}

function nbr_autor($xa, $tp) {
	if (strpos($xa, ',') > 0) {
		$xb = trim(substr($xa, strpos($xa, ',') + 1, 100));
		$xa = trim(substr($xa, 0, strpos($xa, ',')));
		$xa = trim(trim($xb) . ' ' . $xa);
	}
	$xa = $xa . ' ';
	$xp = array();
	$xx = "";
	for ($qk = 0; $qk < strlen($xa); $qk++) {
		if (substr($xa, $qk, 1) == ' ') {
			if (strlen(trim($xx)) > 0) {
				array_push($xp, trim($xx));
				$xx = '';
			}
		} else {
			$xx = $xx . substr($xa, $qk, 1);
		}
	}

	$xa = "";

	/////////////////////////////
	$xp1 = "";
	$xp2 = "";
	$er1 = array("JUNIOR", "JÚNIOR", "JúNIOR", "NETTO", "NETO", "SOBRINHO", "FILHO", "JR.");
	///////////////////////////// SEPARA NOMES
	{
		$xop = 0;
		for ($qk = count($xp) - 1; $qk >= 0; $qk--) {

			$xa = trim($xa . ' - ' . $xp[$qk]);
			if ($xop == 0) { $xp1 = trim($xp[$qk] . ' ' . $xp1);
				$xop = -1;
			} else { $xp2 = trim($xp[$qk] . ' ' . $xp2);
			}

			if ($xop == -1) {
				$xop = 1;
				for ($kr = 0; $kr < count($er1); $kr++) {
					if (trim(UpperCaseSQL($xp[$qk])) == trim($er1[$kr])) {
						$xop = 0;
					}
				}
			}
		}
	}

	////////// 1 e 2
	$xp2a = strtolower($xp2);
	$xa = trim(trim($xp2) . ' ' . trim($xp1));
	if (($tp == 1) or ($tp == 2)) {
		if ($tp == 1) { $xp1 = UpperCase($xp1);
		}
		$xa = trim(trim($xp1) . ', ' . trim($xp2));
		if ($tp == 2) { $xa = UpperCaseSQL(trim(trim($xp1) . ', ' . trim($xp2)));
		}
	}
	if (($tp == 3) or ($tp == 4)) {
		if ($tp == 4) { $xa = UpperCaseSQL($xa);
		}
	}

	if (($tp >= 5) or ($tp <= 6)) {
		$xp2a = str_word_count(lowerCaseSQL($xp2), 1);
		$xp2 = '';
		for ($k = 0; $k < count($xp2a); $k++) {
			if ($xp2a[$k] == 'do') { $xp2a[$k] = '';
			}
			if ($xp2a[$k] == 'da') { $xp2a[$k] = '';
			}
			if ($xp2a[$k] == 'de') { $xp2a[$k] = '';
			}
			if (strlen($xp2a[$k]) > 0) { $xp2 = $xp2 . substr($xp2a[$k], 0, 1) . '. ';
			}
		}
		$xp2 = trim($xp2);
		if ($tp == 6) { $xa = UpperCaseSQL(trim(trim($xp2) . ' ' . trim($xp1)));
		}
		if ($tp == 5) { $xa = UpperCaseSQL(trim(trim($xp1) . ', ' . trim($xp2)));
		}
	}

	////////////////////////////////////////////////////////////////////////////////////
	if (($tp == 7) or ($tp == 8)) {
		$mai = 1;
		$xa = strtolower($xa);
		for ($r = 0; $r < strlen($xa); $r++) {
			if ($mai == 1) { $xa = substr($xa, 0, $r) . UpperCase(substr($xa, $r, 1)) . substr($xa, $r + 1, strlen($xa));
				$mai = 0;
			} else {
				if (substr($xa, $r, 1) == ' ') { $mai = 1;
				}
			}
		}
		$xa = troca($xa, 'De ', 'de ');
		$xa = troca($xa, 'Da ', 'da ');
		$xa = troca($xa, 'Do ', 'do ');
	}
	return $xa;
}

function db_read($rlt) {
	global $dba, $dbn;
	if (!isset($dba)) { $dba = array();
	}

	/* */
	if (count($rlt) == 0) {
		return (FALSE);
	}

	/* */
	if (!isset($dbn)) { $dbn = 0;
	}

	$row = object_to_array($rlt[0]);

	$keys = array_keys($row);
	$key = $keys[0];

	if ((!isset($dba[$key])) or ($dbn == 0)) {
		$dba[$key] = 0;
	} else {
		$dba[$key] = $dba[$key] + 1;
	}
	$dbn = 1;
	$id = round($dba[$key]);

	if ($id >= count($rlt)) {
		return (FALSE);
	} else {
		$rslt = $row = object_to_array($rlt[$id]);
		return ($rslt);
	}

	exit ;
}

function page() {
	$page = base_url();

	return ($page);
}

function load_file_local($file) {
	$sx = '';
	$fld = fopen($file, 'r');
	while (!(feof($fld))) {
		$sx .= fread($fld, 1024);
	}
	fclose($fld);
	return ($sx);
}

/* Funcao */
function UpperCaseSQL($d) {
	$d = strtoupper($d);
	$d = troca($d, 'ç', 'C');
	$d = troca($d, 'Ç', 'C');
	$d = troca($d, 'ñ', 'N');
	$d = troca($d, 'Ñ', 'N');

	$d = troca($d, 'ã', 'A');
	$d = troca($d, 'ä', 'A');
	$d = troca($d, 'à', 'A');
	$d = troca($d, 'á', 'A');
	$d = troca($d, 'â', 'A');

	$d = troca($d, 'Ã', 'A');
	$d = troca($d, 'Ä', 'A');
	$d = troca($d, 'À', 'A');
	$d = troca($d, 'Á', 'A');
	$d = troca($d, 'Â', 'A');

	$d = troca($d, 'ë', 'E');
	$d = troca($d, 'è', 'E');
	$d = troca($d, 'é', 'E');
	$d = troca($d, 'ê', 'E');

	$d = troca($d, 'Ë', 'E');
	$d = troca($d, 'È', 'E');
	$d = troca($d, 'É', 'E');
	$d = troca($d, 'Ê', 'E');

	$d = troca($d, 'ï', 'I');
	$d = troca($d, 'ì', 'I');
	$d = troca($d, 'í', 'I');
	$d = troca($d, 'î', 'I');

	$d = troca($d, 'Ï', 'I');
	$d = troca($d, 'Ì', 'I');
	$d = troca($d, 'Í', 'I');
	$d = troca($d, 'Î', 'I');

	$d = troca($d, 'õ', 'O');
	$d = troca($d, 'ö', 'O');
	$d = troca($d, 'ò', 'O');
	$d = troca($d, 'ó', 'O');
	$d = troca($d, 'ô', 'O');

	$d = troca($d, 'Õ', 'O');
	$d = troca($d, 'Ö', 'O');
	$d = troca($d, 'Ò', 'O');
	$d = troca($d, 'Ó', 'O');
	$d = troca($d, 'Ô', 'O');

	$d = troca($d, 'ü', 'U');
	$d = troca($d, 'ù', 'U');
	$d = troca($d, 'ú', 'U');
	$d = troca($d, 'û', 'U');

	$d = troca($d, 'Ü', 'U');
	$d = troca($d, 'Ù', 'U');
	$d = troca($d, 'Ú', 'U');
	$d = troca($d, 'Û', 'U');

	return $d;
}

function LowerCaseSQL($term) {
	$term = UpperCaseSql($term);
	$term = Strtolower($term);
	return ($term);
}

// ------------------------------------------------------------------------
class form {
	var $cp = array();
	var $data = array();
	var $post = array();
	var $tabela = '';

	/* row */
	var $fd = array();
	var $lb = array();
	var $mk = array();
	var $edit = false;
	var $see = false;
	var $new = false;

	var $row_view = '';
	var $row_edit = '';
	var $offset = 30;
}

/* Paginacao */
function npag($obj, $npage = 1, $tot = 10, $offset = 20) {
	$page = uri_string();
	$pagm = $tot;

	/* algoritimo */
	$page = substr($page, 0, strpos($page, '/'));
	$link = $obj -> row;

	$pagi = $npage;
	$pagf = $npage + 10;

	if ($pagi > 5) {
		$pagi = $pagi - 5;
		$pagf = $pagf - 5;
	} else {
		$pagi = 1;
	}

	$sx = '<ul id="npag" class="npag">';
	if ($pagi > 1) {
		$linka = '<A HREF="' . $link . '/' . ($pagi - 1) . '">';
		$sx .= $linka . '<li><<</li></A> ';
	}

	/* PAGINACAO */
	//echo '==>'.$tot.'= (inicial):'.$pagi.'= (final):'.$pagf;
	if ($pagf > $tot) { $pagf = $tot;
	}
	for ($r = $pagi; $r < ($pagf + 1); $r++) {
		$linka = '<A HREF="' . $link . '/' . $r . '">';
		$sx .= $linka . '<li>' . $r . '</li></a>' . chr(10) . chr(13);
	}
	/* */
	if ($pagf < $pagm) {
		$linka = '<A HREF="' . $link . '/' . $r . '">';
		$sx .= $linka . '<li>>></li></A>';
	}
	/* */
	$sx .= '<li style="width: 100px; border: 0px solid #FFFFFF; background-color: #ffffff;">';
	$sx .= 'Page:';
	$linka = $link . '/';
	$sx .= '<select onChange="location=\'' . $linka . '\'+this.options[this.selectedIndex].value;">';
	for ($r = 1; $r <= $pagm; $r++) {
		$chk = '';
		if ($r == $npage) { $chk = "selected";
		}
		$sx .= '<option value="' . $r . '" ' . $chk . '>' . $r . '</option>';
	}
	$sx .= '</select>';
	$sx .= '</li>';

	/* Busca */
	$sx .= '<li style="width: 50px; border: 0px solid #FFFFFF; background-color: #ffffff;"><nobr>';
	$sx .= 'Filtro:';
	$sx .= '</li>';
	$sx .= '<li style="width: 220px; border: 0px solid #FFFFFF; background-color: #ffffff;"><nobr>';

	$vlr = '';
	$sx .= form_open();
	$data = array('name' => 'dd1', 'id' => 'dd1', 'value' => $vlr, 'maxlength' => '100', 'size' => '100', 'style' => 'width:150px', );
	$sx .= form_input($data);
	$sx .= form_hidden('dd2', 'search');

	$sx .= form_submit('acao', 'busca');
	$sx .= form_close();
	$sx .= '</li>';

	$sx .= '<li style="width: 50px; border: 0px solid #FFFFFF;"><nobr>';
	$sx .= form_open();
	$data = array('name' => 'dd2', 'id' => 'dd1', 'dd9' => 'clean');
	$sx .= form_hidden($data);
	$sx .= form_submit('acao', 'limpa filtro');
	$sx .= form_close();
	$sx .= '</li>';
	$sx .= '</ul>';

	return ($sx);
}

/* Funcao troca */
if (!function_exists('troca')) {
	function troca($qutf, $qc, $qt) {
		if (is_array($qutf)) {
			return ('erro');
		}
		return (str_replace(array($qc), array($qt), $qutf));
	}

}

if (!function_exists('splitx')) {
	function splitx($in, $string) {
		$string .= $in;
		$vr = array();
		while (strpos(' ' . $string, $in)) {
			$vp = strpos($string, $in);
			$v4 = trim(substr($string, 0, $vp));
			$string = trim(substr($string, $vp + 1, strlen($string)));
			if (strlen($v4) > 0) { array_push($vr, $v4);
			}
		}
		return ($vr);
	}

}

if (!function_exists('form_edit')) {
	/* Form Edit
	 * @parameter $cp - campos de edicao
	 * @parameter $tabela - nome da tabela que le/insere/atualiza registro
	 * @paramrter $id - chave primaria do registro
	 * @parameter $data - Dados do post inserir no controler: $data = $this->input->post();
	 */

	function row($obj, $pag = 1) {
		$start = round($pag);
		$offset = $obj -> offset;
		$start = $pag * $offset;
		$CI = &get_instance();

		/* POST */
		$post = $CI -> input -> post();
		$dd = $post;

		$acao = '';
		$term = '';
		if (isset($post)) {
			if (isset($post['dd2'])) { $acao = $post['dd2'];
			}
			if (isset($post['dd1'])) { $term = $post['dd1'];
			}
			$term = troca($term, "'", "´");
		}

		$fd = $obj -> fd;
		$mk = $obj -> mk;
		$lb = $obj -> lb;
		/* parametros */
		$edit = $obj -> edit;
		$see = $obj -> see;
		$new = $obj -> new;

		/* campo ID */
		$fld = $fd[0];

		$sh = '<thead><tr>';
		for ($r = 1; $r < count($fd); $r++) {
			$label = $lb[$r];
			$sh .= '<th>' . $label . '</th>';
			/* campos da consulta */
			$fld .= ', ' . $fd[$r];
		}
		if ($obj -> edit == True) {
			$sh .= '<th>action</th>';
		}
		$sh .= '</tr></thead>';

		/* Recupera dados */
		$tabela = $obj -> tabela;

		$CI = &get_instance();

		/* */
		if (strlen($acao) == 0) {
			if (isset($CI -> session -> userdata['row_termo'])) {
				$term = $CI -> session -> userdata['row_termo'];
			} else {
				$term = '';
			}
		}
		if (isset($post['dd9'])) {
			if ($post['dd9'] == 'clean') {
				$term = '';
				$CI -> session -> userdata['row_termo'] = '';
			}
		}

		/* Where */
		if (strlen($term) > 0) {
			$newdata = array('row_termo' => $term);
			$CI -> session -> set_userdata($newdata);

			$term = troca($term, ' ', ';');
			$term = splitx(';', $term);

			$wh = ' (' . $fd[1] . " like '%" . $term[0] . "%') ";
			$wh = ' where ' . $wh;
		} else {
			$wh = '';
		}
		if (strlen($acao) > 0) {
			$pag = 1;
		}

		/* total de registros */
		$sql = "select count(*) as total from " . $tabela . " $wh ";
		$query = $CI -> db -> query($sql);
		$row = $query -> row();
		$total = $row -> total;

		/* mostra */
		$start_c = ($start - $offset);
		if ($start_c < 1) { $start_c = 1;
		}

		$sql = "select $fld from " . $tabela . ' ' . $wh;
		$sql .= " order by " . $fd[1];
		$sql .= " limit " . $start_c . " , " . $offset;
		$query = $CI -> db -> query($sql);
		$data = '';

		/* Metodo de chamada */
		$url_pre = uri_string();
		$url_pre = substr($url_pre, 0, strpos($url_pre, '/')) . '/view';

		$url_pre = $obj -> row_view;

		foreach ($query->result_array() as $row) {
			/* recupera ID */
			$flds = trim($fd[0]);
			$id = $row[$flds];

			/* mostra resultado da query */
			$data .= '<tr>';
			for ($r = 1; $r < count($fd); $r++) {
				/* mascara */
				$flds = trim($fd[$r]);
				$msk = trim($mk[$r]);
				$mskm = '';
				switch($msk) {
					case 'C' :
						$mskm = ' align="center" ';
						break;
					case 'L' :
						$mskm = ' align="left" ';
						break;
					case 'R' :
						$mskm = ' align-"right" ';
						break;
				}

				/* see */
				if ($see == TRUE) {
					$link = '<A HREF="' . $obj -> row_view . '/' . $id . '/' . checkpost_link($id) . '">';
					$linkf = '</A>';
				} else {
					$link = '';
					$linkf = '';
				}
				$data .= chr(15) . '<td ' . $mskm . '>' . $link . trim($row[$flds]) . $linkf . '</td>';
			}
			if ($obj -> edit == True) {
				$idr = trim($row[$fd[0]]);
				$data .= chr(15) . '<td ' . $mskm . '><A HREF="' . $obj -> row_edit . '/' . $idr . '/' . checkpost_link($idr) . '"><img src="' . base_url('/img/icone_edit.gif') . '" border=0 height="16"></td>';
			}
			$data .= '</tr>' . chr(13) . chr(10);
		}

		/* Tela completa */
		$tela = '<table width="100%" id="row">';
		$tela .= $sh;
		$tela .= $data;
		$tela .= '<tr><th colspan=10 align="left">Total ' . $total . ' de registros' . '</th></tr>';
		$tela .= '</table>';

		$total_page = (int)($total / $offset) + 1;

		$pags = npag($obj, $pag, $total_page, $offset);

		return ($pags . $tela);
	}

	function form_save($obj) {
		/* recupera post */
		$CI = &get_instance();
		$post = $CI -> input -> post();

		$tabela = $obj -> tabela;
		$cp = $obj -> cp;
		$id = $obj -> id;

		/* Modo de gravacao */
		if (round($id) > 0) {
			/* Update */
			$sql = "update " . $tabela . " set ";
			$sv = 0;
			for ($r = 1; $r < count($cp); $r++) {
				/* verifica se existe parametro */
				if (isset($post['dd' . $r])) {
					/* verefica se o campo é gravavel */
					if (strlen($cp[$r][1]) > 0) {
						if ($sv > 0) { $sql .= ', ';
						}
						$sql .= $cp[$r][1] . " = '" . $post['dd' . $r] . "' ";
						$sv++;
					}
				}
			}
			$sql .= "where " . $cp[0][1] . ' = ' . $id;
		} else {
			/* Insert */
			$sql = "insert into " . $tabela . " ";
			$sq1 = '';
			$sq2 = '';
			$sv = 0;
			for ($r = 1; $r < count($cp); $r++) {
				if (isset($post['dd' . $r])) {

					if ($sv > 0) { $sq1 .= ', ';
						$sq2 .= ', ';
					}
					$sq1 .= $cp[$r][1];
					$sq2 .= "'" . $post['dd' . $r] . "'";
					$sv++;
				}
			}
			$sql .= '(' . $sq1 . ') values (' . $sq2 . ')';
		}
		if ($sv == 0) { $sql = "";
		} else {
			$CI = &get_instance();
			$query = $CI -> db -> query($sql);
			return (1);
		}

		return (0);
	}

	function form_menu($id, $editar = FALSE, $excluir = FALSE) {
		$CI = &get_instance();
		$url_pre = uri_string();
		$url_edit = troca($url_pre, '/view', '/edit');
		$url_del = troca($url_pre, '/view', '/del');

		$link = '';

		if ($editar == TRUE) {
			$link_editar = base_url($url_edit);
			$link = '<A HREF="' . $link_editar . '" class="link lt0">' . $CI -> lang -> line('sisdocform_edit') . '</A>';
		}

		if ($excluir == TRUE) {
			$link_delete = base_url($url_del);
			$link .= ' | <A HREF="' . $link_delete . '" class="link lt0">' . $CI -> lang -> line('sisdocform_del') . '</A>';
		}
		return ($link);

	}

	function checkpost_link($id) {
		$chk = md5($id . date("Ymd"));
		return ($chk);
	}

	function checkpost($id, $chk1) {
		$chk2 = checkpost_link($id);
		if ($chk1 == $chk2) {
			return (0);
		} else {
			return (1);
		}
	}

	function le_dados($obj) {
		$id = $obj -> id;
		$tabela = $obj -> tabela;
		$fld = $obj -> cp[0][1];

		$sql = "select * from " . $tabela . "  
					where $fld = $id";

		$CI = &get_instance();
		$query = $CI -> db -> query($sql);
		$row = $query -> row();
		return ($row);
	}

	function form_edit($obj) {
		$dd = array($obj -> id);

		/* recupera post */
		$CI = &get_instance();
		$post = $CI -> input -> post();

		$cp = $obj -> cp;
		/* Recupera dados do banco */
		$recupera = 0;
		/* recupera ACAO do post */
		$acao = '';

		if (!isset($post['acao'])) { $recupera = 1;
		}

		/* Save in table */
		if ($recupera == 0) {
			$saved = form_save($obj);
			if ($saved == 1) {
				/* Redireciona */
				if (strlen($obj -> row) > 0) {
					$url_pre = $obj -> row;
				} else {
					$url_pre = uri_string();
					$url_pre = substr($url_pre, 0, strpos($url_pre, '/')) . '/row';
				}
				redirect($url_pre);
				//redirect($link, 'location', 301);
			}
		}

		$tela = '';
		$tela .= '<table class="tabela00" width="100%" border=0 >';
		$tela .= '<tr><td>' . form_open() . '</td></tr>';

		if ($recupera == 1) {
			/* recupera dados do banco */
			$data = le_dados($obj);
		} else {
			$data = array();
		}
		$tela .= 'Recupera = ' . $recupera;

		for ($r = 0; $r < count($cp); $r++) {
			/* Recupera dados */
			$vlr = '';
			if ($recupera == 1) {
				/* banco de dados */
				$fld = $cp[$r][1];
				if (isset($data -> $fld)) {
					$vlr = $data -> $fld;
				}
			} else {
				if (isset($post['dd' . $r])) {
					$vlr = $post['dd' . $r];
				}
			}
			$tela .= form_field($cp[$r], $vlr);
		}
		$tela .= '</table>';
		$tela .= form_close();

		return ($tela);
	}

	/* Botao novo */
	function form_botton_new($url, $txt = 'Novo registro') {
		$link = '<A HREF="' . $url . '/edit/0/' . checkpost_link('0') . '">';
		$sx = $link . '<span class="botton_new">' . $txt . '</span>' . '</A>';
		return ($sx);
	}

	/* Formulário */
	function form_field($cp, $vlr) {
		global $dd, $ddi;
		/* Zera tela */
		$tela = '';

		$table = 1;
		if (!(isset($dd))) { $dd = array();
			$ddi = 0;
		}

		$type = $cp[0];
		$label = $cp[2];
		$required = $cp[3];
		$placeholder = $label;
		$readonly = $cp[4];

		$tt = substr($type, 1, 1);
		$max = 100;
		$size = 100;
		$dados = array();
		$dn = 'dd' . $ddi;

		if ($table == 1) {
			$td = '<td>';
			$tdl = '<td align="right">';
			$tdn = '</td>';

			$tr = '<tr valign="top">';
			$trn = '</tr>';
		} else {
			$td = '';
			$tdl = '';
			$tdn = '';
			$tr = '';
			$trn = '';
		}

		//$dados = array('name'=>'dd'.$ddi, 'id'=>'dd'.$ddi,'value='.$dd[$ddi],'maxlenght'=>$max,'size'=>$size,$class=>'');

		switch ($tt) {
			/* String */
			case 'S' :
				/* TR da tabela */
				$tela .= $tr;

				/* label */
				if (strlen($label) > 0) {
					$tela .= $tdl . $label . ' ';
				}
				if ($required == 1) { $tela .= ' <font color="red">*</font> ';
				}

				$dados = array('name' => $dn, 'id' => $dn, 'value' => $vlr, 'maxlenght' => $max, 'size' => $size, 'placeholder' => $label, 'class' => 'form_string');
				if ($readonly == false) { $dados['readonly'] = 'readonly';
				}
				$tela .= $td . form_input($dados);
				$tela .= $tdn . $trn;
				break;

			/* Oculto */
			case 'H' :
				$dados = array($dn => $vlr);
				$tela .= form_hidden($dados);
				break;
			/* Update */
			case 'U' :
				if (round($vlr) == 0) { $vlr = date("Ymd");
				}
				$dados = array($dn => $vlr);
				$tela .= form_hidden($dados);
				break;

			/* Textarea */
			case 'T' :
				$ntype = trim(substr($type, 2, strlen($type)));
				$ntype = troca($ntype, ':', ';') . ';';
				$param = splitx(';', $ntype);

				/* TR da tabela */
				$tela .= $tr;

				/* label */
				if (strlen($label) > 0) {
					$tela .= $tdl . $label . ' ';
				}
				if ($required == 1) { $tela .= ' <font color="red">*</font> ';
				}

				$data = array('name' => $dn, 'id' => $dn, 'value' => $vlr, 'rows' => $param[1], 'cols' => $param[0], 'class' => 'form_textarea');
				$tela .= $td . form_textarea($data);
				$tela .= $tdn . $trn;
				break;
			/* Select Box */
			case 'Q' :
				$ntype = trim(substr($type, 2, strlen($type)));
				$ntype = troca($ntype, ':', ';') . ';';
				$param = splitx(';', $ntype);
				$options = array('' => '::select an option::');

				/* recupera dados */
				$sql = "select * from (" . $param[2] . ") as tabela ";
				$CI = &get_instance();
				$query = $CI -> db -> query($sql);
				foreach ($query->result_array() as $row) {
					/* recupera ID */
					$flds = trim($param[0]);
					$vlrs = trim($param[1]);
					$flds = $row[$flds];
					$vlrs = $row[$vlrs];
					$options[$flds] = $vlrs;
				}

				$dados = array('name' => $dn, 'id' => $dn, 'size' => 1, 'class' => 'form_select');

				$shirts_on_sale = array('small', 'large');

				/* label */
				if (strlen($label) > 0) {
					$tela .= $tdl . $label . ' ';
				}
				if ($required == 1) { $tela .= ' <font color="red">*</font> ';
				}
				$tela .= '<TD>';
				$tela .= form_dropdown($dados, $options, $vlr);
				break;
			/* Password */
			case 'P' :
				if (strlen($label) > 0) {
					$tela .= $label . ' ';
				}
				$dados = array('name' => $dn, 'id' => $dn, 'value' => $vlr, 'maxlenght' => $max, 'size' => $size);
				$tela .= form_password($dados);
				break;

			/* Button */
			case 'B' :
				$tela .= $tr . $tdl . $td;
				$dados = array('name' => 'acao', 'id' => 'acao', 'value' => $label);
				$tela .= form_submit($dados);
				$tela .= $tdn . $trn;
				break;
		}
		$ddi++;
		return ($tela);
	}

}
?>