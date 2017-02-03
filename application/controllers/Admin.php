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
class admin extends CI_Controller {
	var $tabela_journals = 'brapci_journal';
	var $tabela_editions = 'brapci_edition';
	function __construct() {
		global $db_public;

		parent::__construct();
		$this -> lang -> load("app", "portuguese");
		$this -> load -> library('form_validation');
		$this -> load -> database();
		$this -> load -> helper('form');
		$this -> load -> helper('form_sisdoc');
		$this -> load -> helper('url');
		$this -> load -> helper('xml');
		$this -> load -> library('session');
		$db_public = 'brapci_publico.';
		date_default_timezone_set('America/Sao_Paulo');
	}

	function security() {
		$user = $this -> session -> userdata('user');
		$email = $this -> session -> userdata('email');
		$nivel = $this -> session -> userdata('nivel');
		if (round($nivel) < 1) {
			redirect(base_url('index.php/social/login'));
		}
	}

	function cab() {
		$this -> load -> model('users');
		$data = array();
		$data['title_page'] = 'Brapci :: ADMIN';
		$data['title'] = 'Brapci :: ADMIN';
		$this -> load -> view("header/cab", $data);
		$data['title'] = '';
		$this -> users -> security();
	}

	function cab2($data = array()) {
		$this -> load -> model("users");
		$js = array();
		$css = array();
		array_push($js, 'form_sisdoc.js');
		array_push($js, 'jquery-ui.min.js');

		$data['js'] = $js;
		$data['css'] = $css;

		$data['title'] = 'Brapci : Admin';
		$this -> load -> view('header/header', $data);
		$data['title'] = '';

		if (!(isset($data['nocab']))) {
			//$this -> load -> view('menus/menu_cab_top', $data);
			$this -> load -> view('header/cab_admin', $data);
		} else {
			$this -> load -> view('header/header_nomargin.php', null);
		}

		$this -> load -> model('users');
		$this -> users -> security();
	}

	function footer() {
		$this -> load -> view('header/footer', null);
	}

	function index() {
		$this -> load -> model('articles');
		$this -> load -> model('oai_pmh');

		$this -> cab();
		
		/* RESUMO GERAL */
		$tela = $this -> articles -> resumo();
		$data['title'] = '';
		
		$tela .= $this -> load -> view('admin/menu_admin', $data, true);
		
		$data['content'] = $tela;
		$this -> load -> view('content', $data);
		
		/* RESUMO OAI */
		//$tela = $this -> oai_pmh -> oai_resumo();
		//$data['content'] = $tela;
		//$this -> load -> view('content', $data);

		

	}

	function games() {
		$this -> load -> model('articles');
		$this -> load -> model('oai_pmh');
		$data = array();
		$this -> cab();
		$this -> load -> view('admin/menu_game', $data);

	}

	function game_idioma($id = '') {
		$this -> load -> model('games');
		$data = array();
		$this -> cab();

		$tela['content'] = $this -> games -> game_i($id);
		$this -> load -> view('content', $tela);

	}

	function journal($id = 0) {
		$this -> cab();
		$this -> load -> model('journals');

		$form = new form;
		$form -> tabela = $this -> tabela_journals;
		$form = $this -> journals -> row($form);
		$form -> novo = true;
		$form -> see = true;
		$form -> edit = true;
		$form -> row_edit = base_url('index.php/admin/journal_edit/');
		$form -> row_view = base_url('index.php/admin/journal_view/');
		$form -> row = base_url('index.php/admin/journal/');
		$form -> offset = 200;

		$tela['tela'] = row($form, $id);
		$url = base_url('index.php/admin/journal');

		$tela['title'] = 'Documentos';

		$this -> load -> view('form/form', $tela);
		//$this -> load -> view('header/foot');
	}

