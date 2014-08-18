<?
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('pendências','brapci_artigos_semresumo.php'));
////////////////////////////

require("cab.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_debug.php");
$titu[0] = 'Artigos sem Resumo na Base de Dados';

$sql = " delete from brapci_article_author where isnull(ae_article) ";
$rlt = db_query($sql);

$sql = " delete from brapci_article_author where isnull(ae_author) ";
$rlt = db_query($sql);

$sql = " delete from brapci_article_author where ae_author='' or  ae_article=''";
$rlt = db_query($sql);

$sql = "SELECT ar_titulo_1_asc, ar_journal_id, ar_status , id_ar ";
$sql .= "FROM `brapci_article` ";
$sql .= "LEFT JOIN brapci_article_author ON `ar_codigo` = ae_article ";
$sql .= "WHERE isnull( ae_article ) ";
$sql .= " and ar_status <> 'X' ";
$sql .= "LIMIT 80 ";
$rlt = db_query($sql);
$s = '';
$tot = 0;
$journal = "X";
while ($line = db_read($rlt))
	{
	$link = '<A HREF="http://www.brapci.ufpr.br/mm/brapci_article_select.php?dd0='.$line['id_ar'].'" tabindex="new_">';
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
	$s .= $link;
	$s .= "(".$line['total'].") ";
	$s .= '<TD>';
	$s .= $line['ar_journal_id'];
	$s .= '<TD>';
	$s .= $link;
	$s .= $line['ar_status'];
	$tot = $tot + 1;
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