<?
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('revistas','brapci_brapci_journal.php'));
////////////////////////////

require("cab.php");
require($include."sisdoc_debug.php");
if (strlen($dd[10]) > 0)
	{ $ed_ano = $dd[10]; }
	else
	{ $ed_ano = 0; }
	$sql = "SELECT ed_ano, ar_journal_id,id_ar, ar_bdoi, ar_codigo, ar_section FROM `brapci_edition` 
	inner join brapci_article on `ed_codigo` = ar_edition 
	inner join brapci_section on ar_section = se_codigo ";
	if ($ed_ano > 1900)
		{ $sql .= " WHERE ar_status <> 'X' and ed_ano = ".$ed_ano." and ar_bdoi = '' "; }
		else
		{ $sql .= " WHERE ar_status <> 'X' and ar_bdoi = '' "; }
	$sql .= " and se_tipo = 'B' ";
	$sql .= " order by ed_ano ";
	$sql .= " limit 15 ";
$rlt = db_query($sql);
?>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center>Nomear bDOI</center>
<br>
<?
$id = 0;
while ($line = db_read($rlt))
	{
	$ed_ano = round($line['ed_ano']);
	$jid = round($line['ar_journal_id']);
	$id++;
	$dd[0] = $line['id_ar'];
	require("bdoi_gravar.php");
	echo '<HR>'.$qsql;
	}
echo 'Atualizado '.$id;
if ($id > 0)
	{
	?>
	<META HTTP-EQUIV="Refresh" CONTENT="1;URL=bdoi.php">
	<?
	}
?>


</DIV>
