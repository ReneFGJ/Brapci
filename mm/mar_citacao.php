<?
global $xbdoi;
if (strlen($xbdoi) > 0)
	{
	$hsql = "select count(*) as total from mar_works where m_bdoi = '".$xbdoi."' ";
	$hrlt = db_query($hsql);
	$hline = db_read($hrlt);
	echo 'Citado por ('.$hline['total'].')';
	}
?>