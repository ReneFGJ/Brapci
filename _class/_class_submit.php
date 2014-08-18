<?/*
 *  
 */
class submit
	{
		var $tabela = 'submit_documento';
		var $tabela_autor = 'submit_autor';
		var $tabela_autor_trabalho = 'submit_documento_autor';
		var $author_nome = '';
		var $author_codigo = '';
		var $author_email = '';
		var $author_email_alt = '';
		var $author_line = '';
		
		var $journal;
		var $admin_email_nome;
		var $admin_email;
		var $line;
		var $erro_email;
		var $erro_senha;
		var $erro;
		
		var $protocolo;
		
		var $saved;
		
	function finalizar()
		{
			$this->le_submit($this->protocolo);
			if (trim($this->line['doc_status'])=='@')
				{
				$this->alterar_status('A');
				//print_r($this);
				echo '<h2>Sua submissão foi completada com sucesso</h2>';
				}
			//print_r($this);			
		}
	function alterar_status($to)
		{
			$sql = "update ".$this->tabela." set doc_status = '$to',
					doc_update = '".date("Ymd")."' 
					where doc_protocolo = '".$this->protocolo."'";
			$rlt = db_query($sql);			
		}
	function adicionar_autor($protocolo,$autor,$titulacao,$instituicao,$pais='',$uf='',$cidade='',$email='')
		{
			if (strlen(trim($autor))==0) { return(0); }
			$autor = utf8_decode($autor);
			$instituicao = utf8_decode($instituicao);
			$sql = "select * from ".$this->tabela_autor_trabalho."
					where sma_protocolo = '$protocolo' and
							sma_nome = '$autor'
			";
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					
				} else {
					$sql = "insert into ".$this->tabela_autor_trabalho."
						(sma_protocolo, sma_nome, sma_instituicao, 
						sma_pais, sma_uf, sma_cidade,
						sma_ativo, sma_titulacao, sma_email )
						values 
						('$protocolo','$autor','$instituicao',
						'$pais','$uf','$cidade',
						1,'$titulacao','$email')";
					$rlt = db_query($sql);
					return(1);
				}
			return(0);
		}
	function remover_autor($protocolo,$autor)
		{
			$sql = "delete from ".$this->tabela_autor_trabalho."
				where sma_protocolo = '".$protocolo."' and id_sma = ".round($autor);
			$rlt = db_query($sql);
		}
	
		
	function top_menu()
		{
			global $path;
			$sx = '';
			$sx .= '<font class="menu_title">'.msg('main_menu').'</font>';
			$sx .= '<BR><BR>';
			$sx .= '<a href="'.http.'pb/index.php/'.$path.'?dd99=submit" class="menu_item">home</A>';
			return($sx);
		}
	function mostra_autor($page='')
		{
			if (strlen($page)==0)
				{
					$page = 'editar.php';
				}
			$sx .= '<BR><BR>';
			$sx .= '<font class="menu_title">'.msg('author_information').'</font><BR><BR>';
			$sx .= '<table class="tabela00" width="100%" border=0 cellpadding=0 cellspacing=1 >';
			$sx .= '<TR>';
			$sx .= '<TD><B>'.msg('author').' '.$this->author_nome.'</B> ';
			$sx .= '&lt;'.trim($this->author_line['sa_email']).'>';
			$sx .= '<TR><TD>::';
			$sx .= trim($this->author_line['sa_instituicao_text']);
			
			$sx .= '<TR><TD align="left" colspan=5>';
			$sx .= '<A HREF="'.$page.'" class="link">';
			$sx .= msg('edit');
			$sx .= '</A>';
			
			$sx .= '</table>';
			$sx .= '<BR><BR>';
			return($sx);
		}
	function le_autor($id)
		{
			$sql = "select * from ".$this->tabela_autor." 
					where sa_codigo = '".$id."' or id_sa = ".round($id);
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					$this->author_line = $line;
					$this->author_nome = trim($line['sa_nome']);
					$this->$author_codigo = trim($line['sa_codigo']);
				}
		}
		
	function email_exists($email)
		{
			global $dd;
			$email = lowercase($email);
			$sql = "select * from ".$this->tabela_autor." 
					where sa_email = '$email'
					limit 1
			";
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
			{
				$ativo = $line['sa_ativo'];
				if ($ativo == 1)
					{
						$sx .= '<font color="red">'.msg("already_email").'</font><BR>';
						$sx .= '<B>'.trim($line['sa_nome']).' &lt;'.trim($line['sa_email']).'>';
						$sx .= '</font>';
						$err = '';
					} else {
						$dd[0] = $line['id_sa'];
						$err = '1';
					}
			} else {
				$sx = ''; 
				$err = '1';
			}
			$this->erro = $sx;
			return($err);
		}
	function cp_user_new()
		{
			global $tabela;
			$tabela = $this->tabela_autor;

			$cp = array();
			array_push($cp,array('$H8','id_sa','',False,TRUE));
			array_push($cp,array('$H8','','',True,True));
			array_push($cp,array('$HV','','01',True,True));
			array_push($cp,array('$A8','',msg('persona_information'),False,True));
			array_push($cp,array('$S100:submit_autor','sa_email',msg('email'),True,True));
			array_push($cp,array('$ALERT','',$this->erro,False,True));
			array_push($cp,array('$S100','sa_nome',msg('name'),True,True));
			array_push($cp,array('$Q ap_tit_titulo:ap_tit_codigo:select * from apoio_titulacao where at_tit_ativo = 1 order by ap_tit_titulo','sa_titulo',msg('titulation'),True,True));
			array_push($cp,array('$S100','sa_lattes',msg('lattes'),False,True));
			array_push($cp,array('$H8','sa_codigo','',False,True));
			
			array_push($cp,array('$A8','',msg('institutional_afiliation'),False,True));
			array_push($cp,array('$SA100:ajax_instituicao.php:','sa_instituicao_text',msg('afiliation'),True,True));
			array_push($cp,array('$CITY','sa_pais_texto',msg('location'),True,True));
			array_push($cp,array('$UF','sa_estado_texto',msg('state'),True,True));
			array_push($cp,array('$S40','sa_cidade_texto',msg('location_city'),True,True));
			array_push($cp,array('$S100','sa_endereco',msg('address'),True,True));
			
			array_push($cp,array('$PHONE','sa_fone_1',msg('phone'),False,True));
			
			
			array_push($cp,array('$A8','',msg('password'),False,True));
			array_push($cp,array('$P20','sa_senha',msg('senha'),True,True));
						
			array_push($cp,array('$U8','sa_dt_cadastro','',True,True));
			array_push($cp,array('$HV','sa_ativo','01',True,True));			
			return($cp);
		}		
		
	function cp_user()
		{
			global $tabela;
			$tabela = $this->tabela_autor;
			echo $tabela;
			$cp = array();
			array_push($cp,array('$H8','id_sa','',False,TRUE));
			array_push($cp,array('$SA100:submit_autor','sa_email',msg('email'),True,True));
			array_push($cp,array('$S100','sa_nome',msg('name'),True,True));
			array_push($cp,array('$H8','sa_codigo','',True,True));
			array_push($cp,array('$D8','sa_nasc',msg('born'),True,True));
			array_push($cp,array('$Q ap_tit_titulo:ap_tit_codigo:select * from apoio_titulacao where at_tit_ativo = 1 order by ap_tit_titulo','sa_titulo',msg('titulation'),True,True));
			array_push($cp,array('$U8','sa_dt_cadastro','',True,True));
			array_push($cp,array('$S100','sa_lattes','Link para o Lattes',False,True));
			
			array_push($cp,array('$S100','sa_pais','Pais',False,True));
			array_push($cp,array('$S100','sa_estado','Estado',False,True));
			array_push($cp,array('$S100','sa_cidade','Cidade',False,True));
			array_push($cp,array('$S100','sa_bairro','Bairro',False,True));
			
			
			array_push($cp,array('$S100','sa_instituicao','',False,True));
			
			array_push($cp,array('$CITY','sa_cidade','',True,True));
			
			array_push($cp,array('$N12','','Valor 2',False,True));
			array_push($cp,array('$I8','','Valor 1',False,True));
			
			array_push($cp,array('$B8','',msg('save'),False,True));
			
			
			
			return($cp);
		}
		
	function submit_fields($pag)
		{
			global $dd,$jid;
			$sql = "select * from submit_documento_valor limit 1";
			$rlt = db_query($sql);
			$line = db_read($rlt);
	
			$jid = strzero($jid,7);
			$pag = round($pag); if ($pag == 0) { $pag = 1; }
			$proto = $_SESSION['protocol_submit'];
			$wh = " and sub_pos = '$pag' ";
			$sql = "select * from submit_manuscrito_field 
					left join submit_documento_valor on sub_codigo = spc_codigo and spc_projeto = '".$proto."'
					where sub_journal_id = '$jid'
					$wh
					order by sub_pos, sub_ordem 
					";
			$rlt = db_query($sql);
			$cp = array();

			while ($line = db_read($rlt))
				{
					$titulo = trim($line['sub_descricao']);
					$field = trim($line['sub_field']);
					$vlr = $line['spc_content'];
					array_push($cp,array($field,'',$titulo,$line['sub_obrigatorio'],True,$line['sub_codigo'],$line['sub_id'],$vlr));
				}
			return($cp);
			
		}
	function submit_autor_acoes()
		{
			global $path,$dd,$acao;
			$status = trim($this->line['doc_status']);
			$sx = '<form method="post" action="'.http.'pb/index.php/'.$path.'">';
			$sx .= '<input type="hidden" name="dd0" value="'.$dd[0].'">';
			$sx .= '<input type="hidden" name="dd90" value="'.$dd[90].'">';
			switch ($status)
				{
					case '@': 
						$sx .= '<input type="hidden" name="dd99" value="submit_edit">';
						$sx .= '<input type="submit" value="'.msg('submit_edit').'" class="bottom_submit">';
						break;
				}
			$sx .= '</form>';
			echo $sx;
		}
		
	function submit_next_protocolo()
		{
			$sql = "select doc_protocolo from submit_documento
					order by doc_protocolo desc
					limit 1  
			";
			echo $sql;
			$rrr = db_query($sql);
			$line = db_read($rrr);
			$proto = strzero(round($line['doc_protocolo'])+1,7);
			return($proto);
		}
	function submit_save()
		{
			global $acao,$dd,$cp,$jid;
			$protocolo = $_SESSION['protocol_submit'];
			//$acao = $_POST['acao'].$_GET['acao'];
			
			for ($d=0;$d < count($cp);$d++)
				{
					$ref = trim($cp[$d][6]);
					$cod = round($cp[$d][5]);
					$vlr = trim($_POST['ddd'.$cod]);
							
					switch ($ref)
					{
						case 'TITLE': $titulo = $vlr; break;
						case 'RESUM': $resumo = $vlr; break;
						case 'KEYW1': $keyword = $vlr; break;
						case 'TIT2': $titulo_2 = $vlr; break;
						case 'RESU2': $resumo_2 = $vlr; break;
						case 'KEYW2': $keyword_2 = $vlr; break;
					}
				}
			$autor = $this->author_codigo;
			if (strlen($acao) > 0)
				{
					if ((strlen($protocolo)==0) and (strlen($titulo) > 0))
						{
							$protocolo = $this->submit_next_protocolo();
							$status = '@';
							$this->submit_insert($protocolo, $autor, $titulo, $resumo, $keyword, $status, $tipo, $jid);
						} else {
							$this->submit_update_protocolo($protocolo, $autor, $titulo, $resumo, $keyword, $status, $tipo, $jid);							
						}
				}
		}
	function submit_update_protocolo($protocolo, $autor, $titulo, $resumo, $keyword, $status, $tipo, $jid)
		{
			$data = date("Ymd");
			$hora = date("H:i");
			$sql = "select * from submit_documento where doc_protocolo = '".$protocolo."' ";
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
				$sql = "update submit_documento set 
						doc_1_titulo = '$titulo',
						doc_update = $data,
						doc_data = $data,
						doc_hora = '$hora'
			
					where doc_protocolo = '$protocolo'
				";
				$rlt = db_query($sql);
				} else {
					echo 'OPS, erro de protocolo!';
				}
			
			return(1);
			
		}
	function submit_insert($protocolo, $autor, $titulo, $resumo, $keyword, $status, $tipo, $jid)
		{
				$data = date("Ymd");
				$hora = date("H:i");
				$sql = "insert into submit_documento 
					(
						doc_1_titulo, doc_2_titulo, doc_3_titulo,
						doc_protocolo, doc_data, doc_hora,
						doc_autor_principal, doc_status, doc_tipo,
						doc_journal_id, doc_update)
						values
						('$titulo','','',
						'$protocolo',$data,'$hora',
						'$autor','$status','$tipo',
						'$jid',$data)
					 ";
				$rlt = db_query($sql);
				$_SESSION['protocol_submit'] = $protocolo;
				return(1);
		}

		function editar($cp,$tabela,$post='')
			{
				global $dd,$acao,$path,$form;
				$ddd = array();
				$saved = 1;
				array_push($cp,array('$B8','',msg('save'),false,false));
				/**
				 * Recupera informaÃ§Ãµes
				 */
				$recupera = 0;
				if (strlen($acao)==0) { $recupera = 1; }
				/**
				 * Processa
				 */
				
				if (strlen($post)==0) { $post = page(); }
				$erro = '';
				$sx .= '<form id="formulario" method="post" action="'.$post.'">'.chr(13);
				$sx .= '<table class="tabela00">';
				for ($r=0;$r < count($cp);$r++)
					{
						$required = $cp[$r][3];
						$caption = $cp[$r][3];
						
						$cod = round($cp[$r][5]);
						//echo '<BR>--->['.$recupera.']'.$cod.'=='.$cp[$r][6];
						if ($recupera == 1) 
							{
								$fld = $cp[$r][1]; 
								$ddd[$cod] = $cp[$r][7]; 
							} else {
								$ddd[$cod] = $_POST['ddd'.$cod];
							}													
						$form->name = 'ddd'.$cod;
						$form->value = $ddd[$cod];
						$sx .= $form->process($cp[$r]);
						if (($required==1) and (strlen($form->value) ==0)) 
							{
								$erro = $caption.' '.msg('is_required').'<BR>';
							 	$saved=0; 
							}
					}
				$sx .= '<input type="hidden" name="dd99" id="dd0" value="'.$dd[0].'">'.chr(13);					
				$sx .= '<input type="hidden" name="dd99" id="dd99" value="'.$dd[99].'">'.chr(13);
				$sx .= '<input type="hidden" name="acao" id="acao" value="">'.chr(13);
				$sx .= chr(13).'</table>';
				$sx .= '</form>';
				
				$this->js_submit = '<script>';
				$this->js_submit .= $form->js_submit;
				$this->js_submit .= chr(13).'</script>';
				
				$sx .= $form->js;
				$sx .= $this->js_submit;
				$this->saved = $saved;
				return($erro.$sx);
			}
	function submit_update()
		{
			global $dd,$cp,$pag;
			$autor = $this->author_codigo;
			$proto = $_SESSION['protocol_submit'];
			$sql = "delete from submit_documento_valor 
					where spc_projeto = '".$proto."' 
							and spc_pagina = '".round($pag)."'
							and spc_autor = '$autor' ";
			$rlt = db_query($sql);							
			
			for ($q=0; $q < count($cp);$q++ )
				{
					$cod = round($cp[$q][5]);
					$vlr = trim($_POST['ddd'.$cod]);
					$cod = strzero($cp[$q][5],5);
					if (strlen($vlr) > 0)
					{
						$sql = "insert into submit_documento_valor 
								(spc_codigo, spc_projeto, spc_content, 
								spc_ativo, spc_pagina, spc_autor
								) values (
								'$cod','$proto','$vlr',
								1,'$pag','$autor'
								)
						";
						$rlt = db_query($sql);
						$vlr = '';
					}
				}
			
		}

	function submit_01($pag)
		{
		global $form,$dd,$path,$cp,$acao,$ged;
		if (strlen($pag) == 0) { $pag = 1; }
		$sx .= '<h4>Submissão</h4>';
		$cp = $this->submit_fields($pag);
		$this->submit_save();
		$post = http.'pb/'.page().'/'.$path.'?dd99='.$dd[99].'&pag='.$pag;
		$tabela = 'submit_documento';
		$proto = $_SESSION['protocol_submit'];
		
		if ((strlen($proto) >0) and (strlen($acao) > 0))
			{
				$this->submit_update();
			}
		
		if ((strlen($dd[0]) >0) and (strlen($acao)==0))
			{
				/* echo '<HR>RECUPERA<HR>'; */				
			}
		
		echo $this->editar($cp,'',$post);
		
		if (($this->saved > 0) and (strlen($acao) > 0))
			{
				$page = http.'pb/index.php/'.$path.'?dd99=submit2&pag='.($pag+1);
				redirecina($page);
				echo 'SAVED';
			}
		return($sx);	
		}	
	
	function acao()
		{
			global $tab_max,$dd;
			$sta = $this->status();
			$status = trim($this->line['doc_status']);
			$acao = array();
			if ($status == '@')
				{
					array_push($acao,array(msg('cancelar'),'X'));
				} 
			$sx .= '<form method="get">'.chr(13);
			$sx .= '<input type="hidden" name="dd0" value="'.$dd[0].'">'.chr(13);
			$sx .= '<input type="hidden" name="dd90" value="'.$dd[90].'">'.chr(13);
			$sx .= '<table width="'.$tab_max.'">';
			$col = 9;
			
			for ($r=0;$r < count($acao);$r++)
				{
					if ($col >= 3)
						{
							$col = 0;
							$sx .= '<TR>';
						}
					$sx .= '<TD align="center">';
					$sx .= '<input type="submit" value="'.$acao[$r][0].'" style="width:250px; height:50px;">';
				}
			$sx .= '</form>';
			return($sx);
		}
	function relacionamento_com_autor()
		{
			$sx = '<center>';	
			$sx .= '<A HREF="javascript:newxy2(\'autor_relacionamento.php?dd0='.$this->protocolo.'\',500,300);" alt="relacionamento com autor principal">';
			$sx .= '<img src="img/icone_email.png" height="48" border=0>';
			$sx .= '</A>';
			$sx .= '<BR>';
			$sx .= '<font class="lt0">sem mensagens</font>';
			return($sx);
		}
	function relacionamento_form()
		{
			global $dd,$acao,$http;
			$sx = '
			<form method="post">
			<table width="100%">
			<TR class="lt1"><TD>Autor principal</TD><TD align="right" rowspan=2>
			<img src="img/icone_email.png" height="48" border=0>
			</TR>
			<TR><TD>'.$this->author_nome.'
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
							$link = http.'subm/relacionamento_autor.php?dd0='.$this->protocolo.'&dd1='.checkpost($this->protocolo);
							$link = '<a href="'.$link.'">'.$link.'</A>';
							$dd[2] .= $link;
							
							$this->relacionamento_grava($dd[1],$texto);
							$email = 'monitoramento@sisdoc.com.br';
							enviaremail($email,'',$this->protocolo.' - '.$dd[1],$dd[2]);
							//echo '<BR>Enviado para '.$email;
							enviaremail($this->autor_email,'',$this->protocolo.' - '.$dd[1],$dd[2]);
							$email = trim($this->$admin_email);
							
							if (strlen($email) > 0)
								{
									enviaremail($email,'','[REol] '.$this->protocolo.' - '.$dd[1],$dd[2]);
								}
							
							$sx = '<CENTER><H2>e-mail enviado com sucesso!</center>';
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
		
		function submit_type()
			{
				global $LANG,$jid;
				$sql = "select * from submit_manuscrito_tipo ";
				$sql .= " where sp_ativo = 1 ";
				$sql .= " and journal_id = ".$jid;
				$sql .= " and sp_idioma = '".$LANG."'";
				$sql .= " order by sp_ordem ";
				$rlt = db_query($sql);
				
				while ($line = db_read($rlt))
				{
					//print_r($line);
					//echo '<HR>';
				}
				return(True);				
			}
		
		function history()
			{
				$id = 0;
				$sx = '';
				$sx .= '<h3>'.msg('show_history').'</h3>';
				$sx .= '<table width="100%" class="lt1" border=1 cellpadding=2 cellspacing=0>';
				$sx .= '<TR>';
				$sx .= '<TH width="10%">'.msg('date');
				$sx .= '<TH width="90%">'.msg('history');
				if ($id == 0)
					{ $sx .= '<TR><TD colspan=2>'.msg('not_register_found'); }
				$sx .= '</table>';
				return($sx);
			}
		
		function mostra()
			{
				//print_r($this->line);
				$line = $this->line;
				$sx = '';
				$sx .= '<table class="lt1" width="100%" border=0 cellpadding=0 cellspacing=0>';
				$sx .= '<TR valign="top">';
				$sx .= '<TD colspan=1 class="lt0">'.msg('journal');
				$sx .= '<TD colspan=3 class="lt0">'.msg('journal_title');
				$sx .= '<TD colspan=1 width="6%" class="lt0">'.msg('subm_protocol');
				
				
				$sx .= '<TR valign="top">';
				$sx .= '<TD rowspan=16 width="100">';
				$path = trim($line['path']);
				$file = http."editora/img_edicao/capa_$path.png";
				$sx .= '<nobr><IMG SRC="'.$file.'" width="70">&nbsp;&nbsp;&nbsp;&nbsp;</nobr>';
				$sx .= '<TD colspan=3 class="subm_table" ><B><font class="lt1">'.$line['title'].'</font></B>';							
				$sx .= '<TD colspan=1 class="subm_table" align="center" height="12px">'.$line['doc_protocolo'].'';
				
				$sx .= '<TR>';								
				$sx .= '<TD colspan=3 class="lt0">'.msg('subm_title');
				

				$sx .= '<TR valign="top">';
				$sx .= '<TD colspan=3 class="subm_table" rowspan=3 ><B><font class="lt4">'.$line['doc_1_titulo'].'</font></B>';
				
				$sx .= '<TR><TD colspan=1 width="6%" class="lt0">'.msg('subm_posted');
				$sx .= '<TR><TD colspan=1 class="subm_table" align="center">'.stodbr($line['doc_data']).'';
				
				$sx .= '<TR>';
				$sx .= '<TD colspan=3 width="70%" class="lt0">'.msg('subm_author');
				$sx .= '<TD colspan=1 width="6%" class="lt0"><NOBR>'.msg('subm_language').'</nobr>';
				
				$sx .= '<TR>';
				$sx .= '<TD colspan=3 class="subm_table">'.$line['sa_nome'].'';
				$sx .= '<TD colspan=1 class="subm_table" align="center">'.msg($line['doc_1_idioma']).'';
				
				$sx .= '<TR>';
				$sx .= '<TD colspan=2 width="50%" class="lt0">'.msg('email');
				$sx .= '<TD colspan=2 width="50%" class="lt0">'.msg('email_alt');

				$sx .= '<TR>';
				$sx .= '<TD colspan=2 class="subm_table">'.$line['sa_email'].'';
				$sx .= '<TD colspan=2 class="subm_table">'.$line['sa_email_alt'].'';

				

				$sx .= '</table>';
				return($sx);
			}
		
		function status()
			{
				$sta = array('@'=>'in_submission', 'N'=>'not_approved','X'=>'canceled');
				return($sta);				
			}
		
		function mostra_total($vlr=0)
			{
				$sx = number_format($vlr,0,',','.');
				if ($sx == '0') { $sx = '-'; }
				return($sx);
			}
			
		function resume_list($status = '')
			{
				global $jid;
				$jidr = strzero($jid,7);
				$sql = "select * from ".$this->tabela." 
					inner join journals on doc_journal_id = jnl_codigo
					where doc_autor_principal = '".$this->author_codigo."'
					and doc_journal_id = '$jidr'
					and doc_status = '$status' order by doc_data desc ";
					$rlt = db_query($sql);
					
					$sx = '<table width="100%" cellspacing=0 cellpadding=3 class="subm_table">';
					while ($line = db_read($rlt))
						{
							$sx .= $this->submit_detalhe($line);
						}
					$sx .= '</table>';
					return($sx);
			}
		function submit_detalhe($line)
			{
				global $path;
				$sta = $this->status();
				if (strlen($path) > 0)
					{
						$link = http.'pb/index.php/'.$path;
						$link = '<A HREF="'.$link.'?dd0='.$line['id_doc'].'&dd99=submit_detalhes&dd90='.checkpost($line['id_doc']).'">';
					} else {
						$link = page();
						$link = troca($link,'.php','_detalhes.').'php';
						$link = '<A HREF="'.$link.'?dd0='.$line['id_doc'].'&dd90='.checkpost($line['id_doc']).'">';
					}
				
				$sx .= '<TR>';
				$sx .= '<TD rowspan=2>';
				$path = trim($line['path']);
				$file = http."editora/img_edicao/capa_$path.png";
				$title = trim($line['doc_1_titulo']);
				if (strlen($title)==0) { $title = msg('no_title'); }
				
				
				$sx .= '<img src="'.$file.'" height="50">';				
				$sx .= '<TD colspan=5 class="lt2"><B>'.$link.$title.'</A>';
				
				$sx .= '<TR class="lt0">';
				$sx .= '<TD width="3%"><nobr>'.$line['doc_protocolo'];
				$sx .= '<TD>'.trim($line['title']);
				$sx .= '<TD>'.msg($sta[trim($line['doc_status'])]);
				$sx .= '<TD align="right">'.stodbr($line['doc_data']);
				$sx .= ' '.$line['doc_hora'];
								
				
				$sx .= '<TR class="lt2">';
				$sx .= '<TD colspan=5>&nbsp;';
				
				return($sx);				
			}
			
		
		function resume()
			{
				global $link,$jid;
				$jidr = strzero($jid,7);
				$sql = "select count(*) as total, doc_status from ".$this->tabela." 
					where doc_autor_principal = '".$this->author_codigo."' 
					and doc_journal_id = '$jidr'
					group by doc_status ";
				
				$rlt = db_query($sql);
				$res = array(0,0,0,0,0,0);
				$linkx = array('','','','','','');
				
				if (strlen($link) > 0)
					{
						$page = $link.'&dd99=submit_resume';
					} else {
						$page = 'submit_resume.php?SUB=1';
					}
				
				while ($line = db_read($rlt))
					{
						$total = $line['total'];
						$sta = trim($line['doc_status']);
						if ($sta == '@') { $res[0] = $res[0] + $total; $linkx[0] = '<A href="'.$page.'&dd1=@" class="linkG">'; }
						if ($sta == 'N') { $res[4] = $res[4] + $total; $linkx[4] = '<A href="'.$page.'&dd1=N" class="linkG">';  }
						if ($sta == 'X') { $res[6] = $res[6] + $total; $linkx[6] = '<A href="'.$page.'&dd1=X" class="linkG">';  }				
					}
				
				$cols = 5;
				if ($res[3] > 0)
					{ $cols++; }
				$size = round(100/$cols);
				$sx .= '<table class="subm_resume" width="100%" cellpadding=2 cellspacing=4 border=0>';
				$sx .= '<TR class="subm_resume_th">';
				$sx .= '<TH width="'.$size.'" align="center">'.msg('in_submission');
				$sx .= '<TH width="'.$size.'" align="center">'.msg('submitted');
				$sx .= '<TH width="'.$size.'" align="center">'.msg('in_analysis');
				if ($res[3] > 0) { $sx .= '<TH width="'.$size.'" align="center">'.msg('need_corrections'); }
				$sx .= '<TH width="'.$size.'" align="center">'.msg('not_approved');
				$sx .= '<TH width="'.$size.'" align="center">'.msg('approved');
				if ($res[6] > 0) { $sx .= '<TH width="'.$size.'" align="center">'.msg('canceled'); }
				
				
				
				$sx .= '<TR align="center" class="lt6">';
				$sx .= '<TD>'.$linkx[0].$this->mostra_total($res[0]).'</A>';
				$sx .= '<TD>'.$linkx[1].$this->mostra_total($res[1]).'</A>';
				$sx .= '<TD>'.$linkx[2].$this->mostra_total($res[2]).'</A>';
				if ($res[3] > 0) { $sx .= '<TD>'.$link[3].$this->mostra_total($res[3]).'</A>'; }
				$sx .= '<TD>'.$linkx[4].$this->mostra_total($res[4]).'</A>';
				$sx .= '<TD>'.$linkx[5].$this->mostra_total($res[5]).'</A>';
				if ($res[6] > 0) { $sx .= '<TD>'.$link[6].$this->mostra_total($res[6]).'</A>'; }
				
				$tot = $res[0]+$res[1]+$res[2]+$res[3]+$res[4]+$res[5]+$res[6];
				if ($tot > 0) { $sx .= '<TR><TD colspan=8 class="lt0">'.msg('click_to_view'); }
				
				$sx .= '</table>';
				return($sx);
			}
		
		function author_id()
			{
				$this->author_nome = $_SESSION['autor_n'];
				$this->author_email = $_SESSION['autor_e1'];
				$this->author_email_alt = $_SESSION['autor_e2'];
				$this->author_codigo = $_SESSION['autor_cod'];
				$page = trim(page());
				if (substr($page,0,6) != '_login')
				if ($redireciona==1)
					{
						if (strlen($this->author_nome) == 0) { redirecina('_login.php'); }
					}
				return(1);	
			}
			
		function  autor_valida()
			{
				$_SESSION['autor_n'] = $this->author_nome;
				$_SESSION['autor_e1'] = $this->author_email;
				$_SESSION['autor_e2'] = $this->author_email_alt;
				$_SESSION['autor_cod'] = $this->author_codigo;
				return(1); 
			}
			
		function autor_login($login,$pass)
			{
				global $page;
				if (strlen($page) ==0) { $page = 'main.php'; }
				if ((strlen($login) > 0) and (strlen($pass) > 0))
					{
						$sql = "select * from ".$this->tabela_autor." where sa_email = '".lowercase($login)."' ";
						$rlt = db_query($sql);
						if ($line = db_read($rlt))
							{
								if (trim($line['sa_senha']) == $pass)
									{
										$this->le($line['id_sa']);
										$this->autor_valida();
										redirecina($page);
									} else {
										$this->erro_senha = msg('subm_password_error');
									}
							} else {
								$this->erro_email = msg('subm_login_error'); 
							}
					} else {
						$this->erro = msg('subm_email_pass_required');
					}
					
			}

		function le_submit($id)
			{
				$sql = "select * from ".$this->tabela." 
						inner join ".$this->tabela_autor." on doc_autor_principal = sa_codigo
						left join journals on doc_journal_id = jnl_codigo
						where id_doc = ".round($id);
				$rlt = db_query($sql);
				if ($line = db_read($rlt))
					{
						$this->line = $line;
						$this->journal = $line['doc_journal_id'];
						$this->author_codigo = $line['doc_autor_principal'];
						$this->protocolo = $line['doc_protocolo'];
						$this->admin_email = $line['jn_email'];
						$this->admin_email_nome = $line['title'];
						return(1);
					}
				return(0);
			}
		
		
		function le($id)
			{
				$sql = "select * from ".$this->tabela_autor." 
						where id_sa = ".round($id);
				$rlt = db_query($sql);
				if ($line = db_read($rlt))
					{
						$this->author_codigo = $line['sa_codigo'];
						$this->author_email = $line['sa_email'];
						$this->author_email_alt = $line['sa_email_alt'];
						$this->author_nome = $line['sa_nome'];
						$this->line = $line;
						return(1);
					}
				return(0);
			}
		function autor_new()
			{
				global $dd,$acao,$path;
			}
		
		function send_password($email)
			{
				$sql = "select * from ".$this->tabela_autor."
						where sa_email = '".trim($email)."'
						limit 1
				";
				$rlt = db_query($sql);
				if ($line = db_read($rlt))
					{
						$senha = trim($line['sa_senha']);
						$nome = trim($line['sa_nome']);
						$texto = msg('Dear').' '.$nome;
						$texto .= '<BR><BR>'.msg('send_your_password');
						$texto .= '<BR><BR>'.msg('your_password').': '.$senha.'</B>';
						
						enviaremail(trim($line['ec_email_1']),'',msg('recover_password'),$texto);
						enviaremail('monitoramento@sisdoc.com.br','',msg('recover_password'),$texto);				
						echo '
						<H2><font color="white">'.msg('recover_password').'</font></H2>
						<BR>
						<font color="white">';
						echo msg('password_send_to').' '.$email;
					} else {
						echo msg('email_not_found');
					}
				
			}
		function autor_login_form($page='')
			{
				global $dd,$acao,$path;
				
				$sx = '
				<style>
					#sub_login_in
					{ font-size:20px; padding:5px; border: 1px solid #505050; width: 400px; }
					#sub_login_in:hover	{ border: 1px solide #ff5050; }
						
					#sub_pass_in
					{ font-size:20px; padding:5px; border: 1px solid #505050; width: 200px; }
					#sub_pass_in:hover { border: 1px solide #ff5050; }	
					
					#sub_submit
						{ wudth: 150px; height: 40px; }						
				</style>
				';
					
				if (strlen($page)==0) { $page = page(); }
				if (strlen($acao) > 0) { $this->autor_login($dd[2],$dd[3]); }
				
				$linkb = '<A HREF="'.http.'pb/index.php/'.$path.'?dd99=newuser" class="lt0">';
				$linka = '<A HREF="javascript:newxy(\''.http.'pb/login_password_forgot.php\',600,300);" onmouseover="returen(true);" class="lt0">';
				
				$sx .= '<table class="subm_login">';
				$sx .= '<TR><TD width="50%"><TD width="50%">';
				$sx .= '<form method="post" action="'.$page.'">';
				$sx .= '<TR><TH colspan=2>'.msg('sub_login_title');
				$sx .= '<TR><TD colspan=2 >'.msg('sub_login');
				$sx .= '<TR><TD colspan=2 ><input type="text" value="'.$dd[2].'" name="dd2" id="sub_login_in">';
				$sx .= '<TR><TD colspan=2><font class="subm_erro">'.$this->erro_email.'</font>';
				$sx .= '<TR><TD colspan=2 >'.msg('sub_pass');
				$sx .= '<TR><TD colspan=2 ><input type="password" name="dd3" id="sub_pass_in">';
				$sx .= '<TR><TD colspan=2><font class="subm_erro">'.$this->erro_senha.'</font>';
				$sx .= '<TR><TD colspan=2><input type="submit" name="acao" value="'.msg('sub_submit').'" id="sub_submit">';
				$sx .= '<TR><TD colspan=2><font class="subm_erro">'.$this->erro.'</font>';
				$sx .= '<TR><TD colspan=2 class="lt0">'.$linka.msg('forgot_password').'</A> | '.$linkb.msg('new_user').'</A></font>';
				$sx .= '<TR><TD></form>';
				$sx .= '</table>';
				return($sx);
			}

		function new_autor()
			{
				global $dd,$LANG;
				$sx = '';
				$sx .= '<table width="80%" cellspacing="3" cellpadding="2" class="lt1" border=0>';
				$sx .= '<TR><TH width="30%">'.msg('field');
				$sx .= '<TH>'.msg('value');
				$sx .= '<TR valign="top"><TD align="right">'.msg('form_full_name');
				$sx .= '<TD><input type="text" name="dd2" id="dd2" value="'.$dd[2].'" class="form01" style="width:100%;">';
				$sx .= '<div id="valid_name"><font color="red" style="display: none;">'.msg('not_use_abbrev').'</div>';
				
				/* Instituition - dd7 */				
				$sx .= '<TR valign="top"><TD align="right">'.msg('form_institution');				
				$sx .= '<TD><input type="text" name="dd7" id="dd7" value="'.$dd[7].'" class="form01" style="width:100%;">';
				$sx .= '<div id="valid_inst"><font color="red" style="display: none;">'.msg('not_use_abbrev').'</div>';
										
				
				$sx .= '<TR valign="top"><TD align="right">'.msg('form_email');				
				$sx .= '<TD><input type="text" name="dd3" id="dd3" value="'.$dd[3].'" class="form01" style="width:100%;">';
				$sx .= '<div id="email_exists"><font color="red" style="display: none;">'.msg('email_already_exist').'</div>';
				
				$sx .= '<TR valign="top"><TD align="right">'.msg('form_email_alt');				
				$sx .= '<TD><input type="text" name="dd4" id="dd4" value="'.$dd[4].'" class="form01" style="width:100%;">';
				
				/* Passowrd - dd8 */				
				$sx .= '<TR valign="top"><TD align="right">'.msg('form_password');				
				$sx .= '<TD><input type="password" name="dd8" id="dd8" value="'.$dd[7].'" class="form01" style="width:150px;">';

				/* titulacao - dd10 */ 
				$sql = "select * from apoio_titulacao order by ap_tit_titulo ";
				$rlt = db_query($sql);

				$sx .= '<TR valign="top"><TD align="right">'.msg('academy_graduation');				
				$sx .= '<TD><select name="dd10" id="dd10">';
				$sx .= '<option value=""></option>';
				
				while ($line = db_read($rlt))
					{
						$sx .= '<option value="'.$line['ap_tit_codigo'].'">'.msg(trim($line['ap_tit_titulo'])).'</option>';		
					}				
				$sx .= '</selection>';

				/* lattes - dd5 */				
				$sx .= '<TR><TD align="right">'.msg('form_lattes');				
				$sx .= '<TD><input type="text" name="dd5" id="dd5" value="'.$dd[5].'" class="form01" style="width:100%;">';
				
				/* Country - dd11 */
				$LANG = 'pt_BR';
				$sql = "select * from ajax_pais where pais_ativo > 0
						and pais_idioma = '$LANG' 
						order by pais_ativo desc, pais_nome ";
				$rlt = db_query($sql);
				
				$sx .= '<TR><TD align="right">'.msg('coutry_name');				
				$sx .= '<TD><select name="dd11" id="dd11">';
				$sx .= '<option value=""></option>';
				
				while ($line = db_read($rlt))
					{
						$sx .= '<option value="'.$line['pais_codigo'].'">'.trim($line['pais_nome']).'</option>';		
					}				
				$sx .= '</selection>';
				
				/* Address - dd6 */				
				$sx .= '<TR valign="top"><TD align="right">'.msg('form_biography');				
				$sx .= '<TD><textarea rows=5 cols=60 name="dd6" id="dd6" class="form01" style="width:100%;">';
				$sx .= $dd[6];
				$sx .= '</textarea>';

				/* lattes - dd5 */				
				$sx .= '<TR><TD align="right">';				
				$sx .= '<TD><input type="button" name="dd50" id="dd50" value="'.msg('form_new_user').'">';
		
				$sx .= '<TR><TD><div id="results"></div>';
				$sx .= '</table>';
				
				$sx .= '
						<script>
							$("#dd50").click(function () {
								var vdd2 = $("#dd2").val();
								var vdd3 = $("#dd3").val();
								var vdd4 = $("#dd4").val();
								var vdd5 = $("#dd5").val();
								var vdd6 = $("#dd6").val();
								var vdd7 = $("#dd7").val();
								var vdd8 = $("#dd8").val();
								var vdd10 = $("#dd10").val();
								var vdd11 = $("#dd11").val();
								
								$.ajax({ type: "POST", url: "_login_new_user_valid.php", 
									data: { dd2: vdd2, dd3: vdd3, dd4: vdd4,
									dd5: vdd5, dd6: vdd6, dd7: vdd7,
									dd8: vdd8, dd10: vdd10, dd11: vdd11
									 }
								}).done(function( html ) { $("#results").html(html); });							
							});
						</script>
						';
				return($sx);
			}
		
		function form_autores()
			{
				global $LANG;
				$LANG = 'pt_BR';
				
				/* Botao */
				$bt_submit = msg('adicionar');
				
				$sx .= '<div id="autor">';
				$sx .= '<table width="704" class="lt0" cellpadding=0 cellspacing=2>';
				/* Autores */
				$sx .= '<TR><TD colspan=3>Autores';
				$sx .= '<TR><TD colspan=3><input type="text" size="80" value="" id="author_name" class="form_input_01">';
				
				/* Titulacao */
				$sx .= '<TR><TD width="20%">Titulação<TD width="2%"><TD width="78%">e-mail';
				$sx .= '<TR><TD><select id="author_form" class="form_input_01">';
				$sx .= '<option value=""></option>'.chr(13);
				
				$sql = "select * from apoio_titulacao where at_tit_ativo = 1 and ap_tit_idioma = '$LANG' order by ap_tit_titulo";
				$rlt = db_query($sql);
				while ($line = db_read($rlt))
					{
						$sx .= '<option value="'.$line['ap_tit_codigo'].'">'.trim($line['ap_tit_titulo']).'</option>';
						$sx .= chr(13);
					}
				$sx .= '</select>'.chr(13);
				
				$sx .= '<TD>&nbsp;';
				
				/* e=mail */
				$sx .= '<TD><input type="text" size="50" value="" id="author_email" class="form_input_01_lower">';

				/* Afiliacao institucional */
				$sx .= '<TR><TD colspan=3>Afiliação Instituicional';
				$sx .= '<TR><TD colspan=3><input type="text" size="80" value="" id="author_inst" class="form_input_01">';
				
				/* Pais */
				$sx .= '<TR><TD colspan=3>Pais';
				
				$sx .= '<TR><TD><select id="author_coun" class="form_input_01">';
				$sx .= '<option value=""></option>'.chr(13);
				
				$sql = "select * from ajax_pais where pais_ativo = 1 and pais_idioma = '$LANG' order by pais_nome";
				$rlt = db_query($sql);
				while ($line = db_read($rlt))
					{
						$check = '';
						if (trim($line['pais_codigo'])=='0000001') { $check = 'selected'; }
						$sx .= '<option value="'.$line['pais_codigo'].'" '.$check.'>'.trim($line['pais_nome']).'</option>';
						$sx .= chr(13);
					}
				$sx .= '</select>'.chr(13);				
				
				$sx .= '<TD>&nbsp;<TD colspan=3><input type="button" value="'.$bt_submit.'" id="author_submit">';
				
				$sx .= '</table>';
				$sx .= '</div>';
				
				$sx .= chr(13);
				$sx .= '<div id="author_result">'.chr(13);
				$sx .= '</div>'.chr(13);
				
				$js .= chr(13);
				$js .= '<script>'.chr(13);
				$js .= '$("#author_submit").click( function() {'.chr(13);
				$js .= '
						var tela = wait();
				
						var dd10=$("#author_name").val();
						var dd11=$("#author_form").val();
						var dd12=$("#author_email").val();
						var dd13=$("#author_inst").val();
						var dd14=$("#author_coun").val();						
						var acao = "adicionar";
						var tabela = "author_submit";
						
						$.ajax({
							type: "POST",
  							url: "subm_ajax.php",
  							cache: false,
  							data: { dd50: acao, dd51: tabela,
  									dd1: dd10, dd2: dd11, dd3: dd12,
  									dd4: dd13, dd5: dd14 
							}
						}).done(function( html ) {
  						$("#author_result").html(html);
						});				
				'.chr(13);
				$js .= 'var tela = nowait();'.chr(13);
				$js .= '});'.chr(13);
				
				$js .= '</script>'.chr(13);
				$js .= chr(13);
				
				$sx .= $js;
				return($sx);
			}
	}
?>

