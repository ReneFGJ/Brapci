<?
require("mar_function.php");
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('revistas','brapci_brapci_journal.php'));
////////////////////////////

require("cab.php");
require($include."sisdoc_debug.php");
require($include."sisdoc_windows.php");

$titu[0] = 'Processar Linguagem de Marcação - Phase II'; 
?><div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<BR>
<?
$path = "mar_marcacao_ii.php";
$pag = round('0'.$dd[1])*100;
$sql = " SELECT nnn.`a_nome` AS n1, mmm.`a_nome` AS n2, mmm.`a_status` AS st, nnn.`a_use` as cc1 , nnn.`a_codigo` as cc2
FROM `mar_autor` AS nnn
INNER JOIN mar_autor AS mmm ON nnn.a_use = mmm.a_codigo
where nnn.a_status <> 'Z' 
ORDER BY nnn.`a_nome_asc`
LIMIT $pag , 100 ";
$rlt = db_query($sql);
$loop = 0;
$online = 0;

$x='';
$y='';
$limit = 10;
$c1 = '';
$c2 = '';
$c3 = '';
$col = 0;
$id = 0;
while (($line = db_read($rlt)) and ($limit > 0))
	{
		$id++;
		$link = '<A href="#" onclick="newxy2('.chr(39).'mar_marcacao_autor_dados.php?dd0='.$line['cc2'].'&dd1=Z'.chr(39).',500,400);"><font color=red>X</font></A>';
		if ($id == 50) { $col++; }
		$x1 = trim($line['cc1']);
		$x2 = trim($line['cc2']);
		$c = trim($line['n1']);
		if ($x1 != $x2)
			{
			$c .= ' <I>use</I> ';
			$c .= '<B>'.trim($line['n2']).'</B>';
			} else {
			$c = '<B>'.$c.' '.$link.'</B>';
			}
//		$c .= ' ['.$x1.'='.$x2.']';
		$c .= '<BR>';
		if ($col == 0) { $c1 .= $c; }
		if ($col == 1) { $c2 .= $c; }
	}
//////////////////////////////////////////////////////////////////////////
?>
<table width="98%" class="lt1">
<TR valign="top">
	<TD width="50%"><?=$c1;?></TD>
	<TD width="50%"><?=$c2;?></TD>
</TR>

</table>
<BR>
</DIV>
<? require("foot.php"); ?>
