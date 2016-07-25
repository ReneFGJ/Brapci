<?php
class autoindexs extends CI_model {
	
	var $stopwords=array();
	
	/* STOP WORD */
	function create_table() {
		$table = '_db_stopword';
		$rlt = $this->db->query("SHOW TABLES LIKE '$table'");
		$rlt = $rlt->result_array();
		$existe = count($rlt);
		
		if ($existe == 0)
			{
			$sql = "CREATE table _db_stopword
						(
							id_sw serial not null,
							sw_language char(5),
							sw_word char(20),
							sw_active integer,
							sw_update timestamp default CURRENT_TIMESTAMP,
							sw_own integer default 0
						)";
			$this -> db -> query($sql);
			}
			return(1);
	}

	function insert_stopword($filename,$language_code='') {
		/* Criar tabela se não existe */
		if (strlen($language_code) == 0)
			{
				echo 'Falta o parametro $language_code';
				exit;
			}
		$this->create_table();
		
		/* Recupera dados do arquivo texto */
		$path = $_SERVER['SCRIPT_FILENAME'];
		$path = substr($path, 0, strpos($path, 'index.php'));

		$filename = $path . $filename;
		$handle = fopen($filename, "r");
		$s = fread($handle, filesize($filename));
		fclose($handle);
		
		$s = troca($s,chr(13),';');
		$s = troca($s,"'","´");
		$s = troca($s,chr(10),'').';';
		$words = splitx(';',$s);
		
		$sqli = "insert into _db_stopword (sw_language, sw_word, sw_active, sw_own) values ";
		$tot = 0;
		for ($r=0;$r < count($words);$r++)
			{
				$word = $words[$r];
				$sss = "select * from _db_stopword where sw_language = '$language_code' and sw_word = '$word' ";
				$rrr = $this->db->query($sss);
				$rrr = $rrr->result_array();
				if (count($rrr) == 0)
					{
						$sqlx = $sqli . " ('$language_code','$word','1','0') ";
						$this -> db -> query($sqlx);
						$tot++;
					}						
			}		
		return('inserido '.$tot.' palavras');
	}
	function convertChars($t)
		{
			$qc = array('ç','á','à','â','â','ä','ã','ú','ù','ü','û','é','ê','è','ë','í','î','ì','ï','ó','ò','ô','ô','ö',"´");
			$qt = array('c','a','a','a','a','a','a','u','u','u','u','e','e','e','e','i','i','i','i','o','o','o','o','o','');			
			$t = (str_replace($qc, $qt, $t));
			
			return($t);
		}

	function language_detect($text='')
		{
			$lg = array();
			$rs = array();
			$text = lowercase($this->ConvertChars($text));
			
			/* Busca StopWord do Banco ou da memória */
			if (count($this->stopwords) == 0)
				{
					$sql = "select * from _db_stopword order by sw_language, sw_word ";
					$rlt = $this->db->query($sql);
					$rlt = $rlt->result_array();
					$this->stopwords = $rlt;
				} else {
					$rlt = $this->stopwords;
				}
				
			/* Comparador */
			for ($r=0;$r < count($rlt);$r++)
				{
					$line = $rlt[$r];
					$word = $this->ConvertChars($line['sw_word']);
					$language = $line['sw_language'];
					$text2 = $text;
					while ($pos = strpos(' '.$text2,' '.$word.' '))
						{
							if (isset($lg[$language]))
								{
									$lg[$language] = $lg[$language] + 1;
								} else {
									$lg[$language] = 1;
								}
							$text2 = substr($text2,$pos+2,strlen($text2));
						}
				}
			$idioma = '-';
			$max = 0;
			
			foreach ($lg as $key => $value) {
				
				if ($value >= $max)
					{
						$idioma = $key;
						$max = $value;
					}
			}
			return($idioma);
		}

}
?>
