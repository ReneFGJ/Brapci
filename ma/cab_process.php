<?php
$include = '../';
$no_cab = 1; /* não mostrar cabecalho */
/* Estitlos adicionais */ 
$style_add = array('style_bp_ma.css','style_bp.css');

require($include.'cab.php');

/* Redireciona para o login */
if ((!isset($login)) and (strlen($user_name) == 0))
	{ redirecina('_login.php'); }

?>
<div>
	Cited Processing
</div>
<center>
<table class="container" align="center" width="95%" border=1>
	<TR><TD>

