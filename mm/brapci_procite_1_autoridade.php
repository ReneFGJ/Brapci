<?

$sql = "delete from brapci_article_author where ae_article='".$ar_codigo."' and ae_journal_id='".$journal_id."'";
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
	$sql .= "'".$journal_id."','".$ar_codigo."',".$r.",";
	$sql .= "'".$autor[$r][0]."','',0,";
	$sql .= "0,0,0,";
	$sql .= "0,0,'".$autor[$r][5]."',";
	$sql .= "'".$autor[$r][6]."')";
	echo '<HR>'.$sql;
	$rlt = db_query($sql);
	}

?>