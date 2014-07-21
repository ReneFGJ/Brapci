<?
///////////////////////////////////////////
// BIBLIOTECA DE FUNÇÕS PHP ///////////////
////////////////////////////// criado por /
////////////////// Rene F. Gabriel Junior /
/////////////////    rene@sisdoc.com.br   /
///////////////////////////////////////////
// Versão atual           //    data     //
//---------------------------------------//
// 0.0a                       04/12/2008 //
///////////////////////////////////////////
if ($mostar_versao == True) {array_push($sis_versao,array("sisDOC (data dump)","0.0a",20081204)); }

function dump($ttt)
	{
	$size = 16;
	$h = '';
	$adr = 0;
	$c1='';
	$c0='';
	for ($ot=0;$ot < strlen($ttt);$ot=($ot+$size))
		{
		$c = substr($ttt,$ot,$size);
		$c2='';
		$addr = dechex($adr);
		while (strlen($addr) < 8) { $addr = '0'.$addr; }
		$c0 = '<TR align="center">';
		$c0 .= '<TD align="right"><TT>'.$adr.'</TD>';
		$c0 .= '<TD bgcolor="#F8F8F8"><TT>'.substr($addr,0,4).':'.substr($addr,4,4).'</TD><TD>&nbsp;&nbsp;</TD>';
		$c1 .= $c0;
		$adr = $adr + $size;
		/////////////////////
		for ($p=0;$p < strlen($c);$p++)
			{
			$ac = ord(substr($c,$p,1));
			$ch = strtoupper(dechex($ac));
			while (strlen($ch) < 2) { $ch = '0'.$ch; }
			if (($p > 0) and (intval($p/8) == ($p/8))) { $c1.='<TD bgcolor="#F0F0F0"><TT>-</TD>'; }
			$c1 .= '<TD bgcolor="#F0F0F0"><TT>'.$ch.'</TD>';
			}
		for ($pc=$p;$pc < $size;$pc++) { $c1 .= '<TD></TD>'; }
		for ($p=0;$p < strlen($c);$p++)
			{
			$ac = ord(substr($c,$p,1));
			if (($p > 0) and (intval($p/8) == ($p/8))) { $c2.='<TD bgcolor="#C8C8C8"><TT>-</TD>'; }
			if ($ac > 32) { $c2 .= '<TD bgcolor="#C8C8C8"><TT>'.substr($c,$p,1).'</TD>'; } else { $c2 .= '<TD bgcolor="#C8C8C8"><TT>.</TD>'; }
			}
			$c1 .= '<TD>&nbsp;&nbsp;</TD>'.$c2;
		}
		$x .= '<TABLE border="1" cellpadding="2" cellspacing="0">';
		$x .= $c1;
		$x .= '</TABLE>';
		echo '=='.$x;
	}
?>