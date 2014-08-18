<?php
/**
 * Class Idiomas do Sistema
 * @category SistemaApoio
 * @author Rene Faustino Gabriel Junior <monitoramento@sisdoc.com.br>
 * @copyright Copyright (c) 2011 - sisDOC.com.br
 * @license http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @package Classe
 * @version 0.12.04
 */

class apoio_idioma
	{
		var $id_i; 
		var $i_codigo;
		var $i_nome;
		var $i_ativo;
	
		var $tabela = 'apoio_idioma';
		
/**
 * Campos de Edição CP
 */
		function cp()
			{
				$cp = array();
				array_push($cp,array('$H8','id_i','id',False,true));
				array_push($cp,array('$S5','i_codigo',msg('codigo'),true,true));
				array_push($cp,array('$S40','i_nome',msg('nome'),true,true));
				array_push($cp,array('$O 1:SIM&0:NÃO','i_ativo',msg('ativo'),true,true));
				return($cp);
			}
			
/**
 * Campos para visualização em Browser
 */
		function row()
			{
				global $cdf,$cdm,$masc;
				$cdf = array('id_i','i_nome','i_codigo','i_ativo');
				$cdm = array('cod',msg('nome'),msg('codigo'),msg('ativo'));
				$masc = array('','','','SN','','','');
				return(1);				
			}
			
/**
 * Atualização dos códigos da tabela
 */
		function updatex()
			{
				return(1);
			}
	}
