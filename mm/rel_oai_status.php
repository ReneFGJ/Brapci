<?
ob_start();
////////////// Menu Position
$breadcrumps = array(); 
array_push($breadcrumps,array('principal','main.php'));
array_push($breadcrumps,array('pendências','brapci_rel_pendencia.php'));
////////////////////////////

require("cab.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_debug.php");
$titu[0] = 'Gestão da Base de Dados';


$sql = "select count(*) as total, cache_status from oai_cache ";
//$sql .= " where cache_journal = '".$jid."' ";
$sql .= " group by cache_status ";
$rlt = db_query($sql);
?>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<BR>
<?
echo '<BR>Processamento de arquivos OAI: '.$novo;
echo '<BR><BR>Resumo';

$coleta = 0;
while ($line = db_read($rlt))
	{
	if ($line['cache_status'] == '@') { $coleta = $line['total']; }
	$nm = $line['cache_status'];
	$link = '<a href="rel_oai_status.php?dd0='.$line['cache_status'].'">'; 
	if ($nm == '@') { $nm = 'Para coletar (@)';}
	if ($nm == 'A') { $nm = 'Coletado (A)'; }
	if ($nm == 'B') { $nm = 'Processado (B)'; $link = ''; }
	if ($nm == 'X') { $nm = 'Cancelado (X)'; $link = '';  }
	echo '<BR><TT>'.$link.$nm.'</a> ('.$line['total'].')</TT>';
	}
?></div>
<?
if (strlen($dd[0]) > 0)
	{
	$titu[0] = 'Pedências de coleta e processamento';
	?>
	<div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center><BR>
	<?
	$sql = "select * from oai_cache ";
	$sql .= " left join brapci_journal on jnl_codigo = cache_journal ";
	$sql .= " where cache_status='".$dd[0]."' ";
	$sql .= " order by cache_status ";
	$sql .= " limit 100 ";
	$rlt = db_query($sql);
	while ($line = db_read($rlt))
		{
		echo $line['jnl_nome'];
		echo '<BR>';
		}
	?>
	</div>
	<?
	}
require("foot.php");
?>