<?php
require("cab.php");

require ("../_class/_class_publications.php");
$res = new publications;

$sql = "select id_ar,ar_journal_id, ar_codigo, ar_ano, ar_titulo_1_asc as Article_Title
			from brapci_article
			where ar_status <> 'X'	 
		order by Article_Title, ar_journal_id, ar_ano 
			";
$rlt = db_query($sql);
$zzz = '';
$jour = '';
$it = 0;
while ($line = db_read($rlt))
{
	$link = '<A HREF="article_view.php?dd0='.$line['ar_codigo'].'" target="_new">';
	$tit = UpperCaseSql(trim($line['Article_Title']));
	if (strlen($tit) == 0)
		{
			$res->update_title_asc($line['id_ar']);
		}
		
	$ano = $line['ar_ano'];
	$ttt = trim(substr($tit,0,60));
	$jou = $line['ar_journal_id'];
	
	if (($ttt == $zzz) and ($jour == $jou))
		{
			$cor = '<font color="red">'; $it++; 
			echo '<HR>';
			echo $atual;
			echo '<BR>'.$link.$jou.'('.$ano.') </A>-'.$cor.$tit.'</font>';
		} else {
			$cor = '<font color="black">';
			$atual = '<BR>'.$link.$jou.'('.$ano.') </A>-'.$cor.$tit.'</font>';
		}
	$zzz = $ttt;
	$jour = $line['ar_journal_id'];
}
echo '<BR>Total de '.$it.' repetições';
require("../foot.php");
?>
