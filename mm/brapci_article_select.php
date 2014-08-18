<?
require("db.php");
if (strlen($dd[0]) > 0)
	{
	$sql = "select * from brapci_article where id_ar = ".$dd[0];
	echo $sql;
	$rlt = db_query($sql);
	if ($line = db_read($rlt))
		{
		echo $line['ar_titulo_1'];
		echo '<HR>';
		print_r($line);
//		exit;
		setcookie("journal_ed" ,$line['ar_edition']);
		setcookie("journal_sel",$line['ar_journal_id']);
		redirecina("bracpi_article.php?dd0=".$line['ar_codigo']);
		}
	}
if (strlen($dd[1]) > 0)
	{
	$sql = "select * from brapci_article where ar_codigo = ".$dd[1];
	echo $sql;
	$rlt = db_query($sql);
	if ($line = db_read($rlt))
		{
		echo $line['ar_titulo_1'];
		echo '<HR>';
		print_r($line);
//		exit;
		setcookie("journal_ed" ,$line['ar_edition']);
		setcookie("journal_sel",$line['ar_journal_id']);
		redirecina("bracpi_article.php?dd0=".$line['ar_codigo']);
		}
	}
?>