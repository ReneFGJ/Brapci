<?php
require("cab.php");

$sql = "select ar_journal_id, ar_codigo, ar_ano, Article_Title 
			from ".$db_public."artigos
			where ar_ano > 2011			 
		order by Article_Title, ar_ano 
			";
$rlt = db_query($sql);
$zzz = '';
$it = 0;
while ($line = db_read($rlt))
{
	$link = '<A HREF="article_view.php?dd0='.$line['ar_codigo'].'" target="_new">';
	$tit = UpperCaseSql($line['Article_Title']);
	$ano = $line['ar_ano'];
	$ttt = trim(substr($tit,0,60));
	
	$cor = '<font color="black">';
	if ($ttt == $zzz) { $cor = '<font color="red">'; $it++; }
	
	$jou = $line['ar_journal_id'];
	echo '<BR>'.$link.$jou.'('.$ano.') </A>-'.$cor.$tit.'</font>';
	$zzz = $ttt;
}
echo '<BR>Total de '.$it.' repetições';
require("../foot.php");
?>
