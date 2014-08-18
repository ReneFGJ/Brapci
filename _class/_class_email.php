<?php
require('_class_smtp.php'); 
class email
	{
		var $to;
		var $to_name;
		var $cco;
		var $cc;
		var $subject;
		var $body;
		
		var $from;
		var $from_reply;
		var $from_name;
		var $from_password;
		var $smtp_server;
		
		var $metodo='html_02';
		var $link;
		
		var $debug=1;
		
		function enviar()
			{
				if (strlen($this->to) == 0)
					{ $rsp = '-2'; }
				else 
					{
					$rsp = -1;
					echo 'Enviando por '.$this->metodo.'<BR>';
					if ($this->metodo == 'html_01') { $rsp = $this->enviar_html_01(); }
					if ($this->metodo == 'html_02') { $rsp = $this->enviar_html_02(); }
					if ($this->metodo == 'html_03') { $rsp = $this->enviar_html_03(); }
					}
				return($rsp);
			}
			
		function validar($id)
			{
				$ok = 0;
				$pre = substr($id,0,strpos($id,'@'));
				$suf = substr($id,strpos($id,'@')+1,strlen($id));
				if (strpos($id,'@') > 0) { $ok=1; }
				if ($ok == 1)
					{
							if (strlen($pre) == 0) { $ok=0; }
							if (strlen($suf) < 5) { $ok=0; }
							if (substr($pre,0,1)=='.') { $ok=0; }
							if (substr($pre,strlen($pre),1)=='.') { $ok=0; }
							if (substr($suf,0,1)=='.') { $ok=0; }
							if (substr($suf,strlen($suf),1)=='.') { $ok=0; }
							if (strpos($suf.$pre,',') > 0 ) {$ok=0; }
					}
				return($ok);
				
			}
		/* Método nativo */
		function enviar_html_01()
			{
				echo 'Enviando método 1';
				/* para o envio em formato HTML */
				
				$subject = $this->subject; //subject
				$recipient = 'From: "'.$this->from_name.'" <'.$from.'>'.chr(13).chr(10); 
				
				/* Corpo do texto */
				$mail_body = $this->body;
				//$rsp = mail($recipient, $subject, $mail_body, $header);
				
				$to = $this->to;
				$subject = $this->subject;
				$message = $this->body;
				$headers = "MIME-Version: 1.0".chr(13).chr(10);
				$headers .= "Content-type: text/html; charset=UTF-8".chr(13).chr(10);
				/* endereço do remitente */
				$headers .= 'From: "'.$this->from_name.'" < '.$this->from_reply.' >'.chr(13).chr(10); 
				$headers .= 'To: "'.$this->to_name.'" <'.$this->to.'>'.chr(13).chr(10); 
				/*endereço de resposta, se queremos que seja diferente a do remitente */
				$headers .= 'Reply-To: "'.$this->from_name.'" < '.$this->from.' >' . "\r\n" .
    						'X-Mailer: PHP/' . phpversion();
				mail($to, $subject, $message, $headers);
				$rsp = 1;
				return($rsp);
			}
		/* Método com autenticação */
		function enviar_html_02()
			{
			if (strlen($this->smtp_server) > 0)
				{
					$smtp = new Smtp($this->smtp_server);
					$smtp->usuario_smtp = $this->from;
					$smtp->senha_smtp = $this->from_password; 
					$smtp->debug=true;
					$rsp = $smtp->Send($this->to, $this->from, $this->subject, $this->body);
				} else {
					echo 'Erro, servidor SMTP não configurado';
					$rsp = -1;
				}
				return($rsp);
			}
		function enviar_html_03()
			{
			$destinatarios = $this->to;
			$assunto = $this->subject;
			$mensagem= $this->body;
			$tela = '(tela)';
 
 			$usuario = $this->from;
 			$senha = $this->from_password;
 			$nomeDestinatario = $this->to_name;
 			$nomeRemetente = $this->from_name;
			// $destinatarios = 'monitoramento@sisdoc.com.br';
 			$tela .= '(tela)';

			$resposta .= '(resposta)';
 
 			$_POST['mensagem'] = nl2br($mensagem);
 			/***********************************A PARTIR DAQUI NAO ALTERAR************************************/
			foreach ($_POST as $dados['me1'] => $dados['me2'])
			{ $dados['me3'][] = '<b>'.$dados['me1'].'</b>: '.$dados['me2']; }

		//	$dados['me3'] = '<hr><h4>Mensagem do site</h4>'.implode('<br>', $dados['me3']).'<hr>';
			$dados['me3'] = $mensagem;
			$dados['email'] = array('usuario' => $usuario, 'senha' => $senha, 'servidor' => 'smtp.'.substr(strstr($usuario, '@'), 1), 'nomeRemetente' => $nomeRemetente, 'nomeDestinatario' => $nomeDestinatario, 'resposta' => $resposta, 'assunto' => $assunto, 'mensagem' => $dados['me3']);
	
			ini_set('php_flag mail_filter', 0);
			$conexao = fsockopen($this->smtp_server, 587, $errno, $errstr, 10);
			fgets($conexao, 512);
			$dados['destinatarios'] = explode(',', $destinatarios);
			foreach ($dados['destinatarios'] as $dados['1'])
			{
				$dados['destinatarios']['RCPTTO'][] = '< '.$dados['1'].' >';
				$dados['destinatarios']['TO'][] = $dados['1'];
			}
			$dados['cabecalho'] = array('EHLO ' => $this->smtp_server, 'AUTH LOGIN', base64_encode($usuario), base64_encode($senha), 'MAIL FROM: ' => '< '.$usuario.' >', 'RCPT TO:' => $dados['destinatarios']['RCPTTO'], 'DATA', 'MIME-Version: ' => '1.0', 'Content-Type: text/html; charset=iso-8859-1', 'Date: ' => date('r',time()), 'From: ' => array($dados['email']['nomeRemetente'].' ' => '< '.$dados['email']['usuario'].' >'), 'To:' => array($dados['email']['nomeDestinatario'].' ' => $dados['destinatarios']['TO']), 'Reply-To: ' => $dados['email']['resposta'],'Subject: ' => $dados['email']['assunto'], 'mensagem' => $dados['email']['mensagem'], 'QUIT');
			foreach ($dados['cabecalho'] as $dados['2'] => $dados['3'])
			{
				if (is_array($dados['3']))
				{ foreach ($dados['3'] as $dados['4'] => $dados['5']) {
					$dados['4'] = empty($dados['4']) ? '' : $dados['4'];
					$dados['5'] = empty($dados['5']) ? '' : $dados['5'];
					$dados['4'] = is_numeric($dados['4']) ? '' : $dados['4'];
					if (is_array($dados['5']))
						{ $dados['5'] = "< ".implode(', ', $dados['5'])." >"; }
					fwrite($conexao, $dados['2'].$dados['4'].$dados['5']."\r\n", 512).'<br>';
					fgets($conexao, 512);
				}
			} else {
			$dados['2'] = empty($dados['2']) ? '' : $dados['2'];
			$dados['3'] = empty($dados['3']) ? '' : $dados['3'];
			$dados['2'] = is_numeric($dados['2']) ? '' : $dados['2'];
			
			if ($dados['2'] == 'Subject: ')
			{
				fwrite($conexao, $dados['2'].$dados['3']."\r\n", 512).'<br>';
				fwrite($conexao, "\r\n", 512).'<br>';
				fgets($conexao, 512);
			} elseif ($dados['2'] == 'mensagem') 
				{
					fwrite($conexao, $dados['3']."\r\n.\r\n").'<br>';
					fgets($conexao);
				} else {
					fwrite($conexao, $dados['2'].$dados['3']."\r\n", 512).'<br>';
					fgets($conexao, 512);
				}
			}
		}
		fclose($conexao);
		return(1);
	}
			
	function email_antispan($idx,$camp)
				{
				$link = 'http://'.$_SERVER['SERVER_NAME'].'/wb/';
				$linkx = $link.'mailer_remover.php?dd0='.$idx;
				$linky = $link.'mailer_leu.php?dd0='.$idx.'&dd1='.$camp;
				$sx .= '<BR><BR><font size=-1>';
				$sx .= 'Nós respeitamos a privacidade de sua conta de correio eletronico e é contra o spam. ';
				$sx .= 'Mantendo sigilo de seu cadastro a terceiros faz parte de nossa politica de privacidade. ';
				$sx .= 'Você optou por receber este e-mail, e por esta recebendo nossas novidades. ';
				$sx .= 'Mas se deseja não mais receber nossos e-mails, ';
				$sx .= '<A href="'.$linkx.'" target="new">';
				$sx .= 'clique aqui.</A></font>';
				$sx .= '<BR><BR>';
				$sx .= 'Não responda este e-mail, ele é enviando automaticamente ';
				$sx .= '<BR><BR>';
				$sx .= '<img src="'.$linky.'"><BR>';
				return($sx);
			}
	}
