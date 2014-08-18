<?php
class areadoconhecimento
	{
	var $tabela = 'ajax_areadoconhecimento';
	function cp()
		{
			$cp = array();
			array_push($cp,array('$H8','id_aa','id_aa',False,True,''));
			array_push($cp,array('$S100','a_descricao','Nome',False,True,''));
			array_push($cp,array('$S14','a_cnpq','Sigla',False,True,''));
			array_push($cp,array('$H7','a_codigo','Codigo',False,True,''));
			array_push($cp,array('$H7','a_geral','Use',False,True,''));
			array_push($cp,array('$O 1:SIM&0:NÃO','a_semic','SEMIC',False,True,''));
			array_push($cp,array('$O 1:Ativo&0:Inativo','a_ativo','Status',False,True,''));
			return($cp);			
		}
	function row()
		{
			global $cdf, $cdm, $masc;
			
			$cdf = array('id_aa','a_descricao','a_cnpq','a_codigo');
			$cdm = array('Código','Nome','email','ativo');
			$masc = array('','','','SN');
			return('');
		}
		
	function relatorio_areas($tp=0)
		{
			global $perfil,$nw;			
			if ($tp==0)
				{
					$sx = '<h1>'.msg('areas_todas').'</h1>';
					$wh = '';
				}			
			if ($tp==1)
				{
					$sx = '<h1>'.msg('areas_do_semic').'</h1>';
					$wh = ' where a_semic = 1 ';
				}
			if ($tp==3)
				{
					$sx = '<h1>Área de submissão</h1>';
					$wh = " where a_submit = '1' ";
				}
			
			$sql = "select * from ".$this->tabela."
				$wh 
				order by a_cnpq ";
			$rlt = db_query($sql);

			while ($line = db_read($rlt))
			{
				$s .= '<TR '.coluna().'>';
				$sf = '';
				$cnpq = trim($line['a_cnpq']);
				if (substr($cnpq,2,2) == '00')
					{
					$sf .= '<TD colspan="4"><B>';
					} else {
						if (substr($cnpq,5,2) == '00')
						{
						$sf .= '<TD><TD colspan="3"><I>';
						} else {
						if (substr($cnpq,8,2) == '00')
							{
							$sf .= '<TD><TD><TD colspan="2">';
							} else {
							$sf .= '<TD>&nbsp;&nbsp;<TD>&nbsp;&nbsp;<TD>&nbsp;&nbsp;<TD>';
							}
						}		
					}
				$s .= '<TD align="center"><TT>';
				$s .= $line['a_cnpq'];
				$s .= ''.$sf;
				$s .= $line['a_descricao'];
				//if (($perfil->valid('#ADM#PIB#COO')))
				//	{
				//		$s .= '<TD>a';
				//	}
			}
			$sx .= '<table width="100%" class="tabela00">'.chr(13);
			$sx .= '<TR>'.chr(13);
			$sx .= '<TH width="20%">Código CNPq'.chr(13);
			$sx .= '<TH colspan="5">Descrição'.chr(13);
			$sx .= $s;
			$sx .= '</table>';
			return($sx);
	}
	
	function forma_area_estrategica($vlr='')
		{
			$sql = "select * from ".$this->tabela."
					where a_cnpq like '9%'  
					order by a_cnpq ";
			$rlt = db_query($sql);
			$sx .= '<option value="" class="">::Não se enquadra em uma área estratégica::</option>';		
			while ($line = db_read($rlt))
				{
					$sel = '';
					$area = substr(trim($line['a_cnpq']),0,4);
					if (substr($area,1,1)=='.')
					{
						if ($vlr == trim($line['a_cnpq'])) { $sel = 'selected'; }				
						$sx .= '<option class="it2" value="'.trim($line['a_cnpq']).'" '.$sel.'>';
						$sx .= substr(trim($line['a_cnpq']),0,10).' '.(substr(trim($line['a_descricao']),0,40));
						$sx .= '</option>';
					}			
				}
			return($sx);
		}

	function forma_area_especifica($vlr='')
		{
			$sql = "select * from ".$this->tabela."
					where not (a_cnpq like 'X%' or a_cnpq like '9%' )  
					order by a_cnpq ";
			$rlt = db_query($sql);
			$sx .= '<option value="" class="">::Seleciona a área::</option>';		
			while ($line = db_read($rlt))
				{
					$sel = '';
					$area = substr(trim($line['a_cnpq']),0,4);
					if (substr($area,1,1)=='.')
					{
						if ($vlr == trim($line['a_cnpq'])) { $sel = 'selected'; }				
						if ($xarea != $area)
							{
								$style = "it1";
								$dsb = '';
								if (substr($area,2,2) == '00') { $style = 'it0'; $dsb = 'disabled'; }
								$sx .= '<option value="'.trim($line['a_cnpq']).'"  '.$dsb.' class="'.$style.'" '.$sel.' >';
								$sx .= trim($line['a_cnpq']).' '.substr(trim($line['a_descricao']),0,40);
								$sx .= '</option>';
								$xarea = $area;			
							}  else {
								$sx .= '<option value="'.trim($line['a_cnpq']).'" class="it2" '.$sel.'>';
								$sx .= substr(trim($line['a_cnpq']),0,10).' '.(substr(trim($line['a_descricao']),0,40));
								$sx .= '</option>';
								$sx .= chr(13);
							}
					}			
				}
			return($sx);
		}
	function forma_area_geral($vlr='')
		{
			$sql = "select * from ".$this->tabela."
					where not (a_cnpq like 'X%' or a_cnpq like '9%' ) 
					and a_semic = 1 
					order by a_cnpq ";
			$rlt = db_query($sql);
			$sx = '';	
			$sx .= '<option value="" class="">::Seleciona a área::</option>';	
			while ($line = db_read($rlt))
				{
					$sel = '';
					$area = substr(trim($line['a_cnpq']),0,4);
					if (substr($area,1,1)=='.')
					{
						if ($vlr == trim($line['a_cnpq'])) { $sel = 'selected'; }				
						if ($xarea != $area)
							{
								$style = "it0";
								$dsb = 'disabled';
								if (substr($area,2,2) != '00') { $style = 'it1'; $dsb = ''; }
								$sx .= '<option value="'.trim($line['a_cnpq']).'" class="'.$style.'" '.$dsb.' '.$sel.' >';
								$sx .= trim($line['a_cnpq']).' '.substr(trim($line['a_descricao']),0,40);
								$sx .= '</option>';
								$xarea = $area;			
							}  else {
								$sx .= '<option class="it2" value="'.$line['a_cnpq'].'" '.$sel.'>';
								$sx .= substr(trim($line['a_cnpq']),0,10).' '.(substr(trim($line['a_descricao']),0,40));
								$sx .= '</option>';
							}
					}			
				}
			return($sx);
		}
	}

