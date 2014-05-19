<?
$breadcrumbs=array();
array_push($breadcrumbs, array('pos_graduacao.php','Pós-graduação'));

require("cab_cip.php");

require('../_class/_class_programa_pos.php');
$pos = new programa_pos;

echo '<h3>Tabela dos programas de Pós-Graduação</h3>';

require("../db_diris.php");
echo $pos->programas();

require("../foot.php");	?>