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

$titu[0] = 'Processar Linguagem de Marcação - Phase III'; 
?><div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<?
$path = "mar_marcacao_ii.php";

$sql = "select * from mar_works ";
$sql .= "inner join mar_journal on m_journal = mj_codigo ";
$sql .= " where m_processar = 'S' and m_status = 'C'";
$rlt = db_query($sql);
$refs = array();
$anos = array();
$loop = 0;
$online = 0;

if ($line = db_read($rlt))
	{
	$idc = $line['id_m'];
	$dd[0] = $line['id_m'];
	$ano = $line['m_ano'];
	$s = $line['m_ref'];
	echo '<div>';
	echo '<BR><TT>Ref>'.$line['m_ref'];
	echo '<BR><TT>Nome:'.$line['mj_nome'];
	echo '<BR><TT>Ano:'.$line['m_ano'];
	echo '</div>';
	require("mar_marcacao_iv_a.php");
	$loop = 1;
	}

?>
<BR>
</DIV>
<? require("foot.php"); ?>
<?
if ($loop == 1)
	{
	if ($kk == 1)
		{ echo '<META HTTP-EQUIV="Refresh" Content = "30; URL=mar_marcacao_iv.php?dda='.date("dmYHms").'">'; }
	else
		{ echo '<META HTTP-EQUIV="Refresh" Content = "30; URL=mar_marcacao_iv.php?dda='.date("dmYHms").'">'; }
	} else 
	{ echo '<META HTTP-EQUIV="Refresh" Content = "150; URL=mar_marcacao_iv.php?dda='.date("dmYHms").'">'; }
?>
