<?
class submit
	{
	var $id;
	var $title;
	var $journal;
	var $autor;
	var $status;
	var $protocolo;
	var $update;
	var $data;
	var $hora;
	var $area;
	
	var $autor_nome;
	var $autor_email;
	var $autor_email_alt;
	var $author_codigo;
	var $journal;
	var $erro = 0;
		
	var $tabela = "submit_documento";
	
	function comunicar_avaliador()
		{
			global $email_adm, $admin_nome, $pp, $http, $secu;
			
			$avaliador = trim($pp->line['us_nome']);
			$avaliador_email_1 = trim($pp->line['us_email']);
			$avaliador_email_2 = trim($pp->line['us_email_alternativo']);
			
			$ic = new ic;
			$cod = 'JNL_AVA_COM';
			$txt = $ic->ic($cod);
			$titulo_email = trim($txt['nw_titulo']);
			$texto_email = mst($txt['nw_descricao']);	
			
			
			$sql = "select * from journals where jnl_codigo = '".$this->journal."' ";
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					$email_adm = trim($line['jn_email']);
					$admin_nome = trim($line['title']);
					$protocolo = $this->protocolo;	
					
					$chk = substr(md5('pibic'.date("Y").trim($pp->line['us_codigo'])),0,10);
					$link = $http.'avaliador/acesso.php?dd0='.trim($pp->line['us_codigo']).'&dd1=2&dd90='.$chk;
					
					
					$texto_email = troca($texto_email,'$protocolo',$this->protocolo);
					$texto_email = troca($texto_email,'$avaliador',$avaliador);
					$texto_email = troca($texto_email,'$data',date("d/m/Y"));
					$texto_email = troca($texto_email,'$hora',date("H:i"));
					$texto_email = troca($texto_email,'$link',$link);
					$texto_email = troca($texto_email,'$journal',$admin_nome);								
					
					if (strlen($avaliador_email_1) > 0)
						{ enviaremail($avaliador_email_1,'','[REOL] '.$titulo_email.' - '.$protocolo,$texto_email); }
					if (strlen($avaliador_email_2) > 0)
						{ enviaremail($avaliador_email_2,'','[REOL] '.$titulo_email.' - '.$protocolo,$texto_email); }
						
					enviaremail($email_adm,'','[REOL] '.$titulo_email.' - '.$protocolo.' (cópia)',$texto_email);
					echo '<BR>Comunicado Avaliador';
				}			
		}
	
	function comunicar_editor()
		{
			global $email_adm, $admin_nome, $pp;
			$avaliador = trim($pp->line['us_nome']);
			
			$ic = new ic;
			$cod = 'JNL_AVA_END';
			$txt = $ic->ic($cod);
			$titulo_email = trim($txt['nw_titulo']);
			$texto_email = mst($txt['nw_descricao']);
			
			
			$sql = "select * from journals where jnl_codigo = '".$this->journal."' ";
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					$email_adm = trim($line['jn_email']);
					$admin_nome = trim($line['title']);
					$protocolo = $this->protocolo;	
					
					$texto_email = troca($texto_email,'$protocolo',$this->protocolo);
					$texto_email = troca($texto_email,'$avaliador',$avaliador);
					$texto_email = troca($texto_email,'$data',date("d/m/Y"));
					$texto_email = troca($texto_email,'$hora',date("H:i"));
					$texto_email = troca($texto_email,'$journal',$admin_nome);								
					
					enviaremail($email_adm,'','[REOL] '.$titulo_email.' - '.$protocolo,$texto_email);
					echo '<BR>Comunicado Editor';
				}			
		}
	
	function set_journal($id)
		{
			$this->journal = round($id);
		}
	
	function resumo()
		{
			global $http,$perfil;
			$sql = "select count(*) as total, doc_status from ".$this->tabela."
					where doc_journal_id = '".round($this->journal)."'
					or doc_journal_id = '".strzero($this->journal,7)."'
					group by doc_status
					order by doc_status
					";
			$rlt = db_query($sql);
			$op = array(0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);
			while ($line = db_read($rlt))
				{
					$sta = trim($line['doc_status']);
					$tot = $line['total'];
					switch ($sta)
						{
						case '@': $op[0] = $op[0] + $tot; break;
						case 'A': $op[1] = $op[1] + $tot; break;
						case 'B': $op[2] = $op[2] + $tot; break;
						case 'X': $op[3] = $op[3] + $tot; break;
						case 'C': $op[5] = $op[5] + $tot; break;
						case 'O': $op[5] = $op[5] + $tot; break;
						case 'L': $op[6] = $op[6] + $tot; break;
						case 'Z': $op[7] = $op[7] + $tot; break;
						case 'G': $op[8] = $op[8] + $tot; break;
						case 'M': $op[8] = $op[8] + $tot; break;
						case 'N': $op[9] = $op[9] + $tot; break;
						default: $op[4] = $op[4] + $tot; break;
						}
				}
			$wd = round(100/10);
			
			$sx = '<table class="tabela10" width="100%" border=0>';
			$sx .= '<TR><TD colspan=10 ><h2>Resumo das Submissões</h2></TR>';
			$sx .= '<TR align="center">';
			if (user_valid('#ADM')) { $sx .= '<TD width="'.$wd.'%">Em submissão'; }
			$sx .= '<TD width="'.$wd.'%">Submissão efetivadas';
			$sx .= '<TD width="'.$wd.'%">Aguanda envio para parecerista';
			$sx .= '<TD width="'.$wd.'%">Com parecerista';
			$sx .= '<TD width="'.$wd.'%">Com autor<BR>para correções';
			$sx .= '<TD width="'.$wd.'%">Com editor';
			$sx .= '<TD width="'.$wd.'%">Cancelados';
			$sx .= '<TD width="'.$wd.'%">Outros';
			$sx .= '<TD width="'.$wd.'%">Não aprovados';			
			$sx .= '<TD width="'.$wd.'%">Enviados para editoração';
			
			$link = array('','','','','');
			if (user_valid('#ADM')) { $link[0] = '<A HREF="'.$http.'editora/submit_works.php?dd1=@">'; }
			$link[1] = '<A HREF="'.$http.'editora/submit_works.php?dd1=A">';
			$link[2] = '<A HREF="'.$http.'editora/submit_works.php?dd1=B">';
			$link[3] = '<A HREF="'.$http.'editora/submit_works.php?dd1=X">';
			$link[4] = '<A HREF="'.$http.'editora/submit_works.php?dd1=D">';
			$link[5] = '<A HREF="'.$http.'editora/submit_works.php?dd1=C">';
			$link[6] = '<A HREF="'.$http.'editora/submit_works.php?dd1=L">';
			$link[7] = '<A HREF="'.$http.'editora/submit_works.php?dd1=Z">';
			$link[8] = '<A HREF="'.$http.'editora/submit_works.php?dd1=G">';
			$link[9] = '<A HREF="'.$http.'editora/submit_works.php?dd1=N">';
			
			$sx .= '<TR align="center"  style="font-size: 30px;"> ';
			if (user_valid('#ADM')) { $sx .= '<TD width="'.$wd.'%"><B>'.$link[0].'<font color="#c0c0c0">'.$op[0].'</FONT></B>'; }
			$sx .= '<TD width="'.$wd.'%"><B>'.$link[1].'<font color="#000000">'.$op[1].'</B>';
			$sx .= '<TD width="'.$wd.'%"><B>'.$link[2].$op[2].'</B>';
			$sx .= '<TD width="'.$wd.'%"><B>'.$link[5].$op[5].'</B>';
			$sx .= '<TD width="'.$wd.'%"><B>'.$link[6].$op[6].'</B>';
			$sx .= '<TD width="'.$wd.'%"><B>'.$link[8].'<font color="#000000">'.$op[8].'</B>';			
			$sx .= '<TD width="'.$wd.'%"><B>'.$link[3].'<font color="#c0c0c0">'.$op[3].'</B>';			
			$sx .= '<TD width="'.$wd.'%"><B>'.$link[4].$op[4].'</B>';
			$sx .= '<TD width="'.$wd.'%"><B>'.$link[9].$op[9].'</B>';
			$sx .= '<TD width="'.$wd.'%"><B>'.$link[7].$op[7].'</B>';
			$sx .= '</table>';
			return($sx);
		}
	
	function msg($ac)
		{
			switch($ac)
				{
				case 'action_A': $ac = 'Indicar parecerista'; break;
				case 'action_B': $ac = 'Aceitar para avaliação'; break;
				case 'action_@': $ac = 'Devolver para ressubmissão'; break;
				case 'action_C': $ac = 'Indicar novo parecerista'; break;
				case 'action_X': $ac = 'Cancelar submissão'; break;
				case 'action_L': $ac = 'Encaminhar correções (autor)'; break;
				case 'action_G': $ac = 'Aprovar, enviar para editoração'; break;
				case 'action_N': $ac = 'Não aprovar trabalho'; break;
				case 'action_E': $ac = 'Enviar para o editor'; break;
				}
			return($ac);
		}
	
	function show_search_form()
		{
			global $dd,$acao;
			
			$sx .= '<div style="border: 1px solid #202020; padding: 20px; ">';
			$sx .= '<div style="width: 100%">
					FILTRAR PESQUISA
					</div>
					';
			$sx .= '
			<form action="'.page().'" method="get" >
				<div>
				<input type="text" style="width: 80%;" name="dd1" >
				<input type="submit" value="BUSCA" style="width: 15%;" name="acao" >
				</div>
			</form>
			';
			
			$sx .= '</div><BR>';
			return($sx);
		}
	
	/* A - Subnetido
	 * B - Aceito para avaliação
	 * ?
	 * */ 
	function action_execute()
		{
			global $dd,$acao;
			
			$sta = trim($dd[2]);
			switch ($sta)
				{
				case 'B': $this->execute_B(); break;
				case 'C': $this->execute_C(); break;
				case 'D': $this->execute_C(); break;
				case 'E': $this->execute_E(); break;
				case 'F': $this->execute_C(); break;
				case 'G': $this->execute_G(); break;
				case 'H': $this->execute_C(); break;
				case 'N': $this->execute_N(); break;
				case 'X': $this->execute_X(); break;
				case 'L': $this->execute_L(); break;
				}
		}
	function execute_B()
		{
			global $dd,$acao,$hd;
			$sx = '';
			$sx = 'Enviando e-mail de aceit';
			/* insere no hisetorico */
			/* Modifica status */
			
			/* insere no historico */
			$hs = new submit_historico;
			$hs->insere_historico($this->protocolo,'AVA',$avaliador,$hd->user_id);
			/* Modifica status */
			$this->alterar_status('B');
			/* enviar e-mail */
			$complemento = $dd[4];
			if ($dd[3]=='1')
				{
					$proto = $this->protocolo;
					$nome = $this->autor_nome;
					$email = $this->autor_email;
					$editor = $hd->editor;
					$title = $this->title;
					
					$mes = $this->ic("subm_B",1);
					$ms = $mes[1];
					$titulo = $mes[0];
					
					$email = array();
					array_push($email,trim($this->autor_email));
					array_push($email,trim($this->autor_email_alt));
					array_push($email,'renefgj@gmail.com');
					array_push($email,$hd->email);

					
					$ms = troca($ms,'$JOURNAL',$hd->journal_name);
					$ms = troca($ms,'$PROTOCOLO',$proto);
					$ms = troca($ms,'$TITULO',$title);
					$ms = troca($ms,'$EDITOR',$editor);
					
					for ($r=0;$r < count($email);$r++)
						{
						$email_send = $email[$r];
						if (strlen($email_send) > 0)
							{
							enviaremail($email_send,'',$titulo,$ms);
							echo '<BR>enviado '.$email_send;
							}
						}
					
				}
			return($sx);
		}
	function execute_C()
		{
			global $dd, $hd;
			$user = strzero($hd->user_id,7);
			$protocolo = strzero($dd[0],7);
			$sx = '';
			$pp = new parecer;
			
			$hs = new submit_historico;
			$tof = round($dd[9]);
			$av = array();
			$sql = '';
			for ($r=0;$r < $tof;$r++)
				{
					$ps = trim($_POST["ddi".$r]).trim($_GET['ddi'.$r]);
					if (strlen($ps) > 0)
						{
							array_push($av,$ps);
							if (strlen($sql) > 0)
								{ $sql .= ' or '; }
							$sql .= "(us_codigo = '$ps') "; 
						}
				}
			if ($tof > 0)
			{
				$sql = "select * from pareceristas where ".$sql."
						order by us_nome
						";
				$rlt = db_query($sql);
				
				while ($line = db_read($rlt))
					{
						/* Inserir avaliacao */
						$avaliador = trim($line['us_codigo']);
						$tipo = 'AV001';
						$pp->inserir_avaliacao($protocolo, $avaliador,$tipo);
						
						/* insere no historico */
						$hs->insere_historico($protocolo,'AVA',$avaliador,$user);
						
						/* Modifica status */
						$this->alterar_status('C');
						
						/* Enviar e-mail ao responsável */
						$ref = 'subm_C';
						if (strlen($dd[3]) > 0)
							{
							$pp->enviar_email($avaliador,$ref,$protocolo);
							}
					}
			}
			return($sx);
		}
	function execute_G()
		{
			global $dd;
			$issue = $dd[6];
			$sessao = $dd[7];
			$user = strzero($hd->user_id,7);
			$protocolo = $this->protocolo;
			$email = $this->autor_email;
			$sx = '';
			/* Publica artigo no Works */
			$ok = $this->transfere_submissao_para_works($protocolo,$issue,$sessao);
			if ($ok =1)
				{
					$sx .= '<BR><font color="red">Artigo já publicado</font>';
				} else {
					$sx .= '<BR><font color="green">Publicação atualizada</font>';
				}
				
			/* Transfere arquivos */
			$works = $this->recupera_procotolo_works($protocolo);
			$this->transfere_arquivos($protocolo,$works);
			
			/* insere no historico */
			$hs = new submit_historico;
			$hs->insere_historico($protocolo,'APR','',$user);
			
			/* Modifica status */
			$this->alterar_status('Z');

			/* Enviar e-mail ao responsável */
			for ($r=0;$r < count($email);$r++)
				{
				$email_send = $email[$r];
				if (strlen($email_send) > 0)
					{
					enviaremail($email_send,'',$titulo,$ms);
					echo '<BR>enviado '.$email_send;
					}
				}
					
			return($sx);
		}
	function transfere_arquivos_insere_works($line,$para)
		{
			global $hd;
					
			$doc_arquivo = trim($line['pl_filename']);
			$doc_tipo = 'SUB';
			$doc_data = $line['pl_data'];
			$doc_hora = trim($line['pl_hora']).':00';
			$doc_versao = trim($line['pl_versao']);
			$doc_filename = trim($line['pl_texto']);
			$doc_ano = substr($line['pl_data'],0,4);
			$doc_status = 'A';
			$doc_extensao = UpperCaseSql(trim($doc_filename));
			$doc_extensao = substr($doc_extensao,strlen($doc_extensao)-3,3);
			$doc_user = strzero($hd->user_id,7);
			
			
			$uploaddir = $_SERVER['DOCUMENT_ROOT'];
			$uploaddir .= '/reol/submissao/public/submit/';
			
			$data = $line['pl_data'];
			$protocolo = 'S'.trim($line['pl_codigo']);
			$file = trim($line['pl_filename']);
			$filename = trim($line['pl_texto']);
			///// recupera diretorio onde foi gravado arquivo
			$arq = $uploaddir;
			//// ano
			$arq .= substr($data,0,4).'/';
			//// mes
			$arq .= substr($data,4,2).'/';
			$arq .= $file;
			$doc_arquivo = $arq;
			
			if (!file_exists($doc_arquivo))
				{
					echo '<font color="red">ops '.$doc_arquivo.' não localizado</font>';
					$doc_size = 0;
					exit;
				} else {
					$doc_size = filesize($doc_arquivo);
				}
			$sql = "select * from submit_files where doc_arquivo = '$doc_arquivo' ";
			$rlt = db_query($sql);

			if ($line = db_read($rlt))
				{
					return(0);
				} else {		
					$sql = "insert into submit_files 
						(
							doc_dd0, doc_tipo, doc_ano, 
							doc_filename, doc_status, doc_data,
							doc_hora, doc_arquivo, doc_extensao,
							
							doc_size, doc_versao, doc_ativo,
							doc_user
						) values (
							'$para','$doc_tipo','$doc_ano',
							'$doc_filename','$doc_status','$doc_data',
							'$doc_hora','$doc_arquivo','$doc_extensao',
							
							'$doc_size','$doc_versao','1',
							'$doc_user'
						)";
					$rlt = db_query($sql);					
				}
		}
	function transfere_arquivos($de,$para)
		{
			$sql = "select * from ged_files ";
			$sql .= " where pl_codigo = '".$de."' ";
			$sql .= " order by id_pl desc ";
			$sql .= " limit 100 ";
			$rlt = db_query($sql);
			while ($line = db_read($rlt))
				{
					$this->transfere_arquivos_insere_works($line,$para);
	
					/*
					SUB - Originais da submissão
					PAE - Etica
					DIR - Declaração de direitos autorais
					IMG - Imagens e gráficos
					*/
				}
			return(1);
		}
	function transfere_submissao_para_works($prj_nr,$issue,$secao)
		{
			global $tesauro;
			$tesauro = new tesauro;
			
			$sql= "select * from reol_submit where doc_protocolo_original = '".$this->protocolo."'";
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					$sql = "update reol_submit set doc_issue = '".$issue."' 
										,doc_section = '".$secao."'
								where doc_protocolo_original = '".$this->protocolo."'";
					$rlt = db_query($sql);
					return(0);
				}
			
			$manuscrito_data_subm = $this->data;
			$manuscrito_jounal_id = $this->journal;
			
			$fsql = "select * from submit_documento_valor ";
			$fsql .= " inner join submit_manuscrito_field on spc_codigo = sub_codigo ";
			$fsql .= " where spc_projeto = '".$prj_nr."'";
			$fsql .= " order by spc_pagina, sub_ordem ";
			$rlt = db_query($fsql);
				
			while ($fline = db_read($rlt))
				{
				$tipo = trim($fline['sub_id']);
				$txt = trim($fline['spc_content']);
				if ($tipo == 'TITLE') 	{ $tit1 = substr($tesauro->padroniza_titulo($txt,''),0,255); }
				if ($tipo == 'TIT2') 	{ $tit2 = substr($tesauro->padroniza_titulo($txt,''),0,255); }
				if ($tipo == 'AUTOR') 	{ $autor = $txt; }
	
				if ($tipo == 'RESUM') 	{ $resumo1 = $txt; }
				if ($tipo == 'RESU2') 	{ $resumo2 = $txt; }
		
				if ($tipo == 'KEYW1') 	{ $key1 = substr($txt,0,255); }
				if ($tipo == 'KEYW2') 	{ $key2 = substr($txt,0,255); }
			}

			$resumo1 .= $rsm;
			$key1 .= $rkey;

			$rautor = troca($rautor,chr(13),'');
			$rautor = troca($rautor,chr(10),'');

			if (strlen($rautor) > 0) { $autor .= chr(13).chr(10).$rautor; }

			$resumo3 = '[proto:'.$prj_nr.']';

			/* insert */
			$sql = "insert into reol_submit ";
			$sql .= "(doc_data_submit,doc_data_aceite,doc_data_final,";
			$sql .= "doc_1_titulo,doc_2_titulo,doc_3_titulo,";
			$sql .= "doc_1_resumo,doc_2_resumo,doc_3_resumo,";
			$sql .= "doc_1_key,doc_2_key,doc_3_key,";
		
			$sql .= "doc_1_idioma,doc_2_idioma,doc_3_idioma,";
			
			$sql .= "doc_issue,doc_section,doc_protocolo,";
			$sql .= "doc_data,doc_hora,";
		
			$sql .= "doc_editor,doc_relator,doc_revisor,doc_diagramador,";
			$sql .= "doc_dt_atualizado,doc_dt_prazo,doc_autor,";
			$sql .= "doc_status,doc_atual,doc_id,";

			$sql .= "doc_tipo,journal_id,doc_grupo,doc_ord,";

			$sql .= "doc_author_pricipal, doc_protocolo_original, doc_origem";
			$sql .= ") values (";

			$sql .= "'".$manuscrito_data_subm."','".date("Ymd")."',".$manuscrito_data_subm.",";
			/////////////Titulos
			$sql .= "'".$tit1."',";
			$sql .= "'".$tit2."',";
			$sql .= "'".$tit3."',";
			/////////////////////////////////////// RESUMO (buscar)
			$sql .= "'".$resumo1."','".$resumo2."','".$resumo3."',";

			/////////////////////////////////////// KEYWORD (buscar)
			$sql .= "'".$key1."','".$key2."','".$key3."',";

			/////////////////////////////////////// IDIOMA (buscar)
			$sql .= "'".$idi1."','".$idi2."','".$idi3."',";

			/////////////////////////////////////// IDIOMA (buscar)
			$sql .= "'0".$issue."','0".$secao."','',";

			$sql .= $manuscrito_data_subm.",'".$manuscrito_hora_subm."',";
			$sql .= "'','','','".$autorp."',";
			$sql .= date("Ymd").",0,'".$autor."',";
			$sql .= "'N','','',";
			$sql .= "'',".$manuscrito_jounal_id.",'0000003',0";
			$sql .= ",'$autor_principal','$prj_nr',''";
			$sql .= ");";

			$rlt = db_query($sql);		
			
			$this->updatex_works();	
			/* Marca Artigo já publicado */
			$ok = 1;
			return(1);	
		}
	function updatex_works()
		{
			$sql = "update reol_submit set doc_protocolo=trim(to_char(id_doc,'0000000')) 
						where (length(trim(doc_protocolo)) < 7) or (doc_protocolo isnull);";
			$rlt = db_query($sql);
			return(1);
		}
		
	function recupera_procotolo_works($prg)
		{
			$xsql = "select * from reol_submit where doc_protocolo_original = '".$prg."' ";
			//$xrlt = db_query($xsql);
			$rlt = db_query($xsql);
			if ($line = db_read($rlt))
				{
					$proto = $line['doc_protocolo'];
					return($proto);
				}			
		}	
	function execute_L()
		{
			global $dd,$acao,$hd;
			$sx = '';
			$sx = 'Enviando e-mail de aceit';
			/* insere no hisetorico */
			/* Modifica status */
			
			/* insere no historico */
			$hs = new submit_historico;
			$hs->insere_historico($this->protocolo,'ENA',$avaliador,$hd->user_id);
			/* Modifica status */
			$this->alterar_status('L');
			/* enviar e-mail */
			$complemento = $dd[4];
			if ($dd[3]=='1')
				{
					$proto = $this->protocolo;
					$nome = $this->autor_nome;
					$email = $this->autor_email;
					$editor = $hd->editor;
					$title = $this->title;
					
					$mes = $this->ic("subm_B",1);
					$ms = $mes[1];
					$titulo = $mes[0];
					
					$email = array();
					array_push($email,trim($this->autor_email));
					array_push($email,trim($this->autor_email_alt));
					array_push($email,$hd->email);

					
					$ms = troca($ms,'$JOURNAL',$hd->journal_name);
					$ms = troca($ms,'$PROTOCOLO',$proto);
					$ms = troca($ms,'$TITULO',$title);
					$ms = troca($ms,'$EDITOR',$editor);
					
					for ($r=0;$r < count($email);$r++)
						{
						$email_send = $email[$r];
						if (strlen($email_send) > 0)
							{
							enviaremail($email_send,'',$titulo,$ms);
							echo '<BR>enviado '.$email_send;
							}
						}
					
				}
			return($sx);
		}
	function execute_N()
		{
			global $dd,$acao,$hd,$hs;
			$sx = '';
			$sx = 'Não aprovação do trabalhos';
			/* insere no hisetorico */
			/* Modifica status */
			/* Enviar e-mail ao responsável */
			
			/* insere no historico */
			$protocolo = $this->protocolo;
			$avaliador = '';
			$user = $hd->user_id;
			$hs->insere_historico($protocolo,'NAP',$avaliador,$user);
			
			/* Modifica status */
			$this->alterar_status('N');
			
			/* enviar e-mail */
			$complemento = $dd[4];
			if ($dd[3]=='1')
				{
					$proto = $this->protocolo;
					$nome = $this->autor_nome;
					$email = $this->autor_email;
					$editor = $hd->editor;
					$title = $this->title;
					
					$mes = $this->ic("subm_N",1);
					$ms = $mes[1];
					$titulo = $mes[0];
												
					$email = array();
					array_push($email,trim($this->autor_email));
					array_push($email,trim($this->autor_email_alt));
					array_push($email,$hd->email);

					$ms = troca($ms,'$JOURNAL',$hd->journal_name);
					$ms = troca($ms,'$PROTOCOLO',$proto);
					$ms = troca($ms,'$TITULO',$title);
					$ms = troca($ms,'$MOTIVOS','<BR><B>'.$dd[4].'</B><BR>');
					$ms = troca($ms,'$EDITOR',$editor);
					
					for ($r=0;$r < count($email);$r++)
						{
						$email_send = $email[$r];
						if (strlen($email_send) > 0)
							{
							enviaremail($email_send,'',$titulo,mst($ms));
							echo '<BR>enviado '.$email_send;
							}
						}
				}
			return($sx);
		}
	function execute_E()
		{
			global $dd,$acao,$hd,$hs;
			$sx = '';
			$sx = 'Enviar para o editor';
			/* insere no hisetorico */
			/* Modifica status */
			/* Enviar e-mail ao responsável */
			
			/* insere no historico */
			$protocolo = $this->protocolo;
			$avaliador = '';
			$user = $hd->user_id;
			$hs->insere_historico($protocolo,'EED',$avaliador,$user);
			
			/* Modifica status */
			$this->alterar_status('G');
			
			return($sx);
		}

	function execute_X()
		{
			global $dd,$acao,$hd;
			$sx = '';
			$sx = 'Cancelamento de projeto';
			/* insere no hisetorico */
			/* Modifica status */
			/* Enviar e-mail ao responsável */
			
			/* insere no historico */
			//$hs->insere_historico($protocolo,'AVA',$avaliador,$user);
			
			/* Modifica status */
			$this->alterar_status('X');
			
			/* enviar e-mail */
			$complemento = $dd[4];
			if ($dd[3]=='1')
				{
					$proto = $this->protocolo;
					$nome = $this->autor_nome;
					$email = $this->autor_email;
					$editor = $hd->editor;
					$title = $this->title;
					
					$mes = $this->ic("subm_X",1);
					$ms = $mes[1];
					$titulo = $mes[0];
												
					$email = array();
					array_push($email,trim($this->autor_email));
					array_push($email,trim($this->autor_email_alt));
					array_push($email,'renefgj@gmail.com');
					array_push($email,$hd->email);

					$ms = troca($ms,'$JOURNAL',$hd->journal_name);
					$ms = troca($ms,'$MOTIVOS','<BR><B>'.$dd[4].'</B><BR>');
					$ms = troca($ms,'$PROTOCOLO',$proto);
					$ms = troca($ms,'$TITULO',$title);
					$ms = troca($ms,'$EDITOR',$editor);
					
					for ($r=0;$r < count($email);$r++)
						{
						$email_send = $email[$r];
						if (strlen($email_send) > 0)
							{
							enviaremail($email_send,'',$titulo,mst($ms));
							echo '<BR>enviado '.$email_send;
							}
						}
				}
			return($sx);
		}

	function form_select_open_session()
		{
			$jid = $this->journal;
			$sx = '<select id="dd7" name="dd7">';
			$sql = "select * from sections 
					where journal_id = ".$jid."
					order by seq
					";
			$irlt = db_query($sql);
			$id = 0;
			while ($iline = db_read($irlt))
				{
					$id++;
					$sx .= chr(13).chr(10);
					$sx .= '<option value="'.$iline['section_id'].'">';
					$sx .= trim($iline['title']);
					$sx .= '</option>';
				}
			$sx .= '</select>';
			if ($id == 0)
				{
					$sx .= '<font color="red">Não existe edição não publicada para aprovação do trabalho</font>';
					$this->erro = 1;
				}
			return($sx);
		}

	function form_select_open_issue()
		{
			$jid = $this->journal;
			$sx = '<select id="dd6" name="dd6">';
			$sql = "select * from issue 
					where journal_id = ".$jid."
					and issue_published = 0
					order by issue_year, issue_number
					";
			$irlt = db_query($sql);
			$id = 0;
			while ($iline = db_read($irlt))
				{
					$id++;
					$sx .= chr(13).chr(10);
					$sx .= '<option value="'.$iline['id_issue'].'">';
					$sx .= 'v.'.trim($iline['issue_volume']).' n.'.trim($iline['issue_number']).', '.trim($iline['issue_year']);
					$sx .= '</option>';
				}
			$sx .= '</select>';
			if ($id == 0)
				{
					$sx .= '<font color="red">Não existe edição não publicada para aprovação do trabalho</font>';
					$this->erro = 1;
				}
			return($sx);
		}

	function alterar_status($sta)
		{
			$protocolo = $this->protocolo;
			$sql = "update ".$this->tabela." set doc_status = '$sta', 
						doc_update = ".date("Ymd").",
						doc_dt_atualizado = ".date("Ymd")."
					where doc_protocolo = '$protocolo'
			";
			$rlt = db_query($sql);
			return(1);
		}
	function ic($id,$tipo=0)
		{
			global $jid,$secu;
			$jnid = strzero($jid,7);
			$sql = "select * from ic_noticia where nw_ref='$id' 
					and nw_journal = $jid ";
			
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					$titulo = $line['nw_titulo'];
					$text = $line['nw_descricao'];
				}
			if ($tipo == 1)
				{
					return(array($titulo,$text));
					exit;
				}
			$link = '<span onclick="newxy2(\'ic_editar_sel.php?dd1='.$jid.'&dd2='.$id.'&dd90='.checkpost($jid.$id.$secu).'\',600,400);" class="link">editar mensagem: '.$id.'</span>';
			$sx .= '<table class="tabela20" width="100%">
					<TR class="tabela_title"><td colspan=3><B>Modelo da comunicação</B>
					<TR class="tabela_title"><TH style="font-size: 14px;">Título do e-mail: <B>'.$titulo.'</B>
					<TR class="tabela"><TD>'.mst($text).'
					<TR><TD>'.$link.'
					</table>
					';
			
			return($sx);
		}
	function show_button($cap,$txt,$email=0,$comment=0,$form='',$issue=0)
		{
			global $dd;
			$sx .= '<form id="form" method="post" action="submit_works_detalhes.php" class="tabela10">'.chr(13);
			$sx .= '<input type="hidden" name="dd0" value="'.$dd[1].'">'.chr(13);
			$sx .= '<input type="hidden" name="dd2" value="'.$dd[2].'">'.chr(13);
			$sx .= '<input type="hidden" name="dd5" value="ACAO">'.chr(13);
			$sx .= '<input type="hidden" name="dd90" value="'.$dd[90].'">'.chr(13);
			
			$sx .= '<table class="tabela20" width="100%">';
			//$sx .= '<TR><TD colspan=2 bgcolor="#FFFFFF">';
			//$sx .= 'Marque os avaliadores';
			if (strlen($form) > 1)
				{
					$sx .= '<TR><TD>';
					$sx .= $form;
				}
			/* informações */
			if ($issue == 1)
				{
					$sx .= '
					<TR class="tabela_title"><td><B>'.$txt.'</B>
					<TR><TD align="left">Sessão de publicação: 
					'.$this->form_select_open_session().'					
					<TR><TD align="left">Aprovar no fascículo: 
					'.$this->form_select_open_issue().'
					'.chr(13);
				}
			/* informações */
			if ($comment == 1)
				{
					$sx .= '
					<TR class="tabela_title"><td><B>'.$txt.'</B>
					<TR><TD align="left">
					<textarea name="dd4" rows="8" cols="60" style="width: 400px;"></textarea>
					'.chr(13);
				}
						
			/* botao */
			if ($email == 1)
				{
					$sx .= '
					
					<TR><TD align="left">
					<input type="checkbox" name="dd3" value="1" checked> Enviar e-mail para comunicar
					'.chr(13);
				}
			$sx .= '</table>';
			
			if ($this->erro == 0)
				{
				$sx .= '<input type="submit" value="'.$cap.'" class="botao-confirmar" id="bt00">
						'.chr(13);
				}
			$sx .= '</form>'.chr(13);
			return($sx);
		}

	/* ACAOES */
	
	function action_B()
		{
			global $dd,$acao;
			$sx .= '<table width="100%" class="tabela">
					<TR class="tabela_title" valign="top">
						<TH colspan="10" class="tabela_title">'.msg("actions").'
					<TR valign="top">
						<TD width="49%">
							'.$this->show_button('confirmar >>>','Comunicar Autor',1,1).'
						<td width="2%">&nbsp;
						<TD width="49%">
							'.$this->ic("subm_B").'	
					</table>
			';
			return($sx);	
		}

	function action_X()
		{
			global $dd,$acao;
			$sx .= '<table width="100%" class="tabela">
					<TR class="tabela_title" valign="top">
						<TH colspan="10" class="tabela_title">'.msg("actions").'
					<TR valign="top">
						<TD width="49%">
							'.$this->show_button('confirmar >>>','Comunicar Autor',1,1).'
						<td width="2%">&nbsp;
						<TD width="49%">
							'.$this->ic("subm_X").'	
					</table>
			';
			return($sx);	
		}
	function action_N()
		{
			global $dd,$acao;
			$sx .= '<table width="100%" class="tabela">
					<TR class="tabela_title" valign="top">
						<TH colspan="10" class="tabela_title">'.msg("actions").'
					<TR><TD colspan=2><h1>Não aprovação do trabalho</h1>
					<TR valign="top">
						<TD width="49%">
							'.$this->show_button('confirmar >>>','Comunicar Autor',1,1).'
						<td width="2%">&nbsp;
						<TD width="49%">
							'.$this->ic("subm_N").'	
					</table>
			';
			return($sx);	
		}
	function action_E()
		{
			global $dd,$acao;
			$sx .= '<table width="100%" class="tabela">
					<TR class="tabela_title" valign="top">
						<TH colspan="10" class="tabela_title">'.msg("actions").'
					<TR><TD colspan=2><h1>Enviar para o editor</h1>
					<TR valign="top">
						<TD width="49%">
							'.$this->show_button('confirmar >>>','Enviar para editor',1,1).'
						<td width="2%">&nbsp;
						<TD width="49%">
							'.$this->ic("subm_E").'	
					</table>
			';
			return($sx);	
		}

	function action_C()
		{
			global $dd,$acao,$jid;
			$pp = new parecer;

			$form = $pp->indicar_avaliadores('',$jid);			
			$sx .= '<table width="100%" class="tabela">
					<TR class="tabela_title" valign="top">
						<TH colspan="10" class="tabela_title">'.msg("actions").'
					<TR valign="top">
						<TD width="49%">
							'.$this->show_button('confirmar >>>',$text,1,0,$form).'
						<td width="2%">&nbsp;
						<TD width="49%">
							'.$this->ic("subm_C").'	
					</table>
			';
			return($sx);	
		}	
	function action_G()
		{
			global $dd,$acao,$jid;
			$pp = new parecer;
			$issue = 1;
			
			$sx .= '<table width="100%" class="tabela">
					<TR class="tabela_title" valign="top">
						<TH colspan="10" class="tabela_title">'.msg("actions").'
					<TR valign="top">
						<TD width="49%">
							'.$this->show_button('confirmar >>>',$text,1,1,$form,$issue).'
						<td width="2%">&nbsp;
						<TD width="49%">
							'.$this->ic("subm_G").'	
					</table>
			';
			return($sx);
		}					
	function action_L()
		{
			global $dd,$acao,$jid;
			$pp = new parecer;
		
			$sx .= '<table width="100%" class="tabela">
					<TR class="tabela_title" valign="top">
						<TH colspan="10" class="tabela_title">'.msg("actions").'
					<TR valign="top">
						<TD width="49%">
							'.$this->show_button('confirmar >>>',$text,1,1,$form).'
						<td width="2%">&nbsp;
						<TD width="49%">
							'.$this->ic("subm_L").'	
					</table>
			';
			return($sx);
		}
	
	function actions_show($sh='')
		{
			$sn = array();
			$st = array();
			
			switch ($sh)
				{
				case '@':
					array_push($sn,'X');
					break;
				case 'A': /* ok */
					array_push($sn,'B');
					array_push($sn,'@');
					array_push($sn,'N');
					array_push($sn,'X');
					break;
				case 'X': 
					array_push($sn,'E');
					break;					
				case 'E': 
					array_push($sn,'E');
					break;					
				case 'B': 
					array_push($sn,'C');
					array_push($sn,'N');
					array_push($sn,'@');
					array_push($sn,'X');
					break;
				case 'C': 
					array_push($sn,'C');
					array_push($sn,'N');
					array_push($sn,'X');
					array_push($sn,'G');
					array_push($sn,'L');
					array_push($sn,'E');
					break;										
				case 'G': 
					array_push($sn,'G');
					array_push($sn,'X');
					array_push($sn,'L');
					array_push($sn,'N');
					break;
				case 'M': 
					array_push($sn,'G');
					array_push($sn,'X');
					array_push($sn,'L');
					array_push($sn,'N');
					break;					
				case 'L': 
					array_push($sn,'C');
					array_push($sn,'N');
					array_push($sn,'G');
					array_push($sn,'X');
					array_push($sn,'E');
					break;	
				case 'N': 
					array_push($sn,'C');
					array_push($sn,'E');
					break;
				case 'Z': 
					array_push($sn,'C');
					break;														
				}
			$sx = '<table width="100%" class="tabela20" border=0>';
			$sx .= '<TR class="tabela_title">
				<TH colspan="10" class="tabela_title">'.msg("actions");
			$sx .= $this->actions_display($sn,$sh);
			$sx .= '</table>';
			$sx .= '<div stlye="float: clear">&nbsp;</div>';
			return($sx);
		}

	function actions_display($a1,$a2)
		{
			global $dd;
			$col = 99;
			for ($r=0;$r < count($a1);$r++)
				{
				if ($col > 2)
					{ $sx .= '<TR class="padding5" style="background-color: #FFFFFF;">'; $col = 0; }
				$sx .= '<TD align="center">';
				$sx .= '<input type="button" 
							id="bt'.strzero($r,2).'" 
							value="'.$this->msg('action_'.$a1[$r]).'" 
							class="botao-finalizar" 
							style="padding: 2px 15x 2px 15px; width: 200px;"
							onclick="goto(\''.$a1[$r].'\')"
							>';
				$col++;
				}
			$sx .= '
				<script>
				function goto(id)
					{
						var link_call = "submit_works_actions.php?dd2="+id+"&dd1='.$dd[1].'&dd90='.$dd[90].'";
						var $tela01 = $.ajax(link_call)
							.done(function(data) { $("#actions").html(data); })
							.always(function(data) { $("#actions").html(data); });			
					}
				</script>			
			';
			
			return($sx);
		}
	function action_execute_button($op)
		{
			echo '<HR>'.$op.'<HR>';
			switch ($op)
			{
			case "B": $sx = $this->action_B(); break;
			case "C": $sx = $this->action_C(); break;
			case "E": $sx = $this->action_E(); break;
			case "X": $sx = $this->action_X(); break;
			case "L": $sx = $this->action_L(); break;
			case "G": $sx = $this->action_G(); break;
			case "N": $sx = $this->action_N(); break;
			}
			return($sx);
		}
	function actions()
		{
			global $dd;
			$sx .= '<div id="actions">Não foi possível carregar a página
					</div>
			';
			$sx .= '
			<script>
			
			var link_call = "submit_works_actions.php?dd1='.$this->id.'&dd90='.checkpost($this->id).'";
			
			var $tela01 = $.ajax(link_call)
				.done(function(data) { $("#actions").html(data); })
				.always(function(data) { $("#actions").html(data); });	

			</script>';
			return($sx);
		}
	
	function show_list($st='',$data=0,$datai=0,$filtro='')
		{
			global $jid;
			if (strlen($st) > 0) { $wh = " and (doc_status = '$st') "; }
			
			if ($data > 0) { $wh .= 'and (doc_update < '.$data.')'; }
			if ($datai > 0) { $wh .= 'and (doc_update >= '.$datai.')'; }
			if (strlen($filtro) > 0)
				{
					$wh .= " or ((doc_protocolo like '%".$filtro."%') ";
					$wh .= " or (upper(ASC7(doc_1_titulo)) like '%".UpperCaseSql($filtro)."%'))";
				}
				
			if ($st == 'D')
				{
					$wh = " and (
					    doc_status <> '@'
					and doc_status <> 'A'
					and doc_status <> 'B'
					and doc_status <> 'C'
					and doc_status <> 'L'
					and doc_status <> 'X'
					and doc_status <> 'Z'
					and doc_status <> 'G'
					and doc_status <> 'M'
					and doc_status <> 'O'
					) ";	
				}
			if ($st == 'G')
				{
					$wh = " and ( 
							doc_status = 'G'
						or  doc_status = 'M'
						)
				 ";	
				}
			/* com parecerista */
			if ($st == 'C')
				{
					$wh = " and ( 
							doc_status = 'C'
						or  doc_status = 'O'
						)
				 ";	
				}								
			
			$order  = " doc_status,  doc_dt_atualizado desc, doc_hora desc ";
			$sql = "select Upper(asc7(doc_1_titulo)) as tt,* from submit_documento 
					left join submit_status on doc_status = s_status
					where doc_journal_id = '".strzero($jid,7)."'
						and ((doc_status <> '*')
						$wh) and (doc_journal_id = '".strzero($jid,7)."')
					order by doc_status, doc_update, s_descricao_1
					";
			$rlt = db_query($sql); 
			$xses = 'x';
			$xiss = 0;
			$sx = '<table width="100%" class="tabela00">';
			$id = 0;
			while ($line = db_read($rlt))
			{
				$ses = trim($line['title']);		
				if ($xses != $ses)
					{
						$sx .= '<TR ><TD colspan=5><h3>'.$line['title'].'</h3>';
						$xses = $ses;
					}
				$sx .= $this->show_work($line);
				$ln = $line;	
				$id++;
			}
			if ($id > 0)
				{
					$sx .= '<TR><TD colspan=5><B>Total de '.$id.' registro(s)</B>';
				}
			$sx .= '</table>';
			//print_r($ln);
			return($sx);
		}	
		
		function show_work($line)
			{
				$tot++;
				$dias = DateDif($line['doc_update'],date("Ymd"),'d');
				$ndias = DateDif($line['doc_data'],date("Ymd"),'d');
				if ($dias > 1000) { $dias = $ndias; }
					$titulo = trim($line['doc_1_titulo']);
				if (strlen($titulo) == 0) { $titulo = '## submetido sem título ##'; }
				$dias = '<font class="lt5"><font color="red">'.$dias.'<font class="lt0"><BR>dias';
				$link = '<A HREF="submit_works_detalhes.php?dd0='.$line['id_doc'].'"><font color="blue" style="font-size: 13px;">';
				$status = trim($line['doc_status']);
				$sta = $status;
				if ($status == 'A') { $status = '<font color="green"><B>Submetido</B></font>'; }
				$sr .= '<TR valign="top">';
				$sr .= '<TD rowspan="3" width="5%"><nobr>';
				$sr .= '<img src="img/subm_icone_'.$sta.'.png" height="64" alt="" border="0">';
				$sr .= '<img src="img/subm_bar_'.$sta.'.png"  height="64" alt="" border="0">';
				$sr .= '</TD>';
				$sr .= '<TD class="lt0">PROTOCOLO: '.$line['doc_protocolo'].' - '.$status.'</TD>';
				$sr .= '<TR><TD colspan="6" class="lt3"><B>'.$link.$titulo.'</TD>';
				$sr .= '<TD rowspan="2" align="center">'.$ndias.'/'.$dias.'</TD>';
				$sr .= '<TR class="lt0">';
				$sr .= '<TD><font color="#c0c0c0">Atualizado: <B>'.stodbr($line['doc_dt_atualizado']).' '.$line['doc_hora'].'</TD>';
				$sr .= '<TD><font color="#c0c0c0">Submissão: <B>'.stodbr($line['doc_update']).'</TD>';
				$sr .= '</TR>';
				$sr .= '<TR class="lt0">';
				$sr .= '<TD colspan="2"><font color="#c0c0c0">Autor: <B>'.$line['sa_nome'].'</TD>';
				$sr .= '<TR><TD colspan="8" height="1" bgcolor="#c0c0c0"></TD></TR>';
				return($sr);
			}		

	/***  ***/
	function mostra_submissoes() 
		{
		global $jid;
		$njid = strzero($jid,7);
		echo '<div style="background: #FFFFFA;">';
		$sql = "select * from submit_documento 
			left join submit_autor on doc_autor_principal = sa_codigo
			where doc_journal_id = '$njid'
			and ((doc_status <> '@') and (doc_status <> 'Z') and (doc_status <> 'X') and (doc_status <> 'N'))
			order by doc_status, doc_data desc
			";
		
		$rlt = db_query($sql);
		$status = $this->status();
		//echo $sub->resumo();
		
		$xsta = '';
		$sx .= '<table width="100%">';
		while ($line = db_read($rlt))
			{
			$sx .= $this->show_work($line);
			}
		$sx .= '</table>';
		return($sx);
			$ln = $line;
			$di = $line['doc_update'];
			if (round($di) == 0)
				{ $di = $line['doc_data']; }
			$dias = DateDif($di,date("Ymd"),'d');
			$ndias = DateDif($line['doc_data'],date("Ymd"),'d');
			
			$sta = trim($line['doc_status']);
		
			if ($sta != $xsta)
				{
					$xsta = $sta;
					$sx .= '<div><HR><h3>'.$status[$sta].' - '.$sta.'</h3><HR></div>';
				}
			
			$sx .= '<div class="tabela30 lt0" style="backgroud-color: #FFFF00; ">';
			$sx .= $this->mostra_dias_submissão($dias);
			$sx .= '<A HREF="submit_detalhe.php?dd0='.$line['id_doc'].'&dd90='.checkpost($line['id_doc']).'" class="lt2">';
			$sx .= $line['doc_1_titulo'];
			$sx .= '</A>';
			
			$sx .= '<BR>
				<font class="lt1">'.stodbr($line['doc_data']).' - </font> 
				<font class="lt1"><B>'.trim($line['sa_nome']).'</B></font>
				<font class="lt1">, atualizado em '.stodbr($line['doc_update']).'</font>
				<font class="lt1">, postado em '.stodbr($line['doc_dt_atualizado']).'</font>
				';
				$sx .= '<BR><BR>';
				$sx .= '</div>';
			
		echo $sx;
		echo '</div>';			
		}
	
	
	function  autor_valida()
		{
			$_SESSION['autor_n'] = $this->author_nome;
			$_SESSION['autor_e1'] = $this->author_email;
			$_SESSION['autor_e2'] = $this->author_email_alt;
			$_SESSION['autor_cod'] = $this->author_codigo;
			return(1); 
		}
		
	function recuperar_autor($protocolo='')
		{
			$codigo = '';
			$sql = "select * from ".$this->tabela." 
					left join submit_autor on doc_autor_principal = sa_codigo
					where doc_protocolo='".$protocolo."' ";
			$sql .= "limit 1 ";
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					$codigo = trim($line['doc_autor_principal']);
					$this->author_nome = $line['sa_nome'];
					$this->author_email = $line['sa_email'];					
					$this->author_email_alt = $line['sa_email_alt'];
					$this->author_codigo = $line['sa_codigo'];
				}
			return($codigo);
		}
	
	function cp()
		{
			
		}
		
	function mostra_dias_submissão($dias)
		{
			$sx = '<div class="submit_dias">
					'.$dias.'<sup class="submit_dias_sup">dias</sup>
				</div>';
			return($sx);
		}
		
	function resumo_mostra()
		{
			global $jid;
			$journal_id = strzero($jid,7);
			$sql = "select count(*) as total, doc_status from submit_documento 
				left join submit_autor on doc_autor_principal = sa_codigo
				where doc_journal_id = '$journal_id'
				and ((doc_status <> '@') and (doc_status <> 'Z') and (doc_status <> 'X'))
				group by doc_status
				order by doc_status
				";	
			$rlt = db_query($sql);
			$rs = array(0,0,0,0,0,0,0,0,0);
			while ($line = db_read($rlt))
				{
					$sta = trim($line['doc_status']);
					$total = $line['total'];
					switch ($sta)
						{
						case 'A': $rs[0] = $rs[0] + $total; break;
						case 'C': $rs[3] = $rs[3] + $total; break;
						case 'O': $rs[3] = $rs[3] + $total; break;
						case 'E': $rs[1] = $rs[1] + $total; break;
						case 'G': $rs[1] = $rs[1] + $total; break;
						case 'M': $rs[1] = $rs[1] + $total; break;
						case 'L': $rs[2] = $rs[2] + $total; break;
						
						}
				}
			$sx = '<table class="tabela20" width="100%">';
			$sx .= '<TR><TH class="tabela_title" colspan=4>Resumo das Submissões';
			$sx .= '<TR align="center">
						<TH class="tabela10"><center>Submetidos</center>
						<TH class="tabela10"><center>Com editor</center>
						<TH class="tabela10"><center>Com autor</center>
						<TH class="tabela10"><center>Com parecerista</center>';
			$link3='<a href="submit_works.php?dd1=O">';
			$link2='<a href="submit_works.php?dd1=L">';
			$link1='<a href="submit_works.php?dd1=E">';
			$link0='<a href="submit_works.php?dd1=A">';
			$sx .= '<TR>
						<TD align="center"  class="lt4">'.$link0.$rs[0].'</A>
						<TD align="center"  class="lt4">'.$link1.$rs[1].'</A>
						<TD align="center"  class="lt4">'.$link2.$rs[2].'</A>
						<TD align="center"  class="lt4">'.$link3.$rs[3].'</A>
						';
			$sx .= '</table>';		
			return($sx);
		}
		
	function status()
		{
			$st = array(
				'@'=>'Em submissão pelo autor',
				'#'=>'Em Correção de submissão pelo autor',
				'A'=>'Submetido, aguardando aceite para avaliação',
				'B'=>'Aceito para avaliação, aguardando indicação de parecerista',
				'C'=>'Em avaliação por parecerista Ad Hoc',
				'D'=>'Avaliação finalizada pelo parecerista',
				'G'=>'Aguardando documentação de direitos autorais dos autores',
				'O'=>'Reavaliação do parecerista Ad Hoc',
				'H'=>'Aceito para publicação',
				'I'=>'Aceito para publicação, falta definir edição',
				'M'=>'Devolvido pelo autor com as correções',
				'N'=>'Não aprovado',
				'L'=>'Enviado para o autor para correções',
				'Q'=>'Enviado para editora',
				'Z'=>'Aceito para publicação',
				'X'=>'Cancelado'				
			);
			return($st);
		}
	function le($id='',$protocolo='')
		{
			if (strlen($id) > 0) { $this->id = $id; }
			if (strlen($protocolo) > 0) { $this->protocolo = $protocolo; }
			$sql = "select * from ".$this->tabela." 
					left join submit_autor on doc_autor_principal = sa_codigo 
					where id_doc = ".round($this->id)."
				or doc_protocolo = '$protocolo' ";
			$rlt = db_query($sql);
			
			if ($line = db_read($rlt))
				{
					$this->autor_nome = trim($line['sa_nome']);
					$this->autor_email = trim($line['sa_email']);
					$this->autor_email_alt = trim($line['sa_email_alt']);
					$this->autor_nome = trim($line['sa_nome']);
					$this->title = $line['doc_1_titulo'];
					$this->journal = $line['doc_journal_id']; 
					$this->autor = trim($line['doc_status']);
					$this->status = trim($line['doc_status']);
					$this->protocolo = trim($line['doc_protocolo']);
					$this->update = $line['doc_dt_atualizado'];
					$this->data = $line['doc_data'];
					$this->hora = $line['doc_hora'];
					$this->autor_principal = trim($line['doc_autor_principal']);
				}
			return(1);
		}	

	/** Mostra dados do sistema **/
	function mostra_dados()
		{
			$status = $this->status();
			$sx .= '<fieldset>'.chr(13);
			$sx .= '<legend><font class="lt1"><B>informações sobre submissão</B></font></legend>'.chr(13);
			$sx .= '<table width="100%">'.chr(13);
			$sx .= '<TR><TD class="lt0" width="10%" align="right">título</TD>'.chr(13);
			$sx .= '<TD class="lt3" colspan="3"><B>'.$this->title.'</B></TD>'.chr(13);
			$sx .= '<TD rowspan="10" align="right">'.chr(13);
			$sx .= '<img src="img/subm_icone_'.$this->status.'.png" width="60" alt=""></TD>'.chr(13);
			$sx .= '</TR>'.chr(13);
			$sx .= '<TR><TD class="lt0" width="10%" align="right"><I>status</I></TD>'.chr(13);
			$sx .= '<TD class="lt2"><B>'.$status[$this->status].'</B> ['.$this->status.']</TD>'.chr(13);
			$sx .= '<TR><TD class="lt0" align="right">data submi.</TD>'.chr(13);
			$sx .= '<TD class="lt1">'.stodbr($this->data).' '.$this->hora.'</TD>'.chr(13);
			$sx .= '<TD class="lt0" align="right">protocolo</TD>'.chr(13);
			$sx .= '<TD class="lt1"><B>'.$this->protocolo.'</B></TD>'.chr(13);
			$sx .= '<TR><TD class="lt0" align="right">atualizado</TD>'.chr(13);
			$sx .= '<TD class="lt1">'.stodbr($this->update).'</TD>'.chr(13);
			
			/** caso contenha especificação de área **/
			if (strlen($$this->area) > 0) 
				{ $sx .= '<TD class="lt0" align="right">área</TD><TD class="lt1"><B>'.$this->area.'</B></TD>'; }
			$sx .= '</TR>'.chr(13);
			$sx .= '</TR>'.chr(13);
			$sx .= '</table>'.chr(13);
			$sx .= '</fieldset>';
			return($sx);
		}
	function mostra_autor_principal()
		{
			return($this->mostra_autores());
		}
	function mostra_autores()
		{
			$sx = '<BR>';
			$sx .= '<table width="100%" border="0" class="tabela10">
					<TR><TH class="tabela_title" colspan=5>sobre o autor principal';
			$sx .= '<TR><TD class="tabela10" width="10%" align="right">nome</TD>';
			$sx .= '<TD class="tabela10" colspan="3"><B>'.$this->autor_nome.'</B></TD>'.chr(13);
			$sx .= '<TD width="48" rowspan=4>';
			$sx .= $this->relacionamento_com_autor();
			$sx .= '</TR>'.chr(13);
			$sx .= '<TR><TD class="tabela10" width="15%" align="right">e-mail</TD>';
			$sx .= '<TD class="tabela10"><B>'.$this->autor_email.'</B></TD>'.chr(13); 
			$sx .= '<TR><TD class="tabela10" width="15%" align="right">e-mail (alt.)</TD>';
			$sx .= '<TD class="tabela10"><B>'.$this->autor_email_alt.'</B></TD>'.chr(13);
			$sx .= '</table>'.chr(13);
			$sx .= '<BR>';
			return($sx);
		}
	function total_mensagens()
		{
			$tot = 0;
			$sql = "select count(*) as total from submissao_relacionamento
					where sr_protocolo = '".$this->protocolo."' 
					";
			$rlt = db_query($sql);			
			if ($line = db_read($rlt))
				{
					$tot = $line['total'];
				}
			return($tot);
		}
	function relacionamento_com_autor()
		{
			$total = $this->total_mensagens();
			$sx = '<center>';	
			$sx .= '<A HREF="javascript:newxy2(\'autor_relacionamento.php?dd0='.$this->protocolo.'\',500,300);" alt="relacionamento com autor principal">';
			$sx .= '<img src="img/icone_email.png" height="48" border=0>';
			$sx .= '</A>';
			$sx .= '<BR>';
			if ($total > 0)
				{
					if ($total > 1)
						{
							$sx .= '<font class="lt0">'.$total.' mensagens</font>';		
						} else {
							$sx .= '<font class="lt0">'.$total.' mensagem</font>';
						}
					
				} else {
					$sx .= '<font class="lt0">sem mensagens</font>';
				}
			return($sx);
		}
	function relacionamento_form()
		{
			global $dd,$acao,$http,$email_adm,$admin_nome;
			$sx = '
			<form method="post">
			<table width="100%">
			<TR class="lt1"><TD>Autor principal</TD><TD align="right" rowspan=2>
			<img src="img/icone_email.png" height="48" border=0>
			</TR>
			<TR><TD>'.$this->autor_nome.'
			<TR class="lt1"><TD>Assunto</TD></TR>
			<TR><TD colspan=2><input type="text" maxsize="100" style="width: 100%" value="'.$dd[1].'" name="dd1"></TD></TR>
			<TR class="lt1"><TD>Mensagem</TD></TR>
			<TR><TD colspan=2><textarea name="dd2" rows=6 style="width: 100%">'.$dd[2].'</textarea></TD></TR>
			<TR><TD><input type="submit" value="enviar mensagens" name="acao"></TD></TR>	
			</table>
			</form>
			';
			
			if (strlen($acao) > 0)
				{
					if ((strlen($dd[1]) > 0) and (strlen($dd[2]) > 0))
						{
							echo 'enviar e-mail';
							$dd[2] = mst($dd[2]);
							$dd[2] .= '<BR><BR>Protocolo '.$this->protocolo;
							$texto = $dd[2];
							$dd[2] .= '<BR><BR>Para responder este e-mail click no link abaixo<BR>';
							$link = $http.'submissao/relacionamento_autor.php?dd0='.$this->protocolo.'&dd1='.checkpost($this->protocolo);
							$link = '<a href="'.$link.'">'.$link.'</A>';
							$dd[2] .= $link;
							
							$this->relacionamento_grava($dd[1],$texto);
							$email_adm = 'editora@pucpr.br';
							$admin_nome = 'Não responda (no replay)';
							enviaremail('renefgj@gmail.com','',$this->protocolo.' - '.$dd[1],$dd[2]);
							enviaremail($this->autor_email,'',$this->protocolo.' - '.$dd[1],$dd[2]);
							echo '<CENTER><H2>e-mail enviado com sucesso!</center>';
							exit;
						} 
				}
			return($sx);
		}
	function relacionamento_grava($assunto,$texto)
		{
			//$this->strucuture();
			$protocolo = $this->protocolo;
			$data = date("Ymd");
			$hora = date("H:i:s");
			$sql = "insert into submissao_relacionamento
				(
				sr_protocolo, sr_assunto, sr_texto,
				sr_data, sr_hora )
				values
				( '$protocolo','$assunto','$texto',
				$data,'$hora')
			";
			$rlt = db_query($sql);
		}
		
	function mostra_autores_todos()
		{
		$sx = '
    		<table width="100%" border="0" class="tabela10">
    		<TR><TH class="tabela_title" colspan=4>sobre o(s) autor(es)
			<TR><TH>nome</TH><TH>e-mail</TH><TH>cidade</TH><TH>Lattes</TR>
			';
			$sql2 = "select * from submit_autores ";
			$sql2 .= " left join apoio_titulacao on qa_titulo = ap_tit_codigo ";
			$sql2 .= " where qa_protocolo = '".$this->protocolo."' ";
			$sql2 .= " limit 20 ";
			$rlt2 = db_query($sql2);
			while ($line2 = db_read($rlt2))
				{
				$lattes = trim($line2['qa_lattes']);
				if (strlen($lattes) > 0)
					{ $lattes = '<A HREF="'.$lattes.'" target="new_"><img src="img/icone_lattes.gif" width="20" height="20" alt="" border="0"></A>'; }
				$titulacao = trim($line2['ap_tit_titulo']);
			
				$nome2 = trim($line2['qa_nome']);
				$cidade2 = trim($line2['qa_cidade']);
		//		$nome2 = trim($line2['qa_instituicao']);
				$titula2 = trim($line2['qa_titulo']);
				$email2 = trim($line2['qa_email']);
				
				$sx .= '<TR><TD class="tabela10" colspan="1">'.trim(trim($titulacao).' '.trim($nome2)).'</TD>
        			<TD class="tabela10">'.$email2.'</TD> 
            		<TD class="tabela10">'.$cidade2.'</TD>   
					<TD align="center">'.$lattes.'</TD>        
	        	</TR></TR>';
	        	}
    		$sx .= '</table><BR><BR>';
			return($sx); 			
		}
	function relacionamento_lista()
		{
			$sql = "select * from submissao_relacionamento
					where sr_protocolo = '".$this->protocolo."' 
					";
			$rlt = db_query($sql);
			$sa = '';
			while ($line = db_read($rlt))
				{
					$sa .= '<TR>';
					$sa .= '<TD>';
					$sa .= stodbr($line['sr_data']);
					$sa .= ' ';
					$sa .= trim($line['sr_hora']);					
					$sa .= '<TR>';
					$sa .= '<TD><B>';
					$sa .= trim($line['sr_assunto']);
					$sa .= '<TR>';
					$sa .= '<TD>';
					$sa .= trim($line['sr_texto']);
					$sa .= '<TR><TD><HR>';
				}
			if (strlen($sa)==0) { $sa = '<TR><TD>Sem relacionamento</TD></TR>';}
			$sx = '
				<table width="100%" class="lt1">
				<TR><TD><B>Relacionamentos anteriores com este protocolo</B></TD></TR>
				';
			$sx .= $sa;
			$sx .= '</table>';
			return($sx);	
			
		}
	function strucuture()
		{
			$sql = "CREATE TABLE submissao_relacionamento
				(
				id_sr serial NOT NULL,
				sr_protocolo char(7),
				sr_assunto char(100),
				sr_texto text,
				sr_data int,
				sr_hora char(8)
				);
			";
			$rlt = db_query($sql);
		}
	}
?>
