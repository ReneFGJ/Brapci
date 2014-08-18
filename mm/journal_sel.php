<?
require("db.php");
echo $dd[0];
setcookie("journal_sel",$dd[0]);
setcookie("journal_ed",'');

$tabela = "brapci_journal";

$sql = "select * from ".$tabela." where id_jnl = 0".$dd[0];
$rlt = db_query($sql);

if ($line = db_read($rlt))
	{ $titu[0] = $line['jnl_nome']; }
	
setcookie("jid_name",$titu[0]);
setcookie("jid",$dd[0]);
redirecina("journal_edition_mst.php?dd0=".$dd[0]);
?>