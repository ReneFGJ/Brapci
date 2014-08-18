<?php
require("db.php");
require($include."sisdoc_data.php");
require($include."sisdoc_cookie.php");
///////////////// Contator de visitas
$jid=1;
require("cab_php.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
	<title><?=$site_title;?></title>
	<link rel="STYLESHEET" type="text/css" href="css/letras_login.css">
</head>
<body><CENTER>
<div id="bar_titulo"><img src="<?php echo $logo_img;?>" alt="" border="0"><br>versão <?php echo $versao;?></div>
<div id="menu"><?php require("menu_top.php");?></div>
<div id="tudo"><div id="tudo_miolo">
