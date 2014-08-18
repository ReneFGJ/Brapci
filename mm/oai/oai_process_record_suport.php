<?
if (count($link) > 0)
	{
	for ($ur = 0;$ur < count($link);$ur++)
		{
		$url = trim($link[$ur]);
		$sql = "select * from brapci_article_suporte ";
		$sql .= " where ";
		$sql .= " bs_journal_id = '".$jid."' ";
		$sql .= " and bs_article = '".$ar_codigo."' ";
		$sql .= " and bs_adress = '".$url."' ";
		$rlt = db_query($sql);
		
		if (!($line = db_read($rlt)))
			{
			$sql = "insert into brapci_article_suporte ";
			$sql .= " (bs_journal_id,bs_article,bs_status,";
			$sql .= " bs_update,bs_type,bs_adress) ";
			$sql .= " values ";
			$sql .= "('".$jid."','".$ar_codigo."','@',";
			$sql .= "'".date("Ymd")."','URL','".$url."'";
			$sql .= ")";
//			echo '<HR>'.$sql.'<HR>';
			$rlt = db_query($sql);
			}
		}
	}
?>	