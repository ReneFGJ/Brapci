<?
///////////////////////////////////////////
// BIBLIOTECA DE FUNгуS PHP ///////////////
////////////////////////////// criado por /
////////////////// Rene F. Gabriel Junior /
/////////////////    rene@sisdoc.com.br   /
///////////////////////////////////////////
// VersЦo atual           //    data     //
//---------------------------------------//
// 0.0b                       06/05/2010 //
// 0.0a                       20/05/2008 //
///////////////////////////////////////////
if ($mostar_versao == True) {array_push($sis_versao,array("sisDOC (Html)","0.0b",20100506)); }

function HtmltoChar($dx)
	{
		$dx = troca($dx,'&amp;','&'); 
		$dx = troca($dx,'&aacute;','А'); 
		$dx = troca($dx,'&eacute;','И'); 
		$dx = troca($dx,'&iacute;','М'); 
		$dx = troca($dx,'&oacute;','С'); 
		$dx = troca($dx,'&uacute;','З'); 
		$dx = troca($dx,'&Aacute;','а'); 
		$dx = troca($dx,'&Eacute;','и'); 
		$dx = troca($dx,'&Iacute;','м'); 
		$dx = troca($dx,'&Oacute;','с'); 
		$dx = troca($dx,'&Uacute;','з'); 
		$dx = troca($dx,'&Atilde;','ц');
		$dx = troca($dx,'&Otilde;','у');
		$dx = troca($dx,'&atilde;','Ц');
		$dx = troca($dx,'&otilde;','У');
		$dx = troca($dx,'&ccedil;','Г');
		$dx = troca($dx,'&Ccedil;','г');

		$dx = troca($dx,'&acirc;','Б');
		$dx = troca($dx,'&ecirc;','Й');
		$dx = troca($dx,'&icirc;','Н');
		$dx = troca($dx,'&ocirc;','Т');
		$dx = troca($dx,'&ucirc;','Ш');

		$dx = troca($dx,'&Acirc;','б');
		$dx = troca($dx,'&Ecirc;','й');
		$dx = troca($dx,'&Icirc;','н');
		$dx = troca($dx,'&Ocirc;','т');
		$dx = troca($dx,'&Ucirc;','ш');

		$dx = troca($dx,'&nbsp;',' ');
		$dx = troca($dx,'&agrave;','Ю');
		$dx = troca($dx,'&Agrave;','ю');

		$dx = troca($dx,'  ',' ');
	return($dx);
	}
function HtmlToTxt($dx)
	{
	$dx = troca($dx,chr(13),'');
	$dx = troca($dx,chr(10),'');
	$dx = troca($dx,chr(9),' ');
	$dx = troca($dx,'<TR',chr(13).'<TR');
	$dx = troca($dx,'<tr',chr(13).'<TR');
	$ddb ='';
	$k = 0;
	for ($kk=0;$kk<strlen($dx);$kk++)
		{
//		echo $ch.'=';
		$ch = substr($dx,$kk,1);
		if (($ch=='<') or ($ch=='>'))
			{
			if ($ch =='<') { $k++; }
			if ($ch =='>') { $k--; }
			} else
			{	if ($k==0)
					{
					$ddb .= $ch;
					}
			}
		}
	return(HtmltoChar($ddb));
	}
?>