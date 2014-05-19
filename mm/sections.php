<?
require("cab.php");
require('../_class/_class_publications.php');
require($include.'sisdoc_colunas.php');
$pb = new publications;

require("main_menu.php");

echo '<div class="nav">';

$tabela = "brapci_section";
$idcp = "se";
$label = "Cadastro de seções";
//$http_ver = 'journal_sel.php'; 
$http_edit = 'sections_ed.php'; 
$editar = true;
$http_redirect = page();

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
echo '</div>';

require("../foot.php");
?>