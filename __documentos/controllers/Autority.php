<?php
class autority extends CI_Controller {

	function __construct() {
		parent::__construct();
		$this -> lang -> load("app", "portuguese");
		$this -> lang -> load("skos", "portuguese");
		$this -> load -> library('form_validation');
		$this -> load -> database();
		$this -> load -> helper('form');
		$this -> load -> helper('form_sisdoc');
		$this -> load -> helper('url');
		$this -> load -> library('session');

		date_default_timezone_set('America/Sao_Paulo');
		/* Security */
		//		$this -> security();
	}

	function cab_old($title = '') {
		$data = array();
		$data['title_page'] = ':: sisDOC :: SKOS';
		$this -> load -> view("header/header", $data);
		$this -> load -> view("wese/cab");
		$this -> load -> view("wese/cab_menu");
	}

	function cab_admin() {
		$data = array();
		$data['title'] = 'Brapci : Admin';
		$data['title_page'] = 'ADMIN';
		$this -> load -> view("header/cab_admin", $data);
		$this -> security();
	}

	function cab() {
		$this -> load -> view('header/cab');
		$this -> load -> view("brapci/content");
	}

	function foot() {
		/* Mostra rodape */
		$this -> load -> view("header/foot");
	}

	function security() {
		$user = $this -> session -> userdata('user');
		$email = $this -> session -> userdata('email');
		$nivel = $this -> session -> userdata('nivel');
		if (round($nivel) < 1) {
			redirect(base_url('index.php/social/login'));
		}
	}

	function cab_scheme() {
		$data = array();
		$scheme_name = $this -> session -> userdata("sh_name");
		$data = $this -> session -> userdata();
		$data['title_page'] = 'WESE: ' . $scheme_name;

		$this -> load -> view("header/header", $data);
		$this -> load -> view("wese/cab_scheme");
		$this -> load -> view("wese/cab_menu");
	}

	function index() {
		/* Load model */
		$this -> load -> model("autorities");
		$this -> cab();

		$tela = '';
		$data['content'] = '<h1>'.msg('autority_control').'</h1>';
		$this -> load -> view('content', $data);

		$data = array();
		$data['content'] = $this -> autorities -> resumo();
		$this -> load -> view('content', $data);

		$this -> load -> view('wese/search', $data);
		$term = $this -> input -> get("term");
		if (strlen($term) >= 3) {
			$tela = '<table width="800" align="center"><tr><td>';
			$tela .= $this -> autorities -> search_term($term);
			$tela .= '</td></tr></table>';
			$data['content'] = $tela;
			$this -> load -> view('content', $data);
		} else {
			$letter = get("dd0");
			$data['content'] = $this -> autorities -> alphabetic_list($letter) . '===' . $letter;
			$this -> load -> view('content', $data);

			if (strlen($letter) > 0) {
				$data['content'] = $this -> autorities -> show_terms_by_letter($letter);
				$this -> load -> view('content', $data);
			}
		}

		$this -> foot();
	}

	/***************************************************************************** term */
	function a($author = '') {
		/* Load model */
		$this -> load -> model("authors");
		$this -> load -> model("autorities");
		
		$this -> cab();

		$data = $this -> autorities -> le_c($author);
		$data['producao'] = $this-> load-> authors -> lista_obras_do_autor($author);
		$this -> load -> view('wese/author_profile', $data);
	}
	/***************************************************************************** term */
	function k($keyword = '') {
		/* Load model */
		$this -> load -> model("keywords");
		$this -> load -> model("autorities");
		
		$this -> cab();

		$data = $this -> autorities -> le_k($keyword);
		$data['producao'] = $this-> load-> keywords -> lista_obras_por_keyword($keyword);
		$this -> load -> view('wese/author_profile', $data);
	}
	function edc($idc = 0, $tp = '') {
		/* Load model */
		$this -> load -> model("skoses");
		$this -> load -> view('header/header');
		$form = new form;

		$cp = array();
		array_push($cp, array('$H8', '', '', False, True));
		array_push($cp, array('$S80', '', msg('Term'), True, True));
		array_push($cp, array('$Q id_lang:lang_name:select * from wese_language order by lang_ordem', '', msg('Language'), True, True));
		array_push($cp, array('$Q id_sh:sh_name:select * from wese_scheme order by sh_name', '', msg('sh_name'), True, True));
		array_push($cp, array('$B8', '', msg('add') . ' >>', False, True));

		$tela = $form -> editar($cp, '');

		if ($form -> saved > 0) {
			$dominio = get("dd3");
			$concept = $idc;
			$term = $this -> skoses -> insere_termo(get("dd1"), get("dd2"));
			$this -> skoses -> create_concept($term, $dominio);

			echo '
					<script>
							window.opener.location.reload();
							close(); 
					</script>
					';
		}

		$data['content'] = $tela;
		$this -> load -> view('content', $data);
	}

