<?php
require("cab.php");
echo '<h3>Cited</h3>';

//require("cited_menu.php");

require("../_class/_class_cited.php");
$cited = new cited;

echo $cited->resumo();

$menu = array();
array_push($menu,array('Controle de autoridade','Autoridade','mar_journal.php'));
array_push($menu,array('Controle de autoridade','__Lista completa de autoridades','mar_journal_lista_completa.php'));
array_push($menu,array('Controle de autoridade','Busca Remissivas','mar_journal_remissivas.php'));

array_push($menu,array('BRIS','iCPA - Índice de concentração de produção do autor','bris_icpa.php'));
array_push($menu,array('BRIS','Fator de impacto do autor','bris_fi_autor_calc.php'));
require($include.'sisdoc_menus.php');

echo menus($menu,3);

require("../foot.php");
?>
