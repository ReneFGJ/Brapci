<?php
require("cab.php");

require('../_class/_class_cited.php');
$ct = new cited;

$cp = $ct->cp_mar_journal();
$tabela = $ct->tabela_journal;

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
$http_ver = 'mar_journal_ver.php'; 
//if ($user_nivel == 1) { 
$http_edit = 'mar_journal_ed.php'; 
$http_edit_para = '&dd99='.$tabela; 
$editar = true;
//	}
$http_redirect = 'mar_journal.php';


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


?>
