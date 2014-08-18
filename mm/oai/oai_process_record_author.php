<?
$autor = oai_autor($autor);

$sql = "delete from brapci_article_author where ae_article='".$ar_codigo."' and ae_journal_id='".$jid."'";
$rlt = db_query($sql);

for ($r=0;$r < count($autor);$r++)
	{
	$sql = "insert into brapci_article_author ";
	$sql .= "(ae_journal_id,ae_article,ae_position,";
	$sql .= "ae_author,ae_instituicao,ae_aluno,";
	$sql .= "ae_professor,ae_ss,ae_pos,";
	$sql .= "ae_mestrado,ae_doutorado,ae_bio,";
	$sql .= "ae_contact";
	$sql .= ") values (";
	$sql .= "'".$jid."','".$ar_codigo."',".($r+1).",";
	$sql .= "'".$autor[$r][0]."','',0,";
	$sql .= "0,0,0,";
	$sql .= "0,0,'".$autor[$r][6]."',";
	$sql .= "'".$autor[$r][5]."')";
	$rlt = db_query($sql);
	}

?>