<?
function nf($_loja,$_cpf,$_nome,$_cliente,$_emissor,$_produtos)
	{
	public $dd;
	//// $_produtos array()
	
	///////////////////////////////////// Busca Próxima Nota
	$sql = "select max(id_nf) as nfnr from notafiscal "
	$rlt = db_query($sql);
	$line = db_read($rlt);
	$nrnf = strzero($line['nfnr'],7);
	
	/////////////////////////////////////////////////////////
	$sql = "insert into notafiscal (";
	$sql .= "nf_emissor, nf_nome, nf_cliente, ";
	$sql .= "nf_data, nf_hora, nf_nr, ";
	$sql .= "nf_status, nf_emissao, nf_cnpf ";
	$sql .= ") values ("
	$sql .= "'".$_loja."','".$_nome."','".$_cliente."',";
	$sql .= "'".date("Ymd")."','".date("H:i")."','".$nrnf."',";
	$sql .= "'@',19000101,'".$_cpf."' ";
	$sql .= "); ".chr(13);
	
	for ($r=0;$r < count($_produtos);$r++)
		{
		$sql = "insert into notafiscal_item (";
		$sql .= "nfi_codigo, nfi_descricao, nfi_ref, ";
		$sql .= "nfi_quan, nfi_valor, nfi_status ) ";
		$sql .= " values ";
		$sql .= "('".$nrnf."','".$_produtos[$r][1]."','".$_produtos[$r][0]."',";
		$sql .= "'".$_produtos[$r][2]."',".$_produtos[$r][3]."','@');".chr(13);
		}
	
	if (count($_produtos) > 0)
		{
		$rlt = db_query($sql);
		}
	}
?>