<?
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('metodologias','brapci_brapci_metodologias.php'));
////////////////////////////

require("cab.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_debug.php");
$titu[0] = 'Cadastro de metodologias / técnicas';
?>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<BR><?

$tabela = "brapci_metodologias_tp";
$idcp = "bmtf";
$label = $titu[0];
//$http_ver = 'editora.php'; 
$http_edit = 'brapci_ed_edit.php'; 
$http_edit_para = '&dd99='.$tabela; 
$editar = true;
$http_redirect = 'brapci_'.$tabela.'.php';


$cdf = array('id_'.$idcp,$idcp.'_descricao',$idcp.'_tipo',$idcp.'_codigo');
$cdm = array('Código','Nome','código');
$masc = array('','','','','','','','','','','');
$busca = true;
$offset = 20;
$pre_where = "";
$order  = $idcp."_tipo, ".$idcp."_descricao ";
//exit;
echo '<TABLE width="'.$tab_max.'" align="center"><TR><TD>';
require($include.'sisdoc_row.php');	
echo '</table>';
?></DIV>