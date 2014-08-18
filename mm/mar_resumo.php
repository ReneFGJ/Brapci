<?
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('revistas','brapci_brapci_journal.php'));
////////////////////////////

require("cab.php");
require($include.'sisdoc_colunas.php');
$titu[0] = 'Resumo das operaçõse - Phase I'; 
?><div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<?

?>
<table width="500" border="1" cellpadding="6" cellspacing="0" align="center">
<TR class="lt3"><TH colspan="2">Referências do Sistema - Resumo</TH></TR>
<TR valign="top">
<?
$xsql = "select count(*) as total, m_status from mar_works group by m_status ";
$zrlt = db_query($xsql);
while ($zline = db_read($zrlt))
	{
	$xsta = trim($zline['m_status']);
	$tot = $zline['total'];
	if ($sta == '@') { $sta = 'Para processar'; }
	if ($sta == 'A') { $sta = 'Fase I'; }
	if ($sta == 'B') { $sta = 'Fase II'; }
	if ($sta == 'C') { $sta = 'Fase III'; }
	if ($sta == 'Z') { $sta = 'Erros de referência'; }
	echo '<TR><TD>'.$xsta.'</TD><TD align="right">'.$tot.'</TD></TR>';
	}

?>
</table>
<BR>

<div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center>Resumo da Base</center>
<BR>
<TABLE width="100%" class="lt2">
<?=$sx;?>
<TR><TD colspan="5">Total de periódicos <B><?=$pub;?></B> com <B><?=$art;?></B> trabalhos.</TD></TR>
</TABLE>
<h1>Resumo 2</h1>
<TABLE width="400" class="lt1" border="1" align="center">
<?
$sql = "select count(*) as total from mar_works ";
$rlt = db_query($sql);
$line = db_read($rlt);
$total = $line['total'];

$sql = "select count(*) as total, m_tipo from mar_works group by m_tipo order by total desc";
$rlt = db_query($sql);

$ttt = 0;
while ($line = db_read($rlt))
	{
	$ttt = $ttt + $line['total'];
	echo '<TR '.coluna().'><TD><TT>'.$line['m_tipo'].'<TD align="right">'.$line['total'].'';
	if ($total > 0) 
	{
		echo '<TD align="right">'.number_format(($line['total']/$total)*100,2).'%</TD>';
	}
	}
?>
<TR><TD></TD><TD align="right"><?=$total;?> / <?=$ttt;?></TD></TR>
</TABLE>
</DIV>
<? require("foot.php"); ?>
<?
if ($loop == 1)
	{
	echo '<META HTTP-EQUIV="Refresh" Content = "120; URL=mar_marcacao_i.php?dda='.date("dmYHms").'">';
	}
?>
