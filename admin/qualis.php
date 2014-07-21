<?php
require("cab.php");

/*
 * $breadcrumbs
 */
$breadcrumbs = array();
array_push($breadcrumbs,array(http.'admin/index.php',msg('principal')));
array_push($breadcrumbs,array(http.'admin/index.php',msg('menu')));
echo '<div id="breadcrumbs">'.breadcrumbs().'</div>';

require($include."sisdoc_menus.php");
$estilo_admin = 'style="width: 200; height: 30; background-color: #EEE8AA; font: 13 Verdana, Geneva, Arial, Helvetica, sans-serif;"';

$menu = array();
/////////////////////////////////////////////////// MANAGERS
array_push($menu,array('WebQualis','Qualis','cited_journal.php'));
array_push($menu,array('WebQualis','Qualis - Teste Import','cited_journal_test.php'));
array_push($menu,array('WebQualis','Qualis - Importar Arquivos CSV','qualis_inport_csv.php')); 

$tela = menus($menu,"3");

require("../foot.php");	
?>