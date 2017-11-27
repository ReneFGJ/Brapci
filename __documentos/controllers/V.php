<?php
class v extends CI_controller {
	function __construct() {
		global $db_public;

		$db_public = 'brapci_publico.';
		parent::__construct();
		$this -> lang -> load("app", "portuguese");
		$this -> load -> library('form_validation');
		$this -> load -> database();
		$this -> load -> helper('form');
		$this -> load -> helper('form_sisdoc');
		$this -> load -> helper('email');
		$this -> load -> helper('url');
		$this -> load -> library('session');

		date_default_timezone_set('America/Sao_Paulo');
	}

	function cab($data=array()) {
		$this -> load -> model('users');
		if (isset($data['title']))
			{
				$data['title_page'] = $data['title'];
			}
		$this -> load -> view("header/cab", $data);
		$data['title'] = '';
	}

	function index() {
		global $dd;
		$this -> load -> view("brapci/article");
	}
	
	function t($id = '')
		{
			$this -> load -> model('keywords');
			$this->cab();
			
			$data = $this->keywords->le($id);
			$this->load->view('keyword/view',$data);
			
		}

	function a($id = '') {
		redirect('http://www.brapci.inf.br/index.php/v/a/'.$id.'/'.$check.'/'.$status);
		exit;		
		global $dd, $acao;
		$status = '';
		$this -> load -> model('Search');
		$this -> Search -> registra_visualizacao($id);

		/* Article */
		$this -> load -> model('articles');
		$this -> load -> model('keywords');
		$this -> load -> model('authors');
		$this -> load -> model('archives');
		$this -> load -> model('cites');
		$this -> load -> model('tools/tools');
		$this -> load -> model('metodologias');

		$data = $this -> articles -> le($id);

		/* Alterar Status */
		if (strlen($status) > 0) {
			$sql = "update brapci_article set ar_status = '$status' where id_ar = " . $id;
			$this -> db -> query($sql);
		}

		$data['title'] = $data['ar_titulo_1'];
		$data['title_page'] = 'ADMIN';
		$data['metadata'] = $this->load->view('article/article_metadata',$data,true);
		$this -> cab($data);


		$data['archives'] = $this -> archives -> show_files($id);
		$data['citeds'] = $this -> cites -> show_cited($id);

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
		$data['metodologias'] = '<div id="metodos" class="border1">' . $this -> metodologias -> mostra($id_art) . '</div>';
		$data['metodologias'] .= $this -> metodologias -> metodos_incluir($id_art);

		/* article - parte I */
		$data['$tab_pdf'] = '';
		$data['tab_descript'] = $this -> load -> view('admin/article_view_tt', $data, true);
		$links = $data['links'];

		$ll = '';
		for ($r = 0; $r < count($links); $r++) {
			$liz = $links[$r];
			$id = $liz['id_bs'];
			$tp = $liz['bs_type'];
			$lk = $liz['bs_adress'];
			switch ($tp) {
				case 'PDF' :
					if (substr($lk, 0, 4) == 'http') {
						$ll .= $this -> load -> view('suport/pdf.php', $liz, true);
					}
					if (substr($lk, 0, 1) == '_') {
						if (file_exists($lk)) {
							$url = base_url('index.php/article/download_view/' . $id);
							$data['tab_pdf'] = '<iframe src="' . $url . '" class="iframe_pdf"></iframe>';

							$data['link_pdf'] = base_url('index.php/article/download/' . $id);
						} else {
							$data['tab_pdf'] = '<div class="alert alert-danger" role="alert">File not found!</div>';
						}
					}
					break;
				case '' :
					break;
			}
		}
		$data['linkss'] = $ll;

		$data['tab_marc21'] = $this -> load -> view('admin/article_view_marc21', $data, true);
		//$data['tab_marc21'] = '';
		$data['tab_editar'] = '';
		$data['tab_refer'] = $this -> load -> view('admin/article_view_refer', $data, true);
        $data['tab_rdf'] = 'em construção';
        $data['link_rdf'] = 'em construção';
		$this -> load -> view('article/article_view', $data);


		$this -> load -> view('header/foot', $data);
	}

}
?>
