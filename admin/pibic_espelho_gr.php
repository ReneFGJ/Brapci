<?
/*** Modelo ****/
require("cab.php");
ini_set('max_execution_time','2360');
global $acao,$dd,$cp,$tabela;
require($include.'sisdoc_colunas.php');
require($include.'sisdoc_form2.php');
require($include.'sisdoc_debug.php');

	/* Dados da Classe */
	require('../_class/_class_pibic_mirror.php');

	$clx = new mirror;
	$cp = $clx->cp();

	$clx->espelho_geral_ano();
	
?> 
<html>
  <head>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load('visualization', '1', {'packages':['motionchart']});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Modalidade');
        data.addColumn('date', 'Date');
        data.addColumn('number', 'Modalidades');
        data.addColumn('number', 'Orientadores');
        data.addColumn('string', 'Centro');
        data.addRows([
				<?=$clx->data_gr;?>
          ]);
        var chart = new google.visualization.MotionChart(document.getElementById('chart_div'));
        chart.draw(data, {width: 800, height:500});
      }
    </script>
  </head>

  <body>
    <div id="chart_div" style="width: 800px; height: 500px;"></div>
  </body>
  
<?
require("../foot.php");
?>
</html>