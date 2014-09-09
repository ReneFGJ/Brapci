<?php
require("cab.php");
require($include."sisdoc_colunas.php");
$include = '../include/';
require($include."_class_menus.php");

require("../_class/_class_search.php");

require("../_class/_class_resume.php");
$res = new resume;

require("../db_diris.php");
require("../_class/_class_diris.php");
$diris = new diris;

require("../_class/_class_programa_pos.php");
$pos = new programa_pos;

echo '<h1>DIR!S</h1>';
echo '<h3>Authors</h3>';

$diris->le($dd[0]);
echo $diris->mostra();

//echo $diris->mostra_producao();
$nome = $diris->line['dis_nome'];
$codigo = $diris->line['dis_codigo'];

echo $pos->mostra_docente_programa($codigo);

echo $diris->mostra_producao_docente($codigo);

require("../foot.php");
?>
