<?
class docentes {
	var $id_pp;
	var $pp_nome;
	var $pp_nasc;
	var $pp_codigo;
	var $pp_cracha;
	var $pp_login;
	var $pp_escolaridade;
	var $pp_titulacao;
	var $pp_carga_semanal;
	var $pp_ss;
	var $pp_cpf;
	var $pp_negocio;
	var $pp_centro;
	var $pp_curso;
	var $pp_telefone;
	var $pp_celular;
	var $pp_lattes;
	var $pp_email;
	var $pp_email_1;
	var $pp_senha;
	var $pp_endereco;
	var $pp_afiliacao;
	var $pp_ativo;
	var $pp_grestudo;
	var $pp_prod;
	var $pp_ass;
	var $pp_update;
	var $pp_instituicao;
	var $pp_pagina;
	var $pp_avaliador;
	var $coordenador;
	var $coordenador_nome;
	var $shortlink;
	var $line;

	var $tabela = 'pibic_professor';

	function arquivos_salva_quebrado($ln, $tipo) {
		$lnh = $ln[0];
		$arq = 0;
		$pos = 0;
		$open = 0;
		$cr = chr(13);

		for ($r = 1; $r < count($ln); $r++) {
			if (($pos == 0) or ($pos > 499)) {
				if ($open == 1) { fclose($farq);
				}
				$farq = fopen('tmp/' . $tipo . '_' . strzero($arq++, 4), 'w');
				echo '<BR>Salvando... ' . $tipo . '_' . strzero($arq, 4);
				fwrite($farq, $lnh . $cr);
				$open = 1;
				$pos = 0;
			}
			$pos++;
			fwrite($farq, $ln[$r] . $cr);
		}
		if ($open == 1) { fclose($farq);
		}
	}

	function docentes_email_escola($escola = '', $emails) {
		$sql = "select * from pibic_professor 
						where pp_escola = '" . $escola . "' 
						and pp_update = '" . date("Y") . "'
						and pp_ativo = 1
			";
		$rlt = db_query($sql);
		while ($line = db_read($rlt)) {
			$nome = $line['pp_nome'];
			$email1 = trim($line['pp_email']);
			$email2 = trim($line['pp_email_1']);
			if (strlen($email1) > 0) { array_push($emails, $email1 . ';' . $nome);
			}
			if (strlen($email2) > 0) { array_push($emails, $email2 . ';' . $nome);
			}
		}
		return ($emails);
	}

	function docente_orientacao_duplicado($programa = '') {
		$sql = "select * from docente_orientacao 
					left join pibic_professor on od_professor = pp_cracha
					left join pibic_aluno on od_aluno = pa_cracha
					left join programa_pos_linhas on od_linha = posln_codigo
					left join programa_pos on od_programa = pos_codigo
					order by pos_nome, pa_nome, od_modalidade
			";
		$rlt = db_query($sql);

		$xpos = '';
		$xnome = '';
		$xmod = '';
		$sx = '<table width="100%" class="tabela00">';
		$id = 0;
		while ($line = db_read($rlt)) {
			$pos = $line['pos_nome'];
			$nome = $line['od_aluno'];
			$mod = $line['od_modalidade'];
			if (($pos == $xpos) and ($nome == $xnome) and ($xmod == $mod)) {
				$id++;
				$sx .= '<TR>';
				$sx .= '<TD>' . $line['pos_nome'];
				$sx .= '<TD>' . $line['od_modalidade'];
				$sx .= '<TD>' . $line['od_aluno'];
				$estudante = trim($line['pa_nome']);
				if (strlen($estudante) == 0) {
					$sx .= '<TD><font color="red">não localizado dados do estudante</font>';
				} else {
					$sx .= '<TD>' . $line['pa_nome'];
				}
				$sx .= '<TD>' . $line['od_professor'];

				$estudante = trim($line['pp_nome']);
				if (strlen($estudante) == 0) {
					$sx .= '<TD><font color="red">não localizado dados do orientador</font>';
				} else {
					$sx .= '<TD>' . $line['pp_nome'];
				}

				$sx .= '<TD>' . $line['od_ano_ingresso'];
				$sx .= '<TD>' . $line['od_ano_diplomacao'];
				$sx .= '<TD>' . $line['od_status'];
			}
			$xpos = $pos;
			$xnome = $nome;
			$xmod = $mod;
		}
		$sx .= '<TR><TD colspan=10>Total de ' . $id . ' duplicados';
		$sx .= '</table>';
		return ($sx);
	}

	function docente_orientacao_erro_codigo_aluno($programa = '') {
		$sql = "select * from docente_orientacao 
					left join pibic_professor on od_professor = pp_cracha
					left join pibic_aluno on od_aluno = pa_cracha
					left join programa_pos_linhas on od_linha = posln_codigo
					left join programa_pos on od_programa = pos_codigo
					where pa_nome isnull or pp_nome isnull
					order by pos_nome, od_professor, od_aluno
			";
		$rlt = db_query($sql);

		$sx = '<table width="100%" class="tabela00">';
		$sx .= '<TR><TD>Mod.<TH>Estudante<TH>Nome estudante<TH>orientador<TH>nome orientador<TH>entrada<TH>saída<TH>status';
		$xpos = '';
		$id = 0;
		while ($line = db_read($rlt)) {
			$id++;
			$pos = trim($line['pos_nome']);
			if ($pos != $xpos) {
				$sx .= '<TR>';
				$sx .= '<TD colspan=10 class="lt3" ><B>' . $pos . '</B>';
				$sx .= $sh;
				$xpos = $pos;
			}
			$sx .= '<TR>';
			$sx .= '<TD>' . $line['od_modalidade'];
			$sx .= '<TD>' . $line['od_aluno'];
			$estudante = trim($line['pa_nome']);
			if (strlen($estudante) == 0) {
				$sx .= '<TD><font color="red">não localizado dados do estudante</font>';
			} else {
				$sx .= '<TD>' . $line['pa_nome'];
			}
			$sx .= '<TD>' . $line['od_professor'];

			$estudante = trim($line['pp_nome']);
			if (strlen($estudante) == 0) {
				$sx .= '<TD><font color="red">não localizado dados do orientador</font>';
			} else {
				$sx .= '<TD>' . $line['pp_nome'];
			}

			$sx .= '<TD>' . $line['od_ano_ingresso'];
			$sx .= '<TD>' . $line['od_ano_diplomacao'];
			$sx .= '<TD>' . $line['od_status'];
		}
		$sx .= '<TR><TD colspan=10>Total de inconsistências ' . $id;
		$sx .= '</table>';
		return ($sx);

	}

	function docente_orientacao_sem_nome_aluno() {
		$sql = "select od_aluno from docente_orientacao 
					left join pibic_professor on od_professor = pp_cracha
					left join pibic_aluno on od_aluno = pa_cracha
					left join programa_pos_linhas on od_linha = posln_codigo
					where pa_nome isnull
					order by pp_nome, posln_descricao, od_ano_ingresso desc, od_modalidade
			";
		$rlt = db_query($sql);

		$crachas = array();
		while ($line = db_read($rlt)) {
			array_push($crachas, trim($line['od_aluno']));
		}
		return ($crachas);
	}

	function docente_orientacao_excluir_cancelados() {
		$sql = "select * from docente_orientacao where od_status = '#' ";
		$rlt = db_query($sql);

		while ($line = db_read($rlt)) {
			$sqlu = "delete from docente_orientacao where od_status = '#' and id_od = " . round($line['id_od']);
			$rtlu = db_query($sqlu);
			//echo $sqlu.'<BR>';
		}
	}

	function inserir_docente($cracha, $nome, $email) {
		$sql = "select * from " . $this -> tabela . " where pp_cracha = '" . $cracha . "'";
		$rlt = db_query($sql);
		if ($line = db_read($rlt)) {
			echo '<font color="red">Já existe</font>';
		} else {
			$ano = date("Y");
			$sql = "insert into " . $this -> tabela . " 
							(pp_cracha, pp_nome, pp_email,
							pp_update)
							values
							('$cracha','$nome','$email',
							'$ano')
					";
			$rlt = db_query($sql);
		}
	}

	function atualliza_dados_docente($cracha, $curso = '', $titulacao = '', $centro = '', $ss = '', $email = '', $ch = '') {
		$usql = "update pibic_professor set ";
		$usql .= " pp_update = '" . date("Y") . "' ";
		$curso = troca($curso, 'ADMINISTRAÇÃO DA GRADUAÇÃO EM ', 'ADM. EM ');
		$tr = array();
		array_push($tr, ' - REG. AN. C');
		array_push($tr, '-(DIURNO)');
		array_push($tr, '(DIURNO) - REG');
		for ($r = 0; $r < count($tr); $r++) {
			if (strpos($curso, $tr[$r]) > 0) { $curso = substr($curso, 0, strpos($curso, $tr[$r]));
			};
		}
		if (strlen($curso) > 0) { $curso = substr($curso, 0, 50);
		}
		if (strlen($ch) > 0) { $usql .= ", pp_carga_semanal = '" . $ch . "' ";
		}
		if (strlen($centro) > 0) { $usql .= ", pp_centro = '" . $centro . "' ";
		}
		//if (strlen($email) > 0) 		{ $usql .= ", pp_email = '".$email."' "; }
		if (strlen($curso) > 0) { $usql .= ", pp_curso = '" . $curso . "' ";
		}
		if (strlen($titulacao) > 0) { $usql .= ", pp_titulacao = '" . $titulacao . "' ";
		}
		if (strlen($ss) > 0) { $usql .= ", pp_ss = '" . substr($ss, 0, 1) . "' ";
		}
		if (strlen($escola) > 0) { $usql .= ", pp_escola = '" . $escola . "' ";
		}
		$usql .= " where pp_cracha = '" . $cracha . "'";
		$rlt = db_query($usql);
		return (1);
	}

	function read_link($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$contents = curl_exec($ch);
		if (curl_errno($ch)) {
			echo curl_error($ch);
			echo "\n<br />";
			$contents = '';
		} else {
			curl_close($ch);
		}
		if (!is_string($contents) || !strlen($contents)) {
			echo "Failed to get contents.";
			$contents = '';
		}
		if (strpos($contents, 'encoding="UTF-8"') > 0) {
			$contents = troca($contents, 'encoding="UTF-8"', 'encoding="ISO-8859-1"');
			$contents = utf8_decode($contents);
		}
		return ($contents);
	}

