<?php
class submit
	{
		var $tabela = 'reol_submit';
		var $tabela_autor = 'submit_autor';
		var $author_nome = '';
		var $author_codigo = '';
		var $author_email = '';
		var $author_email_alt = '';
		var $erro_email;
		var $erro_senha;
		var $erro;
		
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
	function mostra_arquivos_autor()
		{
			$sx .= '<fieldset><legend>Arquivos</legend>';
			$sx .= 'Nenhum arquivo disponível';
			$sx .= '</fieldset>';
		}
	function relacionamento_form()
		{
			global $dd,$acao,$http;
			$sx .= '
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
							$link = http.'subm/relacionamento_autor.php?dd0='.$this->protocolo.'&dd1='.checkpost($this->protocolo);
							$link = '<a href="'.$link.'">'.$link.'</A>';
							$dd[2] .= $link;
							
							$this->relacionamento_grava($dd[1],$texto);
							enviaremail('monitoramento@sisdoc.com.br','',$this->protocolo.' - '.$dd[1],$dd[2]);
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
		
		
		function mostra_total($vlr=0)
			{
				$sx = number_format($vlr,0,',','.');
				if ($sx == '0') { $sx = '-'; }
				return($sx);
			}
		
		function resume()
			{
				$res = array(0,0,0,0,0);
				$cols = 5;
				$size = round(100/$cols);
				$sx .= '<table class="lt1">';
				$sx .= '<TR>';
				$sx .= '<TH width="'.$size.'">'.msg('in_submission');
				$sx .= '<TH width="'.$size.'">'.msg('submitted');
				$sx .= '<TH width="'.$size.'">'.msg('in_analysis');
				$sx .= '<TH width="'.$size.'">'.msg('need_corrections');
				$sx .= '<TH width="'.$size.'">'.msg('not_approved');
				$sx .= '<TH width="'.$size.'">'.msg('approved');
				$sx .= '<TR align="center" class="lt4">';
				$sx .= '<TD>'.$this->mostra_total($res[0]);
				$sx .= '<TD>'.$this->mostra_total($res[0]);
				$sx .= '<TD>'.$this->mostra_total($res[0]);
				$sx .= '<TD>'.$this->mostra_total($res[0]);
				$sx .= '<TD>'.$this->mostra_total($res[0]);
				$sx .= '<TD>'.$this->mostra_total($res[0]);
				$sx .= '</table>';
				echo '333';
				return($sx);
			}
		
		function author_id($redireciona=1)
			{
				$this->author_nome = $_SESSION['autor_n'];
				$this->author_email = $_SESSION['autor_e1'];
				$this->author_email_alt = $_SESSION['autor_e2'];
				$this->author_codigo = $_SESSION['autor_cod'];
				if ($redireciona==1)
					{
						if (strlen($this->author_nome) == 0) { redirecina('_login.php'); }
					}
				
			}
			
		function  autor_valida()
			{
				$_SESSION['autor_n'] = $this->author_nome;
				$_SESSION['autor_e1'] = $this->author_email;
				$_SESSION['autor_e2'] = $this->author_email_alt;
				$_SESSION['autor_cod'] = $this->author_codigo;
				return(1); 
			}
			
		function autor_login($login,$pass,$page='')
			{
				if ((strlen($login) > 0) and (strlen($pass) > 0))
					{
						$sql = "select * from ".$this->tabela_autor." where sa_email = '".lowercase($login)."' ";
						$rlt = db_query($sql);
						if ($line = db_read($rlt))
							{
								if (trim($line['sa_senha']) == $pass)
									{
										if (strlen($page)==0)
											{ $page = 'main.php'; }
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
		
		function le($id)
			{
				$sql = "select * from ".$this->tabela_autor." where id_sa = ".round($id);
				$rlt = db_query($sql);
				if ($line = db_read($rlt))
					{
						$this->author_codigo = $line['sa_codigo'];
						$this->author_email = $line['sa_email'];
						$this->author_email_alt = $line['sa_email_alt'];
						$this->author_nome = $line['sa_nome'];
						return(1);
					}
				return(0);
			}
		
		function autor_login_form($page='')
			{
				global $dd,$acao;
				$pagex = '';
				if (strlen($page)==0) { $page = page(); $pagex = ''; } else { $pagex = $page; }
				if (strlen($acao) > 0) { $this->autor_login($dd[2],$dd[3],$pagex); }
				
				$sx .= '<table class="subm_login">';
				$sx .= '<TR><TD width="50%"><TD width="50%">';
				$sx .= '<form method="post" action="'.$page.'">';
				$sx .= '<TR><TH colspan=2>'.msg('sub_login_title');
				$sx .= '<TR><TD colspan=2 >'.msg('sub_login');
				$sx .= '<TR><TD colspan=2 ><input type="text" value="'.$dd[2].'" name="dd2" id="sub_login" style="width: 100%">';
				$sx .= '<TR><TD colspan=2><font class="subm_erro">'.$this->erro_email.'</font>';
				$sx .= '<TR><TD colspan=2 >'.msg('sub_pass');
				$sx .= '<TR><TD colspan=2 ><input type="password" name="dd3" id="sub_pass">';
				$sx .= '<TR><TD colspan=2><font class="subm_erro">'.$this->erro_senha.'</font>';
				$sx .= '<TR><TD><TD colspan=1 ><input type="radio" name="dd4" value="1" id="sub_stored">'.msg('sub_user_exist');
				$sx .= '<TR><TD><TD colspan=1 ><input type="radio" name="dd4" value="2" id="sub_new">'.msg('sub_user_new');
				$sx .= '<TR><TD align="left"><TD colspan=1 ><input type="submit" name="acao" value="'.msg('sub_submit').'" id="sub_submit">';
				$sx .= '<TR><TD colspan=2><font class="subm_erro">'.$this->erro.'</font>';
				$sx .= '<TR><TD></form>';
				$sx .= '</table>';
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
