<?php

class admin extends CI_controller {
	var $tabela_journals = 'brapci_journal';
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

	function article_view($id, $check) {
		global $dd,$acao;
		form_sisdoc_getpost();
		
		if (($id < 1) or ($check != checkpost_link($id))) {
			redirect(base_url('admin/journal'));
		}
		
		$data['title'] = 'Brapci : Admin';
		$data['title_page'] = 'ADMIN';
		$this -> load -> view("header/cab_admin", $data);

		/* Article */
		$this -> load -> model('articles');
		$this -> load -> model('keywords');
				
		
		/* Save data */
		switch($dd[8])
			{
			case 'TITLE':
				$this->articles->save_TITLE($id,$dd[10],$dd[11],$dd[12],$dd[13]);
				redirect(base_url('admin/article_view/'.$id.'/'.checkpost_link($id)));
				break;
			case 'ABSTRACT1':
				$this->keywords->save_KEYWORDS($id,$dd[11]);
				$this->articles->save_ABSTRACT($id,$dd[10],1);
				redirect(base_url('admin/article_view/'.$id.'/'.checkpost_link($id)));
				break;	
			case 'ABSTRACT2':
				$this->keywords->save_KEYWORDS($id,$dd[11]);
				$this->articles->save_ABSTRACT($id,$dd[10],2);
				redirect(base_url('admin/article_view/'.$id.'/'.checkpost_link($id)));
				break;								
			}

		$data = $this -> articles -> le($id);
		
		$this->load->view('admin/article_view',$data);	

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
