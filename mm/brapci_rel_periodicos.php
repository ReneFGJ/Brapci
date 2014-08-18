<?
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('relatorio','main.php'));
array_push($mnpos,array('periódico','brapci_rel_periodico.php'));

require("cab.php");
require($include."google_chart.php");
$tit[0] = "Resumo das publicações";
$sx = 'Publicações';
//$sql = "update brapci_article set ar_disponibilidade = '20090701' ";
//$rlt = db_query($sql);

$sql = "select substr(ar_disponibilidade,1,6) as mes, count(*) as total from brapci_article ";
$sql .= " inner join brapci_journal on ar_journal_id = jnl_codigo ";
$sql .= " left join brapci_edition on ar_edition = ed_codigo ";
$sql .= " inner join brapci_section on ar_section = se_codigo ";
$sql .= " where (se_tipo = 'B' )";
$sql .= " and (ar_status <> 'X') ";
$sql .= " group by mes ";
$rlt = db_query($sql);

$v1 = array();
$v2 = array();
$v3 = array();

$ini = 0;
while ($line = db_read($rlt))
	{
	$xm = trim($line['mes']);
	if (strlen($xm) > 0)
		{
		$ini = $ini + $line['total'];
		array_push($v1,$ini);
		array_push($v2,substr($line['mes'],4,2).'/'.substr($line['mes'],2,2));	
		}
	}
?>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$tit[0];?>
<BR><BR>
<font class="lt5">Crescimento da Base de Dados BRAPCI</font>
<BR>
<IMG SRC="<?=GoogleBarLine($v1,$v2);?>"><BR>
<font class="lt0">escala do gráfico 1:100</font>


<table width="50%" class="lt2">
	<TR align="center" class="lt0" bgcolor="#c0c0c0">
	<TH>mes/trabalhos</TH>
	<TH>mes/trabalhos</TH>
	<TH>mes/trabalhos</TH>
	<TH>mes/trabalhos</TH>
	<?
	$col = 99;
	for ($r=0;$r < count($v2);$r++)
		{
		if ($col > 3) { $col = 0; echo '<TR align="center">'; }
		echo '<TD>';
		echo $v2[$r];
		echo ' (';
		echo $v1[$r];
		echo ')';
		echo '</TD>';
		$col++;
		}
	?>
</table>

<?
///////////////////////////////////////////////////////////////////////////////////////////
$sql = "select substr(ar_disponibilidade,1,6) as mes, count(*) as total from brapci_article ";
$sql .= " inner join brapci_journal on ar_journal_id = jnl_codigo ";
$sql .= " inner join brapci_edition on ar_edition = ed_codigo ";
$sql .= " inner join brapci_section on ar_section = se_codigo ";
$sql .= " where (se_tipo = 'B' )";
$sql .= " and (ar_status <> 'X') ";
$sql .= " group by mes ";
$rlt = db_query($sql);

$v1 = array();
$v2 = array();
$v3 = array();

$ini = 0;
while ($line = db_read($rlt))
	{
	$xm = trim($line['mes']);
	if (strlen($xm) > 0)
		{
		$ini = $ini + $line['total'];
		array_push($v1,$ini);
		array_push($v2,substr($line['mes'],4,2).'/'.substr($line['mes'],2,2));	
		}
	}
?>
<BR><BR>
<font class="lt5">Crescimento da Base de Dados BRAPCI (Processados)</font>
<BR>
<IMG SRC="<?=GoogleBarLine($v1,$v2);?>"><BR>
<font class="lt0">escala do gráfico 1:100</font>


<table width="50%" class="lt2">
	<TR align="center" class="lt0" bgcolor="#c0c0c0">
	<TH>mes/trabalhos</TH>
	<TH>mes/trabalhos</TH>
	<TH>mes/trabalhos</TH>
	<TH>mes/trabalhos</TH>
	<?
	$col = 99;
	for ($r=0;$r < count($v2);$r++)
		{
		if ($col > 3) { $col = 0; echo '<TR align="center">'; }
		echo '<TD>';
		echo $v2[$r];
		echo ' (';
		echo $v1[$r];
		echo ')';
		echo '</TD>';
		$col++;
		}
	?>
</table>

</CENTER>
</div>
<?
require("foot.php"); ?>