	function issue_edit($id = 0, $jid = 0) {
		global $dd, $acao;
		form_sisdoc_getpost();
		$this -> load -> model('editions');

		$this -> cab();

		/* Formulario */
		$form = new form;
		$form -> id = $id;
		$form -> tabela = 'brapci_edition';
		$form -> row = base_url('index.php/admin/issue_view');
		$cp = $this -> editions -> cp();

		/* form */
		$data['tela'] = $form -> editar($cp, $form -> tabela);
		$data['title'] = 'Journals';
		$form -> cp = $this -> editions -> updatex();

		if ($form -> saved > 0) {
			if ($id > 0) {
				$url = base_url('index.php/admin/issue_view/' . $id . '/' . checkpost_link($id));
			} else {
				$url = base_url('index.php/admin/journal_view/' . $jid . '/' . checkpost_link($id));
			}
			redirect($url);
		}

		$this -> load -> view('form/form', $data);
	}

	function scielo_harvesting($id = 0, $chk = '') {
		$this -> load -> model('articles');

		$this -> cab();
		$data = $this -> articles -> le($id);

		$doi = trim($data['ar_doi']);
		$doi = troca($doi, '10.1590/', '');
		$scielo = 'http://www.scielo.br/scieloOrg/php/articleXML.php?pid=' . $doi;
		$scielo = 'http://www.scielo.br/scielo.php?script=sci_arttext&pid=S' . $doi . '&lng=en&nrm=iso&tlng=pt';
		echo $scielo;
	}

	function article_change($id, $chk) {
		$this -> load -> model('articles');
		$this -> articles -> change_language($id);
		redirect(base_url('index.php/admin/article_view/' . $id . '/' . checkpost_link($id)));
	}

	function article_view($id, $check, $status = '') {
		global $dd, $acao;
		form_sisdoc_getpost();

		if (($id < 1) or ($check != checkpost_link($id))) {
			redirect(base_url('index.php/admin/journal'));
		}

		/* Alterar Status */
		if (strlen($status) > 0) {
			$sql = "update brapci_article set ar_status = '$status' where id_ar = " . $id;
			$this -> db -> query($sql);
		}

		$data['title_page'] = 'ADMIN';
		$this -> cab();

		/* Article */
		$this -> load -> model('articles');
		$this -> load -> model('keywords');
		$this -> load -> model('authors');
		$this -> load -> model('archives');
		$this -> load -> model('cited');
		$this -> load -> model('tools/tools');
		$this -> load -> model('metodologias');

		$data = $this -> articles -> le($id);
		if (strlen($data['ar_key1']) == 0)
			{
				$this->articles->inserir_keywords($id);
				$data = $this -> articles -> le($id);		
			}		

		$data['archives'] = $this -> archives -> show_files($id);
		$data['citeds'] = $this -> cited -> show_cited($id);

		/* Barra de status da indexação */
		$data['progress_bar'] = $this -> tools -> progress_bar($data['ar_status']);

		/* Botoes de acao */
		$data['botao_acoes'] = $this -> tools -> actions_buttons($data['ar_status'], $id);

		/* Links */
		$data['link_issue'] = '<A HREF="' . base_url('index.php/admin/issue_view/' . $data['id_ed']) . '/' . checkpost_link($data['id_ed']) . '" class="lt3">';

		$idioma_1 = trim($data['ar_idioma_1']);
		if (strlen($idioma_1) == 0) { $idioma_1 = 'pt_BR';
		}
		$idioma_2 = trim($data['ar_idioma_2']);
		if (strlen($idioma_2) == 0) { $idioma_2 = 'en';
		}

		/* Save data */
		switch(get("dd8")) {
			case 'ARCHIVE' :
				$this -> archives -> save_LINK($id, $data['ar_journal_id'], $dd[9]);
				redirect(base_url('index.php/v/a/' . $id . '/' . checkpost_link($id)));
				break;
			case 'ISSUE' :
				$this -> articles -> save_ISSUE($id, $dd[11], $dd[12], $dd[13], $dd[14], $dd[15]);
				redirect(base_url('index.php/v/a/' . $id . '/' . checkpost_link($id)));
				break;
			case 'TITLE' :
				$this -> articles -> save_TITLE($id, $dd[10], $dd[11], $dd[12], $dd[13]);
				redirect(base_url('index.php/v/a/' . $id . '/' . checkpost_link($id)));
				break;
			case 'AUTHOR' :
				$this -> authors -> save_AUTHORS($id, $dd[10]);
				redirect(base_url('index.php/v/a/' . $id . '/' . checkpost_link($id)));
				break;
			case 'CITED' :
				if (get("dd63") == '1') {
					$data['tela'] = $this -> cited -> save_ref($id, get("dd62"));
					redirect(base_url('index.php/v/a/' . $id . '/' . checkpost_link($id)));
				}
				break;
		}
		$id_art = $data['ar_codigo'];
		$data['metodologias'] = '<div id="metodos" class="border1">' . $this -> metodologias -> mostra($id_art) . '</div>';
		$data['metodologias'] .= $this -> metodologias -> metodos_incluir($id_art);

		/* article - parte I */
		$data['tab_descript'] = $this -> load -> view('admin/article_view_tt', $data, true);
		$data['tab_descript'] .= $this -> articles->acao($data['ar_codigo'],$data['ar_status']);

		$jid = $data['ar_journal_id'];
		
		$data['tab_marc21'] = $this -> load -> view('admin/article_view_marc21', $data, true);
		$data['tab_editar'] = $this -> articles -> editar($id,$jid);
		$data['tab_refer'] = $this -> load -> view('admin/article_view_refer', $data, true);
		$data['tab_support'] = $this -> articles-> supports($id);
		$data['tab_support'] .= $this -> articles-> supports_novo($id);
		$data['tab_author'] = $this -> authors -> author_editar($id,$jid);

		if ($this -> articles -> saved > 0) {
			redirect(base_url('index.php/admin/article_view/' . $id . '/' . checkpost_link($id)));
			exit ;
		}

		$this -> load -> view('admin/article_view', $data);

		//print_r($data);
		//exit;

		//$this -> load -> view('content', $data);
		$this -> load -> view('header/foot_admin', $data);

	}

