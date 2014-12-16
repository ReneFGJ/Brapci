<?php
class keyword
	{
	var $tabela = "brapci_keyword";
	
	function cp()
		{
			$cp = array();
			array_push($cp,array('$H8','id_kw','',False,True));
			array_push($cp,array('$S80','kw_word','Termo',True,True));
			array_push($cp,array('$S5','kw_idioma','Idioma',True,True));
			array_push($cp,array('$S10','kw_codigo','Codigo',False,True));
			array_push($cp,array('$S10','kw_use','Alias',False,True));
			array_push($cp,array('$O 0:Não&1:SIM','kw_hidden','Oculto',False,True));
			array_push($cp,array('$S1','kw_tipo','Tipo',False,True));
			
			return($cp);
		}
	
	function row()
		{
			global $cdf, $cdm,$masc;
			$idcp = 'kw';
			$cdf = array('id_'.$idcp,$idcp.'_word',$idcp.'_codigo',$idcp.'_use','kw_tipo',$idcp.'_idioma');
			$cdm = array('Código','Nome','Citação','Codigo','Tipo','Alias');
			$masc = array('','','','','','','','','','','');			
		}
	function recupera_keyword($id,$idioma)
		{
			if (round($id) == 0) { return(''); }
			$id = strzero($id,10);			
			$sql = "select * from brapci_article_keyword 
						inner join brapci_keyword on kw_keyword = kw_codigo
						where kw_article = '".$id."' and kw_idioma = '$idioma'";	
			$rlt = db_query($sql);
			$sx = '';
			while ($line = db_read($rlt))
				{
					if (strlen($sx) > 0)
						{ $sx .= '. '; }
					$sx .= trim($line['kw_word']);
				}			
			return($sx);
		}	
	
	function save_keyword_article_v2($id,$keys,$idioma)
			{
			if (round($id) == 0) { return(''); }
			$id = strzero($id,10);
			
			$sql = "select * from brapci_article_keyword 
						inner join brapci_keyword on kw_keyword = kw_codigo
						where kw_article = '".$id."' and kw_idioma = '$idioma'";	
			$rlt = db_query($sql);
			while ($line = db_read($rlt))
				{
					$sqlx = "delete from brapci_article_keyword where id_ak = ".$line['id_ak'];
					$xrlt = db_query($sqlx);
				}

			for ($rx=0;$rx < count($keys);$rx++)
				{
					$nome_ref = $keys[$rx];

					$cod = $this->keyword_find($nome_ref,$idioma);
					$sql = "insert into brapci_article_keyword
							(
							kw_article, kw_keyword, kw_ord
							) value (
							'$id','$cod',$rx)
							";
					$rlt = db_query($sql);
				}
			return(1);
			}	
	
	function save_keyword_article($id,$keys)
			{
			$sql = "delete from brapci_article_keyword where kw_article = '$id'";
			$rlt = db_query($sql);
				
			for ($r=0;$r < count($keys);$r++)
				{
					$nome_ref = $keys[$r][0];
					$idioma = $keys[$r][1];
					$cod = $this->keyword_find($nome_ref,$idioma);
					$sql = "insert into brapci_article_keyword
							(
							kw_article, kw_keyword, kw_ord
							) value (
							'$id','$cod',$r)
							";
					$rlt = db_query($sql);
				}
			return(1);
			}	
	
	function insert_keyword_in_article($article,$key_text,$idioma)
		{
			$key_text = troca($key_text,'. ',';');';';
			$key_text = troca($key_text,', ',';');';';
			$ky = splitx(';',$key_text);
			print_r($ky);
			echo '<BR>'.$idioma.'<HR>';
			if ($idioma == '') { echo '<font color="red">Sem Idioma</font>'; return(''); }
			$keys = array();
			for ($r=0;$r < count($ky);$r++)
				{
					array_push($keys,array($ky[$r],$idioma));
				}
			
			if (count($ky) > 0)
				{
					$this->save_keyword_article($article,$keys);
				}
		}
	
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
