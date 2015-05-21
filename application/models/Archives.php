<?php
class archives extends CI_model
	{
	function show_files($id)
		{
			$id = strzero($id,10);
			$sql = "select * from brapci_article_suporte where bs_article = '$id' order by bs_type";
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
				if (substr($xlink,0,5) == '_repo')
					{
						$link = '<a href="'.base_url(trim($line['bs_adress'])).'" target="_new">';
						$linkf = '</a>';
						$idbs = -1;
					}				
				if (substr($xlink,0,5) == 'http:')
					{
						$link = '<a href="'.trim($line['bs_adress']).'" target="_new">';
						$linkf = '</a>';
						if (((trim($line['bs_status']) == '@') or (trim($line['bs_status']) == 'A')) and ($idbs == 0))
							{
							$ajax = '<div id="coletar" style="color: blue; cursor: pointer; width: 100px; border: 1px #A0A0A0 solid; text-align: center;">coletar</div>';
							$idbs = $line['id_bs'];
							}
					}
				if (substr($xlink,0,6) == 'https:')
					{
						$link = '<a href="'.trim($line['bs_adress']).'" target="_new">';
						$linkf = '</a>';
						if (((trim($line['bs_status']) == '@') or (trim($line['bs_status']) == 'A')) and ($idbs == 0))
							{
							$ajax = '<div id="coletar" style="color: blue; cursor: pointer; width: 100px; border: 1px #A0A0A0 solid; text-align: center;">coletar</div>';
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
			$("#coletar").click(function() {
				$("#coletar").html("coletando..."); 
				$.ajax({
  					method: "POST",
  					url: "'.base_url('oai/coletar_pdf/'.$idbs).'",
  					data: { name: "John", location: "Boston" }
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
?>
