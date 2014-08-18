<?php
/**
 * Class Autor
 * @category SistemaApoio
 * @author Rene Faustino Gabriel Junior <monitoramento@sisdoc.com.br>
 * @copyright Copyright (c) 2011 - sisDOC.com.br
 * @license http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @package Classe
 * @version 0.12.04
 */
class author
	{
		var $id_sa;
		var $sa_codigo;
		var $sa_nome;
		var $sa_nome_asc;
		var $sa_nome_autoridade;
		var $sa_instituicao;
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
		var $sa_cep;
		var $sa_cx_postal;
		var $sa_fone_1;
		var $sa_fone_2;
		var $sa_fone_3;
		var $sa_email_confirmado;
		var $sa_cpf;
		var $sa_status;
		var $sa_language;
		
		var $tabela = 'submit_autor';
		
		function cp()
			{
				global $dd;
				$cp = array();
				array_push($cp,array('$H8','id_sa','key',False,True));
				array_push($cp,array('$H8','sa_codigo','',False,True));
				array_push($cp,array('$S100','sa_nome',msg('full_name'),True,True));
				array_push($cp,array('$HV','sa_nome_asc',UpperCaseSql($dd[2]),True,True));
				array_push($cp,array('$HV','sa_nome_autoridade',UpperCaseSql($dd[2]),False,True));
				array_push($cp,array('$HV','sa_status','A',False,True));

				array_push($cp,array('$QA it_nome:it_codigo:select * from institutions where it_ativo=1 order by it_nome','',msg('institution'),False,True));
				array_push($cp,array('$A8','',msg('persnal_info'),False,False));
				array_push($cp,array('$[1900-'.(date('Y')-16).']','sa_nasc',msg('ano_nasc'),True,True));
				array_push($cp,array('$S22','sa_cpf',msg('document'),False,True));
				array_push($cp,array('$T60:5','sa_content',msg('bioghafic'),False,True));

				array_push($cp,array('$H8','sa_fale','',False,True));
				array_push($cp,array('$A8','',msg('persnal_eletronic'),False,False));
				array_push($cp,array('$EMAIL','sa_email',msg('email'),True,True));
				if (strlen($dd[13])> 0)
					{
					if (($this->email_exists($dd[13])) and (strlen($dd[0]) == 0))
						{
						array_push($cp,array('$M8','','<font color=red>'.msg('email_exist'),False,True));
						} else {
						array_push($cp,array('$H8','','',False,True));
						$dd[16] = '1';
						}
					} else {
						array_push($cp,array('$H8','','',False,True));					
					}
				array_push($cp,array('$EMAIL','sa_email_alt',msg('email_alt'),False,True));
				array_push($cp,array('$HV','','',True,True));
				array_push($cp,array('$S100','sa_lattes',msg('url'),False,True));
				array_push($cp,array('$P20','sa_senha',msg('password'),True,True));
				
				array_push($cp,array('$A8','',msg('persnal_address'),False,False));
				array_push($cp,array('$U8','sa_dt_cadastro','',False,True));
				array_push($cp,array('$HV','sa_ativo',1,False,True));
				array_push($cp,array('$S100','sa_endereco',msg('address'),False,True));
				array_push($cp,array('$S30','sa_bairro',msg('block'),False,True));
				array_push($cp,array('$S8','sa_pais',msg('country'),False,True));
				array_push($cp,array('$S8','sa_cidade',msg('city'),False,True));
				array_push($cp,array('$UF','sa_estado',msg('state'),False,True));
				array_push($cp,array('$S10','sa_cep',msg('cep'),False,True));
				array_push($cp,array('$S10','sa_cx_postal',msg('cxpost'),False,True));
				array_push($cp,array('$S20','sa_fone_1',msg('fone1'),False,True));
				array_push($cp,array('$S20','sa_fone_2',msg('fone2'),False,True));
				array_push($cp,array('$S20','sa_fone_3',msg('fone3'),False,True));
				array_push($cp,array('$H8','sa_email_confirmado','',False,True));
				return($cp);
			}

		function logout()
			{
						$_SESSION['id'] = '';
						$_SESSION['name'] = '';
						$_SESSION['cod'] = '';
			}

		function autenticar($id,$pw)
			{
				if ((strlen($id) > 0) and (strlen($pw) > 0))
				{
				$auto = $this->valida_user($id,$pw);
		
				if ($auto == (-1)) { $msg = msg('email_incorrect'); }
				if ($auto == (-2)) { $msg = msg('password_incorrect'); }
				echo $msg;
		
				if ($auto == 0)
					{
						$ok = $this->email_exists($id);
						$this->id_sa = $ok;
						$this->le($this->id_sa);
						$_SESSION['id'] = $this->sa_codigo;
						$_SESSION['name'] = $this->sa_nome;
						$_SESSION['cod'] = substr(md5($this->sa_nome.$this->sa_codigo),5,10);
						redirecina('main.php'); 
						echo 'ops '.$this->sa_status;
						exit;	
					}
				}
			}
		function le($id='')
			{
				if (strlen($id) > 0) { $this->id_sa = $id; }
				$rsp = 0;
				$sql = "select * from ".$this->tabela." where id_sa = ".$this->id_sa;
				$rlt = db_query($sql);
				if ($line = db_read($rlt))
					{
						$rsp = 1;
						$this->id_sa = $line['id_sa'];
						$this->sa_codigo = $line['sa_codigo'];
						$this->sa_nome = $line['sa_nome'];
						$this->sa_nome_asc = $line['sa_nome_asc'];
						$this->sa_nome_autoridade = $line['sa_nome_autoridade'];
						$this->sa_instituicao = $line['sa_instituicao'];
						$this->sa_nasc = $line['sa_nasc'];
						$this->sa_fale = $line['sa_fale'];
						$this->sa_email = $line['sa_email'];
						$this->sa_email_alt = $line['sa_email_alt'];
						$this->sa_titulo = $line['sa_titulo'];
						$this->sa_dt_cadastro = $line['sa_dt_cadastro'];
						$this->sa_lattes = $line['sa_lattes'];
						$this->sa_content = $line['sa_content'];
						$this->sa_ativo = $line['sa_ativo'];
						$this->sa_senha = $line['sa_senha'];
						$this->sa_endereco = $line['sa_endereco'];
						$this->sa_bairro = $line['sa_bairro'];
						$this->sa_cidade = $line['sa_cidade'];
						$this->sa_estado = $line['sa_estado'];
						$this->sa_cep = $line['sa_cep'];
						$this->sa_cx_postal = $line['sa_cx_postal'];
						$this->sa_fone_1 = $line['sa_fone_1'];
						$this->sa_fone_2 = $line['sa_fone_2'];
						$this->sa_fone_3 = $line['sa_fone_3'];
						$this->sa_email_confirmado = $line['sa_email_confirmado'];
						$this->sa_cpf = $line['sa_cpf'];
						$this->sa_status = $line['sa_status'];
						$this->sa_language = $line['sa_language'];
					}
				return($rsp);
			}
		function cp_sp()
			{
				$cp = $this->cp();
				$dd[5] = '@';
				return($cp);
			}				
		function autor_autenticado()
			{
				$id=$_SESSION['id'];
				$name = $_SESSION['name'];
				if (strlen($id) > 0)
					{
						$this->le($id);
						return(1);
					} else {
						redirecina('login.php');
						return(0);
					}
				return(0);
			}
		function email_exists($email)
			{
				$email = lowercase($email);
				$sql = "select id_sa from submit_autor where sa_email = '".lowercase($email)."' ";
				$sql .= " or sa_email_alt = '".lowercase($email)."' ";
				$rlt = db_query($sql);
				if ($line = db_read($rlt))
					{ $ok = $line['id_sa']; } else { $ok = 0; }
				return($ok);				
			}
		function valida_user($em,$pass)
			{
				$ok = $this->email_exists($em);
				if ($ok > 0)
					{
						$this->id_sa = $ok;
						$this->le();
						if (($this->sa_senha == $pass) and ($pass == $this->sa_senha))
							{ $err = 0; } else { $err = -2; }
					} else {
						$err = -1;
					}
				return($err);
			}
		function password_send($em)
			{
				$id = $this->email_exists($em);
				$this->id_sa = $id;
				$this->le();
				
				$status = trim($this->sa_status);
				
				if ((strlen($status)==0) or ($status=='@'))
					{
						$senha = trim($this->sa_senha);
						if (strlen($senha)==0)
							{ $senha = $this->create_new_password(); }
					}
				$msg = msg('text_send_password');
				$msg = troca($msg,'$password',$senha);
				$msg = troca($msg,'$email',$em);
				
				$ema = new email;
				$ema->to = $em;
				$ema->subject = msg('text_send_subject');
				$ema->body = $msg;
				
				/* Recupera dados */
				global $email,$email_from,$email_nome,$email_pass,$smtp,$metodo;
				
				$ema->metodo = $metodo;
				$ema->from  = $email_from;
				$ema->from_name = $email_nome;
				$ema->from_password = $email_pass;
				$ema->smtp_server = $smtp;
				$ema->enviar();
				echo 'Enviado';
				return(1);
			}
		function create_new_password()
			{
				$pass = date("isn");
				$sql = "update ".$this->tabela." set sa_senha = '".$pass."' where id_sa = ".$this->id_sa;
				$rlt = db_query($sql);	
				return($pass);
			}
		function updatex()
			{
			global $base;
			$dx1 = 'sa_codigo';
			$dx2 = 'sa';
			$dx3 = 7;
			$sql = "update ".$this->tabela." set ".$dx1." = lpad(id_".$dx2.",".$dx3.",0) where ".$dx1." = '' ";
			if ($base="pgsql")
				{ $sql = "update ".$this->tabela." set ".$dx1."=trim(to_char(id_".$dx2.",'".strzero(0,$dx3)."')) where (length(trim(".$dx1.")) < ".$dx3.") or (".$dx1." isnull);";}
			
			$rlt = db_query($sql);	
			return(1); 
			}
				
	}
?>
