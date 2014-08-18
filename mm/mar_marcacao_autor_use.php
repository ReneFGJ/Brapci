<?
require("db.php");

if (strlen($dd[2]) > 0)
{
	$sql = "update mar_autor set a_status = '".$dd[2]."' where a_codigo = '".$dd[0]."' ";
	$rlt = db_query($sql);
} else {
	$sql = "update mar_autor set a_use = '".$dd[0]."', a_status = 'U' where a_codigo = '".$dd[1]."' ";
	$sql .= " and (a_status = '@' or a_status = 'A') ";
	$rlt = db_query($sql);
	
	$sql = "update mar_autor set a_status = 'A' where a_codigo = '".$dd[0]."' and (a_status = '@' or a_status = 'A')";
	$rlt = db_query($sql);
}
require("close.php");
?>
