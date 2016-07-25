<div class="container">
<?php
for ($r=0;$r < count($links);$r++)
	{
		echo '<br>';
		$lk =  $links[$r]['link'];
		if (substr($lk,0,4) == 'http') { $lk = '<A href="'.$lk.'" target="_new">'.$lk.'</A>'; }
		echo $lk;
	}
?>
setname: <?php echo $setspec; ?>
<hr>
opções:
<?php echo $opcoes; ?>
</div>