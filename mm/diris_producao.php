<?
echo '>>>>'.$cdo;

$sql = "select * from brapci_article_author ";
$sql .= " inner join brapci_autor on autor_codigo = ae_author ";
$sql .= " left join instituicoes on inst_codigo = ae_instituicao ";
$sql .= " where autor_codigo = '".$cdo."' ";
$sql .= " order by ae_pos ";
$arlt = db_query($sql);
echo $sql;

while ($line = db_read($arlt))
	{
	$sx = $line[''];
	}
?>