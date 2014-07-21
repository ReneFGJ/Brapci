<?
/**
* Esta classe é a responsável pela conexão com o banco de dados.
* @author Rene F. Gabriel Junior <rene@sisdoc.com.br>
* @version 0.0a
* @copyright Copyright © 2011, Rene F. Gabriel Junior.
* @access public
* @package BIBLIOTECA
* @subpackage sisdoc_tips
*/
///////////////////////////////////////////
// Versão atual           //    data     //
//---------------------------------------//
// 0.0a                       13/05/2008 //
///////////////////////////////////////////
//
// Alterado de
// Link original: http://forum.wmonline.com.br/index.php?showtopic=182224
if ($mostar_versao == True) {array_push($sis_versao,array("sisDOC (Tips)","0.0a",20080513)); }
if (strlen($include) == 0) { exit; }

global $tips_obj;
$tips_obj = 0;
function tips($cx1,$cx2)
	{
	global $tips_obj;
	$tips_obj++;
	$csi = "cdint".$tips_obj;
	$cs = '<span id="'.$csi.'">';
	$cs .= $cx1;
	$cs .= '</span>';
//	$cs .= '<div id="'.$csi.'i" class="tips" style="visibility:hidden;">';
	$cs .= '<div id="'.$csi.'i" class="form_tips_full">';
	$cs .= ''.$cx2;
	$cs .= '</div>';
	$cs .= chr(13).'<script>';
	$cs .= chr(13).' alert($(#'.$csi.'i").hidden()); ';
	$cs .= chr(13).' $("#'.$csi.'").click(function() { ';
	$cs .= chr(13).' 	$("#'.$csi.'i").fadeOut(\'slow\');';
	$cs .= chr(13).' });';
	$cs .= chr(13).'</script>';
	$cs .= chr(13);
	return($cs);
	}
?>
