<?
////////////// Menu Position
$mnpos = array(); 
array_push($mnpos,array('principal','main.php'));
array_push($mnpos,array('revistas','brapci_brapci_journal.php'));
////////////////////////////

require("cab.php");
require($include."sisdoc_colunas.php");
require($include."sisdoc_debug.php");

$tabela = "";
$cp = array();
array_push($cp,array('$H8','','',False,False,''));
array_push($cp,array('$D4','','Data inicial',True,True,''));
array_push($cp,array('$D4','','Data final',True,True,''));

require($include.'sisdoc_form2.php');
require($include.'cp2_gravar.php');
require($include.'google_chart.php');
$tab_max = 450;
$http_edit = 'brapci_rel_enquete_1.php';
$http_redirect = '';
?>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<BR><CENTER>

<TABLE width="<?=$tab_max?>" align="center"><TR><TD><?
editar();
?></TD></TR></TABLE>

<?
if ($saved > 0)
{
$sql = "select * from enquete where eq_codigo = '0000001' ";
$rlt = db_query($sql);
$pg = array();
if ($line = db_read($rlt))
	{
	$peg = $line['eq_pg'];
	array_push($pg,$line['eq_r1']);
	array_push($pg,$line['eq_r2']);
	array_push($pg,$line['eq_r3']);
	array_push($pg,$line['eq_r4']);
	array_push($pg,$line['eq_r5']);
	array_push($pg,$line['eq_r6']);	
	}
$sql = "SELECT count(*) as total, erq_vlr as ed_ano from enquete_resposposta ";
$sql .= "where erq_data >= '".brtos($dd[1])."' and erq_data <= '".brtos($dd[2])."' ";
$sql .= " group by erq_vlr ";
$sql .= " order by erq_vlr ";
$rlt = db_query($sql);
$tot = 0;

$vlr = array();
$fid = array();
$max = 1;
$pmax = 65;
while ($line = db_read($rlt))
	{
	$total = $line['total'];
	if ($total > $max) { $max = $total; }
	if ($line['total'] > $max) { $max = $Line['total']; }
	array_push($fid,$pg[$line['ed_ano']]);
	array_push($vlr,$line['total']);
	$tot = $tot + $line['total'];
	$sx .= '<TR '.coluna().'>';
	$sx .= '<TD>'.$line['ed_ano'].'</TD>';
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
	$dados .= '<TD align="right">'.$fid[$t].' autor(es) ('.$vlr[$t].') '.number_format($vlr[$t]/$tot*100,2).'%</TD>';
	}
?>
<font class="lt4">Perfil dos usuários da Base</font><BR>
<font class="lt3">Data de recorte <?=$dd[1];?> até <?=$dd[2];?></font>
<BR>
<table width="98%" class="lt2" align="center" border="0">
	<TR><TH>Publicação</TH><TH>Artigos</TH></TR>
	<TR><TD colspan="2" align="center"><?=$gr;?></TD></TR>
	<TR><TD colspan="2" align="center">
		DADOS DO GRÁFICO
		<TABLE class="lt1" border=1><?=$dados;?></TABLE>
		pico em <?=$max;?>
	</TD></TR>
	<TR><TD colspan="2" align="right"><B>Total de trabalhos <?=$tot;?></B></TD></TR>
</table>



<?	
}

$titu[0] = 'Relatórios Quantitativos da Base BRAPCI - Quantidade de Artigos / Revista'; 

?>
</DIV>
