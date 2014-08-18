<?
		$sql = "update brapci_article_suporte set ";
		$sql .= " bs_update = ".date("Ymd")." ";
		$sql .= " where id_bs = ".$id;
		$xxx = db_query($sql);
		echo 'Prorrogado';
?>