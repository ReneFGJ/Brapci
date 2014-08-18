<?
///////////////////////////////////////////
// BIBLIOTECA DE FUNЧеS PHP ///////////////
////////////////////////////// criado por /
////////////////// Rene F. Gabriel Junior /
/////////////////    rene@sisdoc.com.br   /
///////////////////////////////////////////
// Versуo atual           //    data     //
//---------------------------------------//
// 0.0a                       20/05/2008 //
///////////////////////////////////////////

function MTOH($x1) 
{
	$hor = intval($x1/60);
	$min = $x1 - $hor * 60;
	while (strlen($hor) < 2) { $hor = '0'.$hor; }
	while (strlen($min) < 2) { $min = '0'.$min; }
	return($hor.':'.$min);
}

function HTOM($x1) 
{
	$x2 = '0';
	$x3 = '0';
	$mi = 0;
	for ($i = 0; $i < strlen($x1); $i ++)
		{
		$chc = substr($x1,$i,1);
		if ($chc == ':')
			{ $chc = ''; $mi = 1; } else
			{
			if ($mi == 0){ $x2 = $x2 . $chc; } else
			{$x3 = $x3 . $chc; }
			}
		}
	return($x2*60+$x3);
}

?>