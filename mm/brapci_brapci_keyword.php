<?
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('palavras-chave','brapci_brapci_keyword.php'));
////////////////////////////

require("cab.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_debug.php");
$titu[0] = 'Cadastro de palavras chaves';
?>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<BR><?

$tabela = "brapci_keyword";
$idcp = "kw";
$label = $titu[0];
//$http_ver = 'editora.php'; 
$http_edit = 'brapci_ed_edit.php'; 
$http_edit_para = '&dd99='.$tabela; 
$http_ver = 'brapci_brapci_article_keyword.php'; 
$editar = true;
$http_redirect = 'brapci_'.$tabela.'.php';


$cdf = array('id_'.$idcp,$idcp.'_word',$idcp.'_idioma',$idcp.'_tipo',$idcp.'_hidden',$idcp.'_codigo',$idcp.'_use');
$cdm = array('Código','Nome','idioma','tipo','oculto','codigo','use');
$masc = array('','','','','SN');
$busca = true;
$offset = 50;
//$pre_where = " (".$idcp."_ativo = 1) ";
$order  = $idcp."_word ";
//exit;
echo '<TABLE width="'.$tab_max.'" align="center"><TR><TD>';
require($include.'sisdoc_row.php');	
echo '</table>';
?></DIV>