	function article_author_editar($id='')
		{
			$this->load->model('authors');
			
			$this->load->view('header/header',null);
			$form = new form;
			$cp = array();
			
			if (strlen(get("acao")) == 0)
				{
					$authors = $this->authors->author_editar($id,'1');
					$_POST['dd2'] = $authors;
				}
			
			array_push($cp,array('$H8','','',False,False));
			array_push($cp,array('$HV','',$id,False,False));
			array_push($cp,array('$T80:5','','Autores',True,False));
			array_push($cp,array('$B8','',msg('save'),False,False));
			$tela = $form->editar($cp,'');
			$data['content'] = $tela;
			$data['title'] = '';
			
			if ($form->saved > 0)
				{
					$au = get("dd2");
					$this->authors->save_AUTHORS($id, $au);
					$this->load->view('wclose',null);					
				} else {
					$this->load->view('content',$data);		
				}
			
			
			
			
		}

	function support_cancelar($id=0,$conf='')
		{
		$this -> load -> model("articles");			
		$data['nocab'] = true;
		$this -> load -> view('header/header', $data);
		
		$data = $this->articles->le_support($id);
		$data['content'] = $data['bs_adress'];
		$data['excluir'] = true;
		$data['id'] = $data['id_bs'];
		$data['link'] = 'index.php/admin/support_cancelar/'.$id;
		$data['title'] = '';
		$data['content'] = $this->load->view('admin/confirm',$data,true);
		
		switch($conf)
			{
				case '1':
					$this->articles->support_alterar_status($id,'X');
					echo '<script> wclose(); </script>';
					break;
			}
		

		$this->load->view('content',$data);			
		}

