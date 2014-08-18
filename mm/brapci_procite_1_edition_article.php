<?
$sql = "update brapci_article ";
$sql .= " set ar_edition = '".$ed_codigo."' ";
$sql .= " , ar_section = '".$tipo."' ";
$sql .= " where ar_codigo = '".$ar_codigo."' ";
$rlt = db_query($sql);
?>	
?>