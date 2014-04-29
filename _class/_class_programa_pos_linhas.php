<?php
class pos_linha
	{
		var $tabela = 'programa_pos_linhas';
		
		function cp()
			{
				$cp = array();
						
				$opa = '&1:ano indefinido';
				for ($r=1950;$r<=date("Y");$r++)
					{$opa .= '&'.$r.':'.$r; }
				array_push($cp,array('$H8','id_posln','id',False,true));
				array_push($cp,array('$H5','posln_codigo',msg('codigo'),False,true));
				array_push($cp,array('$S100','posln_descricao',msg('nome'),true,true));
				
				array_push($cp,array('$Q pos_nome:pos_codigo:select * from programa_pos order by pos_nome','posln_programa',msg('Programa'),True,True,''));
				
				array_push($cp,array('$O 1:SIM&0:N�O','posln_ativo',msg('ativo'),true,true));

				array_push($cp,array('$O : '.$opa,'posln_ano_entrada',msg('abertura_ano'),true,true));
				array_push($cp,array('$O : '.$opa,'posln_ano_saida',msg('encerramento_ano'),true,true));
				
				array_push($cp,array('$T80:5','posln_tema',msg('tema_de_pesquisa'),true,true));
				
				return($cp);
			}
			
		function row()
			{
				global $cdf,$cdm,$masc;
				$cdf = array('id_posln','posln_descricao','posln_ano_entrada','posln_ano_saida','posln_ativo');
				$cdm = array('cod',msg('nome'),msg('ano_start'),msg('ano_end'),msg('ativo'));
				$masc = array('','','','','SN','');
				return(1);				
			}
						
		function structure()
			{
			$sql = "CREATE TABLE ".$this->tabela." (
				id_posln SERIAL NOT NULL ,
				posln_codigo CHAR( 7 ) ,
				posln_programa CHAR( 7 ) ,
				posln_descricao CHAR( 100 ) NOT NULL ,
				posln_ano_entrada INT NOT NULL ,
				posln_ano_saida INT NOT NULL ,
				posln_tema text,
				posln_ativo INT NOT NULL )";
			//	$rlt = db_query($sql);
			return(1);			
			}
			
		function updatex()
			{
					global $base;
				$c = 'posln';
				$c1 = 'id_'.$c;
				$c2 = $c.'_codigo';
				$c3 = 5;
				$sql = "update ".$this->tabela." set $c2 = lpad($c1,$c3,0) where $c2='' ";
				if ($base=='pgsql') { $sql = "update ".$this->tabela." set $c2 = trim(to_char(id_".$c.",'".strzero(0,$c3)."')) where $c2='' "; }
				$rlt = db_query($sql);
			}	
	}
?>
