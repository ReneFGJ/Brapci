<?php
class journals {
	var $tabela = 'brapci_journal';
	var $line;
	var $codigo;
	var $abrev;
	var $journal_name;
	var $issn;
	var $issn_e;
	var $ano_inicial;
	var $ano_final;
	var $artigos;
	var $tipo;
	var $status;
	var $status_nome;
	var $periodicidade;
	var $periodicidade_nome;
	
	function journals_articles_lista_simple($status = '', $journal = '') {
		if (strlen($status)) {
			$wh = " ar_status = '$status' and  ";
		}
		$sql = "select ar_status, ar_journal_id,
						ed_ano, ed_vol, ed_nr, ed_codigo, 
						ed_status
						from brapci_edition
						
						left join brapci_article on ed_codigo = ar_edition
						where $wh ed_journal_id = '$journal'
					group by ar_status, ar_journal_id,
								ed_ano, ed_vol, ed_nr, ed_codigo, ed_status
					order by ed_ano desc, ed_vol desc, ed_nr desc
					";
		
		$rlt = db_query($sql);
		$sx = '<table width="100%" class="tabela00 lt1">';
		$sx .= '<TR><TH>Volume</TR>';
		$xano = '';
		
		while ($line = db_read($rlt)) {
			$ano = $line['ed_ano'];
			$link = 'row_articles.php?dd0=' . $line['ed_codigo'] . '&dd90=' . checkpost($line['ed_codigo']);
			$link = '<A HREF="' . $link . '" class="link" target="frame_articles">';
			if ($xano != $ano) {
				$sx .= '<TR>';
				$xano = $ano;
			}
			$sx .= '<TR class="border_top_1">';
			$sx .= '<TD class="border_top_1">';
			$sx .= $link;
			$sx .= $line['ed_ano'] . ', ';
			$sx .= 'vol. ' . trim($line['ed_vol']);
			$nr = trim($line['ed_nr']);
			if (strlen($nr) > 0) { $sx .= ', nr. ' . $nr . '';
			$sx .= '<TD align="center" class="border_top_1">';
			$sx .= $this->mostra_issue_status($line['ed_status']);
			}
		}
		$sx .= '</table>';
		return ($sx);
	}	
	
	function mostra_issue_status($sta)
		{
			switch ($sta)
				{
					case 'A': $sta = msg('edition'); break;
					case 'C': $sta = msg('to_check'); break;
					case 'F': $sta = msg('checked'); break;
				}
			return($sta);
		}
	

	function journals_articles_lista($status = '', $journal = '') {
		if (strlen($status)) {
			$wh = " ar_status = '$status' and  ";
		}
		$sql = "select count(*) as total, jnl_nome , ar_status, ar_journal_id,
						ed_ano, ed_vol, ed_nr, ed_codigo
						from brapci_article
						inner join brapci_journal on jnl_codigo = ar_journal_id
						inner join brapci_edition on ed_codigo = ar_edition
						where $wh ar_journal_id = '$journal'
					group by ar_status, jnl_nome, ar_journal_id,
								ed_ano, ed_vol, ed_nr, ed_codigo
					order by ed_ano, ed_vol, ed_nr, jnl_nome
					";

		$rlt = db_query($sql);
		$sx = '<table width="100%" class="resumo">';
		$sx .= '<TR><TH>Volume<TH>Total</TH>
			<TH>Volume<TH>Total</TH>
			<TH>Volume<TH>Total</TH>
			<TH>Volume<TH>Total</TH>
			<TH>Volume<TH>Total</TH>
			<TH>Volume<TH>Total</TH>
			<TH>Volume<TH>Total</TH>
			</TR>';
		$xano = '';
		while ($line = db_read($rlt)) {
			$ano = $line['ed_ano'];
			$link = 'issue.php?dd0=' . $line['ed_codigo'] . '&dd90=' . checkpost($line['ed_codigo']);
			$link = '<A HREF="' . $link . '" class="link">';
			if ($xano != $ano) {
				$sx .= '<TR>';
				$xano = $ano;
			}
			$sx .= '<TD>';
			$sx .= $line['ed_ano'] . ', ';
			$sx .= 'vol. ' . trim($line['ed_vol']);
			$nr = trim($line['ed_nr']);
			if (strlen($nr) > 0) { $sx .= ', nr. ' . $nr . '';
			}

			$sx .= '<TD align="center">';
			$sx .= $link . trim($line['total']) . '</A>';
		}
		$sx .= '</table>';
		return ($sx);
	}

