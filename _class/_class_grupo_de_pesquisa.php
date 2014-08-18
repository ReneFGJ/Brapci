<?php
/**
 * Classe Grupo de Pesquisa
 * @author Rene Faustino Gabriel Junior  (Analista-Desenvolvedor)
 * @copyright Copyright (c) 2011 - sisDOC.com.br
 * @access public
 * @version v0.11.34
 * @package Classe
 * @subpackage UC0001 - Classe Grupo de Pesquisa
 */

class grupo_de_pesquisa {
	var $id_gp;
	var $gp_codigo;
	var $gp_nome;
	var $gp_curso;
	var $gp_ano_formacao;
	var $gp_area_1;
	var $gp_contexto;
	var $gp_objetivo;
	var $gp_status;
	var $gp_cert_cnpq;
	var $gp_pp;
	var $gp_link_cnpq;
	var $gp_telefone;
	var $gp_endereco;
	var $gp_repercursao;
	var $gp_site;
	var $gp_unidade;
	var $gp_instituicao;
	var $gp_update;
	var $gp_ata;
	var $gp_cnpq_certificado;
	var $gp_certificado;
	var $gp_cnpq_cod;
	var $gp_pages;

	var $membros;

	var $tabela = 'grupo_de_pesquisa';

	function lista_grupos($status = '') {
		
		$sql = "select * from " . $this -> tabela . " 
					where gp_status = '$status' 
					order by gp_nome
					";
		$rlt = db_query($sql);

		while ($line = db_read($rlt)) {
			$this -> id_gp = $line['id_gp'];
			$this -> gp_codigo = $line['gp_codigo'];
			$this -> gp_nome = $line['gp_nome'];
			$this -> gp_curso = $line['gp_curso'];
			$this -> gp_ano_formacao = $line['gp_ano_formacao'];
			$this -> gp_area_1 = $line['gp_area_1'];
			$this -> gp_contexto = $line['gp_contexto'];
			$this -> gp_objetivo = $line['gp_objetivo'];
			$this -> gp_status = $line['gp_status'];
			$this -> gp_cert_cnpq = $line['gp_cert_cnpq'];
			$this -> gp_pp = $line['gp_pp'];
			$this -> gp_link_cnpq = $line['gp_link_cnpq'];
			$this -> gp_unidade = $line['gp_unidade'];
			$this -> gp_instituicao = $line['gp_instituicao'];
			$this -> gp_update = $line['gp_update'];
			$this -> gp_ata = $line['gp_ata'];
			$this -> gp_site = $line['gp_site'];
			$this -> gp_repercursao = $line['gp_repercursao'];
			$this -> gr_area_01 = $line['gp_area_01'];
			$this -> gr_area_02 = $line['gp_area_02'];

			$this -> gp_cnpq_certificado = $line['gp_cnpq_certificado'];
			$this -> gp_certificado = $line['gp_certificado'];
			$this -> gp_cnpq_cod = trim(substr($this -> gp_link_cnpq, strpos($this -> gp_link_cnpq, '=') + 1, 100));
			$this -> gp_pages = trim($line['gp_pages']);
			$this -> membros = $this -> grupo_de_pesquisa_membros();

			$sx .= $this -> mostra_dados();
		}
		return ($sx);
	}

	function grupos_validados_por_area() {
		
		$sql = "select * from " . $this -> tabela . " 
					where gp_ativo = 1
					order by gp_area_01, gp_area_02
			
			";
		$rlt = db_query($sql);

		$xarea = '';
		$xsubarea = '';
		while ($line = db_read($rlt)) {
			$area = trim($line['gp_area_01']);
			$subarea = trim($line['gp_area_02']);
			$mk = 0;
			if ($xarea != $area) {
				if (strlen($sx) > 0) { $sx .= '</UL>';
					$mk = 1;
				}
				$sx .= '<h3>' . $area . '</h3>';
				$xarea = $area;
			}

			if ($xsubarea != $subarea) {
				if ($mk == 0) { $sx .= '</UL>';
					$mk = 1;
				}
				$xsubarea = $subarea;
				$sx .= '<B>' . $subarea . '</B><UL>';
			}

			$link = '<A HREF="grupo_de_pesquisa_detalhes.php?dd0=' . $line['gp_codigo'] . '&dd90=' . checkpost($line['gp_codigo']) . '">';
			$sx .= '<li>' . $link . trim($line['gp_nome']) . '</A></li>';
			//print_r($line);
			//echo '<HR>';
		}
		if (strlen($sx) > 0) { $sx .= '</UL>';
		}
		return ($sx);
	}

