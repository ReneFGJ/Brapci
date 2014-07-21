<?php
require("cab.php");

require ("../_class/_class_article.php");
$res = new article;

$sql = "select id_ar,ar_journal_id, ar_codigo, ar_ano, ar_titulo_1_asc as Article_Title
			from brapci_article
			where ar_status <> 'X' and 
			(ar_titulo_1_asc = '**SEM TITULO**' or ar_titulo_1_asc = '' or ar_titulo_1_asc is null) 
		order by Article_Title, ar_ano 
			";
echo '<h1>Processar sem título</h1>';
$rlt = db_query($sql);
$zzz = '';
$it = 0;
while ($line = db_read($rlt))
{
	$link = '<A HREF="article_view.php?dd0='.$line['ar_codigo'].'" target="_new">';
	$tit = UpperCaseSql(trim($line['Article_Title']));
	$res->update_title_asc($line['id_ar']);

	$cor = '<font color="red">'; $it++; 
	$jou = $line['ar_journal_id'];
	echo '<HR>';
	echo $atual;
	echo '<BR>'.$link.$jou.'('.$ano.') </A>-'.$cor.$tit.'</font>';
}
echo '<BR>Total de '.$it.' sem título<BR>';
require("../foot.php");
?>