	function professores_atualizados() {
		$sql = "select * from " . $this -> tabela . " 
					where pp_ativo = 0 and pp_update = '" . date("Y") . "'";
		$rlt = db_query($sql);
		$ID = 0;
		echo '<h3>Reativar Docentes</h3>';
		while ($line = db_read($rlt)) {
			$sql = 'update ' . $this -> tabela . " set pp_ativo = 1 where id_pp = " . $line['id_pp'];
			echo '<BR>' . $line['pp_nome'];
			$xrlt = db_query($sql);
			$ID++;
		}
		echo 'Retivado ' . $ID . ' docentes inativos';

	}

	function professores_sem_codigo() {
		$sql = "select * from " . $this -> tabela . " 
					where pp_cracha = ''
			";
		$rlt = db_query($sql);
		$ID = 0;
		echo '<h3>Eliminar Docentes sem código</h3>';
		while ($line = db_read($rlt)) {
			$sql = 'delete from ' . $this -> tabela . " where id_pp = " . $line['id_pp'];
			$xrlt = db_query($sql);
			$ID++;
		}
		echo 'Eliminado ' . $ID . ' registros sem código';
		$this -> professores_atualizados();
	}

	function docentes_e_email() {
		$sql = "select * from " . $this -> tabela . " 
					where  pp_ativo=1 and pp_update = '" . date("Y") . "' ";
		$sx = '<table width="100%">';
		$sx .= '<TR><TH><h2>Lista de e-mail de professores ativos ' . date("Y") . '</h2>';
		$sx .= '<TR>';
		$sx .= '<TD>';
		$rlt = db_query($sql);
		while ($line = db_read($rlt)) {
			$email = lowercase(trim($line['pp_email']));
			if (strlen($email) > 0) { $sx .= $email . '; ';
			}
		}
		$sx .= '</table>';
		echo $sx;
		returnc($sx);
	}

	function pesquisadores() {
		$sql = "select pp_ss, ap_tit_titulo, pp_cracha, pp_nome, max(pibic) as pibic, max(ss) as ss from
				(
					select 1 as pibic, 0 as ss, pb_professor as professor from pibic_bolsa_contempladas where pb_status = 'A'
					union
					select 0 as pibic, 1 as ss, pp_cracha as professor from pibic_professor where pp_ss = 'S' and pp_update = '" . date("Y") . "' 
				) as tabela
				inner join pibic_professor on professor = pp_cracha
				left join apoio_titulacao on pp_titulacao = ap_tit_codigo
				where pp_ativo = 1 and pp_update = '2013'
				group by pp_ss, ap_tit_titulo, pp_cracha, pp_nome
				order by pp_ss desc, pp_nome
			";
		$rlt = db_query($sql);
		$sx = '<table class="tabela00" width="100%">';
		$sx .= '<TR><TD colspan=10><h2>' . msg('Pesquisadores da Instituição') . '</h2>';
		$sx .= '<TR>';
		$sx .= '<td colspan=10 class="lt0">Busca professores do Stricto Sensu e professores que orientam PIBIC/PIBITI';
		$sx .= '<TR>';
		$sx .= '<TH>Pesquisador';
		$sx .= '<TH>Título';
		$sx .= '<TH>Crachá';
		$sx .= '<TH>Pós-Graduação';
		$sx .= '<TH>Orientação';
		$id = 0;
		$dr = 0;
		$msc = 0;
		$sss = 0;

		while ($line = db_read($rlt)) {
			if (UpperCaseSQL(trim($line['ap_tit_titulo'])) == 'DR.') { $dr++;
			}
			if (UpperCaseSQL(trim($line['ap_tit_titulo'])) == 'MSC.') { $msc++;
			}
			if (UpperCaseSQL(trim($line['pp_ss'])) == 'S') { $sss++;
			}
			$id++;
			$sx .= '<TR>';
			$sx .= '<TD class="tabela01">';
			$sx .= $line['pp_nome'];

			$sx .= '<TD class="tabela01">';
			$sx .= $line['ap_tit_titulo'];

			$sx .= '<TD class="tabela01" align="center">';
			$sx .= $line['pp_cracha'];

			$sx .= '<TD class="tabela01" align="center">';
			if ($ss == 'S') {
				$sx .= 'Stricto Sensu';
			} else {
				$sx .= '-';
			}

			$sx .= '<TD class="tabela01" align="center">';
			$pibic = $line['pibic'];
			$pibic_mst = '';
			if ($pibic > 0) { $pibic_mst = 'PIBIC';
			}
			$sx .= $pibic_mst;
		}
		$sx .= '<TR><TD colspan=10>Total ' . $id . ' pesquisadores, ' . $dr . ' doutores, ' . $msc . ' mestres. Deste ' . $sss . ' são do Stricto Sensu';
		$sx .= '</table>';

		return ($sx);
	}

	function avaliador_ic_mudar_ativacao($vl) {
		$sql = "update pibic_professor set pp_avaliador = " . round($vl);
		$sql .= " where pp_cracha = '" . $this -> pp_cracha . "' ";
		$rlt = db_query($sql);
	}

	function avaliador_ic() {
		global $dd, $acao;
		$bb1a = 'desativar avaliador';
		$bb1b = 'ativar avaliador';

		if (($acao == $bb1a) or ($acao == $bb1b)) {
			if ($acao == $bb1a) { $at = 0;
			} else { $at = 1;
			}
			$this -> avaliador_ic_mudar_ativacao($at);
			$this -> avalidor = $at;
			redirecina(page() . '?dd0=' . $dd[0]);
		}
		$av = $this -> pp_avaliador;
		if ($av == 1) {
			$av = '<font color="blue">Ativo</font>';
			$bb1 = $bb1a;
		} else {
			$av = '<font color="red">Inativo</font>';
			$bb1 = $bb1b;
		}
		$sx = '<fieldset><legend>';
		$sx .= msg('avaliador_ic');
		$sx .= '</legend>';
		$sx .= '<table width="100%" class="lt1">';
		$sx .= '<TR><TD>';
		$sx .= 'Avalidor de IC: <font class="lt3"><B>';
		$sx .= $av;
		$sx .= '</B>';
		$sx .= '<td><form action="' . page() . '">';
		$sx .= '<input type="hidden" name="dd0" value="' . $dd[0] . '">';
		$sx .= '<TD align="right">';
		$sx .= '<input type="submit" name="acao" method="get" class="botao-geral" value="' . $bb1 . '">';
		$sx .= '<TD width=1></form>';
		$sx .= '</table>';
		$sx .= '</fieldset>';
		return ($sx);
	}

	function inport_titulacao($c4 = '') {
		$c4a = '000';
		if ($c4 == 'Superior Completo') { $c4a = '004';
		}
		if ($c4 == 'Mestrado') { $c4a = '001';
		}
		if ($c4 == 'Mestrado Completo') { $c4a = '001';
		}
		if ($c4 == 'Doutorado') { $c4a = '002';
		}
		if ($c4 == 'Doutorado Completo') { $c4a = '002';
		}
		if ($c4 == 'Pós-Graduação Completo') { $c4a = '005';
		}
		if ($c4 == 'Especialização') { $c4a = '005';
		}
		if ($c4 == 'Graduação') { $c4a = '004';
		}
		if ($c4 == 'Mestrado Incompleto') {  $c4a = '004';
		}
		if ($c4 == 'Pós-Graduação Incompleto') {  $c4a = '004';
		}
		if ($c4 == 'Doutorado Incompleto') {  $c4a = '001';
		}

		if ($c4a == '000') { echo '<BR><font color="red">Não localizado titulação (' . $c4 . ') - função inport_titulacao(); </font>';
			exit ;
		}
		return ($c4a);
	}

	function docente_escolas($escola) {
		$escola_cod = round($escola);
		$sql = "select * from centro
					inner join pibic_professor on centro_codigo = pp_escola
					where centro_codigo = '$escola' or id_centro = $escola_cod
					order by pp_nome
			";
		$rlt = db_query($sql);
		$sx = $this -> rel_prof_mostra($rlt);
		echo $sx;
		return ($sx);
	}

	function cadastrar_orientacao($professor, $aluno, $ano, $modalidade, $programa) {
		$modalidade = UpperCase(substr($modalidade, 0, 1));
		$sql = "select * from docente_orientacao 
					where od_professor = '$professor'
					and od_aluno = '$aluno'
					and od_modalidade = '" . $modalidade . "'
			";
		$rlt = db_query($sql);
		if ($line = db_read($rlt)) {
			$sx .= '<BR>Jó cadastrado';
		} else {
			$ano_ini = $ano;
			$status = 'C';
			$ano_fim = 0;
			$defesa = 19000101;
			$quali = 19000101;
			$artigo = 19000101;
			$credi = 19000101;
			$idioma1_dt = 19000102;
			$idioma2_dt = 19000103;
			$idioma1 = '';
			$idioma2 = '';
			$sql = "insert into docente_orientacao
							(
							od_professor, od_aluno, od_status, od_programa,  
							od_ano_ingresso, od_ano_diplomacao, 
							od_qualificacao, od_defesa, od_artigo, od_creditos,
							od_idioma_1, od_idioma_1_tipo, 
							od_idioma_2, od_idioma_2_tipo,
							od_titulo_projeto, od_bolsa,
							od_obs, od_modalidade							
							)
							values
							(
							'$professor','$aluno','$status','$programa',
							$ano_ini, $ano_fim, 
							$quali, $defesa, $artigo, $credi,
							$idioma1_dt, '$idioma1',
							$idioma2_dt, '$idioma2',
							'', '',
							'', '$modalidade'
							) ";
			$rlt = db_query($sql);
			return (0);
		}
	}

	function orientador_da_discente($estudante) {
		$sql = "select * from docente_orientacao
						inner join programa_pos on od_programa = pos_codigo
						left join pibic_professor on pp_cracha = pos_coordenador
						and od_status = 'A'
						where od_aluno = '" . $estudante . "' 
						order by od_ano_ingresso desc
						";
		$rlt = db_query($sql);
		if ($line = db_read($rlt)) {
			//print_r($line);
			//exit;
			$this -> coordenador = $line['pp_cracha'];
			$this -> coordenador_nome = trim($line['pp_nome']);
			$this -> line = $line;
		}
		return (1);
	}

