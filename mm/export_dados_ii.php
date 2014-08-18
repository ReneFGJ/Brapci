<?
require("cab.php");
require($include.'sisdoc_debug.php');
require("brapci_autor_qualificacao.php");
?>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right">
EXPORTAÇÃO DOS NÚMEROS DA BASE PARA O MÓDULO PESQUISADOR
<BR><BR><BR>
<?
$sz = 300;

/* Calcula quantidade de artigos */
$sql = "SELECT ar_codigo, ar_journal_id, ar_edition, total, ed_ano, ar_edition, ed_periodo, ar_bdoi
FROM brapci_article 
inner join brapci_edition on ar_edition = ed_codigo
left join 
( select m_work, count(*) as total from mar_works group by m_work ) as tabela
on m_work = ar_codigo WHERE ar_status <> 'X' and ar_edition <> ''
order by total desc ";
$rlt = db_query($sql);

/* Excluir dados anteriores */
$sql = "delete from estatistica_artigos ";
$xrlt = db_query($sql);

/* Gera query da estatística */
$sql = "insert into estatistica_artigos (";
$sql .= "ea_bdoi, ea_article,ea_journal_id,ea_cited_in, ";
$sql .= "ea_cited_out,ea_ano,ae_edition,ea_update,ea_periodo) values ";
while ($line = db_read($rlt))
	{
	if (strlen($sqlq) > 0) { $sqlq .= ', '; }
	$sqlq .= "('";
	$sqlq .= $line['ar_bdoi']."',";
	$sqlq .= "'".$line['ar_codigo']."','".$line['ar_journal_id']."','".$line['total']."',";
	$sqlq .= round('0').",'".$line['ed_ano']."','".$line['ar_edition']."',".date("Ymd");
	$sqlq .= ",'".$line['ed_periodo']."') ";
	}
	
$sql .= ' '.$sqlq;

/* Salva dados na base de dados */
$xrlt = db_query($sql);

/* Citações */
$sql = "SELECT count(*) as total, m_bdoi FROM mar_works where m_bdoi <> '' group by m_bdoi ";
$xrlt = db_query($sql);
$sqlu = "";
while ($line = db_read($xrlt))
	{
	$sqlu = 'update estatistica_artigos set ea_cited_out = (ea_cited_out + '.$line['total'].')';
	$sqlu .= " where ea_bdoi = '".trim($line['m_bdoi'])."' ";
	$yrlt = db_query($sqlu);
	}

echo '<BR><BR><CEnter>Atualização concluída</CENTER>';
?>
<BR><BR><BR>
<?
require("foot.php");
?></div>
