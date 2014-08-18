<?
$sql = "INSERT INTO `log_gestao_mm` ( `lg_user` , `lg_artigo` , `lg_data` , `lg_hora` , `lg_status_de` , `lg_status_para` , `lg_tipo` ) ";
$sql .= "VALUES (";
$sql .= " 0".$user_id.",".$dd[0].", '".date("Ymd")."', '".date("H:i")."', '".$sta."', '".$stb."', '".$stc."' ";
$sql .= ");";
$xrlt = db_query($sql);
?>