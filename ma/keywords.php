<?php
require("cab.php");

$menu = array();
array_push($menu,array('Palavras-Chave','Lista de descritores','keywords_row.php'));
array_push($menu,array('Palavras-Chave','__Não catalogados','keywords_row_N.php'));
array_push($menu,array('Palavras-Chave','__Geográficos','keywords_row_G.php'));
array_push($menu,array('Palavras-Chave','__Autoridades','keywords_row_A.php'));
array_push($menu,array('Palavras-Chave','__Datas','keywords_row_D.php'));
array_push($menu,array('Palavras-Chave','__Descritor','keywords_row_T.php'));
array_push($menu,array('Palavras-Chave','__Dudiva','keywords_row_W.php'));

array_push($menu,array('Palavras-Chave - Gerenciamento','Descritores duplicados','keywords_duplicate.php'));
array_push($menu,array('Palavras-Chave - Gerenciamento','Descritores não utilizados','keywords_not_use.php'));

require($include.'sisdoc_menus.php');

echo menus($menu,3);
?>
