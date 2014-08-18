<?php
class sections
	{
	var $tabela = 'sections';

	function cp()
		{
		global $jid;
		$cp = array();
		array_push($cp,array('$H8','section_id','id_ed',False,True,''));
//		array_push($cp,array('$Q title:journal_id:select * from journals where journal_id = '.round($jid),'journal_id','Publicação',False,True,''));
		array_push($cp,array('$Q title:journal_id:select * from journals order by title','journal_id','Publicação',False,True,''));
		array_push($cp,array('$S120','title','Titulo da seção',True,True,''));
		array_push($cp,array('$S20','abbrev','Abreviatura',False,True,''));
		array_push($cp,array('$I8','seq','Ordem para mostrar',True,True,''));
		array_push($cp,array('$O 0:NÃO&1:SIM','editor_restricted','Nome Abreviado',False,True,''));
		array_push($cp,array('$O 1:SIM&0:NÂO','meta_indexed','Indexado',False,True,''));
		array_push($cp,array('$O 0:NÃO&1:SIM','hide_title','Titulo oculto',False,True,''));
		array_push($cp,array('$O 1:SIM&0:NÂO','abstracts_disabled','Resumo',False,True,''));
		array_push($cp,array('$T60:5','policy','Politica',False,True,''));
		array_push($cp,array('$S60','identify_type','Identificação',False,True,''));
		return($cp);
		}	
		
	function row()
		{
			global $cdf,$cdm,$masc;
			$cdf = array('section_id','title','abbrev','seq','identify_type');
			$cdm = array('Código','descricao','Abreviado','Seq.');
			$masc = array('','','','','','','','','','','');
			return(1);
		}
	function area_do_conhecimento_geral($area,$nome='')
		{
			if (strlen($area)==4) { $area .= '.00.00-%'; }
			$sql = "select * from ajax_areadoconhecimento
					where a_cnpq like '$area' ";
			$rlt = db_query($sql);
			echo $nome.'>>>';
			if ($line = db_read($rlt))
				{
					$nome = trim($line['a_descricao']);
					echo $line['a_descricao'];
				}	
			return($nome);
		}
	function section_reordem($journal)
		{
			//$sql = "alter table ".$this->tabela." add column section_area char(1)";
			//$rlt = db_query($sql);
			
			$sqlx = "select * from ".$this->tabela." 
				where journal_id = $journal 
				order by identify_type ";
			$rlt = db_query($sqlx);
			$ord = 0;
			$sql = '';
			$pre = 0;
			$xtipo = 'x';
			while ($line = db_read($rlt))
				{
					echo '<BR>';
					$tipo = trim(substr($line['identify_type'],0,1));
					if ($tipo != $xtipo)
						{
							$ord = 0;
							if ($tipo == '') { $pre = 800;  $xarea = 'X'; }
							if ($tipo == '1') { $pre = 0;   $xarea = 'A'; }
							if ($tipo == '3') { $pre = 50;  $xarea = 'A'; }
							if ($tipo == '2') { $pre = 100; $xarea = 'B'; }
							if ($tipo == '4') { $pre = 150; $xarea = 'B'; }
							if ($tipo == '5') { $pre = 200; $xarea = 'C'; }
							if ($tipo == '6') { $pre = 250; $xarea = 'D'; }
							if ($tipo == '7') { $pre = 300; $xarea = 'E'; }
							if ($tipo == '8') { $pre = 400; $xarea = 'E'; }
							if ($tipo == 'P') { $pre = 450; $xarea = 'P'; }
							$xtipo = $tipo;
						}
					//$$ord = sonumero($line['identify_type']);
					$ord++;
					$area = trim($line['identify_type']);
					$area_nome = $this->area_do_conhecimento_geral($area,$line['title']);
					$sql .= "update ".$this->tabela." set seq = ".($pre+$ord);
					if (strlen(trim($area_nome)) > 0) { $sql .= ", title = '".$area_nome."' "; }
					$sql .= ", section_area = '".$xarea."' ";
					$sql .= " where section_id = ".$line['section_id']. '; '.chr(13).chr(10);
					echo '=='.($pre+$ord).'==['.$tipo.']'.'====>'.$xarea;
				}
			if (strlen($sql) > 0)
				{ $rlt = db_query($sql); }
			return(1);
		}

	function section($journal=0,$title='',$abbrev='')
	
		{
//			$sql = "delete from ".$this->tabela."
//				where journal_id = $journal ";
//				$rlt = db_query($sql);
//				exit;
			$sqlx = "select * from ".$this->tabela." 
				where abbrev = '$abbrev' and journal_id = $journal ";
			$rlt = db_query($sqlx);
			
			if (!$line = db_read($rlt))
				{			
					$sql = "insert into ".$this->tabela;
					$sql .= "(journal_id,title, abbrev, seq, 
					editor_restricted, meta_indexed, abstracts_disabled, 
					identify_type, hide_title, policy, 
					seq_area 
					) values (
					$journal,'$title','$abbrev',1,
					0,1,0,
					'$abbrev',0,'',
					1)
					";
					$rlt = db_query($sql);
				}
				
			$sqlx = "select * from ".$this->tabela." 
				where abbrev = '$abbrev' and journal_id = $journal ";
			$rlt = db_query($sqlx);
			$line = db_read($rlt);
			return($line['section_id']);
		}	
	}
?>