	function blacklist() {
		$bl = $this -> line['pp_bl'];
		$blobs = $this -> line['pp_bl_motivo'];
		$sx = '';
		if (($bl == '1') or ($bl == 'S')) {
			$sx .= '<div>';
			$sx .= '<img src="../img/icone_exclamation.png" height="50" align="left">';
			$sx .= '<B>BLACK LIST</B><BR>';
			$sx .= $this -> line['pp_bl_motivo'];
			$sx .= '</div>';
		}
		return ($sx);
	}

	function cp_atualizacao() {
		$cp = array();
		array_push($cp, array('$H8', 'pp_cracha', 'id_pp', True, True));

		array_push($cp, array('$S100', 'pp_email', 'e-mail', False, True));
		array_push($cp, array('$S100', 'pp_email_1', 'e-mail (alt)', False, True));
		array_push($cp, array('$S100', 'pp_lattes', 'Link para lattes', False, True));

		array_push($cp, array('$S20', 'pp_telefone', 'Telefone', False, True));
		array_push($cp, array('$S20', 'pp_celular', 'Celular', False, True));

		return ($cp);
	}

	function cp_blacklist() {
		$cp = array();
		array_push($cp, array('$H8', 'id_pp', 'id_pp', False, True));
		array_push($cp, array('$C1', 'pp_bl', 'Blacklist', False, True));
		array_push($cp, array('$T60:4', 'pp_bl_motivo', 'Blacklist (motivo)', False, True));
		return ($cp);
	}

	function cp() {
		global $dd;
		if (strlen($dd[4]) == 0) { $dd[4] = UpperCaseSql($dd[1]);
		}
		$dd[4] = UpperCaseSql($dd[4]);
		//$sql = "CREATE VIEW docentes as SELECT * from pibic_professor";
		//$rlt = db_query($sql);
		//$sql = "ALTER TABLE ".$this->tabela." ADD COLUMN pp_cited char(100);";
		//$rlt = db_query($sql);

		$prod = $this -> produtividade();
		$keys = array_keys($prod);
		$op = ' : ';
		foreach ($keys as $key) { $op .= '&' . trim($key) . ':' . trim($prod[$key]);
		}
		$cp = array();
		array_push($cp, array('$H8', 'id_pp', 'id_pp', False, True));
		array_push($cp, array('$S100', 'pp_nome', 'Nome completo', True, True));
		array_push($cp, array('$HV', 'pp_nome_asc', '', False, True));
		array_push($cp, array('$H8', '', '', False, True));
		array_push($cp, array('$S100', 'pp_nome_lattes', 'Nome completo no Lattes', False, True));
		array_push($cp, array('$S100', 'pp_cited', 'Nome de citação', False, True));
		array_push($cp, array('$S30', 'pp_cpf', 'CPF', False, True));
		array_push($cp, array('$I8', 'pp_carga_semanal', 'Carga horória', True, True));
		array_push($cp, array('$S30', 'pp_negocio', 'Negócio', False, True));
		array_push($cp, array('$S11', 'pp_cracha', 'Cracha', False, True));

		array_push($cp, array('$O N:NÃO&S:SIM', 'pp_ss', 'Stricto Sensu', False, True));
		array_push($cp, array('$S40', 'pp_centro', 'Campus', False, True));

		array_push($cp, array('$Q ap_tit_titulo:ap_tit_codigo:select * from apoio_titulacao order by ap_tit_titulo', 'pp_titulacao', 'Titulação', False, True));

		array_push($cp, array('$S50', 'pp_curso', 'Curso', False, True));
		array_push($cp, array('$S100', 'pp_email', 'e-mail', False, True));
		array_push($cp, array('$S100', 'pp_email_1', 'e-mail (alt)', False, True));
		array_push($cp, array('$O ' . $op, 'pp_prod', 'Produtividade', True, True));
		array_push($cp, array('$S100', 'pp_lattes', 'Link para lattes', False, True));

		array_push($cp, array('$O 0:NÃO&1:SIM&2:Dispensado', 'pp_avaliador', 'Avaliador', True, True));
		array_push($cp, array('$O 0:Sem participação&1:Comitê Local&2:Comitê Gestor', 'pp_comite', 'Participação do Comitê Gestor IC', True, True));
		array_push($cp, array('$Q centro_nome:centro_codigo:select * from centro order by centro_codigo', 'pp_escola', 'Escola', False, True));

		array_push($cp, array('$[2008-' . date("Y") . ']', 'pp_update', 'Atualizado', False, True));
		array_push($cp, array('$O 1:SIM&0:NÃO', 'pp_ativo', 'Ativo', True, True));

		array_push($cp, array('$S20', 'pp_telefone', 'Telefone', False, True));
		array_push($cp, array('$S20', 'pp_celular', 'Celular', False, True));

		array_push($cp, array('$C1', 'pp_livredocencia', 'Livre docência', False, True));
		array_push($cp, array('$C1', 'pp_posdoc', 'Pós-Doutorado', False, True));

		array_push($cp, array('$S20', 'pp_login', 'Login', False, False));

		array_push($cp, array('${', '', 'Função administrativa na Instituição', False, True));
		array_push($cp, array('$S100', 'pp_funcao', 'Função (opcional)', False, True));
		array_push($cp, array('$}', '', 'Função administrativa na Instituição', False, True));

		//$sql = "alter table pibic_professor add pp_funcao char(100)";
		//$rlt = db_query($sql);

		return ($cp);
	}

	function structure_od() {
		$sql = "create table docente_orientacao
					(
					id_od serial not null,
					ob_modalidade char(1),
					od_professor char(8),
					od_aluno char(8),
					od_ano_ingresso int8,
					od_ano_diplomacao int8,
					od_status char(1),
					od_programa char(7),
					od_qualificacao int8,
					od_defesa int8,
					od_artigo int8,
					od_creditos int8,
					od_idioma_1 int8,
					od_idioma_1_tipo char(3),
					od_idioma_2 int8,
					od_idioma_2_tipo char(3),
					od_titulo_projeto text,
					od_bolsa char(3),
					od_obs text														
					)				
			";
		$rlt = db_query($sql);
		return (1);
	}

	function splitx($v1, $v2) {
		$v2 .= $v1;
		$vr = array();
		while (strpos(' ' . $v2, $v1)) {
			$vp = strpos($v2, $v1);
			$v4 = trim(substr($v2, 0, $vp));
			$v2 = trim(substr($v2, $vp + 1, strlen($v2)));
			if (strlen($v4) > 0) { array_push($vr, $v4);
			}
		}
		return ($vr);
	}

	function orientacoes_inport($text, $programa, $modalidade) {
		global $dd;
		if ($dd[5] == 'S') {
			$sql = "delete from docente_orientacao where od_programa = '" . $programa . "' ";
			$rlt = db_query($sql);
		}
		//exit;
		/*
		 * ORIENTADOR	ALUNO	MODALIDADE	INICIO	STATUS	DEFESA
		 * */
		$ln = array();
		$text = troca($text, chr(10), '');
		$loop = 0;
		while ((strpos($text, chr(13)) > 0) and (loop < 400)) {
			$loop++;
			$lna = substr($text, 0, strpos($text, chr(13)));
			$text = substr($text, strpos($text, chr(13)) + 1, strlen($text));
			$lna = troca($lna, chr(9), ';');
			array_push($ln, $lna);
		}

		/** Dados */
		echo '<TT>';
		for ($r = 0; $r < count($ln); $r++) {
			$lnb = $this -> splitx(';', trim($ln[$r]));

			$professor = trim($lnb[1]);
			if (strlen($professor) > 8) { $professor = substr($professor, 3, 8);
			}
			$professor = substr($professor, 0, 8);

			$aluno = $lnb[0];
			if (strlen($aluno) > 8) { $aluno = substr($aluno, 3, 8);
			}
			$aluno = substr($aluno, 0, 8);

			$status = substr($lnb[4], 0, 1);
			if (strpos($lnb[2], '/')) { $ano_ini = brtos($lnb[2]);
			} else { $ano_ini = round(sonumero($lnb[2]));
			}

			if (strpos($lnb[3], '/')) { $ano_fim = brtos($lnb[3]);
			} else { $ano_fim = round(sonumero($lnb[3]));
			}

			$quali = 19000102;
			$defesa = 19000102;
			$artigo = 19000102;
			$credi = 19000102;
			$idioma1_dt = 19000101;
			$idioma2_dt = 19000101;
			$dd1 = round($lnb[3]);
			$dd2 = round($lnb[4]);
			$idioma1 = "''";
			$idioma2 = "''";

			/* Status */
			$status = 'C';
			if ($ano_fim > 19900101) { $status = 'T';
			}

			if (strlen($programa) == 0) {
				echo 'Programa NÃO foi definido';
				return (0);
			}

			$sql = "select * from docente_orientacao 
							where od_professor = '$professor'
							and  od_aluno = '$aluno'
							and od_modalidade = '$modalidade'
					";

			$rlt = db_query($sql);
			if ($line = db_read($rlt)) {
				echo '<BR>Jó cadastrado';
				$sql = "update docente_orientacao set od_programa = '$programa' 
								, od_status = '$status'
								, od_ano_ingresso = $ano_ini
								, od_ano_diplomacao = $ano_fim
								where id_od = " . $line['id_od'];
				$qrlt = db_query($sql);
			} else {
				//						$sql = "ALTER TABLE docente_orientacao add column od_saida int4";
				//						$rlt = db_query($sql);
				$sql = "insert into docente_orientacao
							(
							od_professor, od_aluno, od_status, od_programa,  
							od_ano_ingresso, od_ano_diplomacao, 
							od_qualificacao, od_defesa, od_artigo, od_creditos,
							od_idioma_1, od_idioma_1_tipo, 
							od_idioma_2, od_idioma_2_tipo,
							od_titulo_projeto, od_bolsa,
							od_obs, od_modalidade, od_entrada, od_saida							
							)
							values
							(
							'$professor','$aluno','$status','$programa',
							$ano_ini, $ano_fim, 
							$quali, $defesa, $artigo, $credi,
							$idioma1_dt, $idioma1,
							$idioma2_dt, $idioma2,
							'', '',
							'', '$modalidade', $dd1,$dd2
							) ";
				$rlt = db_query($sql);
			}
		}

		return (1);
	}

	function modalidade($tp) {
		if ($tp == 'M') { $tp = "Mestrado";
		}
		if ($tp == 'D') { $tp = "Doutorado";
		}
		if ($tp == 'N') { $tp = "Mestrado Profissional";
		}
		return ($tp);
	}

