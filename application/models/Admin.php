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
class admins extends CI_model {
	var $tabela_journals = 'brapci_journal';
	var $tabela_editions = 'brapci_edition';


	function index() {
		$data['title'] = 'Brapci : Admin';
		$data['title_page'] = 'ADMIN';
		$this -> cab();
	}

	function journal($id = 0) {

		$this -> cab();
		$this -> load -> model('journals');

		$form = new form;
		$form -> tabela = $this -> tabela_journals;
		$form -> see = true;
		$form -> edit = true;
		$form = $this -> journals -> row($form);
		$form -> row_edit = base_url('index.php/admin/journal_edit/');
		$form -> row_view = base_url('index.php/admin/journal_view/');
		$form -> row = base_url('index.php/admin/journal/');

		$tela['tela'] = row($form, $id);
		$url = base_url('index.php/author');
		$tela['tela'] .= form_botton_new($url, 'Novo registro');

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
		$form -> row = base_url('index.php/admin/issue_view');
		$form -> cp = $this -> editions -> cp();
		$form -> tabela = $this -> tabela_editions;

		/* form */
		$data['tela'] = form_edit($form);
		$data['title'] = 'Journals';
		$form -> cp = $this -> editions -> updatex();

		$this -> load -> view('form/form', $data);
	}

	function article_view($id, $check, $status = '') {
		global $dd, $acao;

		if (($id < 1) or ($check != checkpost_link($id))) {
			redirect(base_url('index.php/admin/journal'));
		}

		/* Alterar Status */
		if (strlen($status) > 0) {
			$sql = "update brapci_article set ar_status = '$status' where id_ar = " . $id;
			$this -> db -> query($sql);
		}

		$this -> cab();

		/* Article */
		$this -> load -> model('articles');
		$this -> load -> model('keywords');
		$this -> load -> model('authors');
		$this -> load -> model('archives');
		$this -> load -> model('cited');
		$this -> load -> model('tools/tools');

		$data = $this -> articles -> le($id);

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
		switch($dd[8]) {
			case 'ARCHIVE' :
				$this -> archives -> save_LINK($id, $data['ar_journal_id'], $dd[9]);
				redirect(base_url('index.php/admin/article_view/' . $id . '/' . checkpost_link($id)));
				break;
			case 'ISSUE' :
				$this -> articles -> save_ISSUE($id, $dd[11], $dd[12], $dd[13], $dd[14], $dd[15]);
				redirect(base_url('index.php/admin/article_view/' . $id . '/' . checkpost_link($id)));
				break;
			case 'TITLE' :
				$this -> articles -> save_TITLE($id, $dd[10], $dd[11], $dd[12], $dd[13]);
				redirect(base_url('index.php/admin/article_view/' . $id . '/' . checkpost_link($id)));
				break;
			case 'AUTHOR' :
				$this -> authors -> save_AUTHORS($id, $dd[10]);
				redirect(base_url('index.php/admin/article_view/' . $id . '/' . checkpost_link($id)));
				break;
		}

		$this -> load -> view('admin/article_view', $data);

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

		$tela_articles = $this -> editions -> issue_view($id);

		$tela = '<table width="100%" border=1 class="tabela00">';
		$tela .= '<TR valign="top">';
		$tela .= '<TD width="10%">';
		$tela .= $tela_issue;
		$tela .= '<TD width="90%">';
		$tela .= $tela_articles;
		/* Botao novo */
		$tela .= '<span class="btn btn-primary" onclick="article_new();">NOVO TRABALHO</span>';
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

		$data['tela'] = $this -> editions -> editions_row($id);
		$data['title'] = 'Editions';

		$this -> load -> view('form/form', $data);

	}

	function journal_edit($id = 0, $check) {
		if (($id < 1) or ($check != checkpost_link($id))) {
			redirect(base_url('index.php/admin/journal'));
		}

		$this -> cab();

		$this -> load -> model('journals');
		$data = $this -> journals -> le($id);

		$this -> load -> view('brapci/journal', $data);

		/* Formulario */
		$form = new form;
		$form -> id = $id;
		$form -> row = base_url('index.php/admin/journal');
		$form -> cp = $this -> journals -> cp();
		$form -> tabela = $this -> tabela_journals;

		/* form */
		$data['tela'] = form_edit($form);
		$data['title'] = 'Journals';

		$this -> load -> view('form/form', $data);

	}

}
?>
