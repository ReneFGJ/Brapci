<?
$xsql = "select * from bp_section_tipo where st_ativo = 1 order by st_codigo ";
$xrlt = db_query($xsql);

while ($xline = db_read($xrlt))
	{
	$op .= '&';
	$op .= $xline['st_codigo'];
	$op .= ':';
	$op .= trim($xline['st_descricao']);
	}
// ar_section - ar_tipo

$tabela = "";
$cp = array();
array_push($cp,array('$H8','','',False,False,''));
array_push($cp,array('$S4','','Ano Inicial do Recorte',True,True,''));
array_push($cp,array('$S4','','Ano Final do Recorte',True,True,''));
array_push($cp,array('$O 2:--Todos os tipos--'.$op,'','Tipo de publicação',True,True,''));
$dd[1] = '1972';
$dd[2] = date("Y");
require($include.'sisdoc_form2.php');
require($include.'cp2_gravar.php');

$tab_max = 450;
$http_redirect = '';
$titu[0] = 'Relatórios Bibliométrico - Quantidade de Artigos / Publicação '; 
?>
<div id="main"><img src="img/bt_down_mini.png" alt="" align="right"><center><?=$titu[0];?></center>
<BR><CENTER>

<TABLE width="<?=$tab_max?>" align="center"><TR><TD><?
editar();
?></TD></TR></TABLE>

<?
if ($saved > 0)
{
$sql = "SELECT count(*) as total, ed_ano from (";
$sql .= "select ed_ano FROM brapci_article ";
$sql .= " left join brapci_edition on ar_edition = ed_codigo ";
$sql .= " left join brapci_section on ar_section = se_codigo ";
if ($dd[3] == '1') { $sql .= " where (se_tipo = 'B' and ar_status <> 'X' )"; }
if ($dd[3] == '2') { $sql .= " where (ar_status <> 'X' )"; }
if (($dd[1] != '1') and ($dd[3] != '2')) { $sql .= " where (se_tipo = '".$dd[3]."' and ar_status <> 'X' )"; }


if (strlen($dd[1]) > 0) { $sql .= " and ( ed_ano >= '".$dd[1]."') "; }
if (strlen($dd[2]) > 0) { $sql .= " and ( ed_ano <= '".$dd[2]."') "; }
$sql .= ") as tabela ";
$sql .= " group by ed_ano ";
$sql .= " order by ed_ano";
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
	array_push($fid,$line['ed_ano']);
	array_push($vlr,$line['total']);
	$tot = $tot + $line['total'];
	$sx .= '<TR '.coluna().'>';
	$sx .= '<TD>'.$line['ed_ano'].'</TD>';
	$sx .= '<TD align="right">'.$line['total'].'</TD>';
	$sx .= '</TR>';
	}
	
///////////////////////////////////////////////////// GRAFICO
$dados = '';
$corte = 99;
for ($t=0;$t < count($vlr);$t++)
	{
	if ($corte > 9)
		{ $dados .= '<TR>'; $corte = 0; }
	$corte++;
	$dados .= '<TD><B>'.$fid[$t].' <TD>'.$vlr[$t].'</TD>';
	}

echo '<BR>';

$eixo_y_legend = 'quantidade de trabalhos';
?>
<font class="lt4">Quantidade de artigos publicados / ano</font><BR>
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
        	data.addColumn('number', 'Artigos');
        	data.addRows(<?=(count($vlr)+1);?>);
			<?
			for ($t=0;$t < count($vlr);$t++)
				{
				echo "data.setValue($t, 0, '".$fid[$t]."');".chr(13);
				echo "data.setValue($t, 1, ".$vlr[$t].");".chr(13);
				}
			?>
        	var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
        	chart.draw(data, {width: 800, height: 350, title: '<?=$titu[0];?> (<?=$dd[1].'-'.$dd[2];?>)'});
      }
    </script>
    <div id="chart_div"></div>

<?	

$dados = '';
$corte = 99;
for ($t=0;$t < count($vlr);$t++)
	{
	if ($corte > 9)
		{ $dados .= '<TR>'; $corte = 0; }
	$corte++;
	$dados .= '<TD><B>'.$fid[$t].' <TD>'.$vlr[$t].'</TD>';
	}

?>
<table width="98%" class="lt2" align="center">
	<TR><TH>Publicação</TH><TH>Artigos</TH></TR>
	<TR><TD colspan="2" align="center"><?=$gr;?></TD></TR>
	<TR><TD colspan="2" align="center">
		<TABLE class="lt1"><?=$dados;?></TABLE>
		pico em <?=$max;?>
	</TD></TR>
	<TR><TD colspan="2" align="right"><B>Total de trabalhos <?=$tot;?></B></TD></TR>
		<TR><TD colspac="2" class="lt1">METODOLOGIA: Para formação dos dados deste recorte são considerados trabalhos publicados nas seções: 
	<?
	if ($dd[3]=='1') { $dd[3] = 'B'; }
	if ($dd[3]=='2') { 
		$sql = "select * from brapci_section where se_tipo <> '-' ";
		} else {
		$sql = "select * from brapci_section where se_tipo = '".$dd[3]."' ";
		}
	$rlt = db_query($sql);
	$ini = 0;
	while ($line = db_read($rlt))
		{
		if ($ini > 0) { echo ', '; }
		$ini++;
		echo '<B>'.trim($line['se_descricao']).'</B>';
		}
	?>. A delimitação temporal do corpus é de trabalhos publicados entre <?=$dd[1];?> e <?=$dd[2];?>.
	</TD></TR>
</table>
</DIV>
<?
}
?>