	function mostra_ano($v) {
		$v = round(substr($v, 0, 4));
		if (($v == 0) or ($v == 1900)) { $v = '-';
		} else { $v = strzero($v, 4);
		}
		return ($v);
	}

	function docentes_orientacoes($programa = '', $area = '') {
		global $http;
		//$sql = "alter table docente_orientacao add column od_linha char(7) ";
		//$rlt = db_query($sql);

		$sql = "select * from docente_orientacao 
					left join pibic_professor on od_professor = pp_cracha
					left join pibic_aluno on od_aluno = pa_cracha
					left join programa_pos_linhas on od_linha = posln_codigo
					where od_programa = '$programa'
					order by pp_nome, posln_descricao, od_ano_ingresso desc, od_modalidade
			";
		$totd = 0;
		$toto = 0;

		$rlt = db_query($sql);
		$xprof = 'x';
		$sx .= '<H2>Docentes Orientaçães</H2>';
		$sx .= '<table class="lt1" width="100%" class="tabela00">';
		$sh = '<TR><TH>Mod<TH>Estudante<TH>Linha de pesquisa<TH>Entrada<TH>Defesa<TH>Status';
		$stt = array('A' => 'Matriculado sem orientador', 'C' => 'Ativo (cursando)', 'T' => 'Titulado', 'R' => 'Desistente', 'G' => 'Aguardando entrega da Tese/Dissertação', 'D' => 'Desligado', 'N' => 'Trancado');
		$stt = $this -> status();

		while ($line = db_read($rlt)) {
			$link = '<A HREF="discente_detalhe.php?dd0=' . $line['od_aluno'] . '&dd90=' . checkpost($line['od_aluno']) . '" clsas="link">';
			$prof = trim($line['pp_cracha']);
			if ($xprof != $prof) {
				$sx .= '<TR class="lt2" ' . coluna() . '>';
				$sx .= '<TD colspan=6><B>' . $line['pp_nome'] . ' (' . $line['od_professor'] . ')';
				$sx .= $sh;
				$xprof = $prof;
			}
			$sx .= '<TR>';
			$sx .= '<TD class="tabela01">' . $this -> modalidade($line['od_modalidade']);
			$sx .= '<TD class="tabela01">' . $link . $line['pa_nome'] . ' (' . $line['od_aluno'] . ')</A>';
			$sx .= '<TD class="tabela01">' . $line['posln_descricao'] . '';
			$sx .= '<TD class="tabela01" align="center">' . $this -> mostra_ano($line['od_ano_ingresso']);
			$sx .= '<TD class="tabela01" align="center">' . $this -> mostra_ano($line['od_ano_diplomacao']);
			$sta = trim($line['od_status']);
			$sx .= '(' . $sta . ')';
			if ($line['od_ano_diplomacao'] > date("Ymd")) { $sta = 'C';
			}
			$sx .= '<TD class="tabela01">' . $stt[$sta];
			$sx .= '<TD>';
			$sx .= '<A HREF="discente_orientacao_ed.php?dd0=' . $line['id_od'] . '" target="_NEW"><img src="../img/icone_editar.gif"></A>';
		}
		$sx .= '</table>';
		return ($sx);
	}

	function resumo_teses_dissertacoes($programa = '', $area = '', $anoi = 19000101, $anof = 29990101) {
		global $include;
		//$wh = 'and (od_ano_diplomacao >= $anoi and od_ano_diplomacao <= $anof);'
		if (strlen($programa) > 0) {
			$sql = "select od_ano_ingresso, od_ano_diplomacao, od_status,
					od_modalidade, 1 as total				
			 		from docente_orientacao 
			 		where od_programa = '$programa'
					$wh 
			 		";
		} else {
			$sql = "select od_ano_ingresso, od_ano_diplomacao, od_status,
					od_modalidade, 1 as total				
			 		from docente_orientacao 
			 		where od_status <> 'X'	
					$wh 
			 		";
		}

		$sql .= " order by od_ano_ingresso ";
		$rlt = db_query($sql);

		$tt = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
		$rsu = array();
		for ($r = 1990; $r <= date("Y"); $r++) { array_push($rsu, $tt);
		}

		while ($line = db_read($rlt)) {
			$status = trim($line['od_status']);
			$total = $line['total'];
			$anoi = round(substr($line['od_ano_ingresso'], 0, 4)) - 1990;
			$anof = round(substr($line['od_ano_diplomacao'], 0, 4)) - 1990;
			$anoa = 0;
			$anod = 0;

			if (($status == 'X') or ($status == 'Y')) { $anod = $anoi;
			}
			if (($status == 'A') or ($status == 'C') or ($status == 'D') or ($status == 'T')) { $anoa = $anoi;
			}

			$xmod = trim($line['od_modalidade']);
			$mod = 0;

			if ($xmod == 'M') { $mod = 1;
			}
			if ($xmod == 'D') { $mod = 5;
			}
			if ($xmod == 'P') { $mod = 9;
			}

			if ($anoi > 0) {
				/* Estudante matriculado */
				$rsu[$anoi][$mod] = $rsu[$anoi][$mod] + $line['total'];
			}
			if ($anof > 0) {
				/* Estudante egresso */
				$rsu[$anof][$mod + 1] = $rsu[$anof][$mod + 1] + $line['total'];
			}
			if ($anod > 0) {
				/* Estudante cancelado ou desistente */
				$rsu[$anod][$mod + 2] = $rsu[$anod][$mod + 2] + $line['total'];
			}
			if ($anoa > 0) {
				/* Estudante ativo ou defendido */
				$rsu[$anoa][$mod + 3] = $rsu[$anoa][$mod + 3] + $line['total'];
			}
		}
		$sx = '<table border=1 class="tabela00" width="800">';
		$sx .= '<TR><TH rowspan=2><TH colspan=4>mestrado';
		$sx .= '<TH colspan=4>doutorado';
		$sx .= '<TH colspan=4>pós-doutorado';
		$sx .= '<TR>';
		$sx .= '<TH width="8%">entrada<TH width="8%">egresso<TH width="8%">desistente<TH width="8%">ativos';
		$sx .= '<TH width="8%">entrada<TH width="8%">egresso<TH width="8%">desistente<TH width="8%">ativos';
		$sx .= '<TH width="8%">entrada<TH width="8%">egresso<TH width="8%">desistente<TH width="8%">ativos';

		for ($r = 0; $r < count($rsu); $r++) {
			$sa = '<TR>';
			$sa .= '<TD>';
			$sa .= (1990 + $r);
			$t1 = 0;
			for ($t = 1; $t <= 12; $t++) {
				$tt[$t] = $tt[$t] + $rsu[$r][$t];
				$t1 = $t1 + $rsu[$r][$t];
				$sa .= '<TD align="center">';
				$sa .= $rsu[$r][$t];
			}
			if ($t1 > 0) { $sx .= $sa . chr(13);
			}
		}
		$sx .= '<TR><TH>';
		for ($t = 1; $t <= 12; $t++) {
			$sx .= '<TD align="center">';
			$sx .= $tt[$t];
		}
		$sx .= '</table>';

		return ($sx);
	}

	function row_docente_orientacoes() {
		global $cdf, $cdm, $masc, $tabela;
		//$sql = "ALTER TABLE docente_orientacao ADD COLUMN od_modalidade char(1);";
		//$rlt = db_query($sql);
		$tabela = "
						( select * from docente_orientacao 
							left join pibic_professor on pp_cracha = od_professor
							left join pibic_aluno on pa_cracha = od_aluno							
						) as docente_orientacao
					";
		$cdf = array('id_od', 'od_modalidade', 'pp_nome', 'od_professor', 'pa_nome', 'od_aluno', 'od_ano_ingresso', 'od_ano_diplomacao', 'od_status', 'od_programa');
		$cdm = array('cod', msg('modalida_ss'), msg('professor'), msg('professor'), msg('estudante'), msg('estudante'), msg('entrada'), msg('diplocacao'), msg('Status'), msg('programa'));
		$masc = array('', '', '', '', '', '', '');
		return (1);
	}

	function status() {

		$sta = array('A' => 'Ativo (cursando)', 'Q' => 'Qualificado', 'C' => 'Ativo (cursando)', 'O' => 'Requalificado', 'D' => 'Defendido', 'T' => 'Titulado', 'R' => 'Trancado', 'X' => 'Cancelado (pelo discente)', 'Y' => 'Desligado (pelo programa)', '#' => 'Excluir do sistema (registro duplicado)');
		return ($sta);

	}

	function status_op() {
		$sta = '';
		$sta .= 'A:Ativo';
		$sta .= '&Q:Qualificado';
		$sta .= '&O:Requalificado';
		$sta .= '&D:Defendido';
		$sta .= '&T:Titulado';
		$sta .= '&R:Trancado';
		$sta .= '&X:Cancelado (pelo discente)';
		$sta .= '&Y:Desligado (pelo programa)';
		$sta .= '&R:Reprovado (pelo programa)';
		$sta .= '&#:Excluir do sistema (registro duplicado)';
		return ($sta);
	}

