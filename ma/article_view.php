<?php
require ("cab.php");
require ("../_class/_class_issue.php");
require ("../_class/_class_author.php");
require ("../_class/_class_publications.php");
require ("../_class/_class_progressive_bar.php");
$res = new publications;

require ('../include/sisdoc_windows.php');

$art = $dd[0];
$editar = 1;
$res -> article_id = $art;
$res -> le($art);

/* Progress Bar */
$status = trim($res -> line['ar_status']);

$it = array('Análise', 'Revisão 1', 'Revisão 2', 'Finalizado');
$pos = 3;

switch ($status)
	{
	case '@': $pos = 0; break;
	case 'A': $pos = 0; break;
	case 'B': $pos = 1; break;
	case 'C': $pos = 2; break;
	case 'F': $pos = 3; break;
	case 'X': $pos = 9; break;
	}
echo progress($it, $pos);
/****************/

echo $res -> show_article($art);

echo $res -> show_files($art);
echo $res -> upload_files($art);

echo $res -> show_references($art);

/* Acoes */
echo $res -> acoes();

echo '<BR><BR>';
echo $res -> seek_google();

echo '<BR><BR><BR><BR>';
echo '-';

require ("../foot.php");
?>
