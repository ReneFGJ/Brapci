<?
$include = '../';
require("../db.php");
require("../_class/_class_language.php");
require("../_class/_class_cep_submit_grupos.php");
$gr = new grupos;

$file = '../messages/msg_submit_protocol.php';
if (file_exists($file)) { require($file); }

$pst = $_POST;
if (count($pst) > 0)
	{
		for ($r=0;$r < 50;$r++)
			{ $dd[$r] = troca($_POST['gr'.$r],"'","´"); }
		$dd[1] = round($dd[1]);
		$dd[2] = round($dd[2]);
		$dd[3] = round($dd[3]);
		$ok = 1;
		if (($dd[1]+$dd[2]+$dd[3]) <= 0) { $ok = 0; }
		if (strlen($dd[9])== 0) { $ok = 0; }			
		if (strlen($dd[8])== 0) { $ok = 0; }			
		if (strlen($dd[7])== 0) { $ok = 0; }			
		if (strlen($dd[6])== 0) { $ok = 0; }
		if (strlen($dd[4])== 0) { $ok = 0; }
		if ($ok == 1)
			{ echo 'SALVO'; exit; }
		}			

echo $gr->form();
?>

