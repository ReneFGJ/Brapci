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

class article extends CI_Controller {
	function __construct() {
		global $db_public;
		
		$db_public = 'brapci_publico.';
		parent::__construct();
		$this -> lang -> load("app", "portuguese");
		$this -> load -> library('form_validation');
		$this -> load -> database();
		$this -> load -> helper('form');
		$this -> load -> helper('form_sisdoc');
		$this -> load -> helper('url');
		$this -> load -> library('session');
		
		date_default_timezone_set('America/Sao_Paulo');
	}
	
	function cab() {
		$this->load->model('users');
		$data = array();
		$data['title'] = 'Brapci: Artigo';
		$data['title_page'] = 'Artigo';
		$this -> load -> view("header/cab", $data);
		$data['title'] = '';
	}	

	function index() {
		global $dd;
		$this->load->view("brapci/article");
	}
	
	function view($id, $check, $status = '') {
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

		$data['title'] = 'Brapci : Admin';
		$data['title_page'] = 'ADMIN';
		$this->cab();

		/* Article */
		$this -> load -> model('articles');
		$this -> load -> model('keywords');
		$this -> load -> model('authors');
		$this -> load -> model('archives');
		$this -> load -> model('cited');
		$this -> load -> model('tools/tools');
		$this -> load -> model('metodologias');

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

		$id_art = $data['ar_codigo'];
		$data['metodologias'] = '<div id="metodos" class="border1">'.$this -> metodologias -> mostra($id_art).'</div>';
		$data['metodologias'] .= $this-> metodologias -> metodos_incluir($id_art);
		
		/* article - parte I */
		$data['tab_descript'] = $this -> load -> view('admin/article_view_tt', $data, true); 
		$data['tab_marc21'] = $this -> load -> view('admin/article_view_marc21', $data, true); 
		$data['tab_editar'] = $this->articles->editar($id);
		$data['tab_refer'] = $this -> load -> view('admin/article_view_refer', $data, true);

		$this -> load -> view('article/article_view', $data);
		
		//print_r($data);
		//exit;
		
		$data['content'] = '</table><br><br>';
		$this->load->view('content',$data);
		$this->load->view('header/foot_admin',$data);

	}
	function view_old($id='',$chk='') {
		global $dd;
		
		$this->load->model("metodologia");
		
		$user_nivel = $this -> session -> userdata('nivel');
				
		form_sisdoc_getpost();
	
		$this->session->userdata('search');
		
		$this -> load -> view("header/cab");

		$this -> load -> view("brapci/content");
		$this->load->model("articles");
		$data = $this->articles->le($id);
		$data['user_nivel'] = $user_nivel;
		
		$metodologia = $this->metodologia->le($id);
		$data['metodologia'] = $this->metodologia->mostra($metodologia);
		
		$this->load->view("brapci/article",$data);
		$this->load->view("header/foot",$data);
	}
}
?>
		