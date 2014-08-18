<?
class centro
	{
		var $id_centro;
		var $centro_nome;
		var $centro_codigo;
		var $centro_tipo;
		var $centro_sigla;
		var $centro_equivalente;
		
		var $tabela = "centro";
		
		function cp()
			{
				$sql = "ALTER TABLE centro ADD COLUMN centro_email_1 char(89) ";
				//$rlt = db_query($sql);
				$sql = "ALTER TABLE centro ADD COLUMN centro_email_2 char(89) ";
				//$rlt = db_query($sql);
				
				$cp = array();
				array_push($cp,array('$H8','id_centro','',False,True));
				array_push($cp,array('$S100','centro_nome',msg('centro'),True,True));
				array_push($cp,array('$H8','centro_codigo','',False,True));
				
				array_push($cp,array('$Q pp_nome:pp_cracha:select * from pibic_professor where pp_ativo = 1 order by pp_nome','centro_decano','Decano',False,True));
				//array_push($cp,array('$Q pp_nome:pp_cracha:select * from pibic_professor where pp_ativo = 1 order by pp_nome ','centro_decano_adj','Decano Adjunto',False,True));
				
				array_push($cp,array('$S100','centro_email_1','e-mail do Decanato (1)',False,True));
				array_push($cp,array('$S100','centro_email_2','e-mail do Decanato (2)',False,True));
				
				array_push($cp,array('$O 1:Centro&2:Área','centro_tipo',msg('tipo'),True,True));
				array_push($cp,array('$S10','centro_sigla',msg('sigla'),False,True));
				array_push($cp,array('$S10','centro_equivalente',msg('equi'),False,True));
				array_push($cp,array('$O 1:SIM&0:NÃO','centro_ativo',msg('ativo'),False,True));
				
				array_push($cp,array('$I8','centro_meta_01',msg('meta_01'),False,True));
				array_push($cp,array('$I8','centro_meta_02',msg('meta_02'),False,True));
				array_push($cp,array('$I8','centro_meta_03',msg('meta_03'),False,True));
				array_push($cp,array('$I8','centro_meta_04',msg('meta_04'),False,True));
				array_push($cp,array('$I8','centro_meta_05',msg('meta_05'),False,True));
				array_push($cp,array('$I8','centro_meta_06',msg('meta_06'),False,True));
				array_push($cp,array('$I8','centro_meta_07',msg('meta_07'),False,True));
				
				$opx = '&E:Ciências Exatas e da Terra';
				$opx .= '&B:Ciências Biológicas';
				$opx .= '&E:Engenharias';
				$opx .= '&S:Ciências da Saúde';
				$opx .= '&A:Ciências Agrárias';
				$opx .= '&S:Ciências Sociais Aplicadas';
				$opx .= '&H:Ciências Humanas';
				$opx .= '&L:Lingüística, Letras e Artes';
				
				array_push($cp,array('$O : &-:Não categorizado'.$opx,'centro_area',msg('area_conhecimento'),False,True));
				
				return($cp);
			}
		function row()
			{
				global $cdf,$cdm,$masc;
				$cdf = array('id_centro','centro_nome','centro_area','centro_sigla','centro_ativo','centro_meta_01','centro_meta_02','centro_meta_03','centro_meta_04','centro_meta_05','centro_meta_06');
				$cdm = array('cod',msg('centro'),msg('area'),msg('codigo'),msg('ativo'),msg('meta01'),msg('meta02'),msg('meta03'),msg('meta04'),msg('meta05'));
				$masc = array('','','','SN','','','');
				return(1);				
			}
		function updatex()
			{
				global $base;
				$c = 'centro';
				$c1 = 'id_'.$c;
				$c2 = $c.'_codigo';
				$c3 = 5;
				$sql = "update ".$this->tabela." set $c2 = lpad($c1,$c3,0) where $c2='' ";
				$sql = "update ".$this->tabela." set $c2 = lpad($c1,$c3,0) where $c2='' ";
				if ($base=='pgsql') { $sql = "update ".$this->tabela." set $c2 = trim(to_char(id_".$c.",'".strzero(0,$c3)."')) where $c2='' "; }
				$rlt = db_query($sql);				
			}
		
	}
