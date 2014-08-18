<?php
class submit_autor
	{
		var $id_sa;
		var $sa_codigo;
		var $sa_nome;	
		var $sa_nome_asc;
		var $sa_nome_autoridade;
		var $sa_nasc;
		var $sa_fale;
		var $sa_email;
		var $sa_email_alt;
		var $sa_titulo;
		var $sa_dt_cadastro;
		var $sa_lattes;
		var $sa_content;
		var $sa_ativo;
		var $sa_senha;
		var $sa_endereco;
		var $sa_bairro;
		var $sa_cidade;
		var $sa_estado;
		var $sa_pais;
		var $sa_cep;
		var $sa_cx_postal;
		var $sa_fone_1;
		var $sa_fone_2;
		var $sa_fone_3;
		var $sa_email_confirmado;
		var $sa_cpf;
		var $sa_cidade_texto;
		var $sa_estado_texto;
		var $sa_pais_texto;
		
		var $erro;
		var $tabela = 'submit_autor';
		
		function cp()
			{
				$tb = 'select cidade_codigo, trim(cidade_nome) || chr(32) || chr(45) || chr(32) || estado_nome as cidade_nome from ( select * from (';
				$tb .= 'select * from(select estado_codigo, pais_nome || chr(32) || chr(40) || trim(estado_nome) || chr(41) as estado_nome from ajax_estado inner join ajax_pais on estado_pais = pais_codigo ) as estado ';
				$tb .= 'inner join ajax_cidade on estado_codigo = cidade_estado ';
				$tb .= ') as cidade1 ) as cidade where (cidade_ativo = 1) order by upper(asc7(cidade_nome)) ';
	
				$cp_nome = msg('nome completo:');
	
				$cp = array();
				array_push($cp,array('$H8','id_sa','id_sa',False,True,''));
				array_push($cp,array('$H8','','checado',True,True,''));
				array_push($cp,array('$S100','sa_email',msg('e-mail'),True,True,''));
				array_push($cp,array('$H7','sa_codigo','sa_codigo',False,True,''));
				array_push($cp,array('$S100','sa_nome',$cp_nome.'*',True,True,''));
				array_push($cp,array('$H8','sa_nome_asc','sa_nome_asc',False,True,''));
				array_push($cp,array('$H8','sa_nome_autoridade','sa_nome_autoridade',False,True,''));
				//array_push($cp,array('$D8','sa_nasc','sa_nasc',False,True,''));
				//array_push($cp,array('$S10','sa_fale','sa_fale',False,True,''));
				//array_push($cp,array('$S100','sa_email','e-mail*',True,True,''));
				array_push($cp,array('$S100','sa_email_alt',msg('e-mail_alt'),False,True,''));
				array_push($cp,array('$Q ap_tit_titulo:ap_tit_titulo:select * from apoio_titulacao order by ap_tit_titulo','sa_titulo',msg('titulacao'),False,True,''));
				array_push($cp,array('$S100','sa_lattes',msg('lattes'),False,True,''));
				array_push($cp,array('$H8','sa_content',msg('biografia'),False,True,''));
				array_push($cp,array('$P20','sa_senha',msg('password').'*',True,True,''));
				array_push($cp,array('$S100','sa_endereco',msg('address'),False,True,''));
				array_push($cp,array('$S20','sa_bairro',msg('bairro'),False,True,''));
				array_push($cp,array('$S30','sa_cidade_texto',msg('city'),False,True,''));
				array_push($cp,array('$S20','sa_estado_texto',msg('state'),False,True,''));
				array_push($cp,array('$S20','sa_pais_texto',msg('counry'),False,True,''));
	
				array_push($cp,array('$CEP','sa_cep',msg('cep'),False,True,''));
				array_push($cp,array('$H10','sa_cx_postal',msg('cx_postal'),False,True,''));
				array_push($cp,array('$S20','sa_fone_1',msg('phone'),False,True,''));
				array_push($cp,array('$H20','sa_fone_2',msg('phone_res'),False,True,''));
				array_push($cp,array('$H20','sa_fone_3',msg('phone_cel'),False,True,''));
				return($cp);
			}
		function cp_user()
			{
				
			}

		function le($id)
			{
				if (strlen($id) > 0) { $this->id_sa = $id; }
				$sql = "select * from ".$this->tabela." where id_sa = ".sonumero($this->id_sa);
				$rlt = db_query($sql);
				if ($line = db_read($rlt))
					{
						$this->id_sa = $line['id_sa'];
						$this->sa_nome = $line['sa_nome'];
						$this->sa_codigo = $line['sa_codigo'];
						$this->sa_email = $line['sa_email'];
						$this->sa_email_alt = $line['sa_email_alt'];
						$this->sa_lattes = $line['sa_lattes'];
						$this->sa_ativo = $line['sa_ativo'];
						$this->sa_titulo = $line['sa_titulo'];
						return(1);
					} else {
						return(0);
					}
			}
		function login_email_valida($email)
			{
				$sql = "select * from ".$this->tabela." where sa_email = '".$email."' ";
				$rlt = db_query($sql);
				if ($line = db_read($rlt))
					{
						$this->erro = msg('email_exist');
						$valido = '';
					} else {
						$this->erro = '';
						$valido = '1';
					}
				return($valido);
			}
		function login_id()
			{
				global $user_id,$user_name,$user_email,$user_email_alt,$user_cod;
				$user_id = $_SESSION['id'];
				$user_name = $_SESSION['user'];
				$user_email = $_SESSION['email1'];
				$user_email_alt = $_SESSION['email2'];
				$user_cod = $_SESSION['cod'];
				if (strlen($user_id) == 0)
					{
						$pag = ' '.$_SERVER['SCRIPT_NAME'];
						while (strpos($pag,'/'))
							{ $pag = ' '.substr($pag,strpos($pag,'/')+1,200); }
						$pag=trim(substr($pag,1,5));
						if ($pag != 'login') { redirecina('login.php'); exit; } 
					}
				return(1);
			}
		function login_messagem($er)
			{
				switch ($er)
					{
					case -1: { $msg = 'erro desconhecido'; break; }
					case 1: { $msg = msg('email_not_exist'); break; }
					case 2: { $msg = msg('incorret_password'); break; }
					case 3: { $msg = msg('no_password'); break; }
					case 4: { $msg = msg('no_email'); break; }
					}
				return($msg);
			}
		function login_sessao_stop()
			{
				$_SESSION['user'] = '';
				$_SESSION['cod'] = '';
				$_SESSION['id'] = '';
				$_SESSION['email1'] = '';
				$_SESSION['email2'] = '';
				redirecina('login.php');
			}			
		function login_sessao_start()
			{
				$_SESSION['user'] = $this->sa_nome;
				$_SESSION['cod'] = $this->sa_codigo;
				$_SESSION['id'] = $this->id_sa;
				$_SESSION['email1'] = $this->sa_email;
				$_SESSION['email2'] = $this->sa_email_alt;
				redirecina('main.php');
			}
		function login_valida()
			{
				global $dd;
				$er = -1;
				
				if ((strlen($dd[10]) > 0) and (strlen($dd[11]) > 0))
					{				
					$sql = "select * from ".$this->tabela." ";
					$sql .= " where sa_email = '".$dd[10]."' ";
					$sql .= " or sa_email_alt = '".$dd[10]."' ";
					$rlt = db_query($sql);
					if ($line = db_read($rlt))
						{
							if ((trim($line['sa_senha']) == $dd[11]) and ($dd[11] == trim($line['sa_senha'])))
								{
									$er = 0; 
									$this->le($line['id_sa']);
									$this->login_sessao_start(); 
								}
							else 
								{ $er = 2; }
						} else {
							$er = 1;
						}
					} else {
						if ((strlen($dd[11]) == 0) and (strlen($dd[10]) > 0)) { $er = 3; }
						if ((strlen($dd[10]) == 0) and (strlen($dd[11]) > 0)) { $er = 4; }
					}
				return($er);
			}
		function login_type_01()
			{
				global $dd,$messa;
				$erro = $this->login_valida();
				$msg = $this->login_messagem($erro);
				
				/**** Mensagens **/
				$mess = '<UL><LI><A HREF="login_register.php">'.msg('want_assign').'</A></LI>';
				if ($erro > 0)
					{
						if ($erro == 1) { $mess .= '<LI><font color="red">'.$this->login_messagem($erro).'</font></LI>'; }
						if ($erro == 2) { $mess = '<UL><LI><font color="red">'.$this->login_messagem($erro).'</font></LI><LI><A HREF="login_forgot.php">'.msg('forgot_password').'</A></LI>'; }
						if ($erro == 3) { $mess .= '<LI><font color="red">'.$this->login_messagem($erro).'</font></LI>'; }
					}
				$size = 350;
				$sx .= '<style>'.chr(13);
				$sx .= '#input {'.chr(13);
				$sx .= 'background-color: #FDFDFD;'.chr(13);
				$sx .= 'border: thin solid Gray;'.chr(13);
				$sx .= 'font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;'.chr(13);
				$sx .= 'font-size: 14px;'.chr(13);
				$sx .= 'font-style: normal;'.chr(13);
				$sx .= 'font-weight: bold;'.chr(13);
				$sx .= '}'.chr(13);				
				$sx .= '</style>'.chr(13);
				
				$sx .= '<table width='.($size+40).' cellpadding=0 cellspacing=0 border=0 align="center">'.chr(13);
				$sx .= '<TR><TD>'.chr(13);
				$sx .= '<fieldset><legend>'.chr(13);
				$sx .= msg('system_login').chr(13);
				$sx .= '</legend>'.chr(13);
					$sx .= '<form method="post" action="login.php">'.chr(13);
					$sx .= '<table width='.$size.' cellpadding=0 cellspacing=0 border=0 align="center">'.chr(13);
					$sx .= '<TR><TD>&nbsp;'.chr(13);
					$sx .= '<TR><TD>'.chr(13);
					$sx .= '<font class="lt0">'.msg('login').chr(13);
					$sx .= '<TR><TD>'.chr(13);
					$sx .= '<input type="text" size=30 id="input"  maxlength=100 name="dd10" value="'.$dd[10].'">'.chr(13);
					$sx .= '<TR><TD>&nbsp;'.chr(13);
					$sx .= '<TR><TD>'.chr(13);
					$sx .= '<font class="lt0">'.msg('password').chr(13);
					$sx .= '<TR><TD>'.chr(13);
					$sx .= '<input type="password" id="input" size=15 maxlength=20 name="dd11" value="">'.chr(13);
					$sx .= '<TR><TD>&nbsp;'.chr(13);
					$sx .= '<TR><TD align="right">'.chr(13);
					$sx .= '<input type="submit" name="dd12" value="'.msg('submit').'">'.chr(13);
					$sx .= '<TR><TD align="left">'.chr(13);
					$sx .= $mess;
					$sx .= '</table>'.chr(13);
					$sx .= '</form>'.chr(13);
				$sx .= '</table>'.chr(13);
				$sx .= chr(13);
				return($sx);
			}
		function updatex()
			{
				
			}
	}