	function cp_docente_orientacoes() {
		$cp = array();
		$sta = $this -> status_op();
		array_push($cp, array('$H8', 'id_od', '', False, True));
		array_push($cp, array('$S8', 'od_professor', 'Professor (cracha)', True, True));
		array_push($cp, array('$S8', 'od_aluno', 'Estudante (cracha)', True, True));

		array_push($cp, array('${', '', 'Nível', False, True));
		array_push($cp, array('$Q pos_nome:pos_codigo:select * from programa_pos where pos_ativo=1 order by pos_nome', 'od_programa', 'Programa', True, True));
		array_push($cp, array('$O : &M:Mestrado&D:Doutorado&P:Pós-Doutorado', 'od_modalidade', 'Nível', True, True));
		array_push($cp, array('$Q posln_descricao:posln_codigo:select * from programa_pos_linhas where posln_ativo = 1 order by posln_descricao', 'od_linha', 'Linha', False, True));
		array_push($cp, array('$O ' . $sta, 'od_status', 'Status', True, True));
		array_push($cp, array('$}', '', 'Nível', False, True));

		array_push($cp, array('${', '', 'Data', False, True));
		array_push($cp, array('$S4', 'od_ano_ingresso', 'Ano de ingresso', True, True));
		array_push($cp, array('$S4', 'od_ano_diplomacao', 'Ano de titulação', True, True));
		array_push($cp, array('$M', '', '<font color=green> Informar o ano de previsão ou \'zero\'</font>', False, True));
		//array_push($cp,array('$O A:Ativo&T:Titulado&R:Trancado&C:Cursando','od_status','Status',True,True));
		array_push($cp, array('$D8', 'od_qualificacao', 'Qualificação', False, True));
		// od_re_qualificacao
		array_push($cp, array('$D8', 'od_re_qualificacao', 'Requalificação', False, True));
		array_push($cp, array('$D8', 'od_defesa', 'Defesa', False, True));
		array_push($cp, array('$D8', 'od_artigo', 'Entrega do artigo', False, True));
		array_push($cp, array('$D8', 'od_creditos', 'Integralização dos créditos', False, True));
		array_push($cp, array('$}', '', 'Idioma 1', False, True));

		array_push($cp, array('${', '', 'Idioma 1', False, True));
		array_push($cp, array('$D8', 'od_idioma_1', 'Idioma 1', False, True));
		array_push($cp, array('$S3', 'od_idioma_1_tipo', 'Idioma 1', False, True));
		array_push($cp, array('$}', '', 'Idioma 1', False, True));

		array_push($cp, array('${', '', 'Idioma 2', False, True));
		array_push($cp, array('$D8', 'od_idioma_2', 'Idioma 2', False, True));
		array_push($cp, array('$S3', 'od_idioma_2_tipo', 'Idioma 2', False, True));
		array_push($cp, array('$}', '', 'Idioma 1', False, True));

		array_push($cp, array('$T70:4', 'od_titulo_projeto', 'Título do projeto', False, True));
		array_push($cp, array('$H3', 'od_bolsa', 'Bolsa', False, True));
		array_push($cp, array('$H8', '', 'Observacao', False, True));

		array_push($cp, array('$T70:5', 'od_obs', 'Observações', False, True));
		$sql = "alter table docente_orientacao add column od_re_qualificacao integer";
		//$rlt = db_query($sql);
		return ($cp);

	}

	function enviar_email($subj, $texto, $ss, $link) {
		$sql = "select * from " . $tabela . " 
				where pp_ativo = 1 
				and pp_ss = 'S' 
				order by pp_nome
				";
		$rlt = db_query($sql);

		while ($line = db_read($rlt)) {
			$this -> le($line['id_pp']);
		}
		return (1);

	}

	function higienizacao_curso() {
		$sql = "select * from " . $this -> tabela . " 
						where pp_curso like '% - HAB.:%'
						or pp_curso like '%-%'
						or pp_curso like '%(%'
						or pp_curso like '%ADM. EM%'
						or pp_curso like '%ADMINISTRACAO DA GRADUACAO EM%'
						limit 10 ";
		$rlt = db_query($sql);
		$sql = '';
		while ($line = db_read($rlt)) {
			$curso = trim($line['pp_curso']);
			if (strpos($curso, '-') > 0) { $curso_novo = substr($curso, 0, strpos($curso, '-'));
				echo '<HR>' . $curso_novo . '<hr>';
			}
			if (strpos($curso, '(') > 0) { $curso_novo = substr($curso, 0, strpos($curso, '('));
			}
			if (strpos(' ' . $curso, 'ADM. EM') > 0) { $curso_novo = substr($curso, strpos($curso, 'ADM. EM') + 7, 200) . '[?]';
			}

			$fr = 'ADMINISTRACAO DA GRADUACAO EM';
			if (strpos(' ' . $curso, $fr) > 0) { $curso_novo = substr($curso, strpos($curso, 'ADM. EM') + strlen($fr) + 1, 200);
			}
			if (strpos($curso, ' - HAB.:') > 0) { $curso_novo = troca($curso, ' - HAB.:', ':');
			}
			$sql .= "update " . $this -> tabela . " set pp_curso = '" . trim($curso_novo) . "' where pp_curso = '" . $curso . "'; " . chr(13) . chr(10);
		}
		if (strlen($sql) > 0) {
			echo '<PRE>' . $sql . '</PRE>';
			$rr = db_query($sql);
		}
	}

	function row() {
		global $cdf, $cdm, $masc;
		$cdf = array('id_pp', 'pp_nome', 'pp_cracha', 'pp_cpf', 'pp_carga_semanal', 'pp_ss', 'pp_centro', 'pp_curso');
		$cdm = array('cod', msg('nome'), msg('cracha'), msg('cpf'), msg('carga_semanal'), msg('Stricto Sensu'), msg('centro'), msg('curso'));
		$masc = array('', '', '', '', '', '', '');
		return (1);
	}

	function le($id) {
		global $http;
		if (strlen($id) > 0) { $this -> id_pp = $id;
		}
		$sql = "select * from " . $this -> tabela . " 
				left join apoio_titulacao on ap_tit_codigo = pp_titulacao
				left join centro on pp_escola = centro_codigo
				where (id_pp = " . round($this -> id_pp) . ") or (pp_cracha = '$id')";
		$rlt = db_query($sql);
		$prod = $this -> produtividade();

		if ($line = db_read($rlt)) {
			$this -> id_pp = $line['id_pp'];
			$this -> pp_nome = $line['pp_nome'];
			$this -> pp_nasc = $line['pp_nasc'];
			$this -> pp_codigo = $line['pp_codigo'];
			$this -> pp_cracha = $line['pp_cracha'];
			$this -> pp_login = $line['pp_login'];
			$this -> pp_escolaridade = $line['ap_tit_titulo'];
			$this -> pp_escola = $line['centro_nome'];

			//$this->pp_titulacao= $line['pp_titulacao'];
			$this -> pp_titulacao = $line['ap_tit_titulo'];

			$this -> pp_carga_semanal = $line['pp_carga_semanal'];
			$this -> pp_ss = $line['pp_ss'];
			$this -> pp_cpf = $line['pp_cpf'];
			$this -> pp_negocio = $line['pp_negocio'];
			$this -> pp_centro = $line['pp_centro'];
			$this -> pp_curso = $line['pp_curso'];
			$this -> pp_telefone = $line['pp_telefone'];
			$this -> pp_celular = $line['pp_celular'];
			$this -> pp_lattes = $line['pp_lattes'];
			$this -> pp_email = $line['pp_email'];
			$this -> pp_email_1 = $line['pp_email_1'];
			$this -> pp_senha = $line['pp_senha'];
			$this -> pp_endereco = $line['pp_endereco'];
			$this -> pp_afiliacao = $line['pp_afiliacao'];
			$this -> pp_ativo = $line['pp_ativo'];
			$this -> pp_grestudo = $line['pp_grestudo'];
			$this -> pp_prod = $prod[$line['pp_prod']];
			$this -> pp_ass = $line['pp_ass'];
			$this -> pp_instituicao = $line['pp_instituicao'];
			$this -> pp_update = $line['pp_update'];
			$this -> pp_pagina = 'http://www2.pucpr.br/reol/a.php?dd0=' . trim($this -> pp_cracha) . '&dd90=' . substr(md5('pesquisador' . $this -> pp_cracha), 0, 2);
			$this -> pp_avaliador = $line['pp_avaliador'];

			/* ShortLink */
			$cracha = trim($line['pp_cracha']);
			$link = http . 'apb.php?dd0=' . $cracha;
			$link .= '&dd90=' . substr(checkpost($cracha), 2, 2);
			$link .= '&dd99=main';
			$this -> post_link = $link;
			$link = '<A HREF="' . $link . '" target="_new">(link de acesso)</A>';
			$link .= ' (<A HREF="#" onclick="newxy2(\'' . $http . 'docente_enviar_senha.php?dd0=' . $line['id_pp'] . '\',600,400);"><img src="' . $http . '/img/icone_email.png" height="20"></A>)';
			$this -> shortlink = $link;
			$this -> line = $line;
			return (1);
		}
		return (0);
	}

