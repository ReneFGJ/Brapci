<?
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('artigos','brapci_brapci_articles.php'));
////////////////////////////

require("cab.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_debug.php");
$titu[0] = 'Palavras-chave sem uso';
?>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<BR><?

$tabela = "brapci_keyword";

$sql = "SELECT *
FROM `brapci_keyword`
LEFT JOIN (select count(*) as total, kw_keyword from brapci_article_keyword group by kw_keyword) as tabela ON kw_codigo = kw_keyword
where  total is null
LIMIT 0 , 300 ";
$rlt = db_query($sql);

while ($line = db_read($rlt))
	{
	$it++;
	$sql = "delete from brapci_keyword where id_kw = ".$line['id_kw'];
	$xxx = db_query($sql);
	}
echo 'Excluído '.$it.' palavras-chave que não tinham vinculos com artigos';
?></DIV>