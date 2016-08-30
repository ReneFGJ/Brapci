<?php
class tools extends CI_model
	{
		var $file_name = '';
		function file_update()
			{
				$sx = $this->load->view('tools/file_update',null,true);
				if (isset($_FILES['userfile']['tmp_name']))
				{
					$file_name = $_FILES['userfile']['tmp_name'];
					if (file_exists(($file_name)))
					{
						$this->file_name = $file_name;
					}
				}
				return($sx);
			}
		function readfile($file='')
			{
				$tx = '';
				
				if ($file == '') { $file = $this->file_name; }
				if (is_file($file))
				{
					$fl = fopen($file,'r+');
					
					while (!feof($fl))
						{
							$tx .= fread($fl,1024);
						}
					fclose($fl);
				}
				return($tx);
			}
		function tratar_xls($txt)
			{
				$txt = troca($txt,',"',';"');
				return($txt);
			}
		function gerar_nomes_xls($txt)
			{
				$txt = troca($txt,chr(13),';');
				$txt = troca($txt,chr(10),';');
				$lns = splitx(';',$txt);
				$ns = array();
				$nf = array();
				for ($r=0;$r < count($lns);$r++)
					{
						$mn = $lns[$r];
						if (isset($ns[$mn]))
							{
								$ns[$mn] = $ns[$mn] + 1;
							} else {
								$ns[$mn] = 1;
								array_push($nf,$mn);
							}
						
					}
				sort($nf);
				$sx  = '';
				$i = 0;
				foreach ($nf as $key => $val) {
					$i++;
				    $sx .= strzero($i,4).'. '.$val. chr(13).chr(10);
				}
				return($sx);
			}
		function gerar_matriz_xls($txt)
			{
				$txt = troca($txt,';','¢');
				$txt = troca($txt,chr(13),'');
				$txt = troca($txt,chr(10),';');
				$lns = splitx(';',$txt);
				$nx = array();
				$ns = array();
				$nf = array();
				for ($r=0;$r < count($lns);$r++)
					{
						$mn = $lns[$r];
						$mn = troca($mn,'¢',';');
						$au = splitx(';',$mn.';');
						
						for ($a=0;$a < count($au);$a++)
							{
								$mm = $au[$a];
								if (isset($ns[$mm]))
									{
										$ns[$mm] = $ns[$mm] + 1;
									} else {
										$ns[$mm] = 1;
										array_push($nf,$mm);
									}
								/* monta matriz */
								if ($a == 0)
									{
										/**************** Primeiro Autor **********************/
										if (isset($nx[$au[0]][$au[0]]))
											{
												$nx[$au[0]][$au[0]] = $nx[$au[0]][$au[0]] + 1;
											} else {
												$nx[$au[0]][$au[0]] = 1;
											}
									} else {
										/*************** Outros autores ***********************/
										for ($b=0;$b < $a;$b++)
										{
										$ma = $au[$b];
										if (isset($nx[$ma][$mm]))
											{
												$nx[$ma][$mm] = $nx[$ma][$mm] + 1;
												$nx[$mm][$ma] = $nx[$mm][$ma] + 1;
											} else {
												$nx[$ma][$mm] = 1;
												$nx[$mm][$ma] = 1;
											}
										}															
									}								
							}
						
					}
				sort($nf);
				/*  matriz */
				$sx = '<table border=1>';
				$sx .= '<tr><td></td>';
				foreach ($nf as $key => $val1) {
					$sx .= '<td>'.$val1.'</td>';
				}
				$sx .= '</tr>'.cr();
				
				foreach ($nf as $key => $val1) {
					$sx .= '<tr>';
					$sx .= '<td>'.$val1.'</td>';
					foreach ($nf as $key2 => $val2 ) {
						if (isset($nx[$val1][$val2]))
						{
				    		$sx .= '<td align="center">'.$nx[$val1][$val2].'</td>';
						} else {
							$sx .= '<td align="center">0</td>';
						}
					}
				}
				$sx  .= '</table>';
				return($sx);
			}

		function gerar_matriz_pajek($txt)
			{
				$txt = troca($txt,';','¢');
				$txt = troca($txt,chr(13),'');
				$txt = troca($txt,chr(10),';');
				$lns = splitx(';',$txt);
				$nx = array();
				$ns = array();
				$nf = array();
				$nz = array();
				for ($r=0;$r < count($lns);$r++)
					{
						$mn = $lns[$r];
						$mn = troca($mn,'¢',';');
						$au = splitx(';',$mn.';');
						
						for ($a=0;$a < count($au);$a++)
							{
								$mm = $au[$a];
								if (isset($ns[$mm]))
									{
										$ns[$mm] = $ns[$mm] + 1;
									} else {
										$ns[$mm] = 1;
										array_push($nf,$mm);
									}
								/* monta matriz */
								if ($a == 0)
									{
										/**************** Primeiro Autor **********************/
										if (isset($nx[$au[0]][$au[0]]))
											{
												$nx[$au[0]][$au[0]] = $nx[$au[0]][$au[0]] + 1;
											} else {
												$nx[$au[0]][$au[0]] = 1;
											}
									} else {
										/*************** Outros autores ***********************/
										for ($b=0;$b < $a;$b++)
										{
										$ma = $au[$b];
										if (isset($nx[$ma][$mm]))
											{
												$nx[$ma][$mm] = $nx[$ma][$mm] + 1;
												$nx[$mm][$ma] = $nx[$mm][$ma] + 1;
											} else {
												$nx[$ma][$mm] = 1;
												$nx[$mm][$ma] = 1;
											}
										}															
									}								
							}
						
					}
				sort($nf);
				/*  matriz */
				$sx = '*Vertices '.count($nf).cr();
				foreach ($nf as $key => $val1) {
					$n1 = number_format($ns[$val1]/10,4);
					$sx .= ($key+1).' "'.$val1.'" '.$n1.' '.$ns[$val1].' '.$ns[$val1].' '.cr();
				}

				$sx .= '*Edges'.cr();
				
				foreach ($nf as $key1 => $val1) {
					foreach ($nf as $key2 => $val2 ) {
						if ($val1 < $val2)
						{
							if (isset($nx[$val1][$val2]))
							{
								if (isset($nx[$val1][$val2]))
									{
										$tot = $nx[$val1][$val2];
									} else {
										$tot = 0;
									}
					    		$sx .= ($key1+1).' '.($key2+1).' '.$tot.cr();
							} else {
								
							}
						}
					}
				}

				return($sx);
			}
			
		function download_file($txt,$type='.csv')
			{
				$arquivo = 'brapci_'.date("Ymd_His").$type;
				//header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
				//header ("Pragma: no-cache");
				header ("Content-type: application/x-msexcel");
				header ("Content-Disposition: attachment; filename=\"{$arquivo}\"" );		
				echo $txt;
			}
		function tratar($txt)
			{
				$txt = troca($txt,';','.,');
				$txt = troca($txt,'","','";"');
				$txt = troca($txt,'"',';');
				$txt = troca($txt,'§','');
				$txt = troca($txt,chr(10),'§');
				$txt = troca($txt,chr(9),'');
				$txt = troca($txt,chr(15),'');
				$txt = troca($txt,chr(13),'');
				return($txt);
			}
		function export_cols($txt,$col)
			{
				$lns = splitx('§',$txt);
				$sx = '';
				for ($r=0;$r < count($lns);$r++)
					{
						$lnx = $lns[$r];
						$ln = splitx(';',$lnx);
						if (isset($ln[$col]))
							{
								$sx .= $ln[$col].'<br>';
							} else {
								$sx .= '<font color="red">'.$lnx.'</font><br>';
							}	 
					}
				return($sx);
			}
		function lines($txt)
			{
				echo $txt;
				exit;
				$lns = splitx('§',$txt);
				return($lns);
			}
	}
?>