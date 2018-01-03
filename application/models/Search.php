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

class search extends CI_model {

    var $sessao = '';
    var $js = '';
    var $ssid = '';

    var $limit = 25;

    function __construct() {
        parent::__construct();
        $this -> lang -> load("app", "portuguese");
        $this -> load -> database();
        $this -> load -> helper('form');
        $this -> load -> helper('url');
        $this -> load -> library('session');

        /* Sessao */
        if (!isset($_SESSION['bp_session']) or (strlen($_SESSION['bp_session']) != 10)) {
            $session = $_SERVER['REMOTE_ADDR'];
            if ($session == '::1') { $session = '127.0.0.1';
            }
            $session .= date("Ymdhis");
            $session = substr(md5($session), 5, 10);
            $_SESSION['bp_session'] = $session;
        } else {
            $session = $_SESSION['bp_session'];
        }
        $this -> ssid = $session;
        $this -> sessao = $session;
    }

    function wcount($t1, $t2) {
        $t = lowercasesql($t1);
        $t = troca($t, '.', ' ');
        $t = troca($t, ',', ' ');
        $t = troca($t, ';', ' ');
        $t = troca($t, '"', ' ');
        $t = troca($t, ':', ' ');
        $t = troca($t, '(', ' ');
        $t = troca($t, ')', ' ');
        $t = troca($t, '-', ' ');
        $t = troca($t, '[', ' ');
        $t = troca($t, '<', ' ');
        $t = troca($t, '>', ' ');
        $t = troca($t, '&', ' ');
        $t = troca($t, '=', ' ');
        $t = troca($t, '/', ' ');

        $t = troca($t, chr(13), ' ');
        $t = troca($t, chr(10), ' ');
        $t = troca($t, ']', ' ');
        $t = troca($t, '  ', ' ');
        $ta = troca($t, ' ', ';');

        $t = lowercasesql($t2);
        $t = troca($t, '.', ' ');
        $t = troca($t, ',', ' ');
        $t = troca($t, ';', ' ');
        $t = troca($t, '"', ' ');
        $t = troca($t, ':', ' ');
        $t = troca($t, '(', ' ');
        $t = troca($t, ')', ' ');
        $t = troca($t, '-', ' ');
        $t = troca($t, '[', ' ');
        $t = troca($t, '<', ' ');
        $t = troca($t, '>', ' ');
        $t = troca($t, '&', ' ');
        $t = troca($t, '=', ' ');
        $t = troca($t, '/', ' ');

        $t = troca($t, chr(13), ' ');
        $t = troca($t, chr(10), ' ');
        $t = troca($t, ']', ' ');
        $t = troca($t, '  ', ' ');
        $tb = troca($t, ' ', ';');

        $ln1 = splitx(';', $ta);
        $ln2 = splitx(';', $tb);
        $sx = '';
        $a = array();
        $b = array();
        $b1 = array();
        $b2 = array();
        for ($r = 0; $r < count($ln1); $r++) {
            $l = $ln1[$r];
            if ((strlen($l) > 3) and (!isset($b1[$l])) and (sonumero($l) != $l)) {
                $lt = substr_count($tb, $l);
                $b1[$l] = $lt;
                array_push($a, strzero($lt, 7) . ';' . $l);
            }
        }
        sort($a);

        for ($r = 0; $r < count($ln2); $r++) {
            $l = $ln2[$r];
            if ((strlen($l) > 3) and (!isset($b2[$l])) and (sonumero($l) != $l)) {
                if (isset($b1[$l])) {
                    $lt = substr_count($ta, $l);
                    $b2[$l] = $lt;
                    array_push($b, strzero($lt, 7) . ';' . $l);
                }
            }
        }

        echo '<hr>';
        for ($q = 0; $q < count($a); $q++) {
            $id = (count($a) - $q - 1);
            $l = substr($a[$id],strpos($a[$id],';')+1,strlen($a[$id]));
            if (isset($b2[$l]))
                {
                    $sx .= '<br>' . ($a[$id]).';'.strzero($b2[$l],7);
                }
        }        
        return ($sx);
    }

    function colletions() {
        $sql = "select * from collections
                        where cl_ativo = 1 
                        order by id_cl";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        return ($rlt);
    }

    function save_session() {
        $loged = 0;
        if (isset($_SESSION['user'])) {
            $user = $_SESSION['user'];
            if (strlen($user) > 0) {
                $loged = 1;
                $this -> saving_session();
                return ('');
            }
        }
        if ($loged == 0) {
            /* User not loged */
            $this -> load -> view('errors/search_save_error', null);
            $this -> load -> view('login/login_simple', null);
        }

    }

    function saving_session() {
        $cp = array();
        array_push($cp, array('$H8', '', '', False, False));
        array_push($cp, array('$S80', '', msg('session_name'), True, True));
        array_push($cp, array('$B8', '', msg('save_session'), False, True));
        echo '<br><br><br>';
        $form = new form;
        $tela = $form -> editar($cp, '');
        $data['content'] = $tela;
        $this -> load -> view('content', $data);

    }

    function selected() {
        global $db_public;

        $session = $_SESSION['bp_session'];
        $sql = "select count(*) as total from " . $db_public . "usuario_selecao 
					where sel_sessao = '$session' 
							and sel_ativo = 1 ";

        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        $line = $rlt[0];
        $total = $line['total'];
        /* comando para fechar o HREF se pertinente */

        $sx = ' ' . $total . ' ' . msg('Selected');

        return ($sx);
    }

    function metodo_pontos($titulo, $resumo, $keyword, $rla) {

        $pt = 0;
        $pt1 = 0;
        $pt2 = 0;
        $pt3 = 0;
        $totr = 0;

        for ($r = 0; $r < count($rla); $r++) {
            $ttt = trim($rla[$r]);
            if (strpos(' ' . $titulo, $ttt) > 0) { $pt1++;
            }
            if (strpos(' ' . $resumo, $ttt) > 0) { $pt2++;
            }
            if (strpos(' ' . $keyword, $ttt) > 0) { $pt3++;
            }

        }

        $total = count($rla);
        //echo "<BR>==><B>($pt1)</B>, <B>($pt2)</B> e ($pt3) = ($total)";
        //echo $titulo;

        $pt1 = ($pt1 == count($rla));
        $pt2 = ($pt2 == count($rla));
        $pt3 = ($pt3 == count($rla));

        $pt = ($pt1 * 4) + ($pt2 * 1) + ($pt3 * 2);
        if ($pt > 0) { $srt = ' <A HREF="' . base_url('index.php/about.php#pontos') . '" target="_new"><img src="' . base_url('img/star_' . $pt . '.png') . '" alt="" border="0" align="absmiddle"></A>';
            $mst = true;
        } else { $srt = '';
            $mst = false;
            $totr--;
        }
        $smetodo = 'Metodo 1';
        return ($srt);
    }

    function export_xml() {
        global $db_public;
        $sx = '';
        $sql = "select * from
						( select max(sel_data) as lastupdate, sel_sessao, count(*) as total  
							from " . $db_public . "usuario_selecao
						  	group by sel_sessao) as tabela
						left join " . $db_public . "usuario_estrategia on e_session = sel_sessao
						order by total desc 			
			";
        $rlt = db_query($sql);
    }

    function result_cited() {
        /* m_works */
        $wh = troca($this -> query, 'ar_codigo', 'm_work');
        $sql = "select * from mar_works where " . $wh;
        $sql .= " order by m_ano, m_ref ";
        $rlt = db_query($sql);
        $sx = '<font class="lt1">';

        $xano = '0';
        $id = 0;
        $to = 0;
        while ($line = db_read($rlt)) {
            $id++;
            $to++;
            $ano = $line['m_ano'];
            if ($xano != $ano) {
                $id = 1;
                $sx .= '<hr><B>' . $ano . '</B><HR>';
                $xano = $ano;
            }
            $sx .= $id . '. ' . trim($line['m_ref']);
            if (strlen($line['m_bdoi']) > 0) {
                $sx .= ' <font color="blue">' . $line['m_bdoi'] . '</font>';
            }
            $sx .= '<BR>';
        }
        $sx .= 'Total de ' . $to . ' referencias';
        return ($sx);

    }

    function selections() {
        global $db_public;
        $sx = '';
        $sql = "select * from
						( select max(sel_data) as lastupdate, sel_sessao, count(*) as total  
							from " . $db_public . "usuario_selecao
						  	group by sel_sessao) as tabela
						left join " . $db_public . "usuario_estrategia on e_session = sel_sessao
						order by total desc 			
			";
        $rlt = db_query($sql);
        $sx .= '<table width="100%">';
        $sx .= '<TR><TH>Descricao<TH>Selecao<TH>Atualizacao';
        while ($line = db_read($rlt)) {
            $link = '<A HREF="' . base_url('index.php/home/selection/' . $line['sel_sessao']) . '">';
            $sx .= '<TR>';
            $sx .= '<td class="tabela01">' . $line['e_descricao'];
            $sx .= '<td class="tabela01" align="center">' . $link . $line['sel_sessao'] . '</A>';
            $sx .= '<td class="tabela01" align="center">' . $line['total'];
            $sx .= '<td class="tabela01" align="center">' . stodbr($line['lastupdate']);
        }
        $sx .= '</table>';
        return ($sx);
    }

