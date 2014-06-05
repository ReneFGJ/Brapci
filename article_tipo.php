<?php
require("db.php");
$dd[2] = uppercasesql(substr($dd[2],0,1));
$_SESSION['TYPE_'.$dd[1]] = $dd[2];
if ($dd[2]=='T')
	{
		echo 'Ativado';
	} else {
		echo 'Desativado';
	}
?>
