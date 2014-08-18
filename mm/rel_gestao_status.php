<?
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('pendências','brapci_rel_pendencia.php'));
////////////////////////////

require("cab.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_debug.php");
$titu[0] = 'Gestão da Base de Dados';
?>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<BR>
<?
$sql = "
SELECT * FROM (
SELECT `ar_status`, count(*) as total, `ar_journal_id` as journal FROM `brapci_article`
where ar_status <> 'X' group by `ar_status`, `ar_journal_id`
) as tabela 
inner join brapci_journal on journal = jnl_codigo
order by jnl_nome
";
$rlt = db_query($sql);

$jnl = array();
$xcod = 'a';
$row = -1;
while ($line = db_read($rlt))
	{
	$cod = $line['jnl_codigo'];
	if ($cod != $xcod)
		{
		$row++;
		$nome = trim($line['jnl_nome']);
		array_push($jnl,array($cod,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,$nome));
		$xcod = $cod;
		}
	$sta = $line['ar_status'];
	$pos = 9;
	if ($sta == 'A') { $pos = 1; }
	if ($sta == 'B') { $pos = 2; }
	if ($sta == 'C') { $pos = 3; }
	if ($sta == 'D') { $pos = 4; }
	if ($sta == 'E') { $pos = 5; }
	
	$tot = $line['total'];
	$jnl[$row][$pos] = $jnl[$row][$pos] + $tot;
	$jnl[$row][10] = $jnl[$row][10] + $tot;
	}
$v1 = 0;
$v2 = 0;
$v3 = 0;
$v4 = 0;
$v10 = 0;


for ($r=0;$r < count($jnl);$r++)
	{
	$v1 = $v1 + $jnl[$r][1];
	$v2 = $v2 + $jnl[$r][2];
	$v3 = $v3 + $jnl[$r][3];
	$v4 = $v4 + $jnl[$r][4];

	$max = $jnl[$r][10];
	if ($max > 0)
		{
		$m1 = $jnl[$r][1] / $max *100;
		$m2 = $jnl[$r][2] / $max *100;
		$m3 = $jnl[$r][3] / $max *100;
		$m4 = $jnl[$r][4] / $max *100;
		} else {
		$m1 = 0;
		$m2 = 0;
		$m3 = 0;
		$m4 = 0;
		}
	$sr .= '<TR '.coluna().'>';
	$sr .= '<TD>';
	$sr .= $jnl[$r][17];
	
	$sr .= '<TD align="center">';
	$sr .= $jnl[$r][1];
	$sr .= '<TD>';
	$sr .= '('.number_format($m1,1).'%)';

	$sr .= '<TD align="center">';
	$sr .= $jnl[$r][2];
	$sr .= '<TD>';
	$sr .= '('.number_format($m2,1).'%)';

	$sr .= '<TD align="center">';
	$sr .= $jnl[$r][3];
	$sr .= '<TD>';
	$sr .= '('.number_format($m3,1).'%)';

	$sr .= '<TD align="center">';
	$sr .= $jnl[$r][4];
	$sr .= '<TD>';
	$sr .= '('.number_format($m4,1).'%)';

	$sr .= '<TD align="center">';
	$sr .= $jnl[$r][10];
	$sr .= '</TR>';
	}

///////////////////////
	$v10 = $v10 + $v1 + $v2 + $v3 +$v4;

	$max = $v10;
	if ($max > 0)
		{
		$m1 = $v1 / $max *100;
		$m2 = $v2 / $max *100;
		$m3 = $v3 / $max *100;
		$m4 = $v4 / $max *100;
		} else {
		$m1 = 0;
		$m2 = 0;
		$m3 = 0;
		$m4 = 0;
		}
	$sr .= '<TR '.coluna().'>';
	$sr .= '<TD align="right"><B>Total</B>';
	
	$sr .= '<TD align="center">';
	$sr .= $v1;
	$sr .= '<TD><B>';
	$sr .= '('.number_format($m1,1).'%)';

	$sr .= '<TD align="center"><B>';
	$sr .= $v2;
	$sr .= '<TD><B>';
	$sr .= '('.number_format($m2,1).'%)';

	$sr .= '<TD align="center"><B>';
	$sr .= $v3;
	$sr .= '<TD><B>';
	$sr .= '('.number_format($m3,1).'%)';

	$sr .= '<TD align="center"><B>';
	$sr .= $v4;
	$sr .= '<TD><B>';
	$sr .= '('.number_format($m4,1).'%)';

	$sr .= '<TD align="center"><B>';
	$sr .= $max;
	$sr .= '</TR>';

?>
<TABLE width="95%" class="lt1">
<TR><TH>Publicação</TH></TR>
<?=$sr;?>
</TABLE>
<?
require("foot.php");
?>