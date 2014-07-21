<?php
if (!isset($include)) { $include = ''; }
$path_ini = $include;

/* Conecta com os parametros do sistema */
	require("db.php");
	
/* Dados do Usuario */
//	require("_class/_class_user.php");
//	$user = new user;
	
	require('_class/_class_oauth_v1.php');
	$user = new oauth;
	$user->token();	
	
/* Habilita modo debug */
	require($include.'sisdoc_debug.php');

/* Ativa mensagens nos varios idiomas */
	require("_class/_class_message.php");
	//$LANG = $lg->language_read();
	$LANG = 'pt_BR';
	$file = 'messages/msg_'.$LANG.'.php';
	if (file_exists($file)) { require($file); } 
		else {
				 $file = '../messages/msg_'.$LANG.'.php';
				 if (file_exists($file)) { require($file); }
				 else { echo 'message not found '.$file; } 
			  }

/* Carrega Classe */
	require('_class/_class_face.php');
	$face = new face;

/* Class de login do google */
//	require("_class/_class_google_api.php");
//	$google = new google_api;
//	$google->google_analytics_id = 'UA-12803182-4';
//	

/* Cabecalho */
	require("_class/_class_header_bp.php");
	$hd = new header;
	
	echo $hd->cab();
	$hd->google_id = $google->google_analytics_id;
	
	if (!isset($no_cab))
		{
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
		
		echo '
			<div class="cab">
				<div class="menu_left">
        				<UL class="nav_menu">
        					<LI><a href="'.$http.'/menu.php"><img src="'.$http.'img/icone_menu.png" border=0 height="20" title="main menu"></LI>
        				</UL>
				</div>
				<div class="geral">
					<div id="div1">&nbsp;&nbsp;<a href="index.php?idioma=pt_BR"><img src="img/ididoma_br.png" border=0 title="Portugues" alt="Português"></A>
					| ';
					if ($user->autenticado ==1)
						{
							echo $user->name.' ('.$user->email.')';
							echo '<BR><I><A href="logout.php">sair</A></I>';
						} else {
							echo '<a href="_login_s.php">login</A>';
						} 
		echo '<BR><BR></div>											   
		
					<div class="topo">
						<img src="img/icone_twitter.png">
					</div>
    			</div>
    		</div>';
		echo '<center>';

 		/**
 		* Carrega cabecalho da versao do sistema
 		*/
		require("cab_versao.php");

		require("cab_menu.php");
		echo '<DIV ID="content_TOP">';
		echo '</div><BR>
			<DIV id="conteudo">';
	}
?>