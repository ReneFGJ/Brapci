<?
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('artigos','brapci_brapci_articles.php'));
////////////////////////////

require("cab.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_debug.php");

//////////////////////////////////////////////////////////
//$sql = "ALTER TABLE brapci_article ADD COLUMN at_metodo_1 char(7)";
//$rlt = db_query($sql);
//////////////////////////////////////////////////////////

$titu[0] = 'Cadastro de usuários';
?>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<BR><?

$tabela = "brapci_article";
$idcp = "ar";
$label = "Cadastro de Artigos de Publicações";
$http_ver = 'bracpi_article.php'; 
$http_edit = 'brapci_ed_edit.php'; 
$http_edit_para = '&dd99='.$tabela; 
$editar = true;
$http_redirect = 'brapci_'.$tabela.'.php';

$cdf = array('id_'.$idcp,$idcp.'_titulo_1',$idcp.'_idioma_1',$idcp.'_idioma_2',$idcp.'_status',$idcp.'_pg_inicial',$idcp.'_pg_final',$idcp.'_codigo');
$cdm = array('Código','Nome','idioma','idioma','sta','pág.','cod');
$masc = array('','','','','','','','','','','');
$busca = true;
$offset = 20;
$pre_where = " (ar_edition = '".strzero($jedsel,7)."') ";
$order  = 'lpad(trim('.$idcp."_pg_inicial),7,0), ".$idcp."_titulo_1 ";
//exit;
echo '<TABLE width="'.$tab_max.'" align="center"><TR><TD>';
require($include.'sisdoc_row.php');	
echo '</table>';
?></DIV>