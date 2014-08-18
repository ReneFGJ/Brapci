<?
$qsql = "select * from brapci_article ";
$qsql .= "where ar_titulo_1_asc = '' and (ar_status <> 'X') ";
$qsql .= "limit 1130 ";
$qrlt = db_query($qsql);
$atu = 0;
while ($qline = db_read($qrlt))
	{
	$atu++;
	$tituloq = trim($qline['ar_titulo_1']);
	if (strlen($tituloq) == 0) { $tituloq = '[em branco]'; }
	$qsql = "update brapci_article set ar_titulo_1_asc = '".UpperCaseSQL($tituloq)."' where id_ar = ".$qline['id_ar'];
	$rrlt = db_query($qsql);
	}
?>