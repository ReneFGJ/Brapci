<?
require("db.php");
require($include."sisdoc_autor.php");
$sql = "select * from brapci_autor where id_autor = ".$dd[0];
$rlt = db_query($sql);
$line = db_read($rlt);
$n1 = nbr_autor(trim($line['autor_nome']),1);
$n2 = nbr_autor(trim($line['autor_nome']),1);

$sql = "update brapci_autor set autor_nome = '".$n1."',	autor_nome_citacao='".$n1."' ,";
$sql .= " autor_nome_asc = '".UpperCaseSql($n1)."' ";
$sql .= " where id_autor = ".$dd[0];

$rlt = db_query($sql);
?>
<script>
	close();
</script>