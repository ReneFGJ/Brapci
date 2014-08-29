<?php

$flr = fopen("_page.txt",'w+');
fwrite($flr,'ola');

for ($r=0;$r < count($_SERVER);$r++)
	{
		echo '.';
		$sx .= '<BR>'.$_SERVER[$r][0].chr(13).chr(10);
	}
fwrite($flt,$sx);
fclose($flr);
echo '---->'.$_SERVER['HTTP_REFERER'];
echo '<PRE>'.$sx.'</pre>';
?>
alert("OLA");
