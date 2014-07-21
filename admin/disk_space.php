<?
require("cab.php");

require($include.'cp2_gravar.php');
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_form2.php');
require($include.'sisdoc_debug.php');
require("../_class/_class_lattes.php");

//$sql = "delete from _messages where 1=1";
//$rlt = db_query($sql);
	$df = disk_free_space("/");
	$ml = 0;
	while ($df > 1024)
		{ $df = ($df / 1024); $ml++; }
		switch ($ml)
			{
			case 1: $und = 'k Bytes'; break;
			case 2: $und = 'M Bytes'; break;
			case 3: $und = 'G Bytes'; break;
			case 4: $und = 'T Bytes'; break;
			case 5: $und = 'P Bytes'; break;
			}
	echo '<h1>Espaço em disco <B>'.number_format($df,1).$und.'</B></h1>';
require("foot.php"); ?>