<style>
#tips {
	background-image: url(img/img_post_it.png);
	width: 244px;
	height: 134px;
	font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;
	font-size: 11px;
	line-height: 14px;
	vertical-align: baseline;
	text-align: center;
	padding-right: 18px;
	padding-left: 12px;	
	padding-top: 12px;	
	float: right;
}
</style>
<?
$sql = "select count(*) as total from brapci_avisos ";
$sql .= " where news_ativo = 1 ";
$sql .= " and news_data_ate >= ".date("Ymd");
$sql .= " and news_tipo = 'D' ";
$rlt = db_query($sql);
if ($line = db_read($rlt))
	{
	$total = $line['total'];
	}
if ($total == 0) { $total = 1; }
$seq = date("s");
if ($total > 59)
	{
		$off = $seq - intval($total/$seq)*$seq;
	} else {
		$off = $seq;
		while ($off >= $total) { $off = $off - $total; }
	}
//if ($off == 0) { $off++; }


$sql = "select * from brapci_avisos ";
$sql .= " where news_ativo = 1 ";
$sql .= " and news_data_ate >= ".date("Ymd");
$sql .= " and news_tipo = 'D' ";
$sql .= " order by news_data desc ";
$sql .= " limit 1 offset ".$off;
$rlt = db_query($sql);

if ($line = db_read($rlt))
	{
	$dtexto.= $line['news_text'];
	}
?>
<div id="tips"><BR><BR><BR><?=$dtexto;?></div>