<?
class cep_submit_documento
	{
	var $autor;
	var $page;
	var $protocol;
	var $title;
	var $clinic;
	var $tipo;
	
	function protocolo_novo()
		{
		global $base;
		$autor = $this->autor;
		$tipo = $this->tipo;
		$clinic = $this->clinic;
		$titu = $this->title;
		$data = date("Ymd");
		$hora = date("H:i:s");
		
		$sql = "insert into cep_submit_documento
			( doc_1_titulo, doc_protocolo, doc_clinic, 
			doc_data, doc_hora, doc_dt_atualizado,
			doc_autor_principal, doc_status, doc_xml, doc_tipo ) 
			values
			( '$titu','$protocolo',$clinic,
			$data,'$hora','$data',
			'$autor','@','','$tipo') ";
			$rlt = db_query($sql);
			$this->updatex();
			
		$sql = "select * from cep_submit_documento
			where doc_data = '$data' and doc_hora = '$hora'
			and doc_autor_principal = '$autor' and
			doc_status = '@' ";
		$rlt = db_query($sql);
		$line = db_read($rlt);
		$protocolo = $line['doc_protocolo'];		
		return($protocolo);
		}
	function updatex()
		{
			$sql = "update cep_submit_documento set ";
			$sql .= "doc_protocolo = lpad(id_doc,7,0) ";
			$sql .= " where doc_protocolo = '' ";
			$rlt = db_query($sql);
		}
	
	function dados_grava()
		{
		global $dd,$cp,$page,$protocol;
		$page = strzero($page,3);
		
		$sql = "delete from cep_submit_documento_dados 
				where sdd_protocol = '$protocol'
				and sdd_page='$page' ";
		$rlt = db_query($sql);			
		
		for ($r=0;$r < count($cp);$r++)
		{
			$cps = $cp[$r];
			$field = $cps[1];
			$texto = $dd[$r];

			if (strlen($protocol) == 0) { echo msg('protocol_invalid'); exit; }
			if (strlen($texto) > 0)
			{
			$sql = "insert into cep_submit_documento_dados 
				(sdd_field, sdd_content, sdd_data, 
				sdd_time, sdd_protocol, sdd_page )
				values
				('$field','$texto','$data',
				'$hora','$protocol','$page'); ";
			$rlt = db_query($sql);
			}
			if ((strlen($this->title) > 0) and (strlen($protocol) > 0))
				{
					$sql = "update cep_submit_documento 
						set doc_1_titulo = '".$this->title."',
						doc_clinic = ".round($this->clinic).", 
						doc_dt_atualizado = ".date("Ymd")." 
						where doc_protocolo = '".$protocol."' ";
					$rlt = db_query($sql);
				}
				
		}
			//$rlt = db_query($sql);
		return(1);	
		}
		
	}
