<?
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('cidade','brapci_ajax_cidade.php'));
array_push($mnpos,array('estado','brapci_ajax_estado.php'));
array_push($mnpos,array('pa�s','brapci_ajax_pais.php'));
////////////////////////////

require("cab.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_debug.php");
$titu[0] = 'Cadastro de cidades / estado / pais';
?><div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<BR>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<BR><?

$tabela = "ajax_idioma";
$idcp = "ido";
$label = $titu[0];
//$http_ver = 'editora.php'; 
$http_edit = 'brapci_ed_edit.php'; 
$http_edit_para = '&dd99='.$tabela; 
$editar = true;
$http_redirect = 'brapci_'.$tabela.'.php';


$cdf = array('id_'.$idcp,$idcp.'_descricao',$idcp.'_codigo');
$cdm = array('C�digo','Nome','c�digo');
$masc = array('','','','','','','','','','','');
$busca = true;
$offset = 20;
//$pre_where = " (".$idcp."_ativo = 1) ";
$order  = $idcp."_descricao ";
//exit;
echo '<TABLE width="'.$tab_max.'" align="center"><TR><TD>';
require($include.'sisdoc_row.php');	
echo '</table>';
?></DIV>