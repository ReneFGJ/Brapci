<?
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('revistas','brapci_brapci_journal.php'));
////////////////////////////

require("cab.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_debug.php");

$tabela = "";
$cp = array();
array_push($cp,array('$H8','','',False,False,''));
array_push($cp,array('$I4','','Ano da publicação',True,True,''));
array_push($cp,array('$I8','','Número sequencial',True,True,''));
require($include.'sisdoc_form2.php');
require($include.'cp2_gravar.php');

$tab_max = 450;
$http_edit = 'bdoi.php';
$http_redirect = '';
$titu[0] = 'Busca de publicação pelo código'; 
?>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<BR><CENTER>

<TABLE width="<?=$tab_max?>" align="center"><TR><TD><?
editar();
?></TD></TR></TABLE>

<?
if ($saved > 0)
{
$sql = "select * from brapci_article ";

$sql .= " where ar_bdoi like '".strzero($dd[1],4).'-'.strzero($dd[2],7)."-%'";
$rlt = db_query($sql);

if ($line = db_read($rlt))
	{
	$link = 'http://www.brapci.ufpr.br/mm/brapci_article_select.php?dd0='.$line['id_ar'];
	redirecina($link);
	} else {
	echo '<BR><BR><font class="lt1">Código não localizado</font>';
	}
}

?>


</DIV>
