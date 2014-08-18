<?
		$xsql = "select * from brapci_keyword ";
		$xsql .= " where kw_word_asc = '".$dd5a."'";
		$xrlt = db_query($xsql);
		if (!($xline = db_read($xrlt)))
			{
			$xsql = "insert into brapci_keyword ";
			$xsql .= "(kw_codigo,kw_word,kw_word_asc,";
			$xsql .= "kw_use,kw_idioma";
			$xsql .= ") values (";
			$xsql .= "'','".$dd5."','".$dd5a."',";
			$xsql .= "'','".$dd[2]."'";
			$xsql .= ")";
			$xrlt = db_query($xsql);	
			
			$xsql = "update brapci_keyword set kw_codigo=lpad(id_kw,10,'0'), kw_use=lpad(id_kw,10,'0') where kw_codigo=''";			
			$xrlt = db_query($xsql);

			$xsql = "select * from brapci_keyword ";
			$xsql .= " where kw_word_asc = '".$dd5a."'";
			
			$xrlt = db_query($xsql);
			$xline = db_read($xrlt);
			}
		$xcod = $xline['kw_use'];
?>