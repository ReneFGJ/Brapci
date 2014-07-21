<?php
echo '<div style="padding: 10px;"><h1>Palavras-chave</h1></div>';

require("_class/_class_keyword.php");
$kw = new keyword;

echo '<div class="two-col" style="padding: 10px;">';

$key = 'A';
if (strlen($dd[0])>0) { $key = $dd[0]; }

$wd = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
for($r=0;$r < strlen($wd);$r++)
	{
		$link = '<A HREF="'.page().'?dd1='.$dd[1].'&dd0='.substr($wd,$r,1).'">';
		echo $link;
		echo substr($wd,$r,1);
		echo '</A>';
		
		echo '&nbsp;';
	}

if (strlen($dd[0]) > 0) { $key = $dd[0]; }
echo $kw->show_keyword($LANG,$key);
echo '</div>';
?>
