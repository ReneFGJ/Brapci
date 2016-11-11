<?php
$dt = '';

/* MAX */
$dt = '';
$n = 0;
$cat = '';
$leg = array('única', 'dupla', 'tripla', 'quadrupla', 'cinco ou mais');

for ($r = 0; $r < count($leg); $r++) {
	if ($r > 0) { $dt .= ', ';
	}
	$dt .= '{ name: \'' . $leg[$r] . '\', ';
	$dt .= ' data: [ ';
	$n = 0;
	foreach ($dados as $key => $value) {
		if ($n > 0) { $dt .= ', ';
		}
		$n++;
		/* max */
		$max = 1;
		for ($t = 0; $t < count($leg); $t++) {
			if (isset($value[$t])) {
				$n = $value[$t];
				if ($n > $max) { $max = $n;
				}
			}
		}

		if (isset($value[$r])) {
			$dt .= number_format($value[$r] / $max * 100, 1, '.', '');
		} else {
			$dt .= number_format(0, 1, '.', '');
		}
	}
	$dt .= ']} ' . cr();
}
?>
<div id="<?php echo $div_name; ?>" style="min-width: 300px; height: 400px; margin: 0 auto"></div>
<script>
		$(function () {
	Highcharts.chart('<?php echo $div_name; ?>
		', {
		title: {
		text: 'Monthly Average Temperature',
		x: -20 //center
		},
		subtitle: {
		text: 'Source: WorldClimate.com',
		x: -20
		},
		xAxis: {
		categories: [
	<?php echo $cat; ?>]
	},
	yAxis: {
	title: {
	text: 'Temperature (°C)'
	},
	plotLines: [{
	value: 0,
	width: 1,
	color: '#808080'
	}]
	},
	tooltip: {
	valueSuffix: '°C'
	},
	legend: {
	layout: 'vertical',
	align: 'right',
	verticalAlign: 'middle',
	borderWidth: 0
	},

	series: [
	<?php echo $dt; ?>
		]
		});
		});
</script>