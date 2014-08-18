<?
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('revistas','brapci_brapci_journal.php'));
////////////////////////////

require("cab.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_debug.php");

//// recupera periódico ativo

$titu[0] = 'Referências';
?>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<BR><?

$tabela = "mar_works";
$idcp = "m";
$label = "Lista de Referências";
$http_ver = 'brapci_mar_journal_busca.php'; 
$http_edit = 'brapci_ed_edit.php'; 
$http_edit_para = '&dd99='.$tabela; 
$editar = true;
$http_redirect = 'mar_marcacao_ref_ii.php';

$cdf = array('id_'.$idcp,$idcp.'_ano',$idcp.'_ref',$idcp.'_bdoi',$idcp.'_status','m_tipo');
$cdm = array('Código','Ano','Ref','DOI','Tipo');
$masc = array('','','','','','','','','','','');
$busca = true;
$offset = 20;
$order  = $idcp."_ano desc, m_ref  ";
//exit;
$tab_max = '100%';
echo '<TABLE width="100%" align="center"><TR><TD>';
require($include.'sisdoc_row.php');	
echo '</table>';
?></DIV>