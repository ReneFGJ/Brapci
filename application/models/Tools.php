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
		function trata($txt)
			{
				$txt = troca($txt,'; ',';');
				for ($r=0;$r < 32;$r++)
					{
						if (($r != 13) and ($r != 10))
							{
								$txt = troca($txt,chr($r),' ');
							}		
					}
				
				$t  = array('á','é','í','ó','ú','Á','É','Í','Ó','Ú','ã','õ','Â','Ô','ä','ë','ï','ö','ü','Ä','Ë','Ï','Ö','Ü','â','ê','î','ô','û','Â','Ê','Ô','Û','ç','Ç');
				$tr = array('a','e','i','o','u','A','E','I','O','U','a','o','A','O','a','e','i','o','u','A','E','I','O','U','a','e','i','o','u','A','E','O','U','c','C');
				for ($r=0;$r < count($tr);$r++)
					{
						$t1 = $t[$r];
						$t2 = $tr[$r];
						$txt = troca($txt,$t1,$t2);
						$txt = troca($txt,utf8_decode($t1),$t2);		
					}
				for ($r=128;$r < 255;$r++)
					{
						$txt = troca($txt,chr($r),' ');		
					}
				$txt = troca($txt,'  ',' ');
				return($txt);
			}
		function gerar_nomes_xls($txt)
			{
				$txt = $this->trata($txt);
				$lns = splitx(';',$txt);
				$ns = array();
				$nf = array();
				for ($r=0;$r < count($lns);$r++)
					{
						$mn = trim(UpperCaseSql($lns[$r]));
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
				    $sx .= ''.strzero($i,4).';'.$val.''. chr(13).chr(10);
				}
				$sx  .= '';
				return($sx);
			}
		function gerar_matriz_xls($txt)
			{
				$txt = $this->trata($txt);
				$txt = troca($txt,';','£');
				$txt = troca($txt,chr(10),';');
				$txt = troca($txt,chr(13),';');
				$lns = splitx(';',$txt);
				
				$nx = array();
				$ns = array();
				$nf = array();
				
				for ($r=0;$r < count($lns);$r++)
					{
						$mn = $lns[$r];
						$mn = troca($mn,'£',';');
						$au = splitx(';',$mn.';');
						
						for ($a=0;$a < count($au);$a++)
							{
								$mm = nbr_autor($au[$a],5);
								$mm = troca($mm,',','');
								$mm = troca($mm,'. ','');
								$mm = troca($mm,'.','');
								$au[$a] = $mm;								
							}
						
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
				
				/*  matriz */
				$sx = '#;';
				foreach ($nf as $key => $val1) {
					$sx .= ''.$val1.';';
				}
				$sx .= ''.cr();
				foreach ($nf as $key => $val1) {
					$sx .= ''.$val1.';';
					foreach ($nf as $key2 => $val2 ) {
						if ((isset($nx[$val1][$val2])) AND ($val1 != $val2))
						{
				    		$sx .= ''.$nx[$val1][$val2].';';
						} else {
							$sx .= '0;';
						}
					}
					$sx .= ''.cr();
				}
				$sx  .= '';
				return($sx);
			}

		function gerar_matriz_pajek($txt)
			{
				$txt = $this->trata($txt);
				$txt = troca($txt,';','£');
				$txt = troca($txt,chr(10),';');
				$txt = troca($txt,chr(13),';');
				$lns = splitx(';',$txt);
				
				$nx = array();
				$ns = array();
				$nf = array();
				$nz = array();
				for ($r=0;$r < count($lns);$r++)
					{
						$mn = $lns[$r];
						$mn = troca($mn,'£',';');
						$au = splitx(';',$mn.';');
						
						for ($a=0;$a < count($au);$a++)
							{
								$mm = nbr_autor($au[$a],5);
								$mm = troca($mm,',','');
								$mm = troca($mm,'. ','');
								$mm = troca($mm,'.','');
								$au[$a] = $mm;								
							}						
						
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
				$max = 10;
				foreach ($nf as $key => $val1) {
					if ($ns[$val1] > $max)
						{
							$max = $ns[$val1]; 
						}
				}
				foreach ($nf as $key => $val1) {
					$n1 = number_format($ns[$val1]/$max*10,4);
					//$sx .= ($key+1).' "'.$val1.'" '.$n1.' '.$ns[$val1].' '.$ns[$val1].' '.cr();
					$sx .= ($key+1).' "'.$val1.'" ellipse x_fact '.$n1.' y_fact '.$n1.' fos 1 ic LightYellow lc Blue '.cr();
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
				$txt = troca($txt,';','¢');
				$txt = troca($txt,';','.,');
				$txt = troca($txt,'","','";"');
				$txt = troca($txt,'"',';');
				$txt = troca($txt,'§','');
				$txt = troca($txt,chr(10),'§');
				$txt = troca($txt,chr(9),'');
				$txt = troca($txt,chr(15),'');
				$txt = troca($txt,chr(13),'');
				$lns = splitx(';',$txt);
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