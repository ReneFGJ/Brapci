<?
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('usuário','brapci_brapci_usuario.php'));
////////////////////////////

require("cab.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_debug.php");
$titu[0] = 'Cadastro de usuários';
?>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<BR>
<?

$tabela = "mar_journal";
$idcp = "mj";
$label = "Cadastro de Publicações";
$http_ver = 'brapci_mar_journal_ver.php'; 
//if ($user_nivel == 1) { 
$http_edit = 'brapci_ed_edit.php'; 
$http_edit_para = '&dd99='.$tabela; 
$editar = true;
//	}
$http_redirect = 'brapci_mar_journal.php';


$cdf = array('id_'.$idcp,$idcp.'_nome',$idcp.'_abrev','mj_tipo','mj_codigo','mj_use');
$cdm = array('Código','Nome','e-mail','código','ativo','codigo','use');
$masc = array('','','','','','','','','','','');
$busca = true;
$offset = 20;
if (strlen($dd[1]) == 0)
	{ $pre_where = " (".$idcp."_ativo = 1) "; }
$order  = $idcp."_nome ";
//exit;
echo '<TABLE width="'.$tab_max.'" align="center"><TR><TD>';
require($include.'sisdoc_row.php');	
echo '</table>';
?></DIV>