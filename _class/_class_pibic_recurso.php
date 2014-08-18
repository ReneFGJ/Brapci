<?php
class recurso {
	var $tabela = "pibic_recurso";
	var $line;
	
	function lista_recursos($status='',$edital='')
		{
			$sql = "select * from ".$this->tabela." where rec_status = '".$status."' ";
			$rlt = db_query($sql);
			
			$sx = '<table class="tabela00" width="100%">';
			while ($line = db_read($rlt))
				{
					$link = '<A HREF="recurso_ver.php?dd0='.$line['id_rec'].'">';
					$sx .= '<TR>';
					$sx .= '<TD>'.$link.strzero($line['id_rec'],5).'/'.substr($line['rec_data'],0,4).'</a>';
					$sx .= '<TD>';
					$sx .= $line['rec_status'];
					$sx .= '<TD>'.$link.$line['rec_protocolo'].'</a>';
					$sx .= '<TD>'.$line['rec_titulo'];					
					$sx .= '<TD>'.$line['rec_tipo'];
				}
			$sx .= '</table>';
			return($sx);
			
		}
	function resumo($edital='')
		{
			$n1 = 0;
			$n2 = 0;
			$n3 = 0;
			$n4 = 0;
			
			$sql = "select rec_status, count(*) as total from ".$this->tabela."
						left join pibic_submit_documento on rec_protocolo = doc_protocolo
						  
						group by rec_status
				";
			$rlt = db_query($sql);
			
			while ($line = db_read($rlt))
				{
					$sta = $line['rec_status'];
					switch ($sta)
						{
						case '@': $n1 = $line['total']; break;
						case 'A': $n2 = $line['total']; break;
						case 'B': $n3 = $line['total']; break;
						case 'C': $n4 = $line['total']; break;
						}
				}
			$link1 = '<A HREF="recursos_lista_tipo.php?dd1=@&dd2='.$edital.'">';
			$sx = '<table class="tabela00" width="100%">';
			$sx .= '<TR class="lt0" align="center"><TD width="25%">Em análise<TD width="25%">Concluídas
								<TD width="25%">Comunicadas<TD width="25%">Total';
			$sx .= '<TR class="lt4" align="center">';
			$sx .= '<TD class="tabela01">'.$link1.$n1.'</A>';
			$sx .= '<TD class="tabela01">'.$n2;
			$sx .= '<TD class="tabela01">'.$n3;
			$sx .= '<TD class="tabela01">'.($n1+$n2+$n3+$n4);			
			$sx .= '</table>';
			return($sx);
		}
	function le($id)
		{
			$sql = "select * 
					from ".$this->tabela." 
					left join pibic_submit_documento on rec_protocolo = doc_protocolo
					left join pibic_projetos on doc_protocolo_mae = pj_codigo
					where id_rec = ".round($id);
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					$this->protocolo = $line['doc_protocolo'];
					$this->protocolo_mae = $line['doc_protocolo_mae'];
					$this->line = $line;
				}
		}
	function mostra()
		{
			$sta = $line['rec_status'];
			switch ($sta)
				{
				case '@':
					$sx .= '<font color="green">Em análise</A>';
					break;
				case 'A':
					$sx .= '<font color="green">Finalizado</A>';
					break;
				}
			$sx .= '<HR><B>Descrição da reconsideração</B><BR>';
			$sx .= mst($this->line['rec_justificativa']);
			$sx .= '<HR>';
			$sx .= $line['rec_solucao'];
			return($sx);
		}
	function row() {
		global $cdf, $cdm, $masc;
		
		$cdf = array('id_rec', 'id_rec','rec_protocolo','rec_titulo', 'rec_data', 'rec_tipo','rec_status','rec_avaliador');
		$cdm = array('cod','ID', msg('protocolo'), msg('titulo'), msg('data'), msg('tipo'),'status','avaliador');
		$masc = array('', '', '', '', '', '', '');
		return (1);
	}
	function cp_resultado()
		{
			global $dd;
			//$sql = "alter table pibic_recurso add column rec_avaliador char(8)";
			//$rlt = db_query($sql);
			
			$cp = array();
			$dd[1] = date("Ymd");
			$dd[2] = date("H:i");
			array_push($cp, array('$H8', 'id_rec', '', False, True));
			array_push($cp, array('$H1', 'rec_data_sol', '', True, True));
			array_push($cp, array('$H1', 'rec_hora_sol', '', True, True));
			array_push($cp, array('$M1', '', 'Deliberação', False, True));			
			array_push($cp, array('$T80:7', 'rec_solucao', '', True, True));
			array_push($cp, array('$O @:Enviado&A:Em análise&I:Indeferido&D:Deferido&1:Encaminhar para novo avaliador&2:Anular avaliçoes e indicar novos avaliadores&X:Cancelado', 'rec_status', 'Status', False, True));
			array_push($cp, array('$Q pp_nome:pp_cracha:select * from pibic_professor where pp_comite = 2 order by pp_nome', 'rec_avaliador', '', False, True));
			return($cp);
			
		}
	
	function cp() {
		global $dd;
		$dd[3]='@';
		$dd[4]='';
		$dd[5]='SUB';
		$dd[6]=date("Ymd");
		$dd[8]='Recurso de avaliação de submissão';
		//$this->structure();
		$cp = array();
		array_push($cp, array('$H8', 'id_rec', '', False, True));
		array_push($cp, array('$H8', 'rec_protocolo', '', True, True));
		array_push($cp, array('$M', '', 'Informe a justificativa', False, False));
		array_push($cp, array('$H1', 'rec_status', '@', True, True));
		array_push($cp, array('$H1', 'rec_solucao', '', False, True));
		array_push($cp, array('$H1', 'rec_tipo', '', True, True));
		array_push($cp, array('$H1', 'rec_data', '', True, True));
		array_push($cp, array('$T80:10', 'rec_justificativa', '', True, True));
		array_push($cp, array('$H1', 'rec_titulo', 'Recurso de avaliação de submissão', True, True));

		return ($cp);
	}
	function structure()
		{
			$sql = "drop table ".$this->tabela;
			//$rlt = db_query($sql);
			
			$sql = "create table ".$this->tabela."
				(
				id_rec serial not null,
				rec_protocolo char(7),
				rec_titulo char(100),
				rec_justificativa text,
				rec_status char(1),
				rec_solucao text,
				rec_tipo char(3),
				rec_data integer,
				rec_hora char(5),
				rec_data_sol integer,
				rec_hora_sol char(5),
				rec_login char(20),
				rec_professor char(8),
				rec_area char(15),
				rec_comunicado char(1),
				)
			 ";
			 $rlt = db_query($sql);
			
		}

}
?>
