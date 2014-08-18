<?
$sql = "select * from brapci_journal_tipo where jtp_ativo = 1 order by jtp_ordem ";
$mrlt = db_query($sql);

while ($mline = db_read($mrlt))
	{
	array_push($menu,array($mline['jtp_descricao'],'brapci_brapci_journal.php?dd90='.$mline['jtp_codigo']));
	}
array_push($menu,array('<HR>',''));
?>