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

/* Calcula os tipos de publicações */
$sql = "select count(*) as total, jnl_status, jnl_tipo, jtp_descricao "; 
$sql .= " from brapci_journal ";
$sql .= " inner join brapci_journal_tipo on jtp_codigo = jnl_tipo ";
$sql .= " where jnl_status <> 'X' ";
$sql .= " group by jnl_status, jnl_tipo, jtp_descricao ";
$rlt = db_query($sql);

/* Total de periódicos ativos */
$st = '<table class="lt1" width="'.$sz.'" cellpadding="4" cellspacing="0" border="0">';
$st .= '<TR><TD colspan="2" align="center" bgcolor="#c0c0c0">A Base em números</TD></TR>';
$s = '';
$total = 0;
while ($line = db_read($rlt))
	{
	$total = $total + $line['total'];
	if ($line['jnl_status'] == 'A') { $vg = ' vigente(s)'; } else { $vg = ' descontinuada(s)'; }
	$s .= '<TR><TD align="right"><font color="#B0B0B0">'.$line['total'].'</B></TD><TD><font color="#B0B0B0">';
	$s .= LowerCase(trim($line['jtp_descricao'])).' '.$vg.'</TD></TR>';
	}
	$s = '<TR><TD align="right"><B>'.$total.'</B></TD><TD>publicações indexadas</TD></TR>'.$s;


/* Fasciculos Publicados */
$sql = "select count(*) as fasciculos, sum(artigos) as artigos from
	( SELECT `ed_codigo`, count(*) as artigos FROM `brapci_edition` 
	inner join brapci_article on ed_codigo = ar_edition ";
$sql .= " inner join brapci_section on se_codigo = ar_section ";
$sql .= " where ar_status <> 'X' and se_tipo = 'B' group by ed_codigo	) as fasciculos  ";
$rlt = db_query($sql);
$line = db_read($rlt);

$s .= '<TR><TD align="right"><B>'.$line['fasciculos'].'</B></B></TD><TD>fascículos</TD></TR>';
$s .= '<TR><TD align="right"><B>'.$line['artigos'].'</B></B></TD><TD>artigos disponibilizados</TD></TR>';

/* Citações */
$sql = "select count(*) as artigos, sum(citacoes) as citacoes from (
select count(*) as citacoes from mar_works group by m_work
) as tabela ";
$rlt = db_query($sql);
$line = db_read($rlt);
$s .= '<TR><TD align="right"><B>'.$line['citacoes'].'</B></B></TD><TD>citações (referências) forneceidas</TD></TR>';
$s .= '<TR><TD align="right"><font color="#B0B0B0">'.$line['artigos'].'</TD><TD><font color="#B0B0B0">Artigos com marcação (referências)</TD></TR>';

$st .= $s. '</table>';

/* Tipos de trabalhos */
$sql = "select count(*) as fasciculos, sum(artigos) as artigos, se_descricao from ( ";
$sql .= "SELECT `ed_codigo`, count(*) as artigos, ar_section, se_descricao ";
$sql .= " FROM `brapci_edition` ";
$sql .= " inner join brapci_article on ed_codigo = ar_edition ";
$sql .= " inner join brapci_section on se_codigo = ar_section ";
$sql .= " where ar_status <> 'X' and (se_tipo <> '-' and se_tipo <> 'Z' and se_tipo <> 'E') group by ed_codigo, ar_section ) ";
$sql .= " as fasciculos group by ar_section, se_descricao ";
$sql .= " order by artigos desc ";
$rlt = db_query($sql);

$s = '<table class="lt1" width="'.$sz.'" cellpadding="4" cellspacing="0" border="0">';
$s .= '<TR><TD colspan="2" align="center" bgcolor="#c0c0c0">Composição das seções*</TD></TR>';
while ($line = db_read($rlt))
	{
	if ($line['artigos'] > 20)
		{ 
		$s .= '<TR>';
		$s .= '<TD>'.trim($line['se_descricao']).'</TD>'; 
		$s .= '<TD align="right">'.$line['artigos'].'</B></TD>';
		}
	}
$s .= '<TR><TD colspan="2" class="lt0">* Algumas seções não são disponibilizadas para consulta</TD></TR>';
$s .= '<TR><TD colspan="2" class="lt0"><BR><BR>Dados gerados em '.date("d/m/Y").'.</TD></TR>';
$s .= '</table>';

$st .= $s;

$myFile = "dados_resumo.php";
$fh = fopen($myFile, 'w') or die("can't open file");
fwrite($fh, $st);
fclose($fh);
echo '<CENTER>Resultado da Exportação de Dados<BR><BR>';
echo $st;
?>
<BR><BR><BR>
<?
require("foot.php");
?></div>
