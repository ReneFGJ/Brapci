<?
if ($dd[3] == 'broader')
	{ $concept = $dd[4]; $relation = $dd[0]; $tp = 'TE'; }

if ($dd[3] == 'narrower')
	{ $concept = $dd[0]; $relation = $dd[4]; $tp = 'TE'; }

if ($dd[3] == 'related')
	{ $concept = $dd[4]; $relation = $dd[0]; $tp = 'TR'; }
	
////////q ver se esses termos não estão relacionados

$sql = "select * from tci_conceito_relacionamento ";
$sql .= " where (crt_conceito = '".$concept."' ";
$sql .= " and crt_termo = '".$relation."' and (crt_rela = 'TE' or crt_rela = 'TR'))";
$sql .= " or (crt_conceito = '".$relation."' ";
$sql .= " and crt_termo = '".$concept."' and (crt_rela = 'TE' or crt_rela = 'TR') )";
$rlt = db_query($sql);

if ($line = db_read($rlt))
	{
		$erro = 'Já existe uma forma de relacionamento entre esses conceitos';
		echo '<BR>';
		echo msg_erro($erro);
		echo '<BR>';
		echo $sql;
	} else {
		/////////qq Gravar relacionamento
		$sql = "insert into tci_conceito_relacionamento ";
		$sql .= "(crt_conceito,crt_termo,crt_rela,";
		$sql .= "crt_tema) values (";
		$sql .= "'".$concept."','".$relation."','".$tp."',";
		$sql .= "'".$dd[5]."')";
//		echo $sql;
//		exit;
		$rlt = db_query($sql);
		
		redirecina('concept.php?dd0='.$dd[0]);
	}
?>