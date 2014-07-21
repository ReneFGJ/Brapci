<?
if (strlen($include) == 0) { exit; }
$fpdf_dir = $_SERVER['DOCUMENT_ROOT'].'/include/fphp-153/';

define('FPDF_FONTPATH',$fpdf_dir);
require('fphp-153/fpdf.php');
?>