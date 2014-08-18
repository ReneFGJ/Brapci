<?php
class expediente
	{
	var $jid;
	var $tabela = "pb_expediente";

	function strucuture()
		{
			$sql = "create table pb_expediente 
					(
					id_pbe serial NOT NULL,
					pbe_persona char(8),
					pbe_type char(3),
					pbe_ordem integer,
					pbe_ativo integer
					)
			";
			//echo $sql;
			//$rlt = db_query($sql);		
			$sql = "create table pb_expediente_type
					(
					id_pbt serial NOT NULL,
					pbt_nome char(20),
					pbt_type char(3),
					pbt_ativo integer
					)
			";
			//$rlt = db_query($sql);
			
			$sql = "insert into pb_expediente_type
					(pbt_nome, pbt_type, pbt_ativo)
					values
					('comissao_cientifica','003',1)
			";
			//$rlt = db_query($sql);		
		}	
	function cp()
			{
				//$sql = "ALTER TABLE ".$this->tabela." ADD COLUMN agf_ordem integer";
				//$rlt = db_query($sql);
				$cp = array();
				$op = ' : &001:'.msg('editor_chefe');
				$op .= '&002:'.msg('editor_secao');
				$op .= '&003:'.msg('comissao_cientifica');
				$op .= '&004:'.msg('avaliador');
				
				array_push($cp,array('$H8','id_pbe','id',False,true));
				array_push($cp,array('$S8','pbe_persona',msg('persona'),False,true));
				array_push($cp,array('$O '.$op,'pbe_type',msg('pbe_type'),False,true));
				array_push($cp,array('$[1-99]','pbe_ordem','Ordem de mostrar',True,true));
				array_push($cp,array('$O 1:SIM&0:NÃO','pbe_ativo','Ativo',True,true));
				return($cp);
			}
	
	function row()
			{
				global $cdf,$cdm,$masc;
				$cdf = array('id_pbe','pbe_persona','pbe_type','pbe_ordem');
				$cdm = array('cod',msg('persona'),msg('pbe_type'),msg('ordem'));
				$masc = array('','','','');
				return(1);				
			}
	
	function display_expediente($type)
		{
			global $jid;
			$sql = "select * from pb_expedient
					inner join pb_expediente_type on pbe_type = pbt_type
					where pbe_journal = ".$jid." 
					 and pbe_type = '$type' 
					 order by pbe_name ";
			$sx = '';
			$sx .= '<H2>';
			$sx .= msg('expedient_'.$type);
			$sx .= '</h2>';
			$sx .= '<UL>';
			while ($line = db_read($rlt))
			{
				$sx .= '<LI>';
				$sx .= $line['pbet_descricao'];
				$sx .= '</LI>';	
			}
			$sx .= '</UL>';
			return($sx);
		}
	
	function form_add_name()
		{
			$sx = '<table width="100%">';
			$sx .= '<TR><TD>'.msg('name');
			$sx .= '<TR><TD><input type="text" id="dd10">';
			$sx .= '<TR><TD>'.msg('name_title');
			$sx .= '<TR><TD><input type="text" id="dd11">';
			$sx .= '<TR><TD>'.msg('institution');
			$sx .= '<TR><TD><input type="text" id="dd12">';
			$sx .= '<TR><TD>'.msg('type');
			$sx .= '<TR><TD><input type="text" id="dd13">';
			$sx .= '</table>';
		}	
	}
?>
