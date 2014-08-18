<?
require('db.php');
$sql = "update mar_works set";
$sql .= " m_status = 'C', m_tipo = '".$dd[1]."' ";
$sql .= " where id_m = ".$dd[0];
$rlt = db_query($sql);
require('close.php');
?>