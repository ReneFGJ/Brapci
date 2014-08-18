<?
$debug = true;
require("cab.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_debug.php");
$titu[0] = 'Cadastro de perfil de usuário';
?>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<BR><?

$tabela = "brapci_usuario_perfil";
$idcp = "perfil";
$label = "Cadastro de Perfil";
//$http_ver = 'editora.php'; 
$http_edit = 'brapci_ed_edit.php'; 
$http_edit_para = '&dd99='.$tabela; 
$editar = true;
$http_redirect = 'brapci_'.$tabela.'.php';

$cdf = array('id_'.$idcp,$idcp.'_nome',$idcp.'_codigo');
$cdm = array('Código','Nome','código');
$masc = array('','','','','','','','','','','');
$busca = true;
$offset = 20;
//	$pre_where = " (ch_ativo = 1) ";
$order  = $idcp."_nome ";
//exit;
echo '<TABLE width="'.$tab_max.'" align="center"><TR><TD>';
require($include.'sisdoc_row.php');	
echo '</table>';
?></DIV>