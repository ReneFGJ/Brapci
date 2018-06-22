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
    var $token = '';

    /**************** server *************/
    function header_xml($id = '') {
        $this -> load -> model('journals');
        $verb = get("verb");
        if (strlen($id) > 0) {
            $line = $this -> journals -> le($id);
        } else {
            $verb = '';
        }

        header("Content-type: text/xml");
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . cr();
        /* $xml .= '<?xml-stylesheet type="text/xsl" href="http://seer.ufrgs.br/lib/pkp/xml/oai2.xsl" ?> '.cr(); */
        $xml .= '<OAI-PMH xmlns="http://www.openarchives.org/OAI/2.0/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.openarchives.org/OAI/2.0/ http://www.openarchives.org/OAI/2.0/OAI-PMH.xsd">' . cr();
        $xml .= '<responseDate>' . date("Y-m-d") . 'T' . date("H:i:s") . 'Z</responseDate>' . cr();
        $content = '';
        switch($verb) {
            case 'GetRecord' :
                $meta = get("metadataPrefix");
                switch($meta) {
                    case 'nlm' :
                        /*************************************************************************/
                        $this -> load -> model('articles');
                        $idf = sonumero(get("identifier"));
                        $data = $this -> articles -> le($idf);

                        $content .= '
                                    <GetRecord>
                                        <record>
                                            <header>
                                                <identifier>oai:brapci.inf.br:article/' . round($idf) . '</identifier>
                                                <datestamp>' . date("Y-m-d") . 'T00:00:00Z</datestamp>
                                                <setSpec>SEC_Brapci:' . $data['ar_section'] . '</setSpec>
                                            </header>                                        
                                <metadata>
                                <article
                                    xmlns="http://dtd.nlm.nih.gov/publishing/2.3"
                                    xmlns:xlink="http://www.w3.org/1999/xlink"
                                    xmlns:mml="http://www.w3.org/1998/Math/MathML"
                                    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                                    xsi:schemaLocation="http://dtd.nlm.nih.gov/publishing/2.3
                                    http://dtd.nlm.nih.gov/publishing/2.3/xsd/journalpublishing.xsd"
                                    article-type="Avaliado por Pares"   xml:lang="PT">
                                    <front>
                                        <journal-meta>
                                            <journal-id journal-id-type="other">' . $data['jnl_nome'] . '</journal-id>
                                            <journal-title>' . $data['jnl_nome'] . '</journal-title>
                                            <trans-title xml:lang="PT">' . $data['jnl_nome'] . '</trans-title>
                                            <trans-title xml:lang="EN">' . $data['jnl_nome'] . '</trans-title>
                                            <issn pub-type="epub">' . $data['jnl_issn_impresso'] . '</issn>
                                            <issn pub-type="ppub">' . $data['jnl_issn_eletronico'] . '</issn>          
                                            <publisher><publisher-name>Brapci.inf.br</publisher-name></publisher>
                                        </journal-meta>
                                        <article-meta>
                                            <article-id pub-id-type="other">' . $idf . '</article-id>
                                            <article-id pub-id-type="doi">' . $data['ar_doi'] . '</article-id>
                                            <article-categories>
                                                <subj-group subj-group-type="heading">
                                                    <subject>' . $data['se_descricao'] . '</subject>
                                                </subj-group>
                                            </article-categories>
                                            <title-group>
                                                <article-title>' . $data['ar_titulo_1'] . '</article-title>
                                                <trans-title xml:lang="' . $data['ar_idioma_1'] . '">' . $data['ar_titulo_1'] . '</trans-title>
                                                <trans-title xml:lang="' . $data['ar_idioma_2'] . '">' . $data['ar_titulo_2'] . '</trans-title>
                                            </title-group>
                                            <contrib-group>';
                        for ($r = 0; $r < count($data['authors']); $r++) {
                            $nm = utf8_encode($data['authors'][$r]['autor_nome']);

                            $content .= '
                                                <contrib corresp="yes" contrib-type="author">
                                                    <name name-style="western">
                                                        <surname>' . utf8_decode(trim(substr($nm, strpos($nm, ',') + 1, strlen($nm)))) . '</surname>
                                                        <given-names>' . trim(substr($nm, 0, strpos($nm, ','))) . '</given-names>
                                                        <utf8>'.mb_detect_encoding($nm).'</utf8>
                                                    </name>
                                                    <aff>' . utf8_decode($data['authors'][$r]['ae_bio']) . '</aff>
                                                    <email></email>
                                                </contrib>
                                        
                                        ';
                        }
                        if (strlen($data['ed_data_publicacao']) == 0) {
                            $data['ed_data_publicacao'] = $data['ed_ano'] . '0101';
                        }
                        $content .= '</contrib-group>
                                            <pub-date pub-type="epub">
                                                <day>' . substr($data['ed_data_publicacao'], 6, 2) . '</day>
                                                <month>' . substr($data['ed_data_publicacao'], 4, 2) . '</month>
                                                <year>' . substr($data['ed_ano'], 0, 4) . '</year>
                                            </pub-date>
                                            <issue-id pub-id-type="other">' . $data['id_ed'] . '</issue-id>
                                            <issue-title>v. ' . $data['ed_vol'] . ', n. ' . $data['ed_nr'] . ', ' . $data['ed_ano'] . '</issue-title>
                                            <permissions>
                                                <copyright-statement>Direitos autorais ao seus editores</copyright-statement>
                                                <copyright-year>' . date("Y") . '</copyright-year>
                                                <license xlink:href="">
                                                </license>
                                            </permissions>
                                            <self-uri xlink:href="http://www.brapci.inf.br/index.php/article/view/' . strzero($idf, 10) . '" />
                                            <self-uri content-type="application/pdf" xlink:href="' . $data['link_pdf'] . '" />
                                            <abstract xml:lang="' . $data['ar_idioma_1'] . '"><p>Este artigo tece um breve histórico do MERCOSUL e do MERCOSUL Cultural, órgão responsável pela promoção e divulgação dos valores e tradições culturais dos países integrantes do bloco. O estudo propôs-se a investigar as ações do MERCOSUL Cultural com vistas à preservação do patrimônio documental bibliográfico. Seguindo uma abordagem qualitativa, fez uso das revisões bibliográfica e documental para a construção teórica. Os resultados da pesquisa demonstram que a cultura, de uma maneira geral, não tem ocupado posição de destaque nas ações e iniciativas do MERCOSUL. A presença dessa lacuna evidencia a necessidade de estímulo a estudos e pesquisas referentes à busca pela integração entre os países, dando a devida consideração às questões de ordem cultural, trazendo à tona a importância dos estudos voltados à preservação e à valorização do patrimônio cultural das nações.</p></abstract>
                                                <abstract-trans xml:lang="' . $data['ar_idioma_1'] . '"><p>' . $data['ar_resumo_1'] . '</p></abstract-trans>
                                                <abstract-trans xml:lang="' . $data['ar_idioma_2'] . '"><p>' . $data['ar_resumo_2'] . '</p></abstract-trans>
                                            
                                            ';
                        /************************************************ KEYWORDS *********************/
                        for ($r = 0; $r < count($data['keywords']); $r++) {
                            $content .= '<kwd-group xml:lang="' . $data['keywords'][$r]['kw_idioma'] . '">' . cr();
                            $content .= '<kwd>' . $data['keywords'][$r]['kw_word'] . '</kwd>' . cr();
                            $content .= '</kwd-group>' . cr();
                        }
                        $content .= '
                                </article-meta>
                                </front>
                                </article>          
                                </metadata>
                                </record>
                                </GetRecord>';
                        break;
                    case 'oai_dc' :
                        /*************************************************************************/
                        $this -> load -> model('articles');
                        $idf = sonumero(get("identifier"));
                        $data = $this -> articles -> le($idf);

                        $content .= '
                            <GetRecord>
                                <record>
                                    <header>
                                        <identifier>oai:brapci.inf.br:article/' . round($idf) . '</identifier>
                                        <datestamp>' . substr($data['ed_data_publicacao'], 0, 4) . '-' . substr($data['ed_data_publicacao'], 4, 2) . '/' . substr($data['ed_data_publicacao'], 6, 2) . 'T00:00:00Z</datestamp>
                                        <setSpec>EmQuestao:ART</setSpec>
                                    </header>
                                    <metadata>                
                                    <oai_dc:dc
                                        xmlns:oai_dc="http://www.openarchives.org/OAI/2.0/oai_dc/"
                                        xmlns:dc="http://purl.org/dc/elements/1.1/"
                                        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                                        xsi:schemaLocation="http://www.openarchives.org/OAI/2.0/oai_dc/
                                        http://www.openarchives.org/OAI/2.0/oai_dc.xsd">';
                                /************************************************ TITULO **********************/
                                for ($r = 1; $r <= 3; $r++) {
                                    $tit = (trim($data['ar_titulo_' . $r]));
                                    if (strlen($tit) > 0) {
                                        $content .= '<dc:title xml:lang="' . $data['ar_idioma_' . $r] . '">' . $data['ar_titulo_' . $r] . '</dc:title>' . cr();
                                    }
                                }
                                /************************************************ AUTORES *********************/
                                for ($r = 0; $r < count($data['authors']); $r++) {
                                    $content .= '<dc:creator>' . $data['authors'][$r]['autor_nome'] . ';' . utf8_decode($data['authors'][$r]['ae_bio']) . '</dc:creator>' . cr();
                                }
                                /************************************************ KEYWORDS *********************/
                                for ($r = 0; $r < count($data['keywords']); $r++) {
                                    $content .= '<dc:subject xml:lang="' . $data['keywords'][$r]['kw_idioma'] . '">' . $data['keywords'][$r]['kw_word'] . '</dc:subject>' . cr();
                                }
                                /************************************************ TITULO **********************/
                                for ($r = 1; $r <= 3; $r++) {
                                    $tit = (strlen(trim($data['ar_resumo_' . $r])));
                                    if (strlen($tit) > 0) {
                                        $content .= '<dc:description xml:lang="' . $data['ar_idioma_1'] . '">' . $data['ar_resumo_' . $r] . '</dc:description>' . cr();
                                    }
                                }
                                /************************************************ JOURNAL *********************/
                                $content .= '<dc:publisher xml:lang="pt-BR">Brapci</dc:publisher>';
                                $content .= '<dc:date>' . substr($data['ed_data_publicacao'], 0, 4) . '-' . substr($data['ed_data_publicacao'], 4, 2) . '/' . substr($data['ed_data_publicacao'], 6, 2) . '</dc:date>';
                                $ed = 'v.' . $data['ed_vol'];
                                $ed .= ', n.' . $data['ed_nr'];
                                $ed .= ', ' . $data['ed_ano'];
                                $ed .= '; ' . $data['ar_pg_inicial'] . '-' . $data['ar_pg_final'];
                                /* v. 1, n. 1 (2003); 69-78 */
                                $content .= '<dc:source xml:lang="pt-BR">' . $data['jnl_nome'] . ';' . $ed . '</dc:source>' . cr();
                                $content .= '<dc:type>info:eu-repo/semantics/' . $data['ar_section'] . '</dc:type>' . cr();
                                $content .= '<dc:type>info:eu-repo/semantics/publishedVersion</dc:type>' . cr();
                                $content .= '<dc:format>application/pdf</dc:format>' . cr();
                                $content .= '<dc:type xml:lang="pt-BR">Avaliado por Pares</dc:type>' . cr();
        
                                $content .= '<dc:identifier>http://www.brapci.inf.br/index.php/article/view/' . $data['ar_codigo'] . '</dc:identifier>';
                                if (substr($data['ar_doi'], 0, 2) == '10') {
                                    $content .= '<dc:identifier>' . trim($data['ar_doi']) . '</dc:identifier>';
                                }
        
                                if (strlen(trim($data['link_pdf'])) > 0) {
                                    $content .= '<dc:relation>' . trim($data['link_pdf']) . '</dc:relation>' . cr();
                                }
                                /************************************************ TITULO **********************/
                                for ($r = 0; $r < count($data['links']); $r++) {
                                    $content .= '<dc:relation>' . trim($data['links'][$r]['bs_type']) . ':';
                                    $content .= trim($data['links'][$r]['bs_adress']);
                                    $content .= '</dc:relation>' . cr();
                                }
                                $content .= '
                                    </oai_dc:dc>
                                </metadata>
                            </record>
                        </GetRecord>                            
                        ';
                        break;
                }
                break;
            case 'ListIdentifiers' :
                $content .= '<request verb="ListIdentifiers">http://wwww.brapci.inf.br/index.php/oai/oai/' . $id . '</request>';
                $content .= '<ListIdentifiers>';

                $sql = "select * from brapci_article where ar_journal_id = '" . strzero($id, 7) . "' and ar_status <> 'X' order by id_ar";
                $rlt = $this -> db -> query($sql);
                $rlt = $rlt -> result_array();
                for ($r = 0; $r < count($rlt); $r++) {
                    $line = $rlt[$r];
                    $dt = substr($line['ar_disponibilidade'], 0, 4) . '-' . substr($line['ar_disponibilidade'], 4, 2) . '-' . substr($line['ar_disponibilidade'], 6, 2);
                    $content .= '<header>';
                    $content .= '<identifier>oai:brapci.inf.br:article/' . $line['id_ar'] . '</identifier>';
                    $content .= '<datestamp>' . $dt . 'T' . date("H:i:s") . 'Z</datestamp>';
                    $content .= '<setSpec>testebse:ART</setSpec>';
                    $content .= '</header>';
                }
                $content .= '</ListIdentifiers>';

                break;
            case 'Identify' :
                $content .= '<request verb="Identify">http://wwww.brapci.inf.br/index.php/oai/oai/' . $id . '</request>
					<Identify>
						<repositoryName>' . utf8_encode($line['jnl_nome']) . '</repositoryName>
						<baseURL>' . base_url('index.php/oai/oai/' . $id) . '</baseURL>
						<protocolVersion>2.0</protocolVersion>
						<adminEmail>renefgj@gmail.com</adminEmail>
						<earliestDatestamp>1972-01-01T00:00:00Z</earliestDatestamp>
						<deletedRecord>persistent</deletedRecord>
						<granularity>YYYY-MM-DDThh:mm:ssZ</granularity>
						<compression>gzip</compression>
						<compression>deflate</compression>
						<description>
							<oai-identifier
								xmlns="http://www.openarchives.org/OAI/2.0/oai-identifier"
								xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
								xsi:schemaLocation="http://www.openarchives.org/OAI/2.0/oai-identifier
									http://www.openarchives.org/OAI/2.0/oai-identifier.xsd">
								<scheme>oai</scheme>
								<repositoryIdentifier>brapci.inf.br</repositoryIdentifier>
								<delimiter>:</delimiter>
								<sampleIdentifier>oai:brapci.inf.br:article/1</sampleIdentifier>
							</oai-identifier>
						</description>
						<description>
							<toolkit
								xmlns="http://oai.dlib.vt.edu/OAI/metadata/toolkit"
								xsi:schemaLocation="http://oai.dlib.vt.edu/OAI/metadata/toolkit
									http://oai.dlib.vt.edu/OAI/metadata/toolkit.xsd">
								<title>Brapci</title>
								<author>
									<name>Brapci</name>
									<email>renefgj@gmail.com</email>
								</author>
								<version>2.4.8.0</version>
								<URL>' . utf8_encode($line['jnl_url']) . '</URL>
							</toolkit>
						</description>
					</Identify>';
                break;
            case 'ListMetadataFormats' :
                $content .= '<request verb="ListMetadataFormats">' . base_url('index.php/oai/oai/' . $id) . '</request>' . cr();
                $content .= '<ListMetadataFormats>' . cr();
                $content .= '<metadataFormat>' . cr();
                $content .= '<metadataPrefix>oai_dc</metadataPrefix>' . cr();
                $content .= '<schema>http://www.openarchives.org/OAI/2.0/oai_dc.xsd</schema>' . cr();
                $content .= '<metadataNamespace>http://www.openarchives.org/OAI/2.0/oai_dc/</metadataNamespace>';
                $content .= '</metadataFormat>' . cr();
                $content .= '</ListMetadataFormats>';

                break;
            default :
                $xml .= '<request>' . base_url('index.php/oai') . '</request>' . cr();
                $content = '<error code="badVerb">Illegal OAI verb - ' . $verb . '</error>' . cr();
                break;
        }
        $xml .= $content;
        $xml .= '</OAI-PMH>' . cr();
        echo $xml;
    }

    function le_oaiid($oai_id) {
        $sql = "select * from oai_cache where cache_oai_id = '$oai_id'";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        if (count($rlt) > 0) {
            $rlt = $rlt[0];
        }
        return ($rlt);
    }

    function rescan_xml($id, $art) {
        $art = strzero($art, 10);
        $idx = strzero($id, 7);
        $file = 'ma/oai/' . $idx . '.xml';
        $txt = load_file_local($file);
        $sx = '<dc:identifier>';
        $txt = substr($txt, strpos($txt, $sx) + strlen($sx), strlen($txt));
        $txt = trim(substr($txt, 0, strpos($txt, '<')));

        if (substr($txt, 0, 4) == 'http') {
            $data['content'] = '==>' . $txt . '<==';
            $sql = "select * from brapci_article_suporte where bs_adress = '$txt' and bs_article = '$idx' ";
            $rlt = $this -> db -> query($sql);
            $rlt = $rlt -> result_array();
            if (count($rlt) == 0) {
                $this -> load -> view('content', $data);
                $sql = "insert brapci_article_suporte 
						(
						bs_status, bs_article, bs_type, 
						bs_adress, bs_journal_id, bs_update
						) values (
						'@','$art','URL',
						'$txt',''," . date("Ymd") . ')';
                $this -> db -> query($sql);
            } else {
            }
        } else {
        }
        redirect(base_url('index.php/article/view/' . $art));
    }

    function repository_list() {
        $sql = "select * from brapci_journal 
						where jnl_status <> 'X'
						AND jnl_url_oai <> ''	
						order by jnl_nome
						";
        $rlt = db_query($sql);
        $sx = '<h1>' . msg('oai_journals') . '</h1>';
        while ($line = db_read($rlt)) {
            $last = $line['jnl_last_harvesting'];
            $url = $line['jnl_url'];
            $link = '<A HREF="' . trim($line['jnl_url']) . '" target="_new">';
            $link_oai = base_url('index.php/oai/Identify/' . $line['id_jnl']);

            $sx .= '<a href="' . $link_oai . '" class="link lt2">';
            $sx .= '<div style="float: left; width: 300px; height: 100px; border: 1px solid #888; margin: 0px 10px 5px 0px; border-radius: 10px; padding: 5px 10px;">';
            $sx .= '<img src="' . base_url('img/icone_oai.png') . '" height="32" border=0 title="Coleta OAI-PMH" align="right">';
            $sx .= $line['jnl_nome'];
            $sx .= $line['jnl_token'];
            $sx .= '<br><br><font class="lt1">' . msg('last_update') . ': ' . stodbr($line['jnl_last_harvesting']) . '</font>';

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
            return ('<span class="label label-warning">Already!</span>');
            /* já existe */
        } else {
            $data = date("Ymd");
            $sql = "update brapci_journal set jnl_last_harvesting = '$data', jnl_update = '$data' where id_jnl = $jid ";
            $rlt = $this -> db -> query($sql);

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
            return ('<span class="label label-success">Insert!</span>');
        }
    }

    function ListIdentifiers_Method_1($url) {
        $rs = load_page($url);

        $xml_rs = $rs['content'];
        $xml = simplexml_load_string($xml_rs);

        $token = $xml -> ListIdentifiers -> resumptionToken;
        $this -> token = $token;

        $xml = $xml -> ListIdentifiers -> header;
        $sx = '<ul>';
        $status = 'ok';
        for ($r = 0; $r < count($xml); $r++) {
            foreach ($xml[$r]->attributes() as $a => $b) {
                if ($a == 'status') {
                    //$status = $b;
                }
            }
            $ida = $xml[$r] -> identifier;
            $date = $xml[$r] -> datestamp;
            $setSpec = $xml[$r] -> setSpec;

            if ($status == 'deleted') {
                $rt = '<span class="label label-important">deleted</span>';
                $sx .= '<li>' . $ida . ' - ' . $status . '</li>';

            } else {
                $rt = $this -> oai_listset($ida, $setSpec, $date);
                $sx .= '<li>' . $ida . ' - ' . $rt . '</li>';
            }
        }
        $sx .= '</ul>';

        return ($sx);

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
                    echo '<meta http-equiv="refresh" content="1">';
                    return ('');
                }
                /*********************** registro deleted *******************/
                if ($article['status'] == 'reload') {
                    $this -> altera_status_chache($idc, '@');
                    echo '<meta http-equiv="refresh" content="1">';
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
						and 
						ar_journal_id = '" . strzero($jid, 7) . "'
				";
                $article['section'] = '';
                $rlt = db_query($sql);
                if ($line = db_read($rlt)) {
                    /* Existe */

                    $this -> altera_status_chache($idc, 'C');
                    $this -> load -> view("oai/oai_process", $article);
                } else {
                    if ($article['issue_id'] != '0000000') {
                        $article['setSpec'] = troca($article['setSpec'], '+', '_');
                        $article['setSpec'] = troca($article['setSpec'], ':', '_');

                        /* Bloqueado */
                        if ($article['issue_id'] == '9999999') {
                            $this -> altera_status_chache($idc, 'F');
                            echo '<meta http-equiv="refresh" content="1">';
                            return ('');
                        } else {
                            /* processa e grava dados */
                            $ids = $this -> recupera_section($article['setSpec'], $article['journal_id']);
                            $article['section'] = $ids;

                            if (strlen($ids) == 0) {
                                $data = array();
                                $data['setspec'] = $article['setSpec'];

                                $data['links'] = $article['links'];

                                $sql = "select * from brapci_section order by se_descricao ";
                                $rlt = $this -> db -> query($sql);
                                $rlt = $rlt -> result_array();

                                $sx = '<table width="100%" class="tabela01"><tr valign="top"><td>';
                                $id = 0;
                                $div = round(count($rlt) / 4) + 1;
                                for ($r = 0; $r < count($rlt); $r++) {
                                    $line = $rlt[$r];
                                    if ($id > $div) { $sx .= '</td><td width="25%">';
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
                                $link = $article['links'][$r]['link'];
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
                echo '<meta http-equiv="refresh" content="1">';
                return ('ERROR');
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
        $s = troca($s, 'esp.', '');
        $s = troca($s, 'Esp.', '');
        $s = troca($s, 'esp', '');
        if (strpos($s, 'n.')) { $nr = substr($s, strpos($s, 'n.'), strlen($s));
        }
        if (strpos($s, 'No ')) { $nr = substr($s, strpos($s, 'No ') + 3, strlen($s));
        }
        if (strpos($s, 'No. ')) { $nr = substr($s, strpos($s, 'No. ') + 4, strlen($s));
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
        $s = troca($s, 'V.', 'v.');
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
        $fr = fopen($file, 'r');
        $st = fread($fr, 512);
        fclose($fr);

        //echo $file;
        if (strlen($st) == 0) {
            $doc['status'] = 'reload';
            echo '<meta http-equiv="refresh" content="1">';
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
            //echo '<br>'.$status;
            $doc['status'] = 'deleted';
            return ($doc);
        } else {
            $doc['status'] = 'active';
        }

        /* setSpec */
        $headers = $dom -> getElementsByTagName('setSpec');
        $size = ($headers -> length);
        /* Header inválido */
        if ($size < 1) {
            $doc['status'] = 'deleted';
            return ($doc);
            exit ;
        }

        foreach ($headers as $header) {
            $setSpec = $header -> nodeValue;
        }
        $setSpec = troca($setSpec, ':', '_');
        $setSpec = troca($setSpec, ' ', '_');
        $setSpec = troca($setSpec, '+', '_');
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

    function oai_resset_cache($id) {
        $sql = "update oai_cache set cache_status = '@' where cache_journal = '" . strzero($id, 7) . "'";
        $rlt = $this -> db -> query($sql);
        return (1);
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

        $sx = '';
        $sx .= 'OAI-PMH Status';
        $sx .= '<ul class="nav nav-tabs nav-justified">';
        $sx .= '<li><a href="#">para coletar <span class="badge">' . number_format($t[0], 0, ',', '.') . '</span></a></li>';
        $sx .= '<li><a href="#">coletado <span class="badge">' . number_format($t[2], 0, ',', '.') . '</span></a></li>';
        $sx .= '<li><a href="#">processado <span class="badge">' . number_format(($t[1] + $t[3]), 0, ',', '.') . '</span></a></li>';
        $sx .= '<li><a href="#">total <span class="badge">' . number_format(($t[0] + $t[1] + $t[2] + $t[3]), 0, ',', '.') . '</span></a></li>';
        $sx .= '</ul>';
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

    function artcle_wifout_file($pag = 0) {
        $off = $pag * 350;
        $sql = "select count(*) as total from brapci_article
					LEFT JOIN (
						select count(*) as total, bs_article from brapci_article_suporte 
								where bs_status <> 'X' and bs_type = 'PDF' 
								group by bs_article
						) as tabela ON bs_article = ar_codigo
					WHERE TOTAL is null AND ar_status <> 'X' 
					limit 50 offset $off";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        $sx = '<h4>' . $rlt[0]['total'] . '</h4>';

        $sql = "select ar_codigo, ar_titulo_1, jnl_nome from brapci_article
					LEFT JOIN (
						select count(*) as total, bs_article from brapci_article_suporte 
								where bs_status <> 'X' and bs_type = 'PDF' 
								group by bs_article
						) as tabela
					ON bs_article = ar_codigo
					INNER JOIN brapci_journal ON jnl_codigo = ar_journal_id
					
					WHERE TOTAL is null AND ar_status <> 'X'					
					ORDER BY jnl_nome, ar_codigo";
        /* removido em 27/07/2017 - limit 350 offset $off"; */

        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();

        $sx .= '<ul>';
        $jnl = '';
        for ($r = 0; $r < count($rlt); $r++) {
            $line = $rlt[$r];
            $xjnl = $line['jnl_nome'];
            if ($jnl != $xjnl) {
                $sx .= '<h4>' . $xjnl . '</h4>';
                $jnl = $xjnl;
            }
            $link = '<a href="' . base_url('index.php/admin/article_view/' . $line['ar_codigo'] . '/' . checkpost_link($line['ar_codigo'])) . '">';
            $sx .= '<li>' . $link . $line['ar_titulo_1'] . '</a></li>';
        }
        $sx .= '</ul>';
        return ($sx);
    }

    function fileExistPDFlink($pag = 0) {
        $sz = 30;
        $OFFSET = ($pag * 100);
        $data = date("Ymd");
        $sql = "select * from brapci_article_suporte 
					WHERE bs_update <> '$data' 
						and bs_status <> 'X'
						and bs_type = 'PDF'
					order by id_bs 
					LIMIT 100 OFFSET $OFFSET
					
					";
        $rlt = $this -> db -> query($sql);
        $rlt = $rlt -> result_array();
        $sx = '';
        for ($r = 0; $r < count($rlt); $r++) {
            $line = $rlt[$r];
            $sx .= '<br>';
            $sx .= ($r + $pag * 100) . '. ';
            $file = $line['bs_adress'];
            $sx .= $file;
            if (file_exists($file)) {
                $sx .= ' <b><font color="green">OK</font></b>' . cr();
            } else {
                $sx .= ' <b><font color="red">file not found</font></b>' . cr();
                $sql = "update brapci_article_suporte set bs_status = 'X', bs_update = '" . date("Ymd") . "' where id_bs = " . $line['id_bs'];
                $rla = $this -> db -> query($sql);
            }
        }
        if (count($rlt) > 0) {
            $sx .= '<META http-equiv="refresh" content="5;URL=' . base_url('index.php/admin/fileexist_pdf/' . ($pag + 1)) . '">';
        }
        return ($sx);
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