	function support_editar($id=0,$art='')
		{
		$this -> load -> model("articles");			
		$data['nocab'] = true;
		$this -> load -> view('header/header', $data);
		
		$cp = array();
		array_push($cp,array('$H8','id_bs','',false,true));
		array_push($cp,array('$HV','bs_article',$art,true,true));
		$op = 'URL:Link de Internet&DOI:DOI&PDF:PDF&HTM:HTM&MP3:MP3&OAI:OAI&DOC:DOC';
		array_push($cp,array('$O '.$op,'bs_type','Tipo',true,true));
		array_push($cp,array('$S80','bs_adress','Link',true,true));
		$op = 'A:Ativo&X:Cancelado&@:Coletar&Z:Excluir';
		array_push($cp,array('$O '.$op,'bs_status','Situação',true,true));
		array_push($cp,array('$HV','bs_update',date("Ymd"),true,true));
		
		$form = new form;
		$form->id=$id;
		$data['title'] = '';
		$data['content'] = $form->editar($cp,'brapci_article_suporte');
		if ($form->saved > 0)
			{
				$this->articles->excluir_suportes();
				echo '
					<script> 
						close(); 
					</script>';
			}

		$this->load->view('content',$data);			
		}
	function support_upload($id=0,$art='')
		{
		$this -> load -> model("articles");
		$this -> load -> model("geds");			
		$data['nocab'] = true;
		$this -> load -> view('header/header', $data);
	
		$data['content'] = $this->geds->upload($id);
		$data['title'] = '';
	
		if (isset($_FILES['userfile']) and (strlen($_FILES['userfile']['name']) > 0))
			{
				$this->geds->save_post_file($id);
				//$this->articles->excluir_suportes();
				echo '
					<script> 
						close(); 
					</script>';
			}

		$this->load->view('content',$data);			
		}
	function refer($ar = '') {
		$this -> load -> model("cited");

		$data['nocab'] = true;
		$this -> load -> view('header/header', $data);
		$cp = array();
		array_push($cp, array('$H8', '', '', False, True));
		array_push($cp, array('$T80:10', '', msg('bibliography'), True, True));
		array_push($cp, array('$O 1:SIM', '', msg('finalizar'), True, True));
		array_push($cp, array('$B8', '', 'Processar >>>', False, True));
		$form = new form;
		$tela = $form -> editar($cp, '');

		if ($form -> saved > 0) {
			$this -> cited -> save_ref($ar, get("dd1"));
			$tela = '<script> wclose(); </script>';
		} else {
			$ln = $this -> cited -> save_ref_pre(get("dd1"));
			$tela .= '<ul>';
			for ($r = 0; $r < count($ln); $r++) {
				if (strlen($ln[$r]) > 5) {
					$tela .= '<li>' . $ln[$r] . '</li>';
				}
			}
			$tela .= '</ul>';

		}

		$data['content'] = $tela;
		$data['title'] = '';
		$this -> load -> view('content', $data);
	}

	function metodo($tipo = '', $art = '') {
		$sx = 'método';
		$ar = get("dd1");
		$this -> load -> model('metodologias');
		$this -> metodologias -> acao($art, $tipo, $ar);
		$sx = $this -> metodologias -> mostra($art);
		$data = array();
		$data['content'] = $sx . ' ' . date("Y-m-d H:i:s");
		$this -> load -> view('content', $data);
	}

	function article_new($issue) {
		$sql = "select * from brapci_edition where id_ed = " . $issue;
		$rlt = $this -> db -> query($sql);
		$rlt = $rlt -> result_array($rlt);

		if (count($rlt) > 0) {
			$line = $rlt[0];
			$edition = $line['ed_codigo'];
			$jid = $line['ed_journal_id'];
			$ano = $line['ed_ano'];
			$sql = "insert into brapci_article
						(
						ar_journal_id, ar_codigo, ar_titulo_1,
						ar_idioma_1, ar_idioma_2, ar_edition,
						ar_status, ar_tipo, ar_ano
						) values (
						'$jid', '', '#without title#',
						'pt_BR','en','$edition',
						'A','ARTIC','$ano')
			 ";
			$rlt = $this -> db -> query($sql);

			$this -> load -> model('articles');
			$this -> articles -> updatex();

			redirect(base_url('index.php/admin/issue_view/' . $issue . '/' . checkpost_link($issue)));
		}
	}

