<?
$tit[0] = 'Mensagens e Avisos';
$sql = "select * from brapci_avisos ";
$sql .= " where news_ativo = 1 ";
$sql .= " and news_data_ate >= ".date("Ymd");
$sql .= " and (news_tipo = 'A' or news_tipo = 'M')";
$sql .= " order by news_data desc ";
$rlt = db_query($sql);

$sx = '';
while ($line = db_read($rlt))
	{
	if (strlen($sx) > 0) { $sx .= '<HR>'; }
	$sx .= '<BR><B>'.trim($line['news_assunto']).'</B> ';
	$sx .= '<BR><font class="lt0"><I>'.stodbr($line['news_data']).' '.$line['news_hora'].'</I></font>';
	$sx .= '<BR>';
	$sx .= mst($line['news_text']);
	}
?>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$tit[0];?></center>
<? require('tips.php'); ?>
<?=$sx;?>
</div>