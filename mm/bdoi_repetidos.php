<?
require('cab.php');

echo '<div>';
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('revistas','brapci_brapci_journal.php'));
////////////////////////////
$sql = "SELECT count( * ) AS total, substr( ar_bdoi, 1, 4 ) AS ano
FROM `brapci_article`
WHERE ar_bdoi <> ''
GROUP BY ano order by ano desc ";
$rlt = db_query($sql);
$tot = 0;
while ($line = db_read($rlt))
	{
	$tot = $tot + $line['total'];
	$sx .= '<TR>';
	$sx .= '<TD align="center">'.$line['ano'];
	$sx .= '<TD align="center">'.$line['total'];
	$sx .= '</TR>';
	}
?>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center>Nomear bDOI - Resumo e Repetidos</center>
<br>
<center>

<table width="400" border="1" class="lt0">
<TR><TH>Ano</TH><TH>Total</TH></TR>
<?=$sx;?>
<TR><TH>Total</TH><TH><?=$tot;?></TH></TR>
</table></center>
<BR><BR>
<?
$sql = "select * from (";
$sql .= " SELECT count( * ) AS total, substr( ar_bdoi, 1, 12 ) AS ar_bdoi ";
$sql .= " FROM brapci_article WHERE ar_bdoi <> '' GROUP BY ar_bdoi "; 
$sql .= ") as tabela where total > 1 ";
$rlt = db_query($sql);

while ($line = db_read($rlt))
	{
	$sql = "select * from brapci_article where ar_bdoi like '".$line['ar_bdoi']."%' ";
	$rrlt = db_query($sql);
	$id = 0;
	while ($rline = db_read($rrlt))
		{
		if ($id > 0)
			{ 
			$sql = "update brapci_article set ar_bdoi = '' where id_ar = ".$rline['id_ar'];
			echo $sql;
			$qrlt = db_query($sql);
			echo '<HR>';
			}
			$id++;
		}
	}

require("cab.php");

?>

</DIV>