	function grupos_areas_resumo() {
		$this -> updatex();

		$sql = "select count(*) as grupos, 
					gp_area_01, sum(totall) as linhas 
					, sum(gp_pesquisadores) as pesq
					, sum(gp_estudantes) as estu
					, sum(gp_tecnicos) as tecn
					 from " . $this -> tabela . "
					inner join ( 
						select count(*) as totall, lpg_grupo 
							from linha_de_pesquisa_grupo 
							inner join " . $this -> tabela . " on lpg_grupo = gp_codigo
							where lpg_grupo <> ''
							group by lpg_grupo
						) as tabela on lpg_grupo = gp_codigo 
					where gp_status <> 'X'
					group by gp_area_01
					order by gp_area_01
			";

		$rlt = db_query($sql);
		$sx = '<table class="tabela00" width="100%">
					<TR><TH>Áreas
						<TH>Grupos<BR>G
						<TH>Pesquisadores<BR>P
						<TH>Doutores<BR>D
						<TH>Estudantes<BR>E
						<TH>Técnicos<BR>T
						<TH>Linhas de Pesquisa<BR>L
						<TH>L/G
						<TH>P/G
						<TH>E/G
						<TH>P/L
					';
		$tot0 = 0;
		$tot1 = 0;
		$tot2 = 0;
		$tot3 = 0;
		$tot4 = 0;
		$tot8 = 0;

		while ($line = db_read($rlt)) {
			$tot0 = $tot0 + $line['pesq'];
			$tot1 = $tot1 + $line['estu'];
			$tot2 = $tot2 + $line['tecn'];
			$tot3 = $tot3 + $line['linhas'];
			$tot8 = $tot8 + $line['grupos'];
			$tot1A = $tot1A + round($line['pesq'] * 0.7);

			$sx .= '<TR>';
			$sx .= '<TD>' . $line['gp_area_01'];
			$sx .= '<TD class="tabela01 lt2" align="center">' . $line['grupos'];
			$sx .= '<TD class="tabela01 lt2" align="center">' . $line['pesq'];
			$sx .= '<TD class="tabela01 lt2" align="center">' . round($line['pesq'] * 0.7);
			$sx .= '<TD class="tabela01 lt2" align="center">' . $line['estu'];
			$sx .= '<TD class="tabela01 lt2" align="center">' . $line['tecn'];
			$sx .= '<TD class="tabela01 lt2" align="center">' . $line['linhas'];
			$sx .= '<TD class="tabela01 lt2" align="center">' . number_format($line['linhas'] / $line['grupos'], 2, ',', '.');
			$sx .= '<TD class="tabela01 lt2" align="center">' . number_format($line['pesq'] / $line['grupos'], 2, ',', '.');
			$sx .= '<TD class="tabela01 lt2" align="center">' . number_format($line['estu'] / $line['grupos'], 2, ',', '.');
			$sx .= '<TD class="tabela01 lt2" align="center">' . number_format($line['pesq'] / $line['linhas'], 2, ',', '.');
		}
		$sx .= '<TR>';
		$sx .= '<TD>';
		$sx .= '<TD class="tabela00 lt3" align="center"><B>' . $tot8;
		$sx .= '<TD class="tabela00 lt3" align="center"><B>' . $tot0;
		$sx .= '<TD class="tabela00 lt3" align="center"><B>' . $tot1A;
		$sx .= '<TD class="tabela00 lt3" align="center"><B>' . $tot1;
		$sx .= '<TD class="tabela00 lt3" align="center"><B>' . $tot2;
		$sx .= '<TD class="tabela00 lt3" align="center"><B>' . $tot3;
		if ($tot8 > 0) {
			$sx .= '<TD class="tabela00 lt3" align="center"><B>' . number_format($tot3 / $tot8, 2, ',', '.');
			$sx .= '<TD class="tabela00 lt3" align="center"><B>' . number_format($tot1 / $tot8, 2, ',', '.');
			$sx .= '<TD class="tabela00 lt3" align="center"><B>' . number_format($tot2 / $tot8, 2, ',', '.');
			$sx .= '<TD class="tabela00 lt3" align="center"><B>' . number_format($tot0 / $tot3, 2, ',', '.');
		} else {
			$sx .= '<TD>&nbsp;';
		}
		$sx .= '</table>';

		return ($sx);
	}

	function grupos_resumo() {
		$sql = "select * from " . $this -> tabela . " 
					where gp_status <> 'X'
			";
		$rlt = db_query($sql);

		$rt = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

		while ($line = db_read($rlt)) {
			$nome = $line['gp_nome'];
			$area_01 = $line['gp_area_01'];
			$area_02 = $line['gp_area_02'];
			$pesq = $line['gp_pesquisadores'];
			$estu = $line['gp_estudantes'];
			$tecn = $line['gp_tecnicos'];

			$rt[1] = $rt[1] + 1;
			$rt[3] = $rt[3] + $pesq;
			$rt[4] = $rt[4] + $estu;
			$rt[5] = $rt[5] + $tecn;
		}

		/* Recupera total de Linhas de Pesquisa */
		$sql = "select * from " . $this -> tabela . "
					inner join linha_de_pesquisa_grupo on lpg_grupo = gp_codigo 
					where gp_status <> 'X'
			";
		$rlt = db_query($sql);
		while ($line = db_read($rlt)) {
			$rt[6] = $rt[6] + 1;
		}

		$rt[2] = round($rt[3] * (0.70));
		$rt[10] = $rt[3] / $rt[1];
		/* P/G */
		$rt[11] = $rt[2] / $rt[1];
		/* P/G */
		$rt[12] = $rt[6] / $rt[1];
		/* P/G */
		$rt[13] = $rt[4] / $rt[1];
		/* P/G */
		$rt[14] = $rt[5] / $rt[1];
		/* P/G */
		$rt[15] = $rt[6] / $rt[1];
		/* P/G */

		$sx = '<table class="tabela00" width="100%">
					<TR><TH>Grupos<BR>G
						<TH>Pesquisadores<BR>P
						<TH>Doutores<BR>D
						<TH>Estudantes<BR>E
						<TH>Técnicos<BR>T
						<TH>Linhas de Pesquisa<BR>L
						<TH>P/G
						<TH>D/G
						<TH>L/G
						<TH>T/G
						<TH>G/E
					<TR><TD class="tabela01 lt3" align="center">' . $rt[1] . '
						<TD class="tabela01 lt3" align="center">' . $rt[3] . '
						<TD class="tabela01 lt3" align="center">' . $rt[2] . '
						<TD class="tabela01 lt3" align="center">' . $rt[4] . '
						<TD class="tabela01 lt3" align="center">' . $rt[5] . '
						
						<TD class="tabela01 lt3" align="center">' . $rt[6] . '
						<TD class="tabela01 lt3" align="center">' . number_format($rt[10], 2, ',', '.') . '
						<TD class="tabela01 lt3" align="center">' . number_format($rt[11], 2, ',', '.') . '
						<TD class="tabela01 lt3" align="center">' . number_format($rt[12], 2, ',', '.') . '
						<TD class="tabela01 lt3" align="center">' . number_format($rt[13], 2, ',', '.') . '
						<TD class="tabela01 lt3" align="center">' . number_format($rt[14], 2, ',', '.') . '
				';
		$sx .= '</table>';

		return ($sx);
	}

	function grupos_de_pesquisa_relacao() {
		
		$sql = "select * from " . $this -> tabela . " 
					where gp_ativo = 1
					order by gp_nome
			";
		$rlt = db_query($sql);
		$sx = '<table width="100%" class="tabela00">';
		$sx .= '<TR><TD colspan=10><center>';
		$sx .= '<H4>Relação dos Grupos de Pesquisa Ativos</h4>';
		$sx .= '<TR><TH width="70%">Nome do grupo<TH width="20%"><I>Status<TH width="10%">Atualizado';
		$id = 0;
		$xnome = "x";
		while ($line = db_read($rlt)) {
			$cor = '';
			$nome = trim($line['gp_nome']);
			if ($xnome == $nome) { $cor = '<font color="red">';
			}
			$id++;
			$sx .= '<TR>';
			$sx .= '<TD>';
			$sx .= $cor;
			$sx .= trim($line['gp_nome']);
			$sx .= '<TD>';
			$sx .= $cor;
			$sta = trim($line['gp_status']);
			if ($sta == 'A') { $sta = 'Em cadastro';
			}
			if ($sta == 'B') { $sta = 'Falta documentos';
			}
			if ($sta == 'C') { $sta = 'Ativo';
			}
			if ($sta == 'D') { $sta = 'Encerrado';
			}
			if ($sta == 'X') { $sta = 'Cancelado';
			}
			$sx .= $sta;
			$sx .= '<TD>';
			$sx .= $cor;
			$sx .= stodbr($line['gp_data_autorizacao']);
			$xnome = $nome;
		}
		$sx .= '<TR><TD colspan=10><B>Total de ' . $id . ' grupos cadastrados';
		$sx .= '</table>';
		return ($sx);

	}

