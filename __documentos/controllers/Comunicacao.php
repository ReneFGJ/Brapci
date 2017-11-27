<?php
class comunicacao extends CI_Controller {

	function __construct() {
		global $dd, $acao;
		parent::__construct();
		$this -> load -> library('form_validation');
		$this -> load -> database();
		//		$this -> lang -> load("app", "portuguese");
		//		$this -> lang -> load("ic", "portuguese");

		$this -> load -> helper('form');
		$this -> load -> helper('form_sisdoc');
		$this -> load -> helper('email');
		$this -> load -> helper('url');
		$this -> load -> library('session');

		date_default_timezone_set('America/Sao_Paulo');

	}

	function security() {
		/* Seguranca */
		$this -> load -> model('usuarios');
		$this -> usuarios -> security();
	}

	function cab($data = array()) {
		$js = array();
		$css = array();
		array_push($js, 'form_sisdoc.js');
		array_push($js, 'jquery-ui.min.js');

		$data = array();
		$data['js'] = $js;
		$data['css'] = $css;

		$data['title'] = ':: sisDOC :: phpProjectStart';
		$this -> load -> view('header/header', $data);
		$this -> load -> view('menus/menu_cab_top', $data);

//		$this -> load -> model('users');
//		$this -> users -> security();
	}

	function comunicacao_edit($id = 0, $gr = 0, $tp = 0) {
		/* Load Models */
		$this -> load -> model('comunicacoes');
		$cp = $this -> comunicacoes -> cp();

		$this -> cab();
		$data = array();

		$form = new form;
		$form -> id = $id;

		$tela = $form -> editar($cp, $this -> comunicacoes -> tabela);
		$data['title'] = msg('eq_equipamento_title');
		$data['content'] = $tela;

		/* Salva */
		if ($form -> saved > 0) {
			redirect(base_url('index.php/comunicacao/comunicacao'));
		}

		$this -> load -> view('content', $data);

	}

	function comunicacao_view($id = 0, $gr = 0, $tp = 0) {
		/* Load Models */
		$this -> load -> model('comunicacoes');

		$this -> cab();
		$data = array();

		$data = $this -> comunicacoes -> le($id);

		if (strlen(get("dd1")) == 0) {
			$id_gr = $data['mc_tipo'];
			$_POST['dd1'] = $this -> comunicacoes -> le_email_grupo($id_gr);
		}

		
		$head = trim($data['m_header']);
		$foot = trim($data['m_foot']);
		
		if (strlen($head) > 0)
			{
				$head = base_url($head);
			}
		if (strlen($foot) > 0)
			{
				$foot = base_url($foot);
			}

		$msg_body = $data['mc_texto'];
		if ($data['mc_formato'] == 'TEXT') { $msg_body = mst($msg_body);
		}

		$form = new form;
		$cp = array();
		array_push($cp, array('$h', '', '', False, False));
		array_push($cp, array('$T40:50', '', 'emails', True, False));
		array_push($cp, array('$B8', '', 'Enviar >>>', False, False));
		$tela = $form -> editar($cp, '');
		$data['title'] = msg('visualizar_mensagem');
		$data['tela'] = $tela;

		if ($form -> saved > 0) {
			$em = get('dd1');
			$em = troca($em, chr(13), ';');
			$em = troca($em, chr(10), '');
			$em = troca($em, chr(8), '');
			$em = troca($em, chr(15), '');
			$ems = splitx(';', $em . ';');

			for ($r = 0; $r < count($ems); $r++) {
				$para = array($ems[$r]);
				$de = $data['mc_own'];
				$assunto = utf8_decode($data['mc_titulo']);

				/* texto */
				$texto_o = utf8_decode($data['mc_texto']);
				if (trim($data['mc_formato']) == 'TEXT') { $texto_o = mst($texto_o);
				}
				$texto = '<table width="700">';
				if (strlen($head) > 0) {
					$texto .= '<tr><td><img src="' . $head . '" width="700"></td></tr>';
					$texto .= '<tr><td><br></td></tr>';
				}
				$texto .= '<tr><td>';
				$texto .= $texto_o . '<br><br></td></tr>';

				if (strlen($foot) > 0) {
					$texto .= '<tr><td><img src="' . $foot . '" width="700"></td></tr>';
				}
				$texto .= '</table>';

				/* enviar e-mail */
				enviaremail($para, $assunto, $texto, $de);
			}
			enviaremail(array('cleybe.vieira@pucpr.br'), $assunto, $texto, $de);
			//enviaremail(array('rene.gabriel@pucpr.br'), $assunto, $texto, $de);
		}

		$data['content'] = '<table width="100%">'.cr();
	
	$data['content'] .= '<tr valign="top"><td><table width="700" align="center" class="border1">'.cr();
	
	if (strlen(trim($head)) > 0) { $data['content'] .= '<tr><td><img src="' . $head . '" width="700"></td></tr>';
		}
	
	$data['content'] .= '
	<tr><td><br>' . $msg_body . '</td></tr>
	<tr><td><br><br><br></td></tr>';
		if (strlen(trim($foot)) > 0) { $data['content'] .= '<tr><td><img src="' . $foot . '" width="700"></td></tr>';
		}
		$data['content'] .= '</table></td><td>' . $tela . '</td></tr></table>';

		$data['title'] = msg('visualizar_mensagem');

		$this -> load -> view('content', $data);

	}

