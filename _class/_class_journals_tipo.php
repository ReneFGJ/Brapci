<?php
class journals_tipo
	{
		var $id_ps; 
		var $ps_codigo;
		var $ps_nome;
		var $ps_ativo;
	
		var $tabela = 'journals_tipo';
		
		function cp()
			{
				global $messa;
				$cp = array();
				array_push($cp,array('$H8','id_jt','id',False,true));
				array_push($cp,array('$H5','jt_codigo',msg('codigo'),False,true));
				array_push($cp,array('$S100','jt_nome',msg('nome'),true,true));
				array_push($cp,array('$O 1:SIM&0:NÃO','jt_ativo',msg('ativo'),true,true));
				array_push($cp,array('$O 1:SIM&0:NÃO','jt_evento',msg('journal'),true,true));
				array_push($cp,array('$O 1:SIM&0:NÃO','jt_anais',msg('anais'),true,true));
				return($cp);
			}
		function row()
			{
				global $cdf,$cdm,$masc;
				$cdf = array('id_jt','jt_nome','jt_codigo','jt_evento','jt_anais','jt_ativo');
				$cdm = array('cod',msg('nome'),msg('codigo'),msg('evento'),msg('anais'),msg('ativo'));
				$masc = array('','','','SN','SN','SN','SN','SN');
				return(1);				
			}
		function structure()
			{
				$sql = "CREATE TABLE journals_tipo (
					id_jt serial NOT NULL,
					jt_codigo char(3),
					jt_nome char(100),
					jt_ativo int2,
					jt_evento int2,
					jt_evento_anais int2,
					jt_anais int2 
					)
				";
				$rlt = db_query($sql);
				return(1);
			}
		function updatex()
		{
			global $base;
			$c = 'jt';
			$c1 = 'id_'.$c;
			$c2 = $c.'_codigo';
			$c3 = 3;
			$sql = "update ".$this->tabela." set $c2 = lpad($c1,$c3,0) where $c2='' ";
			if ($base=='pgsql') { $sql = "update ".$this->tabela." set $c2 = trim(to_char(id_".$c.",'".strzero(0,$c3)."')) where $c2='' "; }
			$rlt = db_query($sql);
		}
	}