	/* importar dados */
	function import_dados() {
		$s = $this -> gp_pages;
		$s = troca($s, '&nbsp;', ' ');
		echo $s;

	}

	function recupera_id_do_grupo($nome) {
		$this -> id = '';
		$sql = "select id_gp, gp_nome from " . $this -> tabela . "  
					where upper(asc7(gp_nome)) = '" . uppercasesql($nome) . "' ";

		$rlt = db_query($sql);
		if ($line = db_read($rlt)) {
			$this -> id = $line['id_gp'];
			return ($line['id_gp']);
		} else {
			echo 'Not FOUND';
		}
		return ('');
	}

	function cp_membros() {
		$cp = array();
		array_push($cp, array('$H8', '', 'id_gp', False, True, ''));
		array_push($cp, array('$H8', '', '', False, True, ''));
		array_push($cp, array('${', '', 'Dados do professor', False, True, ''));
		array_push($cp, array('$S8', '', 'Crachá do professor', True, True, ''));
		array_push($cp, array('$O : &L:Lider&P:Participante', '', 'Categoria', True, True, ''));
		array_push($cp, array('$B8', '', 'Adicionar >>>', False, True, ''));
		array_push($cp, array('$}', '', '', False, True, ''));
		return ($cp);
	}

	function cp_novo_grupo() {
		//$tabela = "grupo_de_pesquisa";
		$cp = array();
		array_push($cp, array('$H8', 'id_gp', 'id_gp', False, True, ''));
		array_push($cp, array('$S250', 'gp_nome', 'Nome do grupo', True, True, ''));
		//array_push($cp,array('$Q crs_nome:crs_codigo:select * from cursos where crs_ativo=1 order by crs_nome ','gp_curso','Curso de Graduação/Programa de Pós-Graduação',True,True,''));
		array_push($cp, array('$[1970-' . date("Y") . ']', 'gp_ano_formacao', 'Ano de formação', True, True, ''));
		array_push($cp, array('$H8', '', '', False, True, ''));
		$sql = "a_descricao:a_cnpq:select * from ajax_areadoconhecimento where a_semic = '1' and a_cnpq like '%00.00%' and not a_cnpq like '%00.00.00%' order by a_descricao  ";
		array_push($cp, array('$Q ' . $sql, 'gp_area_1', 'Área do Conhecimento', False, True, ''));
		array_push($cp, array('$T60:6', 'gp_repercursao', 'Objetivos do Grupo', False, True, ''));
		//array_push($cp,array('$T60:3','gp_comentarios','Comentários (interno)',False,True,''));
		array_push($cp, array('$O 1:SIM&0:NÃO', 'gp_ativo', 'Ativo', True, True, ''));
		array_push($cp, array('$S150', 'gp_site', 'Site do Grupo', False, True, ''));
		//	array_push($cp,array('$S150','gp_link_cnpq','Link do CNPq',False,True,''));
		//	array_push($cp,array('$O : &1:SIM&0:NÃO','gp_cnpq_certificado','Certificado no CNPq',True,True,''));
		//	array_push($cp,array('$D8','gp_data_autorizacao','Data do parecer da Pró-Reitoria Acadêmica',True,True,''));
		array_push($cp, array('$HV', 'gp_status', '!', False, True, ''));
		array_push($cp,array('$U8','gp_update','Conteúdo da página',False,True,''));

		array_push($cp, array('$H8', 'gp_codigo', '', False, True, ''));
		return ($cp);
	}

	/* campos de edição de dados */
	function cp() {
		$tabela = "grupo_de_pesquisa";
		$cp = array();
		array_push($cp, array('$H8', 'id_gp', 'id_gp', False, True, ''));
		array_push($cp, array('$S250', 'gp_nome', 'Nome do grupo', True, True, ''));
		//array_push($cp,array('$Q crs_nome:crs_codigo:select * from cursos where crs_ativo=1 order by crs_nome ','gp_curso','Curso de Graduação/Programa de Pós-Graduação',True,True,''));
		array_push($cp, array('$[1970-' . date("Y") . ']', 'gp_ano_formacao', 'Ano de formação', True, True, ''));
		array_push($cp, array('$H8', '', '', False, True, ''));
		array_push($cp, array('$S7', 'gp_area_1', 'Área do Conhecimento', False, True, ''));
		array_push($cp, array('$T60:6', 'gp_repercursao', 'Contexto', False, True, ''));
		array_push($cp, array('$T60:3', 'gp_comentarios', 'Comentários (interno)', False, True, ''));
		array_push($cp, array('$O 1:SIM&0:NÃO', 'gp_ativo', 'Ativo', True, True, ''));
		array_push($cp, array('$S150', 'gp_site', 'Site do Grupo', False, True, ''));
		array_push($cp, array('$S150', 'gp_link_cnpq', 'Link do CNPq', False, True, ''));
		array_push($cp, array('$O : &1:SIM&0:NÃO', 'gp_cnpq_certificado', 'Certificado no CNPq', True, True, ''));
		array_push($cp, array('$D8', 'gp_data_autorizacao', 'Data do parecer da Pró-Reitoria Acadêmica', True, True, ''));
		array_push($cp, array('$O A:Cadastrar&B:Falta documentos ou Dados&C:Ativo&D:Encerrado&X:Cancelado', 'gp_status', 'Status', True, True, ''));
		array_push($cp, array('$T80:6', 'gp_pages', 'Conteúdo da página', False, True, ''));

		array_push($cp, array('$H8', 'gp_codigo', '', False, True, ''));
		return ($cp);
	}