	function issue_view($id = 0, $check) {
		if (($id < 1) or ($check != checkpost_link($id))) {
			redirect(base_url('index.php/admin/journal'));
		}
		$this -> cab();

		/* Editions */
		$this -> load -> model('editions');

		/* Atualiza o Status do ISSUE */
		$this -> editions -> update_status($id);

		$data = $this -> editions -> le($id);

		$jid = $data['ed_journal_id'];

		$this -> load -> model('journals');
		$data = $this -> journals -> le($jid);

		$this -> load -> view('brapci/journal', $data);

		/* Editions */
		$this -> load -> model('editions');
		$this -> editions -> row = base_url('index.php/admin/issue_view/');
		$tela_issue = $this -> editions -> editions_row($jid, $id);

		$tela_articles = $this -> editions -> issue_view($id, 1, 1);

		$tela = '<table width="100%" class="table">';
		$tela .= '<TR valign="top">';
		$tela .= '<TD width="20%" style="border-right: 1px solid #cccccc;">';
		$tela .= $tela_issue;
		$tela .= '<TD width="80%">';

		$href = '<A HREF="' . base_url('index.php/admin/issue_edit/' . $id . '/' . $jid) . '" class="btn btn-default">';
		$tela .= ' ' . $href . 'Editar edição</a> ';
		$tela .= ' | ';
		$href = '<A HREF="' . base_url('index.php/admin/issue_edit/0/' . $jid) . '" class="btn btn-default">';
		$tela .= ' ' . $href . ' Nova edição</a> ';
		$tela .= '<hr>';
		$tela .= $tela_articles;
		/* Botao novo */
		$tela .= '<input type="button" style="margin: 20px;" onclick="article_new();" value="NOVO TRABALHO" class="btn btn-default">';
		$tela .= '
				<script>
					function article_new()
						{
							window.location.assign("' . base_url('index.php/admin/article_new/' . $id . '/' . checkpost_link($id)) . '");
						}
				</script>
		';
		$tela .= '</td></tr>';
		$tela .= '</table>';

		$data['title'] = 'Editions';
		$data['tela'] = $tela;

		$this -> load -> view('form/form', $data);
	}

	function journal_view($id = 0, $check) {
		if (($id < 1) or ($check != checkpost_link($id))) {
			redirect(base_url('index.php/admin/journal'));
		}
		$this -> cab();

		$this -> load -> model('journals');
		$data = $this -> journals -> le($id);

		$this -> load -> view('brapci/journal', $data);

		/* Editions */
		$this -> load -> model('editions');
		$this -> editions -> row = base_url('index.php/admin/issue_view/');

		$link_new = '<a href="' . base_url('index.php/admin/issue_edit/0/' . $id) . '" class="btn btn-default">' . msg('issue_new') . '</a>';

		$data['tela'] = $link_new . $this -> editions -> editions_row($id);
		$data['title'] = 'Editions';

		$this -> load -> view('form/form', $data);

	}

	function article_double() {
		$this -> cab();
		$this -> load -> model('articles');

		$tela = $this -> articles -> double_articles();
		$data['content'] = $tela;
		$data['title'] = '';

		$this -> load -> view('content', $data);
	}