	function journals_articles_status($status = '') {
		$sql = "select count(*) as total, jnl_nome , ar_status, ar_journal_id 
						from brapci_article
						inner join brapci_journal on jnl_codigo = ar_journal_id
						where ar_status = '$status'
					group by ar_status, jnl_nome, ar_journal_id
					order by jnl_nome
					";
		$rlt = db_query($sql);
		$sx = '<table width="100%" class="resumo">';
		$sx .= '<TR><TH>Publica��o</TH><TH>Total</TH></TR>';
		while ($line = db_read($rlt)) {
			$link = 'articles_resumo_lista.php?dd1=' . $status . '&dd2=' . $line['ar_journal_id'] . '&dd90=' . checkpost($status . $line['ar_journal_id']);
			$link = '<A HREF="' . $link . '" class="link">';
			$sx .= '<TR>';
			$sx .= '<TD>';
			$sx .= trim($line['jnl_nome']);
			$sx .= '<TD align="center">';
			$sx .= $link . trim($line['total']) . '</A>';
		}
		$sx .= '</table>';
		return ($sx);
	}

	function jnl_resumo() {
		$sx = $this -> jnl_resumo_journals();
		$sx .= $this -> jnl_resumo_articles();
		return ($sx);
	}

	function jnl_resumo_articles() {

		$sql = "select count(*) as total, ar_status from brapci_article
					where ar_status <> 'X'
					group by ar_status 
					order by ar_status ";
		$rlt = db_query($sql);
		$st = array("" => "N�o classficado", "@" => "Coletado", "A" => "1� Revis�o", "B" => "2� Revis�o", "C" => "3� Revis�o", "D" => "Conclu�do", "X" => "Cancleado", "F" => "Metodologia");
		$th = '';
		$tv = '';
		$tt = 0;
		$sz = 1;

		while ($line = db_read($rlt)) {
			$link = '<A href="articles_resumo.php?dd1=' . $line['ar_status'] . '&dd90=' . checkpost($line['ar_status']) . '" class="link">';
			$sz++;
			$tt = $tt + $line['total'];
			$th .= '<TH $sz >' . $st[$line['ar_status']] . ' (' . $line['ar_status'] . ')';
			$tv .= '<TD class="lt4">' . $link . $line['total'] . '</A>';
		}

		if ($sz > 0) { $sz = (int)(100 / $sz);
		}
		$th = troca($th, ' $sz', ' width="' . $sz . '%" ');

		$th .= '<Th>Total';
		$tv .= '<TD class="lt4">' . $tt;
		$sx .= '<table width="100%" class="resumo">';
		$sx .= '<TR align="center">' . $th;
		$sx .= '<TR align="center">' . $tv;
		$sx .= '</table>';
		return ($sx);
	}

	function jnl_resumo_journals() {
		$sql = "select count(*) as total, jnl_tipo, jtp_descricao
						from brapci_journal 
						inner join brapci_journal_tipo on jtp_codigo = jnl_tipo
						group by jnl_tipo, jtp_descricao, jtp_ordem
						order by jtp_ordem ";
		$rlt = db_query($sql);

		$sz = 0;

		while ($line = db_read($rlt)) {
			$tp = $line['jnl_tipo'];
			$total = $line['total'];
			$th .= '<TH $sz>' . $line['jtp_descricao'] . '</TH>';
			$tv .= '<TD class="lt5">' . $total . '</td>';
			$sz++;
		}

		if ($sz > 0) { $sz = (int)(100 / $sz);
		}
		$th = troca($th, ' $sz', ' width="' . $sz . '%" ');

		$sx .= '<table width="100%" class="resumo">';
		$sx .= '<TR align="center">' . $th;
		$sx .= '<TR align="center">' . $tv;
		$sx .= '</table>';
		return ($sx);
	}

	function mostra_detalhe() {
		//print_r($this);
		$link = '<A HREF="' . $this -> line['jnl_url'] . '" target="_new"><img src="../img/icone_link.png" height="15" border=0 ></A>';

		$sx .= '<table width="100%" class="tabela00">';
		$sx .= '<TR class="lt0">
							<TD width="7%">ISSN</TD>
							<TD width="7%">e-ISSN</TD>
							<TD width="5%">Ano inicial</TD>
							<TD>Periodicidade</TD>
							<TD width="5%">Fasc�culos</TD>
							<TD width="5%">Artigos</TD>
							<TD width="4%">Link</TD>
							</TR>';
		$sx .= '<TR class="lt1">
							<TD>' . $this -> issn . '</TD>
							<TD>' . $this -> issn_e . '</TD>
							<TD>' . $this -> line['jnl_ano_inicio'] . '</td>
							<TD>' . $this -> line['peri_nome'] . '</td>
							<TD>-</TD>
							<TD>-</TD>
							<TD>' . $link . '</td>
							</TR>';
		$sx .= '</table>';
		return ($sx);
	}