    function mark_resume() {
        global $ssid, $db_public, $user;
        $sql = "select count(*) as total from " . $db_public . "usuario_selecao 
				 where sel_sessao = '$ssid' and sel_ativo = 1";

        $rlt = db_query($sql);
        if ($line = db_read($rlt)) {
            return ($line['total']);
        }
        return (0);
    }

    function mark($art, $vl) {
        global $ssid, $db_public, $user;

    }

    function session_set($ss) {
        $ss = array('bp_session' => $ss);
        $this -> session -> set_userdata($ss);
        return (1);
    }

    function session() {
        $ss = $this -> session -> userdata('bp_session');
        $sa = array('bp_session' => $ss);
        $this -> session -> set_userdata($sa);
        return ($ss);
    }

    function trata_termo_composto($term) {
        $asp = 0;
        $sa = '';
        $term = troca($term, '\"', '"');
        for ($r = 0; $r < strlen($term); $r++) {
            $ca = substr($term, $r, 1);
            if ($ca == '"') {
                if ($asp == 0) { $asp = 1;
                } else {$asp = 0;
                }
            } else {
                /* troca espaco por _ quando termo composto */
                if (($ca == ' ') and ($asp == 1)) { $ca = '_';
                }
                $sa .= $ca;
            }
        }
        return ($sa);
    }

    /* TRATAMENTO DAS PALAVRAS DE BUSCA
     *
     *
     *
     */
    function where($term = '', $field = '') {
        global $dd;
        $term = $this -> trata_termo_composto($term);
        $term = troca($term, '\"', '');
        $term = troca($term, '"', '');
        $term = troca($term, '(', '#');
        $term = troca($term, '#', ';(;');
        $term = troca($term, ')', '#');
        $term = troca($term, '#', ';);');
        $term = troca($term, ' ', ';');
        if (strlen($term) > 0) { $terms = splitx(';', $term);
        }
        $wh = '';
        $pre = '';
        $par = 0;
        $bor = 0;
        for ($r = 0; $r < count($terms); $r++) {
            $term = $terms[$r];

            if (strlen($term) > 2) {
                $term = troca($term, '_', ' ');
                if (($term == 'NOT') or ($term == 'AND')) {
                    $pre = ' ' . UpperCase($term) . ' ';
                } else {
                    if ((strlen($wh) > 0) and ($bor == 0)) { $wh .= ' AND ';
                    }
                    $wh .= $pre;
                    if (substr($term, 0, 1) == '-') {
                        $wh .= " NOT (" . $field . " like '%" . substr($term, 1, strlen($term)) . "%') ";
                    } else {
                        $wh .= " (" . $field . " like '%" . $term . "%') ";
                    }

                    $pre = '';
                    $bor = 0;
                }
            } else {
                if ($term == 'OR') { $pre = ' OR ';
                }
                if (trim($term) == '(') { $bor = 1;
                    $par++;
                    $wh .= $pre . ' ' . $term;
                    $pre = '';
                }
                if (trim($term) == ')') { $bor = 1;
                    $par--;
                    $wh .= $pre . ' ' . $term;
                    $pre = '';
                }
                //$wh .= $term;
            }
        }
        $wh = '(' . $wh . ')';
        $wh = troca($wh, 'AND  AND', 'AND');
        for ($r = 0; $r < $par; $r++) { $wh .= ')';
        }

        /* Tipos de Publicação, artigos, livros, teses e dissertações */
        $tps = array('srcid', 'srcid2', 'srcid6', 'srcid8');
        $tpf = array('J', 'E', 'T', 'D');
        $sqlw = '';
        for ($r = 0; $r < count($tps); $r++) {
            //$id = $SESSION[$tps[$r]];
            $id = 1;
            if ($id == 1) {
                if (strlen($sqlw) > 0) { $sqlw .= ' or ';
                }
                $sqlw .= " (jnl_tipo = '" . $tpf[$r] . "') ";
            }
        }
        if (strlen($sqlw) > 0) { $wh .= ' AND (' . $sqlw . ' ) ';
        }
        $wh = troca($wh, 'AND  OR', 'OR');
        return ($wh);
    }

    /* result article */
    function result_total_articles($term = '', $datai = '', $dataf = '') {
        global $db_public, $SESSION;
        //$term = utf8_decode($term);

        $sessao = $this -> sessao;

        $wh = $this -> where(UpperCaseSql($term), 'ar_asc');
        if (strlen($datai) > 0) { $wh .= ' and (ar_ano >= ' . $datai . ' )';
        }
        if (strlen($dataf) > 0) { $wh .= ' and (ar_ano <= ' . $dataf . ' )';
        }

        /* Origem do documentos */
        if (strlen($SESSION['srcid']) == 0) {
            /* Todos os tipo */
            $wh .= '';
        } else {
            $wha = '';
            if ((strlen($SESSION['srcid1']) == 0) or ($SESSION['srcid1'] == '1')) {
                $wha .= " Call_Number = '1' ";
            }
            if ((strlen($SESSION['srcid2']) == 0) or ($SESSION['srcid2'] == '1')) {
                if (strlen($wha) > 0) { $wha .= ' or ';
                } $wha .= " Call_Number = '2' ";
            }
            if ((strlen($SESSION['srcid3']) == 0) or ($SESSION['srcid3'] == '1')) {
                if (strlen($wha) > 0) { $wha .= ' or ';
                } $wha .= " Call_Number = '3' ";
            }
            if ((strlen($SESSION['srcid4']) == 0) or ($SESSION['srcid4'] == '1')) {
                if (strlen($wha) > 0) { $wha .= ' or ';
                } $wha .= " Call_Number = '4' ";
            }
            if ((strlen($SESSION['srcid5']) == 0) or ($SESSION['srcid5'] == '1')) {
                if (strlen($wha) > 0) { $wha .= ' or ';
                } $wha .= " Call_Number = '5' ";
            }
            if ((strlen($SESSION['srcid6']) == 0) or ($SESSION['srcid6'] == '1')) {
                if (strlen($wha) > 0) { $wha .= ' or ';
                } $wha .= " Call_Number = '6' ";
            }
            if ((strlen($SESSION['srcid7']) == 0) or ($SESSION['srcid7'] == '1')) {
                if (strlen($wha) > 0) { $wha .= ' or ';
                } $wha .= " Call_Number = '7' ";
            }
            if ((strlen($SESSION['srcid8']) == 0) or ($SESSION['srcid8'] == '1')) {
                if (strlen($wha) > 0) { $wha .= ' or ';
                } $wha .= " Call_Number = '8' ";
            }
        }
        if (strlen($wha) > 0) { $wh .= ' and (' . $wha . ')';
        }

        $sql = "select count(*) as total from " . $db_public . "artigos 
					inner join brapci_journal on jnl_codigo = ar_journal_id
					where " . $wh . "
					";
        $rlt = db_query($sql);
        $line = db_read($rlt);
        $total = $line['total'];
        return ($total);
    }

    /* Trata os registro informados para busca
     *
     *
     *
     *
     */
    function tratar_term($t) {
        /* Termo composto - polilexico */
        $t = troca($t, "\\", '');
        $t = troca($t, "&quot;", '"');
        $as = 0;
        $tr = '';
        for ($r = 0; $r < strlen($t); $r++) {
            $ch = substr($t, $r, 1);
            switch ($ch) {
                case '"' :
                    if ($as == 0) { $as = 1;
                    } else { $as = 0;
                    }
                    break;
                default :
                    if (($as == 1) and ($ch == ' ')) { $ch = '_';
                    }
                    $tr .= $ch;
            }
        }
        return ($tr);
    }

