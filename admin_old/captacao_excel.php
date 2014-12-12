<?
/*** Modelo ****/
$breadcrumbs=array();
$include = '../';
require("../db.php");

header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-type: application/x-msexcel; charset=utf-8");
header("Content-Disposition: attachment; filename=estatistica_".date("Y-m-d").".xls"); 
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);

require("../_class/_class_captacao.php");
$cap = new captacao;
require($include.'sisdoc_data.php');
function coluna()
	{return('');}

$programa_pos_anoi = 2010;
$programa_pos_anof = date("Y");

echo $cap->mostra_captacao_todos($programa_pos_anoi,$programa_pos_anof);	

?> 