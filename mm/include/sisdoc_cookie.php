<?
///////////////////////////////////////////
// BIBLIOTECA DE FUNЧеS PHP ///////////////
////////////////////////////// criado por /
////////////////// Rene F. Gabriel Junior /
/////////////////    rene@sisdoc.com.br   /
///////////////////////////////////////////
// Versуo atual           //    data     //
//---------------------------------------//
// 0.0b                       11/12/2008 //
// 0.0a                       20/05/2008 //
///////////////////////////////////////////
if ($mostar_versao == True) { array_push($sis_versao,array("sisDOC (Cookies)","0.0c",20090621)); }
$cookie_lib = True;
function read_cookie($ss)
	{
	$ssx = $_COOKIE[$ss];
	if (strlen($ssx) ==0) { $ssx = $HTTP_COOKIE_VARS[$ss]; }
	return($ssx);
	}
	
function write_cookie($nm,$vl,$tp)
	{
	if (strlen($tp) > 0)
		{
		$tpp = strtoupper($tp[0]);
		$tp = substr($tp,1,20);
		if ($tpp = 'Y') { $tpc = 365*24*60*60; } // ano
		if ($tpp = 'M') { $tpc = 31*24*60*60; } // mes
		if ($tpp = 'W') { $tpc = 7*24*60*60; } // semana
		if ($tpp = 'D') { $tpc = 1*24*60*60; } // dia
		if ($tpp = 'F') { $tpc = 12*60*60; } // 12 horas
		if ($tpp = 'Q') { $tpc = 4*60*60; } // 4 horas
		if ($tpp = 'H') { $tpc = 1*60*60; } // 1 horas
		if ($tpp = 'T') { $tpc = 30*60; } // 30 min
		if ($tpp = 'S') { $tpc = 10*60; } // 10 min
		if ($tpp = 'X') { $tpc = -99999; } // expirar
		$tpc = ($tp * 60);
		}
	setcookie('nw_user','',time()+$tpc);
	}
?>