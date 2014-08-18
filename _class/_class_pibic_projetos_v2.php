<?php
class projetos {
	var $protocolo;
	var $protocolo_aluno;
	var $status;
	var $titulo = '';
	var $line;

	var $plano_pibic = 0;
	var $plano_pibiti = 0;
	var $plano_pibic_em = 0;
	var $plano_ici = 0;

	var $plano_submissao = 0;

	var $erros;
	var $tabela = "pibic_projetos";
	var $tabela_planos = "pibic_submit_documento";

	var $internacional = 0;
	var $status = '@';

	var $ano;
	
	function areas_mostra($c)
		{
			if ($c=='E') { $sx = '<font style="color: #006b9f; font-size: 30px;">Ciências Exatas</font>'; }
			if ($c=='H') { $sx = '<font style="color: #ff0000; font-size: 30px;">Ciências Humanas</font>'; }
			if ($c=='S') { $sx = '<font style="color: #ff0000; font-size: 30px;">Ciências Sociais Aplicadas</font>'; }
			if ($c=='V') { $sx = '<font style="color: #00A000; font-size: 30px;">Ciências da Vida</font>'; }
			if ($c=='A') { $sx = '<font style="color: #009000; font-size: 30px;">Ciências Agrárias</font>'; }
			return($sx);
		}
	
	
	function mostra_edital($ano,$bolsa,$modalidade,$tipo='')
		{	
			$sql = "select * from pibic_submit_documento where doc_edital = 'PIBICE' ";
//			$rlt = db_query($sql);
//			while ($line = db_read($rlt))
//				{
//					print_r($line);
//					exit;
//				}
			
			
			$cps = 'doc_1_titulo, doc_ava_estrategico, id_pj, pj_codigo, doc_doutorando, doc_area, ap_tit_titulo, pp_nome, pp_centro, pp_ss, doc_icv, doc_estrategica, doc_nota, doc_protocolo_mae, doc_protocolo ';
			$cps .= ', pp_prod, pp_cracha, doc_aluno, doc_avaliacoes, pb_vies, doc_protocolo ';
			if ($modalidade!='PIBICE') { $cps .= ', pb1.pb_tipo as pb_tipo, pa_nome '; }
			//$cps = '*';
			$sql = "select ".$cps." from pibic_submit_documento ";
			$sql .= " inner join pibic_professor on pp_cracha = doc_autor_principal ";
			$sql .= " inner join apoio_titulacao on pp_titulacao = ap_tit_codigo ";
			if ($modalidade!='PIBICE')
				{
				$sql .= " inner join pibic_aluno on pa_cracha = doc_aluno ";
				$sql .= " left join pibic_bolsa as pb1 on (doc_protocolo = pb1.pb_protocolo) ";
				
				}
			$sql .= " left join pibic_projetos on pj_codigo = doc_protocolo_mae ";
			$sql .= " where doc_ano = '".$ano."' ";
			$sql .= " and (doc_edital = '".$modalidade."' ";
			if ($modalidade == 'PIBITI')
				{
					$sql .= " or (pb_vies = '1' and pb_tipo = 'I') ";
				}
			$sql .= ") and (doc_protocolo <> doc_protocolo_mae) ";
			if (strlen($area) > 0) { $sql .= " and doc_area = '".$area."' "; }
			$sql .= " and (doc_status <> 'X' and doc_status <> '@' ) ";
			$sql .= " and pb_tipo <> 'X' ";
			if (strlen($tipo) > 0) { $sql .= " and pb_tipo = '$tipo' "; }
			//$sql .= " and (doc_aluno <> '') ";
			//$sql .= " and doc_nota > 10 ";
			$sql .= " order by doc_area, pp_nome ";
			$rlt = db_query($sql);
		
			$sx .= '<table class="lt0">';
			$tot = 0;
			$sh .= '<TR><TH>bolsa';
			$sh .= '<TH>tit';
			$sh .= '<TH>professor';
			$sh .= '<TH>aluno';
			$sh .= '<TH>título do plano de trabalho';
			
			$xarea = '-';
			$id = 0;
			while ($line = db_read($rlt))
				{
					$idr = $line['id_pj'];
					$nota = round($line['doc_nota']);
					//$link = '<A HREF="pibic_projetos_detalhes.php?dd0='.$idr.'&dd90='.checkpost($idr).'" class="lt1a" target="new'.$id.'">';
					$cp = 'ap_tit_titulo, pp_nome, pa_nome, doc_1_titulo';
					$area = $line['doc_area'];
					if ($area != $xarea)
						{
							$edital = trim($line['doc_edital']);
							if ($edital == 'PIBICE') { $edital = 'PIBIC_EM'; }
							$sx .= '<TR><TD class="lt4" colspan=6><center>'.$this->areas_mostra($area);
							$sx .= '<BR>'.$edital ;
							$sx .= $sh.chr(13);
							$xarea = $area;
						}
					$tot++;
					$bolsa = trim($line['pb_tipo']);
					if ($nota < 60) { $bolsa = 'D'; }
					if ($bolsa == 'R') { $bolsa = 'D'; }
					if ($bolsa != 'D')
					{
						$id++;
						$tipo = trim($line['pb_tipo']);
						$vies = trim($line['pb_vies']);
						$sx .= '<TR '.coluna().' class="lt1a">';
						$sx .= '<TD><img src="img/logo_bolsa_'.$bolsa.'.png">';
						if ($vies=='1' and $tipo =='I') { $sx .= '*'; }
						$sx .= '<TD>';
						$sx .= $link;
						$sx .= $line['ap_tit_titulo'];
						$sx .= '<TD width="20%">';
						$sx .= $link;
						$sx .= nbr_autor($line['pp_nome'],7);
						$sx .= '<TD width="20%">';
						$sx .= $link;
						$aluno = trim($line['pa_nome']);
					if (strlen($aluno)==0) 
						{ $aluno = ':: Sem Definição de Aluno ::'; } 
					else 
						{ $aluno = trim(nbr_autor($line['pa_nome'],7)); }
					if (strlen($aluno)==0) 
						{ $aluno = ':: Sem Definiçâo de Aluno ::'; }
					$sx .= $aluno;
					$sx .= '<TD>';
					$sx .= $link;
					$sx .= nbr_autor($line['doc_1_titulo'],7);
					$sx .= '</tr>'.chr(13).chr(10);
					$sx .= '<tr><td colspan="6"><img src="img/nada_black.gif" alt="" width="100%" border="0" height="1"></td></tr>'.chr(13).chr(10);
					}
										
				}
			$sx .= '<TR><TD colspan=5>Total de '.$tot.' projetos nesta modalidade';
			$sx .= '</table>';
			return($sx);
		}
	

	function demandas($ano = '') {
		$sx = '<table class="tabela00 lt3">';
		$sx .= '<TR><TD>Projetos de Professor';
		$sx .= '<TD>' . $this -> demandas_projetos_submetidos($ano);

		$sx .= '<TR><TD>Professor com projetos submetidos';
		$sx .= '<TD>' . $this -> demandas_projetos_professor($ano);

		$sx .= '<TR valign="top"><TD>Total de Planos Submetidos';
		$sx .= '<TD>' . $this -> demandas_planos_submetidos($ano);

		$sx .= '<TR valign="top"><TD>Total de Avaliações (planos/projetos)';
		$sx .= '<TD>' . $this -> planos_submetidos_avaliados($ano);

		$sx .= '<TR valign="top"><TD>Projetos PIBIC com viés PIBITI';
		$sx .= '<TD>' . $this -> planos_com_vies_pibiti($ano);

		$sx .= '</table>';
		$sx .= $this -> planos_notas($ano);
		//$sx .= $this->planos_notas_mostra($ano);
		return ($sx);
	}

	function planos_com_vies_pibiti($ano) {
		$tabela = "pibic_parecer_".$ano;
		$sql = "select count(*) as total, pp_protocolo from " . $tabela . "  
			inner join pibic_submit_documento on doc_protocolo = pp_protocolo
			where pp_tipo = 'SUBMP' 
			and pp_status <> 'D'
			and pp_p08 = '1'
			and doc_edital = 'PIBIC'
			group by pp_protocolo
			order by pp_protocolo
			";
		$rlt = db_query($sql);
		$t1 = 0;
		$t2 = 0;
		while ($line = db_read($rlt))
			{
				$t = $line['total'];
				if ($t==2)
					{
						$t2++;
					} else {
						$t1++;
					}
			}
		$sx .= '<table class="tabela00 lt3">
				<TR><TD>Apontado por <B>um</B> avaliador <TD>'.$t1.'</td></tr>
				<TR><TD>Apontado por <B>dois</B> avaliador <TD>'.$t2.'</td></tr>
				</table>
		';
		return($sx);
	}

	function demandas_projetos_submetidos($ano) {
		$sql = "select count(*) as total 
						from " . $this -> tabela . " 
						where pj_ano = '$ano' 
								and pj_status <> 'X' 
								and pj_status <> '!'
						";
		$rlt = db_query($sql);
		$line = db_read($rlt);
		return ($line['total']);
	}

	function planos_notas_mostra($ano) {
		$sql = "select * from pibic_submit_documento 
							where doc_ano = '$ano'
								and (doc_status <> '@' and doc_status <> 'X' and doc_status <> '!')
								and doc_edital = 'PIBICE'
						order by doc_protocolo_mae, doc_protocolo";
		$rlt = db_query($sql);
		$id = 0;
		$sx .= '<table width="100%" class="tabela00">';
		while ($line = db_read($rlt)) {
			$id++;
			$sx .= '<TR>';
			$sx .= '<TD>' . $line['doc_protocolo_mae'];
			$sx .= '<TD>' . $line['doc_protocolo'];
			$sx .= '<TD>' . $line['doc_status'];
			$sx .= '<TD>' . $line['doc_nota'];
			$sx .= '<TD>' . $line['doc_edital'];
		}
		$sx .= '<TR><TD colspam=10>' . $id;
		$sx .= '</table>';
		return ($sx);
	}

	function planos_notas($ano) {
		$sql = "select * from pibic_submit_documento 
							where doc_ano = '$ano'
							and (doc_status <> '@' and doc_status <> 'X' and doc_status <> '!')			
				";
		$rlt = db_query($sql);
		$line = db_read($rlt);
		$ar = array('PIBIC', 'PIBITI', 'PIBICE', 'ICI');
		$ar1 = array(0, 0, 0, 0);
		$ar2 = array(0, 0, 0, 0);
		$ar3 = array(0, 0, 0, 0);
		$ar4 = array(0, 0, 0, 0);

		while ($line = db_read($rlt)) {
			$nota = round('0' . trim($line['doc_nota']));
			$t1 = 0;
			$t2 = 0;
			$t3 = 0;
			/* Notas */
			if ($nota < 65) { $t3 = 1;
			}
			if (($nota >= 65) and ($nota < 90)) { $t2 = 1;
			}
			if ($nota >= 90) { $t1 = 1;
			}
			$ed = trim($line['doc_edital']);

			switch ($ed) {

				case 'PIBIC' :
					$ar1[0] = $ar1[0] + $t1;
					$ar2[0] = $ar2[0] + $t2;
					$ar3[0] = $ar3[0] + $t3;
					$ar4[0] = $ar4[0] + $t4;
					break;
				case 'PIBITI' :
					$ar1[1] = $ar1[1] + $t1;
					$ar2[1] = $ar2[1] + $t2;
					$ar3[1] = $ar3[1] + $t3;
					$ar4[1] = $ar4[1] + $t4;
					break;
				case 'PIBICE' :
					$ar1[2] = $ar1[2] + $t1;
					$ar2[2] = $ar2[2] + $t2;
					$ar3[2] = $ar3[2] + $t3;
					$ar4[2] = $ar4[2] + $t4;
					break;
				case 'ICI' :
					$ar1[3] = $ar1[3] + $t1;
					$ar2[3] = $ar2[3] + $t2;
					$ar3[3] = $ar3[3] + $t3;
					$ar4[3] = $ar4[3] + $t4;
					break;
			}
		}
		$sa = '<table class="tabela00 lt3" width="100%">';
		$sa .= '<TR><Th>Modalidade
					<Th>Aprovado +
					<BR><font class="lt0">(nota > 90)</font>
					<Th>Aprovado
					<Th>Reprovado
					<Th>Total Projetos
					<Th>Total Aprovado
					<Th>Total Reprovado';

		for ($r = 0; $r < 4; $r++) {
			$sa .= '<TR align="center">';
			$sa .= '<TD width="20" align="right">' . $ar[$r];
			$sa .= '<TD width="20" bgcolor="#F0F0FF">' . $ar1[$r];
			$sa .= '<TD width="20" bgcolor="#F0FFF0">' . $ar2[$r];
			$sa .= '<TD width="20" bgcolor="#FFf0F0">' . $ar3[$r];
			$sa .= '<TD width="20">' . ($ar1[$r] + $ar2[$r] + $ar3[$r] + $ar4[$r]);
			$sa .= '<TD width="20" align="center"><B>' . ($ar1[$r] + $ar2[$r]) . '</B>';

			$to = ($ar1[$r] + $ar2[$r] + $ar3[$r] + $ar4[$r]);
			$ta = ($ar1[$r] + $ar2[$r]);
			$sa .= ' (' . number_format($ta / $to * 100, 1) . '%)';

			$sa .= '<TD width="20">' . $ar3[$r];
			$ta = ($ar3[$r]);
			$sa .= ' (' . number_format($ta / $to * 100, 1) . '%)';

		}
		$sa .= '</table>';
		return ($sa);
	}

	function planos_submetidos_avaliados($ano) {
		$sql = "select count(*) as total from pibic_parecer_" . $ano . " 
						where (pp_tipo = 'SUBMP' or pp_tipo = 'SUBMI') 
								and pp_status <> 'D'						 
						";
		$rlt = db_query($sql);
		$rlt = db_query($sql);
		$line = db_read($rlt);
		return ($line['total']);
	}

	function demandas_planos_submetidos($ano) {

		$sql = "select count(*) as total, doc_edital
						from " . $this -> tabela . " 
						inner join pibic_submit_documento on (pj_codigo =  doc_protocolo_mae) and (doc_status <> 'X' and doc_status <> 'X')
						where pj_ano = '$ano' 
								and pj_status <> 'X' 
								and pj_status <> '!'
						group by doc_edital
						order by total desc
						";
		$rlt = db_query($sql);
		$sx = '<table width="100%" class="tabela00 lt3">';
		$sx .= '<TR><TD>';
		$tz = 0;
		while ($line = db_read($rlt)) {
			$tp = trim($line['doc_edital']);
			$to = $line['total'];
			switch ($tp) {
				case 'PIBIC' :
					$tp = 'Iniciação Científica';
					break;
				case 'PIBITI' :
					$tp = 'Iniciação Tecnológica';
					break;
				case 'PIBICE' :
					$tp = 'Iniciação Científica Júnior';
					break;
				case 'ICI' :
					$tp = 'Iniciação Científica Internacional';
					break;
			}
			$sx .= '<TR>';
			$sx .= '<TD class="tabela01">';
			$sx .= $tp;
			$sx .= '<TD class="tabela01" align="center">';
			$sx .= $to;
			$tz = $tz + $to;
		}
		$sx .= '<TR><TD colspan=2><I>Total ' . $tz;
		$sx .= '</table>';
		return ($sx);
	}

	function demandas_planos_modalidade($ano) {
		$sql = "select count(*) as total 
						from " . $this -> tabela . " 
						inner join pibic_submit_documento on (pj_codigo =  doc_protocolo_mae) and (doc_status <> 'X' and doc_status <> 'X')
						where pj_ano = '$ano' 
								and pj_status <> 'X' 
								and pj_status <> '!'
						";
		$rlt = db_query($sql);
		$line = db_read($rlt);
		return ($line['total']);
	}

