<?
require("cab.php");

require("../_class/_class_author.php");
$au = new author;

require($include."sisdoc_colunas.php");
require($include."sisdoc_debug.php");

$tabela = "brapci_autor";
$idcp = "autor";
$label = "Cadastro de Autores";
$http_ver = 'autores_autoridades_ver.php'; 
$http_edit = 'autores_autoridades_ed.php'; 

$editar = true;
$http_redirect = page();

$au->row();
$busca = true;
$offset = 80;
//$pre_where = " (".$idcp."_ativo = 1) ";
$order  = $idcp."_nome_asc ";
//exit;
$tab_max = '99%';
echo '<h1>'.$label.'</h1>';
echo '<TABLE width="100%" align="center"><TR><TD>';
require($include.'sisdoc_row.php');	
echo '</table>';

require("foot.php");
?>
