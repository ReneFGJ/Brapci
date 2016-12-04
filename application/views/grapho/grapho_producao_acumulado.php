<?php
$dt = '';
foreach ($dados as $key => $value) {
	if (strlen($dt) > 0)
		{
			$dt .= ', ';
		}
	$dt .= "['$key', $value]";
}
?>
<div id="<?php echo $div_name;?>" style="min-width: 300px; height: 400px; margin: 0 auto"></div>
<script>
	$(function() {
		$('#<?php echo $div_name;?>').highcharts({
			chart : {
				type : 'line'
			},
			title : {
				text : 'Produção acumulada'
			},
			subtitle : {
				text : 'Source: Brapci (<?php echo date("Y");?>)'
			},
			xAxis : {
				type : 'category',
				labels : {
					rotation : -45,
					style : {
						fontSize : '10px',
						fontFamily : 'Arial, Verdana, sans-serif'
					}
				}
			},
			yAxis : {
				min : 0,
				title : {
					text : 'Trabalhos Publicados'
				}
			},
			legend : {
				enabled : false
			},
			tooltip : {
				pointFormat : '<b>{point.y:.0f} trabalhos</b>'
			},
			series : [{
				name : 'Trabalhos',
				data : [<?php echo $dt;?>],
				dataLabels : {
					enabled : true,
					rotation : 0,
					color : '#000000',
					align : 'center',
					format : '{point.y:.0f}', // one decimal
					y : -10, // 10 pixels down from the top
					style : {
						fontSize : '11px',
						fontFamily : 'Arial, Verdana, sans-serif'
					}
				}
			}]
		});
	}); 
</script>