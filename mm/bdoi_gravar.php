<?
	$art_id = 1;
	echo '<TT><BR>';
	$hsql = "select count(*) as total from brapci_article where ar_bdoi like '".$ed_ano."-%' ";
	$drlt = db_query($hsql);
	$dline = db_read($drlt);
	$art_id = $dline['total']+1;
	
	$qsql = "update brapci_article set ";
	$qsql .= " ar_bdoi = '".$ed_ano.'-'.strzero($art_id,7).'-'.strzero($jid,5)."' ";
	$qsql .= " where ar_journal_id = '".strzero($jid,7)."' and id_ar= '".$dd[0]."' ";
	$hrlt = db_query($qsql);
	$bdoi = $ed_ano.'-'.strzero($art_id,7).'-'.strzero($jid,5);
?>