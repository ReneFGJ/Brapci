<?php
class pibic_status
	{
		var $id_ps; 
		var $ps_codigo;
		var $ps_nome;
		var $ps_ativo;
	
		var $tabela = 'pibic_status';
		
		function cp()
			{
				$cp = array();
				array_push($cp,array('$H8','id_ps','id',False,true));
				array_push($cp,array('$S1','ps_codigo',msg('codigo'),False,true));
				array_push($cp,array('$S100','ps_nome',msg('nome'),False,true));
				array_push($cp,array('$O 1:SIM&0:NÃO','ps_ativo',msg('ativo'),False,true));
				return($cp);
			}
		function row()
			{
				global $cdf,$cdm,$masc;
				$cdf = array('id_ps','ps_nome','ps_codigo','ps_ativo');
				$cdm = array('cod',msg('nome'),msg('codigo'),msg('ativo'));
				$masc = array('','','','SN','','','');
				return(1);				
			}
		function updatex()
			{
				return(1);
			}
	}