    /* REALIZA BUSCA NO SISTEMA
     *
     *
     *
     *
     *
     */
    function result_article($term = '', $datai = '', $dataf = '', $data = array()) {
        global $db_public, $dd, $SESSION;

        $term = $this -> tratar_term($term);
        $total = $this -> result_total_articles($term, $datai, $dataf);
        $sessao = $this -> sessao;
        $wh = $this -> where(UpperCaseSql($term), 'ar_asc');
        if (strlen($datai) > 0) { $wh .= ' and ar_ano >= ' . $datai;
        }
        if (strlen($dataf) > 0) { $wh .= ' and ar_ano <= ' . $dataf;
        }

        /* Origem do documentos */
        if (strlen($SESSION['srcid']) == 0) {
            /* Todos os tipo */
            $wh .= '';
        } else {
            $wha = '';
            if ($SESSION['srcid1'] == '1') { $wha .= " Call_Number = '1' ";
            }
            if ($SESSION['srcid2'] == '1') {
                if (strlen($wha) > 0) { $wha .= ' or ';
                } $wha .= " Call_Number = '2' ";
            }
            if ($SESSION['srcid3'] == '1') {
                if (strlen($wha) > 0) { $wha .= ' or ';
                } $wha .= " Call_Number = '3' ";
            }
            if ($SESSION['srcid4'] == '1') {
                if (strlen($wha) > 0) { $wha .= ' or ';
                } $wha .= " Call_Number = '4' ";
            }
            if ($SESSION['srcid5'] == '1') {
                if (strlen($wha) > 0) { $wha .= ' or ';
                } $wha .= " Call_Number = '5' ";
            }
            if ($SESSION['srcid6'] == '1') {
                if (strlen($wha) > 0) { $wha .= ' or ';
                } $wha .= " Call_Number = '6' ";
            }
            if ($SESSION['srcid7'] == '1') {
                if (strlen($wha) > 0) { $wha .= ' or ';
                } $wha .= " Call_Number = '7' ";
            }
            if ($SESSION['srcid8'] == '1') {
                if (strlen($wha) > 0) { $wha .= ' or ';
                } $wha .= " Call_Number = '8' ";
            }
        }
        if (strlen($wha) > 0) { $wh .= ' and (' . $wha . ')';
        }
        /**************************** page *************************/
        $page = round($data['pag']);
        if ($page == 0) { $page = 1;
        }
        $pag = round(($page - 1) * $this -> limit);

        /****** offset **********/
        $sql = "select * from " . $db_public . "artigos 
					inner join brapci_journal on ar_journal_id = jnl_codigo
					left join " . $db_public . "usuario_selecao on ar_codigo = sel_work and sel_sessao = '" . $sessao . "'
					where " . $wh . "
					";
        $sql .= " order by ar_ano desc, Article_Title  ";
        $sql .= " limit " . $this -> limit . " offset $pag ";
        $rlt = db_query($sql);

        $this -> query = $wh;

        $sx = chr(13) . chr(10);
        /* total */
        $sx .= ', ' . $this -> lang -> line('form_found') . ' <B>' . $total . '</B> ' . $this -> lang -> line('form_records');

        /******************************** paginação ************************************/
        $sx .= $this -> paginacao($page, $total);

        $sx .= '<div id="result_select">selection</div>';
        $sx .= '<table width="100%" class="lt1">';
        $id = 0;
        while ($line = db_read($rlt)) {
            $sx .= $this -> show_article_mini($line);
        }
        $sx .= '</table>';
        $sx .= chr(13) . chr(10);
        $sx .= $this -> js;
        $sx .= chr(13) . chr(10);
        return ($sx);

    }

    function result_article_key($data = '') {
        global $db_public, $db_base;
        $term_code = $data['dd4'];

        //$total = $this->result_total_articles($term,$datai,$dataf);

        //$term = utf8_decode($term);

        $sessao = $this -> sessao;

        $sql = "SELECT * FROM 
						( select distinct kw_article from brapci_keyword 
								INNER JOIN brapci_article_keyword ON kw_use = kw_keyword 
								WHERE kw_word_asc LIKE '%$term_code%'
						) as tabela					 
					INNER JOIN brapci_publico.artigos on ar_codigo = kw_article 
					INNER JOIN brapci_journal ON ar_journal_id = jnl_codigo
					LEFT JOIN  brapci_publico.usuario_selecao on ar_codigo = sel_work and sel_sessao = '$sessao'
				";
        $sql .= " order by ar_ano desc ";
        $sql .= " limit 100 offset 0 ";

        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        $total = count($rlt);
        $sx = chr(13) . chr(10);
        /* total */

        $sx .= ', found ' . $total;

        $sx .= '<div id="result_select">' . msg('result_search') . '</div>';
        $sx .= '<table width="100%" class="lt1">';
        $id = 0;
        $wh = '';
        while ($line = db_read($rlt)) {
            $sx .= $this -> show_article_mini($line);
            if (strlen($wh) > 0) { $wh .= ' or ';
            }
            $wh .= " ar_codigo = '" . trim($line['ar_codigo']) . "' ";
        }
        $sx .= '</table>';
        $sx .= chr(13) . chr(10);
        $sx .= $this -> js;
        $sx .= chr(13) . chr(10);
        $this -> query = $wh;

        return ($sx);

    }

    function result_article_titles($data = '') {
        global $db_public, $db_base;
        $term_code = $data['dd4'];

        //$total = $this->result_total_articles($term,$datai,$dataf);

        //$term = utf8_decode($term);

        $sessao = $this -> sessao;

        $sql = "SELECT * FROM brapci_publico.artigos
					INNER JOIN brapci_journal ON ar_journal_id = jnl_codigo
					LEFT JOIN brapci_publico.usuario_selecao on ar_codigo = sel_work and sel_sessao = '$sessao'
					WHERE Article_title like '%$term_code%'
				";
        $sql .= " order by ar_ano desc ";
        $sql .= " limit 100 offset 0 ";

        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        $total = count($rlt);
        $sx = chr(13) . chr(10);
        /* total */

        $sx .= ', found ' . $total;

        $sx .= '<div id="result_select">' . msg('result_search') . '</div>';
        $sx .= '<table width="100%" class="lt1">';
        $id = 0;
        $wh = '';
        while ($line = db_read($rlt)) {
            $sx .= $this -> show_article_mini($line);
            if (strlen($wh) > 0) { $wh .= ' or ';
            }
            $wh .= " ar_codigo = '" . trim($line['ar_codigo']) . "' ";
        }
        $sx .= '</table>';
        $sx .= chr(13) . chr(10);
        $sx .= $this -> js;
        $sx .= chr(13) . chr(10);
        $this -> query = $wh;

        return ($sx);

    }

    function result_article_resumos($data = '') {
        global $db_public, $db_base;
        $term_code = $data['dd4'];

        //$total = $this->result_total_articles($term,$datai,$dataf);

        //$term = utf8_decode($term);

        $sessao = $this -> sessao;

        $sql = "SELECT * FROM brapci_publico.artigos
					INNER JOIN brapci_journal ON ar_journal_id = jnl_codigo
					LEFT JOIN brapci_publico.usuario_selecao on ar_codigo = sel_work and sel_sessao = '$sessao'
					WHERE Abstract like '%$term_code%'
				";
        $sql .= " order by ar_ano desc ";
        $sql .= " limit 100 offset 0 ";

        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        $total = count($rlt);
        $sx = chr(13) . chr(10);
        /* total */

        $sx .= ', found ' . $total;

        $sx .= '<div id="result_select">' . msg('result_search') . '</div>';
        $sx .= '<table width="100%" class="lt1">';
        $id = 0;
        $wh = '';
        while ($line = db_read($rlt)) {
            $sx .= $this -> show_article_mini($line);
            if (strlen($wh) > 0) { $wh .= ' or ';
            }
            $wh .= " ar_codigo = '" . trim($line['ar_codigo']) . "' ";
        }
        $sx .= '</table>';
        $sx .= chr(13) . chr(10);
        $sx .= $this -> js;
        $sx .= chr(13) . chr(10);
        $this -> query = $wh;

        return ($sx);

    }

    function result_article_cited($data = '') {
        global $db_public, $db_base;
        $term_code = $data['dd4'];

        //$total = $this->result_total_articles($term,$datai,$dataf);

        //$term = utf8_decode($term);

        $sessao = $this -> sessao;

        $sql = "select * FROM mar_works 
					LEFT JOIN bdoi_doi ON id_doi = m_obra_bdoi
					WHERE m_ref like '%$term_code%' ";
        $sql .= " order by doi_ref, m_ref desc ";
        //$sql .= " limit 100 offset 0 ";
        //echo $sql;
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        $total = count($rlt);
        $sx = chr(13) . chr(10);
        /* total */

        $sx .= ', found ' . $total;

        $sx .= '<div id="result_select">' . msg('result_search') . '</div>';
        $sx .= '<table width="100%" class="lt1">';
        $id = 0;
        $wh = '';
        $xref = '';
        $tot = 0;
        $ida = 0;
        for ($r = 0; $r < count($rlt); $r++) {
            $line = $rlt[$r];
            $idx = $line['m_obra_bdoi'];
            $link = '<a href="' . base_url('index.php/article/view/' . $line['m_work']) . '" target="_new' . $r . '">';
            if (isset($_SESSION['nivel'])) {
                if ($_SESSION['nivel'] > 1) {
                    $link = '<a href="' . base_url('index.php/admin/article_view/' . $line['m_work'] . '/' . checkpost_link($line['m_work'])) . '" target="_new' . $r . '">';
                }
            }

            $ref = $line['doi_ref'];

            if ($idx != $ida) {
                $ida = $idx;
                if ($tot > 1) {
                    $sx .= '<tr><td align="left" colspan=5">Total: ' . $tot . ' trabalhos.</td></tr>';
                }
                $tot = 0;
            }

            if ($ref != $xref) {
                $xref = $ref;
                $sx .= '<tr><td colspan=5 class="big">' . $ref . '</td></tr>' . cr();
            }
            $idx = round($line['doi_use']);
            if ($idx > 0) { $idxm = '*';
            } else { $idxm = '&nbsp;';
            }
            $sx .= '<tr valign="top">
						<td>' . $idxm . '</td>
						<td>' . $link . $line['m_ref'] . '</a>' . '</td></tr>';
            if (strlen($wh) > 0) { $wh .= ' or ';
            }
            $wh .= " ar_codigo = '" . trim($line['m_work']) . "' ";

            $tot++;
        }

        $sx .= '</table>';
        $sx .= chr(13) . chr(10);
        $sx .= $this -> js;
        $sx .= chr(13) . chr(10);
        $this -> query = $wh;

        return ($sx);

    }

