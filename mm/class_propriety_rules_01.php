<?
/////class_propriety_rules_01.php
///// Termo Preferencial

$idioma = $dd[3];
$value  = $dd[4];
$tipo   = $dd[2];
$conceito = $dd[0];

////////// Regra 1 - Ver se não existe uma regra para este termo neste idioma neste tema
$sql = "select * from tci_conceito_relacionamento  ";
$sql .= " inner join tci_keyword on kw_codigo = crt_termo ";
$sql .= " where crt_rela = 'PD' ";
$sql .= " and crt_tema = '".$tema."' ";
$sql .= " and kw_idioma = '".$idioma."' ";
$sql .= " and crt_conceito = '".$dd[0]."' ";
$rlt = db_query($sql);

if ($line = db_read($rlt))
	{
	$erro = 'Já existe um termo preferencial relacionado a este conceito';
	echo '<BR>';
	echo msg_erro($erro);
	echo '<BR>';
	} else {
		///////////////// PHASE I - OK
		$sqla = "select * from tci_keyword ";
		$sqla .= " where kw_word_asc = '".UpperCaseSql($value)."' ";
		$sqla .= " and kw_idioma = '".$idioma."' ";
		$rlt = db_query($sqla);
		
		if ($line = db_read($rlt))
			{
				$termo = $line['kw_codigo'];
			} else {
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
		$sql = "insert into tci_conceito_relacionamento ";
		$sql .= "(crt_conceito,crt_termo,crt_rela,";
		$sql .= "crt_tema)";
		$sql .= " values ";
		$sql .= "('".$dd[0]."','".$termo."','PD',";
		$sql .= "'".$tema."')";
		$rlt = db_query($sql);
		redirecina('http://localhost/2010/SKOS-Editor/v0.09.53/concept.php?dd0='.$dd[0]);
	}

//$sql = "select * from tci_keyword where kw_word_asc is NULL ";
//$rlt = db_query($sql);
//while ($line = db_read($rlt))
//	{
//	$sql = "update tci_keyword set kw_word_asc = '".uppercasesql($line['kw_word'])."' where id_kw = ".$line['id_kw'];
//	$rrr = db_query($sql);
//	echo $line['kw_word'];
//	echo '<BR>';
//	}	
?>