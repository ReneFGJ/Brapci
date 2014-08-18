<?
		//// ver se esse termo já não foi utilizado ';
		$sqla = "select * from tci_keyword ";
		$sqla .= " where kw_word_asc = '".$termo_asc."' ";
		$sqla .= " and kw_idioma = '".$idioma."' ";

		$rlt = db_query($sqla);
		if ($line = db_read($rlt))
			{
				$termo = $line['kw_codigo'];
			} else {
				$value = $termo;
				/////////// Cadastra novo termo
				$sql = "insert into tci_keyword ";
				$sql .= "(kw_word,kw_word_asc,kw_codigo,";
				$sql .= "kw_use,kw_idioma";
				$sql .= ") values (";
				$sql .= "'".$value."','".UpperCaseSql($value)."','',";
				$sql .= "'','".$idioma."'";
				$sql .= ")";
				$rlt = db_query($sql);
				$sql = "update tci_keyword set kw_codigo = lpad(id_kw,7,0), kw_use = lpad(id_kw,7,0) ";
				$sql .= " where kw_codigo = '' ";
				$rlt = db_query($sql);
				///////////// Busca Código
				$rlt = db_query($sqla);
				$line = db_read($rlt);
				$termo = $line['kw_codigo'];
			}
?>