<?php
///////////////////////////////////////////
// BIBLIOTECA DE FUNЧеS PHP ///////////////
////////////////////////////// criado por /
////////////////// Rene F. Gabriel Junior /
/////////////////    rene@sisdoc.com.br   /
///////////////////////////////////////////
// Versуo atual           //    data     //
//---------------------------------------//
// 0.0a                       19/05/2009 //
///////////////////////////////////////////
if ($mostar_versao == True) { array_push($sis_versao,array("sisDOC (Security Post)","0.0a",20090519)); }
if (strlen($include) == 0) { exit; }
function p($id)
	{
	return(true);
	global $secu;
	$idx = md5(trim($id).trim($secu));
	$idx = sonumero($idx);
	$xi = 0;
	$xc = 1;
	for ($ri=0;$ri < strlen($idx);$ri++)
		{
		$xv = intval(substr($idx,$ri,1));
		$xi = $xi + $xv * ($xc);
		if ($xc == 1) { $xc = 3; } else {$xc = 0; }
		}
	while ($xi > 10) { $xi = $xi - 10; }
	if ($xi == 10) { $xi = '0'; }
	$idx .= $xi;
	return($idx);
	}
?>