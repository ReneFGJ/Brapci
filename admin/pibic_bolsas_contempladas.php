<?php
require("cab.php");
require($include.'sisdoc_email.php');
require($include.'sisdoc_debug.php');

require("../pibicpr/realce.php");
/*
 * $breadcrumbs
 */
$breadcrumbs = array();
array_push($breadcrumbs,array(http.'admin/index.php',msg('principal')));
array_push($breadcrumbs,array(http.'admin/index.php',msg('menu')));
echo '<div id="breadcrumbs">'.breadcrumbs().'</div>';

require("../_class/_class_pibic_bolsa_contempladas.php");
$pb = new pibic_bolsa_contempladas;

require("../_class/_class_pibic_mirror.php");
$mr = new mirror;

require("../_class/_class_pibic_historico.php");
$ph = new pibic_historico;

require("../_class/_class_parecer_pibic.php");
$pp = new parecer_pibic;

$idx = $dd[0];
$pb->le($dd[0]);
echo $pb->mostar_dados();
$proto = $pb->pb_protocolo;

require("../pibic/_ged_config.php");
echo '<fieldset><legend>Arquivos</legend>';

$ged->protocol = trim($pb->pb_protocolo_mae);
$tela = $ged->filelist();
if ($ged->total_file > 0)
	{ echo $tela; }

$ged->protocol = $proto;
echo $ged->filelist();
echo '</fieldset>';

echo $pp->avaliadores($proto);
echo '<HR>';
//echo $mr->mostra_espelho($proto);
//echo '<HR>';
echo $ph->mostra_historico($proto);

echo $pb->acoes();

require("../foot.php");	
?>