	function periodicidade_publicaoes() {
		$sql = "select count(*) as total, peri_nome, peri_ordem from " . $this -> tabela . " 
						left join brapci_periodicidade on peri_codigo = jnl_periodicidade
						where jnl_tipo = 'J' 
						and jnl_vinc_vigente = '1'
						group by peri_nome, peri_ordem
						order by peri_ordem, total desc
						";
		$rlt = db_query($sql);

		$sx = '<table width="300" class="tabela00">';
		$to = 0;
		while ($line = db_read($rlt)) {
			$to = $to + $line['total'];
			$sx .= '<tr><TD class="tabela01">' . $line['peri_nome'];
			$sx .= '<TD align="center" class="tabela01">' . $line['total'];
		}
		$sx .= '<tr><TD><I>Total ' . $to;
		$sx .= '</table>';
		return ($sx);
	}

	function periodicidade_publicaoes_lista($status = '') {
		if (strlen($status) > 0) { $wh = "and jnl_vinc_vigente = '$status' ";
		}
		$sql = "select * from " . $this -> tabela . " 
						left join brapci_periodicidade on peri_codigo = jnl_periodicidade
						where jnl_tipo = 'J' 
						$wh
						order by peri_ordem, jnl_nome
						";

		$rlt = db_query($sql);

		$sx = '<table width="600" class="tabela00">';
		$to = 0;
		$xperi = 'x';
		while ($line = db_read($rlt)) {
			$to++;
			$peri = trim($line['peri_nome']);
			if ($xperi != $peri) {
				$sx .= '<tr><TD class="tabela00 lt2" colspan=3><B>' . $line['peri_nome'] . '</B></TD></TR>';
				$xperi = $peri;
			}
			$sx .= '<TR>';
			$sx .= '<TD width="40">';
			$sx .= '<TD align="left" class="tabela01">' . $line['jnl_nome'];
			$sx .= '<TD>' . ShowLink($line['jnl_url'], '1', 'NewSite', trim($line['jnl_nome']));
		}
		$sx .= '<tr><TD><I>Total ' . $to;
		$sx .= '</table>';
		return ($sx);
	}

	function row() {
		global $cdf, $cdm, $masc;
		$cdf = array('id_jnl', 'jnl_nome', 'jnl_issn_impresso');
		$cdm = array('C�digo', 'Nome', 'ISSN');
		$masc = array('', '', '');
		return (1);
	}

	function recupera_journal_pelo_path($path) {
		$sql = "select * from brapci_journal where jnl_patch = '" . $path . "' ";
		$rlt = db_query($sql);

		if ($line = db_read($rlt)) {
			return ( array($line['id_jnl'], 'J'));
		}

		$sql = "select * from brapci_edition where ed_patch = '" . $path . "' ";
		$rlt = db_query($sql);

		if ($line = db_read($rlt)) {
			return ( array($line['id_jnl'], 'I'));
		}
		return ( array(0, ''));

	}

	function structure() {
		$sql = "ALTER TABLE brapci_journal ADD jnl_patch CHAR(20) NOT NULL ; ";
		$rlt = db_query($sql);

		$sql = "ALTER TABLE brapci_edition ADD ed_path CHAR(20) NOT NULL ;";
		$rlt = db_query($sql);
		return (0);
	}

	function mostra() {
		$sx .= '<h1>' . $this -> journal_name . '</h1>';
		return ($sx);
	}

	function mostra_issue_menu() {

	}

	function le($id) {
		$sql = "select * from " . $this -> tabela . " 
						left join brapci_periodicidade on jnl_periodicidade = peri_codigo
						where id_jnl = " . round($id);
		$rlt = db_query($sql);
		if ($line = db_read($rlt)) {
			$this -> codigo = trim($line['jnl_codigo']);
			$this -> journal_name = trim($line['jnl_nome']);
			$this -> abrev = trim($line['jnl_nome_abrev']);
			$this -> issn = trim($line['jnl_issn_impresso']);
			$this -> issn_e = trim($line['jnl_issn_eletronico']);
			$this -> ano_inicial = trim($line['jnl_ano_inicio']);
			$this -> ano_final = trim($line['jnl_ano_final']);
			if ($this -> ano_final == 0) { $this -> ano_final = '';
			}
			$this -> url = trim($line['jnl_url']);
			$this -> artigos = trim($line['jnl_artigos_indexados']);
			$this -> tipo = trim($line['jnl_tipo']);
			$this -> status = trim($line['jnl_status']);
			$this -> periodicidade = trim($line['jnl_periodicidade']);
			$this -> periodicidade_nome = trim($line['peri_nome']);
			$sta = trim($line['jnl_status']);

			if ($sta == 'A') { $sta = msg('current');
			}
			if ($sta == 'B') { $sta = msg('closed');
			}
			if ($sta == 'X') { $sta = msg('cancel');
			}
		}
	}

