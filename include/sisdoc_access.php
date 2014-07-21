<?php
/**
* Esta classe й a responsбvel pela conexгo com o banco de dados.
* @author Rene F. Gabriel Junior <rene@sisdoc.com.br>
* @version 0.0e
* @copyright Copyright © 2011, Rene F. Gabriel Junior.
* @access public
* @package BIBLIOTECA
* @subpackage sisdoc_access
*/
///////////////////////////////////////////
// Versгo atual           //    data     //
//---------------------------------------//
// 0.0a                       10/01/2011 //
///////////////////////////////////////////
if ($mostar_versao == True) { array_push($sis_versao,array("sisDOC (Access)","0.0a",20110110)); }

function access()
	{
	global $user_nivel, $us_nivel, $access, $access_ref;
	$usp = $user_nivel;
	$page = $_SERVER['SCRIPT_NAME'];
	$asql = "select * from access_ref where ar_page = '".$page."'";
	$arlt = db_query($asql);
	if (!($aline = db_read($arlt)))
		{ 
		$usql = "insert into access_ref (ar_ref,ar_descricao,ar_sistema,";
		$usql .= "ar_perf0,ar_perf1,ar_perf2,ar_perf3,ar_perf4,";
		$usql .= "ar_perf5,ar_perf6,ar_perf7,ar_perf8,ar_perf9,";
		$usql .= "ar_page";
		$usql .= ") values (";
		$usql .= "'','Sem descriзгo','',";
		$usql .= "'*','*','*','*','*',";
		$usql .= "'*','*','*','*','*',";
		$usql .= "'".$page."'";
		$usql .= ");";
		$arlt = db_query($usql);		
		$usql = "update access_ref set ar_ref = trim(to_char(id_ar,'00000')) where ar_ref = '' ";
		$arlt = db_query($usql);
//		//////////////////////////////// Busca novo cуdigo
		$arlt = db_query($asql);
		$aline = db_read($arlt);
		}
	$access_ref = $aline['ar_ref'];
	$access = $aline['ar_perf'.$usp];
	
	if ($access == '-')
		{ echo 'Acesso restrito'; exit; }
	return($access);
	}
?>