<?
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('revistas','brapci_brapci_journal.php'));
////////////////////////////

require("cab.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_debug.php");
require("updatex_article_asc.php");
require($include."sisdoc_menus.php");


$titu[0] = 'Relatórios Quantitativos da Base BRAPCI'; 
?><div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<BR>
<?
$sql = "select * from brapci_metodologias ";
$sql .= " inner join brapci_metodologias_tp on bmt_tipo = bmtf_tipo ";
$sql .= " where bmt_ativo = 1 ";
if (strlen($dd[0]) > 0 ) { $sql .= " and bmt_tipo = '".$dd[0]."' "; }
$sql .= " order by bmtf_tipo, bmt_ordem, bmt_descricao ";
$rlt = db_query($sql);

$s = "";
$tc = 'X';
while ($line = db_read($rlt))
	{
	$tp = $line['bmtf_tipo'];
	$tpa = $line['bmtf_descricao'];
	if ($tc != $tp)
		{
		$tp_desc = $tpa; 
		$s .= '<TR><TD align="center" class="lt5"><B>'.$tp_desc.'</B></TD></TR>';
		$tc = $tp;
		}
	$s .= '<TR>';
	$s .= '<TD>';
	$s .= '<font class="lt3">';
	$s .= trim($line['bmt_descricao']).' ';
	//<font class="lt1">'.$tpa.'</font>';
	$s .= '</font>';
	$s .= '<BR>';
	$s .= mst($line['bmt_content']);
	$s .= '</TR>';
	$s .= '<TR><TD><BR><BR></TD></TR>';
	}
?>
<table width="98%" align="center" class="lt1">
	<?=$s;?>
</table>
</DIV>
