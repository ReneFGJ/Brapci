<?php
$include = '../';
require("../db.php");
require($include.'sisdoc_data.php');
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_windows.php');
require($include.'sisdoc_debug.php');
require($include.'sisdoc_breadcrumb.php');

/* Seguranï¿½a do Login */
require($include.'sisdoc_security_pucpr.php');
$nw = new usuario;
$sec = $nw->Security();

require("../_class/_class_message.php");
require("../_class/_class_user_perfil.php");
$perfil = new user_perfil; 

require("../cab_institucional.php");

/* Messages */
$file = '../messages/msg_pt_BR.php';
require($file);
$menu = array();
array_push($menu,array('docentes','docentes_menu.php'));
array_push($menu,array('lattes','cnpq.php'));
array_push($menu,array('captacao','captacao.php'));
array_push($menu,array('qualis','qualis.php'));
array_push($menu,array('usuários','usuarios.php'));
array_push($menu,array('discente','discente.php'));
array_push($menu,array('manutencao','manutencao.php'));
array_push($menu,array('manutencao pibic','manutencao_pibic.php'));

require('../_class/_class_header.php');
$hd = new header;
echo $hd->mostra('admin');
?>
<center>
