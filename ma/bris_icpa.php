<?php
require("cab.php");
echo '<h3>iCPA</h3>';
echo '<font class="lt1">Índice de concentração de produção do autor</font>';

$menu = array();
array_push($menu,array('iCPA','Visualizar resultados','bris_icpa_ver.php'));
array_push($menu,array('iCPA','Cálcular Índice','bris_icpa_calc.php'));
require($include.'sisdoc_menus.php');

echo menus($menu,3);

require("../foot.php");
?>
