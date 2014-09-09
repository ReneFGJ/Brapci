<?
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('revistas','brapci_brapci_journal.php'));
////////////////////////////

require("cab.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_debug.php");
ini_set('display_errors', 1);
ini_set('error_reporting', 7);
$tabela = "";
$cp = array();
array_push($cp,array('$H8','','',False,False,''));
array_push($cp,array('$S4','','Ano Inicial do Recorte',True,True,''));
array_push($cp,array('$S4','','Ano Final do Recorte',True,True,''));

require($include.'_class_form.php');
$form = new form;
require($include.'google_chart.php');
$tab_max = 450;
$http_edit = 'brapci_rel_quanti_3.php';
$http_redirect = '';
?>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<BR><CENTER>

<TABLE width="<?=$tab_max?>" align="center"><TR><TD><?
echo $form->editar($cp,'');
?></TD></TR></TABLE>

<?
function msg($x) { return($x); }
if ($form->saved > 0)
{
$sql = "select count(*) as total from (";
$sql .= "select 1, ar_codigo from brapci_article ";
$sql .= "inner join brapci_edition on ar_edition = ed_codigo ";
$sql .= "inner join brapci_section on ar_section = se_codigo ";
$sql .= "inner join (select count(*) as autor, ae_article from brapci_article_author group by ae_article) as tabela02 on ae_article = ar_codigo ";
$sql .= " where (se_tipo = 'B' and ar_status <> 'X' )";
if (strlen($dd[1]) > 0) { $sql .= " and ( ed_ano >= '".$dd[1]."') "; }
if (strlen($dd[2]) > 0) { $sql .= " and ( ed_ano <= '".$dd[2]."') "; }
$sql .= ") as total ";

$rlt = db_query($sql);

if ($line = db_read($rlt))
	{
	print_r($line);
	}

$sql = "select count(*) as total, autores from ( ";
$sql .= "SELECT count(*) as autores, ae_article FROM brapci_article  ";
$sql .= "inner join brapci_article_author on ar_codigo = ae_article ";
$sql .= "inner join brapci_edition on ar_edition = ed_codigo ";
$sql .= " inner join brapci_section on ar_section = se_codigo ";
$sql .= " where (se_tipo = 'B' and ar_status <> 'X' )";
if (strlen($dd[1]) > 0) { $sql .= " and ( ed_ano >= '".$dd[1]."') "; }
if (strlen($dd[2]) > 0) { $sql .= " and ( ed_ano <= '".$dd[2]."') "; }
$sql .= "group by ae_article ) as tabelas group by autores ";
$rlt = db_query($sql);
$tor = 0;


$vlr = array();
$fid = array();
$max = 1;
$pmax = 65;
while ($line = db_read($rlt))
	{
	$total = $line['total'];
	if ($total > $max) { $max = $total; }
	if ($line['total'] > $max) { $max = $Line['total']; }
	array_push($fid,$line['autores']);
	array_push($vlr,$line['total']);
	$tor = $tor + $line['total'];
	$sx .= '<TR '.coluna().'>';
	$sx .= '<TD>'.$line['autores'].'</TD>';
	$sx .= '<TD align="right">'.$line['total'].'</TD>';
	$sx .= '</TR>';
	}
	
///////////////////////////////////////////////////// GRAFICO

$gr =  '<IMG SRC="';
$gr .=  'http://chart.apis.google.com/chart?cht=p&chs=750x350&&chd=s:';
$gr1 = '';
for ($r=0;$r < count($vlr);$r++)
	{
	$ppp = intval($vlr[$r]/$max*65);
	$gr .= $grvlr[$ppp];
	$gr1 .= $fid[$r].' autor(es)|';
	}
$gr .= '&chl='.$gr1;
$gr .= '">';

$dados = '';
$corte = 99;
for ($t=0;$t < count($vlr);$t++)
	{
	if ($corte > 3)
		{ $dados .= '<TR>'; $corte = 0; }
	$corte++;
	$dados .= '<TD align="right">'.$fid[$t].' autor(es) ('.$vlr[$t].') '.number_format($vlr[$t]/$tor*100,2).'%</TD>';
	}
?>
<font class="lt4">Quantidade de artigos publicados / nº de autores</font><BR>
<font class="lt3">Ano de recorte <?=$dd[1];?> até <?=$dd[2];?></font>
<BR>
<table width="98%" class="lt2" align="center" border="0">
	<TR><TH>Publicação</TH><TH>Artigos</TH></TR>
	<TR><TD colspan="2" align="center"><?=$gr;?></TD></TR>
	<TR><TD colspan="2" align="center">
		DADOS DO GRÁFICO
		<TABLE class="lt1" border=1><?=$dados;?></TABLE>
		amplitude em <?=$max;?>
	</TD></TR>
	<TR><TD colspan="2" align="right"><B>Total de trabalhos <?=$tor;?></B></TD></TR>
</table>

<?	
}

$titu[0] = 'Relatórios Quantitativos da Base BRAPCI - Quantidade de Artigos / Revista'; 

?>
</DIV>
