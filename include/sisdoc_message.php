<?
/**
* Esta classe é a responsável pelo tratamento de mensagens do sistema.
* @author Rene F. Gabriel Junior <rene@sisdoc.com.br>
* @version 0.0a
* @copyright Copyright © 2011, Rene F. Gabriel Junior.
* @access public
* @package BIBLIOTECA
* @subpackage mensagens
*/
///////////////////////////////////////////
// Versão atual           //    data     //
//---------------------------------------//
// 0.0b                       06/05/2010 //
// 0.0a                       13/09/2009 //
///////////////////////////////////////////
if ($mostar_versao == True) { array_push($sis_versao,array("sisDOC (Message)","0.0b",20100506)); }
if (strlen($include) == 0) { exit; }
?>
<style>
/*========================= CAIXAS DE MENSAGEM =========================*/

.info, .ok, .warn, .error {
	border: 1px solid;
	background-repeat: no-repeat;
	background-position: 5px 5px;
	padding: .5em .5em .5em 30px;
	font-size: 90%;
	margin-left: 1.5em;
	margin-right: 1.5em;
	text-align: justify;
}

/*---------- Caixas Informativas - azul - Com um icone (i) ----------*/
.info {
	border-color: #000099;
	background-color: #ECF5FF;
	background-image: URL(img/ico_info.gif);
	color: #000033;
}
</style>
<?
/**
* Func msg_info - Mostra o conteúdo de um texto com o estilo info
*/
function msg_info($mensg)
	{
	$sa = '<fieldset class="info"><legend>Nota:</legend>';
	$sa .= $mensg.'</fieldset>';
	return($sa);
	}

function msg_alert($mensg)
	{
	global $include;
	$sa = '<div style="background-color: White; border: thin solid Black; padding: 2px 2px 2px 2px; width: 300px;">';
	$sa .= '<table width="300" cellpadding="0" cellspacing="0">';
	$sa .= '<TR><TD>';
	$sa .= '<img src="'.$include.'img/icone_alert.jpg" width="50" height="50" alt="">';
	$sa .= '</TD><TD>';
	$sa .= '<font color="#000000">'.$mensg.'</font>';
	$sa .= '</TD></TR>';
	$sa .= '</table>';
	$sa .= '</div>';
	return($mensg);
	}
function msg_erro($mensg)
	{
	global $include;
	$sa = '<div style="background-color:#fff4f4; border: thin solid Black; padding: 2px 2px 2px 2px; width: 300px;">';
	$sa .= '<table width="300" cellpadding="0" cellspacing="0">';
	$sa .= '<TR><TD>';
	$sa .= '<img src="'.$include.'img/icone_erro.gif" width="50" height="50" alt="">';
	$sa .= '</TD><TD>';
	$sa .= '<font color="#000000">'.$mensg.'</font>';
	$sa .= '</TD></TR>';
	$sa .= '</table>';
	$sa .= '</div>';
	return($mensg);	
	}
	
?>