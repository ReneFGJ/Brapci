<?php
class patrocinadores
	{
		var $tabela = "patrocinadores";
		var $tabela_journals = "patrocinadores_journal";
		var $tabela_join = "(select journals.journal_id as jid, * from patrocinadores_journal 
							left join journals on journals.journal_id = patrocinadores_journal.journal_id
							left join patrocinadores on patrocinadores_id = id_patro) as patrocinadores_journal ";		
		
		function cp_patro()
			{
				$cp = array();
				array_push($cp,array('$H8','id_patro','id',False,true));
				array_push($cp,array('$Q title:journal_id:select * from journals order by title','journal_id','Journal',False,true));
				array_push($cp,array('$Q patro_descricao:id_patro:select * from patrocinadores ','patrocinadores_id','lProtocinador',False,true));
				return($cp);
			}
			
		function row_patro()
			{
				global $cdf,$cdm,$masc;
				$cdf = array('id_pj','title','patro_descricao','patro_tipo');
				$cdm = array('cod',msg('nome'),msg('tipo'),msg('ativo'));
				$masc = array('','','','','','','','');
				return(1);				
			}			
		function cp()
			{
				$cp = array();
				array_push($cp,array('$H8','id_patro','id',False,true));
				array_push($cp,array('$S100','patro_descricao','Descrição',False,true));
				array_push($cp,array('$S100','patro_imagem','link da imagem',False,true));
				array_push($cp,array('$S100','patro_link','link oficial da instituição',False,true));
				array_push($cp,array('$T60:4','patro_texto','contexto',False,true));
				array_push($cp,array('$O : &P:Patrocinador&C:Créditos&B:Bases de dados','patro_tipo','Tipo',False,true));
				array_push($cp,array('$U8','patro_dt_criacao','',False,true));
				
				return($cp);
			}

		function row()
			{
				global $cdf,$cdm,$masc;
				$cdf = array('id_patro','patro_descricao','patro_tipo','patro_ativo');
				$cdm = array('cod',msg('nome'),msg('tipo'),msg('ativo'));
				$masc = array('','','','SN','','','SN','');
				return(1);				
			}			
		
		function strucuture()
			{
				$sql = "
				CREATE TABLE patrocinadores
				( 
				id_patro serial NOT NULL, 
				patro_descricao char(100), 
				patro_imagem char(100), 
				patro_link char(100), 
				patro_texto text, 
				patro_tipo char(1) DEFAULT 'P'::bpchar, 
				patro_ativo char(1) DEFAULT 'S'::bpchar, 
				patro_dt_criacao int8 
				);";
				$rlt = db_query($sql);
				
				$sql = "
				CREATE TABLE patrocinadores_journal
				( 
				id_pj serial NOT NULL, 
				journal_id int8, 
				patrocinadores_id int8 
				);
				";
				$rlt = db_query($sql); 
			}
		function updatex()
			{
				return(1);
				exit;
				global $base;
				$c = 'patro';
				$c1 = 'id_'.$c;
				$c2 = $c.'_codigo';
				$c3 = 5;
				$sql = "update ".$this->tabela." set $c2 = lpad($c1,$c3,0) where $c2='' ";
				if ($base=='pgsql') { $sql = "update ".$this->tabela." set $c2 = trim(to_char(id_".$c.",'".strzero(0,$c3)."')) where $c2='' "; }
				$rlt = db_query($sql);
			}			
	}
	
?>
