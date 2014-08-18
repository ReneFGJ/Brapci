<?
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('seções','brapci_brapci_section.php'));
////////////////////////////

require("cab.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_debug.php");
$titu[0] = 'Cadastro de seções';
?><div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<BR><?

$tabela = "brapci_section";
$idcp = "se";
$label = "Cadastro de seções";
//$http_ver = 'journal_sel.php'; 
$http_edit = 'brapci_ed_edit.php'; 
$http_edit_para = '&dd99='.$tabela; 
$editar = true;
$http_redirect = 'brapci_'.$tabela.'.php';

$cdf = array('id_'.$idcp,$idcp.'_descricao',$idcp.'_ativo',$idcp.'_codigo',$idcp.'_cod',$idcp.'_tipo');
$cdm = array('Código','Nome','Ativo','codigo','grupo');
$masc = array('','','SN','@','#','');
$busca = true;
$offset = 50;
$order  = $idcp."_descricao ";
//exit;
echo '<TABLE width="'.$tab_max.'" align="center"><TR><TD>';
require($include.'sisdoc_row.php');	
echo '</table>';
echo '* somente o grupo "B" é considerado artigo científico e será utilizado para exportação';
?></DIV>