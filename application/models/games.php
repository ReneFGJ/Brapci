<?php 
class games extends CI_model
	{
	function game_i($id)
		{
			$idk = get("dd0");
			$acao = get("acao");
			
			if ((strlen($acao) > 0) and (strlen($idk) > 0))
				{
					if ($acao == 'SIM')
						{
							$sql = "update brapci_keyword set kw_folk_idioma_yes = kw_folk_idioma_yes + 1 where id_kw = ".$idk;
						} else {
							$sql = "update brapci_keyword set kw_folk_idioma_no = kw_folk_idioma_no + 1 where id_kw = ".$idk;
						}
						$rlt = $this->db->query($sql);
				}
			
			$sql = "SELECT id_kw, kw_word, 
							(kw_folk_idioma_yes + kw_folk_idioma_no) as total,
							kw_idioma 
						FROM brapci_keyword WHERE kw_idioma = 'pt_BR' order by total limit 1";
			$rlt = $this->db->query($sql);
			$rlt = $rlt->result_array();
			$line = $rlt[0];
			$sx = '';
			
			$sx .= '<form method="post">';
			$sx .= '<input type="hidden" name="dd0" value="'.$line['id_kw'].'">';
			$sx .= '<center>';
			$sx .= '<table width="400" align="center">';
			$sx .= '<tr><td align="center" class="lt8 border1" style="padding: 20px;" colspan=2>'.$line['kw_word'].'</td></tr>';
			
			$sx .= '<tr><td><br><br>';
			$sx .= '<tr><td align="center" colspan=2>'.msg($line['kw_idioma']).'</td></tr>';
			$sx .= '<tr><td><br><br>';
			
			$sx .= '<tr align="center">';
			$sx .= '<td align="center">';
			$sx .= '<input type="submit" value="SIM" name="acao" class="botao3d">';
			$sx .= '</td>';

			$sx .= '<td align="center">';
			$sx .= '<input type="submit" value="NÃO" name="acao" class="botao3d">';
			$sx .= '</td>';

			$sx .= '</table>';
			$sx .= '</form>';
			return($sx);
		}	
	}
?>
