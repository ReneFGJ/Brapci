<?php
class keyword
	{
	var $tabela = "brapci_keyword";
	
	function show_keyword($idioma='pt-BR',$key='A')
		{
			$wh = " and kw_word_asc like '$key%' ";
			$sql = "select kw_word, kw_codigo, kw_use from brapci_keyword 
						where kw_use = kw_codigo
						and kw_idioma = '$idioma'
						$wh
						and kw_word <> ''
					order by kw_word_asc
			";
			$rlt = db_query($sql);
			
			$sx = '';
			while ($line = db_read($rlt))
				{
					$keyc = trim($line['kw_codigo']);
					$link = '<A HREF="busca_keyword.php?dd1='.$keyc.'" class="link lt1">';
					$sx .= '<BR>'.$link.utf8_encode(trim($line['kw_word'])).'</A>'.chr(13).chr(10);
				}
			return($sx);
			
		}
	function keyword_insert($key,$idioma)
		{
			$key1 = UpperCaseSql($key);
			$sql = "insert into brapci_keyword 
					(kw_word, kw_word_asc, kw_codigo,
					kw_use, kw_idioma, kw_tipo,
					kw_hidden 
					) values (
					'$key', '$key1', '',
					'', '$idioma','N',
					0)";
			$rlt = db_query($sql);
			$this->updatex_keys();
			$rs = $this->keyword_find($key,$idioma);
			return($rs);
		}
	function updatex_keys()
		{
				$c = 'kw';
				$c1 = 'id_'.$c;
				$c2 = $c.'_codigo';
				$c5 = $c.'_use';
				$c3 = 10;
				$sql = "update brapci_keyword set 
						$c2 = lpad($c1,$c3,0) , 
						$c5 = lpad($c1,$c3,0)
						where $c2='' ";
				$rlt = db_query($sql);
				return(1);
		}		
	function keyword_find($key,$idioma)
		{
			$key_asc = UpperCaseSql($key);
			$sql = "select * from brapci_keyword 
					where kw_word_asc = '$key_asc'
					and kw_idioma = '$idioma'";
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					return(trim($line['kw_use']));
				} else {
					return($this->keyword_insert($key, $idioma));
				}
		}
	}
