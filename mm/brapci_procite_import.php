<?
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
$titu[0] = 'Importação de arquivo do Pró-Cite';
require("cab.php");
?>
		<div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center><BR>
		<?
		$filename = trim($_FILES['userfile']['name']);
		if (strlen($filename) == 0)
			{
			?>
			<form enctype="multipart/form-data" action="brapci_procite_import.php" method="POST">
			<input type="hidden" name="MAX_FILE_SIZE" value="3000000">
			<input name="userfile" type="file" class="lt2">
			&nbsp;<input type="submit" value="e n v i a r" class="lt2" <?=$estilo?>>
			</form>		
			<? } else {
				require("brapci_procite_1.php");
			} ?>
		</div>
	</TD>
</TR>
</TABLE>