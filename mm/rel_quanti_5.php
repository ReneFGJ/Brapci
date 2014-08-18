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

require($include.'sisdoc_form2.php');
require($include.'cp2_gravar.php');
require($include.'google_chart.php');
$tab_max = 450;
$http_edit = 'rel_quanti_5.php';
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
$dmax = $dd[2] - $dd[1];
if ($dmax > 40)
	{
	echo 'Diferença entre anos é superior a 40 anos. ';
	exit;
	}
$ano1 = intval($dd[1]);
$ano2 = intval($dd[2]);

$sql = "";
$sql = "select count(*) as total, ed_ano from ( ";
$sql .= "SELECT `ed_journal_id`,`ed_ano` FROM `brapci_edition`  ";
$sql .= "where ed_ano >= ".$ano1." and ed_ano <= ".$ano2." ";
//$sql .= " and ed_ativo = 1  ";
$sql .= "group by ed_ano, ed_journal_id";
$sql .= ") as tabela  ";
$sql .= "group by ed_ano  ";
$sql .= "order by ed_ano ";
$rlt = db_query($sql);

$vlr = array();
$fid  = array();
for ($r=$ano1; $r <= $ano2; $r++)
	{
	array_push($vlr,0);
	array_push($fid,$r);
	}

$max = 1;
$pmax = 65;
while ($line = db_read($rlt))
	{
	$total = $line['total'];
	if ($total > $max) { $max = $total; }
	$tt = ($line['ed_ano']-$dd[1]);	
	$vlr[$tt] = $total;
	}
	

$t1 = '';
$t2 = '';
$t3 = '';
$t4 = '';
$t5 = '';
$dados = '<TR><TH>ano</TH><TH colspan="2">publicações</TH></TR>';
for ($t=0;$t < count($fid);$t++)
	{
	$i1 = round(intval('0'.$vlr[$t])/$max*65);
	$g1 .= $grvlr[$i1];
	$td = $vlr[$t];
	if ($td == 0) { $td = 0.0000001; }
	$dados .= '<TR >';
	$dados .= '<TD align="center">&nbsp;&nbsp;'.$fid[$t].'&nbsp;&nbsp;</TD>';
	$dados .= '<TD align="center">'.$vlr[$t].'</TD>';
	$dados .= '</TR>';
	$tot = $tot + $vlr[$t];
	}


//////////////////////////// Grafico Novo
?>
    <script type="text/javascript" src="http://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Ano');
        data.addColumn('number', 'Revistas');
        data.addRows(<?=count($fid);?>);
		<? for ($t=0;$t < count($fid);$t++)
		{
		?>
		data.setValue(<?=$t;?>, 0, '<?=$fid[$t];?>');
		data.setValue(<?=$t;?>, 1, <?=$vlr[$t];?>);
		<?
		}
		?>
        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
        chart.draw(data, {width: 800, height: 350, title: 'Revistas - Ano'});
      }
    </script>
  </head>

  <body>
    <div id="chart_div"></div>
  </body>

<font class="lt4">Número de Revistas que publicaram edições no ano</font><BR>
<font class="lt3">Ano de recorte <?=$dd[1];?> até <?=$dd[2];?></font>
<BR>
<table width="98%" class="lt2" align="center">
	<TR><TD colspan="2" align="center">
		<TABLE width="640" class="lt1" border=1 cellpadding="3" cellspacing="0"><?=$dados;?></TABLE>
		pico em <?=$max;?>
	</TD></TR>
	<TR><TD colspan="2" align="right"><B>Total de edições publicadoras <?=$tot;?></B></TD></TR>
</table>
<?	
/////////////////////////////////////////////////////////////////////////////// Lista de Publicações
$sql = "";
$sql = "select jnl_nome,sum(ed) as total, ed_ano from ( ";
$sql .= "SELECT count(*) as ed, `ed_journal_id`,`ed_ano` FROM `brapci_edition`  ";
$sql .= "where ed_ano >= ".$ano1." and ed_ano <= ".$ano2." ";
//$sql .= " and ed_ativo = 1  ";
$sql .= "group by ed_ano, ed_journal_id";
$sql .= ") as tabela  ";
$sql .= " inner join brapci_journal on ed_journal_id = jnl_codigo ";
$sql .= "group by ed_ano, jnl_nome  ";
$sql .= "order by ed_ano, jnl_nome ";
$rlt = db_query($sql);
$sx = '';
$xano = '0';
$tot = 0;
$toe = 0;
while ($line = db_read($rlt))
	{
	$ano = $line['ed_ano'];
	if ($ano != $xano)
		{
		if ($tot > 0)
			{ $sx .= '<TR><TD colspan="4" align="right"><I>Subtotal do ano '.$tot.' / '.$toe.' fasciculos</TD></TR>'; }
		$sx .= '<TR><TD colspan="4"><B>Ano: '.$ano.'</B></TD></TR>';
		$xano = $ano;
		$tot = 0;
		$toe = 0;
		}
	$tot = $tot + 1;
	$toe = $toe + $line['total'];
	$sx .= '<TR>';
	$sx .= '<TD>';
	$sx .= $line['jnl_nome'];
	$sx .= '<TD align="right">';
	$sx .= $line['total'];
	$sx .= '</TR>';
	}
	if ($tot > 0)
		{ $sx .= '<TR><TD colspan="4" align="right"><I>Subtotal do ano '.$tot.' / '.$toe.' fasciculos</TD></TR>'; }
}	
?>
<table>
<TR><TD></TD></TR>
<?=$sx;?>
</table>
</DIV>
