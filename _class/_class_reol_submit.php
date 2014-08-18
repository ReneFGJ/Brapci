<?php
class submit
	{
	var $protocolo;
	
	function status()
		{
			$sta = array(
				'@'=>'in_submission',
				'A'=>'submited',
				'B'=>'???'
			);
			return($sta);
		}
	
	function resumo_submissoes()
		{
			global $jid;
			$sql = "select count(*) as total, doc_status from reol_submit 
				where journal_id = $jid
				group by doc_status
			";
			$rlt = db_query($sql);
			
			$ops = array(0,0,0,0,0,0,0,0);
			
			while ($line = db_read($rlt))
				{
					$sta = trim($line['doc_status']);
					$total = $line['total'];
					
					if ($sta == 'X') { $ops[9] = $ops[9] + $total; }
					if ($sta == '@') { $ops[0] = $ops[0] + $total; }
					if ($sta == 'A') { $ops[1] = $ops[1] + $total; }
					if ($sta == 'N') { $ops[2] = $ops[2] + $total; }
					if ($sta == 'R') { $ops[3] = $ops[3] + $total; }
					if ($sta == 'O') { $ops[4] = $ops[4] + $total; }
					if ($sta == 'Y') { $ops[5] = $ops[5] + $total; }
					if ($sta == 'M') { $ops[6] = $ops[6] + $total; }
				}
			$sa = ''; $sb = '';
			
			$sx .= '<table width="740" class="tebela_0">';
			$sx .= '<TR align="center">';
			
			$sa .= '<TD>'.msg("sta_@");
			$sb .= '<TD>'.$ops[0];
			
			$sa .= '<TD>'.msg("sta_A");
			$sb .= '<TD>'.$ops[1];

			$sa .= '<TD>'.msg("sta_N");
			$sb .= '<TD>'.$ops[2];
			
			$sa .= '<TD>'.msg("sta_R");
			$sb .= '<TD>'.$ops[3];
		
			$sa .= '<TD>'.msg("sta_O");
			$sb .= '<TD>'.$ops[4];
			
			$sa .= '<TD>'.msg("sta_Y");
			$sb .= '<TD>'.$ops[5];
			
			$sa .= '<TD>'.msg("sta_M");
			$sb .= '<TD>'.$ops[5];

			$sa .= '<TD>'.msg("sta_X");
			$sb .= '<TD>'.$ops[9];
												
			$sx .= '<TR align="center">'.$sa;
			$sx .= '<TR align="center">'.$sb;
			$sx .= '</table>';
			return($sx);
		}
	
	function autores_form($protocolo)
		{
			$sx = '<div id="autores_form">
					<table width="100%" cellppaing=2 cellspacing=0>
					<TR><TD width="90%">'.msg('author_name').'
						<TD width="10%">'.msg('formation').'
					<TR><TD><input name="dd20" type="text" size=50 maxlength=200 style="width: 99%">
						<TD><select name="dd21">
							<option value=""></option>
							<option value="PosDc">Pos-Doc</option>
							<option value="Dr">Dr.</option>
							<option value=""Dra>Dra.</option>
							<option value="Msc">Msc</option>
							<option value="Esp.">Esp.</option>
							<option value="Grad">Grad.</option>
							<option value="na">'.msg('not_applied').'</option>
							</select>							
					<TR><TD colspan=2>'.msg('instituition').'
					<TR><TD><input name="dd22" type="text" size=50 maxlength=200 style="width: 99%">
					<TR><TD colspan=2>'.msg('lattes').'
					<TR><TD><input name="dd23" type="text" size=50 maxlength=200 style="width: 99%">
					<TR><TD><input name="acao" type="botton" value="'.msg('submit').'">		
					</table>
				   </div>
				   <script>
				   		
				   </script>
			';
		}
		
	function acao_001()
		{
			
		}
	function acao_002()
		{
			
		}
	function acao_003()
		{
			
		}
	}
?>
