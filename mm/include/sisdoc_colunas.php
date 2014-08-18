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
if ($mostar_versao == True) { array_push($sis_versao,array("sisDOC (Colunas)","0.0a",20080520)); }

global $vcol, $est_col;
$$vcol = 0;
$est_col = "onMouseOver=this.style.backgroundColor='#e4f7fa' onMouseOut=this.style.backgroundColor=''";

function Coluna()
	{
	global $vcol, $est_col;	
	if ($vcol ==0)
		{
		$xvcol = "";
		$vcol=1;
		}
	else
		{
		$xvol = ' bgcolor="#F0F0F0" ';
		$vcol=0;
		}
		$xvol = $xvol . $est_col;
		return($xvol  );
	}
?>