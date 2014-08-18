<?
class grafico
	{
	
	function pie($data)
		{
		global $graph;
		if (empty($graph)) { $graph = 0; }
		$graph++;
			
		$caps = '';
		for ($r=0; $r < count($data);$r++)
			{
				if (strlen($caps) > 0) { $caps .= '|'; }
				$caps .= $data[$r][0];
			}
		if ($graph == 1)
		{ $sx .= '<script language="javascript" src="http://www.google.com/jsapi"></script>'; }
		$sx .= '
   		<div id="chart'.$graph.'"></div>
		
		   <script type="text/javascript">
      		var queryString = \'\';
      		var dataUrl = \'\';
		
      		function onLoadCallback() {
        		if (dataUrl.length > 0) {
          		var query = new google.visualization.Query(dataUrl);
          		query.setQuery(queryString);
          		query.send(handleQueryResponse);
        		} else {
          		var dataTable = new google.visualization.DataTable();
          		dataTable.addRows('.count($data).');
		
          		dataTable.addColumn(\'number\');';
				for ($r=0;$r<count($data);$r++)
					{
						$sx .= 'dataTable.setValue('.$r.', 0, '.$data[$r][1].'.00);'.chr(13);
					}
				$sx .= '	
          		draw'.$graph.'(dataTable);
        		}
      		}

		      function draw'.$graph.'(dataTable) {
		        var vis = new google.visualization.ImageChart(document.getElementById(\'chart'.$graph.'\'));
		        var options = {
          		chs: \'300x150\',
          		cht: \'p3\',
          		chco: \'76A4FB|3366CC\',
          		chd: \'s:ZR\',
          		chdl: \''.$caps.'\',
          		chl: \'|\'
        		};
        		vis.draw(dataTable, options);
      		}

		      function handleQueryResponse(response) {
		        if (response.isError()) {
          		alert(\'Error in query: \' + response.getMessage() + \' \' + response.getDetailedMessage());
          		return;
        		}
        		draw'.$graph.'(response.getDataTable());
      		}

		    google.load("visualization", "1", {packages:["imagechart"]});
      		google.setOnLoadCallback(onLoadCallback);

	    </script>';
		return($sx);
		}
		
	}
?>
