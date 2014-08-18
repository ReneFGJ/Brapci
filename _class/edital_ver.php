<?
$include = '../';
require("../db.php");
require('../_class/_class_fomento.php');

	$clx = new fomento;
	$clx->le($dd[0]);
	
	print_r($line);
	
	$clx->lido($dd[0],$dd[1]);
	$url = trim($clx->line['ed_url_externa']);
	redirecina($url);
		
require("../foot.php");		
?> 