	/**
	 * Le dados do grupo
	 */
	function le($id) {
		if (strlen($id) > 0) { $this -> id_gp = $id;
		}
		$sql = "select * from " . $this -> tabela;
		$sql .= " where id_gp = " . $this -> id_gp;
		$rlt = db_query($sql);

		if ($line = db_read($rlt)) {
			$this -> id_gp = $line['id_gp'];
			$this -> gp_codigo = $line['gp_codigo'];
			$this -> gp_nome = $line['gp_nome'];
			$this -> gp_curso = $line['gp_curso'];
			$this -> gp_ano_formacao = $line['gp_ano_formacao'];
			$this -> gp_area_1 = $line['gp_area_1'];
			$this -> gp_contexto = $line['gp_contexto'];
			$this -> gp_objetivo = $line['gp_objetivo'];
			$this -> gp_status = $line['gp_status'];
			$this -> gp_cert_cnpq = $line['gp_cert_cnpq'];
			$this -> gp_pp = $line['gp_pp'];
			$this -> gp_link_cnpq = $line['gp_link_cnpq'];
			$this -> gp_unidade = $line['gp_unidade'];
			$this -> gp_instituicao = $line['gp_instituicao'];
			$this -> gp_update = $line['gp_update'];
			$this -> gp_ata = $line['gp_ata'];
			$this -> gp_site = $line['gp_site'];
			$this -> gp_repercursao = $line['gp_repercursao'];
			$this -> gr_area_01 = $line['gp_area_01'];
			$this -> gr_area_02 = $line['gp_area_02'];

			$this -> gp_cnpq_certificado = $line['gp_cnpq_certificado'];
			$this -> gp_certificado = $line['gp_certificado'];
			$this -> gp_cnpq_cod = trim(substr($this -> gp_link_cnpq, strpos($this -> gp_link_cnpq, '=') + 1, 100));
			$this -> gp_pages = trim($line['gp_pages']);
			$this -> membros = $this -> grupo_de_pesquisa_membros();
		}
		return ($sx);

	}

	/**
	 * Status
	 */
	function grupo_status_array() {
		$cg = array('A' => 'Em edição', 'B' => 'Falta documentação', 'C' => 'Ativo');
		return ($cg);
	}

	/**
	 * Mostar dados do Grupo
	 */
	function mostra_dados() {
		global $tab_max, $messa;
		$group_name = $this -> gp_nome;
		$gr_year = $this -> gp_ano_formacao;
		$gr_update = stodbr($this -> gp_update);
		$gr_ata_nr = $this -> gp_ata;
		$gr_status = $this -> gp_status;
		$gr_area_01 = $this -> gr_area_01;
		$gr_area_02 = $this -> gr_area_02;
		$gr_link = trim($this -> gr_link);
		/*** Link CNPq **/
		$gr_link_cnpq = trim($this -> gp_link_cnpq);

		if (strlen($gr_link_cnpq) > 0) { $gr_link_cnpq = '<A HREF="' . $gr_link_cnpq . '" target="new">' . $gr_link_cnpq . '</A>';
		}

		/** Status **/
		$grs = $this -> grupo_status_array();
		$gr_status = $grs[$gr_status];

		/** Data da ata **/
		if ($gr_ata_dt < 19500101) { $gr_ata_dt = msg('not_date');
		} else { $gr_ata_dt = stodbr($gr_ata_dr);
		}

		/*** Status CNPq ***/
		$grs = $this -> grupo_lattes_status();
		$gr_cnpq_status = $this -> gp_cnpq_certificado;
		$gr_cnpq_status = $grs[$gr_cnpq_status];
		$gr_cnpq_status .= ', atualizado em ' . stodbr($this -> gp_certificado);
		$linkd = '<A HREF="gp_detalhe.php?dd0='.$this->id_gp.'">';

		$sx .= "<fieldset><legend>" . msg('group_research') . "</legend>
					<table width=100% cellpadding=0 cellspacing=0>
					<TR class=lt0 ><TD colspan=5>" . msg('group_name') . "</td>
					<TR class=lt4 ><TD colspan=5><B>$linkd $group_name </A></TD>
					<TR><TD>
						<fieldset><legend>" . msg('group_data') . "</legend>
						<table width=100% cellpadding=1 cellspacing=0 border=0>
						<TR class=lt0>
							<TD>" . msg('group_link_cnpq') . "
							<TD align=right >" . msg('group_year') . "<TD class=lt2 >$gr_year
						<TR>
							<TD>" . $gr_link_cnpq . "&nbsp;
							<TD align=right >" . msg('group_update') . "<TD class=lt2 >$gr_update
						<TR>
							<TD colspan=1>" . msg('cnpq_status') . "
							<TD align=right >" . msg('group_ata_nr') . "<TD class=lt2>$gr_ata_nr
						<TR>
							<TD colspan=1><B>$gr_cnpq_status</B> &nbsp;
							<TD align=right >" . msg('group_ata_data') . "<TD class=lt2>$gr_ata_dt
						<TR>
							<TD>" . msg('group_link') . "
							<TD align=right >" . msg('group_status') . "<TD class=lt2>$gr_status
						<TR>
							<TD>$gr_link &nbsp;
							<TD align=right >" . msg('group_area1') . "<TD class=lt2>$gr_area_01
						<TR>
							<TD>
							<TD align=right >" . msg('group_area2') . "<TD class=lt2>$gr_area_02
														
						
						</table>
						</fieldset>
						</table>
						</fieldset>
																								
					";
		//$sx .= '</table></table>';
		return ($sx);
	}

	/**
	 * Mostra lider
	 */
	function mostra_lider() {
		
		global $coluna;
		$sx = '<fieldset><legend>' . msg('group_leader') . '</legend>
					<table width=100% cellpadding=3 cellspacing=0 border=0 class="lt1">
					<TR class=lt0 >
						<TH>' . msg('leader_name') . '</td>
						<TH>' . msg('leader_quaif') . '</td>
						<TH>' . msg('leader_inst') . '</td>
						<TH>' . msg('leader_type') . '</td>
					';
		$tot = 0;
		/*** Mosta lideres **/
		$ldm = $this -> membros;

		for ($r = 0; $r < count($ldm); $r++) {
			$ld = $ldm[$r];
			if ($ld[4] == 'L') {
				$tot++;
				$sx .= '<TR ' . coluna() . '>';
				$sx .= '<TD>' . $ld[0];
				$sx .= '<TD>' . $ld[1];
				$sx .= '<TD>' . $ld[3];
				$sx .= '<TD>' . $ld[2];
			}
		}
		/*** Sem lider **/
		if ($tot == 0) { $sx .= '<TR><TD colspan=4 align=center class=lt2><font color=red>' . msg('not_leader') . '</font></TD>';
		}
		$sx .= '</table></fieldset>';
		return ($sx);
	}

