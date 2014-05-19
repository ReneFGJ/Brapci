<?php
$include = '../';
$no_cab = 1; /* não mostrar cabecalho */
/* Estitlos adicionais */ 
$style_add = array('style_bp_ma.css','style_bp.css');

require($include.'cab.php');

/* Redireciona para o login */
if ((!isset($login)) and (strlen($user_name) == 0))
	{ redirecina('_login.php'); }

if (!($popup)) 
	{
		 require("cab_menu.php");
		 require("cab_menu_left.php"); 
	}
?>
<div class="container">

