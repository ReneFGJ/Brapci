<?php
class pibic_historico
	{
		var $tabela = 'pibic_bolsa_historico';
		
		function inserir_historico($proto,$ac,$hist,$aluno1,$aluno2,$motivo,$obs='')
			{
				global $nw;

					ini_set('display_errors',255);
					ini_set('error_reporting',255);				
				
				$data = date("Ymd");
				$hora = date("H:i");
				$log = round($nw->user_cracha);
				$sql = "select * from ".$this->tabela."
						where bh_protocolo = '$proto'
						and bh_data = $data
						and bh_acao = $ac
					";
				$rlt = db_query($sql);
				
				if ($line = db_read($rlt))
					{
						
					} else {
						$sql = "insert into ".$this->tabela." 
							(bh_protocolo, bh_data, bh_hora,
							bh_log, bh_acao, bh_historico,
							bh_aluno_1, bh_aluno_2, bh_motivo,
							bh_obs
							) values (
							'$proto',$data,'$hora',
							'$log','$ac','$hist',
							'$aluno2','$aluno1','$motivo',
							'$obs')
					";
					$rlt = db_query($sql);
					}
				return($sx);
			}
		
		function mostra_historico($proto)
			{
				global $tab_max;						
				$sql = "select * from ".$this->tabela." where bh_protocolo = '".$proto."' ";
				$rlt = db_query($sql);
				$sx = '<table width="900" class="tabela00" align="center">';
				$sx .= '<TR><TH>Data<TH>Hora<TH>Historico<TH>Protocolo';
				while ($line = db_read($rlt))
					{
						$hist = trim($line['bh_historico']);
						switch ($hist)
							{
								case 'Validação do contrato por ()':
									$sx .= '';
									break;
								default:
									$sx .= '<TR valign="top">';
									$sx .= '<TD class="tabela01">'.stodbr($line['bh_data']);
									$sx .= '<TD class="tabela01">'.$line['bh_hora'];
									$sx .= '<TD class="tabela01">'.$line['bh_historico'];
									$sx .= '<TD class="tabela01">'.$line['bh_protocolo'];
									$sx .= '<TR valign="top">';
									$sx .= '<TD><TD class="tabela01" colspan=3>'.$line['bh_obs'];
									break;
							}
						
					}
				$sx .= '</table>';
				return($sx);
			}
		
		function cp()
			{
				$cp = array();
				array_push($cp,array('$H8','id_bh','',False,True));
				array_push($cp,array('$S7','bh_protocolo','Protocolo',False,True));
				array_push($cp,array('$S7','bh_aluno_1','Aluno (Sai)',False,True));
				array_push($cp,array('$S7','bh_aluno_2','Aluno (Entra)',False,True));
				return($cp);
			}

		
		function historico($tp=90,$dd1=19000101,$dd2=20500101,$bolsa='')
			{
				$tp = 91;
				$wh = ' and bh_acao = '.round($tp);
						
				$in = ' left join pibic_bolsa_contempladas on bh_protocolo = pb_protocolo 
						inner join pibic_bolsa_tipo on pbt_codigo = pb_tipo
				';
				$sql = "select * from ".$this->tabela."
						$in
						where  bh_data >= $dd1 and bh_data <= $dd2
						$wh
						order by bh_data
						"
						;
				
				$rlt = db_query($sql);
				$sx = '<table width="100%">';
				$sx .= '<TR><TH>Data';
				$sx .= '<TH>Estudante<BR>Sai';
				$sx .= '<TH>Estudante<BR>Entra';
				$sx .= '<TH>Protocolo';
				$sx .= '<TH>Descricao';
				$id = 0;
				while ($line = db_read($rlt))
					{
						$id++;
						$link = '<A HREF="pibic_historico_ed.php?dd0='.$line['id_bh'].'" target="_new">';
						$sx .= '<TR>';
						$sx .= '<TD class="tabela01"><nobr>';
						$sx .= stodbr($line['bh_data']);

						$sx .= '<TD class="tabela01">';
						$sx .= $line['bh_aluno_1'];	
						$sx .= '<TD class="tabela01">';
						$sx .= $line['bh_aluno_2'];	

						$sx .= '<TD class="tabela01">';
						$sx .= $line['bh_protocolo'];
						
						$sx .= '<TD class="tabela01">';
						$sx .= $line['bh_historico'];
						$sx .= '<TD class="tabela01">';
						$sx .= $link;
						$sx .= $line['bh_acao'];	
						
						$sx .= '<TD class="tabela01">';
						$sx .= $line['pbt_edital'];

						$sx .= '<TD class="tabela01">';
						$sx .= $line['pbt_descricao'];
												
						$ln = $line;					
					}
				$sx .= '<TR><TD colspan=2>Total de '.$id.' registros localizados';
				$sx .= '</table>';
				//print_r($ln);
				return($sx);
			} 
		
		function consulta_historico($cracha)
			{
				$sql = "select * from ".$this->tabela."
					where bh_aluno_1 = '$cracha' 
					or bh_aluno_2 = '$cracha'
					or bh_historico like '%".$cracha."%' 
					order by bh_data, bh_hora, id_bh			
				";
				$rlt = db_query($sql);
				
				$sx = '<table width="100%">';
				$sx .= '<TR><TD colspan=10>';
				$sx .= '<h3>'.msg('historico').' - '.stodbr($dd1).' até '.stodbr($dd2).'</h3>';
				$sx .= '<TR>';
				$sx .= '<TH width="7%">'.msg('data');
				$sx .= '<TH width="35">'.msg('historico');
				$id = 0;			
				while ($line = db_read($rlt))
					{
						$id++;
						$sx .= '<TR>';
						$sx .= '<TD class="tabela01">';
						$sx .= stodbr($line['bh_data']);
						$sx .= '&nbsp;';
						$sx .= $line['bh_hora'];
						$sx .= '<TD class="tabela01">';
						$sx .= $line['bh_historico'];
						
					}
				$sx .= '</table>';
				if ($id==0) { return(''); }
				return($sx);
			}
	}
?>