    function busca_form_title($data = '') {
        global $dd, $SESSION;

        $SESSION = array();
        $SESSION['ssid'] = '';
        $SESSION['srcid'] = '1';
        for ($r = 0; $r < 100; $r++) {
            $SESSION['srcid' . $r] = '1';
        }

        if (isset($data['anoi'])) {
            $data1 = $data['anoi'];
            $data2 = $data['anof'];
        } else {
            $data1 = 1970;
            $data2 = (date("Y") + 1);
        }
        $sx = '';
        /* registra consulta */

        if (strlen($dd[1])) {
            //$this -> registra_consulta($dd[2]);
        }
        $sa = '';
        if (strlen($data['dd4']) > 0) {
            $sr = $this -> result_search_title($data, $data1, $data2);
            if (strlen($this -> query) > 0) {

                $sa = $this -> result_journals($data);
                $sa .= $this -> result_year();
                $sa .= $this -> result_author();
                $sa .= $this -> result_keyword();
            }
            $sx .= $this -> realce($sr, $data['dd4']);
        }
        $data['tela1'] = $sx;
        $data['tela2'] = $sa;
        return ($data);
    }

    function result_autor_key($data = '') {
        global $db_public, $db_base;
        $term_code = $data['dd4'];
        $sessao = $this -> sessao;
        $sql = "SELECT * FROM 
						( select distinct ae_article from brapci_base.brapci_autor 
								INNER JOIN brapci_base.brapci_article_author ON autor_alias = ae_author 
								WHERE autor_nome_asc LIKE '%$term_code%'
						) as tabela					 
					INNER JOIN brapci_publico.artigos on ar_codigo = ae_article 
					INNER JOIN brapci_base.brapci_journal ON ar_journal_id = jnl_codigo
					LEFT JOIN  brapci_publico.usuario_selecao on ar_codigo = sel_work and sel_sessao = '$sessao'
				";
        $sql .= " order by ar_ano desc ";
        $sql .= " limit 100 offset 0 ";

        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        $total = count($rlt);
        $sx = chr(13) . chr(10);
        /* total */

        $sx .= ', found ' . $total;

        $sx .= '<div id="result_select">selection</div>';
        $sx .= '<table width="100%" class="lt1">';
        $id = 0;
        $wh = '';
        while ($line = db_read($rlt)) {
            $sx .= $this -> show_article_mini($line);
            if (strlen($wh) > 0) { $wh .= ' or ';
            }
            $wh .= " ar_codigo = '" . trim($line['ar_codigo']) . "' ";
        }
        $sx .= '</table>';
        $sx .= chr(13) . chr(10);
        $sx .= $this -> js;
        $sx .= chr(13) . chr(10);
        $this -> query = $wh;

        return ($sx);

    }

    function result_article_auth($term_code = '') {
        global $db_public, $db_base;

        //$total = $this->result_total_articles($term,$datai,$dataf);

        //$term = utf8_decode($term);

        $sessao = $this -> sessao;

        $sql = "select * from " . $db_base . "brapci_article_author 
					inner join " . $db_public . "artigos on ar_codigo = ae_article
					where ae_author = '$term_code'
					";
        $sql .= " order by ar_ano desc ";
        $sql .= " limit 100 offset 0 ";

        $rlt = db_query($sql);

        $sx = chr(13) . chr(10);
        /* total */
        $sx .= ', found ' . $total;

        $sx .= '<div id="result_select">selection</div>';
        $sx .= '<table width="100%" class="lt1">';
        $id = 0;
        $wh = '';
        while ($line = db_read($rlt)) {
            $sx .= $this -> show_article_mini($line);
            if (strlen($wh) > 0) { $wh .= ' or ';
            }
            $wh .= " ar_codigo = '" . trim($line['ar_codigo']) . "' ";
        }
        $sx .= '</table>';
        $sx .= chr(13) . chr(10);
        $sx .= $this -> js;
        $sx .= chr(13) . chr(10);
        $this -> query = $wh;

        return ($sx);

    }

    function result_article_selected($session, $pag = 1) {
        global $db_public;
        $offset = 100 * (round($pag) - 1);
        $total = 0;
        //$total = $this->result_total_articles($term,$datai,$dataf);

        //$term = utf8_decode($term);

        $sql = "select * from " . $db_public . "usuario_selecao 
					inner join " . $db_public . "artigos on ar_codigo = sel_work
					left join brapci_journal on jnl_codigo = ar_journal_id
					left join brapci_journal_tipo on jnl_tipo = jtp_codigo
					left join brapci_section on ar_tipo = se_codigo
					where sel_sessao = '$session' and sel_ativo = 1 
					";

        $sql .= " order by ar_ano desc ";
        $sql .= " limit 300 offset $offset ";

        $this -> query = " sel_sessao = '$session' and sel_ativo = 1 ";

        $rlt = db_query($sql);

        $sx = chr(13) . chr(10);
        /* total */
        $sx .= ', found ' . $total;

        $sx .= '<div id="result_select">selection</div>';
        $sx .= '<table width="100%" class="lt1">';
        $id = 0;
        $wh = '';
        while ($line = db_read($rlt)) {

            $sx .= $this -> show_article_mini($line);
            if (strlen($wh) > 0) { $wh .= ' or ';
            }
            $wh .= " ar_codigo = '" . trim($line['ar_codigo']) . "' ";
        }
        $sx .= '</table>';
        $sx .= chr(13) . chr(10);
        $sx .= $this -> js;
        $sx .= chr(13) . chr(10);
        $this -> query = $wh;

        return ($sx);

    }

    function result_article_selected_xls($session, $pag = 1) {
        global $db_public;
        $offset = 99999;
        $total = 0;

        $sql = "select * from " . $db_public . "usuario_selecao 
					inner join " . $db_public . "artigos on ar_codigo = sel_work
					left join brapci_journal on jnl_codigo = ar_journal_id
					left join brapci_journal_tipo on jnl_tipo = jtp_codigo
					left join brapci_section on ar_tipo = se_codigo
					where sel_sessao = '$session' and sel_ativo = 1 
					";

        $sql .= " order by ar_ano desc ";
        $this -> query = " sel_sessao = '$session' and sel_ativo = 1 ";
        $rlt = db_query($sql);

        $sx = '<table width="100%" class="lt1" border=1>';
        $sx .= '<tr>
					<th>Authors</th>
					<th>Title</th>
					<th>Year</th>
					<th>Source title</th>
					<th>ISSN</th>
					<th>Volume</th>
					<th>Issue</th>
					<th>Art. No.</th>
					<th>Page start</th>
					<th>Page end</th>
					<th>Page count</th>
					<th>Cited by</th>
					<th>DOI</th>
					<th>Link</th>
					<th>Document Type</th>
					<th>Source</th>
					<th>EID</th>
					<th>Abstract</th>
					<th>Keywords</th>
					<th>Language of Original Document</th>
				</tr>';
        $id = 0;
        $wh = '';
        while ($line = db_read($rlt)) {

            $sx .= $this -> export_xls($line);
            if (strlen($wh) > 0) { $wh .= ' or ';
            }
            $wh .= " ar_codigo = '" . trim($line['ar_codigo']) . "' ";
        }
        $sx .= '</table>';
        return ($sx);
    }

    function result_cited_selected($session, $pag = '1') {
        global $db_public, $db_base, $pag;
        if (isset($_GET['pag'])) {
            $pag = $_GET['pag'];
        }

        $offset = 100 * (round($pag));

        //$total = $this->result_total_articles($term,$datai,$dataf);

        //$term = utf8_decode($term);

        $sessao = $this -> sessao;

        $sql = "select * from " . $db_public . "usuario_selecao 
					inner join mar_works on m_work = sel_work
					where sel_sessao = '$sessao' and sel_ativo = 1 
					";

        $sql .= " order by  m_ref, m_ano desc ";
        //$sql .= " limit 300 offset $offset ";
        $rlt = db_query($sql);

        $sx = chr(13) . chr(10);
        /* total */

        $sx .= '<div id="result_select">selection</div>';
        $sx .= '<table width="100%" class="lt1">';
        $id = 0;
        $wh = '';
        while ($line = db_read($rlt)) {
            $id++;
            $sx .= '<BR>';
            $sx .= '[' . $line['m_work'] . ']';
            $sx .= $line['m_ref'];

            //if (strlen($wh) > 0) { $wh .= ' or '; }
            //$wh .= " ar_codigo = '".trim($line['ar_codigo'])."' ";
        }
        $sx .= '<tr><td>' . $id . ' total';
        $sx .= '</table>';

        return ($sx);

    }

