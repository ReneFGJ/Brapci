<?
global $grvlr;
$grvlr = array();
for ($kr = 65; $kr < 91;$kr++) { array_push($grvlr,chr($kr)); }
for ($kr = 97; $kr < 127;$kr++) { array_push($grvlr,chr($kr)); }
for ($kr = 48; $kr < 58;$kr++) { array_push($grvlr,chr($kr)); }

function GoogleChat($vvlr,$vcor)
	{
	global $grvlr;
	$vvmax = 0;
	for ($kr = 0; $kr < count($vvlr);$kr++)
		{
		if ($vvlr[$kr] > $vvmax) { $vvmax = $vvlr[$kr]; }
		}
	$glink = "http://chart.apis.google.com/chart?cht=p3&chs=750x300&";
	$glink .= "&chd=s:";
	for ($kr = 0; $kr < count($vvlr);$kr++)
		{
		$vvll = round($vvlr[$kr] / $vvmax  * 63);
		$glink .= $grvlr[$vvll];
		}
		
	$glink .= '&chl=';
	for ($kr = 0; $kr < count($vcor);$kr++)
		{
		if ($kr > 0) { $glink .= '|'; }
		$glink .= $vcor[$kr];
		}
	return($glink);
	}
	
function GoogleChat2D($vvlr,$vcor)
	{
	global $grvlr;
	$vvmax = 0;
	for ($kr = 0; $kr < count($vvlr);$kr++)
		{
		if ($vvlr[$kr] > $vvmax) { $vvmax = $vvlr[$kr]; }
		}
	$glink = "http://chart.apis.google.com/chart?cht=p&chs=750x350&";
	$glink .= "&chd=s:";
	for ($kr = 0; $kr < count($vvlr);$kr++)
		{
		$vvll = round($vvlr[$kr] / $vvmax  * 63);
		$glink .= $grvlr[$vvll];
		}
		
	$glink .= '&chl=';
	for ($kr = 0; $kr < count($vcor);$kr++)
		{
		if ($kr > 0) { $glink .= '|'; }
		$glink .= $vcor[$kr];
		}
	return($glink);
	}
	
function GoogleBarLine($vvlr,$vcor)
	{
	global $grvlr;
	$vvmax = 0;
	for ($kr = 0; $kr < count($vvlr);$kr++)
		{
		if ($vvlr[$kr] > $vvmax) { $vvmax = $vvlr[$kr]; }
		}
	
	if (($vvmax > 0) and ($vvmax <= 100)) { $vvmax = 100; }
	if (($vvmax > 100) and ($vvmax <= 1000)) { $vvmax = 1000; }
	if (($vvmax > 1000) and ($vvmax <= 10000)) { $vvmax = 10000; }
	if (($vvmax > 10000) and ($vvmax <= 100000)) { $vvmax = 100000; }
	if (($vvmax > 100000) and ($vvmax <= 1000000)) { $vvmax = 1000000; }
	if (($vvmax > 1000000) and ($vvmax <= 10000000)) { $vvmax = 10000000; }
	if (($vvmax > 10000000) and ($vvmax <= 100000000)) { $vvmax = 100000000; }
	
	$glink = "http://chart.apis.google.com/chart?cht=lc&chs=750x300&";
	$glink .= "&chd=s:";
	for ($kr = 0; $kr < count($vvlr);$kr++)
		{
		$vvll = round($vvlr[$kr] / $vvmax  * 63);
		$glink .= $grvlr[$vvll];
		}
	$glink .= '&chco=676767&chls=4.0,3.0,0.0&chxt=x,y';
	$glink .= '&chf=c,lg,45,ffffff,0,76A4FB,0.75|bg,s,FFFFFF';
	$glink .= '&chl=';
	for ($kr = 0; $kr < count($vcor);$kr++)
		{
		if ($kr > 0) { $glink .= '|'; }
		$glink .= $vcor[$kr];
		}
	return($glink);
	}
	
//// http://code.google.com/intl/pt-BR/apis/chart/
?>