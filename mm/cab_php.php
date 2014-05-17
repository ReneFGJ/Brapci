<?
$data_dia = date("d");
$data_mes = nomemes(intval(date("m")));
$data_ano = date("Y");
$data_semana = date("w")+1;
if ($data_semana > 7) { $data_semana = 1; }
$data_semana = nomedia($data_semana);

?>