<?
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('revistas','brapci_brapci_journal.php'));
////////////////////////////

require("cab.php");
require($include."sisdoc_debug.php");

//$sql = "update mar_works set m_status='@' where m_status = 'Z' ";
//$rlt = db_query($sql);

$titu[0] = 'Processar Linguagem de Marcação - Phase Z - Erros de referência'; 
?><div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<BR>
<?
$path = "mar_marcacao_i.php";
$sql = "delete from mar_works where m_ref = '' ";
$rlt = db_query($sql);

$sql = "select * from mar_works where m_status = 'Z' limit 150 ";
$rlt = db_query($sql);
$id = 0;
while ($line = db_read($rlt))
	{
	$id++;
	$work = $line['m_work'];
	$link = '<A href="http://www.brapci.ufpr.br/mm/brapci_article_select.php?dd1='.$work.'">';
	echo $link;
	echo $id.'. ';
	echo $line['m_ref'];
	echo '</A>';
	echo '<BR>';
	echo '<BR>';
	}
?>
<BR>
</DIV>
<? require("foot.php"); ?>
