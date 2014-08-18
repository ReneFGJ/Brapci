<?php
class users
	{
		var $id;
		var $login;
		var $name;
		var $perfil;
		var $codigo;
		
		var $usuario_tabela = 'usuario';
		var $usuario_tabela_login = 'us_login';
		var $usuario_tabela_pass = 'us_senha';
		var $usuario_tabela_nome = 'us_nome';
		var $usuario_tabela_nivel = 'us_nivel';
		var $usuario_tabela_id = 'id_us';
		var $usuario_tabela_email = 'us_email';
		var $usuario_tabela_codigo = 'us_codigo';
		var $usuario_tabela_cracha = 'us_cracha';
		var $senha_md5 = 1;
		
		var $line;
	
		var $tabela = 'usuario';
		
    /**
     * Liberar Usuario
     * @return Booblean
     */
    	function le($id=0,$cracha='xxx')
		{
			$sql = "select * from ".$this->tabela." 
				where ".$this->usuario_tabela_id." = ".round($id)."
				or ".$this->usuario_tabela_codigo." = '".$cracha."' ";
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					$this->id = $line[$this->usuario_tabela_id];
					$this->login = $line[$this->usuario_tabela_login];
					$this->name = $line[$this->usuario_tabela_nome];
					$this->codigo = $line[$this->usuario_tabela_codigo];
					$this->perfil = trim($result['us_perfil']);
					
					$this->line = $line;
					return(1);
				}
			
			return(0);
		}
		
		function row()
		{
			global $tabela,$http_edit,$http_edit_para,$cdf,$cdm,$masc,$offset,$order;
		
			$cdf = array('id_us','us_login','us_nome','us_codigo','us_cracha');
			$cdm = array('ID','login','nome','codigo','cracha');
			$masc = array('','','','','','','','','');
			return(True);
		}			
		
		function send_pass_email($email)
		{
			$sql = "select * from usuario where us_email = '".$email."' ";
			$rlt = db_query($sql);
			if ($line = db_read($rlt))
				{
					$email1 = trim($line['us_email']);
					$text = msg('send_email_text');
					$text .= '<BR><BR>Password: <B>'.$line['us_senha'].'</B>';
					enviaremail('monitoramento@sisdoc.com.br','',msg('your_password',$text));
					enviaremail($email1,'',msg('your_password',$text));
					$ok = 'send_email_ok';
				} else {
					$ok = 'send_email_erro';
				}
			return($ok);
		}
				
		function LiberarUsuario()
			{
			global $secu,$perfil;

			if ((strlen($this->user_login) > 0) and ($this->user_erro > 0))
				{
				$_SESSION["user_login"] = $this->user_login;
				$_SESSION["user_nome"] = $this->user_nome;
				$_SESSION["user_nivel"] = $this->user_nivel;
				$_SESSION["user_id"] = $this->user_id;
				$_SESSION["user_perfil"] = $this->user_perfil;
				$_SESSION["user_codigo"] = $this->user_codigo;
				$_SESSION["user_perfil"] = $this->user_perfil;
				$_SESSION["user_chk"] = md5($this->user_login.$this->user_nome.$this->user_nivel.$secu);
				setcookie("user_login", $this->user_login, time()+60*60*2);
				setcookie("user_nome", $this->user_nome, time()+60*60*2);
				setcookie("user_nivel", $this->user_nivel, time()+60*60*2);
				setcookie("user_id", $this->user_id, time()+60*60*2);
				setcookie("user_perfil", $this->user_perfil, time()+60*60*2);
				setcookie("user_codigo", $this->user_codigo, time()+60*60*2);
				setcookie("user_perfil", $this->user_perfil, time()+60*60*2);
				setcookie("user_chk", md5($this->user_login.$this->user_nome.$this->user_nivel.$secu), time()+60*60*2);
				$perfil->us_codigo = $this->user_codigo;
				}
			return(True);
			}		
		
	function login($login,$pass)
		{
		global $messa;
		$login = uppercase($login);
		
		if ((strlen($login) == 0) or (strlen($pass) == 0))
			{
				$this->user_erro = -3;
				$this->user_msg = 'login_required';
				return(-3);
			} else {
				$login = troca($login,"'","´");
				$pass = troca($pass,"'","´");
				
				$sql = "select * from ".$this->usuario_tabela;
				$sql .= " where ".$this->usuario_tabela_email." = '".LowerCase($login)."' ";
				
				$resrlt = db_query($sql);
				if ($result = db_read($resrlt))
					{
						$user_senha = trim($result[$this->usuario_tabela_pass]);
						if ($result['senha_md5'] == 1) { $pass = md5($pass); }
						if ($user_senha == $pass)
							{
								$this->user_erro = 1;
								$this->user_msg = '';				
								$this->user_login = trim($result[$this->usuario_tabela_login]);
								$this->user_nome = trim($result[$this->usuario_tabela_nome]);
								$this->user_nivel = trim($result[$this->usuario_tabela_nivel]);
								$this->user_id = trim($result[$this->usuario_tabela_id]);
								$this->user_codigo = trim($result['us_codigo']);
								$this->user_perfil = trim($result['us_perfil']);
								$this->LiberarUsuario();
							} else {
								$this->user_erro = -2;
								$this->user_msg = 'password_incorrect';
							}
					} else {
							$this->user_erro = -1;
							$this->user_msg = 'login_invalid';
					}
			}
			if ($this->user_erro == 1) { $this->LiberarUsuario(); return(True); } else
			{ return(False); }
		}		
    /**
     * Limpar dados do Usuario
     * @return Booblean
     */			
		function LimparUsuario()
			{
			global $secu;
			if ((strlen($this->user_login) > 0) and ($this->user_erro > 0))
				{
				$_SESSION["user_login"] = '';
				$_SESSION["user_nome"] = '';
				$_SESSION["user_nivel"] = '';
				$_SESSION["user_chk"] = '';
				$_SESSION["user_id"] = '';
				setcookie("user_login", '', time());
				setcookie("user_nome", '', time());
				setcookie("user_nivel", '', time());
				setcookie("user_chk", '', time());
				setcookie("user_id", '', time());
				}
			return(True);
			}		
		
		function security()
			{
			global $secu,$user_login,$user_nivel,$user_nome,$user_id;
			
			$md5 = trim($_SESSION["user_chk"]);
			$nm1 = trim($_SESSION['user_login']);
			$nm2 = trim($_SESSION['user_nome']);
			$nm3 = trim($_SESSION['user_nivel']);
			$nm6 = trim($_SESSION['user_id']);
			$nm7 = trim($_SESSION['user_perfil']);
			$nm8 = trim($_SESSION['user_codigo']);
			$mt1 = 10;

			if (strlen($md5) == 0) 
				{ 
				/* Recupera por Cookie */
				$md5 = trim($_COOKIE["user_chk"]); 
				$nm1 = $_COOKIE["user_login"];
				$nm2 = $_COOKIE["user_nome"];
				$nm3 = $_COOKIE["user_nivel"];
				$nm6 = $_COOKIE['user_id'];
				$nm7 = $_COOKIE['user_perfil'];
				$nm8 = $_COOKIE['user_codigo'];
				$mt1 = 20;
				}
			$mm4 = md5($nm1.$nm2.$nm3.$secu);
			
			if ((strlen($nm1) > 0) and (strlen($nm2) > 0))
				{
				if (trim($mm4) == trim($md5))
					{
						$this->user_login = $nm1;
						$this->user_nome = $nm2;
						$this->user_nivel = $nm3;
						$this->user_id = $nm6;
						$this->user_erro = $mt1;
						$this->user_perfil = $nm7;
						$this->user_codigo = $nm8;
						$user_id = $nm6;
						$user_login = $nm1;
						$user_nivel = $nm3;
						$user_nome = $nm2;
					return(True);
					} else {
						$this->user_erro = -4;
						$this->user_msg = 'End section';
						redirecina('login.php');
						return(False);
					}
				} else {
						$this->user_erro = -5;
						$this->user_msg = 'End section';
						redirecina('login.php');
						return(False);
				}
			}		
		
		function cp()
			{
				global $messa,$dd;
				$cp = array();
				if (strlen($dd[1]) == 0) { $dd[1] = '#RES'; }
				array_push($cp,array('$H8','id_us','',False,True));
				array_push($cp,array('$HV','us_perfil','',False,True));
				array_push($cp,array('${','',msg('about_user'),False,True));
				array_push($cp,array('$M','','',False,False));
				array_push($cp,array('$S100','us_nome',msg('name'),False,True));
				array_push($cp,array('$H20','us_login',$logx,False,True));
				array_push($cp,array('$S20','us_login','Login',False,False));
				array_push($cp,array('$S8','us_cracha',msg('cracha'),False,True));
				array_push($cp,array('$S100','us_email',msg('email'),False,True));
				array_push($cp,array('$H8','us_email_alt',msg('email_alt'),False,False));
				array_push($cp,array('$P20','us_senha',msg('senha'),False,False));
				array_push($cp,array('$}','','',False,True));
				array_push($cp,array('$B8','',msg('submit'),False,True));
				return($cp);
				
			}
		function cp_myaccount()
			{
				global $messa,$dd;
				$cp = array();				
				array_push($cp,array('$H8','id_us','',False,True));
				array_push($cp,array('${','',msg('my_account'),False,True));
				array_push($cp,array('$SL100','us_nome',msg('name'),True,True));
				array_push($cp,array('$H20','us_login',msg('login'),True,True));
				array_push($cp,array('$SL100','us_email',msg('email'),True,True));
				array_push($cp,array('$SL100','us_email_alt',msg('email_alt'),False,True));
				array_push($cp,array('$S8','us_cracha',msg('cracha'),False,False));
				array_push($cp,array('$P20','us_senha',msg('senha'),True,True));
				array_push($cp,array('$HV','us_ativo','1',True,True));
				array_push($cp,array('$T60:5','us_endereco',msg('address'),True,True));
				array_push($cp,array('$Q pais_nome:pais_codigo:select * from ajax_pais order by pais_nome','us_country',msg('country'),True,True));
				array_push($cp,array('$S100','us_instituition',msg('institution'),True,True));
				array_push($cp,array('$}','','',False,True));
				return($cp);
			}	
		function cp_admin()
			{
				global $messa,$dd;
				$cp = array();				
				$op = '&-1:'.msg('not_valided');
				$op .= '&0:'.msg('inative');
				$op .= '&1:'.msg('valided');
				array_push($cp,array('$H8','id_us','',False,True));
				array_push($cp,array('${','',msg('account'),False,True));
				array_push($cp,array('$S100','us_nome',msg('name'),True,True));
				array_push($cp,array('$H20','us_login',msg('login'),True,True));
				array_push($cp,array('$S100','us_email',msg('email'),True,True));
				array_push($cp,array('$S100','us_email_alternativo',msg('email_alt'),False,True));
				array_push($cp,array('$S8','us_cracha',msg('cracha'),False,True));
				array_push($cp,array('$T60:5','us_endereco',msg('address'),False,True));
				//array_push($cp,array('$S100','us_instituition',msg('institution'),False,True));
				
				array_push($cp,array('$O : '.$op,'us_ativo',msg('status'),'1',False,False));
				array_push($cp,array('$}','','',False,True));
				array_push($cp,array('$S8','us_codigo',msg('codigo'),False,False));								
				return($cp);
			}
			
		function user_valid()
			{
				$sql = "update ".$this->tabela." set  
					us_ativo = 1,
					us_email_ativo = 1,
					us_login = 'active'
					where id_us = ".$this->id;
				$rlt = db_query($sql);
				
			}
		
		function updatex()
			{
				global $base;
				$c = 'us';
				$c1 = 'id_'.$c;
				$c2 = $c.'_codigo';
				$c3 = 7;
				$sql = "update ".$this->tabela." set $c2 = lpad($c1,$c3,0) ,
						us_login =  lpad($c1,$c3,0) ,
						us_cracha =  lpad($c1,$c3,0) 
						where $c2='' or 1=1";
				if ($base=='pgsql') { $sql = "update ".$this->tabela." set $c2 = trim(to_char(id_".$c.",'".strzero(0,$c3)."')) where $c2='' or 1=1 "; }
				$rlt = db_query($sql);				
			}
	}
?>