	function edn($idn = 0, $tp = '') {
		/* Load model */
		$this -> load -> model("skoses");
		$this -> load -> view('header/header');
		$form = new form;
		$form -> id = $idn;
		$cp = array();
		array_push($cp, array('$H8', 'id_wn', '', False, True));
		array_push($cp, array('$T80:12', 'wn_note', msg('Comment'), True, True));
		array_push($cp, array('$B8', '', msg('save') . ' >>', False, True));

		$tela = $form -> editar($cp, 'wese_note');

		if ($form -> saved > 0) {
			echo '
					<script>
							window.opener.location.reload();
							close(); 
					</script>
					';
		}

		$data['content'] = $tela;
		$this -> load -> view('content', $data);
	}

	function ed($idc = 0, $tp = '') {
		/* Load model */
		$this -> load -> model("skoses");
		$this -> load -> view('header/header');
		$form = new form;

		switch ($tp) {
			case 'ALT' :
				$cp = array();
				array_push($cp, array('$H8', '', '', False, True));
				array_push($cp, array('$S80', '', msg('Term'), True, True));
				array_push($cp, array('$Q id_lang:lang_name:select * from wese_language order by lang_ordem', '', msg('Language'), True, True));
				array_push($cp, array('$B8', '', msg('add') . ' >>', False, True));
				array_push($cp, array('$HV', '', 'ALT', True, True));
				break;
			case 'PREF' :
				$cp = array();
				array_push($cp, array('$H8', '', '', False, True));
				array_push($cp, array('$S80', '', msg('Term'), True, True));
				array_push($cp, array('$Q id_lang:lang_name:select * from wese_language order by lang_ordem', '', msg('Language'), True, True));
				array_push($cp, array('$B8', '', msg('add') . ' >>', False, True));
				array_push($cp, array('$HV', '', 'PREF', True, True));
				break;
			case 'HIDDEN' :
				$cp = array();
				array_push($cp, array('$H8', '', '', False, True));
				array_push($cp, array('$S80', '', msg('Term'), True, True));
				array_push($cp, array('$Q id_lang:lang_name:select * from wese_language order by lang_ordem', '', msg('Language'), True, True));
				array_push($cp, array('$B8', '', msg('add') . ' >>', False, True));
				array_push($cp, array('$HV', '', 'HIDDEN', True, True));
				break;
			case 'NOTE' :
				$cp = array();
				array_push($cp, array('$H8', '', '', False, True));
				array_push($cp, array('$T80:8', '', msg('Note'), True, True));
				array_push($cp, array('$B8', '', msg('add') . ' >>', False, True));
				array_push($cp, array('$HV', '', 'NOTE', True, True));
				break;
		}
		$tela = $form -> editar($cp, '');

		if ($form -> saved > 0) {
			$type = get("dd4");
			$concept = $idc;
			if (($tp == 'ALT') or ($tp == 'PREF') or ($tp == 'HIDDEN')) {
				$term = $this -> skoses -> insere_termo(get("dd1"), get("dd2"));
				$idt = $this -> skoses -> association_term($term, $concept, $type);
			}
			if (($tp == 'NOTE')) {
				$dd1 = get("dd1");
				$sql = "insert into wese_note (wn_id_c, wn_note) value ($idc,'$dd1')";
				$this -> db -> query($sql);
			}
			echo '
					<script>
							window.opener.location.reload();
							close(); 
					</script>
					';
		}

		$data['content'] = $tela;
		$this -> load -> view('content', $data);
	}

	/* Narrowed */
	function narrows_add($id = '') {
		/* Load model */
		$this -> load -> model("skoses");

		$dd1 = utf8_decode($this -> input -> post('link'));
		$txt = '/thema/';
		$dd1 = substr($dd1, strpos($dd1, $txt) + strlen($txt), strlen($dd1));
		$dd1 = troca($dd1, '/', ';');
		$it = splitx(';', $dd1);

		$c1 = $this -> skoses -> recupera_id_concept($it[1]);
		$c2 = $this -> skoses -> recupera_id_concept($it[2]);

		if (($c1 > 0) and ($c2 > 0)) {
			$this -> skoses -> narrows_link($c1, $c2);
		}
		$sx = '';
		$sx .= '
			<script>
				location.reload();
			</script>
			';
		echo $sx;
	}

