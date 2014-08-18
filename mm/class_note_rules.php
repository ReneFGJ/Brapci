<?
$sql = "select * from tci_note ";
$sql .= " where tn_codigo = '".$dd[0]."' ";
$rlt = db_query($sql);

if ($line = db_read($rlt))
	{
	} else {
		$sql = "insert into tci_note ";
		$sql .= "(tn_codigo,tn_note,tn_idioma)";
		$sql .= " values ";
		$sql .= "('".$dd[0]."','".$dd[3]."','".$dd[4]."')";
		$rlt = db_query($sql);
		redirecina('http://localhost/2010/SKOS-Editor/v0.09.53/concept.php?dd0='.$dd[0]);
		
	}

?>