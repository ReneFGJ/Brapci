<?
class institution
	{
	var $id_i;
	var $i_date_format;
	
	var $tabela = 'institution';
	
	function cp()
		{
				$cp = array();
				array_push($cp,array('$H8','id_inst','id_cidade',False,True,''));
				array_push($cp,array('$H8','inst_codigo','Codigo',False,True,''));
				array_push($cp,array('$S100','inst_nome','Nome',False,True,''));
				array_push($cp,array('$S10','inst_abreviatura','Sigla',False,True,''));
				array_push($cp,array('$T60:5','inst_endereco','Endereço',False,True,''));
				array_push($cp,array('$Q cidade_nome:cidade_codigo:select * from ajax_cidade order by cidade_nome','inst_cidade','Cidade',True,True));
				array_push($cp,array('$S100','inst_site','Site',False,True,''));
				array_push($cp,array('$[0-9]','inst_ordem','Ordem',False,True,''));			
			return($cp);
		}
	
	function row()
			{
				global $cdf,$cdm,$masc;
				$cdf = array('id_cidade','inst_nome','inst_abreviatura');
				$cdm = array('cod',msg('nome'),msg('nome'),msg('abreviatura'));
				$masc = array('','','','','','','');
				return(1);				
			}	
	function le()
		{
			if (strlen($this->id_i) == 0) { $this->id_i = 1; }
			$sql = "select * from ".$this->tabela;
			$sql .= " where id_i = ".$this->id_i;
			$rlt = db_query($sql);
			
			if ($line = db_read($rlt))
			{
				$this->id_i = $line['id_i'];
				$this->i_name = trim($line['i_name']);
				$this->i_name_2 = trim($line['i_name_2']);
				$this->i_name_3 = trim($line['i_name_3']);
				$this->i_address_1 = trim($line['i_address_1']);
				$this->i_address_2 = trim($line['i_address_2']);
				$this->i_address_3 = trim($line['i_address_3']);
				$this->i_city  = trim($line['i_city']);
				$this->i_fone  = trim($line['i_fone']);
				$this->i_fax = trim($line['i_fax']);
				$this->i_email  = trim($line['i_email']);
				$this->i_cordenation = trim($line['i_cordenation']);
				$this->i_information = trim($line['i_information']);
				$this->i_system = trim($line['i_system']);
				$this->i_opas_cod = trim($line['i_opas_cod']);
				$this->i_date_format = trim($line['i_date_format']);
				return(1);
			}
			return(0);			
		}
	function export()
		{
			$rst = fopen('../'.lowercase($this->i_system).'/_config.php','w');
			fwrite($rst,'<?php'.chr(13));
			fwrite($rst,'$i_name = '.chr(39).$this->i_name.chr(39).';'.chr(13));
			fwrite($rst,'$i_name_2 = '.chr(39).$this->i_name_2.chr(39).';'.chr(13));
			fwrite($rst,'$i_name_3 = '.chr(39).$this->i_name_3.chr(39).';'.chr(13));
			fwrite($rst,'$i_address_1 = '.chr(39).$this->i_address_1.chr(39).';'.chr(13));
			fwrite($rst,'$i_address_2 = '.chr(39).$this->i_address_2.chr(39).';'.chr(13));
			fwrite($rst,'$i_address_3 = '.chr(39).$this->i_address_3.chr(39).';'.chr(13));
			fwrite($rst,'$i_city = '.chr(39).$this->i_city.chr(39).';'.chr(13));
			fwrite($rst,'$i_fax = '.chr(39).$this->i_fax.chr(39).';'.chr(13));
			fwrite($rst,'$i_email = '.chr(39).$this->i_email.chr(39).';'.chr(13));
			fwrite($rst,'$i_cordenation = '.chr(39).$this->i_cordenation.chr(39).';'.chr(13));
			fwrite($rst,'$i_information = '.chr(39).$this->i_information.chr(39).';'.chr(13));
			fwrite($rst,'$i_date_format = '.chr(39).$this->i_date_format.chr(39).';'.chr(13));
			fwrite($rst,'$nucleo = '.chr(39).$this->i_system.chr(39).';'.chr(13));
			fwrite($rst,'$i_opas_cod = '.chr(39).$this->i_opas_cod.chr(39).';'.chr(13));
			fwrite($rst,'?>'.chr(13));
			fclose($rst);
			return(1);
		}
	}
?>
