<?php
class metodologias extends CI_model
	{
	function mostra($art)
		{
			$sql = "select * from brapci_metodologias_artigos
					INNER JOIN brapci_metodologias on id_bmt = bma_metodologia
					LEFT JOIN brapci_metodologias_tp ON bmtf_codigo = bmt_tipo
					WHERE bma_artigo = '$art'
					ORDER BY id_bmtf, bmt_ordem ";

			$rlt = $this->db->query($sql);
			$rlt = $rlt->result_array();
			
			$sx = '<hr><table width="100%">';
			$xtipo = '';
			for ($r=0;$r < count($rlt);$r++)
				{
					$line = $rlt[$r];
					$tipo = $line['bmtf_codigo'];
					if ($tipo != $xtipo)
						{
							$sx .= '<tr>';
							$sx .= '<td colspan=5 class="lt2">';
							$sx .= '<b>'.$line['bmtf_descricao'].'</b>';
							$sx .= '</td>';					
							$xtipo = $tipo;		
						}
					$sx .= '<tr>';
					$sx .= '<td class="lt1">';
					$sx .= $line['bmt_descricao'];
					$sx .= '</td>';	
									
					$sx .= '<td class="lt1">';
					$sx .= stodbr($line['bma_update']);
					$sx .= '</td>';		
					
					$sx .= '</tr>';			
				}
			$sx .= '</table>';
			return($sx);
		}
		
	function acao($art='',$acao='',$met='')
		{
			switch ($acao)
				{
				case 'ADD':
					
					$this->insere_metodologia($art,$met);
					break;
				}
			$data = array();
			//$data['content'] = $this->mostra($art);
			//$this->load->view('content',$data);
			
		}
		
	function insere_metodologia($art,$met)
		{
			$sql = "select * from brapci_metodologias_artigos 
					WHERE 	bma_artigo = '$art' and bma_metodologia = '$met' ";
			$rlt = $this->db->query($sql);
			$rlt = $rlt->result_array();
			if (count($rlt) > 0)
				{
					
				} else {
					$data = date("Y-m-d");
					$sql = "insert into brapci_metodologias_artigos
							(bma_artigo, bma_metodologia, bma_update)
								values
							('$art',$met,'$data')";
					$rlt = $this->db->query($sql);
				}
			return(1);	
		}
	function metodos_incluir($art)
		{
			$sql = "select * from brapci_metodologias 
						LEFT JOIN brapci_metodologias_tp ON bmtf_codigo = bmt_tipo
						where bmt_ativo = 1
						ORDER BY bmt_tipo, bmt_ordem ";
			$rlt = $this->db->query($sql);
			$rlt = $rlt->result_array();
			
			$sx = '<select name="met" id="met" size=1>';
			$sx .= '<option value="">::Seleciona::</option>';
			$xtp = '';
			for ($r=0;$r < count($rlt);$r++)
				{
					$line = $rlt[$r];
					$tp = $line['bmtf_descricao'];
					if ($tp != $xtp)
						{
							$xtp = $tp;
							$sx .= '<option value="'.$line['id_bmt'].'" class="lt3" readonly>'.$line['bmtf_descricao'].'</option>'.cr(); 	
						}
					$sx .= '<option value="'.$line['id_bmt'].'">'.$line['bmt_descricao'].'</option>'.cr();
				}
			$sx .= '</select>';
			$sx .= '<input type="button" id="add" value="Adicionar >>">';
			
			$sx .= '
			<script>
				$("#add").click(function() {
					ok = 1;
					ms = $("#met").val();
					 
					$.ajax({
						type : "POST",
						url : "'.base_url('index.php/admin/metodo/ADD/'.$art.'/').'",
						data : {
							dd1 : ms,
							dd2 : ok
						}
					}).done(function(data) {
						$("#metodos").html(data);
					});
				});
			</script>
			';
			
			return($sx); 
		}	
	}
?>
