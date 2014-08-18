<? 
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('pendências','brapci_rel_pendencia.php'));
////////////////////////////

require("cab.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_debug.php");
$titu[0] = 'Directory of Researchers in Information Science';
$tab_max = '98%';
?>
<div id="main"><center><?=$titu[0];?></center>
<BR><BR><TABLE width="95%" class="lt1">
<TR><TH>Directory of Researchers in Information Science</TH></TR>
<TR><TD><img src="img/logo_diris.png"  height="80" alt="" border="0"></TD></TR>
</TABLE>

<?
require("../db_diris.php");

$tabela = "diris";
$idcp = "dis";
$label = "Directory of Researchers in Information Science";
$http_ver = 'diris_detalhe.php'; 
$http_edit = 'brapci_ed_edit.php'; 
$http_edit_para = '&dd99='.$tabela; 
$editar = true;
$http_redirect = 'diris.php';

$cdf = array('id_'.$idcp,$idcp.'_nome','dir_nascionalidade','dis_codigo');
$cdm = array('Código','Nome','Nacionalidade','codigo');
$masc = array('','','','','','','','','','','');
$busca = true;
$offset = 40;
$order  = $idcp."_nome ";

echo '<TABLE width="'.$tab_max.'" align="center"><TR><TD>';
require($include.'sisdoc_row.php');	
echo '</table>';
?>
<TABLE width="95%" class="lt1">
<TH>Nome do pesquisador</TH>
<?=$sx;?>
</TABLE>
<?
require("foot.php");
?>