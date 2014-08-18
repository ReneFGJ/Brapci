<?
require("cab.php");
require($include.'sisdoc_debug.php');
?>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right">
EXPORTAÇÃO PARA PRODUÇÂO
<BR><BR><BR>
<?
global $ed_editar;
////////////// Menu Position
$sqli = "INSERT INTO keyword ( ";
$sqli .= "kw_word , kw_word_asc, kw_codigo, ";
$sqli .= " kw_use , kw_idioma , kw_tipo  ";
$sqli .= " ) values ";

$sqls = '';

$sql = "select ";
$sql .= "kw_word , kw_word_asc, kw_codigo, ";
$sql .= " kw_use , kw_idioma , kw_tipo  "; 
$sql .= " from brapci_keyword ";
//$sql .= " limit 1 ";
$rlt = db_query($sql);

$sqlq = '';
while ($line = db_read($rlt))
	{
	if (strlen($sqlq) > 0)
		{ $sqlq .= ', '; }
	/////////////////////////////////// Edião
		$sqlq .= "(";
		for ($k=0;$k < 6;$k++)
			{
			if ($k > 0) { $sqlq .= ', '; }
			$sqlq .= "'".$line[$k]."'";
			}
		$sqlq .= ")";
	}
	

echo '<HR>';
require("../db_pesquisador.php");
$sql = "delete from keyword ";
$rlt = db_query($sql);

$rlt = db_query($sqli. $sqlq);
?></div>
xxxxxxxx FIM OK xxxxxxxxxxxxx