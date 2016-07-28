<?php
// This file is part of the Brapci Software. 
// 
// Copyright 2015, UFPR. All rights reserved. You can redistribute it and/or modify
// Brapci under the terms of the Brapci License as published by UFPR, which
// restricts commercial use of the Software. 
// 
// Brapci is distributed in the hope that it will be useful, but WITHOUT ANY
// WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
// PARTICULAR PURPOSE. See the ProEthos License for more details. 
// 
// You should have received a copy of the Brapci License along with the Brapci
// Software. If not, see
// https://github.com/ReneFGJ/Brapci/tree/master//LICENSE.txt 
/* @author: Rene Faustino Gabriel Junior <renefgj@gmail.com>
 * @date: 2015-12-01
 */
class keywords extends CI_model
	{
	var $keywords = array();
	function cp()
		{
			$cp = array();
			array_push($cp,array('$H8','id_kw','',False,True));
			array_push($cp,array('$S100','kw_word',msg('termo'),True,True));
			array_push($cp,array('$S8','kw_codigo',msg('codigo'),False,False));
			array_push($cp,array('$S8','kw_use',msg('remissiva'),True,True));
			$sql = "ido_codigo:ido_descricao:select * from ajax_idioma";
			array_push($cp,array('$Q '.$sql,'kw_idioma',msg('idioma'),True,True));
			$sql = "kwt_codigo:kwt_descricao:select * from ajax_keyword_tipo";
			array_push($cp,array('$Q '.$sql,'kw_tipo',msg('tipo'),True,True));
			array_push($cp,array('$O 0:NÃO&1:SIM','kw_hidden',msg('oculto'),False,True));	
			return($cp);		
		}
	function check_keywords_language()
		{
			$sql = "select count(*) as total, kw_idioma from brapci_keyword where 
							kw_idioma <> 'es' and
							 kw_idioma <> 'en' and 
							 kw_idioma <> 'pt_BR' and 
							 kw_idioma <> 'fr' 
					group by kw_idioma";
			$rlt = $this->db->query($sql);
			$rlt = $rlt->result_array();
			$total = 0;
			$sx = '';
			for ($r=0;$r < count($rlt);$r++)
				{
				$line = $rlt[$r];
				$total = $total + $line['total'];
				$sx .= 'Idioma: '.$line['kw_idioma'].' não localizado<br>';
				}
			$sx .= 'Total de '.$total.' idiomas para serem ajustados ajustados ';
			
			/* Vazio */
			$sql = "update brapci_keyword set kw_idioma = 'pt_BR' where kw_idioma = '' ";
			$rlt = $this->db->query($sql);
			
			/* es-ES */
			$sql = "update brapci_keyword set kw_idioma = 'es' where kw_idioma = 'es-ES' ";
			$rlt = $this->db->query($sql);
			
			/* pt-BR */
			$sql = "update brapci_keyword set kw_idioma = 'pt_BR' where kw_idioma = 'pt-BR' ";
			$rlt = $this->db->query($sql);

			/* us */
			$sql = "update brapci_keyword set kw_idioma = 'en' where kw_idioma = 'us' ";
			$rlt = $this->db->query($sql);
			
			/* fr-CA */
			$sql = "update brapci_keyword set kw_idioma = 'fr' where kw_idioma = 'fr-CA' ";
			$rlt = $this->db->query($sql);
			
			/* o */
			$sql = "update brapci_keyword set kw_idioma = 'pt_BR' where kw_idioma = 'o' ";
			$rlt = $this->db->query($sql);						
						
			return($sx);
		}
	function delete_KWYWORDS($id)
		{
			$id = strzero($id,10);
			$sql = "delete from brapci_article_keyword where kw_article = '$id' ";
			$this->db->query($sql);
			return('');
		}
	function save_KEYWORDS($id,$keys,$idioma)
		{
			$sx = '';
			$id = strzero($id,10);
			$keys = $this->trata_keywords($keys);
			$akeys = array();
			$nkeys = '';
			for ($r=0;$r < count($keys);$r++)
				{
					if (strlen($keys[$r]) > 0)
						{
							$name = substr($keys[$r],0,100);
							$name = UpperCaseSql($name);
							if (!isset($akeys[$name]))
								{									
									$akeys[$name] = 0;
									if (strlen($nkeys) > 0) { $nkeys .= ', '; }
									$xkeys = troca($keys[$r],"'","´");
									$nkeys .= "'".UpperCaseSql($xkeys)."' ";
								}
						}
				}
			/* retorna se vazio */
			if (count($akeys) == 0) { return(''); }
			
			$sql = "select kw_word_asc, kw_use from brapci_keyword where kw_word_asc IN ($nkeys) and kw_idioma = '$idioma'";
			$rlt = db_query($sql);
			while ($line = db_read($rlt))
			{
				$name = trim($line['kw_word_asc']);
				$akeys[$name] = $line['kw_use'];
			}

			/* Salva novas keywords */
			$hw = '';
			$pos = 0;
			foreach ($akeys as $key => $value) {
				if ($value == 0)
					{
						$sx .= 'Termos '.$key.' = '.$keys[$pos].' ('.$idioma.')<BR>';
						$keyr = $this->incorpore_keyword($keys[$pos],$idioma);
						$akeys[$key] = $keyr;
					}
					$pos++;
			}				
				
			/* Salva keywords */
			$hw = '';
			$pos = 0;
			foreach ($akeys as $key => $value) {
				$pos++;
				if (strlen($hw) > 0) { $hw .= ', '; }
				$hw .= "('".$value."','".$id."','".$pos."')";
				if (strlen($value) == 0)
					{
						echo 'ops '.$key.'-'.$value;
						exit;
					}
			}
			$sql = "insert into brapci_article_keyword (kw_keyword, kw_article, kw_ord) values $hw ";
			$sql = troca($sql,'$hw',$hw);

			if (strlen($hw) > 0)
				{
					$rlt = $this->db->query($sql);
				}
			return($sx);
		}
	function updatex()	
		{
			$sql = "update brapci_keyword set kw_codigo = lpad(id_kw,10,0), kw_use = lpad(id_kw,10,0) where kw_codigo = '' ";
			$query = $this->db->query($sql);
			return(1);
		}
	function incorpore_keyword($term,$idioma)
		{
			$term = troca($term,"'","´");
			$sql = "select * from brapci_keyword where kw_word_asc = '".($term)."' and kw_idioma = '$idioma' ";
			$query = $this->db->query($sql);
			
			$query = $query->result_array();
			if (count($query)==0)
				{
					$term_asc = ($term);
					$sql = "insert into brapci_keyword (kw_word, kw_word_asc, kw_codigo, kw_use, kw_idioma, kw_tipo, kw_hidden)
					values
					('$term','$term_asc','','','$idioma','N',0)
					";
					$query = $this->db->query($sql);
					
					/* Update X */
					$this->updatex();
					
					$sql = "select * from brapci_keyword where kw_word_asc = '".($term)."' and kw_idioma = '$idioma' ";
					$query = $this->db->query($sql);
					$query = $query->result_array();					
				}
			$query = $query[0];
			$rs = $query['kw_use'];
			return($rs);
		}
	function retrieve_keywords($id,$idioma='pt_BR')
		{
			$sql = "select * from brapci_article_keyword
					inner join brapci_keyword on kw_keyword = kw_codigo 
					where kw_article = '$id' and kw_idioma = '$idioma'
					order by kw_ord
			";

			$rlt = $this->db->query($sql);
			$rlt = $rlt->result_array();
			$keys = '';
			for ($r=0;$r < count($rlt);$r++)
				{
					$line = $rlt[$r];
					if (strlen($keys) > 0) { $keys .= '; '; }
					$keys .= trim($line['kw_word']);
				}
			return($keys);
		}
	function retrieve_keywords_all($id)
		{
			$sql = "select * from brapci_article_keyword
					inner join brapci_keyword on kw_keyword = kw_codigo 
					where kw_article = '$id' 
					order by kw_idioma, kw_ord
			";
			$rlt = $this->db->query($sql);
			$rlt = $rlt->result_array();
			return($rlt);
		}	
	function trata_keywords($keys)
		{
			/* Keywords */
			$keys = troca($keys,chr(13),' ');
			$keys = troca($keys,chr(10),'');
			$keys = troca($keys,'.','; ');
			$keys = troca($keys,',','; ');
			$keys = troca($keys,'  ','');
			$keys = troca($keys,'  ','');
			$keys = troca($keys,'  ','');
			$keys .= ';';
			$keys = splitx(';',$keys);
			return($keys);
		}	
	}
?>