	function update() {
		if ($this -> id_gp > 0) {
			$sql = "update " . $this -> tabela;
			$sql .= " set ";
			$sql .= "gp_ano_formacao = '" . $this -> gp_ano_formacao . "' ";
			$sql .= " where id_gp = " . $this -> id_gp;
			$rlt = db_query($sql);
		}
		return (1);
	}

	/* mostra resumo dos grupos */
	function resumo_mostra() {
		global $tab_max;
		$gps = $this -> resumo();
		$sx = '<table width="' . $tab_max . '" cellpadding=3 cellspacing=0 border=0>';
		$sx .= '<TR align="center">';
		$sx .= '<TD width="25%"><FONT CLASS="lt0">ativos</FONT><BR>' . ($gps[2] + $gps[3]) . '</TD>';
		$sx .= '<TD width="25%"><FONT CLASS="lt0">inativos</FONT><BR>' . $gps[4] . '</TD>';
		$sx .= '<TD width="25%"><FONT CLASS="lt0">em cadastramento</FONT><BR>' . $gps[0] . '</TD>';
		$sx .= '<TD width="25%"	><FONT CLASS="lt0">encerrados</FONT><BR>' . $gps[5] . '</TD>';
		$sx .= '</TR></table>';
		return ($sx);
	}

	/* calcula o total de grupos */
	function resumo() {
		$sql = "select count(*) as total, gp_status from grupo_de_pesquisa group by gp_status ";
		$rlt = db_query($sql);
		$rst = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
		/*
		 0 - em cadastro (@)
		 1 - sem grupo definido (Z)
		 2 - ativos e não revisados (A)
		 3 - ativos e atualizadados (B)
		 4 - inativos (I)
		 5 - encerrados (E)
		 6 - reservado (R)
		 7 - reservado (R)
		 8 - reservado (R)
		 9 - cancelados (X)
		 */

		while ($cline = db_read($rlt)) {
			$sta = trim($cline['gp_status']);
			$tot = $cline['total'];
			if ($sta == '@') { $rst[0] = $tot;
			}
			if ($sta == 'Z') { $rst[1] = $tot;
			}
			if ($sta == 'A') { $rst[2] = $tot;
			}
			if ($sta == 'B') { $rst[3] = $tot;
			}
			if ($sta == 'I') { $rst[4] = $tot;
			}
			if ($sta == 'E') { $rst[5] = $tot;
			}
			if ($sta == 'R') { $rst[6] = $rst[6] + $tot;
			}
			if ($sta == '!') { $rst[7] = $tot;
			}
			if ($sta == '!') { $rst[8] = $tot;
			}
			if ($sta == 'X') { $rst[9] = $tot;
			}
		}
		return ($rst);
	}

	function linhas_de_pesquisa_listar() {
		global $tab_max;
		$sql = "select * from linha_de_pesquisa ";
		$sql .= " inner join linha_de_pesquisa_grupo on lpg_linha = lp_codigo ";
		$sql .= " where lpg_grupo = '" . $this -> gp_codigo . "' ";
		$sql .= " and lpg_ativo = 1 ";
		$rlt = db_query($sql);

		//$sx .= '<table width='.$tab_max.' cellpadding=0 cellspacing=0><TR><TD>';
		$sx .= '<fieldset><legend>' . msg('research_line') . '</legend>';
		$sx .= '<table width="100%" class="lt4" border=0  cellpadding=3 cellspacing=0 class="lt1" >';
		$sx .= '<TR class="lt0">';
		$sx .= '<TH>Nome da linha de pesquisa';
		$sx .= '<TH>Área do conhecimento';

		while ($line = db_read($rlt)) {
			$link = '<A href="linha_de_pesquisa_detalhar.php?dd0=' . $line['id_lp'] . '&&dd90=' . checkpost($line['id_lp']) . '">';
			$sx .= '<TR class="lt2" ' . coluna() . '>';
			$sx .= '<TD align="left">' . $link;
			$sx .= $line['lp_nome'];
			$sx .= '<TD align="left">' . $link;
			$sx .= $line['lp_area_1'];
			$sx .= '<TD align="left">' . $link;
			$sx .= $line['lp_codigo'];
			$sx .= '<TD align="left">' . $link;
			$sx .= $line['lpg_grupo'];
			$sx .= '<TD align="left">' . $link;
			$sx .= $line['lpg_ativo'];
		}
		$sx .= '</table>';
		$sx .= '</fieldset>';

		return ($sx);
	}

	function grupo_de_pesquisa_membros_listar() {
		global $coluna;
		$nm = $this -> grupo_de_pesquisa_membros();
		$sx .= '';
		$sx .= '<fieldset><legend>' . msg('group_member') . '</legend>';
		$sx .= '<table width="100%" class="lt1" border=0 cellspacing=3 >';
		$sx .= '<TR class="lt0">';
		$sx .= '<TH>Tipo';
		$sx .= '<TH>NOME COMPLETO';
		$sx .= '<TH>Qualificação';
		$sx .= '<TH>Instituição';
		$sx .= '<TH>Carga Horaria';
		for ($rr = 0; $rr < count($nm); $rr++) {
			$sx .= '<TR ' . coluna() . '>';
			$sx .= '<TD align="left">';
			$sx .= $nm[$rr][2];
			$sx .= '<TD>';
			$sx .= $nm[$rr][0];
			$sx .= '<TD>';
			$sx .= $nm[$rr][1];
			$sx .= '<TD align="center">';
			$sx .= $nm[$rr][3];
			$sx .= '<TD align="center">';
			$sx .= $nm[$rr][5] . 'h';
		}
		$sx .= '</table>';
		$sx .= '</fieldset>';
		return ($sx);
	}

	function actions_show($sh = '') {
		$sn = array();
		$st = array();

		array_push($sn, 'A');
		array_push($sn, 'B');
		array_push($sn, 'C');

		$sx = '<table width="100%" class="tabela20" border=0>';
		$sx .= '<TR class="tabela_title">
				<TH colspan="10" class="tabela_title">' . msg("actions");
		$sx .= $this -> actions_display($sn, $sh);
		$sx .= '</table>';
		$sx .= '<div stlye="float: clear">&nbsp;</div>';
		return ($sx);
	}

