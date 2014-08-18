<?
require($include.'sisdoc_debug.php');
$ww = array();
for ($r = 0; $r < count($wd);$r++)
	{
	if (strlen($wd[$r]) > 4) { array_push($ww,$wd[$r]); }
	}
echo '<TR><TD align="center">3</TD><TD>Recuperação com '.($wmax+1).' termos</TD></TR>';

$sql = "select * from artigos ";
$sql .= " where ";
$bs = "";
for ($r=$ini; $r < $wmax;$r++)
	{
	if (strlen($wh) > 0) { $wh .= ' and '; }
	$wh .= " ar_asc_mini like '%".$ww[$r]."%' ";
	$bs .= $ww[$r].' ';
	}
$sql .= "(".$wh.") and ar_ano = '".$ano."' ";

echo '<TR><TD align="center">3.1</TD><TD><TT>Palavras-chave:'.$bs.'</TD></TR>';
echo '<TR><TD align="center">3.2</TD><TD><TT>Ano:'.$ano.'</TD></TR>';

require("../db/db_pesquisador.php");
$rlt = db_query($sql);
$ln = array();
while ($line = db_read($rlt))
	{
	array_push($ln,$line['ar_doi']);
	}

?>