<?php

class publication_type
	{
		var $tabela = 'brapci_journal_tipo';
		function cp()
			{
				$cp = array();
				array_push($cp,array('$H8','id_jtp','',False,True));
				array_push($cp,array('$S1','jtp_codigo',msg('cod'),False,True));
				array_push($cp,array('$S60','jtp_descricao',msg('name'),TrueTrue,True));
				array_push($cp,array('$[1-99]','jtp_ordem',msg('ord'),True,True));
				array_push($cp,array('$O 1:SIM&0:NÃO','jtp_ativo',msg('active'),False,True));
				return($cp);
			}
		function row()
			{
				global $cdf,$cdm,$masc;
				$cdf = array('id_jtp','jtp_descricao','jtp_ordem','jtp_codigo');
				$cdm = array('cod',msg('name'),msg('ord'),msg('cod'));
				$masc = array('','','','','','','','');
				return(1);				
			}	
	}
?>
