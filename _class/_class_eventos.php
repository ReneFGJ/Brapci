<?php
class eventos
	{
		var $id;
		var $nome;
		var $evento;
		var $erro;
		var $erro_msg;
		var $line;
		
		var $tabela = 'evento_cadastro';
		var $tabela_cadastro = 'evento_cadastro';
		var $tabela_cursos = 'evento_cursos';
		
		function row_cursos()
			{
				global $cdf,$cdm,$masc;
				$cdf = array('id_ev','ev_nome','ev_codigo','ev_preco','ev_de','ev_ate','ec_journal');
				$cdm = array('cod',msg('nome'),msg('codigo'),msg('preco'),msg('de'),msg('ate'),msg('journal'));
				$masc = array('','','','','','','');
				return(1);
			}
		function cp_cursos()
			{
				$cp = array();
				array_push($cp,array('$H8','id_ev','',False,True));
				array_push($cp,array('$H8','ev_codigo','',False,True));
				array_push($cp,array('$S50','ev_nome','Descrição',False,True));
				array_push($cp,array('$N8','ev_preco','Preço',False,True));
				array_push($cp,array('$D8','ev_de','de',False,True));
				array_push($cp,array('$D8','ev_ate','até',False,True));
				return($cp);
			}
		
		function row()
			{
				global $cdf,$cdm,$masc;
				$cdf = array('id_ec','ec_nome_inscrito','ec_nome','ec_cpf','ec_email_1','ec_instituicao','ec_senha');
				$cdm = array('cod',msg('nome'),msg('faturamento'),msg('cpf/cnpj'),msg('email'),msg('instituição'),msg('senha'));
				$masc = array('','','','','','','');
				return(1);								
			}
		
		function lista_inscritos()
			{
				global $jid,$dd;
				$this->updatex();
				$sql = "select * from evento_efetivadas 
						left join evento_cadastro on ef_inscrito = ec_codigo  
						order by ef_data, id_ef
						";
						//where ef_journal = ".round($jid);
				$rlt = db_query($sql);
				$sx .= '<table class="tabela00 lt1" border=1>';
				$sx .= '<TR><TH>Data';
				$sx .= '<TH>Boleto';
				$sx .= '<TH>Valor';
				$sx .= '<TH>Sta';
				$sx .= '<TH>Cod.';
				$sx .= '<TH>Nome';
				$sx .= '<TH>Cidade';
				$sx .= '<TH>e-mail';
				$sx .= '<TH>fone';
				$sx .= '<TH>Instituições';
				
				while ($line = db_read($rlt))
				{
					if (($line['id_ef']==$dd[0]) and ($dd[2]=='CANCELAR'))
						{
							$sql = "update evento_efetivadas set  ef_status = 'X' where ef_status = 'A' and id_ef = ".round($dd[0]);
							$xrlt = db_query($sql);
							redirecina(page());
						}
					if (($line['id_ef']==$dd[0]) and ($dd[2]=='PAGO'))
						{
							$sql = "update evento_efetivadas set  ef_status = 'B' where ef_status = 'A' and id_ef = ".round($dd[0]);
							$xrlt = db_query($sql);
							redirecina(page());	
							//$xrlt = db_query($sql);
						}						
					$link = '<A HREF="'.page().'?dd0='.$line['id_ef'].'&dd2=CANCELAR">CANCELAR</A>';
					$linkp = '<A HREF="'.page().'?dd0='.$line['id_ef'].'&dd2=PAGO">';
					if ($line['ef_status']!='A') { $link = ''; }
					$sx .= '<TR class="tabela01">';
					$sx .= '<TD>';
					$sx .= stodbr($line['ef_data']);
					$sx .= '<TD>';
					$sx .= $line['ef_boleto'];

					$sx .= '<TD>';
					$sx .= number_format($line['ef_valor'],2,',','.');
					
					$sx .= '<TD>';
					$sx .= $line['ef_status'];
					$sx .= '<TD>';
					$sx .= $line['ef_inscrito'];
					$sx .= '<TD>';
					$sx .= $line['ec_nome'];
					$sx .= '<TD>';
					$sx .= $line['ec_cidade'];
					$sx .= '<TD>';
					$sx .= $line['ec_email_1'];
					$sx .= '<TD>';
					$sx .= $line['ec_tele_1'];
					$sx .= '<TD>';
					$sx .= $line['ec_instituicao'];
					
					if (trim($line['ef_status'])=='A')
						{
							$linkb = "'".http.'eventos/boleto.php?dd0='.$line['ef_boleto']."'";
							$boleto = '<input type="button" value="imprimir boleto" onclick="newxy2('.$linkb.',800,600);">';
							$sx .= '<TD>';
							$sx .= $boleto;
							
							$sx .= '<TD>';
							$sx .= $linkp;
							$sx .= 'QUITAR';
							$sx .= '</A>';
						}
					$sx .= '<TD>';
					$sx .= $link;
					$sx .= '';						
				}
				$sx .= '</table>';
				return($sx);
			}
		
		function boleto_emite($cliente,$valor)
			{
				$sql = "select * from evento_cadastro 
						where id_ec = ".$cliente;
				
				$rlt = db_query($sql);
				if ($line = db_read($rlt))
					{
						$venc = DateAdd('d',15,date("Ymd"));
						$venc = 20130522;
 						/* (Formato final dd-mm-aaaa) */
						$venc = substr($venc,6,2).'-'.substr($venc,4,2).'-'.substr($venc,0,4);
						$cliente_nome = troca($cliente_nome,'&',' e ');
						$NomePessoa = trim(uppercasesql($line['ec_nome']));
						$chave = "PUC2008";
						$Valor = round($valor);
						$cpf = sonumero($line['ec_cpf']);
						$cpfc = trim($line['ec_cpf']);
						$site .= '&chave='.md5($chave.$NomePessoa);
						$dtn = $line['ec_nascimento'];
						$datanascimento = substr($dtn,6,2).'-'.substr($dtn,4,2).'-'.substr($dtn,0,4);
						$rg = trim($line['ec_rg']);
						$tel1 = sonumero($line['ec_tele_1']);
						$tel2 = sonumero($line['ec_tele_2']);
						$tel3 = '';
						$email1 = lowercase($line['ec_email_1']);
						$email2 = lowercase($line['ec_email_2']);
						$NomePessoa = trim(uppercasesql($line['ec_nome']));							
						$cep = sonumero($line['ec_cep']);
						$pais = 1;
						$estado = round($line['ec_estado']);
						$municipio = UpperCaseSql($line['ec_cidade']);
						$bairro = UpperCaseSql($line['ec_bairro']);
						$logradouro = UpperCaseSql($line['ec_endereco']);
						$numero = trim(UpperCaseSql($line['ec_numero']));
						if (strlen($bairro)==0) { $numero = 'SN'; }
						if (strlen($numero)==0) { $numero = 'SN'; }
						$complemento = UpperCaseSql($line['ec_complemento']);						
						
					/* Recupera dados do CNPJ ou CPF da primeira inscrição */
					//$sql = "select * from evento_cadastro where ec_cpf like '%".$cpfc."%' order by id_ec limit 1 ";
					//$rlt = db_query($sql);
					//if ($line = db_read($rlt))
					//	{
					//	$NomePessoa = trim(uppercasesql($line['ec_nome']));							
					//	$cep = sonumero($line['ec_cep']);
					//	$pais = 1;
					//	$estado = round($line['ec_estado']);
					//	$municipio = UpperCaseSql($line['ec_cidade']);
					//	$bairro = UpperCaseSql($line['ec_bairro']);
					//	$logradouro = UpperCaseSql($line['ec_endereco']);
					//	$numero = trim(UpperCaseSql($line['ec_numero']));
					//	if (strlen($bairro)==0) { $numero = 'SN'; }
					//	if (strlen($numero)==0) { $numero = 'SN'; }
					//	$complemento = UpperCaseSql($line['ec_complemento']);							
					//	}

					echo '<BR>Faturamento para:<BR>';
					echo $NomePessoa;
					echo '<BR>'.$logradouro.' '.$numero.' '.$complemento;
					echo '<BR>'.$municipio.' '.$bairro;
					
					$vars = array(
        				'cod_empresa' => '1',
        				'pessoasemcadastro' => $NomePessoa,
        				'cod_tipo_titulo' => '598',
    				    'condicaorec' => '324',
    				    'data_vencimento' => $venc,
    				    'data_limite_pgto' => $venc,
    				    'numparcelas_par' => 1,
        				'valor' => $Valor,
        				'chave' => md5($chave.$NomePessoa),
        				'cpf' => $cpf,
        				'rg' => $rg,
        				'datanascimento' => $datanascimento,
        				'tel1' => $tel1,
        				'tel2' => $tel2,
        				'tel3' => $tel3,
        				'email1' => $email1,
        				'email2' => $email2,
        				'cep' => $cep,
        				'pais' => $pais,
        				'estado' => $estado,
        				'municipio' => $municipio,
        				'bairro' => $bairro,
        				'municipio' => $municipio,
        				'logradouro' => $logradouro,
        				'numero' => $numero,
        				'complemento' => $complemento,
        				'tipocategoriaprincipal' => 8
						);
				require_once('_class_boleto_pucpr.php');
				$bol = new boleto;
				$postdata = $bol->http_parse_query( $vars );
				
				$opts = array('http' =>
				    array(
				        'method'  => 'POST',
				        'header'=> "Content-type: application/x-www-form-urlencoded\r\n"
 				               . "Content-Length: " . strlen($postdata) . "\r\n",
				        'content' => $postdata
    				)
				);
 
				$context = stream_context_create($opts);
				$url = 'https://wwws.pucpr.br/sistemas_s/pucpr/financeiro/boleto/geraTituloPessoa.php';
				$fp = fopen($url,"r",false,$context);
				$rlt = '';
				while ( ! feof( $fp ) ) {
   								$rlt .= fgets( $fp, 1024 );
					}						
				}
				return($rlt);
			}
		
		function minhas_inscricoes()
			{
				global $jid;
				
				$user = strzero($_SESSION['id_ec'],7);
				$sql = "select * from evento_efetivadas
						inner join evento_cursos on ef_curso = ev_codigo				
						where ef_journal = ".$jid." 
						and ef_inscrito = '$user'
						";
				$rlt = db_query($sql);
				$sx = '<TABLE width="100%">';
				$sx .= '<TR><TH>Curso / Evento<TH>Valor<TH>Data<TH>Status<TH>Ação';
				while ($line = db_read($rlt))
					{
						$sx .= '<TR>';
						$sx .= '<TD>';
						$sx .= $line['ev_nome'];
						$sx .= '<TD align="right">';
						$sx .= number_format($line['ev_preco'],2,',','.');
						$sx .= '<TD align="center">';
						$sx .= stodbr($line['ef_data']);
						$sta = trim($line['ef_status']);
						$sts = trim($line['ef_status']);
						if ($sta == 'A') { $sta = '<font color="green">Aberto</font>'; }
						if ($sta == 'B') { $sta = '<font color="blue">Quitado</font>'; }
						if ($sta == 'C') { $sta = '<font color="grey">**Cancelado**</font>'; }
						if ($sta == 'X') { $sta = '<font color="grey">**Cancelado**</font>'; }
						$sx .= '<TD>';
						$sx .= $sta;	
						$sx .= '<TD width="100">';
						$boleto = '';
						if ($sts == 'A')
							{
							$link = "'".http.'eventos/boleto.php?dd0='.$line['ef_boleto']."'";
							$boleto = '<input type="button" value="imprimir boleto" onclick="newxy2('.$link.',800,600);">';
							}
						$sx .= $boleto;					
					}
				$sx .= '</TABLE>';
				return($sx);
			}
		
		function realiza_inscricao($curso)
			{
				global $jid;
				$sql = "select * from evento_cursos where ev_codigo = '".$curso."' ";
				$rlt = db_query($sql);
				if ($line = db_read($rlt))
					{ $valor = $line['ev_preco']; }
				
				if ($valor > 0)
				{
				$user = strzero($_SESSION['id_ec'],7);
				
				$sql = "select * from evento_efetivadas 
						where ef_curso = '$curso' and 
						ef_inscrito= '$user' and ef_status = 'A'
				";
				$rlt = db_query($sql);
				if (!($line = db_read($rlt)))
					{
					$data = date("Ymd");
					$boleto = '';
					$boleto = $this->boleto_emite($user,$valor);
					
					if (strlen($boleto) > 15)
						{
							echo '<font color="red">';
							echo $boleto;
							echo '</font>';
							
							$pos = strpos($boleto,'pessoa cadastrada Nome:');
							$nome = trim(substr($boleto,$pos+23,100));
							$xpos = strpos($nome,'CPF:');
							if ($xpos > 0)
								{ $nome = trim(substr($nome,0,strpos($nome,'CPF:'))); }
							$xpos = strpos($nome,'-');
							if ($xpos > 0)
								{ $nome = trim(substr($nome,0,strpos($nome,'-'))); }
							
							$xpos = strpos($boleto,':(');
							if ($xpos > 0)
								{
									{
										$nome = trim(substr($nome,0,strpos($nome,':(')));
										$nome = substr($nome,strpos($nome,')',100)); 
									}
								}								
							if (strlen($nome) > 0)
								{			
								$sql = "update evento_cadastro
										set ec_nome = '$nome'
										where id_ec = $user
								";
								echo '<HR>'.$sql.'<HR>';
								$rlt = db_query($sql);
								$boleto = $this->boleto_emite($user,$valor);								
								} else {
									return(0);
									exit;
								}							
						}					
					if (strlen($boleto) > 15)
						{
							echo '<font color="red">';
							echo $boleto;
							echo '</font>';
							return(0);
						}
						
					$sql = "insert into evento_efetivadas
							(
								ef_curso, ef_data, ef_inscrito,
								ef_boleto, ef_valor, ef_status,
								ef_journal 
							) values (
								'$curso',$data,'$user',
								'$boleto',$valor,'A',
								$jid
							)
					";
					$rlt = db_query($sql);
					echo 'SALVADO';
					}
				}
			
			}
		
		function inscricoes()
			{
				global $jid,$path,$dd;
				
				$page = http.'pb/'.page().'/'.$path.'?dd99=inscricao4';
				
				$sql = "select * from ";
				$data = date("Ymd");
				if (strlen($dd[3]) > 0)
					{
						$this->realiza_inscricao($dd[3]);
					} 
				
				//inscricoes
				echo '<H2>Inscrições</h2>';
				$sql = "select * from evento_cursos 
						where ev_de <= $data and ev_ate >= $data
						and ec_journal = $jid
						order by ev_codigo 
				";
				$rlt = db_query($sql);
				$sx = '';
				$sx .= $this->minhas_inscricoes();
				
				$sx .= '<form method="post" action="'.$page.'">';
				while ($line = db_read($rlt))
				{
					$sx .= '<input type="radio" name="dd3" value="'.$line['ev_codigo'].'">';
					$sx .= $line['ev_nome'];
					$sx .= '(até '.stodbr($line['ev_ate']).') R$ '.number_format($line['ev_preco'],2);
					$sx .= ' no boleto bancário.';
					$sx .= '<BR>';
				}
				$sx .= '<input type="submit" value="confirmar inscrição" class="botao-normal">';
				$sx .= '</form>';
				//exit;
				//$sql = "delete from evento_cursos where id_ev = 2";
				//$rlt = db_query($sql);
				//$sql = "delete from evento_cursos";
				//$rlt = db_query($sql);
				//echo $sql;
				echo $sx;
			}
		
		function le($id=0)
			{
				$sql = "select * from ".$this->tabela." 
					where id_ec = ".round($id);
				$rlt = db_query($sql);
				if ($line = db_read($rlt))
					{
						$this->line = $line;
					}
				return('');
			}
		
		function mostra()
			{
				$line = $this->line;
				$sx = '';
				$sx .= '<table width="100%">';
				$sx .= '<TR valign="top">';
				$sx .= '<TD><B>'.$line['ec_nome'].'</B>';
				$sx .= '<BR>'.$line['ec_cpf'];
				$sx .= '<BR>'.$line['ec_email_1'];
				$sx .= '<BR>'.$line['ec_email_2'];
				$sx .= '<BR>'.$line['ec_tele_1'];
				$sx .= '<BR>'.$line['ec_tele_2'];
				$sx .= '<TD><B>Endereço</B><BR>'.$line['ec_endereco'].'';
				$sx .= ', '.$line['ec_numero'];
				$sx .= ' '.$line['ec_complemento'];
				$sx .= '<BR>'.$line['ec_bairro'];
				$sx .= '<BR>'.$line['ec_cidade'];
				$sx .= '</table>';
						
				return($sx);
			}
		
		function valida_usuario()
			{
				global $dd;
				if ((strlen($dd[1]) > 0) and (strlen($dd[2]) > 0))
					{
						$ok = $this->login($dd[1],$dd[2]);				
					}
			}
		
		function login($user,$pass)
			{
				$sql = "select * from ".$this->tabela." ";
				$rlt = db_query($sql);
				while ($line = db_read($rlt))
					{
						$sql = "update ".$this->tabela." set ec_email_1 = '".lowercase($line['ec_email_1'])."' where id_ec = ".round($line['id_ec']);
						$xrlt = db_query($sql);
					}
				$sql = "select * from ".$this->tabela." 
						where ec_email_1 = '".lowercase($user)."' 
				";
				$rlt = db_query($sql);
				if ($line = db_read($rlt))
					{
						$senha = md5(trim($line['ec_senha']));
						if (($senha == $pass) or ($senha == md5($pass)))
							{
								$this->erro = 1;
								$_SESSION['id_ec'] = $line['id_ec'];
								$_SESSION['ec_nome'] = $line['ec_nome'];
							} else {
								$this->erro = -2;
								$this->erro_msg = 'Senha incorreta';
							}
					} else {
						$this->erro = -1;
						$this->erro_msg = 'e-mail não localizado';
					}
				return($this->erro);
			}		
		function cp()
			{
				global $dd,$acao;
								
				if ((strlen(trim($dd[0]))==0) and (strlen($dd[8]) > 0))
					{
						$sql = "select * from ".$this->tabela." where
							ec_email_1 = '".$dd[8]."' 
						";
						$rlt = db_query($sql);
						if ($line = db_read($rlt))
							{
								$message = '<font color="red">e-mail '.$dd[8].' já existe no sistema</font>';
								$dd[8] = '';
							}
					}
				/* Estados */
				$op .=' : ';
				$op .='&1:Acre (AC)';
				$op .='&2:Alagoas (AL)';
				$op .='&3:Amapá (AP)';
				$op .='&4:Amazonas (AM)';
				$op .='&5:Bahia (BA)';
				$op .='&6:Ceará (CE)';
				$op .='&7:Distrito Federal (DF)';
				$op .='&8:Espírito Santo (ES)';
				$op .='&10:Goiás (GO)';
				$op .='&11:Maranhão (MA)';
				$op .='&12:Mato Grosso (MT)';
				$op .='&13:Mato Grosso do Sul (MS)';
				$op .='&14:Minas Gerais (MG)';
				$op .='&15:Pará (PA)';
				$op .='&16:Paraíba (PB)';
				$op .='&17:Paraná (PR)';
				$op .='&18:Pernambuco (PE)';
				$op .='&19:Piauí (PI)';
				$op .='&20:Rio de Janeiro (RJ)';
				$op .='&21:Rio Grande do Norte (RN)';
				$op .='&22:Rio Grande do Sul (RS)';
				$op .='&23:Rondônia (RO)';
				$op .='&9:Roraima (RR)';
				$op .='&24:Tocantins (TO)';
				$op .='&25:Santa Catarina (SC)';
				$op .='&26:São Paulo (SP)';
				$op .='&27:Sergipe (SE)';
				
				$cp = array();
				array_push($cp,array('$H8','id_ec','',False,True));
				array_push($cp,array('$H8','ec_codigo','',False,True));
				
				array_push($cp,array('${','','Dados Pessoais',False,True));
				array_push($cp,array('$S100','ec_nome_inscrito','Nome completo',True,True));
				array_push($cp,array('$S10','ec_rg','RG',False,True));
				array_push($cp,array('$D8','ec_nascimento','Data de nascimento',True,True));
				array_push($cp,array('$}','','Dados Pessoais',False,True));
				
				array_push($cp,array('${','','Contato eletrônico e telefônico',False,True));
				array_push($cp,array('$S60','ec_email_1','e-mail principal',True,True));
				array_push($cp,array('$M1','','-',False,True));
				array_push($cp,array('$S60','ec_email_2','e-mail alter.',False,True));				
				array_push($cp,array('$P20','ec_senha','senha para acesso',True,False));
				array_push($cp,array('$S14','ec_tele_1','Celular (ddd)nnnn.nnnn',True,True));
				array_push($cp,array('$S14','ec_tele_2','Telefone (ddd)nnnn.nnnn',False,True));
				array_push($cp,array('$}','','',False,True));
				
				array_push($cp,array('${','','Instituição afiliada',False,True));
				array_push($cp,array('$S80','ec_instituicao','Nome da instituição',True,True));
				array_push($cp,array('$}','','',False,True));
				
				array_push($cp,array('${','','Dados para Faturamento',False,True));
				array_push($cp,array('$S100','ec_nome','Faturamento para',True,True));
				array_push($cp,array('$CPF','ec_cpf','CPF/CNPJ',True,True));
				array_push($cp,array('$S50','ec_endereco','Endereço',True,True));
				array_push($cp,array('$S10','ec_numero','Número',False,True));
				array_push($cp,array('$S20','ec_complemento','Complemento',False,True));
				array_push($cp,array('$S20','ec_bairro','Bairro',True,True));
				array_push($cp,array('$S20','ec_cidade','Cidade',True,True));
				array_push($cp,array('$CEP','ec_cep','CEP',True,True));
				array_push($cp,array('$O '.$op,'ec_estado','Estado',True,True));
				array_push($cp,array('$O 1:Brasil','ec_pais','Pais',True,True));
				array_push($cp,array('$}','','',False,True));
				$cp[9][2] = $message;
				array_push($cp,array('$B8','','Cadastrar usuário >>>',False,True));
				
				return($cp);
			}
		function strucutre()
			{
				$sql = "alter table evento_cadastro add ec_instituicao char(100)";
				//$rlt = db_query($sql);
				
				$sql = "create table evento_efetivadas
					( 
						id_ef serial not null,
						ef_curso char(5),
						ef_data integer,
						ef_inscrito char(7),
						ef_boleto char(20),
						ef_valor float,
						ef_status char(1),
						ef_journal integer
					)";
				//$rlt = db_query($sql);
				
				$sql = "create table evento_cursos
					( 
						id_ev serial not null,
						ev_codigo char(5),
						ev_nome char(50),
						ev_preco float,
						ev_de integer,
						ev_ate integer,
						ec_journal integer
					)";
				//$rlt = db_query($sql);
				
				$sql = "create table inscricoes 
					( 
						id_ec serial not null,
						ec_codigo char(7),
						ec_nome char(100),
						ec_instituicao char(100),
						ec_cpf char(20),
						ec_rg char(20),
						ec_cep char(10),
						ec_endereco char(50),
						ec_numero char(10),
						ec_complemento char(20),
						ec_bairro char(20),
						ec_cidade char(20),
						ec_estado int8,
						ec_pais int8,
						ec_nascimento int8,
						ec_ativo int8,
						ec_email_1 char(60),
						ec_email_2 char(60),
						ec_tele_1 char(14),
						ec_tele_2 char(14)					
					)				
				";
				//$rlt = db_query($sql);
				return(1);		
				
			}
		function login_form()
			{ 
				global $dd,$acao,$path,$jid,$LANG;
				$bbt = 'Entrar >>';
				
				$div2_style = 'style="display: none;" ';
				$div3_style = 'style="display: none;" ';
				
				if (strlen($this->erro) > 0) { $div3_style = ''; }
				$sx .= '<h2>Inscrições</h2>';
				$page = http.'pb/'.page().'/'.$path.'?dd99=inscricao2';
				$page3 = http.'pb/'.page().'/'.$path.'?dd99=inscricao3';
				$sx .= '
				<div id="div_login" >
					<form method="post" action="'.$page.'">
					<table align="center" class="login">
						<TR><TD align="center" colspan=2 class="login_cab"><B>Entrar no Sistema</B>
						<TR><TD>Informe seu e-mail
							<TD><input type="text" name="dd1" value="'.$dd[1].'" size="35" maxsize="100" class="input_stly"></TD>
						</TR>
						<TR><TD><TD><div id="div_erro" '.$div3_style.'><center><font class="login_erro">'.$this->erro_msg.'</div>
						<TR><TD>Informe sua senha
							<TD align="left"><input type="password" name="dd2" size="20" maxsize="20"class="input_stly"></TD>
						</TR>
						<TR><TD><TD colspan=1 align="left">
							<input type="submit" name="acao" value="'.$bbt.'"></TD>
						</TR>
					<TR><TD></TD><TD>
						<A href="javascript:newxy2(\'login_password_send.php?dd1=\',400,200)" class="lt0h">
						Esqueci minha senha</A>
						&nbsp;|&nbsp;
						<A href="'.$page3.'" class="lt0h">
						Quero me cadastrar</A>
						</TD></TR>
					</table>
				</form>
		
				</div>
					<div id="div_msg" '.$div2_style.'>Usuário não logado</div>
					
				</div>
				
				<style>
					.login_erro { color: red; font-size: 14px; text-align: center; }
				</style>
				';				
				return($sx);
			}
		function updatex()
			{
				global $base;
				$c = 'ec';
				$dx2 = 'id_'.$c;
				$dx1 = $c.'_codigo';
				$dx3 = 7;
				$sql = "update ".$this->tabela." set ".$dx1." = lpad(".$dx2.",".$dx3.",0) where ".$dx1." = '' ";
				if ($base="pgsql")
					{ $sql = "update ".$this->tabela." set ".$dx1."=trim(to_char(".$dx2.",'".strzero(0,$dx3)."')) where (length(trim(".$dx1.")) < ".$dx3.") or (".$dx1." isnull);";}
				
				$rlt = db_query($sql);	
			return(1); 
			}
	}
?>