<?
class partner
	{		
		var $tabela = 'cep_submit_institution';
		
		function cp()
			{
				global $messa,$dd,$tabela;
				
				$tabela = $this->tabela;
				$cp = array();
				array_push($cp,array('$H8','id_it','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','it_author','',True,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$H8','','',False,True));
				array_push($cp,array('$S100','it_nome',msg('name'),True,True));
				array_push($cp,array('$S20','it_abreviatura',msg('abbreviation'),False,True));
				array_push($cp,array('$R 1:#FISI&2:#JURI','it_tipo',msg('person'),True,True));
				array_push($cp,array('$H8','it_estrangeiro',msg('foreign'),False,True));
				array_push($cp,array('$S100','it_endereco',msg('address'),False,True));
				array_push($cp,array('$S20','it_bairro',msg('neighborhood'),False,True));
				array_push($cp,array('$S30','it_cidade',msg('city'),False,True));
				array_push($cp,array('$Q pais_nome:pais_codigo:select * from ajax_pais order by pais_nome','it_pais',msg('country'),False,True));
				array_push($cp,array('$H8','it_status',msg('status'),False,True));
				array_push($cp,array('$H8','it_id_fiscal',msg('fiscal'),False,True));
				array_push($cp,array('$HV','it_ativo','1',False,True));
				array_push($cp,array('$H8','it_contato',msg('contact'),False,True));
				array_push($cp,array('$H8','it_telefone',msg('phone'),False,True));
				array_push($cp,array('$H8','it_fax',msg('fax'),False,True));
				
				array_push($cp,array('${','',msg('internet_data'),False,True));
				array_push($cp,array('$S100','it_email',msg('email'),False,True));
				array_push($cp,array('$S100','it_site',msg('site'),False,True));
				array_push($cp,array('$}','','',False,True));
				array_push($cp,array('$H8','it_obs',msg('obs'),False,True));
				return($cp);
			}
		function updatex()
			{
				$c = 'it';
				$c1 = 'id_'.$c;
				$c2 = $c.'_codigo';
				$c3 = 7;
				$sql = "update ".$this->tabela." set $c2 = lpad($c1,$c3,0) where $c2='' ";
				$rlt = db_query($sql);
			}
	}
?>
