<?
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('revistas','brapci_brapci_journal.php'));
////////////////////////////

require("cab.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_debug.php");

$http_redirect = '';
$titu[0] = "Autores mais encontrados nos trabalhos";
?>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<BR><CENTER>
<?
$sql = "select * from brapci_journal ";
$sql .= " left join brapci_qualis on q_issn = jnl_issn_impresso ";
$sql .= " where jnl_status <> 'X' ";
$sql .= " order by jnl_status, q_qualis ";
$rlt = db_query($sql);

$tp = 'X';
while ($line = db_read($rlt))
	{
	$link = '<A HREF="http://www.brapci.ufpr.br/mm/journal_sel.php?dd0='.$line['id_jnl'].'">';
	if ($line['jnl_status'] != $tp)
		{
		$tp = $line['jnl_status'];
		if ($tp == 'A') { $stn = 'Periódicos Ativos'; }
		if ($tp == 'B') { $stn = 'Periódicos Históricos'; }
		$s .= '<TR><TD colspan="5" class="lt3">'.$stn.'</TD></TR>';
		}
	$s .= '<TR '.coluna().' valign="top">';
	$s .= '<TD>';
	$s .= $line['jnl_issn_impresso'];
	$s .= '<TD>';
	$s .= $link.$line['jnl_nome'].'</A>';
	$s .= '<BR><font class="lt0">';
	$s .= $line['jnl_nome_abrev'];
	$s .= '<TD><NOBR>';
	$s .= $line['q_qualis'].' ('.$line['q_ano'].')';
	$s .= '</TD>';
	$s .= '</TR>';
	}
?>
<table width="<?=$tab_max;?>" align="center" class="lt1">
	<TR>
	<TH>ISSN Impr.</TH>
	<TH>Título do periódico</TH>
	<TH>Abrev.</TH>
	<TH>Qualis</TH>
	</TR>
<?=$s;?>
</table>
</DIV>
