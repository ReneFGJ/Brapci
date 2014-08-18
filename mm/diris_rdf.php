<? 
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('pendências','brapci_rel_pendencia.php'));
////////////////////////////

require("cab.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_debug.php");
$titu[0] = 'Directory of Researchers in Information Science';

$sql = "select * from diris where id_dir = ".sonumero($dd[0]);
$rlt = db_query($sql);
if ($line = db_read($rlt))
	{
	print_r($line);
	}
?>
<div id="main"><center><?=$titu[0];?></center>
<BR><BR><TABLE width="95%" class="lt1">
<TR><TH>Directory of Researchers in Information Science</TH></TR>
<TR><TD><img src="img/logo_diris.png"  height="80" alt="" border="0"></TD></TR>
<?=$sr;?>
</TABLE>
<?
require("foot.php");
?>