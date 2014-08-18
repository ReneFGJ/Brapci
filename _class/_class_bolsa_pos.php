<?php
class bolsa_pos
	{
		var $tabela = "bolsa_pos_tipo";
		
		function row()
			{
				global $cdf,$cdm,$masc;
				$cdf = array('id_bs','bs_codigo','bs_descricao','bs_internacional','bs_publico');
				$cdm = array('cod',msg('codigo'),msg('descricao'),msg('abrangencia'),msg('publico'));
				$masc = array('','','','','','','','');
				return(1);				
			}
		function cp()
			{
				$cp = array();
				array_push($cp,array('$H8','id_bs','',False,True));
				array_push($cp,array('$H8','bs_codigo','',False,True));
				array_push($cp,array('$S60','bs_descricao','Descrição',False,True));
				array_push($cp,array('$T60:3','bs_obs','Obs',False,True));
				array_push($cp,array('$O : &I:Internacional&N:Nacional','bs_internacional','Tipo',False,True));
				array_push($cp,array('$O D:Docente&I:Discente','bs_publico','Público',False,True));
				return($cp);
			}
		function updatex()
			{
				global $base;
				$c = 'bs';
				$c1 = 'id_'.$c;
				$c2 = $c.'_codigo';
				$c3 = 3;
				if ($base=='pgsql') { $sql = "update ".$this->tabela." set $c2 = trim(to_char(id_".$c.",'000')) where $c2 = '' "; }
				$rlt = db_query($sql);				
			}
		function strucuture()
			{
				$sql = "create table bolsa_pos_tipo
					(
						id_bs serial not null,
						bs_codigo char(5),
						bs_descricao char(60),
						bs_obs text,
						bs_internacional char(1),
						bs_publico char(1)	
					)
				";
				//$rlt = db_query($sql);
			}
	}
