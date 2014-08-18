<?php
class pagamentos
	{
		var $tabela = 'pibic_pagamentos';
		function cancelar_lancar_manual($id)
			{
				$sql = "select * from pibic_pagamentos where pg_nrdoc = '$id' ";
				$rlt = db_query($sql);
				if ($line = db_read($rlt))
					{
						$sql = "delete from pibic_pagamentos where pg_nrdoc = '$id' ";
						$rlt = db_query($sql);
						echo '<h3>BO Excluído com sucesso!</h3>';
					} else {
						echo '<h3><font color="red">Não foi localizado o número do BO!</h3>';
					}
			}
		function lancar_manual($nrdoc,$vlr,$vc,$cpf,$tipo,$descricao)
			{
				$sql = "select * from pibic_pagamentos where pg_nrdoc = '$nrdoc' ";
				$rlt = db_query($sql);
				if ($line = db_read($rlt))
				{
					echo 'Já existe pagamento com este documento';
					exit;
				}
				$compl = $tipo;
				$ctr = '051462';
				$cnpj = '76659820000151';
				$tp = 'I';
				$sql = "select * from pibic_aluno where pa_cpf = '".$cpf."' ";
				$rlt = db_query($sql);
				if ($line = db_read($rlt))
					{
						$banco = '399';
						$ccag = $line['pa_cc_agencia'];
						$ccac = $line['pa_cc_conta'];
					}
				$sql = "insert into pibic_pagamentos
						(pg_ctr, pg_cnpj, pg_nrdoc,
							pg_valor, pg_vencimento, pg_cpf,
							pg_tipo, pg_nome,pg_banco,
							pg_agencia,pg_conta,pg_cc, pg_complemento
								) values (
							'$ctr','$cnpj','$nrdoc',
							'$vlr','$vc','$cpf',
							'$tp','$descricao','$banco',
							'$ccag','$ccc','$ccac','$compl')
						";	
				$rlt = db_query($sql);												
			}
		function troca_cpf($de,$para)
			{
				global $nw;
				if ((strlen($de) > 0) and (strlen($para) > 0))
					{
					$sql = "select * from pibic_aluno where pa_cpf = '$para' ";
					$rlt = db_query($sql);
					if ($line = db_read($rlt))
						{
						$obs = 'Troca de CPF de '.$de.' para '.$para.' em '.date("d/m/Y H:i").' por '.$nw->user_login;
						$obs .= chr(13).chr(10).$line['pa_obs'];
						$sql = "update pibic_aluno set pa_obs = '".$obs."' where pa_cpf = '$para' ";
						$rlt = db_query($sql);
						}
					$sql = "update ".$this->tabela." set pg_cpf = '".$para."' where pg_cpf = '".$de."' ";
					$rlt = db_query($sql);					
					}
				return(1);
			}
		function lancar_titulo($nrdoc, $descricao, $venc)
			{
				//$this->troca_cpf('07041992977', '070211992977');
				$sql = "select max(pg_valor) as valor, count(*) as total, count(pg_valor) as pg_valor  from ".$this->tabela." 
					where pg_nrdoc = '$nrdoc'
				";
				$rlt = db_query($sql);
				if ($line = db_read($rlt))
					{
						$total = $line['total'];
						$valor = $line['valor'];
						
						$sql = "select * from ".$this->tabela." where pg_nrdoc = '$nrdoc' and pg_valor = '$valor'";
						$rrr = db_query($sql);
						if ($ll = db_read($rrr))
							{
								$ctr = $ll['pg_ctr'];
								$cnpj = trim($ll['pg_cnpj']);
								$nrdoc = trim($ll['pg_nrdoc']);
								$vlr = $ll['pg_valor'];
								$vc = $venc;
								$cpf = trim($ll['pg_cpf']);
								$tp = 'M';
								$nome = trim($ll['pg_nome']);
								
								$sql = "select * from pibic_aluno where pa_cpf = '".$cpf."' ";
								$rra = db_query($sql);
								if ($l2 = db_read($rra))
									{
										
									} else {
										$sql = "select * from pibic_aluno where pa_nome like '".uppercasesql($nome)."%' ";
										echo $sql;
										$rra = db_query($sql);				
										echo '<BR>ERRO NO CPF '.$cpf.' '.$nome;
										if ($l2 = db_read($rra))
											{
													echo $sql;
													echo '<BR>CPF NÃO LOCALIZADO';
													exit;
											}
									}
								$cpf = $l2['pa_cpf'];
								
								$banco = $ll['pg_banco'];
								$ccag = $ll['pg_agencia'];
								$ccc = $ll['pg_conta'];
								$ccac = $ll['pg_cc'];
								
								$compl = round($total/2);
								$sql = "insert into pibic_pagamentos
									(pg_ctr, pg_cnpj, pg_nrdoc,
										pg_valor, pg_vencimento, pg_cpf,
										pg_tipo, pg_nome,pg_banco,
										
										pg_agencia,pg_conta,pg_cc, pg_complemento
										) values (
										'$ctr','$cnpj','$nrdoc',
										'$vlr','$vc','$cpf',
										'$tp','$descricao','$banco',
								
										'$ccag','$ccc','$ccac','$compl')
										";	
								$rlt = db_query($sql);								
							}
					}
				
			}
		
		function extornar_titulo($titulo,$descricao,$data)
			{
				$sql = "select * from ".$this->tabela." 
					where pg_nrdoc = '".$titulo."' 
					and pg_vencimento = $data
					";
				$rlt = db_query($sql);
				
				$valor = 0;
				
				while ($line = db_read($rlt))
					{
						$ctr = $line['pg_ctr'];
						$cnpj = $line['pg_cnpj'];
						$nrdoc = $line['pg_nrdoc'];
						$vlr = $line['pg_valor'];
						$vc = $line['pg_vencimento'];
						$cpf = $line['pg_cpf'];
						$tp = $line['pg_tipo'];
						$nome = $line['pg_nome'];
						$banco = $line['pg_banco'];
						$ccag = $line['pg_agencia'];
						$ccc = $line['pg_conta'];
						$ccac = $line['pg_cc'];
						
						$valor = $valor + $vlr;
					}
					if ($valor > 0)
						{
						$vlr = ($valor * (-1));
						$sql = "insert into pibic_pagamentos
							(pg_ctr, pg_cnpj, pg_nrdoc,
								pg_valor, pg_vencimento, pg_cpf,
								pg_tipo, pg_nome,pg_banco,
								
								pg_agencia,pg_conta,pg_cc
								) values (
								'$ctr','$cnpj','$nrdoc',
								'$vlr','$vc','$cpf',
								'$tp','$descricao','$banco',
						
								'$ccag','$ccc','$ccac')
								";	
						$rlt = db_query($sql);
						}					
				return($sx);
			}
		
		function processar_mes_ano()
			{
				//estpelmho
				$sql = "alter table ".$this->tabela." add pg_ano char(4)";
				//$rlt = db_query($sql);
				$sql = "alter table ".$this->tabela." add pg_mes char(3)";
				//$rlt = db_query($sql);
				
				$sql = "select * from ".$this->tabela." where pg_mes isnull 
						limit 500
				";
				$rlt = db_query($sql);
				while ($line = db_read($rlt))
					{
						$id = $line['id_pg'];
						$data = $line['pg_vencimento'];
						$ano = substr($data,0,4);
						$mes = substr($data,4,2);
						$sql = "update ".$this->tabela." set 
							pg_mes = '".$mes."',
							pg_ano = '".$ano."',
							pg_vencimento = ".$data."
							where id_pg = ".$id;
						$rrrr = db_query($sql);
						echo '. ';
					}
			}
				
		function gerar_pagamentos($bolsa,$credito,$ano=2012,$bcos,$ss='')
			{
				global $total,$total1,$total2,$total3,$total4;
				$wh = "pb_tipo = '$bolsa' and ";
				
				if ($bcos=='ORDEM')
					{
						$wh .= " pa_cc_conta = '0000000' and ";
					} else {
						$wh .= " pa_cc_conta <> '0000000' and ";
					}

				$hsbc = new hsbc;
				/* Linha 1 */
				$sx = $hsbc->header_rq();
				/* Linha 2 */
				$sx .= $hsbc->header_rq2();
				$total = 0;
				$sql = "
					select * from pibic_bolsa_contempladas
				 	inner join pibic_bolsa_tipo on pbt_codigo = pb_tipo
				 	left join pibic_aluno on pb_aluno = pa_cracha
				 	
					where (pb_status <> 'C' and pb_status <> 'S' and pb_status <> 'F') 
					and $wh pb_ano = '$ano'
					order by pa_nome
					 ";	
					 
					//left join pibic_pagamentos on (pg_cpf = pa_cpf and (pg_vencimento >= ".date("Ym01")." and pg_vencimento < ".date("Ym99").")) 
				$rlt = db_query($sql);
				while ($line = db_read($rlt))	
					{
						$valor = round($line['pg_valor']);
						$ok = 1;

						if (($ss=='S') and ($valor > 0)) { $ok = 0; }
						if ($ok == 1)
							{
							$sx .= $hsbc->req_pagamento($line,$credito);
							}
					}
				$sx .= $hsbc->req_fim(); 
				return($sx);
			}
		
		function pagamentos_por_data($dd1,$dd2)
			{
				$sql = "select * from (
						select pg_cpf, pg_nome, sum(pg_valor) as valor,
						count(*) as total
					    from ".$this->tabela."
						where pg_vencimento >= ".$dd1." and pg_vencimento <= ".$dd2."
						group by pg_nome, pg_cpf
						) as tabela
						left join pibic_aluno on pg_cpf = pa_cpf
						order by valor desc, pg_nome
					";
				
				$rlt = db_query($sql);

				$sx = '<table width="100%">';
				$sx .= '<TR><TD colspan=10>';
				$sx .= '<h3>'.msg('pagamentos').' - '.stodbr($dd1).' até '.stodbr($dd2).'</h3>';
				$sx .= '<TR>';
				$sx .= '<TH width="7%">'.msg('cpf');
				$sx .= '<TH width="35">'.msg('nome');
				$sx .= '<TH width="35%">'.msg('nome_siga');
				$sx .= '<TH width="7%">'.msg('cracha');
				$sx .= '<TH width="7%">'.msg('lacamentos');
				$sx .= '<TH width="7%">'.msg('valor');
				$sx .= '<TH width="7%">'.msg('media');
				$tot = 0;
				while ($line = db_read($rlt))
					{
					$tot = $tot + $line['valor'];
					$pag++;
					$link = '<A HREF="discente.php?dd0='.$line['id_pa'].'&dd90='.checkpost($line['id_pa']).'">';
					$tot = $tot + $line['pg_valor'];
					$sx .= '<TR>';
					$sx .= '<TD class="tabela01" align="center">';
					$sx .= $cor.$line['pg_cpf'];	
					$sx .= '<TD class="tabela01">';
					$sx .= $cor.$line['pg_nome'];
					$sx .= '<TD class="tabela01">';
					$sx .= $link.$cor.$line['pa_nome'].'</A>';
					$sx .= '<TD class="tabela01" align="center">';
					$sx .= $link.$cor.$line['pa_cracha'].'</A>';
					$sx .= '<TD class="tabela01" align="center">';
					$sx .= $cor.($line['total']);	
					$sx .= '<TD class="tabela01" align="right">';
					$sx .= $cor.number_format($line['valor'],2);
					$sx .= '<TD class="tabela01" align="right">';
					$sx .= $cor.number_format($line['valor']/$line['total'],2);
					}
				$sx .= '<TR>';
				$sx .= '<TD colspan=10>'.msg('total').' '.number_format($tot,2,',','.').' ('.$pag.')';
				$sx .= '</table>';
				return($sx);						
			}
		function excluir_pagamentos_lotes($data)
			{
				$sql = "delete from ".$this->tabela." ";
				$sql .= " where pg_vencimento = ".$data;
				$rlt = db_query($sql);
			}
		function pagamentos_lotes($data,$fim=0)
			 {
			 	if (strlen($data)==4)
					{
						$dd1 = $data.'0101'; 
						$dd2 = $data.'1299';
					}
				if (strlen($data)==6)
					{
						$dd1 = $data.'01';
						$dd2 = $data.'31';
					}
				if (strlen($data)==8)
					{
						$dd1 = $data;
						$dd2 = $data;
					}
				if (strlen($fim)==8)
					{ $dd2=$fim; }
					
				
				$sql = "select lote, count(*) as registros, sum(pg_valor) as total from
						( 
						select substr(pg_nrdoc,1,6) as lote,* from ".$this->tabela."
						where pg_vencimento >= $dd1 and
						pg_vencimento <= $dd2 and pg_valor > 0
						) as tabela 
						group by lote
						order by lote
				";
				
				$rlt = db_query($sql);
				//echo 'FIM'; exit;
				
				$sx = '<table width="100%">';
				$sx .= '<TR><TD colspan=10>';
				$sx .= '<h3>'.msg('pagamentos').'- '.stodbr($dd1).' até '.stodbr($dd2).'</h3>';
				$sx .= '<TR>';
				$sx .= '<TH width="1%">'.msg('Lote');
				$sx .= '<TH width="7%">'.msg('Bolsas');
				$sx .= '<TH width="7%">'.msg('Valor');
				
				$tot = 0;
				$pag = 0;
				while ($line = db_read($rlt))
					{
					$cpf = $line['pg_cpf'];
					$id = $line['id_pg'];
					$link = '<A HREF="'.page().'?dd1='.$data.'&dd2='.$line['lote'].'">';

					$valor = $line['total'];
					$sx .= '<TR>';
					$sx .= '<TD>'.$link.$line['lote'].'</A>';
					$sx .= '<TD>'.$line['registros'];
					$sx .= '<TD align="right">'.number_format($line['total'],2,',','.');
					$pag = $pag + $valor;
					$tot = $tot + $line['registros'];
					}
				$sx .= '<TR>';
				$sx .= '<TD colspan=10>'.msg('total').' '.number_format($pag,2,',','.').' ('.$tot.')';
				$sx .= '</table>';
				return($sx);
			 }
		
		function detalhe_pagamentos($data,$fim=0,$lote='')
			 {
			 	echo '===>'.$data;
			 	if (strlen($data)==4)
					{
						$dd1 = $data.'0101'; 
						$dd2 = $data.'1299';
					}
				if (strlen($data)==6)
					{
						$dd1 = $data.'01';
						$dd2 = $data.'31';
					}
				if (strlen($data)==8)
					{
						$dd1 = $data;
						$dd2 = $data;
					}
				if (strlen($fim)==8)
					{ $dd2=$fim; }
					
				$wh = '';
				if (strlen($lote) > 0)
					{ $wh = " and (substr(pg_nrdoc,1,6) like '".$lote."%' ) "; }
					
				$sql = "select * from ".$this->tabela."
						left join pibic_aluno on pg_cpf = pa_cpf
						where pg_vencimento >= $dd1 and
						pg_vencimento <= $dd2 $wh
						order by pa_nome, pg_vencimento, 
						pg_valor desc
				";
				//echo $sql;
				//$sql = "delete from ".$this->tabela." where pg_vencimento = 20130925 ";
				
				$rlt = db_query($sql);
				//echo 'FIM'; exit;
				
				$sx = '<table width="100%">';
				$sx .= '<TR><TD colspan=10>';
				$sx .= '<h3>'.msg('pagamentos').'- '.stodbr($dd1).' até '.stodbr($dd2).'</h3>';
				$sx .= '<TR>';
				$sx .= '<TH width="1%">'.msg('id');
				$sx .= '<TH width="7%">'.msg('venc');
				$sx .= '<TH width="7%">'.msg('cpf');
				$sx .= '<TH width="35">'.msg('nome');
				$sx .= '<TH width="35%">'.msg('nome_siga');
				$sx .= '<TH width="7%">'.msg('cracha');
				$sx .= '<TH width="7%">'.msg('documento');
				$sx .= '<TH width="7%">'.msg('valor');
				
				//$sql = "delete from ".$this->tabela." where pg_valor = -400 ";
				//$rlt = db_query($sql);
				
				
				$tot = 0;
				$pag = 0;
				$xcpf = 'x';
				$xid = 0;
				$idc = '';
				while ($line = db_read($rlt))
					{
					$pag++;
					$cpf = $line['pg_cpf'];
					$id = $line['id_pg'];
					if ($id != $xid)
						{
							$valor = $line['pg_valor'];
							$link = '<A HREF="discente.php?dd0='.$line['id_pa'].'&dd90='.checkpost($line['id_pa']).'">';
							$cor = '';
							$xid = $id;
							if ($cpf == $xcpf) { $cor = '<font color="red">'; }
							if ($valor < 0) { $cor = ''; }
							$xcpf = $cpf;
							$tot = $tot + $line['pg_valor'];
							$sx .= '<TR>';
							$sx .= '<TD>'.$pag;
							$sx .= '<TD class="tabela01" align="center">';
							$sx .= $cor.stodbr($line['pg_vencimento']);	
							$sx .= '<TD class="tabela01" align="center">';
							$sx .= $cor.$line['pg_cpf'];	
							$sx .= '<TD class="tabela01">';
							$sx .= $cor.$line['pg_nome'];
							$sx .= '<TD class="tabela01">';
							$sx .= $link.$cor.$line['pa_nome'].'</A>';
							$sx .= '<TD class="tabela01" align="center">';
							$sx .= $link.$cor.$line['pa_cracha'].'</A>';
							$sx .= '<TD class="tabela01" align="center">';
							$sx .= $line['pg_nrdoc'];
							$sx .= '<TD class="tabela01" align="right">';
							if ($line['pg_valor'] < 0)
								{
									$cor = '<font color="red">'; 
								}
							$sx .= $cor.number_format($line['pg_valor'],2);
						}
						$xdoc = trim($line['pg_nrdoc']);
						if ($xdoc == $ydoc)
							{ $sx .= '===='; }
						$ydoc = $xdoc;
						
						$pc = trim($line['pg_cracha']);
						if ((strlen($$pc) == 0) and (strlen($line['pa_nome']) > 0))
							{
								$sql = "update ".$this->tabela."
									set pg_cracha = '".$line['pa_cracha']."'
									where id_pg = ".$line['id_pg'];  
								$rltx = db_query($sql);
							}
						if (trim($line['pg_cpf']) != sonumero($line['pg_cpf']))	
							{
								$sql = "update ".$this->tabela." 
									set pg_cpf = '".strzero(sonumero($line['pg_cpf']),11)."' 
									where id_pg = ".$line['id_pg'];
								$rltx = db_query($sql);
							}				
					}
				$sx .= '<TR>';
				$sx .= '<TD colspan=10>'.msg('total').' '.number_format($tot,2,',','.').' ('.$pag.')';
				$sx .= '</table>';
				return($sx);
			 }
		
		function resumo_pagamentos()
			{
				$sql = "alter table ".$this->tabela." add column pg_modalidade char(10)";
				//$rlt = db_query($sql);
				
				$sql = "select pg_modalidade, pg_vencimento, count(*) as total,
						sum(pg_valor) as valor
						from ".$this->tabela."
						group by pg_vencimento, pg_modalidade
						order by pg_vencimento desc
						";
				$rlt = db_query($sql);
				$xmes = 'xxxx';
				$tot = 0;
				$tot1 = 0;
				$pag = 0;
				$pag1 = 0;
				$sx = '<table width="100%">';
				$sh = '<TR>';
				$sh .= '<TH>'.msg('modalidade');
				$sh .= '<TH>'.msg('dt_vencimento');
				$sh .= '<TH>'.msg('documentos');
				$sh .= '<TH>'.msg('valor_total');
				
				$page = page();
				$page = troca($page,'.php','a.php');
				
				while ($line = db_read($rlt))
					{
						$mes = substr($line['pg_vencimento'],0,6);
						
						$link = '<A href="'.$page.'?dd1='.$line['pg_vencimento'].'">'; 
						if ($xmes != $mes)
							{
								$sx .= '<TR><TD colspan=5>';
								$sx .= '<H3>';
								$sx .= substr($mes,4,2).'/'.substr($mes,0,4);
								$sx .= '</H3>';
								$sx .= $sh;
								$xmes = $mes;
							}
						$sx .= '<TR>';
						$sx .= '<TD class="tabela01" width="25%">';
						$sx .= $link;
						$sx .= stodbr($line['pg_modalidade']);
						
						$sx .= '<TD class="tabela01" width="25%" align="center">';
						$sx .= $link;
						$sx .= stodbr($line['pg_vencimento']);
						
						$sx .= '<TD align="center" class="tabela01" width="25%">';
						$sx .= $link;
						$sx .= $line['total'];
						
						
						$sx .= '<TD align="right" class="tabela01" width="25%">';
						$sx .= number_format($line['valor'],2,',','.');
					}
				$sx .= '</table>';
				return($sx);
				
			}
		
		function pagamento_mes($mes=0,$ano=1900)
			{
				$dd1 = strzero($ano,4).strzero($mes,2).'01';
				$dd2 = strzero($ano,4).strzero($mes,2).'99';
				$sql = "select * from ".$this->tabela."
					where pg_vencimento >= $dd1 and pg_vencimento <= $dd2
				";
				echo $sql;
				
			}
	
		function pagamentos($cracha='',$cpf='')
			{
				$cracha = '';
				$sql = "select * from ".$this->tabela." where ";
					if ($cracha != '') { $sql .= "pg_cracha = '$cracha' "; }
					if ($cpf != '') { $sql .= " pg_cpf = '$cpf' "; }
				if (strlen($cracha.$cpf)==0) { return(''); }
				
				$sql .= " order by pg_vencimento desc ";
				$rlt = db_query($sql);
				$sx = '<table width="100%" class="tabela01">';
				
				$sx .= '<TR>';
				$sx .= '<TD colspan=10>';
				$sx .= '<H3>'.msg('pagamento').'</h3>';
				
				$sx .= '<TR>';
				$sx .= '<TH width="10%">'.msg('cpf');
				$sx .= '<TH width="40%">'.msg('beneficiario');
				$sx .= '<TH width="5%">'.msg('nrdoc');
				$sx .= '<TH width="5%">cp';
				$sx .= '<TH width="10%">'.msg('vencimento');
				$sx .= '<TH width="15%">'.msg('conta');
				$sx .= '<TH width="10%">'.msg('valor');
				$sx .= '<TH width="10%">'.msg('status');
			
				$tot = 0;
				$pag = 0;
				while($line = db_read($rlt))
					{
						$sx .= '<TR>';
						$sx .= '<TD class="tabela01" align="center">';
						$sx .= trim($line['pg_cpf']);
						$sx .= '<TD class="tabela01">';
						$sx .= trim($line['pg_nome']);
						$sx .= '<TD class="tabela01" align="center">';
						$sx .= trim($line['pg_nrdoc']);
						$sx .= '<TD class="tabela01" align="center">';
						$sx .= trim($line['pg_complemento']);
						$sx .= '<TD class="tabela01" align="center">';
						$sx .= stodbr($line['pg_vencimento']);
						$sx .= '<TD class="tabela01" align="center">';
						$sx .= $line['pg_agencia'];
						$sx .= '-'.$line['pg_conta'];
						$sx .= '<TD class="tabela01" align="right">';
						$sx .= number_format($line['pg_valor'],2,',','.');
						$sx .= '<TD class="tabela01" align="center">';
						$sta .= trim($line['pg_status']);
						if ($sta == '') { $sx .= msg('lancado'); }

						$sx .= '<TD class="tabela01" align="center">';
						$sx .= trim($line['pg_mes']).'/'.trim($line['pg_ano']);

						$tot = $tot + $line['pg_valor'];
						$pag++;
					}
				$sx .= '<TR><TD colspan=10 align="right"><B><I>';
				$sx .= msg('total').' '.number_format($tot,2,',','.').' ('.$pag.' '.msg('lancamento').')';
				$sx .= '</table>';	
				
				return($sx);
			}
		function processa_seq()
			{
				$sql = "select count(*) as total from ln where ln_status='A' ";	
				$rlt = db_query($sql);
				$line = db_read($rlt);
				echo '<H2>'.$line['total'].'</h2>';
				$sql = "select * from ln where ln_status='A' order by id_ln ";	
				$rlt = db_query($sql);
				$id=0;
				while ($ln = db_read($rlt))
				{
					print_r($ln);
					echo '<HR>';
					$id++;
					//print_r($ln);
					$l = utf8_decode(trim($ln['ln_text']));
					$tp = substr($l,13,1);
					
					if ($tp == '0')
						{
							$cnpj = substr($l,18,14);
							$ctr = substr($l,32,6);
						}
						
					if ($tp == 'A')
						{
							$banco = substr($l,20,3);
							$seq = substr($l,3,4);
							$op = substr($l,7,1);
							$nrdoc = trim(substr($l,73,18));
							$venc = substr($l,93,8);
							$vc = substr($venc,4,4).substr($venc,2,2).substr($venc,0,2);
							$vlr = round(substr($l,121,13))/100;
							$nome = substr($l,43,30);
							$bco = $banco;
							echo '<TT>'.$l.'</tt>';
/*							
3990002300001A00070000100009 0000000610305
*/

							
							$ccag = substr($l,23,5);
							$ccc = substr($l,35,7);
							$ccac = substr($l,30,12);
							$id1 = $ln['id_ln'];
						}
						
					if ($tp == 'B')
						{
							$cpf = substr($l,21,14);
							$tp = 'I';
							$id2 = $ln['id_ln'];
							
						echo '<BR>banco->'.$bco.'Ag. ['.$ccag.'-'.$ccc.']';
						echo ''.$seq.'-'.$op.'-'.$tp;
						echo '->'.$vlr;
						echo '->'.$nome;
						echo '->'.$cpf;
						echo '->'.$vc;
						
						$sql = "select * from pibic_pagamentos 
								where pg_nrdoc = '$nrdoc'
								and pg_cpf = '$cpf' and pg_vencimento = $vc
						";

						$rlt2 = db_query($sql);
						if (!(db_read($rlt2)))
							{						
							$sql = "insert into pibic_pagamentos
							(pg_ctr, pg_cnpj, pg_nrdoc,
								pg_valor, pg_vencimento, pg_cpf,
								pg_tipo, pg_nome,pg_banco,
								
								pg_agencia,pg_conta,pg_cc
								) values (
								'$ctr','$cnpj','$nrdoc',
								'$vlr','$vc','$cpf',
								'$tp','$nome','$banco',
						
								'$ccag','$ccc','$ccac')
								";	
							$rlt2 = db_query($sql);
							}
							$sql = "update ln set ln_status = 'B' where (id_ln = ".$id1." or id_ln = ".$id2." )";
							$rlt3 = db_query($sql);
												
						}
				}
				return($id);				
			}
		
		function inport_file($file)
			{
				if (!(file_exists($file)))
				{
					echo $file.'<BR>';
					echo 'ERRO DE ABERTURA';
					return(0);
				}
				$fld = fopen($file,'r');
				$sx = '';
				while (!(feof($fld)))
				{
					$sx .= fread($fld,1024);
				}
				fclose($fld);
			
				$ln = splitx(chr(13),$sx);
				$sql = "create table ln (
					id_ln serial NOT NULL,
					ln_text text,
					ln_status char(1)
				)";
				//$rlt = db_query($sql);
				$sql = "delete from ln where 1=1 ";
				$rlt = db_query($sql);

				echo '===>'.count($ln);
				for ($r=0;$r < count($ln);$r++)
					{
					if (round($r/10) == ($r/10)) { echo 'x'; } else { echo '.'; }
					if (round($r/100) == ($r/100)) { echo '<BR>'; }
					$lnx = UpperCaseSql($ln[$r]);
					$lnx = troca($lnx,"'",'´');
					$sql = "insert into ln (ln_text, ln_status) 
						values
						('".$lnx."','A')		
					";
				$rlt = db_query($sql);
				}
			}
		
		function lista_pagamentos_total($dd1=19000101,$dd2=20990101,$cpf='')
			{
				$sql = "selec";
			}
		
		function limpa_duplicados()
			{
				$sql = "
					select * from (
					select pg_cpf, round(pg_vencimento/100) as vencimento, count(*) as total, max(id_pg) as id_pg from dados
					group by pg_cpf, vencimento
					) as tabela where total > 1
					order by total desc
					limit 300 ";
				$rlt = db_query($sql);
				
				$sql = "delete from ".$this->tabela." where ";
				$id = 0;
				while($line = db_read($rlt))
					{
						
						if ($id > 0) { $sql .= ' or '; }
						$sql = $sql . '(id_pg = '.$line['id_pg'].') ';
						$id++;
					}
				if ($id > 0)
					{
						echo $sql;
						$rlt = db_query($sql);
					}				
			}	
		function row()
			{
				global $cdf,$cdm,$masc;
				$cdf = array('id_pg','pg_cpf','pg_nrdoc','pg_nome','pg_valor','pg_complemento');
				$cdm = array('cod',msg('cpf'),msg('nrdoc'),msg('nome'),msg('valor'),'compl');
				$masc = array('','','','','','','');
				return(1);				
			}	
		
		function cp()
			{
				$cp = array();
				array_push($cp,array('$H8','id_pg','',False,true));
				array_push($cp,array('$S1','pg_complemento','Complemento',False,true));
				array_push($cp,array('$D8','pg_vencimento','Vencimento',False,true));
				array_push($cp,array('$S20','pg_nrdoc','Documento',False,true));
				return($cp);
			}
		function structure()
			{
				$sql = "
					CREATE TABLE pibic_pagamentos
					( 
					id_pg serial NOT NULL, 
					pg_ctr char(6), 
					pg_cnpj char(15), 
					pg_nrdoc char(10), 
					pg_valor float8, 
					pg_vencimento int4, 
					pg_cpf char(15), 
					pg_tipo char(3), 
					pg_nome char(100), 
					pg_banco char(4), 
					pg_agencia char(6), 
					pg_conta char(8), 
					pg_cc char(15), 
					pg_cracha char(8),
					pg_protocolo char(7),
					pg_mes char(3),
					pg_ano char(4
					);";
				$rlt = db_query($sql); 	
				echo $sql;			
			} 
	}
?>

