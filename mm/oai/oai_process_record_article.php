<?
/////////////////////////////////////////////////////////
////////////////////////////////////////////////// ARTIGO
		$sql = "select * from brapci_article ";
		$sql .= " where ar_edition = '".$edicao_artigo."' ";
		$sql .= " and ar_journal_id = '".$jid."' ";
		$sql .= " and ar_titulo_1_asc = '".UpperCaseSQL($titulo)."' ";
		$rlt = db_query($sql);
		
		if ($line = db_read($rlt))
			{
				echo '<font size="5" color="#00c6c6">Trabalho já cadastrado na base</font>';
				$sql = "update oai_cache set cache_status = 'B' where id_cache = ".$id;
				$irlt = db_query($sql);				
				exit;
				$grava = 0;
			} else {
				$sqli = "insert into brapci_article ";
				$sqli .= "(";
				//////////////////////////////////////////////////////////////////////////////// CAMPOS
				$sqli .= "ar_journal_id,ar_codigo,ar_titulo_1,ar_titulo_1_asc, ";
				$sqli .= "ar_titulo_2,ar_titulo_3,ar_idioma_1, ";
				$sqli .= "ar_idioma_2,ar_idioma_3,ar_obs, ";
				
				$sqli .= "ar_status,ar_disponibilidade,ar_pg_inicial, ";
				$sqli .= "ar_pg_final,ar_data_envio,ar_data_aceite, ";
				$sqli .= "ar_data_aprovado,ar_doi,ar_tipo, ";
				
				$sqli .= " ar_area_conhecimento,ar_brapci_id, ar_oai_id, ";
				$sqli .= " ar_resumo_1,ar_resumo_2,ar_resumo_3 ";
				$sqli .= ",ar_position,ar_section,";
				
				$sqli .= "ar_edition";
				
				$sqli .= ") values (";
				//////////////////////////////////////////////////////////////////////////////// DADOS
				$sqli .= "'".$jid."','','".$titulo."', '".UpperCaseSql($titulo)."',";
				$sqli .= "'".$titulo_2."','','pt_BR',";
				$sqli .= "'en','','".$obs."', ";
				
				$sqli .= "'A',".date("Ymd").",'', ";
				$sqli .= "'',19000101,19000101,";
				$sqli .= "19000101,'','".$tp[2]."', ";
				
				$sqli .= "'','', '".$identify ."',";
				$sqli .= "'".$resumo."','".$resumo_2."','' ";
				$sqli .= ",-1,'".$tp[2]."',";
				
				$sqli .= "'".$edicao_artigo."'";
				$sqli .= ")";
				$rlt = db_query($sqli);
				
				$sqlu = "update brapci_article set ar_codigo=lpad(id_ar,'10','0') where (length(ar_codigo) < 10);";		
				$rlt = db_query($sqlu);
				///////////////qq recupera novo código do artigo
				$rlt = db_query($sql);
				$line = db_read($rlt);
				echo '<font size="5" color="#1cb5ff">Cadastrado novo trabalho com sucesso !</font><BR>Código:';
			}
		
		$ar_codigo = $line['ar_codigo'];
		echo $ar_codigo;
?>	