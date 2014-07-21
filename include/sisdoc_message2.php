<?
///////////////////////////////////////////
// BIBLIOTECA DE FUNÇÕS PHP ///////////////
////////////////////////////// criado por /
////////////////// Alexandre Koutton      /
/////////////// koutton@fonzaghi.com.br   /
///////////////////////////////////////////
// Versão atual           //    data     //
//---------------------------------------//
// 0.0a                       10/06/2010 //
///////////////////////////////////////////

require("letras.css");

if ($mostar_versao == True) { array_push($sis_versao,array("sisDOC (Message 2)","0.0a",20100610)); }

function msg_info($msg){
	$saida =  '<br><br><CENTER><font class="lt3"><b>'.$msg.'</b></font></CENTER>';
	return ($saida);
}

function msg_erro($msg){
	$saida =  '<br><br><CENTER><font class="lt3"><b><font color="#ff0000">'.$msg.'</font></b></font></CENTER>';
	return ($saida);	
}
?>