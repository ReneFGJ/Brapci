<?
if (strlen($bdoi) > 0)
	{
	$hsql = "select * from mar_works where m_bdoi = '".$bdoi."' ";
	$hrlt = db_query($hsql);
	
	$hline = db_read($hrlt);
	$sr .= 'Citado por ('.$hline['total'].')';
	}
?>