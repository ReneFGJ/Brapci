<?php
if (!(isset($title))) { $title = 'none';
}
if (!(isset($bootstrap))) { $bootstrap = 1;
}
if (!isset($js)) { $js = array(); }
if (!isset($css)) { $css = array(); }
?>
<header>
	<head lang="pt-br">
	<meta charset="utf-8">
	<title>Brapci: Base de Dados em Ciência da Informação</title>
	<script src="<?php echo base_url('js/jquery.js'); ?>"></script>
	<?php
	if ($bootstrap == 1) { echo $this -> load -> view('header/header_bootstrap', null, true);
	}
 ?>
	<?php
	array_push($js,'jquery.mask.js');
	array_push($js,'sisdoc_form.js');
	for ($r = 0; $r < count($js); $r++) {
		echo '	<script src="' . base_url('js/' . $js[$r]) . '"></script>' . cr();
	}
	array_push($css,'style_brapci.css');
	if (isset($nocab))
		{
			array_push($css,'style_brapci_nocab.css');		
		}
	array_push($css,'style_roboto.css');
	array_push($css,'style_menu_top.css');
	array_push($css,'style_form_sisdoc.css');
	array_push($css,'jquery-ui.css');
	
	for ($r = 0; $r < count($css); $r++) {
		echo '	<link rel="stylesheet" href="' . base_url('css/' . $css[$r]) . '">' . cr();
	}
	?>
</header>