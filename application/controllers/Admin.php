<?php

class admin extends CI_controller {
	var $tabela_journals = 'brapci_journal';
	var $tabela_editions = 'brapci_edition';
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

	function index() {
		$data['title'] = 'Brapci : Admin';
		$data['title_page'] = 'ADMIN';
		$this -> load -> view("header/cab_admin", $data);
		$this -> load -> view("admin/menu", $data);
	}

	function journal($id = 0) {
		$data['title'] = 'Brapci : Admin';
		$data['title_page'] = 'ADMIN';
		$this -> load -> view("header/cab_admin", $data);

		$this -> load -> model('journals');

		$form = new form;
		$form -> tabela = $this -> tabela_journals;
		$form -> see = true;
		$form -> edit = true;
		$form = $this -> journals -> row($form);
		$form -> row_edit = base_url('admin/journal_edit/');
		$form -> row_view = base_url('admin/journal_view/');
		$form -> row = base_url('admin/journal/');

		$tela['tela'] = row($form, $id);
		$url = base_url('author');
		$tela['tela'] .= form_botton_new($url, 'Novo registro');

		$tela['title'] = 'Documentos';

		$this -> load -> view('form/form', $tela);
		//$this -> load -> view('header/foot');
	}

	function issue_edit($id = 0, $jid = 0) {
		global $dd, $acao;
		form_sisdoc_getpost();
		$this -> load -> model('editions');

		$data['title'] = 'Brapci : Admin';
		$data['title_page'] = 'ADMIN';
		$this -> load -> view("header/cab_admin", $data);

		/* Formulario */
		$form = new form;
		$form -> id = $id;
		$form -> row = base_url('admin/issue_view');
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
		form_sisdoc_getpost();
		
		$this->load->model("metodologia");

		if (($id < 1) or ($check != checkpost_link($id))) {
			redirect(base_url('admin/journal'));
		}

		/* Alterar Status */
		if (strlen($status) > 0) {
			$sql = "update brapci_article set ar_status = '$status' where id_ar = " . $id;
			$this -> db -> query($sql);
		}

		$data['title'] = 'Brapci : Admin';
		$data['title_page'] = 'ADMIN';
		$this -> load -> view("header/cab_admin", $data);

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
		$data['link_issue'] = '<A HREF="' . base_url('admin/issue_view/' . $data['id_ed']) . '/' . checkpost_link($data['id_ed']) . '" class="lt3">';

		$idioma_1 = trim($data['ar_idioma_1']);
		if (strlen($idioma_1) == 0) { $idioma_1 = 'pt_BR';
		}
		$idioma_2 = trim($data['ar_idioma_2']);
		if (strlen($idioma_2) == 0) { $idioma_2 = 'en';
		}

		/* Save data */
		switch($dd[8]) {
			case 'ARCHIVE':
				$this->archives->save_LINK($id,$data['ar_journal_id'],$dd[9]);
				redirect(base_url('admin/article_view/' . $id . '/' . checkpost_link($id)));
				break;
			case 'ISSUE' :
				$this -> articles -> save_ISSUE($id, $dd[11], $dd[12], $dd[13], $dd[14], $dd[15]);
				redirect(base_url('admin/article_view/' . $id . '/' . checkpost_link($id)));
				break;
			case 'TITLE' :
				$this -> articles -> save_TITLE($id, $dd[10], $dd[11], $dd[12], $dd[13]);
				redirect(base_url('admin/article_view/' . $id . '/' . checkpost_link($id)));
				break;
			case 'AUTHOR' :
				$this -> authors -> save_AUTHORS($id, $dd[10]);
				redirect(base_url('admin/article_view/' . $id . '/' . checkpost_link($id)));
				break;
			case 'ABSTRACT1' :
				$this -> keywords -> save_KEYWORDS($id, $dd[11], $idioma_1);
				$this -> articles -> save_ABSTRACT($id, $dd[10], 1);
				redirect(base_url('admin/article_view/' . $id . '/' . checkpost_link($id)));
				break;
			case 'ABSTRACT2' :
				$this -> keywords -> save_KEYWORDS($id, $dd[11], $idioma_2);
				$this -> articles -> save_ABSTRACT($id, $dd[10], 2);
				redirect(base_url('admin/article_view/' . $id . '/' . checkpost_link($id)));
				break;
		}

		$metodologia = $this->metodologia->le($id,True);
		$data['metodologia'] = $this->metodologia->mostra($metodologia);

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
			
			$this->load->model('articles');
			$this -> articles -> updatex();
			
			redirect(base_url('admin/issue_view/'.$issue.'/'.checkpost_link($issue)));
		}
	}

	function issue_view($id = 0, $check) {
		if (($id < 1) or ($check != checkpost_link($id))) {
			redirect(base_url('admin/journal'));
		}

		$data['title'] = 'Brapci : Admin';
		$data['title_page'] = 'ADMIN';
		$this -> load -> view("header/cab_admin", $data);

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
		$this -> editions -> row = base_url('admin/issue_view/');
		$tela_issue = $this -> editions -> editions_row($jid, $id);

		$tela_articles = $this -> editions -> issue_view($id);

		$tela = '<table width="100%" border=1 class="tabela00">';
		$tela .= '<TR valign="top">';
		$tela .= '<TD width="10%">';
		$tela .= $tela_issue;
		$tela .= '<TD width="90%">';
		$tela .= $tela_articles;
		/* Botao novo */
		$tela .= '<input type="button" style="margin: 20px;" onclick="article_new();" value="NOVO TRABALHO" class="botao3d back_blue back_blue_shadown">';
		$tela .= '
				<script>
					function article_new()
						{
							window.location.assign("' . base_url('admin/article_new/' . $id . '/' . checkpost_link($id)) . '");
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
			redirect(base_url('admin/journal'));
		}

		$data['title'] = 'Brapci : Admin';
		$data['title_page'] = 'ADMIN';
		$this -> load -> view("header/cab_admin", $data);

		$this -> load -> model('journals');
		$data = $this -> journals -> le($id);

		$this -> load -> view('brapci/journal', $data);

		/* Editions */
		$this -> load -> model('editions');
		$this -> editions -> row = base_url('admin/issue_view/');

		$data['tela'] = $this -> editions -> editions_row($id);
		$data['title'] = 'Editions';

		$this -> load -> view('form/form', $data);

	}

	function journal_edit($id = 0, $check) {
		if (($id < 1) or ($check != checkpost_link($id))) {
			redirect(base_url('admin/journal'));
		}

		$data['title'] = 'Brapci : Admin';
		$data['title_page'] = 'ADMIN';
		$this -> load -> view("header/cab_admin", $data);

		$this -> load -> model('journals');
		$data = $this -> journals -> le($id);

		$this -> load -> view('brapci/journal', $data);

		/* Formulario */
		$form = new form;
		$form -> id = $id;
		$form -> row = base_url('admin/journal');
		$form -> cp = $this -> journals -> cp();
		$form -> tabela = $this -> tabela_journals;

		/* form */
		$data['tela'] = form_edit($form);
		$data['title'] = 'Journals';

		$this -> load -> view('form/form', $data);

	}

	function export($id = 0) {
		$exp = 150;
		$data['title'] = 'Brapci : Admin';
		$data['title_page'] = 'ADMIN - EXPORTAÇÃO';
		$this -> load -> view("header/cab_admin", $data);
		$this -> load -> model('export');

		/* Gerar arquivo */
		if ($id == 'file') {
			$this -> export -> exporta_texto();
			$data['tela'] = '<center><h1><font color="green">Finalizado com sucesso!</font></h1></center>';
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

			$url = base_url() . 'admin/export/' . ($id + $exp);
			echo ' <meta http-equiv="refresh" content="3;' . $url . '">';
		} else {
			$url = base_url() . 'admin/export/file';
			echo ' <meta http-equiv="refresh" content="3;' . $url . '">';
		}
	}

}
?>
