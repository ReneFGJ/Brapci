<?
/**
* Esta classe � a respons�vel pelo tratamento do formato ISO2709.
* @author Rene F. Gabriel Junior <rene@sisdoc.com.br>
* @version 0.0a
* @copyright Copyright � 2011, Rene F. Gabriel Junior.
* @access public
* @package BIBLIOTECA
* @subpackage sisdoc_iso2709
*/
///////////////////////////////////////////
// Vers�o atual           //    data     //
//---------------------------------------//
// 0.0a                       02/12/2008 //
///////////////////////////////////////////
if ($mostar_versao == True) { array_push($sis_versao,array("sisDOC (ISO2709)","0.0a",20081202)); }
if (strlen($include) == 0) { exit; }
function le_iso2709($iso)
	{
	$isis = array();
	///// Busca Tira os Enter no 81 caracter
	$isoc = '';
	$aa=0;
	///////////////////////// CONVERTE EM DADOS CONTINUOS
	while (strpos($iso,chr(29)) > 0 )
		{
		$pos = strpos($iso,chr(29));
		$isod = substr($iso,0,$pos);
		$ix = '';
		while (strlen($isod) > 0)
			{
//			$ix .= substr($isod,$kx,2);
			$ix .= substr($isod,0,80);
			$isod = substr($isod,82,strlen($isod));
			}
//		dump($ix);

		$doc = le_registro_iso2709($ix);
		array_push($isis,$doc);
//		echo '<TABLE border=1 width="600" align="center">';
//		for ($k=0;$k < count($doc);$k++)
//			{
//				$dados = substr($dado,intval($doc[$k][2]),intval($doc[$k][1])-1);
//			echo '<TR><TD width="50">'.$doc[$k][0].'</TD><TD>'.$doc[$k][3];
//			}
//		echo '</TABLE>';	
		////////////// �rea de escape
		$aa++;
		if ($aa > 1000) { exit; }
		$iso = substr($iso,$pos+3,strlen($iso));
		}
		return($isis);
	}
	
function le_registro_iso2709($reg)
	{
	$reg = troca($reg,'�','�');
	$doc = array();
	$guia = array();
	array_push($guia,substr($reg,0,5));
	array_push($guia,substr($reg,5,1));
	array_push($guia,substr($reg,6,1));
	array_push($guia,substr($reg,7,1));
	array_push($guia,substr($reg,8,1));
	array_push($guia,substr($reg,9,1));
	array_push($guia,substr($reg,10,1));
	array_push($guia,substr($reg,11,1));
	array_push($guia,substr($reg,12,5));
	array_push($guia,substr($reg,17,1));
	array_push($guia,substr($reg,18,1));
	array_push($guia,substr($reg,19,1));
	array_push($guia,substr($reg,20,1));
	array_push($guia,substr($reg,21,1));
	array_push($guia,substr($reg,22,1));
	array_push($guia,substr($reg,23,1));

	$size = intval(substr($reg,12,5));
//	$reg = substr($reg,5,strlen($reg));
	//////////////////////////////////////////////////////////////////////
	$dire = substr($reg,24,$size-24);
	$dado = substr($reg,$size,strlen($reg));
	while (strlen($dire) > 3)
		{
		$x1 = substr($dire,0,3);
		$x2 = substr($dire,3,4);
		$x3 = substr($dire,7,5);
		array_push($doc,array($x1,$x2,$x3,'',''));
		if (strlen($x1) == 3)
			{
			$dire = substr($dire,12,strlen($dire));
			}
		}
	for ($k=0;$k < count($doc);$k++)
		{
		$dados = substr($dado,intval($doc[$k][2]),intval($doc[$k][1])-1);
		$doc[$k][3] = $dados;
		}
	return($doc);
	}
?>
