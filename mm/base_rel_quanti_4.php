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
array_push($cp,array('$S4','','Ano Inicial do Recorte',True,True,''));
array_push($cp,array('$S4','','Ano Final do Recorte',True,True,''));
array_push($cp,array('$B4','','Gerar análise >>',false,True,''));
if (strlen($dd[1]) == 0) { $dd[1] = '1972'; }
if (strlen($dd[2]) == 0) { $dd[2] = date("Y"); }

require($include.'sisdoc_form2.php');
require($include.'cp2_gravar.php');
require($include.'google_chart.php');
$tab_max = 450;
$http_edit = 'base_rel_quanti_4.php';
$http_redirect = '';
$tit = "Quantidade de artigos publicados por ano em coautoria";
$metodologia = "<B>Metodologia:</B> São selecionados trabalhos cuja seção estão enquadrados como artigos científicos (Tipo B) na base de todas as publicações disponíveis na base. Desta seleção são agrupado e mostrados a quantidade de trabalhos com o seu respectivo número de autores.";


?>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<BR><CENTER>
<BR><BR><font class="Lt3"><?=$tit;?></font>
<TABLE width="<?=$tab_max?>" align="center">
<TR><TD><?
editar();
?></TD></TR>
<TR><TD class="lt1"><?=$metodologia;?></TD></TR>
</TABLE>