    function send_to_email($article, $size = 32) {
        global $user_email;
        $sx = '';
        if (strlen($user_email) > 0) {
            $link = nwin('article_send_email.php?dd0=' . $cod . '&dd99=' . checkpost($cod), 200, 200);
            $sx = '<img src="img/icone_send_to_email_off.png" 
							border=0 title="' . msg('send_to_email') . '" 
							height="' . $size . '"
							onmouseover="this.src=\'img/icone_send_to_email.png\'"
							onmouseout="this.src=\'img/icone_send_to_email_off.png\'"
							' . $link . '
							>';
        }
        return ($sx);
    }

    function show_article_mini($line) {
        global $id, $email, $dd;
        $sx = '';
        $js = '<script>' . chr(13) . chr(10);
        $js .= 'function abstractshow(ms)
					{ $(ms).toggle("slow"); }
					
				function mark(ms,ta)
					{
						alert("Consulta");
						var ok = ta.checked;
						$.ajax({
  							type: "POST",
  							url: "' . base_url('index.php/public/mark/') . '",
  							data: { dd1: ms, dd2: ok }
						}).done(function( data ) {
							$("#basket").html(data);
						});						
					}
					';
        $js .= '</script>' . chr(13) . chr(10);
        $this -> js = $js;

        $link = '<A HREF="' . base_url('index.php/article/view/' . $line['ar_codigo'] . '/' . checkpost_link($line['ar_codigo'])) . '"
					 class="big"
					 target="_new' . $line['ar_codigo'] . '"
					 >';

        $id++;
        $cod = trim($line['ar_codigo']);

        $sx .= '<div class="row">';
        $sx .= '<div class="col-md-12">';

        /* Marcacao */
        $selected = round($line['sel_ativo']);
        if ($selected == 1) { $selected = 'checked';
        } else { $selected = '';
        }
        $jscmd = 'onchange="mark(\'#mt' . trim($cod) . '\',this);" ';
        $fm = '<input type="checkbox" name="ddq" ' . $jscmd . ' ' . $selected . '>';

        /*************** numeracao **************/
        $sx .= $id . '. ';
        $sx .= $fm;
        $sx .= ' - ';

        $sx .= $link;
        $sx .= (trim(UpperCase($line['Article_Title'])));

        $sx .= '</A>';
        /* Show */

        $jscmd = 'onclick="abstractshow(\'#mt' . trim($cod) . '\');" ';

        $sx .= '<BR><p style="margin-right: 20px;">';
        $sx .= '<img src="' . base_url('img/icone_abstract.png') . '" height="16" style="cursor: pointer;" id="it' . $cod . '" ' . $jscmd . ' align="left">';
        /* enviar por e-mail */
        if (isset($email)) { $sx .= $this -> send_to_email($cod, 16);
        }

        //$sx .= '<td colspan=3 class="lt0">';
        $sx .= (trim($line['Author_Analytic']));

        /* Volume numero */
        $vol = trim($line['Volume_ID']);
        $num = trim($line['Issue_ID']);
        $pag = trim($line['Pages']);
        $ano = trim($line['ar_ano']);

        $v = '';
        if (strlen($vol) > 0) { $v .= ', ' . $vol;
        }
        if (strlen($num) > 0) { $v .= ', ' . $num;
        }
        if (strlen($pag) > 0) { $v .= ', ' . $pag;
        }
        if (strlen($ano) > 0) { $v .= ', ' . $ano;
        }

        //$sx .= '<BR>'.utf8_encode($ar->mostra_issue($line));

        $sx .= '<BR>';
        $sx .= '<B>' . (trim($line['Journal_Title'])) . '</B>';
        $sx .= $v;

        $tipo = $line['jnl_tipo'];
        $sect = $line['ar_tipo'];
        $sx .= ' (' . $this -> tipo_publicacao($tipo) . '-' . $sect . ')';

        /* DIV */
        $hid = 'display: none;';
        $sx .= '<div style="text-align: justify; ' . $hid . ' color: #A0A0A0; line-height:130%; margin: 8px; 8px; 8px; 8px;" id="mt' . $cod . '">';
        $abst1 = trim($line['ar_resumo_1']);
        $abst2 = trim($line['ar_resumo_2']);

        if (strlen($abst1) == 0) {
            $resumo = $abst2;
        } else {
            $resumo = $abst1;
            if (strlen($abst2) > 0) {
                $resumo .= '<BR><BR>' . $abst2;
            }
        }
        $sx .= $resumo;
        $sx .= '<BR>';
        $keys = trim($line['Idioma']);
        $key = trim($line['ar_keyword_1']);
        $key .= ' ' . trim($line['ar_keyword_2']);
        $key = trim(troca($key, ' /', ','));
        if ($keys == 'pt_BR') { $sx .= '<B>Palavras-chave</B>: ' . $key;
        } else { $sx .= '<BR><B>Keywords</B>: ' . $key;
        }

        /* Pontos */
        $sx .= '</p>';
        $sx .= '</div>';

        /* Para a marcacao de pontos */
        $titulo = trim(UpperCaseSQL($line['Article_Title']));
        $titulo .= trim(UpperCaseSQL($line['Title_2']));

        $resumo = trim(UpperCaseSQL($line['ar_resumo_1']));
        $resumo .= ' ' . trim(UpperCaseSQL($line['ar_resumo_2']));

        $keyword = trim(UpperCaseSQL($line['Keywords']));
        $keyword .= trim(UpperCaseSQL($line['ar_keyword_1']));
        $keyword .= trim(UpperCaseSQL($line['ar_keyword_2']));

        $termos = $dd[2] . ' ';
        $termos = troca($termos, ' ', ';');
        $termos = splitx(";", UpperCaseSql($termos));

        $sx .= $this -> metodo_pontos($titulo, $resumo, $keyword, $termos);
        $sx .= '</div>' . cr();
        $sx .= '</div>' . cr();
        $sx .= '<hr>';
        return ($sx);
    }

    /* Exportação de dados para o XLS */
    function export_xls($line) {
        global $id, $email, $dd;
        $sx = '';

        $id++;
        $cod = trim($line['ar_codigo']);

        $sx .= '<tr valign="top">';

        /* Authors */
        $sx .= '<td>';
        $sx .= (trim($line['Author_Analytic']));
        $sx .= '</td>';

        /* Title */
        $sx .= '<td>';
        $sx .= (trim($line['Article_Title']));
        $sx .= '</td>';

        /* Year */
        $sx .= '<td>';
        $sx .= trim($line['ar_ano']);
        $sx .= '</td>';

        /* Journal */
        $sx .= '<td>';
        $sx .= trim($line['Journal_Title']);
        $sx .= '</td>';

        /* ISSN */
        $sx .= '<td>';
        $sx .= '"' . trim($line['ISSN']) . '"';
        $sx .= '</td>';

        /* Volume numero */
        $sx .= '<td>';
        $sx .= trim(troca($line['Volume_ID'], 'v.', ''));
        $sx .= '</td>';

        $sx .= '<td>';
        $sx .= trim(troca($line['Issue_ID'], 'n.', ''));
        $sx .= '</td>';

        /* Pages */
        $sx .= '<td>';
        $p = $line['Pages'];
        $p = trim(troca($p, 'p.', ''));
        if (strpos($p, '-')) {
            $p1 = substr($p, 0, strpos($p, '-'));
            $p2 = substr($p, strpos($p, '-') + 1, 10);
        } else {
            $p1 = $p;
            $p2 = $p;
        }

        $sx .= '<td>' . $p1 . '</td>';
        $sx .= '<td>' . $p2 . '</td>';

        /* Page count */
        $sx .= '<td>' . ($p2 - $p1 + 1) . '</td>';

        /* Cited By */
        $sx .= '<td></td>';

        /* DOI */
        $sx .= '<td>' . $line['ar_doi'] . '</td>';

        /* LINK */
        $link = base_url('indexp.php/article/view/' . $line['ar_codigo']);
        $sx .= '<td>' . $link . '</td>';

        /* Publication Type */
        $sx .= '<td>';
        $sect = $line['ar_tipo'];
        $sx .= $sect;
        //$sx .= ' (' . $this -> tipo_publicacao($tipo).'-' .$sect. ')';
        $sx .= '</td>';

        /* Source */
        $tipo = $line['jnl_tipo'];
        switch ($tipo) {
            case 'J' :
                $tipo = 'Journal';
                break;
        }
        $sx .= '<td>' . $tipo . '</td>';

        /* EID */
        $sx .= '<td>' . $line['ar_codigo'] . '</td>';

        /* Abstract */
        $abst1 = trim($line['ar_resumo_1']);
        $sx .= '<td>';
        $sx .= $abst1;
        $sx .= '</td>';

        /* Key Words */
        $sx .= '<td>';
        $key = trim($line['ar_keyword_1']);
        $key = trim(troca($key, ' /', ';'));
        $sx .= $key;
        $sx .= '</td>';

        /* Language */
        $sx .= '<td>' . $line['Idioma'] . '</td>';

        $sx .= '</tr>';
        $sx .= cr();

        return ($sx);
    }