	function list_journals($tipo = 'J', $sta = '', $linkr = '', $target = '') {
		if (strlen($linkr) == 0) {
			$linkd = 'journal_mostra.php';
		} else {
			$linkd = $linkr;
		}
		if (strlen($tipo) > 0) { $wh = " and jnl_tipo = '$tipo' ";
		}
		$sql = "select * from " . $this -> tabela . " 
							where jnl_status <> 'X'
				";
		if (strlen($sta) > 0) {
			$sql .= " and jnl_status = '$sta' ";
		}
		$sql .= " order by jnl_tipo desc, jnl_nome ";
		$rlt = db_query($sql);

		/* gera resultados */
		$sx .= '<table class="lt1" width="100%">';
		$sx .= '<TR><TH>' . msg('journal_name');
		$tot = 0;
		while ($line = db_read($rlt)) {
			$tot++;
			$sx .= $this -> mostra_journal_linha($line, $linkd, $target);
		}
		$sx .= '<TR><TD colspan=5><B>' . msg('found') . ' ' . $tot . ' ' . msg('register');
		$sx .= '</table>';

		return ($sx);
	}

	function mostra_journal_linha($line, $link = '', $target) {
		if (strlen($target) > 0) { $target = ' target="' . $target . '"';
		}

		if (strlen($link) > 0) {
			$linkf = "</A>";
			$link .= '?dd0=' . $line['id_jnl'] . '&dd90=' . checkpost($line['id_jnl']);
			$link = '<A HREF="' . $link . '" ' . $target . ' class="link">';
		} else {
			$linkf = '';
		}
		$sx .= '<TR>';
		$sx .= '<TD colspan=2 class="border_top_1">';
		$sx .= $link;
		$sx .= trim($line['jnl_nome']);
		$sx .= $linkf;

		$sx .= '<TR class="lt0">';
		$sx .= '<TD align="left">';
		$sx .= trim($line['jnl_issn_impresso']);

		$sta = trim($line['jnl_status']);
		if ($sta == 'A') { $sta = msg('current');
		}
		if ($sta == 'B') { $sta = msg('closed');
		}

		$sx .= '<TD align="right">';
		$sx .= $sta;

		return ($sx);
		//		}

		$this -> status_nome = $sta;
		$this -> line = $line;
	}

	function jourmal_legenda() {
		$sx .= $this -> journal_name;
		return ($sx);
	}

	function journals_mostra() {
		$sx .= '<fieldset><legend style="margin-left: 10px; padding-left:10px; padding-right:10px;">' . msg("journal") . '</legend>';
		$sx .= '<table width="99%" class="lt0" cellpadding=0 cellspacing=2 >';
		/* nome */
		$sx .= '<TR><TD class="lt0" colspan=4 >' . msg('journal_name');
		$sx .= '<TR class="lt1"><TD colspan=4><B>' . $this -> journal_name;
		/* issn */
		$sx .= '<TR><TD class="lt0" colspan=1 >' . msg('journal_issn');
		$sx .= '    <TD class="lt0" colspan=1 >' . msg('journal_issne');
		$sx .= '    <TD class="lt0" colspan=2 >' . msg('journal_abrev');
		$sx .= '<TR class="lt1" colspan=1 >	<TD><B>' . $this -> issn;
		$sx .= '						 	<TD><B>' . $this -> issn_e;
		$sx .= '						 	<TD colspan=2><B>' . $this -> abrev;

		$sx .= '<TR><TD class="lt0" colspan=1 >' . msg('journal_start');
		$sx .= '    <TD class="lt0" colspan=1 >' . msg('journal_closed');
		$sx .= '    <TD class="lt0" colspan=1 >' . msg('periodicity');
		$sx .= '    <TD class="lt0" colspan=1 >' . msg('status');

		$sx .= '<TR class="lt1" colspan=1 >	<TD><B>' . $this -> ano_inicial;
		$sx .= '						 	<TD><B>' . $this -> ano_final;
		$sx .= '						 	<TD><B>' . $this -> periodicidade_nome;
		$sx .= '						 	<TD><B>' . $this -> status_nome;

		$sx .= '</table>';
		$sx .= '</fieldset>';
		return ($sx);
	}

}
