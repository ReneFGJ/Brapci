<?php
require("db.php");
require("_class/_class_header.php");
$hd = new header;


	require('../_class/_class_oauth_v1.php');
	$user = new oauth;
	$user->token();	
	
	if (strlen($user->name) == 0)
		{
			//redirecina("../index.php");
		}

$user_name = $user->name;
$user_email = $user->email;
$logout = '<A HREF="../logout.php"><I>Sair</I></A>';

$head = '<table width="100%" border=1 ><TR><TD>';
$head .= $user_name.'<BR>'.$logout;
$head .= '</table>';

$head = '
    <div class="container clearfix">
        <div id="logo_top" class="logo">
        </div>
        <nav>
            <a href="index.php">Home</a>
        </nav>
    </div>
';


$hd->cab_html = $head;

/* Mostra cabecalho */
echo $hd->head();
echo $hd->cab();

echo '
<script src="../js/header_rezise.js"></script>';

?>
<div id="content">

