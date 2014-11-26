<?php
require ("cab.php");

require ("../_class/_class_bris.php");
$bris = new bris;

$ano = $dd[0];
echo '<H1>Indice de Concentração de Produção por Autor (iCPA)</h1>';
echo '<h3>Ano Base: ' . $ano . '</h3>';

$sd = $bris -> indicador_pa($ano);
$sx = '
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
	google.load("visualization", "1", {
		packages : ["corechart"]
	});
	google.setOnLoadCallback(drawChart);
	function drawChart() {

		var data = google.visualization.arrayToDataTable([[\'Ano\', \'Artigos\'], '.$bris->google_data.']);

		var options = {
			title : \'Produção Anual\',
			hAxis : {
				title : \'Ano\',
				titleTextStyle : {
					color : \'red\'
				}
			}
		};

		var chart = new google.visualization.AreaChart(document.getElementById(\'chart_div\'));
        chart.draw(data, options);

	}
</script>
<div id="chart_div" style="width: 900px; height: 500px;"></div>
';
echo $sx;
echo $sd;
echo $bris->indicador_pa_journal();
?>
