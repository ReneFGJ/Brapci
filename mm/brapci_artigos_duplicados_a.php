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

if ($dd[4] == 'DEL')
	{
	$sql = "update brapci_article set ar_status = 'X' ";
	$sql .= " , ar_journal_id = 'X".substr($dd[2],1,6)."' ";
	$sql .= " where id_ar = 0".$dd[3];
	$sql .= " and ar_journal_id = '".$dd[2]."' ";
	$sql .= " and ar_edition = '' ";
	$rlt = db_query($sql);
	}

$sql = "SELECT * FROM brapci_article ";
$sql .= " inner join brapci_journal on ar_journal_id = jnl_codigo ";
$sql .= " left join brapci_edition on ar_edition = ed_codigo ";
$sql .= " WHERE ar_titulo_1_asc = '".$dd[1]."' ";
$sql .= " and ar_journal_id = '".$dd[2]."' ";
$sql .= " and ar_status <> 'X' ";
$rlt = db_query($sql);

$s = '';
$tot = 0;
$journal = "X";
while ($line = db_read($rlt))
	{
	$tot++;
	$link = '<A HREF="http://www.brapci.ufpr.br/mm/brapci_article_select.php?dd0='.$line['id_ar'].'">';
	$s .= '<TR '.coluna().'><TD colspan="3" class="lt2">';
	$s .= $link;
	$s .= $line['ar_titulo_1'];
	$s .= '<TD>';
	$s .= $line['ar_edition'];
	$s .= '<TD>';
	$s .= $line['ar_status'];
	if (strlen(trim($line['ar_edition'])) == 0)
		{
		$s .= '<TD>';
		$s .= '<A href="brapci_artigos_duplicados_a.php?dd1='.$dd[1].'&dd2='.$dd[2].'&dd3='.$line['id_ar'].'&dd4=DEL">';
		$s .= '[excluir]';
		$s .= '</A>';
		}
	$s .= '</TD></TR>';
	echo '<HR>';
	}
	
if ($tot <= 1)
	{
	redirecina("brapci_artigos_duplicados.php");
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
<BR>
<a href="brapci_artigos_duplicados.php">Voltar para trabalhos duplicados</a>
</DIV>