<?php
require("db.php");
require("_class/_class_header.php");
$hd = new header;


	require('../_class/_class_oauth_v1.php');
	$user = new oauth;
	$user->token();	
	
	if (strlen($user->name) == 0)
		{
			redirecina("../index.php");
		}

$user_name = $user->name;
$user_email = $user->email;
$logout = '<A HREF="../logout.php"><I>Sair</I></A>';
$hd->cab_html = '<div id="login">'.$user_name.'<BR>'.$logout.'</div>';

/* Mostra cabecalho */
echo $hd->head();
echo $hd->cab();
?>
<div id="content">