<?
if ($saved > 0)
{
$dmax = $dd[2] - $dd[1];
if ($dmax > 40)
	{
	echo 'Diferença entre anos é superior a 40 anos. ';
	exit;
	}
$ano1 = intval($dd[1]);
$ano2 = intval($dd[2]);

$aut1 = array();
$aut2 = array();
$aut3 = array();
$aut4 = array();
$aut5 = array();
$fid  = array();
for ($r=$ano1; $r <= $ano2; $r++)
	{
	array_push($aut1,0);
	array_push($aut2,0);
	array_push($aut3,0);
	array_push($aut4,0);
	array_push($aut5,0);
	array_push($fid,$r);
	}

///////////////////////////////////////////////////////////////////////////////////////

$sql = "select count(*) as total, autores, ed_ano from (  ";
$sql .= "SELECT count(*) as autores, `ae_article`, ed_ano FROM brapci_article_author  ";
$sql .= "inner join brapci_article on ar_codigo = ae_article  ";
$sql .= "inner join brapci_edition on ar_edition = ed_codigo  ";
$sql .= "inner join brapci_section on ar_section = se_codigo  ";
$sql .= " where (se_tipo = 'B' and ar_status <> 'X' )";
if (strlen($dd[1]) > 0) { $sql .= " and ( ed_ano >= '".$dd[1]."') "; }
if (strlen($dd[2]) > 0) { $sql .= " and ( ed_ano <= '".$dd[2]."') "; }
$sql .= "group by ae_article, ed_ano ) as tabelas group by autores, ed_ano ";
$sql .= " order by ed_ano ";

$rlt = db_query($sql);
$tot = 0;

$max = 1;
$pmax = 65;
while ($line = db_read($rlt))
	{
	$total = $line['total'];
	$autor = $line['autores'];
	if ($autor >= 5) { $autor = 5; }
	
	if ($total > $max) { $max = $total; }
	if ($line['total'] > $max) { $max = $Line['total']; }
	$tt = ($line['ed_ano']-$dd[1]);
//	$fid[$tt] = $line['ed_ano'];
	if ($autor == 1) { $aut1[$tt] = $total; }
	if ($autor == 2) { $aut2[$tt] = $total; }
	if ($autor == 3) { $aut3[$tt] = $total; }
	if ($autor == 4) { $aut4[$tt] = $total; }
	if ($autor == 5) { $aut5[$tt] = $total; }
//	echo '<BR>'.$line['ed_ano'].'('.$tt.')='.$autor.' '.$total;
	$tot = $tot + $line['total'];
	}
	
///////////////////////////////////////////////////// GRAFICO

for ($t=0;$t < count($fid);$t++)
	{
	$i1 = round(intval('0'.$aut1[$t])/$max*65);
	$i2 = round(intval('0'.$aut2[$t])/$max*65);
	$i3 = round(intval('0'.$aut3[$t])/$max*65);
	$i4 = round(intval('0'.$aut4[$t])/$max*65);
	$i5 = round(intval('0'.$aut5[$t])/$max*65);
	
	$g1 .= $grvlr[$i1];
	$g2 .= $grvlr[$i2];
	$g3 .= $grvlr[$i3];
	$g4 .= $grvlr[$i4];
	$g5 .= $grvlr[$i5];
	
	$td = $aut1[$t]+$aut2[$t]+$aut3[$t]+$aut4[$t]+$aut5[$t];
	if ($td == 0) { $td = 0.0000001; }
	$dados .= '<TR >';
	$dados .= '<TD align="center">&nbsp;&nbsp;'.$fid[$t].'&nbsp;&nbsp;</TD>';
	$dados .= '<TD align="center">'.$aut1[$t].'<TD align="right">'.number_format($aut1[$t]/$td*100,1).'%</TD>';
	$dados .= '<TD align="center">'.$aut2[$t].'<TD align="right">'.number_format($aut2[$t]/$td*100,1).'%</TD>';
	$dados .= '<TD align="center">'.$aut3[$t].'<TD align="right">'.number_format($aut3[$t]/$td*100,1).'%</TD>';
	$dados .= '<TD align="center">'.$aut4[$t].'<TD align="right">'.number_format($aut4[$t]/$td*100,1).'%</TD>';
	$dados .= '<TD align="center">'.$aut5[$t].'<TD align="right">'.number_format($aut5[$t]/$td*100,1).'%</TD>';
	$dados .= '<TD align="center">'.(round($td)).'</TD>';
	$dados .= '</TR>';
	}
?>
<font class="lt4"><?=$tit;?></font><BR>
<font class="lt3">Ano de recorte <?=$dd[1];?> até <?=$dd[2];?></font>
<BR>
    <script type="text/javascript" src="http://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() 
			{
        	var data = new google.visualization.DataTable();
        	data.addColumn('string', 'Ano');
        	data.addColumn('number', 'Autoria única');
        	data.addColumn('number', 'Dupla autoria');
        	data.addColumn('number', 'Tripla autoria');
        	data.addColumn('number', 'Quadrupla autoria');
        	data.addColumn('number', 'mais de cinco autores');
        	data.addRows(<?=(count($fid)+1);?>);
			<?
			for ($t=0;$t < count($fid);$t++)
				{
				echo "data.setValue($t, 0, '".$fid[$t]."');".chr(13);
				echo "data.setValue($t, 1, ".$aut1[$t].");".chr(13);
				echo "data.setValue($t, 2, ".$aut2[$t].");".chr(13);
				echo "data.setValue($t, 3, ".$aut3[$t].");".chr(13);
				echo "data.setValue($t, 4, ".$aut4[$t].");".chr(13);
				echo "data.setValue($t, 5, ".$aut5[$t].");".chr(13);
				}
			?>
        	var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
        	chart.draw(data, {width: 800, height: 350, title: '<?=$tit;?> (<?=$dd[1].'-'.$dd[2];?>)'});
      }
    </script>
    <div id="chart_div"></div>
<BR><BR>
<table width="98%" class="lt2" align="center">
	<TR><TD colspan="2" align="center"><?=$gr;?></TD></TR>
	<TR><TD colspan="2" align="right"><B>Total de trabalhos <?=$tot;?></B></TD></TR>
</table>

    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() 
			{
        	var data = new google.visualization.DataTable();
        	data.addColumn('string', 'Ano');
        	data.addColumn('number', 'Autoria única');
        	data.addColumn('number', 'Dupla autoria');
        	data.addColumn('number', 'Tripla autoria');
        	data.addColumn('number', 'Quadrupla autoria');
        	data.addColumn('number', 'mais de cinco autores');
        	data.addRows(<?=(count($fid)+1);?>);
			<?
			for ($t=0;$t < count($fid);$t++)
				{
				$max = round($aut1[$t]+$aut2[$t]+$aut3[$t]+$aut4[$t]+$aut5[$t]);
				if ($max == 0) { $max = 0.000001; }
				echo "data.setValue($t, 0, '".$fid[$t]."');".chr(13);
				echo "data.setValue($t, 1, ".(round(($aut1[$t]*1000)/$max)/10).");".chr(13);
				echo "data.setValue($t, 2, ".(round(($aut2[$t]*1000)/$max)/10).");".chr(13);
				echo "data.setValue($t, 3, ".(round(($aut3[$t]*1000)/$max)/10).");".chr(13);
				echo "data.setValue($t, 4, ".(round(($aut4[$t]*1000)/$max)/10).");".chr(13);
				echo "data.setValue($t, 5, ".(round(($aut5[$t]*1000)/$max)/10).");".chr(13);
				}
			?>
        	var chart = new google.visualization.LineChart(document.getElementById('chart_div2'));
        	chart.draw(data, {width: 800, height: 350, title: '<?=$tit;?> em % (<?=$dd[1].'-'.$dd[2];?>)'});
      }
    </script>
    <div id="chart_div2"></div>
	
<font class="lt4">Distribuição de artigos por número de autores e ano de publicação</font><BR>
<font class="lt3">Ano de recorte <?=$dd[1];?> até <?=$dd[2];?></font>
<BR>
<table width="98%" class="lt2" align="center">
	<TR><TD colspan="2" align="center"><?=$gr;?></TD></TR>
	<TR><TD colspan="2" align="center">
		<TABLE width="640" class="lt1" border=1 cellpadding="3" cellspacing="0"><?=$dados;?></TABLE>
		pico em <?=$max;?>
	</TD></TR>
	<TR><TD colspan="2" align="right"><B>Total de trabalhos <?=$tot;?></B></TD></TR>
</table>


<?	
}	

?>
</DIV>
