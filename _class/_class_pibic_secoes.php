<?php
class pibic_secoes
	{
		var $id_pse; 
		var $pse_codigo;
		var $pse_nome;
		var $pse_ativo;
		var $pse_ordem;
		var $pse_ref;
	
		var $tabela = 'pibic_secoes';
		
		function cp()
			{
				$cp = array();
				array_push($cp,array('$H8','id_pse','id',False,true));
				array_push($cp,array('$H8','pse_codigo',msg('codigo'),False,true));
				array_push($cp,array('$S10','pse_ref',msg('codigo'),true,true));
				array_push($cp,array('$S100','pse_nome',msg('nome'),true,true));
				array_push($cp,array('$O 1:SIM&0:NÃO','pse_ativo',msg('ativo'),true,true));
				array_push($cp,array('$Q psa_nome:psa_codigo:select * from pibic_areas where psa_ativo=1 order by psa_ordem','pse_area',msg('area'),true,true));
				array_push($cp,array('$[1-50]','pse_ordem',msg('ordem'),true,true));
				return($cp);
			}
		function row()
			{
				global $cdf,$cdm,$masc;
				$cdf = array('id_pse','pse_nome','pse_codigo','pse_area','pse_ativo','pse_ordem');
				$cdm = array('cod',msg('nome'),msg('codigo'),msg('area'),msg('ativo'),msg('ordem'));
				$masc = array('','','','','SN','','','');
				return(1);				
			}
		function updatex()
			{
				$c = 'pse';
				$c1 = 'id_'.$c;
				$c2 = $c.'_codigo';
				$c3 = 4;
				$sql = "update ".$this->tabela." set $c2 = lpad($c1,$c3,0) where $c2='' ";
				$rlt = db_query($sql);
			}
	}