	function comunicacao() {
		$this -> cab();
		$data = array();

		$menu = array();
		array_push($menu, array('Mensagens', 'Mensagens padrão do sistema', 'ITE', '/comunicacao/comunicacao_1'));
		array_push($menu, array('Mensagens', 'Mensagens de comunicação', 'ITE', '/comunicacao/comunicacao_2'));

		/*View principal*/
		$data['menu'] = $menu;
		$data['title_menu'] = 'Menu de Mensagens';
		$this -> load -> view('header/main_menu', $data);

	}

	function mensagens_edit($id = 0, $chk = '') {
		/* Load Models */
		$this -> load -> model('mensagens');
		$cp = $this -> mensagens -> cp();

		$this -> cab();
		$data = array();

		$form = new form;
		$form -> id = $id;

		$tela = $form -> editar($cp, $this -> mensagens -> tabela);
		$data['title'] = msg('mensagens_title');
		$data['tela'] = $tela;
		$this -> load -> view('form/form', $data);

		/* Salva */
		if ($form -> saved > 0) {
			redirect(base_url('index.php/ic/comunicacao_1'));
		}

		//$this -> load -> view('content', $data);

		$this -> load -> view('header/content_close');
		$this -> load -> view('header/foot', $data);
	}

	function comunicacao_1($id = 0, $gr = 0, $tp = 0) {
		/* Load Models */
		$this -> load -> model('comunicacoes');
		$this -> load -> model('mensagens');

		$this -> cab();
		$data = array();

		/* Lista de Mensagens do Sistema */
		$form = new form;
		$form -> tabela = $this -> mensagens -> tabela;
		$form -> see = true;
		$form -> edit = true;
		$form -> novo = true;
		$form -> order = ' nw_ref ';
		$form = $this -> mensagens -> row($form);

		$form -> row_edit = base_url('index.php/ic/mensagens_edit');
		$form -> row_view = '';
		$form -> row = base_url('index.php/ic/comunicacao_1/');

		$data['content'] = row($form, $id);
		$data['title'] = msg('messagem_cadastradas');

		$this -> load -> view('content', $data);

		$this -> load -> view('header/content_close');
		$this -> load -> view('header/foot', $data);
	}

	function comunicacao_2($id = 0, $gr = 0, $tp = 0) {
		/* Load Models */
		$this -> load -> model('comunicacoes');

		$this -> cab();
		$data = array();

		/* Lista de comunicacoes anteriores */
		$form = new form;
		$form -> tabela = $this -> comunicacoes -> tabela_view();
		$form -> see = true;
		$form -> edit = true;
		$form -> novo = true;
		$form -> order = ' id_mc desc ';
		$form = $this -> comunicacoes -> row($form);

		$form -> row_edit = base_url('index.php/comunicacao/comunicacao_edit');
		$form -> row_view = base_url('index.php/comunicacao/comunicacao_view');
		$form -> row = base_url('index.php/ic/comunicacao_2/');

		$data['content'] = row($form, $id);
		$data['title'] = msg('comunicacoes_cadastradas');

		$this -> load -> view('content', $data);

	}

	function index($id = 0) {
		/* Load Models */
		$this -> cab();
		$data = array();

	}

}
?>