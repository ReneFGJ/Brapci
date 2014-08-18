<?
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('revistas','brapci_brapci_journal.php'));
////////////////////////////

require("cab.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_debug.php");
require("updatex_article_asc.php");

$tabela = "brapci_journal";

$sql = "select * from ".$tabela." ";
$sql .= " left join brapci_qualis on q_issn = jnl_issn_impresso ";
$sql .= " where id_jnl = 0".$jid ;
$rlt = db_query($sql);

if ($line = db_read($rlt))
	{
	$link = '<A HREF="'.trim($line['jnl_url']).'" target="new'.date("Hmis").'">';
	$titu[0] = $line['jnl_nome'];
	}
require("journal_detalhe.php");
require("journal_edition.php");
?>Total de <?=$atu;?> atualizados
