<?
/**
* Esta classe é a responsável pelo tratamento de dados HTML
* @author Rene F. Gabriel Junior <rene@sisdoc.com.br>
* @version 0.0a
* @copyright Copyright © 2011, Rene F. Gabriel Junior.
* @access public
* @package BIBLIOTECA
* @subpackage sisdoc_html
*/
///////////////////////////////////////////
// Versão atual           //    data     //
//---------------------------------------//
// 0.0b                       06/05/2010 //
// 0.0a                       20/05/2008 //
///////////////////////////////////////////
if ($mostar_versao == True) {array_push($sis_versao,array("sisDOC (Html)","0.0b",20100506)); }
if (strlen($include) == 0) { exit; }
function HtmltoChar($dx)
	{
		$dx = troca($dx,'&amp;','&'); 
		$dx = troca($dx,'&aacute;','á'); 
		$dx = troca($dx,'&eacute;','é'); 
		$dx = troca($dx,'&iacute;','í'); 
		$dx = troca($dx,'&oacute;','ó'); 
		$dx = troca($dx,'&uacute;','ú'); 
		$dx = troca($dx,'&Aacute;','Á'); 
		$dx = troca($dx,'&Eacute;','É'); 
		$dx = troca($dx,'&Iacute;','Í'); 
		$dx = troca($dx,'&Oacute;','Ó'); 
		$dx = troca($dx,'&Uacute;','Ú'); 
		$dx = troca($dx,'&Atilde;','Ã');
		$dx = troca($dx,'&Otilde;','Õ');
		$dx = troca($dx,'&atilde;','ã');
		$dx = troca($dx,'&otilde;','õ');
		$dx = troca($dx,'&ccedil;','ç');
		$dx = troca($dx,'&Ccedil;','Ç');

		$dx = troca($dx,'&acirc;','â');
		$dx = troca($dx,'&ecirc;','ê');
		$dx = troca($dx,'&icirc;','î');
		$dx = troca($dx,'&ocirc;','ô');
		$dx = troca($dx,'&ucirc;','û');

		$dx = troca($dx,'&Acirc;','Â');
		$dx = troca($dx,'&Ecirc;','Ê');
		$dx = troca($dx,'&Icirc;','Î');
		$dx = troca($dx,'&Ocirc;','Ô');
		$dx = troca($dx,'&Ucirc;','Û');

		$dx = troca($dx,'&nbsp;',' ');
		$dx = troca($dx,'&agrave;','à');
		$dx = troca($dx,'&Agrave;','À');

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