	function journal_edit($id = 0, $check) {

		$this -> cab();

		$this -> load -> model('journals');
		if ($id > 0) {
			$data = $this -> journals -> le($id);
			$this -> load -> view('brapci/journal', $data);
		} else {
			$data = array();
		}

		/* Formulario */
		$form = new form;
		$form -> id = $id;
		$form -> row = base_url('index.php/admin/journal');
		$cp = $this -> journals -> cp();
		$form -> tabela = $this -> tabela_journals;

		/* form */
		$data['tela'] = $form -> editar($cp, $form -> tabela);
		$data['title'] = 'Journals';

		if ($form -> saved > 0) {
			$this -> journals -> updatex();
			redirect(base_url('index.php/admin/journal'));
		}

		$this -> load -> view('form/form', $data);

	}

	function export_resume() {
		$exp = 150;

		$this -> load -> model('export');
		$this -> export -> resume();
		$this -> cab();
		
		
		$data['content'] = $this -> load -> view('success', null, true);
		$data['title'] = 'Export to resumo';
		$this->load->view('content',$data);		
	}

	function export($id = '') {
		$exp = 150;

		$this -> cab();
		$this -> load -> model('export');

		/* Gerar arquivo */
		if ($id == 'file') {
			echo '==>' . $id;
			//$this -> export -> exporta_texto();
			$data['tela'] = '<center><h1><font color="green">Finalizado com sucesso (text)!</font></h1></center>';
			$this -> load -> view('form/form', $data);
			return (0);
		}

		/* Mostrar */
		$data['tela'] = '';
		if ($id == 0) {
			$data['tela'] .= '<BR>Zerando base';
			$this -> export -> zera_public();
		}

		$total = $this -> export -> export_public($id, $exp);

		if ($total > 0) {
			$sending = '<center><img src="' . base_url('img/sending.gif') . '"></center>';
			$data['tela'] .= $sending;

			$data['tela'] .= '<BR>Exportando ' . $total . ' registros, já foram enviados ' . round($id) . ' registros';
			$this -> load -> view('form/form', $data);

			$url = base_url('index.php/admin/export/' . ($id + $exp));
			echo ' <meta http-equiv="refresh" content="3;' . $url . '">';
		} else {
			$url = base_url('index.php/admin/export/file');
			echo ' <meta http-equiv="refresh" content="3;' . $url . '">';
		}
	}

	function export_author() {
		$exp = 150;
		$this -> cab();
		$this -> load -> model('export');

		$tela = $this -> export -> export_author();
		$data['content'] = $tela . '<br><br>';
		$this -> load -> view('content', $data);

		$this -> load -> view('header/foot', null);

	}

	function export_keywords() {
		$exp = 150;
		$this -> cab();
		$this -> load -> model('export');

		$tela = $this -> export -> export_keywords();
		$data['content'] = $tela . '<br><br>';
		$this -> load -> view('content', $data);

		$this -> load -> view('header/foot', null);

	}

	function doi_find_abstract($id = '') {
		/* Model */
		$this -> load -> model("doi");
		$this -> cab();
		$data = array();
		$data['title'] = '';
		$data['content'] = $this -> doi -> find_doi_in_abstract($id);
		$this -> load -> view('content', $data);
	}

	function doi_find_files($id = '') {
		/* Model */
		$this -> load -> model("doi");
		$this -> cab();
		$data = array();
		$data['title'] = '';
		$data['content'] = $this -> doi -> find_doi_in_files($id);
		$this -> load -> view('content', $data);
	}

	function author_use($id = '') {
		/* Model */
		$this -> load -> model("authors");
		$this -> cab();
		$data = array();
		$data['content'] = $this -> authors -> check_remissive();
		$this -> load -> view('content', $data);
	}

	function terms_use($id = '') {
		/* Model */
		$this -> load -> model("terms");
		$this -> cab();
		$data = array();
		$data['content'] = $this -> terms -> check_remissive();
		$this -> load -> view('content', $data);
	}

	function terms_language($id = '') {
		/* Model */
		$this -> load -> model("keywords");
		$this -> cab();
		$data = array();
		$data['content'] = $this -> keywords -> check_keywords_language();
		$this -> load -> view('content', $data);
	}

