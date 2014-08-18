<?
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('pendências','brapci_rel_pendencia.php'));
////////////////////////////

require("cab.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_debug.php");
$titu[0] = 'Artigos Duplicados na Base de Dados';

$sql = "select jnl_nome, ar_titulo_1_asc, ar_journal_id, total from ( SELECT substring(ar_titulo_1_asc,1,120) as  ar_titulo_1_asc , count(*) as total, ar_journal_id FROM brapci_article ";
$sql .= " where ar_status <> 'X' group by ar_titulo_1_asc,  ar_journal_id) as tabela ";
$sql .= " inner join brapci_journal on ar_journal_id = jnl_codigo ";
$sql .= " where total > 1 ";
$sql .= " order by jnl_nome, ar_titulo_1_asc ";
$rlt = db_query($sql);

$s = '';
$tot = 0;
$journal = "X";
while ($line = db_read($rlt))
	{
	$link = '<A HREF="brapci_artigos_duplicados_a.php?dd1='.trim($line['ar_titulo_1_asc']).'&dd2='.$line['ar_journal_id'].'" target="_new">';
	$jnome = $line['jnl_nome'];
	if ($journal != $jnome)
		{
		$s .= '<TR valign="top" >';
		$s .= '<TD colspan="3" class="lt3">'.$jnome.'</TD>';
		$journal = $jnome;
		}
	$s .= '<TR valign="top" '.coluna().'>';
	$s .= '<TD>';
	$s .= $link;
	$s .= $line['ar_titulo_1_asc'];
	$s .= '<TD>';
	$s .= "(".$line['total'].") ";
	$s .= '<TD>';
	$s .= $line['ar_journal_id'];
	$tot = $tot + $line['total']-1;
	}
?>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<table width="100%" class="lt0">
<TR>
	<TH>título do trabalho</TH>
	<TH>total</TH>
	<TH>public.</TH>
</TR>
<?
	echo $s;
?>
</table>
<BR>total de <B><?=$tot;?> trabalhos duplicados</B>
</DIV>