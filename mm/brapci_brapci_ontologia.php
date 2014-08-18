<?
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('ontologia','brapci_brapci_ontologia.php'));
////////////////////////////

require("cab.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_debug.php");
$titu[0] = 'Ontologias';
?>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<BR><?

$tabela = "tci_conceito_relacionamento";
$idcp = "crt";
$label = $titu[0];
//$http_ver = 'editora.php'; 
$http_edit = ''; 
$http_edit_para = ''; 
$http_ver = 'brapci_brapci_ontologia_ver.php'; 
$editar = false;
$http_redirect = 'brapci_'.$tabela.'.php';

$tabela2 = "(select * from tci_conceito_relacionamento inner join tci_conceito on crt_conceito = cnt_codigo inner join tci_thema on crt_tema = tci_codigo) as tci_conceito ";
$tabela = $tabela2;

$cdf = array('id_'.$idcp,'cnt_descricao','tci_descricao');
$cdm = array('Código','Nome','idioma','use');
$masc = array('','','','','','','','','','','');
$busca = true;
$offset = 20;
$pre_where = " (crt_rela = 'PD') ";
$order  ='cnt_descricao';
//exit;
echo '<TABLE width="'.$tab_max.'" align="center"><TR><TD>';
require($include.'sisdoc_row.php');	
echo '</table>';
?></DIV>