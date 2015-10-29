<?php
class cited extends CI_Model
	{
		function show_cited($id = 0)
		{
			$id = strzero($id,10);
			$sql = "select * from brapci_article_suporte where bs_article = '$id'
							and bs_adress like 'http%' 
							order by bs_type";
			$rlt = db_query($sql);
			$pdf = 0;
			$sx = '<table width="100%" class="tabela00">';
			$sx .= '<tr><th>archive</th></tr>';
			$idbs = 0;
			while ($line = db_read($rlt))
			{
				$xlink = trim($line['bs_adress']);
				$tipo = trim($line['bs_type']);
				$link = '';
				$linkf = '';
				$ajax = '';
		
				if (substr($xlink,0,4) == 'http')
					{
						$link = '<a href="'.trim($line['bs_adress']).'" target="_new">';
						$linkf = '</a>';
						if (((trim($line['bs_status']) == '@') or (trim($line['bs_status']) == 'B') or (trim($line['bs_status']) == 'A')) and ($idbs == 0))
							{
							$ajax = '<div id="coletar_cited" style="color: blue; cursor: pointer; width: 100px; border: 1px #A0A0A0 solid; text-align: center;">coletar '.$line['id_bs'].'</div>';
							$idbs = $line['id_bs'];
							}
					}
				
				$sx .= '<tr>';
				$sx .= '<td>';
				$sx .= $link.trim($line['bs_adress']).$linkf;
				$sx .= '</td>';
				$sx .= '<td>';
				$sx .= trim($line['id_bs']);
				$sx .= '</td>';
				$sx .= '<td>';
				$sx .= trim($line['bs_status']);
				$sx .= '</td>';
				$sx .= '<td>'.$ajax.'</td>';
				$sx .= '</tr>';
			}
			$sx .= '</table>';
			
			if ($idbs > 0)
			{
			$sx .= '
			<script>
			$("#coletar_cited").click(function() {
				$("#coletar_cited").html("coletando..."); 
				$.ajax({
  					method: "POST",
  					url: "'.base_url('index.php/oai/coletar_cited/'.$idbs).'",
  					data: { name: "OAI", location: "PDF" }
					})
  					.done(function( data ) {
    						$("#coletar").html(data);
  					});
			});
			</script>
			';
			}
			return($sx);			
		}
	}