    function tipo_publicacao($tp) {
        $tipo = trim($tp);
        $sx = '';
        switch ($tipo) {
            case 'A' :
                $sx = 'Tese';
                break;
            case 'B' :
                $sx = 'Dissertacao';
                break;
            case 'J' :
                $sx = 'Revista';
                break;
            case 'L' :
                $sx = 'Livro';
                break;
            case 'T' :
                $sx = 'Tese de Doutorado';
                break;
            case 'U' :
                $sx = 'Dissertaçao de Mestrado';
                break;
            case 'D' :
                $sx = 'Livro diático';
                break;
            case 'E' :
                $sx = 'Anais de eventos';
                break;
            default :
                $sx .= $tipo;
                break;
        }
        return ($sx);
    }

    function tipo_secoes() {
        $sql = "select * from brapci_section ";
        $data = array();
        $rlt = db_query($sql);
        while ($line = db_read($rlt)) {
            $sec = trim($line['se_codigo']);
            $data[$sec] = trim($line['se_descricao']);
        }
        return ($data);
    }

    function result_journals() {
        global $db_base, $db_public;
        $wh = $this -> query;

        $sx = '';

        if (strlen($wh) == 0) { $wh = '(1 = 1)';
        }
        $sql = "select count(*) as total, jnl_nome_abrev from " . $db_public . "artigos 
					inner join " . $db_base . "brapci_journal on jnl_codigo = ar_journal_id
			where $wh ";

        $sql .= "group by jnl_nome_abrev order by total desc ";
        $rlt = db_query($sql);
        $sx .= '<table class="lt0 table" width="100%">';
        $sx .= '<TR><Th>' . msg("journal") . '<Th>' . msg('quant.');

        while ($line = db_read($rlt)) {
            $sx .= '<TR>';
            $sx .= '<td>';
            $sx .= trim($line['jnl_nome_abrev']);
            $sx .= '<td align="center">';
            $sx .= trim($line['total']);
        }
        $sx .= '</table>';
        return ($sx);
    }

    function result_year() {
        global $db_base, $db_public;
        $wh = $this -> query;

        $sx = '';

        if (strlen($wh) == 0) { $wh = '(1 = 1)';
        }
        $sql = "select count(*) as total, ar_ano from " . $db_public . "artigos 
				inner join brapci_journal on jnl_codigo = ar_journal_id
			where $wh ";

        $sql .= "group by ar_ano order by ar_ano desc, total desc ";

        $rlt = db_query($sql);
        $sx .= '<table class="lt0 table" width="100%">';
        $sx .= '<TR><Th>' . msg("year") . '<Th>' . msg('quant.');

        while ($line = db_read($rlt)) {
            $sx .= '<TR>';
            $sx .= '<td align="center">';
            $sx .= trim($line['ar_ano']);
            $sx .= '<td align="center">';
            $sx .= trim($line['total']);
        }
        $sx .= '</table>';
        return ($sx);
    }

    function result_author() {
        global $db_base, $db_public;
        $wh = $this -> query;

        $sx = '';

        if (strlen($wh) == 0) { $wh = '(1 = 1)';
        }
        $sql = "
			select autor_nome, autor_codigo, sum(total) as total from (
				select count(*) as total, ae_author from " . $db_public . "artigos 
				inner join " . $db_base . "brapci_article_author on ae_article = ar_codigo
				inner join brapci_journal on jnl_codigo = ar_journal_id 
				where $wh
				group by ae_author 
			) as tabela 
				inner join " . $db_base . "brapci_autor on ae_author = autor_codigo
				
				group by autor_nome, autor_codigo
				order by total desc, autor_nome
				limit 20
			";
        $rlt = db_query($sql);
        $sx .= '<table class="lt0 table" width="100%">';
        $sx .= '<TR><Th>' . msg("author") . '<Th>' . msg('quant.');

        while ($line = db_read($rlt)) {
            $sx .= '<TR>';
            $sx .= '<td align="left">';
            $sx .= trim($line['autor_nome']);
            $sx .= '<td align="center">';
            $sx .= trim($line['total']);
        }
        $sx .= '</table>';
        return ($sx);
    }

    function result_author_network() {
        global $db_base, $db_public;
        $wh = $this -> query;

        $sql = "select * from " . $db_public . "artigos where " . $wh;
        $rlt = db_query($sql);
        $sx = '';
        while ($line = db_read($rlt)) {
            $sx .= '<BR>';
            $sx .= trim($line['Author_Analytic']) . ';';
            $key = troca(trim($line['Keywords']), '/', ';');
            //$sx .= $key;
        }
        return ($sx);
    }

    function result_keyword_network() {
        global $db_base, $db_public;
        $wh = $this -> query;
        $wh = troca($wh, 'ar_codigo', 'kw_article');

        $sql = "select * from brapci_article_keyword
				inner join brapci_keyword on kw_keyword = kw_codigo and kw_idioma = 'pt_BR'
				where " . $wh . "
				order by kw_article
				";
        $rlt = db_query($sql);
        $sx = '';
        $xart = '';
        while ($line = db_read($rlt)) {
            $art = $line['kw_article'];
            if ($art != $xart) {
                $sx .= '<BR>';
                $xart = $art;
            }
            $sx .= trim($line['kw_word']) . ';';
            //$sx .= $key;
        }
        $sx .= '<HR>TOTAL:' . $this -> result_keyword_total();
        return ($sx);
    }

    function result_keyword_total() {
        global $db_base, $db_public;
        $wh = $this -> query;
        $wh = troca($wh, 'ar_codigo', 'kw_word');

        $sql = "select count(*) as total, kw_word from brapci_article_keyword
				inner join brapci_keyword on kw_keyword = kw_codigo and kw_idioma = 'pt_BR'
				where " . $wh . "
				group by kw_word
				";

        $rlt = db_query($sql);
        $sx = '';
        $xart = '';
        while ($line = db_read($rlt)) {
            $sx .= '<BR>';
            $sx .= trim($line['kw_word']) . ';';
            $sx .= trim($line['total']) . ';';
            //$sx .= $key;
        }
        return ($sx);
    }

    function result_articles() {
        $wh = $this -> query;

        $sql = "select * from " . BASE_PUBLIC . "artigos
						WHERE " . $wh . "
					ORDER BY ar_ano, Date_Publication ";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        $sx = '';
        for ($r = 0; $r < count($rlt); $r++) {
            $line = $rlt[$r];

            $link = '<a href="' . base_url('index.php/article/view/' . $line['ar_codigo']) . '" target="_new_' . $r . '">';
            $sx .= '<br>';
            $sx .= ($r + 1) . ') ';
            $sx .= $link;
            $sx .= $line['Author_Analytic'] . '. '; ;
            $sx .= $line['ar_titulo_1'] . '. ';
            $sx .= $line['Journal_Title'] . ', ';
            $sx .= $line['Volume_ID'];
            ', ';
            $sx .= $line['Issue_ID'] . ', '; ;
            $sx .= $line['Date_Publication'] . '. ';
            $sx .= $line['Pages'];
            $sx .= '</a>';
        }
        return ($sx);
    }

    function result_keyword() {
        global $db_base, $db_public;
        $wh = $this -> query;

        $sx = '';

        if (strlen($wh) == 0) { $wh = '(1 = 1)';
        }
        $sql = "
			select kw_word,kw_keyword, sum(total) as total from (
				select count(*) as total, kw_keyword from " . $db_public . "artigos 
				inner join " . $db_base . "brapci_article_keyword on ar_codigo = kw_article
				inner join brapci_journal on jnl_codigo = ar_journal_id
				where $wh
				group by kw_keyword 
			) as tabela 
				inner join " . $db_base . "brapci_keyword on kw_keyword = kw_codigo
				where kw_idioma = 'pt_BR'
				group by kw_word, kw_codigo
				order by total desc, kw_word
				limit 40
			";

        $rlt = db_query($sql);
        $sx .= '<table class="lt0 table" width="160">';
        $sx .= '<TR><Th>' . msg("keyword") . '<Th>' . msg('quant.');
        while ($line = db_read($rlt)) {
            $sx .= '<TR>';
            $sx .= '<td align="left">';
            $sx .= trim($line['kw_word']);
            $sx .= '<td align="center">';
            $sx .= trim($line['total']);
        }
        $sx .= '</table>';
        $this -> tags_cloud();
        return ($sx);
    }

