<?php
class semic_programacao
	{
		var $work;
		
		var $tabela = 'semic_programacao';
		
		function schedule_show($block='',$data)
			{
				$sql = "select * from ".$this->tabela." 
						where sp_hora = '".$block."'
						and sp_data = $data
						order by sp_data, sp_hora, sp_sala, id_sp
						";
				$rlt = db_query($sql);	
				
				$sx = '<table border=1>';
				$sx .= '<TR><TD>';

				$xsala = 'x';
				$xblocl = 'x';
				$sxa = '';
				$sxb = '<TD>SALAS';
				while ($line = db_read($rlt))
					{
						$block = trim($line['sp_hora']);
						if ($block != $xblock)
							{
								$sxa .= chr(13).chr(10).'<TR><TD>';
								$sxa .= $blocl;
								$xblock = $block;
							}

						$sala = trim($line['sp_sala']);
						if ($sala != $xsala)
							{
								$xsala = $sala;
								$sxb .= chr(13).chr(10).'<TD>Sala '.substr($sala,4,3);
								$sxa .= chr(13).chr(10).'<TD align="center">';
							}
						$sxa .= $line['sp_codigo'].'<BR>';
					}
				$sx .= chr(13).chr(10).'<TR>'.$sxb;
				$sx .= '<TR valign="top">'.$sxa;
				$sx .= '</table>';
				return($sx);
			}
		
		function schedule_insert($work,$data,$block,$room)
			{
				//$sql = "alter table semic_programacao add column sp_poster_local char(10)";
				//$rlt = db_query($sql);
				
				$sql = "select * from ".$this->tabela." 
						where sp_codigo = '".$work."'
						and sp_data > ".date("Y")."0101";
				$rlt = db_query($sql);
				
				if (!($line = db_read($rlt)))
					{
						$sql = "insert into ".$this->tabela."
							(
							sp_data, sp_hora, sp_sala,
							sp_poster_local, sp_codigo
							) values (
							'$data','$block','$room',
							'','$work'
							)					
						";
						$rlt = db_query($sql);
						echo '<h1>Saved</h1>';
					} else {
						$sql = "update ".$this->tabela."
							set sp_data = $data,
							sp_poster_local = '$poster',
							sp_hora = '$block',
							sp_sala = '$room'
							where id_sp = ".$line['id_sp'];
						$rlt = db_query($sql);
					}
				echo $this->schedule_show($block);
				return(1);
			}
		function structure()
			{
				$sql = "
				CREATE TABLE semic_programacao
				( 
				id_sp serial NOT NULL, 
				sp_data int4, 
				sp_hora char(5), 
				sp_sala char(6),
				sp_poster_local char(10), 
				sp_codigo char(10), 
				sp_avaliador_1 char(8), 
				sp_avaliador_2 char(8), 
				sp_avaliador_3 char(8), 
				sp_avaliador_4 char(8), 
				sp_avaliador_5 char(8), 
				sp_avaliador_6 char(8), 
				sp_avaliador_7 char(8), 
				sp_avaliador_1_sta char(1), 
				sp_avaliador_2_sta char(1), 
				sp_avaliador_3_sta char(1), 
				sp_avaliador_4_sta char(1), 
				sp_avaliador_5_sta char(1), 
				sp_avaliador_6_sta char(1), 
				sp_avaliador_7_sta char(1) 
				);
				"; 				
			}
	}
?>
