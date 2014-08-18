<?
/**
 * Class Calendario tipo de evento
 * @category SistemaApoio
 * @author Rene Faustino Gabriel Junior <monitoramento@sisdoc.com.br>
 * @copyright Copyright (c) 2011 - sisDOC.com.br
 * @license http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @package Classe
 * @version 0.12.04
 */
class calendario_tipo
	{
	var $id_ct;
	var $ct_descricao;
	var	$ct_ativo;
	var $ct_ambiente;
	
	var $tabela = 'calendario_tipo';
/**
 * Campos de Ediчуo CP
 */
	function cp()
		{
			$cp = array();
			array_push($cp,array('$H8','id_ct','key',False,True));
			array_push($cp,array('$S50','ct_descricao','',True,True));			
			array_push($cp,array('$O 1:'.msg('yes').'&0'.msg('no'),'ct_ativo','',True,True));			
			array_push($cp,array('$S2','ct_ambiente','',True,True));			
			array_push($cp,array('$H2','ct_codigo','',False,True));			
			return($cp);
		}
	function updatex()
		{
			return(1);
		}
	}
?>