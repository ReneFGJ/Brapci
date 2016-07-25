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
	<title><?php echo $title; ?></title>
	<script src="<?php echo base_url('js/jquery.js'); ?>"></script>
	<?php
	if ($bootstrap == 1) { echo $this -> load -> view('header/header_bootstrap', null, true);
	}
 ?>
	<?php
	array_push($js,'jquery.mask.js');
	for ($r = 0; $r < count($js); $r++) {
		echo '	<script src="' . base_url('js/' . $js[$r]) . '"></script>' . cr();
	}
	array_push($css,'style_brapci.css');
	array_push($css,'style_roboto.css');
	array_push($css,'style_form_sisdoc.css');
	array_push($css,'jquery-ui.css');
	
	for ($r = 0; $r < count($css); $r++) {
		echo '	<link rel="stylesheet" href="' . base_url('css/' . $css[$r]) . '">' . cr();
	}
	?>
</header>