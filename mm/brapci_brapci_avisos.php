<?
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('avisos','brapci_brapci_avisos.php'));
////////////////////////////

require("cab.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_debug.php");
$titu[0] = 'Cadastro de Avisos e Mensagens';
?>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<BR><?

$tabela = "brapci_avisos";
$idcp = "news";
$label = $titu[0];
//$http_ver = 'editora.php'; 
if ($user_nivel == 1)
	{ 
	$http_edit = 'brapci_ed_edit.php'; 
	$http_edit_para = '&dd99='.$tabela; 
	$editar = true;
	}
$http_redirect = 'brapci_'.$tabela.'.php';


$cdf = array('id_'.$idcp,$idcp.'_assunto',$idcp.'_tipo',$idcp.'_data');
$cdm = array('Código','Título','tipo','data');
$masc = array('','','','D','','','','','','','','');
$busca = true;
$offset = 20;
$pre_where = " (".$idcp."_ativo = 1) ";
$order  = $idcp."_data desc ";
//exit;
echo '<TABLE width="'.$tab_max.'" align="center"><TR><TD>';
require($include.'sisdoc_row.php');	
echo '</table>';
echo '<font class="lt0">A:Aviso - M:Mensagem - D:Dica</font>';
?></DIV>