	function demandas_projetos_professor($ano) {
		$sql = "select count(*) as total from (
						select 1 as total, pj_professor
						from " . $this -> tabela . " 
						where pj_ano = '$ano' 
								and pj_status <> 'X' 
								and pj_status <> '!'
						group by pj_professor ) as tabela
						";
		$rlt = db_query($sql);
		$line = db_read($rlt);
		return ($line['total']);
	}

	function mostra_planos_com_uma_indicacao() {
		$sql = "select * from (
						select count(*) as total, pp_protocolo from pibic_parecer_" . date("Y") . " 
						where pp_tipo = 'SUBMI' and substr(pp_protocolo,1,1) = '1'
								and pp_status <> 'D'						
						group by pp_protocolo
						) as total 
						inner join " . $this -> tabela . " on pj_codigo = pp_protocolo
						where total < 2
						order by total desc";
		$rlt = db_query($sql);
		$id = 0;
		while ($line = db_read($rlt)) {

			$pp = $line['pj_status'];
			if ($pp != 'C') {
				$id++;
				$sql = "update " . $this -> tabela . " set pj_status = 'C' where pj_codigo = '" . $line['pp_protocolo'] . "'";
				$rrr = db_query($sql);
			}
		}
		echo '<h3>Protocolos reencaminhado para avaliação: ' . $id . '';

		$sql = "select * from (
						select count(*) as total, pp_protocolo from pibic_parecer_" . date("Y") . " 
						where pp_tipo = 'SUBMI' and substr(pp_protocolo,1,1) = '1'
								and pp_status = 'B'						
						group by pp_protocolo
						) as total 
						inner join " . $this -> tabela . " on pj_codigo = pp_protocolo 
						where total >= 2
						order by total desc";
		$rlt = db_query($sql);
		$id = 0;
		while ($line = db_read($rlt)) {

			$pp = $line['pj_status'];
			if ($pp != 'F') {
				$id++;
				$sql = "update " . $this -> tabela . " set pj_status = 'F' where pj_codigo = '" . $line['pp_protocolo'] . "'";
				$rrr = db_query($sql);
			}

		}
		echo '<h3>Protocolos finalizados: ' . $id;

		$sql = " select count(*) as total, pp_status from pibic_parecer_" . date("Y") . " 
						where pp_tipo = 'SUBMI' and substr(pp_protocolo,1,1) = '1'
						group by pp_status
				";
		$rlt = db_query($sql);
		echo '<HR>';

		while ($line = db_read($rlt)) {
			$sta = $line['pp_status'];
			switch ($sta) {
				case '@' :
					echo 'Em avaliação';
					break;
				case 'B' :
					echo 'Avaliado';
					break;
				case 'D' :
					echo 'Declinado';
					break;
				default :
					echo 'Outros (' . $sta . ')';
			}
			echo ' ' . $line['total'] . '<BR>';
		}
		echo '<HR>';

		$this -> status_projetos();

	}

	function status_projetos() {
		$sql = "select pj_status, count(*) as total 
						from " . $this -> tabela . " 
						where pj_ano = '" . date("Y") . "' and pj_status <> 'X' and pj_status <> '!'
						group by pj_status 
						";
		$rlt = db_query($sql);
		$id = 0;
		while ($line = db_read($rlt)) {
			$id = $id + $line['total'];
			$sta = $line['pj_status'];
			switch ($sta) {
				case '@' :
					echo 'Em correção do professor';
					break;
				case 'B' :
					echo 'Submetido, aguardando análise';
					break;
				case 'C' :
					echo 'Aguardando indicação de avaliador';
					break;
				case 'D' :
					echo 'Aguardando avaliação';
					break;

				case 'F' :
					echo 'Avaliação finalizada';
					break;
				case 'T' :
					echo 'Com a TI';
					break;
				default :
					echo 'Outros (' . $sta . ')';
			}
			echo ' ' . $line['total'] . '<BR>';
		}
		echo '<B>Total ' . $id . '</B>';
	}

	function projetos_para_correcao($professor = '') {
		$sql = "select count(*) as total from " . $this -> tabela . " where pj_professor = '" . $professor . "' ";
		$rlt = db_query($sql);

		$line = db_read($rlt);
		$total = $line['total'];
		return ($total);
	}

	function gerar_relatorio_analitico_avaliador_projetos($ano = '') {
		global $jid;
		if (strlen($ano) == 0) { $ano = date("Y");
		}

		$sql = "select * from ajax_areadoconhecimento where a_ativo = '1' order by a_cnpq";
		$sql = "select * from ajax_areadoconhecimento order by a_cnpq";
		$rlt = db_query($sql);

		$area = array();
		$aa = array();
		while ($line = db_read($rlt)) {
			array_push($area, array(trim($line['a_cnpq']), trim($line['a_descricao']), 0, 0, 0, 0, 0, 0));
			array_push($aa, trim($line['a_cnpq']));
		}

		/* Projetos */
		$sql = "select * from " . $this -> tabela . " where pj_ano = '" . $ano . "'
						and (pj_status <> '@' and pj_status <> 'X')
						";
		$rlt = db_query($sql);
		while ($line = db_read($rlt)) {
			$a = trim($line['pj_area']);
			$key = array_search($a, $aa);
			$area[$key][2] = $area[$key][2] + 1;
		}

		/* Avaliadores Externos */
		$sql = "select pa_area, a_cnpq from pareceristas 
						left join pareceristas_area on us_codigo = pa_parecerista
						left join ajax_areadoconhecimento on a_codigo = pa_area
						where us_journal_id = '" . $jid . "' and (us_ativo = 1) and (us_aceito = 10)";
		$rlt = db_query($sql);
		while ($line = db_read($rlt)) {
			$a = trim($line['a_cnpq']);
			$key = array_search($a, $aa);
			$area[$key][3] = $area[$key][3] + 1;
		}

		/* Avaliadores Internos */
		$sql = "select pa_area, a_cnpq from pibic_professor 
						inner join pareceristas_area on pp_cracha = pa_parecerista
						left join ajax_areadoconhecimento on a_codigo = pa_area
						where pp_ativo = 1 ";
		$rlt = db_query($sql);
		while ($line = db_read($rlt)) {
			$a = trim($line['a_cnpq']);
			$key = array_search($a, $aa);
			$area[$key][4] = $area[$key][4] + 1;
		}

		$sx .= '<table class="tabela00" width="100%">';
		$sx .= '<TR><TD rowspan=2 colspan=2>Área do conhecimento<TD colspan=5 align="center">N° de avaliadores';
		$sx .= '<TR>
							<TD width="80" align="center">Projetos
							<TD width="80" align="center">Externo
							<TD width="80" align="center">Interno
							<TD width="80" align="center">GAP Externo
							<TD width="80" align="center">GAP Interno';
		for ($r = 0; $r < count($area); $r++) {
			if (($area[$r][2] + $area[$r][3] + $area[$r][4]) > 0) {
				$sx .= '<TR>';
				$sx .= '<TD class="tabela01"><nobr>';
				$sx .= $area[$r][0];
				$sx .= '<TD class="tabela01">';
				$sx .= $area[$r][1];
				$sx .= '<TD align="center" class="tabela01">';
				$sx .= $area[$r][2];
				$sx .= '<TD align="center" class="tabela01">';
				$sx .= $area[$r][3];
				$sx .= '<TD align="center" class="tabela01">';
				$sx .= $area[$r][4];

				$vl = $area[$r][2] - $area[$r][3];
				if ($vl > 0) { $cor = '<font color="red">';
				} else { $cor = '<font color="blue">';
				}
				$sx .= '<TD align="center" class="tabela01">';
				$sx .= $cor . $vl . '</font>';

				$vl = $area[$r][2] - $area[$r][4];
				if ($vl > 0) { $cor = '<font color="red">';
				} else { $cor = '<font color="blue">';
				}
				$sx .= '<TD align="center" class="tabela01">';
				$sx .= $cor . $vl . '</font>';
			}
		}
		$sx .= '</table>';
		return ($sx);
	}

	function acao_enviar_email_avaliacao($avaliador, $tipo = '', $data) {
		global $email_adm, $admin_nome;

		$sql = "select * from pareceristas where us_codigo = '" . $avaliador . "' ";
		$rlt = db_query($sql);
		if ($line = db_read($rlt)) {
			$nome = trim($line['us_nome']);
			$email = trim($line['us_email']);
			$email2 = trim($line['us_email_alternativo']);
		}

		//$sql = "select * from ic_noticia where nw_ref = 'PARIC_AVAL_SUBMI' ";
		$sql = "select * from ic_noticia where nw_ref = 'ic_indicacao_SUBMI' ";
		$rlt = db_query($sql);

		if ($line = db_read($rlt)) {
			$texto = $line['nw_descricao'];
			$texto = troca($texto, chr(13), '<BR>');
			$texto = troca($texto, '$parecerista', $nome);

			$chk = substr(md5('pibic' . date("Y") . $avaliador), 0, 10);
			$linkx = 'http://www2.pucpr.br/reol/avaliador/';
			$linkx .= 'acesso.php?dd0=' . $avaliador . '&dd90=' . $chk;
			$link = '<A HREF="' . $linkx . '" target="new">';
			$link .= $linkx;
			$link .= '</A>';
			$texto = troca($texto, '$parecer_link', $link);
			$texto = troca($texto, '$data_previsao', '<B>' . $data . '</B>');

			//$email_adm = 'pibicpr@pucpr.br';
			//$admin_nome = 'PIBIC (PUCPR)';
			//enviaremail('monitoramento@sisdoc.com.br','','Indicação de avaliação Iniciação Científica',$texto);

			if (strlen($email) > 0) { enviaremail($email, '', '[IC] - Indicação de avaliação Iniciação Científica - ' . trim($nome), $texto);
				echo '>>>' . $email;
			}
			if (strlen($email2) > 0) { enviaremail($email2, '', '[IC] - Indicação de avaliação Iniciação Científica (copia)' . trim($nome), $texto);
				echo '>>>' . $email2;
			}
			$texto .= '<BR><BR>' . $email . ' ' . $email2;
			enviaremail('pibicpr@pucpr.br', '', '[IC] - Indicação de avaliação Iniciação Científica (copias)', $texto);
			enviaremail('monitoramento@sisdoc.com.br', '', '[IC] - Indicação de avaliação Iniciação Científica (copia)', $texto);
		}
		return (true);
	}

	function acao_indicar_avaliaca($protocolo, $avaliador, $obj, $tipo, $data = 0) {
		if ($data == 0) {
			$data = mktime(0, 0, 0, date("m"), date("d"), date("Y"), 0);
			$data = $data + 15 * 24 * 60 * 60;
			$data = date("Ymd", $data);
		}
		$sql = "select * from " . $obj -> tabela . " 
					where pp_protocolo = '" . $protocolo . "' 
					and pp_avaliador = '" . $avaliador . "'
					and pp_tipo = '$tipo'
					";
		$rlt = db_query($sql);
		if ($line = db_read($rlt)) {
			$sta = trim($line['pp_status']);
			//if (($sta != 'X') and ($sta != '@'))
			{
				$sql = "update " . $obj -> tabela . " set
								pp_data = $data, 
								pp_status = '@',
								pp_tipo = '$tipo'
								where (id_pp = " . $line['id_pp'] . ")
								or (pp_protocolo_mae = '" . $line['pp_protocolo'] . "' 
								and pp_avaliador = '" . $avaliador . "')";
				$rlt = db_query($sql);
				echo '<BR><font color=green>Reenviar e-mail</font><BR> ';
				$this -> acao_enviar_email_avaliacao($avaliador, '', stodbr($data));
			}
		} else {
			$sql = "insert into " . $obj -> tabela . "
						(pp_tipo, pp_protocolo, pp_protocolo_mae, 
						pp_avaliador, pp_revisor, pp_status, 
						pp_pontos, pp_pontos_pp, pp_data, 
						pp_data_leitura, pp_hora, pp_parecer_data, 
						pp_parecer_hora, 
						pp_p01, pp_p02, pp_p03, pp_p04, pp_p05,
						pp_p06, pp_p07, pp_p08, pp_p09, pp_p10,
						pp_p11, pp_p12, pp_p13, pp_p14, pp_p15,
						pp_p16, pp_p17, pp_p18, pp_p19, 
						pp_abe_01, pp_abe_02, pp_abe_03, pp_abe_04, pp_abe_05,
						pp_abe_06, pp_abe_07, pp_abe_08, pp_abe_09, pp_abe_10 
						) values (
						'$tipo','$protocolo','',
						'$avaliador','','@',
						0,0,$data,
						0,'',0,
						'',
						0,0,0,0,0,
						0,0,0,0,0,
						0,0,0,0,0,
						0,0,0,0,
						'','','','','',
						'','','','',''
						)";
			$rlt = db_query($sql);
			$this -> acao_enviar_email_avaliacao($avaliador, '', stodbr($data));
		}
	}

	function projetos_validar($ano, $status = '@') {
		$sql = "select * from " . $this -> tabela . "
					left join ajax_areadoconhecimento on a_cnpq = pj_area
					where pj_status = '" . $status . "' and pj_ano = '$ano'
					order by a_cnpq 
			";
		$rlt = db_query($sql);

		$tot = 0;

		$sx = '<table width="100%">';
		$sx .= '<TR>';
		$sx .= '<TH>Protocolo';
		$sx .= '<TH>Título';
		$sx .= '<TH>Status';
		$xarea = '';
		while ($line = db_read($rlt)) {
			$area = $line['a_descricao'];
			if ($xarea != $area) {
				$sx .= '<TR><TD colspan=2><B>' . $line['a_cnpq'] . '-' . $line['a_descricao'] . '</td></tr>';
				$xarea = $area;
			}
			$tot++;
			$sx .= '<TR>';
			$sx .= $this -> mostra_projeto_linha($line);
		}
		$sx .= '<TR><TD colspan=5>Total ' . $tot;
		$sx .= '</table>';
		return ($sx);
	}

	function mostra_projeto_linha($line) {
		$protocolo = trim($line['pj_codigo']);
		$link = 'pibic_projetos_detalhes.php?dd0=' . $protocolo . '&dd90=' . checkpost($protocolo);
		$link = '<A HREF="' . $link . '">';
		$sx .= '<TR>';
		$sx .= '<TD>';
		$sx .= $link;
		$sx .= $line['pj_codigo'];
		$sx .= '</A>';
		$sx .= '<TD>';
		$sx .= $line['pj_titulo'];
		$sx .= '<TD align="center">';
		$sx .= $line['pj_status'];
		return ($sx);
	}

	function lista_submissoes_excel($ano, $modalidade) {
		if (strlen($modalidade) > 0) { $wh .= " and (doc_edital = '" . $modalidade . "') ";
		}
		$ano = $this -> ano;
		if (strlen($ano) == 0) { $ano = date("Y");
		}

		$sql = "select * from " . $this -> tabela . "
					left join pibic_professor on pj_professor = pp_cracha
					left join centro on pp_escola = centro_codigo
					left join " . $this -> tabela_planos . " on doc_protocolo_mae = pj_codigo
					left join pibic_aluno on doc_aluno = pa_cracha
					left join ajax_areadoconhecimento on a_cnpq = pj_area  
					where pj_ano = '$ano'
					and (pj_status <> '!' and pj_status <> '@' and pj_status <> 'X' and pj_status <> 'E')
					and (doc_status <> '!' and doc_status <> '@' and doc_status <> 'X' and doc_status <> 'E')
					$wh
			";
		$rlt = db_query($sql);

		$id = 0;
		$sx = '<table>';
		$sx .= '<TR>
					<TH>PROJETO
					<th>COD.PROFESSOR
					<th>PROFESSOR
					<TH>ESCOLA
					<TH>CAMPUS
					<TH>CURSO
					<th>TIT.PROJETO
					<TH>STATUS
					<th>PROT. PLANO
					<th>TÍTULO PLANO
					<th>COD.ALUNO
					<th>ALUNO
					<th>MODALIDADE
					<th>CURSO
					<th>STATUS
					<th>AREA
					<th>AREA DESCRICAO
					';
		while ($line = db_read($rlt)) {
			$id++;
			$sx .= '<TR>';
			$sx .= '<TD>';
			$sx .= trim($line['pj_codigo']);
			$sx .= '<TD>';
			$sx .= trim($line['pp_cracha']);
			$sx .= '<TD>';
			$sx .= trim($line['pp_nome']);
			$sx .= '<TD>';
			$sx .= trim($line['centro_nome']);
			$sx .= '<TD>';
			$sx .= trim($line['pp_centro']);
			$sx .= '<TD>';
			$sx .= trim($line['pp_curso']);
			$sx .= '<TD>';
			$sx .= trim($line['pj_titulo']);
			$sx .= '<TD>';
			$sx .= trim($line['pj_status']);
			$sx .= '<TD>';
			$sx .= trim($line['doc_protocolo']);
			$sx .= '<TD>';
			$sx .= trim($line['doc_1_titulo']);
			$sx .= '<TD>';
			$sx .= trim($line['doc_aluno']);
			$sx .= '<TD>';
			$sx .= trim($line['pa_nome']);
			$sx .= '<TD>';
			$sx .= trim($line['doc_edital']);
			$sx .= '<TD>';
			$sx .= trim($line['pa_curso']);
			$sx .= '<TD>';
			$sx .= trim($line['doc_status']);
			$sx .= '<TD>';
			$sx .= trim($line['doc_area']);
			$sx .= '<TD>';
			$sx .= trim($line['a_descricao']);

			$ln = $line;
		}
		$sx .= '<TR><TD>' . $id . ' total';
		$sx .= '</table>';
		return ($sx);
	}

	function projetos_area($area = '', $ano = '2013') {

		$sql = "select * from " . $this -> tabela . " 
					where pj_area_estra = '$area' or pj_area = '$area'
					and (pj_status = 'B' or pj_status = 'C' or pj_status = 'D' or pj_status = 'E')
					and pj_ano = '$ano'
			";

		$rlt = db_query($sql);
		$tot = 0;
		while ($line = db_read($rlt)) {
			$tot++;
			$sx .= $this -> projeto_mostra_mini($line);
			$sx .= '<BR><BR>';
			//e/xit;
		}
		$sx .= '<BR>Total ' . $tot;
		return ($sx);
	}

	function razao_submissao_planos($ano, $edital = '', $status = 'B') {
		if ($status == 'B') {
			$wh = "
				where pj_ano = '" . date("Y") . "'
					and (doc_status = 'B' or doc_status = 'C' or doc_status = 'D' or doc_status = 'E') 
					and (pj_status = 'B' or pj_status = 'C' or pj_status = 'D' or pj_status = 'E') ";
		}
		if ($status == '@') {
			$wh = "
				where pj_ano = '" . date("Y") . "'
					and (doc_status = '@' or doc_status = 'A') 
					and (pj_status = '@' or pj_status = 'A') ";
		}
		/* pj_professor */
		$sql = "select * from pibic_submit_documento 
				left join " . $this -> tabela . " on pj_codigo = doc_protocolo_mae
				left join pibic_professor on doc_autor_principal = pp_cracha
				" . $wh;
		if (strlen($edital) > 0) { $sql .= " and doc_edital = '$edital' ";
		}
		$sql .= "
				order by pp_centro, pp_nome, doc_edital, pj_codigo, doc_protocolo
			";
		$rlt = db_query($sql);

		$sx = '<table width="100%" class="tabela00">';
		$xcentro = "X";
		$xnome = "X";
		$id = 0;
		$tot0 = 0;
		while ($line = db_read($rlt)) {
			$id++;
			$centro = trim($line['pp_centro']);
			$nome = trim($line['pp_nome']);
			$professor = trim($line['doc_autor_principal']);
			$prefessor_pj = trim($line['pj_professor']);
			$status = trim($line['doc_status']);

			if (strlen($professor) == 0) {
				$sql = "update pibic_submit_documento set 
								doc_autor_principal = '" . $prefessor_pj . "'
								where id_doc = " . $line['id_doc'];
				//echo $sql;
				$rrr = db_query($sql);
				//print_r($line);
				//exit;
			}

			if ($centro != $xcentro) {
				if ($tot0 > 0) {
					$sx .= '<TR><TD colspan=10 class="lt0">';
					$sx .= 'Total no centro ' . $tot0;
				}
				$sx .= '<TR><TD colspan=10 class="lt4">';
				$sx .= trim($line['pp_centro']);
				$xcentro = $centro;
				$tot0 = 0;
				//print_r($line);
			}
			if ($nome != $xnome) {
				$sx .= '<TR>
									<TD>&nbsp;&nbsp;
									<TD colspan=9 class="lt3">';
				$sx .= $nome;
				$xnome = $nome;
			}
			if ($status == '@') { $cor = '<font color="green">';
				$status = 'Em submissão';
			}
			if ($status == 'B') { $cor = '<font color="black">';
				$status = 'Concluído';
			}
			if ($status == 'X') { $cor = '<font color="red">';
				$status = 'Cancelado';
			}

			$id = $line['id_pj'];
			$link = '<A HREF="pibic_projetos_detalhes.php?dd0=' . $id . '&dd90=' . checkpost($id) . '" target="_new" class="link2">';
			$sx .= '<TR>
							<TD colspan=2>&nbsp;&nbsp;&nbsp;&nbsp;';
			$sx .= '<TD class="tabela01">' . $cor . trim($line['doc_edital']) . '</font>';
			$sx .= '<TD class="tabela01">' . $cor . trim($line['pj_codigo']) . '</font>';
			$sx .= '<TD class="tabela01">' . $link . $cor . trim($line['doc_protocolo']) . '</A></font>';
			$sx .= '<TD class="tabela01">' . $cor . trim($line['doc_1_titulo']) . '</font>';
			$sx .= '<TD class="tabela01">' . $cor . $status . '</font>';
			$sx .= '<TD class="tabela01">' . $cor . stodbr($line['doc_data']) . '</font>';
			$sx .= '<TD class="tabela01">' . $cor . trim($line['pj_professor']) . '</font>';
			$tot0++;

		}
		if ($tot0 > 0) {
			$sx .= '<TR><TD colspan=10 class="lt0">';
			$sx .= 'Total no centro ' . $tot0;
		}
		$sx .= '<TR><TD colspan=10>' . $id . ' total de planos';
		$sx .= '</table>';
		return ($sx);
	}

	function alterar_estatus_para_indicado() {
		$sql = "
						select * from " . $this -> tabela . " 
							inner join pibic_submit_documento on (pj_codigo =  doc_protocolo_mae) and (doc_status <> 'X' and doc_status <> 'X')
							where pj_ano = '" . date("Y") . "' and (pj_status <> 'X' and pj_status <> '@') 	
						";
		$rlt = db_query($sql);
		while ($line = db_read($rlt)) {
			$sta = trim($line['pj_status']);
			$stb = trim($line['doc_status']);
			$proto = $line['doc_protocolo'];
			if ($sta != $stb) {
				$sql = "update pibic_submit_documento set doc_status = '" . $sta . "' where doc_protocolo = '" . $proto . "' ";
				$rrr = db_query($sql);
			}

		}

		$sql = "
					select * from " . $this -> tabela . " left join (
					select count(*) as total, pp_protocolo from pibic_parecer_" . date("Y") . " 
					where (pp_status <> 'D' and pp_status <> 'X') and (pp_tipo = 'SUBMI')
					group by pp_protocolo 
					) as tabela on pp_protocolo = pj_codigo
					where pj_status <> 'X' and pj_status <> '@'
					";
		//$sql = "select * from pibic_parecer_".date("Y")." limit 1";
		$rlt = db_query($sql);
		while ($line = db_read($rlt)) {
			$total = $line['total'];
			$status = $line['pj_status'];
			$proto = $line['pj_codigo'];
			//echo '<BR>'.$protp.'-'.$status.'-'.$total;
			if (($status == 'C') and ($total >= 2)) {
				$sql = "update " . $this -> tabela . " set pj_status ='D' where id_pj = " . $line['id_pj'];
				$rrr = db_query($sql);
			}
			if (($status == 'D') and ($total < 2)) {
				$sql = "update " . $this -> tabela . " set pj_status ='C' where id_pj = " . $line['id_pj'];
				$rrr = db_query($sql);
			}
		}
	}

	function razao_submissao_planos_indicar($data, $status = '@') {
		if ($status == 'C') {
			/* Alterar no sistema para indicados */
			$this -> alterar_estatus_para_indicado();
		}
		$wh = "
				where pj_ano = '" . date("Y") . "'
					and ((doc_status = '$status') 
					or (pj_status = '$status')) and doc_status <> 'X' ";

		/* pj_professor */
		$sql = "select * from pibic_submit_documento 
				left join " . $this -> tabela . " on pj_codigo = doc_protocolo_mae
				left join pibic_professor on doc_autor_principal = pp_cracha
				" . $wh;
		if (strlen($edital) > 0) { $sql .= " and doc_edital = '$edital' ";
		}
		$sql .= "
				order by pp_centro, pp_nome, doc_edital, pj_codigo, doc_protocolo
			";
		$rlt = db_query($sql);
		$sx = '<table width="100%" class="tabela00">';
		$xcentro = "X";
		$xnome = "X";
		$idt = 0;
		$tot0 = 0;
		while ($line = db_read($rlt)) {
			$idt++;
			$centro = trim($line['pp_centro']);
			$nome = trim($line['pp_nome']);
			$professor = trim($line['doc_autor_principal']);
			$prefessor_pj = trim($line['pj_professor']);
			$status = trim($line['doc_status']);

			if (strlen($professor) == 0) {
				$sql = "update pibic_submit_documento set 
								doc_autor_principal = '" . $prefessor_pj . "'
								where id_doc = " . $line['id_doc'];
				//echo $sql;
				$rrr = db_query($sql);
				//print_r($line);
				//exit;
			}

			if ($centro != $xcentro) {
				if ($tot0 > 0) {
					$sx .= '<TR><TD colspan=10 class="lt0">';
					$sx .= 'Total no centro ' . $tot0;
				}
				$sx .= '<TR><TD colspan=10 class="lt4">';
				$sx .= trim($line['pp_centro']);
				$xcentro = $centro;
				$tot0 = 0;
				//print_r($line);
			}
			if ($nome != $xnome) {
				$sx .= '<TR>
									<TD>&nbsp;&nbsp;
									<TD colspan=9 class="lt3">';
				$sx .= $nome;
				$xnome = $nome;
			}
			if ($status == '@') { $cor = '<font color="green">';
				$status = 'Em submissão';
			}
			if ($status == 'B') { $cor = '<font color="black">';
				$status = 'Concluído';
			}
			if ($status == 'C') { $cor = '<font color="black">';
				$status = 'Aceito';
			}
			if ($status == 'D') { $cor = '<font color="black">';
				$status = 'Finalizado';
			}
			if ($status == 'P') { $cor = '<font color="red">';
				$status = 'Problemas Coordenação';
			}
			if ($status == 'T') { $cor = '<font color="red">';
				$status = 'Problemas com a TI';
			}
			if ($status == 'X') { $cor = '<font color="red">';
				$status = 'Cancelado';
			}

			$id = $line['id_pj'];
			$link = '<A HREF="pibic_projetos_detalhes.php?dd0=' . $id . '&dd90=' . checkpost($id) . '" target="_new" class="link2">';

			$sx .= '<TR>
							<TD colspan=2>&nbsp;&nbsp;&nbsp;&nbsp;';
			$sx .= '<TD class="tabela01">' . $cor . trim($line['doc_edital']) . '</font>';
			$sx .= '<TD class="tabela01">' . $link . trim($line['pj_codigo']) . '</A>';
			$sx .= '<TD class="tabela01">' . $cor . trim($line['doc_protocolo']) . '</font>';
			$sx .= '<TD class="tabela01">' . $cor . trim($line['doc_1_titulo']) . '</font>';
			$sx .= '<TD class="tabela01">' . $cor . $status . '</font>';
			$sx .= '<TD class="tabela01">' . $cor . stodbr($line['doc_data']) . '</font>';
			$sx .= '<TD class="tabela01">' . $cor . trim($line['pj_professor']) . '</font>';
			$tot0++;

		}
		if ($tot0 > 0) {
			$sx .= '<TR><TD colspan=10 class="lt0">';
			$sx .= 'Total no centro ' . $tot0;
		}
		$sx .= '<TR><TD colspan=10>' . $idt . ' total de planos';
		$sx .= '</table>';
		return ($sx);
	}

	function row() {
		global $cdf, $cdm, $masc;
		$cdf = array('id_pj', 'pj_codigo', 'pj_titulo', 'pj_ano', 'pj_status', 'pj_professor');
		$cdm = array('cod', msg('codigo'), msg('titulo'), msg('ano'), msg('status'), msg('professor'));
		$masc = array('', '', '', '', '', '', '');
		return (1);
	}

	function row_plano() {
		global $cdf, $cdm, $masc;
		$cdf = array('id_doc','doc_protocolo_mae', 'doc_protocolo','doc_1_titulo', 'doc_autor_principal', 'doc_status', 'doc_aluno','doc_ano','pb_vies');
		$cdm = array('id','cod', msg('protocolo'),msg('titulo'), msg('professor'), msg('status'), msg('aluno'), msg('ano'),'vies');
		$masc = array('', '', '', '', '', '', '');
		
		return (1);
	}

	function cp_ti() {
		$cp = array();
		array_push($cp, array('$H8', 'id_doc', '', False, TRUE));
		array_push($cp, array('$M8', '', '<B>Título do plano do aluno', False, TRUE));
		array_push($cp, array('$T60:5', 'doc_1_titulo', 'Título', TRUE, TRUE));
		return ($cp);
	}

	function cp_pj_ti() {
		$cp = array();
		array_push($cp, array('$H8', 'id_pj', '', False, TRUE));
		array_push($cp, array('$M8', '', '<B>Título do projeto do professor', False, TRUE));
		array_push($cp, array('$T60:5', 'pj_titulo', 'Título', TRUE, TRUE));
		return ($cp);
	}

	function cp_plano() {
		$sql = "alter table pibic_submit_documento add column doc_recurso int8";
		//$rlt = db_query($sql);
		
		$cp = array();
		array_push($cp, array('$H8', 'id_doc', '', False, TRUE));
		array_push($cp, array('$T60:5', 'doc_1_titulo', 'Título', TRUE, TRUE));
		array_push($cp, array('$S8', 'doc_aluno', 'Aluno', TRUE, TRUE));
		array_push($cp, array('$S1', 'doc_status', 'Status', TRUE, TRUE));
		array_push($cp, array('$S8', 'doc_autor_principal', 'Professor', TRUE, TRUE));
		array_push($cp, array('$S8', 'doc_edital', 'Edital', TRUE, TRUE));
		array_push($cp, array('$O :Não&1:SIM', 'pb_vies', 'Viés PIBITI', False, TRUE));
		array_push($cp, array('$[0-20]', 'doc_recurso', 'Nota (+) do recurso', False, TRUE));
		return ($cp);
	}

	function cp() {
		$cp = array();
		array_push($cp, array('$H8', 'id_pj', '', False, TRUE));
		array_push($cp, array('$H8', '', '', False, TRUE));
		array_push($cp, array('$S8', 'pj_codigo', 'Protocolo', True, True));
		array_push($cp, array('${', '', 'Dados do projeto do professor', False, True));
		array_push($cp, array('$M8', '', '<B>Título do projeto do professor', False, TRUE));
		array_push($cp, array('$T60:5', 'pj_titulo', 'Título', TRUE, TRUE));

		//				array_push($cp,array('$M8','','<B>Breve resumo (até 250 palavaras)',False,TRUE));
		//				array_push($cp,array('$T60:5','pj_resumo','Resumo',False,TRUE));

		//				array_push($cp,array('$M8','','<B>Palavras-chave (até 5 termos)',False,TRUE));
		//				array_push($cp,array('$S255','pj_keyword','Palavras-chave',False,TRUE));

		//				array_push($cp,array('$}','','Dados do projeto do professor',False,True));

		array_push($cp, array('${', '', 'Área de conhecimento', False, True));
		array_push($cp, array('$S14 ', 'pj_area', 'Área', TRUE, TRUE));
		//array_push($cp,array('$S14','pj_area_estrategica','Área estratégica',False,True));
		array_push($cp, array('$}', '', 'Dados do projeto do professor', False, True));

		array_push($cp, array('$S1', 'pj_status', 'Status', True, True));
		array_push($cp, array('$S8', 'pj_professor', 'Professor', TRUE, TRUE));
		return ($cp);
	}

	/*
	 * MOstra Planos
	 */
	function mostra_planos_projetos() {
		global $perfil;
		$protocolo = $this -> protocolo;
		$sql = "select * from " . $this -> tabela_planos . "
						left join pibic_aluno on pa_cracha = doc_aluno 
						where doc_protocolo_mae = '" . $protocolo . "' 
						and doc_status <> 'X'
						";
		$rlt = db_query($sql);
		$tot = 0;
		$sx = '';
		while ($line = db_read($rlt)) {
			$tot++;
			$sx .= $this -> mostra_plano_aluno($line);
			if ($perfil -> valid('#CPI#ADM#CPP')) {
				$sx .= '<BR><span onclick="newxy2(\'pibic_plano_editar.php?dd0=' . $line['id_doc'] . '&dd90=' . checkpost($line['id_doc']) . '\',600,500);" class="link">';
				$sx .= '<DIV class="button_submit">editar</div>';
				$sx .= '</span><br><br>';
			}

		}
		return ($sx);
	}

	function mostra_planos($edital, $ano) {
		$sql = "select * from " . $this -> tabela_planos . "
						left join pibic_aluno on pa_cracha = doc_aluno 
						where doc_ano = '" . $ano . "' 
						and (doc_status <> '!') and (doc_status <> '@') and (doc_status <> 'X')
					
						and doc_edital = '" . $edital . "'
						";
		$rlt = db_query($sql);
		$tot = 0;
		$sx = '';
		while ($line = db_read($rlt)) {
			$tot++;
			$sx .= $this -> mostra_plano_aluno($line);
		}
		$sx .= 'Total ' . $tot;
		return ($sx);
	}

	/**
	 * Resumos
	 */
	function resumo_planos_centro($ss = '', $centro = 0, $escola = 1) {
		$proto = $this -> protocolo;
		$ano = $this -> ano;
		if (strlen($ano) == 0) { $ano = date("Y");
		}
		$cp = "*";
		if ($escola == 1) {
			$cp = 'centro_nome, centro_codigo, doc_edital, centro_meta_01, centro_meta_02, centro_meta_03';
			$sql = "select count(*) as total, $cp from " . $this -> tabela . ' ';
			if ($ss == 'SS') { $sql .= "left join pibic_professor on (pj_professor = pp_cracha)  ";
			}
			if ($ss == 'NS') { $sql .= "left join pibic_professor on (pj_professor = pp_cracha)  ";
			}
			if ($ss == '') { $sql .= "left join pibic_professor on pj_professor = pp_cracha ";
			}

			$sql .= "inner join pibic_submit_documento on pj_codigo = doc_protocolo_mae
								left join centro on pp_escola = centro_codigo
								where pj_ano = '" . $ano . "'
										and (doc_status <> 'X' and doc_status <> '!' and doc_status <> '@') 
										and (pj_status = 'B' or pj_status = 'C' or pj_status = 'D' or pj_status = 'T'  or pj_status = 'A')
								";

			if ($ss == 'SS') { $sql .= "and (pp_ss = 'S')  ";
			}
			if ($ss == 'NS') { $sql .= "and (pp_ss = 'N')  ";
			}

			$sql .= "group by $cp
								order by centro_codigo ";
		}
		$rlt = db_query($sql);
		$totalp = 0;
		$totalt = 0;
		$totale = 0;
		$totali = 0;

		$ttotalp = 0;
		$ttotalt = 0;
		$ttotale = 0;
		$ttotali = 0;

		$cap = "--";
		//$sx .= '<center><h2>';
		//$sx .= 'NÚMERO DE PLANOS DE TRABALHO ALUNOS SUBMETIDOS<br>';
		//$sx .= 'NO EDITAL DA INICIAÇÃO CIENTÍFICA - '.$ano.'</h2>';
		$sx .= '<table width="100%" align="center" class="tabela00">';
		$sx .= '<TR><TH>Escola<TH>PIBIC<TH>PIBITI<TH>PIBIC_EM<TD>Intern.<TH>Sub-total';
		$rs = array();
		while ($line = db_read($rlt)) {
			$xcap = trim($line['centro_nome']);
			if ($cap != $xcap) {
				$sx .= $this -> resumo_mostra_painel($cap, $totalp, $totalt, $totale);
				array_push($rs, array($cap, $totalp, $totalt, $totoale, $centro_meta_01, $centro_meta_02, $centro_meta_04));
				$cap = $xcap;

				$totalp = 0;
				$totalt = 0;
				$totale = 0;
			}
			$centro_meta_01 = $line['centro_meta_01'];
			$centro_meta_02 = $line['centro_meta_02'];
			$centro_meta_03 = $line['centro_meta_03'];

			$total = $line['total'];
			$edital = trim($line['doc_edital']);
			if ($edital == '') { $totalp = $totalp + $total;
			}
			if ($edital == 'PIBIC') { $totalp = $totalp + $total;
			}
			if ($edital == 'PIBITI') { $totalt = $totalt + $total;
			}
			if ($edital == 'PIBICE') { $totale = $totale + $total;
			}
			if ($edital == 'ICI') { $totali = $totali + $total;
			}

			if ($edital == 'PIBIC') { $ttotalp = $ttotalp + $total;
			}
			if ($edital == 'PIBITI') { $ttotalt = $ttotalt + $total;
			}
			if ($edital == 'PIBICE') { $ttotale = $ttotale + $total;
			}
			if ($edital == 'ICI') { $ttotali = $ttotali + $total;
			}
		}
		$sx .= $this -> resumo_mostra_painel($cap, $totalp, $totalt, $totale, $totali);
		array_push($rs, array($cap, $totalp, $totalt, $totoale, $totoali, $centro_meta_01, $centro_meta_02, $centro_meta_04));

		$sx .= '<TR><TD class="tabela00" align="right"><B>Totais';
		$sx .= '<TD class="tabela01" align="center"><B>' . $ttotalp;
		$sx .= '<TD class="tabela01" align="center"><B>' . $ttotalt;
		$sx .= '<TD class="tabela01" align="center"><B>' . $ttotale;
		$sx .= '<TD class="tabela01" align="center"><B>' . $ttotali;
		$sx .= '<TD class="tabela01" align="center"><B>' . ($ttotale + $ttotalt + $ttotalp + $ttotali);
		$sx .= '</table>';
		$this -> plano_pibic = $totalp;
		$this -> plano_pibiti = $totalt;
		$this -> plano_pibic_em = $totale;
		$this -> plano_ici = $totali;
		$this -> rst = $rs;

		return ($sx);
	}

	function resumo_projeto_centro($ss = '', $centro = 0, $escola = 1) {
		$proto = $this -> protocolo;
		$cp = "*";
		if ($escola == 1) {
			$cp = 'centro_nome, centro_codigo, doc_edital, centro_meta_01, centro_meta_02, centro_meta_03';
			$sql = "select count(*) as total, $cp from " . $this -> tabela . ' ';

			$sql .= "inner join 
								(select doc_edital,doc_autor_principal from pibic_submit_documento group by doc_edital,doc_autor_principal ) 
								as tabela on pj_professor = doc_autor_principal
								";
			$sql .= " left join pibic_professor on doc_autor_principal = pp_cracha ";

			$sql .= " left join centro on pp_escola = centro_codigo
								where (pj_status <> 'X' and pj_status <> '@')";

			if ($ss == 'SS') { $sql .= "and (pp_ss = 'S')  ";
			}
			if ($ss == 'NS') { $sql .= "and (pp_ss = 'N')  ";
			}

			$sql .= " group by $cp
								order by centro_codigo ";
		}

		$rlt = db_query($sql);
		$totalp = 0;
		$totalt = 0;
		$totale = 0;

		$ttotalp = 0;
		$ttotalt = 0;
		$ttotale = 0;

		$cap = "xxxx";
		$sx .= '<table width="704" align="center" class="lt1">';
		$sx .= '<TR><TH>Escola<TH>PIBIC<TH>PIBITI<TH>PIBIC_EM';
		while ($line = db_read($rlt)) {
			$xcap = trim($line['centro_nome']);
			if ($cap != $xcap) {
				$sx .= $this -> resumo_mostra_painel($cap, $totalp, $totalt, $totale);
				$cap = $xcap;

				$totalp = 0;
				$totalt = 0;
				$totale = 0;
			}
			$total = $line['total'];
			$edital = trim($line['doc_edital']);
			if ($edital == '') { $totalp = $total;
			}
			if ($edital == 'PIBIC') { $totalp = $total;
			}
			if ($edital == 'PIBITI') { $totalt = $total;
			}
			if ($edital == 'PIBICE') { $totale = $total;
			}

			if ($edital == 'PIBIC') { $ttotalp = $ttotalp + $total;
			}
			if ($edital == 'PIBITI') { $ttotalt = $ttotalt + $total;
			}
			if ($edital == 'PIBICE') { $ttotale = $ttotale + $total;
			}
		}
		$sx .= $this -> resumo_mostra_painel($cap, $totalp, $totalt, $totale);
		$sx .= '<TR><TD class="tabela00" align="right"><B>Totais';
		$sx .= '<TD align="center" class="tabela01">' . $ttotalt;
		$sx .= '<TD align="center" class="tabela01">' . $ttotale;

		$sx .= '</table>';
		$this -> plano_pibic = $totalp;
		$this -> plano_pibiti = $totalt;
		$this -> plano_pibic_em = $totale;

		return ($sx);
	}

	function resumo_planos_campi() {
		/* pj_professor */
		$ano = $this -> ano;
		if (strlen($ano) == 0) { $ano = date("Y");
		}

		$cp = 'pp_centro, doc_edital';
		$sql = "select count(*) as total, $cp from " . $this -> tabela . ' ';
		$sql .= "left join pibic_professor on pj_professor = pp_cracha ";

		$sql .= "inner join pibic_submit_documento on pj_codigo = doc_protocolo_mae
						left join centro on pp_escola = centro_codigo
				where (doc_ano = '" . $ano . "') 
					and (doc_status = 'B' or doc_status = 'C' or doc_status = 'D' or doc_status = 'F' or doc_status = 'T'  or doc_status = 'A') 
					and (pj_status = 'B' or pj_status = 'C' or pj_status = 'D' or pj_status = 'F' or pj_status = 'E' or pj_status = 'T'  or pj_status = 'A') ";
		$sql .= "group by $cp
						order by pp_centro ";

		$rlt = db_query($sql);

		$campi = array();
		$total = array(0, 0, 0, 0, 0);
		$result = array();

		while ($line = db_read($rlt)) {
			$campus = trim($line['pp_centro']);
			$modalidade = UpperCaseSql(trim($line['doc_edital']));
			$tot = $line['total'];
			if (!(in_array($campus, $campi))) {
				array_push($campi, $campus);
				array_push($result, $total);
				$pos = array_search($campus, $campi);
			} else {
				$pos = array_search($campus, $campi);
			}
			$xpos = 0;
			if ($modalidade == 'PIBIC') { $xpos = 1;
			}
			if ($modalidade == 'PIBITI') { $xpos = 2;
			}
			if ($modalidade == 'PIBICE') { $xpos = 3;
			}
			if ($modalidade == 'ICI') { $xpos = 4;
			}

			$result[$pos][$xpos] = $result[$pos][$xpos] + $tot;
		}

		$sx .= '<table class="tabela00" width="100%">';
		$sx .= '<TR><TH width="50%">Campus
						<TH width="12%">PIBIC
						<TH width="12%">PIBITI
						<TH width="12%">PIBIC_EM
						<TH width="12%">ICI
						<TH width="12%">Total';
		for ($r = 0; $r < count($campi); $r++) {
			$sx .= '<TR>';
			$sx .= '<TD class="tabela01">' . $campi[$r];
			$toti = 0;
			for ($y = 1; $y < count($result[$r]); $y++) {
				$toti = $toti + $result[$r][$y];
				$sx .= '<TD class="tabela01 lt3" align="center">';
				$sx .= $result[$r][$y];
				$total[$y] = $total[$y] + $result[$r][$y];
			}
			$total[5] = $total[5] + $toti;
			$sx .= '<TD class="tabela01 lt3" align="center">';
			$sx .= $toti;
		}
		$sx .= '<TR class="lt3"><TD align="right"><B>Totais</B>';
		$sx .= '<TD align="center"><B>' . $total[1] . '</B>';
		$sx .= '<TD align="center"><B>' . $total[2] . '</B>';
		$sx .= '<TD align="center"><B>' . $total[3] . '</B>';
		$sx .= '<TD align="center"><B>' . $total[4] . '</B>';
		$sx .= '<TD align="center"><B>' . $total[5] . '</B>';
		$sx .= '</table>';
		return ($sx);

	}

	function resumo_projetos_area($ano = '2013', $edital = '', $status = 'B') {
		/* pj_professor */
		$ano = $this -> ano;
		if (strlen($ano) == 0) { $ano = date("Y");
		}

		$cp = 'pj_area, a_descricao';
		$sql = "select count(*) as total, $cp from " . $this -> tabela . ' ';
		$sql .= "left join pibic_professor on pj_professor = pp_cracha ";
		$sql .= "left join centro on pp_escola = centro_codigo
					 left join ajax_areadoconhecimento on a_cnpq = pj_area
				where (pj_status = 'B' or pj_status ='C' or pj_status = 'D') and pj_ano = '$ano' ";
		$sql .= "group by $cp
						order by pj_area ";
		$rlt = db_query($sql);

		$campi = array();
		$total = array(0, 0, 0, 0);
		$result = array();
		$xarea = 0;
		$xtotal = 0;
		while ($line = db_read($rlt)) {
			$area = substr($line['a_cnpq'], 0, 1);
			$campus = trim($line['pj_area']) . ' ' . trim($line['a_descricao']);
			$modalidade = UpperCaseSql(trim($line['doc_edital']));
			$modalidade = 'PIBIC';
			$tot = $line['total'];
			if (!(in_array($campus, $campi))) {
				array_push($campi, $campus);
				array_push($result, $total);
				$pos = array_search($campus, $campi);
			} else {
				$pos = array_search($campus, $campi);
			}
			$xpos = 0;
			if ($modalidade == 'PIBIC') { $xpos = 1;
			}
			if ($modalidade == 'PIBITI') { $xpos = 2;
			}
			if ($modalidade == 'PIBICE') { $xpos = 3;
			}
			if ($modalidade == 'ICI') { $xpos = 1;
			}

			$result[$pos][$xpos] = $result[$pos][$xpos] + $tot;
		}
		$sx .= '<h1>Projetos do Professor</h1>';
		$sx .= '<table class="tabela00">';
		$sx .= '<TR><TH width="350">Área do conhecimento
						<TH width="80">PIBIC
						<TH width="80">PIBITI
						<TH width="80">PIBIC_EM
						<TH width="80">Total';
		for ($r = 0; $r < count($campi); $r++) {
			$sx .= '<TR>';
			$sx .= '<TD class="tabela01">' . $campi[$r];
			$toti = 0;
			for ($y = 1; $y < count($result[$r]); $y++) {
				$toti = $toti + $result[$r][$y];
				$sx .= '<TD class="tabela01 lt3" align="center">';
				$sx .= $result[$r][$y];
				$total[$y] = $total[$y] + $result[$r][$y];
			}
			$total[4] = $total[4] + $toti;
			$sx .= '<TD class="tabela01 lt3" align="center">';
			$sx .= $toti;
		}
		$sx .= '<TR class="lt3"><TD align="right"><B>Totais</B>';
		$sx .= '<TD align="center"><B>' . $total[1] . '</B>';
		$sx .= '<TD align="center"><B>' . $total[2] . '</B>';
		$sx .= '<TD align="center"><B>' . $total[3] . '</B>';
		$sx .= '<TD align="center"><B>' . $total[4] . '</B>';
		$sx .= '</table>';
		return ($sx);
	}

	function resumo_planos_area_conhecimento($ano = '2013', $edital = '', $status = 'B') {
		/* pj_professor */
		$ano = $this -> ano;
		if (strlen($ano) == 0) { $ano = date("Y");
		}

		$cp = 'substr(pj_area,1,1) as pj_area, doc_edital, a_descricao';
		$sql = "select count(*) as total, pj_area, doc_edital from (			
						select substr(pj_area,1,1) as pj_area, doc_edital from " . $this -> tabela . " 
						left join pibic_professor on pj_professor = pp_cracha 
						inner join pibic_submit_documento on pj_codigo = doc_protocolo_mae
					where (pj_ano = '" . date("Y") . "') 
						and (doc_status = 'B' or doc_status = 'C' or doc_status = 'D')
						and (pj_status = 'B' or pj_status = 'C' or pj_status = 'D')
						) as tabela ";
		$sql .= "group by pj_area, doc_edital
						order by pj_area, doc_edital ";
		$rlt = db_query($sql);
		$campi = array();
		$total = array(0, 0, 0, 0);
		$result = array();

		$areas = array('Ciências Exatas e da Terra', 'Ciências Biológicas ', 'Engenharias', 'Ciências da Saúde', 'Ciências Agrárias', 'Ciências Sociais Aplicadas ', 'Ciências Humanas', 'Lingüística', '==', 'Área estratégica');

		while ($line = db_read($rlt)) {
			$campus = trim($line['pj_area']) . ' ' . trim($line['a_descricao']);
			$modalidade = UpperCaseSql(trim($line['doc_edital']));
			$tot = $line['total'];
			if (!(in_array($campus, $campi))) {
				array_push($campi, $campus);
				array_push($result, $total);
				$pos = array_search($campus, $campi);
			} else {
				$pos = array_search($campus, $campi);
			}
			$xpos = 0;
			if ($modalidade == 'PIBIC') { $xpos = 1;
			}
			if ($modalidade == 'PIBITI') { $xpos = 2;
			}
			if ($modalidade == 'PIBICE') { $xpos = 3;
			}

			$result[$pos][$xpos] = $result[$pos][$xpos] + $tot;
		}
		$sx .= '<h1>Planos de Alunos</h1>';
		$sx .= '<table class="tabela00">';
		$sx .= '<TR><TH width="350">Área do conhecimento
						<TH width="80">PIBIC
						<TH width="80">PIBITI
						<TH width="80">PIBIC_EM
						<TH width="80">Total';
		for ($r = 0; $r < count($campi); $r++) {
			$sx .= '<TR>';
			$sx .= '<TD class="tabela01">' . $areas[$r];
			$toti = 0;
			for ($y = 1; $y < count($result[$r]); $y++) {
				$toti = $toti + $result[$r][$y];
				$sx .= '<TD class="tabela01 lt3" align="center">';
				$sx .= $result[$r][$y];
				$total[$y] = $total[$y] + $result[$r][$y];
			}
			$total[4] = $total[4] + $toti;
			$sx .= '<TD class="tabela01 lt3" align="center">';
			$sx .= $toti;
		}
		$sx .= '<TR class="lt3"><TD align="right"><B>Totais</B>';
		$sx .= '<TD align="center"><B>' . $total[1] . '</B>';
		$sx .= '<TD align="center"><B>' . $total[2] . '</B>';
		$sx .= '<TD align="center"><B>' . $total[3] . '</B>';
		$sx .= '<TD align="center"><B>' . $total[4] . '</B>';
		$sx .= '</table>';
		return ($sx);
	}

	function resumo_planos_area($ano = '2013', $edital = '', $status = 'B') {
		/* pj_professor */
		$ano = $this -> ano;
		if (strlen($ano) == 0) { $ano = date("Y");
		}

		$cp = 'pj_area, doc_edital, a_descricao';
		$sql = "select count(*) as total, $cp from " . $this -> tabela . ' ';
		$sql .= "left join pibic_professor on pj_professor = pp_cracha ";

		$sql .= "inner join pibic_submit_documento on pj_codigo = doc_protocolo_mae
						left join centro on pp_escola = centro_codigo
						left join ajax_areadoconhecimento on a_cnpq = pj_area
				where (pj_ano = '" . date("Y") . "') 
						and (doc_status = 'B' or doc_status = 'C' or doc_status = 'D')
						and (pj_status = 'B' or pj_status = 'C' or pj_status = 'D')					
					";
		$sql .= "group by $cp
						order by pj_area, doc_edital ";
		$rlt = db_query($sql);

		$campi = array();
		$total = array(0, 0, 0, 0);
		$result = array();

		while ($line = db_read($rlt)) {
			$campus = trim($line['pj_area']) . ' ' . trim($line['a_descricao']);
			$modalidade = UpperCaseSql(trim($line['doc_edital']));
			$tot = $line['total'];
			if (!(in_array($campus, $campi))) {
				array_push($campi, $campus);
				array_push($result, $total);
				$pos = array_search($campus, $campi);
			} else {
				$pos = array_search($campus, $campi);
			}
			$xpos = 0;
			if ($modalidade == 'PIBIC') { $xpos = 1;
			}
			if ($modalidade == 'PIBITI') { $xpos = 2;
			}
			if ($modalidade == 'PIBICE') { $xpos = 3;
			}

			$result[$pos][$xpos] = $result[$pos][$xpos] + $tot;
		}
		$sx .= '<h1>Planos de Alunos</h1>';
		$sx .= '<table class="tabela00">';
		$sx .= '<TR><TH width="350">Área do conhecimento
						<TH width="80">PIBIC
						<TH width="80">PIBITI
						<TH width="80">PIBIC_EM
						<TH width="80">Total';
		for ($r = 0; $r < count($campi); $r++) {
			$sx .= '<TR>';
			$sx .= '<TD class="tabela01">' . $campi[$r];
			$toti = 0;
			for ($y = 1; $y < count($result[$r]); $y++) {
				$toti = $toti + $result[$r][$y];
				$sx .= '<TD class="tabela01 lt3" align="center">';
				$sx .= $result[$r][$y];
				$total[$y] = $total[$y] + $result[$r][$y];
			}
			$total[4] = $total[4] + $toti;
			$sx .= '<TD class="tabela01 lt3" align="center">';
			$sx .= $toti;
		}
		$sx .= '<TR class="lt3"><TD align="right"><B>Totais</B>';
		$sx .= '<TD align="center"><B>' . $total[1] . '</B>';
		$sx .= '<TD align="center"><B>' . $total[2] . '</B>';
		$sx .= '<TD align="center"><B>' . $total[3] . '</B>';
		$sx .= '<TD align="center"><B>' . $total[4] . '</B>';
		$sx .= '</table>';
		return ($sx);
	}

	function resumo_planos_area_estrategica($ano = '2013', $edital = '', $status = 'B') {
		/* pj_professor */
		$ano = $this -> ano;
		if (strlen($ano) == 0) { $ano = date("Y");
		}

		$cp = 'pj_area_estra, doc_edital, a_descricao';
		$sql = "select count(*) as total, $cp from " . $this -> tabela . ' ';
		$sql .= "left join pibic_professor on pj_professor = pp_cracha ";

		$sql .= "inner join pibic_submit_documento on pj_codigo = doc_protocolo_mae
						left join centro on pp_escola = centro_codigo
						left join ajax_areadoconhecimento on a_cnpq = pj_area_estra
				where (doc_ano = '" . date("Y") . "') 
					and (doc_status = 'B' or doc_status = 'C' or doc_status = 'D' or doc_status = 'E') 
					and (pj_status = 'B' or pj_status = 'C' or pj_status = 'D' or pj_status = 'E') ";
		$sql .= "group by $cp
						order by pj_area_estra, doc_edital ";

		$rlt = db_query($sql);

		$campi = array();
		$total = array(0, 0, 0, 0);
		$result = array();

		while ($line = db_read($rlt)) {
			$campus = trim($line['pj_area_estra']) . ' ' . trim($line['a_descricao']);
			$modalidade = UpperCaseSql(trim($line['doc_edital']));
			$tot = $line['total'];
			if (!(in_array($campus, $campi))) {
				array_push($campi, $campus);
				array_push($result, $total);
				$pos = array_search($campus, $campi);
			} else {
				$pos = array_search($campus, $campi);
			}
			$xpos = 0;
			if ($modalidade == 'PIBIC') { $xpos = 1;
			}
			if ($modalidade == 'PIBITI') { $xpos = 2;
			}
			if ($modalidade == 'PIBICE') { $xpos = 3;
			}

			$result[$pos][$xpos] = $result[$pos][$xpos] + $tot;
		}

		$sx .= '<table class="tabela00">';
		$sx .= '<TR><TH width="350">Área do conhecimento
						<TH width="80">PIBIC
						<TH width="80">PIBITI
						<TH width="80">PIBIC_EM
						<TH width="80">Total';
		for ($r = 0; $r < count($campi); $r++) {
			$sx .= '<TR>';
			$sx .= '<TD class="tabela01">' . $campi[$r];
			$toti = 0;
			for ($y = 1; $y < count($result[$r]); $y++) {
				$toti = $toti + $result[$r][$y];
				$sx .= '<TD class="tabela01 lt3" align="center">';
				$sx .= $result[$r][$y];
				$total[$y] = $total[$y] + $result[$r][$y];
			}
			$total[4] = $total[4] + $toti;
			$sx .= '<TD class="tabela01 lt3" align="center">';
			$sx .= $toti;
		}
		$sx .= '<TR class="lt3"><TD align="right"><B>Totais</B>';
		$sx .= '<TD align="center"><B>' . $total[1] . '</B>';
		$sx .= '<TD align="center"><B>' . $total[2] . '</B>';
		$sx .= '<TD align="center"><B>' . $total[3] . '</B>';
		$sx .= '<TD align="center"><B>' . $total[4] . '</B>';
		$sx .= '</table>';
		return ($sx);
	}

	function resumo_mostra_painel($titulo, $pibic, $pibiti, $pibicem, $internacional = 0) {
		if (($pibic + $pibiti + $pibicem) > 0) {
			if (strlen(trim($titulo)) == 0) { $titulo = '--sem categorização--';
			}
			$sx .= '<TR><TD class="tabela01">' . $titulo;
			$sx .= '<TD class="tabela01" align="center">';
			$sx .= $pibic;
			$sx .= '<TD class="tabela01" align="center">';
			$sx .= $pibiti;
			$sx .= '<TD class="tabela01" align="center">';
			$sx .= $pibicem;
			$sx .= '<TD class="tabela01" align="center">';
			$sx .= $internacional;
			$sx .= '<TD class="tabela01" align="center">';
			$sx .= ($pibicem + $pibiti + $pibic + $internacional);
		}
		return ($sx);
	}

	/**
	 * ACOES
	 */
	function projetos_acoes() {
		global $dd, $acao;
		$sta = $this -> status;
		$action = array();
		$cmd = array();
		if ($sta == '@') {
			array_push($action, 'Aceitar para avaliação');
			array_push($action, 'Cancelar projeto');
			array_push($cmd, '001');
			array_push($cmd, '900');
		}
		if ($sta == 'B') {
			array_push($action, 'Indicar para avaliador');
			array_push($action, 'Marcar como Pendência TI');
			array_push($action, 'Marcar como Pendência Gestor');
			array_push($cmd, '001');
			array_push($cmd, '003');
			array_push($cmd, '004');
		}
		if ($sta == 'D') {
			array_push($action, 'Reindicar para avaliador');
			array_push($cmd, '001');
		}
		if ($sta == 'F') {
			array_push($action, 'Reindicar para avaliador');
			array_push($cmd, '001');
		}

		if ($sta == 'C') {
			array_push($action, 'Indicar para avaliador');
			array_push($cmd, '001');
		}

		if ($sta == 'T') {
			array_push($action, 'Devolver para Indicação');
			array_push($action, 'Cancelar protocolo');
			array_push($cmd, '005');
			array_push($cmd, '900');
		}

		if ($sta == 'U') {
			array_push($action, 'Devolver para Indicação');
			array_push($cmd, '005');
		}

		if ($sta == 'C') {
			array_push($action, 'Indicar para avaliador');
			array_push($action, 'Finalizar avaliação');
			array_push($cmd, '001', '002');
		}

		if (strlen($acao) > 0) {
			$ccc = '';
			for ($r = 0; $r < count($action); $r++) {
				if ($action[$r] == $acao) { $ccc = $cmd[$r];
				}
			}

			if ($ccc == '001') { $this -> acao_indicar_avaliadores();
			}
			if ($ccc == '002') { $this -> acao_finalizar_avaliacao();
			}
			if ($ccc == '003') { $this -> acao_pendecia_avaliacao_ti();
			}
			if ($ccc == '004') { $this -> acao_pendecia_avaliacao_gestor();
			}
			if ($ccc == '005') { $this -> acao_devolver_avaliacao();
			}
			if ($ccc == '900') { $this -> acao_cancelar_protocolo();
			}
		}
		if ((count($action) > 0) and (strlen($acao) == 0)) {
			$sx .= '<form action="' . page() . '" method="get"">';
			$sx .= '<input type="hidden" name="dd0" value="' . $dd[0] . '">';
			$sx .= '<input type="hidden" name="dd90" value="' . $dd[90] . '">';
			$sx .= '<table width="100%">';
			$sx .= '<TR>';
			for ($r = 0; $r < count($action); $r++) {
				$sx .= '<TD align="center">';
				$sx .= '<input type="submit" name="acao" value="' . $action[$r] . '">';
			}
			$sx .= '</table>';
			$sx .= '</form>';
		}
		return ($sx);
	}

	function status_tipos() {
		$st = array('@' => 'Em submissão (início)', 'A' => 'Submissão', 'B' => 'Enviado', 'X' => 'Cancelado', 'T' => 'Pendência para TI', 'U' => 'Pendência para o Gestor', '!' => 'Devolver ao professor para correções');
		$cr = array('@' => 'orange', 'A' => 'orange', 'B' => 'Green', 'X' => 'Red', 'U' => 'Red', 'T' => 'Red', '@' => 'orange');
		return ( array($st, $cr));
	}

	/**
	 * Acoes
	 */

	function enviar_email_correcao() {
		global $dd, $http;
		$line = $this -> line;
		$email = $line['pp_email'];
		$email_1 = $line['pp_email_1'];
		$nome = $line['pp_nome'];
		$protocolo = $this -> protocolo;

		$ic = new ic;
		$nw = $ic -> ic('ic_proj_dev_prof');

		$titulo = $nw['nw_titulo'] . ' - ' . $this -> line['pp_nome'];
		$texto = mst($nw['nw_descricao']);

		$texto = troca($texto, '$MOTIVO', '<B>' . $dd[2] . '</B>');
		$texto = troca($texto, '$NOME', '<B>' . $nome . '</B>');
		$texto = troca($texto, '$PROTOCOLO', '<B>' . $protocolo . '</B>');

		$texto = '<IMG SRC="' . $http . 'img/email_ic_header.png"><BR><BR>' . mst($texto) . '<BR><BR><BR><img src="' . $http . 'img/email_ic_foot.png">';

		if (strlen($email) > 0) { enviaremail($email, '', $titulo, $texto);
		}
		if (strlen($email_1) > 0) { enviaremail($email_1, '', $titulo, $texto);
		}
		enviaremail('renefgj@gmail.com', '', $titulo . ' [copia]', $texto . '<BR><BR>Enviao para ' . $email . ' ' . $email_1);
		enviaremail('pibicpr@pucpr.br', '', $titulo . ' [copia]', $texto . '<BR><BR>Enviao para ' . $email . ' ' . $email_1);
		return (1);
	}

	function acao_01() {
		global $dd, $acao, $ss;

		$sta = $this -> status_tipos();
		$sts = $this -> status;
		$sx = '';

		//if (($sts != '@') and ($sts != '!') and ($sts != 'A'))
		{
			if ((strlen($acao) > 0) and (strlen($dd[3]) > 0)) {
				$proto = $this -> protocolo;
				$ac = $dd[3];
				$std = $dd[3];
				if ($std == '@') { $std = 'Devolvido ao professor para correções ';
					$ac = 1;
				}
				if ($std == 'A') { $std = 'Aceito submissão manualmente ';
					$ac = 2;
				}
				if ($std == 'C') { $std = 'Aceito para avaliação ';
					$ac = 6;
				}
				if ($std == 'T') { $std = 'Análise da TI ';
					$ac = 7;
				}
				if ($std == 'P') { $std = 'Análise da Coordenação ';
					$ac = 8;
				}
				if ($std == 'E') { $std = 'Avaliação Finalizadas ';
					$ac = 9;
				}
				if ($std == 'X') { $std = 'Cancelado ';
					$ac = 10;
				}

				$hist = 'AÇÃO: ' . $std . ' por ' . $ss -> user_login;

				$aluno1 = '';
				$aluno2 = '';
				$motivo = '';

				$his = new pibic_historico;
				//$his->inserir_historico($proto,$ac,$hist,$aluno1,$aluno2,$motivo,$dd[2]);

				if (strlen($dd[3]) > 0) {
					/* enviar e-mail professor */
					if ($dd[3] == '@') {
						$this -> enviar_email_correcao();
					}

					$sql = "update pibic_projetos 
									set pj_status = '" . $dd[3] . "',
									pj_coment = '" . $dd[2] . "' 
									where pj_codigo = '$proto' ";
					$rlt = db_query($sql);

					$sql = "update pibic_submit_documento 
										set doc_status = '" . $dd[3] . "' 
										where (doc_protocolo_mae = '$proto') 
										and (doc_status <> 'X') ";
					$rlt = db_query($sql);

				}
				redirecina(page() . '?dd0=' . $dd[0] . '&dd90=' . $dd[90]);
			}

			$sx .= '<table width="940" class="tabela00" align="center"><TR><TD>';
			$sx .= '<fieldset><legend>Ações sobre o protocolo</legend>';
			$sx .= '<form method="post">';
			$sx .= '<h3>Validação do projeto e indicação de avaliação</h3>';
			$sx .= '<input type="hidden" name="dd0" value="' . $dd[0] . '">';
			$sx .= '<input type="hidden" name="dd90" value="' . $dd[90] . '">';
			$sx .= '<font class="lt0">Comentários</font><BR>';
			$sx .= '<textarea name="dd2" rows=80 style="width: 100%; height: 70px;">' . $dd[2] . '</textarea>';
			$sx .= '<BR><BR><font class="lt0">Informe o tipo de ação para o projeto:</font><BR>';

			/* Projeto como coordenador */

			if ($sts == '@') {
				$sx .= '<input TYPE="RADIO" name="dd3" value="A">Aceitar Submissão manualmente<BR>';
				$sx .= '<input TYPE="RADIO" name="dd3" value="@">Devolver para o professor realizar correções<BR>';
			} else {
				$sx .= '<input TYPE="RADIO" name="dd3" value="@">Devolver para o professor realizar correções<BR>';
				$sx .= '<input TYPE="RADIO" name="dd3" value="C">Acatar projeto para avaliação<BR>';
				$sx .= '<input TYPE="RADIO" name="dd3" value="T">Encaminhar para TI<BR>';
				$sx .= '<input TYPE="RADIO" name="dd3" value="P">Encaminhar para análise da coordenação<BR>';
				$sx .= '<input TYPE="RADIO" name="dd3" value="B">Reencaminhar para validação<BR>';
				$sx .= '<input TYPE="RADIO" name="dd3" value="E">Finalizar avaliação<BR>';
				$sx .= '<input TYPE="RADIO" name="dd3" value="X">Cancelar<BR>';
			}

			$sx .= '<BR><BR><input TYPE="submit" name="acao" value="encaminhar >>>" class="botao-geral">';
			$sx .= '</form>';
			$sx .= '</legend>';
			$sx .= '</table>';
		}
		return ($sx);
	}

	/**
	 * Projeto Geral
	 */
	function mostra_projeto() {
		$sql = "select geral.a_descricao as a_descricao_geral,
								estra.a_descricao as a_descricao_estra,
						* from " . $this -> tabela . " ";
		$sql .= " left join docentes on pj_professor = pp_cracha ";
		$sql .= " left join ajax_areadoconhecimento as geral on pj_area = geral.a_cnpq ";
		$sql .= " left join ajax_areadoconhecimento as estra on pj_area_estra = estra.a_cnpq ";
		$sql .= " where pj_codigo = '" . $this -> protocolo . "' ";
		$rlt = db_query($sql);
		$line = db_read($rlt);

		$sn = array('NÃO', 'SIM');
		$ap1 = $sn[$line['pj_ext_sn']];
		$ap2 = $sn[$line['pj_gr2_sn']];
		$lattes = trim($line['pp_lattes']);
		$lattes = troca($lattes, ".jsp", ".do");
		$professor = trim($line['pp_cracha']);

		if (strlen($lattes) > 0) {
			$lattes = '&nbsp;&nbsp;<A HREF="' . $lattes . '" target="_NEW">lattes</a>';
		}

		/** Banco de Projetos */
		$bp = $line['pj_bp'];
		if ($bp == 'S') {
			$sa .= '<B><Font color="green">SIM</font></B> Integrar com o banco de projetos';
		} else {
			$sa .= '<B><Font color="red">NÃO</font></B> Integrar com o banco de projetos';
		}

		$sx .= '<table width="100%" class="lt0">
						<TR><TD colspan=2>Protocolo:<B><font class="lt3"><BR>' . $line['pj_codigo'] . '
				
						<TR><TD colspan=2>Nome do professor
						<TR><TD class="lt3" colspan=2><B>' . $line['pp_nome'] . '</B> (' . $line['pp_cracha'] . ') ' . $lattes . '
						<TR><TD colspan=2>Projeto do Professor
						<TR><TD colspan=2 class="lt3"><B>' . $line['pj_titulo'] . '

						<TR><TD>Área do conhecimento
							<TD>Área estratégica do conhecimento
						<TR><TD class="lt3"><B>' . trim($line['pj_area']) . '-' . trim($line['a_descricao_geral']) . '
						    <TD class="lt3"><B>' . $line['pj_area_estra'] . '-' . trim($line['a_descricao_estra']) . '
						
						<TR><TD>Aprovado Externamente: <B>' . $ap1 . ' 
						    <TD>Aprovado por Empresa: <B>' . $ap2 . '

						<TR><TD>Banco de Projetos PUCPR
						<TR><TD class="lt2" colspan=2>' . $sa;
		$sx .= '<TR><TD colspan=2>LISTA DE ARQUIVOS';
		$sx .= $this -> projeto_file_lista($line['pj_codigo']);

		$sx .= '</table>';

		return ($sx);
	}

	function planos_mostra_mini($line) {
		$tipo = trim($line['doc_edital']);
		$img = http . 'pibic/img/icone_plano_aluno.png';

		if ($tipo == '') { $tipo = 'PIBIC';
		}

		if ($tipo == 'PIBITI') { $img = http . 'pibic/img/icone_plano_aluno_pibiti.png';
		}
		if ($tipo == 'PIBICE') { $img = http . 'pibic/img/icone_plano_aluno_jr.png';
		}

		$sx .= '<TR valign="top">';
		$sx .= '<TD rowspan=4><img src="' . $img . '" width="50">';
		$sx .= '<TD class="lt0">PROTOCOLO:<BR><b>' . $line['doc_protocolo'];
		$sx .= '/';
		$sx .= '<A HREF="' . http . 'pibicpr/pibic_projetos_detalhes.php?dd0=' . $line['id_pj'] . '&dd90=' . checkpost($line['id_pj']) . '">';
		$sx .= $line['doc_protocolo_mae'];
		$sx .= '</A>';
		$sx .= '<TD class="lt0">MODALIDADE:<BR><font class="lt2"><b>' . $tipo;

		$sx .= '<TR>';
		$sx .= '<TD class="lt0" colspan=2>TÍTULO DO PLANO DO ALUNO:<BR><font class="lt2"><b>' . $line['doc_1_titulo'];
		$sx .= '<TR>';
		$sx .= '<TD class="lt0">PROFESSOR:<BR><font class="lt2"><b>' . $line['pp_nome'] . '(' . trim($line['pp_cracha']) . ')&nbsp;';
		$sx .= '<TD class="lt0">ESTUDANTE:<BR><font class="lt2"><b>' . $line['pa_nome'] . '(' . trim($line['pa_cracha']) . ')&nbsp;';

		$sx .= '<TR><TD colspan=5>&nbsp;';
		return ($sx);
	}

	function projeto_file_lista($protocolo = '') {
		global $ged, $user_nivel;
		$ged -> tabela = 'pibic_submit_ged';
		$ged -> protocol = $protocolo;
		$ged -> file_type = '';
		if ($user_nivel >= 9) {
			$sx = $ged -> file_list();
			$sx .= '<a href="javascript:newxy2(\'../pibic/ged_upload_pibic.php?dd1=' . $protocolo . '\',400,300)">upload</A>';
		} else {
			$sx = $ged -> filelist();
		}
		return ($sx);
	}

	function mostra_plano($proto = '') {
		$sql = "select * from pibic_submit_documento
						left join pibic_aluno on pa_cracha = doc_aluno 
						where doc_protocolo_mae = '" . $this -> protocolo . "' 
						and doc_status <> 'X' 
						order by doc_edital, doc_protocolo";
		if (strlen($proto) > 0) {
			$sql = "select * from pibic_submit_documento
						left join pibic_aluno on pa_cracha = doc_aluno 
						where doc_protocolo_mae = '" . $proto . "'
						or  doc_protocolo = '" . $proto . "'
						and doc_status <> 'X' ";
		}
		$rlt = db_query($sql);

		while ($line = db_read($rlt)) {
			$sx .= $this -> mostra_plano_line($line);
		}

		return ($sx);
	}

	function mostra_plano_line($line) {
		$sx .= '<table width="100%" class="tabela00" border=0>';
		$edital = trim($line['doc_edital']);
		$icv = round($line['doc_icv']);
		if ($icv == 1) { $icv = 'SIM';
		} else { $icv = 'NÃO';
		}
		if ($edital == 'PIBICE') { $edital = "PIBIC_EM";
		}

		$sx .= '<TR valign="top">
									<TD rowspan=5 width="50"><IMG SRC="' . $this -> imagem_edital($edital) . '" height="50">
									<TD class="lt0">título do plano de trabalho do aluno';

		$sx .= '<tr><td valign="top" class="tabela01"><H4>' . $line['doc_1_titulo'] . '</H4>';
		$sx .= '<TD class="tabela01" valign="top" ><font class="lt0">Protocolo</font><br><b>' . $line['doc_protocolo'] . '</b>';

		$aluno = trim($line['doc_aluno']);
		if (strlen($aluno) > 0) {
			$sx .= '<TR><TD class="lt0">Aluno';
			$sx .= '<TD class="lt0">Modalidade';
			$sx .= '<TR><TD class="tabela01" colspan=1>' . $line['doc_aluno'] . ' ' . $line['pa_nome'];
			$sx .= '<TD class="tabela01">' . $edital . '/' . $line['doc_ano'];
		}
		$sx .= '<TR><TD>Concorrer somente a bolsas PUCPR e ICV (pois o aluno tem vinculo empregatício): ' . $icv;

		$sx .= '<TR><TD colspan=3><HR>';
		$sx .= '</table>';
		return ($sx);
	}

	function submit_projeto_valida($protocolo) {
		$sql = "select * from " . $this -> tabela . " where pj_codigo = '" . $protocolo . "' ";
		$rlt = db_query($sql);
		if ($line = db_read($rlt)) {
			$ok = 1;
			$this -> erros = '';
			if (strlen(trim($line['pj_titulo'])) < 5) { $ok = -1;
				$this -> erros .= 'Título inválido<BR>';
			}
			if (strlen(trim($line['pj_area'])) < 8) { $ok = -2;
				$this -> erros .= 'Área do conhecimento não definida<BR>';
			}
			if (strlen(trim($line['pj_ext_sn'])) == 0) { $ok = -2;
				$this -> erros .= 'Existência de financiamento externo não informado<BR>';
			}
			if (strlen(trim($line['pj_gr2_sn'])) == 0) { $ok = -2;
				$this -> erros .= 'Existência de financiamento por empresas não informado<BR>';
			}
			/** */
			if ($line['pr_ext_sn'] == 'S') {
				if (strlen(trim($line['pj_ext_local'])) == 0) { $ok = -2;
					$this -> erros .= 'Financiador externo não informado<BR>';
				}
				if (strlen(trim($line['pj_ext_edital'])) == 0) { $ok = -2;
					$this -> erros .= 'Edital do financiador externo não informado<BR>';
				}
				if (strlen(trim($line['pj_ext_valor'])) == 0) { $ok = -2;
					$this -> erros .= 'Valor do financiador externo não informado<BR>';
				}
			}

			$sql = "select count(*) as total from pibic_submit_ged 
								where doc_dd0 = '" . $protocolo . "' 
								and doc_tipo = 'PROJ'
								and doc_ativo = 1
								";
			$rrlt = db_query($sql);
			$rline = db_read($rrlt);
			$total = $rline['total'];
			if ($total <= 0) { $ok = -3;
				$this -> erros .= 'Arquivo com o projeto do professor não postado.';
			}
		} else {
			$ok = 0;
		}
		return ($ok);
	}

	function valida_arquivo_plano($tipo = '', $protocolo = '') {
		$sql = "select count(*) as total from pibic_ged_documento 
					where doc_dd0 = '" . $protocolo . "' 
					and doc_tipo = 'PLANO'
					and doc_ativo = 1
					";
		$rrlt = db_query($sql);
		$rline = db_read($rrlt);
		$total = $rline['total'];
		if ($total > 0) {
			return (1);
		} else {
			return (0);
		}
	}

	function aluno_em_outros_planos($aluno, $protocolo = '') {
		$ano = $this -> ano;
		if (strlen($ano) == 0) { $ano = date("Y");
		}

		$sql = "select * from pibic_submit_documento where  
				 		doc_aluno = '$aluno' and doc_ano = '$ano' 
				 		and doc_protocolo <> '" . $protocolo . "' 
				 		and doc_status <> 'X'
				 		";
		$rlt = db_query($sql);
		$id = 0;
		while ($line = db_read($rlt)) {
			$ppp = $line['doc_protocolo'];
			// ALUNO JÁ INCLUíDO EM OUTROS PROTOCOLOSecho '<HR>';
			$id++;
			$status = $line['doc_status'];
		}
		if ($aluno == '00000000') { $id = 0;
		}
		return ($id);
	}

	/**
	 * Projeto do professor
	 */

	function project_new_form() {
		global $dd, $ss;
		$professor = $ss -> user_cracha;
		$sx = '
			<form action="' . page() . '">
			<input type="hidden" name="dd1" value="' . $dd[1] . '">
			<fieldset><legend>Projeto do professor</legend>
			<table width="100%">
			';

		/* RN: Salva novo titulo se não existe */
		if ((strlen($dd[0]) == 0) and (strlen($dd[2]) > 0)) {
			if ($this -> verifica_se_ja_cadastrado($dd[2]) == 0) {
				$this -> salva_novo_titulo($dd[2], $professor);
				$_SESSION['protocolo'] = $this -> protocolo;
				redirecina('submit_pos_1.php');
			} else {
				$sx .= '<font color="red">Já existe um projeto com este título</font><BR>';
				$sx .= $this -> form_00();
			}

		} else {
			$sx .= $this -> form_00();
		}
		$sx .= '
			</table>
			</fieldset>
			</form>
			';
		return ($sx);
	}

	function form_00() {
		global $dd;
		$sx .= '
				<TR><TD rowspan=20 width="50"><img src="' . http . '/pibic/img/icone_projeto_professor.png" width="50">
				<TR><TD class="lt0">Informe o título do projeto do professor
				<TR><TD><textarea name="dd2" style="width: 100%; height: 80px;">' . $dd[2] . '</textarea>
				<TR><TD>' . $this -> erro . '
				<TR><TD><input type="submit" class="botao-geral" value="gravar >>>">
			';
		return ($sx);
	}

	function form_01() {
		global $dd;
		$sx .= '
				<TR valign="top"><TD rowspan=20 width="50"><img src="' . http . '/pibic/img/icone_projeto_professor.png" width="50">
				<TR><TD class="lt0">Protocolo: ' . $this -> protocolo . '
				<TR><TD class="lt0">Informe o título do projeto do professor
				<TR><TD><h2>' . $dd[2] . '</h2></textarea>
			';
		return ($sx);
	}

	function form_02a() {

	}

	function form_02b() {
		global $dd;
		$sx .= '<TR><TD>Área do conhecimento';
		$sx .= '<TR>' . sget("dd20", '$Q descricao:a_cnpq:select a_cnpq || \' \' || a_descricao as descricao,* from ajax_areadoconhecimento where a_submit=\'1\' and not a_cnpq like \'9%\' order by a_cnpq', 'Área', 1, 1);
		return ($sx);
	}

	function form_02c() {
		global $dd;
		$sx .= '<TR><TD>Área estratégica';
		$sx .= '<TR>' . sget("dd21", '$Q descricao:a_cnpq:select a_cnpq || \' \' || a_descricao as descricao,* from ajax_areadoconhecimento where a_cnpq like \'9%\' and a_submit=\'1\' order by a_cnpq', 'Área', 1, 1);
		return ($sx);
	}

	function form_03a() {
		global $dd, $acao;
		if ((strlen($acao) > 0) and (strlen(trim($dd[25])) == 0)) { $cor = '<img src="' . http . '/img/icone_alert.jpg" width="30"><font color="red" >';
		}
		$sx .= '<TR><TD>' . $cor . 'Projeto aprovado externamente por uma agência de fomento?  (obrigatório, anexar o documento comprobatório se pertinente)';
		$sx .= '<TR>' . sget("dd25", '$R S:SIM&N:NÃO', '', 1, 1);
		return ($sx);

	}

	function form_03b() {
		global $dd, $acao;
		if ((strlen($acao) > 0) and (strlen(trim($dd[35])) == 0)) { $cor = '<img src="' . http . '/img/icone_alert.jpg" width="30"><font color="red" >';
		}
		$sx .= '<TR><TD>' . $cor . 'Grupo 2 - Financiamento por empresa?  (obrigatório, anexar o documento comprobatório se pertinente)';
		$sx .= '<TR>' . sget("dd35", '$R S:SIM&N:NÃO', '', 1, 1);
		return ($sx);

	}

	function form_04a() {
		global $dd;
		$sx .= '<TR><TD>Comitê de Ética em Pesquisa';
		$sx .= sget("dd30", '$O N:--Não aplicável--&A:Em submissão&B:em avaliação&C:aprovado', '', 1, 1);
		$sx .= sget("dd31", '$S10', False);
		return ($sx);
	}

	function form_04b() {
		global $dd;
		$sx .= '<TR><TD>Comitê de Ética no Uso de Animais';
		$sx .= sget("dd32", '$O N:--Não aplicável--&A:Em submissão&B:em avaliação&C:aprovado', '', 1, 1);
		$sx .= sget("dd33", '$S10', '', 1, 1);
		return ($sx);
	}

	function valida_post() {
		global $dd;
		$protocolo = $_SESSION['protocolo'];

		$valid = 1;
		$err = array();

		if (strlen(trim($dd[20])) == 0) { $valid = 0;
			array_push($err, 'Área do conhecimento não informada');
		}

		if (strlen($dd[30]) == 0) { $valid = 0;
			array_push($err, 'Status do CEP não informado.');
		}
		if (strlen($dd[32]) == 0) { $valid = 0;
			array_push($err, 'Status do CEUA não informado.');
		}

		if (strlen($dd[25]) == 0) { $valid = 0;
			array_push($err, 'Aprovação por orgão de fomento não informado.');
		}
		if (strlen($dd[35]) == 0) { $valid = 0;
			array_push($err, 'Aprovação por empresa não informado.');
		}

		/* arquivo */
		if ($this -> valida_arquivo('PROJ', $protocolo) == 0) { $valid = 0;
			array_push($err, 'Projeto ou Plano não foi anexado.');
		}

		$this -> erro = $err;
		return ($valid);
	}

	function verifica_se_ja_cadastrado($titulo) {

	}

	function salva_novo_titulo($titulo, $professor) {
		global $dd, $professor;
		if ($this -> exist_titulo($titulo, $professor) == 1) {
			$this -> erro = 'Já existe um projeto com este título';
			return (0);
		} else {
			$this -> grava_titulo($dd[2], $professor);
		}
		return (0);
	}

	function exist_titulo($titulo, $professor) {
		global $ss;
		$professor = $ss -> user_cracha;

		$ano = $this -> ano;
		if (strlen($ano) == 0) { $ano = date("Y");
		}

		$sql = "select * from " . $this -> tabela . " 
					where (pj_titulo = '" . $titulo . "' or pj_search = '" . UpperCaseSql($titulo) . "') 
					and pj_professor = '$professor'
					and pj_ano = '$ano' and pj_status <> 'X'
					";
		$rlt = db_query($sql);
		if ($line = db_read($rlt)) {
			$this -> protocolo = trim($line['pj_codigo']);
			return (1);
		} else {
			return (0);
		}
		return (0);
	}

	function grava_titulo($titulo, $professor) {
		global $ss;
		$professor = $ss -> user_cracha;
		/* RN: Insere projeto se não existe */
		$data = date("Ymd");
		$proto = '';
		$ano = $this -> ano;
		if (strlen($ano) == 0) { $ano = date("Y");
		}

		$sql = "insert into " . $this -> tabela . "
					(pj_titulo, pj_codigo, pj_professor,pj_update, pj_data, pj_status, pj_ano) values
					('" . $titulo . "','$proto','$professor',$data,$data,'!','$ano')";
		$rlt = db_query($sql);

		/*** Atualiza códigos */
		$sql = "select * from " . $this -> tabela . " 
					where pj_codigo = ''
					and pj_professor = '$professor' 
					and pj_status = '!' ";
		$xrlt = db_query($sql);
		if ($xline = db_read($xrlt)) {
			$proto = '1' . strzero($xline['id_pj'], 6);
			$sql = "update " . $this -> tabela . " 
					set pj_codigo = '" . $proto . "'
					where id_pj = " . $xline['id_pj'];
			$yrlt = db_query($sql);
			$_SESSION['protocolo'] = $proto;
			$this -> protocolo = $proto;
		}
		return ($proto);

	}

	function project_professor_dados() {
		global $dd, $acao, $ged;
		$proto = $_SESSION['protocolo'];
		$this -> le($proto);
		$dd[0] = $this -> id;
		$dd[2] = $this -> titulo;

		/* */
		if (strlen($acao) == 0) {
			$dd[20] = trim($this -> line['pj_area']);
			$dd[21] = trim($this -> line['pj_area_estra']);
			$dd[30] = trim($this -> line['pj_cep_status']);
			$dd[31] = trim($this -> line['pj_cep']);
			$dd[32] = trim($this -> line['pj_ceua_status']);
			$dd[33] = trim($this -> line['pj_ceua']);
			/* */
			$dd[25] = trim($this -> line['pj_ext_sn']);

			$dd[35] = trim($this -> line['pj_gr2_sn']);
		} else {
			$sql = "update " . $this -> tabela . " 
							set pj_area = '" . trim($dd[20]) . "', 
							pj_area_estra = '" . trim($dd[21]) . "',
							pj_update = " . date("Ymd") . ",
							pj_cep_status = '" . $dd[30] . "',
							pj_cep = '" . trim($dd[31]) . "',
							pj_ceua_status = '" . $dd[32] . "',
							pj_ceua = '" . trim($dd[33]) . "',
							
							pj_ext_sn = '" . $dd[25] . "',
							pj_gr2_sn = '" . $dd[35] . "'
							where 
							pj_codigo = '" . $proto . "' 					
					";

			if (strlen($proto) > 0) {
				$rlt = db_query($sql);
			}
			$valida = $this -> valida_post();
			if ($valida == 1) {
				redirecina('submit_project.php');
			}
		}

		/* formulario */
		$sx = '
			<form action="' . page() . '">
			<input type="hidden" name="dd1" value="' . $dd[1] . '">
			<input type="hidden" name="dd89" value="' . $proto . '">
			<fieldset><legend>Projeto do professor</legend>
			<table width="100%">
			';

		$sx .= '<TR><TD colspan=2>' . $this -> mostra_erros_post();

		$sx .= $this -> form_01();

		$sx .= '<TR><TD>';
		$sx .= '<fieldset><legend>Área do conhecimento</legend>';
		$sx .= '<table width="100%">';
		$sx .= $this -> form_02a();
		/* banco de projetos */
		$sx .= $this -> form_02b();
		/* area do conhecimento */
		$sx .= $this -> form_02c();
		/* area estratégica */
		$sx .= '</table>';
		$sx .= '</fieldset>';

		$sx .= '<TR><TD>';
		$sx .= '<fieldset><legend>Aprovação & Financiamento Externo</legend>';
		$sx .= '<table width="100%">';
		$sx .= $this -> form_03a();
		/* aprovado externamente */
		$sx .= $this -> form_03b();
		/* grupo 2 */
		$sx .= '</table>';
		$sx .= '</fieldset>';

		$sx .= '<TR><TD>';
		$sx .= '<fieldset><legend>Comitês de Ética</legend>';
		$sx .= '<table width="100%">';
		$sx .= '<TR align="left"><TH>Comitê<TH>Status<TH>Nº Parecer';
		$sx .= $this -> form_04a();
		/* Comitê de ètica em pesquisa */
		$sx .= $this -> form_04b();
		/* Comitê de ética no uso de animais */
		$sx .= '</table>';
		$sx .= '</fieldset>';

		$sx .= '<TR><TD>';
		$sx .= '<fieldset><legend>Arquivos do professor</legend>';

		/** Arquivos */
		$sx .= '<fieldset><legend>Arquivos submetidos</legend>';
		$sx .= '<iframe src="submit_pibic_arquivos.php?dd1=' . $proto . '" 
					width=100%
					height=150 style="border: 0px solid #FFFFFF;"></iframe>';
		$sx .= '</fieldset>';

		$sx .= '<TR><TD>';
		$sx .= '<input type="submit" value="salvar e continuar >>>" name="acao" class="botao-geral">';
		$sx .= '</form>';

		$sx .= '</table>';

		return ($sx);
	}

	function mostra_erros_post() {
		global $http;
		$erro = '';
		$err = $this -> erro;
		for ($r = 0; $r < count($err); $r++) {
			if (strlen($err[$r]) > 0) { $erro .= '<BR>';
			}
			$erro .= '<font color="red">' . $err[$r] . '</font>';
		}
		if (strlen($erro) > 0) {
			$erro = '<table width="100%" class="tabela00">
								<TR><TD width="50">
								<img src="' . $http . 'img/icone_alert.png">
								<TD class="lt2">' . $erro . '
							</table>
					';
		}
		return ($erro);
	}

	function valida_arquivo($tipo = '', $protocolo = '') {
		$sql = "select count(*) as total from pibic_submit_ged 
					where doc_dd0 = '" . $protocolo . "' 
					and doc_tipo = '$tipo'
					and doc_ativo = 1
					";

		$rrlt = db_query($sql);
		$rline = db_read($rrlt);
		$total = $rline['total'];
		if ($total > 0) {
			return (1);
		} else {
			return (0);
		}
	}

	/**
	 * JUNIOR
	 */
	function submit_plano_jr() {
		global $dd, $acao, $user;
		/** Grava **/
		$proto = $_SESSION["proto_aluno"];
		$data = date("Ymd");
		$hora = date("H:i");
		$proto_mae = $this -> protocolo;
		$professor = $user -> cracha;
		$tipo = 'PLANO';
		$ano = date("Y");
		$edital = 'PIBICE';
		$aluno = $dd[8];
		$titulo = $dd[2];
		$ok = 0;
		$id = $_SESSION['proto_aluno'];
		if (strlen($id) == 0) { redirecina('submit_project.php');
		}
		if (strlen($acao) == 0) {

			$sql = "select * from pibic_submit_documento
								where doc_protocolo = '$id'
									and doc_autor_principal = '$professor' 
									and doc_status <> 'X' 
									";
			$rlt = db_query($sql);
			$line = db_read($rlt);
			$proto = $line['doc_protocolo'];
			$_SESSION["proto_aluno"] = $proto;
			$dd[8] = trim($line['doc_aluno']);
			$aluno = $dd[8];
			$dd[2] = trim($line['doc_1_titulo']);
			$dd[10] = trim($line['doc_edital']);
			$proto_mae = $line['doc_protocolo_mae'];
		}

		if ((strlen($acao) > 0) and ($proto != '')) {
			$valida = $this -> submit_plano_jr_valida($proto);
			$sta = '@';
			$ok = 0;
			$aluno = substr($aluno, 0, 8);
			if ($valida > 0) { $sta = 'A';
				$ok = 1;
			}
			$sql = "update pibic_submit_documento set 
							doc_status = '$sta',
							doc_1_titulo = '$titulo',
							doc_aluno = '$aluno',
							doc_dt_atualizado = '$data',
							doc_tipo = '$tipo',
							doc_edital = '$edital'							
							where doc_protocolo = '$proto'
							and doc_autor_principal = '$professor'
						";
			$rlt = db_query($sql);
		}
		$proto = $_SESSION['proto_aluno'];
		$_SESSION["proto_aluno"] = $proto;

		if ($dd[10] == 'PIBICE') { $sel10a = 'selected';
		}
		if ($dd[9] == '1') { $chk9 = 'checked';
		}

		$sx .= '<form method="post">';
		$sx .= '<input type="hidden" name="dd1" value="' . $dd[1] . '">
						<input type="hidden" name="dd0" value="' . $dd[0] . '">
						';
		$sx .= '<fieldset><legend>Título do plano do aluno do PIBIC_EM (PIBICjr)</legend>
						<table width="100%">
						<TR class="lt0"><TD align="right">PROTOCOLO:<B>' . $proto . '</B>/' . $proto_mae . '
						<tr><TD class="lt0">' . $msg1 . 'TÍTULO DO PLANO DO ALUNO ' . $obr . '
						<tr><td><textarea name="dd2" cols="80" rows="3">' . $dd[2] . '</textarea>
				
						<tr><TD class="lt0">' . $msg1 . 'Modalidade ' . $obr . '
						<tr><td>
							<select name="dd10">
							<option value="PIBICE" ' . $sel10a . '>PIBIC_EM (Pibic Jr)</option>
							</select>
						</table>
						';

		$sx .= '</fieldset>';

		/** Arquivos */
		$sx .= '<fieldset><legend>Arquivos submetidos</legend>';
		$sx .= '<iframe src="submit_pibic_plano_arquivos.php" 
						width=100%
						height=150 style="border: 0px solid #FFFFFF;"></iframe>';
		$sx .= '</fieldset>';

		/*** SALVAR **/

		$sx .= '<BR><BR><center>
					<input type="submit" name="acao" value="Gravar e avançar >>>" style="width: 300px; height: 50px;">
					';

		$sx .= '</form>';

		if (($ok == 1) and (strlen($acao) > 0)) {
			redirecina('submit_project.php');
		}
		$sa = $this -> mostra_erros();
		return ($sa . $sx);
	}

	function submit_plano_jr_new() {
		global $dd, $acao, $user;
		/** Grava **/
		$data = date("Ymd");
		$hora = date("H:i");
		$proto_mae = $this -> protocolo;
		$professor = $user -> cracha;
		$tipo = 'PLANO';
		$ano = $this -> ano;
		if (strlen($ano) == 0) { $ano = date("Y");
		}

		$edital = 'PIBICE';
		$aluno = $dd[8];
		$titulo = $dd[2];
		$ok = 0;
		if ($dd[0] == 'NEW') {
			$proto_mae = $this -> protocolo;

			$sql = "select * from pibic_submit_documento
								where doc_protocolo_mae = '$proto_mae'
								and doc_status = '@' and doc_edital = 'PIBICE'";
			$rlt = db_query($sql);

			if (!($line = db_read($rlt))) {
				$sql = "insert into pibic_submit_documento
								(doc_1_titulo, doc_1_idioma, doc_protocolo,
								doc_protocolo_mae, doc_aluno, doc_data, 
								doc_hora, doc_dt_atualizado, doc_autor_principal, 
								doc_status, doc_tipo, doc_journal_id, 
								doc_update, doc_icv, doc_ano, 
								doc_edital, doc_nota, doc_avaliacoes)
								values
								('sem título','pt_BR','',
								'$proto_mae','$aluno','$data',
								'$hora','$data','$professor',
								'@','$tipo',20,
								$data,'$icv','$ano',
								'$edital',0,0)";
				$rlt = db_query($sql);
			}

			/*** Atualiza códigos */
			$sql = "select * from pibic_submit_documento where doc_protocolo = '' and doc_status = '@'; ";
			$xrlt = db_query($sql);

			while ($xline = db_read($xrlt)) {
				$sql = "update pibic_submit_documento 
										set doc_protocolo = '" . strzero($xline['id_doc'], 7) . "'
										where id_doc = " . $xline['id_doc'];
				$yrlt = db_query($sql);
			}

			$sql = "select * from pibic_submit_documento
								where doc_protocolo_mae = '$proto_mae'
								and doc_status = '@' and doc_edital = 'PIBICE'";
			$rlt = db_query($sql);
			$line = db_read($rlt);
			$proto = $line['doc_protocolo'];
			$_SESSION["proto_aluno"] = $proto;
			redirecina('submit_phase_4.php');
		}
		return (1);
	}

	function submit_plano_jr_valida($protocolo) {
		$sql = "select * from pibic_submit_documento where doc_protocolo = '" . $protocolo . "' ";
		$rlt = db_query($sql);
		if ($line = db_read($rlt)) {
			$ok = 1;
			if (strlen(trim($line['doc_1_titulo'])) < 10) { $ok = -1;
			}
			$sql = "select count(*) as total from pibic_ged_documento 
								where doc_dd0 = '" . $protocolo . "' 
								and doc_tipo = 'PLANO'
								and doc_ativo = 1
								";
			$rrlt = db_query($sql);
			$rline = db_read($rrlt);
			$total = $rline['total'];
			if ($total <= 0) { $ok = -3;
			}
		} else {
			$ok = 0;
		}
		return ($ok);

	}

	function submit_plano_valida($protocolo) {
		$sql = "select * from pibic_submit_documento where doc_protocolo = '" . $protocolo . "' ";
		$rlt = db_query($sql);
		if ($line = db_read($rlt)) {
			$ok = 1;
			$aluno = trim($line['doc_aluno']);
			if (strlen(trim($line['doc_1_titulo'])) < 10) {
				$ok = -1;
				echo '<BR><font color="red">Título do trabalho muito curto</font>';
			}
			if (strlen(trim($line['doc_aluno'])) != 8) { $ok = -2;
			}
			$sql = "select count(*) as total from pibic_ged_documento 
								where doc_dd0 = '" . $protocolo . "' 
								and doc_tipo = 'PLANO'
								and doc_ativo = 1
								";
			$rrlt = db_query($sql);
			$rline = db_read($rrlt);
			$total = $rline['total'];
			if ($total <= 0) { $ok = -3;
			}
		} else {
			$ok = 0;
		}

		/* RN: Verifica se o aluno não está na BlackList */
		if (strlen($aluno) != 8) {
			echo '<font color="red">Código do aluno é necessário</font>';
			$ok = 0;
		}
		if (($aluno != '00000000') and (strlen($aluno) == 8)) {
			$ix = $this -> aluno_em_outros_planos($aluno, $protocolo);
			if ($ix > 0) {
				echo '<font color="red">ALUNO JÁ INCLUÍDO EM OUTROS PROTOCOLOS ()</font>';
				$ok = 0;
				;
			}
			$ix = $this -> aluno_blacklist($aluno);
			if ($ix > 0) {
				echo '<font color="red">ALUNO IMPEDIDO DE PARTICIPAR POR NÃO CUMPRIMENTO AS NORMAS DA INICIAÇÃO CIENTÍFICA, EM CASO DE DÚVIDA ENTRE EM CONTATO COM pibicpr@pucpr.br</font>';
				$ok = 0;
			}
		}

		/* RN: Verifica se o aluno não esta em outro plano */
		return ($ok);
	}

	function aluno_blacklist($aluno) {
		$bl = 0;
		$sql = "select * from pibic_aluno where pa_cracha = '" . $aluno . "' ";
		$rlt = db_query($sql);
		if ($line = db_read($rlt)) {
			$bl = round($line['pa_blacklist']);
		}
		return ($bl);
	}

	function submit_plano_total() {
		$proto_mae = $this -> protocolo;
		$sql = "select count(*) as total from pibic_submit_documento 
						where (doc_edital = 'PIBIC' or doc_edital = 'PIBITI')
						and doc_status <> 'X' and doc_protocolo_mae = '$proto_mae' ";
		$yrlt = db_query($sql);
		if ($yline = db_read($yrlt)) {
			$tot = $yline['total'];
		} else {
			$tot = 0;
		}
		return ($tot);
	}

	function submit_plano_new() {
		global $dd, $user, $ss;
		$data = date("Ymd");
		$hora = date("H:i");

		$proto_mae = $this -> protocolo;
		$professor = $ss -> user_cracha;
		$tipo = 'PIBIC';
		$edital = 'PIBIC';
		$ano = $this -> ano;
		if (strlen($ano) == 0) { $ano = date("Y");
		}

		$proto_mae = $this -> protocolo;
		$qsql = "select * from pibic_submit_documento
							where doc_protocolo_mae = '$proto_mae'
							and doc_status = '@' and doc_1_titulo = 'sem título'";
		$rlt = db_query($qsql);
		if (!($line = db_read($rlt))) {
			$tot = $this -> submit_plano_total();

			if ($tot >= 2) {
				echo 'Limite de planos de alunos por projeto';
				exit ;
			}

			$sql = "insert into pibic_submit_documento 
							(doc_1_titulo, doc_1_idioma, doc_protocolo,
							doc_protocolo_mae, doc_aluno, doc_data, 
							doc_hora, doc_dt_atualizado, doc_autor_principal, 
							doc_status, doc_tipo, doc_journal_id, 
							doc_update, doc_icv, doc_ano, 
							doc_edital, doc_nota, doc_avaliacoes)
							values
							('sem título','pt_BR','',
							'$proto_mae','$aluno','$data',
							'$hora',$data,'$professor',
							'@','$tipo',20,
							$data,'$icv','$ano',
							'$edital',0,0)";
			$rlt = db_query($sql);

			/* Atualiza */
			$this -> updatex();
			$sql = "
								update pibic_submit_documento 
									set doc_protocolo = '" . strzero($xline['id_doc'], 7) . "'
									where doc_protocolo = '' ";
			$rlt = db_query($sql);
			$rlt = db_query($qsql);
			$line = db_read($rlt);
		} else {
			/* Já existe */
		}

		/* Recupera protocolo */
		$proto = $line['doc_protocolo'];
		$_SESSION["proto_aluno"] = $proto;
		redirecina(page() . '?dd90=' . $proto);
	}

	function mostra_erros() {
		if (strlen($this -> erros) > 0) {
			$sx = '<center><div style="background-color: #FFc0c0; height: 50px; width: 80%; padding: 10px 10px 10px 10px; ">';
			$sx .= '<img src="' . http . 'pibic/img/icone_error.png" align="left" height="50">';
			$sx .= '<font class="lt2">';
			$sx .= $this -> erros;
			$sx .= '</div>';
		}
		return ($sx);
	}

	function submit_plano($edt = '') {
		global $dd, $acao, $user, $ss;

		require_once ('../_class/_class_discentes.php');
		$estu = new discentes;
		/** Grava **/
		$data = date("Ymd");
		$hora = date("H:i");
		$proto_mae = $this -> protocolo;
		$professor = $ss -> user_cracha;
		$tipo = 'PLANO';
		$ano = $this -> ano;
		if (strlen($ano) == 0) { $ano = date("Y");
		}

		$edital = $dd[10];
		$aluno = substr($dd[8], 0, 8);
		$titulo = substr($dd[2], 0, 200);
		$icv = $dd[9];
		$ok = 0;

		$id = $_SESSION["proto_aluno"];

		if (strlen($acao) == 0) {
			$sql = "select * from pibic_submit_documento
								where doc_protocolo = '$id'
									and doc_protocolo_mae = '$proto_mae' ";
			$rlt = db_query($sql);
			$line = db_read($rlt);
			$id = $line['doc_protocolo'];
			$_SESSION["proto_aluno"] = $id;
			$dd[8] = trim($line['doc_aluno']);
			$aluno = substr($dd[8], 0, 8);
			$dd[2] = trim($line['doc_1_titulo']);
			$dd[10] = trim($line['doc_edital']);
			$dd[9] = $line['doc_icv'];
			$proto_mae = $line['doc_protocolo_mae'];
		}

		/** Consulta **/
		$valid = '';
		if (strlen($aluno) > 0) { $aluno = strzero(round($aluno), 8);
		}

		if (strlen($aluno) == 8) {
			if ($estu -> le('', $aluno) == 0) {
				$estu -> consulta($aluno);
				$estu -> le('', $aluno);
			}
		}

		$proto = $_SESSION['proto_aluno'];
		$_SESSION["proto_aluno"] = $proto;

		if ($dd[10] == 'PIBIC') { $sel10a = 'selected';
		}
		if ($dd[10] == 'PIBITI') { $sel10b = 'selected';
		}
		if ($dd[10] == 'ICI') { $sel10c = 'selected';
		}
		if ($dd[9] == '1') { $chk9 = 'checked';
		}

		$sx .= '<form method="post">';
		$sx .= '<input type="hidden" name="dd1" value="' . $dd[1] . '">
						<input type="hidden" name="dd0" value="' . $dd[0] . '">
						';
		$sx .= '<fieldset><legend>Título do plano do aluno</legend>
						<table width="100%">
						<TR class="lt0"><TD align="right">PROTOCOLO:<B>' . $proto . '</B>/' . $proto_mae . '
						<tr><TD class="lt0">' . $msg1 . 'TÍTULO DO PLANO DO ALUNO ' . $obr . '
						<tr><td><textarea name="dd2" cols="80" rows="3">' . $dd[2] . '</textarea>
				
						<tr><TD class="lt0">' . $msg1 . 'Modalidade ' . $obr . '
						<tr><td>
							<select name="dd10">
							<option value="">::Modalidade::</option>
							';
		if ($edt == 'IC') {
			$sx .= '
							<option value="PIBIC" ' . $sel10a . '>PIBIC</option>
							<option value="PIBITI" ' . $sel10b . '>PIBITI</option>
							';
		}
		if ($edt == 'ICI') {
			$sx .= '
							<option value="ICI" ' . $sel10c . '>PIBIC Internacional</option>
							';
		}
		$sx .= '
							</select>
						</table>';

		$sx .= '</fieldset>';

		/** Estudante */
		$sx .= '<fieldset><legend>Dados do estudante</legend>
						<table>
						<tr><TD class="lt0">' . $msg1 . 'IDENTIFICAÇÃO DO ALUNO ' . $obr . '
						<TR><TD>Código do aluno: <input type="text" name="dd8" size=8 maxlength=8 value="' . $aluno . '"> (8 digitos)
						<BR><font class="lt1">Ex: 101<font color="blue">12345678</font>1
						<BR>Caso não tenha o nome do estudante, preencha com 00000000.
						';

		$sx .= '<table width="100%"><TR><TD>';

		$estu -> le('', $aluno);
		if (strlen($estu -> pa_nome) == 0) {
			$this -> erros = 'Código do aluno inválido<BR>';
		} else {
			$sx .= $estu -> mostra_dados_pessoais();
			$valid = '1';
		}
		$sx .= '</table>';

		$sx .= '<tr><TD class="lt2"><BR><BR>';
		$sx .= '<B>O estudante tem vinculo empregatício?';
		$sx .= '<tr><TD class="lt2">
							<input type="checkbox" value="1" name="dd9" ' . $chk9 . '> SIM. O estudante com vínculo empregatício poderá concorrer somente a Bolsa PUCPR 
							<input type="hidden" name="dd12" value="' . $valid . '">
						</table>
						';
		$sx .= '</fieldset>';

		/** Arquivos */
		$sx .= '<fieldset><legend>Arquivos submetidos</legend>';
		$sx .= '<iframe src="submit_pibic_plano_arquivos.php" 
						width=100%
						height=150 style="border: 0px solid #FFFFFF;"></iframe>';
		$sx .= '</fieldset>';

		/*** SALVAR **/

		$sx .= '<BR><BR><center>
					<input type="submit" name="acao" value="Gravar e avançar >>>" style="width: 300px; height: 50px;">
					';

		$sx .= '</form>';
		/*** Checagems **/
		$icv = $dd[9];
		$ok = 1;
		if (strlen($dd[2]) < 10) { $ok = -1;
			$this -> erros .= 'Título muito curto<BR>';
		}
		/*** Checar dados do plano */
		$sql = "select * from pibic_submit_documento 
						where doc_1_titulo = '" . $titulo . "' 
						and doc_protocolo <> '" . $proto . "' 
						and doc_status <> 'X' ";
		$yrlt = db_query($sql);
		if ($yline = db_read($yrlt)) {
			$ok = -2;
			$this -> erros .= 'Título já lançado no sistema<BR>';
		}
		/*** Salva Dados */

		if ((strlen($acao) > 0) and ($proto != '')) {
			$sta = '@';
			$aluno = substr($aluno, 0, 8);
			if ($valida > 0) { $sta = 'A';
				$ok = 1;
			}
			$sql = "update pibic_submit_documento set 
							doc_status = '$sta',
							doc_1_titulo = '$titulo',
							doc_aluno = '$aluno',
							doc_dt_atualizado = '$data',
							doc_tipo = '$tipo',
							doc_edital = '$edital',	
							doc_autor_principal = '$professor',
							doc_icv = '$icv'						
							where doc_protocolo = '$proto' 
						";
			//echo '<HR>'.$sql.'<HR>';
			$rlt = db_query($sql);
			$valida = $this -> submit_plano_valida($proto);
		}

		if (strlen(trim($dd[10])) == 0) { $ok = -5;
			$this -> erros .= 'Modalidade do edital não selecionada<BR>';
		}

		if ($valida == -3) { $ok = -3;
			$this -> erros .= 'Falta arquivo do plano dos alunos<BR>';
		}

		if (($ok == 1) and (strlen($acao) > 0) and ($valida == 1) and (strlen($aluno) == 8)) {
			redirecina('submit_project.php');
		}
		//echo "ok=$ok, acao=$acao, valid=$valid, aluno=$aluno ";
		if (strlen($acao) > 0) { $sa = $this -> mostra_erros();
		}

		return ($sa . $sx);
	}

	function le($id = '') {
		$id = round($id);
		$sql = "select * from " . $this -> tabela . "
						left join pibic_professor on pj_professor = pp_cracha
						left join apoio_titulacao on ap_tit_codigo = pp_titulacao
						where id_pj = $id or pj_codigo = '$id'
					";
		$rlt = db_query($sql);
		if ($line = db_read($rlt)) {
			$this -> protocolo = trim($line['pj_codigo']);
			$this -> line = $line;
			$this -> status = $line['pj_status'];
		}
		return (1);
	}

	function mostra_plano_botao($nr) {
		if ($nr == 1 or $nr == 2) { $sx = $this -> mostra_botao_submissao('IC');
		}
		if ($nr == 3) { $sx = $this -> mostra_botao_submissao('ICI');
		}
		if ($nr == 4) { $sx = $this -> mostra_botao_submissao('JR');
		}
		return ($sx);
	}

	function mostra_botao_submissao($tipo) {
		global $proto;
		switch ($tipo) {
			case 'IC' :
				$sx = '<center>
							<form action="submit_pos_IC.php" method="get">
							<input type="hidden" name="dd89" value="' . $this -> protocolo . '" >
							<input type="hidden" name="dd10" value="" >
							<input type="hidden" name="dd0" value="NEW" >
							<input type="submit" value="submeter novo plano' . chr(13) . 'PIBIC/PIBITI" class="plano_novo">
							</form>
							</center>';
				break;
			case 'JR' :
				$sx = '<center>
							<form action="submit_pos_JR.php" method="post">
							<input type="hidden" name="dd89" value="' . $this -> protocolo . '" >
							<input type="hidden" name="dd10" value="" >
							<input type="hidden" name="dd0" value="NEW" >
							<input type="submit" value="submeter novo plano' . chr(13) . 'PIBIC EM" class="plano_novo_pibicem">
							</form>
							</center>';
				break;
			case 'ICI' :
				$sx = '<center>
							<form action="submit_pos_ICI.php" method="post">
							<input type="hidden" name="dd89" value="' . $this -> protocolo . '" >
							<input type="hidden" name="dd10" value="" >
							<input type="hidden" name="dd0" value="NEW" >
							<input type="submit" value="submeter novo plano' . chr(13) . 'PIBIC Internacional" class="plano_novo_ici">
							</form>
							</center>';
				break;
		}
		return ($sx);
	}

	function imagem_edital($edital) {
		global $http;
		$edital = trim($edital);
		switch ($edital) {
			case 'PIBIC' :
				$img = $http . 'img/logo_ic_pibic.png';
				break;
			case 'PIBITI' :
				$img = $http . 'img/logo_ic_pibiti.png';
				break;
			case 'PIBICE' :
				$img = $http . 'img/logo_ic_pibicem.png';
				break;
			case 'PIBIC_EM' :
				$img = $http . 'img/logo_ic_pibicem.png';
				break;
			case 'ICI' :
				$img = $http . 'img/logo_ic_internacional.png';
				break;
		}
		return ($img);
	}

	function acao_indicar_avaliadores() {
		global $dd, $acao, $professor, $pj;
		echo 'Area:' . $this -> area;
		require_once ("_class_pareceristas.php");
		require_once ("_class_parecer_pibic.php");
		$par = new parecerista;
		$pp = new parecer_pibic;
		$pp -> tabela = 'pibic_parecer_' . date("Y");
		//$pp->structure();
		$bb1 = 'enviar indicação da avaliação >>>';
		$acao2 = $_POST["acao2"];
		$professor = $pj -> professor;
		if ($bb1 == $acao2) {
			$avaliadores = array();
			for ($r = 0; $r < 50000; $r++) {
				$vlr = $_POST['ddp' . $r];
				if (strlen($vlr) > 0) { array_push($avaliadores, $vlr);
				}
			}
			for ($rx = 0; $rx < count($avaliadores); $rx++) {
				$aval = $avaliadores[$rx];
				$prev = (mktime(0, 0, 0, date("m"), date("d"), date("Y"), 0) + 15 * 60 * 60 * 24);
				$prev = date("Ymd", $prev);

				if (strlen($aval) > 0) { $this -> acao_indicar_avaliaca($this -> protocolo, $aval, $pp, 'SUBMI', $prev);
				}
			}
			if (count($avaliadores) > 0) {
				$sql = "update pibic_submit_documento set doc_status='D' ";
				$sql .= "where doc_protocolo_mae = '" . $this -> protocolo . "') 
									and doc_status='C' ";

				$sql = "update " . $this -> tabela . " set pj_status = 'D' where pj_codigo = '" . $this -> protocolo . "'";
				$wrlt = db_query($sql);
			}
			echo '<BR><BR>Indicado e enviado';
			exit ;
		}

		$this -> area = $this -> line['pj_area'];
		$prof = $this -> line['pj_professor'];
		$externo = $par -> parecerista_lista('E', 'pibic_parecer', $this -> area, 'pibic_parecer_' . date("Y"), 'SUBMI', $prof);
		$interno = $par -> parecerista_lista('L', 'pibic_parecer', $this -> area, 'pibic_parecer_' . date("Y"), 'SUBMI', $prof);
		$gestor = $par -> parecerista_lista('G', 'pibic_parecer', $this -> area, 'pibic_parecer_' . date("Y"), 'SUBMI', $prof);

		$professor = trim($pj -> professor);

		$sx .= '<form method="post" action="' . page() . '">';
		$sx .= '<input type="hidden" name="dd0" value="' . $dd[0] . '">' . chr(13);
		$sx .= '<input type="hidden" name="dd1" value="' . $dd[1] . '">' . chr(13);
		$sx .= '<input type="hidden" name="acao" value="' . $acao . '">' . chr(13);
		$sx .= '<input type="hidden" name="dd90" value="' . $dd[90] . '">' . chr(13);

		$sx .= '<table width="100%" border=1 class="lt1" >';
		$sx .= '<TR valign="top">';
		$sx .= '<TD width="33%">';

		$sx .= '<fieldset><legend>Avaliadores Ad Hoc</legend>';
		$sx .= '<table>';
		$ax = 'x';
		$in = 0;
		for ($r = 0; $r < count($externo); $r++) {
			$in++;
			if ($ax != $externo[$r][2]) {
				$sx .= '<BR>';
				$sx .= '<B>' . $externo[$r][2] . ' - ' . $externo[$r][3] . '</b><BR>';
				$ax = $externo[$r][2];
			}
			$sx .= '<input type="checkbox" name="ddp' . $in . '" value="' . $externo[$r][0] . '">';
			$sx .= trim($externo[$r][1]);
			$sx .= '<BR>';
		}
		$sx .= '</table>';
		$sx .= '</fieldset>';

		$sx .= '<TD width="33%">';

		$sx .= '<fieldset><legend>Avaliadores Locais</legend>';
		$sx .= '<table>';
		$ax = '';
		for ($r = 0; $r < count($interno); $r++) {
			if ($professor != trim($interno[$r][0])) {
				$in++;
				if ($ax != $interno[$r][2]) {
					//$sx .= '<BR>';
					//$sx .= '<B>'.$interno[$r][2].' - '.$interno[$r][3].'</b><BR>';
					$ax = $interno[$r][2];
				}

				$sx .= '<input type="checkbox" name="ddp' . $in . '" value="' . $interno[$r][0] . '">';
				$sx .= trim($interno[$r][1]);
				$sx .= '<BR>';
			}
		}
		$sx .= '</table>';
		$sx .= '</fieldset>';

		$sx .= '<TD width="33%">';
		/* Comite Gestor */
		$sx .= '<fieldset><legend>Comitê Gestor</legend>';
		$sx .= '<table>';
		$ax = '';

		for ($r = 0; $r < count($gestor); $r++) {
			if ($professor != trim($gestor[$r][0])) {
				$in++;
				if ($ax != $gestor[$r][2]) {
					$sx .= '<BR>';
					$sx .= '<B>' . $gestor[$r][2] . ' - ' . $gestor[$r][3] . '</b><BR>';
					$ax = $gestor[$r][2];
				}

				$sx .= '<input type="checkbox" name="ddp' . $in . '" value="' . $gestor[$r][0] . '">';
				$sx .= trim($gestor[$r][1]);
				$sx .= '</FONT><BR>';
			}
		}
		$sx .= '</table>';
		$sx .= '</fieldset>';
		$sx .= '<TR><TD colspan=3>';
		$sx .= '<input type="submit" name="acao2" value="' . $bb1 . '">';
		$sx .= '</table>';
		$sx .= '</form>';
		echo $sx;
		return ($sx);
	}

	function mostra_plano_aluno($line) {
		global $user, $perfil;
		$aluno = trim($line['pa_nome']);
		$tipo = trim($line['doc_edital']);
		$vies = $line['pb_vies'];
		
		$nota = $line['doc_nota'];
		$recurso = round($line['doc_recurso']);
		$penalidade = $line['doc_penalidade'];
		$bonificacao = round('0'.$line['doc_bonificacao']);
		
		$img = $this -> imagem_edital($tipo);
		$link = '<A HREF="pibic_projetos_detalhes.php?dd0=' . $line['doc_protocolo_mae'] . '&dd90=' . checkpost($line['doc_protocolo_mae']) . '">';
		switch ($tipo) {
			case 'PIBIC' :
				$linka = ' onclick="window.location.replace(\'submit_pos_IC.php?dd90=' . trim($line['doc_protocolo']) . '&dd89=' . trim($line['pj_codigo']) . '\');" class="botao-geral" ';
				;
				$linkb = ' onclick="newxy2(\'main_submit_cancel.php?dd0=' . $line['doc_protocolo'] . '&dd90=' . checkpost($line['pj_codigo']) . '\',600,200);" class="botao-geral" ';
				break;
			case 'PIBITI' :
				$linka = ' onclick="window.location.replace(\'submit_pos_IC.php?dd90=' . trim($line['doc_protocolo']) . '&dd89=' . trim($line['pj_codigo']) . '\');" class="botao-geral" ';
				;
				$linkb = ' onclick="newxy2(\'main_submit_cancel.php?dd0=' . $line['doc_protocolo'] . '&dd90=' . checkpost($line['pj_codigo']) . '\',600,200);" class="botao-geral" ';
				break;
			case 'PIBICE' :
				$linka = ' onclick="window.location.replace(\'submit_pos_JR.php?dd90=' . trim($line['doc_protocolo']) . '&dd89=' . trim($line['pj_codigo']) . '\');" class="botao-geral" ';
				;
				$linkb = ' onclick="newxy2(\'main_submit_cancel.php?dd0=' . $line['doc_protocolo'] . '&dd90=' . checkpost($line['pj_codigo']) . '\',600,200);" class="botao-geral" ';
				break;
			case 'ICI' :
				$linka = ' onclick="window.location.replace(\'submit_pos_ICI.php?dd90=' . trim($line['doc_protocolo']) . '&dd89=' . trim($line['pj_codigo']) . '\');" class="botao-geral" ';
				;
				$linkb = ' onclick="newxy2(\'main_submit_cancel.php?dd0=' . $line['doc_protocolo'] . '&dd90=' . checkpost($line['pj_codigo']) . '\',600,200);" class="botao-geral" ';
				break;
		}

		$sx = '';
		$sx .= '<img src="' . $img . '" height="60" align="left">';
		$sx .= 'Protocolo:' . $line['doc_protocolo'] . '/' . $link . $line['doc_protocolo_mae'] . '</A>' . '';
		if (($perfil->valid('#ADM#PIB#PIT')))
			{
				$sx .= ' Nota: <B>'.$nota.'</B>, Bonificação: <B>'.$bonificacao.'</B>, Penalidade: ,<b>'.$penalidade.'</B>, Recurso <B>'.$recurso.'</B>';
			}
		$sx .= '<BR><HR>';

		$sx .= '<BR><B>' . $line['doc_1_titulo'] . '</b><BR>';
		$sx .= '<BR><B>' . $aluno . '</b>';

		$sx .= 'ARQUIVOS';
		$ged = new ged;
		$ged -> tabela = 'pibic_ged_documento';
		$ged -> protocol = $line['doc_protocolo'];
		$sx .= '<div>';
		$sx .= $ged -> filelist();
		if ($vies=='1')
			{
				$sx .= '<BR><font color="brown">*** <B>Indicado pelo avaliador com vies PIBITI</B></font>';
			}
		$sx .= '</div>';
		$sx .= '<HR>';

		return ($sx);
	}

	function mostra_plano_mini($line) {
		$aluno = trim($line['pa_nome']);
		$tipo = trim($line['doc_edital']);
		$img = $this -> imagem_edital($tipo);
		switch ($tipo) {
			case 'PIBIC' :
				$linka = ' onclick="window.location.replace(\'submit_pos_IC.php?dd90=' . trim($line['doc_protocolo']) . '&dd89=' . trim($line['pj_codigo']) . '\');" class="botao-geral" ';
				;
				$linkb = ' onclick="newxy2(\'main_submit_cancel.php?dd0=' . $line['doc_protocolo'] . '&dd90=' . checkpost($line['pj_codigo']) . '\',600,200);" class="botao-geral" ';
				break;
			case 'PIBITI' :
				$linka = ' onclick="window.location.replace(\'submit_pos_IC.php?dd90=' . trim($line['doc_protocolo']) . '&dd89=' . trim($line['pj_codigo']) . '\');" class="botao-geral" ';
				;
				$linkb = ' onclick="newxy2(\'main_submit_cancel.php?dd0=' . $line['doc_protocolo'] . '&dd90=' . checkpost($line['pj_codigo']) . '\',600,200);" class="botao-geral" ';
				break;
			case 'PIBICE' :
				$linka = ' onclick="window.location.replace(\'submit_pos_JR.php?dd90=' . trim($line['doc_protocolo']) . '&dd89=' . trim($line['pj_codigo']) . '\');" class="botao-geral" ';
				;
				$linkb = ' onclick="newxy2(\'main_submit_cancel.php?dd0=' . $line['doc_protocolo'] . '&dd90=' . checkpost($line['pj_codigo']) . '\',600,200);" class="botao-geral" ';
				break;
			case 'ICI' :
				$linka = ' onclick="window.location.replace(\'submit_pos_ICI.php?dd90=' . trim($line['doc_protocolo']) . '&dd89=' . trim($line['pj_codigo']) . '\');" class="botao-geral" ';
				;
				$linkb = ' onclick="newxy2(\'main_submit_cancel.php?dd0=' . $line['doc_protocolo'] . '&dd90=' . checkpost($line['pj_codigo']) . '\',600,200);" class="botao-geral" ';
				break;
		}

		$sx = '';
		$sx .= '<img src="' . $img . '" height="30" align="right">';
		$sx .= 'Protocolo:' . $line['doc_protocolo'] . '<BR><HR>';

		$sx .= '<BR><B>' . $line['doc_1_titulo'] . '</b><HR>';
		$sx .= '<BR><B>' . $aluno . '</b>';
		if (($this -> status == '!') or ($this -> status == '@')) {
			$sx .= '<BR>';
			$sx .= '<input type="button" value="editar" class="submit-geral" ' . $linka . '>';
			$sx .= ' | ';
			$sx .= '<input type="button" value="cancelar" class="submit-geral" ' . $linkb . '>';
		}

		return ($sx);
	}

	function mostra_planos_resumo() {
		$sql = "select * from " . $this -> tabela_planos . " 
						left join pibic_aluno on doc_aluno = pa_cracha
						inner join " . $this -> tabela . " on pj_codigo = doc_protocolo_mae 
						where doc_protocolo_mae = '" . $this -> protocolo . "'
						and doc_status <> 'X'
						order by doc_protocolo
						";
		$rlt = db_query($sql);
		$t1 = array();
		if (($this -> status == '!') or ($this -> status == '@')) {
			array_push($t1, $this -> mostra_plano_botao(1));
			array_push($t1, $this -> mostra_plano_botao(2));
			array_push($t1, $this -> mostra_plano_botao(3));
			array_push($t1, $this -> mostra_plano_botao(4));
		} else {
			array_push($t1, 'Sem plano submetido');
			array_push($t1, 'Sem plano submetido');
			array_push($t1, 'Sem plano submetido');
			array_push($t1, 'Sem plano submetido');
		}
		$ic1 = 0;
		$ic2 = 0;
		$ic3 = 0;
		$tot = 0;
		while ($line = db_read($rlt)) {
			$tot++;
			//echo '<HR>';
			//print_r($line);
			$tipo = trim($line['doc_edital']);
			if ($tipo == 'PIBIC' or $tipo == 'PIBITI') {
				$t1[$ic1] = $this -> mostra_plano_mini($line);
				$ic1++;
			}
			if ($tipo == 'ICI') {
				$t1[2] = $this -> mostra_plano_mini($line);
				$ic2++;
			}
			if ($tipo == 'PIBICE') {
				$t1[3] = $this -> mostra_plano_mini($line);
				$ic3++;
			}
		}

		$sx .= '<table width="100%" border=0 class="tabela00">';
		$sx .= '<TR valign="top">';
		$sx .= '<TD width="25%">
							<div class="plano01">' . $t1[0] . '</div>';
		$sx .= '<TD width="25%">
							<div class="plano01">' . $t1[1] . '</div>';
		$sx .= '<TD width="25%">
							<div class="plano01">' . $t1[2] . '</div>';
		$sx .= '<TD width="25%">
							<div class="plano01">' . $t1[3] . '</div>';

		if ($tot == 0) {
			$sx .= '<TR><TD colspan=4><font color="red" style="font-size: 16px;">Nenhum plano cadastrado';
		} else {
			if (($this -> status == '!') or ($this -> status == '@')) {
				$sx .= '<TR><TD colspan=4><font color="Green" style="font-size: 16px;">Total de plano(s) ' . $tot . ' submetido(s).';
				$sx .= $this -> submit_button_project();
			}
		}
		$sx .= '</table>';
		return ($sx);
	}

	function submit_button_project() {
		$sx .= '<form method="post" action="submit_pos_6.php">';
		$sx .= '<input type="hidden" name="dd89" value="' . $this -> protocolo . '">';
		$sx .= '<input type="submit" value="enviar projeto e plano" name="acao">';
		$sx .= '</form>';
		return ($sx);
	}

	function mostra_sn($sn) {
		if ($sn == 'S') { $sn = 'Sim';
		}
		if ($sn == 'N') { $sn = 'Não';
		}
		if ($sn == 'A') { $sn = '<font color="green">Em submissão</font>';
		}
		if ($sn == 'B') { $sn = '<font color="blue" Em avaliação</font>';
		}
		if ($sn == 'C') { $sn = 'Aprovado';
		}
		return ($sn);
	}

	function mostra_botoes_editar($line) {
		$sx .= '<input type="button" value="editar projeto do professor" onclick="window.location.replace(\'submit_pos_1.php?dd89=' . trim($line['pj_codigo']) . '\');" class="botao-geral">';
		$sx .= '&nbsp;|&nbsp;';
		$sx .= '<input type="button" value="cancelar projeto do professor" onclick="newxy2(\'main_submit_cancel.php?dd0=' . $line['pj_codigo'] . '&dd90=' . checkpost($line['pj_codigo']) . '\',600,200);" class="botao-geral">';
		//$sb .= '<img src="img/icone_error.png" height=16" title="cancelar projeto do professor" border=0>';
		return ($sx);
	}

	function nome_orientador($cracha) {
		$sql = "select * from pibic_professor where pp_cracha = '" . $cracha . "' ";
		$rlt = db_query($sql);
		$line = db_read($rlt);
		return ($line['pp_nome']);
	}

	function botao_novo_projeto() {
		if ($this -> plano_submissao == 0) {
			$sx .= '
					<form method="get" action="submit_new_project.php">
					<input type="submit" value="Submeter Novo Projeto >>>" class="botao-confirmar">
					</form>
					';
		} else {
			$sx = '';
		}
		return ($sx);
	}

	function mostra_area($area = '') {
		$sql = "select * from ajax_areadoconhecimento where a_cnpq = '" . $area . "' ";
		$rlt = db_query($sql);
		if ($line = db_read($rlt)) {
			return (trim($line['a_descricao']));
		}
		return ('(não identificada) ' . $area);
	}

	function mostra($line, $id = '', $edit = 1) {
		global $user,$perfil;
		$this -> session_seta(trim($line['pj_codigo']));
		$this -> protocolo = trim($line['pj_codigo']);
		$this -> status = $line['pj_status'];
		/* status e link */
		if (($line['pj_status'] == '@') or ($line['pj_status'] == 'A')) { $link = '<A HREF="submit_phase_sel.php?dd0=' . $line['id_pj'] . '&dd90=' . checkpost($line['id_pj']) . '">';
		}
		if ($edit == 0) { $link = '';
		}
		/* Number Id do projeto */
		$sx .= '<TR valign="top">
								<TD colspan = 5 bgcolor="#606060">
								<font color="white" style="font-size: 20px;">
								Projeto 
								' . trim($line['pj_codigo']) . ' - 
								';

		/* Status do projeto */
		$sta .= $line['pj_status'];
		switch ($sta) {
			case '@' :
				$sx .= '<font color="orange"><B>Em submissão</B><font>';
				break;
			case '!' :
				$sx .= '<font color="orange"><B>Em submissão</B><font>';
				break;
			case 'A' :
				$sx .= '<font color="white"><B>Submetido em ' . stodbr($line['pj_data']) . '</B><font>';
				break;
			case 'B' :
				$sx .= '<font color="white"><B>Submetido em ' . stodbr($line['pj_data']) . '</B><font>';
				break;
			case 'E' :
				$sx .= '<font color="white"><B>Avaliação Finalizada</B><font>';
				break;
			case 'C' :
				$sx .= '<font color="white"><B>Projeto em avaliação</B><font>';
				break;
			case 'X' :
				$sx .= '<font color="red"><B>Cancelado</B><font>';
				break;
			case 'D' :
				$sx .= '<font color="white"><B>Em avaliação</B><font>';
				break;
			case 'T' :
				$sx .= '<font color="white"><B>Em análise da TI</B><font>';
				break;
			case 'P' :
				$sx .= '<font color="white"><B>Em análise do Comitê Gestor</B><font>';
				break;
			case 'F' :
				$sx .= '<font color="white"><B>Avaliação Finalizada</B><font>';
				break;
			default :
				$sx .= '???' . $sta;
				break;
		}

		/* Iconografia */
		$sx .= '<TR valign="top">
								<TD rowspan=6 width="20">
								<img src="' . http . 'pibic/img/icone_projeto_professor.png" width=50>';

		/* Título da Pesquisa */

		$sx .= '<TR valign="top">';

		$sx .= '<TD class="tabela00" colspan=3 align="center">';
		$sx .= '<font class="lt4"><B>';
		$sx .= trim($line['pj_titulo']);
		$sx .= '</font>';

		/* Orientador e protocolo */

		$sx .= '<TR valign="top">';
		$sx .= '<TD width="10%" class="tabela00" align="right">';
		$sx .= '<font class="lt0">orientador:</font>';

		$sx .= '<TD class="tabela00">';
		$sx .= '<font class="lt2" width="80%">';
		$sx .= $line['ap_tit_titulo'] . ' ';
		$sx .= $this -> nome_orientador($line['pj_professor']);
		$sx .= ' (' . trim($line['pp_cracha']) . ')';
		$lattes = trim($line['pp_lattes']);
		if (strlen($lattes) > 0) {
			$sx .= ' <A HREF="' . $lattes . '" target="_blank">Lattes</A>';
		} else {
			$sx .= '<font color="red">sem lattes</font>';
		}

		$sx .= '</font>';

		$sx .= '<TD width="15%" class="tabela00" align="center">';
		$sx .= '<nobr>Protocolo: <B>' . trim($line['pj_codigo']) . '</B>';

		/* Comentarios */
		$comment = trim($line['pj_coment']);
		if ((strlen($comment) > 0) and ($line['pj_status'] == '@')) {
			$sx .= '<TR><TD class="tabela00" colspan=3>';
			$sx .= '<img src="' . http . '/img/icone_alert.png" align="left">';
			$sx .= '<font color="red">';
			$sx .= $comment;
		} else {
			$sx .= '<TR><TD colspan=3></td></tr>';
		}

		/* dados complementares */
		$sx .= '
						<TR><TD colspan=3>
						<table width="600" align="right" bgcolor="#D0D0D0" border=1>';

		/* area */
		$sx .= '<TR><TD align="right" class="tabela00">';
		$sx .= 'área do conhecimento:';
		$sx .= '<TD class="tabela00" colspan=1 ><B>' . trim($line['pj_area']) . ' - ' . $this -> mostra_area(trim($line['pj_area'])) . '</B>';

		/* Aprovação & Financiamento Externo */
		$sa = '<font class="lt0" align="right">Projeto aprovado externamente por uma agência de fomento: </font>';

		/* area estratégica */
		$sx .= '<TR><TD class="tabela00" align="right">';
		$sx .= 'área estratégica:';
		$sx .= '<TD class="tabela00" colspan=1 ><nobr><B>' . trim($line['pj_area_estra']) . ' - ' . $this -> mostra_area(trim($line['pj_area_estra'])) . '</B>';

		$sx .= '<TR><TD class="tabela00" align="right">' . $sa . '
									<TD class="tabela00"><B>' . $this -> mostra_sn($line['pj_ext_sn']) . '</B>';

		/* Aprovação & Financiamento Externo */
		$sa = '<font class="tabela00" align="right">Grupo 2 - Financiamento por empresa: </font>';
		$sx .= '<TR><TD class="tabela00" align="right">' . $sa . '
									<TD class="tabela00"><B>' . $this -> mostra_sn($line['pj_gr2_sn']) . '</B>';

		/* CEP e CEUA */
		$cep = $line['pj_cep_status'];
		$ceua = $line['pj_ceua_status'];

		$sa = '<font class="tabela00" align="right">Análise do comitê de ética de humanos (CEP):</font>';
		$sx .= '<TR><TD class="tabela00" align="right">' . $sa . '
									<TD class="tabela00"><B>' . $this -> mostra_sn($cep) . '</B>';

		$sa = '<font class="tabela00" align="right">Análise do comitê de ética no uso de animais (CEUA):</font>';
		$sx .= '<TR><TD class="tabela00" align="right">' . $sa . '
									<TD class="tabela00"><B>' . $this -> mostra_sn($ceua) . '</B>';

		$sx .= '</table>';
		/* arquivos */
		if ($sta != 'X') {
			require ("../pibic/_ged_config_submit_pibic.php");
			$ged -> protocol = trim($line['pj_codigo']);
			$sx .= '<TR>
										<td colspan=3 class="tabela00">Arquivos:';
			if ($edit == 1) {
				$sx .= $ged -> filelist();
			} else {
				$sx .= $ged -> file_list();
			}
		}

		if ($sta == 'T') {
			require ("../pibic/_ged_config_submit_pibic.php");
			if ($_GET['ddg'] == 'DEL') {
				$ddf = round($_GET['ddf']);
				$ged -> id_doc = $ddf;
				$ged -> file_delete();
			}
			$ged -> protocol = trim($line['pj_codigo']);
			$sx .= '<TR>
										<td colspan=3 class="tabela00">Arquivos:';
			$sx .= $ged -> file_list();
		}

		return ($sx);

	}

	function session_zera() {
		$_SESSION['protocolo'] = '';
		$_SESSION['proto'] = '';
		return (1);
	}

	function session_seta($protocolo) {
		$_SESSION['protocolo'] = $protocolo;
		$_SESSION['proto'] = $protocolo;
		return (1);
	}

	function mostra_projetos() {
		global $dd, $ss;
		$professor = $ss -> user_cracha;

		$ano = $this -> ano;
		if (strlen($ano) == 0) { $ano = date("Y");
		}

		$cp = 'a1.a_descricao as area1, a2.a_descricao as area2 ';
		$sql = "select $cp,* from " . $this -> tabela . " 
					left join ajax_areadoconhecimento as a1 on pj_area = a1.a_cnpq and a1.a_semic = '1'
					left join ajax_areadoconhecimento as a2 on pj_area_estra = a2.a_cnpq and a1.a_semic = '1'
					left join pibic_professor on pj_professor = pp_cracha
					left join apoio_titulacao on ap_tit_codigo = pp_titulacao
										
					where pj_professor = '$professor' 
					and pj_status <> 'X'
					and pj_ano = '$ano'
					order by pj_ano desc, pj_status, pj_codigo
					limit 20 ";
		$yrlt = db_query($sql);

		$id = 0;
		$sx .= '<table bgcolor="green" border=0 width="100%">';
		while ($yline = db_read($yrlt)) {
			$id++;
			$this -> protocolo = $yline['pj_codigo'];
			$this -> status = $yline['pj_status'];

			$sx .= '<TR><TD class="tabela01">';
			$sx .= $this -> mostra($yline, $id);

			/* Botoes de acao */
			if (($this -> status == '!') or ($this -> status == '@')) {
				$sx .= '<TR><TD align="right" colspan=4>';
				$sx .= $this -> mostra_botoes_editar($yline);
			}

			/* Resumo dos planos */
			$sx .= $this -> mostra_planos_resumo();
			//$sx .= $this->planos_submit();
		}
		$sx .= '</table>';
		return ($sx);
	}

	function resumo($professor, $ano) {
		$stp = array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);
		$sql = "select pj_status, 1 as total, pj_codigo, doc_protocolo from " . $this -> tabela . "
					left join pibic_submit_documento on pj_codigo = doc_protocolo_mae
					where pj_professor = '$professor'
						and pj_ano = '$ano'  
						group by pj_status, pj_codigo, doc_protocolo
						order by pj_codigo
				";

		$rlt = db_query($sql);
		$xproto = '';
		while ($line = db_read($rlt)) {
			$sta = trim($line['pj_status']);
			$stb = trim($line['doc_status']);
			$proto = trim($line['pj_codigo']);
			$total = $line['total'];

			if ($proto != $xproto) {
				$xproto = $proto;
				switch ($sta) {
					case '@' :
						$stp[0] = $stp[0] + $total;
						break;
					case '!' :
						$stp[0] = $stp[0] + $total;
						break;
					case 'A' :
						$stp[0] = $stp[0] + $total;
						break;
					case 'B' :
						$stp[3] = $stp[3] + $total;
						break;
					case 'X' :
						$stp[4] = $stp[4] + $total;
						break;
				}
				$stp[2] = $stp[2] + $total;
			}

			switch ($sta) {
				case '@' :
					$stp[5] = $stp[5] + $total;
					break;
				case '!' :
					$stp[5] = $stp[5] + $total;
					break;
				case 'A' :
					$stp[6] = $stp[6] + $total;
					break;
				case 'B' :
					$stp[8] = $stp[8] + $total;
					break;
				case 'X' :
					$stp[9] = $stp[9] + $total;
					break;
			}
			$stp[7] = $stp[7] + $total;
			/* Planos */
		}

		$this -> plano_submissao = $stp[0];

		//echo '<HR>';
		$sta = trim($line['doc_status']);
		$total = $line['total'];
		if ($sta == '@') { $stp[5] = $stp[5] + $total;
		}
		if ($sta == 'C') { $stp[8] = $stp[8] + $total;
		}
		if ($sta == 'B') { $stp[8] = $stp[8] + $total;
		}
		if ($sta == 'A') { $stp[6] = $stp[6] + $total;
		}
		if ($sta == 'X') { $stp[9] = $stp[9] + $total;
		}
		$stp[7] = $stp[7] + $total;

		/* Tela do resumo */
		$sx = '<table class="tabela00" width="940">';
		$sx .= '<TR>';
		$sx .= '<TH colspan=3 class="tabela01"><font class="lt2">Projeto do professor</font>';
		$sx .= '<TH colspan=3 class="tabela01"><font class="lt2">Planos de Alunos</font>';
		$sx .= '<TR>';
		$sx .= '<TH class="tabela01" width="16%">Em Submissão';
		$sx .= '<TH class="tabela01" width="16%">Submetido';
		$sx .= '<TH class="tabela01" width="16%">Cancelado';

		$sx .= '<TH class="tabela01" width="16%">Em Submissão';
		$sx .= '<TH class="tabela01" width="16%">Submetido';
		$sx .= '<TH class="tabela01" width="16%">Cancelado';

		$sx .= '<TR>';
		$sx .= '<TD class="tabela01 lt5" align="center"> ' . $stp[0];
		$sx .= '<TD class="tabela01 lt5" align="center"> ' . $stp[3];
		$sx .= '<TD class="tabela01 lt5" align="center"> ' . $stp[4];

		$sx .= '<TD class="tabela01 lt5" align="center"> ' . $stp[5];
		$sx .= '<TD class="tabela01 lt5" align="center"> ' . $stp[8];
		$sx .= '<TD class="tabela01 lt5" align="center"> ' . $stp[9];

		$sx .= '</table>';
		return ($sx);

	}

	function updatex() {
		global $base;
		$c = 'doc';
		$c1 = 'id_doc';
		$c2 = 'doc_protocolo';
		$c3 = 7;
		$sql = "update " . $this -> tabela_planos . " set $c2 = trim(to_char(id_" . $c . ",'" . strzero(0, $c3) . "')) where $c2='' or ($c2 = '0000000')";
		$rlt = db_query($sql);
	}

}
