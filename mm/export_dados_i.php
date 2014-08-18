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
$sql = "select count(*) as article, ed_ano, ed_codigo , id_ed, ar_journal_id, sum(cited) as cited
from brapci_article 
inner join brapci_edition on ar_edition = ed_codigo
inner join brapci_section on ar_section = se_codigo
left join (SELECT count(*) as cited, `m_work` FROM `mar_works` group by m_work) as citeds on m_work = ar_codigo 
where  ar_status <> 'X' and (se_tipo = 'B')
group by ed_ano, ed_codigo ";
$rlt = db_query($sql);

/* Excluir dados anteriores */
$sql = "delete from estatistica_producao ";
$xrlt = db_query($sql);

/* Gera query da estatística */
$sql = "insert into estatistica_producao (ej_journal_id,ej_ano,ej_fasciculo,ej_cited_out,ej_cited_in,ej_update,ed_id_fasciculo) values ";
while ($line = db_read($rlt))
	{
	if (strlen($sqlq) > 0) { $sqlq .= ', '; }
	$sqlq .= "('".$line['ar_journal_id']."','".$line['ed_ano']."','".$line['article']."',";
	$sqlq .= round('0'.$line['cited']).",0,".date("Ymd").",'".$line['ed_codigo']."') ";
	}
	
$sql .= ' '.$sqlq;

/* Salva dados na base de dados */
$xrlt = db_query($sql);

/* Citações */
$sql = "select ed_codigo, total from brapci_article 
inner join brapci_edition on ar_edition = ed_codigo
inner join (
SELECT 1,count(*) as total, m_bdoi FROM mar_works where m_bdoi <> '' group by m_bdoi
) as tabelas on m_bdoi = ar_bdoi
order by total desc";
$xrlt = db_query($sql);
$sqlu = "";
while ($line = db_read($xrlt))
	{
	$sqlu = 'update estatistica_producao set ej_cited_in = (ej_cited_in + '.$line['total'].')';
	$sqlu .= " where ed_id_fasciculo = '".$line['ed_codigo']."' ";
	$yrlt = db_query($sqlu);
	}

echo '<BR><BR><CEnter>Atualização concluída</CENTER>';
?>
<BR><BR><BR>
<?
require("foot.php");
?></div>
