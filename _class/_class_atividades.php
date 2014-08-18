<?php
class atividades
	{
	var $docente;
	var $discente;
	
	var $tabela = 'atividade';
	
	function total_artigos_validar($professor='')
		{
			global $art;
			$art = new artigo;
			
			$sql = "select * from artigo
					inner join 
						( 
						select pdce_programa, pdce_docente from programa_pos_docentes group by pdce_programa, pdce_docente
						) as tabela on ar_professor = pdce_docente
					left join programa_pos on pdce_programa = pos_codigo
					left join pibic_professor on pp_cracha = ar_professor
					where pos_coordenador = '$professor'
					and ar_status = 10
			 ";
			$rlt = db_query($sql);
			$id = 0;
			$sx = '<table>';
			while ($line = db_read($rlt))
				{
					$id++;
					$sx .= $art->mostra_artigo_row($line);
					$ln = $line;					
				}
			$sx = '</table>';				
			return($id);
		}	
	
	function total_captacoes_validar($professor='')
		{
			global $cap;
			$cap = new captacao;
			
			$sql = "select * from captacao
					inner join 
						( 
						select pdce_programa, pdce_docente from programa_pos_docentes group by pdce_programa, pdce_docente
						) as tabela on ca_professor = pdce_docente
					left join programa_pos on pdce_programa = pos_codigo
					left join pibic_professor on pp_cracha = ca_professor
					where pos_coordenador = '$professor'
					and ca_status = 10
			 ";
			$rlt = db_query($sql);
			$id = 0;
			$sx = '<table>';
			while ($line = db_read($rlt))
				{
					$id++;
					$sx .= $cap->mostra_captacao_row($line);
					$ln = $line;					
				}
			$sx = '</table>';				
			return($id);
		}
	
	function total_isencoes($professor='')
		{
			$this->remover_atividade_professor('ise',$professor);
			
			$sql = "
				select * from bonificacao
				inner join captacao on bn_original_protocolo = ca_protocolo
				where bn_professor = '$professor'
				and bn_original_tipo = 'IPR' ";
			$sql .= " and bn_status = '!' ";
			$rlt = db_query($sql);
			$total = 0;			
			while ($line = db_read($rlt))
			{	
				$professor = $line['bn_professor'];
				$discente = '';				 
				$total++;
				$protocolo=$line['bn_original_protocolo'];
				$limite = 20990101;
				$this->inserir_atividade('ise','Isenção de Alunos / Ato. Normativo 001/2012 - '.$protocolo,$professor,$discente,$limite,$protocolo);	
			}			
			return($total);
		}
	function remover_atividade_professor($tipo='',$professor)
		{
			$proto = trim($proto);
			$tipo = trim($tipo);
			$sql = "delete from ".$this->tabela." 
					where act_docente = '$professor'					
					";
			if (strlen($tipo) > 0)
				{
					$sql .= " and act_codigo = '$tipo' ";
				}
			$rlt = db_query($sql);
			return(0);
			
		}
	function remover_artividade($proto,$tipo)
		{
			$proto = trim($proto);
			$tipo = trim($tipo);
			$sql = "delete from ".$this->tabela." 
					where act_protocolo = '$proto'
					and act_codigo = '$tipo'
					";
			$rlt = db_query($sql);
			return(0);
			
		}
	function remover_atividade_tipo($tipo)
		{
			$proto = trim($proto);
			$tipo = trim($tipo);
			$sql = "delete from ".$this->tabela." 
					where act_codigo = '$tipo'
					";
			$rlt = db_query($sql);
			return(0);
			
		}		
	function tipos($tp='')
		{
			$ar = '(???)'.$tp;
			if ($tp=='rr') { $ar = 'SEMIC - IC'; }
			if ($tp=='ic') { $ar = 'Iniciação Científica'; }
			if ($tp=='pq') { $ar = 'Diretoria de Pesquisa'; }
			if ($tp=='is') { $ar = 'Diretoria de Pesquisa'; }
			return($ar);
		}
	function total_atividades($codigo='')
		{			
			if (strlen($codigo)==0) { return(''); }
			$sql = "select count(*) as total from ".$this->tabela." 
					where act_docente = '$codigo'
					or act_discente = 'codigo' ";;
			// $sql .= " and act_status = '@' ";
			$rlt = db_query($sql);
			$line = db_read($rlt);
			$total = $line['total'];
			return($total);	
		}
	function total_atividades_reconsideracao($codigo='')
		{
			$sql = "select * from pibic_recurso where rec_avaliador = '".$codigo."' and rec_status = '@' ";
			$rlt = db_query($sql);
			$to = 0;
			$sx = '';
			while ($line = db_read($rlt))
				{
					$sx .= '<TR>';
					$sx .= '<TD width="50">'.strzero($line['id_rec'],7);
					$sx .= '<TD>'.$line['rec_titulo'];
					$sx .= '<TD align="right">';
					$sx .= '<TD><form method="post" action="pibic_gestor/recurso_lista.php">';
					$sx .= '<input type="submit" value="verificar >>>" class="botao_submit">';
					$to++;
				}
			$this->sx = $sx;
			return($to);
		}
	function total_atividades_isencao($professor='')
		{
			$sql = "
				select * from bonificacao
				inner join captacao on bn_original_protocolo = ca_protocolo
				where bn_professor = '$professor'
				and bn_original_tipo = 'IPR' ";
			$sql .= " and bn_status = '!' ";
			$rlt = db_query($sql);
			
			while ($line = db_read($rlt))
				{
					$chk = checkpost($professor.$line['id_bn'].'ISE');
					$link = '<A HREF="atividade_detalhes.php?dd1=ISE&dd2='.$line['id_bn'].'&dd5='.$professor.'&dd90='.$chk.'">';
					$sx .= '<TR>';
					$sx .= '<TD> Isenção de estudante Pós-Graduação (strico sensu)';
					$sx .= '<TD>';
					$sx .= $link;
					$sx .= '<span class="botao-geral">Indicar Insenção >></span>';
					$sx .= '</A>';	
				}
			$this->sx = $sx;
			return($sx);
		}
		
	function lista_atividades($codigo='')
		{
			global $tab_max;
			$sx .= '<table width="100%" class="tabela00">';
					
			$this->total_atividades_reconsideracao($codigo);
			$sx .= $this->sx;
			
			/* Isenções */
			$this->total_atividades_isencao($codigo);
			$sx .= $this->sx;
			
			$sx .= '</table>';			
			return($sx);
		}
	function inserir_atividade($cod='',$descricao='',$docente='',$discente='',$limite=19000101,$protocolo)
		{
			$data = date("Ymd");
			if (strlen($cod) == 3)
			{
			$sql = "select * from ".$this->tabela."
				where act_codigo = '$cod' and act_docente = '$docente' 
						and act_discente = '$discente' 
						and act_protocolo = '$protocolo'";
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
			{
				$id = $line['id_act'];
				$sql = "update ".$this->tabela."
						set act_limite = $limite,
						act_status = '@',
						act_data = $data
						where id_act = $id
						";
				$rlt = db_query($sql);
			} else {
				
				$sql = "insert into ".$this->tabela." 
					(act_descricao, act_codigo, act_limite,
					act_data, act_ativo, act_status,
					act_docente, act_discente, act_protocolo
					) values (
					'$descricao','$cod',$limite,
					$data,1,'@',
					'$docente','$discente','$protocolo'
					)					
				";			
				$rlt = db_query($sql);
				}
			}		
		}
	function strucuture()
		{
			$sql = "CREATE TABLE atividade
					(
					id_act serial not null,
					act_descricao char(60),
					act_codigo char(5),
					act_limite int,
					act_data int,
					act_ativo int,
					act_status char(1),
					act_docente char(8),
					act_discente char(8)
					)
			";
			$rlt = db_query($sql);
		}
	}
?>
