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
$jid = read_cookie("jid");
$jid_nome = read_cookie("jid_name");

$titu[0] = 'Edições da Publicação - '.$jid_nome;
?>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<BR><?

$tabela = "(select lpad(ed_nr,3,0) as pad, id_ed,ed_ano,ed_vol,ed_periodo,ed_nr,ed_tematica_titulo,ed_artigo_total,ed_artigo_processado,ed_journal_id from brapci_edition) as tabela ";
$idcp = "ed";
$label = "Cadastro de Edições de Publicações";
$http_ver = 'brapci_brapci_journal_edition.php'; 
$http_ver = 'journal_ed_sel.php';
$http_edit = 'brapci_ed_edit.php'; 
$http_edit_para = '&dd99=brapci_edition'; 
$editar = true;
$http_redirect = 'brapci_'.$tabela.'.php';

$cdf = array('id_'.$idcp,$idcp.'_ano',$idcp.'_vol',$idcp.'_periodo',$idcp.'_nr',$idcp.'_tematica_titulo',$idcp.'_artigo_total',$idcp.'_artigo_processado',$idcp.'_journal_id');
$cdm = array('Código','Ano','Mes','Vol.','Nr.','Temática','art','proc');
$masc = array('','','','','','','','','','','');
$busca = true;
$offset = 20;
$pre_where = " (".$idcp."_journal_id = '".strzero($jid,7)."') ";
$order  = $idcp."_ano desc, ".$idcp."_nr desc, pad desc  ";
//exit;
echo '<TABLE width="'.$tab_max.'" align="center"><TR><TD>';
require($include.'sisdoc_row.php');	
echo '</table>';
?></DIV>