    function tags_cloud() {
        global $db_base, $db_public;
        $wh = $this -> query;

        $sx = '';

        if (strlen($wh) == 0) { $wh = '(1 = 1)';
        }
        $sql = "
			select kw_word,kw_keyword, sum(total) as total from (
				select count(*) as total, kw_keyword from " . $db_public . "artigos 
				inner join " . $db_base . "brapci_article_keyword on ar_codigo = kw_article
				inner join brapci_journal on jnl_codigo = ar_journal_id
				where $wh
				group by kw_keyword 
			) as tabela 
				inner join " . $db_base . "brapci_keyword on kw_keyword = kw_codigo
				where kw_idioma = 'pt_BR'
				group by kw_word, kw_codigo
				order by kw_word
			";

        $rlt = db_query($sql);
        $id = 0;
        $ntag = '';
        $sz = 20;
        $ntag = '<BR><BR><BR>
					<div style="float: clear;">';
        while ($line = db_read($rlt)) {
            $size = round(log($line['total']) * 10);
            $ntag .= '<div style="float: left">';
            $ntag .= '&nbsp<font style="font-size: ' . $size . 'pt;">' . trim($line['kw_word']) . '</font> ';
            $ntag .= '</div>';
        }
        $ntag .= '</div>
						<BR><BR><BR><BR><BR>
						<div style="float: clear;"></div>';
        $this -> tag = $ntag;
        return ($sx);
    }

    function registra_consulta($tipo, $qanoi, $qanof, $txt) {
        global $db_public;
        $session = $_SESSION['bp_session'];

        $dt = array('anoi' => $qanoi, 'anof' => $qanof, 'term' => $txt, 'type' => $tipo);
        for ($r = 97; $r <= 102; $r++) {
            $dt['dd4' . chr($r)] = get("dd4" . chr($r));
        }
        $this -> session -> set_userdata($dt);

        $ip = ip();
        $sql = "insert into " . $db_public . "queries 
			(
				q_session, q_termo, 
				q_tipo, q_anoi, q_anof, q_ip
			) values (
				'$session','$txt',
				'$tipo','$qanoi','$qanof','$ip'
			)";
        $rlt = $this -> db -> query($sql);
    }

    function registra_visualizacao($id) {
        global $db_public;
        $session = $_SESSION['bp_session'];
        $ip = ip();
        $qanoi = 0;
        $qanof = 0;
        $tipo = 100;
        $txt = '';
        $sql = "insert into " . $db_public . "queries 
			(
				q_session, q_termo, q_view,
				q_tipo, q_anoi, q_anof, q_ip
			) values (
				'$session','$txt','$id',
				'$tipo','$qanoi','$qanof','$ip'
			)";
        $rlt = $this -> db -> query($sql);
    }

    function registra_download($id, $tipo = '101') {
        global $db_public;
        $session = $_SESSION['bp_session'];
        $ip = ip();
        $qanoi = 0;
        $qanof = 0;
        $txt = '';
        $sql = "insert into " . $db_public . "queries 
			(
				q_session, q_termo, q_view,
				q_tipo, q_anoi, q_anof, q_ip
			) values (
				'$session','$txt','$id',
				'$tipo','$qanoi','$qanof','$ip'
			)";
        $rlt = $this -> db -> query($sql);
    }

    function busca_form($data = array()) {
        global $dd, $SESSION;

        $SESSION = array();
        $SESSION['ssid'] = '';
        $SESSION['srcid'] = '1';
        for ($r = 0; $r < 100; $r++) {
            $SESSION['srcid' . $r] = '1';
        }

        if (isset($data['anoi'])) {
            $data1 = $data['anoi'];
            $data2 = $data['anof'];
        } else {
            $data1 = 1970;
            $data2 = (date("Y") + 1);
        }
        $sx = '';

        /* registra consulta */
        /*****************************************************************************************/
        $sa = '';
        if (strlen($data['dd4']) > 0) {
            //$this -> registra_consulta($dd[2]);
            $sr = $this -> result_search($data, $data1, $data2);
            if (strlen($this -> query) > 0) {

                $sa = $this -> result_journals();
                $sa .= $this -> result_year();
                $sa .= $this -> result_author();
                $sa .= $this -> result_keyword();
            }
            $sx .= $this -> realce($sr, $data['dd4']);
        }
        $data['tela1'] = $sx;
        $data['tela2'] = $sa;
        return ($data);
    }

    function busca_form_keyword($data = array()) {
        global $dd, $SESSION;

        $SESSION = array();
        $SESSION['ssid'] = '';
        $SESSION['srcid'] = '1';
        for ($r = 0; $r < 100; $r++) {
            $SESSION['srcid' . $r] = '1';
        }

        if (isset($data['anoi'])) {
            $data1 = $data['anoi'];
            $data2 = $data['anof'];
        } else {
            $data1 = 1970;
            $data2 = (date("Y") + 1);
        }
        $sx = '';
        /* registra consulta */

        if (strlen($dd[1])) {
            //$this -> registra_consulta($dd[2]);
        }
        $sa = '';
        if (strlen($data['dd4']) > 0) {
            $sr = $this -> result_search_keyword($data, $data1, $data2);
            if (strlen($this -> query) > 0) {

                $sa = $this -> result_journals($data);
                $sa .= $this -> result_year();
                $sa .= $this -> result_author();
                $sa .= $this -> result_keyword();
            }
            $sx .= $this -> realce($sr, $data['dd4']);
        }
        $data['tela1'] = $sx;
        $data['tela2'] = $sa;
        return ($data);
    }

    function busca_form_cited($data = array()) {
        global $dd, $SESSION;

        $SESSION = array();
        $SESSION['ssid'] = '';
        $SESSION['srcid'] = '1';
        for ($r = 0; $r < 100; $r++) {
            $SESSION['srcid' . $r] = '1';
        }

        if (isset($data['anoi'])) {
            $data1 = $data['anoi'];
            $data2 = $data['anof'];
        } else {
            $data1 = 1970;
            $data2 = (date("Y") + 1);
        }
        $sx = '';
        /* registra consulta */

        if (strlen($dd[1])) {
            //$this -> registra_consulta($dd[2]);
        }
        $sa = '';
        $sb = '';
        if (strlen($data['dd4']) > 0) {
            $sr = $this -> result_search_cited($data, $data1, $data2);
            if (strlen($this -> query) > 0) {

                $sa = $this -> result_journals($data);
                $sa .= $this -> result_year();
                $sa .= $this -> result_author();
                $sa .= $this -> result_keyword();

                $sb = $this -> result_articles();
            }
            $sx .= $this -> realce($sr, $data['dd4']);
        }
        $data['tela1'] = $sx;
        $data['tela2'] = $sa;
        $data['tela3'] = $sb;
        return ($data);
    }

    function busca_form_autor($data = array()) {
        global $dd, $SESSION;

        $SESSION = array();
        $SESSION['ssid'] = '';
        $SESSION['srcid'] = '1';
        for ($r = 0; $r < 100; $r++) {
            $SESSION['srcid' . $r] = '1';
        }

        if (isset($data['anoi'])) {
            $data1 = $data['anoi'];
            $data2 = $data['anof'];
        } else {
            $data1 = 1970;
            $data2 = (date("Y") + 1);
        }
        $sx = '';
        /* registra consulta */

        if (strlen($dd[1])) {
            //$this -> registra_consulta($dd[2]);
        }
        $sa = '';
        if (strlen($data['dd4']) > 0) {
            $sr = $this -> result_search_autor($data, $data1, $data2);
            if (strlen($this -> query) > 0) {

                $sa = $this -> result_journals();
                $sa .= $this -> result_year();
                $sa .= $this -> result_author();
                $sa .= $this -> result_keyword();
            }
            $sx .= $this -> realce($sr, $data['dd4']);
        }
        $data['tela1'] = $sx;
        $data['tela2'] = $sa;
        return ($data);
    }

    function delimitacao_por_journals() {
        global $SESSION;
        $sx = '';
        $sql = "select * from brapci_journal_tipo where jtp_ativo = 1
							order by jtp_ordem ";
        $rlt = db_query($sql);

        while ($line = db_read($rlt)) {
            $fld = 'srcid' . trim($line['id_jtp']);
            if (!isset($SESSION[$fld])) { $SESSION[$fld] = '';
            }
            $vl = $SESSION[$fld];
            $check = '';
            if ((strlen($vl) == 0) or ($vl == '1')) { $check = 'checked';
            }
            $jscmd = 'onclick="mark_type(this,\'id' . trim($line['id_jtp']) . '\');" ';
            $sx .= '<input id="tp' . trim($line['id_jtp']) . '" type="checkbox" ' . $jscmd . ' name="dd13" value="1" ' . $check . '>
							&nbsp;' . $line['jtp_descricao'] . '<BR>';
        }
        $sx .= '
					<script>
					function mark_type(ta,tp)
					{
						var ok = ta.checked;
						var ms = tp;
						$.ajax({
  							type: "POST",
  							url: "article_type_mark.php",
  							data: { dd1: ms, dd2: ok }
						})
						.done(function( data ) {
							$("#delimit_type").html(data);
						})
						.erro(function( data ) {
							alert("erro "+data);
						});						
					}
					</script>
				';
        return ($sx);

    }

