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

$tabela = "tci_keyword";
$idcp = "kw";
$label = $titu[0];
//$http_ver = 'editora.php'; 
$http_edit = 'brapci_ed_edit.php'; 
$http_edit_para = '&dd99='.$tabela; 
$http_ver = 'tci_conceito.php'; 
$editar = true;
$http_redirect = 'brapci_'.$tabela.'.php';

$tabela2 = "(select t1.id_kw as id_kw, t1.kw_word as kw_word, t1.kw_idioma, t2.kw_word as kw_word_use ";
$tabela2 .= "from ".$tabela." as t1 ";
$tabela2 .= "inner join ".$tabela." as t2 on t1.kw_use = t2.kw_codigo) as tabela ";
$tabela = $tabela2;

$cdf = array('id_'.$idcp,$idcp.'_word',$idcp.'_idioma',$idcp.'_word_use');
$cdm = array('Código','Nome','idioma','use');
$masc = array('','','','','','','','','','','');
$busca = true;
$offset = 20;
//$pre_where = " (".$idcp."_ativo = 1) ";
$order  = $idcp."_word ";
//exit;
echo '<TABLE width="'.$tab_max.'" align="center"><TR><TD>';
require($include.'sisdoc_row.php');	
echo '</table>';
?></DIV>