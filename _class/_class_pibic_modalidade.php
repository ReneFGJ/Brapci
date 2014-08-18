<?php
class pibic_modalidade
	{
		var $id_pm; 
		var $pm_codigo;
		var $pm_nome;
		var $pm_ativo;
	
		var $tabela = 'pibic_modalidade';
		
		function cp()
			{
				$cp = array();
				array_push($cp,array('$H8','id_pm','id',False,true));
				array_push($cp,array('$S1','pm_codigo','codigo',False,true));
				array_push($cp,array('$S100','pm_nome','Nome',False,true));
				array_push($cp,array('$O 1:SIM&0:NÃO','pm_ativo','id',False,true));
				return($cp);
			}
		function row()
			{
				global $cdf,$cdm,$masc;
				$cdf = array('id_pm','pm_nome','pm_codigo','pm_ativo');
				$cdm = array('cod',msg('nome'),msg('codigo'),msg('ativo'));
				$masc = array('','','SN','','','');
				return(1);				
			}
		function updatex()
			{
				return(1);
			}
	}