	function sobre_corpo_docente($mod = '', $ss = '') {
		$sql = "select * from " . $this -> tabela . "
					inner join apoio_titulacao on pp_titulacao = ap_tit_codigo
					where pp_ativo = 1
					and pp_centro <> 'DOUTORANDO'
					and pp_update = '" . date("Y") . "'
					";

		$ch40 = 0;
		$ch20 = 0;
		$ch10 = 0;
		$ss = 0;
		$rst = array();
		array_push($rst, array('Dr.', 0, 0, 0, 0, 0));
		array_push($rst, array('Msc.', 0, 0, 0, 0, 0));
		array_push($rst, array('Esp.', 0, 0, 0, 0, 0));
		array_push($rst, array('Gra.', 0, 0, 0, 0, 0));
		array_push($rst, array('Outros', 0, 0, 0, 0, 0));
		$rlt = db_query($sql);
		$tot = 0;
		while ($line = db_read($rlt)) {
			$tot++;
			$ch40 = 0;
			$ch20 = 0;
			$ch10 = 0;
			$ss = 0;

			$tit = trim($line['ap_tit_titulo']);
			$id_tit = -1;
			if ($tit == 'Dr.') { $id_tit = 0;
			}
			if ($tit == 'Dra.') { $id_tit = 0;
			}
			if ($tit == 'Msc.') { $id_tit = 1;
			}
			if ($tit == 'Esp.') { $id_tit = 2;
			}
			if ($tit == 'Grad.') { $id_tit = 3;
			}
			if ($tit == 'PhD') { $id_tit = 0;
			}

			if ($id_tit == -1) { echo 'Erro ' . $tit;
				exit ;
			}

			$ch = trim($line['pp_carga_semanal']);
			$ss = trim($line['pp_ss']);
			if ($ss == 'S') { $ss = 1;
			}
			if ($ch == 40) { $ch40 = 1;
			}
			if (($ch >= 20) and ($ch < 40)) { $ch20 = 1;
			}
			if ($ch < 20) { $ch10 = 1;
			}

			$rst[$id_tit][1] = $rst[$id_tit][1] + $ch40;
			$rst[$id_tit][2] = $rst[$id_tit][2] + $ch20;
			$rst[$id_tit][3] = $rst[$id_tit][3] + $ch10;
			$rst[$id_tit][4] = $rst[$id_tit][4] + $ss;

		}
		$sx = '<CENTER><h2>Sobre o corpo docente</h2>';
		$sx .= '<table width="704" border=1 class="tabela00">';
		$sx .= '<TR><TH>Titulação<TH>40 horas<TH>20-39 horas<TH>1-19 horas<TH>Stricto<BR>Sensu<TH>Proporção';
		$sx .= '<TR><TD class="tabela01">Dr.';
		$sx .= '<TD class="tabela01" align="center">' . $rst[0][1];
		$sx .= '<TD class="tabela01" align="center">' . $rst[0][2];
		$sx .= '<TD class="tabela01" align="center">' . $rst[0][3];
		$sx .= '<TD class="tabela01" align="center">' . $rst[0][4];
		if ($tot > 0) {
			$drs = $rst[0][1] + $rst[0][2] + $rst[0][3];
			$sx .= '<TD align="center">' . number_format(($drs / $tot * 100), 1) . '%';
		}

		$sx .= '<TR><TD class="tabela01">Msc.';
		$sx .= '<TD class="tabela01" align="center">' . $rst[1][1];
		$sx .= '<TD class="tabela01" align="center">' . $rst[1][2];
		$sx .= '<TD class="tabela01" align="center">' . $rst[1][3];
		$sx .= '<TD class="tabela01" align="center">' . $rst[1][4];
		if ($tot > 0) {
			$drs = $rst[1][1] + $rst[1][2] + $rst[1][3];
			$sx .= '<TD align="center">' . number_format(($drs / $tot * 100), 1) . '%';
		}

		$sx .= '<TR><TD class="tabela01">Esp.';
		$sx .= '<TD class="tabela01" align="center">' . $rst[2][1];
		$sx .= '<TD class="tabela01" align="center">' . $rst[2][2];
		$sx .= '<TD class="tabela01" align="center">' . $rst[2][3];
		$sx .= '<TD class="tabela01" align="center">' . $rst[2][4];
		if ($tot > 0) {
			$drs = $rst[2][1] + $rst[2][2] + $rst[2][3];
			$sx .= '<TD align="center">' . number_format(($drs / $tot * 100), 1) . '%';
		}

		$sx .= '<TR><TD class="tabela01">Grad.';
		$sx .= '<TD class="tabela01" align="center">' . $rst[3][1];
		$sx .= '<TD class="tabela01" align="center">' . $rst[3][2];
		$sx .= '<TD class="tabela01" align="center">' . $rst[3][3];
		$sx .= '<TD class="tabela01" align="center">' . $rst[3][4];
		if ($tot > 0) {
			$drs = $rst[3][1] + $rst[3][2] + $rst[3][3];
			$sx .= '<TD align="center">' . number_format(($drs / $tot * 100), 1) . '%';
		}

		$tot_dr = $rst[0][1] + $rst[1][1] + $rst[2][1] + $rst[3][1];
		$tot_msc = $rst[0][2] + $rst[1][2] + $rst[2][2] + $rst[3][2];
		$tot_esp = $rst[0][3] + $rst[1][3] + $rst[2][3] + $rst[3][3];
		$tot_gra = $rst[0][4] + $rst[1][4] + $rst[2][4] + $rst[3][4];

		if ($tot > 0) {
			$tot_dr .= ' (' . number_format($tot_dr / $tot * 100, 1) . '%)';
			$tot_msc .= ' (' . number_format($tot_msc / $tot * 100, 1) . '%)';
			$tot_esp .= ' (' . number_format($tot_esp / $tot * 100, 1) . '%)';
			$tot_gra .= ' (' . number_format($tot_gra / $tot * 100, 1) . '%)';
		}
		$sx .= '<TR><TD><B>Total</B>';
		$sx .= '<TD align="center">' . ($tot_dr);
		$sx .= '<TD align="center">' . ($tot_msc);
		$sx .= '<TD align="center">' . ($tot_esp);
		$sx .= '<TD align="center">' . ($tot_gra);
		$sx .= '<TD class="tabela01" align="center">-';

		$sx .= '<TR><TD colspan=6 class="tabela01"><I>Total de docentes: ' . $tot;
		$sx .= '</table>';
		return ($sx);
	}

	function processar_cursos() {
		global $cs;
		$sql = "select * from pibic_professor 
						where pp_curso_cod = '' 
						and pp_curso like '%Biologia%'
						limit 1";
		$rlt = db_query($sql);
		if ($line = db_read($rlt)) {
			$curso = $line['pp_curso'];
			$cs -> curso_busca($curso);
			if (strlen(trim($cs -> curso_codigo)) > 0) {
				$sql = "update pibic_professor set 
						pp_curso_cod = '" . $cs -> curso_codigo . "' ,
						pp_escola = '" . $cs -> centro_codigo . "' ,
						pp_curso = '" . $cs -> curso_nome . "' 
						where id_pp = " . $line['id_pp'];
				$rlt = db_query($sql);
			}

		}
		return (1);
	}

	function rel_prof_produtividade_total() {
		$sql = 'select count(*) as total, pp_prod from ' . $this -> tabela . " 
				inner join centro on centro_codigo = pp_escola
			where pp_prod > 0 and pp_ativo = 1 and pp_update = '" . date("Y") . "'
			group by pp_prod
			order by pp_prod ";
		$rlt = db_query($sql);
		return ($rlt);
	}

	function rel_prof_produtividade() {
		$sql = 'select * from ' . $this -> tabela . " 
				left join centro on centro_codigo = pp_escola
			where pp_prod > 0 and pp_ativo = 1 and pp_update = '" . date("Y") . "'
			
			order by pp_nome ";
		$rlt = db_query($sql);
		return ($rlt);
	}

	function rel_prof_comite($tp = 1) {
		$sql = 'select * from ' . $this -> tabela . " 
				inner join centro on centro_codigo = pp_escola
			where pp_comite=" . $tp . " and pp_ativo = 1 
			order by pp_nome ";
		$rlt = db_query($sql);

		return ($rlt);
	}