	/* Concept */
	function concecpt_create() {
		$scheme = $this -> session -> userdata("scheme_id");

		/* Load model */
		$this -> load -> model("skoses");

		$id = $this -> input -> post("dd1");
		$is_active = $this -> skoses -> is_concept($id);
		$is_active = $is_active + $this -> skoses -> is_hidden($id);
		$is_active = $is_active + $this -> skoses -> is_prefterm($id);
		$is_active = $is_active + $this -> skoses -> is_altterm($id);

		if ($is_active == 0) {
			$this -> skoses -> create_concept($id, $scheme);
			redirect(base_url('index.php/skos/terms'));
		}

	}

	/* Terms */
	function terms($id = '') {
		/* Load model */
		$this -> load -> model("skoses");
		$this -> cab();

		/* Variavers */
		$is_concept = 0;

		/* Set Scheme */
		$scheme = $this -> session -> userdata("scheme_id");
		if (strlen($scheme) == 0) {
			redirect(base_url('index.php/skos/'));
		}

		/* Show list Scheme */
		$data = array();
		$data['terms'] = $this -> skoses -> terms();
		$data['terms_content'] = $this -> load -> view('wese/term_new_form', null, true);

		if (strlen($id) > 0) {
			$data['id'] = $id;
			$data['terms_content'] = $this -> skoses -> terms_link($id);
			$is_concept = $this -> skoses -> is_concept($id);
			if ($is_concept == 0) {
				$data['terms_content'] .= $this -> load -> view('wese/concept_create', $data, true);

				/* Formulario para Alternativo */
				$data['type'] = 'ALT';
				$data['terms_content'] .= $this -> load -> view('wese/term_alternative', $data, true);
				$dd5 = $this -> input -> post('dd5');
				if (strlen($dd5) > 0) {
					$concept = $dd5;
					$this -> skoses -> association_term($concept, $id, 'ALT');
					redirect(base_url('index.php/skos/terms'));
				}

				/* Formulario para Hidden */
				$data['type'] = 'HIDDEN';
				$data['terms_content'] .= $this -> load -> view('wese/term_hidden', $data, true);
				$dd5 = $this -> input -> post('dd8');
				print_r($this -> input -> post());
				if (strlen($dd5) > 0) {
					$concept = $dd5;
					$this -> skoses -> association_term($concept, $id, 'HIDDEN');
					redirect(base_url('index.php/skos/terms'));
				}
			}
		}
		$this -> load -> view('wese/term_drashboard', $data);

	}

	/* Scheme */
	function thema_edit($scheme = '', $concept = '') {
		/* Load model */

		$this -> load -> model("skoses");
		$this -> cab_scheme();

		/* Show list Terms */
		$data = array();
		$data['terms'] = $this -> skoses -> concepts($scheme, $concept);
		$data['terms'] .= '<hr>';
		$data['terms'] .= $this -> skoses -> concepts_no($scheme, $concept);
		if (strlen($concept) > 0) {
			$data['terms_content'] = $this -> skoses -> concept($concept);
		} else {
			$data['terms_content'] = '';
		}

		$this -> load -> view('wese/term_drashboard', $data);

	}

	/* Scheme */
	function thema($scheme = '', $concept = '') {
		/* Load model */

		$this -> load -> model("skoses");
		$this -> cab_scheme();

		if (strlen($concept) > 0) {
			/* Show list Terms */
			$data = array();
			$data = $this -> skoses -> le($scheme, $concept);
			$data['border'] = $this -> skoses -> broader_le($data['id_c']);
			$data['narrow'] = $this -> skoses -> narrow_le($data['id_c']);
			$data['used'] = $this -> skoses -> used_le($data['id_c']);
			$data['hidden'] = $this -> skoses -> hidden_le($data['id_c']);
			$data['scheme'] = $scheme;
			$data['concept'] = $concept;

			$this -> load -> view('wese/term_skos', $data);
		} else {
			$data = array();
			$data['terms'] = $this -> skoses -> concepts($scheme, $concept);
			$data['terms'] .= '<hr>';
			$data['terms'] .= $this -> skoses -> concepts_no($scheme, $concept);
			$data['terms_content'] = '';
			$this -> load -> view('wese/term_drashboard', $data);
		}

	}

	function scheme($scheme = '') {
		/* Load model */

		$this -> load -> model("skoses");
		$this -> cab();

		/* Set Scheme */
		if (strlen($scheme) > 0) {
			if ($this -> skoses -> scheme_set($scheme) == 1) {
				$tema = $this -> session -> userdata('sh_initials');
				redirect(base_url('index.php/skos/thema/' . $tema));
			}
		}

		/* Show list Scheme */
		$data = array();
		$data['content'] = $this -> skoses -> schemes();
		$this -> load -> view('content', $data);

	}

}
?>