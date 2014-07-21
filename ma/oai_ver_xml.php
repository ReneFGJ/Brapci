<?php
$include = '../';
require("../db.php");

require("../_class/_class_oai.php");
$oai = new oai;

$sql = "select * from oai_cache where id_cache = ".round($dd[0]);
$rlt = db_query($sql);
$line = db_read($rlt);

$jid = $line['cache_journal'];
$file = 'oai/'.strzero($line['id_cache'],7).'.xml';
if (!(file_exists($file)))
	{
		echo 'Arquivo não localizado.';
		exit;
	}
header ("Content-Type:text/xml");
$s = $oai->read_link_fopen($file);
echo $s;

?>
