<?
$sql = "select * from brapci_article_suporte ";
$sql .= " where bs_journal_id = '".strzero($jid,7)." '";
$sql .= " and bs_type = 'URL' ";
$sql .= " and bs_status ='@' ";
echo $sql;
$rlt = db_query($sql);

while ($line = db_read($rlt))
	{
	$sx .= '<BR>'.$line['bs_type'].' - '.$line['bs_adress'];
	$sx .= $line['bs_status'];
	
	$arq = trim($line['bs_adress']);
	$tela = fopen($arq,'r');
	$tt = fread($tela,512);
	$sx .= '<HR>';
	$sx .= $tela;
	$sx .= '<HR>';
	fclose($arq);
	echo $tela;
	exit;
	}
?>