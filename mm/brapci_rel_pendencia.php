<?
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('pendências','brapci_rel_pendencia.php'));
////////////////////////////

require("cab.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_debug.php");
$titu[0] = 'Relatório de pendências';
?>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<BR><?

$tabela = "brapci_article";
$idcp = "ar";
$label = "Trabalhos com pendências";
$http_ver = 'brapci_article_select.php'; 
//$http_edit = 'brapci_rel_pendencia.php'; 
//$http_edit_para = '&dd99='.$tabela; 
$editar = true;
$http_redirect = 'brapci_rel_pendencia.php';

$cdf = array('id_'.$idcp,'ar_titulo_1','at_obs_data');
$cdm = array('Código','Nome','Data');
$masc = array('','','D','','','','','','','','');
$busca = true;
$offset = 20;
$pre_where = " (".$idcp."_status <> 'X' ) ";
$order  = " at_obs_data desc ";
$where = " at_obs <> '' ";
//exit;
echo '<TABLE width="'.$tab_max.'" align="center"><TR><TD>';
require($include.'sisdoc_row.php');	
echo '</table>';
?></DIV>