<?php
require("db.php");

require("_class/_class_message.php");
$LANG = "pt_BR";
$file = 'messages/msg_'.$LANG.'.php';
$LANG = $lg->language_read();
if (file_exists($file)) { require($file); } else { echo 'message not found '.$file; }

if (strlen($dd[1]) > 0)
	{
		$vl = $dd[2];
		if ($vl=='true') { $vl = 1; } else { $vl = 0; }
		$_SESSION['src'.$dd[1]] = $vl;
		$_SESSION['srcid'] = '1';
	}

require("_class/_class_search.php");
$nt = new search;
echo $nt->delimitacao_por_journals();
?>