	function rel_prof_prod_ss($ss) {
		if ($ss == 'S') {
			$where = "pp_ss = 'S'";
		} else {
			if ($ss == 'N') { $where = "pp_ss <> 'S'";
			} else { $where = "(pp_ativo = 1 )";
			}

		}
		$wh = 'and (la_ano = \'2010\' or la_ano = \'2011\')';
		$sql = 'select * from ' . $this -> tabela . '
				left join (
					select count(*) as total, sum(artigo) as artigo, sum(livro) as livro, sum(evento) as evento,
							sum(organizado) as organizado, la_professor from (
								select count(*) as artigo, 0 as livro, 0 as evento, 0 as organizado, la_professor from lattes_artigos where la_tipo = \'A\' ' . $wh . ' group by la_professor
								union 
								select 0 as artigo, count(*) as livro, 0 as evento, 0 as organizado, la_professor from lattes_artigos where (la_tipo = \'L\'  or la_tipo = \'O\') ' . $wh . ' group by la_professor
								union 
								select 0 as artigo, 0 as livro, count(*) as evento, 0 as organizado, la_professor from lattes_artigos where la_tipo = \'E\' ' . $wh . ' group by la_professor
								union 
								select 0 as artigo, 0 as livro, 0 as evento, count(*) as organizado, la_professor from lattes_artigos where la_tipo = \'C\' ' . $wh . ' group by la_professor
							) as tabela group by la_professor					
				) as tabprof on la_professor = pp_cracha
				left join centro on centro_codigo = pp_escola
			where ' . $where . ' and pp_ativo = 1 
			and pp_centro <> \'DOUTORANDO\'
			
			order by centro_nome, pp_nome ';

		$rlt = db_query($sql);
		return ($rlt);
	}

	function rel_prof_pibic_ss($ss) {
		if ($ss == 'S') {
			$where = "pp_ss = 'S'";
		} else {
			if ($ss == 'N') { $where = "pp_ss <> 'S'";
			} else { $where = "(pp_ativo = 1 )";
			}

		}

		$sql = "select count(*) as total, pp_nome from pibic_bolsa_contempladas ";
		$sql .= " inner join docentes on pp_cracha = pb_professor ";
		$sql .= " inner join pibic_bolsa_tipo on pbt_codigo = pb_tipo ";
		$sql .= " where " . $where;
		$sql .= " and (pb_status <> 'C' and pb_status <> '@' )";
		$sql .= " and (pb_ano = '2010' or pb_ano = '2011')";
		$sql .= " group by pp_nome ";
		//$sql .= " and (pb_tipo = 'P' or pb_tipo = 'G' or pb_tipo = 'E')";
		//$sql .= " and (pb_tipo = 'V')";

		$rlt = db_query($sql);

		$sx .= '<table class="lt1">';
		$tot = 0;
		while ($line = db_read($rlt)) {
			$ano = trim($line['pb_ano']);
			//if (($ano == '2010') or ($ano == '2011'))
			{
				$tot++;
				$sx .= '<TR>';
				$sx .= '<TD>' . $line['pp_nome'];
				$sx .= '<TD>' . $line['pb_tipo'];
				$sx .= '<TD>' . $line['pb_ano'];
				$sx .= '<TD>' . $line['pbt_descricao'];
			}
		}
		$sx .= '<TR><TD colspan=5>Total ' . $tot;
		$sx .= '</table>';
		return ($sx);
	}

	function rel_prof_ss($ss) {
		if ($ss == 'S') {
			$where = "pp_ss = 'S'";
		} else {
			if ($ss == 'N') { $where = "pp_ss <> 'S'";
			} else { $where = "(pp_ativo = 1 )";
			}

		}

		$sql = 'select * from ' . $this -> tabela . ' 
				left join centro on centro_codigo = pp_escola
			where ' . $where . ' and pp_ativo = 1 
			and pp_centro <> \'DOUTORANDO\'
			order by pp_nome ';

		$rlt = db_query($sql);
		return ($rlt);
	}

	function rel_prof_ss_prog($ss) {
		$sql = 'select * from ' . $this -> tabela . ' 
				left join centro on centro_codigo = pp_escola
				left join programa_pos_docentes on pp_cracha = pdce_docente and pdce_ativo = 1
				left join programa_pos on pos_codigo = pdce_programa
				left join programa_pos_linhas on pdce_programa_linha = posln_codigo
			where pp_ativo = 1 and pp_ss = \'S\'
			and pp_centro <> \'DOUTORANDO\'
			order by pp_nome ';
		$rlt = db_query($sql);
		$sx .= '<table class="lt1">';
		$sx .= '<TR><TH>Docente<TH>Programa<TH>Linha';
		$xprof = "x";
		$tot = 0;
		while ($line = db_read($rlt)) {
			$prof = trim($line['pp_nome']);
			$ln = $line;
			$sx .= '<TR ' . coluna() . '>';
			$sx .= '<TD>';
			if ($xprof != $prof) {
				$tot++;
				$sx .= trim($line['pp_nome']);
				$xprof = $prof;
			} else {
				$sx .= '&nbsp;';
			}
			$sx .= '<TD>';
			$sx .= trim($line['pos_nome']);
			$sx .= '<TD>';
			$sx .= trim($line['posln_descricao']);
		}
		$sx .= '<TR><TD colspan=4>Total de ' . $tot . ' professores Stricto Sensu';
		$sx .= '</table>';

		echo $sx;
		exit ;
		return ($sx);
	}

	function link_lattes($link) {
		$lattes = trim($link);
		if (strlen($lattes) > 0) { $lattes = '<A HREF="' . $lattes . '" target="_new">';
		}
		$lattes = troca($lattes, '.jsp?', '.do?');
		$lattes . '</A>';
		return ($lattes);
	}

	function produtividade() {

		$pd = array(0 => ' ', 1 => '--', 2 => 'Nível PQ 1A', 3 => 'Nível PQ 1B', 4 => 'Nível PQ 1C', 5 => 'Nível PQ 1D', 6 => 'Nível PQ 2', 12 => 'Nível DT 1A', 13 => 'Nível DT 1B', 14 => 'Nível DT 1C', 15 => 'Nível DT 1D', 16 => 'Nível DT 2', 20 => 'Nível PQ (FA)');
		return ($pd);
	}

	function rel_prof_prod_mostra($rlt) {
		global $tab_max;
		$sx .= '<table width="' . $tab_max . '" class="lt1">';
		$sx .= '<TR><TH>Nome<TH>Tót.<TH>Produtivade<TH>SS<TH>Cracha<TH>Campus<TH>Atualizado';
		$sx .= '<TH>Art.<TH>Livro<TH>Event.<TH>Cap.Livro<TH>Total';
		$tot = 0;
		$tot1 = 0;
		$tot2 = 0;
		$tot3 = 0;
		$tot4 = 0;

		$tot1a = 0;
		$tot2a = 0;
		$tot3a = 0;
		$tot4a = 0;
		$xcentro = 'X';
		$prod = $this -> produtividade();
		while ($line = db_read($rlt)) {
			$tot1 = $tot1 + $line['artigo'];
			$tot2 = $tot2 + $line['livro'];
			$tot3 = $tot3 + $line['evento'];
			$tot4 = $tot4 + $line['organizado'];

			$tot1a = $tot1a + $line['artigo'];
			$tot2a = $tot2a + $line['livro'];
			$tot3a = $tot3a + $line['evento'];
			$tot4a = $tot4a + $line['organizado'];

			$tot++;

			$centro = trim($line['centro_nome']);
			if ($centro != $xcentro) {
				$xcentro = $centro;
				if (($tot1a + $tot2a + $tot3a + $tot4a) > 0) {
					$sx .= '<TR><TD colspan=7 align="right">sub-total';
					$sx .= '<TD align="center">' . $tot1a;
					$sx .= '<TD align="center">' . $tot2a;
					$sx .= '<TD align="center">' . $tot3a;
					$sx .= '<TD align="center">' . $tot4a;
				}
				$sx .= '<TR><TD colspan=5>';
				$sx .= '<B><I>' . $centro;
				$tot1a = 0;
				$tot2a = 0;
				$tot3a = 0;
				$tot4a = 0;
			}

			$link = '<A HREF="docentes_detalhe.php?dd0=' . $line['id_pp'] . '&dd90=' . checkpost($line['id_pp']) . '">';
			$sx .= '<TR ' . coluna() . '>';
			$sx .= '<TD>';
			$sx .= $this -> link_lattes($line['pp_lattes']) . $line['pp_nome'] . '</A>';
			$sx .= '<TD>';
			$sx .= $line['pp_titulo'];
			$sx .= '<TD>';
			$sx .= $prod[$line['pp_prod']];
			$sx .= '<TD>';
			$sx .= $line['pp_ss'];
			$sx .= '<TD>';
			$sx .= $link;
			$sx .= $line['pp_cracha'];
			$sx .= '<TD>';
			$sx .= $line['pp_centro'];
			$sx .= '<TD align="center">';
			$sx .= $line['pp_update'];
			$sx .= '<TD align="center">';
			$sx .= $line['artigo'];
			$sx .= '<TD align="center">';
			$sx .= $line['livro'];
			$sx .= '<TD align="center">';
			$sx .= $line['evento'];
			$sx .= '<TD align="center">';
			$sx .= $line['organizado'];
			$sx .= '<TD align="center">';
			$sx .= $line['total'];
		}
		if (($tot1a + $tot2a + $tot3a + $tot4a) > 0) {
			$sx .= '<TR><TD colspan=7 align="right">sub-total';
			$sx .= '<TD align="center">' . $tot1a;
			$sx .= '<TD align="center">' . $tot2a;
			$sx .= '<TD align="center">' . $tot3a;
			$sx .= '<TD align="center">' . $tot4a;
		}

		$sx .= '<TR><TD colspan=7 align="right">Total';
		$sx .= '<TD align="center"><B>' . $tot1;
		$sx .= '<TD align="center"><B>' . $tot2;
		$sx .= '<TD align="center"><B>' . $tot3;
		$sx .= '<TD align="center"><B>' . $tot4;
		$sx .= '<TD align="center"><B>' . ($tot1 + $tot2 + $tot3 + $tot4);
		$sx .= '<TR><TD colspan=8><B>Total de ' . $tot . ' docentes nesta categoria';

		$sx .= '</table>';
		return ($sx);
	}

	function rel_prof_mostra($rlt) {
		global $tab_max;
		$sx .= '<table width="98%" class="tabela00" align="center">';
		$sx .= '<TR><TH>Nome<TH>Tót.<TH>Produtivade<TH>SS<TH>Cracha<TH>Campus<TH>Curso<TH>Escola<TH>Atualizado<TH>e-amil<TH>e-mail alt.';
		$tot = 0;
		$prod = $this -> produtividade();
		$prod_n = array('');
		$prod_t = array(0);

		while ($line = db_read($rlt)) {
			$tipo = trim($prod[$line['pp_prod']]);
			if (array_search($tipo, $prod_n)) {
				$i = array_search($tipo, $prod_n);
				$prod_t[$i] = $prod_t[$i] + 1;
			} else {
				array_push($prod_n, $tipo);
				array_push($prod_t, 1);
			}
			$tot++;
			$link = '<A HREF="docentes_detalhe.php?dd0=' . $line['id_pp'] . '&dd90=' . checkpost($line['id_pp']) . '">';
			$sx .= '<TR ' . coluna() . '>';
			$sx .= '<TD class="tabela01"><nobr>';
			$sx .= $this -> link_lattes($line['pp_lattes']) . trim($line['pp_nome']) . '</A>';
			$sx .= '<TD class="tabela01">';
			$sx .= $line['pp_titulo'];
			$sx .= '<TD class="tabela01">';
			$sx .= $prod[$line['pp_prod']];
			$sx .= '<TD class="tabela01">';
			$sx .= $line['pp_ss'];
			$sx .= '<TD class="tabela01">';
			$sx .= $link;
			$sx .= $line['pp_cracha'];
			$sx .= '<TD class="tabela01">';
			$sx .= $line['pp_centro'];
			$sx .= '<TD class="tabela01">';
			$sx .= $line['pp_curso'];
			$sx .= '<TD class="tabela01">';
			$sx .= $line['centro_nome'];
			$sx .= '<TD align="center" class="tabela01">';
			$sx .= $line['pp_update'];
			$sx .= '<TD align="center" class="tabela01">';
			$sx .= $line['pp_email'];
			$sx .= '<TD align="center" class="tabela01">';
			$sx .= $line['pp_email_1'];
		}
		$sx .= '<TR><TD colspan=8><B>Total de ' . $tot . ' docentes nesta categoria';
		$sx .= '</table>';

		$sx .= '<table class="tabela00" width="400">';
		$tot = 0;
		for ($r = 1; $r < count($prod_n); $r++) {
			$tot = $tot + $prod_t[$r];
			$sx .= '<TR><TD class="tabela01">' . $prod_n[$r] . '
							<TD class="tabela01" align="center">' . $prod_t[$r];
		}
		$sx .= '<TR><TD><TD align="center">' . $tot;
		$sx .= '</table>';
		return ($sx);
	}

	function recupera_foto() {
		global $nw;

		$img_photo = '<IMG SRC="' . http . 'cip/img/no_photo.jpg" border=0 width="130"  class="foto-perfil">';
		$fotod = "cip/img_prof/" . trim($this -> pp_cracha) . '.jpg';

		if (file_exists($fotod)) {
			$fotod = http . "cip/img_prof/" . trim($this -> pp_cracha) . '.jpg';
			$img_photo = '<IMG SRC="' . $fotod . '" border=0 width=150 class="foto-perfil">';
			return ($img_photo);
		}
		/* REMOVER */
		return ($img_photo);

		$lattes_link = trim($this -> pp_lattes);
		$lattes = new lattes;
		$lattes -> recupera_lattes($lattes_link);

		return ($img_photo);
	}

	function mostra_dados($link = 1) {
		global $tab_max;

		//$sql = "ALTER TABLE ".$this->tabela." ADD pp_livredocencia char(1) ";
		//$rlt = db_query($sql);
		//$sql = "ALTER TABLE ".$this->tabela." ADD pp_posdoc char(1) ";
		//$rlt = db_query($sql);

		$lattes = trim($this -> pp_lattes);
		$lattes = troca($lattes, '.jsp', '.do');
		if (strlen($lattes) > 10) {
			$lattes = '<a href="' . $lattes . '" target="new">';
			$lattes .= '<img src="' . http . 'img/icone_plataforma_lattes.png" height="35" border=0>';
			$lattes . '</A>';
		} else {
			$lattes = '';
		}
		$img_photo = $this -> recupera_foto();

		$ss = ($this -> pp_ss);
		if ($ss == 'S') { $ss = "SIM";
		} else { $ss = "NÃO";
		}
		// class="foto-perfil"

		$ativo = $this -> pp_ativo;

		//if ($ativo == 1)
		//	{ $ativo = ''; } else
		//	{ $ativo = '<li><h2><font color="red">Desligado da instituição</font></h2></li>'; }
		if ($link == 1) {
			$linkc = '<A class="link lt1" HREF="' . http . '/cip/captacao_novo.php?pag=1"><B>Cadastre projetos com captação de recursos</B></A>';
			$linka = '<A class="link lt1" HREF="' . http . '/cip/artigo_novo.php?pag=1"><B>Cadastro de artigos para bonificação</B></A>';
		}
		$pp = trim($this -> pp_prod);
		if (strlen($pp) == 0) { $pp = 'NÃO';
		}
		$sx = '
			<table id="cabecalho-user-perfil" class="info-pessoais" border=0>
			<TR>
			<TD width="150">
			<div id="foto-perfil">' . $img_photo . '</div>
			<TD>
			<div id="nome-dados-perfil">
				<li><B>' . $this -> pp_nome . '&nbsp;</B></li>
				<li>CPF: ' . $this -> pp_cpf . '</li>
				<li>' . $this -> pp_telefone . '</li>
				<li>' . $this -> pp_celular . '</li>
				<li>' . $this -> pp_email . '<BR>
				' . $this -> pp_email_1 . '</li>
				<li>' . mst($this -> pp_endereco) . '</li>
				' . $ativo . '
				<li>' . $lattes . '</li>
				<li>' . $linkc . '</li>
				<li>' . $linka . '</li>
				
			</div>
			<TD width="300">
			<div id="info-pesquisador" class="info-pesquisador lt1">
				<span class="lt2 titulo-info-pesquisador">Informaçães do Pesquisador</span><br /><br />
				<li><strong>Crachá:</strong> ' . $this -> pp_cracha . '</li>
				<li><strong>Maior titulação:</strong> ' . $this -> pp_escolaridade . '</li>				
				<li><strong>Curso:</strong> ' . $this -> pp_curso . '</li>
				<li><strong>Centro:</strong> ' . $this -> pp_centro . '</li>
				<li><strong>Escola:</strong> ' . $this -> pp_escola . '</li>
				<li><strong>Stricto Sensu:</strong> ' . $ss . '</li>
				<li><strong>Bolsa produtividade:</strong> ' . $pp . '&nbsp;</li>
				<li><strong>Carga horária:</strong> ' . $this -> pp_carga_semanal . ' horas</li>
				<li><strong>Dados Atualizados:</strong> ' . $this -> pp_update . '</li>
			</div>	
			</table>
			';
		return ($sx);
	}

	function mostra_dados_pessoais() {
		global $tab_max;

		$lattes = trim($this -> pp_lattes);
		$lattes = troca($lattes, '.jsp', '.do');
		if (strlen($lattes) > 0) { $lattes = '<a href="' . $lattes . '" target="new">' . $lattes . "</A>";
		}
		$img_photo = $this -> recupera_foto();

		$sx .= '<table width="100%" cellspacing=0 cellpadding=0>';
		$sx .= '<TR><TD>';
		$sx .= '<fieldset><legend>' . msg('dados_pessoais') . '</legend>';
		$sx .= '<table width="100%" cellspacing=0 cellpadding=0 border=0>';
		$sx .= '<TR class="lt0" >';
		$sx .= '<TD colspan=3>' . msg('nome_completo');
		$sx .= '<TD width=100 align="center">' . msg('photo');
		$sx .= '<TR class="lt1"><TD colspan=3><B>' . $this -> pp_nome . '&nbsp;';
		$sx .= '<TD rowspan=12 width=100 align="center">' . $img_photo;

		$sx .= '<TR class="lt0">';
		$sx .= '<TD>' . msg('titulacao');
		$sx .= '<TD colspan=2>' . msg('escolaridade');
		$sx .= '<TR class="lt1">';
		$sx .= '<TD colspan=1><B>' . $this -> pp_titulacao . '&nbsp;';
		$sx .= '<TD colspan=2><B>' . $this -> pp_escolaridade . '&nbsp;';

		$sx .= '<TR class="lt0">';
		$sx .= '<TD>' . msg('Bolsista Prod.');
		$sx .= '<TD>' . msg('Stricto Sensu');
		$sx .= '<TD>' . msg('Carga Horória');
		$sx .= '<TR class="lt1">';
		$ss = ($this -> pp_ss);
		if ($ss == 'S') { $ss = "SIM";
		} else { $ss = "NÃO";
		}
		$sx .= '<TD colspan=1><B>' . $this -> pp_prod . '&nbsp;';
		$sx .= '<TD colspan=1><B>' . ($ss . '&nbsp;');
		$sx .= '<TD colspan=1><B>' . $this -> pp_carga_semanal . '&nbsp;horas';

		$sx .= '<TR class="lt0">';
		$sx .= '<TD>' . msg('cracha');
		$sx .= '<TD>' . msg('codigo');
		$sx .= '<TR class="lt1">';
		$sx .= '<TD><B>' . $this -> pp_cracha . '&nbsp;';
		$sx .= '<TD><B>' . $this -> pp_codigo . '&nbsp;';

		$sx .= '<TR class="lt0"><TD colspan=4>' . msg('email');
		$sx .= '<TR class="lt1"><TD colspan=4><A HREF="mailto:' . $this -> pp_email . '">' . $this -> pp_email . "</A>&nbsp;";

		$sx .= '<TR class="lt0"><TD colspan=1>' . msg('email2');
		$sx .= '<TD colspan=2>' . msg('telefone');
		$sx .= '<TR class="lt1"><TD colspan=1><A HREF="mailto:' . $this -> pp_email_1 . '">' . $this -> pp_email_1 . "</A>&nbsp;";
		$sx .= '<TD colspan=2><B>' . $this -> pp_telefone . ' ' . $this -> pp_celular;

		$sx .= '<TR class="lt0"><TD colspan=4>' . msg('lattes');
		$sx .= '<TR class="lt1"><TD colspan=4>' . $lattes . '&nbsp;';

		$sx .= '<TR class="lt0">';
		$sx .= '<TD>' . msg('centro');
		$sx .= '<TD>' . msg('negocio');
		$sx .= '<TD colspan=2>' . msg('curso');
		$sx .= '<TR class="lt1">';
		$sx .= '<TD>' . $this -> pp_centro . '&nbsp;';
		$sx .= '<TD>' . $this -> pp_negocio . '&nbsp;';
		$sx .= '<TD colspan=2>' . $this -> pp_curso . '&nbsp;';

		$sx .= '<TR class="lt0">';
		$sx .= '<TD colspan=3>' . msg('link do pesquisador');
		$sx .= '<TR class="lt1">';
		$sx .= '<TD colspan=3><A HREF="' . $this -> pp_pagina . '" target="new">' . $this -> pp_pagina . '</A>&nbsp;';

		$sx .= '</table>';
		$sx .= '</table>';
		return ($sx);
	}

	function mostra() {
		global $tab_max;

		$lattes = trim($this -> pp_lattes);
		$lattes = troca($lattes, '.jsp', '.do');
		if (strlen($lattes) > 0) { $lattes_img = '<a href="' . $lattes . '" target="new"><img src="' . http . '/img/logolattes.gif" border=0></A>';
		}
		if (strlen($lattes) > 0) { $lattes = '<a href="' . $lattes . '" target="new">' . $lattes . "</A>";
		}
		$img_photo = $this -> recupera_foto();

		$sx .= '<table width="100%" cellspacing=0 cellpadding=0>';
		$sx .= '<TR><TD>';
		$sx .= '<fieldset><legend>' . msg('dados_pessoais') . '</legend>';
		$sx .= '<table width="100%" cellspacing=0 cellpadding=0 border=0>';
		$sx .= '<TR class="lt0" >';
		$sx .= '<TD colspan=3>' . msg('nome_completo');
		$sx .= '<TD width=100 align="center">' . msg('photo');
		$sx .= '<TR class="lt1"><TD colspan=3><B>' . $this -> pp_nome . '&nbsp;' . $lattes_img;
		$sx .= '<TD rowspan=8 width=100 align="center">' . $img_photo;

		$sx .= '<TR class="lt0">';
		$sx .= '<TD>' . msg('titulacao');
		$sx .= '<TD colspan=1>' . msg('escolaridade');
		$sx .= '<TD>' . msg('cracha');
		$sx .= '<TR class="lt1">';
		$sx .= '<TD colspan=1><B>' . $this -> pp_titulacao . '&nbsp;';
		$sx .= '<TD colspan=1><B>' . $this -> pp_escolaridade . '&nbsp;';
		$sx .= '<TD><B>' . $this -> pp_cracha . '&nbsp;';

		$sx .= '<TR class="lt0">';
		$sx .= '<TD>' . msg('Bolsista Prod.');
		$sx .= '<TD>' . msg('Stricto Sensu');
		$sx .= '<TD>' . msg('Carga Horória');
		$sx .= '<TR class="lt1">';
		$ss = ($this -> pp_ss);
		if ($ss == 'S') { $ss = "SIM";
		} else { $ss = "NÃO";
		}
		$sx .= '<TD colspan=1><B>' . $this -> pp_prod . '&nbsp;';
		$sx .= '<TD colspan=1><B>' . ($ss . '&nbsp;');
		$sx .= '<TD colspan=1><B>' . $this -> pp_carga_semanal . '&nbsp;horas';

		$sx .= '<TR class="lt0"><TD colspan=1>' . msg('email');
		$sx .= '<TD colspan=1>' . msg('email2');
		$sx .= '<TR class="lt1"><TD colspan=1><A HREF="mailto:' . $this -> pp_email . '">' . $this -> pp_email . "</A>&nbsp;";
		$sx .= '<TD colspan=1><A HREF="mailto:' . $this -> pp_email_1 . '">' . $this -> pp_email_1 . "</A>&nbsp;";

		$sx .= '<TR class="lt0">';
		$sx .= '<TD>' . msg('centro');
		$sx .= '<TD>' . msg('negocio');
		$sx .= '<TD colspan=2>' . msg('curso');
		$sx .= '<TR class="lt1">';
		$sx .= '<TD>' . $this -> pp_centro . '&nbsp;';
		$sx .= '<TD>' . $this -> pp_negocio . '&nbsp;';
		$sx .= '<TD colspan=2>' . $this -> pp_curso . '&nbsp;';

		$sx .= '</table>';
		$sx .= '</table>';
		return ($sx);
	}

	function updatex() {
		$sql = "select * from " . $this -> tabela . " where pp_nome_asc = ''";
		$rlt = db_query($sql);
		$sqlx = '';
		while ($line = db_read($rlt)) {
			$sqlx .= "update " . $this -> tabela . " set pp_nome_asc = '" . UpperCaseSql($line['pp_nome']) . "' 
						where id_pp = " . $line['id_pp'] . ';' . chr(13);
		}
		$xrlt = db_query($sqlx);
		return (1);
	}

}
?>
