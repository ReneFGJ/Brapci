<?
if (strlen($include) == 0) { exit; }
$pgina = troca($_SERVER['SCRIPT_NAME'],'/','|');
echo $pgina;

$sql = "select * from usuario_pagina where ipag_href = '".substr($pgina,0,100)."'";
$irlt = db_query($sql);
if ($iline = db_read($irlt))
	{$ipag = $iline['ipag_codigo']; } 
else 
	{
	$isql = "insert into usuario_pagina (ipag_href,ipag_codigo) values ('".$pgina."','')";
	$irlt = db_query($isql);

	$isql = "update usuario_pagina set ipag_codigo=trim(to_char(id_ipag,'".strzero(0,7)."')) where (length(trim(ipag_codigo)) < 7) or (ipag_codigo isnull);";
	$irlt = db_query($isql);
	
	$sql = "select * from usuario_pagina where ipag_href = '".substr($pgina,0,100)."'";
	$irlt = db_query($sql);
	if ($iline = db_read($irlt))
		{ $ipag = $iline['ipag_codigo']; } 
	}
echo $ipag;
?>