	function fileexist_pdf($pag = 0) {
		$this -> load -> model('Oai_pmh');
		$this -> cab();

		$sx = $this -> Oai_pmh -> fileExistPDFlink($pag);

		$data['content'] = $sx;
		$this -> load -> view('content', $data);

	}

	function harvesting_pdf($pag = 0) {
		$this -> load -> model('Oai_pmh');
		$this -> cab();

		if ($pag == 0) {
			$this -> Oai_pmh -> doublePDFlink();
		}
		$js = '<script>' . cr();
		$sx = '<table width="100%" border=1>';
		for ($rx = 1; $rx <= 1; $rx++) {
			$reg = $this -> Oai_pmh -> nextPDFharvesting();
			$id = round($reg['id_bs']);
			if ($id == 0) {
				echo 'FIM';
				redirect(base_url('index.php/admin/'));
				exit ;
			}
			$url = $reg['bs_adress'];
			$sx .= '<tr>';
			$sx .= '<td>' . $url . '</td>' . cr();
			$sx .= '<td width="400">' . cr();
			$divm = 'id' . $rx;
			$sx .= '<div id="' . $divm . '" style="width:400px; height: 30px; border:1px solid #333;" class="lt1"></div>';
			$sx .= '</td>' . cr();
			$sx .= '</tr>';

			$js .= '
				$("#' . $divm . '").html("coletando..."); 
				$.ajax({
  					method: "POST",
  					url: "' . base_url('index.php/oai/coletar_pdf/' . $id . '/' . ($pag + 1)) . '",
  					data: { name: "OAI", location: "PDF" }
					})
  					.done(function( data ) {
    						$("#' . $divm . '").html(data);
  					});
			' . cr();

		}
		$js .= '</script>' . cr();
		$sx .= '</table>';
		$sx .= $js;

		if (count($reg) > 0) {
			$sx .= '<br>Total para coleta: ' . $this -> Oai_pmh -> totalPDFharvesting();
			$sx .= '<br>ID:' . $reg['id_bs'];
			$sx .= '<meta http-equiv=refresh content="5;URL=' . base_url('index.php/admin/harvesting_pdf/' . ($pag + 1)) . '">';
		} else {
			$sx .= 'Nada para coletar';
		}
		$data['content'] = $sx;
		$this -> load -> view('content', $data);

	}

	function harvesting_pdf_convert($pag = 0) {
		$this -> load -> model('Oai_pmh');
		$this -> cab();

		$js = '<script>' . cr();
		$sx = '<table width="100%" border=1>';
		for ($rx = 1; $rx <= 1; $rx++) {
			$reg = $this -> Oai_pmh -> nextPDFconvert();
			$id = round($reg['id_bs']);
			if ($id == 0) {
				echo 'FIM';
				exit ;
			}
			$url = $reg['bs_adress'];
			$sx .= '<tr>';
			$sx .= '<td>' . $url . '</td>' . cr();
			$sx .= '<td width="400">' . cr();
			$divm = 'id' . $rx;
			$sx .= '<div id="' . $divm . '" style="width:400px; height: 30px; border:1px solid #333;" class="lt1"></div>';
			$sx .= '</td>' . cr();
			$sx .= '</tr>';

			$js .= '
				$("#' . $divm . '").html("convertendo..."); 
				$.ajax({
  					method: "POST",
  					url: "' . base_url('index.php/oai/converter/' . $id . '/' . ($pag + 1)) . '",
  					data: { name: "OAI", location: "PDF" }
					})
  					.done(function( data ) {
    						$("#' . $divm . '").html(data);
  					});
			' . cr();

		}
		$js .= '</script>' . cr();
		$sx .= '</table>';
		$sx .= $js;

		if (count($reg) > 0) {
			$sx .= '<br>ID:' . $reg['id_bs'];
			$sx .= '<meta http-equiv=refresh content="5;URL=' . base_url('index.php/admin/harvesting_pdf_convert/' . ($pag + 1)) . '">';
		} else {
			$sx .= 'Nada para converter';
		}
		$data['content'] = $sx;
		$this -> load -> view('content', $data);

	}