    function delimitacao_por_data() {
        /* Delimitacao por data */
        global $dd, $SESSION;
        $sx = '';
        $dtf = '';
        for ($r = 1900; $r <= date("Y"); $r++) {
            $chk = '';
            if ($dd[10] == $r) { $chk = 'selected';
            }
            $dtf .= '<option value="' . $r . '" ' . $chk . ' >' . $r . '</option>';
        }
        $dti = '<select name="dd10" id="typeid10">' . $dtf . '</select>';

        $dtf = '';
        for ($r = date("Y"); $r >= 1972; $r--) {
            $chk = '';
            if ($dd[11] == $r) { $chk = 'selected';
            }
            $dtf .= '<option value="' . $r . '" ' . $chk . '>' . $r . '</option>';
        }

        $dtf = '<select name="dd11" id="typeid11">' . $dtf . '</select>';
        $sx .= msg('publicacao_de') . ' ' . $dti . ' ' . msg('ate') . ' ' . $dtf;
        return ('<NOBR>' . $sx . '</nobr>');
    }

    /* realca texto */
    function realce($txt, $term) {
        $sx = '';
        /* trato os termos para realce */
        $term = $this -> trata_termo_composto($term);
        $term = troca($term, ' ', ';');
        $term = troca($term, chr(13), ';');
        $term = troca($term, chr(10), '');
        $term = troca($term, '"', '');
        $term = splitx(';', $term);

        /* Forma de marcaçao do texto */
        $mark_on = '<font style="background-color : Yellow;"><B>';
        $mark_off = '</B></font>';

        //$mark_on = '[x]';
        //$mark_off = '[y]';

        /* inicio da marcao */

        /* convert texto em texto ASC */

        for ($rx = 0; $rx < count($term); $rx++) {
            $termx = $term[$rx];
            $termx = troca($termx, '_', ' ');
            $words = array();
            $words_mark = array();

            if (strlen($termx) > 2) {
                array_push($words, $termx);
                array_push($words, UpperCase($termx));
                array_push($words, UpperCaseSql($termx));
                for ($r = 0; $r < count($words); $r++) {
                    array_push($words_mark, $mark_on . $words[$r] . $mark_off);
                }
                $txt = highlight($txt, $words);
            }
        }

        return ($txt);
    }

    function result_search($post = array(), $data1, $data2) {
        global $dd, $acao;
        $sx = '';
        $sx .= msg('form_query') . ' <b>' . $post['dd4'] . '</b>';
        /* realiza busca */
        $data1 = $post['anoi'];
        $data2 = $post['anof'];

        $sx .= $this -> result_article($post['dd4'], $data1, $data2, $post);

        return ($sx);
    }

    function result_search_title($key_cod = '') {
        $sx = '';
        $sx .= $this -> lang -> line('form_found') . ' <B> ' . $key_cod['dd4'] . '</B>';
        $sx .= $this -> result_article_titles($key_cod);
        return ($sx);
    }

    function result_search_abstract($key_cod = '') {
        $sx = '';
        $sx .= $this -> lang -> line('form_found') . ' <B> ' . $key_cod['dd4'] . '</B>';
        $sx .= $this -> result_article_abstracts($key_cod);
        return ($sx);
    }

    function result_search_keyword($key_cod = '') {
        $sx = '';
        $sx .= $this -> lang -> line('form_found') . ' <B> ' . $key_cod['dd4'] . '</B>';
        $sx .= $this -> result_article_key($key_cod);
        return ($sx);
    }

    function result_search_cited($key_cod = '') {
        $sx = '';
        $sx .= $this -> lang -> line('form_found') . ' <B> ' . $key_cod['dd4'] . '</B>';
        $sx .= $this -> result_article_cited($key_cod);
        return ($sx);
    }

    function result_search_autor($key_cod = '') {
        $sx = '';
        $sx .= $this -> lang -> line('form_found') . ' <B> ' . $key_cod['dd4'] . '</B>';
        $sx .= $this -> result_autor_key($key_cod);
        return ($sx);
    }

    function result_search_author($key_cod = '') {
        global $dd, $acao;
        $sx = '';
        $sx .= '<table border=1 class="lt1 table" width="100%">';
        $sx .= '<TR valign="top">';
        $sx .= '<td>';
        $sx .= msg('find') . '<B> ' . $dd[2] . '</B>';
        $sx .= $this -> result_article_auth($key_cod);
        $sx .= '<td width="120">';
        if (strlen($this -> query) > 0) {

            $sa = $this -> result_journals();
            $sa .= $this -> result_year();
            $sa .= $this -> result_author();
            $sa .= $this -> result_keyword();
            $sx .= $sa;
        }
        $sx .= '</table>';
        return ($sx);
    }

    function result_search_selected() {
        global $dd, $acao;
        $sx = '<table border=0 class="table" width="100%">';
        $sx .= '<TR valign="top">';
        $sx .= '<td>';
        //$sx .= msg('find') . '<B> ' . $dd[2] . '</B>';
        $sx .= $this -> result_article_selected($this -> session());

        $sx .= '<td width="120">';
        $sa = $this -> result_journals();
        $sa .= $this -> result_year();
        $sa .= $this -> result_author();
        $sa .= $this -> result_keyword();
        $sx .= $sa;
        $sx .= '</table>';

        $sx .= $this -> result_author_network();
        $sx .= '<HR>';
        $sx .= $this -> result_keyword_network();
        $sx .= '<HR>';
        $sx .= $this -> result_cited_selected($this -> session());
        return ($sx);
    }

    function result_search_selected_xls() {
        $sx = $this -> result_article_selected_xls($this -> session());
        return ($sx);
    }

    function busca_author($data, $pag = 1) {
        $term = $this -> tratar_term($data['dd1']);
        $term = troca($term, ' ', ';');
        $term = splitx(';', $term);
        $wh = '';
        for ($r = 0; $r < count($term); $r++) {
            $t = $term[$r];
            $fld = 'autor.autor_nome';
            if (strlen($wh) > 0) { $wh .= ' AND ';
            }
            $wh .= "($fld LIKE '%$t%') ";
        }
        $sql = "SELECT * 
					FROM brapci_autor AS autor
					WHERE $wh 
					AND autor_codigo = autor_alias
					ORDER BY $fld ";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();

        $sx = '<table width="100%" class="tabela00 lt2">';
        for ($r = 0; $r < count($rlt); $r++) {
            $line = $rlt[$r];
            $sx .= '<tr>';
            $sx .= '<td>';
            $sx .= $line['autor_nome'];

            /* USE */
            $sx .= '</td>';

            $sx .= '</tr>';
        }
        $sx .= '</table>';
        return ($sx);

    }

    function paginacao($p = 1, $t = 0) {
        $tot = 5;
        $ini = $p - 2;
        if ($ini < 1) { $ini = 1;
        }
        $max = 5;
        $tp = $this -> limit;
        $tot = (int)($t / $tp) + 1;
        $sx = '' . cr();

        $sx .= '<div class="row">';

        /* DIV #6 */
        $sx .= '<div class="col-md-6">';
        $sx .= '<ul class="pagination">' . cr();

        /**************************/
        if ($ini > 1) {
            $sx .= '<li><a href="' . base_url('index.php/home/pag/' . ($ini - 1)) . '"> << ' . msg('prev') . '</a></li>' . cr();
        }
        for ($r = $ini; $r <= ($ini + $max); $r++) {
            if ($r <= $tot) {
                $cl = '';
                if ($p == $r) {
                    $cl = 'active';
                }
                $sx .= '<li class="' . $cl . '"><a href="' . base_url('index.php/home/pag/' . $r) . '">' . $r . '</a></li>' . cr();
            }
        }
        if (($ini + $max) < $tot) {
            $sx .= '<li><a href="' . base_url('index.php/home/pag/' . ($r)) . '">' . msg('next') . ' >></a></li>' . cr();
        }
        $sx .= '</ul>';
        $sx .= '</div>';

        /* DIV #3 */
        $sx .= '<div class="col-md-3 center-block" style="margin-top: 30px;">';
        $sx .= '<input type="checkbox" class="form_control">';
        $sx .= ' ';
        $sx .= msg('select_all');
        $sx .= '</div>';

        /* DIV #3 */
        $sx .= '<div class="col-md-3" style="margin-top: 30px;">';
        $sx .= msg('showing') . ' ';
        $ff = (($p) * $this -> limit - 1);
        if ($ff > $t) { $ff = $t;
        }
        $sx .= (($p - 1) * $this -> limit + 1) . '-' . ($ff) . ' de ' . $t;
        $sx .= '</div>';
        $sx .= '</div>';
        return ($sx);
    }

}
?>
