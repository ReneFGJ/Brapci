<?php
class keywords extends CI_model
	{
	function save_KEYWORDS($id,$keys,$idioma)
		{
			$id = strzero($id,10);
			$keys = $this->trata_keywords($keys);
			$akeys = array();
			$nkeys = '';
			for ($r=0;$r < count($keys);$r++)
				{
					if (strlen($keys[$r]) > 0)
						{
							$name = substr(UpperCaseSQL($keys[$r]),0,100);
							if (!isset($akeys[$name]))
								{
									
									$akeys[$name] = 0;
									if (strlen($nkeys) > 0) { $nkeys .= ', '; }
									$nkeys .= "'".$keys[$r]."' ";
								}
						}
				}
			/* retorna se vazio */
			if (count($akeys) == 0) { return(''); }
			
			$sql = "select * from brapci_keyword where kw_word_asc IN ($nkeys) and kw_idioma = '$idioma'";
			$rlt = db_query($sql);
			while ($line = db_read($rlt))
			{
				$name = trim($line['kw_word_asc']);
				$akeys[$name] = $line['kw_use'];
			}
			
			/* Excluir palavras no idioma */
			$sql = "select * from brapci_article_keyword
					inner join brapci_keyword on kw_keyword = kw_codigo 
					where kw_article = '$id' and kw_idioma = '$idioma'
					order by kw_ord
			";
			
			$rlt = db_query($sql);
			$sql = 'delete from brapci_article_keyword where id_ak IN ($hw) ';
			$hw = '';
			while ($line = db_read($rlt))
				{
					if (strlen($hw) > 0) { $hw .= ', '; }
					$hw .= trim($line['id_ak']);
				}		
			$sql = troca($sql,'$hw',$hw);
			if (strlen($hw) > 0)
				{
					$rlt = $this->db->query($sql);		
				}
				
			/* Salva novas keywords */
			$hw = '';
			$pos = 0;
			foreach ($akeys as $key => $value) {
				if ($value == 0)
					{
						echo 'Not found '.$key.' = '.$keys[$pos].'<BR>';
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
			return(1);
		}
	function updatex()	
		{
			$sql = "update brapci_keyword set kw_codigo = lpad(id_kw,10,0), kw_use = lpad(id_kw,10,0) where kw_codigo = '' ";
			$query = $this->db->query($sql);
			return(1);
		}
	function incorpore_keyword($term,$idioma)
		{
			$sql = "select * from brapci_keyword where kw_word_asc = '".UpperCaseSql($term)."' and kw_idioma = '$idioma' ";
			$query = $this->db->query($sql);
			
			$query = $query->result_array();
			if (count($query)==0)
				{
					$term_asc = UpperCaseSql($term);
					$sql = "insert into brapci_keyword (kw_word, kw_word_asc, kw_codigo, kw_use, kw_idioma, kw_tipo, kw_hidden)
					values
					('$term','$term_asc','','','$idioma','N',0)
					";
					$query = $this->db->query($sql);
					
					/* Update X */
					$this->updatex();
					
					$sql = "select * from brapci_keyword where kw_word_asc = '".UpperCaseSql($term)."' and kw_idioma = '$idioma' ";
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

			$rlt = db_query($sql);
			$keys = '';
			while ($line = db_read($rlt))
				{
					if (strlen($keys) > 0) { $keys .= '; '; }
					$keys .= trim($line['kw_word']);
				}
			return($keys);
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
