<?
class campus
	{
		var $id_campus;
		var $campus_nome;
		var $campus_codigo;
		var $campus_tipo;
		var $campus_sigla;
		var $campus_equivalente;
		
		var $tabela = "campus";
		
		function cp()
			{			
				$cp = array();
				array_push($cp,array('$H8','id_campus','',False,True));
				array_push($cp,array('$S100','campus_nome',msg('campus'),True,True));
				array_push($cp,array('$H8','campus_codigo','',False,True));
				array_push($cp,array('$O 1:campus','campus_tipo',msg('tipo'),True,True));
				array_push($cp,array('$S10','campus_sigla',msg('sigla'),False,True));
				array_push($cp,array('$S10','campus_equivalente',msg('equi'),False,True));
				array_push($cp,array('$O 1:SIM&0:NÃO','campus_ativo',msg('ativo'),False,True));
						
				return($cp);
			}
		function row()
			{
				global $cdf,$cdm,$masc;
				$cdf = array('id_campus','campus_nome','campus_sigla','campus_ativo','campus_codigo');
				$cdm = array('cod',msg('campus'),msg('codigo'),msg('ativo'),msg('codigo'));
				$masc = array('','','','SN','','','');
				return(1);				
			}

		function structure()
			{
				$sql = "
				CREATE TABLE campus
					( 
					id_campus serial NOT NULL, 
					campus_codigo char(5), 
					campus_nome char(100), 
					campus_sigla char(10), 
					campus_equivalente char(10), 
					campus_ativo int8, 
					campus_tipo int8
					); ";
				$rlt = db_query($sql);
			}
		function updatex()
			{
				global $base;
				$c = 'campus';
				$c1 = 'id_'.$c;
				$c2 = $c.'_codigo';
				$c3 = 5;
				$sql = "update ".$this->tabela." set $c2 = lpad($c1,$c3,0) where $c2='' ";
				$sql = "update ".$this->tabela." set $c2 = lpad($c1,$c3,0) where $c2='' ";
				if ($base=='pgsql') { $sql = "update ".$this->tabela." set $c2 = trim(to_char(id_".$c.",'".strzero(0,$c3)."')) where $c2='' "; }
				$rlt = db_query($sql);				
			}
		
	}
