<?php
ob_start();
session_start();
    /**
     * Sistema de Segurança
	 * @author Rene Faustino Gabriel Junior <renefgj@gmail.com> (Analista-Desenvolvedor)
	 * @copyright Copyright (c) 2011 - sisDOC.com.br
	 * @access public
     * @version v0.11.29
	 * @package Include
	 * @subpackage Security
     */

class usuario
	{
	var $user_login;
	var $user_nome;
	var $user_nivel;
	var $user_erro;
	var $user_msg;
	
	var $usuario_tabela = 'usuario';
	var $usuario_tabela_login = 'us_login';
	var $usuario_tabela_pass = 'us_senha';
	var $usuario_tabela_nome = 'us_nome';
	var $usuario_tabela_nivel = 'us_nivel';
	var $usuario_tabela_id = 'id_us';
	var $senha_md5 = 1;
	
    /**
     * Login do Sistema
     * @param string $login Login do usuário no sistema
     * @param string $pass  Senha do usuário no sistema
     * @return Booblean
     */
    /**
     * Gravar nova senha do Usuário
     * @return Booblean
     */
	function GravaSenha($login,$novasenha)
			{
			global $secu;
			$sql = "update ".$this->usuario_tabela." set ";
			$sql .= $this->usuario_tabela_pass . " = '".md5($novasenha)."' ";
			$sql .= " where ".$this->usuario_tabela_login." = '".$login."' ";
			$resrlt = db_query($sql);
			return(True);
			}
			
	function grava_senha($login,$senha)
		{
			$pass = md5();
			$sql = "update usuario ";
			$sql .= "set us_senha='".$pass."'";
			$sql .= "where us_login = '".$login."' ";
		}

	/**
	 * Valida login da PUCPR ou atualiza dados
	 */		
	function login_exists($login,$pass)
		{
			//$sql = "alter table usuario ADD COLUMN us_senha_md5 int2;";
			//$rlt = db_query($sql);
			$passm5 = md5(trim($pass));
			
			$sql = "select * from ".$this->usuario_tabela;
			$sql .= " where ".$this->usuario_tabela_login." = '".UpperCase($login)."' ";
			$rlt = db_query($sql);
			
			$ok = 0;
			$new = 1; /* new user */	
			if ($line = db_read($rlt))
				{
					$passx = trim($line[$this->usuario_tabela_pass]);
					$loginx = trim($line[$this->usuario_tabela_login]);
					$md5 = round($line['us_senha_md5']);
					$new = 0; /* old user */
					if (($loginx == $login) and (strlen($login) > 0) and ($md5==1))
						{
							/* Retorna se valido e senha correta */
							if ($passx == $passm5) { $ok=1; return(1); }
						}
				}
			/* Login não localizado ou não autenticado, buscando dados */
			$codigo = lowercase($login);
			$senha = $pass;
			require("admin/_pucpr_soap_autenticarUsuario.php");	
			if ($result == 'Autenticado')
				{	
					/*** Atenticado pelo sevidor */
					if ($new == 1)
					{
						$login = uppercase($login);
						/** save new user */
						$sql = "insert into ".$this->usuario_tabela."
							(".$this->usuario_tabela_login.",
							".$this->usuario_tabela_pass.",
							".$this->usuario_tabela_nome.", us_senha_md5,
							".$this->usuario_tabela_nivel."
							) values ('$login','$passm5','',1,1)";
							$rlt = db_query($sql);
					} else {
						/** update user data */
						$sql = "update ".$this->usuario_tabela."
							set ".$this->usuario_tabela_pass." = '$passm5', us_senha_md5=1 
							where ".$this->usuario_tabela_login." = '$login' ";
							$rlt = db_query($sql);
					}
					return(1);
				}			
			return(0);
		}
	/*** Autenticar usuario */
	function login($login,$pass)
		{
		$login = uppercase($login);
		
		/** Valida usuário **/		
		if ((strlen($login) == 0) or (strlen($pass) == 0))
			{
				$this->user_erro = -3;
				$this->user_msg = 'Login e Senha são necessários';
			} else {
				$login = troca($login,"'","´");
				$pass = troca($pass,"'","´");

				/** verifica se existe o login no sistema **/
				$ok = $this->login_exists($login,$pass);
				
				if ($ok == 0) 
					{
						$this->user_erro = -3;
						$this->user_msg = 'Login não encontrado';
					}

				/** Autentica Usuário */
				$sql = "select * from ".$this->usuario_tabela;
				$sql .= " where ".$this->usuario_tabela_login." = '".UpperCase($login)."' ";
				$resrlt = db_query($sql);
				if ($result = db_read($resrlt))
					{
						$user_senha = lowercase(trim($result[$this->usuario_tabela_pass]));
						if (round($result['us_senha_md5']) == 1) { $pass = md5($pass); }
						if ($user_senha == $pass)
							{
								$this->user_erro = 1;
								$this->user_msg = '';				
								$this->user_login = trim($result[$this->usuario_tabela_login]);
								$this->user_nome = trim($result[$this->usuario_tabela_nome]);
								$this->user_nivel = trim($result[$this->usuario_tabela_nivel]);
								$this->user_id = trim($result[$this->usuario_tabela_id]);
							} else {
								$this->user_erro = -2;
								$this->user_msg = 'Senha inválida';
							}
					} else {
							$this->user_erro = -1;
							$this->user_msg = 'Login inválido';
					}
			}
			if ($this->user_erro == 1) { $this->LiberarUsuario(); return(True); } else
			{ return(False); }
		}
	 
    /**
     * Liberar Usuário
     * @return Booblean
     */
		function LiberarUsuario()
			{
			global $secu;
			if ((strlen($this->user_login) > 0) and ($this->user_erro > 0))
				{
				$_SESSION["user_login"] = $this->user_login;
				$_SESSION["user_nome"] = $this->user_nome;
				$_SESSION["user_nivel"] = $this->user_nivel;
				$_SESSION["user_id"] = $this->user_id;
				$_SESSION["user_chk"] = md5($this->user_login.$this->user_nome.$this->user_nivel.$secu);
				setcookie("user_login", $this->user_login, time()+60*60*2);
				setcookie("user_nome", $this->user_nome, time()+60*60*2);
				setcookie("user_nivel", $this->user_nivel, time()+60*60*2);
				setcookie("user_id", $this->user_id, time()+60*60*2);
				setcookie("user_chk", md5($this->user_login.$this->user_nome.$this->user_nivel.$secu), time()+60*60*2);
				}
			return(True);
			}

    /**
     * Limpar dados do Usuário
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

    /**
     * Recupera dados do Usuário
     * @return Booblean
     */		
		function Security()
			{
			global $secu,$user_login,$user_nivel,$user_nome,$user_id;
			
			$md5 = trim($_SESSION["user_chk"]);
			$nm1 = trim($_SESSION['user_login']);
			$nm2 = trim($_SESSION['user_nome']);
			$nm3 = trim($_SESSION['user_nivel']);
			$nm6 = trim($_SESSION['user_id']);
			$mt1 = 10;

			if (strlen($md5) == 0) 
				{ 
				/* Recupera por Cookie */
				$md5 = trim($_COOKIE["user_chk"]); 
				$nm1 = $_COOKIE["user_login"];
				$nm2 = $_COOKIE["user_nome"];
				$nm3 = $_COOKIE["user_nivel"];
				$nm6 = $_COOKIE['user_id'];
				$mt1 = 20;
				}
				
			$mm4 = md5($nm1.$nm2.$nm3.$secu);
			if ((strlen($nm1) > 0) and (strlen($nm2.$nm1) > 0))
				{
				if ($mm4 == $md5)
					{
						$this->user_login = $nm1;
						$this->user_nome = $nm2;
						$this->user_nivel = $nm3;
						$this->user_id = $nm6;
						$this->user_erro = $mt1;
						$user_id = $nm6;
						$user_login = $nm1;
						$user_nivel = $nm3;
						$user_nome = $nm2;
					return(True);
					} else {
						$this->user_erro = -4;
						$this->user_msg = 'Fim da Sessão';
						return(False);
					}
				} else {
						$this->user_erro = -5;
						$this->user_msg = 'Fim da Sessão';
						return(False);
				}
			}
    /**
     * Fim
     */		
	}
?>