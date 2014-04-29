<?php
class journals
	{
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
		
		function row()
			{
				global $cdf, $cdm, $masc;
				$cdf = array('id_jnl','jnl_nome','jnl_issn_impresso');
				$cdm = array('Código','Nome','ISSN');
				$masc = array('','','');
				return(1);				
			}		
		
		function recupera_journal_pelo_path($path)
			{
				$sql = "select * from brapci_journal where jnl_patch = '".$path."' ";
				$rlt = db_query($sql);
				
				if ($line = db_read($rlt))
					{
						return(array($line['id_jnl'],'J'));	
					}

				$sql = "select * from brapci_edition where ed_patch = '".$path."' ";
				$rlt = db_query($sql);
				
				if ($line = db_read($rlt))
					{
						return(array($line['id_jnl'],'I'));	
					}
				return(array(0,''));

			}
		
		
		function structure()
			{
				$sql = "ALTER TABLE brapci_journal ADD jnl_patch CHAR(20) NOT NULL ; ";
				$rlt = db_query($sql);
				
				$sql = "ALTER TABLE brapci_edition ADD ed_path CHAR(20) NOT NULL ;";
				$rlt = db_query($sql);
				return(0);				
			}			
			
			
		
		function mostra()
			{
				$sx .= '<h1>'.$this->journal_name.'</h1>';
				return($sx);
			}
		
		function mostra_issue_menu()
			{
				
			}
		function  le($id)
			{
				$sql = "select * from ".$this->tabela." 
						inner join brapci_periodicidade on jnl_periodicidade = peri_codigo
						where id_jnl = ".round($id);
				$rlt = db_query($sql);
				if ($line = db_read($rlt))
					{
						$this->codigo = trim($line['jnl_codigo']);
						$this->journal_name = trim($line['jnl_nome']);
						$this->abrev = trim($line['jnl_nome_abrev']);
						$this->issn = trim($line['jnl_issn_impresso']);
						$this->issn_e = trim($line['jnl_issn_eletronico']);
						$this->ano_inicial = trim($line['jnl_ano_inicio']);
						$this->ano_final = trim($line['jnl_ano_final']);
						if ($this->ano_final==0) { $this->ano_final = ''; }
						$this->url = trim($line['jnl_url']);
						$this->artigos = trim($line['jnl_artigos_indexados']);
						$this->tipo = trim($line['jnl_tipo']);
						$this->status = trim($line['jnl_status']);
						$this->periodicidade = trim($line['jnl_periodicidade']);
						$this->periodicidade_nome = trim($line['peri_nome']);
						$sta = trim($line['jnl_status']);
						
						if ($sta=='A') { $sta = msg('current'); }
						if ($sta=='B') { $sta = msg('closed'); }
						if ($sta=='X') { $sta = msg('cancel'); }
						$this->status_nome = $sta;
						$this->line = $line;	
					}
			}
		function jourmal_legenda()
			{
				$sx .= $this->journal_name;
				return($sx);
			}
		function journals_mostra()
			{
				$sx .= '<fieldset><legend>'.msg("journal").'</legend>';
					$sx .= '<table width="99%" class="lt0" cellpadding=0 cellspacing=2 >';
					/* nome */
					$sx .= '<TR><TD class="lt0" colspan=4 >'.msg('journal_name');
					$sx .= '<TR class="lt1"><TD colspan=4><B>'.$this->journal_name;
					/* issn */
					$sx .= '<TR><TD class="lt0" colspan=1 >'.msg('journal_issn');
					$sx .= '    <TD class="lt0" colspan=1 >'.msg('journal_issne');
					$sx .= '    <TD class="lt0" colspan=2 >'.msg('journal_abrev');
					$sx .= '<TR class="lt1" colspan=1 >	<TD><B>'.$this->issn;
					$sx .= '						 	<TD><B>'.$this->issn_e;
					$sx .= '						 	<TD colspan=2><B>'.$this->abrev;						
					
					$sx .= '<TR><TD class="lt0" colspan=1 >'.msg('journal_start');
					$sx .= '    <TD class="lt0" colspan=1 >'.msg('journal_closed');
					$sx .= '    <TD class="lt0" colspan=1 >'.msg('periodicity');
					$sx .= '    <TD class="lt0" colspan=1 >'.msg('status');

					$sx .= '<TR class="lt1" colspan=1 >	<TD><B>'.$this->ano_inicial;
					$sx .= '						 	<TD><B>'.$this->ano_final;
					$sx .= '						 	<TD><B>'.$this->periodicidade_nome;
					$sx .= '						 	<TD><B>'.$this->status_nome;
					
					$sx .= '</table>';
				$sx .= '</fiedlset>';
				return($sx);
			}
		function list_journals($sta)
			{
				$sql = "select * from ".$this->tabela." where jnl_tipo = 'J' and jnl_status = '$sta'
						order by jnl_nome
				";
				$rlt = db_query($sql);
				
				/* gera resultados */
				$sx .= '<table class="lt1" width="100%">';
				$sx .= '<TR><TH>'.msg('journal_name');
				$sx .= '    <TH>'.msg('journal_issn');
				$sx .= '    <TH>'.msg('journal_status');
				$tot = 0;
				while ($line = db_read($rlt))
					{
						$tot++;
						$sx .= $this->mostra_journal_linha($line,'journal_mostra.php');
					}
				$sx .= '<TR><TD colspan=5><B>'.msg('found').' '.$tot.' '.msg('register');
				$sx .= '</table>';
				
				return($sx);
			}
		function mostra_journal_linha($line,$link='')
			{
				if (strlen($link) > 0)
					{
						$linkf = "</A>";
						$link .= '?dd0='.$line['id_jnl'].'&dd90='.checkpost($line['id_jnl']);
						$link = '<A HREF="'.$link.'">';
					} else {
						$linkf='';
					}
				$sx .= '<TR '.coluna().'>';
				$sx .= '<TD>';
				$sx .= $link;
				$sx .= trim($line['jnl_nome']);
				$sx .= $linkf;

				$sx .= '<TD align="center">';
				$sx .= $link;
				$sx .= trim($line['jnl_issn_impresso']);
				$sx .= $linkf;

				$sta = trim($line['jnl_status']);
				if ($sta=='A') { $sta = msg('current'); }
				if ($sta=='B') { $sta = msg('closed'); }

				$sx .= '<TD align="center">';
				$sx .= $link;
				$sx .= $sta;
				$sx .= $linkf;

				return($sx);
			}
	}

	
	