	function actions_display($a1, $a2) {
		global $dd;
		$col = 99;
		for ($r = 0; $r < count($a1); $r++) {
			if ($col > 2) { $sx .= '<TR class="padding5" style="background-color: #FFFFFF;">';
				$col = 0;
			}
			$sx .= '<TD align="center">';
			$sx .= '<input type="button" 
							id="bt' . strzero($r, 2) . '" 
							value="' . $this -> msg('action_' . $a1[$r]) . '" 
							class="botao-finalizar" 
							style="padding: 2px 15x 2px 15px; width: 200px;"
							onclick="goto(\'' . $a1[$r] . '\')"
							>';
			$col++;
		}
		$sx .= '
				<script>
				function goto(id)
					{
						var link_call = "submit_works_actions.php?dd2="+id+"&dd1=' . $dd[1] . '&dd90=' . $dd[90] . '";
						var $tela01 = $.ajax(link_call)
							.done(function(data) { $("#actions").html(data); })
							.always(function(data) { $("#actions").html(data); });			
					}
				</script>			
			';

		return ($sx);
	}

	function msg($ac) {
		switch($ac) {
			case 'action_A' :
				$ac = 'Incluir novo lider';
				break;
			case 'action_B' :
				$ac = 'Incluir novo membro';
				break;
			case 'action_C' :
				$ac = 'Excluir membro ou lider';
				break;
		}
		return ($ac);
	}

	function grupo_de_pesquisa_membros() {
		$mb = array();
		$this -> gp_codigo = strzero($this -> id_gp, 7);
		$sql = "select * from grupo_de_pesquisa_membro ";
		$sql .= " left join docentes on pp_cracha = gpm_cracha ";
		$sql .= " left join apoio_titulacao on ap_tit_codigo = pp_titulacao ";
		$sql .= " where gpm_grupo = '" . $this -> gp_codigo . "' ";

		$rlt = db_query($sql);
		while ($line = db_read($rlt)) {
			$tipo = $line['gpm_tipo'];
			if ($tipo == 'L') { $tipo = 'Lider do grupo';
			}
			if ($tipo == 'P') { $tipo = 'Participante';
			}
			if ($tipo == 'E') { $tipo = 'Estudante';
			}
			array_push($mb, array(trim($line['pp_nome']), $line['ap_tit_titulo'], $tipo, $line['pp_centro'], $line['gpm_tipo'], $line['pp_carga_semanal']));
		}
		return ($mb);
	}

	function inserir_professor_membro($codigo, $tipo) {
		$grupo = $this -> gp_codigo;
		$data = date("Ymd");

		$sql = "select * from grupo_de_pesquisa_membro 
							where gpm_cracha = '$codigo'
								and gpm_grupo = '$grupo'
				";
		$rlt = db_query($sql);
		if ($line = db_read($rlt)) {

		} else {
			$sql = "insert into grupo_de_pesquisa_membro ";
			$sql .= "(gpm_cracha,gpm_grupo,gpm_update,gpm_tipo,gpm_ativo,gmp_inventario)";
			$sql .= " values ";
			$sql .= "('$codigo','$grupo','$data','$tipo',1,0)";
			$rlt = db_query($sql);
		}

	}

	function grupo_de_pesquisa_membro_atualizar($nome, $tipo) {
		$sql = "select * from pibic_professor 
					where pp_nome_lattes like '%" . uppercasesql($nome) . "%'
					or pp_nome_asc = '" . uppercasesql($nome) . "'
					 ";
		$rlt = db_query($sql);
		if ($line = db_read($rlt)) {
			$codigo = $line['pp_cracha'];
			$sql = "select * from grupo_de_pesquisa_membro ";
			$sql .= " where gpm_cracha = '" . $codigo . "' ";
			$sql .= " and 	gpm_grupo = '" . strzero($this -> id_gp, 7) . "' ";
			$rlt = db_query($sql);

			$data = date("Ymd");
			$grupo = strzero($this -> id_gp, 7);

			if ($line = db_read($rlt)) {
				$sql = "update grupo_de_pesquisa_membro set ";
				$sql .= " gpm_tipo = '" . $tipo . "' ";
				$sql .= ", gpm_ativo = 1";
				$sql .= " where id_gpm = " . $line['id_gpm'];
			} else {
				$sql = "insert into grupo_de_pesquisa_membro ";
				$sql .= "(gpm_cracha,gpm_grupo,gpm_update,gpm_tipo,gpm_ativo,gmp_inventario)";
				$sql .= " values ";
				$sql .= "('$codigo','$grupo','$data','$tipo',1,0)";
			}
			$rlt = db_query($sql);
		} else {
			echo '<H1>Não localizado</H1>';
		}
	}

