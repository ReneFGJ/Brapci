<?php
class archives extends CI_model {
	
	function save_LINK($ar,$jid,$link)
		{
			$data = date("Ymd");
			$ar = strzero($ar,10);
			
			$sql = "insert into brapci_article_suporte 
					(bs_article, bs_type, bs_adress,
					bs_status, bs_journal_id, bs_update) 
					value 
					('$ar','URL','$link',
					'A','$jid','$data')
			";
			$this->db->query($sql);
			return(1);
		}

	function new_file($id_ar) {
		$sx =  '<div id="archives_bt" class="lt4" style="cursor: pointer;">+</div>
		
		<div id="archives" style="display: none;">';

		/* Open form */
		$sx .= form_open('admin/article_view/' . $id_ar . '/' . checkpost_link($id_ar));

		/* Hidden */
		$data = array('dd8' => 'ARCHIVE');
		$sx .= form_hidden($data);

		/* Fieldset */
		$sx .= form_label('link') . '<BR>';
		$fld = Array("name" => "dd9", 'value' => '', 'class' => 'fullscreen');
		$sx .= form_input($fld);
		$sx .= form_submit('acao', 'save >>');
		
		$sx .= form_close();
		$sx .= '</div>';
		
		$sx .= '
		<script>
		$("#archives_bt").click(function() {
			$("#archives").toggle();
			
		});
		</script>
		';
		
		return($sx);
	}

	function show_files($id) {
		$id = strzero($id, 10);
		$sql = "select * from brapci_article_suporte where bs_article = '$id' order by bs_type";
		$rlt = db_query($sql);
		$pdf = 0;
		$sx = '<table width="100%" class="tabela00">';
		$sx .= '<tr><th>archive</th></tr>';
		$idbs = 0;
		while ($line = db_read($rlt)) {
			$xlink = trim($line['bs_adress']);
			$tipo = trim($line['bs_type']);
			$link = '';
			$linkf = '';
			$ajax = '';
			if (substr($xlink, 0, 5) == '_repo') {
				$link = '<a href="' . base_url(trim($line['bs_adress'])) . '" target="_new">';
				$linkf = '</a>';
				$idbs = -1;
			}
			if (substr($xlink, 0, 4) == 'http') {
				$link = '<a href="' . trim($line['bs_adress']) . '" target="_new">';
				$linkf = '</a>';
				if ((trim($line['bs_status']) == '@') or (trim($line['bs_status']) == 'A')) {
					$onclick = ' onclick="coletar('.$line['id_bs'].',\'coletar'.$line['id_bs'].'\');" ';
					$ajax = '<div id="coletar'.$line['id_bs'].'" style="color: blue; cursor: pointer; width: 100px; border: 1px #A0A0A0 solid; text-align: center;" '.$onclick.'>coletar</div>';
					$idbs = $line['id_bs'];
				}
			}

			$sx .= '<tr>';
			$sx .= '<td>';
			$sx .= $link . trim($line['bs_adress']) . $linkf;
			$sx .= '</td>';
			$sx .= '<td>';
			$sx .= trim($line['id_bs']);
			$sx .= '</td>';
			$sx .= '<td>';
			$sx .= trim($line['bs_status']);
			$sx .= '</td>';
			$sx .= '<td>' . $ajax . '</td>';
			$sx .= '</tr>';
		}
		$sx .= '</table>';

		if ($idbs > 0) {
			$sx .= '
			<script>
			function coletar($id,$div) {
				$("#"+$div).html("coletando..."); 
				$.ajax({
  					method: "POST",
  					url: "' . base_url('oai/coletar_pdf'). '/" + $id,
  					data: { name: "OAI", location: "PDF" }
					})
  					.done(function( data ) {
    						$("#"+$div).html(data);
  					});
			};
			</script>
			';
		}
		return ($sx);
	}

}
?>
