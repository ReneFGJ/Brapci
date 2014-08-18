<?
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('revistas','brapci_brapci_journal.php'));
////////////////////////////

require("cab.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_debug.php");

$http_redirect = '';
$titu[0] = "Autores mais encontrados nos trabalhos";
?>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<BR><CENTER>
<?
$sql = "SELECT count( * ) AS total ";
$sql .= " FROM ( SELECT 1 ";
$sql .= " FROM `brapci_article_suporte` ";
$sql .= "INNER JOIN brapci_article ON `bs_article` = ar_codigo ";
$sql .= "WHERE ar_status <> 'X' AND bs_type = 'URL'  ";
$sql .= "GROUP BY `bs_article` ) AS tabela ";
$rlt = db_query($sql);
echo '<font class="lt4">';
if ($line = db_read($rlt))
	{
	echo '<BR>Base indicado para link dos periódicos '.$line['total'];
	}
$sql = "SELECT count( * ) AS total ";
$sql .= " FROM ( SELECT 1 ";
$sql .= " FROM `brapci_article_suporte` ";
$sql .= "INNER JOIN brapci_article ON `bs_article` = ar_codigo ";
$sql .= "WHERE ar_status <> 'X' AND bs_type = 'PDF'  ";
$sql .= "GROUP BY `bs_article` ) AS tabela ";
$rlt = db_query($sql);
if ($line = db_read($rlt))
	{
	echo '<BR>PDF coletados dentro da base '.$line['total'];
	}

?>
</DIV>
