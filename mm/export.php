<?
require("cab.php");
require($include.'sisdoc_debug.php');
require("brapci_autor_qualificacao.php");
require("../_class/_class_export.php");
$exp = new export;
$max = 50;
if (strlen($dd[0]) == 0)
	{
		$exp->zera_public();
		echo '<BR>Base Zerda';
		$ini = 0;
	} else {
		$ini = round($dd[0]);
	}

echo '<div id="main"><img src="img/bt_down_mini.png" alt="" align="right">';
echo 'EXPORTAÇÃO PARA PRODUÇÃO';
echo '<BR><BR><BR>';

print_r($ext);
$sql = $exp->export_public($ini,$max);
if (strlen($sql) > 0)
	{
		$ini = $ini + $max;
		$rlt = db_query($sql); 
		echo '<A HREF="http://www.brapci.ufpr.br/mm/export.php?dd0='.$ini.'">Proxima</a>';  
    	echo '<META HTTP-EQUIV="Refresh" CONTENT="3;URL=export.php?dd0='.$ini.'">';
	}
?>
