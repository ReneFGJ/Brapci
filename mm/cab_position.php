<div id="toppath">
<TABLE width="98%" class="lt1" cellpadding="0" cellspacing="0">
<TR><TD>
&nbsp;
<?php
for ($km=0;$km < count($mnpos);$km++)
	{
	if ($kn >= 0) { echo '&nbsp;&raquo;&nbsp;'; }
	echo '<A href="'.$mnpos[$km][1].'" onmouseover="return true;">';
	echo $mnpos[$km][0].'</A>';
	}
?>
<TD align="right"><a href="logout.php">sair</a></TD>
</TABLE>
</div>