<?php
class declaracao_ic
	{
	var $ano = '2014';
	var $tabela = "submit_parecer_2013";
	var $tabela_pibic = 'pibic_parecer_2014';
	
	var $secu = 'decla2014ic';
	
	function listar_declaracoes_disponiveis($avaliador='')
		{
			$sql = "select * from ".$this->tabela_pibic." 
						
						where pp_avaliador = '$avaliador'
							and (pp_status = 'B' or pp_status = 'C')
							and (pp_tipo = 'SUBMI') and (substr(pp_protocolo,1,1) = '1')
					order by pp_parecer_data desc, pp_parecer_hora desc
			";
			//left join journals on pp_journal = id_journal_id
			//inner join journals on doc_journal_id = jnl_codigo
			$rlt = db_query($sql);
			$sx = '<table width="100%" align="center">';
			$sx .= '<TR><TH align="center" width="10%">Tipo
						<TH align="center" width="10%">Protocolo
						<TH align="center" width="20%">Estatus
						<TH align="center" width="25%">Data e hora da avaliação
						<TH align="center" width="25%">Situação';
			$id = 0;
			while ($line = db_read($rlt))
				{
					$id++;
					$sx .= '<TR>';
					$sx .= '<TD class="tabela01" align="center">';
					$sx .= trim($line['pp_tipo']);
					$sx .= '<TD class="tabela01" align="center">';
					$sx .= trim($line['pp_protocolo']);
					$sx .= '<TD class="tabela01" align="center">';
					$sx .= $this->mostra_status(trim($line['pp_status']));
					$sx .= '<TD class="tabela01" align="center">';
					$sx .= stodbr($line['pp_parecer_data']);
					$sx .= ' ';
					$sx .= trim($line['pp_parecer_hora']);
					$sx .= '<TD class="tabela01" align="center">';
					$sx .= $this->link_declaracao($line);
				}
			if ($id==0)
				{
					$sx .= '<TR><TD colspan=10>Nenuma avalição disponível';
				}
			$sx .= '</table>';
			return($sx);
		}
	function link_declaracao($line)
		{
			$sta = $line['pp_status'];
			if ($sta == 'B') { $sta = 'C'; }
			switch ($sta)
				{
				case 'B':
					$sx = '
						<font style="background-color: #FFC0C0; padding: 0px 15px 0px 15px;"
						<B>Indisponível, aguardando liberação</B>
						</font>
					';
					break;
				case 'C':
					$proto = trim($line['pp_avaliador']);
					$secu = $this->secu;
					$link = '<a href="'.$http.'/reol/pibicpr/declaracao_emitir_ic.php?dd0='.$proto.'&dd1='.$this->tabela.'&dd90='.checkpost($proto.$secu).'" target="_new'.date("s").'">';
					$sx = $link;
					$sx .= '
						<font style="background-color: #C0FFC0; padding: 0px 15px 0px 15px;"
						<B>Liberado para impressão</B>
						</font>
					';
					$sx .= '</A>';
					break;
				}
			return($sx);
		}
	function mostra_status($sta)
		{
			switch ($sta)
				{
				case '@': return("em avaliação"); break;
				case 'B': return("avaliador"); break;
				case 'C': return("avaliador"); break;
				case 'D': return("declinado"); break;
				}
			return("???");
		}
	}
?>
