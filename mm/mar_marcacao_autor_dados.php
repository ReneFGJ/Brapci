<?
require("db.php");

$sql = "update mar_autor set a_status = 'Z' where a_codigo = '".$dd[0]."' ";
$rlt = db_query($sql);

require("close.php");
?>