<?
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('artigos','brapci_brapci_articles.php'));
////////////////////////////

require("cab.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_debug.php");
require($include."sisdoc_autor.php");
require($include."sisdoc_windows.php");
$titu[0] = 'Autores não normalizados';
?>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<BR><?

$tabela = "brapci_autor";

$sql = "SELECT * from ".$tabela;
$sql .= " where not autor_nome like '%,%' ";
$sql .= " and autor_tipo = 'A' ";
$sql .= " order by autor_nome ";
$rlt = db_query($sql);

while ($line = db_read($rlt))
	{
	$link = '<a href="brapci_ed_edit.php?dd0='.$line['id_autor'].'&dd99=brapci_autor" target="_news">';
	$link2 = '<a href="#" onclick="newxy2('.chr(39).'brapci_autores_normas_auto.php?dd0='.$line['id_autor'].chr(39).',300,200);">';
	echo '<BR>';
	echo $link;
	echo $line['autor_nome'];
	echo '</A> ';
	echo nbr_autor($line['autor_nome'],1);
	echo $link2 .= '[AUTO]';
	echo '</A>';
	}
?></DIV>