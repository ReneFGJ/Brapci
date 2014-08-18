<?
/////////////////////////////////////////////////////////
////////////////////////////////////////////////// ARTIGO
		$sql = "select * from brapci_article ";
		$sql .= " where ar_brapci_id = '".$id."' ";
		$rlt = db_query($sql);
		if ($line = db_read($rlt))
			{
			} else {
				$sqli = "insert into brapci_article ";
				$sqli .= "(ar_journal_id,ar_codigo,ar_titulo_1, ";
				$sqli .= "ar_titulo_2,ar_titulo_3,ar_idioma_1, ";
				$sqli .= "ar_idioma_2,ar_idioma_3,ar_obs, ";
				$sqli .= "ar_status,ar_disponibilidade,ar_pg_inicial, ";
				$sqli .= "ar_pg_final,ar_data_envio,ar_data_aceite, ";
				$sqli .= "ar_data_aprovado,ar_doi,ar_tipo, ";
				$sqli .= " ar_area_conhecimento,ar_brapci_id, ";
				$sqli .= " ar_resumo_1,ar_resumo_2,ar_resumo_3 ";
				$sqli .= ",ar_position,ar_section,ar_edition) values (";
				$sqli .= "'".$journal_id."','','".$titulo."', ";
				$sqli .= "'','','??',";
				$sqli .= "'','','".$sr."', ";
				$sqli .= "'A',".date("Ymd").",'', ";
				$sqli .= "'',19000101,19000101,";
				$sqli .= "19000101,'','".$tipo."', ";
				$sqli .= "'','".$id."', ";
				$sqli .= "'".$abstract."','','' ";
				$sqli .= ",-1,'','');";
				$rlt = db_query($sqli);
				
				$sqlu = "update brapci_article set ar_codigo=lpad(id_ar,'10','0') where (length(ar_codigo) < 10);";		
				$rlt = db_query($sqlu);
				///////////////qq recupera novo código do artigo
				$rlt = db_query($sql);
				$line = db_read($rlt);
			}
		
		$ar_codigo = $line['ar_codigo'];
?>		