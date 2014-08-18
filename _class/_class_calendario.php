<?
/**
 * Class Calendario
 * @category SistemaApoio
 * @author Rene Faustino Gabriel Junior <monitoramento@sisdoc.com.br>
 * @copyright Copyright (c) 2011 - sisDOC.com.br
 * @license http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @package Classe
 * @version 0.12.04
 */
class calendario
	{
	var $mes;
	var $ano;
	var $dia;
	var $hora;
	var $evento_nome;
	var $evento_descricao;
	var $tipo;
	
	var $tabela = 'calendario';
	function cp()
		{
			$cp = array();
			array_push($cp,array('$H8','id_cal','key',False,True));
			array_push($cp,array('$D8','cal_data',msg('data'),True,True));			
			array_push($cp,array('$S5','cal_hora',msg('hora'),False,True));			
			array_push($cp,array('$O 1:'.msg('yes').'&0'.msg('no'),'cal_ativo',msg('ativo'),True,True));			
			array_push($cp,array('$S100','cal_nome',msg('nome'),True,True));			
			array_push($cp,array('$T50:3','cal_descricao',msg('descricao'),False,True));			
			array_push($cp,array('$S2','cal_tipo',msg('tipo'),False,True));			
			return($cp);			
		}
	function evento_inserir()
		{
			
		}	
	function evento_cancelar()
		{
			
		}
	function evento_mostrar_linha()
		{
		$sql = "select * from ".$this->tabela;
		$sql .= " where cal_data >= ".date("Ymd");
		$sql .= " order by cal_data, cal_hora ";
		$rlt = db_query($sql);
		$sx = '<style>'.chr(13);
		$sx .= '.tr_calendario {'.chr(13);
		$sx .= ' font-family: Verdana, Geneva, Arial, Helvetica, sans-serif; font-size: 12px; line-height: 150%; '.chr(13);
		$sx .= '}'.chr(13);
		$sx .= '</style>'.chr(13);
		$xmes = 0;
		while ($line = db_read($rlt))
			{
				$mes = round(substr($line['cal_data'],4,2));
				if ($xmes != $mes)
					{
						$sx .= '<TR bgcolor="#FFB5B5"><TD colspan="3" align="center">';
						$sx .= 	nomemes($mes).'/'.substr($line['cal_data'],0,4);
						$sx .= '<TR bgcolor="#FFB5B5">';
						$sx .= '<TH class="lt0" align="center">dia';
						$sx .= '<TH class="lt0" align="center">hora';
						$sx .= '<TH class="lt0" align="center">descricao';
						
						$xmes = $mes;		
					}
				$sx .= '<tr class="tr_calendario" valign="top">';
				$sx .= '<td><font class="lt4">';
				$sx .= substr($line['cal_data'],6,2);
				$sx .= '<td>';
				$sx .= $line['cal_hora'];
				$sx .= '<td>';
				$sx .= $line['cal_nome'];
			}	
		return($sx);
		}
	function evento_mostrar_calendario()
		{
			
		}
	function updatex()
		{
			
		}
	function structure()
		{
			
		}
	}
?>