	function tools() {
		$this -> cab();
		$menu = array();
		array_push($menu, array(msg('Autoindex'), msg('find DOI in Abstract'), 'ITE', '/admin/doi_find_abstract'));
		array_push($menu, array(msg('Autoindex'), msg('find DOI in Files'), 'ITE', '/admin/doi_find_files'));
		array_push($menu, array(msg('Autoindex'), msg('set_portugues_primary'), 'ITE', '/admin/linguage_portuguese_first'));
		array_push($menu, array(msg('Autoindex'), msg('set_second_linguage'), 'ITE', '/admin/linguage_second'));
		array_push($menu, array(msg('Articles'), msg('Check duplicate'), 'ITE', '/admin/article_double'));
		array_push($menu, array(msg('Public Module'), msg('Export to public module'), 'ITE', '/admin/export'));
		array_push($menu, array(msg('Public Module'), msg('Export Author to public module'), 'ITE', '/admin/export_author'));
		array_push($menu, array(msg('Public Module'), msg('Export Keywords to public module'), 'ITE', '/admin/export_keywords'));
		array_push($menu, array(msg('Public Module'), msg('Export Resume to Public'), 'ITE', '/admin/export_resume'));
		array_push($menu, array(msg('Autoridade'), msg('Check remissive authors n use'), 'ITE', '/admin/author_use'));
		array_push($menu, array(msg('Autoridade'), msg('Check remissive terms in use'), 'ITE', '/admin/terms_use'));
		array_push($menu, array(msg('Autoridade'), msg('Check language of terms'), 'ITE', '/admin/terms_language'));
		array_push($menu, array(msg('PDF'), msg('Harvesting PDF'), 'ITE', '/admin/harvesting_pdf'));
		array_push($menu, array(msg('PDF'), msg('Convert PDF'), 'ITE', '/admin/harvesting_pdf_convert'));
		array_push($menu, array(msg('PDF'), msg('File Exist'), 'ITE', '/admin/fileexist_pdf'));
		array_push($menu, array(msg('OAI'), msg('Harvesting all publications'), 'ITE', '/oai/harvest'));
		array_push($menu, array(msg('OAI'), msg('Resume all harvesting'), 'ITE', '/oai/harvesting'));
		array_push($menu, array(msg('OAI'), msg('Reset OAI cached'), 'ITE', '/oai/cache_reset'));
		$data = array();
		$data['menu'] = $menu;
		$data['title_menu'] = msg('tools');
		$this -> load -> view('header/main_menu', $data);
	}

	function linguage_portuguese_first() {
		$this -> cab();
		$this -> load -> model('articles');
		$this->articles->autoindex_linguage();
		$tela = $this -> articles -> autoindex_change_linguage();
		$data['content'] = $tela;
		$data['title'] = 'Autoindex linguage';
		$this -> load -> view('content', $data);
	}
	function linguage_second() {
		$this -> cab();
		$this -> load -> model('articles');

		$tela = $this -> articles -> autoindex_linguage_second();
		$data['content'] = $tela;
		$this -> load -> view('content', $data);
	}
	function resumo_status($id)
		{
			$this->load->model('articles');
			
			$this->cab();
			$sx = $this->articles->task_next('A');
			$data['content'] = $sx;
			$data['title'] = '';
			$this->load->view('content',$data);
			
		}
	function article_alterar_status($ar,$sta)
		{
			$this->load->model('articles');
			$this->articles->mudar_status($ar,$sta);
			$link = base_url('index.php/admin/resumo_status/0');
			redirect($link);
		}

}
?>
