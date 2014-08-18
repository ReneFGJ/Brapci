<?
require("cab.php");
require($include.'sisdoc_form2.php');
require($include.'sisdoc_colunas.php');

require($include.'cp2_gravar.php');
$titu[0] = 'Relatórios Quantitativos da Base BRAPCI'; 
?><div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<BR>
<?
?>
<table width="100%" border="1">
<TR valign="top">
	<TD width="350"><? require("class_tree.php");?></TD>
	<TD><? require("class_propriety_add.php");?></TD>
</tr>	
</table>
</div>