	function grupo_lattes_importar() {
		return ('');
		require_once ('_class_linha_de_pesquisa.php');
		$li = new linha_de_pesquisa;

		$site = trim($this -> gp_pages);
		if (strlen($site) > 0) {
			$s .= $site;
		} else {
			echo 'Link em branco';
		}
		$sf = UpperCaseSql(remove_tags($s));
		$sf = troca($sf, '&NBSP', ' ');
		//if (strlen($this->gp_ano_formacao) == 0)
		{
			$ss = 'ANO DE FORMACAO';
			$sa = sonumero(substr($sf, strpos($sf, $ss) + strlen($ss), 8));

			if ($sa == 0) {
				$ss = 'ANO DE FORMAC&ATILDE;O:';
				$sa = sonumero(substr($sf, strpos($sf, $ss) + strlen($ss), 8));
			}
			if ($sa == 0) {
				$ss = 'ANO DE FORMA&CCEDIL;&ATILDE;O:';
				$sa = sonumero(substr($sf, strpos($sf, $ss) + strlen($ss), 8));
			}
			$this -> gp_ano_formacao = $sa;

		}

		/*** Busca Lider ***/
		$sx = $s;
		$ss = '<img onclick="envioEmail(';
		while (strpos($sx, $ss) > 0) {
			$x = strpos($sx, $ss);
			$sa = substr($sx, $x - 100, 100);
			$sb = 'Font-Size: 8.5pt">';
			$sa = substr($sa, strpos($sa, $sb) + strlen($sb));
			$sa = trim(substr($sa, 0, strpos($sa, '-')));
			$this -> grupo_de_pesquisa_membro_atualizar($sa, 'L');
			$sx = substr($sx, strpos($sx, $ss) + strlen($ss), strlen($sx));
		}

		/*** Buscar nomes das linhas de pesquisa */
		$sx = $s;
		$sql = "delete from  linha_de_pesquisa_grupo where  lpg_grupo = '" . $this -> gp_codigo . "'";
		$rlt = db_query($sql);

		$ss = '<LI><A hRef="JavaScript:abreDetalheLinha(';
		//http://dgp.cnpq.br/buscaoperacional/detalhelinha.jsp?grupo=0207103VPL28M5&seqlinha=5
		while (strpos($sx, $ss) > 0) {
			$sa = substr($sx, strpos($sx, $ss) + 15, 200);
			$sl = sonumero(substr($sa, strpos($sa, ','), 5));
			$sb = '">';
			$sa = substr($sa, strpos($sa, $sb) + strlen($sb), 200);
			$sa = trim(substr($sa, 0, strpos($sa, '<')));
			$site = 'http://dgp.cnpq.br/buscaoperacional/detalhelinha.jsp?grupo=' . $this -> gp_cnpq_cod . '&seqlinha=' . $sl;

			//Linha de pesquisa
			//$ln_cod = $li->linha_de_pesquisa_atualizar($sa,$site,$grupo);

			$this -> grupo_de_pesquisa_linha_atualizar($ln_cod);
			$sx = substr($sx, strpos($sx, $ss) + strlen($ss), strlen($sx));
		}
		/** Pesquisadores */
		$sx = $sf;
		$ss = 'PESQUISADOR(ES)';
		$pesq = (substr($sx, strpos($sx, $ss) + strlen($ss), 160));
		echo '<BR>Pesqu:' . $pesq;
		$pesq = sonumero(substr($pesq, 0, strpos($pesq, 'EST')));
		echo '<BR>Pesqu:' . $pesq;
		/** Pesquisadores */
		$sx = $sf;
		$ss = 'ESTUDANTE(S)';
		$estu = substr($sx, strpos($sx, $ss) + strlen($ss), 160);
		$estu = sonumero(substr($estu, 0, strpos($estu, 'T')));
		echo '==><HR>' . $estu . '<HR>';
		/** Pesquisadores */
		$sx = $sf;
		$ss = ' TECNICO(S)';
		$tecn = sonumero(substr($sx, strpos($sx, $ss) + strlen($ss), 140));

		/** Atualização */
		$sx = $sf;
		$ss = 'DATA DA &UACUTE;LTIMA ATUALIZA&CCEDIL;&ATILDE;O:';
		$sa = sonumero(substr($sx, strpos($sx, $ss) + strlen($ss), 40));
		$sa = substr($sa, 0, 8);
		$updt = substr($sa, 4, 4) . substr($sa, 2, 2) . substr($sa, 0, 2);
		if (round($updt) == 0) {
			$ss = 'DATA DO ULTIMO ENVIO:';
			$sa = sonumero(substr($sx, strpos($sx, $ss) + strlen($ss), 20));
			$sa = substr($sa, 0, 8);
			$updt = substr($sa, 4, 4) . substr($sa, 2, 2) . substr($sa, 0, 2);
		}
		if (round($updt) == 0) {
			$ss = 'DATA DA ULTIMA ATUALIZACAO:';
			$sa = sonumero(substr($sx, strpos($sx, $ss) + strlen($ss), 20));
			$sa = substr($sa, 0, 8);
			$updt = substr($sa, 4, 4) . substr($sa, 2, 2) . substr($sa, 0, 2);
		}

		/* ÁREA PREDOMINANTE */
		$sx = $sf;
		$ss = 'PREDOMINANTE:';
		$area = substr($sx, strpos($sx, $ss) + strlen($ss), 200);
		echo '<BR>==>' . $area;
		$ss = 'INSTITUI';
		$area = substr($area, 0, strpos($area, $ss));
		echo '<BR>==>' . $area;
		$pos = strpos($area, ';');
		$area_01 = trim(substr($area, 0, $pos));
		$area_02 = trim(substr($area, $pos + 1, 100));

		/** Status do grupo **/
		$sx = $sf;
		$ss = UpperCaseSql('Status do grupo:');
		$sa = substr($sx, strpos($sx, $ss) + strlen($ss), 100);
		$sa = trim(substr($sa, 0, strpos($sa, chr(13))));

		$sta = -1;
		if ($sa == UpperCaseSql('processo de carga')) { $sta = 1;
		}
		if ($sa == 'CERTIFICADO PELA INSTITUICAO') { $sta = 2;
		}
		if ($sa == UpperCaseSql('certificado pela instituição - Não atualizado há mais de 12 meses')) { $sta = 3;
		}
		if ($sa == UpperCaseSql('aguardando certificação pela instituição')) { $sta = 4;
		}
		if ($sa == UpperCaseSql('em preenchimento')) { $sta = 5;
		}

		echo $sf;

		if (strlen($area_01) > 0) {
			$r = 100;
			$sql = "update " . $this -> tabela . " set gp_certificado = " . round($updt);
			$sql .= ", gp_cnpq_certificado = " . $sta . " ";
			$sql .= ", gp_pesquisadores = " . round('0' . $pesq);
			$sql .= ", gp_estudantes = " . round('0' . $estu);
			$sql .= ", gp_tecnicos = " . round('0' . $tecn);
			$sql .= ", gp_area_01 = '$area_01' ";
			$sql .= ", gp_area_02 = '$area_02' ";
			$sql .= " where id_gp = " . $this -> id_gp;
		}
		echo '<HR>' . $sql . '<HR>';
		$rlt = db_query($sql);
		echo '[1]';
		$this -> update();
		echo '[2]';
	}

	function grupo_lattes_status() {
		$st = array(-1 => 'Não identificado', 1 => 'processo de carga', 2 => 'certificado pela instituição', 3 => 'certificado pela instituição - Não atualizado há mais de 12 meses', 4 => 'aguardando certificação pela instituição', 5 => 'em preenchimento');
		return ($st);
	}

