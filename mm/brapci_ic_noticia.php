<?
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('artigos','brapci_brapci_articles.php'));
////////////////////////////

require("cab.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_debug.php");
$titu[0] = 'Cadastro de autores';
?>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<BR><?
$label = "Cadastro Informações";
$cpi = "nw";
$tabela = "ic_noticia";
if ($user_nivel >= 0)
	{	
	$http_edit = 'brapci_ed_edit.php'; 
	$http_edit_para = '&dd99='.$tabela; 
	$editar = true;
	}
	$http_redirect = 'brapci_ic_noticia_ed.php';
//	$http_ver = $tabela.'.php';
	$cdf = array('id_'.$cpi,$cpi.'_ref',$cpi.'_titulo',$cpi.'_dt_de',$cpi.'_dt_ate',$cpi.'_idioma');
	$cdm = array('Código','Ref.','Título','De','até','idioma');
	$masc = array('','','','D','D','');
	$busca = true;
	$offset = 20;
	$order  = "";
echo '<TABLE width="'.$tab_max.'" align="center"><TR><TD>';
require($include.'sisdoc_row.php');	
echo '</table>';
?></DIV>
