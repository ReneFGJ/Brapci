<?
require("cab.php");
?>
<form method="post" action="<?=page();?>">
<textarea name="dd1" cols=80 rows="10"></textarea>
<BR>
<input type="submit" value="converter">
</form>


<PRE>
	<?
	$rr=utf8_decode($dd[1]);
	$rr = troca($rr,'`','');
	$rr = troca($rr,'´',"'");
	echo $rr;
	?>
</PRE>