	function grupo_de_pesquisa_linha_atualizar($cod) {
		$grupo = $this -> gp_codigo;
		$sql = "select * from linha_de_pesquisa_grupo 
				where lpg_linha = '$cod' and lpg_grupo = '$grupo' ";
		$rlt = db_query($sql);
		if ($line = db_read($rlt)) {
			$sql = "update linha_de_pesquisa_grupo set lpg_ativo=1 where id_lpg = " . $line['id_lpg'];
			$rlt = db_query($sql);
		} else {
			$sql = "insert into linha_de_pesquisa_grupo (
						lpg_linha, lpg_grupo, lpg_ativo
						) values (
						'$cod','$grupo',1
						)";
			$rlt = db_query($sql);
		}
		return (1);
	}

	function structure() {
		return (0);

		$sql = "DELETE FROM grupo_de_pesquisa";
		$rlt = db_query($sql);
		$sql = "DELETE FROM grupo_de_pesquisa_membro";
		$rlt = db_query($sql);
		$sql = "DELETE FROM cip_ged_documento ";
		$rlt = db_query($sql);
		EXIT;
				
		$sql = "CREATE TABLE grupo_de_pesquisa_protocolo_membro (
  				id_gpmp serial,
  				gpmp_cracha char(8),
  				gpmp_grupo char(7),
  				gpmp_linha char(7),
  				gpmp_update int8,
  				gpmp_tipo char(1),
  				gpmp_ativo int2,
  				gpmp_tipo_alteracao char(1),
  				gpmp_data int8,
  				gpmp_protocolo char(7),
  				gmpp_inventario int8 DEFAULT 0
				) ;";

		$rlt = db_query($sql);

		$sql = "CREATE TABLE grupo_de_pesquisa_protocolo (
  				id_gpmp serial,
  				gpp_linha char(7),
  				gpp_cracha char(8),
  				gpp_grupo char(7),
  				gpp_update int8,
  				gpp_protocolo char(7),
  				gpp_status int2,
  				gpp_obs text,
  				gpp_data int8
				) ;";
		$rlt = db_query($sql);
		return (1);

		$sql = "update grupo_de_pesquisa set gp_ativo = 1 ";
		//$rlt = db_query($sql);


		$sql = "
			CREATE TABLE grupo_de_pesquisa (
			id_gp serial,
			gp_nome char(250),
  			gp_curso char(100),
  			gp_ano_formacao char(5),
  			gp_ata char(15),
  			gp_data_autorizacao int8,
  			gp_area_1 char(15),
  			gp_codigo char(7),
  			gp_status char(1),
  			gp_certificado int8,
  			gp_update int8,
  			gp_instituicao char(7),
  			gp_unidade char(7),
  			gp_site char(150),
  			gp_repercursao text,
  			gp_endereco char(255),
  			gp_telefone char(15),
  			gp_cnpq int8,
  			gp_cnpq_certificado int8,
  			gp_link_cnpq char(100),
  			gp_comentarios text,
  			gp_pages text,
  			gp_ativo int2
			);";
		//$rlt = db_query($sql);
		$sql = "CREATE TABLE  grupo_de_pesquisa_status (
  				id_gps serial,
    				gps_codigo char(1),
    				gps_descricao char(50),
    				gps_ativo int2
  				) ;
  				
  				INSERT INTO grupo_de_pesquisa_status (id_gps, gps_codigo, gps_descricao, gps_ativo) VALUES
  				(1, '@', 'em cadastro', 1),
  				(2, 'A', 'ativo e não revisado', 1),
  				(3, 'B', 'ativo e atualizado', 1),
  				(4, 'E', 'encerrado', 1),
  				(5, 'R', 'reservado', 1),
  				(6, 'X', 'cancelado', 1),
  				(7, 'I', 'inativo', 1),
  				(8, 'Z', 'sem definição', 1);";
		//$rlt = db_query($sql);

		$sql = "CREATE TABLE grupo_de_pesquisa_membro (
  				id_gpm serial,
  				gpm_cracha char(8),
  				gpm_grupo char(7),
  				gpm_update int8,
  				gpm_tipo char(1),
  				gpm_ativo int2,
  				gmp_inventario int8 DEFAULT 0
				) ;";
		//$rlt = db_query($sql);

		$sql = "CREATE TABLE  cip_ged_documento (
  				id_doc serial,
  				doc_dd0 char(7) ,
  				doc_tipo char(5) ,
  				doc_ano char(4) ,
  				doc_filename text,
  				doc_status char(1) ,
  				doc_data int8 ,
  				doc_hora char(8) ,
  				doc_arquivo text,
  				doc_extensao char(4) ,
  				doc_size float ,
  				doc_ativo int2 
				)";
		//$rlt = db_query($sql);

		$sql = "CREATE TABLE  cip_ged_documento_tipo (
  				id_doct serial,
  				doct_nome char(50) ,
  				doct_codigo char(5) ,
  				doct_publico int8 ,
  				doct_avaliador int8 ,
  				doct_autor int8 ,
  				doct_restrito int8 ,
  				doct_ativo int8 
				) ";
		//$rlt = db_query($sql);

		$sql = "INSERT INTO cip_ged_documento_tipo (
				doct_nome , doct_codigo , doct_publico , doct_avaliador , doct_autor , doct_restrito , doct_ativo )
				VALUES ( 'Parecer de Grupo de Pesquisa', 'PAREC', '1', '1', '1', '1', '1' );";
		//$rlt = db_query($sql);
	}

	function updatex() {
		global $base;
		$c = 'gp';
		$c1 = 'id_' . $c;
		$c2 = $c . '_codigo';
		$c3 = 7;
		$sql = "update " . $this -> tabela . " set $c2 = lpad($c1,$c3,0) where $c2='' ";
		if ($base == 'pgsql') { $sql = "update " . $this -> tabela . " set $c2 = trim(to_char(id_" . $c . ",'" . strzero(0, $c3) . "')) where $c2='' ";
		}
		$rlt = db_query($sql);
	}

}

function remove_tags($s) {
	$s = substr($s, strpos($s, '<BODY'), strlen($s));
	$s = troca($s, '<', '[');
	$s = troca($s, '>', ']');

	$s = ' ' . $s;
	$c = 0;

	while ((strpos($s, '[') > 0) and ($c < 1450)) {
		$c++;
		$x = strpos($s, '[');
		$y = strpos($s, ']');

		$s1 = substr($s, 0, $x);
		//echo '<BR>==>'.$s1;
		$s2 = substr($s, $y + 1, strlen($s));
		//echo '<BR>===>'.$s1;
		//echo '<BR>===>'.$s2;
		$s = $s1 . $s2;

	}
	return ($s);
}

/// Gerado pelo sistem "base.php" versao 1.0.5
?>