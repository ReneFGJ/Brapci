<?
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('revistas','brapci_brapci_journal.php'));
////////////////////////////

require("cab.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_debug.php");
$titu[0] = 'Cadastro de usuários';
?><div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<BR><?

$tabela = "brapci_journal";
$idcp = "jnl";
$label = "Cadastro de Publicações";
$http_ver = 'journal_sel.php'; 
$http_edit = 'brapci_ed_edit.php'; 
$http_edit_para = '&dd99='.$tabela; 
$editar = true;
$http_redirect = 'brapci_'.$tabela.'.php';

$cdf = array('id_'.$idcp,$idcp.'_nome',$idcp.'_issn_impresso',$idcp.'_issn_eletronico',$idcp.'_vinc_vigente');
$cdm = array('Código','Nome','ISSN','eISSN');
$masc = array('','','','','SN','','','','','','');
$busca = true;
$offset = 50;
$pre_where = " (".$idcp."_status <> 'X') ";
if (strlen($dd[90]) > 0)
	{ $pre_where .= " and (jnl_tipo = '".$dd[90]."') "; }
	
$order  = $idcp."_nome ";
//exit;
echo '<TABLE width="'.$tab_max.'" align="center"><TR><TD>';
require($include.'sisdoc_row.php');	
echo '</table>';
?></DIV>