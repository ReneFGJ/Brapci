<?php
class pibic_areas
	{
		var $id_psa; 
		var $psa_ref;
		var $psa_nome;
		var $psa_ativo;
		var $psa_ordem;
		var $psa_codigo;
	
		var $tabela = 'pibic_areas';
		
		function cp()
			{
				$cp = array();
				array_push($cp,array('$H8','id_psa','id',False,true));
				array_push($cp,array('$H','psa_codigo',msg('codigo'),False,true));
				array_push($cp,array('$S5','psa_ref',msg('codigo'),true,true));
				array_push($cp,array('$S100','psa_nome',msg('nome'),true,true));
				array_push($cp,array('$O 1:SIM&0:NÃO','psa_ativo',msg('ativo'),true,true));
				array_push($cp,array('$[1-50]','psa_ordem',msg('ordem'),true,true));
				return($cp);
			}
		function row()
			{
				global $cdf,$cdm,$masc;
				$cdf = array('id_psa','psa_nome','psa_ref','psa_ativo','psa_ordem');
				$cdm = array('cod',msg('nome'),msg('codigo'),msg('ativo'),msg('ordem'));
				$masc = array('','','','SN','','','');
				return(1);				
			}
		function updatex()
			{
				$c = 'psa';
				$c1 = 'id_'.$c;
				$c2 = $c.'_codigo';
				$c3 = 5;
				$sql = "update ".$this->tabela." set $c2 = lpad($c1,$c3,0) where $c2='' ";
				$rlt = db_query($sql);
			}
	}
