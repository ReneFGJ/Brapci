<?php
header('Content-type: text/html; charset=ISO-8859-1');
$include = '../';
require("../db.php");
require($include.'sisdoc_debug.php');
//require("../_class/_class_message.php");
//$LANG = $lg->language_read();
$LANG = 'pt_BR';

$file = '../messages/msg_'.$LANG.'.php';
if (file_exists($file)) { require($file); } else { echo 'message not found '.$file; }
/*
 * 	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
 * <meta charset="utf-8" />
 */
?>
<head>
	<title>::Brapci - Base Referencial de Periodicos em Ciência da Informacao::</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<link rel="STYLESHEET" type="text/css" href="../css/style.css">
	<link rel="STYLESHEET" type="text/css" href="../css/style_menu.css">
	<link rel="STYLESHEET" type="text/css" href="../css/style_roboto.css">
	<link rel="shortcut icon" href="http://www.brapci.inf.br/favicon.png" />
	
	<script type="text/javascript" src="../js/jquery.js"></script>
	<script type="text/javascript" src="../js/jquery.corner.js"></script>
	<script type="text/javascript" src="../js/jquery.example.js"></script>
	<script type="text/javascript" src="../js/jquery.autocomplete.js"></script>
</head>
<BODY>
<?
if ($xcab != 1)
	{
	$sx = '<script type="text/javascript">
	';
	$sx .= "
  		var _gaq = _gaq || [];
  		_gaq.push(['_setAccount', 'UA-12803182-4']);
  		_gaq.push(['_trackPageview']);

		  (function() {
    		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  		})();
	</script>
	";
	
	$sx .= '
		<div class="cab">
				<div class="geral">
					<div id="div1">&nbsp;&nbsp;<a href="index.php?idioma=pt_BR"><img src="../img/ididoma_br.png" border=0 title="Português" alt="Português"></A>
					| <B>ISSN xxxx-xxxx</B>
					</div>											   
		
					<div class="topo">	
        				<a href="http://www.facebook.com/brapci" target="_blank"><div class="bt_face_puc">face</div></a>
    				</div>
    			</div>
    		</div>
	';


	$sx .= '<center>';
	$sx .= '<DIV ID="content_TOP">';
	$sx .=	'</div><BR>
		<DIV id="conteudo">
			<table width="100%"><TR align="left"><TD><A HREF="main.php">HOME</A></TD><TD> 
		</TD></TR></table>
	';
	}
echo $sx;
?>