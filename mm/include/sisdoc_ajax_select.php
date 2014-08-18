<?php
require("db.php");
$p = array();
$s = $_POST['para'].':';
$s = $_GET['para'].':';
while (strpos($s,':') > 0)
	{
	$ps = strpos($s,':');
	$sx = trim(substr($s,0,$ps));
	$s = ' '.substr($s,$ps+1,strlen($s));
	array_push($p,$sx);
	}
	
$db = $p[3];
/**
* Esta classe é a responsável pela conexão com o banco de dados.
* @author Rene F. Gabriel Junior <rene@sisdoc.com.br>
* @version 0.0a
* @copyright Copyright © 2011, Rene F. Gabriel Junior.
* @access public
* @package BIBLIOTECA
* @subpackage sisdoc_ajax
*/
///////////////////////////////////////////
// Versão atual           //    data     //
//---------------------------------------//
// 0.0a                       28/01/2008 // Primeiras Rotinas em AJAX
///////////////////////////////////////////
?>
<option value="XX"><?=$db;?></option>
<option><?php print_r($_POST); ?></option>

<?
for ($r=0;$r < count($p);$r++)
	{
	echo '<option value="">';
	echo $p[$r];
	echo '</opton>';
	}
?>xx


