<?php
require("cab.php");
$include = '../include/';
require($include."_class_menus.php");
require("../_class/_class_resume.php");
$res = new resume;

require("main_menu.php");
//echo $res->resume();

/* MENU */
$menu = array();

array_push($menu,array(msg('DIR!S'),msg('pos_graduacao'),'programa_pos.php'));
array_push($menu,array(msg('DIR!S'),'__'.msg('programas_pos'),'pos_graduacao_relatorio.php'));
array_push($menu,array(msg('DIR!S'),'__'.msg('programas_pos_linha'),'programa_pos_linhas.php'));
array_push($menu,array(msg('DIR!S'),msg('autores_diris'),'diris_autores.php'));
array_push($menu,array(msg('DIR!S'),'Diret�rio de pesquisadores (p�s-gradua��o)','diris_authors_pos.php'));

array_push($menu,array(msg('Autoridades'),'Autores','autores_autoridades.php'));
array_push($menu,array(msg('Autoridades'),'__Autores<BR>(Trocar remissivas)','autores_autoridades_remissavas.php'));

array_push($menu,array(msg('vocabulario'),msg('controle_vocabulario'),'words.php'));

array_push($menu,array(msg('cited'),msg('citacoes'),'cited.php'));
array_push($menu,array(msg('cited'),msg('artigos_sem_referencias'),'cited_artigos_sem_referencias.php'));
array_push($menu,array(msg('cited'),'__'.msg('referencias_processar'),'cited_process.php'));
array_push($menu,array(msg('cited'),'__'.msg('referencias_processar_I'),'cited_process_01.php'));
array_push($menu,array(msg('cited'),'__'.msg('referencias_processar_II'),'cited_process_02.php'));


/* Message */
array_push($menu,array(msg('message'),msg('message'),'message.php'));
array_push($menu,array(msg('message'),msg('message_update'),'message_create.php'));

array_push($menu,array(msg('manutention'),msg('type_publications'),'publications_type.php'));
array_push($menu,array(msg('manutention'),msg('oai'),'oai.php'));
menus($menu,'3');

require("../foot.php");
?>
