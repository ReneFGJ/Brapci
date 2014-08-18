<?
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('revistas','brapci_brapci_journal.php'));
////////////////////////////
$no_menu_left = 1;

require("cab.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_debug.php");

$tabela = "";

$sql = "select * from brapci_article ";
$sql .= " where ";

$cp = array();
array_push($cp,'at_metodo_1');
array_push($cp,'at_metodo_2');
array_push($cp,'at_metodo_3');
array_push($cp,'at_tecnica_1');
array_push($cp,'at_tecnica_2');
array_push($cp,'at_tecnica_3');
array_push($cp,'at_analise_1');
array_push($cp,'at_analise_2');


$wh = '';
if (strlen($dd[2]) > 0)
	{ 
	for ($rx=0;$rx < count($cp);$rx++)
		{ 
		if ($rx > 0) { $wh .= ' or '; }
		$wh .= '('.$cp[$rx]. " = '".$dd[2]."') ";
		}
	}
$sql .= ' ('.$wh.") and (ar_status <> 'X' and ar_journal_id = '0000003') ";

$rlt = db_query($sql);
?>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<BR><CENTER>

<?
?>
<table width="90%" cellpadding="4" cellspacing="0" border="1">
<TR>
<TH width="45%">Artigo</TH>
</TR>
<?
$to1 = 0;
$tot = 0;
while ($line = db_read($rlt))
	{
	$to1++;
	$tot++;
	$ID = $line['ar_codigo'];
	$link = '<A HREF="http://www.brapci.ufpr.br/documento.php?dd0='.$ID.'&dd1='.substr(md5($ID.$secu),0,5).'" target="X">';
	echo '<TR><TD>';
	echo $link;
	echo $line['ar_titulo_1'];
	echo '</TD></TR>';
	}
?>

<TR class="lt1"><TD colspan="3" align="right"><B>Total de <?=$to1;?> trabalhos</B></TD></TR>
<TR class="lt2"><TD colspan="3" align="right">Total de <?=$tot;?> trabalhos</TD></TR>
</table>
</DIV>
