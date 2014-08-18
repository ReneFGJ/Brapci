<?
require("cab.php");
require($include.'sisdoc_form2.php');
require($include.'sisdoc_colunas.php');
require($include.'cp2_gravar.php');
$titu[0] = 'Relatórios Quantitativos da Base BRAPCI'; 
session_start();
if (strlen($dd[0]) > 0)
	{ $_SESSION['concept'] = $dd[0]; }
?><div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<BR>
<?
$ttg = array();
$tte = array();
$ttr = array();
$ttt = array();
$ttu = array();
?>
<table width="100%" border="1">
<TR valign="top">
	<TD width="250"><? require("class_tree.php");?></TD>
	<TD>
	<? require("class_propriety.php");?>
	<? require("skos.php"); ?>
	</TD>
</tr>	

</TD></TR>
</table>
</div>