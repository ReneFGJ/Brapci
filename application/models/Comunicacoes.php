<?php
class comunicacoes extends CI_Model
	{
	var $tabela_grupo = 'mensagem_grupo';
	var $tabela_status = 'mensagem_status';
	var $tabela = 'mensagem_comunicacao';
	
	function cp()
		{
			$cp = array();
			$sql_grupo = 'id_mg:mg_nome:select * from mensagem_grupo where mg_ativo = 1 and mg_grupo <> 0';
			$sql_own = 'id_m:m_descricao:select * from mensagem_own where m_ativo = 1 ';
			array_push($cp,array('$H8','id_mc','',False,True));
			array_push($cp,array('$Q '.$sql_grupo,'mc_tipo',msg('grupo'),True,True));
			array_push($cp,array('$S40','mc_titulo',msg('titulo'),False,True));
			array_push($cp,array('$T80:13','mc_texto',msg('texto'),False,True));
			array_push($cp,array('$H8','mc_enviado','',False,True));
			array_push($cp,array('$O HTML:HTML&TEXT:TEXT','mc_formato',msg('formato'),False,True));
			array_push($cp,array('$U8','mc_dt','',False,True));
			array_push($cp,array('$Q '.$sql_own,'mc_own','Enviador',False,True));
			array_push($cp,array('$H8','mc_dt_envio','',False,True));
			array_push($cp,array('$HV','mc_status_mgs_id',1,False,True));
			array_push($cp,array('$B','',msg('enviar'),false,True));			
			return($cp);
		}	
	
	function tabela_view()
		{
			$tabela = "(
							select * from ".$this->tabela."
							left join ".$this->tabela_status." on mc_status_mgs_id = id_mgs
						) as tabela 
			";
			return($tabela);
		}
	function le($id=0)
		{
			$sql = "select * from ".$this->tabela."
						left join mensagem_own on mc_own = id_m
						where id_mc = ".$id;
			$rlt = $this->db->query($sql);
			$rlt = $rlt->result_array();
			if (count($rlt) == 0)
				{
					return(array());
				}
			$line = $rlt[0];
			return($line);
		}
	function row($obj) {
		$obj -> fd = array('id_mc', 'mc_titulo','mc_dt','mgs_nome');
		$obj -> lb = array('ID', 'Nome','Data','status');
		$obj -> mk = array('', 'L','C','C');
		return ($obj);
	}	
	
	function row_grupo($obj) {
		$obj -> fd = array('id_mg', 'mg_nome');
		$obj -> lb = array('ID', 'Nome');
		$obj -> mk = array('', 'L');
		return ($obj);
	}
	
	function cp_grupo()
		{
			$cp = array();
			array_push($cp,array('$H8','id_ng','',False,True));
			array_push($cp,array('$S200','mg_nome',msg('mg_nome'),True,True));
			array_push($cp,array('$O 1:SIM&0:N�O','mg_ativo',msg('mg_ativo'),True,True));
			
			array_push($cp,array('$B','',msg('enviar'),false,True));
			
			return($cp);
		}
	
	function le_grupo($id = 0)
		{
			$sql = "select * from ".$this->tabela_grupo." 
					where id_mg = ".$id;
					
			$rlt = $this->db->query($sql);
			$rlt = $rlt->result_array($rlt);
			$data = $rlt[0];
			
			return($data);
		}
		
	//gera grupo com emails para criar msg de comunica��o	
	function le_email_grupo($gr=0)
		{
			$email = '';
			/* Professores orientadores IC com planos vigente */
			if ($gr == 7)
				{
					$ano = date("Y");
					if (date("m") < 8) { $ano = ($ano -1); }
					$sql = "select distinct usm_email from ( 
							       select distinct id_us as id from ic inner 
							       join ic_aluno on id_ic = ic_id 
							       inner join ic_modalidade_bolsa on id_mb = mb_id 
							       inner join us_usuario on ic_cracha_prof = us_cracha
							       where ic_ano = '$ano'
							       and us_ativo = 1 
							       ) as tabela
							inner join us_email on id = usuario_id_us
							where usm_ativo = 1 ";
					$rlt = $this->db->query($sql);
					$rlt = $rlt->result_array();
					$email = '';
					for ($r=0;$r < count($rlt);$r++)
						{
							$line = $rlt[$r];
							$email .= $line['usm_email'].cr();
						}
						$email .= 'cleybe.vieira@pucpr.br'.cr();
				}
			/* Professores orientadores IC que n�o entregaram relat�rio de acompanhamento */
			if ($gr == 9)
				{
					$ano = date("Y");
					if (date("m") < 8) { $ano = ($ano -1); }
					$sql = "select distinct usm_email from ( 
							       select distinct id_us as id from ic inner 
							       join ic_aluno on id_ic = ic_id 
							       inner join ic_modalidade_bolsa on id_mb = mb_id 
							       inner join us_usuario on ic_cracha_prof = us_cracha
							       where ic_ano = '$ano'
							       and us_ativo = 1 and ic_pre_data = '0000-00-00'
							       ) as tabela
							inner join us_email on id = usuario_id_us
							where usm_ativo = 1 ";
					$rlt = $this->db->query($sql);
					$rlt = $rlt->result_array();
					$email = '';
					for ($r=0;$r < count($rlt);$r++)
						{
							$line = $rlt[$r];
							$email .= $line['usm_email'].cr();
						}
						$email .= 'cleybe.vieira@pucpr.br'.cr();
				}
			/* Professores orientadores IC que n�o entregaram relat�rio rarcial */
			if ($gr == 12)
				{
					$ano = date("Y");
					if (date("m") < 8) { $ano = ($ano -1); }
					$sql = "select distinct usm_email from ( 
							       select distinct id_us as id from ic inner 
							       join ic_aluno on id_ic = ic_id 
							       inner join ic_modalidade_bolsa on id_mb = mb_id 
							       inner join us_usuario on ic_cracha_prof = us_cracha
							       where ic_ano = '$ano'
							       and us_ativo = 1 and (ic_rp_data <= '2000-01-01')
							       and (icas_id = 1) and (s_id = 1)
							       ) as tabela
							inner join us_email on id = usuario_id_us
							where usm_ativo = 1 ";
					$rlt = $this->db->query($sql);
					$rlt = $rlt->result_array();
					$email = '';
					for ($r=0;$r < count($rlt);$r++)
						{
							$line = $rlt[$r];
							$email .= $line['usm_email'].cr();
						}
						$email .= 'cleybe.vieira@pucpr.br'.cr();
				}

			//* IC - Avaliadores que n�o avaliaram o Relat�rio Parcial (RPAR) */
			if ($gr == 14)
				{
					$ano = date("Y");
					if (date("m") < 8) { $ano = ($ano -1); }
					$sql = "select distinct usm_email 
									from pibic_parecer_2016
									inner join us_email on pp_avaliador_id = usuario_id_us
									where usm_ativo = 1
									and pp_tipo = 'RPAR'
									AND pp_status = 'A'
					";
					$rlt = $this->db->query($sql);
					$rlt = $rlt->result_array();
					$email = '';
					for ($r=0;$r < count($rlt);$r++)
						{
							$line = $rlt[$r];
							$email .= $line['usm_email'].cr();
						}
						$email .= 'cleybe.vieira@pucpr.br'.cr();
				}
	
			//* IC - Avaliadores que n�o reavaliaram o Relat�rio Parcial (RPRC) */
			if ($gr == 17)
				{
					$ano = date("Y");
					if (date("m") < 8) { $ano = ($ano -1); }
					$sql = "select distinct usm_email 
									from pibic_parecer_2016
									inner join us_email on pp_avaliador_id = usuario_id_us
									where usm_ativo = 1
									and pp_tipo = 'RPRC'
									AND pp_status = 'A'
					";
					$rlt = $this->db->query($sql);
					$rlt = $rlt->result_array();
					$email = '';
					for ($r=0;$r < count($rlt);$r++)
						{
							$line = $rlt[$r];
							$email .= $line['usm_email'].cr();
						}
						$email .= 'cleybe.vieira@pucpr.br'.cr();
				}				
				
			//* IC - Orientadores que n�o postaram a corre��o Relat�rio Parcial (RPRC) */
			if ($gr == 18)
				{
					$ano = date("Y");
					if (date("m") < 8) { $ano = ($ano -1); }
					$sql = "SELECT DISTINCT usm_email
									FROM us_usuario
									INNER JOIN ic ON us_cracha = ic_cracha_prof
									INNER JOIN us_email on id_us = usuario_id_us
									WHERE usm_ativo = 1
									AND s_id = 1 
									AND ic_rpc_data = '0000-00-00'
									AND ic_nota_rp = 2
									and ic_ano = '2015'
								 ";
					$rlt = $this->db->query($sql);
					$rlt = $rlt->result_array();
					$email = '';
					for ($r=0;$r < count($rlt);$r++)
						{
							$line = $rlt[$r];
							$email .= $line['usm_email'].cr();
						}
						$email .= 'cleybe.vieira@pucpr.br'.cr();
				}
				
			//* IC - Alunos PIBIC E PIBITI com IC  */
			if ($gr == 20)
				{
					$ano = date("Y");
					if (date("m") < 8) { $ano = ($ano -1); }
					$sql = "SELECT DISTINCT usm_email
									FROM ic_aluno
									INNER JOIN us_usuario ON id_us = aluno_id
									INNER JOIN us_email on id_us = usuario_id_us
									WHERE usm_ativo = 1
									AND icas_id = 1
									ORDER BY usm_email
								 ";
					$rlt = $this->db->query($sql);
					$rlt = $rlt->result_array();
					$email = '';
					for ($r=0;$r < count($rlt);$r++)
						{
							$line = $rlt[$r];
							$email .= $line['usm_email'].cr();
						}
						$email .= 'cleybe.vieira@pucpr.br'.cr();
				}
			/* Professores orientadores IC com planos vigente em 2016 */
			if ($gr == 23){
				$ano = date("Y");
					$sql = "SELECT DISTINCT usm_email
									FROM ic_submissao_plano
									LEFT JOIN ic_submissao_projetos ON doc_protocolo_mae = pj_codigo
									LEFT JOIN us_usuario ON us_cracha = pj_professor
									LEFT JOIN us_email ON usuario_id_us = id_us
									WHERE pj_ano = '$ano'
									AND (pj_status = 'B')
									AND (doc_status = 'B')
									AND usm_ativo = 1
									AND us_ativo = 1
									";
					$rlt = $this->db->query($sql);
					$rlt = $rlt->result_array();
					$email = '';
					for ($r=0;$r < count($rlt);$r++)
						{
							$line = $rlt[$r];
							$email .= $line['usm_email'].cr();
						}
						//$email .= 'cleybe.vieira@pucpr.br'.cr();
						//$email .= 'evandro.cavalheri@pucpr.br'.cr();
				}
			/* Avaliadores Indicador para Avaliar Relat�rio Final 2015/2016 */
			if ($gr == 29){
				$ano = date("Y");
					$sql = "select distinct usm_email 
									from pibic_parecer_2016
									inner join us_email on pp_avaliador_id = usuario_id_us
									left join us_usuario on pp_avaliador_id = id_us
									left join ic on ic_plano_aluno_codigo = pp_protocolo
									where pp_status = 'A'
									and pp_tipo = 'RFIN'
									AND usm_ativo = 1
									AND us_ativo = 1
									group by pp_avaliador_id, usm_email
									order by usm_email
									";
					$rlt = $this->db->query($sql);
					$rlt = $rlt->result_array();
					$email = '';
					for ($r=0;$r < count($rlt);$r++)
						{
							$line = $rlt[$r];
							$email .= $line['usm_email'].cr();
						}
						$email .= 'cleybe.vieira@pucpr.br'.cr();
						$email .= 'evandro.cavalheri@pucpr.br'.cr();
					  $email .= 'elizandro.slima@gmail.com'.cr();
				}					
				
			return($email);
		}
	function form_comunicacao_0()
		{
			$dd1 = $this->input->post('dd1');
			$dd2 = $this->input->post('dd2');
			
			$sql   = "select * from ".$this->tabela_grupo." where mg_ativo = 1 and mg_grupo = 0 order by mg_nome";
			$sql_g = "select * from ".$this->tabela_grupo." where mg_ativo = 1 and mg_grupo = $dd1 order by mg_nome";
			$cp = array();
			array_push($cp,array('$H8','','',False,True));
			
			if (strlen($dd1) > 0)
				{
					array_push($cp,array('$Q id_mg:mg_nome:'.$sql,'mg_nome',msg('mg_nome_grupo'),True,False));
					array_push($cp,array('$Q id_mg:mg_nome:'.$sql_g,'mg_nome',msg('mg_nome_classe'),True,True));		
				} else {
					array_push($cp,array('$Q id_mg:mg_nome:'.$sql,'mg_nome',msg('mg_nome'),True,True));
					array_push($cp,array('$H8','','',True,True));		
				}
			array_push($cp,array('$B8','',msg('avan�ar').' >>>',False,True));
			
			$form = new form;
			$form->tabel = $this->tabela_grupo;
			$tela = $form->editar($cp,'');
			
			if ($form->saved > 0)
				{
					$link = current_url().'/1/'.$dd1.'/'.$dd2;					
					redirect($link);
				}
			return($tela);
			
		}	
	function form_comunicacao_1($gr='',$id)
		{
			$dd1 = $this->input->post('dd1');
			
			$sql1   = "select * from ".$this->tabela_grupo." where id_mg = ".$gr;
			$sql2   = "select * from ".$this->tabela_grupo." where id_mg = ".$id;
			
			$cp = array();
			array_push($cp,array('$H8','','',False,True));
			array_push($cp,array('$HV','mc_grupo',$gr,True,True));
			array_push($cp,array('$HV','mc_tipo',$id,True,True));
			array_push($cp,array('$S200','mc_titulo',msg('email_titulo'),True,True));
			array_push($cp,array('$T80:9','mc_texto',msg('email_corpo'),True,True));
			array_push($cp,array('$O HTML:HTML&TEXT:TEXTO','mc_formato',msg('email_corpo'),True,True));
			array_push($cp,array('$U','mc_dt','',True,True));
			array_push($cp,array('$HV','mc_status','1',True,True));
			array_push($cp,array('$H8','mc_enviado','',False,True));			
			
			array_push($cp,array('$B8','',msg('avan�ar').' >>>',False,True));
			
			$form = new form;
			$form->tabel = $this->tabela;
			$tela = $form->editar($cp,$this->tabela);
			
			if ($form->saved > 0)
				{
					echo 'SAVED';
					exit;
					$link = current_url();
					$link = troca($link,'/1','/2');					
					redirect($link);
				} else {
					echo 'Campos obrigat�rio n�o preenchidos!';
				}
			return($tela);
			
		}
	}
?>
