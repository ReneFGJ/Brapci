<?php
require("cab.php");

require ("../_class/_class_publications.php");
$res = new publications;

$sql = "select ar_status, ar_edition, id_ar,ar_journal_id, ar_codigo, ar_ano, ar_titulo_1_asc as Article_Title
			from brapci_article
			where ar_status <> 'X'	 
		order by Article_Title, ar_journal_id, ar_ano , ar_codigo
			";
$rlt = db_query($sql);
$zzz = '';
$jour = '';
$it = 0;
$xano = '';
$xedi = '';
while ($line = db_read($rlt))
{
	$link = '<A HREF="article_view.php?dd0='.$line['ar_codigo'].'" target="_new">';
	$tit = UpperCaseSql(trim($line['Article_Title']));
	if (strlen($tit) == 0)
		{
			$res->update_title_asc($line['id_ar']);
		}
		
	$ano = $line['ar_ano'];
	$ttt = trim(substr($tit,0,30));
	$jou = $line['ar_journal_id'];
	$edicao = $line['ar_edition'];
	$status = $line['ar_status'];

	if (($ttt == $zzz) and ($jour == $jou) and ($ano == $xano) and ($xedi == $edicao))
		{
			$cor = '<font color="red">'; $it++; 
			echo '<HR>';
			echo $atual;
			echo '<BR>'.$link.' '.$edicao.' '.'('.$status.') '.$jou.'('.$ano.') </A>-'.$cor.$tit.'</font>';
			$sql = "update brapci_article set ar_status = 'X' where ar_status = 'A' and id_ar = ".$line['id_ar'];
			echo '<BR>'.$sql;
			//$eee = db_query($sql);
		} else {
			$cor = '<font color="black">';
			$atual = '<BR>'.$link.' '.$edicao.' '.'('.$status.') '.$jou.'('.$ano.') </A>-'.$cor.$tit.'</font>';
		}
	$zzz = $ttt;
	$xano = $ano;
	$xedi = $edicao;
	$jour = $line['ar_journal_id'];
}
echo '<BR>Total de '.$it.' repetições';
require("../foot.php");
?>
