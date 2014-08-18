<?php
class submit_manuscrito_tipo
	{
		var $tabela = 'submit_manuscrito_tipo';
		
		function cp()
			{
				$cp = array();
				array_push($cp,array('$H8','id_sp','id',False,true));
				array_push($cp,array('$S5','sp_codigo',msg('codigo'),False,true));
				array_push($cp,array('$S50','sp_descricao',msg('nome'),False,true));

				array_push($cp,array('$[1-50]','sp_ordem',msg('nome'),False,true));
				array_push($cp,array('$T60:4','sp_content',msg('content'),False,true));
				array_push($cp,array('$S7','sp_nucleo',msg('nucle'),False,true));
				array_push($cp,array('$T60:4','sp_caption',msg('caption'),False,true));
				array_push($cp,array('$S5','journal_id',msg('Journal'),False,true));
				array_push($cp,array('$S5','sp_idioma',msg('idioma'),False,true));

				array_push($cp,array('$O 1:SIM&0:NÃO','sp_ativo',msg('ativo'),False,true));
				return($cp);
			}
		function row()
			{
				global $cdf,$cdm,$masc;
				$cdf = array('id_sp','sp_descricao','sp_codigo','sp_ativo','journal_id');
				$cdm = array('cod',msg('nome'),msg('codigo'),msg('ativo'),msg('journal'));
				$masc = array('','','','SN','','','');
				return(1);				
			}
		function updatex()
			{
				global $base;
				$c = 'sp';
				$c1 = 'id_'.$c;
				$c2 = $c.'_codigo';
				$c3 = 5;
				$sql = "update ".$this->tabela." set $c2 = lpad($c1,$c3,0) where $c2='' ";
				if ($base=='pgsql') { $sql = "update ".$this->tabela." set $c2 = trim(to_char(id_".$c.",'".strzero(0,$c3)."')) where $c2='' "; }
				$rlt = db_query($sql);
			}			
			
	}
?>
