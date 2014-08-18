<?
require($include."google_chart.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_debug.php");
$pub = 0;
$art = 0;
$sx = '';
$sql = " select * from (";
$sql .= "select count(*) as total, ar_journal_id from brapci_article group by ar_journal_id ";
$sql .= ") as tabela ";
$sql .= " inner join brapci_journal on ar_journal_id = jnl_codigo ";
$sql .= " order by total desc ";
$rlt = db_query($sql);

$xpub = "X";
$v1 = array();
$v2 = array();
$outra = 0;
$otr   = 0;
while ($line = db_read($rlt))
	{
	$link = '<A HREF="journal_sel.php?dd0='.$line['id_jnl'].'" class="lt2">';
	$art = $art + $line['total'];
	if ($xpub != $line['jnl_codigo'])
		{ $pub++; $xpub = $line['jnl_codigo']; }
	$sx .= '<TR '.coluna().'><TD>'.$link.$line['jnl_nome'].'</A></TD>';
	$sx .= '<TD align="right"><B>'.$line['total'].'</B> trabalhos</TD>';
	$sx .= '</TR>';
	if ($line['total'] < 40)
		{ $outra = $outra + $line['total']; $otr++; } else
		{
			array_push($v1,$line['total']);
			array_push($v2,troca(LowerCaseSQL(troca(trim($line['jnl_nome_abrev']),' ','%20')),'&','e'));
		}
	}
if ($outra > 0)
	{
			array_push($v1,$outra);
			array_push($v2,'outras ('.$otr.')');
	}
?>

</div>
