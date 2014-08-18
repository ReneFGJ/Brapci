<?php
class pibic_documento_tipo
	{
		var $id_ps; 
		var $ps_codigo;
		var $ps_nome;
		var $ps_ativo;
	
		var $tabela = 'pibic_ged_documento_tipo';
		
		function cp()
			{
				global $messa;
				$cp = array();
				array_push($cp,array('$H8','id_doct','id',False,true));
				array_push($cp,array('$S5','doct_codigo',msg('codigo'),true,true));
				array_push($cp,array('$S100','doct_nome',msg('nome'),true,true));
				array_push($cp,array('$O 1:SIM&0:NÃO','doct_ativo',msg('ativo'),False,true));
				
				array_push($cp,array('$O 1:SIM&0:NÃO','doct_publico',msg('publico'),False,true));
				array_push($cp,array('$O 1:SIM&0:NÃO','doct_avaliador',msg('avaliador'),False,true));
				array_push($cp,array('$O 1:SIM&0:NÃO','doct_autor',msg('autor'),False,true));
				array_push($cp,array('$O 0:NÃO&1:SIM','doct_restrito',msg('restrito'),False,true));

				return($cp);
			}
		function row()
			{
				global $cdf,$cdm,$masc;
				$cdf = array('id_doct','doct_nome','doct_codigo','doct_publico','doct_avaliador','doct_autor','doct_restrito','doct_ativo');
				$cdm = array('cod',msg('nome'),msg('codigo'),msg('publico'),msg('avaliador'),msg('autor'),msg('restrito'),msg('ativo'));
				$masc = array('','','','SN','SN','SN','SN','SN');
				return(1);				
			}
		function updatex()
			{
				return(1);
			}
	}
