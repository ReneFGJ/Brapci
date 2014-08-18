<?
require("journal_edition_update.php");
require($include.'sisdoc_colunas.php');
if ($user_nivel == 1) { $perfil = "B"; }
if ($user_nivel == 2) { $perfil = "A"; }



$tit[0] = 'Pendências';

$sql = "select * from brapci_journal ";

$sql .= "inner join (select count(*) as jnl_artigos_indexados, ar_journal_id , ar_status ";
$sql .= " from brapci_article ";
$sql .= " where ar_status = '".$perfil."' ";
$sql .= " group by ar_journal_id, ar_status ";
$sql .= ") as tabela";
$sql .= " on ar_journal_id = jnl_codigo ";
$sql .= " where ar_status = '".$perfil."' ";
$sql .= " order by jnl_nome ";
//$sql .= " order by jnl_update desc ";
////////////////////////////////////////// Resumo das indexações

$sql = "select (sum(A)) as A, (sum(B)) as B, (sum(C)) as C, (sum(D)) as D, ";
$sql .= " ar_journal_id, id_jnl, jnl_nome, jnl_update, jnl_artigos_indexados, jnl_artigos from (";
$sql .= "select count(*) as A,0 as B,0 as C,0 as D, ar_journal_id  from brapci_article where ar_status = 'A' group by ar_status = 'A', ar_journal_id ";
$sql .= " union ";
$sql .= "select 0,count(*) as A,0,0, ar_journal_id  from brapci_article where ar_status = 'B' group by ar_status = 'B',ar_journal_id  ";
$sql .= " union ";
$sql .= "select 0,0,count(*) as A,0, ar_journal_id  from brapci_article where ar_status = 'C' group by ar_status = 'C',ar_journal_id  ";
$sql .= " union ";
$sql .= "select 0,0,0,count(*) as A, ar_journal_id  from brapci_article where ar_status = 'D' group by ar_status = 'D',ar_journal_id  ";
$sql .= ") as tabela ";
$sql .= "inner join brapci_journal on ar_journal_id = jnl_codigo ";
//$sql .= " where jnl_ativo = 1";
$sql .= " group by ar_journal_id ";
$sql .= " order by jnl_nome ";

$rlt = db_query($sql);
$sx = '';
while ($line = db_read($rlt))
	{
	$in1 = '-';
	$in2 = '-';
	$t1 = ($line['A']+$line['B']+$line['C']+$line['D']);
	$t2 = ($line['B']+$line['C']);
	$t3 = ($line['A']);
	$cor = '';
	if (($t3 == 0) and ($t2 == 0)) { $cor = '<font color="#008000"><B><I>'; }
	
	if ($t1 > 0) { $in1 = number_format(100*($t3/$t1),1).'%'; }
	if ($t1 > 0) { $in2 = number_format(100*($t2/$t1),1).'%'; }
	if ($in1 == '0.0%') { $in1 = ''; }
	if ($in2 == '0.0%') { $in2 = ''; }
	$link = '<A HREF="journal_sel.php?dd0='.$line['id_jnl'].'" class="lt2">';
	$sx .= '<TR '.coluna().'>';
	$sx .= '<TD>'.$link.$cor.trim($line['jnl_nome']);
	$sx .= '<TD align="center" width="4%">'.$line['A'];
	$sx .= '<TD align="center" width="4%">'.$in1.'</TD>';
	$sx .= '<TD align="center" width="0%">|</TD>';
	$sx .= '<TD align="center" width="4%">'.($line['C'] + $line['B']);
	$sx .= '<TD align="center" width="4%">'.$in2.'</TD>';
	$sx .= '<TD align="center" width="0%">|</TD>';
	$sx .= '<TD align="center" width="4%">'.$line['D'].'</TD>';
	$sx .= '<TD align="center" width="0%">|</TD>';
	$sx .= '<TD align="center" width="4%"><B>'.$t1.'</B></TD>';
	$sx .= '<TD align="center" width="0%">|</TD>';
	$sx .= '<TD>'.stodbr($line['jnl_update']);
	}
	
?>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$tit[0];?></center>
<BR>
<table width="99%" class="lt1">
<TR class="lt0">
	<TH>Título</TH>
	<TH colspan="3">indexação</TH>
	<TH colspan="3">1º/2º revisão</TH>
	<TH colspan="1">revisado</TH>
	<TH></TH>
	<TH colspan="2">total</TH>
	<TH>atualizado</TH>
</TR>
<?=$sx;?>
</table>
</div>