<?php

/* Conecta com os parametros do sistema */
	require("db.php");

/* Habilita modo debug */
	require($include.'sisdoc_debug.php');

/* Ativa mensagens nos varios idiomas */
	require("_class/_class_message.php");
	//$LANG = $lg->language_read();
	$LANG = 'pt_BR';
	$file = 'messages/msg_'.$LANG.'.php';
	if (file_exists($file)) { require($file); } else { echo 'message not found '.$file; }

/* Carrega Classe */
	require('_class/_class_face.php');
	$face = new face;

/* Class de login do google */
	require("_class/_class_google_api.php");
	$google->google_analytics_id = 'UA-12803182-4';
 
/* Cabecalho */
	require("_class/_class_header_bp.php");
	$hd = new header;
	echo $hd->cab();

$menu_google = '
	<div id="menu14" style="display: none;">
	<img src="img/logo_brapci_face.png" id="logo">
	<ul>
	<li><a id="current" href="index.php" title="Home Page">Home</a></li>
	<li><a  href="/menu.php?dd1=0" title="Sobre a base">Sobre a base</a></li>
	<li><a  href="/menu.php?dd1=1" title="Corpus da base">Corpus da Base</a></li>
	<li><a  href="/menu.php?dd1=2" title="Citation Reports">Relatório de Citações</a></li>
	<li><a  href="/menu.php?dd1=3" title="Índice de palavras chave">Índicde de palavras-chave</a></li>
	<li><a  href="/menu.php?dd1=4" title="Índice de autores">Índice de autores</a></li>
	<li><a  href="/menu.php?dd1=5" title="Map Generator">Ajuda</a></li>
	</ul></div>
	';
	echo utf8_encode($menu_google);
?>

			<div class="cab">
				<div class="menu_left">
        				<UL class="nav_menu">
        					<LI><a href="<?=$http;?>/menu.php"><img src="img/icone_menu.png" border=0 height="20" title="main menu"></LI>
        				</UL>
				</div>
				<div class="geral">
					<div id="div1">&nbsp;&nbsp;<a href="index.php?idioma=pt_BR"><img src="img/ididoma_br.png" border=0 title="PortuguÃªs" alt="PortuguÃªs"></A>
					| <?=$face->userID(); ?>
					<?
					if (strlen($user_name) > 0)
						{
							echo $user_name.' ('.$user_email.')';
							echo '<BR><I>'.$link_logout.'</I>';
						} else {
							echo $link_login;
						} 
					?>
					
					<BR><BR>
					</div>											   
		
					<div class="topo"></div>
    			</div>
    		</div>

<?php
echo '<center>';

 /**
 * Carrega cabecalho da versao do sistema
 */
require("cab_versao.php");

require("cab_menu.php");


echo '<DIV ID="content_TOP">';
?>
</div>
<BR>
<DIV id="conteudo">
