<?php
/* @author: Rene Faustino Gabriel Junior <renefgj@gmail.com>
 * @date: 2015-04-25
 */

class author extends CI_Controller {
	var $tabela = 'brapci_autor';

	function __construct() {
		date_default_timezone_set('GMT');
		parent::__construct();
		$this -> load -> library('form_validation');
		$this -> load -> database();
		$this -> load -> helper('form');
		$this -> load -> helper('form_sisdoc');
		$this -> load -> helper('url');
		/* $this -> lang -> load("app", "portuguese"); */
		$this -> load -> library('session');
	}

	/*
	 * Authors
	 */

	function index() {

		$data['title'] = 'Brapci : Admin - Authors';
		$data['title_page'] = 'ADMIN - Author';
		$this -> load -> view("header/cab_admin", $data);

		$this -> load -> view("header/foot_admin");

		/* Security */
		$this -> load -> model('security_set');
		$this -> security_set -> login_set();
	}

	public function row($id=1) {
		$this -> load -> model('authors');

		$data['title'] = 'Brapci : Admin - Authors';
		$data['title_page'] = 'ADMIN - Author';
		$this -> load -> view("header/cab_admin", $data);

		$form = new form;
		$form -> tabela = $this -> tabela;
		$form -> see = true;
		$form->row = base_url('index.php/author/row');
		$form->row_view = base_url('index.php/author/view');
		$form->row_edit = base_url('index.php/author/edit');
		$form->edit = True;
		$form = $this -> authors -> row($form);
		
		
		$tela['tela'] = row($form,$id);
		$url = base_url('index.php/author');
		$tela['tela'] .= form_botton_new($url, 'Novo registro');

		$tela['title'] = 'Documentos';

		$this -> load -> view('form/form', $tela);
		//$this -> load -> view('header/foot');
	}

	function edit($id = 0) {
		$data['title'] = 'Brapci : Admin - Authors';
		$data['title_page'] = 'ADMIN - Author';
		$this -> load -> view("header/cab_admin", $data);

		if ($id > 0) {

			$this -> load -> model('authors');
			$data = $this -> authors -> le($id);
			$data['acao_editar'] = '';
			$this -> load -> view("brapci/author_resume", $data);
			
			/* Formulario */
			$form = new form;
			$form -> id = $id;
			$form -> tabela = $this -> tabela;;
			$form -> row = base_url('index.php/author/edit/'.$id.'/'.checkpost_link($id));
			$form -> cp = $this -> authors -> cp();
	
			/* form */
			$data['content'] = $form -> editar($form -> cp, $form -> tabela);
			$data['title'] = msg('Authors');
			$this -> load -> view('content', $data);			
	
			if ($form -> saved > 0) {
				$url = base_url('index.php/author/row');
				redirect($url);
			}			

		}

	}

	function view($id = 0) {

		$data['title'] = 'Brapci : Admin - Authors';
		$data['title_page'] = 'ADMIN - Author';
		$this -> load -> view("header/cab_admin", $data);

		if ($id > 0) {
			$this -> load -> model('authors');
			$row = $this -> authors -> le($id);
			$codigo = $row['autor_codigo'];
			
			$data = $row;

			$this -> load -> view("brapci/author_resume", $data);
			
			/* Article publicados */
			$this->load->model('articles');
			$tela = $this->articles->articles_author($codigo);
			$data['artigos'] = $tela;
			
			$this -> load -> view("brapci/autor_article.php", $data);
			
		}
	}

}
?>
