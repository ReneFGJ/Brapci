<?php
require("db.php");
require($include."sisdoc_data.php");
require($include."sisdoc_cookie.php");
require($include."sisdoc_security.php");

$idioma = 'pt_BR';
$ed_editar = 1;
security();
///////////////// Contator de visitas
$jid=1;
$jidsel = read_cookie("journal_sel");
$jedsel = read_cookie("journal_ed");
$user_perfil = $user_nivel;
if ($user_nivel == 1) { $user_perfil = "Coordenador"; }
if ($user_nivel == 2) { $user_perfil = "Indexador"; }

//// recupera periódico ativo
$jid = read_cookie("jid");
$jid_nome = read_cookie("jid_name");
/////////////////////////////////////
require("cab_php.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title><?=$site_title;?></title>
	<link rel="STYLESHEET" type="text/css" href="css/letras.css">
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
</head>
<body>
<CENTER>
<div id="toppagina"><table width="100%" cellpadding="0" cellspacing="0" bgcolor="#ffffff">
<TR><TD width="280"><img src="<?=$logo_img;?>" alt="60" >
<TD><?=$empresa_01;?><BR><font class="lt1">Módulo Manutenção <?=$versao;?><BR>
<I><?=$user_nome;?></I> (<?=$user_perfil;?>)</font></TD></TR></table></div>

<div id="nada"><img src="img/nada.gif" border=0></div>

<? require("cab_position.php"); ?>
<div id="nada"><img src="img/nada.gif" border=0></div>
<div id="conteudo">
<CENTER><TABLE align="center" width="100%" border=0>
<TR valign="top">
<TD width="150">
<? require("menu_left.php"); ?>
</TD>
<TD width="20">&nbsp;&nbsp;</TD>
<TD width="90%">