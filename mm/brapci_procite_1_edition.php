<?
	//////////////////////////////////// Ediчуo
	$sql = 'select * from brapci_edition where ';
	$sql .= " ed_journal_id = '".$journal_id."' ";
	$sql .= " and ed_ano = '".$ed_ano."' ";
	$sql .= " and ed_vol = '".$ed_vol."' ";
	$sql .= " and ed_nr = '".$ed_nur."' ";
	$rlt = db_query($sql);
	if ($line = db_read($rlt))
		{
			$id_edicao = $line['ed_codigo'];
		} else {
			$sqli = "insert into brapci_edition ";
			$sqli .= "(ed_codigo,ed_editor,ed_coeditor,";
			$sqli .= "ed_journal_id,ed_ano,ed_vol,";
			$sqli .= "ed_nr,ed_qualis,ed_data_publicacao,";
			
			$sqli .= "ed_mes_inicial,ed_mes_final,ed_periodo,";
			$sqli .= "ed_tematica_titulo,ed_artigo_total,ed_artigo_processado,";
			$sqli .= "ed_biblioteca,ed_data_cadastro,ed_obs,ed_ativo)";
			
			$sqli .= " values ";
			$sqli .= "('','','',";
			$sqli .= "'".$journal_id."','".$ed_ano."','".$ed_vol."',";
			$sqli .= "'".$ed_nur."','','".$ed_ano."0101',";

			$sqli .= "0,0,'".$ed_mes."',";
			$sqli .= "'',0,0,";
			$sqli .= "0,".date("Ymd").",'".$sr."'";
			$sqli .= ",1)";
			$rlt = db_query($sqli);

			$sqli = "update brapci_edition set ed_codigo=lpad(id_ed,'7','0') where (length(ed_codigo) < 7);";		
			$rlt = db_query($sqli);
			
			$rlt = db_query($sql);
		}
		$ed_codigo = $line['ed_codigo'];
?>