<?
$link = "http://revista.ibict.br/liinc/index.php/liinc/oai/?verb=GetRecord&metadataPrefix=oai_dc&identifier=oai:ojs.ibict.liinc:article/293";

$rlt = fopen($link,'r');
$s = '';
$t = 1;
while ($t > 0)
	{
	$sx = fread($rlt,512);
	$s .= $sx;
	$t = strlen($sx);
	}
fclose($rlt);	
echo $s;

?>