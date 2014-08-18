<?
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('pendências','brapci_artigos_semresumo.php'));
////////////////////////////

require("cab.php");
require($include."sisdoc_html.php");
require($include."sisdoc_debug.php");
$titu[0] = 'Auto correção';

$sql = " SELECT * from brapci_article ";
$sql .= " where id_ar = ".$dd[0];
$rlt = db_query($sql);

$s = '';
$journal = "X";
?>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<table width="100%" class="lt2">
<TR>
	<TH>título do trabalho</TH>
	<TH>total</TH>
	<TH>public.</TH>
</TR>
<TR><TD>
<?
	if ($line = db_read($rlt))
		{
		$s1 = $line['ar_resumo_1'];
		$s2 = $line['ar_resumo_2'];
		$s3 = $line['ar_resumo_3'];
		
		$sc1 = HtmltoChar($line['ar_resumo_1']);
		$sc2 = HtmltoChar($line['ar_resumo_2']);
		$sc3 = HtmltoChar($line['ar_resumo_3']);
		?>
		<div><TT><?=$s1;?></div>
		<HR>
		<B>HtmltoChar</B>
		<HR>
		<div><TT><?=HtmltoChar($s1);?></div>
		<HR>
		<B>htmlentities</B>		
		<HR>
		<div><TT><?=htmlentities($s1);?></div>		
		<HR>
		<div><TT><?=$s2;?></div>
		<div><TT><?=$s3;?></div>
		<?
		$sql = " update brapci_article set ";
		$sql .= " ar_resumo_1 = '".$sc1."',";
		$sql .= " ar_resumo_2 = '".$sc2."',";
		$sql .= " ar_resumo_3 = '".$sc3."' ";
		$sql .= " where id_ar = ".$dd[0];
		$rlt = db_query($sql);
		}
?>
</TD></TR>
</table>
<BR>Autocorreção efetuada com sucesso!</B>
</DIV>