<?
$sql = "SELECT count(*) as total, ar_journal_id FROM `brapci_article` ";
$sql .= "group by ar_journal_id";
$rlt = db_query($sql);
$sqlu = "";
while ($line = db_read($rlt))
	{
	$sqlu = "update brapci_journal set jnl_artigos=".$line['total']." where jnl_codigo='".$line['ar_journal_id']."';".chr(13).chr(10);
	$xrlt = db_query($sqlu);
	}

$sql = "SELECT count(*) as total, ar_journal_id FROM `brapci_article` WHERE ar_status = 'A' ";
$sql .= "group by ar_journal_id";
$rlt = db_query($sql);
while ($line = db_read($rlt))
	{
	$sqlu = "update brapci_journal set jnl_artigos_indexados=".$line['total']." ";
	$sqlu .= "where jnl_codigo='".$line['ar_journal_id']."';".chr(13).chr(10);
	
	
	$xrlt = db_query($sqlu);
	}

?>
