<?php
class pibic_contrato
	{
		function lista_minutas()
			{
				global $tab_max;
				$sql = "select * from pibic_bolsa_tipo ";
				$rlt = db_query($sql);
				$bolsa = array();
				while ($line = db_read($rlt))
					{
						$op1 = $line['pbt_codigo'];
						$op2 = $line['pbt_descricao'];
						array($bolsa,$op1=>$op2);
						print_r($line);
						echo '<HR>';
					}
				print_r($bolsa);
				
				$sql = "select * from ic_noticia ";
				$sql .= " where nw_ref like 'termo_%' ";
				$rlt = db_query($sql);
				$sx = '<table width="'.$tab_max.'" class="lt0">';
				while ($line = db_read($rlt))
				{
					$sx .= '<TR><TD class="lt4">';
					$sx .= $line['nw_ref'];
					$sx .= '<TR><TD>';
					$sx .= mst($line['nw_descricao']);
				}
				$sx .= '</table>';
				return($sx);
			}
	}
