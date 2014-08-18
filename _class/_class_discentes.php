<?
//$sql = "ALTER TABLE pibic_aluno ADD COLUMN pa_curso_cod char(7)";
//$rlt = db_query($sql);

class discentes
	{
	var $id_pa;
	var $pa_nome;
	var $pa_nasc;
	var $pa_codigo;
	var $pa_cracha;
	var $pa_login;
	var $pa_escolaridade;
	var $pa_titulacao;
	var $pa_carga_semanal;
	var $pa_ss;
	var $pa_cpf;
	var $pa_negocio;
	var $pa_centro;
	var $pa_curso;
	var $pa_telefone;
	var $pa_celular;
	var $pa_lattes;
	var $pa_email;
	var $pa_email_1;
	var $pa_senha;
	var $pa_endereco;
	var $pa_afiliacao;
	var $pa_ativo;
	var $pa_grestudo;
	var $pa_prod;
	var $pa_ass;
	var $pa_instituicao;
	
	var $ss_ano_entrada;
	var $ss_ano_saida;
	var $ss_modalidade;
	var $ss_modalidade_nome;
	
	var $line;
	
	var $tabela = 'pibic_aluno';
	

	function sem_genero()
		{
			$sql = "select pa_nome, id_pa from ".$this->tabela." 
				where pa_genero = '' or pa_genero isnull
				and pa_nome <> ''
				order by pa_nome
				limit 20
			";
			$rlt = db_query($sql);
			while ($line = db_read($rlt))
				{
					$tp = $_GET["id".trim($line['id_pa'])];
					if (strlen($tp) > 0)
					{
						$sql = "update ".$this->tabela." set pa_genero = '".$tp."' where id_pa = ".round($line['id_pa']);
						$xrlt = db_query($sql);
					}
				}
			
			$sql = "select pa_nome, id_pa from ".$this->tabela." 
				where pa_genero = '' or pa_genero isnull
				and pa_nome <> ''
				order by pa_nome
				limit 20
			";
			$rlt = db_query($sql);
			$sx = '<table width="800">';
			$sx .= '<TR><TD><form method="get" action="'.page().'">';
			$sx .= '<TR><TD colspan=4><h2>Estudantes sem gênero</h2>';
			$sx .= '<TR><TH width="5%">Masc.<TH width="5%">Fem.<TH>Nome';
			while ($line = db_read($rlt))
			{
				$sx .= '<TR>';
				$sx .= '<TD>';
				$sx .= '<input type="radio" name="id'.$line['id_pa'].'" value="M">';
				$sx .= '<TD>';
				$sx .= '<input type="radio" name="id'.$line['id_pa'].'" value="F">';
				$sx .= '<TD>';
				$sx .= trim($line['pa_nome']);
			}
			$sx .= '</table>';
			$sx .= '<input type="submit" value="gravar >>"';
			return($sx);
		}
	
	function limpar_aluno_sem_codigo()
		{
			$sql = "delete from ".$this->tabela." where pa_cracha = '' or pa_cracha = '0' ";
			$rlt = db_query($sql);
			return(1);
		}
	
	function mostra_comentario($comentario)
		{
			$com = trim($comentario);
			if (strlen($com) > 0)
				{
					$sx = '<img src="../img/icone_blog.png" height="20" border=0 title="'.$com.'">';
				}
			return($sx);
		}
	
	function mostra_datas($data)
		{
			if ($data < 20000101)
				{ $data= 'não informado'; }
			else 	
				{ $data= stodbr($data); }
			return('<B>'.$data.'</B>');
		}

	function mostra_idioma($tpd)
		{
			$tpd = trim($tpd);
			$td = '(não definido '.$tpd.')';
			if ($tpd == 'en_US') { $td = 'Inglês'; }
			if ($tpd == 'en') { $td = 'Inglês'; }
			if ($tpd == 'es') { $td = 'Espanhol'; }
			if ($tpd == 'ge') { $td = 'Alemão'; }
			if ($tpd == 'it') { $td = 'Italiano'; }
			return('<B>'.$td.'</B>');
		}
	function mostra_isencao($is)
		{
			$ist = '';
			if ($is=='CPS') { $ist = 'CAPES'; }
			return($ist);
		}
	function mostra_linha_pesq($linha,$id = 0)
		{
			global $user,$perfil,$line;
			
			if (strlen($linha)==0)
				{ $linha = '<I>Não definida</I>'; }
				$linha = '<B>'.$linha.'</B>';
			if (($perfil->valid('#CPS')) and ($id > 0))
				{
					$linha .= ' ';
					$linha .= '<span class="screen">';
					$linha .= '<A HREF="discente_alterar_linha.php?dd0='.$id.'&dd90='.checkpost($id).'" class="link">';
					$linha .= msg('alterar');
					$linha .= '</A>';
					$linha .= '</span>';
				}				
			return($linha);
		}
	function formacao_pos($cracha)
		{
			global $user,$perfil;
			$sql = "select * from docente_orientacao
					left join pibic_professor on pp_cracha = od_professor
					left join programa_pos on od_programa = pos_codigo  
					left join programa_pos_linhas on od_linha = posln_codigo
					where od_aluno = '$cracha'
					order by od_ano_ingresso desc
			";
			$rlt = db_query($sql);
			$sx = '<table width="100%" class="tabela00">';
			$sx .= '<TR><TD colspan=5><h3>'.msg('formacao_pos').'</h3>';
			$sx .= '<TR>';
			$sx .= '<TH width=25%>'.msg('orientador');
			$sx .= '<TH width=10%>'.msg('modalidade');
			$sx .= '<TH width=10%>'.msg('entrada');
			$sx .= '<TH width=10%>'.msg('egresso');
			$sx .= '<TH width=20%>'.msg('programa_pos');
			$sx .= '<TH width=20%>'.msg('linha');
			$sx .= '<TH width=5%>'.msg('see');
			while ($line = db_read($rlt))
				{
					$sx .= '<TR>';
					$sx .= '<TD class="tabela01">';
					$sx .= $line['pp_nome'];
					
					$sx .= '<TD class="tabela01">';
					$sx .= msg('mod_'.$line['od_modalidade']);
					
					$anoi = substr($line['od_ano_ingresso'],0,4);
					if ($anoi < 1950) { $anoi = ' - '; } 
					$sx .= '<TD class="tabela01" align="center">';
					$sx .= $anoi;	
					
					$anof = substr($line['od_ano_diplomacao'],0,4);
					if ($anof < 1950) { $anof = ' cursando '; } 
					$sx .= '<TD class="tabela01" align="center">';
					$sx .= $anof;
									
					$sx .= '<TD class="tabela01">';
					$sx .= $line['pos_nome'];
					
					$sx .= '<TD class="tabela01">';
					$sx .= $line['posln_descricao'];		
					$sx .= '&nbsp;';			

					$sx .= '<TD class="tabela01">';
					$sx .= '<span class="link" id="pos'.$line['id_od'].'">';
					$sx .= msg('more').'...';
					$sx .= '</span>';		
					$sx .= '&nbsp;';
					
					$sx .= '<tr><TD colspan=10 class="tabela01">';
					$sx .= '<div id="pos'.$line['id_od'].'a" style="display: none;">';
						$sx .= '<table width="100%">';
						
						$sx .= '<TR><TD>Linha de pesquisa: '.$this->mostra_linha_pesq($line['posln_descricao'],$line['id_od']).'';
						
						$sx .= '<TR><TD>Data qualificação: '.$this->mostra_datas($line['od_qualificacao']);
						if ($line['od_modalidade']=='D')
							{ $sx .= '<TD>Exame proficiência em '.$this->mostra_idioma($line['od_idioma_1_tipo']).' em '.$this->mostra_datas($line['od_idioma_1']); }
						
						
						$sx .= '<TR><TD>Data integralização créditos: '.$this->mostra_datas($line['od_creditos']);
						$sx .= '<TD>Exame proficiência em '.$this->mostra_idioma($line['od_idioma_2_tipo']).' em '.$this->mostra_datas($line['od_idioma_2']);
						
						$sx .= '<TR><TD>Data submissão/publicação do artigo: '.$this->mostra_datas($line['od_artigo']);
						$sx .= '<TR><TD>Data defesa: '.$this->mostra_datas($line['od_defesa']);
						
						$bolsa = trim($line['od_bolsa']);
						if (strlen($bolsa) > 0)
							{
								$sx .= '<TD>Bolsa / isenção: <B>'.$this->mostra_isencao($bolsa).'</B>';
							}
	
						
						$sx .= '<TR><TD colspan=2 class="lt1">';
						$sx .= 'Título da tese/dissertação: <B>'.$line['od_titulo_projeto'].'</B>';
						
						$obs = trim($line['od_obs']);
						if (strlen($obs) > 0)
							{ $sx .= '<TR><TD colspan=2><B>'.$line['od_obs'].'</B>'; }

						if ($perfil->valid('#SEP'))
							{
							$sx .= '<TR><TD colspan=2 align="right"><span class="screen">';
							$sx .= '<a href="discente_fluxo_edit.php?dd0='.$line['id_od'].'&dd90='.checkpost($line['id_od']).'" class="link">editar</A>';
							$sx .= '</span>';
							}
						$sx .= '</tr>';
					$sx .= '</table>';
					$sx .= '</div>';
					
					$sx .= '
					<script>
						$("#pos'.$line['id_od'].'").click(function()
							{
								$("#pos'.$line['id_od'].'a").show();
							});
					</script>
					';
				}
			$sx .= '</table>';
			return($sx);
		}
	
	function documentos_pessoais($cracha)
		{
			$cpf = trim($this->line['pa_img_cpf']);
			$res = trim($this->line['pa_img_residencia']);
			$rg  = trim($this->line['pa_img_rg']);
			
			$sx = 'Documentos: ';
			$sx .= '<A HREF="'.$cpf.'" title="documento de CPF">CPF</A>';
			$sx .= ' | ';
			$sx .= '<A HREF="'.$rg.'" title="documento de CPF">RG</A>';
			$sx .= ' | ';
			$sx .= '<A HREF="'.$res.'" title="documento de CPF">Residência</A>';
			
			return($sx);
		}	
	
	function relatorio_escolas_bolsas($ano,$escola='',$modalidade)
		{
			$sql = "select count(*) as total, pibic_aluno.pa_centro as pa_centro, pibic_aluno.pa_curso as pa_curso from pibic_bolsa_contempladas ";
			$sql .= "left join pibic_aluno on pb_aluno = pa_cracha ";
//$sql .= "left join pibic_professor on pb_professor = pp_cracha ";
//$sql .= "left join pibic_submit_documento on doc_protocolo = pb_protocolo_mae ";
//$sql .= "left join pibic_edital on pb_protocolo = pee_protocolo ";
			$sql .= "where pb_ano = '".$ano."' ";
			if (strlen($dd[0]) > 0)
				{ $sql .= " and (pb_tipo = '".$modalidade."' )"; }
				$sql .= " and pb_status = 'A' "; 	
				$sql .= " group by pa_centro, pa_curso";
				$sql .= " order by pa_centro, pa_curso"; 
				
			$rlt = db_query($sql);
			
		}
	
	function orientador_da_discente($estudante)
		{
			$sql = "select * from docente_orientacao
						where od_aluno = '".$estudante."' order by od_modalidade ";
						
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					$this->ss_ano_entrada = $line['od_ano_ingresso'];
					$this->ss_ano_saida = $line['od_ano_diplomacao'];
					$this->ss_modalidade = $line['od_modalidade'];
					$this->ss_status = $line['od_status'];
					$mod_nome = $line['od_modalidade'];
					if ($mod_nome == 'D') { $mod_nome = 'Doutorado'; }
					if ($mod_nome == 'M') { $mod_nome = 'Mestrado'; }
					$this->ss_modalidade_nome = $mod_nome; 
					
				}
		}	
	
	function limpar_turno_curso_estudante()
		{
			$sql = "select * from ".$this->tabela." where pa_curso like '%(%' ";
			$sql .= "limit 1";
			$rlt = db_query($sql);
			$tot = 0;
			if ($line = db_read($rlt))
			{
				$tot = 0;
				$curso = trim($line['pa_curso']);
				$pos = strpos($curso,'(');
				if ($pos > 0)
					{
						$curso2 = substr($curso,0,$pos);
						echo $curso.'<BR>'.$curso2;
						echo '<HR>';
						
						$sql = "update ".$this->tabela." set pa_curso = '".$curso2."' where pa_curso = '".$curso."' ";
						$rltz = db_query($sql); 
					}
			}
		}
	
	function valida_set()
		{
			$_SESSION['pa_nome'] = $this->pa_nome;
			$_SESSION['pa_cracha'] = trim($this->pa_cracha);
			$_SESSION['oa_data'] = date("YmdHis");
			return(1);
		}


	function valida()
		{
			$cracha = $_SESSION['pa_cracha'];
			if (strlen(trim($cracha)) == 8)
				{
					$this->le('',$cracha);
					return(1);
				} else {
					return(0);
				}
			return(0);
		}
	function cp_linha_de_pesquisa($programa)
		{
			global $dd,$tabela;
			$cp = array();
			array_push($cp,array('$H4','id_od','id_od',False,True,''));
			array_push($cp,array('$Q posln_descricao:posln_codigo:select * from programa_pos_linhas where posln_programa = \''.$programa.'\'','od_linha','Linha de Pesquisa',False,True,''));
			return($cp);
		}		
	function cp_formacao()
		{
			global $dd,$tabela;
			$oidio = ' : &en:Inglês&es:Espanhol&it:Italiano';
			$tabela = 'docente_orientacao';
			$programa = $dd[12];
			$cp = array();
			array_push($cp,array('$H4','id_od','id_od',False,True,''));
			array_push($cp,array('$D8','od_ano_ingresso','Entrada',True,True,''));
			array_push($cp,array('$D8','od_ano_diplomacao','Diplomação',False,True,''));
			array_push($cp,array('$M','','Informe 0 (zero) se não estiver concluído ou não identificado',False,True,''));
			
			array_push($cp,array('$S8','od_professor','Orientador',False,True,''));
			
			$stt = array('A'=>'Matriculado sem orientador','C'=>'Cursando','T'=>'Titulado','R'=>'Desistente','G'=>'Aguardando entrega da Tese/Dissertação','D'=>'Desligado','N'=>'Trancado');
			$ops = ' : ';
			$ops .= '&A:'.$stt['A'];
			$ops .= '&C:'.$stt['C'];
			$ops .= '&T:'.$stt['T'];
			$ops .= '&R:'.$stt['R'];
			$ops .= '&G:'.$stt['G'];
			$ops .= '&F:'.$stt['D'];
			$ops .= '&N:'.$stt['N'];
			array_push($cp,array('$O '.$ops,'od_status','Situação atual',True,True,''));
			
			array_push($cp,array('${','','Dados do aluno',False,True,''));
			array_push($cp,array('$T80:5','od_titulo_projeto','Título da Tese/Dissertação',False,True,''));
			array_push($cp,array('$Q bs_descricao:bs_codigo:select * from bolsa_pos_tipo order by bs_descricao','od_bolsa','Bolsa/Isenção',False,True,''));
			array_push($cp,array('$D8','od_qualificacao','Data Qualificação',False,True,''));
			array_push($cp,array('$D8','od_defesa','Data da Defesa',False,True,''));
			array_push($cp,array('$D8','od_artigo','Data entrega Artigo/Submissão',False,True,''));
			array_push($cp,array('$D8','od_creditos','Data da integralização dos créditos',False,True,''));
			array_push($cp,array('$}','','',False,True,''));
			
			array_push($cp,array('${','','Proficiência de Idioma(s)',False,True,''));
			array_push($cp,array('$D8','od_idioma_1','Proficiência (Mestrado)',False,True,''));
			array_push($cp,array('$O '.$oidio,'od_idioma_1_tipo','no idioma',False,True,''));
			array_push($cp,array('$D8','od_idioma_2','Proficiência (Doutorado)',False,True,''));
			array_push($cp,array('$O '.$oidio,'od_idioma_2_tipo','no idioma',False,True,''));
			array_push($cp,array('$}','','',False,True,''));
			
			
			array_push($cp,array('${','','Modalidade do Curso',False,True,''));
			array_push($cp,array('$O : &M:Mestrado&D:Doutorado&P:Mestrado Profissionalizante','od_modalidade','Modalidade',False,True,''));
			//array_push($cp,array('$Q posln_descricao:posln_codigo:select * from programa_pos_linhas where posln_programa = \''.$programa.'\'','od_linha','Linha de Pesquisa',False,True,''));
			array_push($cp,array('$}','','',False,True,''));
			
			array_push($cp,array('$T80:3','od_obs','Observações',False,True,''));
			
			return($cp);						
		}
		
	function cp()
		{
			global $dd;
			$cp = array();
			if (strlen($dd[4])==0) { $dd[4] = UpperCaseSql($dd[3]); }
			array_push($cp,array('$H4','id_pa','id_pa',False,True,''));
			array_push($cp,array('$S8','pa_cracha','Cracha',True,True,''));
			array_push($cp,array('$A','','Dados do Aluno',False,True,''));
			array_push($cp,array('$S100','pa_nome','Nome completo do aluno',True,True,''));
			array_push($cp,array('$S100','pa_nome_lattes','Nome no lattes',True,True,''));

			//array_push($cp,array('$H8','pa_nome_asc','Nome ASC',False,False,''));
			//array_push($cp,array('$Q ap_tit_titulo:ap_tit_codigo:select * from apoio_titulacao order by ap_tit_titulo','pa_titulacao','Titulacao',True,True,''));
		
			//array_push($cp,array('$O N:NÃO&S:SIM','pa_ss','Ativo',True,True,''));
	
			array_push($cp,array('$S18','pa_cpf','CPF',True,True,''));
			array_push($cp,array('$S18','pa_rg','RG',True,True,''));
			array_push($cp,array('$S40','pa_escolaridade','Escolaridade',True,True,''));
			array_push($cp,array('$S80','pa_curso','Curso',True,True,''));
			array_push($cp,array('$S80','pa_centro','Centro',True,True,''));

			//array_push($cp,array('$I8','pa_carga_semanal','Carga horaria',True,True,''));
		
			array_push($cp,array('$A','','Filiação',False,True,''));
			array_push($cp,array('$S100','pa_pai','Nome do pai',False,True,''));
			array_push($cp,array('$S100','pa_mae','Nome da mae',False,True,''));
			array_push($cp,array('$D8','pa_nasc','Data nascimento',False,True,''));
			array_push($cp,array('$O : &M:Masculino&F:Feminino','pa_genero','Genero',False,True,''));
			
			

			array_push($cp,array('$A','','Formas de contato',False,True,''));
			array_push($cp,array('$S20','pa_telefone','Telefone<BR><font class="lt0">(xx)0000.0000',False,True,''));
			array_push($cp,array('$S20','pa_celular','Celular<font class="lt0"><BR>(xx)0000.0000',False,True,''));
	
			array_push($cp,array('$A','','Endereço',False,True,''));
			array_push($cp,array('$T60:5','pa_endereco','Endereço',False,True,''));
	
			array_push($cp,array('$S100','pa_email','e-mail',False,True,''));
			array_push($cp,array('$S100','pa_email_1','e-mail (alternativo)',False,True,''));

			array_push($cp,array('$A','','Dados bancários',False,True,''));
			array_push($cp,array('$S3','pa_cc_banco','N. banco',False,True,''));
			array_push($cp,array('$O : &001:001&023:023','pa_cc_mod','Mod (p/Caixa)',False,True,''));
			array_push($cp,array('$S6','pa_cc_agencia','Agência',False,True,''));
			array_push($cp,array('$S15','pa_cc_conta','N. Conta',False,True,''));
			array_push($cp,array('$O N:Conta corrente Individual&P:Conta Poupança','pa_cc_tipo','Tipo de conta',False,True,''));

			array_push($cp,array('$A','','Lattes',False,True,''));
			array_push($cp,array('$S100','pa_lattes','Link para Lattes',False,True,''));

			array_push($cp,array('$A','','Bolsa Anterior',False,True,''));
			array_push($cp,array('$O  : - Nada&C:CNPq&F:Fundação Araucária&P:PUCPR&I:ICV','pa_bolsa_anterior','Bolsa (anterior)',False,True,''));
			array_push($cp,array('$O  : - Nada&C:CNPq&F:Fundação Araucária&P:PUCPR&I:ICV','pa_bolsa','Bolsa atual',False,True,''));

			array_push($cp,array('$Q ci_nome:ci_codigo:select * from ca_instituicao where ci_ativo=1 and ci_codigo = '.chr(39).'0000002'.chr(39).' order by ci_nome','pa_afiliacao','Afiliação Institucional',True,True,''));

			array_push($cp,array('$HV','pa_nome_asc',UpperCaseSQL($dd[3]),False,True,''));
			
			array_push($cp,array('$A','','Informações complementares',False,True,''));
			array_push($cp,array('$C1','pa_blacklist','Blacklist',False,False,''));
			array_push($cp,array('$T60:5','pa_obs','Observações',False,True,''));
			
			//$sql = "alter table pibic_aluno add column pa_blacklist char(1)";
			//$rlt = db_query($sql);
			
			//$sql = "alter table pibic_aluno add column pa_obs text";
			//$rlt = db_query($sql);
			
			return($cp);
		}
		
	function consulta($cracha,$force=0)
		{
			/* Habilita consulta */
			$consulta = True;
			$debug  = True;
			$cracha = sonumero($cracha);
			if (strlen($cracha) < 8) { return(0); }
			if (strlen($cracha) == 12) { $cracha = substr($cracha,3,8); }
			if (strlen($cracha) == 11) { $cracha = substr($cracha,3,8); }

			$ssql = "select * from pibic_aluno ";
			$ssql .= " where pa_cracha = '".$cracha."' ";
			$rrlt = db_query($ssql);
			if ($rline = db_read($rrlt))
				{
				$data = substr($rline['pa_update'],0,6);
				/* Se jï¿½ foi consultado no dia nï¿½o realiza nova consulta */
				if (($data == date("Ym")) and ($force != '1'))
					{
					$consulta = False;
					$rst = True;
					return(1);
					}
				}	

			if ($consulta == true) { $this->consulta_pucpr($cracha); return(1); } 
		}
		
	function row()
			{
				global $cdf,$cdm,$masc;
				$cdf = array('id_pa','pa_nome','pa_cracha','pa_centro','pa_curso');
				$cdm = array('cod',msg('nome'),msg('cracha'),msg('centro'),msg('curso'));
				$masc = array('','','','','','','','');
				return(1);				
			}		
	function le_programa_pos($id)
		{
			$sql = "select od_programa from docente_orientacao where id_od = ".round($id);
			$rlt = db_query($sql);
			$line = db_read($rlt);
			return($line['od_programa']);			
		}
	function le_fluxo($id)
		{
			$sql = "select od_aluno from docente_orientacao where id_od = ".round($id);
			$rlt = db_query($sql);
			$line = db_read($rlt);
			
			$this->pa_cracha = $line['od_aluno'];
			return($line['od_aluno']);
		}
	function le($id='',$cracha='')
		{
			if (strlen($id) > 0) { $this->id_pa = $id; }
			if (strlen($cracha) > 0) { $this->pa_cracha = $cracha; }
			$sql = "select * from ".$this->tabela." where ";
			if (strlen($this->id_pa) > 0)
				{ $sql .= " id_pa = ".round($this->id_pa); }
			else
				{ $sql .= " pa_cracha = '".($this->pa_cracha)."'"; }
			$rlt = db_query($sql);
			
			if ($line = db_read($rlt))
				{
					$this->id_pa= $line['id_pa'];
					$this->pa_nome= $line['pa_nome'];
					$this->pa_nasc= $line['pa_nasc'];
					$this->pa_codigo= $line['pa_codigo'];
					$this->pa_cracha= $line['pa_cracha'];
					$this->pa_login= $line['pa_login'];
					$this->pa_escolaridade= $line['pa_escolaridade'];
					$this->pa_titulacao= $line['pa_titulacao'];
					$this->pa_carga_semanal= $line['pa_carga_semanal'];
					$this->pa_ss= $line['pa_ss'];
					$this->pa_cpf= $line['pa_cpf'];
					$this->pa_negocio= $line['pa_negocio'];
					$this->pa_centro= $line['pa_centro'];
					$this->pa_curso= $line['pa_curso'];
					$this->pa_telefone= $line['pa_telefone'];
					$this->pa_celular= $line['pa_celular'];
					$this->pa_lattes= $line['pa_lattes'];
					$this->pa_email= $line['pa_email'];
					$this->pa_email_1= $line['pa_email_1'];
					$this->pa_senha= $line['pa_senha'];
					$this->pa_endereco= $line['pa_endereco'];
					$this->pa_afiliacao= $line['pa_afiliacao'];
					$this->pa_ativo= $line['pa_ativo'];
					$this->pa_grestudo= $line['pa_grestudo'];
					$this->pa_prod= $line['pa_prod'];
					$this->pa_ass= $line['pa_ass'];
					$this->pa_instituicao= $line['pa_instituicao'];
					
					$this->line = $line;
					return(1);					
				}
			return(0);
		}

	function consulta_pucpr($cracha)
		{
			global $secu;
			require("../pibicpr2/_pucpr_login.php");
			if ($cracha != '00000000') {
				require_once('../include/nusoap/nusoap.php'); 
				/* Objeto de consulta do WebService */
			$client = new soapclient('https://portalintranet.pucpr.br:8081/servicePibic?wsdl');
			$client->setCredentials($user, $pass);
			
			$err = $client->getError();
			if ($err) { echo '<h2>Constructor error</h2><pre>' . $err . '</pre>'; } 
			$param = array('arg0' => $cracha);
			$result = $client->call('pesquisarPorCodigo', $param,'http://consultas.servicos.apc.br/', '', false, true);

			/* Retorna parametro da consulta */
			$al_centroAcademico = $result['centroAcademico'];
			$al_cpf = $result['cpf'];
			$al_nivelCurso = $result['nivelCurso'];
			$al_nomeAluno = troca($result['nomeAluno'],"'","`");
			$al_nomeCurso = troca($result['nomeCurso'],"'","`");
			$al_pessoa = $result['pessoa'];
			$al_tel1 = $result['tel1'];
			$al_tel2 = $result['tel2'];
			$al_email1  = $result['email1'];
			$al_email2  = $result['email2'];			

			/* Grava dados no banco de dados */
			$ssql = "select * from pibic_aluno ";
			$ssql .= " where pa_cracha = '".$cracha."' ";
			$rrlt = db_query($ssql);
			if ($rline = db_read($rrlt))
				{
					$ssql = "update ".$this->tabela." set ";
					$ssql .= "pa_centro='".$al_centroAcademico."',";
					$ssql .= "pa_curso='".$al_nomeCurso."',";
					$ssql .= "pa_tel1='".$al_tel1."',";
					$ssql .= "pa_tel2='".$al_tel2."',";
					$ssql .= "pa_escolaridade='".$al_nivelCurso."',";
					$ssql .= "pa_update='".date("Ymd")."',";
					$ssql .= "pa_email='".$al_email1."',";
					$ssql .= "pa_email_1='".$al_email2."' ";
					$ssql .= " where pa_cracha = '".$cracha."' ";
					$rrlt = db_query($ssql);
					$rst = True;
					$msg = 'Atualizado';
				} else {
					$ssql = "insert into ".$this->tabela." ";
					$ssql .= "(pa_nome,pa_nome_asc,pa_nasc,";
					$ssql .= "pa_cracha,pa_cpf,pa_centro,";
					$ssql .= "pa_curso,pa_tel1,pa_tel2,";
					$ssql .= "pa_escolaridade,pa_update ";
					$ssql .= ",pa_email,pa_email_1";
					$ssql .= ") ";
					$ssql .= " values ";
					$ssql .= "('".UpperCase($al_nomeAluno)."','".UpperCaseSQL($al_nomeAluno)."','',";
					$ssql .= "'".$al_pessoa."','".$al_cpf."','".$al_centroAcademico."',";
					$ssql .= "'".$al_nomeCurso."','".$al_tel1 ."','".$al_tel2."',";
					$ssql .= "'".$al_nivelCurso."','".date("Ymd")."'";
					$ssql .= ",'".$al_email1."','".$al_email2."'";
					$ssql .= ")";
					$rrlt = db_query($ssql);
					$msg = 'Inserido';
					$rst = True;
				}
			return(1);	
			}
		}

	function mostra_dados_pessoais()
		{
			global $tab_max;
			/*
			 * 
			 */			
			$lattes = trim($this->pa_lattes);
			$lattes = troca($lattes,'.jsp','.do');
			if (strlen($lattes) > 10)
				{
					$lattes = '<a href="'.$lattes.'" target="new">';
					$lattes .= '<img src="'.http.'img/icone_plataforma_lattes.png" height="35" border=0>';
					$lattes .'</A>'; 
				} else {
					$lattes = '';
				}
			//$img_photo = $this->recupera_foto();
			
			$ss = ($this->pp_ss);
			if ($ss == 'S') { $ss = "SIM"; } else { $ss = "NÃO"; }			
					// class="foto-perfil"
					
			$ativo = $this->pp_ativo;
			
			//if ($ativo == 1)
			//	{ $ativo = ''; } else 
			//	{ $ativo = '<li><h2><font color="red">Desligado da instituição</font></h2></li>'; }	
			$pp =trim($this->pp_prod);
			if (strlen($pp) == 0) { $pp = 'NÃO'; }
			$sx .= '
			<table id="cabecalho-user-perfil" class="info-pessoais" border=0>
			<TR>
			<TD width="150">
			<div id="foto-perfil">'.$img_photo.'</div>
			<TD>
			<div id="nome-dados-perfil">
				<li><h1>'.$this->pa_nome.'&nbsp;</h1></li>
				<li>CPF: '.$this->pa_cpf.'</li>
				<li>RG: '.$this->pa_rg.'</li>
				<li>Data Nascimento: '.$this->pa_dn.'</li>
				<li>'.$this->pa_telefone.'</li>
				<li>'.$this->pa_celular.'</li>
				<li>'.$this->pa_email.'</li>
				<li>'.$this->pa_email_1.'</li>
				<li>Dt. Nascimento:'.stodbr($this->pa_nasc).'</li>
				<li>'.mst($this->pa_endereco).'</li>
				'.$ativo.'
				<li>'.$lattes.'</li>				
			</div>
			<TD width="300">
			<div id="info-pesquisador" class="info-pesquisador lt1">
				<span class="lt2 titulo-info-pesquisador">Informaçães Discente</span><br /><br />
				<li><strong>Crachá:</strong> '.$this->pa_cracha.'</li>
				<li><strong>Cursando:</strong> '.$this->pa_escolaridade.'</li>				
				<li><strong>Curso:</strong> '.$this->pa_curso.'</li>
				<li><strong>Centro:</strong> '.$this->pa_centro.'</li>
				<li><strong>Escola:</strong> '.$this->pa_escola.'</li>
				<BR>
				<li><strong>Dados Bancários:</strong>
						<BR>Banco: '.$this->line['pa_cc_banco'].'
						<BR>Ag.: '.$this->line['pa_cc_agencia'].' '.$this->line['pa_cc_mod'].'
						<BR>Conta: '.$this->line['pa_cc_conta'].'
				</li>
			</div>	
			</table>
			'; 
			return($sx);
		}
	function updatex()
		{
			return(1);	
		}
	}
?>
